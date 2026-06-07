import 'package:flutter/material.dart';
import '../../services/auth_service.dart';
import 'tenant_dashboard.dart';
import 'landlord_dashboard.dart';
import 'agent_dashboard.dart';

class MainDashboardScreen extends StatelessWidget {
  const MainDashboardScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final authService = AuthService();
    final user = authService.currentUser;

    if (user == null) {
      return const Scaffold(
        body: Center(
          child: Text('User session expired. Please log in again.'),
        ),
      );
    }

    final role = user.role;

    if (role == 'tenant') {
      return const TenantDashboard();
    } else if (role == 'landlord') {
      return const LandlordDashboard();
    } else {
      return const AgentDashboard();
    }
  }
}
