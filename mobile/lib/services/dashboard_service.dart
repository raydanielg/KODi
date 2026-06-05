import 'api_service.dart';

class DashboardService {
  final ApiService _api;

  DashboardService(this._api);

  Future<Map<String, dynamic>> getDashboard() async {
    return await _api.get('/dashboard');
  }

  Future<Map<String, dynamic>> getPayments() async {
    return await _api.get('/payments');
  }

  Future<Map<String, dynamic>> getPaymentHistory() async {
    return await _api.get('/payments/history');
  }

  Future<Map<String, dynamic>> makePayment(Map<String, dynamic> data) async {
    return await _api.post('/payments/make', body: data);
  }

  Future<Map<String, dynamic>> getLeases() async {
    return await _api.get('/leases');
  }

  Future<Map<String, dynamic>> getMaintenance() async {
    return await _api.get('/maintenance');
  }

  Future<Map<String, dynamic>> createMaintenance(Map<String, dynamic> data) async {
    return await _api.post('/maintenance', body: data);
  }

  Future<Map<String, dynamic>> getMessages() async {
    return await _api.get('/messages');
  }

  Future<Map<String, dynamic>> sendMessage(Map<String, dynamic> data) async {
    return await _api.post('/messages', body: data);
  }

  Future<Map<String, dynamic>> getApplications() async {
    return await _api.get('/applications');
  }

  Future<Map<String, dynamic>> getUsers() async {
    return await _api.get('/users');
  }
}
