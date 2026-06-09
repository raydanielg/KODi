import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../constants/app_colors.dart';
import '../../constants/app_strings.dart';

class PrivacyPolicyScreen extends StatelessWidget {
  const PrivacyPolicyScreen({super.key});

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
                      style: GoogleFonts.poppins(
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
                                'Privacy Policy',
                                style: GoogleFonts.poppins(
                                  fontSize: 28,
                                  fontWeight: FontWeight.w800,
                                  color: const Color(0xff111827),
                                ),
                              ),
                              const SizedBox(height: 8),
                              Text(
                                'Last Updated: January 1, 2025',
                                style: GoogleFonts.poppins(
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
                                    style: GoogleFonts.poppins(
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
                          '1. Introduction',
                          'Welcome to ${AppStrings.appName} ("we," "our," or "us"). We are committed to protecting your personal information and your right to privacy. If you have any questions or concerns about our policy, or our practices with regard to your personal information, please contact us at support@manna.com.\n\nThis privacy policy applies to all information collected by ${AppStrings.appName}, including through our website, mobile applications, and related services (collectively, the "Services"). By using the Services, you agree to the collection and use of information in accordance with this policy.',
                        ),
                        
                        _buildSection(
                          '2. Information We Collect',
                          'We collect several types of information from and about users of our Services, including information:\n\n• Personal Identification Information: Name, email address, phone number, and other contact information you provide when creating an account or using our Services.\n• Account Information: Username, password, and other authentication credentials. We store this information securely and never share your password with third parties.\n• Property Information: Details about properties you list, rent, or inquire about, including addresses, photos, descriptions, and rental terms.\n• Payment Information: Payment method details processed through secure third-party payment processors. We do not store complete credit card numbers on our servers.\n• Usage Information: Information about how you use our Services, including pages visited, features used, time spent, and other behavioral data.\n• Device Information: IP address, browser type, operating system, device identifiers, and other technical information about your device.\n• Location Information: Approximate location based on IP address or precise location if you enable location services on your device.',
                        ),
                        
                        _buildSection(
                          '3. How We Use Your Information',
                          'We use the information we collect for various purposes, including to:\n\n• Provide, maintain, and improve our Services\n• Process transactions and send related information\n• Send technical notices and support messages\n• Respond to comments, questions, and customer service requests\n• Send marketing and promotional communications (with your consent)\n• Monitor and analyze trends, usage, and activities\n• Detect, prevent, and address technical issues and fraud\n• Comply with legal obligations and enforce our terms\n• Personalize your experience and deliver relevant content\n• Facilitate communication between landlords and tenants\n• Verify identity and prevent fraudulent activities',
                        ),
                        
                        _buildSection(
                          '4. Information Sharing',
                          'We may share information we collect with third parties in the following circumstances:\n\n4.1 Service Providers\nWe may share your information with third-party service providers who perform services on our behalf, such as payment processing, data hosting, analytics, and customer support. These service providers have access to your information only to perform specific tasks and are contractually obligated to protect your information.\n\n4.2 Business Transfers\nIf we are involved in a merger, acquisition, or sale of assets, your information may be transferred as part of the transaction. We will notify you of any such transfer and will continue to protect your information as described in this policy.\n\n4.3 Legal Requirements\nWe may disclose your information if required to do so by law or in response to valid requests by public authorities (e.g., a court or government agency). We may also disclose information to protect our rights, property, or safety, or that of our users or others.\n\n4.4 Other Users\nWhen you use our rental platform, certain information may be shared with other users to facilitate transactions. For example, landlords may see tenant contact information for rental applications, and tenants may see property details and landlord contact information for inquiries.',
                        ),
                        
                        _buildSection(
                          '5. Data Security',
                          'We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. These measures include:\n\n• Encryption of sensitive data in transit and at rest\n• Secure authentication protocols and password hashing\n• Regular security audits and vulnerability assessments\n• Access controls and authentication for our systems\n• Employee training on data protection practices\n• Secure data storage and backup procedures\n\nHowever, no method of transmission over the Internet or electronic storage is completely secure. While we strive to use commercially acceptable means to protect your information, we cannot guarantee its absolute security.',
                        ),
                        
                        _buildSection(
                          '6. Data Retention',
                          'We retain your personal information for as long as necessary to provide our Services and fulfill the purposes outlined in this policy, unless a longer retention period is required or permitted by law. We may retain certain information after you close your account as required by law or for legitimate business purposes.\n\nWhen we no longer need your information, we will securely delete or anonymize it. However, some information may remain in our backup systems for a reasonable period for disaster recovery purposes.',
                        ),
                        
                        _buildSection(
                          '7. Your Rights and Choices',
                          'Depending on your location, you may have certain rights regarding your personal information, including:\n\n• Access: Request access to the personal information we hold about you\n• Correction: Request correction of inaccurate or incomplete information\n• Deletion: Request deletion of your personal information\n• Portability: Request transfer of your information to another service\n• Objection: Object to processing of your information\n• Restriction: Request restriction of processing of your information\n• Withdraw Consent: Withdraw consent for processing where consent is the legal basis\n\nTo exercise these rights, please contact us at support@manna.com. We will respond to your request within 30 days in accordance with applicable law.',
                        ),
                        
                        _buildSection(
                          '8. Children\'s Privacy',
                          'Our Services are not intended for children under the age of 18. We do not knowingly collect personal information from children under 18. If you are a parent or guardian and believe your child has provided us with personal information, please contact us, and we will delete such information.',
                        ),
                        
                        _buildSection(
                          '9. International Data Transfers',
                          'Your information may be transferred to and processed in countries other than your country of residence. When we transfer your information internationally, we ensure appropriate safeguards are in place to protect your information in accordance with this policy and applicable data protection laws.',
                        ),
                        
                        _buildSection(
                          '10. Changes to This Policy',
                          'We may update this privacy policy from time to time. We will notify you of any material changes by posting the new policy on our website and updating the "Last Updated" date. We encourage you to review this policy periodically to stay informed about how we protect your information.',
                        ),
                        
                        _buildSection(
                          '11. Contact Us',
                          'If you have any questions about this privacy policy or our data practices, please contact us:\n\n• Email: support@manna.com\n• Phone: +254 700 000 000\n• Address: Nairobi, Kenya',
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
            style: GoogleFonts.poppins(
              fontSize: 20,
              fontWeight: FontWeight.w700,
              color: const Color(0xff111827),
            ),
          ),
          const SizedBox(height: 12),
          Text(
            content,
            style: GoogleFonts.poppins(
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
