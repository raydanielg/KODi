import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../constants/app_colors.dart';
import '../../constants/app_strings.dart';

class TermsOfServiceScreen extends StatelessWidget {
  const TermsOfServiceScreen({super.key});

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
                                'Terms of Service',
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
                                    'Effective Date: January 1, 2025',
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
                          '1. Agreement to Terms',
                          'By accessing or using ${AppStrings.appName} ("we," "our," or "us"), you agree to be bound by these Terms of Service ("Terms"). If you disagree with any part of these terms, you may not access the Service.\n\nThese Terms, together with our Privacy Policy, govern your use of ${AppStrings.appName} and all related services, features, and functionality (collectively, the "Service").',
                        ),
                        
                        _buildSection(
                          '2. Description of Service',
                          '${AppStrings.appName} is a long-term rental platform that connects landlords with tenants across Africa. Our Service includes:\n\n• Property listing and management tools for landlords\n• Property search and inquiry features for tenants\n• Rental application and payment processing\n• Communication tools between landlords and tenants\n• Review and rating systems\n• Account management and user profiles\n\nWe reserve the right to modify, suspend, or discontinue the Service at any time without prior notice.',
                        ),
                        
                        _buildSection(
                          '3. User Accounts',
                          '3.1 Account Creation\nTo use certain features of the Service, you must create an account. You agree to provide accurate, complete, and current information during registration. You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account.\n\n3.2 Account Responsibilities\nYou agree to:\n• Notify us immediately of any unauthorized use of your account\n• Ensure that you exit from your account at the end of each session\n• Not use anyone else\'s account at any time\n• Keep your password secure and not share it with others\n\n3.3 Account Termination\nWe reserve the right to suspend or terminate your account at any time for any reason, including but not limited to violation of these Terms. You may also terminate your account at any time by contacting us or using account deletion features.',
                        ),
                        
                        _buildSection(
                          '4. User Conduct',
                          'You agree not to use the Service for any purpose that is unlawful or prohibited by these Terms. You agree not to:\n\n• Use the Service for any fraudulent or deceptive purpose\n• Post false, misleading, or inaccurate information\n• Impersonate any person or entity or misrepresent your affiliation\n• Violate any applicable laws or regulations\n• Infringe upon the rights of others\n• Transmit viruses, malware, or other harmful code\n• Interfere with or disrupt the Service or servers\n• Attempt to gain unauthorized access to our systems\n• Harass, abuse, or harm other users\n• Discriminate based on race, religion, gender, or other protected characteristics',
                        ),
                        
                        _buildSection(
                          '5. Property Listings',
                          '5.1 Landlord Responsibilities\nAs a landlord listing properties on our platform, you agree to:\n• Provide accurate and complete property information\n• Upload current and representative photos of the property\n• Disclose all material facts about the property\n• Respond to tenant inquiries in a timely manner\n• Comply with all applicable housing laws and regulations\n• Maintain the property in habitable condition\n\n5.2 Property Content\nYou retain ownership of any content you post on our platform. By posting content, you grant us a worldwide, non-exclusive, royalty-free license to use, display, and distribute such content for the purpose of providing the Service.',
                        ),
                        
                        _buildSection(
                          '6. Rental Transactions',
                          '6.1 Payment Processing\nWe facilitate rental payments through third-party payment processors. By using our payment features, you agree to the terms and conditions of these third-party providers. We are not responsible for any errors or issues arising from payment processing.\n\n6.2 Rental Agreements\n${AppStrings.appName} is not a party to rental agreements between landlords and tenants. We provide a platform for connection and communication but do not create, enforce, or guarantee rental agreements. All rental terms are negotiated directly between landlords and tenants.\n\n6.3 Dispute Resolution\nWe are not responsible for resolving disputes between landlords and tenants. We may provide tools to facilitate communication but do not mediate or arbitrate disputes. Parties are responsible for resolving their own disputes through appropriate legal channels.',
                        ),
                        
                        _buildSection(
                          '7. Intellectual Property',
                          '7.1 Our Intellectual Property\nThe Service and its original content, features, and functionality are owned by ${AppStrings.appName} and are protected by international copyright, trademark, and other intellectual property laws.\n\n7.2 Your Intellectual Property\nYou retain ownership of content you submit to the Service. By submitting content, you grant us the license described in Section 5.2. You represent and warrant that you have the right to grant such license.\n\n7.3 Trademarks\n${AppStrings.appName} and related graphics, logos, and service names are trademarks of ${AppStrings.appName}. You may not use our trademarks without our prior written consent.',
                        ),
                        
                        _buildSection(
                          '8. Privacy',
                          'Your use of the Service is also governed by our Privacy Policy, which describes how we collect, use, and protect your personal information. Please review our Privacy Policy carefully before using the Service.',
                        ),
                        
                        _buildSection(
                          '9. Disclaimers and Warranties',
                          'THE SERVICE IS PROVIDED ON AN "AS IS" AND "AS AVAILABLE" BASIS WITHOUT WARRANTIES OF ANY KIND, EITHER EXPRESS OR IMPLIED. WE DISCLAIM ALL WARRANTIES, INCLUDING BUT NOT LIMITED TO:\n\n• Merchantability and fitness for a particular purpose\n• Non-infringement of third-party rights\n• Accuracy, reliability, or availability of the Service\n• Security of the Service or protection against unauthorized access\n• Freedom from viruses or other harmful components\n\nWe do not guarantee that the Service will be uninterrupted, secure, or error-free. We are not responsible for any damage to your device or loss of data resulting from use of the Service.',
                        ),
                        
                        _buildSection(
                          '10. Limitation of Liability',
                          'TO THE MAXIMUM EXTENT PERMITTED BY LAW, ${AppStrings.appName} SHALL NOT BE LIABLE FOR ANY INDIRECT, INCIDENTAL, SPECIAL, CONSEQUENTIAL, OR PUNITIVE DAMAGES, INCLUDING BUT NOT LIMITED TO LOSS OF PROFITS, DATA, USE, OR OTHER INTANGIBLE LOSSES, RESULTING FROM:\n\n• Your access to or use of or inability to access or use the Service\n• Any conduct or content of any third party on the Service\n• Any content obtained from the Service\n• Unauthorized access, use, or alteration of your transmissions or content\n\nOur total liability to you for all claims shall not exceed the amount you paid to us, if any, for using the Service during the twelve months preceding the claim.',
                        ),
                        
                        _buildSection(
                          '11. Indemnification',
                          'You agree to indemnify, defend, and hold harmless ${AppStrings.appName} and its affiliates, officers, directors, employees, and agents from any claims, damages, losses, liabilities, and expenses arising from:\n\n• Your use of the Service\n• Your violation of these Terms\n• Your violation of any third-party rights\n• Content you post or transmit through the Service',
                        ),
                        
                        _buildSection(
                          '12. Governing Law',
                          'These Terms shall be governed by and construed in accordance with the laws of Kenya, without regard to its conflict of law provisions. Any disputes arising under these Terms shall be subject to the exclusive jurisdiction of the courts of Kenya.',
                        ),
                        
                        _buildSection(
                          '13. Changes to Terms',
                          'We reserve the right to modify these Terms at any time. We will notify you of material changes by posting the updated Terms on our website and updating the "Last Updated" date. Your continued use of the Service after such changes constitutes your acceptance of the new Terms.',
                        ),
                        
                        _buildSection(
                          '14. Contact Information',
                          'If you have any questions about these Terms, please contact us:\n\n• Email: support@manna.com\n• Phone: +254 700 000 000\n• Address: Nairobi, Kenya',
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
