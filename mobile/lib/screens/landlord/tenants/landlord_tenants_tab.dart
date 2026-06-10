import 'package:flutter/material.dart';
import '../../../constants/app_colors.dart';
import '../../../models/tenant_model.dart';
import '../../../services/landlord_service.dart';
import '../../../services/sms_service.dart';
import '../../../utils/helpers.dart';

class LandlordTenantsTab extends StatefulWidget {
  const LandlordTenantsTab({super.key});

  @override
  State<LandlordTenantsTab> createState() => _LandlordTenantsTabState();
}

class _LandlordTenantsTabState extends State<LandlordTenantsTab> {
  final LandlordService _service = LandlordService();
  final _searchCtrl = TextEditingController();
  List<TenantModel> _tenants = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load({String? search}) async {
    setState(() => _isLoading = true);
    try {
      final res = await _service.getTenants(search: search);
      if (res['data'] != null) {
        final list = res['data'] is List ? res['data'] : (res['data']['data'] ?? []);
        setState(() => _tenants = (list as List).map((j) => TenantModel.fromJson(j)).toList());
      }
    } catch (_) {}
    if (mounted) setState(() => _isLoading = false);
  }

  @override
  void dispose() {
    _searchCtrl.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF4F6F8),
      body: SafeArea(
        child: Column(
          children: [
            _buildHeader(context),
            _buildSearchBar(),
            Expanded(
              child: _isLoading
                  ? const Center(child: CircularProgressIndicator(color: AppColors.primary))
                  : _tenants.isEmpty
                      ? const Center(child: Text('No tenants found.', style: TextStyle(color: Color(0xFF9CA3AF), fontSize: 15)))
                      : RefreshIndicator(
                          onRefresh: _load,
                          color: AppColors.primary,
                          child: ListView.builder(
                            padding: const EdgeInsets.fromLTRB(16, 8, 16, 100),
                            itemCount: _tenants.length,
                            itemBuilder: (ctx, i) => _buildTenantCard(_tenants[i]),
                          ),
                        ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildHeader(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.fromLTRB(16, 16, 16, 8),
      child: Row(
        children: [
          const Text('Tenants', style: TextStyle(fontSize: 22, fontWeight: FontWeight.w800)),
          const Spacer(),
          // SMS Blast button
          GestureDetector(
            onTap: () => _showSmsBlastSheet(context),
            child: Container(
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
              decoration: BoxDecoration(
                color: AppColors.primaryLight,
                border: Border.all(color: AppColors.primary.withOpacity(0.3)),
                borderRadius: BorderRadius.circular(10),
              ),
              child: Row(
                mainAxisSize: MainAxisSize.min,
                children: const [
                  Icon(Icons.sms_outlined, color: AppColors.primary, size: 16),
                  SizedBox(width: 4),
                  Text('SMS', style: TextStyle(color: AppColors.primary, fontSize: 12, fontWeight: FontWeight.w700)),
                ],
              ),
            ),
          ),
          const SizedBox(width: 8),
          GestureDetector(
            onTap: () => _showAddTenantSheet(context),
            child: Container(
              width: 36,
              height: 36,
              decoration: BoxDecoration(
                color: AppColors.primaryLight,
                border: Border.all(color: AppColors.primary.withOpacity(0.3)),
                borderRadius: BorderRadius.circular(10),
              ),
              child: const Icon(Icons.add, color: AppColors.primary, size: 22),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSearchBar() {
    return Padding(
      padding: const EdgeInsets.fromLTRB(16, 0, 16, 8),
      child: TextField(
        controller: _searchCtrl,
        onChanged: (v) => _load(search: v),
        decoration: InputDecoration(
          hintText: 'Search tenants',
          hintStyle: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 13),
          prefixIcon: const Icon(Icons.search, color: Color(0xFF9CA3AF), size: 20),
          contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
          border: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
          enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
          focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: AppColors.primary)),
          filled: true,
          fillColor: Colors.white,
        ),
      ),
    );
  }

  Widget _buildTenantCard(TenantModel t) {
    final isPaid = t.hasPaidThisMonth;
    return Container(
      margin: const EdgeInsets.only(bottom: 10),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(14),
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.04), blurRadius: 6)],
      ),
      child: Padding(
        padding: const EdgeInsets.all(14),
        child: Row(
          children: [
            // Avatar
            Container(
              width: 44,
              height: 44,
              decoration: BoxDecoration(
                color: AppColors.primaryLight,
                borderRadius: BorderRadius.circular(12),
              ),
              child: Center(
                child: Text(t.initials, style: const TextStyle(color: AppColors.primary, fontWeight: FontWeight.w700, fontSize: 15)),
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(t.fullName, style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 14)),
                  const SizedBox(height: 2),
                  Text(t.phone, style: const TextStyle(color: Color(0xFF6B7280), fontSize: 12)),
                  if (t.propertyTitle != null) ...[
                    const SizedBox(height: 2),
                    Row(
                      children: [
                        const Icon(Icons.home_outlined, size: 12, color: Color(0xFF9CA3AF)),
                        const SizedBox(width: 3),
                        Flexible(child: Text(t.propertyTitle!, style: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 11))),
                      ],
                    ),
                  ],
                ],
              ),
            ),
            Column(
              crossAxisAlignment: CrossAxisAlignment.end,
              children: [
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                  decoration: BoxDecoration(
                    color: isPaid ? const Color(0xFF10B981).withOpacity(0.1) : AppColors.primaryLight,
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: Text(
                    isPaid ? 'Amelipa' : 'Hajalipa',
                    style: TextStyle(
                      color: isPaid ? const Color(0xFF10B981) : AppColors.primary,
                      fontSize: 10,
                      fontWeight: FontWeight.w700,
                    ),
                  ),
                ),
                if (t.rentAmount != null) ...[
                  const SizedBox(height: 4),
                  Text(
                    'TZS ${Helpers.formatMoney(t.rentAmount!)}',
                    style: const TextStyle(color: Color(0xFF374151), fontWeight: FontWeight.w600, fontSize: 11),
                  ),
                ],
                const SizedBox(height: 4),
                GestureDetector(
                  onTap: () => _showSendSmsToTenant(context, t),
                  child: const Icon(Icons.sms_outlined, color: AppColors.primary, size: 18),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  void _showAddTenantSheet(BuildContext context) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (_) => const _AddTenantSheet(),
    ).then((_) => _load());
  }

  void _showSmsBlastSheet(BuildContext context) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (_) => _SmsBlastSheet(tenants: _tenants),
    );
  }

  void _showSendSmsToTenant(BuildContext context, TenantModel tenant) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (_) => _SendSmsToTenantSheet(tenant: tenant),
    );
  }
}

// ─── Add Tenant Multi-Step Sheet ────────────────────────────────────────────

class _AddTenantSheet extends StatefulWidget {
  const _AddTenantSheet();
  @override
  State<_AddTenantSheet> createState() => _AddTenantSheetState();
}

class _AddTenantSheetState extends State<_AddTenantSheet> {
  final LandlordService _service = LandlordService();
  int _step = 1;
  bool _saving = false;

  // Step 1
  final _firstCtrl = TextEditingController();
  final _lastCtrl = TextEditingController();
  final _emailCtrl = TextEditingController();
  String? _gender;
  final _phoneCtrl = TextEditingController();

  // Step 2
  final _propertyCtrl = TextEditingController();
  final _rentCtrl = TextEditingController();
  final _depositCtrl = TextEditingController();
  final _startCtrl = TextEditingController();

  @override
  void dispose() {
    _firstCtrl.dispose(); _lastCtrl.dispose(); _emailCtrl.dispose();
    _phoneCtrl.dispose(); _propertyCtrl.dispose(); _rentCtrl.dispose();
    _depositCtrl.dispose(); _startCtrl.dispose();
    super.dispose();
  }

  Future<void> _save() async {
    setState(() => _saving = true);
    try {
      final res = await _service.createTenant({
        'first_name': _firstCtrl.text.trim(),
        'last_name': _lastCtrl.text.trim(),
        'name': '${_firstCtrl.text.trim()} ${_lastCtrl.text.trim()}',
        'email': _emailCtrl.text.trim(),
        'phone': '+255${_phoneCtrl.text.trim()}',
        if (_gender != null) 'gender': _gender,
        if (_propertyCtrl.text.isNotEmpty) 'property_id': _propertyCtrl.text.trim(),
        if (_rentCtrl.text.isNotEmpty) 'rent_amount': double.tryParse(_rentCtrl.text),
        if (_depositCtrl.text.isNotEmpty) 'deposit_amount': double.tryParse(_depositCtrl.text),
        if (_startCtrl.text.isNotEmpty) 'lease_start': _startCtrl.text.trim(),
      });
      if (mounted) {
        if (res['success'] == true) {
          Helpers.showSnackBar(context, 'Mpangaji ameongezwa!');
          Navigator.pop(context);
        } else {
          Helpers.showSnackBar(context, res['message'] ?? 'Imeshindikana');
        }
      }
    } catch (e) {
      if (mounted) Helpers.showSnackBar(context, e.toString());
    }
    if (mounted) setState(() => _saving = false);
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      padding: EdgeInsets.only(bottom: MediaQuery.of(context).viewInsets.bottom),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            margin: const EdgeInsets.only(top: 10),
            width: 40, height: 4,
            decoration: BoxDecoration(color: const Color(0xFFE5E7EB), borderRadius: BorderRadius.circular(2)),
          ),
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 14, 16, 0),
            child: Row(
              children: [
                GestureDetector(
                  onTap: () => Navigator.pop(context),
                  child: Container(
                    width: 34, height: 34,
                    decoration: BoxDecoration(color: AppColors.primaryLight, borderRadius: BorderRadius.circular(10)),
                    child: const Icon(Icons.close, color: AppColors.primary, size: 18),
                  ),
                ),
                const SizedBox(width: 12),
                const Text('Add Tenant', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 17)),
                const Spacer(),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                  decoration: BoxDecoration(color: AppColors.primaryLight, borderRadius: BorderRadius.circular(20)),
                  child: Text('Step $_step/2', style: const TextStyle(color: AppColors.primary, fontSize: 12, fontWeight: FontWeight.w700)),
                ),
              ],
            ),
          ),
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 8, 16, 4),
            child: Text(
              _step == 1 ? 'Capture tenant details for this record.' : 'Optional: assign property and lease info.',
              style: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 12),
            ),
          ),
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16),
            child: ClipRRect(
              borderRadius: BorderRadius.circular(4),
              child: LinearProgressIndicator(
                value: _step / 2, minHeight: 5,
                backgroundColor: const Color(0xFFE5E7EB),
                valueColor: const AlwaysStoppedAnimation<Color>(AppColors.primary),
              ),
            ),
          ),
          const SizedBox(height: 16),
          Flexible(
            child: SingleChildScrollView(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: _step == 1 ? _buildStep1() : _buildStep2(),
            ),
          ),
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 12, 16, 20),
            child: Column(
              children: [
                SizedBox(
                  width: double.infinity, height: 50,
                  child: ElevatedButton(
                    onPressed: _saving ? null : () {
                      if (_step == 1) {
                        if (_firstCtrl.text.isEmpty || _phoneCtrl.text.isEmpty) {
                          Helpers.showSnackBar(context, 'Jaza jina na simu ya mpangaji');
                          return;
                        }
                        setState(() => _step = 2);
                      } else {
                        _save();
                      }
                    },
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppColors.primary, foregroundColor: Colors.white,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                      elevation: 0,
                    ),
                    child: _saving
                        ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                        : Text(_step == 1 ? 'Next' : 'Save Tenant',
                            style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 15)),
                  ),
                ),
                const SizedBox(height: 8),
                SizedBox(
                  width: double.infinity, height: 50,
                  child: OutlinedButton(
                    onPressed: () => _step == 2 ? setState(() => _step = 1) : Navigator.pop(context),
                    style: OutlinedButton.styleFrom(
                      side: const BorderSide(color: Color(0xFFE5E7EB)),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                    ),
                    child: Text(_step == 2 ? 'Back' : 'Cancel',
                        style: const TextStyle(color: Color(0xFF374151), fontWeight: FontWeight.w600)),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildStep1() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        _sectionLabel('Tenant Details'),
        const SizedBox(height: 12),
        Row(
          children: [
            Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
              _fieldLabel('First Name'),
              _inputField(_firstCtrl, 'e.g. John'),
            ])),
            const SizedBox(width: 12),
            Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
              _fieldLabel('Last Name'),
              _inputField(_lastCtrl, 'e.g. Doe'),
            ])),
          ],
        ),
        const SizedBox(height: 14),
        Row(
          children: [
            Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
              _fieldLabel('Email'),
              _inputField(_emailCtrl, 'name@example.com', keyboard: TextInputType.emailAddress),
            ])),
            const SizedBox(width: 12),
            Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
              _fieldLabel('Gender'),
              _dropdownField(
                value: _gender,
                items: ['Male', 'Female', 'Other'],
                onChanged: (v) => setState(() => _gender = v),
              ),
            ])),
          ],
        ),
        const SizedBox(height: 14),
        _fieldLabel('Phone Number'),
        _phoneInput(),
        const SizedBox(height: 8),
      ],
    );
  }

  Widget _buildStep2() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        _sectionLabel('Lease & Property (Optional)'),
        const SizedBox(height: 12),
        _fieldLabel('Property ID'),
        _inputField(_propertyCtrl, 'Enter property ID', keyboard: TextInputType.number),
        const SizedBox(height: 14),
        Row(
          children: [
            Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
              _fieldLabel('Monthly Rent (TZS)'),
              _inputField(_rentCtrl, 'e.g. 300000', keyboard: TextInputType.number),
            ])),
            const SizedBox(width: 12),
            Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
              _fieldLabel('Deposit (TZS)'),
              _inputField(_depositCtrl, 'e.g. 600000', keyboard: TextInputType.number),
            ])),
          ],
        ),
        const SizedBox(height: 14),
        _fieldLabel('Lease Start Date'),
        _inputField(_startCtrl, 'YYYY-MM-DD'),
        const SizedBox(height: 8),
      ],
    );
  }

  Widget _phoneInput() {
    return TextField(
      controller: _phoneCtrl,
      keyboardType: TextInputType.phone,
      maxLength: 9,
      style: const TextStyle(fontSize: 14),
      decoration: InputDecoration(
        hintText: '7XXXXXXXX',
        hintStyle: const TextStyle(color: Color(0xFFD1D5DB), fontSize: 13),
        counterText: '${_phoneCtrl.text.length}/9',
        prefixIcon: Container(
          margin: const EdgeInsets.all(8),
          padding: const EdgeInsets.symmetric(horizontal: 8),
          decoration: BoxDecoration(
            border: Border(right: BorderSide(color: const Color(0xFFE5E7EB))),
          ),
          child: Row(
            mainAxisSize: MainAxisSize.min,
            children: const [
              Text('🇹🇿', style: TextStyle(fontSize: 16)),
              SizedBox(width: 4),
              Text('+255', style: TextStyle(color: Color(0xFF374151), fontSize: 13, fontWeight: FontWeight.w600)),
            ],
          ),
        ),
        contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
        enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
        focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: AppColors.primary)),
        filled: true,
        fillColor: Colors.white,
      ),
      onChanged: (_) => setState(() {}),
    );
  }

  Widget _sectionLabel(String text) {
    return Text(text, style: const TextStyle(color: AppColors.primary, fontWeight: FontWeight.w700, fontSize: 13));
  }

  Widget _fieldLabel(String text) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 6),
      child: Text(text, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13, color: Color(0xFF374151))),
    );
  }

  Widget _inputField(TextEditingController ctrl, String hint, {TextInputType? keyboard}) {
    return TextField(
      controller: ctrl,
      keyboardType: keyboard,
      style: const TextStyle(fontSize: 14),
      decoration: InputDecoration(
        hintText: hint,
        hintStyle: const TextStyle(color: Color(0xFFD1D5DB), fontSize: 13),
        contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
        enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
        focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: AppColors.primary)),
        filled: true,
        fillColor: Colors.white,
      ),
    );
  }

  Widget _dropdownField({required String? value, required List<String> items, required ValueChanged<String?> onChanged}) {
    return DropdownButtonFormField<String>(
      value: value,
      hint: const Text('', style: TextStyle(color: Color(0xFFD1D5DB), fontSize: 13)),
      items: items.map((e) => DropdownMenuItem(value: e, child: Text(e, style: const TextStyle(fontSize: 14)))).toList(),
      onChanged: onChanged,
      decoration: InputDecoration(
        contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
        enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
        focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: AppColors.primary)),
        filled: true,
        fillColor: Colors.white,
      ),
    );
  }
}

