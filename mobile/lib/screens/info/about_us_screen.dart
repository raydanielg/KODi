import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../constants/app_colors.dart';
import '../../constants/app_strings.dart';

class AboutUsScreen extends StatelessWidget {
  const AboutUsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.primary,
      body: Container(
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
            colors: [
              AppColors.primary,
              Color(0xff059669),
              Color(0xff047857),
              Color(0xff065f46),
            ],
          ),
        ),
        child: SafeArea(
          child: Column(
            children: [
              // Back button
              Padding(
                padding: const EdgeInsets.all(16.0),
                child: Row(
                  children: [
                    IconButton(
                      onPressed: () => Navigator.pop(context),
                      icon: const Icon(Icons.arrow_back, color: Colors.white),
                    ),
                    const SizedBox(width: 8),
                    Text(
                      'Back',
                      style: GoogleFonts.inter(
                        color: Colors.white,
                        fontSize: 16,
                        fontWeight: FontWeight.w500,
                      ),
                    ),
                  ],
                ),
              ),
              // A4 Paper
              Expanded(
                child: SingleChildScrollView(
                  padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                  child: Container(
                    width: double.infinity,
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(8),
                      boxShadow: [
                        BoxShadow(
                          color: Colors.black.withOpacity(0.3),
                          blurRadius: 80,
                          offset: const Offset(0, 25),
                        ),
                      ],
                    ),
                    padding: const EdgeInsets.all(24),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        // Header
                        Center(
                          child: Column(
                            children: [
                              const SizedBox(height: 16),
                              Text(
                                'About Us',
                                style: GoogleFonts.inter(
                                  fontSize: 28,
                                  fontWeight: FontWeight.w800,
                                  color: const Color(0xff111827),
                                ),
                              ),
                              const SizedBox(height: 8),
                              Text(
                                'Last Updated: January 1, 2025',
                                style: GoogleFonts.inter(
                                  fontSize: 14,
                                  color: const Color(0xff6b7280),
                                ),
                              ),
                              const SizedBox(height: 24),
                              Container(
                                width: double.infinity,
                                padding: const EdgeInsets.all(16),
                                decoration: BoxDecoration(
                                  color: const Color(0xfff3f4f6),
                                  borderRadius: BorderRadius.circular(8),
                                ),
                                child: Center(
                                  child: Text(
                                    'Welcome to ${AppStrings.appName}',
                                    style: GoogleFonts.inter(
                                      fontSize: 16,
                                      fontWeight: FontWeight.w600,
                                      color: const Color(0xff374151),
                                    ),
                                  ),
                                ),
                              ),
                            ],
                          ),
                        ),
                        const SizedBox(height: 32),
                        
                        // Content Sections
                        _buildSection(
                          'Our Mission',
                          '${AppStrings.appName} is a modern long-term rental platform designed to connect tenants with landlords across Africa. Our mission is to make the rental process simple, secure, and transparent for everyone involved.',
                        ),
                        
                        _buildSection(
                          'What We Do',
                          'We provide a comprehensive platform that helps tenants find their perfect home while giving landlords the tools they need to manage their properties efficiently. Our platform features advanced search capabilities, secure payment processing, and direct communication between parties.',
                        ),
                        
                        _buildSection(
                          'Our Values',
                          'At ${AppStrings.appName}, we believe in:\n\n• Transparency in all transactions\n• Security of user data and payments\n• Accessibility for users across Africa\n• Innovation in rental technology\n• Community building through trusted connections',
                        ),
                        
                        _buildSection(
                          'Our Team',
                          'Our team consists of experienced professionals from the real estate, technology, and customer service industries. We are passionate about revolutionizing the rental market in Africa and creating opportunities for both tenants and landlords.',
                        ),
                        
                        _buildSection(
                          'Our Vision',
                          'We envision a future where finding and renting a home in Africa is as easy as booking a hotel room. We aim to become the leading rental platform across the continent, known for our reliability, innovation, and commitment to user satisfaction.',
                        ),
                        
                        _buildSection(
                          'Contact Us',
                          'Have questions or feedback? We\'d love to hear from you:\n\n• Email: support@manna.com\n• Phone: +254 700 000 000\n• Address: Nairobi, Kenya',
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

  Widget _buildSection(String title, String content) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 24),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            title,
            style: GoogleFonts.inter(
              fontSize: 20,
              fontWeight: FontWeight.w700,
              color: const Color(0xff111827),
            ),
          ),
          const SizedBox(height: 12),
          Text(
            content,
            style: GoogleFonts.inter(
              fontSize: 15,
              color: const Color(0xff4b5563),
              height: 1.8,
            ),
          ),
        ],
      ),
    );
  }
}
