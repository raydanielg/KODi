import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../constants/app_colors.dart';
import '../../constants/app_strings.dart';
import '../../models/user_model.dart';
import '../../services/auth_service.dart';
import '../../utils/validators.dart';
import '../../utils/helpers.dart';
import '../../utils/app_settings.dart';

class RegisterScreen extends StatefulWidget {
  const RegisterScreen({super.key});

  @override
  State<RegisterScreen> createState() => _RegisterScreenState();
}

class _RegisterScreenState extends State<RegisterScreen> {
  final _formKey = GlobalKey<FormState>();
  final _nameController = TextEditingController();
  final _emailController = TextEditingController();
  final _phoneController = TextEditingController();
  final _passwordController = TextEditingController();
  final _confirmPasswordController = TextEditingController();
  final _authService = AuthService();
  String _selectedRole = '';
  bool _obscurePassword = true;
  bool _obscureConfirmPassword = true;
  bool _agreeToTerms = false;
  bool _isLoading = false;
  final _appSettings = AppSettings.instance;

  final List<String> _roles = [
    'Landlord (Mwenye Nyumba)',
    'Agent (Wakala wa Nyumba)',
  ];

  final Map<String, String> _roleDescriptions = {
    'Landlord (Mwenye Nyumba)': 'Landlord: Own properties to rent out',
    'Agent (Wakala wa Nyumba)': 'Agent: Manage properties on behalf of landlords',
  };

  String _mapRoleValue(String label) {
    if (label.startsWith('Landlord')) return 'landlord';
    if (label.startsWith('Agent')) return 'agent';
    if (label.startsWith('Support')) return 'support';
    if (label.startsWith('Maintenance')) return 'maintenance';
    if (label.startsWith('Accountant')) return 'accountant';
    return 'landlord';
  }

  final List<Map<String, String>> _termsOfServiceContent = [
    {
      'title': '1. Agreement to Terms',
      'content': 'By accessing or using Manna, you agree to be bound by these Terms of Service ("Terms"). If you disagree with any part of these terms, you may not access the Service.\n\nThese Terms, together with our Privacy Policy, govern your use of Manna and all related services, features, and functionality.',
    },
    {
      'title': '2. Description of Service',
      'content': 'Manna is a long-term rental platform that connects landlords with tenants across Africa. Our Service includes:\n• Property listing and management tools for landlords\n• Property search and inquiry features for tenants\n• Rental application and payment processing\n• Communication tools between landlords and tenants\n• Review and rating systems\n• Account management and user profiles',
    },
    {
      'title': '3. User Accounts',
      'content': 'To use certain features, you must create an account. You agree to provide accurate, complete, and current info during registration. You are responsible for maintaining your account credentials confidentiality and for all activities under your account.',
    },
    {
      'title': '4. User Conduct',
      'content': 'You agree not to use the Service for any purpose that is unlawful or prohibited. You agree not to post false, misleading, or inaccurate info, impersonate others, violate laws, transmit malware, or harass other users.',
    },
    {
      'title': '5. Property Listings',
      'content': 'As a landlord, you agree to provide accurate property info, current photos, disclose material facts, and comply with housing laws. You grant Manna a royalty-free license to use/display your content.',
    },
    {
      'title': '6. Rental Transactions',
      'content': 'We facilitate payments via third-party processors. Manna is not a party to rental agreements between landlords and tenants. All terms are negotiated directly. We do not arbitrate disputes.',
    },
  ];

