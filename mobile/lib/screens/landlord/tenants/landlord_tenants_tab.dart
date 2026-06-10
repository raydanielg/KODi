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
  String _filter = 'all'; // 'all' | 'paid' | 'unpaid'
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

  List<TenantModel> get _filtered {
    if (_filter == 'paid') return _tenants.where((t) => t.hasPaidThisMonth).toList();
    if (_filter == 'unpaid') return _tenants.where((t) => !t.hasPaidThisMonth).toList();
    return _tenants;
  }

  @override
  Widget build(BuildContext context) {
    final paid = _tenants.where((t) => t.hasPaidThisMonth).length;
    final unpaid = _tenants.length - paid;

    return Scaffold(
      backgroundColor: const Color(0xFFF1F5F9),
      body: SafeArea(
        child: Column(
          children: [
            _buildHeader(context),
            _buildStatsRow(paid, unpaid),
            _buildSearchAndFilter(),
            Expanded(
              child: _isLoading
                  ? const Center(child: CircularProgressIndicator(color: AppColors.primary))
                  : _filtered.isEmpty
                      ? _buildEmpty()
                      : RefreshIndicator(
                          onRefresh: _load,
                          color: AppColors.primary,
                          child: ListView.builder(
                            padding: const EdgeInsets.fromLTRB(16, 6, 16, 100),
                            itemCount: _filtered.length,
                            itemBuilder: (ctx, i) => _buildTenantCard(_filtered[i]),
                          ),
                        ),
            ),
          ],
        ),
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () => _showAddTenantSheet(context),
        backgroundColor: AppColors.primary,
        elevation: 4,
        icon: const Icon(Icons.person_add_rounded, color: Colors.white),
        label: const Text('Mpangaji Mpya', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w700)),
      ),
    );
  }

  Widget _buildHeader(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.fromLTRB(16, 16, 16, 0),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(
              gradient: const LinearGradient(colors: [AppColors.primary, AppColors.primaryDark]),
              borderRadius: BorderRadius.circular(12),
            ),
            child: const Icon(Icons.people_rounded, color: Colors.white, size: 20),
          ),
          const SizedBox(width: 12),
          const Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text('Wapangaji', style: TextStyle(fontSize: 20, fontWeight: FontWeight.w900, color: Color(0xFF0F172A))),
              Text('Wasimamizi wa wakazi wako', style: TextStyle(fontSize: 11, color: Color(0xFF9CA3AF))),
            ],
          ),
          const Spacer(),
          // SMS Blast
          GestureDetector(
            onTap: () => _showSmsBlastSheet(context),
            child: Container(
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
              decoration: BoxDecoration(
                gradient: const LinearGradient(colors: [AppColors.primary, AppColors.primaryDark]),
                borderRadius: BorderRadius.circular(10),
              ),
              child: const Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Icon(Icons.send_rounded, color: Colors.white, size: 14),
                  SizedBox(width: 5),
                  Text('SMS Zote', style: TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.w700)),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildStatsRow(int paid, int unpaid) {
    return Padding(
      padding: const EdgeInsets.fromLTRB(16, 12, 16, 0),
      child: Row(
        children: [
          _statCard('${_tenants.length}', 'Jumla', const Color(0xFF3B82F6), const Color(0xFFDBEAFE), Icons.group_rounded),
          const SizedBox(width: 8),
          _statCard('$paid', 'Wamelipa', const Color(0xFF10B981), const Color(0xFFDCFCE7), Icons.check_circle_rounded),
          const SizedBox(width: 8),
          _statCard('$unpaid', 'Hawajalipa', const Color(0xFFEF4444), const Color(0xFFFEE2E2), Icons.cancel_rounded),
        ],
      ),
    );
  }

  Widget _statCard(String count, String label, Color color, Color bg, IconData icon) {
    return Expanded(
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 10, horizontal: 12),
        decoration: BoxDecoration(
          color: bg, borderRadius: BorderRadius.circular(12),
          border: Border.all(color: color.withValues(alpha: 0.2)),
        ),
        child: Row(
          children: [
            Icon(icon, color: color, size: 18),
            const SizedBox(width: 8),
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(count, style: TextStyle(color: color, fontWeight: FontWeight.w900, fontSize: 18)),
                Text(label, style: const TextStyle(color: Color(0xFF64748B), fontSize: 10, fontWeight: FontWeight.w500)),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildSearchAndFilter() {
    return Padding(
      padding: const EdgeInsets.fromLTRB(16, 12, 16, 6),
      child: Column(
        children: [
          // Search
          TextField(
            controller: _searchCtrl,
            onChanged: (v) => _load(search: v),
            decoration: InputDecoration(
              hintText: 'Tafuta mpangaji...',
              hintStyle: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 13),
              prefixIcon: const Icon(Icons.search_rounded, color: Color(0xFF9CA3AF), size: 20),
              contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
              border: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: Color(0xFFE2E8F0))),
              enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: Color(0xFFE2E8F0))),
              focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: AppColors.primary, width: 1.5)),
              filled: true, fillColor: Colors.white,
            ),
          ),
          const SizedBox(height: 8),
          // Filter chips
          Row(
            children: [
              _filterChip('all', 'Wote'),
              const SizedBox(width: 8),
              _filterChip('paid', 'Wamelipa'),
              const SizedBox(width: 8),
              _filterChip('unpaid', 'Hawajalipa'),
            ],
          ),
        ],
      ),
    );
  }

  Widget _filterChip(String value, String label) {
    final selected = _filter == value;
    Color color;
    if (value == 'paid') color = const Color(0xFF10B981);
    else if (value == 'unpaid') color = const Color(0xFFEF4444);
    else color = AppColors.primary;

    return GestureDetector(
      onTap: () => setState(() => _filter = value),
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 7),
        decoration: BoxDecoration(
          color: selected ? color.withValues(alpha: 0.1) : Colors.white,
          borderRadius: BorderRadius.circular(20),
          border: Border.all(color: selected ? color : const Color(0xFFE2E8F0), width: 1.5),
        ),
        child: Text(label, style: TextStyle(
          color: selected ? color : const Color(0xFF64748B),
          fontSize: 12, fontWeight: selected ? FontWeight.w700 : FontWeight.w500,
        )),
      ),
    );
  }

  Widget _buildEmpty() {
    return Center(
      child: Column(mainAxisAlignment: MainAxisAlignment.center, children: [
        Container(padding: const EdgeInsets.all(24),
          decoration: BoxDecoration(color: AppColors.primaryLight, shape: BoxShape.circle),
          child: const Icon(Icons.people_outline_rounded, color: AppColors.primary, size: 40)),
        const SizedBox(height: 16),
        const Text('Hakuna wapangaji', style: TextStyle(color: Color(0xFF374151), fontWeight: FontWeight.w700, fontSize: 16)),
        const SizedBox(height: 6),
        const Text('Bonyeza kitufe hapa chini kuongeza mpangaji', style: TextStyle(color: Color(0xFF9CA3AF), fontSize: 13)),
      ]),
    );
  }

  Widget _buildTenantCard(TenantModel t) {
    final isPaid = t.hasPaidThisMonth;
    final initials = t.initials;
    final avatarColors = [
      [const Color(0xFFB44040), const Color(0xFF7E2B2B)],
      [const Color(0xFF3B82F6), const Color(0xFF1D4ED8)],
      [const Color(0xFF10B981), const Color(0xFF047857)],
      [const Color(0xFF8B5CF6), const Color(0xFF6D28D9)],
      [const Color(0xFFF59E0B), const Color(0xFFD97706)],
    ];
    final grad = avatarColors[t.id % avatarColors.length];

    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.04), blurRadius: 10, offset: const Offset(0, 2))],
        border: Border.all(color: const Color(0xFFF1F5F9)),
      ),
      child: Padding(
        padding: const EdgeInsets.all(14),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [

            // ── Top row
            Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Gradient avatar
                Container(
                  width: 50, height: 50,
                  decoration: BoxDecoration(
                    gradient: LinearGradient(colors: grad, begin: Alignment.topLeft, end: Alignment.bottomRight),
                    borderRadius: BorderRadius.circular(14),
                  ),
                  child: Center(child: Text(initials,
                      style: const TextStyle(color: Colors.white, fontWeight: FontWeight.w800, fontSize: 17))),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                    Row(
                      children: [
                        Expanded(child: Text(t.fullName,
                            style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 15, color: Color(0xFF0F172A)),
                            overflow: TextOverflow.ellipsis)),
                        const SizedBox(width: 8),
                        _paymentBadge(isPaid),
                      ],
                    ),
                    const SizedBox(height: 3),
                    Row(children: [
                      const Icon(Icons.phone_rounded, size: 12, color: Color(0xFF9CA3AF)),
                      const SizedBox(width: 4),
                      Text(t.phone, style: const TextStyle(color: Color(0xFF64748B), fontSize: 12)),
                    ]),
                    if (t.propertyTitle != null) ...[
                      const SizedBox(height: 2),
                      Row(children: [
                        const Icon(Icons.home_rounded, size: 12, color: Color(0xFF9CA3AF)),
                        const SizedBox(width: 4),
                        Flexible(child: Text(t.propertyTitle!,
                            style: const TextStyle(color: Color(0xFF94A3B8), fontSize: 11),
                            overflow: TextOverflow.ellipsis)),
                      ]),
                    ],
                  ]),
                ),
              ],
            ),

            // ── Rent + actions
            const SizedBox(height: 12),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
              decoration: BoxDecoration(
                color: const Color(0xFFF8FAFC),
                borderRadius: BorderRadius.circular(10),
                border: Border.all(color: const Color(0xFFE2E8F0)),
              ),
              child: Row(
                children: [
                  if (t.rentAmount != null) ...[
                    const Icon(Icons.payments_rounded, size: 14, color: Color(0xFF9CA3AF)),
                    const SizedBox(width: 6),
                    Text('TZS ${Helpers.formatMoney(t.rentAmount!)}',
                        style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 13, color: Color(0xFF0F172A))),
                    const SizedBox(width: 3),
                    const Text('/mwezi', style: TextStyle(color: Color(0xFF9CA3AF), fontSize: 10)),
                    const Spacer(),
                  ] else
                    const Spacer(),

                  // SMS button
                  GestureDetector(
                    onTap: () => _showSendSmsToTenant(context, t),
                    child: Container(
                      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 7),
                      decoration: BoxDecoration(
                        color: AppColors.primaryLight,
                        borderRadius: BorderRadius.circular(8),
                        border: Border.all(color: AppColors.primary.withValues(alpha: 0.3)),
                      ),
                      child: const Row(mainAxisSize: MainAxisSize.min, children: [
                        Icon(Icons.sms_rounded, size: 14, color: AppColors.primary),
                        SizedBox(width: 5),
                        Text('SMS', style: TextStyle(color: AppColors.primary, fontSize: 11, fontWeight: FontWeight.w700)),
                      ]),
                    ),
                  ),
                  const SizedBox(width: 8),

                  // Status action
                  if (!isPaid)
                    GestureDetector(
                      onTap: () => Helpers.showSnackBar(context, 'Rekodi malipo ya ${t.fullName}'),
                      child: Container(
                        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 7),
                        decoration: BoxDecoration(
                          color: const Color(0xFFFEE2E2),
                          borderRadius: BorderRadius.circular(8),
                          border: Border.all(color: const Color(0xFFEF4444).withValues(alpha: 0.3)),
                        ),
                        child: const Row(mainAxisSize: MainAxisSize.min, children: [
                          Icon(Icons.add_circle_outline_rounded, size: 14, color: Color(0xFFEF4444)),
                          SizedBox(width: 5),
                          Text('Lipa', style: TextStyle(color: Color(0xFFEF4444), fontSize: 11, fontWeight: FontWeight.w700)),
                        ]),
                      ),
                    ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _paymentBadge(bool isPaid) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
      decoration: BoxDecoration(
        color: isPaid ? const Color(0xFFDCFCE7) : const Color(0xFFFEE2E2),
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: isPaid ? const Color(0xFF10B981).withValues(alpha: 0.3) : const Color(0xFFEF4444).withValues(alpha: 0.3)),
      ),
      child: Row(mainAxisSize: MainAxisSize.min, children: [
        Icon(isPaid ? Icons.check_circle_rounded : Icons.warning_rounded,
            size: 10, color: isPaid ? const Color(0xFF10B981) : const Color(0xFFEF4444)),
        const SizedBox(width: 4),
        Text(isPaid ? 'Amelipa' : 'Hajalipa',
            style: TextStyle(color: isPaid ? const Color(0xFF10B981) : const Color(0xFFEF4444),
                fontSize: 10, fontWeight: FontWeight.w800)),
      ]),
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

