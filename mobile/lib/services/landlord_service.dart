import 'api_service.dart';

class LandlordService {
  final ApiService _api = ApiService();

  // ─── Dashboard ───────────────────────────────────────────────
  Future<Map<String, dynamic>> getDashboard() async {
    return await _api.get('landlord/dashboard');
  }

  // ─── Properties ──────────────────────────────────────────────
  Future<Map<String, dynamic>> getProperties({int page = 1}) async {
    return await _api.get('landlord/properties', params: {'page': '$page'});
  }

  Future<Map<String, dynamic>> createProperty(Map<String, dynamic> data) async {
    return await _api.post('landlord/properties', body: data);
  }

  Future<Map<String, dynamic>> updateProperty(int id, Map<String, dynamic> data) async {
    return await _api.put('landlord/properties/$id', body: data);
  }

  Future<Map<String, dynamic>> deleteProperty(int id) async {
    return await _api.delete('landlord/properties/$id');
  }

  // ─── Tenants ─────────────────────────────────────────────────
  Future<Map<String, dynamic>> getTenants({String? search}) async {
    final params = <String, String>{};
    if (search != null && search.isNotEmpty) params['search'] = search;
    return await _api.get('landlord/tenants', params: params);
  }

  Future<Map<String, dynamic>> createTenant(Map<String, dynamic> data) async {
    return await _api.post('landlord/tenants', body: data);
  }

  Future<Map<String, dynamic>> getTenantDetail(int id) async {
    return await _api.get('landlord/tenants/$id');
  }

  // ─── Leases ──────────────────────────────────────────────────
  Future<Map<String, dynamic>> getLeases({String? status}) async {
    final params = <String, String>{};
    if (status != null) params['status'] = status;
    return await _api.get('landlord/leases', params: params);
  }

  Future<Map<String, dynamic>> createLease(Map<String, dynamic> data) async {
    return await _api.post('landlord/leases', body: data);
  }

  Future<Map<String, dynamic>> terminateLease(int id, String reason) async {
    return await _api.post('landlord/leases/$id/terminate', body: {'reason': reason});
  }

  // ─── Payments ────────────────────────────────────────────────
  Future<Map<String, dynamic>> getRentPayments({String? status, String? month}) async {
    final params = <String, String>{};
    if (status != null) params['status'] = status;
    if (month != null) params['month'] = month;
    return await _api.get('landlord/payments', params: params);
  }

  Future<Map<String, dynamic>> recordPayment(Map<String, dynamic> data) async {
    return await _api.post('landlord/payments/record', body: data);
  }

  // ─── Utilities ───────────────────────────────────────────────
  Future<Map<String, dynamic>> getUtilityBills({int? propertyId}) async {
    final params = <String, String>{};
    if (propertyId != null) params['property_id'] = '$propertyId';
    return await _api.get('landlord/utilities', params: params);
  }

  Future<Map<String, dynamic>> recordUtilityReading(Map<String, dynamic> data) async {
    return await _api.post('landlord/utilities/reading', body: data);
  }

  // ─── Reports ─────────────────────────────────────────────────
  Future<Map<String, dynamic>> getRevenueReport({String? year}) async {
    final params = <String, String>{};
    if (year != null) params['year'] = year;
    return await _api.get('landlord/reports/revenue', params: params);
  }

  Future<Map<String, dynamic>> getOccupancyReport() async {
    return await _api.get('landlord/reports/occupancy');
  }
}