  final List<Map<String, String>> _privacyPolicyContent = [
    {
      'title': '1. Introduction',
      'content': 'Welcome to Manna. We are committed to protecting your personal information and your right to privacy. If you have any questions or concerns, please contact us at support@manna.com.',
    },
    {
      'title': '2. Information We Collect',
      'content': 'We collect several types of info, including:\n• Personal Identification: Name, email, phone number.\n• Account Information: Username, encrypted passwords.\n• Property Information: Details, photos, addresses.\n• Payment Information: Secure processing details.\n• Usage and Device Data: IP address, browser type, cookies.',
    },
    {
      'title': '3. How We Use Your Information',
      'content': 'We use your info to:\n• Provide and improve our services\n• Process transactions and send updates\n• Facilitate landlord-tenant communications\n• Detect and prevent fraudulent activities\n• Comply with legal obligations',
    },
    {
      'title': '4. Information Sharing',
      'content': 'We may share info with:\n• Service providers (payment processors, hosting)\n• Business transfers (mergers, acquisitions)\n• Legal requirements (courts, government agencies)\n• Other users (facilitating landlord-tenant connection)',
    },
    {
      'title': '5. Data Security',
      'content': 'We implement technical measures like data encryption in transit and secure database hashing to protect your information. No method of electronic transmission is 100% secure.',
    },
  ];

