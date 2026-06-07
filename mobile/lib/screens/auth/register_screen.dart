import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../constants/app_colors.dart';
import '../../constants/app_strings.dart';
import '../../services/auth_service.dart';
import '../../utils/validators.dart';
import '../../utils/helpers.dart';

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

  final List<String> _roles = [
    'Tenant (Mpangaji)',
    'Landlord (Mmiliki wa Nyumba)',
    'Agent (Wakala wa Nyumba)',
    'Support Agent',
    'Maintenance Staff',
    'Accountant',
  ];

  final Map<String, String> _roleDescriptions = {
    'Tenant (Mpangaji)': 'Tenant: Looking for a long-term rental property',
    'Landlord (Mmiliki wa Nyumba)': 'Landlord: Own properties to rent out',
    'Agent (Wakala wa Nyumba)': 'Agent: Manage properties on behalf of landlords',
    'Support Agent': 'Support Agent: Help users with their questions',
    'Maintenance Staff': 'Maintenance Staff: Handle property repairs',
    'Accountant': 'Accountant: Manage financial records',
  };

  String _mapRoleValue(String label) {
    if (label.startsWith('Tenant')) return 'tenant';
    if (label.startsWith('Landlord')) return 'landlord';
    if (label.startsWith('Agent')) return 'agent';
    if (label.startsWith('Support')) return 'support';
    if (label.startsWith('Maintenance')) return 'maintenance';
    if (label.startsWith('Accountant')) return 'accountant';
    return 'tenant';
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
                          style: GoogleFonts.inter(
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
                          style: GoogleFonts.inter(
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
                            style: GoogleFonts.inter(
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
                                  style: GoogleFonts.inter(
                                    fontSize: 16,
                                    fontWeight: FontWeight.w700,
                                    color: const Color(0xff111827),
                                  ),
                                ),
                                const SizedBox(height: 8),
                                Text(
                                  section['content']!,
                                  style: GoogleFonts.inter(
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
                                style: GoogleFonts.inter(
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
      await _authService.register(
        name: _nameController.text.trim(),
        email: _emailController.text.trim(),
        phone: _phoneController.text.trim(),
        password: _passwordController.text,
        passwordConfirmation: _confirmPasswordController.text,
        role: _mapRoleValue(_selectedRole),
      );
      if (mounted) {
        Navigator.pushNamedAndRemoveUntil(context, '/dashboard', (route) => false);
      }
    } catch (e) {
      if (mounted) {
        Helpers.showSnackBar(context, e.toString(), isError: true);
      }
    } finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        decoration: const BoxDecoration(color: Colors.white),
        child: Stack(
          children: [
            Positioned.fill(
              child: CustomPaint(
                painter: PremiumAuthBackgroundPainter(),
              ),
            ),
            SafeArea(
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
                            Align(
                              alignment: Alignment.centerLeft,
                              child: IconButton(
                                icon: const Icon(Icons.arrow_back, color: Colors.black),
                                onPressed: () => Navigator.pop(context),
                              ),
                            ),
                            const SizedBox(height: 20),
                            Text(
                              AppStrings.createAccount,
                              style: GoogleFonts.inter(
                                fontSize: 32,
                                fontWeight: FontWeight.w700,
                                color: Colors.black,
                              ),
                            ),
                            const SizedBox(height: 8),
                            Text(
                              'Start your journey today by creating a new account.',
                              style: GoogleFonts.inter(
                                fontSize: 14,
                                color: Colors.grey[600],
                              ),
                            ),
                            const SizedBox(height: 32),
                            // Role Dropdown Field
                            Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  'I am a',
                                  style: GoogleFonts.inter(
                                    fontSize: 14,
                                    fontWeight: FontWeight.w500,
                                    color: Colors.grey[700],
                                  ),
                                ),
                                const SizedBox(height: 8),
                                Container(
                                  decoration: BoxDecoration(
                                    color: const Color(0xfff3f4f6),
                                    borderRadius: BorderRadius.circular(6),
                                    border: Border.all(color: const Color(0xffd1d5db)),
                                    boxShadow: [
                                      BoxShadow(
                                        color: Colors.black.withOpacity(0.05),
                                        blurRadius: 4,
                                        offset: const Offset(0, 1),
                                      ),
                                    ],
                                  ),
                                  child: DropdownButtonFormField<String>(
                                    value: _selectedRole.isEmpty ? null : _selectedRole,
                                    isExpanded: true,
                                    decoration: const InputDecoration(
                                      prefixIcon: Icon(Icons.person_outline, size: 20, color: Colors.grey),
                                      border: InputBorder.none,
                                      contentPadding: EdgeInsets.symmetric(
                                        horizontal: 16,
                                        vertical: 12,
                                      ),
                                    ),
                                    hint: Text(
                                      'Select your role',
                                      style: GoogleFonts.inter(color: const Color(0xff6b7280)),
                                    ),
                                    icon: const Icon(Icons.keyboard_arrow_down, color: Colors.grey, size: 20),
                                    items: _roles.map((String role) {
                                      return DropdownMenuItem<String>(
                                        value: role,
                                        child: Text(role, style: const TextStyle(fontSize: 14)),
                                      );
                                    }).toList(),
                                    onChanged: (String? newValue) {
                                      setState(() => _selectedRole = newValue!);
                                    },
                                  ),
                                ),
                              ],
                            ),
                            if (_selectedRole.isNotEmpty && _roleDescriptions.containsKey(_selectedRole))
                              Padding(
                                padding: const EdgeInsets.only(top: 8),
                                child: Text(
                                  _roleDescriptions[_selectedRole]!,
                                  style: GoogleFonts.inter(
                                    fontSize: 12,
                                    color: Colors.grey[600],
                                    fontStyle: FontStyle.italic,
                                  ),
                                ),
                              ),
                            const SizedBox(height: 20),
                            // Name Field
                            Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  'Full name',
                                  style: GoogleFonts.inter(
                                    fontSize: 14,
                                    fontWeight: FontWeight.w500,
                                    color: Colors.grey[700],
                                  ),
                                ),
                                const SizedBox(height: 8),
                                Container(
                                  decoration: BoxDecoration(
                                    color: const Color(0xfff3f4f6),
                                    borderRadius: BorderRadius.circular(6),
                                    border: Border.all(color: const Color(0xffd1d5db)),
                                    boxShadow: [
                                      BoxShadow(
                                        color: Colors.black.withOpacity(0.05),
                                        blurRadius: 4,
                                        offset: const Offset(0, 1),
                                      ),
                                    ],
                                  ),
                                  child: TextFormField(
                                    controller: _nameController,
                                    validator: Validators.validateName,
                                    decoration: const InputDecoration(
                                      hintText: 'John Doe',
                                      prefixIcon: Icon(Icons.person_outline, size: 20, color: Colors.grey),
                                      border: InputBorder.none,
                                      contentPadding: EdgeInsets.symmetric(
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
                                  'Email address',
                                  style: GoogleFonts.inter(
                                    fontSize: 14,
                                    fontWeight: FontWeight.w500,
                                    color: Colors.grey[700],
                                  ),
                                ),
                                const SizedBox(height: 8),
                                Container(
                                  decoration: BoxDecoration(
                                    color: const Color(0xfff3f4f6),
                                    borderRadius: BorderRadius.circular(6),
                                    border: Border.all(color: const Color(0xffd1d5db)),
                                    boxShadow: [
                                      BoxShadow(
                                        color: Colors.black.withOpacity(0.05),
                                        blurRadius: 4,
                                        offset: const Offset(0, 1),
                                      ),
                                    ],
                                  ),
                                  child: TextFormField(
                                    controller: _emailController,
                                    keyboardType: TextInputType.emailAddress,
                                    validator: Validators.validateEmail,
                                    decoration: const InputDecoration(
                                      hintText: 'name@company.com',
                                      prefixIcon: Icon(Icons.email_outlined, size: 20, color: Colors.grey),
                                      border: InputBorder.none,
                                      contentPadding: EdgeInsets.symmetric(
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
                                  'Phone number',
                                  style: GoogleFonts.inter(
                                    fontSize: 14,
                                    fontWeight: FontWeight.w500,
                                    color: Colors.grey[700],
                                  ),
                                ),
                                const SizedBox(height: 8),
                                Container(
                                  decoration: BoxDecoration(
                                    color: const Color(0xfff3f4f6),
                                    borderRadius: BorderRadius.circular(6),
                                    border: Border.all(color: const Color(0xffd1d5db)),
                                    boxShadow: [
                                      BoxShadow(
                                        color: Colors.black.withOpacity(0.05),
                                        blurRadius: 4,
                                        offset: const Offset(0, 1),
                                      ),
                                    ],
                                  ),
                                  child: TextFormField(
                                    controller: _phoneController,
                                    keyboardType: TextInputType.phone,
                                    validator: Validators.validatePhone,
                                    decoration: const InputDecoration(
                                      hintText: '+255 700 000 000',
                                      prefixIcon: Icon(Icons.phone_outlined, size: 20, color: Colors.grey),
                                      border: InputBorder.none,
                                      contentPadding: EdgeInsets.symmetric(
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
                                  'Password',
                                  style: GoogleFonts.inter(
                                    fontSize: 14,
                                    fontWeight: FontWeight.w500,
                                    color: Colors.grey[700],
                                  ),
                                ),
                                const SizedBox(height: 8),
                                Container(
                                  decoration: BoxDecoration(
                                    color: const Color(0xfff3f4f6),
                                    borderRadius: BorderRadius.circular(6),
                                    border: Border.all(color: const Color(0xffd1d5db)),
                                    boxShadow: [
                                      BoxShadow(
                                        color: Colors.black.withOpacity(0.05),
                                        blurRadius: 4,
                                        offset: const Offset(0, 1),
                                      ),
                                    ],
                                  ),
                                  child: TextFormField(
                                    controller: _passwordController,
                                    obscureText: _obscurePassword,
                                    validator: Validators.validatePassword,
                                    decoration: InputDecoration(
                                      hintText: '••••••••',
                                      prefixIcon: const Icon(Icons.lock_outlined, size: 20, color: Colors.grey),
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
                                          color: Colors.grey,
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
                                  'Confirm password',
                                  style: GoogleFonts.inter(
                                    fontSize: 14,
                                    fontWeight: FontWeight.w500,
                                    color: Colors.grey[700],
                                  ),
                                ),
                                const SizedBox(height: 8),
                                Container(
                                  decoration: BoxDecoration(
                                    color: const Color(0xfff3f4f6),
                                    borderRadius: BorderRadius.circular(6),
                                    border: Border.all(color: const Color(0xffd1d5db)),
                                    boxShadow: [
                                      BoxShadow(
                                        color: Colors.black.withOpacity(0.05),
                                        blurRadius: 4,
                                        offset: const Offset(0, 1),
                                      ),
                                    ],
                                  ),
                                  child: TextFormField(
                                    controller: _confirmPasswordController,
                                    obscureText: _obscureConfirmPassword,
                                    validator: (value) => Validators.validateConfirmPassword(value, _passwordController.text),
                                    decoration: InputDecoration(
                                      hintText: '••••••••',
                                      prefixIcon: const Icon(Icons.check_circle_outline, size: 20, color: Colors.grey),
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
                                          color: Colors.grey,
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
                                        style: GoogleFonts.inter(fontSize: 13, color: Colors.grey[700]),
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
                                          style: GoogleFonts.inter(
                                            fontSize: 13,
                                            color: AppColors.primary,
                                            fontWeight: FontWeight.w600,
                                            decoration: TextDecoration.underline,
                                          ),
                                        ),
                                      ),
                                      Text(
                                        ' and ',
                                        style: GoogleFonts.inter(fontSize: 13, color: Colors.grey[700]),
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
                                          style: GoogleFonts.inter(
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
                                child: _isLoading
                                    ? const SizedBox(
                                        height: 24,
                                        width: 24,
                                        child: CircularProgressIndicator(
                                          strokeWidth: 2.5,
                                          color: Colors.white,
                                        ),
                                      )
                                    : Text(
                                        'Create account',
                                        style: GoogleFonts.inter(
                                          fontWeight: FontWeight.w600,
                                          fontSize: 16,
                                        ),
                                      ),
                              ),
                            ),
                            const SizedBox(height: 24),
                            Row(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: [
                                Text(
                                  AppStrings.alreadyHaveAccount,
                                  style: GoogleFonts.inter(color: Colors.grey[600]),
                                ),
                                TextButton(
                                  onPressed: () => Navigator.pushNamed(context, '/login'),
                                  child: Text(
                                    AppStrings.signIn,
                                    style: GoogleFonts.inter(
                                      color: AppColors.primary,
                                      fontWeight: FontWeight.w600,
                                    ),
                                  ),
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
          ],
        ),
      ),
    );
  }
}

class PremiumAuthBackgroundPainter extends CustomPainter {
  @override
  void paint(Canvas canvas, Size size) {
    // 1. Draw a soft premium gradient background
    final rect = Offset.zero & size;
    final gradient = LinearGradient(
      begin: Alignment.topLeft,
      end: Alignment.bottomRight,
      colors: [
        Colors.white,
        const Color(0xfff0fdf4), // Extremely light emerald/teal
        const Color(0xffecfdf5), // Slightly darker soft emerald/teal
      ],
      stops: const [0.0, 0.7, 1.0],
    );
    final bgPaint = Paint()..shader = gradient.createShader(rect);
    canvas.drawRect(rect, bgPaint);

    // 2. Draw beautiful faint abstract circular rings/waves
    final wavePaint = Paint()
      ..color = const Color(0xff10b981).withOpacity(0.04) // Faint AppColors.primary
      ..style = PaintingStyle.stroke
      ..strokeWidth = 1.5;

    // Top Right Ring
    canvas.drawCircle(Offset(size.width * 0.9, size.height * 0.05), 120, wavePaint);
    canvas.drawCircle(Offset(size.width * 0.9, size.height * 0.05), 180, wavePaint);
    canvas.drawCircle(Offset(size.width * 0.9, size.height * 0.05), 240, wavePaint);

    // Bottom Left Ring
    canvas.drawCircle(Offset(size.width * 0.1, size.height * 0.85), 150, wavePaint);
    canvas.drawCircle(Offset(size.width * 0.1, size.height * 0.85), 220, wavePaint);
    canvas.drawCircle(Offset(size.width * 0.1, size.height * 0.85), 290, wavePaint);

    // 3. Draw very faint, elegant dots
    final dotPaint = Paint()
      ..color = const Color(0xff10b981).withOpacity(0.035)
      ..style = PaintingStyle.fill;

    const dotRadius = 1.5;
    const spacing = 24.0;

    for (double x = 12; x < size.width; x += spacing) {
      for (double y = 12; y < size.height; y += spacing) {
        // Skip dots near the middle-top where main text headers are to keep it clean
        if (y > size.height * 0.15 && y < size.height * 0.45) {
          continue;
        }
        canvas.drawCircle(Offset(x, y), dotRadius, dotPaint);
      }
    }
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) => false;
}
