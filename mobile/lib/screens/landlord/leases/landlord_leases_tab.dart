import 'package:flutter/material.dart';
import '../../../constants/app_colors.dart';
import '../../../models/lease_model.dart';
import '../../../services/landlord_service.dart';
import '../../../utils/helpers.dart';

class LandlordLeasesTab extends StatefulWidget {
  const LandlordLeasesTab({super.key});

  @override
  State<LandlordLeasesTab> createState() => _LandlordLeasesTabState();
}

class _LandlordLeasesTabState extends State<LandlordLeasesTab> with SingleTickerProviderStateMixin {
  final LandlordService _service = LandlordService();
  late TabController _tabCtrl;
  List<LeaseModel> _all = [], _active = [], _expired = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _tabCtrl = TabController(length: 3, vsync: this);
    _load();
  }

  @override
  void dispose() {
    _tabCtrl.dispose();
    super.dispose();
  }

  Future<void> _load() async {
    setState(() => _isLoading = true);
    try {
      final res = await _service.getLeases();
      if (res['data'] != null) {
        final list = res['data'] is List ? res['data'] : (res['data']['data'] ?? []);
        final all = (list as List).map((j) => LeaseModel.fromJson(j)).toList();
        setState(() {
          _all = all;
          _active = all.where((l) => l.status == 'active').toList();
          _expired = all.where((l) => l.status != 'active').toList();
        });
      }
    } catch (_) {}
    if (mounted) setState(() => _isLoading = false);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF4F6F8),
      body: SafeArea(
        child: Column(
          children: [
            _buildHeader(context),
            _buildTabs(),
            Expanded(
              child: _isLoading
                  ? const Center(child: CircularProgressIndicator(color: AppColors.primary))
                  : TabBarView(
                      controller: _tabCtrl,
                      children: [
                        _buildList(_active),
                        _buildList(_expired),
                        _buildList(_all),
                      ],
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
          const Text('Leases', style: TextStyle(fontSize: 22, fontWeight: FontWeight.w800)),
          const Spacer(),
          GestureDetector(
            onTap: () => _showAddLeaseSheet(context),
            child: Container(
              width: 36, height: 36,
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

  Widget _buildTabs() {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 16),
      decoration: BoxDecoration(
        color: const Color(0xFFE5E7EB),
        borderRadius: BorderRadius.circular(10),
      ),
      child: TabBar(
        controller: _tabCtrl,
        labelColor: Colors.white,
        unselectedLabelColor: const Color(0xFF6B7280),
        labelStyle: const TextStyle(fontWeight: FontWeight.w700, fontSize: 12),
        unselectedLabelStyle: const TextStyle(fontSize: 12),
        indicator: BoxDecoration(
          color: AppColors.primary,
          borderRadius: BorderRadius.circular(8),
        ),
        indicatorSize: TabBarIndicatorSize.tab,
        dividerColor: Colors.transparent,
        tabs: [
          Tab(text: 'Active (${_active.length})'),
          Tab(text: 'Expired (${_expired.length})'),
          Tab(text: 'All (${_all.length})'),
        ],
      ),
    );
  }

  Widget _buildList(List<LeaseModel> leases) {
    if (leases.isEmpty) {
      return const Center(child: Text('No leases found.', style: TextStyle(color: Color(0xFF9CA3AF), fontSize: 15)));
    }
    return RefreshIndicator(
      onRefresh: _load,
      color: AppColors.primary,
      child: ListView.builder(
        padding: const EdgeInsets.fromLTRB(16, 10, 16, 100),
        itemCount: leases.length,
        itemBuilder: (ctx, i) => _buildLeaseCard(leases[i]),
      ),
    );
  }

  Widget _buildLeaseCard(LeaseModel l) {
    final statusColor = l.isActive ? const Color(0xFF10B981) : AppColors.primary;
    final daysLeft = l.isActive
        ? DateTime.tryParse(l.endDate)?.difference(DateTime.now()).inDays
        : null;

    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(14),
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.04), blurRadius: 6)],
      ),
      child: Padding(
        padding: const EdgeInsets.all(14),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Expanded(
                  child: Text(
                    l.propertyTitle ?? 'Property',
                    style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 15),
                  ),
                ),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                  decoration: BoxDecoration(
                    color: statusColor.withOpacity(0.1),
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: Text(l.statusLabel, style: TextStyle(color: statusColor, fontSize: 11, fontWeight: FontWeight.w600)),
                ),
              ],
            ),
            const SizedBox(height: 6),
            Row(
              children: [
                const Icon(Icons.person_outline, size: 14, color: Color(0xFF9CA3AF)),
                const SizedBox(width: 4),
                Text(l.tenantName ?? 'Tenant', style: const TextStyle(color: Color(0xFF6B7280), fontSize: 12)),
                const SizedBox(width: 8),
                const Icon(Icons.phone_outlined, size: 14, color: Color(0xFF9CA3AF)),
                const SizedBox(width: 4),
                Text(l.tenantPhone ?? '', style: const TextStyle(color: Color(0xFF6B7280), fontSize: 12)),
              ],
            ),
            const SizedBox(height: 8),
            const Divider(height: 1, color: Color(0xFFF3F4F6)),
            const SizedBox(height: 8),
            Row(
              children: [
                _leaseInfo('Kodi', 'TZS ${Helpers.formatMoney(l.rentAmount)}'),
                _leaseInfo('Mwanzo', l.startDate.length >= 10 ? l.startDate.substring(0, 10) : l.startDate),
                _leaseInfo('Mwisho', l.endDate.length >= 10 ? l.endDate.substring(0, 10) : l.endDate),
                if (daysLeft != null)
                  _leaseInfo('Siku', '$daysLeft', color: daysLeft < 30 ? const Color(0xFFEF4444) : null),
              ],
            ),
            if (l.isActive) ...[
              const SizedBox(height: 10),
              Row(
                children: [
                  _actionBtn('Malipo', Icons.payments_outlined, () {
                    Helpers.showSnackBar(context, 'Rekodi malipo ya ${l.tenantName}');
                  }),
                  const SizedBox(width: 8),
                  _actionBtn('Katisha', Icons.cancel_outlined, () {
                    _confirmTerminate(context, l);
                  }, isDestructive: true),
                ],
              ),
            ],
          ],
        ),
      ),
    );
  }

  Widget _leaseInfo(String label, String value, {Color? color}) {
    return Expanded(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(label, style: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 10)),
          const SizedBox(height: 2),
          Text(value, style: TextStyle(fontWeight: FontWeight.w700, fontSize: 12, color: color ?? const Color(0xFF374151))),
        ],
      ),
    );
  }

  Widget _actionBtn(String label, IconData icon, VoidCallback onTap, {bool isDestructive = false}) {
    return Expanded(
      child: GestureDetector(
        onTap: onTap,
        child: Container(
          padding: const EdgeInsets.symmetric(vertical: 8),
          decoration: BoxDecoration(
            color: isDestructive ? const Color(0xFFEF4444).withOpacity(0.08) : AppColors.primaryLight,
            borderRadius: BorderRadius.circular(8),
            border: Border.all(color: isDestructive ? const Color(0xFFEF4444).withOpacity(0.3) : AppColors.primary.withOpacity(0.3)),
          ),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Icon(icon, size: 14, color: isDestructive ? const Color(0xFFEF4444) : AppColors.primary),
              const SizedBox(width: 5),
              Text(label, style: TextStyle(
                fontSize: 12, fontWeight: FontWeight.w600,
                color: isDestructive ? const Color(0xFFEF4444) : AppColors.primary,
              )),
            ],
          ),
        ),
      ),
    );
  }

  void _confirmTerminate(BuildContext context, LeaseModel lease) {
    final reasonCtrl = TextEditingController();
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        title: const Text('Katisha Mkataba', style: TextStyle(fontWeight: FontWeight.w700)),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Text('Una uhakika wa kukatisha mkataba wa ${lease.tenantName}?'),
            const SizedBox(height: 12),
            TextField(
              controller: reasonCtrl,
              decoration: const InputDecoration(
                hintText: 'Sababu ya kukatisha...',
                border: OutlineInputBorder(),
              ),
              maxLines: 2,
            ),
          ],
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('Hapana')),
          ElevatedButton(
            onPressed: () async {
              Navigator.pop(ctx);
              try {
                await _service.terminateLease(lease.id, reasonCtrl.text);
                Helpers.showSnackBar(context, 'Mkataba umekatishwa');
                _load();
              } catch (e) {
                Helpers.showSnackBar(context, e.toString());
              }
            },
            style: ElevatedButton.styleFrom(backgroundColor: const Color(0xFFEF4444), foregroundColor: Colors.white),
            child: const Text('Ndio, Katisha'),
          ),
        ],
      ),
    );
  }

  void _showAddLeaseSheet(BuildContext context) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (_) => const _AddLeaseSheet(),
    ).then((_) => _load());
  }
}

