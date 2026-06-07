import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../constants/app_colors.dart';
import '../../models/user_model.dart';
import '../../models/dashboard_stats_model.dart';
import '../../utils/helpers.dart';
import '../../widgets/dashboard_card.dart';

class TenantDashboard extends StatelessWidget {
  final UserModel user;
  final DashboardStatsModel stats;
  final VoidCallback onRefresh;

  const TenantDashboard({
    super.key,
    required this.user,
    required this.stats,
    required this.onRefresh,
  });

  @override
  Widget build(BuildContext context) {
    final statsMap = stats.stats;
    final recentItems = stats.recentItems ?? [];

    return RefreshIndicator(
      onRefresh: () async => onRefresh(),
      color: AppColors.primary,
      child: ListView(
        padding: const EdgeInsets.all(20),
        children: [
          // 1. Credentials display card
          _buildCredentialsCard(),
          const SizedBox(height: 24),

          // 2. Active Rental Highlight Card
          _buildRentStatusCard(context),
          const SizedBox(height: 24),

          // 3. Quick Actions
          _buildQuickActions(context),
          const SizedBox(height: 24),

          // 4. Statistics Grid
          const Text(
            'Takwimu za Mpangaji (My Statistics)',
            style: TextStyle(
              fontSize: 16,
              fontWeight: FontWeight.w700,
              color: Color(0xFF0F172A),
            ),
          ),
          const SizedBox(height: 12),
          _buildStatsGrid(statsMap),
          const SizedBox(height: 24),

          // 5. Recent Activities
          if (recentItems.isNotEmpty) ...[
            const Text(
              'Shughuli za Hivi Karibuni',
              style: TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.w700,
                color: Color(0xFF0F172A),
              ),
            ),
            const SizedBox(height: 12),
            ...recentItems.map((item) => _buildRecentItem(item)),
          ],
        ],
      ),
    );
  }

  Widget _buildCredentialsCard() {
    return Container(
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          colors: [Color(0xff10b981), Color(0xff059669)],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: const Color(0xff10b981).withOpacity(0.25),
            blurRadius: 12,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                'MPANGAJI CREDENTIALS',
                style: GoogleFonts.inter(
                  fontSize: 10,
                  fontWeight: FontWeight.w800,
                  color: Colors.white.withOpacity(0.85),
                  letterSpacing: 1.2,
                ),
              ),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                decoration: BoxDecoration(
                  color: Colors.white.withOpacity(0.2),
                  borderRadius: BorderRadius.circular(20),
                ),
                child: Text(
                  user.roleLabel.toUpperCase(),
                  style: const TextStyle(
                    color: Colors.white,
                    fontSize: 10,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          Text(
            user.name,
            style: GoogleFonts.inter(
              fontSize: 20,
              fontWeight: FontWeight.w800,
              color: Colors.white,
            ),
          ),
          const SizedBox(height: 8),
          Row(
            children: [
              const Icon(Icons.email_outlined, color: Colors.white70, size: 14),
              const SizedBox(width: 8),
              Text(
                user.email,
                style: GoogleFonts.inter(color: Colors.white70, fontSize: 13, fontWeight: FontWeight.w500),
              ),
            ],
          ),
          const SizedBox(height: 4),
          Row(
            children: [
              const Icon(Icons.phone_outlined, color: Colors.white70, size: 14),
              const SizedBox(width: 8),
              Text(
                user.phone,
                style: GoogleFonts.inter(color: Colors.white70, fontSize: 13, fontWeight: FontWeight.w500),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildRentStatusCard(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: const Color(0xff10b981).withOpacity(0.3)),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.02),
            blurRadius: 6,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Column(
        children: [
          Row(
            children: [
              Container(
                padding: const EdgeInsets.all(10),
                decoration: BoxDecoration(
                  color: const Color(0xff10b981).withOpacity(0.1),
                  shape: BoxShape.circle,
                ),
                child: const Icon(Icons.house, color: Color(0xff10b981), size: 24),
              ),
              const SizedBox(width: 14),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Apartment A4 - Palm Heights',
                      style: GoogleFonts.inter(
                        fontSize: 15,
                        fontWeight: FontWeight.w700,
                        color: const Color(0xff1f2937),
                      ),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      'Dar es Salaam, Tanzania',
                      style: GoogleFonts.inter(
                        fontSize: 12,
                        color: Colors.grey[500],
                      ),
                    ),
                  ],
                ),
              ),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                decoration: BoxDecoration(
                  color: const Color(0xffecfdf5),
                  borderRadius: BorderRadius.circular(20),
                  border: Border.all(color: const Color(0xffa7f3d0)),
                ),
                child: const Text(
                  'KODI IMELIPWA',
                  style: TextStyle(
                    color: Color(0xff047857),
                    fontSize: 10,
                    fontWeight: FontWeight.w700,
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 16),
          const Divider(),
          const SizedBox(height: 12),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Kodi ya Mwezi huu',
                    style: TextStyle(color: Colors.grey[500], fontSize: 11),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    'TSh 450,000',
                    style: GoogleFonts.inter(
                      fontSize: 16,
                      fontWeight: FontWeight.w800,
                      color: const Color(0xff1f2937),
                    ),
                  ),
                ],
              ),
              Column(
                crossAxisAlignment: CrossAxisAlignment.end,
                children: [
                  Text(
                    'Tarehe ya Mwisho',
                    style: TextStyle(color: Colors.grey[500], fontSize: 11),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    '30 June 2026',
                    style: GoogleFonts.inter(
                      fontSize: 14,
                      fontWeight: FontWeight.w600,
                      color: const Color(0xffef4444),
                    ),
                  ),
                ],
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildQuickActions(BuildContext context) {
    final actions = [
      {'label': 'Lipa Kodi', 'icon': Icons.payment, 'color': const Color(0xff10b981)},
      {'label': 'Omba Fundi', 'icon': Icons.build, 'color': const Color(0xff6366f1)},
      {'label': 'Wasiliana', 'icon': Icons.message, 'color': const Color(0xfff59e0b)},
    ];

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Text(
          'Njia za Mkato (Quick Actions)',
          style: TextStyle(
            fontSize: 16,
            fontWeight: FontWeight.w700,
            color: Color(0xFF0F172A),
          ),
        ),
        const SizedBox(height: 12),
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: actions.map((act) {
            return InkWell(
              onTap: () {
                Helpers.showSnackBar(context, 'Kazi ya "${act['label']}" inakuja hivi karibuni!');
              },
              child: Container(
                width: MediaQuery.of(context).size.width * 0.27,
                padding: const EdgeInsets.symmetric(vertical: 12),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(12),
                  border: Border.all(color: const Color(0xffe5e7eb)),
                  boxShadow: [
                    BoxShadow(
                      color: Colors.black.withOpacity(0.02),
                      blurRadius: 4,
                      offset: const Offset(0, 2),
                    ),
                  ],
                ),
                child: Column(
                  children: [
                    Icon(act['icon'] as IconData, color: act['color'] as Color, size: 24),
                    const SizedBox(height: 6),
                    Text(
                      act['label'] as String,
                      style: GoogleFonts.inter(
                        fontSize: 10,
                        fontWeight: FontWeight.w600,
                        color: const Color(0xff374151),
                      ),
                      textAlign: TextAlign.center,
                    ),
                  ],
                ),
              ),
            );
          }).toList(),
        ),
      ],
    );
  }

  Widget _buildStatsGrid(Map<String, dynamic> stats) {
    final cards = <DashboardCard>[];
    final keys = ['total_properties', 'active_rentals', 'total_revenue', 'maintenance_requests'];
    final colorPalette = [
      AppColors.primary,
      const Color(0xFF6366F1),
      const Color(0xFFF59E0B),
      const Color(0xFFEF4444),
    ];

    var index = 0;
    for (var key in keys) {
      if (stats.containsKey(key)) {
        final color = colorPalette[index % colorPalette.length];
        cards.add(DashboardCard(
          title: _statLabel(key),
          value: _formatStatValue(stats[key]),
          icon: _statIcon(key),
          color: color,
        ));
        index++;
      }
    }

    return Column(
      children: [
        for (var i = 0; i < cards.length; i += 2)
          Padding(
            padding: const EdgeInsets.only(bottom: 12),
            child: Row(
              children: [
                Expanded(child: cards[i]),
                const SizedBox(width: 12),
                Expanded(
                  child: i + 1 < cards.length ? cards[i + 1] : const SizedBox(),
                ),
              ],
            ),
          ),
      ],
    );
  }

  Widget _buildRecentItem(dynamic item) {
    final map = item is Map<String, dynamic> ? item : <String, dynamic>{};
    return Container(
      margin: const EdgeInsets.only(bottom: 8),
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.03),
            blurRadius: 6,
            offset: const Offset(0, 1),
          ),
        ],
      ),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(8),
            decoration: BoxDecoration(
              color: AppColors.primary.withOpacity(0.1),
              borderRadius: BorderRadius.circular(8),
            ),
            child: const Icon(Icons.history, color: AppColors.primary, size: 18),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Text(
              map['description'] ?? map['title'] ?? 'Activity',
              style: const TextStyle(
                fontSize: 13,
                fontWeight: FontWeight.w500,
                color: Color(0xFF0F172A),
              ),
            ),
          ),
          Text(
            map['time'] ?? map['created_at'] ?? '',
            style: TextStyle(fontSize: 11, color: Colors.grey[400]),
          ),
        ],
      ),
    );
  }

  String _statLabel(String key) {
    switch (key) {
      case 'total_properties': return 'Mkataba Wangu';
      case 'active_rentals': return 'Kodi Inaendelea';
      case 'total_revenue': return 'Kodi ya Mwezi';
      case 'maintenance_requests': return 'Matengenezo';
      default: return key.replaceAll('_', ' ');
    }
  }

  IconData _statIcon(String key) {
    switch (key) {
      case 'total_properties': return Icons.assignment_turned_in_rounded;
      case 'active_rentals': return Icons.key;
      case 'total_revenue': return Icons.account_balance_wallet_rounded;
      case 'maintenance_requests': return Icons.build;
      default: return Icons.info;
    }
  }

  String _formatStatValue(dynamic value) {
    if (value == null) return '0';
    if (value is double) {
      if (value >= 100000) {
        return 'TSh ${(value / 1000).toStringAsFixed(0)}K';
      }
      return value.toStringAsFixed(0);
    }
    return value.toString();
  }
}
