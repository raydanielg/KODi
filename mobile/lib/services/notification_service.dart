import 'dart:convert';
import 'package:http/http.dart' as http;
import '../constants/app_strings.dart';
import 'api_service.dart';

class NotificationService {
  final ApiService _apiService = ApiService();

  Future<List<Map<String, dynamic>>> fetchNotifications({int limit = 10}) async {
    try {
      final response = await _apiService.get('/notifications?limit=$limit');
      
      if (response['success'] == true) {
        final List<dynamic> data = response['data'] ?? [];
        return data.map((item) => item as Map<String, dynamic>).toList();
      }
      return [];
    } catch (e) {
      throw Exception('Failed to fetch notifications: $e');
    }
  }

  Future<int> fetchUnreadCount() async {
    try {
      final response = await _apiService.get('/notifications/unread-count');
      
      if (response['success'] == true) {
        return response['data']['unread_count'] ?? 0;
      }
      return 0;
    } catch (e) {
      throw Exception('Failed to fetch unread count: $e');
    }
  }

  Future<void> markAsRead(String notificationId) async {
    try {
      await _apiService.put('/notifications/$notificationId/read', {});
    } catch (e) {
      throw Exception('Failed to mark notification as read: $e');
    }
  }

  Future<void> markAllAsRead() async {
    try {
      await _apiService.post('/notifications/mark-all-read', {});
    } catch (e) {
      throw Exception('Failed to mark all notifications as read: $e');
    }
  }
}
