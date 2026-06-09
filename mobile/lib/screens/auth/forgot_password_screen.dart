import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../constants/app_colors.dart';
import '../../constants/app_strings.dart';

class ForgotPasswordScreen extends StatefulWidget {
  const ForgotPasswordScreen({super.key});

  @override
  State<ForgotPasswordScreen> createState() => _ForgotPasswordScreenState();
}

class _ForgotPasswordScreenState extends State<ForgotPasswordScreen> {
  final _formKey = GlobalKey<FormState>();
  final _emailController = TextEditingController();

  @override
  void dispose() {
    _emailController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        decoration: const BoxDecoration(
          color: Colors.white,
        ),
        child: Stack(
          children: [
            // Premium faint background
            Positioned.fill(
              child: CustomPaint(
                painter: PremiumAuthBackgroundPainter(),
              ),
            ),
            // Content
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
                            // Back button
                            Align(
                              alignment: Alignment.centerLeft,
                              child: IconButton(
                                icon: const Icon(Icons.arrow_back, color: Colors.black),
                                onPressed: () => Navigator.pop(context),
                              ),
                            ),
                            const SizedBox(height: 20),
                            // Header
                            Row(
                              children: [
                                Text(
                                  '🔐',
                                  style: GoogleFonts.poppins(
                                    fontSize: 40,
                                  ),
                                ),
                                const SizedBox(width: 12),
                                Expanded(
                                  child: Text(
                                    'Forgot Password',
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
                              'Enter your email address and we\'ll send you a link to reset your password.',
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
                                  'Your Email',
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
                                  child: TextField(
                                    controller: _emailController,
                                    keyboardType: TextInputType.emailAddress,
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
                                      filled: false,
                                    ),
                                  ),
                                ),
                              ],
                            ),
                            const SizedBox(height: 24),
                            // Send Reset Link Button
                            ElevatedButton(
                              onPressed: () {
                                if (_formKey.currentState!.validate()) {
                                  // TODO: Implement forgot password logic
                                  ScaffoldMessenger.of(context).showSnackBar(
                                    const SnackBar(
                                      content: Text('Password reset link sent to your email'),
                                    ),
                                  );
                                }
                              },
                              style: ElevatedButton.styleFrom(
                                backgroundColor: AppColors.primary,
                                foregroundColor: Colors.white,
                                padding: const EdgeInsets.symmetric(vertical: 18),
                                shape: RoundedRectangleBorder(
                                  borderRadius: BorderRadius.circular(12),
                                ),
                                elevation: 0,
                              ),
                              child: Text(
                                'Send Reset Link',
                                style: GoogleFonts.poppins(
                                  fontWeight: FontWeight.w600,
                                  fontSize: 16,
                                ),
                              ),
                            ),
                            const SizedBox(height: 24),
                            // Back to Login Link
                            Row(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: [
                                Text(
                                  'Remember your password?',
                                  style: GoogleFonts.poppins(
                                    color: Colors.grey[600],
                                  ),
                                ),
                                TextButton(
                                  onPressed: () {
                                    Navigator.pop(context);
                                  },
                                  child: Text(
                                    'Sign in',
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
