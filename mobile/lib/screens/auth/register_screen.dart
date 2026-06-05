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
                painter: DottedPatternPainter(),
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
                            DropdownButtonFormField<String>(
                              value: _selectedRole.isEmpty ? null : _selectedRole,
                              isExpanded: true,
                              decoration: const InputDecoration(
                                labelText: 'I am a',
                                prefixIcon: Icon(Icons.person_outline, size: 20),
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
                            TextFormField(
                              controller: _nameController,
                              validator: Validators.validateName,
                              decoration: const InputDecoration(
                                labelText: 'Full name',
                                prefixIcon: Icon(Icons.person_outline, size: 20),
                              ),
                            ),
                            const SizedBox(height: 20),
                            TextFormField(
                              controller: _emailController,
                              keyboardType: TextInputType.emailAddress,
                              validator: Validators.validateEmail,
                              decoration: const InputDecoration(
                                labelText: 'Email address',
                                prefixIcon: Icon(Icons.email_outlined, size: 20),
                              ),
                            ),
                            const SizedBox(height: 20),
                            TextFormField(
                              controller: _phoneController,
                              keyboardType: TextInputType.phone,
                              validator: Validators.validatePhone,
                              decoration: const InputDecoration(
                                labelText: 'Phone number',
                                prefixIcon: Icon(Icons.phone_outlined, size: 20),
                              ),
                            ),
                            const SizedBox(height: 20),
                            TextFormField(
                              controller: _passwordController,
                              obscureText: _obscurePassword,
                              validator: Validators.validatePassword,
                              decoration: InputDecoration(
                                labelText: 'Password',
                                prefixIcon: const Icon(Icons.lock_outlined, size: 20),
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
                            const SizedBox(height: 20),
                            TextFormField(
                              controller: _confirmPasswordController,
                              obscureText: _obscureConfirmPassword,
                              validator: (value) => Validators.validateConfirmPassword(value, _passwordController.text),
                              decoration: InputDecoration(
                                labelText: 'Confirm password',
                                prefixIcon: const Icon(Icons.check_circle_outline, size: 20),
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
                                        onTap: () => Navigator.pushNamed(context, '/terms-of-service'),
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
                                        onTap: () => Navigator.pushNamed(context, '/privacy-policy'),
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

class DottedPatternPainter extends CustomPainter {
  @override
  void paint(Canvas canvas, Size size) {
    final paint = Paint()
      ..color = AppColors.primary.withOpacity(0.15)
      ..style = PaintingStyle.fill;

    final dotRadius = 2.0;
    final spacing = 20.0;

    for (double x = 0; x < size.width; x += spacing) {
      for (double y = 0; y < size.height; y += spacing) {
        canvas.drawCircle(Offset(x, y), dotRadius, paint);
      }
    }
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) => false;
}
