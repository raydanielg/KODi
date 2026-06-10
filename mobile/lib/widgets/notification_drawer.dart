import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../constants/app_colors.dart';
import '../services/notification_service.dart';
import '../utils/helpers.dart';

class NotificationDrawer extends StatefulWidget {
  final NotificationService notificationService;
  
  const NotificationDrawer({
    super.key,
    required this.notificationService,
  });

  @override
  State<NotificationDrawer> createState() => _NotificationDrawerState();
}

class _NotificationDrawerState extends State<NotificationDrawer> {
  List<Map<String, dynamic>> _notifications = [];
  bool _isLoading = true;
  int _unreadCount = 0;

  @override
  void initState() {
    super.initState();
    _loadNotifications();
  }

  Future<void> _loadNotifications() async {
    setState(() => _isLoading = true);
    try {
      final notifications = await widget.notificationService.fetchNotifications(limit: 20);
      final unreadCount = await widget.notificationService.fetchUnreadCount();
      setState(() {
        _notifications = notifications;
        _unreadCount = unreadCount;
        _isLoading = false;
      });
    } catch (e) {
      setState(() => _isLoading = false);
      if (mounted) {
        Helpers.showSnackBar(context, 'Imeshindikana kupata arifa. Tafadhali jaribu tena.');
      }
    }
  }

  Future<void> _markAsRead(String notificationId) async {
    try {
      await widget.notificationService.markAsRead(notificationId);
      setState(() {
        _notifications = _notifications.map((n) {
          if (n['id'] == notificationId) {
            n['is_new'] = false;
            n['read_at'] = DateTime.now().toIso8601String();
          }
          return n;
        }).toList();
        _unreadCount = _unreadCount > 0 ? _unreadCount - 1 : 0;
      });
    } catch (e) {
      if (mounted) {
        Helpers.showSnackBar(context, 'Imeshindikana kuweka arifa kama imesomwa.');
      }
    }
  }

