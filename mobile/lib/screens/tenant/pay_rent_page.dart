import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../constants/app_colors.dart';
import '../../services/auth_service.dart';
import '../../utils/helpers.dart';

class PayRentPage extends StatefulWidget {
  const PayRentPage({super.key});

  @override
  State<PayRentPage> createState() => _PayRentPageState();
}

class _PayRentPageState extends State<PayRentPage> {
  final AuthService _authService = AuthService();
  int _selectedMonths = 1;
  String _selectedMethod = 'Transfer';
  bool _isProcessing = false;
  bool _isEnglish = false;

  String _t(String sw, String en) {
    return _isEnglish ? en : sw;
  }

  @override
  Widget build(BuildContext context) {
    final user = _authService.currentUser;

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
          _t('Lipa Kodi', 'Pay Rent'),
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
            // Rent Amount Card
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(24),
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  colors: [
                    AppColors.primary,
                    AppColors.primaryDark,
                  ],
                ),
                borderRadius: BorderRadius.circular(20),
                boxShadow: [
                  BoxShadow(
                    color: AppColors.primary.withOpacity(0.3),
                    blurRadius: 12,
                    offset: const Offset(0, 4),
                  ),
                ],
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    _t('Kodi ya Mwezi Huu', 'This Month\'s Rent'),
                    style: GoogleFonts.poppins(
                      color: Colors.white70,
                      fontSize: 14,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    'TZS 450,000',
                    style: GoogleFonts.poppins(
                      color: Colors.white,
                      fontSize: 32,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    'Unit A-12',
                    style: GoogleFonts.poppins(
                      color: Colors.white,
                      fontSize: 14,
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 24),

            // Number of Months
            _buildSectionCard(
              title: _t('Idadi ya Miezi', 'Number of Months'),
              child: Row(
                children: List.generate(6, (index) {
                  final months = index + 1;
                  final isSelected = _selectedMonths == months;
                  return Expanded(
                    child: GestureDetector(
                      onTap: () {
                        setState(() {
                          _selectedMonths = months;
                        });
                      },
                      child: Container(
                        margin: EdgeInsets.only(
                          right: index < 5 ? 8 : 0,
                        ),
                        padding: const EdgeInsets.symmetric(vertical: 16),
                        decoration: BoxDecoration(
                          color: isSelected ? AppColors.primary : Colors.grey[100],
                          borderRadius: BorderRadius.circular(12),
                        ),
                        child: Text(
                          '$months',
                          textAlign: TextAlign.center,
                          style: GoogleFonts.poppins(
                            color: isSelected ? Colors.black : Colors.grey[700],
                            fontWeight: isSelected ? FontWeight.bold : FontWeight.w500,
                            fontSize: 16,
                          ),
                        ),
                      ),
                    ),
                  );
                }),
              ),
            ),
            const SizedBox(height: 20),

            // Payment Method
            _buildSectionCard(
              title: _t('Njia ya Malipo', 'Payment Method'),
              child: Column(
                children: [
                  _paymentMethodOption(_t('Transfer', 'Transfer'), Icons.account_balance_outlined),
                  const SizedBox(height: 12),
                  _paymentMethodOption(_t('Cash', 'Cash'), Icons.money_outlined),
                  const SizedBox(height: 12),
                  _paymentMethodOption(_t('Mobile Money', 'Mobile Money'), Icons.phone_android_outlined),
                ],
              ),
            ),
            const SizedBox(height: 20),

            // Summary
            _buildSectionCard(
              title: _t('Muhtasari', 'Summary'),
              child: Column(
                children: [
                  _summaryRow(_t('Kodi ya Mwezi', 'Monthly Rent'), 'TZS 450,000'),
                  const Divider(color: Color(0xffe5e7eb)),
                  _summaryRow(_t('Idadi ya Miezi', 'Number of Months'), '$_selectedMonths'),
                  const Divider(color: Color(0xffe5e7eb)),
                  _summaryRow(
                    _t('Jumla', 'Total'),
                    'TZS ${450000 * _selectedMonths}',
                    isBold: true,
                  ),
                ],
              ),
            ),
            const SizedBox(height: 32),

            // Pay Button
            SizedBox(
              width: double.infinity,
              height: 56,
              child: ElevatedButton(
                onPressed: _isProcessing ? null : _handlePayment,
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.primary,
                  foregroundColor: Colors.black,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(16),
                  ),
                  elevation: 0,
                  shadowColor: AppColors.primary.withOpacity(0.3),
                ),
                child: _isProcessing
                    ? const SizedBox(
                        height: 24,
                        width: 24,
                        child: CircularProgressIndicator(
                          strokeWidth: 2,
                          color: Colors.black,
                        ),
                      )
                    : Text(
                        _t('Lipa Sasa', 'Pay Now'),
                        style: GoogleFonts.poppins(
                          fontWeight: FontWeight.w600,
                          fontSize: 16,
                        ),
                      ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildSectionCard({required String title, required Widget child}) {
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

  Widget _paymentMethodOption(String method, IconData icon) {
    final isSelected = _selectedMethod == method;
    return GestureDetector(
      onTap: () {
        setState(() {
          _selectedMethod = method;
        });
      },
      child: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: isSelected ? AppColors.primary.withOpacity(0.1) : Colors.grey[50],
          borderRadius: BorderRadius.circular(12),
          border: Border.all(
            color: isSelected ? AppColors.primary : Colors.grey[300]!,
            width: isSelected ? 2 : 1,
          ),
        ),
        child: Row(
          children: [
            Icon(
              icon,
              color: isSelected ? AppColors.primary : Colors.grey[600],
              size: 24,
            ),
            const SizedBox(width: 12),
            Text(
              method,
              style: GoogleFonts.poppins(
                fontSize: 15,
                fontWeight: isSelected ? FontWeight.w600 : FontWeight.w500,
                color: isSelected ? AppColors.primary : Colors.black87,
              ),
            ),
            const Spacer(),
            if (isSelected)
              Icon(
                Icons.check_circle,
                color: AppColors.primary,
                size: 24,
              ),
          ],
        ),
      ),
    );
  }

  Widget _summaryRow(String label, String value, {bool isBold = false}) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(
            label,
            style: GoogleFonts.poppins(
              fontSize: 14,
              color: Colors.grey[600],
              fontWeight: isBold ? FontWeight.w600 : FontWeight.w500,
            ),
          ),
          Text(
            value,
            style: GoogleFonts.poppins(
              fontSize: 15,
              color: const Color(0xff111827),
              fontWeight: isBold ? FontWeight.bold : FontWeight.w600,
            ),
          ),
        ],
      ),
    );
  }

  Future<void> _handlePayment() async {
    setState(() => _isProcessing = true);
    
    // Simulate payment processing
    await Future.delayed(const Duration(seconds: 2));
    
    setState(() => _isProcessing = false);
    
    if (mounted) {
      Helpers.showSnackBar(
        context,
        'Malipo yamekamilika! (Payment completed)',
        isError: false,
      );
    }
  }
}