// ─── SMS Blast Sheet ─────────────────────────────────────────────────────────

class _SmsBlastSheet extends StatefulWidget {
  final List<TenantModel> tenants;
  const _SmsBlastSheet({required this.tenants});
  @override
  State<_SmsBlastSheet> createState() => _SmsBlastSheetState();
}

class _SmsBlastSheetState extends State<_SmsBlastSheet> {
  final SmsService _sms = SmsService();
  String _target = 'unpaid'; // 'paid' | 'unpaid' | 'all'
  final _msgCtrl = TextEditingController();
  bool _sending = false;

  final _templates = {
    'paid': 'Habari! Asante kwa kulipa kodi yako kwa mwezi huu. Tunakushukuru.',
    'unpaid': 'Habari! Kodi yako bado haijafika. Tafadhali lipa haraka iwezekanavyo ili kuepuka faini.',
  };

  @override
  void initState() {
    super.initState();
    _msgCtrl.text = _templates['unpaid']!;
  }

  @override
  void dispose() {
    _msgCtrl.dispose();
    super.dispose();
  }

  Future<void> _send() async {
    if (_msgCtrl.text.isEmpty) {
      Helpers.showSnackBar(context, 'Andika ujumbe kwanza');
      return;
    }
    setState(() => _sending = true);
    try {
      Map<String, dynamic> res;
      if (_target == 'paid') {
        res = await _sms.sendToPaidTenants(message: _msgCtrl.text);
      } else if (_target == 'unpaid') {
        res = await _sms.sendToUnpaidTenants(message: _msgCtrl.text);
      } else {
        res = await _sms.sendToPaidTenants(message: _msgCtrl.text);
      }
      if (mounted) {
        final sent = res['sent_count'] ?? res['data']?['sent_count'] ?? 0;
        Helpers.showSnackBar(context, 'SMS imetumwa kwa wapangaji $sent!');
        Navigator.pop(context);
      }
    } catch (e) {
      if (mounted) Helpers.showSnackBar(context, e.toString());
    }
    if (mounted) setState(() => _sending = false);
  }

