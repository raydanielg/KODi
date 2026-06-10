import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../constants/app_colors.dart';
import '../../constants/app_strings.dart';
import '../../models/user_model.dart';
import '../../services/auth_service.dart';
import '../../services/api_service.dart';
import '../../utils/validators.dart';
import '../../utils/helpers.dart';
import 'forgot_password_screen.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _formKey = GlobalKey<FormState>();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  final _authService = AuthService();
  bool _obscurePassword = true;
  bool _rememberMe = false;
  bool _isLoading = false;

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  Future<void> _handleLogin() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isLoading = true);

    try {
      final result = await _authService.login(
        _emailController.text.trim(),
        _passwordController.text,
      );
      if (mounted) {
        final user = result['user'] as UserModel;
        Helpers.showSnackBar(
          context,
          'Karibu tena, ${user.name}! Umefanikiwa kuingia kama ${user.roleLabel}.',
          isError: false,
        );
        Navigator.pushNamedAndRemoveUntil(context, '/dashboard', (route) => false);
      }
    } catch (e) {
      if (mounted) {
        print('❌ Login Error: $e');
        // Extract the error message - it should already be formatted by _handleException
        String errorMessage = e.toString();
        
        // If it's not already a user-friendly message, provide a default
        if (!errorMessage.contains('Tafadhali') && 
            !errorMessage.contains('Imeshindikana') && 
            !errorMessage.contains('Muunganisho')) {
          errorMessage = 'Imeshindikana kuingia. Tafadhali angalia mtandao wako na jaribu tena.';
        }
        
        Helpers.showSnackBar(context, errorMessage, isError: true);
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
                            Row(
                              children: [
                                Text(
                                  '\u{1F44B}',
                                  style: GoogleFonts.poppins(fontSize: 40),
                                ),
                                const SizedBox(width: 12),
                                Expanded(
                                  child: Text(
                                    AppStrings.welcomeBack,
                                    style: GoogleFonts.poppins(
                                      fontSize: 32,
                                      fontWeight: FontWeight.w700,
                                      color: Colors.black,
                                    ),
                                  ),
                                ),
                              ],
                            ),
                            const SizedBox(height: 8),
                            Text(
                              'Sign in to your account to continue your journey with us.',
                              style: GoogleFonts.poppins(
                                fontSize: 14,
                                color: Colors.grey[600],
                              ),
                            ),
                            const SizedBox(height: 32),
                            // Email Field
                            Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  AppStrings.emailAddress,
                                  style: GoogleFonts.poppins(
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
                                    style: GoogleFonts.poppins(
                                      color: const Color(0xff1f2937),
                                      fontSize: 15,
                                    ),
                                    decoration: InputDecoration(
                                      hintText: 'name@company.com',
                                      hintStyle: GoogleFonts.poppins(
                                        color: const Color(0xff6b7280),
                                      ),
                                      prefixIcon: const Icon(
                                        Icons.email_outlined,
                                        color: Colors.grey,
                                        size: 20,
                                      ),
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
                                  AppStrings.password,
                                  style: GoogleFonts.poppins(
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
                                    style: GoogleFonts.poppins(
                                      color: const Color(0xff1f2937),
                                      fontSize: 15,
                                    ),
                                    decoration: InputDecoration(
                                      hintText: '••••••••',
                                      hintStyle: GoogleFonts.poppins(
                                        color: const Color(0xff6b7280),
                                      ),
                                      prefixIcon: const Icon(
                                        Icons.lock_outlined,
                                        color: Colors.grey,
                                        size: 20,
                                      ),
                                      suffixIcon: IconButton(
                                        icon: Icon(
                                          _obscurePassword
                                              ? Icons.visibility_outlined
                                              : Icons.visibility_off_outlined,
                                          color: Colors.grey,
                                          size: 20,
                                        ),
                                        onPressed: () => setState(() => _obscurePassword = !_obscurePassword),
                                      ),
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
                            const SizedBox(height: 16),
                            Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                Row(
                                  children: [
                                    Checkbox(
                                      value: _rememberMe,
                                      onChanged: (value) => setState(() => _rememberMe = value!),
                                      activeColor: AppColors.primary,
                                    ),
                                    Text(
                                      'Remember me',
                                      style: GoogleFonts.poppins(
                                        fontSize: 14,
                                        color: Colors.grey[700],
                                      ),
                                    ),
                                  ],
                                ),
                                TextButton(
                                  onPressed: () => Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                      builder: (context) => const ForgotPasswordScreen(),
                                    ),
                                  ),
                                  child: Text(
                                    'Forgot Password?',
                                    style: GoogleFonts.poppins(
                                      color: AppColors.primary,
                                      fontWeight: FontWeight.w600,
                                      fontSize: 14,
                                    ),
                                  ),
                                ),
                              ],
                            ),
                            const SizedBox(height: 24),
                            SizedBox(
                              height: 56,
                              child: ElevatedButton(
                                onPressed: _isLoading ? null : _handleLogin,
                                style: ElevatedButton.styleFrom(
                                  backgroundColor: AppColors.primary,
                                  foregroundColor: Colors.white,
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
                                          color: Colors.white,
                                        ),
                                      )
                                    : Text(
                                        AppStrings.signIn,
                                        style: GoogleFonts.poppins(
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
                                  AppStrings.dontHaveAccount,
                                  style: GoogleFonts.poppins(color: Colors.grey[600]),
                                ),
                                TextButton(
                                  onPressed: () => Navigator.pushNamed(context, '/register'),
                                  child: Text(
                                    'Create account',
                                    style: GoogleFonts.poppins(
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
