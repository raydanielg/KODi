import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import '../../constants/app_colors.dart';
import '../../utils/app_settings.dart';
import 'home/landlord_home_tab.dart';
import 'tenants/landlord_tenants_tab.dart';
import 'properties/landlord_properties_tab.dart';
import 'leases/landlord_leases_tab.dart';
import 'reports/landlord_reports_tab.dart';

class LandlordMainScreen extends StatefulWidget {
  const LandlordMainScreen({super.key});

  @override
  State<LandlordMainScreen> createState() => _LandlordMainScreenState();
}

class _LandlordMainScreenState extends State<LandlordMainScreen> {
  int _currentIndex = 0;

  @override
  void initState() {
    super.initState();
    AppSettings.instance.addListener(_onThemeChanged);
  }

  @override
  void dispose() {
    AppSettings.instance.removeListener(_onThemeChanged);
    super.dispose();
  }

  void _onThemeChanged() { if (mounted) setState(() {}); }

  final List<Widget> _tabs = const [
    LandlordHomeTab(),
    LandlordTenantsTab(),
    LandlordPropertiesTab(),
    LandlordLeasesTab(),
    LandlordReportsTab(),
  ];

  @override
  Widget build(BuildContext context) {
    SystemChrome.setSystemUIOverlayStyle(const SystemUiOverlayStyle(
      statusBarColor: Colors.transparent,
      statusBarIconBrightness: Brightness.dark,
      systemNavigationBarColor: Colors.white,
      systemNavigationBarIconBrightness: Brightness.dark,
    ));

    final isDark = AppSettings.instance.isDark;
    return Scaffold(
      backgroundColor: isDark ? const Color(0xFF0F0F0F) : const Color(0xFFF4F6F8),
      body: IndexedStack(index: _currentIndex, children: _tabs),
      bottomNavigationBar: _buildBottomNav(isDark),
    );
  }

  Widget _buildBottomNav(bool isDark) {
    return Container(
      decoration: BoxDecoration(
        color: isDark ? const Color(0xFF141414) : Colors.white,
        border: Border(top: BorderSide(color: isDark ? const Color(0xFF2A2A2A) : const Color(0xFFF3F4F6), width: 1)),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: isDark ? 0.3 : 0.06),
            blurRadius: 16,
            offset: const Offset(0, -4),
          ),
        ],
      ),
      child: SafeArea(
        child: SizedBox(
          height: 62,
          child: Row(
            children: [
              _navItem(0, Icons.home_rounded, Icons.home_outlined, 'Home', isDark),
              _navItem(1, Icons.people_rounded, Icons.people_outline_rounded, 'Tenants', isDark),
              _navItem(2, Icons.apartment_rounded, Icons.apartment_outlined, 'Properties', isDark),
              _navItem(3, Icons.description_rounded, Icons.description_outlined, 'Leases', isDark),
              _navItem(4, Icons.bar_chart_rounded, Icons.bar_chart_outlined, 'Reports', isDark),
            ],
          ),
        ),
      ),
    );
  }

  Widget _navItem(int index, IconData activeIcon, IconData inactiveIcon, String label) {
    final isActive = _currentIndex == index;
    return Expanded(
      child: GestureDetector(
        onTap: () => setState(() => _currentIndex = index),
        behavior: HitTestBehavior.opaque,
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            AnimatedContainer(
              duration: const Duration(milliseconds: 200),
              padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
              decoration: BoxDecoration(
                color: isActive ? AppColors.primaryLight : Colors.transparent,
                borderRadius: BorderRadius.circular(20),
              ),
              child: Icon(
                isActive ? activeIcon : inactiveIcon,
                color: isActive ? AppColors.primary : const Color(0xFF9CA3AF),
                size: 22,
              ),
            ),
            const SizedBox(height: 2),
            Text(
              label,
              style: TextStyle(
                fontSize: 10,
                fontWeight: isActive ? FontWeight.w700 : FontWeight.w400,
                color: isActive ? AppColors.primary : const Color(0xFF9CA3AF),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
