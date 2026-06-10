import 'package:flutter/material.dart';
import 'dart:io';
import 'package:image_picker/image_picker.dart';
import '../../../constants/app_colors.dart';
import '../../../models/user_model.dart';
import '../../../services/auth_service.dart';
import '../../../services/api_service.dart';
import '../../../utils/helpers.dart';

class LandlordProfilePage extends StatefulWidget {
  const LandlordProfilePage({super.key});

  @override
  State<LandlordProfilePage> createState() => _LandlordProfilePageState();
}

class _LandlordProfilePageState extends State<LandlordProfilePage> {
  final AuthService _auth = AuthService();
  final ApiService _api = ApiService();
  final ImagePicker _picker = ImagePicker();

  final _nameCtrl = TextEditingController();
  final _phoneCtrl = TextEditingController();
  final _emailCtrl = TextEditingController();
  final _currentPwCtrl = TextEditingController();
  final _newPwCtrl = TextEditingController();
  final _confirmPwCtrl = TextEditingController();

  File? _avatarFile;
  bool _saving = false;
  bool _uploading = false;
  bool _changingPw = false;
  bool _showCurrentPw = false;
  bool _showNewPw = false;
  int _activeSection = 0; // 0=profile, 1=security, 2=notifications

  @override
  void initState() {
    super.initState();
    final u = _auth.currentUser;
    if (u != null) {
      _nameCtrl.text = u.name;
      _phoneCtrl.text = u.phone.replaceAll('+255', '').replaceAll('255', '');
      _emailCtrl.text = u.email;
    }
  }

  @override
  void dispose() {
    _nameCtrl.dispose(); _phoneCtrl.dispose(); _emailCtrl.dispose();
    _currentPwCtrl.dispose(); _newPwCtrl.dispose(); _confirmPwCtrl.dispose();
    super.dispose();
  }

