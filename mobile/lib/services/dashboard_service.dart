import '../models/dashboard_stats_model.dart';
import 'api_service.dart';

class DashboardService {
  final ApiService _api = ApiService();

  Future<DashboardStatsModel> fetchDashboardStats() async {
    print('📊 Fetching Dashboard Stats...');
    try {
      final response = await _api.get('dashboard');
      print('✅ Dashboard Response: $response');
      return DashboardStatsModel.fromJson(response['data']);
    } catch (e) {
      print('❌ Dashboard Error: $e');
      rethrow;
    }
  }
}