// ─── Add Tenant Sheet ─────────────────────────────────────────────────────────

class _AddTenantSheet extends StatefulWidget {
  const _AddTenantSheet();
  @override
  State<_AddTenantSheet> createState() => _AddTenantSheetState();
}

class _AddTenantSheetState extends State<_AddTenantSheet> {
  final LandlordService _service = LandlordService();
  int _step = 1;
  bool _saving = false;

  final _firstCtrl    = TextEditingController();
  final _lastCtrl     = TextEditingController();
  final _emailCtrl    = TextEditingController();
  String? _gender;
  final _phoneCtrl    = TextEditingController();
  final _propertyCtrl = TextEditingController();
  final _rentCtrl     = TextEditingController();
  final _depositCtrl  = TextEditingController();
  final _startCtrl    = TextEditingController();

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
        'last_name':  _lastCtrl.text.trim(),
        'name':       '${_firstCtrl.text.trim()} ${_lastCtrl.text.trim()}',
        'email':      _emailCtrl.text.trim(),
        'phone':      '+255${_phoneCtrl.text.trim()}',
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
          Container(margin: const EdgeInsets.only(top: 12), width: 40, height: 4,
            decoration: BoxDecoration(color: const Color(0xFFE5E7EB), borderRadius: BorderRadius.circular(2))),
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 14, 16, 0),
            child: Row(
              children: [
                Container(padding: const EdgeInsets.all(8),
                  decoration: BoxDecoration(
                    gradient: const LinearGradient(colors: [AppColors.primary, AppColors.primaryDark]),
                    borderRadius: BorderRadius.circular(10)),
                  child: const Icon(Icons.person_add_rounded, color: Colors.white, size: 18)),
                const SizedBox(width: 12),
                const Text('Ongeza Mpangaji', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 17)),
                const Spacer(),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                  decoration: BoxDecoration(color: AppColors.primaryLight, borderRadius: BorderRadius.circular(20)),
                  child: Text('Hatua $_step/2', style: const TextStyle(color: AppColors.primary, fontSize: 12, fontWeight: FontWeight.w700)),
                ),
              ],
            ),
          ),
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 10, 16, 8),
            child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
              Text(_step == 1 ? 'Jaza maelezo ya mpangaji' : 'Weka mali na mkataba (hiari)',
                  style: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 12)),
              const SizedBox(height: 8),
              ClipRRect(
                borderRadius: BorderRadius.circular(4),
                child: LinearProgressIndicator(
                  value: _step / 2, minHeight: 5,
                  backgroundColor: const Color(0xFFE5E7EB),
                  valueColor: const AlwaysStoppedAnimation<Color>(AppColors.primary),
                ),
              ),
            ]),
          ),
          Flexible(
            child: SingleChildScrollView(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: _step == 1 ? _buildStep1() : _buildStep2(),
            ),
          ),
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 12, 16, 20),
            child: Row(
              children: [
                if (_step == 2)
                  Expanded(child: OutlinedButton(
                    onPressed: () => setState(() => _step = 1),
                    style: OutlinedButton.styleFrom(
                      side: const BorderSide(color: Color(0xFFE5E7EB)),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                      minimumSize: const Size(0, 50),
                    ),
                    child: const Text('Nyuma', style: TextStyle(color: Color(0xFF374151), fontWeight: FontWeight.w600)),
                  )),
                if (_step == 2) const SizedBox(width: 12),
                Expanded(
                  flex: 2,
                  child: ElevatedButton(
                    onPressed: _saving ? null : () {
                      if (_step == 1) {
                        if (_firstCtrl.text.isEmpty || _phoneCtrl.text.isEmpty) {
                          Helpers.showSnackBar(context, 'Jaza jina na simu ya mpangaji');
                          return;
                        }
                        setState(() => _step = 2);
                      } else { _save(); }
                    },
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppColors.primary, foregroundColor: Colors.white,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                      elevation: 0, minimumSize: const Size(0, 50),
                    ),
                    child: _saving
                        ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                        : Text(_step == 1 ? 'Endelea' : 'Hifadhi',
                            style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 15)),
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
    return Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
      Row(children: [
        Expanded(child: _f('Jina la Kwanza *', _firstCtrl, 'e.g. Amina')),
        const SizedBox(width: 12),
        Expanded(child: _f('Jina la Mwisho', _lastCtrl, 'e.g. Juma')),
      ]),
      const SizedBox(height: 14),
      Row(children: [
        Expanded(child: _f('Barua pepe', _emailCtrl, 'email@mfano.com', keyboard: TextInputType.emailAddress)),
        const SizedBox(width: 12),
        Expanded(child: _drop()),
      ]),
      const SizedBox(height: 14),
      const Text('Nambari ya Simu *', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13, color: Color(0xFF374151))),
      const SizedBox(height: 6),
      _phoneInput(),
      const SizedBox(height: 8),
    ]);
  }

  Widget _buildStep2() {
    return Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
      _f('Mali ID (hiari)', _propertyCtrl, 'e.g. 1', keyboard: TextInputType.number),
      const SizedBox(height: 14),
      Row(children: [
        Expanded(child: _f('Kodi ya Mwezi (TZS)', _rentCtrl, 'e.g. 300000', keyboard: TextInputType.number)),
        const SizedBox(width: 12),
        Expanded(child: _f('Amana (TZS)', _depositCtrl, 'e.g. 600000', keyboard: TextInputType.number)),
      ]),
      const SizedBox(height: 14),
      _f('Tarehe ya Mwanzo wa Mkataba', _startCtrl, 'YYYY-MM-DD'),
      const SizedBox(height: 8),
    ]);
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
        counterText: '',
        prefixIcon: Container(
          margin: const EdgeInsets.all(8),
          padding: const EdgeInsets.symmetric(horizontal: 8),
          decoration: const BoxDecoration(border: Border(right: BorderSide(color: Color(0xFFE5E7EB)))),
          child: const Row(mainAxisSize: MainAxisSize.min, children: [
            Text('🇹🇿', style: TextStyle(fontSize: 16)),
            SizedBox(width: 4),
            Text('+255', style: TextStyle(color: Color(0xFF374151), fontSize: 13, fontWeight: FontWeight.w600)),
          ]),
        ),
        contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
        enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
        focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: AppColors.primary)),
        filled: true, fillColor: Colors.white,
      ),
    );
  }

  Widget _f(String label, TextEditingController ctrl, String hint, {TextInputType? keyboard}) {
    return Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
      Text(label, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13, color: Color(0xFF374151))),
      const SizedBox(height: 6),
      TextField(controller: ctrl, keyboardType: keyboard, style: const TextStyle(fontSize: 14),
        decoration: InputDecoration(hintText: hint,
          hintStyle: const TextStyle(color: Color(0xFFD1D5DB), fontSize: 12),
          contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
          border: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
          enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
          focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: AppColors.primary)),
          filled: true, fillColor: Colors.white,
        )),
    ]);
  }

  Widget _drop() {
    return Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
      const Text('Jinsia', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13, color: Color(0xFF374151))),
      const SizedBox(height: 6),
      DropdownButtonFormField<String>(
        value: _gender,
        hint: const Text('Chagua', style: TextStyle(color: Color(0xFFD1D5DB), fontSize: 13)),
        items: ['Male', 'Female', 'Other'].map((e) => DropdownMenuItem(value: e, child: Text(e, style: const TextStyle(fontSize: 14)))).toList(),
        onChanged: (v) => setState(() => _gender = v),
        decoration: InputDecoration(
          contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
          border: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
          enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
          focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: AppColors.primary)),
          filled: true, fillColor: Colors.white,
        ),
      ),
    ]);
  }
}