  Future<void> _pickAvatar() async {
    final src = await showModalBottomSheet<ImageSource>(
      context: context,
      backgroundColor: Colors.white,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(20))),
      builder: (_) => Padding(
        padding: const EdgeInsets.all(20),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(width: 40, height: 4, decoration: BoxDecoration(color: const Color(0xFFE5E7EB), borderRadius: BorderRadius.circular(2))),
            const SizedBox(height: 16),
            const Text('Chagua Picha', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 16, color: Color(0xFF111827))),
            const SizedBox(height: 16),
            Row(
              children: [
                Expanded(child: _sourceOption(Icons.camera_alt_rounded, 'Kamera', ImageSource.camera)),
                const SizedBox(width: 12),
                Expanded(child: _sourceOption(Icons.photo_library_rounded, 'Galeri', ImageSource.gallery)),
              ],
            ),
            const SizedBox(height: 8),
          ],
        ),
      ),
    );
    if (src == null) return;
    final picked = await _picker.pickImage(source: src, imageQuality: 80, maxWidth: 800);
    if (picked != null) {
      setState(() => _avatarFile = File(picked.path));
      await _uploadAvatar(File(picked.path));
    }
  }

  Widget _sourceOption(IconData icon, String label, ImageSource src) {
    return GestureDetector(
      onTap: () => Navigator.pop(context, src),
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 16),
        decoration: BoxDecoration(
          color: AppColors.primaryLight,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(color: AppColors.primary.withValues(alpha: 0.2)),
        ),
        child: Column(
          children: [
            Icon(icon, color: AppColors.primary, size: 26),
            const SizedBox(height: 6),
            Text(label, style: const TextStyle(color: AppColors.primary, fontWeight: FontWeight.w600, fontSize: 13)),
          ],
        ),
      ),
    );
  }

  Future<void> _uploadAvatar(File file) async {
    setState(() => _uploading = true);
    try {
      final res = await _api.uploadFile('auth/avatar', file);
      if (res['success'] == true && mounted) {
        Helpers.showSnackBar(context, 'Picha imehifadhiwa!');
      }
    } catch (e) {
      if (mounted) Helpers.showSnackBar(context, 'Imeshindikana kupakia picha');
    }
    if (mounted) setState(() => _uploading = false);
  }

  Future<void> _saveProfile() async {
    if (_nameCtrl.text.trim().isEmpty) {
      Helpers.showSnackBar(context, 'Jaza jina lako');
      return;
    }
    setState(() => _saving = true);
    try {
      final res = await _api.post('auth/profile', body: {
        'name': _nameCtrl.text.trim(),
        'phone': '+255${_phoneCtrl.text.replaceAll(RegExp(r'[^0-9]'), '')}',
      });
      if (mounted) {
        if (res['success'] == true) {
          Helpers.showSnackBar(context, 'Wasifu umehifadhiwa!');
        } else {
          Helpers.showSnackBar(context, res['message'] ?? 'Imeshindikana');
        }
      }
    } catch (e) {
      if (mounted) Helpers.showSnackBar(context, e.toString());
    }
    if (mounted) setState(() => _saving = false);
  }

  Future<void> _changePassword() async {
    if (_newPwCtrl.text != _confirmPwCtrl.text) {
      Helpers.showSnackBar(context, 'Nenosiri jipya hazilingani');
      return;
    }
    if (_newPwCtrl.text.length < 6) {
      Helpers.showSnackBar(context, 'Nenosiri liwe na herufi 6 au zaidi');
      return;
    }
    setState(() => _changingPw = true);
    try {
      final res = await _api.post('auth/change-password', body: {
        'current_password': _currentPwCtrl.text,
        'password': _newPwCtrl.text,
        'password_confirmation': _confirmPwCtrl.text,
      });
      if (mounted) {
        if (res['success'] == true) {
          Helpers.showSnackBar(context, 'Nenosiri limebadilishwa!');
          _currentPwCtrl.clear(); _newPwCtrl.clear(); _confirmPwCtrl.clear();
        } else {
          Helpers.showSnackBar(context, res['message'] ?? 'Imeshindikana kubadilisha nenosiri');
        }
      }
    } catch (e) {
      if (mounted) Helpers.showSnackBar(context, e.toString());
    }
    if (mounted) setState(() => _changingPw = false);
  }

  Future<void> _logout() async {
    final confirmed = await showDialog<bool>(
      context: context,
      builder: (_) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        title: const Text('Toka', style: TextStyle(fontWeight: FontWeight.w800, color: Color(0xFF111827))),
        content: const Text('Una uhakika unataka kutoka kwenye akaunti yako?',
            style: TextStyle(color: Color(0xFF6B7280))),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context, false), child: const Text('Hapana')),
          ElevatedButton(
            onPressed: () => Navigator.pop(context, true),
            style: ElevatedButton.styleFrom(backgroundColor: AppColors.primary, foregroundColor: Colors.white),
            child: const Text('Ndio, Toka'),
          ),
        ],
      ),
    );
    if (confirmed == true) {
      await _auth.logout();
      if (mounted) Navigator.of(context).pushNamedAndRemoveUntil('/login', (_) => false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final user = _auth.currentUser;
    return Scaffold(
      backgroundColor: const Color(0xFFF4F6F8),
      body: Column(
        children: [
          _buildHeader(context, user),
          Expanded(
            child: SingleChildScrollView(
              padding: const EdgeInsets.fromLTRB(16, 0, 16, 100),
              child: Column(
                children: [
                  const SizedBox(height: 16),
                  _buildAvatarSection(user),
                  const SizedBox(height: 20),
                  _buildSectionTabs(),
                  const SizedBox(height: 16),
                  if (_activeSection == 0) _buildProfileSection(),
                  if (_activeSection == 1) _buildSecuritySection(),
                  if (_activeSection == 2) _buildNotificationsSection(),
                  const SizedBox(height: 20),
                  _buildLogoutBtn(),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildHeader(BuildContext context, UserModel? user) {
    return Container(
      color: Colors.white,
      child: SafeArea(
        bottom: false,
        child: Padding(
          padding: const EdgeInsets.fromLTRB(16, 12, 16, 12),
          child: Row(
            children: [
              GestureDetector(
                onTap: () => Navigator.pop(context),
                child: Container(
                  width: 38,
                  height: 38,
                  decoration: BoxDecoration(
                    color: const Color(0xFFF4F6F8),
                    borderRadius: BorderRadius.circular(10),
                    border: Border.all(color: const Color(0xFFE5E7EB)),
                  ),
                  child: const Icon(Icons.arrow_back_ios_new_rounded, size: 16, color: Color(0xFF374151)),
                ),
              ),
              const SizedBox(width: 12),
              const Text('My Profile', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 18, color: Color(0xFF111827))),
              const Spacer(),
              if (user?.role != null)
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                  decoration: BoxDecoration(
                    color: AppColors.primaryLight,
                    borderRadius: BorderRadius.circular(20),
                    border: Border.all(color: AppColors.primary.withValues(alpha: 0.2)),
                  ),
                  child: Text(
                    user!.roleLabel,
                    style: const TextStyle(color: AppColors.primary, fontSize: 11, fontWeight: FontWeight.w700),
                  ),
                ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildAvatarSection(UserModel? user) {
    final name = user?.name ?? '';
    final initials = name.isNotEmpty
        ? name.split(' ').take(2).map((w) => w.isNotEmpty ? w[0] : '').join().toUpperCase()
        : 'L';

    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.05), blurRadius: 12)],
      ),
      child: Column(
        children: [
          Stack(
            children: [
              Container(
                width: 90,
                height: 90,
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(26),
                  border: Border.all(color: AppColors.primary.withValues(alpha: 0.3), width: 3),
                  color: AppColors.primaryLight,
                  boxShadow: [BoxShadow(color: AppColors.primary.withValues(alpha: 0.15), blurRadius: 12, offset: const Offset(0, 4))],
                ),
                child: ClipRRect(
                  borderRadius: BorderRadius.circular(23),
                  child: _uploading
                      ? const Center(child: CircularProgressIndicator(strokeWidth: 2, color: AppColors.primary))
                      : _avatarFile != null
                          ? Image.file(_avatarFile!, fit: BoxFit.cover)
                          : user?.avatar != null && user!.avatar!.isNotEmpty
                              ? Image.network(user.avatar!, fit: BoxFit.cover,
                                  errorBuilder: (_, __, ___) => Center(
                                    child: Text(initials, style: const TextStyle(color: AppColors.primary, fontWeight: FontWeight.w900, fontSize: 32)),
                                  ))
                              : Center(
                                  child: Text(initials, style: const TextStyle(color: AppColors.primary, fontWeight: FontWeight.w900, fontSize: 32)),
                                ),
                ),
              ),
              Positioned(
                bottom: 0,
                right: 0,
                child: GestureDetector(
                  onTap: _pickAvatar,
                  child: Container(
                    width: 30,
                    height: 30,
                    decoration: BoxDecoration(
                      color: AppColors.primary,
                      borderRadius: BorderRadius.circular(10),
                      border: Border.all(color: Colors.white, width: 2),
                    ),
                    child: const Icon(Icons.camera_alt_rounded, color: Colors.white, size: 14),
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 14),
          Text(
            user?.name ?? '',
            style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 18, color: Color(0xFF111827)),
          ),
          const SizedBox(height: 4),
          Text(
            user?.email ?? '',
            style: const TextStyle(color: Color(0xFF6B7280), fontSize: 13),
          ),
          const SizedBox(height: 12),
          // Info chips
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              _infoChip(Icons.phone_rounded, user?.phone ?? ''),
              const SizedBox(width: 8),
              _infoChip(Icons.calendar_today_rounded,
                  user?.createdAt != null ? 'Joined ${user!.createdAt!.substring(0, 10)}' : 'Active'),
            ],
          ),
        ],
      ),
    );
  }

  Widget _infoChip(IconData icon, String label) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
      decoration: BoxDecoration(
        color: const Color(0xFFF4F6F8),
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: const Color(0xFFE5E7EB)),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, size: 12, color: const Color(0xFF9CA3AF)),
          const SizedBox(width: 5),
          Text(label, style: const TextStyle(color: Color(0xFF6B7280), fontSize: 11, fontWeight: FontWeight.w500)),
        ],
      ),
    );
  }

  Widget _buildSectionTabs() {
    final tabs = ['Profile', 'Security', 'Notifications'];
    return Container(
      decoration: BoxDecoration(
        color: const Color(0xFFE5E7EB),
        borderRadius: BorderRadius.circular(12),
      ),
      padding: const EdgeInsets.all(3),
      child: Row(
        children: tabs.asMap().entries.map((e) {
          final selected = _activeSection == e.key;
          return Expanded(
            child: GestureDetector(
              onTap: () => setState(() => _activeSection = e.key),
              child: AnimatedContainer(
                duration: const Duration(milliseconds: 200),
                padding: const EdgeInsets.symmetric(vertical: 9),
                decoration: BoxDecoration(
                  color: selected ? Colors.white : Colors.transparent,
                  borderRadius: BorderRadius.circular(9),
                  boxShadow: selected ? [BoxShadow(color: Colors.black.withValues(alpha: 0.06), blurRadius: 4)] : [],
                ),
                child: Text(
                  e.value,
                  textAlign: TextAlign.center,
                  style: TextStyle(
                    color: selected ? const Color(0xFF111827) : const Color(0xFF9CA3AF),
                    fontWeight: selected ? FontWeight.w700 : FontWeight.w500,
                    fontSize: 13,
                  ),
                ),
              ),
            ),
          );
        }).toList(),
      ),
    );
  }

  Widget _buildProfileSection() {
    return _card(
      title: 'Personal Information',
      icon: Icons.person_rounded,
      child: Column(
        children: [
          _inputField('Full Name', _nameCtrl, Icons.person_outline_rounded, 'Your full name'),
          const SizedBox(height: 14),
          _inputField('Phone Number', _phoneCtrl, Icons.phone_outlined, '7XXXXXXXX',
              keyboard: TextInputType.phone,
              prefix: Container(
                margin: const EdgeInsets.only(right: 8),
                padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 12),
                decoration: const BoxDecoration(border: Border(right: BorderSide(color: Color(0xFFE5E7EB)))),
                child: const Text('+255', style: TextStyle(color: Color(0xFF374151), fontWeight: FontWeight.w600, fontSize: 14)),
              )),
          const SizedBox(height: 14),
          _inputField('Email Address', _emailCtrl, Icons.email_outlined, 'your@email.com',
              enabled: false,
              keyboard: TextInputType.emailAddress),
          const SizedBox(height: 20),
          SizedBox(
            width: double.infinity,
            height: 50,
            child: ElevatedButton.icon(
              onPressed: _saving ? null : _saveProfile,
              icon: _saving
                  ? const SizedBox(width: 18, height: 18, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                  : const Icon(Icons.save_rounded, size: 18),
              label: Text(_saving ? 'Inahifadhi...' : 'Hifadhi Mabadiliko',
                  style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 15)),
              style: ElevatedButton.styleFrom(
                backgroundColor: AppColors.primary,
                foregroundColor: Colors.white,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                elevation: 0,
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSecuritySection() {
    return _card(
      title: 'Change Password',
      icon: Icons.lock_rounded,
      child: Column(
        children: [
          _pwField('Current Password', _currentPwCtrl, _showCurrentPw,
              () => setState(() => _showCurrentPw = !_showCurrentPw)),
          const SizedBox(height: 14),
          _pwField('New Password', _newPwCtrl, _showNewPw,
              () => setState(() => _showNewPw = !_showNewPw)),
          const SizedBox(height: 14),
          _pwField('Confirm New Password', _confirmPwCtrl, _showNewPw,
              () => setState(() => _showNewPw = !_showNewPw)),
          const SizedBox(height: 8),
          // Password strength hint
          Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              color: const Color(0xFFF0FDF4),
              borderRadius: BorderRadius.circular(10),
              border: Border.all(color: const Color(0xFF86EFAC)),
            ),
            child: const Row(
              children: [
                Icon(Icons.info_outline_rounded, color: Color(0xFF16A34A), size: 16),
                SizedBox(width: 8),
                Expanded(child: Text(
                  'Nenosiri liwe na herufi 6+, namba na alama',
                  style: TextStyle(color: Color(0xFF16A34A), fontSize: 11),
                )),
              ],
            ),
          ),
          const SizedBox(height: 20),
          SizedBox(
            width: double.infinity,
            height: 50,
            child: ElevatedButton.icon(
              onPressed: _changingPw ? null : _changePassword,
              icon: _changingPw
                  ? const SizedBox(width: 18, height: 18, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                  : const Icon(Icons.lock_reset_rounded, size: 18),
              label: Text(_changingPw ? 'Inabadilisha...' : 'Badilisha Nenosiri',
                  style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 15)),
              style: ElevatedButton.styleFrom(
                backgroundColor: AppColors.primary,
                foregroundColor: Colors.white,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                elevation: 0,
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildNotificationsSection() {
    final settings = [
      {'label': 'Arifa za malipo', 'sub': 'Ujumbe wa malipo ya kodi', 'icon': Icons.payments_rounded, 'value': true},
      {'label': 'Matengenezo', 'sub': 'Maombi ya fundi', 'icon': Icons.build_rounded, 'value': true},
      {'label': 'Mikataba inayoisha', 'sub': 'Ikumbushwe wiki 2 kabla', 'icon': Icons.description_rounded, 'value': true},
      {'label': 'SMS notifications', 'sub': 'Ujumbe kwenye simu yako', 'icon': Icons.sms_rounded, 'value': false},
      {'label': 'Email updates', 'sub': 'Taarifa kwenye barua pepe', 'icon': Icons.email_rounded, 'value': true},
    ];

    return _card(
      title: 'Notification Settings',
      icon: Icons.notifications_rounded,
      child: Column(
        children: settings.map((s) {
          return Container(
            margin: const EdgeInsets.only(bottom: 2),
            child: SwitchListTile(
              contentPadding: EdgeInsets.zero,
              title: Text(s['label'] as String,
                  style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13, color: Color(0xFF111827))),
              subtitle: Text(s['sub'] as String,
                  style: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 11)),
              secondary: Container(
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(
                  color: AppColors.primaryLight,
                  borderRadius: BorderRadius.circular(10),
                ),
                child: Icon(s['icon'] as IconData, color: AppColors.primary, size: 18),
              ),
              value: s['value'] as bool,
              activeColor: AppColors.primary,
              onChanged: (v) => Helpers.showSnackBar(context, 'Mipangilio imehifadhiwa'),
            ),
          );
        }).toList(),
      ),
    );
  }

  Widget _buildLogoutBtn() {
    return SizedBox(
      width: double.infinity,
      height: 50,
      child: OutlinedButton.icon(
        onPressed: _logout,
        icon: const Icon(Icons.logout_rounded, size: 18),
        label: const Text('Toka Kwenye Akaunti', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 15)),
        style: OutlinedButton.styleFrom(
          foregroundColor: const Color(0xFFEF4444),
          side: const BorderSide(color: Color(0xFFFECACA)),
          backgroundColor: const Color(0xFFFFF5F5),
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        ),
      ),
    );
  }

  // ─── Helpers ──────────────────────────────────────────────────────────────

  Widget _card({required String title, required IconData icon, required Widget child}) {
    return Container(
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.04), blurRadius: 10)],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(color: AppColors.primaryLight, borderRadius: BorderRadius.circular(10)),
                child: Icon(icon, color: AppColors.primary, size: 18),
              ),
              const SizedBox(width: 10),
              Text(title, style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 15, color: Color(0xFF111827))),
            ],
          ),
          const SizedBox(height: 16),
          const Divider(height: 1, color: Color(0xFFF3F4F6)),
          const SizedBox(height: 16),
          child,
        ],
      ),
    );
  }

  Widget _inputField(
    String label,
    TextEditingController ctrl,
    IconData icon,
    String hint, {
    TextInputType? keyboard,
    bool enabled = true,
    Widget? prefix,
  }) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(label, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 12, color: Color(0xFF374151))),
        const SizedBox(height: 6),
        TextField(
          controller: ctrl,
          keyboardType: keyboard,
          enabled: enabled,
          style: TextStyle(fontSize: 14, color: enabled ? const Color(0xFF111827) : const Color(0xFF9CA3AF)),
          decoration: InputDecoration(
            hintText: hint,
            hintStyle: const TextStyle(color: Color(0xFFD1D5DB), fontSize: 13),
            prefixIcon: prefix ?? Icon(icon, size: 18, color: const Color(0xFF9CA3AF)),
            prefixIconConstraints: prefix != null ? const BoxConstraints() : null,
            contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 14),
            border: OutlineInputBorder(borderRadius: BorderRadius.circular(11), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
            enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(11), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
            focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(11), borderSide: const BorderSide(color: AppColors.primary, width: 1.5)),
            disabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(11), borderSide: const BorderSide(color: Color(0xFFF3F4F6))),
            filled: true,
            fillColor: enabled ? Colors.white : const Color(0xFFF9FAFB),
          ),
        ),
      ],
    );
  }

  Widget _pwField(String label, TextEditingController ctrl, bool visible, VoidCallback toggle) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(label, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 12, color: Color(0xFF374151))),
        const SizedBox(height: 6),
        TextField(
          controller: ctrl,
          obscureText: !visible,
          style: const TextStyle(fontSize: 14, color: Color(0xFF111827)),
          decoration: InputDecoration(
            hintText: '••••••••',
            hintStyle: const TextStyle(color: Color(0xFFD1D5DB)),
            prefixIcon: const Icon(Icons.lock_outline_rounded, size: 18, color: Color(0xFF9CA3AF)),
            suffixIcon: GestureDetector(
              onTap: toggle,
              child: Icon(visible ? Icons.visibility_off_outlined : Icons.visibility_outlined,
                  size: 18, color: const Color(0xFF9CA3AF)),
            ),
            contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 14),
            border: OutlineInputBorder(borderRadius: BorderRadius.circular(11), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
            enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(11), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
            focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(11), borderSide: const BorderSide(color: AppColors.primary, width: 1.5)),
            filled: true,
            fillColor: Colors.white,
          ),
        ),
      ],
    );
  }
}