  Future<void> _markAllAsRead() async {
    try {
      await widget.notificationService.markAllAsRead();
      setState(() {
        _notifications = _notifications.map((n) {
          n['is_new'] = false;
          n['read_at'] = DateTime.now().toIso8601String();
          return n;
        }).toList();
        _unreadCount = 0;
      });
      if (mounted) {
        Helpers.showSnackBar(context, 'Arifa zote zimewekwa kama zimesomwa.', isError: false);
      }
    } catch (e) {
      if (mounted) {
        Helpers.showSnackBar(context, 'Imeshindikana kuweka arifa zote kama zimesomwa.');
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Drawer(
      width: MediaQuery.of(context).size.width * 0.85,
      child: Container(
        color: Colors.white,
        child: Column(
          children: [
            // Header
            Container(
              padding: const EdgeInsets.only(left: 20, right: 20, top: 48, bottom: 20),
              decoration: const BoxDecoration(
                color: AppColors.primary,
                border: Border(
                  bottom: BorderSide(color: Color(0xffe5e7eb), width: 1),
                ),
              ),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Row(
                    children: [
                      const Icon(Icons.notifications_rounded, color: Colors.white, size: 24),
                      const SizedBox(width: 12),
                      Text(
                        'Arifa (Notifications)',
                        style: GoogleFonts.poppins(
                          fontSize: 18,
                          fontWeight: FontWeight.w700,
                          color: Colors.white,
                        ),
                      ),
                      if (_unreadCount > 0) ...[
                        const SizedBox(width: 8),
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                          decoration: BoxDecoration(
                            color: Colors.white,
                            borderRadius: BorderRadius.circular(12),
                          ),
                          child: Text(
                            '$_unreadCount',
                            style: GoogleFonts.poppins(
                              fontSize: 11,
                              fontWeight: FontWeight.w700,
                              color: AppColors.primary,
                            ),
                          ),
                        ),
                      ],
                    ],
                  ),
                  if (_unreadCount > 0)
                    TextButton(
                      onPressed: _markAllAsRead,
                      child: Text(
                        'Wote Zisome',
                        style: GoogleFonts.poppins(
                          fontSize: 12,
                          fontWeight: FontWeight.w600,
                          color: Colors.white,
                        ),
                      ),
                    ),
                ],
              ),
            ),
            // Notifications List
            Expanded(
              child: _isLoading
                  ? const Center(
                      child: CircularProgressIndicator(color: AppColors.primary),
                    )
                  : _notifications.isEmpty
                      ? Center(
                          child: Column(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Icon(
                                Icons.notifications_none_outlined,
                                size: 64,
                                color: Colors.grey[300],
                              ),
                              const SizedBox(height: 16),
                              Text(
                                'Hakuna arifa bado',
                                style: GoogleFonts.poppins(
                                  fontSize: 14,
                                  color: Colors.grey[500],
                                ),
                              ),
                            ],
                          ),
                        )
                      : ListView.builder(
                          padding: const EdgeInsets.symmetric(vertical: 8),
                          itemCount: _notifications.length,
                          itemBuilder: (context, index) {
                            final notification = _notifications[index];
                            return _buildNotificationItem(notification);
                          },
                        ),
            ),
            // View All Button
            Container(
              padding: const EdgeInsets.all(20),
              decoration: const BoxDecoration(
                border: Border(
                  top: BorderSide(color: Color(0xffe5e7eb), width: 1),
                ),
              ),
              child: SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: () {
                    Navigator.pop(context);
                    Navigator.pushNamed(context, '/notifications');
                  },
                  style: ElevatedButton.styleFrom(
                    backgroundColor: AppColors.primary,
                    foregroundColor: Colors.white,
                    padding: const EdgeInsets.symmetric(vertical: 14),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(10),
                    ),
                  ),
                  child: Text(
                    'Angalia Zote (View All)',
                    style: GoogleFonts.poppins(
                      fontWeight: FontWeight.w600,
                      fontSize: 14,
                    ),
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildNotificationItem(Map<String, dynamic> notification) {
    final isNew = notification['is_new'] ?? false;
    final data = notification['data'] as Map<String, dynamic>?;
    final title = data?['title'] ?? 'Arifa';
    final message = data?['message'] ?? 'Hakuna ujumbe';
    final createdAt = notification['created_at'];
    
    return InkWell(
      onTap: () {
        if (isNew) {
          _markAsRead(notification['id']);
        }
      },
      child: Container(
        margin: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: isNew ? const Color(0xfff0fdf4) : Colors.white,
          borderRadius: BorderRadius.circular(8),
          border: Border.all(
            color: isNew ? const Color(0xff10b981).withOpacity(0.3) : const Color(0xffe5e7eb),
            width: isNew ? 1 : 1,
          ),
        ),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Icon
            Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                color: isNew ? const Color(0xff10b981).withOpacity(0.1) : Colors.grey[100],
                borderRadius: BorderRadius.circular(8),
              ),
              child: Icon(
                _getNotificationIcon(notification['type']),
                color: isNew ? const Color(0xff10b981) : Colors.grey[600],
                size: 20,
              ),
            ),
            const SizedBox(width: 12),
            // Content
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Expanded(
                        child: Text(
                          title,
                          style: GoogleFonts.poppins(
                            fontSize: 13,
                            fontWeight: FontWeight.w700,
                            color: const Color(0xff111827),
                          ),
                        ),
                      ),
                      if (isNew)
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                          decoration: BoxDecoration(
                            color: const Color(0xff10b981),
                            borderRadius: BorderRadius.circular(4),
                          ),
                          child: Text(
                            'NEW',
                            style: GoogleFonts.poppins(
                              fontSize: 8,
                              fontWeight: FontWeight.w700,
                              color: Colors.white,
                            ),
                          ),
                        ),
                    ],
                  ),
                  const SizedBox(height: 4),
                  Text(
                    message,
                    style: GoogleFonts.poppins(
                      fontSize: 12,
                      color: Colors.grey[600],
                    ),
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),
                  const SizedBox(height: 4),
                  Text(
                    _formatDate(createdAt),
                    style: GoogleFonts.poppins(
                      fontSize: 10,
                      color: Colors.grey[400],
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  IconData _getNotificationIcon(String? type) {
    if (type == null) return Icons.notifications_none_outlined;
    
    switch (type.toLowerCase()) {
      case 'payment':
        return Icons.payments_rounded;
      case 'maintenance':
        return Icons.build_rounded;
      case 'application':
        return Icons.description_rounded;
      case 'lease':
        return Icons.home_work_rounded;
      case 'message':
        return Icons.message_rounded;
      default:
        return Icons.notifications_rounded;
    }
  }

  String _formatDate(String? dateString) {
    if (dateString == null) return '';
    try {
      final date = DateTime.parse(dateString);
      final now = DateTime.now();
      final difference = now.difference(date);
      
      if (difference.inMinutes < 1) {
        'Sasa hivi';
      } else if (difference.inMinutes < 60) {
        return '${difference.inMinutes} dakika iliyopita';
      } else if (difference.inHours < 24) {
        return '${difference.inHours} masaa iliyopita';
      } else if (difference.inDays < 7) {
        return '${difference.inDays} siku iliyopita';
      } else {
        return '${date.day}/${date.month}/${date.year}';
      }
    } catch (e) {
      return '';
    }
    return dateString;
  }
}
