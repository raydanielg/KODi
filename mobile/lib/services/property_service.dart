import '../models/property_model.dart';
import 'api_service.dart';

class PropertyService {
  final ApiService _api;

  PropertyService(this._api);

  Future<Map<String, dynamic>> getProperties({int? page, String? city, String? type, int? minPrice, int? maxPrice, int? bedrooms}) async {
    final params = <String, String>{};
    if (page != null) params['page'] = page.toString();
    if (city != null) params['city'] = city;
    if (type != null) params['type'] = type;
    if (minPrice != null) params['min_price'] = minPrice.toString();
    if (maxPrice != null) params['max_price'] = maxPrice.toString();
    if (bedrooms != null) params['bedrooms'] = bedrooms.toString();

    final result = await _api.get('/properties', params: params, timeoutSeconds: 30);

    if (result['success'] == true && result['data'] != null) {
      final List<dynamic> data = result['data'] is List ? result['data'] : result['data']['data'] ?? [];
      final properties = data.map((json) => PropertyModel.fromJson(json)).toList();
      return {'success': true, 'properties': properties, 'meta': result['meta'] ?? {}};
    }
    return {'success': false, 'message': result['message'] ?? 'Failed to load properties', 'properties': <PropertyModel>[]};
  }

  Future<Map<String, dynamic>> getProperty(int id) async {
    final result = await _api.get('/properties/$id');
    if (result['success'] == true) {
      return {'success': true, 'property': PropertyModel.fromJson(result['data'])};
    }
    return {'success': false, 'message': result['message'] ?? 'Property not found'};
  }

  Future<Map<String, dynamic>> getFeatured() async {
    final result = await _api.get('/properties/featured');
    if (result['success'] == true) {
      final List<dynamic> data = result['data'] is List ? result['data'] : [];
      final properties = data.map((json) => PropertyModel.fromJson(json)).toList();
      return {'success': true, 'properties': properties};
    }
    return {'success': false, 'message': 'Failed to load featured', 'properties': <PropertyModel>[]};
  }

  Future<Map<String, dynamic>> search(String query, {String? city, String? type}) async {
    final params = <String, String>{'q': query};
    if (city != null) params['city'] = city;
    if (type != null) params['type'] = type;
    final result = await _api.get('/properties/search', params: params);
    if (result['success'] == true) {
      final List<dynamic> data = result['data'] is List ? result['data'] : result['data']['data'] ?? [];
      final properties = data.map((json) => PropertyModel.fromJson(json)).toList();
      return {'success': true, 'properties': properties};
    }
    return {'success': false, 'message': 'Search failed', 'properties': <PropertyModel>[]};
  }

  Future<Map<String, dynamic>> toggleFavorite(int propertyId) async {
    return await _api.post('/properties/$propertyId/favorite');
  }

  Future<Map<String, dynamic>> getFavorites() async {
    final result = await _api.get('/favorites');
    if (result['success'] == true) {
      final List<dynamic> data = result['data'] is List ? result['data'] : [];
      final properties = data.map((json) => PropertyModel.fromJson(json)).toList();
      return {'success': true, 'properties': properties};
    }
    return {'success': false, 'message': 'Failed to load favorites', 'properties': <PropertyModel>[]};
  }

  Future<Map<String, dynamic>> apply(int propertyId, {String? message, double? offer}) async {
    return await _api.post('/properties/$propertyId/apply', body: {
      if (message != null) 'message': message,
      if (offer != null) 'monthly_offer': offer,
    });
  }
}
