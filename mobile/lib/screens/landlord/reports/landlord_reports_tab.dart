import 'package:flutter/material.dart';
import '../../../constants/app_colors.dart';
import '../../../services/landlord_service.dart';
import '../../../utils/helpers.dart';

class LandlordReportsTab extends StatefulWidget {
  const LandlordReportsTab({super.key});

  @override
  State<LandlordReportsTab> createState() => _LandlordReportsTabState();
}

class _LandlordReportsTabState extends State<LandlordReportsTab> {
  final LandlordService _service = LandlordService();
  Map<String, dynamic> _revenue = {};
  Map<String, dynamic> _occupancy = {};
  bool _isLoading = true;
  String _year = DateTime.now().year.toString();

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() => _isLoading = true);
    try {
      final r = await Future.wait([
        _service.getRevenueReport(year: _year),
        _service.getOccupancyReport(),
      ]);
      setState(() {
        _revenue   = r[0]['data'] ?? {};
        _occupancy = r[1]['data'] ?? {};
      });
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
            _buildHeader(),
            Expanded(
              child: _isLoading
                  ? const Center(child: CircularProgressIndicator(color: AppColors.primary))
                  : RefreshIndicator(
                      onRefresh: _load,
                      color: AppColors.primary,
                      child: ListView(
                        padding: const EdgeInsets.fromLTRB(16, 12, 16, 100),
                        children: [
                          _buildSummaryCards(),
                          const SizedBox(height: 16),
                          _buildOccupancyCard(),
                          const SizedBox(height: 16),
                          _buildMonthlyBreakdown(),
                          const SizedBox(height: 16),
                          _buildExportCard(context),
                        ],
                      ),
                    ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildHeader() {
    final years = List.generate(5, (i) => (DateTime.now().year - i).toString());
    return Padding(
      padding: const EdgeInsets.fromLTRB(16, 16, 16, 4),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(
              gradient: const LinearGradient(colors: [AppColors.primary, AppColors.primaryDark]),
              borderRadius: BorderRadius.circular(12),
            ),
            child: const Icon(Icons.analytics_rounded, color: Colors.white, size: 20),
          ),
          const SizedBox(width: 12),
          const Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
            Text('Ripoti', style: TextStyle(fontSize: 20, fontWeight: FontWeight.w900, color: Color(0xFF0F172A))),
            Text('Uchambuzi wa mapato na mali', style: TextStyle(fontSize: 11, color: Color(0xFF9CA3AF))),
          ]),
          const Spacer(),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
            decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(10), border: Border.all(color: const Color(0xFFE2E8F0))),
            child: DropdownButtonHideUnderline(
              child: DropdownButton<String>(
                value: _year,
                items: years.map((y) => DropdownMenuItem(value: y, child: Text(y, style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w700)))).toList(),
                onChanged: (v) { if (v != null) { setState(() => _year = v); _load(); } },
                isDense: true, iconSize: 16,
                icon: const Icon(Icons.arrow_drop_down, color: AppColors.primary),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSummaryCards() {
    final totalCollected = (_revenue['total_collected'] ?? 0.0).toDouble();
    final totalExpected  = (_revenue['total_expected'] ?? 0.0).toDouble();
    final outstanding    = totalExpected - totalCollected;
    final rate = totalExpected > 0 ? (totalCollected / totalExpected * 100).toInt() : 0;

    final cards = [
      {'label': 'Iliyokusanywa', 'value': 'TZS ${Helpers.formatMoney(totalCollected)}', 'icon': Icons.payments_rounded, 'color': AppColors.primary, 'bg': AppColors.primaryLight},
      {'label': 'Inayotarajiwa', 'value': 'TZS ${Helpers.formatMoney(totalExpected)}', 'icon': Icons.trending_up_rounded, 'color': const Color(0xFF3B82F6), 'bg': const Color(0xFFDBEAFE)},
      {'label': 'Inayodaiwa', 'value': 'TZS ${Helpers.formatMoney(outstanding)}', 'icon': Icons.pending_rounded, 'color': const Color(0xFFEF4444), 'bg': const Color(0xFFFEE2E2)},
      {'label': 'Mkusanyiko', 'value': '$rate%', 'icon': Icons.percent_rounded, 'color': const Color(0xFF10B981), 'bg': const Color(0xFFDCFCE7)},
    ];

    return GridView.builder(
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 2, crossAxisSpacing: 10, mainAxisSpacing: 10, childAspectRatio: 1.6,
      ),
      itemCount: cards.length,
      itemBuilder: (ctx, i) {
        final c = cards[i];
        return Container(
          padding: const EdgeInsets.all(14),
          decoration: BoxDecoration(
            color: Colors.white, borderRadius: BorderRadius.circular(14),
            border: Border.all(color: const Color(0xFFE2E8F0)),
            boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.04), blurRadius: 8)],
          ),
          child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
            Container(padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(color: c['bg'] as Color, borderRadius: BorderRadius.circular(10)),
              child: Icon(c['icon'] as IconData, color: c['color'] as Color, size: 18)),
            const Spacer(),
            Text(c['value'] as String, style: TextStyle(fontWeight: FontWeight.w900, fontSize: 14, color: c['color'] as Color),
                overflow: TextOverflow.ellipsis),
            const SizedBox(height: 2),
            Text(c['label'] as String, style: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 11)),
          ]),
        );
      },
    );
  }

  Widget _buildOccupancyCard() {
    final occupancyRate = (_occupancy['occupancy_rate'] ?? 0).toInt();
    final occupied = _occupancy['occupied'] ?? 0;
    final vacant   = _occupancy['vacant'] ?? 0;
    final total    = _occupancy['total'] ?? 0;

    return Container(
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        color: Colors.white, borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xFFE2E8F0)),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.04), blurRadius: 8)],
      ),
      child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
        Row(children: [
          Container(padding: const EdgeInsets.all(8),
            decoration: BoxDecoration(color: AppColors.primaryLight, borderRadius: BorderRadius.circular(10)),
            child: const Icon(Icons.home_work_rounded, color: AppColors.primary, size: 18)),
          const SizedBox(width: 10),
          const Text('Ripoti ya Ukaliaji', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 14, color: Color(0xFF0F172A))),
          const Spacer(),
          Text('$occupancyRate%', style: const TextStyle(color: AppColors.primary, fontWeight: FontWeight.w900, fontSize: 16)),
        ]),
        const SizedBox(height: 16),
        Row(children: [
          Expanded(
            flex: 2,
            child: Stack(alignment: Alignment.center, children: [
              SizedBox(width: 90, height: 90,
                child: CircularProgressIndicator(
                  value: occupancyRate / 100, strokeWidth: 10,
                  backgroundColor: const Color(0xFFE2E8F0),
                  valueColor: const AlwaysStoppedAnimation<Color>(AppColors.primary),
                )),
              Column(children: [
                Text('$occupancyRate%', style: const TextStyle(fontWeight: FontWeight.w900, fontSize: 18, color: AppColors.primary)),
                const Text('Imekaliwa', style: TextStyle(color: Color(0xFF9CA3AF), fontSize: 9)),
              ]),
            ]),
          ),
          const SizedBox(width: 20),
          Expanded(
            flex: 3,
            child: Column(children: [
              _occupancyRow('Imekaliwa', '$occupied', const Color(0xFF10B981)),
              const SizedBox(height: 10),
              _occupancyRow('Imewazi', '$vacant', const Color(0xFFEF4444)),
              const SizedBox(height: 10),
              _occupancyRow('Jumla ya Mali', '$total', const Color(0xFF3B82F6)),
            ]),
          ),
        ]),
      ]),
    );
  }

  Widget _occupancyRow(String label, String value, Color color) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
      decoration: BoxDecoration(color: color.withValues(alpha: 0.06), borderRadius: BorderRadius.circular(9)),
      child: Row(children: [
        Container(width: 8, height: 8, decoration: BoxDecoration(color: color, shape: BoxShape.circle)),
        const SizedBox(width: 8),
        Expanded(child: Text(label, style: const TextStyle(color: Color(0xFF64748B), fontSize: 12, fontWeight: FontWeight.w600))),
        Text(value, style: TextStyle(color: color, fontWeight: FontWeight.w900, fontSize: 14)),
      ]),
    );
  }

  Widget _buildMonthlyBreakdown() {
    final months = _revenue['monthly'] as List? ?? [];

    return Container(
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        color: Colors.white, borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xFFE2E8F0)),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.04), blurRadius: 8)],
      ),
      child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
        Row(children: [
          Container(padding: const EdgeInsets.all(8),
            decoration: BoxDecoration(color: AppColors.primaryLight, borderRadius: BorderRadius.circular(10)),
            child: const Icon(Icons.bar_chart_rounded, color: AppColors.primary, size: 18)),
          const SizedBox(width: 10),
          Text('Mapato kila Mwezi — $_year',
              style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 14, color: Color(0xFF0F172A))),
        ]),
        const SizedBox(height: 16),
        if (months.isEmpty)
          const Center(child: Padding(
            padding: EdgeInsets.symmetric(vertical: 24),
            child: Text('Hakuna data ya mapato bado.', style: TextStyle(color: Color(0xFF9CA3AF), fontSize: 13)),
          ))
        else
          Column(
            children: months.map<Widget>((m) {
              final expected  = (m['expected'] ?? 0.0).toDouble();
              final collected = (m['collected'] ?? 0.0).toDouble();
              final pct       = expected > 0 ? (collected / expected).clamp(0.0, 1.0) : 0.0;
              final done      = pct >= 1.0;

              return Padding(
                padding: const EdgeInsets.only(bottom: 12),
                child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                  Row(mainAxisAlignment: MainAxisAlignment.spaceBetween, children: [
                    Text(m['month'] ?? '', style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w700, color: Color(0xFF374151))),
                    Row(children: [
                      if (done) const Icon(Icons.check_circle_rounded, color: Color(0xFF10B981), size: 13),
                      const SizedBox(width: 4),
                      Text('TZS ${Helpers.formatMoney(collected)} / ${Helpers.formatMoney(expected)}',
                          style: TextStyle(color: done ? const Color(0xFF10B981) : const Color(0xFF64748B), fontSize: 11, fontWeight: FontWeight.w600)),
                    ]),
                  ]),
                  const SizedBox(height: 5),
                  ClipRRect(
                    borderRadius: BorderRadius.circular(6),
                    child: LinearProgressIndicator(
                      value: pct, minHeight: 7,
                      backgroundColor: const Color(0xFFF1F5F9),
                      valueColor: AlwaysStoppedAnimation<Color>(done ? const Color(0xFF10B981) : AppColors.primary),
                    ),
                  ),
                ]),
              );
            }).toList(),
          ),
      ]),
    );
  }

  Widget _buildExportCard(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        color: Colors.white, borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xFFE2E8F0)),
      ),
      child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
        const Text('Pakua Ripoti', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 14, color: Color(0xFF0F172A))),
        const SizedBox(height: 4),
        const Text('Toa ripoti yako katika muundo unaopenda', style: TextStyle(color: Color(0xFF9CA3AF), fontSize: 11)),
        const SizedBox(height: 14),
        Row(children: [
          Expanded(child: _exportBtn('Pakua PDF', Icons.picture_as_pdf_rounded, const Color(0xFFEF4444), const Color(0xFFFEE2E2), context)),
          const SizedBox(width: 10),
          Expanded(child: _exportBtn('Pakua Excel', Icons.table_chart_rounded, const Color(0xFF10B981), const Color(0xFFDCFCE7), context)),
        ]),
      ]),
    );
  }

  Widget _exportBtn(String label, IconData icon, Color color, Color bg, BuildContext context) {
    return GestureDetector(
      onTap: () => Helpers.showSnackBar(context, '$label - inakuja hivi karibuni!'),
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 13),
        decoration: BoxDecoration(
          color: bg, borderRadius: BorderRadius.circular(12),
          border: Border.all(color: color.withValues(alpha: 0.3)),
        ),
        child: Row(mainAxisAlignment: MainAxisAlignment.center, children: [
          Icon(icon, color: color, size: 18),
          const SizedBox(width: 8),
          Text(label, style: TextStyle(color: color, fontWeight: FontWeight.w800, fontSize: 12)),
        ]),
      ),
    );
  }
}
