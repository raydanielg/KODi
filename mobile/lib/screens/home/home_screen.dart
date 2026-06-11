import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../constants/app_assets.dart';
import '../../constants/app_colors.dart';
import '../../constants/app_strings.dart';
import '../../constants/app_routes.dart';
import '../info/about_us_screen.dart';
import '../info/privacy_policy_screen.dart';
import '../info/terms_of_service_screen.dart';
import '../auth/login_screen.dart';
import '../../services/auth_service.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen>
    with SingleTickerProviderStateMixin {
  final AuthService _authService = AuthService();
  late AnimationController _controller;
  late Animation<double> _animation;
  late PageController _pageController;
  int _currentPage = 0;
  bool _isCheckingAuth = true;

  final List<Map<String, String>> _onboardingData = [
    {
      'title': 'FIND YOUR\nPERFECT HOME',
      'subtitle': 'ACROSS AFRICA',
      'description': 'Discover long-term rental properties that match your lifestyle and budget.',
    },
    {
      'title': 'CONNECT WITH\nLANDLORDS',
      'subtitle': 'DIRECTLY',
      'description': 'Skip the middleman and connect directly with property owners.',
    },
    {
      'title': 'SECURE YOUR\nDREAM SPACE',
      'subtitle': 'TODAY',
      'description': 'Easy booking, secure payments, and hassle-free rentals.',
    },
  ];

  @override
  void initState() {
    super.initState();
    _controller = AnimationController(
      duration: const Duration(milliseconds: 500),
      vsync: this,
    )..repeat(reverse: true);
    _animation = Tween<double>(begin: 0, end: 10).animate(
      CurvedAnimation(parent: _controller, curve: Curves.easeInOut),
    );
    _pageController = PageController();
    _checkAuthAndNavigate();
  }

  Future<void> _checkAuthAndNavigate() async {
    final loggedIn = await _authService.loadSavedAuth();

    if (loggedIn && mounted) {
      print('✅ Auto-login active. Redirecting to dashboard.');
      Navigator.pushReplacementNamed(context, AppRoutes.dashboard);
      return;
    }

    if (mounted) {
      setState(() => _isCheckingAuth = false);
    }
  }

  @override
  void dispose() {
    _controller.dispose();
    _pageController.dispose();
    super.dispose();
  }

  Widget _buildMenuItem({
    required IconData icon,
    required String title,
    required VoidCallback onTap,
  }) {
    return ListTile(
      leading: Container(
        width: 44,
        height: 44,
        decoration: BoxDecoration(
          color: AppColors.primary.withOpacity(0.15),
          borderRadius: BorderRadius.circular(12),
          border: Border.all(
            color: AppColors.primary.withOpacity(0.2),
            width: 1,
          ),
        ),
        child: Icon(
          icon,
          color: AppColors.primary,
          size: 22,
        ),
      ),
      title: Text(
        title,
        style: GoogleFonts.poppins(
          color: Colors.white,
          fontSize: 15,
          fontWeight: FontWeight.w600,
        ),
      ),
      trailing: Icon(
        Icons.chevron_right,
        color: Colors.grey.shade600,
        size: 20,
      ),
      onTap: onTap,
    );
  }

  @override
  Widget build(BuildContext context) {
    if (_isCheckingAuth) {
      return Scaffold(
        backgroundColor: Colors.white,
        body: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Image.asset(
                AppAssets.appIcon,
                width: 100,
              ),
              const SizedBox(height: 24),
              const CircularProgressIndicator(color: AppColors.primary),
            ],
          ),
        ),
      );
    }

    return Scaffold(
      drawer: Drawer(
        backgroundColor: const Color(0xff0a0a0a),
        child: SafeArea(
          child: Column(
            children: [
              // Header
              Container(
                decoration: BoxDecoration(
                  color: const Color(0xff1e1e1e),
                ),
                padding: const EdgeInsets.all(24.0),
                child: Row(
                  children: [
                    Container(
                      width: 70,
                      height: 70,
                      decoration: BoxDecoration(
                        color: const Color(0xff2a2a2a),
                        borderRadius: BorderRadius.circular(20),
                        border: Border.all(color: Colors.white.withOpacity(0.08)),
                        boxShadow: [
                          BoxShadow(
                            color: Colors.black.withOpacity(0.15),
                            blurRadius: 8,
                            offset: const Offset(0, 2),
                          ),
                        ],
                      ),
                      child: Padding(
                        padding: const EdgeInsets.all(14.0),
                        child: Image.asset(
                          AppAssets.appIcon,
                          fit: BoxFit.contain,
                        ),
                      ),
                    ),
                    const SizedBox(width: 20),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            AppStrings.appName,
                            style: GoogleFonts.poppins(
                              fontSize: 26,
                              fontWeight: FontWeight.w900,
                              color: Colors.white,
                              letterSpacing: 1,
                              height: 1.1,
                            ),
                          ),
                          const SizedBox(height: 6),
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                            decoration: BoxDecoration(
                              color: Colors.white.withOpacity(0.1),
                              borderRadius: BorderRadius.circular(12),
                              border: Border.all(
                                color: Colors.white.withOpacity(0.15),
                                width: 1,
                              ),
                            ),
                            child: Text(
                              'Find your perfect home',
                              style: GoogleFonts.poppins(
                                fontSize: 11,
                                color: Colors.white,
                                fontWeight: FontWeight.w600,
                                letterSpacing: 0.5,
                              ),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
              const Divider(color: Colors.grey, height: 1),
              // Menu Items
              Expanded(
                child: ListView(
                  padding: const EdgeInsets.symmetric(vertical: 16),
                  children: [
                    _buildMenuItem(
                      icon: Icons.home_outlined,
                      title: 'Home',
                      onTap: () {
                        Navigator.pop(context);
                      },
                    ),
                    _buildMenuItem(
                      icon: Icons.search_outlined,
                      title: 'Search Properties',
                      onTap: () {
                        Navigator.pop(context);
                      },
                    ),
                    _buildMenuItem(
                      icon: Icons.favorite_outline,
                      title: 'Saved Properties',
                      onTap: () {
                        Navigator.pop(context);
                      },
                    ),
                    const SizedBox(height: 8),
                    const Padding(
                      padding: EdgeInsets.symmetric(horizontal: 24, vertical: 8),
                      child: Text(
                        'INFORMATION',
                        style: TextStyle(
                          color: Colors.grey,
                          fontSize: 12,
                          fontWeight: FontWeight.w600,
                          letterSpacing: 1,
                        ),
                      ),
                    ),
                    _buildMenuItem(
                      icon: Icons.info_outline,
                      title: 'About Us',
                      onTap: () {
                        Navigator.pop(context);
                        Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (context) => const AboutUsScreen(),
                          ),
                        );
                      },
                    ),
                    _buildMenuItem(
                      icon: Icons.lock_outline,
                      title: 'Privacy Policy',
                      onTap: () {
                        Navigator.pop(context);
                        Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (context) => const PrivacyPolicyScreen(),
                          ),
                        );
                      },
                    ),
                    _buildMenuItem(
                      icon: Icons.description_outlined,
                      title: 'Terms of Service',
                      onTap: () {
                        Navigator.pop(context);
                        Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (context) => const TermsOfServiceScreen(),
                          ),
                        );
                      },
                    ),
                    _buildMenuItem(
                      icon: Icons.help_outline,
                      title: 'FAQ',
                      onTap: () {
                        Navigator.pop(context);
                      },
                    ),
                  ],
                ),
              ),
              // Footer
              Container(
                decoration: BoxDecoration(
                  color: const Color(0xff0a0a0a),
                ),
                padding: const EdgeInsets.all(24.0),
                child: Column(
                  children: [
                    Text(
                      '© 2024 ${AppStrings.appName}',
                      style: GoogleFonts.poppins(
                        color: Colors.grey,
                        fontSize: 12,
                      ),
                      textAlign: TextAlign.center,
                    ),
                    const SizedBox(height: 8),
                    Text(
                      'Version 1.0.0',
                      style: GoogleFonts.poppins(
                        color: Colors.grey.shade600,
                        fontSize: 10,
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
      body: Container(
        decoration: const BoxDecoration(
          color: Color(0xff0a0a0a),
        ),
        child: SafeArea(
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 24),
              child: Column(
                children: [
                  Padding(
                    padding: const EdgeInsets.only(top: 15),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Container(
                          padding: const EdgeInsets.all(12),
                          decoration: BoxDecoration(
                            color: const Color(0xff1e1e1e),
                            borderRadius: BorderRadius.circular(16),
                            border: Border.all(
                              color: Colors.white.withOpacity(0.08),
                              width: 1,
                            ),
                          ),
                          child: Image.asset(
                            AppAssets.appIcon,
                            width: 40,
                          ),
                        ),
                        Builder(
                          builder: (context) => Container(
                            decoration: BoxDecoration(
                              color: const Color(0xff1e1e1e),
                              borderRadius: BorderRadius.circular(12),
                              border: Border.all(
                                color: Colors.white.withOpacity(0.08),
                                width: 1,
                              ),
                            ),
                            child: IconButton(
                              onPressed: () {
                                Scaffold.of(context).openDrawer();
                              },
                              icon: const Icon(
                                Icons.menu,
                                color: Colors.white,
                              ),
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 40),
                  Expanded(
                    child: Column(
                      children: [
                        // Page View
                        Expanded(
                          child: PageView.builder(
                            controller: _pageController,
                            onPageChanged: (index) {
                              setState(() {
                                _currentPage = index;
                              });
                            },
                            itemCount: _onboardingData.length,
                            itemBuilder: (context, index) {
                              return Padding(
                                padding: const EdgeInsets.only(top: 20),
                                child: SizedBox(
                                  width: 280,
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      Container(
                                        padding: const EdgeInsets.all(20),
                                        decoration: BoxDecoration(
                                          color: const Color(0xff1e1e1e),
                                          borderRadius: BorderRadius.circular(20),
                                          border: Border.all(color: Colors.white.withOpacity(0.08)),
                                          boxShadow: [
                                            BoxShadow(
                                              color: Colors.black.withOpacity(0.15),
                                              blurRadius: 8,
                                              offset: const Offset(0, 2),
                                            ),
                                          ],
                                        ),
                                        child: Text(
                                          _onboardingData[index]['title']!,
                                          style: GoogleFonts.poppins(
                                            fontSize: 42,
                                            fontWeight: FontWeight.w800,
                                            color: Colors.white,
                                            height: 1.1,
                                            letterSpacing: -1,
                                          ),
                                        ),
                                      ),
                                      const SizedBox(height: 16),
                                      Text(
                                        _onboardingData[index]['subtitle']!,
                                        style: GoogleFonts.poppins(
                                          fontSize: 42,
                                          fontWeight: FontWeight.w800,
                                          color: AppColors.primary,
                                          height: 1.1,
                                          letterSpacing: -1,
                                        ),
                                      ),
                                      const SizedBox(height: 24),
                                      Container(
                                        padding: const EdgeInsets.all(20),
                                        decoration: BoxDecoration(
                                          color: const Color(0xff1e1e1e),
                                          borderRadius: BorderRadius.circular(16),
                                          border: Border.all(color: Colors.white.withOpacity(0.08)),
                                          boxShadow: [
                                            BoxShadow(
                                              color: Colors.black.withOpacity(0.15),
                                              blurRadius: 8,
                                              offset: const Offset(0, 2),
                                            ),
                                          ],
                                        ),
                                        child: Text(
                                          _onboardingData[index]['description']!,
                                          style: GoogleFonts.poppins(
                                            color: Colors.white70,
                                            fontSize: 16,
                                            height: 1.6,
                                            fontWeight: FontWeight.w400,
                                          ),
                                        ),
                                      ),
                                      const SizedBox(height: 30),
                                      Row(
                                        children: List.generate(
                                          _onboardingData.length,
                                          (dotIndex) {
                                            return GestureDetector(
                                              onTap: () {
                                                _pageController.animateToPage(
                                                  dotIndex,
                                                  duration: const Duration(milliseconds: 300),
                                                  curve: Curves.easeInOut,
                                                );
                                              },
                                              child: Container(
                                                margin: const EdgeInsets.only(right: 8),
                                                width: _currentPage == dotIndex ? 32 : 8,
                                                height: 8,
                                                decoration: BoxDecoration(
                                                  color: _currentPage == dotIndex
                                                      ? AppColors.primary
                                                      : Colors.white.withOpacity(0.3),
                                                  borderRadius: _currentPage == dotIndex
                                                      ? BorderRadius.circular(10)
                                                      : null,
                                                  shape: _currentPage == dotIndex
                                                      ? BoxShape.rectangle
                                                      : BoxShape.circle,
                                                  boxShadow: _currentPage == dotIndex
                                                      ? [
                                                          BoxShadow(
                                                            color: AppColors.primary.withOpacity(0.5),
                                                            blurRadius: 8,
                                                            offset: const Offset(0, 2),
                                                          ),
                                                        ]
                                                      : null,
                                                ),
                                              ),
                                            );
                                          },
                                        ),
                                      ),
                                    ],
                                  ),
                                ),
                              );
                            },
                          ),
                        ),
                        // Button at bottom
                        Padding(
                          padding: const EdgeInsets.only(bottom: 30),
                          child: Center(
                            child: Container(
                              decoration: BoxDecoration(
                                color: AppColors.primary,
                                borderRadius: BorderRadius.circular(50),
                                boxShadow: [
                                  BoxShadow(
                                    color: AppColors.primary.withOpacity(0.3),
                                    blurRadius: 12,
                                    offset: const Offset(0, 4),
                                  ),
                                ],
                              ),
                              child: ElevatedButton(
                                onPressed: () {
                                  Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                      builder: (context) => const LoginScreen(),
                                    ),
                                  );
                                },
                                style: ElevatedButton.styleFrom(
                                  backgroundColor: Colors.transparent,
                                  foregroundColor: Colors.black,
                                  padding: const EdgeInsets.symmetric(horizontal: 48, vertical: 22),
                                  shape: RoundedRectangleBorder(
                                    borderRadius: BorderRadius.circular(50),
                                  ),
                                  elevation: 0,
                                  shadowColor: Colors.transparent,
                                ),
                                child: Row(
                                  mainAxisSize: MainAxisSize.min,
                                  mainAxisAlignment: MainAxisAlignment.center,
                                  children: [
                                    Text(
                                      "GET STARTED",
                                      style: GoogleFonts.poppins(
                                        fontSize: 18,
                                        fontWeight: FontWeight.w800,
                                        color: Colors.black,
                                        letterSpacing: 1,
                                      ),
                                    ),
                                    const SizedBox(width: 12),
                                    AnimatedBuilder(
                                      animation: _animation,
                                      builder: (context, child) {
                                        return Transform.translate(
                                          offset: Offset(_animation.value, 0),
                                          child: const Icon(
                                            Icons.arrow_forward,
                                            color: Colors.black,
                                            size: 22,
                                          ),
                                        );
                                      },
                                    ),
                                  ],
                                ),
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
          ),
        ),
      ),
    );
  }
}