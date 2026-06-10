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
      backgroundColor: const Color(0xFFF1F5F9),
      body: SafeArea(
        child: Column(
          children: [
            _buildHeader(context),
            _buildSummaryRow(),
            const SizedBox(height: 12),
            _buildTabBar(),
            const SizedBox(height: 2),
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
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () => _showAddLeaseSheet(context),
        backgroundColor: AppColors.primary,
        elevation: 4,
        icon: const Icon(Icons.add_rounded, color: Colors.white),
        label: const Text('Mkataba Mpya', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w700)),
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
            child: const Icon(Icons.description_rounded, color: Colors.white, size: 20),
          ),
          const SizedBox(width: 12),
          const Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text('Mikataba', style: TextStyle(fontSize: 20, fontWeight: FontWeight.w900, color: Color(0xFF0F172A))),
              Text('Usimamizi wa mikataba yako', style: TextStyle(fontSize: 11, color: Color(0xFF9CA3AF))),
            ],
          ),
          const Spacer(),
          GestureDetector(
            onTap: _load,
            child: Container(
              width: 38, height: 38,
              decoration: BoxDecoration(
                color: Colors.white, borderRadius: BorderRadius.circular(11),
                border: Border.all(color: const Color(0xFFE2E8F0)),
              ),
              child: const Icon(Icons.refresh_rounded, color: Color(0xFF64748B), size: 18),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSummaryRow() {
    final expiringSoon = _active.where((l) {
      final end = DateTime.tryParse(l.endDate);
      if (end == null) return false;
      return end.difference(DateTime.now()).inDays <= 30;
    }).length;

    return Padding(
      padding: const EdgeInsets.fromLTRB(16, 12, 16, 0),
      child: Row(
        children: [
          _summaryChip('${_active.length}', 'Hai', const Color(0xFF10B981), const Color(0xFFDCFCE7), Icons.play_circle_rounded),
          const SizedBox(width: 8),
          _summaryChip('${_expired.length}', 'Zilizokwisha', const Color(0xFF64748B), const Color(0xFFF1F5F9), Icons.stop_circle_rounded),
          const SizedBox(width: 8),
          _summaryChip('$expiringSoon', 'Zinaisha Hivi Karibuni', const Color(0xFFF59E0B), const Color(0xFFFEF9C3), Icons.warning_rounded),
        ],
      ),
    );
  }

  Widget _summaryChip(String count, String label, Color color, Color bg, IconData icon) {
    return Expanded(
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 10, horizontal: 10),
        decoration: BoxDecoration(color: bg, borderRadius: BorderRadius.circular(12),
          border: Border.all(color: color.withValues(alpha: 0.2))),
        child: Row(
          children: [
            Icon(icon, color: color, size: 16),
            const SizedBox(width: 6),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(count, style: TextStyle(color: color, fontWeight: FontWeight.w900, fontSize: 16)),
                  Text(label, style: const TextStyle(color: Color(0xFF64748B), fontSize: 9, fontWeight: FontWeight.w500),
                      overflow: TextOverflow.ellipsis),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildTabBar() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(color: const Color(0xFFE2E8F0)),
        ),
        child: TabBar(
          controller: _tabCtrl,
          labelColor: Colors.white,
          unselectedLabelColor: const Color(0xFF64748B),
          labelStyle: const TextStyle(fontWeight: FontWeight.w700, fontSize: 12),
          unselectedLabelStyle: const TextStyle(fontWeight: FontWeight.w500, fontSize: 12),
          indicator: BoxDecoration(
            gradient: const LinearGradient(colors: [AppColors.primary, AppColors.primaryDark]),
            borderRadius: BorderRadius.circular(10),
          ),
          indicatorSize: TabBarIndicatorSize.tab,
          dividerColor: Colors.transparent,
          padding: const EdgeInsets.all(4),
          tabs: [
            Tab(text: 'Hai (${_active.length})'),
            Tab(text: 'Zilishaa (${_expired.length})'),
            Tab(text: 'Zote (${_all.length})'),
          ],
        ),
      ),
    );
  }

  Widget _buildList(List<LeaseModel> leases) {
    if (leases.isEmpty) {
      return Center(
        child: Column(mainAxisAlignment: MainAxisAlignment.center, children: [
          Container(padding: const EdgeInsets.all(24),
            decoration: BoxDecoration(color: AppColors.primaryLight, shape: BoxShape.circle),
            child: const Icon(Icons.description_outlined, color: AppColors.primary, size: 40)),
          const SizedBox(height: 16),
          const Text('Hakuna mikataba hapa', style: TextStyle(color: Color(0xFF374151), fontWeight: FontWeight.w700, fontSize: 16)),
          const SizedBox(height: 6),
          const Text('Bonyeza kitufe hapa chini kuunda mkataba mpya', style: TextStyle(color: Color(0xFF9CA3AF), fontSize: 13)),
        ]),
      );
    }
    return RefreshIndicator(
      onRefresh: _load,
      color: AppColors.primary,
      child: ListView.builder(
        padding: const EdgeInsets.fromLTRB(16, 12, 16, 100),
        itemCount: leases.length,
        itemBuilder: (ctx, i) => _buildLeaseCard(leases[i]),
      ),
    );
  }

  Widget _buildLeaseCard(LeaseModel l) {
    final isActive = l.isActive;
    final startDate = DateTime.tryParse(l.startDate);
    final endDate = DateTime.tryParse(l.endDate);
    final now = DateTime.now();

    final daysLeft = endDate != null ? endDate.difference(now).inDays : null;
    final totalDays = (startDate != null && endDate != null) ? endDate.difference(startDate).inDays : null;
    final daysElapsed = (startDate != null) ? now.difference(startDate).inDays : null;
    final progress = (totalDays != null && totalDays > 0 && daysElapsed != null)
        ? (daysElapsed / totalDays).clamp(0.0, 1.0)
        : 0.0;

    Color statusColor;
    Color statusBg;
    String statusLabel;
    if (!isActive) {
      statusColor = const Color(0xFF64748B);
      statusBg = const Color(0xFFF1F5F9);
      statusLabel = 'Imekwisha';
    } else if (daysLeft != null && daysLeft <= 7) {
      statusColor = const Color(0xFFEF4444);
      statusBg = const Color(0xFFFEE2E2);
      statusLabel = 'Inaisha Hivi Karibuni';
    } else if (daysLeft != null && daysLeft <= 30) {
      statusColor = const Color(0xFFF59E0B);
      statusBg = const Color(0xFFFEF9C3);
      statusLabel = 'Inaisha Karibuni';
    } else {
      statusColor = const Color(0xFF10B981);
      statusBg = const Color(0xFFDCFCE7);
      statusLabel = 'Hai';
    }

    return Container(
      margin: const EdgeInsets.only(bottom: 14),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(18),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.05), blurRadius: 12, offset: const Offset(0, 3))],
        border: Border(left: BorderSide(color: statusColor, width: 4)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [

          // ── Top section
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 14, 14, 0),
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Container(
                  width: 46, height: 46,
                  decoration: BoxDecoration(
                    color: statusBg,
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: Icon(Icons.home_work_rounded, color: statusColor, size: 22),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(l.propertyTitle ?? 'Mali', overflow: TextOverflow.ellipsis,
                          style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 15, color: Color(0xFF0F172A))),
                      const SizedBox(height: 3),
                      Row(children: [
                        const Icon(Icons.person_rounded, size: 13, color: Color(0xFF9CA3AF)),
                        const SizedBox(width: 4),
                        Flexible(child: Text(l.tenantName ?? 'Mpangaji',
                            style: const TextStyle(color: Color(0xFF64748B), fontSize: 12, fontWeight: FontWeight.w600))),
                      ]),
                      if (l.tenantPhone != null && l.tenantPhone!.isNotEmpty) ...[
                        const SizedBox(height: 2),
                        Row(children: [
                          const Icon(Icons.phone_rounded, size: 12, color: Color(0xFF9CA3AF)),
                          const SizedBox(width: 4),
                          Text(l.tenantPhone!, style: const TextStyle(color: Color(0xFF94A3B8), fontSize: 11)),
                        ]),
                      ],
                    ],
                  ),
                ),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                  decoration: BoxDecoration(
                    color: statusBg, borderRadius: BorderRadius.circular(20),
                    border: Border.all(color: statusColor.withValues(alpha: 0.3)),
                  ),
                  child: Text(statusLabel, style: TextStyle(color: statusColor, fontSize: 10, fontWeight: FontWeight.w800)),
                ),
              ],
            ),
          ),

          const SizedBox(height: 14),

          // ── Rent + key info row
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16),
            child: Container(
              padding: const EdgeInsets.all(14),
              decoration: BoxDecoration(
                color: const Color(0xFFF8FAFC),
                borderRadius: BorderRadius.circular(12),
                border: Border.all(color: const Color(0xFFE2E8F0)),
              ),
              child: Row(
                children: [
                  Expanded(child: _infoBox('Kodi ya Mwezi', 'TZS ${Helpers.formatMoney(l.rentAmount)}',
                      const Color(0xFFB44040), bold: true)),
                  _divider(),
                  Expanded(child: _infoBox('Mwanzo', _fmtDate(l.startDate), const Color(0xFF374151))),
                  _divider(),
                  Expanded(child: _infoBox('Mwisho', _fmtDate(l.endDate), const Color(0xFF374151))),
                  if (isActive && daysLeft != null) ...[
                    _divider(),
                    Expanded(child: _infoBox('Siku Zimebaki',
                        '$daysLeft siku',
                        daysLeft <= 7 ? const Color(0xFFEF4444)
                            : daysLeft <= 30 ? const Color(0xFFF59E0B)
                            : const Color(0xFF10B981))),
                  ],
                ],
              ),
            ),
          ),

          // ── Progress bar (active leases only)
          if (isActive && totalDays != null && totalDays > 0) ...[
            const SizedBox(height: 12),
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text('Muda wa Mkataba Uliokwisha',
                          style: const TextStyle(fontSize: 10, color: Color(0xFF9CA3AF), fontWeight: FontWeight.w600)),
                      Text('${(progress * 100).toStringAsFixed(0)}%',
                          style: TextStyle(fontSize: 11, fontWeight: FontWeight.w800, color: statusColor)),
                    ],
                  ),
                  const SizedBox(height: 5),
                  ClipRRect(
                    borderRadius: BorderRadius.circular(6),
                    child: LinearProgressIndicator(
                      value: progress, minHeight: 6,
                      backgroundColor: const Color(0xFFF1F5F9),
                      valueColor: AlwaysStoppedAnimation<Color>(statusColor),
                    ),
                  ),
                ],
              ),
            ),
          ],

          // ── Action buttons (active only)
          if (isActive) ...[
            const SizedBox(height: 12),
            Padding(
              padding: const EdgeInsets.fromLTRB(16, 0, 16, 14),
              child: Row(
                children: [
                  Expanded(child: _actionBtn('Rekodi Malipo', Icons.payments_rounded, const Color(0xFF10B981),
                      const Color(0xFFDCFCE7), () => Helpers.showSnackBar(context, 'Rekodi malipo ya ${l.tenantName}'))),
                  const SizedBox(width: 10),
                  Expanded(child: _actionBtn('Katisha', Icons.cancel_rounded, const Color(0xFFEF4444),
                      const Color(0xFFFEE2E2), () => _confirmTerminate(context, l))),
                ],
              ),
            ),
          ] else
            const SizedBox(height: 14),
        ],
      ),
    );
  }

  Widget _infoBox(String label, String value, Color valueColor, {bool bold = false}) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.center,
      children: [
        Text(label, style: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 9, fontWeight: FontWeight.w600),
            textAlign: TextAlign.center),
        const SizedBox(height: 3),
        Text(value, style: TextStyle(color: valueColor, fontWeight: bold ? FontWeight.w900 : FontWeight.w700, fontSize: bold ? 13 : 11),
            textAlign: TextAlign.center, overflow: TextOverflow.ellipsis),
      ],
    );
  }

  Widget _divider() => Container(width: 1, height: 30, color: const Color(0xFFE2E8F0), margin: const EdgeInsets.symmetric(horizontal: 2));

  Widget _actionBtn(String label, IconData icon, Color color, Color bg, VoidCallback onTap) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 10),
        decoration: BoxDecoration(
          color: bg, borderRadius: BorderRadius.circular(10),
          border: Border.all(color: color.withValues(alpha: 0.3)),
        ),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, size: 15, color: color),
            const SizedBox(width: 6),
            Text(label, style: TextStyle(fontSize: 12, fontWeight: FontWeight.w700, color: color)),
          ],
        ),
      ),
    );
  }

  String _fmtDate(String raw) {
    final dt = DateTime.tryParse(raw);
    if (dt == null) return raw;
    return '${dt.day}/${dt.month}/${dt.year.toString().substring(2)}';
  }

  void _confirmTerminate(BuildContext context, LeaseModel lease) {
    final reasonCtrl = TextEditingController();
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        title: Row(children: [
          Container(padding: const EdgeInsets.all(8),
            decoration: BoxDecoration(color: const Color(0xFFFEE2E2), borderRadius: BorderRadius.circular(10)),
            child: const Icon(Icons.cancel_rounded, color: Color(0xFFEF4444), size: 20)),
          const SizedBox(width: 10),
          const Text('Katisha Mkataba', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 16)),
        ]),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Una uhakika wa kukatisha mkataba wa ${lease.tenantName} kwa ${lease.propertyTitle}?',
                style: const TextStyle(color: Color(0xFF374151), fontSize: 13)),
            const SizedBox(height: 14),
            const Text('Sababu:', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
            const SizedBox(height: 8),
            TextField(
              controller: reasonCtrl,
              decoration: InputDecoration(
                hintText: 'Sababu ya kukatisha...',
                hintStyle: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 12),
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
                contentPadding: const EdgeInsets.all(12),
                filled: true, fillColor: const Color(0xFFF9FAFB),
              ),
              maxLines: 2,
            ),
          ],
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx),
              child: const Text('Hapana', style: TextStyle(color: Color(0xFF64748B)))),
          ElevatedButton(
            onPressed: () async {
              Navigator.pop(ctx);
              try {
                await _service.terminateLease(lease.id, reasonCtrl.text);
                if (mounted) Helpers.showSnackBar(context, 'Mkataba umekatishwa');
                _load();
              } catch (e) {
                if (mounted) Helpers.showSnackBar(context, e.toString());
              }
            },
            style: ElevatedButton.styleFrom(backgroundColor: const Color(0xFFEF4444),
                foregroundColor: Colors.white, elevation: 0,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10))),
            child: const Text('Ndio, Katisha', style: TextStyle(fontWeight: FontWeight.w700)),
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