  void _showContentBottomSheet({
    required BuildContext context,
    required String title,
    required String lastUpdated,
    required String effectiveDate,
    required List<Map<String, String>> sections,
  }) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) {
        return DraggableScrollableSheet(
          initialChildSize: 0.85,
          minChildSize: 0.5,
          maxChildSize: 0.95,
          builder: (context, scrollController) {
            return Container(
              decoration: const BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.vertical(
                  top: Radius.circular(24),
                ),
              ),
              child: Column(
                children: [
                  Center(
                    child: Container(
                      margin: const EdgeInsets.only(top: 12, bottom: 8),
                      width: 40,
                      height: 5,
                      decoration: BoxDecoration(
                        color: Colors.grey[300],
                        borderRadius: BorderRadius.circular(2.5),
                      ),
                    ),
                  ),
                  Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 8),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text(
                          title,
                          style: GoogleFonts.poppins(
                            fontSize: 22,
                            fontWeight: FontWeight.w800,
                            color: const Color(0xff111827),
                          ),
                        ),
                        IconButton(
                          onPressed: () => Navigator.pop(context),
                          icon: const Icon(Icons.close, color: Colors.grey),
                          style: IconButton.styleFrom(
                            backgroundColor: Colors.grey[100],
                            padding: const EdgeInsets.all(8),
                          ),
                        ),
                      ],
                    ),
                  ),
                  const Divider(),
                  Expanded(
                    child: ListView(
                      controller: scrollController,
                      padding: const EdgeInsets.all(24),
                      children: [
                        Text(
                          'Last Updated: $lastUpdated',
                          style: GoogleFonts.poppins(
                            fontSize: 13,
                            color: const Color(0xff6b7280),
                          ),
                        ),
                        const SizedBox(height: 8),
                        Container(
                          width: double.infinity,
                          padding: const EdgeInsets.all(12),
                          decoration: BoxDecoration(
                            color: const Color(0xfff3f4f6),
                            borderRadius: BorderRadius.circular(8),
                          ),
                          child: Text(
                            'Effective Date: $effectiveDate',
                            style: GoogleFonts.poppins(
                              fontSize: 14,
                              fontWeight: FontWeight.w600,
                              color: const Color(0xff374151),
                            ),
                          ),
                        ),
                        const SizedBox(height: 24),
                        ...sections.map((section) {
                          return Padding(
                            padding: const EdgeInsets.only(bottom: 24),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  section['title']!,
                                  style: GoogleFonts.poppins(
                                    fontSize: 16,
                                    fontWeight: FontWeight.w700,
                                    color: const Color(0xff111827),
                                  ),
                                ),
                                const SizedBox(height: 8),
                                Text(
                                  section['content']!,
                                  style: GoogleFonts.poppins(
                                    fontSize: 14,
                                    color: const Color(0xff4b5563),
                                    height: 1.6,
                                  ),
                                ),
                              ],
                            ),
                          );
                        }).toList(),
                      ],
                    ),
                  ),
                  Container(
                    padding: const EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      boxShadow: [
                        BoxShadow(
                          color: Colors.black.withOpacity(0.05),
                          blurRadius: 10,
                          offset: const Offset(0, -5),
                        ),
                      ],
                    ),
                    child: SafeArea(
                      top: false,
                      child: Row(
                        children: [
                          Expanded(
                            child: ElevatedButton(
                              onPressed: () => Navigator.pop(context),
                              style: ElevatedButton.styleFrom(
                                backgroundColor: AppColors.primary,
                                foregroundColor: Colors.white,
                                padding: const EdgeInsets.symmetric(vertical: 16),
                                shape: RoundedRectangleBorder(
                                  borderRadius: BorderRadius.circular(12),
                                ),
                                elevation: 0,
                              ),
                              child: Text(
                                'I Understand',
                                style: GoogleFonts.poppins(
                                  fontWeight: FontWeight.w600,
                                  fontSize: 15,
                                ),
                              ),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                ],
              ),
            );
          },
        );
      },
    );
  }

  @override
  void dispose() {
    _nameController.dispose();
    _emailController.dispose();
    _phoneController.dispose();
    _passwordController.dispose();
    _confirmPasswordController.dispose();
    super.dispose();
  }

  Widget _buildRoleCard({
    required String role,
    required IconData icon,
    required bool isDark,
  }) {
    final isSelected = _selectedRole == role;
    return GestureDetector(
      onTap: () => setState(() => _selectedRole = role),
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 200),
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: isSelected 
              ? AppColors.primary 
              : (isDark ? Colors.white.withOpacity(0.05) : Colors.grey[100]),
          borderRadius: BorderRadius.circular(16),
          border: Border.all(
            color: isSelected 
                ? AppColors.primary 
                : (isDark ? Colors.white.withOpacity(0.1) : Colors.grey[300]!),
            width: isSelected ? 2 : 1,
          ),
          boxShadow: isSelected
              ? [
                  BoxShadow(
                    color: AppColors.primary.withOpacity(0.3),
                    blurRadius: 12,
                    offset: const Offset(0, 4),
                  ),
                ]
              : null,
        ),
        child: Column(
          children: [
            Icon(
              icon,
              size: 32,
              color: isSelected ? Colors.black : (isDark ? Colors.white70 : Colors.grey[600]),
            ),
            const SizedBox(height: 8),
            Text(
              role.split(' (')[0],
              style: GoogleFonts.poppins(
                fontSize: 14,
                fontWeight: FontWeight.w600,
                color: isSelected ? Colors.black : (isDark ? Colors.white : Colors.black87),
              ),
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 4),
            Text(
              role.contains('(') ? role.split('(')[1].replaceAll(')', '') : '',
              style: GoogleFonts.poppins(
                fontSize: 11,
                fontWeight: FontWeight.w400,
                color: isSelected ? Colors.black54 : (isDark ? Colors.white60 : Colors.grey[500]),
              ),
              textAlign: TextAlign.center,
            ),
          ],
        ),
      ),
    );
  }

  Future<void> _handleRegister() async {
    if (!_formKey.currentState!.validate()) return;
    if (_selectedRole.isEmpty) {
      Helpers.showSnackBar(context, AppStrings.pleaseSelectRole, isError: true);
      return;
    }
    if (!_agreeToTerms) {
      Helpers.showSnackBar(context, AppStrings.pleaseAgreeToTerms, isError: true);
      return;
    }

    setState(() => _isLoading = true);

    try {
      final mappedRole = _mapRoleValue(_selectedRole);
      final result = await _authService.register(
        name: _nameController.text.trim(),
        email: _emailController.text.trim(),
        phone: _phoneController.text.trim(),
        password: _passwordController.text,
        passwordConfirmation: _confirmPasswordController.text,
        role: mappedRole,
      );
      if (mounted) {
        final user = result['user'] as UserModel;
        Helpers.showSnackBar(
          context,
          'Hongera, ${user.name}! Akaunti yako ya ${user.roleLabel} imefunguliwa kikamilifu.',
          isError: false,
        );
        Navigator.pushNamedAndRemoveUntil(context, '/dashboard', (route) => false);
      }
    } catch (e) {
      if (mounted) {
        Helpers.showSnackBar(context, e.toString(), isError: true, onRetry: _handleRegister);
      }
    } finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final isDark = _appSettings.isDark;
    
    return Scaffold(
      body: Container(
        decoration: BoxDecoration(
          color: isDark ? const Color(0xff1a1a1a) : Colors.white,
        ),
        child: SafeArea(
          child: Column(
            children: [
              Expanded(
                child: SingleChildScrollView(
                  padding: const EdgeInsets.all(24.0),
                  child: Form(
                    key: _formKey,
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.stretch,
                      children: [
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            // Language Selector
                            Container(
                              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                              decoration: BoxDecoration(
                                color: isDark ? Colors.white.withOpacity(0.1) : Colors.grey[200],
                                borderRadius: BorderRadius.circular(20),
                              ),
                              child: Row(
                                children: [
                                  Text(
                                    '🇹🇿',
                                    style: TextStyle(fontSize: 18),
                                  ),
                                  const SizedBox(width: 8),
                                  GestureDetector(
                                    onTap: () async {
                                      await _appSettings.toggleLocale();
                                      setState(() {});
                                    },
                                    child: Text(
                                      _appSettings.isEnglish ? 'EN' : 'SW',
                                      style: GoogleFonts.poppins(
                                        fontSize: 14,
                                        fontWeight: FontWeight.w600,
                                        color: isDark ? Colors.white : Colors.black87,
                                      ),
                                    ),
                                  ),
                                ],
                              ),
                            ),
                            // Theme Toggle
                            GestureDetector(
                              onTap: () async {
                                await _appSettings.toggleTheme();
                                setState(() {});
                              },
                              child: Container(
                                padding: const EdgeInsets.all(10),
                                decoration: BoxDecoration(
                                  color: isDark ? Colors.white.withOpacity(0.1) : Colors.grey[200],
                                  borderRadius: BorderRadius.circular(12),
                                ),
                                child: Icon(
                                  _appSettings.isDark ? Icons.light_mode : Icons.dark_mode,
                                  color: isDark ? Colors.white : Colors.black87,
                                  size: 20,
                                ),
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 30),
                        Text(
                          AppStrings.t(AppStrings.createAccount),
                          style: GoogleFonts.poppins(
                            fontSize: 32,
                            fontWeight: FontWeight.w700,
                            color: isDark ? Colors.white : Colors.black,
                          ),
                        ),
                        const SizedBox(height: 8),
                        Text(
                          AppStrings.t(AppStrings.createAccountSubtitle),
                          style: GoogleFonts.poppins(
                            fontSize: 14,
                            color: isDark ? Colors.white60 : Colors.grey[600],
                          ),
                        ),
                        const SizedBox(height: 32),
                        // Role Selection Cards
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              AppStrings.t(AppStrings.iAmA),
                              style: GoogleFonts.poppins(
                                fontSize: 14,
                                fontWeight: FontWeight.w500,
                                color: isDark ? Colors.white70 : Colors.grey[700],
                              ),
                            ),
                            const SizedBox(height: 12),
                            Row(
                              children: [
                                Expanded(
                                  child: _buildRoleCard(
                                    role: 'Landlord (Mwenye Nyumba)',
                                    icon: Icons.home_work_outlined,
                                    isDark: isDark,
                                  ),
                                ),
                                const SizedBox(width: 12),
                                Expanded(
                                  child: _buildRoleCard(
                                    role: 'Agent (Wakala wa Nyumba)',
                                    icon: Icons.business_center_outlined,
                                    isDark: isDark,
                                  ),
                                ),
                              ],
                            ),
                          ],
                        ),
                        const SizedBox(height: 20),
                        // Name Field
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              AppStrings.t(AppStrings.fullName),
                              style: GoogleFonts.poppins(
                                fontSize: 14,
                                fontWeight: FontWeight.w500,
                                color: isDark ? Colors.white70 : Colors.grey[700],
                              ),
                            ),
                            const SizedBox(height: 8),
                            Container(
                              decoration: BoxDecoration(
                                color: isDark ? Colors.white.withOpacity(0.1) : Colors.grey[100],
                                borderRadius: BorderRadius.circular(12),
                                border: Border.all(color: isDark ? Colors.white.withOpacity(0.2) : Colors.grey[300]!),
                              ),
                              child: TextFormField(
                                controller: _nameController,
                                validator: Validators.validateName,
                                style: GoogleFonts.poppins(
                                  color: isDark ? Colors.white : Colors.black,
                                  fontSize: 15,
                                ),
                                decoration: InputDecoration(
                                  hintText: 'John Doe',
                                  hintStyle: GoogleFonts.poppins(color: isDark ? Colors.white.withOpacity(0.4) : Colors.grey[400]),
                                  prefixIcon: Icon(Icons.person_outline, size: 20, color: AppColors.primary),
                                  border: InputBorder.none,
                                  contentPadding: const EdgeInsets.symmetric(
                                    horizontal: 16,
                                    vertical: 16,
                                  ),
                                ),
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 20),
                        // Email Field
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              AppStrings.t(AppStrings.emailAddress),
                              style: GoogleFonts.poppins(
                                fontSize: 14,
                                fontWeight: FontWeight.w500,
                                color: isDark ? Colors.white70 : Colors.grey[700],
                              ),
                            ),
                            const SizedBox(height: 8),
                            Container(
                              decoration: BoxDecoration(
                                color: isDark ? Colors.white.withOpacity(0.1) : Colors.grey[100],
                                borderRadius: BorderRadius.circular(12),
                                border: Border.all(color: isDark ? Colors.white.withOpacity(0.2) : Colors.grey[300]!),
                              ),
                              child: TextFormField(
                                controller: _emailController,
                                keyboardType: TextInputType.emailAddress,
                                validator: Validators.validateEmail,
                                style: GoogleFonts.poppins(
                                  color: isDark ? Colors.white : Colors.black,
                                  fontSize: 15,
                                ),
                                decoration: InputDecoration(
                                  hintText: 'name@company.com',
                                  hintStyle: GoogleFonts.poppins(color: isDark ? Colors.white.withOpacity(0.4) : Colors.grey[400]),
                                  prefixIcon: Icon(Icons.email_outlined, size: 20, color: AppColors.primary),
                                  border: InputBorder.none,
                                  contentPadding: const EdgeInsets.symmetric(
                                    horizontal: 16,
                                    vertical: 16,
                                  ),
                                ),
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 20),
                        // Phone Field
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              AppStrings.t(AppStrings.phoneNumber),
                              style: GoogleFonts.poppins(
                                fontSize: 14,
                                fontWeight: FontWeight.w500,
                                color: isDark ? Colors.white70 : Colors.grey[700],
                              ),
                            ),
                            const SizedBox(height: 8),
                            Container(
                              decoration: BoxDecoration(
                                color: isDark ? Colors.white.withOpacity(0.1) : Colors.grey[100],
                                borderRadius: BorderRadius.circular(12),
                                border: Border.all(color: isDark ? Colors.white.withOpacity(0.2) : Colors.grey[300]!),
                              ),
                              child: TextFormField(
                                controller: _phoneController,
                                keyboardType: TextInputType.phone,
                                validator: Validators.validatePhone,
                                style: GoogleFonts.poppins(
                                  color: isDark ? Colors.white : Colors.black,
                                  fontSize: 15,
                                ),
                                decoration: InputDecoration(
                                  hintText: '+255 700 000 000',
                                  hintStyle: GoogleFonts.poppins(color: isDark ? Colors.white.withOpacity(0.4) : Colors.grey[400]),
                                  prefixIcon: Icon(Icons.phone_outlined, size: 20, color: AppColors.primary),
                                  border: InputBorder.none,
                                  contentPadding: const EdgeInsets.symmetric(
                                    horizontal: 16,
                                    vertical: 16,
                                  ),
                                ),
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 20),
                        // Password Field
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              AppStrings.t(AppStrings.password),
                              style: GoogleFonts.poppins(
                                fontSize: 14,
                                fontWeight: FontWeight.w500,
                                color: isDark ? Colors.white70 : Colors.grey[700],
                              ),
                            ),
                            const SizedBox(height: 8),
                            Container(
                              decoration: BoxDecoration(
                                color: isDark ? Colors.white.withOpacity(0.1) : Colors.grey[100],
                                borderRadius: BorderRadius.circular(12),
                                border: Border.all(color: isDark ? Colors.white.withOpacity(0.2) : Colors.grey[300]!),
                              ),
                              child: TextFormField(
                                controller: _passwordController,
                                obscureText: _obscurePassword,
                                validator: Validators.validatePassword,
                                style: GoogleFonts.poppins(
                                  color: isDark ? Colors.white : Colors.black,
                                  fontSize: 15,
                                ),
                                decoration: InputDecoration(
                                  hintText: '••••••••',
                                  hintStyle: GoogleFonts.poppins(color: isDark ? Colors.white.withOpacity(0.4) : Colors.grey[400]),
                                  prefixIcon: Icon(Icons.lock_outlined, size: 20, color: AppColors.primary),
                                  border: InputBorder.none,
                                  contentPadding: const EdgeInsets.symmetric(
                                    horizontal: 16,
                                    vertical: 16,
                                  ),
                                  suffixIcon: IconButton(
                                    icon: Icon(
                                      _obscurePassword
                                          ? Icons.visibility_outlined
                                          : Icons.visibility_off_outlined,
                                      color: isDark ? Colors.white60 : Colors.grey,
                                    ),
                                    onPressed: () => setState(() => _obscurePassword = !_obscurePassword),
                                  ),
                                ),
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 20),
                        // Confirm Password Field
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              AppStrings.t(AppStrings.confirmPassword),
                              style: GoogleFonts.poppins(
                                fontSize: 14,
                                fontWeight: FontWeight.w500,
                                color: isDark ? Colors.white70 : Colors.grey[700],
                              ),
                            ),
                            const SizedBox(height: 8),
                            Container(
                              decoration: BoxDecoration(
                                color: isDark ? Colors.white.withOpacity(0.1) : Colors.grey[100],
                                borderRadius: BorderRadius.circular(12),
                                border: Border.all(color: isDark ? Colors.white.withOpacity(0.2) : Colors.grey[300]!),
                              ),
                              child: TextFormField(
                                controller: _confirmPasswordController,
                                obscureText: _obscureConfirmPassword,
                                validator: (value) => Validators.validateConfirmPassword(value, _passwordController.text),
                                style: GoogleFonts.poppins(
                                  color: isDark ? Colors.white : Colors.black,
                                  fontSize: 15,
                                ),
                                decoration: InputDecoration(
                                  hintText: '••••••••',
                                  hintStyle: GoogleFonts.poppins(color: isDark ? Colors.white.withOpacity(0.4) : Colors.grey[400]),
                                  prefixIcon: Icon(Icons.check_circle_outline, size: 20, color: AppColors.primary),
                                  border: InputBorder.none,
                                  contentPadding: const EdgeInsets.symmetric(
                                    horizontal: 16,
                                    vertical: 16,
                                  ),
                                  suffixIcon: IconButton(
                                    icon: Icon(
                                      _obscureConfirmPassword
                                          ? Icons.visibility_outlined
                                          : Icons.visibility_off_outlined,
                                      color: isDark ? Colors.white60 : Colors.grey,
                                    ),
                                    onPressed: () => setState(() => _obscureConfirmPassword = !_obscureConfirmPassword),
                                  ),
                                ),
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 16),
                        Row(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Transform.scale(
                              scale: 1.2,
                              child: Checkbox(
                                value: _agreeToTerms,
                                onChanged: (value) => setState(() => _agreeToTerms = value!),
                                activeColor: AppColors.primary,
                                materialTapTargetSize: MaterialTapTargetSize.shrinkWrap,
                                visualDensity: VisualDensity.compact,
                              ),
                            ),
                            const SizedBox(width: 8),
                            Expanded(
                              child: Wrap(
                                children: [
                                  Text(
                                    'I agree to the ',
                                    style: GoogleFonts.poppins(fontSize: 13, color: isDark ? Colors.white70 : Colors.grey[700]),
                                  ),
                                  GestureDetector(
                                    onTap: () {
                                      _showContentBottomSheet(
                                        context: context,
                                        title: 'Terms of Service',
                                        lastUpdated: 'January 1, 2025',
                                        effectiveDate: 'January 1, 2025',
                                        sections: _termsOfServiceContent,
                                      );
                                    },
                                    child: Text(
                                      'Terms of Service',
                                      style: GoogleFonts.poppins(
                                        fontSize: 13,
                                        color: AppColors.primary,
                                        fontWeight: FontWeight.w600,
                                        decoration: TextDecoration.underline,
                                      ),
                                    ),
                                  ),
                                  Text(
                                    ' and ',
                                    style: GoogleFonts.poppins(fontSize: 13, color: isDark ? Colors.white70 : Colors.grey[700]),
                                  ),
                                  GestureDetector(
                                    onTap: () {
                                      _showContentBottomSheet(
                                        context: context,
                                        title: 'Privacy Policy',
                                        lastUpdated: 'January 1, 2025',
                                        effectiveDate: 'January 1, 2025',
                                        sections: _privacyPolicyContent,
                                      );
                                    },
                                    child: Text(
                                      'Privacy Policy',
                                      style: GoogleFonts.poppins(
                                        fontSize: 13,
                                        color: AppColors.primary,
                                        fontWeight: FontWeight.w600,
                                        decoration: TextDecoration.underline,
                                      ),
                                    ),
                                  ),
                                ],
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 24),
                        SizedBox(
                          height: 56,
                          child: ElevatedButton(
                            onPressed: _isLoading ? null : _handleRegister,
                            style: ElevatedButton.styleFrom(
                              backgroundColor: AppColors.primary,
                              foregroundColor: Colors.black,
                              disabledBackgroundColor: AppColors.primary.withOpacity(0.6),
                              elevation: 0,
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(12),
                              ),
                            ),
                            child: _isLoading
                                ? const SizedBox(
                                    height: 24,
                                    width: 24,
                                    child: CircularProgressIndicator(
                                      strokeWidth: 3,
                                      color: Colors.black,
                                    ),
                                  )
                                : Text(
                                    AppStrings.t(AppStrings.createAccount),
                                    style: GoogleFonts.poppins(
                                      fontWeight: FontWeight.w600,
                                      fontSize: 16,
                                      color: Colors.black,
                                    ),
                                  ),
                                ),
                          ),
                        const SizedBox(height: 24),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Text(
                              AppStrings.t(AppStrings.alreadyHaveAccount),
                              style: GoogleFonts.poppins(color: isDark ? Colors.white60 : Colors.grey[600]),
                            ),
                            TextButton(
                              onPressed: () => Navigator.pushNamed(context, '/login'),
                              style: TextButton.styleFrom(
                                foregroundColor: AppColors.primary,
                                textStyle: GoogleFonts.poppins(
                                  fontWeight: FontWeight.w600,
                                ),
                              ),
                              child: Text(AppStrings.t(AppStrings.signIn)),
                            ),
                          ],
                        ),
                        const SizedBox(height: 32),
                      ],
                    ),
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
