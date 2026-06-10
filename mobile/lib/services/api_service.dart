import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import '../models/user_model.dart';
import '../constants/app_strings.dart';

class ApiService {
  static final ApiService _instance = ApiService._internal();
  factory ApiService() => _instance;
  ApiService._internal();

  String _baseUrl = AppStrings.apiBaseUrl;
  String? _token;
  UserModel? _currentUser;

  String get baseUrl => _baseUrl;
  String? get token => _token;
  UserModel? get currentUser => _currentUser;

  void configure({required String baseUrl}) {
    _baseUrl = baseUrl;
  }

  Map<String, String> get _headers {
    final headers = <String, String>{
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    };
    if (_token != null) {
      headers['Authorization'] = 'Bearer $_token';
    }
    return headers;
  }

  Future<Map<String, dynamic>> get(String endpoint) async {
    final cleanEndpoint = endpoint.startsWith('/') ? endpoint.substring(1) : endpoint;
    print('📡 GET Request: $_baseUrl/$cleanEndpoint');
    try {
      final response = await http.get(
        Uri.parse('$_baseUrl/$cleanEndpoint'),
        headers: _headers,
      ).timeout(
        const Duration(seconds: 10),
        onTimeout: () {
          throw Exception('Request timed out. Please check your internet connection.');
        },
      );
      return _handleResponse(response);
    } catch (e) {
      throw ApiException(_handleException(e), 0);
    }
  }

  Future<Map<String, dynamic>> post(String endpoint, {Map<String, dynamic>? body}) async {
    final cleanEndpoint = endpoint.startsWith('/') ? endpoint.substring(1) : endpoint;
    final url = '$_baseUrl/$cleanEndpoint';
    print('📡 POST Request: $url');
    print('📡 POST Body: ${body != null ? jsonEncode(body) : "null"}');
    print('📡 Headers: $_headers');
    
    try {
      print('🌐 Starting HTTP POST request...');
      final uri = Uri.parse(url);
      print('🌐 Parsed URI: $uri');
      
      final response = await http.post(
        uri,
        headers: _headers,
        body: body != null ? jsonEncode(body) : null,
      ).timeout(
        const Duration(seconds: 10),
        onTimeout: () {
          print('⏰ Request timed out after 10 seconds');
          throw Exception('Request timed out. Please check your internet connection.');
        },
      );
      
      print('✅ HTTP Response received: ${response.statusCode}');
      return _handleResponse(response);
    } catch (e) {
      print('❌ POST Request failed: $e');
      print('❌ Error type: ${e.runtimeType}');
      throw ApiException(_handleException(e), 0);
    }
  }

  Future<Map<String, dynamic>> put(String endpoint, {Map<String, dynamic>? body}) async {
    final cleanEndpoint = endpoint.startsWith('/') ? endpoint.substring(1) : endpoint;
    print('📡 PUT Request: $_baseUrl/$cleanEndpoint');
    print('📡 PUT Body: ${body != null ? jsonEncode(body) : "null"}');
    try {
      final response = await http.put(
        Uri.parse('$_baseUrl/$cleanEndpoint'),
        headers: _headers,
        body: body != null ? jsonEncode(body) : null,
      ).timeout(
        const Duration(seconds: 10),
        onTimeout: () {
          throw Exception('Request timed out. Please check your internet connection.');
        },
      );
      return _handleResponse(response);
    } catch (e) {
      throw ApiException(_handleException(e), 0);
    }
  }

  Future<Map<String, dynamic>> delete(String endpoint) async {
    final cleanEndpoint = endpoint.startsWith('/') ? endpoint.substring(1) : endpoint;
    try {
      final response = await http.delete(
        Uri.parse('$_baseUrl/$cleanEndpoint'),
        headers: _headers,
      ).timeout(
        const Duration(seconds: 10),
        onTimeout: () {
          throw Exception('Request timed out. Please check your internet connection.');
        },
      );
      return _handleResponse(response);
    } catch (e) {
      throw ApiException(_handleException(e), 0);
    }
  }

