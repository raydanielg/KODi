import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../constants/app_colors.dart';
import '../../models/user_model.dart';
import '../../models/dashboard_stats_model.dart';
import '../../services/auth_service.dart';
import '../../services/dashboard_service.dart';
import '../../utils/helpers.dart';
import '../../widgets/dashboard_card.dart';
import '../../widgets/role_drawer.dart';

class LandlordDashboard extends StatefulWidget {
  const LandlordDashboard({super.key});

  @override
  State<LandlordDashboard> createState() => _LandlordDashboardState();
}

class _LandlordDashboardState extends State<LandlordDashboard> {
  final AuthService _authService = AuthService();
  final DashboardService _dashboardService = DashboardService();

  DashboardStatsModel? _stats;
  bool _isLoading = true;
  String? _error;

  @override
  void initState() {
    super.initState();
    _loadDashboard();
  }

  Future<void> _loadDashboard() async {
    setState(() {
      _isLoading = true;
      _error = null;
    });
    try {
      final stats = await _dashboardService.fetchDashboardStats();
      setState(() {
        _stats = stats;
        _isLoading = false;
      });
    } catch (_) {
      // Local Fallback Mock Stats
      setState(() {
        _stats = DashboardStatsModel(
          stats: {
            'total_properties': 12,
            'active_rentals': 9,
            'vacant_properties': 3,
            'total_revenue': 3850000.0,
            'pending_payments': 900000.0,
            'maintenance_requests': 2,
          },
          recentItems: [
            {'title': 'Kodi imelipwa na Daniel Juma (Apt A4)', 'time': 'Masaa 2 yaliyopita'},
            {'title': 'Ombi la matengenezo toka kwa Aisha (Apt B2)', 'time': 'Leo asubuhi'},
            {'title': 'Mpangaji mpya kajiunga (Hamis - Apt C1)', 'time': 'Siku 2 zilizopita'},
          ],
        );
        _isLoading = false;
      });
    }
  }

  String _getGreeting() {
    final hour = DateTime.now().hour;
    if (hour < 12) {
      return 'Good Morning ☀️';
    } else if (hour < 17) {
      return 'Good Afternoon 🌤️';
    } else {
      return 'Good Evening 🌙';
    }
  }

  @override
  Widget build(BuildContext context) {
    final user = _authService.currentUser!;

    return Scaffold(
      drawer: RoleDrawer(authService: _authService),
      backgroundColor: const Color(0xfff9fafb),
      body: Column(
        children: [
          // 1. Beautiful Custom Header
          _buildTopHeader(user),
          
          // 2. Dashboard Body Content
          Expanded(
            child: _isLoading
                ? const Center(
                    child: CircularProgressIndicator(color: AppColors.primary),
                  )
                : RefreshIndicator(
                    onRefresh: _loadDashboard,
                    color: AppColors.primary,
                    child: ListView(
                      padding: const EdgeInsets.all(20),
                      children: [
                        // Highlight Credentials Display Card
                        _buildCredentialsCard(user),
                        const SizedBox(height: 24),

                        // Portfolio Overview Highlight Card
                        _buildPortfolioHighlightCard(context),
                        const SizedBox(height: 24),

                        // Quick Actions
                        _buildQuickActions(context),
                        const SizedBox(height: 24),

                        // Statistics Grid
                        const Text(
                          'Takwimu za Mwenye Nyumba (My Portfolio)',
                          style: TextStyle(
                            fontSize: 16,
                            fontWeight: FontWeight.w700,
                            color: Color(0xFF0F172A),
                          ),
                        ),
                        const SizedBox(height: 12),
                        _buildStatsGrid(_stats!.stats),
                        const SizedBox(height: 24),

                        // Recent Activities
                        if (_stats!.recentItems != null && _stats!.recentItems!.isNotEmpty) ...[
                          const Text(
                            'Shughuli za Hivi Karibuni',
                            style: TextStyle(
                              fontSize: 16,
                              fontWeight: FontWeight.w700,
                              color: Color(0xFF0F172A),
                            ),
                          ),
                          const SizedBox(height: 12),
                          ..._stats!.recentItems!.map((item) => _buildRecentItem(item)),
                        ],
                      ],
                    ),
                  ),
          ),
        ],
      ),
    );
  }

