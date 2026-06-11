import 'package:flutter/material.dart';
import 'package:flutter/services.dart';

class Helpers {
  Helpers._();

  static bool _isNetworkError(String msg) {
    final m = msg.toLowerCase();
    return m.contains('internet') || m.contains('mtandao') ||
        m.contains('socketexception') || m.contains('muunganisho') ||
        m.contains('failed host') || m.contains('timeout') ||
        m.contains('timed out') || m.contains('connection');
  }

  static void showSnackBar(BuildContext context, String message,
      {bool isError = false, VoidCallback? onRetry}) {
    // For network errors, show the beautiful dialog instead
    if (isError && _isNetworkError(message)) {
      showNetworkError(context, message: message, onRetry: onRetry);
      return;
    }

    ScaffoldMessenger.of(context).clearSnackBars();
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Row(
          children: [
            Icon(
              isError ? Icons.error_outline_rounded : Icons.check_circle_outline_rounded,
              color: Colors.white,
              size: 22,
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Text(
                message,
                style: const TextStyle(
                  color: Colors.white,
                  fontSize: 14,
                  fontWeight: FontWeight.w500,
                ),
              ),
            ),
          ],
        ),
        backgroundColor: isError ? const Color(0xffef4444) : const Color(0xff10b981),
        behavior: SnackBarBehavior.floating,
        elevation: 6,
        margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        duration: const Duration(seconds: 4),
      ),
    );
  }

  static void showNetworkError(BuildContext context,
      {String? message, VoidCallback? onRetry}) {
    HapticFeedback.mediumImpact();
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      isDismissible: true,
      builder: (_) => _NetworkErrorSheet(
        message: message,
        onRetry: onRetry,
      ),
    );
  }

  static void hideKeyboard(BuildContext context) {
    FocusScope.of(context).unfocus();
  }

  static Future<bool> showConfirmationDialog(
    BuildContext context, {
    required String title,
    required String message,
    String confirmText = 'Confirm',
    String cancelText = 'Cancel',
  }) async {
    final result = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: Text(title),
        content: Text(message),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: Text(cancelText),
          ),
          TextButton(
            onPressed: () => Navigator.pop(context, true),
            child: Text(confirmText),
          ),
        ],
      ),
    );
    return result ?? false;
  }

  static String formatCurrency(double amount) {
    return '\$${amount.toStringAsFixed(2)}';
  }

  static String formatMoney(double amount) {
    String val = amount.toStringAsFixed(0);
    RegExp reg = RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))');
    return val.replaceAllMapped(reg, (Match m) => '${m[1]},');
  }

  static String formatDate(DateTime date) {
    return '${date.day}/${date.month}/${date.year}';
  }
}

// ─── Beautiful network error bottom sheet ────────────────────────────────────

class _NetworkErrorSheet extends StatefulWidget {
  final String? message;
  final VoidCallback? onRetry;
  const _NetworkErrorSheet({this.message, this.onRetry});

  @override
  State<_NetworkErrorSheet> createState() => _NetworkErrorSheetState();
}