  @override
  Widget build(BuildContext context) {
    final paidCount = widget.tenants.where((t) => t.hasPaidThisMonth).length;
    final unpaidCount = widget.tenants.length - paidCount;

    return Container(
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      padding: EdgeInsets.only(bottom: MediaQuery.of(context).viewInsets.bottom + 20),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            margin: const EdgeInsets.only(top: 10),
            alignment: Alignment.center,
            child: Container(
              width: 40, height: 4,
              decoration: BoxDecoration(color: const Color(0xFFE5E7EB), borderRadius: BorderRadius.circular(2)),
            ),
          ),
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 14, 16, 0),
            child: Row(
              children: [
                Container(
                  padding: const EdgeInsets.all(8),
                  decoration: BoxDecoration(color: AppColors.primaryLight, borderRadius: BorderRadius.circular(10)),
                  child: const Icon(Icons.sms_rounded, color: AppColors.primary, size: 20),
                ),
                const SizedBox(width: 12),
                const Text('Tuma SMS kwa Wapangaji', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 16)),
                const Spacer(),
                GestureDetector(
                  onTap: () => Navigator.pop(context),
                  child: const Icon(Icons.close, color: Color(0xFF9CA3AF)),
                ),
              ],
            ),
          ),
          const SizedBox(height: 16),
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text('Tuma kwa:', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13, color: Color(0xFF374151))),
                const SizedBox(height: 10),
                Row(
                  children: [
                    _targetChip('unpaid', 'Hawajalipa ($unpaidCount)', Icons.warning_rounded, const Color(0xFFEF4444)),
                    const SizedBox(width: 8),
                    _targetChip('paid', 'Wamelipa ($paidCount)', Icons.check_circle_rounded, const Color(0xFF10B981)),
                  ],
                ),
                const SizedBox(height: 16),
                const Text('Ujumbe:', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13, color: Color(0xFF374151))),
                const SizedBox(height: 8),
                // Template chips
                Wrap(
                  spacing: 8,
                  children: [
                    _templateChip('Kodi', _templates['unpaid']!),
                    _templateChip('Shukrani', _templates['paid']!),
                  ],
                ),
                const SizedBox(height: 10),
                TextField(
                  controller: _msgCtrl,
                  maxLines: 4,
                  maxLength: 160,
                  style: const TextStyle(fontSize: 13),
                  decoration: InputDecoration(
                    hintText: 'Andika ujumbe wako hapa...',
                    hintStyle: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 12),
                    contentPadding: const EdgeInsets.all(12),
                    border: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
                    enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
                    focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: AppColors.primary)),
                    filled: true,
                    fillColor: Colors.white,
                  ),
                ),
                const SizedBox(height: 14),
                SizedBox(
                  width: double.infinity, height: 50,
                  child: ElevatedButton.icon(
                    onPressed: _sending ? null : _send,
                    icon: _sending
                        ? const SizedBox(width: 18, height: 18, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                        : const Icon(Icons.send_rounded, size: 18),
                    label: Text(_sending ? 'Inatuma...' : 'Tuma SMS',
                        style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 15)),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppColors.primary, foregroundColor: Colors.white,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                      elevation: 0,
                    ),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _targetChip(String value, String label, IconData icon, Color color) {
    final selected = _target == value;
    return Expanded(
      child: GestureDetector(
        onTap: () {
          setState(() {
            _target = value;
            _msgCtrl.text = value == 'paid' ? _templates['paid']! : _templates['unpaid']!;
          });
        },
        child: Container(
          padding: const EdgeInsets.symmetric(vertical: 10, horizontal: 12),
          decoration: BoxDecoration(
            color: selected ? color.withOpacity(0.1) : const Color(0xFFF9FAFB),
            border: Border.all(color: selected ? color : const Color(0xFFE5E7EB), width: 1.5),
            borderRadius: BorderRadius.circular(10),
          ),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Icon(icon, color: selected ? color : const Color(0xFF9CA3AF), size: 16),
              const SizedBox(width: 6),
              Flexible(child: Text(label, style: TextStyle(color: selected ? color : const Color(0xFF6B7280), fontSize: 12, fontWeight: selected ? FontWeight.w700 : FontWeight.w400))),
            ],
          ),
        ),
      ),
    );
  }

  Widget _templateChip(String label, String template) {
    return GestureDetector(
      onTap: () => setState(() => _msgCtrl.text = template),
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
        decoration: BoxDecoration(
          color: AppColors.primaryLight,
          borderRadius: BorderRadius.circular(20),
        ),
        child: Text(label, style: const TextStyle(color: AppColors.primary, fontSize: 11, fontWeight: FontWeight.w600)),
      ),
    );
  }
}

