import 'package:flutter/material.dart';
import '../../../constants/app_colors.dart';
import '../../../services/auth_service.dart';
import '../../../services/landlord_service.dart';
import '../../../utils/helpers.dart';

class LandlordHomeTab extends StatefulWidget {
  const LandlordHomeTab({super.key});

  @override
  State<LandlordHomeTab> createState() => _LandlordHomeTabState();
}

class _LandlordHomeTabState extends State<LandlordHomeTab> {
  final AuthService _auth = AuthService();
  final LandlordService _service = LandlordService();

  Map<String, dynamic> _stats = {};
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() => _isLoading = true);
    try {
      final res = await _service.getDashboard();
      if (res['success'] == true && res['data'] != null) {
        setState(() => _stats = Map<String, dynamic>.from(res['data']));
      }
    } catch (_) {}
    if (mounted) setState(() => _isLoading = false);
  }

  @override
  Widget build(BuildContext context) {
    final user = _auth.currentUser;
    final name = user?.name ?? 'Landlord';

    return Scaffold(
      backgroundColor: const Color(0xFFF4F6F8),
      body: RefreshIndicator(
        onRefresh: _load,
        color: AppColors.primary,
        child: CustomScrollView(
          slivers: [
            _buildAppBar(name),
            SliverPadding(
              padding: const EdgeInsets.fromLTRB(16, 16, 16, 100),
              sliver: SliverList(
                delegate: SliverChildListDelegate([
                  _buildPortfolioCard(),
                  const SizedBox(height: 16),
                  _buildStatsRow(),
                  const SizedBox(height: 20),
                  _buildSectionTitle('Revenue Trends'),
                  const SizedBox(height: 10),
                  _buildRevenueCard(),
                  const SizedBox(height: 10),
                  _buildYearlyCard(),
                  const SizedBox(height: 20),
                  _buildSectionTitle('Shortcuts'),
                  const SizedBox(height: 10),
                  _buildShortcuts(context),
                ]),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildAppBar(String name) {
    return SliverAppBar(
      backgroundColor: const Color(0xFFF4F6F8),
      elevation: 0,
      floating: true,
      automaticallyImplyLeading: false,
      flexibleSpace: FlexibleSpaceBar(
        background: SafeArea(
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
            child: Row(
              children: [
                Container(
                  width: 40,
                  height: 40,
                  decoration: BoxDecoration(
                    color: AppColors.primaryLight,
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: Center(
                    child: Text(
                      name.isNotEmpty ? name[0].toUpperCase() : 'L',
                      style: const TextStyle(
                        color: AppColors.primary,
                        fontWeight: FontWeight.w700,
                        fontSize: 16,
                      ),
                    ),
                  ),
                ),
                const Spacer(),
                _iconBtn(Icons.notifications_outlined, () {
                  Helpers.showSnackBar(context, 'Arifa zitafunguka hivi karibuni');
                }),
                const SizedBox(width: 8),
                _iconBtn(Icons.person_outline, () {
                  Helpers.showSnackBar(context, 'Wasifu wako');
                }),
              ],
            ),
          ),
        ),
      ),
      expandedHeight: 64,
    );
  }

  Widget _iconBtn(IconData icon, VoidCallback onTap) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 38,
        height: 38,
        decoration: BoxDecoration(
          color: AppColors.primaryLight,
          borderRadius: BorderRadius.circular(12),
        ),
        child: Icon(icon, color: AppColors.primary, size: 20),
      ),
    );
  }

  Widget _buildPortfolioCard() {
    final props = _stats['total_properties'] ?? 0;
    final collected = (_stats['collected_revenue'] ?? 0.0).toDouble();
    final outstanding = (_stats['outstanding_revenue'] ?? 0.0).toDouble();
    final occupancyRate = (_stats['occupancy_rate'] ?? 0).toInt();
    final occupied = _stats['occupied_units'] ?? 0;
    final vacant = _stats['vacant_units'] ?? 0;
    final total = _stats['total_units'] ?? 0;
    final expectedRent = (_stats['expected_rent'] ?? 0.0).toDouble();

    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(color: Colors.black.withOpacity(0.05), blurRadius: 8, offset: const Offset(0, 2)),
        ],
      ),
      child: Column(
        children: [
          // Header
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 14, 16, 0),
            child: Row(
              children: [
                Container(
                  padding: const EdgeInsets.all(8),
                  decoration: BoxDecoration(
                    color: AppColors.primaryLight,
                    borderRadius: BorderRadius.circular(10),
                  ),
                  child: const Icon(Icons.business_center_outlined, color: AppColors.primary, size: 18),
                ),
                const SizedBox(width: 10),
                const Text('Portfolio Summary', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 15)),
                const Spacer(),
                Text('$props properties', style: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 12)),
              ],
            ),
          ),
          const SizedBox(height: 12),
          // Collected Revenue Banner
          Container(
            margin: const EdgeInsets.symmetric(horizontal: 14),
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              gradient: const LinearGradient(
                colors: [Color(0xFFB44040), Color(0xFF7E2B2B)],
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
              ),
              borderRadius: BorderRadius.circular(12),
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text('Collected Revenue', style: TextStyle(color: Colors.white70, fontSize: 12)),
                const SizedBox(height: 4),
                Text(
                  'TZS ${Helpers.formatMoney(collected)}',
                  style: const TextStyle(color: Colors.white, fontSize: 22, fontWeight: FontWeight.w800),
                ),
                const SizedBox(height: 2),
                Text(
                  'Outstanding: TZS ${Helpers.formatMoney(outstanding)}',
                  style: const TextStyle(color: Colors.white70, fontSize: 12),
                ),
              ],
            ),
          ),
          const SizedBox(height: 12),
          // Occupancy
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 14),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text('Occupancy rate', style: TextStyle(fontSize: 13, color: Color(0xFF6B7280))),
                    Text('$occupancyRate%', style: const TextStyle(color: AppColors.primary, fontWeight: FontWeight.w700, fontSize: 13)),
                  ],
                ),
                const SizedBox(height: 6),
                ClipRRect(
                  borderRadius: BorderRadius.circular(4),
                  child: LinearProgressIndicator(
                    value: occupancyRate / 100,
                    minHeight: 6,
                    backgroundColor: const Color(0xFFE5E7EB),
                    valueColor: const AlwaysStoppedAnimation<Color>(AppColors.primary),
                  ),
                ),
                const SizedBox(height: 6),
                Text(
                  '$occupied occupied • $vacant vacant • $total total units',
                  style: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 11),
                ),
              ],
            ),
          ),
          const SizedBox(height: 14),
          // Units & Expected Rent
          Padding(
            padding: const EdgeInsets.fromLTRB(14, 0, 14, 14),
            child: Row(
              children: [
                Expanded(
                  child: _miniStatCard(
                    icon: Icons.apartment_outlined,
                    label: 'Units',
                    value: '$total',
                  ),
                ),
                const SizedBox(width: 10),
                Expanded(
                  child: _miniStatCard(
                    icon: Icons.trending_up_rounded,
                    label: 'Expected Rent',
                    value: 'TZS ${Helpers.formatMoney(expectedRent)}',
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _miniStatCard({required IconData icon, required String label, required String value}) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
      decoration: BoxDecoration(
        color: const Color(0xFFF9FAFB),
        borderRadius: BorderRadius.circular(10),
        border: Border.all(color: const Color(0xFFE5E7EB)),
      ),
      child: Row(
        children: [
          Icon(icon, color: AppColors.primary, size: 18),
          const SizedBox(width: 8),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(label, style: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 11)),
                Text(value, style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 13), overflow: TextOverflow.ellipsis),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildStatsRow() {
    final pending = _stats['pending_payments'] ?? 0;
    final maintenance = _stats['maintenance_requests'] ?? 0;
    final leases = _stats['active_leases'] ?? 0;

    return Row(
      children: [
        _quickStatChip(Icons.hourglass_top_rounded, 'Pending\nPayments', '$pending', const Color(0xFFF59E0B)),
        const SizedBox(width: 8),
        _quickStatChip(Icons.build_outlined, 'Maintenance\nRequests', '$maintenance', const Color(0xFF3B82F6)),
        const SizedBox(width: 8),
        _quickStatChip(Icons.description_outlined, 'Active\nLeases', '$leases', const Color(0xFF10B981)),
      ],
    );
  }

  Widget _quickStatChip(IconData icon, String label, String value, Color color) {
    return Expanded(
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 12, horizontal: 10),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(12),
          boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.04), blurRadius: 6)],
        ),
        child: Column(
          children: [
            Icon(icon, color: color, size: 22),
            const SizedBox(height: 6),
            Text(value, style: TextStyle(color: color, fontWeight: FontWeight.w800, fontSize: 16)),
            const SizedBox(height: 2),
            Text(label, style: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 9), textAlign: TextAlign.center),
          ],
        ),
      ),
    );
  }

  Widget _buildSectionTitle(String title) {
    return Text(title, style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w700));
  }

  Widget _buildRevenueCard() {
    final hasData = _stats['monthly_revenue'] != null;
    return _revenueContainer(
      icon: Icons.bar_chart_rounded,
      title: 'Monthly Rent vs Collected',
      message: hasData
          ? null
          : 'No monthly data available yet. Add leases and payments to populate this chart.',
      child: hasData ? _buildMonthlyChart() : null,
    );
  }

  Widget _buildYearlyCard() {
    final hasData = _stats['yearly_revenue'] != null;
    return _revenueContainer(
      icon: Icons.bar_chart_rounded,
      title: 'Yearly Collection Performance',
      message: hasData ? null : 'No yearly revenue data available yet.',
      child: hasData ? _buildYearlyChart() : null,
    );
  }

  Widget _revenueContainer({
    required IconData icon,
    required String title,
    String? message,
    Widget? child,
  }) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(14),
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.04), blurRadius: 6)],
      ),
      child: message != null
          ? Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Container(
                  padding: const EdgeInsets.all(8),
                  decoration: BoxDecoration(
                    color: AppColors.primaryLight,
                    borderRadius: BorderRadius.circular(10),
                  ),
                  child: Icon(icon, color: AppColors.primary, size: 18),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(title, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
                      const SizedBox(height: 4),
                      Text(message, style: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 12)),
                    ],
                  ),
                ),
              ],
            )
          : child ?? const SizedBox.shrink(),
    );
  }

  Widget _buildMonthlyChart() => const SizedBox(height: 120, child: Center(child: Text('Chart')));
  Widget _buildYearlyChart() => const SizedBox(height: 120, child: Center(child: Text('Chart')));

  Widget _buildShortcuts(BuildContext context) {
    final shortcuts = [
      {'label': 'Properties', 'icon': Icons.apartment_rounded, 'tab': 2},
      {'label': 'View Leases', 'icon': Icons.description_rounded, 'tab': 3},
      {'label': 'Subscription', 'icon': Icons.star_rounded, 'tab': -1},
    ];

    return GridView.count(
      crossAxisCount: 2,
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      mainAxisSpacing: 10,
      crossAxisSpacing: 10,
      childAspectRatio: 2.2,
      children: shortcuts.map((s) {
        return GestureDetector(
          onTap: () {
            if (s['tab'] as int >= 0) {
              // Switch tab via parent — using a callback would be ideal,
              // but for simplicity show a snack for subscription
            } else {
              Helpers.showSnackBar(context, 'Subscription page inakuja hivi karibuni!');
            }
          },
          child: Container(
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: const Color(0xFFE5E7EB)),
            ),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Icon(s['icon'] as IconData, color: AppColors.primary, size: 20),
                const SizedBox(width: 8),
                Text(s['label'] as String, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
              ],
            ),
          ),
        );
      }).toList(),
    );
  }
}