  Future<Map<String, dynamic>> uploadFile(String endpoint, File file, {Map<String, dynamic>? fields}) async {
    print('📡 UPLOAD Request: $_baseUrl/$endpoint');
    
    try {
      final request = http.MultipartRequest('POST', Uri.parse('$_baseUrl/$endpoint'));
      
      // Add headers without Content-Type (let http set it with boundary)
      final headers = <String, String>{
        'Accept': 'application/json',
      };
      if (_token != null) {
        headers['Authorization'] = 'Bearer $_token';
      }
      request.headers.addAll(headers);
      
      // Add file
      final fileStream = http.ByteStream(file.openRead());
      final fileLength = await file.length();
      final multipartFile = http.MultipartFile('avatar', fileStream, fileLength, filename: file.path.split('/').last);
      request.files.add(multipartFile);
      
      // Add additional fields
      if (fields != null) {
        fields.forEach((key, value) {
          request.fields[key] = value.toString();
        });
      }
      
      final response = await request.send().timeout(
        const Duration(seconds: 60),
        onTimeout: () {
          throw Exception('Upload timed out. Please check your internet connection.');
        },
      );
      final responseBody = await response.stream.bytesToString();
      
      print('🔍 API Response Status: ${response.statusCode}');
      print('🔍 API Response Body: $responseBody');
      
      try {
        final body = jsonDecode(responseBody) as Map<String, dynamic>;
        if (response.statusCode >= 200 && response.statusCode < 300) {
          return body;
        }
        
        if (body.containsKey('errors')) {
          final errors = body['errors'] as Map<String, dynamic>;
          final errorMessages = <String>[];
          errors.forEach((key, value) {
            if (value is List) {
              errorMessages.addAll(value.map((e) => e.toString()));
            } else {
              errorMessages.add(value.toString());
            }
          });
          throw ApiException(errorMessages.join('\n'), response.statusCode);
        }
        
        throw ApiException(body['message'] ?? 'Something went wrong', response.statusCode);
      } catch (e) {
        if (e is ApiException) rethrow;
        print('❌ JSON Parse Error: $e');
        print('❌ Response Body: $responseBody');
        throw ApiException('Failed to parse server response: ${e.toString()}', response.statusCode);
      }
    } catch (e) {
      if (e is ApiException) rethrow;
      throw ApiException(_handleException(e), 0);
    }
  }

  Map<String, dynamic> _handleResponse(http.Response response) {
    print('🔍 API Response Status: ${response.statusCode}');
    print('🔍 API Response Body: ${response.body}');
    
    try {
      final body = jsonDecode(response.body) as Map<String, dynamic>;
      if (response.statusCode >= 200 && response.statusCode < 300) {
        return body;
      }
      
      // If there are validation errors (e.g., status 422), parse them nicely
      if (body.containsKey('errors')) {
        final errors = body['errors'] as Map<String, dynamic>;
        final errorMessages = <String>[];
        errors.forEach((key, value) {
          if (value is List) {
            errorMessages.addAll(value.map((e) => e.toString()));
          } else {
            errorMessages.add(value.toString());
          }
        });
        throw ApiException(errorMessages.join('\n'), response.statusCode);
      }
      
      throw ApiException(body['message'] ?? 'Something went wrong', response.statusCode);
    } catch (e) {
      if (e is ApiException) rethrow;
      print('❌ JSON Parse Error: $e');
      print('❌ Response Body: ${response.body}');
      throw ApiException('Failed to parse server response: ${e.toString()}', response.statusCode);
    }
  }

  String _handleException(dynamic error) {
    print('❌ Exception: $error');
    
    if (error is ApiException) {
      return error.message;
    }
    
    final errorString = error.toString().toLowerCase();
    
    // Socket exception - no internet or DNS failure
    if (errorString.contains('socketexception') || 
        errorString.contains('failed host lookup') ||
        errorString.contains('no address associated') ||
        errorString.contains('connection refused')) {
      return 'Tafadhali angalia mtandao wako. Hakuna muunganisho wa internet.';
    }
    
    // Timeout
    if (errorString.contains('timeout') || errorString.contains('timed out')) {
      return 'Muunganisho umechukua muda mrefu. Tafadhali jaribu tena.';
    }
    
    // Client exception
    if (errorString.contains('clientexception') || errorString.contains('http')) {
      return 'Imeshindikana kuunganisha na server. Tafadhali jaribu tena.';
    }
    
    // Format exception
    if (errorString.contains('formatexception')) {
      return 'Imeshindikana kusoma data kutoka server.';
    }
    
    // Generic error
    return 'Imeshindikana kumaliza shughuli. Tafadhali jaribu tena.';
  }

  void setAuth(String token, UserModel user) {
    _token = token;
    _currentUser = user;
  }

  void clearAuth() {
    _token = null;
    _currentUser = null;
  }
}

class ApiException implements Exception {
  final String message;
  final int statusCode;
  ApiException(this.message, this.statusCode);
  @override
  String toString() => message;
}
