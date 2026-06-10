import 'api_service.dart';

class SmsService {
  final ApiService _api = ApiService();

  /// Send SMS to tenants who have paid this month
  Future<Map<String, dynamic>> sendToPaidTenants({
    required String message,
    String? month,
  }) async {
    return await _api.post('landlord/sms/paid-tenants', body: {
      'message': message,
      if (month != null) 'month': month,
    });
  }

  /// Send SMS to tenants who have NOT paid this month
  Future<Map<String, dynamic>> sendToUnpaidTenants({
    required String message,
    String? month,
  }) async {
    return await _api.post('landlord/sms/unpaid-tenants', body: {
      'message': message,
      if (month != null) 'month': month,
    });
  }

  /// Send utility bill SMS (electricity or water) to a specific tenant
  Future<Map<String, dynamic>> sendUtilityBill({
    required int tenantId,
    required String utilityType, // 'electricity' | 'water'
    required double amount,
    required String period,
    String? customMessage,
  }) async {
    return await _api.post('landlord/sms/utility-bill', body: {
      'tenant_id': tenantId,
      'utility_type': utilityType,
      'amount': amount,
      'period': period,
      if (customMessage != null) 'custom_message': customMessage,
    });
  }

  /// Send bulk utility bills to all tenants of a property
  Future<Map<String, dynamic>> sendBulkUtilityBills({
    required int propertyId,
    required String utilityType,
    required String period,
    required List<Map<String, dynamic>> bills, // [{tenant_id, amount}]
  }) async {
    return await _api.post('landlord/sms/bulk-utility', body: {
      'property_id': propertyId,
      'utility_type': utilityType,
      'period': period,
      'bills': bills,
    });
  }

  /// Send custom SMS to specific tenant
  Future<Map<String, dynamic>> sendToTenant({
    required int tenantId,
    required String message,
  }) async {
    return await _api.post('landlord/sms/send', body: {
      'tenant_id': tenantId,
      'message': message,
    });
  }

  /// Get SMS history
  Future<Map<String, dynamic>> getSmsHistory({int page = 1}) async {
    return await _api.get('landlord/sms/history', params: {'page': '$page'});
  }
}