// ─── Add Lease Sheet ──────────────────────────────────────────────────────────

class _AddLeaseSheet extends StatefulWidget {
  const _AddLeaseSheet();
  @override
  State<_AddLeaseSheet> createState() => _AddLeaseSheetState();
}

class _AddLeaseSheetState extends State<_AddLeaseSheet> {
  final LandlordService _service = LandlordService();
  bool _saving = false;

  final _propertyIdCtrl = TextEditingController();
  final _tenantIdCtrl   = TextEditingController();
  final _rentCtrl       = TextEditingController();
  final _depositCtrl    = TextEditingController();
  final _startCtrl      = TextEditingController();
  final _endCtrl        = TextEditingController();
  String _paymentFreq   = 'monthly';
  int _dueDay = 1;

  @override
  void dispose() {
    _propertyIdCtrl.dispose(); _tenantIdCtrl.dispose();
    _rentCtrl.dispose(); _depositCtrl.dispose();
    _startCtrl.dispose(); _endCtrl.dispose();
    super.dispose();
  }

  Future<void> _pickDate(TextEditingController ctrl) async {
    final picked = await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: DateTime(2020),
      lastDate: DateTime(2035),
      builder: (ctx, child) => Theme(
        data: Theme.of(ctx).copyWith(colorScheme: const ColorScheme.light(primary: AppColors.primary)),
        child: child!,
      ),
    );
    if (picked != null) {
      ctrl.text = '${picked.year}-${picked.month.toString().padLeft(2,'0')}-${picked.day.toString().padLeft(2,'0')}';
    }
  }

  Future<void> _save() async {
    if (_propertyIdCtrl.text.isEmpty || _tenantIdCtrl.text.isEmpty || _rentCtrl.text.isEmpty) {
      Helpers.showSnackBar(context, 'Jaza sehemu zote zinazohitajika');
      return;
    }
    setState(() => _saving = true);
    try {
      final res = await _service.createLease({
        'property_id':       int.tryParse(_propertyIdCtrl.text) ?? 0,
        'tenant_id':         int.tryParse(_tenantIdCtrl.text) ?? 0,
        'rent_amount':       double.tryParse(_rentCtrl.text) ?? 0,
        'deposit_amount':    double.tryParse(_depositCtrl.text) ?? 0,
        'start_date':        _startCtrl.text.trim(),
        'end_date':          _endCtrl.text.trim(),
        'payment_frequency': _paymentFreq,
        'due_day':           _dueDay,
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
          // Handle
          Container(margin: const EdgeInsets.only(top: 12), width: 40, height: 4,
            decoration: BoxDecoration(color: const Color(0xFFE5E7EB), borderRadius: BorderRadius.circular(2))),
          // Header
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 14, 16, 0),
            child: Row(
              children: [
                Container(padding: const EdgeInsets.all(8),
                  decoration: BoxDecoration(
                    gradient: const LinearGradient(colors: [AppColors.primary, AppColors.primaryDark]),
                    borderRadius: BorderRadius.circular(10)),
                  child: const Icon(Icons.description_rounded, color: Colors.white, size: 18)),
                const SizedBox(width: 12),
                const Text('Unda Mkataba Mpya', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 17)),
                const Spacer(),
                GestureDetector(
                  onTap: () => Navigator.pop(context),
                  child: Container(width: 32, height: 32,
                    decoration: BoxDecoration(color: const Color(0xFFF1F5F9), borderRadius: BorderRadius.circular(8)),
                    child: const Icon(Icons.close, color: Color(0xFF64748B), size: 16)),
                ),
              ],
            ),
          ),
          const SizedBox(height: 16),
          Flexible(
            child: SingleChildScrollView(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
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
                  Expanded(child: _datePicker('Tarehe ya Mwanzo', _startCtrl)),
                  const SizedBox(width: 12),
                  Expanded(child: _datePicker('Tarehe ya Mwisho', _endCtrl)),
                ]),
                const SizedBox(height: 14),
                const Text('Malipo ya:', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 13, color: Color(0xFF374151))),
                const SizedBox(height: 8),
                Row(children: [
                  _freqChip('monthly', 'Kila Mwezi'),
                  const SizedBox(width: 8),
                  _freqChip('quarterly', 'Kila Robo'),
                  const SizedBox(width: 8),
                  _freqChip('yearly', 'Kila Mwaka'),
                ]),
                const SizedBox(height: 14),
                Text('Siku ya kufika kodi: Siku $_dueDay ya mwezi',
                    style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13, color: Color(0xFF374151))),
                Slider(
                  value: _dueDay.toDouble(), min: 1, max: 31, divisions: 30,
                  activeColor: AppColors.primary,
                  label: '$_dueDay',
                  onChanged: (v) => setState(() => _dueDay = v.round()),
                ),
              ]),
            ),
          ),
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 8, 16, 0),
            child: SizedBox(
              width: double.infinity, height: 50,
              child: ElevatedButton(
                onPressed: _saving ? null : _save,
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.primary, foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)), elevation: 0,
                ),
                child: _saving
                    ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                    : const Text('Hifadhi Mkataba', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 15)),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _datePicker(String label, TextEditingController ctrl) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(label, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13, color: Color(0xFF374151))),
        const SizedBox(height: 6),
        GestureDetector(
          onTap: () => _pickDate(ctrl),
          child: AbsorbPointer(
            child: TextField(
              controller: ctrl,
              style: const TextStyle(fontSize: 13),
              decoration: InputDecoration(
                hintText: 'Chagua tarehe',
                hintStyle: const TextStyle(color: Color(0xFFD1D5DB), fontSize: 12),
                suffixIcon: const Icon(Icons.calendar_today_rounded, size: 16, color: Color(0xFF9CA3AF)),
                contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
                enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
                focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: AppColors.primary)),
                filled: true, fillColor: Colors.white,
              ),
            ),
          ),
        ),
      ],
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
          padding: const EdgeInsets.symmetric(vertical: 9),
          decoration: BoxDecoration(
            color: sel ? AppColors.primaryLight : const Color(0xFFF9FAFB),
            border: Border.all(color: sel ? AppColors.primary : const Color(0xFFE5E7EB), width: 1.5),
            borderRadius: BorderRadius.circular(9),
          ),
          child: Text(label, textAlign: TextAlign.center,
            style: TextStyle(color: sel ? AppColors.primary : const Color(0xFF6B7280),
              fontSize: 11, fontWeight: sel ? FontWeight.w700 : FontWeight.w400)),
        ),
      ),
    );
  }
}