// ─── SMS Blast Sheet ──────────────────────────────────────────────────────────

class _SmsBlastSheet extends StatefulWidget {
  final List<TenantModel> tenants;
  const _SmsBlastSheet({required this.tenants});
  @override
  State<_SmsBlastSheet> createState() => _SmsBlastSheetState();
}

class _SmsBlastSheetState extends State<_SmsBlastSheet> {
  final SmsService _sms = SmsService();
  String _target = 'unpaid';
  final _msgCtrl = TextEditingController();
  bool _sending = false;

  final _templates = {
    'paid':   'Habari! Asante kwa kulipa kodi yako kwa mwezi huu. Tunakushukuru sana.',
    'unpaid': 'Habari! Kodi yako bado haijafika. Tafadhali lipa haraka iwezekanavyo ili kuepuka faini.',
  };

  @override
  void initState() {
    super.initState();
    _msgCtrl.text = _templates['unpaid']!;
  }

  @override
  void dispose() { _msgCtrl.dispose(); super.dispose(); }

  Future<void> _send() async {
    if (_msgCtrl.text.isEmpty) { Helpers.showSnackBar(context, 'Andika ujumbe kwanza'); return; }
    setState(() => _sending = true);
    try {
      final res = _target == 'paid'
          ? await _sms.sendToPaidTenants(message: _msgCtrl.text)
          : await _sms.sendToUnpaidTenants(message: _msgCtrl.text);
      if (mounted) {
        final sent = res['sent_count'] ?? res['data']?['sent_count'] ?? 0;
        Helpers.showSnackBar(context, 'SMS imetumwa kwa wapangaji $sent!');
        Navigator.pop(context);
      }
    } catch (e) { if (mounted) Helpers.showSnackBar(context, e.toString()); }
    if (mounted) setState(() => _sending = false);
  }

