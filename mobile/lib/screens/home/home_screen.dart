import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class HomeScreen extends StatelessWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: SafeArea(
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
                      "assets/logo.png",
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

                    /// IMAGE
                    Positioned(
                      bottom: 0,
                      left: 0,
                      right: 0,
                      child: ClipRRect(
                        borderRadius: BorderRadius.circular(30),
                        child: Image.asset(
                          "assets/building.jpg",
                          fit: BoxFit.cover,
                          height: 500,
                        ),
                      ),
                    ),

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
                                color: Colors.black,
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
                                color: Colors.grey.shade600,
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
                            children: [

                              const Expanded(
                                child: Center(
                                  child: Text(
                                    "Get Started",
                                    style: TextStyle(
                                      fontSize: 18,
                                      fontWeight: FontWeight.w700,
                                    ),
                                  ),
                                ),
                              ),

                              Container(
                                width: 55,
                                height: 55,
                                margin: const EdgeInsets.all(5),
                                decoration: const BoxDecoration(
                                  color: Colors.black,
                                  shape: BoxShape.circle,
                                ),
                                child: const Icon(
                                  Icons.arrow_forward,
                                  color: Colors.white,
                                ),
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
    );
  }
}