// ─── Add Lease Sheet ─────────────────────────────────────────────────────────

class _AddLeaseSheet extends StatefulWidget {
  const _AddLeaseSheet();
  @override
  State<_AddLeaseSheet> createState() => _AddLeaseSheetState();
}

class _AddLeaseSheetState extends State<_AddLeaseSheet> {
  final LandlordService _service = LandlordService();
  bool _saving = false;

  final _propertyIdCtrl = TextEditingController();
  final _tenantIdCtrl = TextEditingController();
  final _rentCtrl = TextEditingController();
  final _depositCtrl = TextEditingController();
  final _startCtrl = TextEditingController();
  final _endCtrl = TextEditingController();
  String _paymentFreq = 'monthly';
  int _dueDay = 1;

  @override
  void dispose() {
    _propertyIdCtrl.dispose(); _tenantIdCtrl.dispose();
    _rentCtrl.dispose(); _depositCtrl.dispose();
    _startCtrl.dispose(); _endCtrl.dispose();
    super.dispose();
  }

  Future<void> _save() async {
    if (_propertyIdCtrl.text.isEmpty || _tenantIdCtrl.text.isEmpty || _rentCtrl.text.isEmpty) {
      Helpers.showSnackBar(context, 'Jaza sehemu zote zinazohitajika');
      return;
    }
    setState(() => _saving = true);
    try {
      final res = await _service.createLease({
        'property_id': int.tryParse(_propertyIdCtrl.text) ?? 0,
        'tenant_id': int.tryParse(_tenantIdCtrl.text) ?? 0,
        'rent_amount': double.tryParse(_rentCtrl.text) ?? 0,
        'deposit_amount': double.tryParse(_depositCtrl.text) ?? 0,
        'start_date': _startCtrl.text.trim(),
        'end_date': _endCtrl.text.trim(),
        'payment_frequency': _paymentFreq,
        'due_day': _dueDay,
        'currency': 'TZS',
      });
      if (mounted) {
        if (res['success'] == true) {
          Helpers.showSnackBar(context, 'Mkataba umeundwa!');
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
      padding: EdgeInsets.only(bottom: MediaQuery.of(context).viewInsets.bottom + 20),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            margin: const EdgeInsets.only(top: 10),
            alignment: Alignment.center,
            child: Container(width: 40, height: 4, decoration: BoxDecoration(color: const Color(0xFFE5E7EB), borderRadius: BorderRadius.circular(2))),
          ),
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 14, 16, 0),
            child: Row(
              children: [
                GestureDetector(
                  onTap: () => Navigator.pop(context),
                  child: Container(width: 34, height: 34, decoration: BoxDecoration(color: AppColors.primaryLight, borderRadius: BorderRadius.circular(10)), child: const Icon(Icons.close, color: AppColors.primary, size: 18)),
                ),
                const SizedBox(width: 12),
                const Text('Unda Mkataba Mpya', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 17)),
              ],
            ),
          ),
          const SizedBox(height: 16),
          Flexible(
            child: SingleChildScrollView(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(children: [
                    Expanded(child: _field('Property ID *', _propertyIdCtrl, 'e.g. 1', keyboard: TextInputType.number)),
                    const SizedBox(width: 12),
                    Expanded(child: _field('Tenant ID *', _tenantIdCtrl, 'e.g. 5', keyboard: TextInputType.number)),
                  ]),
                  const SizedBox(height: 14),
                  Row(children: [
                    Expanded(child: _field('Kodi ya Mwezi (TZS) *', _rentCtrl, 'e.g. 300000', keyboard: TextInputType.number)),
                    const SizedBox(width: 12),
                    Expanded(child: _field('Amana (TZS)', _depositCtrl, 'e.g. 600000', keyboard: TextInputType.number)),
                  ]),
                  const SizedBox(height: 14),
                  Row(children: [
                    Expanded(child: _field('Tarehe ya Mwanzo', _startCtrl, 'YYYY-MM-DD')),
                    const SizedBox(width: 12),
                    Expanded(child: _field('Tarehe ya Mwisho', _endCtrl, 'YYYY-MM-DD')),
                  ]),
                  const SizedBox(height: 14),
                  const Text('Malipo ya:', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
                  const SizedBox(height: 8),
                  Row(children: [
                    _freqChip('monthly', 'Kila Mwezi'),
                    const SizedBox(width: 8),
                    _freqChip('quarterly', 'Kila Robo'),
                    const SizedBox(width: 8),
                    _freqChip('yearly', 'Kila Mwaka'),
                  ]),
                  const SizedBox(height: 14),
                  Text('Siku ya kufika kodi (siku $_dueDay ya mwezi)', style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
                  Slider(
                    value: _dueDay.toDouble(),
                    min: 1, max: 31,
                    divisions: 30,
                    activeColor: AppColors.primary,
                    label: '$_dueDay',
                    onChanged: (v) => setState(() => _dueDay = v.round()),
                  ),
                  const SizedBox(height: 8),
                ],
              ),
            ),
          ),
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 8, 16, 0),
            child: Column(
              children: [
                SizedBox(
                  width: double.infinity, height: 50,
                  child: ElevatedButton(
                    onPressed: _saving ? null : _save,
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppColors.primary, foregroundColor: Colors.white,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)), elevation: 0,
                    ),
                    child: _saving
                        ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                        : const Text('Hifadhi Mkataba', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 15)),
                  ),
                ),
                const SizedBox(height: 8),
                SizedBox(
                  width: double.infinity, height: 50,
                  child: OutlinedButton(
                    onPressed: () => Navigator.pop(context),
                    style: OutlinedButton.styleFrom(side: const BorderSide(color: Color(0xFFE5E7EB)), shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12))),
                    child: const Text('Ghairi', style: TextStyle(color: Color(0xFF374151), fontWeight: FontWeight.w600)),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _field(String label, TextEditingController ctrl, String hint, {TextInputType? keyboard}) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(label, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13, color: Color(0xFF374151))),
        const SizedBox(height: 6),
        TextField(
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
        ),
      ],
    );
  }

  Widget _freqChip(String value, String label) {
    final sel = _paymentFreq == value;
    return Expanded(
      child: GestureDetector(
        onTap: () => setState(() => _paymentFreq = value),
        child: Container(
          padding: const EdgeInsets.symmetric(vertical: 8),
          decoration: BoxDecoration(
            color: sel ? AppColors.primaryLight : const Color(0xFFF9FAFB),
            border: Border.all(color: sel ? AppColors.primary : const Color(0xFFE5E7EB), width: 1.5),
            borderRadius: BorderRadius.circular(8),
          ),
          child: Text(label, textAlign: TextAlign.center, style: TextStyle(color: sel ? AppColors.primary : const Color(0xFF6B7280), fontSize: 11, fontWeight: sel ? FontWeight.w700 : FontWeight.w400)),
        ),
      ),
    );
  }
}