  @override
  Widget build(BuildContext context) {
    final paidCount = widget.tenants.where((t) => t.hasPaidThisMonth).length;
    final unpaidCount = widget.tenants.length - paidCount;

    return Container(
      decoration: const BoxDecoration(color: Colors.white, borderRadius: BorderRadius.vertical(top: Radius.circular(24))),
      padding: EdgeInsets.only(bottom: MediaQuery.of(context).viewInsets.bottom + 20),
      child: Column(mainAxisSize: MainAxisSize.min, crossAxisAlignment: CrossAxisAlignment.start, children: [
        Container(margin: const EdgeInsets.only(top: 12), alignment: Alignment.center,
          child: Container(width: 40, height: 4, decoration: BoxDecoration(color: const Color(0xFFE5E7EB), borderRadius: BorderRadius.circular(2)))),
        Padding(
          padding: const EdgeInsets.fromLTRB(16, 14, 16, 0),
          child: Row(children: [
            Container(padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                gradient: const LinearGradient(colors: [AppColors.primary, AppColors.primaryDark]),
                borderRadius: BorderRadius.circular(10)),
              child: const Icon(Icons.send_rounded, color: Colors.white, size: 18)),
            const SizedBox(width: 12),
            const Text('Tuma SMS kwa Wapangaji', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 16)),
            const Spacer(),
            GestureDetector(onTap: () => Navigator.pop(context),
              child: Container(width: 32, height: 32,
                decoration: BoxDecoration(color: const Color(0xFFF1F5F9), borderRadius: BorderRadius.circular(8)),
                child: const Icon(Icons.close, color: Color(0xFF64748B), size: 16))),
          ]),
        ),
        const SizedBox(height: 16),
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 16),
          child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
            const Text('Tuma kwa:', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 13, color: Color(0xFF374151))),
            const SizedBox(height: 10),
            Row(children: [
              _targetChip('unpaid', 'Hawajalipa ($unpaidCount)', Icons.warning_rounded, const Color(0xFFEF4444)),
              const SizedBox(width: 8),
              _targetChip('paid', 'Wamelipa ($paidCount)', Icons.check_circle_rounded, const Color(0xFF10B981)),
            ]),
            const SizedBox(height: 16),
            const Text('Ujumbe:', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 13, color: Color(0xFF374151))),
            const SizedBox(height: 8),
            Row(children: [
              _templateBtn('Kodi', _templates['unpaid']!),
              const SizedBox(width: 8),
              _templateBtn('Shukrani', _templates['paid']!),
            ]),
            const SizedBox(height: 10),
            TextField(
              controller: _msgCtrl, maxLines: 4, maxLength: 160, style: const TextStyle(fontSize: 13),
              decoration: InputDecoration(
                hintText: 'Andika ujumbe wako hapa...',
                hintStyle: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 12),
                contentPadding: const EdgeInsets.all(12),
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
                enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
                focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: AppColors.primary)),
                filled: true, fillColor: Colors.white,
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
                label: Text(_sending ? 'Inatuma...' : 'Tuma SMS', style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 15)),
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.primary, foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)), elevation: 0,
                ),
              ),
            ),
          ]),
        ),
      ]),
    );
  }

  Widget _targetChip(String value, String label, IconData icon, Color color) {
    final sel = _target == value;
    return Expanded(
      child: GestureDetector(
        onTap: () { setState(() { _target = value; _msgCtrl.text = value == 'paid' ? _templates['paid']! : _templates['unpaid']!; }); },
        child: Container(
          padding: const EdgeInsets.symmetric(vertical: 10, horizontal: 12),
          decoration: BoxDecoration(
            color: sel ? color.withValues(alpha: 0.1) : const Color(0xFFF9FAFB),
            border: Border.all(color: sel ? color : const Color(0xFFE5E7EB), width: 1.5),
            borderRadius: BorderRadius.circular(10),
          ),
          child: Row(mainAxisAlignment: MainAxisAlignment.center, children: [
            Icon(icon, color: sel ? color : const Color(0xFF9CA3AF), size: 15),
            const SizedBox(width: 6),
            Flexible(child: Text(label, style: TextStyle(color: sel ? color : const Color(0xFF6B7280),
                fontSize: 11, fontWeight: sel ? FontWeight.w700 : FontWeight.w400))),
          ]),
        ),
      ),
    );
  }

  Widget _templateBtn(String label, String template) {
    return GestureDetector(
      onTap: () => setState(() => _msgCtrl.text = template),
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 7),
        decoration: BoxDecoration(color: AppColors.primaryLight, borderRadius: BorderRadius.circular(20)),
        child: Text(label, style: const TextStyle(color: AppColors.primary, fontSize: 11, fontWeight: FontWeight.w700)),
      ),
    );
  }
}

