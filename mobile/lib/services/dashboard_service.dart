import '../models/dashboard_stats_model.dart';
import 'api_service.dart';

class DashboardService {
  final ApiService _api = ApiService();

  Future<DashboardStatsModel> fetchDashboardStats() async {
    final response = await _api.get('dashboard');
    return DashboardStatsModel.fromJson(response['data']);
  }
}
