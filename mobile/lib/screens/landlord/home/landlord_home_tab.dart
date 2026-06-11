import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import '../../../constants/app_colors.dart';
import '../../../services/auth_service.dart';
import '../../../services/landlord_service.dart';
import '../../../services/notification_service.dart';
import '../../../utils/helpers.dart';
import '../../../utils/app_settings.dart';
import '../../notifications/notifications_screen.dart';
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
  final NotificationService _notifService = NotificationService();
  Map<String, dynamic> _stats = {};
  bool _isLoading = true;
  int _unreadNotifications = 0;

  @override
  void initState() {
    super.initState();
    AppSettings.instance.addListener(_onSettingsChanged);
    _load();
    _loadNotifCount();
  }

  @override
  void dispose() {
    AppSettings.instance.removeListener(_onSettingsChanged);
    super.dispose();
  }

  void _onSettingsChanged() { if (mounted) setState(() {}); }

  Future<void> _loadNotifCount() async {
    try {
      final count = await _notifService.fetchUnreadCount();
      if (mounted) setState(() => _unreadNotifications = count);
    } catch (_) {}
  }

  Future<void> _load() async {
    setState(() => _isLoading = true);
    try {
      final res = await _service.getDashboard();
      if (res['success'] == true && res['data'] != null) {
        final data = res['data'];
        final stats = data['stats'] ?? data;
        setState(() => _stats = Map<String, dynamic>.from(stats));
      }
    } catch (e) {
      setState(() => _error = e.toString());
    }
    if (mounted) setState(() => _isLoading = false);
  }

  @override
  Widget build(BuildContext context) {
    final isDark = AppSettings.instance.isDark;
    SystemChrome.setSystemUIOverlayStyle(SystemUiOverlayStyle(
      statusBarColor: Colors.transparent,
      statusBarIconBrightness: isDark ? Brightness.light : Brightness.dark,
    ));
    final user = _auth.currentUser;
    final name = user?.name ?? 'Landlord';

    return Scaffold(
      backgroundColor: isDark ? const Color(0xFF0F0F0F) : const Color(0xFFF1F5F9),
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
                  if (_isLoading)
                    _buildSkeletonLoading()
                  else ...[
                    _buildPortfolioCard(),
                    const SizedBox(height: 14),
                    _buildMetricsRow(),
                    const SizedBox(height: 20),
                    _buildSectionTitle('Mapato ya Mwezi Huu'),
                    const SizedBox(height: 10),
                    _buildRevenueProgressCard(),
                    const SizedBox(height: 20),
                    _buildSectionTitle('Vitendo vya Haraka'),
                    const SizedBox(height: 10),
                    _buildQuickActionsGrid(context),
                    const SizedBox(height: 20),
                    _buildSectionTitle('Habari za Hivi Karibuni'),
                    const SizedBox(height: 10),
                    _buildActivityFeed(),
                  ],
                ]),
              ),
            ),
          ],
        ),
      ),
    );
  }

  // ─── Sliver Header ────────────────────────────────────────────────────────

  Widget _buildSliverHeader(BuildContext context, String name, String? avatar) {
    final isDark = AppSettings.instance.isDark;
    final settings = AppSettings.instance;
    final bgColor = isDark ? const Color(0xFF141414) : Colors.white;
    final textColor = isDark ? Colors.white : const Color(0xFF0F172A);
    final subColor = isDark ? const Color(0xFF9CA3AF) : const Color(0xFF9CA3AF);
    final btnBg = isDark ? const Color(0xFF1E1E1E) : const Color(0xFFF4F6F8);
    final btnBorder = isDark ? const Color(0xFF2A2A2A) : const Color(0xFFE2E8F0);
    final btnIcon = isDark ? const Color(0xFFD1D5DB) : const Color(0xFF374151);

    return SliverAppBar(
      backgroundColor: bgColor,
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
          // Avatar → goes to profile
          GestureDetector(
            onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const LandlordProfilePage())),
            child: _buildAvatar(name, avatar, isDark),
          ),
          const SizedBox(width: 12),
          // Greeting + Name (tapping name also opens profile)
          Expanded(
            child: GestureDetector(
              onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const LandlordProfilePage())),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                mainAxisSize: MainAxisSize.min,
                children: [
                  Text(_greeting(), style: TextStyle(fontSize: 11, color: subColor, fontWeight: FontWeight.w500)),
                  const SizedBox(height: 2),
                  Row(mainAxisSize: MainAxisSize.min, children: [
                    Text(name.split(' ').first,
                        style: TextStyle(fontSize: 18, fontWeight: FontWeight.w800, color: textColor),
                        overflow: TextOverflow.ellipsis),
                    const SizedBox(width: 4),
                    Icon(Icons.chevron_right_rounded, size: 16, color: subColor),
                  ]),
                ],
              ),
            ),
          ),
          // Language toggle
          _headerBtn(
            child: Text(settings.isEnglish ? 'EN' : 'SW',
                style: TextStyle(fontSize: 11, fontWeight: FontWeight.w800, color: AppColors.primary)),
            bg: btnBg, border: btnBorder,
            onTap: () async {
              HapticFeedback.selectionClick();
              await settings.toggleLocale();
              if (!mounted) return;
              Helpers.showSnackBar(context,
                  settings.isEnglish ? '🌐 Language: English' : '🌐 Lugha: Kiswahili');
            },
          ),
          const SizedBox(width: 8),
          // Dark mode toggle
          _headerBtn(
            child: Icon(isDark ? Icons.light_mode_rounded : Icons.dark_mode_rounded,
                color: isDark ? const Color(0xFFFBBF24) : const Color(0xFF374151), size: 18),
            bg: btnBg, border: btnBorder,
            onTap: () async {
              HapticFeedback.selectionClick();
              await settings.toggleTheme();
            },
          ),
          const SizedBox(width: 8),
          // Notifications with real count
          _notificationBtn(context, btnBg, btnBorder, btnIcon),
        ],
      ),
    );
  }

  Widget _headerBtn({required Widget child, required Color bg, required Color border, required VoidCallback onTap}) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 40, height: 40,
        decoration: BoxDecoration(
          color: bg, borderRadius: BorderRadius.circular(12),
          border: Border.all(color: border),
        ),
        child: Center(child: child),
      ),
    );
  }

  Widget _notificationBtn(BuildContext context, Color bg, Color border, Color iconColor) {
    return GestureDetector(
      onTap: () async {
        HapticFeedback.lightImpact();
        await Navigator.push(context, MaterialPageRoute(builder: (_) => const NotificationsScreen()));
        _loadNotifCount(); // refresh count after returning
      },
      child: Stack(
        clipBehavior: Clip.none,
        children: [
          Container(
            width: 40, height: 40,
            decoration: BoxDecoration(
              color: bg, borderRadius: BorderRadius.circular(12),
              border: Border.all(color: border),
            ),
            child: Icon(
              _unreadNotifications > 0 ? Icons.notifications_rounded : Icons.notifications_outlined,
              color: _unreadNotifications > 0 ? AppColors.primary : iconColor,
              size: 20,
            ),
          ),
          if (_unreadNotifications > 0)
            Positioned(
              top: -4, right: -4,
              child: Container(
                padding: const EdgeInsets.symmetric(horizontal: 5, vertical: 2),
                decoration: BoxDecoration(
                  color: AppColors.primary,
                  borderRadius: BorderRadius.circular(10),
                  border: Border.all(color: Colors.white, width: 1.5),
                ),
                child: Text(
                  _unreadNotifications > 99 ? '99+' : '$_unreadNotifications',
                  style: const TextStyle(color: Colors.white, fontSize: 9, fontWeight: FontWeight.w800),
                ),
              ),
            ),
        ],
      ),
    );
  }

  Widget _buildAvatar(String name, String? avatarUrl, bool isDark) {
    final initials = name.isNotEmpty
        ? name.split(' ').take(2).map((w) => w.isNotEmpty ? w[0] : '').join().toUpperCase()
        : 'L';
    return Container(
      width: 44, height: 44,
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: AppColors.primary.withValues(alpha: 0.35), width: 2),
        color: AppColors.primaryLight,
      ),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(12),
        child: avatarUrl != null && avatarUrl.isNotEmpty
            ? Image.network(avatarUrl, fit: BoxFit.cover,
                errorBuilder: (_, e, __) => Center(child: Text(initials,
                    style: const TextStyle(color: AppColors.primary, fontWeight: FontWeight.w800, fontSize: 16))))
            : Center(child: Text(initials,
                style: const TextStyle(color: AppColors.primary, fontWeight: FontWeight.w800, fontSize: 16))),
      ),
    );
  }

  // ─── Portfolio Card ───────────────────────────────────────────────────────

  Widget _buildPortfolioCard() {
    final props       = _stats['my_properties'] ?? _stats['total_properties'] ?? 0;
    final collected   = (_stats['collected_revenue'] ?? 0.0).toDouble();
    final outstanding = (_stats['outstanding_revenue'] ?? 0.0).toDouble();
    final expected    = (_stats['expected_rent'] ?? 0.0).toDouble();
    final occupied    = _stats['occupied_units'] ?? 0;
    final vacant      = _stats['vacant_units'] ?? 0;
    final rate        = (_stats['occupancy_rate'] ?? 0).toInt();

    return Container(
      decoration: BoxDecoration(
        color: _cardBg,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.06), blurRadius: 20, offset: const Offset(0, 4))],
        border: Border.all(color: _cardBorder),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [

          // ── Top: title + badge
          Padding(
            padding: const EdgeInsets.fromLTRB(18, 16, 18, 0),
            child: Row(
              children: [
                Container(
                  padding: const EdgeInsets.all(9),
                  decoration: BoxDecoration(
                    gradient: const LinearGradient(colors: [AppColors.primary, AppColors.primaryDark]),
                    borderRadius: BorderRadius.circular(10),
                  ),
                  child: const Icon(Icons.business_rounded, color: Colors.white, size: 18),
                ),
                const SizedBox(width: 10),
                const Text('Portfolio Yangu',
                    style: TextStyle(fontSize: 15, fontWeight: FontWeight.w800, color: Color(0xFF0F172A))),
                const Spacer(),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                  decoration: BoxDecoration(
                    color: AppColors.primaryLight,
                    borderRadius: BorderRadius.circular(20),
                    border: Border.all(color: AppColors.primary.withValues(alpha: 0.2)),
                  ),
                  child: Text('$props ${props == 1 ? 'mali' : 'mali'}',
                      style: const TextStyle(color: AppColors.primary, fontSize: 11, fontWeight: FontWeight.w700)),
                ),
              ],
            ),
          ),

          const SizedBox(height: 14),

          // ── Revenue gradient banner
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 14),
            child: Container(
              padding: const EdgeInsets.fromLTRB(18, 16, 18, 16),
              decoration: BoxDecoration(
                gradient: const LinearGradient(
                  colors: [Color(0xFFB44040), Color(0xFF7E2B2B)],
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                ),
                borderRadius: BorderRadius.circular(16),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            const Text('Kodi Iliyokusanywa',
                                style: TextStyle(color: Colors.white70, fontSize: 11, fontWeight: FontWeight.w500)),
                            const SizedBox(height: 4),
                            Text('TZS ${Helpers.formatMoney(collected)}',
                                style: const TextStyle(color: Colors.white, fontSize: 24, fontWeight: FontWeight.w900)),
                            const SizedBox(height: 2),
                            Text('wa TZS ${Helpers.formatMoney(expected)} inayotarajiwa',
                                style: const TextStyle(color: Colors.white60, fontSize: 11)),
                          ],
                        ),
                      ),
                      Container(
                        padding: const EdgeInsets.all(12),
                        decoration: BoxDecoration(
                          color: Colors.white.withValues(alpha: 0.15),
                          borderRadius: BorderRadius.circular(12),
                        ),
                        child: const Icon(Icons.account_balance_wallet_rounded, color: Colors.white, size: 26),
                      ),
                    ],
                  ),
                  if (outstanding > 0) ...[
                    const SizedBox(height: 12),
                    const Divider(color: Colors.white24, height: 1),
                    const SizedBox(height: 10),
                    Row(
                      children: [
                        const Icon(Icons.warning_amber_rounded, color: Colors.orange, size: 14),
                        const SizedBox(width: 6),
                        Text('Inayodaiwa: TZS ${Helpers.formatMoney(outstanding)}',
                            style: const TextStyle(color: Colors.white70, fontSize: 12, fontWeight: FontWeight.w600)),
                      ],
                    ),
                  ],
                ],
              ),
            ),
          ),

          const SizedBox(height: 14),

          // ── Occupancy bar
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 18),
            child: Column(
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text('Kiwango cha Ukaliaji',
                        style: TextStyle(fontSize: 12, color: Color(0xFF6B7280), fontWeight: FontWeight.w600)),
                    Text('$rate%',
                        style: const TextStyle(color: AppColors.primary, fontWeight: FontWeight.w800, fontSize: 13)),
                  ],
                ),
                const SizedBox(height: 7),
                ClipRRect(
                  borderRadius: BorderRadius.circular(8),
                  child: LinearProgressIndicator(
                    value: rate / 100,
                    minHeight: 8,
                    backgroundColor: const Color(0xFFF1F5F9),
                    valueColor: const AlwaysStoppedAnimation<Color>(AppColors.primary),
                  ),
                ),
                const SizedBox(height: 8),
                Row(
                  children: [
                    _dotLabel(const Color(0xFF10B981), '$occupied inayokaliwa'),
                    const SizedBox(width: 16),
                    _dotLabel(const Color(0xFFE5E7EB), '$vacant wazi', border: true),
                  ],
                ),
              ],
            ),
          ),

          const SizedBox(height: 14),
          const Divider(height: 1, color: Color(0xFFF1F5F9)),

          // ── Bottom stats
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 12, 16, 14),
            child: Row(
              children: [
                Expanded(child: _bottomStat(Icons.apartment_rounded, 'Jumla ya Mali',
                    '$props', const Color(0xFFB44040))),
                Container(width: 1, height: 40, color: const Color(0xFFE2E8F0)),
                Expanded(child: _bottomStat(Icons.trending_up_rounded, 'Kodi Inayotarajiwa',
                    'TZS ${Helpers.formatMoney(expected)}', const Color(0xFF10B981))),
                Container(width: 1, height: 40, color: const Color(0xFFE2E8F0)),
                Expanded(child: _bottomStat(Icons.home_work_rounded, 'Zinazokaliwa',
                    '$occupied', const Color(0xFF3B82F6))),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _dotLabel(Color color, String label, {bool border = false}) {
    return Row(children: [
      Container(width: 8, height: 8,
        decoration: BoxDecoration(color: color, shape: BoxShape.circle,
          border: border ? Border.all(color: const Color(0xFFD1D5DB)) : null)),
      const SizedBox(width: 5),
      Text(label, style: const TextStyle(color: Color(0xFF6B7280), fontSize: 11)),
    ]);
  }

  Widget _bottomStat(IconData icon, String label, String value, Color color) {
    return Column(
      children: [
        Container(padding: const EdgeInsets.all(7),
          decoration: BoxDecoration(color: color.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(9)),
          child: Icon(icon, color: color, size: 16)),
        const SizedBox(height: 5),
        Text(label, style: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 9, fontWeight: FontWeight.w600),
            textAlign: TextAlign.center),
        const SizedBox(height: 2),
        Text(value, style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 12, color: Color(0xFF0F172A)),
            textAlign: TextAlign.center, overflow: TextOverflow.ellipsis),
      ],
    );
  }

  // ─── Metrics Row ──────────────────────────────────────────────────────────

  Widget _buildMetricsRow() {
    final pending     = _stats['pending_payments'] ?? 0;
    final maintenance = _stats['maintenance_requests'] ?? 0;
    final leases      = _stats['active_leases'] ?? 0;

    return Row(children: [
      Expanded(child: _metricCard('Malipo\nYanayosubiri', '$pending',
          Icons.hourglass_top_rounded, const Color(0xFFF59E0B), const Color(0xFFFEF9C3))),
      const SizedBox(width: 10),
      Expanded(child: _metricCard('Matengenezo\nYenye Kazi', '$maintenance',
          Icons.build_rounded, const Color(0xFF3B82F6), const Color(0xFFDBEAFE))),
      const SizedBox(width: 10),
      Expanded(child: _metricCard('Mikataba\nHai', '$leases',
          Icons.description_rounded, const Color(0xFF10B981), const Color(0xFFDCFCE7))),
    ]);
  }

  Widget _metricCard(String label, String value, IconData icon, Color color, Color bg) {
    return Container(
      padding: const EdgeInsets.fromLTRB(12, 14, 12, 14),
      decoration: BoxDecoration(
        color: _cardBg,
        borderRadius: BorderRadius.circular(14),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.04), blurRadius: 8)],
        border: Border.all(color: _cardBorder),
      ),
      child: Column(
        children: [
          Container(padding: const EdgeInsets.all(9),
            decoration: BoxDecoration(color: bg, borderRadius: BorderRadius.circular(10)),
            child: Icon(icon, color: color, size: 18)),
          const SizedBox(height: 8),
          Text(value, style: TextStyle(color: color, fontWeight: FontWeight.w900, fontSize: 22)),
          const SizedBox(height: 3),
          Text(label, style: TextStyle(color: _textSecondary, fontSize: 10, fontWeight: FontWeight.w500),
              textAlign: TextAlign.center),
        ],
      ),
    );
  }

  // ─── Revenue Progress ─────────────────────────────────────────────────────

  Widget _buildRevenueProgressCard() {
    final collected = (_stats['collected_revenue'] ?? 0.0).toDouble();
    final expected  = (_stats['expected_rent'] ?? 0.0).toDouble();
    final pct       = expected > 0 ? (collected / expected).clamp(0.0, 1.0) : 0.0;
    final outstanding = (_stats['outstanding_revenue'] ?? 0.0).toDouble();

    return Container(
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        color: _cardBg,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.04), blurRadius: 10)],
        border: Border.all(color: _cardBorder),
      ),
      child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
        Row(children: [
          Container(padding: const EdgeInsets.all(8),
            decoration: BoxDecoration(color: AppColors.primaryLight, borderRadius: BorderRadius.circular(9)),
            child: const Icon(Icons.bar_chart_rounded, color: AppColors.primary, size: 18)),
          const SizedBox(width: 10),
          const Expanded(child: Text('Mapato ya Mwezi Huu',
              style: TextStyle(fontWeight: FontWeight.w700, fontSize: 13, color: Color(0xFF0F172A)))),
          Text('${(pct * 100).toStringAsFixed(0)}%',
              style: const TextStyle(color: AppColors.primary, fontWeight: FontWeight.w800, fontSize: 14)),
        ]),
        const SizedBox(height: 14),
        ClipRRect(
          borderRadius: BorderRadius.circular(8),
          child: LinearProgressIndicator(
            value: pct, minHeight: 10,
            backgroundColor: const Color(0xFFF1F5F9),
            valueColor: const AlwaysStoppedAnimation<Color>(AppColors.primary),
          ),
        ),
        const SizedBox(height: 12),
        Row(children: [
          _revenueChip('Imekusanywa', 'TZS ${Helpers.formatMoney(collected)}',
              const Color(0xFF10B981), const Color(0xFFDCFCE7)),
          const SizedBox(width: 8),
          _revenueChip('Inayodaiwa', 'TZS ${Helpers.formatMoney(outstanding)}',
              const Color(0xFFEF4444), const Color(0xFFFEE2E2)),
        ]),
      ]),
    );
  }

  Widget _revenueChip(String label, String value, Color color, Color bg) {
    return Expanded(
      child: Container(
        padding: const EdgeInsets.all(10),
        decoration: BoxDecoration(color: bg, borderRadius: BorderRadius.circular(10)),
        child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
          Text(label, style: TextStyle(fontSize: 10, color: color, fontWeight: FontWeight.w600)),
          const SizedBox(height: 3),
          Text(value, style: TextStyle(fontSize: 12, color: color, fontWeight: FontWeight.w800),
              overflow: TextOverflow.ellipsis),
        ]),
      ),
    );
  }

  // ─── Quick Actions ────────────────────────────────────────────────────────

  Widget _buildQuickActionsGrid(BuildContext context) {
    final actions = [
      {'label': 'Mali zangu', 'icon': Icons.apartment_rounded, 'color': const Color(0xFFB44040), 'bg': const Color(0xFFFDF0F0)},
      {'label': 'Mikataba', 'icon': Icons.description_rounded, 'color': const Color(0xFF3B82F6), 'bg': const Color(0xFFDBEAFE)},
      {'label': 'Wakazi', 'icon': Icons.people_rounded, 'color': const Color(0xFF10B981), 'bg': const Color(0xFFDCFCE7)},
      {'label': 'Subscription', 'icon': Icons.workspace_premium_rounded, 'color': const Color(0xFFF59E0B), 'bg': const Color(0xFFFEF9C3)},
      {'label': 'Matengenezo', 'icon': Icons.build_rounded, 'color': const Color(0xFF8B5CF6), 'bg': const Color(0xFFF3E8FF)},
      {'label': 'Ripoti', 'icon': Icons.analytics_rounded, 'color': const Color(0xFF0891B2), 'bg': const Color(0xFFCFFAFE)},
    ];

    return GridView.builder(
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 3, crossAxisSpacing: 10, mainAxisSpacing: 10, childAspectRatio: 1.05,
      ),
      itemCount: actions.length,
      itemBuilder: (ctx, i) {
        final a = actions[i];
        return GestureDetector(
          onTap: () {
            if (a['label'] == 'Subscription') {
              Navigator.push(context, MaterialPageRoute(builder: (_) => const LandlordSubscriptionPage()));
            }
          },
          child: Container(
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(14),
              border: Border.all(color: const Color(0xFFE2E8F0)),
              boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.03), blurRadius: 6)],
            ),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Container(padding: const EdgeInsets.all(10),
                  decoration: BoxDecoration(color: a['bg'] as Color, borderRadius: BorderRadius.circular(12)),
                  child: Icon(a['icon'] as IconData, color: a['color'] as Color, size: 22)),
                const SizedBox(height: 7),
                Text(a['label'] as String,
                    style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 11, color: Color(0xFF374151)),
                    textAlign: TextAlign.center),
              ],
            ),
          ),
        );
      },
    );
  }

  // ─── Activity Feed ────────────────────────────────────────────────────────

  Widget _buildActivityFeed() {
    final pending     = _stats['pending_payments'] ?? 0;
    final maintenance = _stats['maintenance_requests'] ?? 0;
    final leases      = _stats['active_leases'] ?? 0;
    final props       = _stats['my_properties'] ?? _stats['total_properties'] ?? 0;

    final items = [
      if (pending > 0) _ActivityItem(
        icon: Icons.warning_amber_rounded, color: const Color(0xFFF59E0B), bg: const Color(0xFFFEF9C3),
        title: '$pending malipo yanayosubiri', subtitle: 'Angalia wakazi ambao hawajalipa kodi'),
      if (maintenance > 0) _ActivityItem(
        icon: Icons.build_rounded, color: const Color(0xFF3B82F6), bg: const Color(0xFFDBEAFE),
        title: '$maintenance maombi ya matengenezo', subtitle: 'Yanahitaji umakini wako'),
      _ActivityItem(
        icon: Icons.home_work_rounded, color: const Color(0xFF10B981), bg: const Color(0xFFDCFCE7),
        title: '$leases mikataba hai', subtitle: '$props mali kwenye portfolio yako'),
    ];

    if (items.isEmpty) {
      return Container(
        padding: const EdgeInsets.all(24),
        decoration: BoxDecoration(
          color: _cardBg, borderRadius: BorderRadius.circular(14),
          border: Border.all(color: _cardBorder),
        ),
        child: Center(
          child: Text('Hakuna habari mpya kwa sasa.',
              style: TextStyle(color: _textSecondary, fontSize: 13)),
        ),
      );
    }

    return Container(
      decoration: BoxDecoration(
        color: _cardBg, borderRadius: BorderRadius.circular(16),
        border: Border.all(color: _cardBorder),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.04), blurRadius: 8)],
      ),
      child: Column(
        children: items.asMap().entries.map((e) {
          final item = e.value;
          final last = e.key == items.length - 1;
          return Column(
            children: [
              Padding(
                padding: const EdgeInsets.fromLTRB(16, 14, 16, 14),
                child: Row(
                  children: [
                    Container(width: 40, height: 40,
                      decoration: BoxDecoration(color: item.bg, borderRadius: BorderRadius.circular(12)),
                      child: Icon(item.icon, color: item.color, size: 20)),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                        Text(item.title, style: const TextStyle(fontWeight: FontWeight.w700,
                            fontSize: 13, color: Color(0xFF0F172A))),
                        const SizedBox(height: 2),
                        Text(item.subtitle, style: const TextStyle(fontSize: 11, color: Color(0xFF9CA3AF))),
                      ]),
                    ),
                    const Icon(Icons.chevron_right, color: Color(0xFFD1D5DB), size: 18),
                  ],
                ),
              ),
              if (!last) const Divider(height: 1, color: Color(0xFFF1F5F9), indent: 16, endIndent: 16),
            ],
          );
        }).toList(),
      ),
    );
  }

  // ─── Skeleton loading ─────────────────────────────────────────────────────

  Widget _buildSkeletonLoading() {
    return Column(children: [
      _skeletonBox(height: 240),
      const SizedBox(height: 14),
      Row(children: [
        Expanded(child: _skeletonBox(height: 90)),
        const SizedBox(width: 10),
        Expanded(child: _skeletonBox(height: 90)),
        const SizedBox(width: 10),
        Expanded(child: _skeletonBox(height: 90)),
      ]),
    ]);
  }

  Widget _skeletonBox({double height = 80}) {
    return Container(
      height: height,
      decoration: BoxDecoration(
        color: const Color(0xFFE2E8F0),
        borderRadius: BorderRadius.circular(14),
      ),
    );
  }

  Widget _buildSectionTitle(String title) {
    final isDark = AppSettings.instance.isDark;
    return Text(title,
        style: TextStyle(fontSize: 15, fontWeight: FontWeight.w800,
            color: isDark ? Colors.white : const Color(0xFF0F172A)));
  }

  // Returns the surface card color based on theme
  Color get _cardBg => AppSettings.instance.isDark ? const Color(0xFF1C1C1C) : Colors.white;
  Color get _cardBorder => AppSettings.instance.isDark ? const Color(0xFF2A2A2A) : const Color(0xFFE2E8F0);
  Color get _textPrimary => AppSettings.instance.isDark ? Colors.white : const Color(0xFF0F172A);
  Color get _textSecondary => AppSettings.instance.isDark ? const Color(0xFF9CA3AF) : const Color(0xFF64748B);
  Color get _dividerColor => AppSettings.instance.isDark ? const Color(0xFF2A2A2A) : const Color(0xFFF1F5F9);

  String _greeting() {
    final h = DateTime.now().hour;
    if (h < 12) return 'Habari za asubuhi,';
    if (h < 17) return 'Habari za mchana,';
    return 'Habari za jioni,';
  }
}

class _ActivityItem {
  final IconData icon;
  final Color color, bg;
  final String title, subtitle;
  const _ActivityItem({required this.icon, required this.color, required this.bg, required this.title, required this.subtitle});
}
