import '../models/user_model.dart';
import 'api_service.dart';

class AuthService {
  final ApiService _api = ApiService();

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
  }
}
