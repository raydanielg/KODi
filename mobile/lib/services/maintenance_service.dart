import 'package:manna/services/api_service.dart';
import 'package:manna/services/auth_service.dart';

class MaintenanceService {
  final ApiService _apiService = ApiService();
  final AuthService _authService = AuthService();

  // Fetch maintenance requests
  Future<List<Map<String, dynamic>>> fetchMaintenanceRequests() async {
    try {
      final response = await _apiService.get('/maintenance-requests');
      if (response['success'] == true) {
        return List<Map<String, dynamic>>.from(response['data'] ?? []);
      }
      return [];
    } catch (e) {
      print('Error fetching maintenance requests: $e');
      return [];
    }
  }

  // Submit new maintenance request
  Future<Map<String, dynamic>> submitMaintenanceRequest({
    required String category,
    required String title,
    required String description,
  }) async {
    try {
      final user = _authService.currentUser;
      final response = await _apiService.post(
        '/maintenance-requests',
        body: {
          'user_id': user?.id,
          'category': category,
          'title': title,
          'description': description,
          'status': 'pending',
        },
      );
      return response;
    } catch (e) {
      print('Error submitting maintenance request: $e');
      return {'success': false, 'message': 'Failed to submit request'};
    }
  }

  // Get request history
  Future<List<Map<String, dynamic>>> getRequestHistory() async {
    try {
      final response = await _apiService.get('/maintenance-requests/history');
      if (response['success'] == true) {
        return List<Map<String, dynamic>>.from(response['data'] ?? []);
      }
      return [];
    } catch (e) {
      print('Error fetching request history: $e');
      return [];
    }
  }

  // Update request status
  Future<Map<String, dynamic>> updateRequestStatus(
    String requestId,
    String status,
  ) async {
    try {
      final response = await _apiService.put(
        '/maintenance-requests/$requestId',
        body: {
          'status': status,
        },
      );
      return response;
    } catch (e) {
      print('Error updating request status: $e');
      return {'success': false, 'message': 'Failed to update status'};
    }
  }
}
