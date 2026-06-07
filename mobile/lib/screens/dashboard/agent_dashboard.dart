import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../constants/app_colors.dart';
import '../../models/user_model.dart';
import '../../models/dashboard_stats_model.dart';
import '../../utils/helpers.dart';
import '../../widgets/dashboard_card.dart';

class AgentDashboard extends StatelessWidget {
  final UserModel user;
  final DashboardStatsModel stats;
  final VoidCallback onRefresh;

  const AgentDashboard({
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

          // 2. Agency Highlight Card
          _buildAgencyHighlightCard(context),
          const SizedBox(height: 24),

          // 3. Quick Actions
          _buildQuickActions(context),
          const SizedBox(height: 24),

          // 4. Statistics Grid
          const Text(
            'Takwimu za Uwakala (My Agency)',
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
                'WAKALA CREDENTIALS',
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

  Widget _buildAgencyHighlightCard(BuildContext context) {
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
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(
                  color: const Color(0xfff59e0b).withOpacity(0.1),
                  shape: BoxShape.circle,
                ),
                child: const Icon(Icons.star_rounded, color: Color(0xfff59e0b), size: 20),
              ),
              const SizedBox(width: 12),
              Text(
                'Utendaji wa Wakala (Agent Rating)',
                style: GoogleFonts.inter(
                  fontSize: 14,
                  fontWeight: FontWeight.w700,
                  color: const Color(0xff1f2937),
                ),
              ),
            ],
          ),
          const SizedBox(height: 16),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Kiwango cha Huduma',
                    style: TextStyle(color: Colors.grey[500], fontSize: 11),
                  ),
                  const SizedBox(height: 4),
                  Row(
                    children: [
                      Text(
                        '4.9',
                        style: GoogleFonts.inter(
                          fontSize: 22,
                          fontWeight: FontWeight.w800,
                          color: const Color(0xff1f2937),
                        ),
                      ),
                      const SizedBox(width: 4),
                      const Icon(Icons.star, color: Color(0xfff59e0b), size: 18),
                      const Icon(Icons.star, color: Color(0xfff59e0b), size: 18),
                      const Icon(Icons.star, color: Color(0xfff59e0b), size: 18),
                      const Icon(Icons.star, color: Color(0xfff59e0b), size: 18),
                      const Icon(Icons.star, color: Color(0xfff59e0b), size: 18),
                    ],
                  ),
                ],
              ),
              Column(
                crossAxisAlignment: CrossAxisAlignment.end,
                children: [
                  Text(
                    'Kamisheni Inayosubiri',
                    style: TextStyle(color: Colors.grey[500], fontSize: 11),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    'TSh 450,000',
                    style: GoogleFonts.inter(
                      fontSize: 16,
                      fontWeight: FontWeight.w800,
                      color: const Color(0xff10b981),
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
      {'label': 'Sajili Nyumba', 'icon': Icons.playlist_add, 'color': const Color(0xff10b981)},
      {'label': 'Wenye Nyumba', 'icon': Icons.supervisor_account, 'color': const Color(0xff6366f1)},
      {'label': 'Ratiba ya Kazi', 'icon': Icons.calendar_month, 'color': const Color(0xfff59e0b)},
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
    final keys = ['total_properties', 'total_landlords', 'total_tenants', 'total_revenue', 'pending_payments', 'maintenance_requests'];
    final colorPalette = [
      AppColors.primary,
      const Color(0xFF6366F1),
      const Color(0xFF3B82F6),
      const Color(0xFF10B981),
      const Color(0xFFEF4444),
      const Color(0xFFF59E0B),
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
      case 'total_properties': return 'Nyumba Nazosimamia';
      case 'total_landlords': return 'Wenye Nyumba';
      case 'total_tenants': return 'Wapangaji';
      case 'total_revenue': return 'Thamani ya Kodi';
      case 'pending_payments': return 'Kamisheni Yangu';
      case 'maintenance_requests': return 'Maombi ya Mafundi';
      default: return key.replaceAll('_', ' ');
    }
  }

  IconData _statIcon(String key) {
    switch (key) {
      case 'total_properties': return Icons.gite_rounded;
      case 'total_landlords': return Icons.supervisor_account_rounded;
      case 'total_tenants': return Icons.supervised_user_circle_rounded;
      case 'total_revenue': return Icons.account_balance_rounded;
      case 'pending_payments': return Icons.percent_rounded;
      case 'maintenance_requests': return Icons.build;
      default: return Icons.info;
    }
  }

  String _formatStatValue(dynamic value) {
    if (value == null) return '0';
    if (value is double) {
      if (value >= 1000000) {
        return 'TSh ${(value / 1000000).toStringAsFixed(1)}M';
      } else if (value >= 1000) {
        return 'TSh ${(value / 1000).toStringAsFixed(0)}K';
      }
      return value.toStringAsFixed(0);
    }
    return value.toString();
  }
}
