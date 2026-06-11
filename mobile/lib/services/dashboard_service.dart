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
      
      // For landlord, the response structure is different
      // Landlord API returns: {"success":true,"data":{"stats":{...},"my_properties":[...]}}
      // We need to pass the entire 'data' object to the model
      return DashboardStatsModel.fromJson(response['data']);
    } catch (e) {
      print('❌ Dashboard Error: $e');
      rethrow;
    }
  }
}
