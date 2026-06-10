import 'package:shared_preferences/shared_preferences.dart';
import 'dart:convert';
import '../models/user_model.dart';
import 'api_service.dart';

class AuthService {
  final ApiService _api = ApiService();
  static const String _tokenKey = 'auth_token';
  static const String _userKey = 'auth_user';

  UserModel? get currentUser => _api.currentUser;

  Future<Map<String, dynamic>> login(String email, String password) async {
    print('🔐 Login Attempt: $email');
    try {
      final response = await _api.post('auth/login', body: {
        'email': email,
        'password': password,
      });
      print('✅ Login Response: $response');
      final user = UserModel.fromJson(response['data']['user']);
      final token = response['data']['token'] as String;
      _api.setAuth(token, user);
      await _saveAuthData(token, user);
      return {'user': user, 'token': token};
    } catch (e) {
      print('❌ Login Error: $e');
      rethrow;
    }
  }

  Future<Map<String, dynamic>> register({
    required String name,
    required String email,
    required String phone,
    required String password,
    required String passwordConfirmation,
    required String role,
  }) async {
    print('📝 Register Attempt: $email as $role');
    try {
      final response = await _api.post('auth/register', body: {
        'name': name,
        'email': email,
        'phone': phone,
        'password': password,
        'password_confirmation': passwordConfirmation,
        'role': role,
      });
      print('✅ Register Response: $response');
      final user = UserModel.fromJson(response['data']['user']);
      final token = response['data']['token'] as String;
      _api.setAuth(token, user);
      await _saveAuthData(token, user);
      return {'user': user, 'token': token};
    } catch (e) {
      print('❌ Register Error: $e');
      rethrow;
    }
  }

  Future<void> logout() async {
    try {
      await _api.post('auth/logout');
    } catch (_) {}
    _api.clearAuth();
    await _clearAuthData();
  }

  void updateUser(UserModel updatedUser) {
    _api.setAuth(_api.token ?? '', updatedUser);
    _saveAuthData(_api.token ?? '', updatedUser);
  }

  Future<bool> isLoggedIn() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString(_tokenKey);
    return token != null && token.isNotEmpty;
  }

  Future<void> loadSavedAuth() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString(_tokenKey);
      final userJson = prefs.getString(_userKey);

      if (token != null && token.isNotEmpty && userJson != null) {
        final userMap = jsonDecode(userJson) as Map<String, dynamic>;
        final user = UserModel.fromJson(userMap);
        _api.setAuth(token, user);
        print('✅ Auto-login successful for user: ${user.email}');
      }
    } catch (e) {
      print('❌ Failed to load saved auth: $e');
      await _clearAuthData();
    }
  }

  Future<void> _saveAuthData(String token, UserModel user) async {
    try {
      final prefs = await SharedPreferences.getInstance();
      await prefs.setString(_tokenKey, token);
      await prefs.setString(_userKey, jsonEncode(user.toJson()));
      print('✅ Auth data saved successfully');
    } catch (e) {
      print('❌ Failed to save auth data: $e');
    }
  }

  Future<void> _clearAuthData() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      await prefs.remove(_tokenKey);
      await prefs.remove(_userKey);
      print('✅ Auth data cleared successfully');
    } catch (e) {
      print('❌ Failed to clear auth data: $e');
    }
  }
}
