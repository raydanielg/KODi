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
import 'screens/tenant/search_homes_page.dart';
import 'screens/tenant/my_home_page.dart';
import 'screens/tenant/favorites_page.dart';
import 'screens/tenant/payments_page.dart';
import 'screens/common/messages_page.dart';
import 'screens/common/settings_page.dart';
import 'constants/app_theme.dart';
import 'constants/app_routes.dart';
import 'utils/app_settings.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await AppSettings.instance.init();
  runApp(const MannaApp());
}

class MannaApp extends StatefulWidget {
  const MannaApp({super.key});

  @override
  State<MannaApp> createState() => _MannaAppState();
}

class _MannaAppState extends State<MannaApp> {
  @override
  void initState() {
    super.initState();
    AppSettings.instance.addListener(_onSettingsChanged);
  }

  @override
  void dispose() {
    AppSettings.instance.removeListener(_onSettingsChanged);
    super.dispose();
  }

  void _onSettingsChanged() => setState(() {});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Manna',
      debugShowCheckedModeBanner: false,
      theme: AppTheme.lightTheme,
      darkTheme: AppTheme.darkTheme,
      themeMode: AppSettings.instance.themeMode,
      initialRoute: AppRoutes.home,
      routes: {
        AppRoutes.home:             (_) => const HomeScreen(),
        AppRoutes.login:            (_) => const LoginScreen(),
        AppRoutes.register:         (_) => const RegisterScreen(),
        AppRoutes.dashboard:        (_) => const MainDashboardScreen(),
        AppRoutes.properties:       (_) => const PropertyListScreen(),
        AppRoutes.propertyDetails:  (_) => const PropertyDetailScreen(),
        AppRoutes.payments:         (_) => const PaymentScreen(),
        AppRoutes.maintenance:      (_) => const MaintenanceScreen(),
        AppRoutes.messages:         (_) => const MessageScreen(),
        AppRoutes.profile:          (_) => const ProfileScreen(),
        AppRoutes.notifications:    (_) => const NotificationsScreen(),
        AppRoutes.navigation:       (_) => const NavigationPage(),
        AppRoutes.payRent:          (_) => const PayRentPage(),
        AppRoutes.tenantMaintenance:(_) => const MaintenancePage(),
        AppRoutes.tenantProfile:    (_) => const TenantProfilePage(),
        AppRoutes.searchHomes:      (_) => const SearchHomesPage(),
        AppRoutes.myHome:           (_) => const MyHomePage(),
        AppRoutes.favorites:        (_) => const FavoritesPage(),
        AppRoutes.tenantPayments:   (_) => const PaymentsPage(),
        AppRoutes.settings:         (_) => const SettingsPage(),
      },
    );
  }
}
