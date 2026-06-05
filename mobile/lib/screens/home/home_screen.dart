import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../constants/app_assets.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen>
    with SingleTickerProviderStateMixin {
  late AnimationController _controller;
  late Animation<double> _animation;

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
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        decoration: const BoxDecoration(
          image: DecorationImage(
            image: AssetImage(AppAssets.onboardingBg),
            fit: BoxFit.cover,
          ),
        ),
        child: Container(
          decoration: BoxDecoration(
            gradient: LinearGradient(
              begin: Alignment.topCenter,
              end: Alignment.bottomCenter,
              colors: [
                Colors.white.withOpacity(0.7),
                Colors.white.withOpacity(0.3),
                Colors.transparent,
              ],
              stops: const [0.0, 0.3, 0.5],
            ),
          ),
          child: SafeArea(
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 24),
            child: Column(
              children: [

                /// HEADER
                Padding(
                  padding: const EdgeInsets.only(top: 15),
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Image.asset(
                        AppAssets.appIcon,
                        width: 120,
                      ),

                      IconButton(
                        onPressed: () {},
                        icon: const Icon(Icons.menu),
                      ),
                    ],
                  ),
                ),

                const SizedBox(height: 30),

                /// CONTENT
                Expanded(
                  child: Stack(
                    children: [
                      /// TEXT
                      Positioned(
                        top: 0,
                        left: 0,
                        child: SizedBox(
                          width: 230,
                          child: Column(
                            crossAxisAlignment:
                                CrossAxisAlignment.start,
                            children: [

                              Text(
                                "WE BUILD\nYOUR VISION",
                                style: GoogleFonts.inter(
                                  fontSize: 40,
                                  fontWeight: FontWeight.w800,
                                  color: Colors.black87,
                                  height: 1.1,
                                ),
                              ),

                              Text(
                                "TO REALITY",
                                style: GoogleFonts.inter(
                                  fontSize: 40,
                                  fontWeight: FontWeight.w800,
                                  color: const Color(0xffF4B63E),
                                  height: 1.1,
                                ),
                              ),

                              const SizedBox(height: 20),

                              Text(
                                "Trusted construction services for residential, commercial & industrial projects.",
                                style: GoogleFonts.inter(
                                  color: Colors.black54,
                                  fontSize: 15,
                                  height: 1.5,
                                ),
                              ),

                              const SizedBox(height: 25),

                              /// DOTS
                              Row(
                                children: [
                                  Container(
                                    width: 20,
                                    height: 6,
                                    decoration: BoxDecoration(
                                      color: Color(0xffF4B63E),
                                      borderRadius:
                                          BorderRadius.circular(10),
                                    ),
                                  ),

                                  const SizedBox(width: 6),

                                  Container(
                                    width: 6,
                                    height: 6,
                                    decoration: BoxDecoration(
                                      color: Colors.grey.shade300,
                                      shape: BoxShape.circle,
                                    ),
                                  ),

                                  const SizedBox(width: 6),

                                  Container(
                                    width: 6,
                                    height: 6,
                                    decoration: BoxDecoration(
                                      color: Colors.grey.shade300,
                                      shape: BoxShape.circle,
                                    ),
                                  ),
                                ],
                              ),
                            ],
                          ),
                        ),
                      ),

                      /// BUTTON
                      Positioned(
                        left: 0,
                        right: 0,
                        bottom: 25,
                        child: Center(
                          child: Container(
                            width: 280,
                            height: 65,
                            decoration: BoxDecoration(
                              color: const Color(0xffF4B63E),
                              borderRadius:
                                  BorderRadius.circular(40),
                            ),
                            child: Row(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: [
                                Text(
                                  "GET STARTED",
                                  style: GoogleFonts.inter(
                                    fontSize: 18,
                                    fontWeight: FontWeight.w700,
                                    color: Colors.black,
                                  ),
                                ),
                                const SizedBox(width: 10),
                                const Icon(
                                  Icons.arrow_forward,
                                  color: Colors.black,
                                  size: 20,
                                ),
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
        ),
      ),
    );
  }
}