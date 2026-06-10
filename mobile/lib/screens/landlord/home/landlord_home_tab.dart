import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import '../../../constants/app_colors.dart';
import '../../../services/auth_service.dart';
import '../../../services/landlord_service.dart';
import '../../../utils/helpers.dart';
import '../profile/landlord_profile_page.dart';
import '../subscription/landlord_subscription_page.dart';

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
    SystemChrome.setSystemUIOverlayStyle(const SystemUiOverlayStyle(
      statusBarColor: Colors.transparent,
      statusBarIconBrightness: Brightness.dark,
    ));
    final user = _auth.currentUser;
    final name = user?.name ?? 'Landlord';

    return Scaffold(
      backgroundColor: const Color(0xFFF4F6F8),
      body: RefreshIndicator(
        onRefresh: _load,
        color: AppColors.primary,
        child: CustomScrollView(
          slivers: [
            _buildSliverHeader(context, name, user?.avatar),
            SliverPadding(
              padding: const EdgeInsets.fromLTRB(16, 0, 16, 100),
              sliver: SliverList(
                delegate: SliverChildListDelegate([
                  const SizedBox(height: 16),
                  _buildPortfolioCard(),
                  const SizedBox(height: 12),
                  _buildQuickStatsRow(),
                  const SizedBox(height: 20),
                  _buildSectionHeader('Revenue Trends'),
                  const SizedBox(height: 10),
                  _buildRevenueCard('Monthly Rent vs Collected',
                      'No monthly data available yet. Add leases and payments to populate this chart.'),
                  const SizedBox(height: 10),
                  _buildRevenueCard('Yearly Collection Performance',
                      'No yearly revenue data available yet.'),
                  const SizedBox(height: 20),
                  _buildSectionHeader('Shortcuts'),
                  const SizedBox(height: 10),
                  _buildShortcutsGrid(context),
                ]),
              ),
            ),
          ],
        ),
      ),
    );
  }

  // ─── Sliver AppBar ────────────────────────────────────────────────────────

  Widget _buildSliverHeader(BuildContext context, String name, String? avatar) {
    return SliverAppBar(
      backgroundColor: Colors.white,
      surfaceTintColor: Colors.transparent,
      elevation: 0,
      shadowColor: Colors.black.withValues(alpha: 0.08),
      forceElevated: true,
      floating: true,
      snap: true,
      automaticallyImplyLeading: false,
      toolbarHeight: 70,
      title: Row(
        children: [
          // Avatar
          GestureDetector(
            onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const LandlordProfilePage())),
            child: _buildAvatar(name, avatar, size: 42),
          ),
          const SizedBox(width: 12),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisSize: MainAxisSize.min,
            children: [
              Text(
                _greeting(),
                style: const TextStyle(
                  fontSize: 11,
                  color: Color(0xFF9CA3AF),
                  fontWeight: FontWeight.w500,
                  height: 1,
                ),
              ),
              const SizedBox(height: 2),
              Text(
                name.split(' ').first,
                style: const TextStyle(
                  fontSize: 17,
                  fontWeight: FontWeight.w800,
                  color: Color(0xFF111827),
                  height: 1,
                ),
              ),
            ],
          ),
          const Spacer(),
          _headerIconBtn(
            Icons.notifications_outlined,
            () => Helpers.showSnackBar(context, 'Arifa zako'),
            badge: true,
          ),
          const SizedBox(width: 8),
          _headerIconBtn(
            Icons.person_outline_rounded,
            () => Navigator.push(context, MaterialPageRoute(builder: (_) => const LandlordProfilePage())),
          ),
        ],
      ),
    );
  }

  Widget _buildAvatar(String name, String? avatarUrl, {double size = 42}) {
    final initials = name.isNotEmpty
        ? name.split(' ').take(2).map((w) => w.isNotEmpty ? w[0] : '').join().toUpperCase()
        : 'L';
    return Container(
      width: size,
      height: size,
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(size / 3),
        border: Border.all(color: AppColors.primary.withValues(alpha: 0.3), width: 2),
        color: AppColors.primaryLight,
      ),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(size / 3 - 2),
        child: avatarUrl != null && avatarUrl.isNotEmpty
            ? Image.network(avatarUrl, fit: BoxFit.cover,
                errorBuilder: (_, __, ___) => _initialsWidget(initials, size))
            : _initialsWidget(initials, size),
      ),
    );
  }

  Widget _initialsWidget(String initials, double size) {
    return Center(
      child: Text(initials,
          style: TextStyle(
            color: AppColors.primary,
            fontWeight: FontWeight.w800,
            fontSize: size * 0.38,
          )),
    );
  }

  Widget _headerIconBtn(IconData icon, VoidCallback onTap, {bool badge = false}) {
    return GestureDetector(
      onTap: onTap,
      child: Stack(
        children: [
          Container(
            width: 40,
            height: 40,
            decoration: BoxDecoration(
              color: const Color(0xFFF4F6F8),
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: const Color(0xFFE5E7EB)),
            ),
            child: Icon(icon, color: const Color(0xFF374151), size: 20),
          ),
          if (badge)
            Positioned(
              top: 6,
              right: 6,
              child: Container(
                width: 8,
                height: 8,
                decoration: const BoxDecoration(
                  color: AppColors.primary,
                  shape: BoxShape.circle,
                ),
              ),
            ),
        ],
      ),
    );
  }

  // ─── Portfolio Card ───────────────────────────────────────────────────────

  Widget _buildPortfolioCard() {
    final props = _stats['total_properties'] ?? 0;
    final collected = (_stats['collected_revenue'] ?? 0.0).toDouble();
    final outstanding = (_stats['outstanding_revenue'] ?? 0.0).toDouble();
    final rate = (_stats['occupancy_rate'] ?? 0).toInt();
    final occupied = _stats['occupied_units'] ?? 0;
    final vacant = _stats['vacant_units'] ?? 0;
    final total = _stats['total_units'] ?? 0;
    final expected = (_stats['expected_rent'] ?? 0.0).toDouble();

    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.06),
            blurRadius: 16,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Column(
        children: [
          // ── Header row
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 16, 16, 0),
            child: Row(
              children: [
                Container(
                  padding: const EdgeInsets.all(9),
                  decoration: BoxDecoration(
                    gradient: const LinearGradient(
                      colors: [AppColors.primary, AppColors.primaryDark],
                    ),
                    borderRadius: BorderRadius.circular(10),
                  ),
                  child: const Icon(Icons.business_center_rounded, color: Colors.white, size: 18),
                ),
                const SizedBox(width: 10),
                const Text(
                  'Portfolio Summary',
                  style: TextStyle(
                    fontSize: 15,
                    fontWeight: FontWeight.w800,
                    color: Color(0xFF111827),
                  ),
                ),
                const Spacer(),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                  decoration: BoxDecoration(
                    color: AppColors.primaryLight,
                    borderRadius: BorderRadius.circular(20),
                    border: Border.all(color: AppColors.primary.withValues(alpha: 0.2)),
                  ),
                  child: Text(
                    '$props ${props == 1 ? 'property' : 'properties'}',
                    style: const TextStyle(
                      color: AppColors.primary,
                      fontSize: 11,
                      fontWeight: FontWeight.w700,
                    ),
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 14),

          // ── Revenue banner
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 14),
            child: Container(
              padding: const EdgeInsets.all(18),
              decoration: BoxDecoration(
                gradient: const LinearGradient(
                  colors: [Color(0xFFB44040), Color(0xFF7E2B2B)],
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                ),
                borderRadius: BorderRadius.circular(14),
              ),
              child: Row(
                children: [
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text(
                          'Collected Revenue',
                          style: TextStyle(
                            color: Colors.white70,
                            fontSize: 11,
                            fontWeight: FontWeight.w500,
                          ),
                        ),
                        const SizedBox(height: 4),
                        Text(
                          'TZS ${Helpers.formatMoney(collected)}',
                          style: const TextStyle(
                            color: Colors.white,
                            fontSize: 22,
                            fontWeight: FontWeight.w900,
                          ),
                        ),
                        const SizedBox(height: 4),
                        Row(
                          children: [
                            const Icon(Icons.pending_outlined, color: Colors.white60, size: 13),
                            const SizedBox(width: 4),
                            Text(
                              'Outstanding: TZS ${Helpers.formatMoney(outstanding)}',
                              style: const TextStyle(
                                color: Colors.white70,
                                fontSize: 12,
                              ),
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),
                  Container(
                    padding: const EdgeInsets.all(12),
                    decoration: BoxDecoration(
                      color: Colors.white.withValues(alpha: 0.15),
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: const Icon(Icons.account_balance_wallet_rounded, color: Colors.white, size: 28),
                  ),
                ],
              ),
            ),
          ),

          const SizedBox(height: 14),

          // ── Occupancy bar
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16),
            child: Column(
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text(
                      'Occupancy rate',
                      style: TextStyle(fontSize: 12, color: Color(0xFF6B7280), fontWeight: FontWeight.w500),
                    ),
                    Text(
                      '$rate%',
                      style: const TextStyle(
                        color: AppColors.primary,
                        fontWeight: FontWeight.w800,
                        fontSize: 13,
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 6),
                ClipRRect(
                  borderRadius: BorderRadius.circular(6),
                  child: LinearProgressIndicator(
                    value: rate / 100,
                    minHeight: 7,
                    backgroundColor: const Color(0xFFF3F4F6),
                    valueColor: const AlwaysStoppedAnimation<Color>(AppColors.primary),
                  ),
                ),
                const SizedBox(height: 6),
                Row(
                  children: [
                    _dot(const Color(0xFF10B981)),
                    const SizedBox(width: 4),
                    Text('$occupied occupied', style: const TextStyle(color: Color(0xFF6B7280), fontSize: 11)),
                    const SizedBox(width: 12),
                    _dot(const Color(0xFFE5E7EB), border: true),
                    const SizedBox(width: 4),
                    Text('$vacant vacant', style: const TextStyle(color: Color(0xFF6B7280), fontSize: 11)),
                    const SizedBox(width: 12),
                    _dot(const Color(0xFF3B82F6)),
                    const SizedBox(width: 4),
                    Text('$total total units', style: const TextStyle(color: Color(0xFF6B7280), fontSize: 11)),
                  ],
                ),
              ],
            ),
          ),

          const SizedBox(height: 14),
          const Divider(height: 1, color: Color(0xFFF3F4F6)),

          // ── Bottom stats
          Padding(
            padding: const EdgeInsets.fromLTRB(14, 12, 14, 14),
            child: Row(
              children: [
                Expanded(
                  child: _bottomStat(
                    icon: Icons.apartment_rounded,
                    label: 'Units',
                    value: '$total',
                    color: const Color(0xFF3B82F6),
                  ),
                ),
                Container(width: 1, height: 36, color: const Color(0xFFE5E7EB)),
                Expanded(
                  child: _bottomStat(
                    icon: Icons.trending_up_rounded,
                    label: 'Expected Rent',
                    value: 'TZS ${Helpers.formatMoney(expected)}',
                    color: const Color(0xFF10B981),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _dot(Color color, {bool border = false}) {
    return Container(
      width: 8,
      height: 8,
      decoration: BoxDecoration(
        color: color,
        shape: BoxShape.circle,
        border: border ? Border.all(color: const Color(0xFFD1D5DB)) : null,
      ),
    );
  }

  Widget _bottomStat({required IconData icon, required String label, required String value, required Color color}) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.center,
      children: [
        Container(
          padding: const EdgeInsets.all(7),
          decoration: BoxDecoration(
            color: color.withValues(alpha: 0.1),
            borderRadius: BorderRadius.circular(8),
          ),
          child: Icon(icon, color: color, size: 16),
        ),
        const SizedBox(width: 8),
        Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(label, style: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 10, fontWeight: FontWeight.w500)),
            Text(value,
                style: const TextStyle(
                  fontWeight: FontWeight.w800,
                  fontSize: 13,
                  color: Color(0xFF111827),
                ),
                overflow: TextOverflow.ellipsis),
          ],
        ),
      ],
    );
  }

  // ─── Quick Stats ──────────────────────────────────────────────────────────

  Widget _buildQuickStatsRow() {
    final pending = _stats['pending_payments'] ?? 0;
    final maintenance = _stats['maintenance_requests'] ?? 0;
    final leases = _stats['active_leases'] ?? 0;

    return Row(
      children: [
        _statChip('Pending\nPayments', '$pending', Icons.hourglass_top_rounded, const Color(0xFFF59E0B)),
        const SizedBox(width: 8),
        _statChip('Maintenance\nRequests', '$maintenance', Icons.build_rounded, const Color(0xFF3B82F6)),
        const SizedBox(width: 8),
        _statChip('Active\nLeases', '$leases', Icons.description_rounded, const Color(0xFF10B981)),
      ],
    );
  }

  Widget _statChip(String label, String value, IconData icon, Color color) {
    return Expanded(
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 14, horizontal: 10),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(14),
          boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.04), blurRadius: 8)],
        ),
        child: Column(
          children: [
            Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                color: color.withValues(alpha: 0.1),
                borderRadius: BorderRadius.circular(10),
              ),
              child: Icon(icon, color: color, size: 20),
            ),
            const SizedBox(height: 8),
            Text(value,
                style: TextStyle(
                  color: color,
                  fontWeight: FontWeight.w900,
                  fontSize: 20,
                )),
            const SizedBox(height: 3),
            Text(label,
                style: const TextStyle(
                  color: Color(0xFF9CA3AF),
                  fontSize: 10,
                  fontWeight: FontWeight.w500,
                ),
                textAlign: TextAlign.center),
          ],
        ),
      ),
    );
  }

  // ─── Section Header ───────────────────────────────────────────────────────

  Widget _buildSectionHeader(String title) {
    return Text(
      title,
      style: const TextStyle(
        fontSize: 16,
        fontWeight: FontWeight.w800,
        color: Color(0xFF111827),
      ),
    );
  }

  // ─── Revenue Cards ────────────────────────────────────────────────────────

  Widget _buildRevenueCard(String title, String emptyMsg) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(14),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.04), blurRadius: 8)],
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            padding: const EdgeInsets.all(9),
            decoration: BoxDecoration(
              color: AppColors.primaryLight,
              borderRadius: BorderRadius.circular(10),
            ),
            child: const Icon(Icons.bar_chart_rounded, color: AppColors.primary, size: 18),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(title,
                    style: const TextStyle(
                      fontWeight: FontWeight.w700,
                      fontSize: 13,
                      color: Color(0xFF111827),
                    )),
                const SizedBox(height: 4),
                Text(emptyMsg,
                    style: const TextStyle(
                      color: Color(0xFF9CA3AF),
                      fontSize: 12,
                      height: 1.4,
                    )),
              ],
            ),
          ),
        ],
      ),
    );
  }

  // ─── Shortcuts ────────────────────────────────────────────────────────────

  Widget _buildShortcutsGrid(BuildContext context) {
    final shortcuts = [
      {'label': 'Properties', 'icon': Icons.apartment_rounded, 'color': AppColors.primary},
      {'label': 'View Leases', 'icon': Icons.description_rounded, 'color': const Color(0xFF3B82F6)},
      {'label': 'Subscription', 'icon': Icons.star_rounded, 'color': const Color(0xFFF59E0B)},
    ];

    return Column(
      children: [
        Row(
          children: shortcuts.map((s) {
            return Expanded(
              child: GestureDetector(
                onTap: () {
                  if (s['label'] == 'Subscription') {
                    Navigator.push(context, MaterialPageRoute(builder: (_) => const LandlordSubscriptionPage()));
                  }
                },
                child: Container(
                  margin: const EdgeInsets.only(right: 8),
                  padding: const EdgeInsets.symmetric(vertical: 16, horizontal: 10),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(14),
                    border: Border.all(color: const Color(0xFFE5E7EB)),
                    boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.03), blurRadius: 6)],
                  ),
                  child: Column(
                    children: [
                      Icon(s['icon'] as IconData, color: s['color'] as Color, size: 24),
                      const SizedBox(height: 6),
                      Text(
                        s['label'] as String,
                        style: const TextStyle(
                          fontWeight: FontWeight.w700,
                          fontSize: 11,
                          color: Color(0xFF374151),
                        ),
                        textAlign: TextAlign.center,
                      ),
                    ],
                  ),
                ),
              ),
            );
          }).toList(),
        ),
      ],
    );
  }

  String _greeting() {
    final h = DateTime.now().hour;
    if (h < 12) return 'Good morning,';
    if (h < 17) return 'Good afternoon,';
    return 'Good evening,';
  }
}
