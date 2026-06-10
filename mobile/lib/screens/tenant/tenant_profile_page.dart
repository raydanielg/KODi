import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../constants/app_colors.dart';
import '../../services/auth_service.dart';
import '../../utils/helpers.dart';

class TenantProfilePage extends StatefulWidget {
  const TenantProfilePage({super.key});

  @override
  State<TenantProfilePage> createState() => _TenantProfilePageState();
}

class _TenantProfilePageState extends State<TenantProfilePage> {
  final AuthService _authService = AuthService();

  @override
  Widget build(BuildContext context) {
    final user = _authService.currentUser;

    return Scaffold(
      backgroundColor: const Color(0xfff9fafb),
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back, color: Color(0xff111827)),
          onPressed: () => Navigator.pop(context),
        ),
        title: Text(
          'Wasifu',
          style: GoogleFonts.poppins(
            fontSize: 18,
            fontWeight: FontWeight.w700,
            color: const Color(0xff111827),
          ),
        ),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          children: [
            // Profile Header
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(24),
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  colors: [
                    AppColors.primary,
                    AppColors.primaryDark,
                  ],
                ),
                borderRadius: BorderRadius.circular(20),
                boxShadow: [
                  BoxShadow(
                    color: AppColors.primary.withOpacity(0.3),
                    blurRadius: 12,
                    offset: const Offset(0, 4),
                  ),
                ],
              ),
              child: Column(
                children: [
                  CircleAvatar(
                    radius: 40,
                    backgroundColor: Colors.white,
                    child: Text(
                      user?.initials ?? 'U',
                      style: GoogleFonts.poppins(
                        color: AppColors.primary,
                        fontWeight: FontWeight.bold,
                        fontSize: 24,
                      ),
                    ),
                  ),
                  const SizedBox(height: 16),
                  Text(
                    user?.name ?? 'User',
                    style: GoogleFonts.poppins(
                      color: Colors.white,
                      fontSize: 20,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    user?.email ?? '',
                    style: GoogleFonts.poppins(
                      color: Colors.white70,
                      fontSize: 14,
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 24),

            // Profile Options
            _buildSectionCard(
              title: 'Maelezo Binafsi',
              child: Column(
                children: [
                  _profileOption(
                    'Jina Kamili',
                    user?.name ?? 'N/A',
                    Icons.person_outline,
                    () {},
                  ),
                  const Divider(color: Color(0xffe5e7eb)),
                  _profileOption(
                    'Barua Pepe',
                    user?.email ?? 'N/A',
                    Icons.email_outlined,
                    () {},
                  ),
                  const Divider(color: Color(0xffe5e7eb)),
                  _profileOption(
                    'Namba ya Simu',
                    user?.phone ?? 'N/A',
                    Icons.phone_outlined,
                    () {},
                  ),
                ],
              ),
            ),
            const SizedBox(height: 20),

            _buildSectionCard(
              title: 'Mawasilio ya Dharura',
              child: Column(
                children: [
                  _profileOption(
                    'Jina la Mawasilio',
                    'John Doe',
                    Icons.contact_phone_outlined,
                    () {},
                  ),
                  const Divider(color: Color(0xffe5e7eb)),
                  _profileOption(
                    'Namba ya Simu',
                    '+255 123 456 789',
                    Icons.phone_outlined,
                    () {},
                  ),
                ],
              ),
            ),
            const SizedBox(height: 20),

            _buildSectionCard(
              title: 'Usalama',
              child: Column(
                children: [
                  _profileOption(
                    'Badilisha Nenosiri',
                    '••••••••',
                    Icons.lock_outline,
                    () {},
                  ),
                  const Divider(color: Color(0xffe5e7eb)),
                  _profileOption(
                    'Usalama wa Akaunti',
                    'Imewezeshwa',
                    Icons.security_outlined,
                    () {},
                  ),
                ],
              ),
            ),
            const SizedBox(height: 20),

            // Logout Button
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                color: Colors.red.withOpacity(0.1),
                borderRadius: BorderRadius.circular(16),
                border: Border.all(color: Colors.red.withOpacity(0.3)),
              ),
              child: InkWell(
                onTap: _handleLogout,
                borderRadius: BorderRadius.circular(16),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    const Icon(Icons.logout, color: Colors.red, size: 24),
                    const SizedBox(width: 12),
                    Text(
                      'Toka',
                      style: GoogleFonts.poppins(
                        color: Colors.red,
                        fontSize: 16,
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildSectionCard({required String title, required Widget child}) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 8,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            title,
            style: GoogleFonts.poppins(
              fontSize: 16,
              fontWeight: FontWeight.bold,
              color: const Color(0xff111827),
            ),
          ),
          const SizedBox(height: 16),
          child,
        ],
      ),
    );
  }

  Widget _profileOption(String label, String value, IconData icon, VoidCallback onTap) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(8),
      child: Padding(
        padding: const EdgeInsets.symmetric(vertical: 12),
        child: Row(
          children: [
            Icon(icon, color: AppColors.primary, size: 24),
            const SizedBox(width: 16),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    label,
                    style: GoogleFonts.poppins(
                      fontSize: 12,
                      color: Colors.grey[600],
                      fontWeight: FontWeight.w500,
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    value,
                    style: GoogleFonts.poppins(
                      fontSize: 15,
                      color: const Color(0xff111827),
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                ],
              ),
            ),
            const Icon(
              Icons.chevron_right_rounded,
              color: Colors.grey,
              size: 24,
            ),
          ],
        ),
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