class _NetworkErrorSheetState extends State<_NetworkErrorSheet>
    with SingleTickerProviderStateMixin {
  late AnimationController _ctrl;
  late Animation<double> _scale;

  @override
  void initState() {
    super.initState();
    _ctrl = AnimationController(vsync: this, duration: const Duration(milliseconds: 600));
    _scale = CurvedAnimation(parent: _ctrl, curve: Curves.elasticOut);
    _ctrl.forward();
  }

  @override
  void dispose() {
    _ctrl.dispose();
    super.dispose();
  }

  bool get _isTimeout =>
      (widget.message ?? '').toLowerCase().contains('timeout') ||
      (widget.message ?? '').toLowerCase().contains('muda');

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(28)),
      ),
      padding: const EdgeInsets.fromLTRB(24, 16, 24, 40),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          // Handle
          Container(
            width: 40, height: 4,
            decoration: BoxDecoration(
              color: const Color(0xFFE5E7EB),
              borderRadius: BorderRadius.circular(2),
            ),
          ),
          const SizedBox(height: 28),

          // Animated icon
          ScaleTransition(
            scale: _scale,
            child: Container(
              width: 88, height: 88,
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                  colors: _isTimeout
                      ? [const Color(0xFFF59E0B), const Color(0xFFD97706)]
                      : [const Color(0xFFEF4444), const Color(0xFFDC2626)],
                ),
                shape: BoxShape.circle,
                boxShadow: [
                  BoxShadow(
                    color: (_isTimeout ? const Color(0xFFF59E0B) : const Color(0xFFEF4444))
                        .withValues(alpha: 0.3),
                    blurRadius: 20,
                    offset: const Offset(0, 8),
                  ),
                ],
              ),
              child: Icon(
                _isTimeout ? Icons.timer_off_rounded : Icons.wifi_off_rounded,
                color: Colors.white,
                size: 40,
              ),
            ),
          ),
          const SizedBox(height: 20),

          // Title
          Text(
            _isTimeout ? 'Muda Umekwisha' : 'Hakuna Mtandao',
            style: const TextStyle(
              fontSize: 22,
              fontWeight: FontWeight.w900,
              color: Color(0xFF0F172A),
              letterSpacing: -0.5,
            ),
          ),
          const SizedBox(height: 8),

          // Message
          Text(
            widget.message ??
                (_isTimeout
                    ? 'Muunganisho umechukua muda mrefu sana. Hakikisha mtandao wako ni imara kisha jaribu tena.'
                    : 'Tafadhali angalia muunganisho wako wa internet na ujaribu tena.'),
            style: const TextStyle(
              fontSize: 14,
              color: Color(0xFF64748B),
              height: 1.5,
            ),
            textAlign: TextAlign.center,
          ),
          const SizedBox(height: 12),

          // Tips
          Container(
            padding: const EdgeInsets.all(14),
            decoration: BoxDecoration(
              color: const Color(0xFFF8FAFC),
              borderRadius: BorderRadius.circular(14),
              border: Border.all(color: const Color(0xFFE2E8F0)),
            ),
            child: Column(
              children: [
                _tip(Icons.wifi_rounded, 'Angalia WiFi au data ya simu'),
                const SizedBox(height: 8),
                _tip(Icons.airplane_mode_off_rounded, 'Zima na uwashe tena ndege mode'),
                const SizedBox(height: 8),
                _tip(Icons.signal_cellular_alt_rounded, 'Hakikisha una mtandao wa kutosha'),
              ],
            ),
          ),
          const SizedBox(height: 24),

          // Buttons
          if (widget.onRetry != null)
            SizedBox(
              width: double.infinity,
              child: ElevatedButton.icon(
                onPressed: () {
                  HapticFeedback.mediumImpact();
                  Navigator.pop(context);
                  widget.onRetry!();
                },
                icon: const Icon(Icons.refresh_rounded, color: Colors.white),
                label: const Text('Jaribu Tena',
                    style: TextStyle(color: Colors.white, fontWeight: FontWeight.w800, fontSize: 16)),
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFFB44040),
                  foregroundColor: Colors.white,
                  padding: const EdgeInsets.symmetric(vertical: 16),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
                  elevation: 0,
                ),
              ),
            ),
          const SizedBox(height: 10),
          SizedBox(
            width: double.infinity,
            child: OutlinedButton(
              onPressed: () => Navigator.pop(context),
              style: OutlinedButton.styleFrom(
                padding: const EdgeInsets.symmetric(vertical: 14),
                side: const BorderSide(color: Color(0xFFE2E8F0)),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
              ),
              child: const Text('Funga',
                  style: TextStyle(color: Color(0xFF374151), fontWeight: FontWeight.w600)),
            ),
          ),
        ],
      ),
    );
  }

  Widget _tip(IconData icon, String text) {
    return Row(
      children: [
        Container(
          width: 28, height: 28,
          decoration: BoxDecoration(
            color: const Color(0xFFEFF6FF),
            borderRadius: BorderRadius.circular(8),
          ),
          child: Icon(icon, size: 14, color: const Color(0xFF3B82F6)),
        ),
        const SizedBox(width: 10),
        Expanded(
          child: Text(text, style: const TextStyle(fontSize: 12, color: Color(0xFF64748B))),
        ),
      ],
    );
  }
}
