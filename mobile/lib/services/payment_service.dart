import 'package:manna/services/api_service.dart';
import 'package:manna/services/auth_service.dart';

class PaymentService {
  final ApiService _apiService = ApiService();
  final AuthService _authService = AuthService();

  // Fetch payments
  Future<List<Map<String, dynamic>>> fetchPayments() async {
    try {
      final response = await _apiService.get('payments');
      if (response['success'] == true) {
        return List<Map<String, dynamic>>.from(response['data'] ?? []);
      }
      return [];
    } catch (e) {
      print('Error fetching payments: $e');
      return [];
    }
  }

  // Submit payment request
  Future<Map<String, dynamic>> submitPaymentRequest({
    required String amount,
    required String paymentMethod,
    required String transactionId,
    String? proofImage,
  }) async {
    try {
      final user = _authService.currentUser;
      final response = await _apiService.post(
        'payments/make',
        body: {
          'user_id': user?.id,
          'amount': amount,
          'payment_method': paymentMethod,
          'transaction_id': transactionId,
          'proof_image': proofImage,
          'status': 'pending',
        },
      );
      return response;
    } catch (e) {
      print('Error submitting payment request: $e');
      return {'success': false, 'message': 'Failed to submit payment request'};
    }
  }

  // Get payment history
  Future<List<Map<String, dynamic>>> getPaymentHistory() async {
    try {
      final response = await _apiService.get('payments/history');
      if (response['success'] == true) {
        return List<Map<String, dynamic>>.from(response['data'] ?? []);
      }
      return [];
    } catch (e) {
      print('Error fetching payment history: $e');
      return [];
    }
  }

  // Update payment status (for landlord/admin)
  Future<Map<String, dynamic>> updatePaymentStatus(
    String paymentId,
    String status,
  ) async {
    try {
      final response = await _apiService.put(
        'payments/$paymentId/status',
        body: {
          'status': status,
        },
      );
      return response;
    } catch (e) {
      print('Error updating payment status: $e');
      return {'success': false, 'message': 'Failed to update payment status'};
    }
  }
}
