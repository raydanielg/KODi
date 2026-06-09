import 'dart:convert';
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
    print('📡 GET Request: $_baseUrl/$endpoint');
    final response = await http.get(
      Uri.parse('$_baseUrl/$endpoint'),
      headers: _headers,
    );
    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> post(String endpoint, {Map<String, dynamic>? body}) async {
    print('📡 POST Request: $_baseUrl/$endpoint');
    print('📡 POST Body: ${body != null ? jsonEncode(body) : "null"}');
    final response = await http.post(
      Uri.parse('$_baseUrl/$endpoint'),
      headers: _headers,
      body: body != null ? jsonEncode(body) : null,
    );
    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> put(String endpoint, {Map<String, dynamic>? body}) async {
    final response = await http.put(
      Uri.parse('$_baseUrl/$endpoint'),
      headers: _headers,
      body: body != null ? jsonEncode(body) : null,
    );
    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> delete(String endpoint) async {
    final response = await http.delete(
      Uri.parse('$_baseUrl/$endpoint'),
      headers: _headers,
    );
    return _handleResponse(response);
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
