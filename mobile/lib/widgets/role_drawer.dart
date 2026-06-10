import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../constants/app_colors.dart';
import '../services/auth_service.dart';

class RoleDrawer extends StatelessWidget {
  final AuthService authService;

  const RoleDrawer({super.key, required this.authService});

  @override
  Widget build(BuildContext context) {
    final user = authService.currentUser;
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
                'Menu',
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
                await authService.logout();
                Navigator.of(context).pushNamedAndRemoveUntil('/login', (route) => false);
              },
              borderRadius: BorderRadius.circular(12),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  const Icon(Icons.logout, color: Colors.red, size: 20),
                  const SizedBox(width: 8),
                  Text(
                    'Toka',
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

    items.add(_MenuItem(Icons.dashboard_rounded, 'Dashboard', '/dashboard'));

    switch (role) {
      case 'super_admin':
      case 'admin':
        items.addAll([
          _MenuItem(Icons.people_rounded, 'Watumiaji', '/users'),
          _MenuItem(Icons.home_work_rounded, 'Mali', '/properties'),
          _MenuItem(Icons.receipt_long_rounded, 'Malipo', '/payments'),
          _MenuItem(Icons.assessment_rounded, 'Ripoti', '/reports'),
        ]);
      case 'landlord':
        items.addAll([
          _MenuItem(Icons.home_work_rounded, 'Mali Zangu', '/properties'),
          _MenuItem(Icons.people_rounded, 'Wapangaji', '/tenants'),
          _MenuItem(Icons.monetization_on_rounded, 'Kodi', '/rent-collection'),
          _MenuItem(Icons.receipt_rounded, 'Malipo', '/payments'),
          _MenuItem(Icons.build_rounded, 'Matengenezo', '/maintenance'),
          _MenuItem(Icons.message_rounded, 'Ujumbe', '/messages'),
          _MenuItem(Icons.assessment_rounded, 'Ripoti', '/reports'),
        ]);
      case 'agent':
        items.addAll([
          _MenuItem(Icons.home_work_rounded, 'Mali', '/properties'),
          _MenuItem(Icons.people_rounded, 'Wateja', '/clients'),
          _MenuItem(Icons.monetization_on_rounded, 'Commission', '/commission'),
          _MenuItem(Icons.receipt_rounded, 'Malipo', '/payouts'),
          _MenuItem(Icons.message_rounded, 'Ujumbe', '/messages'),
        ]);
      case 'tenant':
        items.addAll([
          _MenuItem(Icons.search_rounded, 'Tafuta Nyumba', '/search'),
          _MenuItem(Icons.home_rounded, 'Nyumba Yangu', '/my-rental'),
          _MenuItem(Icons.payment_rounded, 'Malipo', '/payments'),
          _MenuItem(Icons.build_rounded, 'Matengenezo', '/maintenance'),
          _MenuItem(Icons.message_rounded, 'Ujumbe', '/messages'),
          _MenuItem(Icons.favorite_rounded, 'Zinazopendwa', '/favorites'),
        ]);
      case 'support':
        items.addAll([
          _MenuItem(Icons.confirmation_number_rounded, 'Tiketi', '/tickets'),
          _MenuItem(Icons.chat_rounded, 'Mazungumzo', '/chat'),
          _MenuItem(Icons.message_rounded, 'Ujumbe', '/messages'),
          _MenuItem(Icons.assessment_rounded, 'Ripoti', '/reports'),
        ]);
      case 'maintenance':
        items.addAll([
          _MenuItem(Icons.assignment_rounded, 'Kazi', '/tasks'),
          _MenuItem(Icons.schedule_rounded, 'Ratiba', '/schedule'),
          _MenuItem(Icons.message_rounded, 'Ujumbe', '/messages'),
        ]);
      case 'accountant':
        items.addAll([
          _MenuItem(Icons.payment_rounded, 'Malipo', '/payments'),
          _MenuItem(Icons.account_balance_rounded, 'Payouts', '/payouts'),
          _MenuItem(Icons.trending_up_rounded, 'Mapato', '/revenue'),
          _MenuItem(Icons.assessment_rounded, 'Ripoti', '/reports'),
        ]);
      case 'investor':
        items.addAll([
          _MenuItem(Icons.account_balance_rounded, 'Fedha', '/financial'),
          _MenuItem(Icons.bar_chart_rounded, 'Vipimo', '/metrics'),
          _MenuItem(Icons.trending_up_rounded, 'Ukuaji', '/growth'),
          _MenuItem(Icons.assessment_rounded, 'Ripoti', '/reports'),
        ]);
    }

    items.add(_MenuItem(Icons.person_rounded, 'Wasifu', '/profile'));
    items.add(_MenuItem(Icons.settings_rounded, 'Mipangilio', '/settings'));

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
