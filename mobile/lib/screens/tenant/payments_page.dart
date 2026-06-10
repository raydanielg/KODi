import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../constants/app_colors.dart';
import '../../services/auth_service.dart';
import '../../utils/helpers.dart';

class PaymentsPage extends StatefulWidget {
  const PaymentsPage({super.key});

  @override
  State<PaymentsPage> createState() => _PaymentsPageState();
}

class _PaymentsPageState extends State<PaymentsPage> {
  final AuthService _authService = AuthService();
  bool _isEnglish = false;

  String _t(String sw, String en) {
    return _isEnglish ? en : sw;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xfff9fafb),
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back, color: Color(0xff111827)),
          onPressed: () => Navigator.pop(context),
        ),
        title: Text(
          _t('Malipo', 'Payments'),
          style: GoogleFonts.poppins(
            fontSize: 18,
            fontWeight: FontWeight.w700,
            color: const Color(0xff111827),
          ),
        ),
        actions: [
          // Language toggle
          Container(
            margin: const EdgeInsets.only(right: 16),
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
            decoration: BoxDecoration(
              color: const Color(0xfff3f4f6),
              borderRadius: BorderRadius.circular(20),
            ),
            child: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                GestureDetector(
                  onTap: () {
                    setState(() {
                      _isEnglish = false;
                    });
                  },
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                    decoration: BoxDecoration(
                      color: !_isEnglish ? AppColors.primary : Colors.transparent,
                      borderRadius: BorderRadius.circular(16),
                    ),
                    child: Text(
                      'SW',
                      style: GoogleFonts.poppins(
                        fontSize: 11,
                        fontWeight: !_isEnglish ? FontWeight.bold : FontWeight.w500,
                        color: !_isEnglish ? Colors.black : Colors.grey[600],
                      ),
                    ),
                  ),
                ),
                const SizedBox(width: 4),
                GestureDetector(
                  onTap: () {
                    setState(() {
                      _isEnglish = true;
                    });
                  },
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                    decoration: BoxDecoration(
                      color: _isEnglish ? AppColors.primary : Colors.transparent,
                      borderRadius: BorderRadius.circular(16),
                    ),
                    child: Text(
                      'EN',
                      style: GoogleFonts.poppins(
                        fontSize: 11,
                        fontWeight: _isEnglish ? FontWeight.bold : FontWeight.w500,
                        color: _isEnglish ? Colors.black : Colors.grey[600],
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          children: [
            // Summary Cards
            Row(
              children: [
                Expanded(
                  child: _summaryCard(
                    _t('Jumla Imelipwa', 'Total Paid'),
                    'TZS 1,350,000',
                    Icons.check_circle_outline,
                    Colors.green,
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: _summaryCard(
                    _t('Salio', 'Balance'),
                    'TZS 0',
                    Icons.account_balance_wallet_outlined,
                    AppColors.primary,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 24),

            // Quick Actions
            Row(
              children: [
                Expanded(
                  child: _quickActionButton(
                    _t('Lipa Kodi', 'Pay Rent'),
                    Icons.payment_rounded,
                    () {},
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: _quickActionButton(
                    _t('Pakua Risiti', 'Download Receipts'),
                    Icons.download_rounded,
                    () {},
                  ),
                ),
              ],
            ),
            const SizedBox(height: 24),

            // Payment History
            _sectionCard(
              title: _t('Historia ya Malipo', 'Payment History'),
              child: Column(
                children: [
                  _paymentItem(
                    'Machi 2025',
                    'TZS 450,000',
                    '01/03/2025',
                    true,
                  ),
                  const Divider(color: Color(0xffe5e7eb)),
                  _paymentItem(
                    'Februari 2025',
                    'TZS 450,000',
                    '01/02/2025',
                    true,
                  ),
                  const Divider(color: Color(0xffe5e7eb)),
                  _paymentItem(
                    'Januari 2025',
                    'TZS 450,000',
                    '01/01/2025',
                    true,
                  ),
                  const Divider(color: Color(0xffe5e7eb)),
                  _paymentItem(
                    'Desemba 2024',
                    'TZS 450,000',
                    '01/12/2024',
                    true,
                  ),
                  const Divider(color: Color(0xffe5e7eb)),
                  _paymentItem(
                    'Novemba 2024',
                    'TZS 450,000',
                    '01/11/2024',
                    true,
                  ),
                ],
              ),
            ),
            const SizedBox(height: 24),

            // Receipts Section
            _sectionCard(
              title: _t('Risiti', 'Receipts'),
              child: Column(
                children: [
                  _receiptItem(
                    'Risiti #12345',
                    'Machi 2025',
                    'TZS 450,000',
                  ),
                  const Divider(color: Color(0xffe5e7eb)),
                  _receiptItem(
                    'Risiti #12344',
                    'Februari 2025',
                    'TZS 450,000',
                  ),
                  const Divider(color: Color(0xffe5e7eb)),
                  _receiptItem(
                    'Risiti #12343',
                    'Januari 2025',
                    'TZS 450,000',
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _summaryCard(String title, String value, IconData icon, Color color) {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 8,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Column(
        children: [
          Icon(icon, color: color, size: 32),
          const SizedBox(height: 12),
          Text(
            title,
            style: GoogleFonts.poppins(
              fontSize: 12,
              color: Colors.grey[600],
            ),
          ),
          const SizedBox(height: 4),
          Text(
            value,
            style: GoogleFonts.poppins(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: const Color(0xff111827),
            ),
          ),
        ],
      ),
    );
  }

  Widget _quickActionButton(String title, IconData icon, VoidCallback onTap) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.all(20),
        decoration: BoxDecoration(
          color: AppColors.primary,
          borderRadius: BorderRadius.circular(16),
          boxShadow: [
            BoxShadow(
              color: AppColors.primary.withOpacity(0.3),
              blurRadius: 8,
              offset: const Offset(0, 2),
            ),
          ],
        ),
        child: Column(
          children: [
            Icon(icon, color: Colors.black, size: 28),
            const SizedBox(height: 12),
            Text(
              title,
              style: GoogleFonts.poppins(
                fontSize: 13,
                fontWeight: FontWeight.w600,
                color: Colors.black,
              ),
              textAlign: TextAlign.center,
            ),
          ],
        ),
      ),
    );
  }

  Widget _sectionCard({required String title, required Widget child}) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 8,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            title,
            style: GoogleFonts.poppins(
              fontSize: 16,
              fontWeight: FontWeight.bold,
              color: const Color(0xff111827),
            ),
          ),
          const SizedBox(height: 16),
          child,
        ],
      ),
    );
  }

  Widget _paymentItem(
    String month,
    String amount,
    String date,
    bool isPaid,
  ) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(
              color: isPaid ? Colors.green.withOpacity(0.1) : Colors.grey[100],
              borderRadius: BorderRadius.circular(10),
            ),
            child: Icon(
              isPaid ? Icons.check_circle : Icons.pending,
              color: isPaid ? Colors.green : Colors.grey[600],
              size: 20,
            ),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  month,
                  style: GoogleFonts.poppins(
                    fontSize: 14,
                    fontWeight: FontWeight.w600,
                    color: const Color(0xff111827),
                  ),
                ),
                Text(
                  date,
                  style: GoogleFonts.poppins(
                    fontSize: 12,
                    color: Colors.grey[500],
                  ),
                ),
              ],
            ),
          ),
          Text(
            amount,
            style: GoogleFonts.poppins(
              fontSize: 15,
              fontWeight: FontWeight.bold,
              color: AppColors.primary,
            ),
          ),
          const SizedBox(width: 12),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
            decoration: BoxDecoration(
              color: Colors.grey[100],
              borderRadius: BorderRadius.circular(8),
            ),
            child: Icon(
              Icons.download_outlined,
              size: 18,
              color: Colors.grey[600],
            ),
          ),
        ],
      ),
    );
  }

  Widget _receiptItem(
    String receiptNumber,
    String month,
    String amount,
  ) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(
              color: AppColors.primary.withOpacity(0.1),
              borderRadius: BorderRadius.circular(10),
            ),
            child: Icon(
              Icons.receipt_long_outlined,
              color: AppColors.primary,
              size: 20,
            ),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  receiptNumber,
                  style: GoogleFonts.poppins(
                    fontSize: 14,
                    fontWeight: FontWeight.w600,
                    color: const Color(0xff111827),
                  ),
                ),
                Text(
                  month,
                  style: GoogleFonts.poppins(
                    fontSize: 12,
                    color: Colors.grey[500],
                  ),
                ),
              ],
            ),
          ),
          Text(
            amount,
            style: GoogleFonts.poppins(
              fontSize: 15,
              fontWeight: FontWeight.bold,
              color: const Color(0xff111827),
            ),
          ),
          const SizedBox(width: 12),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
            decoration: BoxDecoration(
              color: AppColors.primary,
              borderRadius: BorderRadius.circular(8),
            ),
            child: Icon(
              Icons.visibility_outlined,
              size: 18,
              color: Colors.black,
            ),
          ),
        ],
      ),
    );
  }
}