// ─── Send SMS to Single Tenant ───────────────────────────────────────────────

class _SendSmsToTenantSheet extends StatefulWidget {
  final TenantModel tenant;
  const _SendSmsToTenantSheet({required this.tenant});
  @override
  State<_SendSmsToTenantSheet> createState() => _SendSmsToTenantSheetState();
}

class _SendSmsToTenantSheetState extends State<_SendSmsToTenantSheet> {
  final SmsService _sms = SmsService();
  final _msgCtrl = TextEditingController();
  bool _sending = false;
  String _type = 'custom'; // 'custom' | 'electricity' | 'water'
  final _amountCtrl = TextEditingController();
  final _periodCtrl = TextEditingController();

  @override
  void dispose() {
    _msgCtrl.dispose();
    _amountCtrl.dispose();
    _periodCtrl.dispose();
    super.dispose();
  }

  Future<void> _send() async {
    setState(() => _sending = true);
    try {
      Map<String, dynamic> res;
      if (_type == 'custom') {
        res = await _sms.sendToTenant(tenantId: widget.tenant.id, message: _msgCtrl.text);
      } else {
        res = await _sms.sendUtilityBill(
          tenantId: widget.tenant.id,
          utilityType: _type,
          amount: double.tryParse(_amountCtrl.text) ?? 0,
          period: _periodCtrl.text,
          customMessage: _msgCtrl.text.isNotEmpty ? _msgCtrl.text : null,
        );
      }
      if (mounted) {
        Helpers.showSnackBar(context, 'SMS imetumwa kwa ${widget.tenant.fullName}!');
        Navigator.pop(context);
      }
    } catch (e) {
      if (mounted) Helpers.showSnackBar(context, e.toString());
    }
    if (mounted) setState(() => _sending = false);
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      padding: EdgeInsets.only(bottom: MediaQuery.of(context).viewInsets.bottom + 20),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            margin: const EdgeInsets.only(top: 10),
            alignment: Alignment.center,
            child: Container(
              width: 40, height: 4,
              decoration: BoxDecoration(color: const Color(0xFFE5E7EB), borderRadius: BorderRadius.circular(2)),
            ),
          ),
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 14, 16, 16),
            child: Row(
              children: [
                Container(
                  width: 40, height: 40,
                  decoration: BoxDecoration(color: AppColors.primaryLight, borderRadius: BorderRadius.circular(12)),
                  child: Center(child: Text(widget.tenant.initials, style: const TextStyle(color: AppColors.primary, fontWeight: FontWeight.w700))),
                ),
                const SizedBox(width: 12),
                Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                  Text(widget.tenant.fullName, style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 15)),
                  Text(widget.tenant.phone, style: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 12)),
                ]),
                const Spacer(),
                GestureDetector(onTap: () => Navigator.pop(context), child: const Icon(Icons.close, color: Color(0xFF9CA3AF))),
              ],
            ),
          ),
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text('Aina ya SMS:', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
                const SizedBox(height: 10),
                Row(
                  children: [
                    _typeChip('custom', 'Ujumbe', Icons.message_outlined),
                    const SizedBox(width: 8),
                    _typeChip('electricity', 'Umeme', Icons.bolt_rounded),
                    const SizedBox(width: 8),
                    _typeChip('water', 'Maji', Icons.water_drop_rounded),
                  ],
                ),
                const SizedBox(height: 14),
                if (_type != 'custom') ...[
                  Row(
                    children: [
                      Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                        const Text('Kiasi (TZS)', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
                        const SizedBox(height: 6),
                        _inputField(_amountCtrl, 'e.g. 15000', keyboard: TextInputType.number),
                      ])),
                      const SizedBox(width: 12),
                      Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                        const Text('Kipindi', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
                        const SizedBox(height: 6),
                        _inputField(_periodCtrl, 'e.g. June 2026'),
                      ])),
                    ],
                  ),
                  const SizedBox(height: 14),
                ],
                const Text('Ujumbe wa ziada (hiari):', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
                const SizedBox(height: 8),
                TextField(
                  controller: _msgCtrl,
                  maxLines: 3,
                  style: const TextStyle(fontSize: 13),
                  decoration: InputDecoration(
                    hintText: _type == 'custom' ? 'Andika ujumbe wako...' : 'Ujumbe wa ziada (hiari)',
                    hintStyle: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 12),
                    contentPadding: const EdgeInsets.all(12),
                    border: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
                    enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
                    focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: AppColors.primary)),
                    filled: true,
                    fillColor: Colors.white,
                  ),
                ),
                const SizedBox(height: 14),
                SizedBox(
                  width: double.infinity, height: 50,
                  child: ElevatedButton.icon(
                    onPressed: _sending ? null : _send,
                    icon: const Icon(Icons.send_rounded, size: 18),
                    label: Text(_sending ? 'Inatuma...' : 'Tuma', style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 15)),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppColors.primary, foregroundColor: Colors.white,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                      elevation: 0,
                    ),
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 4),
        ],
      ),
    );
  }

  Widget _typeChip(String value, String label, IconData icon) {
    final selected = _type == value;
    return Expanded(
      child: GestureDetector(
        onTap: () => setState(() => _type = value),
        child: Container(
          padding: const EdgeInsets.symmetric(vertical: 8),
          decoration: BoxDecoration(
            color: selected ? AppColors.primaryLight : const Color(0xFFF9FAFB),
            border: Border.all(color: selected ? AppColors.primary : const Color(0xFFE5E7EB), width: 1.5),
            borderRadius: BorderRadius.circular(10),
          ),
          child: Column(
            children: [
              Icon(icon, color: selected ? AppColors.primary : const Color(0xFF9CA3AF), size: 18),
              const SizedBox(height: 4),
              Text(label, style: TextStyle(color: selected ? AppColors.primary : const Color(0xFF6B7280), fontSize: 11, fontWeight: selected ? FontWeight.w700 : FontWeight.w400)),
            ],
          ),
        ),
      ),
    );
  }

  Widget _inputField(TextEditingController ctrl, String hint, {TextInputType? keyboard}) {
    return TextField(
      controller: ctrl, keyboardType: keyboard,
      style: const TextStyle(fontSize: 13),
      decoration: InputDecoration(
        hintText: hint, hintStyle: const TextStyle(color: Color(0xFFD1D5DB), fontSize: 12),
        contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
        enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
        focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: AppColors.primary)),
        filled: true, fillColor: Colors.white,
      ),
    );
  }
}
