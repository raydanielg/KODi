import 'package:flutter/material.dart';
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
      child: Column(children: [
        DrawerHeader(
          decoration: const BoxDecoration(
            gradient: LinearGradient(
              colors: [Color(0xFF0F172A), Color(0xFF1E293B)],
            ),
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisAlignment: MainAxisAlignment.end,
            children: [
              CircleAvatar(
                radius: 28,
                backgroundColor: AppColors.primary,
                child: Text(
                  user?.initials ?? '?',
                  style: const TextStyle(
                    color: Colors.white,
                    fontWeight: FontWeight.bold,
                    fontSize: 18,
                  ),
                ),
              ),
              const SizedBox(height: 12),
              Text(
                user?.name ?? 'User',
                style: const TextStyle(
                  color: Colors.white,
                  fontSize: 16,
                  fontWeight: FontWeight.bold,
                ),
              ),
              Text(
                user?.roleLabel ?? '',
                style: TextStyle(color: Colors.grey[400], fontSize: 13),
              ),
            ],
          ),
        ),
        Expanded(
          child: ListView(children: _getMenuItems(context, role)),
        ),
        const Divider(height: 1),
        ListTile(
          leading: const Icon(Icons.logout, color: Colors.red),
          title: const Text('Toka', style: TextStyle(color: Colors.red)),
          onTap: () async {
            await authService.logout();
            Navigator.of(context).pushNamedAndRemoveUntil('/login', (route) => false);
          },
        ),
        const SizedBox(height: 20),
      ]),
    );
  }

  List<Widget> _getMenuItems(BuildContext context, String role) {
    final items = <_MenuItem>[];

    items.add(_MenuItem(Icons.dashboard, 'Dashboard', '/dashboard'));

    switch (role) {
      case 'super_admin':
      case 'admin':
        items.addAll([
          _MenuItem(Icons.people, 'Watumiaji', '/users'),
          _MenuItem(Icons.home_work, 'Mali', '/properties'),
          _MenuItem(Icons.receipt_long, 'Malipo', '/payments'),
          _MenuItem(Icons.assessment, 'Ripoti', '/reports'),
        ]);
      case 'landlord':
        items.addAll([
          _MenuItem(Icons.home_work, 'Mali Zangu', '/properties'),
          _MenuItem(Icons.people, 'Wapangaji', '/tenants'),
          _MenuItem(Icons.monetization_on, 'Kodi', '/rent-collection'),
          _MenuItem(Icons.receipt, 'Malipo', '/payments'),
          _MenuItem(Icons.build, 'Matengenezo', '/maintenance'),
          _MenuItem(Icons.message, 'Ujumbe', '/messages'),
          _MenuItem(Icons.assessment, 'Ripoti', '/reports'),
        ]);
      case 'agent':
        items.addAll([
          _MenuItem(Icons.home_work, 'Mali', '/properties'),
          _MenuItem(Icons.people, 'Wateja', '/clients'),
          _MenuItem(Icons.monetization_on, 'Commission', '/commission'),
          _MenuItem(Icons.receipt, 'Malipo', '/payouts'),
          _MenuItem(Icons.message, 'Ujumbe', '/messages'),
        ]);
      case 'tenant':
        items.addAll([
          _MenuItem(Icons.search, 'Tafuta Nyumba', '/search'),
          _MenuItem(Icons.home, 'Nyumba Yangu', '/my-rental'),
          _MenuItem(Icons.payment, 'Malipo', '/payments'),
          _MenuItem(Icons.build, 'Matengenezo', '/maintenance'),
          _MenuItem(Icons.message, 'Ujumbe', '/messages'),
          _MenuItem(Icons.favorite, 'Zinazopendwa', '/favorites'),
        ]);
      case 'support':
        items.addAll([
          _MenuItem(Icons.confirmation_number, 'Tiketi', '/tickets'),
          _MenuItem(Icons.chat, 'Mazungumzo', '/chat'),
          _MenuItem(Icons.message, 'Ujumbe', '/messages'),
          _MenuItem(Icons.assessment, 'Ripoti', '/reports'),
        ]);
      case 'maintenance':
        items.addAll([
          _MenuItem(Icons.assignment, 'Kazi', '/tasks'),
          _MenuItem(Icons.schedule, 'Ratiba', '/schedule'),
          _MenuItem(Icons.message, 'Ujumbe', '/messages'),
        ]);
      case 'accountant':
        items.addAll([
          _MenuItem(Icons.payment, 'Malipo', '/payments'),
          _MenuItem(Icons.account_balance, 'Payouts', '/payouts'),
          _MenuItem(Icons.trending_up, 'Mapato', '/revenue'),
          _MenuItem(Icons.assessment, 'Ripoti', '/reports'),
        ]);
      case 'investor':
        items.addAll([
          _MenuItem(Icons.account_balance, 'Fedha', '/financial'),
          _MenuItem(Icons.bar_chart, 'Vipimo', '/metrics'),
          _MenuItem(Icons.trending_up, 'Ukuaji', '/growth'),
          _MenuItem(Icons.assessment, 'Ripoti', '/reports'),
        ]);
    }

    items.add(_MenuItem(Icons.person, 'Wasifu', '/profile'));
    items.add(_MenuItem(Icons.settings, 'Mipangilio', '/settings'));

    return items
        .map((item) => ListTile(
              leading: Icon(item.icon, size: 22),
              title: Text(item.title, style: const TextStyle(fontSize: 14)),
              onTap: () {
                Navigator.pop(context);
                Navigator.pushNamed(context, item.route);
              },
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
