import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../constants/app_colors.dart';
import '../services/auth_service.dart';

class RoleDrawer extends StatefulWidget {
  final AuthService authService;

  const RoleDrawer({super.key, required this.authService});

  @override
  State<RoleDrawer> createState() => _RoleDrawerState();
}

class _RoleDrawerState extends State<RoleDrawer> {
  bool _isEnglish = false;

  String _t(String sw, String en) {
    return _isEnglish ? en : sw;
  }

  @override
  Widget build(BuildContext context) {
    final user = widget.authService.currentUser;
    final role = user?.role ?? 'tenant';

    return Drawer(
      backgroundColor: Colors.white,
      child: Column(children: [
        // Simple Header - Just close button
        Container(
          padding: const EdgeInsets.fromLTRB(20, 60, 20, 20),
          decoration: const BoxDecoration(
            color: Colors.white,
            border: Border(
              bottom: BorderSide(color: Color(0xffe5e7eb), width: 1),
            ),
          ),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                _t('Menu', 'Menu'),
                style: GoogleFonts.poppins(
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                  color: const Color(0xff111827),
                ),
              ),
              IconButton(
                icon: const Icon(Icons.close, color: Color(0xff4b5563)),
                onPressed: () => Navigator.pop(context),
              ),
            ],
          ),
        ),
        // Language Toggle
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
          child: Container(
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
            decoration: BoxDecoration(
              color: const Color(0xfff3f4f6),
              borderRadius: BorderRadius.circular(20),
            ),
            child: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                GestureDetector(
                  onTap: () {
                    setState(() {
                      _isEnglish = false;
                    });
                  },
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                    decoration: BoxDecoration(
                      color: !_isEnglish ? AppColors.primary : Colors.transparent,
                      borderRadius: BorderRadius.circular(16),
                    ),
                    child: Text(
                      'SW',
                      style: GoogleFonts.poppins(
                        fontSize: 11,
                        fontWeight: !_isEnglish ? FontWeight.bold : FontWeight.w500,
                        color: !_isEnglish ? Colors.black : Colors.grey[600],
                      ),
                    ),
                  ),
                ),
                const SizedBox(width: 4),
                GestureDetector(
                  onTap: () {
                    setState(() {
                      _isEnglish = true;
                    });
                  },
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                    decoration: BoxDecoration(
                      color: _isEnglish ? AppColors.primary : Colors.transparent,
                      borderRadius: BorderRadius.circular(16),
                    ),
                    child: Text(
                      'EN',
                      style: GoogleFonts.poppins(
                        fontSize: 11,
                        fontWeight: _isEnglish ? FontWeight.bold : FontWeight.w500,
                        color: _isEnglish ? Colors.black : Colors.grey[600],
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),
        // Menu Items
        Expanded(
          child: ListView(
            padding: const EdgeInsets.symmetric(vertical: 12),
            children: _getMenuItems(context, role),
          ),
        ),
        // Logout Button
        Padding(
          padding: const EdgeInsets.all(20),
          child: Container(
            width: double.infinity,
            padding: const EdgeInsets.symmetric(vertical: 16),
            decoration: BoxDecoration(
              color: Colors.red.withOpacity(0.1),
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: Colors.red.withOpacity(0.3)),
            ),
            child: InkWell(
              onTap: () async {
                await widget.authService.logout();
                Navigator.of(context).pushNamedAndRemoveUntil('/login', (route) => false);
              },
              borderRadius: BorderRadius.circular(12),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  const Icon(Icons.logout, color: Colors.red, size: 20),
                  const SizedBox(width: 8),
                  Text(
                    _t('Toka', 'Logout'),
                    style: GoogleFonts.poppins(
                      color: Colors.red,
                      fontSize: 14,
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                ],
              ),
            ),
          ),
        ),
      ]),
    );
  }

  List<Widget> _getMenuItems(BuildContext context, String role) {
    final items = <_MenuItem>[];

    items.add(_MenuItem(Icons.dashboard_rounded, _t('Dashboard', 'Dashboard'), '/dashboard'));

    switch (role) {
      case 'super_admin':
      case 'admin':
        items.addAll([
          _MenuItem(Icons.people_rounded, _t('Watumiaji', 'Users'), '/users'),
          _MenuItem(Icons.home_work_rounded, _t('Mali', 'Properties'), '/properties'),
          _MenuItem(Icons.receipt_long_rounded, _t('Malipo', 'Payments'), '/payments'),
          _MenuItem(Icons.assessment_rounded, _t('Ripoti', 'Reports'), '/reports'),
        ]);
      case 'landlord':
        items.addAll([
          _MenuItem(Icons.home_work_rounded, _t('Mali Zangu', 'My Properties'), '/properties'),
          _MenuItem(Icons.people_rounded, _t('Wapangaji', 'Tenants'), '/tenants'),
          _MenuItem(Icons.monetization_on_rounded, _t('Kodi', 'Rent'), '/rent-collection'),
          _MenuItem(Icons.receipt_rounded, _t('Malipo', 'Payments'), '/payments'),
          _MenuItem(Icons.build_rounded, _t('Matengenezo', 'Maintenance'), '/maintenance'),
          _MenuItem(Icons.message_rounded, _t('Ujumbe', 'Messages'), '/messages'),
          _MenuItem(Icons.assessment_rounded, _t('Ripoti', 'Reports'), '/reports'),
        ]);
      case 'agent':
        items.addAll([
          _MenuItem(Icons.home_work_rounded, _t('Mali', 'Properties'), '/properties'),
          _MenuItem(Icons.people_rounded, _t('Wateja', 'Clients'), '/clients'),
          _MenuItem(Icons.monetization_on_rounded, _t('Commission', 'Commission'), '/commission'),
          _MenuItem(Icons.receipt_rounded, _t('Malipo', 'Payouts'), '/payouts'),
          _MenuItem(Icons.message_rounded, _t('Ujumbe', 'Messages'), '/messages'),
        ]);
      case 'tenant':
        items.addAll([
          _MenuItem(Icons.search_rounded, _t('Tafuta Nyumba', 'Search Homes'), '/search'),
          _MenuItem(Icons.home_rounded, _t('Nyumba Yangu', 'My Home'), '/my-rental'),
          _MenuItem(Icons.payment_rounded, _t('Malipo', 'Payments'), '/payments'),
          _MenuItem(Icons.build_rounded, _t('Matengenezo', 'Maintenance'), '/maintenance'),
          _MenuItem(Icons.message_rounded, _t('Ujumbe', 'Messages'), '/messages'),
          _MenuItem(Icons.favorite_rounded, _t('Zinazopendwa', 'Favorites'), '/favorites'),
        ]);
      case 'support':
        items.addAll([
          _MenuItem(Icons.confirmation_number_rounded, _t('Tiketi', 'Tickets'), '/tickets'),
          _MenuItem(Icons.chat_rounded, _t('Mazungumzo', 'Chat'), '/chat'),
          _MenuItem(Icons.message_rounded, _t('Ujumbe', 'Messages'), '/messages'),
          _MenuItem(Icons.assessment_rounded, _t('Ripoti', 'Reports'), '/reports'),
        ]);
      case 'maintenance':
        items.addAll([
          _MenuItem(Icons.assignment_rounded, _t('Kazi', 'Tasks'), '/tasks'),
          _MenuItem(Icons.schedule_rounded, _t('Ratiba', 'Schedule'), '/schedule'),
          _MenuItem(Icons.message_rounded, _t('Ujumbe', 'Messages'), '/messages'),
        ]);
      case 'accountant':
        items.addAll([
          _MenuItem(Icons.payment_rounded, _t('Malipo', 'Payments'), '/payments'),
          _MenuItem(Icons.account_balance_rounded, _t('Payouts', 'Payouts'), '/payouts'),
          _MenuItem(Icons.trending_up_rounded, _t('Mapato', 'Revenue'), '/revenue'),
          _MenuItem(Icons.assessment_rounded, _t('Ripoti', 'Reports'), '/reports'),
        ]);
      case 'investor':
        items.addAll([
          _MenuItem(Icons.account_balance_rounded, _t('Fedha', 'Financial'), '/financial'),
          _MenuItem(Icons.bar_chart_rounded, _t('Vipimo', 'Metrics'), '/metrics'),
          _MenuItem(Icons.trending_up_rounded, _t('Ukuaji', 'Growth'), '/growth'),
          _MenuItem(Icons.assessment_rounded, _t('Ripoti', 'Reports'), '/reports'),
        ]);
    }

    items.add(_MenuItem(Icons.person_rounded, _t('Wasifu', 'Profile'), '/profile'));
    items.add(_MenuItem(Icons.settings_rounded, _t('Mipangilio', 'Settings'), '/settings'));

    return items
        .map((item) => Container(
              margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
              decoration: BoxDecoration(
                color: Colors.grey[50],
                borderRadius: BorderRadius.circular(12),
              ),
              child: ListTile(
                leading: Icon(item.icon, size: 22, color: AppColors.primary),
                title: Text(
                  item.title,
                  style: GoogleFonts.poppins(
                    fontSize: 14,
                    fontWeight: FontWeight.w500,
                    color: Colors.black87,
                  ),
                ),
                trailing: const Icon(
                  Icons.chevron_right_rounded,
                  size: 20,
                  color: Colors.grey,
                ),
                onTap: () {
                  Navigator.pop(context);
                  Navigator.pushNamed(context, item.route);
                },
              ),
            ))
        .toList();
  }
}

class _MenuItem {
  final IconData icon;
  final String title;
  final String route;
  _MenuItem(this.icon, this.title, this.route);
}
