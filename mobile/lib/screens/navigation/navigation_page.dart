import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../constants/app_colors.dart';
import '../../services/auth_service.dart';

class NavigationPage extends StatefulWidget {
  const NavigationPage({super.key});

  @override
  State<NavigationPage> createState() => _NavigationPageState();
}

class _NavigationPageState extends State<NavigationPage> {
  final AuthService _authService = AuthService();
  int _selectedIndex = 0;

  final List<NavItem> _navItems = [
    // Home Section
    NavItem(
      title: 'Rent Due',
      icon: Icons.payments_outlined,
      category: 'Home',
    ),
    NavItem(
      title: 'Balance',
      icon: Icons.account_balance_wallet_outlined,
      category: 'Home',
    ),
    NavItem(
      title: 'Lease Status',
      icon: Icons.home_work_outlined,
      category: 'Home',
    ),
    // Payments Section
    NavItem(
      title: 'Pay Rent',
      icon: Icons.payment_outlined,
      category: 'Payments',
    ),
    NavItem(
      title: 'Payment History',
      icon: Icons.history_outlined,
      category: 'Payments',
    ),
    NavItem(
      title: 'Download Receipts',
      icon: Icons.download_outlined,
      category: 'Payments',
    ),
    // Maintenance Section
    NavItem(
      title: 'Report Issue',
      icon: Icons.report_problem_outlined,
      category: 'Maintenance',
    ),
    NavItem(
      title: 'Active Requests',
      icon: Icons.assignment_outlined,
      category: 'Maintenance',
    ),
    NavItem(
      title: 'Request History',
      icon: Icons.list_alt_outlined,
      category: 'Maintenance',
    ),
    // Documents Section
    NavItem(
      title: 'Lease Agreement',
      icon: Icons.description_outlined,
      category: 'Documents',
    ),
    NavItem(
      title: 'Receipts',
      icon: Icons.receipt_long_outlined,
      category: 'Documents',
    ),
    NavItem(
      title: 'Notices from Landlord',
      icon: Icons.notifications_active_outlined,
      category: 'Documents',
    ),
    // Profile Section
    NavItem(
      title: 'Personal Information',
      icon: Icons.person_outline,
      category: 'Profile',
    ),
    NavItem(
      title: 'Emergency Contact',
      icon: Icons.contact_phone_outlined,
      category: 'Profile',
    ),
    NavItem(
      title: 'Change Password',
      icon: Icons.lock_outline,
      category: 'Profile',
    ),
    NavItem(
      title: 'Logout',
      icon: Icons.logout,
      category: 'Profile',
      isLogout: true,
    ),
  ];

  List<String> get _categories {
    return _navItems.map((item) => item.category).toSet().toList();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xfff9fafb),
      body: Row(
        children: [
          // Sidebar
          _buildSidebar(),
          // Main Content
          Expanded(
            child: _buildContent(),
          ),
        ],
      ),
    );
  }

  Widget _buildSidebar() {
    return Container(
      width: 280,
      decoration: const BoxDecoration(
        color: Colors.white,
        border: Border(
          right: BorderSide(color: Color(0xffe5e7eb), width: 1),
        ),
      ),
      child: Column(
        children: [
          // Header
          Container(
            padding: const EdgeInsets.all(24),
            decoration: BoxDecoration(
              gradient: LinearGradient(
                colors: [
                  AppColors.primary,
                  AppColors.primaryDark,
                ],
              ),
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  'Navigation',
                  style: GoogleFonts.poppins(
                    fontSize: 24,
                    fontWeight: FontWeight.bold,
                    color: Colors.white,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  'Tenant Portal',
                  style: GoogleFonts.poppins(
                    fontSize: 14,
                    color: Colors.white70,
                  ),
                ),
              ],
            ),
          ),
          // Navigation Items
          Expanded(
            child: ListView.builder(
              padding: const EdgeInsets.symmetric(vertical: 16),
              itemCount: _categories.length,
              itemBuilder: (context, categoryIndex) {
                final category = _categories[categoryIndex];
                final categoryItems = _navItems
                    .where((item) => item.category == category)
                    .toList();

                return Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Category Header
                    Padding(
                      padding: const EdgeInsets.fromLTRB(20, 16, 20, 8),
                      child: Text(
                        category,
                        style: GoogleFonts.poppins(
                          fontSize: 12,
                          fontWeight: FontWeight.w700,
                          color: Colors.grey[500],
                          letterSpacing: 1,
                        ),
                      ),
                    ),
                    // Category Items
                    ...categoryItems.map((item) {
                      final itemIndex = _navItems.indexOf(item);
                      final isSelected = _selectedIndex == itemIndex;

                      return _buildNavItem(item, isSelected, itemIndex);
                    }),
                  ],
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildNavItem(NavItem item, bool isSelected, int index) {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
      decoration: BoxDecoration(
        color: isSelected ? AppColors.primary.withOpacity(0.1) : Colors.transparent,
        borderRadius: BorderRadius.circular(12),
      ),
      child: ListTile(
        leading: Icon(
          item.icon,
          color: isSelected ? AppColors.primary : Colors.grey[600],
          size: 22,
        ),
        title: Text(
          item.title,
          style: GoogleFonts.poppins(
            fontSize: 14,
            fontWeight: isSelected ? FontWeight.w600 : FontWeight.w500,
            color: isSelected ? AppColors.primary : Colors.black87,
          ),
        ),
        onTap: () {
          setState(() {
            _selectedIndex = index;
          });

          if (item.isLogout) {
            _handleLogout();
          }
        },
      ),
    );
  }

  Widget _buildContent() {
    final selectedItem = _navItems[_selectedIndex];

    return Container(
      padding: const EdgeInsets.all(32),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Page Header
          Row(
            children: [
              Icon(
                selectedItem.icon,
                color: AppColors.primary,
                size: 32,
              ),
              const SizedBox(width: 16),
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    selectedItem.category,
                    style: GoogleFonts.poppins(
                      fontSize: 14,
                      color: Colors.grey[600],
                      fontWeight: FontWeight.w500,
                    ),
                  ),
                  Text(
                    selectedItem.title,
                    style: GoogleFonts.poppins(
                      fontSize: 28,
                      fontWeight: FontWeight.bold,
                      color: const Color(0xff111827),
                    ),
                  ),
                ],
              ),
            ],
          ),
          const SizedBox(height: 32),
          // Content Placeholder
          Expanded(
            child: Container(
              padding: const EdgeInsets.all(48),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(20),
                border: Border.all(color: const Color(0xffe5e7eb)),
              ),
              child: Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(
                      selectedItem.icon,
                      size: 64,
                      color: Colors.grey[300],
                    ),
                    const SizedBox(height: 16),
                    Text(
                      '${selectedItem.title} Page',
                      style: GoogleFonts.poppins(
                        fontSize: 20,
                        fontWeight: FontWeight.w600,
                        color: Colors.grey[600],
                      ),
                    ),
                    const SizedBox(height: 8),
                    Text(
                      'This page is under development',
                      style: GoogleFonts.poppins(
                        fontSize: 14,
                        color: Colors.grey[400],
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Future<void> _handleLogout() async {
    await _authService.logout();
    if (mounted) {
      Navigator.of(context).pushNamedAndRemoveUntil('/login', (route) => false);
    }
  }
}

class NavItem {
  final String title;
  final IconData icon;
  final String category;
  final bool isLogout;

  NavItem({
    required this.title,
    required this.icon,
    required this.category,
    this.isLogout = false,
  });
}
