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
        _revenue = r[0]['data'] ?? {};
        _occupancy = r[1]['data'] ?? {};
      });
    } catch (_) {}
    if (mounted) setState(() => _isLoading = false);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF4F6F8),
      body: SafeArea(
        child: _isLoading
            ? const Center(child: CircularProgressIndicator(color: AppColors.primary))
            : RefreshIndicator(
                onRefresh: _load,
                color: AppColors.primary,
                child: ListView(
                  padding: const EdgeInsets.fromLTRB(16, 16, 16, 100),
                  children: [
                    Row(
                      children: [
                        const Text('Reports', style: TextStyle(fontSize: 22, fontWeight: FontWeight.w800)),
                        const Spacer(),
                        _yearSelector(),
                      ],
                    ),
                    const SizedBox(height: 16),
                    _buildSummaryCards(),
                    const SizedBox(height: 16),
                    _buildOccupancyCard(),
                    const SizedBox(height: 16),
                    _buildMonthlyBreakdown(),
                    const SizedBox(height: 16),
                    _buildQuickExportCard(context),
                  ],
                ),
              ),
      ),
    );
  }

  Widget _yearSelector() {
    final years = List.generate(5, (i) => (DateTime.now().year - i).toString());
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(10),
        border: Border.all(color: const Color(0xFFE5E7EB)),
      ),
      child: DropdownButtonHideUnderline(
        child: DropdownButton<String>(
          value: _year,
          items: years.map((y) => DropdownMenuItem(value: y, child: Text(y, style: const TextStyle(fontSize: 13)))).toList(),
          onChanged: (v) {
            if (v != null) setState(() => _year = v);
            _load();
          },
          isDense: true,
          iconSize: 18,
          icon: const Icon(Icons.arrow_drop_down, color: AppColors.primary),
        ),
      ),
    );
  }

  Widget _buildSummaryCards() {
    final totalCollected = (_revenue['total_collected'] ?? 0.0).toDouble();
    final totalExpected = (_revenue['total_expected'] ?? 0.0).toDouble();
    final collectionRate = totalExpected > 0 ? (totalCollected / totalExpected * 100).toInt() : 0;
    final outstanding = totalExpected - totalCollected;

    return Column(
      children: [
        Row(children: [
          _summaryCard('Iliyokusanywa', 'TZS ${Helpers.formatMoney(totalCollected)}', Icons.payments_rounded, AppColors.primary),
          const SizedBox(width: 10),
          _summaryCard('Inayotarajiwa', 'TZS ${Helpers.formatMoney(totalExpected)}', Icons.trending_up_rounded, const Color(0xFF3B82F6)),
        ]),
        const SizedBox(height: 10),
        Row(children: [
          _summaryCard('Deni Linalobaki', 'TZS ${Helpers.formatMoney(outstanding)}', Icons.pending_rounded, const Color(0xFFEF4444)),
          const SizedBox(width: 10),
          _summaryCard('Mkusanyiko %', '$collectionRate%', Icons.percent_rounded, const Color(0xFF10B981)),
        ]),
      ],
    );
  }

  Widget _summaryCard(String label, String value, IconData icon, Color color) {
    return Expanded(
      child: Container(
        padding: const EdgeInsets.all(14),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(14),
          boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.04), blurRadius: 6)],
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(color: color.withOpacity(0.1), borderRadius: BorderRadius.circular(10)),
              child: Icon(icon, color: color, size: 18),
            ),
            const SizedBox(height: 10),
            Text(value, style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 16)),
            const SizedBox(height: 2),
            Text(label, style: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 11)),
          ],
        ),
      ),
    );
  }

  Widget _buildOccupancyCard() {
    final occupancyRate = (_occupancy['occupancy_rate'] ?? 0).toInt();
    final occupied = _occupancy['occupied'] ?? 0;
    final vacant = _occupancy['vacant'] ?? 0;
    final total = _occupancy['total'] ?? 0;

    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(14),
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.04), blurRadius: 6)],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text('Occupancy Report', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 15)),
          const SizedBox(height: 14),
          Row(
            children: [
              Expanded(
                flex: 2,
                child: Stack(
                  alignment: Alignment.center,
                  children: [
                    SizedBox(
                      width: 90, height: 90,
                      child: CircularProgressIndicator(
                        value: occupancyRate / 100,
                        strokeWidth: 10,
                        backgroundColor: const Color(0xFFE5E7EB),
                        valueColor: const AlwaysStoppedAnimation<Color>(AppColors.primary),
                      ),
                    ),
                    Column(
                      children: [
                        Text('$occupancyRate%', style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 18, color: AppColors.primary)),
                        const Text('Full', style: TextStyle(color: Color(0xFF9CA3AF), fontSize: 10)),
                      ],
                    ),
                  ],
                ),
              ),
              const SizedBox(width: 16),
              Expanded(
                flex: 3,
                child: Column(
                  children: [
                    _occupancyRow('Imekaliwa', '$occupied', const Color(0xFF10B981)),
                    const SizedBox(height: 10),
                    _occupancyRow('Imewazi', '$vacant', const Color(0xFFEF4444)),
                    const SizedBox(height: 10),
                    _occupancyRow('Jumla Vyumba', '$total', const Color(0xFF3B82F6)),
                  ],
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _occupancyRow(String label, String value, Color color) {
    return Row(
      children: [
        Container(width: 10, height: 10, decoration: BoxDecoration(color: color, shape: BoxShape.circle)),
        const SizedBox(width: 8),
        Expanded(child: Text(label, style: const TextStyle(color: Color(0xFF6B7280), fontSize: 12))),
        Text(value, style: TextStyle(color: color, fontWeight: FontWeight.w700, fontSize: 13)),
      ],
    );
  }

  Widget _buildMonthlyBreakdown() {
    final months = _revenue['monthly'] as List? ?? [];

    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(14),
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.04), blurRadius: 6)],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text('Mapato kila Mwezi', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 15)),
          const SizedBox(height: 12),
          if (months.isEmpty)
            const Center(
              child: Padding(
                padding: EdgeInsets.symmetric(vertical: 24),
                child: Text('Hakuna data ya mapato bado.', style: TextStyle(color: Color(0xFF9CA3AF), fontSize: 13)),
              ),
            )
          else
            Column(
              children: months.map<Widget>((m) {
                final expected = (m['expected'] ?? 0.0).toDouble();
                final collected = (m['collected'] ?? 0.0).toDouble();
                final pct = expected > 0 ? collected / expected : 0.0;
                return Padding(
                  padding: const EdgeInsets.only(bottom: 10),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text(m['month'] ?? '', style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w600)),
                          Text('TZS ${Helpers.formatMoney(collected)} / ${Helpers.formatMoney(expected)}',
                              style: const TextStyle(color: Color(0xFF6B7280), fontSize: 11)),
                        ],
                      ),
                      const SizedBox(height: 4),
                      ClipRRect(
                        borderRadius: BorderRadius.circular(4),
                        child: LinearProgressIndicator(
                          value: pct.clamp(0.0, 1.0),
                          minHeight: 6,
                          backgroundColor: const Color(0xFFE5E7EB),
                          valueColor: AlwaysStoppedAnimation<Color>(
                              pct >= 1.0 ? const Color(0xFF10B981) : AppColors.primary),
                        ),
                      ),
                    ],
                  ),
                );
              }).toList(),
            ),
        ],
      ),
    );
  }

  Widget _buildQuickExportCard(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(14),
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.04), blurRadius: 6)],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text('Pakua Ripoti', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 15)),
          const SizedBox(height: 12),
          Row(children: [
            _exportBtn('PDF', Icons.picture_as_pdf_rounded, const Color(0xFFEF4444), context),
            const SizedBox(width: 10),
            _exportBtn('Excel', Icons.table_chart_rounded, const Color(0xFF10B981), context),
          ]),
        ],
      ),
    );
  }

  Widget _exportBtn(String label, IconData icon, Color color, BuildContext context) {
    return Expanded(
      child: GestureDetector(
        onTap: () => Helpers.showSnackBar(context, 'Pakua $label - inakuja hivi karibuni!'),
        child: Container(
          padding: const EdgeInsets.symmetric(vertical: 12),
          decoration: BoxDecoration(
            color: color.withOpacity(0.1),
            borderRadius: BorderRadius.circular(10),
            border: Border.all(color: color.withOpacity(0.3)),
          ),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Icon(icon, color: color, size: 18),
              const SizedBox(width: 6),
              Text(label, style: TextStyle(color: color, fontWeight: FontWeight.w700, fontSize: 13)),
            ],
          ),
        ),
      ),
    );
  }
}
