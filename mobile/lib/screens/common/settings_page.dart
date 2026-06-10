import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../constants/app_colors.dart';
import '../../services/auth_service.dart';

class SettingsPage extends StatefulWidget {
  const SettingsPage({super.key});

  @override
  State<SettingsPage> createState() => _SettingsPageState();
}

class _SettingsPageState extends State<SettingsPage> {
  final AuthService _authService = AuthService();
  bool _isEnglish = false;
  bool _isDarkMode = false;
  bool _notificationsEnabled = true;
  bool _biometricEnabled = true;

  String _t(String sw, String en) {
    return _isEnglish ? en : sw;
  }

  @override
  Widget build(BuildContext context) {
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
          _t('Mipangilio', 'Settings'),
          style: GoogleFonts.poppins(
            fontSize: 18,
            fontWeight: FontWeight.w700,
            color: const Color(0xff111827),
          ),
        ),
        actions: [
          // Language toggle
          Container(
            margin: const EdgeInsets.only(right: 16),
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
        ],
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          children: [
            // Appearance Section
            _sectionCard(
              title: _t('Muonekano', 'Appearance'),
              child: Column(
                children: [
                  _switchTile(
                    _t('Mweusi', 'Dark Mode'),
                    _isDarkMode,
                    (value) {
                      setState(() {
                        _isDarkMode = value;
                      });
                    },
                    Icons.dark_mode,
                  ),
                ],
              ),
            ),
            const SizedBox(height: 20),

            // Notifications Section
            _sectionCard(
              title: _t('Arifa', 'Notifications'),
              child: Column(
                children: [
                  _switchTile(
                    _t('Arifa za Push', 'Push Notifications'),
                    _notificationsEnabled,
                    (value) {
                      setState(() {
                        _notificationsEnabled = value;
                      });
                    },
                    Icons.notifications_outlined,
                  ),
                ],
              ),
            ),
            const SizedBox(height: 20),

            // Security Section
            _sectionCard(
              title: _t('Usalama', 'Security'),
              child: Column(
                children: [
                  _switchTile(
                    _t('Ugani wa Biometric', 'Biometric Authentication'),
                    _biometricEnabled,
                    (value) {
                      setState(() {
                        _biometricEnabled = value;
                      });
                    },
                    Icons.fingerprint,
                  ),
                  const Divider(color: Color(0xffe5e7eb)),
                  _navigationTile(
                    _t('Badilisha Nenosiri', 'Change Password'),
                    Icons.lock_outline,
                    () {},
                  ),
                ],
              ),
            ),
            const SizedBox(height: 20),

            // Language Section
            _sectionCard(
              title: _t('Lugha', 'Language'),
              child: Column(
                children: [
                  _languageOption('Kiswahili', true),
                  const Divider(color: Color(0xffe5e7eb)),
                  _languageOption('English', false),
                ],
              ),
            ),
            const SizedBox(height: 20),

            // About Section
            _sectionCard(
              title: _t('Kuhusu', 'About'),
              child: Column(
                children: [
                  _navigationTile(
                    _t('Toleo la App', 'App Version'),
                    Icons.info_outline,
                    () {},
                    trailing: '1.0.0',
                  ),
                  const Divider(color: Color(0xffe5e7eb)),
                  _navigationTile(
                    _t('Sera ya Faragha', 'Privacy Policy'),
                    Icons.privacy_tip_outlined,
                    () {},
                  ),
                  const Divider(color: Color(0xffe5e7eb)),
                  _navigationTile(
                    _t('Masharti ya Matumizi', 'Terms of Service'),
                    Icons.description_outlined,
                    () {},
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _sectionCard({required String title, required Widget child}) {
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

  Widget _switchTile(
    String title,
    bool value,
    Function(bool) onChanged,
    IconData icon,
  ) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        children: [
          Icon(icon, color: AppColors.primary, size: 24),
          const SizedBox(width: 16),
          Expanded(
            child: Text(
              title,
              style: GoogleFonts.poppins(
                fontSize: 14,
                color: const Color(0xff111827),
              ),
            ),
          ),
          Switch(
            value: value,
            onChanged: onChanged,
            activeColor: AppColors.primary,
          ),
        ],
      ),
    );
  }

  Widget _navigationTile(
    String title,
    IconData icon,
    VoidCallback onTap, {
    String? trailing,
  }) {
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
              child: Text(
                title,
                style: GoogleFonts.poppins(
                  fontSize: 14,
                  color: const Color(0xff111827),
                ),
              ),
            ),
            if (trailing != null)
              Text(
                trailing,
                style: GoogleFonts.poppins(
                  fontSize: 13,
                  color: Colors.grey[500],
                ),
              )
            else
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

  Widget _languageOption(String language, bool isSelected) {
    return InkWell(
      onTap: () {
        setState(() {
          _isEnglish = language == 'English';
        });
      },
      borderRadius: BorderRadius.circular(8),
      child: Padding(
        padding: const EdgeInsets.symmetric(vertical: 12),
        child: Row(
          children: [
            Expanded(
              child: Text(
                language,
                style: GoogleFonts.poppins(
                  fontSize: 14,
                  color: const Color(0xff111827),
                ),
              ),
            ),
            if (isSelected)
              Icon(
                Icons.check_circle,
                color: AppColors.primary,
                size: 24,
              )
            else
              Icon(
                Icons.radio_button_unchecked,
                color: Colors.grey[400],
                size: 24,
              ),
          ],
        ),
      ),
    );
  }
}
