import '../models/dashboard_stats_model.dart';
import 'api_service.dart';

class DashboardService {
  final ApiService _api = ApiService();

  Future<DashboardStatsModel> fetchDashboardStats({String role = 'tenant'}) async {
    print('📊 Fetching Dashboard Stats for role: $role...');
    try {
      final endpoint = role == 'landlord' ? 'landlord/dashboard' : 'dashboard';
      final response = await _api.get(endpoint);
      print('✅ Dashboard Response: $response');
      return DashboardStatsModel.fromJson(response['data']);
    } catch (e) {
      print('❌ Dashboard Error: $e');
      rethrow;
    }
  }
}
