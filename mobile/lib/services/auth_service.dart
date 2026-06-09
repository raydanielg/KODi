import '../models/user_model.dart';
import 'api_service.dart';

class AuthService {
  final ApiService _api = ApiService();

  UserModel? get currentUser => _api.currentUser;

  Future<Map<String, dynamic>> login(String email, String password) async {
    final response = await _api.post('auth/login', body: {
      'email': email,
      'password': password,
    });
    final user = UserModel.fromJson(response['data']['user']);
    final token = response['data']['token'] as String;
    _api.setAuth(token, user);
    return {'user': user, 'token': token};
  }

  Future<Map<String, dynamic>> register({
    required String name,
    required String email,
    required String phone,
    required String password,
    required String passwordConfirmation,
    required String role,
  }) async {
    final response = await _api.post('auth/register', body: {
      'name': name,
      'email': email,
      'phone': phone,
      'password': password,
      'password_confirmation': passwordConfirmation,
      'role': role,
    });
    final user = UserModel.fromJson(response['data']['user']);
    final token = response['data']['token'] as String;
    _api.setAuth(token, user);
    return {'user': user, 'token': token};
  }

  Future<void> logout() async {
    try {
      await _api.post('auth/logout');
    } catch (_) {}
    _api.clearAuth();
  }
}