// ─── Send SMS to Single Tenant ────────────────────────────────────────────────

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
  String _type = 'custom';
  final _amountCtrl = TextEditingController();
  final _periodCtrl = TextEditingController();

  @override
  void dispose() { _msgCtrl.dispose(); _amountCtrl.dispose(); _periodCtrl.dispose(); super.dispose(); }

  Future<void> _send() async {
    setState(() => _sending = true);
    try {
      final res = _type == 'custom'
          ? await _sms.sendToTenant(tenantId: widget.tenant.id, message: _msgCtrl.text)
          : await _sms.sendUtilityBill(tenantId: widget.tenant.id, utilityType: _type,
              amount: double.tryParse(_amountCtrl.text) ?? 0, period: _periodCtrl.text,
              customMessage: _msgCtrl.text.isNotEmpty ? _msgCtrl.text : null);
      if (mounted) {
        Helpers.showSnackBar(context, 'SMS imetumwa kwa ${widget.tenant.fullName}!');
        Navigator.pop(context);
      }
    } catch (e) { if (mounted) Helpers.showSnackBar(context, e.toString()); }
    if (mounted) setState(() => _sending = false);
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: const BoxDecoration(color: Colors.white, borderRadius: BorderRadius.vertical(top: Radius.circular(24))),
      padding: EdgeInsets.only(bottom: MediaQuery.of(context).viewInsets.bottom + 20),
      child: Column(mainAxisSize: MainAxisSize.min, crossAxisAlignment: CrossAxisAlignment.start, children: [
        Container(margin: const EdgeInsets.only(top: 12), alignment: Alignment.center,
          child: Container(width: 40, height: 4, decoration: BoxDecoration(color: const Color(0xFFE5E7EB), borderRadius: BorderRadius.circular(2)))),
        Padding(
          padding: const EdgeInsets.fromLTRB(16, 14, 16, 16),
          child: Row(children: [
            Container(width: 44, height: 44,
              decoration: BoxDecoration(
                gradient: const LinearGradient(colors: [AppColors.primary, AppColors.primaryDark]),
                borderRadius: BorderRadius.circular(12)),
              child: Center(child: Text(widget.tenant.initials, style: const TextStyle(color: Colors.white, fontWeight: FontWeight.w800, fontSize: 15)))),
            const SizedBox(width: 12),
            Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
              Text(widget.tenant.fullName, style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 15)),
              Text(widget.tenant.phone, style: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 12)),
            ]),
            const Spacer(),
            GestureDetector(onTap: () => Navigator.pop(context),
              child: Container(width: 32, height: 32,
                decoration: BoxDecoration(color: const Color(0xFFF1F5F9), borderRadius: BorderRadius.circular(8)),
                child: const Icon(Icons.close, color: Color(0xFF64748B), size: 16))),
          ]),
        ),
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 16),
          child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
            const Text('Aina ya SMS:', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 13)),
            const SizedBox(height: 10),
            Row(children: [
              _typeChip('custom', 'Ujumbe', Icons.message_rounded),
              const SizedBox(width: 8),
              _typeChip('electricity', 'Umeme', Icons.bolt_rounded),
              const SizedBox(width: 8),
              _typeChip('water', 'Maji', Icons.water_drop_rounded),
            ]),
            const SizedBox(height: 14),
            if (_type != 'custom') ...[
              Row(children: [
                Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                  const Text('Kiasi (TZS)', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
                  const SizedBox(height: 6),
                  _inp(_amountCtrl, 'e.g. 15000', keyboard: TextInputType.number),
                ])),
                const SizedBox(width: 12),
                Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                  const Text('Kipindi', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
                  const SizedBox(height: 6),
                  _inp(_periodCtrl, 'e.g. Juni 2026'),
                ])),
              ]),
              const SizedBox(height: 14),
            ],
            const Text('Ujumbe (hiari):', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
            const SizedBox(height: 8),
            TextField(
              controller: _msgCtrl, maxLines: 3, style: const TextStyle(fontSize: 13),
              decoration: InputDecoration(
                hintText: _type == 'custom' ? 'Andika ujumbe wako...' : 'Ujumbe wa ziada (hiari)',
                hintStyle: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 12),
                contentPadding: const EdgeInsets.all(12),
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
                enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
                focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: AppColors.primary)),
                filled: true, fillColor: Colors.white,
              ),
            ),
            const SizedBox(height: 14),
            SizedBox(
              width: double.infinity, height: 50,
              child: ElevatedButton.icon(
                onPressed: _sending ? null : _send,
                icon: const Icon(Icons.send_rounded, size: 18),
                label: Text(_sending ? 'Inatuma...' : 'Tuma', style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 15)),
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.primary, foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)), elevation: 0,
                ),
              ),
            ),
          ]),
        ),
        const SizedBox(height: 4),
      ]),
    );
  }

  Widget _typeChip(String value, String label, IconData icon) {
    final sel = _type == value;
    return Expanded(
      child: GestureDetector(
        onTap: () => setState(() => _type = value),
        child: Container(
          padding: const EdgeInsets.symmetric(vertical: 9),
          decoration: BoxDecoration(
            color: sel ? AppColors.primaryLight : const Color(0xFFF9FAFB),
            border: Border.all(color: sel ? AppColors.primary : const Color(0xFFE5E7EB), width: 1.5),
            borderRadius: BorderRadius.circular(10),
          ),
          child: Column(children: [
            Icon(icon, color: sel ? AppColors.primary : const Color(0xFF9CA3AF), size: 18),
            const SizedBox(height: 4),
            Text(label, style: TextStyle(color: sel ? AppColors.primary : const Color(0xFF6B7280),
                fontSize: 11, fontWeight: sel ? FontWeight.w700 : FontWeight.w400)),
          ]),
        ),
      ),
    );
  }

  Widget _inp(TextEditingController ctrl, String hint, {TextInputType? keyboard}) {
    return TextField(controller: ctrl, keyboardType: keyboard, style: const TextStyle(fontSize: 13),
      decoration: InputDecoration(hintText: hint, hintStyle: const TextStyle(color: Color(0xFFD1D5DB), fontSize: 12),
        contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
        enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
        focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: AppColors.primary)),
        filled: true, fillColor: Colors.white,
      ));
  }
}