  Widget _buildTopHeader(UserModel user) {
    return Container(
      padding: const EdgeInsets.only(left: 20, right: 20, top: 48, bottom: 16),
      decoration: const BoxDecoration(
        color: Colors.white,
        border: Border(
          bottom: BorderSide(color: Color(0xffe5e7eb), width: 1),
        ),
      ),
      child: Row(
        children: [
          // 1. Avatar with drawer trigger or popup profile
          Builder(
            builder: (context) => GestureDetector(
              onTap: () {
                Scaffold.of(context).openDrawer();
              },
              child: CircleAvatar(
                radius: 22,
                backgroundColor: AppColors.primary.withOpacity(0.15),
                child: Text(
                  user.initials,
                  style: GoogleFonts.poppins(
                    color: AppColors.primary,
                    fontWeight: FontWeight.w700,
                    fontSize: 14,
                  ),
                ),
              ),
            ),
          ),
          const SizedBox(width: 12),
          // 2. Greeting and Name Column
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisSize: MainAxisSize.min,
              children: [
                Text(
                  _getGreeting(),
                  style: GoogleFonts.poppins(
                    fontSize: 11,
                    color: Colors.grey[500],
                    fontWeight: FontWeight.w600,
                  ),
                ),
                const SizedBox(height: 2),
                Text(
                  user.name,
                  style: GoogleFonts.poppins(
                    fontSize: 15,
                    fontWeight: FontWeight.w800,
                    color: const Color(0xff111827),
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
              ],
            ),
          ),
          // 3. Right Icons (Notification with badge, Settings icon)
          Row(
            mainAxisSize: MainAxisSize.min,
            children: [
              Stack(
                children: [
                  IconButton(
                    onPressed: () {
                      Helpers.showSnackBar(context, 'Arifa (Notifications) zitafunguka hapa!');
                    },
                    icon: const Icon(Icons.notifications_none_rounded, color: Color(0xff4b5563), size: 24),
                    style: IconButton.styleFrom(
                      backgroundColor: const Color(0xfff3f4f6),
                      padding: const EdgeInsets.all(8),
                    ),
                  ),
                  Positioned(
                    top: 6,
                    right: 6,
                    child: Container(
                      width: 8,
                      height: 8,
                      decoration: const BoxDecoration(
                        color: Color(0xffef4444),
                        shape: BoxShape.circle,
                      ),
                    ),
                  ),
                ],
              ),
              const SizedBox(width: 8),
              IconButton(
                onPressed: () {
                  Helpers.showSnackBar(context, 'Mipangilio (Settings) itafunguka hapa!');
                },
                icon: const Icon(Icons.settings_outlined, color: Color(0xff4b5563), size: 24),
                style: IconButton.styleFrom(
                  backgroundColor: const Color(0xfff3f4f6),
                  padding: const EdgeInsets.all(8),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildCredentialsCard(UserModel user) {
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
                'MWENYE NYUMBA CREDENTIALS',
                style: GoogleFonts.poppins(
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
            style: GoogleFonts.poppins(
              fontSize: 18,
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
                style: GoogleFonts.poppins(color: Colors.white70, fontSize: 13, fontWeight: FontWeight.w500),
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
                style: GoogleFonts.poppins(color: Colors.white70, fontSize: 13, fontWeight: FontWeight.w500),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildPortfolioHighlightCard(BuildContext context) {
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
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Row(
                children: [
                  Container(
                    padding: const EdgeInsets.all(8),
                    decoration: BoxDecoration(
                      color: const Color(0xff6366f1).withOpacity(0.1),
                      shape: BoxShape.circle,
                    ),
                    child: const Icon(Icons.analytics_rounded, color: Color(0xff6366f1), size: 20),
                  ),
                  const SizedBox(width: 12),
                  Text(
                    'Ukusanyaji wa Kodi (Collection Status)',
                    style: GoogleFonts.poppins(
                      fontSize: 14,
                      fontWeight: FontWeight.w700,
                      color: const Color(0xff1f2937),
                    ),
                  ),
                ],
              ),
            ],
          ),
          const SizedBox(height: 16),
          // Progress Bar
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                'Ukusanyaji wa Mwezi huu',
                style: TextStyle(color: Colors.grey[600], fontSize: 12),
              ),
              Text(
                '81% Imekusanywa',
                style: GoogleFonts.poppins(
                  color: const Color(0xff10b981),
                  fontWeight: FontWeight.w700,
                  fontSize: 12,
                ),
              ),
            ],
          ),
          const SizedBox(height: 8),
          ClipRRect(
            borderRadius: BorderRadius.circular(4),
            child: const LinearProgressIndicator(
              value: 0.81,
              backgroundColor: Color(0xfff3f4f6),
              valueColor: AlwaysStoppedAnimation<Color>(Color(0xff10b981)),
              minHeight: 8,
            ),
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
                    'Zilizolipwa (Received)',
                    style: TextStyle(color: Colors.grey[500], fontSize: 11),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    'TSh 3,850,000',
                    style: GoogleFonts.poppins(
                      fontSize: 15,
                      fontWeight: FontWeight.w800,
                      color: const Color(0xff10b981),
                    ),
                  ),
                ],
              ),
              Column(
                crossAxisAlignment: CrossAxisAlignment.end,
                children: [
                  Text(
                    'Bado (Pending)',
                    style: TextStyle(color: Colors.grey[500], fontSize: 11),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    'TSh 900,000',
                    style: GoogleFonts.poppins(
                      fontSize: 15,
                      fontWeight: FontWeight.w800,
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
      {'label': 'Weka Nyumba', 'icon': Icons.add_business, 'color': const Color(0xff10b981)},
      {'label': 'Wapangaji', 'icon': Icons.people, 'color': const Color(0xff6366f1)},
      {'label': 'Mapato', 'icon': Icons.account_balance_wallet, 'color': const Color(0xfff59e0b)},
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
                      style: GoogleFonts.poppins(
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
    final keys = ['total_properties', 'active_rentals', 'vacant_properties', 'total_revenue', 'pending_payments', 'maintenance_requests'];
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
      case 'total_properties': return 'Nyumba Zangu';
      case 'active_rentals': return 'Zilizopangwa';
      case 'vacant_properties': return 'Zilizowazi';
      case 'total_revenue': return 'Mapato ya Mwezi';
      case 'pending_payments': return 'Kodi Inayosubiri';
      case 'maintenance_requests': return 'Maombi ya Mafundi';
      default: return key.replaceAll('_', ' ');
    }
  }

  IconData _statIcon(String key) {
    switch (key) {
      case 'total_properties': return Icons.home_work_rounded;
      case 'active_rentals': return Icons.people_alt_rounded;
      case 'vacant_properties': return Icons.door_front_door_rounded;
      case 'total_revenue': return Icons.account_balance_wallet_rounded;
      case 'pending_payments': return Icons.hourglass_top_rounded;
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
