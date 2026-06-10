import 'package:flutter/material.dart';
import 'screens/home/home_screen.dart';
import 'screens/auth/login_screen.dart';
import 'screens/auth/register_screen.dart';
import 'screens/dashboard/main_dashboard_screen.dart';
import 'screens/properties/property_list_screen.dart';
import 'screens/properties/property_detail_screen.dart';
import 'screens/payments/payment_screen.dart';
import 'screens/maintenance/maintenance_screen.dart';
import 'screens/messages/message_screen.dart';
import 'screens/profile/profile_screen.dart';
import 'screens/notifications/notifications_screen.dart';
import 'screens/navigation/navigation_page.dart';
import 'screens/tenant/pay_rent_page.dart';
import 'screens/tenant/maintenance_page.dart';
import 'screens/tenant/tenant_profile_page.dart';
import 'constants/app_theme.dart';
import 'constants/app_routes.dart';

void main() {
  runApp(const MannaApp());
}

class MannaApp extends StatelessWidget {
  const MannaApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Manna',
      debugShowCheckedModeBanner: false,
      theme: AppTheme.lightTheme,
      darkTheme: AppTheme.darkTheme,
      initialRoute: AppRoutes.home,
      routes: {
        AppRoutes.home: (context) => const HomeScreen(),
        AppRoutes.login: (context) => const LoginScreen(),
        AppRoutes.register: (context) => const RegisterScreen(),
        AppRoutes.dashboard: (context) => const MainDashboardScreen(),
        AppRoutes.properties: (context) => const PropertyListScreen(),
        AppRoutes.propertyDetails: (context) => const PropertyDetailScreen(),
        AppRoutes.payments: (context) => const PaymentScreen(),
        AppRoutes.maintenance: (context) => const MaintenanceScreen(),
        AppRoutes.messages: (context) => const MessageScreen(),
        AppRoutes.profile: (context) => const ProfileScreen(),
        AppRoutes.notifications: (context) => const NotificationsScreen(),
        AppRoutes.navigation: (context) => const NavigationPage(),
        AppRoutes.payRent: (context) => const PayRentPage(),
        AppRoutes.tenantMaintenance: (context) => const MaintenancePage(),
        AppRoutes.tenantProfile: (context) => const TenantProfilePage(),
      },
    );
  }
}
