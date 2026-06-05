import 'package:flutter/foundation.dart';
import '../models/user_model.dart';
import 'api_service.dart';

class AuthService extends ChangeNotifier {
  final ApiService _api;
  UserModel? _currentUser;
  String? _token;
  bool _isLoading = false;

  AuthService(this._api);

  UserModel? get currentUser => _currentUser;
  String? get token => _token;
  bool get isLoading => _isLoading;
  bool get isAuthenticated => _currentUser != null && _token != null;
  String? get userRole => _currentUser?.role;

  Future<Map<String, dynamic>> login(String email, String password) async {
    _isLoading = true;
    notifyListeners();

    try {
      final result = await _api.post('/auth/login', body: {
        'email': email,
        'password': password,
      });

      if (result['success'] == true) {
        _token = result['data']['token'];
        _currentUser = UserModel.fromJson(result['data']['user']);
        _api.setToken(_token);
      }

      _isLoading = false;
      notifyListeners();
      return result;
    } catch (e) {
      _isLoading = false;
      notifyListeners();
      return {'success': false, 'message': 'Login failed: $e'};
    }
  }

  Future<Map<String, dynamic>> register(String name, String email, String phone, String password, String passwordConfirmation, String role) async {
    _isLoading = true;
    notifyListeners();

    try {
      final result = await _api.post('/auth/register', body: {
        'name': name,
        'email': email,
        'phone': phone,
        'password': password,
        'password_confirmation': passwordConfirmation,
        'role': role,
      });

      if (result['success'] == true) {
        _token = result['data']['token'];
        _currentUser = UserModel.fromJson(result['data']['user']);
        _api.setToken(_token);
      }

      _isLoading = false;
      notifyListeners();
      return result;
    } catch (e) {
      _isLoading = false;
      notifyListeners();
      return {'success': false, 'message': 'Registration failed: $e'};
    }
  }

  Future<void> logout() async {
    await _api.post('/auth/logout');
    _token = null;
    _currentUser = null;
    _api.setToken(null);
    notifyListeners();
  }

  Future<Map<String, dynamic>> fetchUser() async {
    final result = await _api.get('/auth/user');
    if (result['success'] == true) {
      _currentUser = UserModel.fromJson(result['data']);
      notifyListeners();
    }
    return result;
  }

  Future<Map<String, dynamic>> updateProfile(Map<String, dynamic> data) async {
    final result = await _api.post('/auth/profile', body: data);
    if (result['success'] == true) {
      _currentUser = UserModel.fromJson(result['data']);
      notifyListeners();
    }
    return result;
  }
}
