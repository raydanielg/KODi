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

class TenantDashboard extends StatefulWidget {
  const TenantDashboard({super.key});

  @override
  State<TenantDashboard> createState() => _TenantDashboardState();
}

class _TenantDashboardState extends State<TenantDashboard> {
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
            'total_properties': 1,
            'active_rentals': 1,
            'total_revenue': 450000.0,
            'pending_payments': 0.0,
            'maintenance_requests': 1,
          },
          recentItems: [
            {'title': 'Malipo ya kodi yamefanikiwa (TSh 450,000)', 'time': 'Masaa 2 yaliyopita'},
            {'title': 'Maombi ya matengenezo ya bomba la jikoni yamepokewa', 'time': 'Jana'},
            {'title': 'Mkataba mpya umesainiwa na Mwenye Nyumba', 'time': 'Siku 3 zilizopita'},
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
      backgroundColor: Colors.white,
      body: Container(
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topCenter,
            end: Alignment.bottomCenter,
            colors: [
              Colors.white,
              Color(0xfff0fdf4), // Soft emerald background overlay
              Color(0xfff8fafc), // Soft slate bottom transition
            ],
            stops: [0.0, 0.4, 1.0],
          ),
        ),
        child: Column(
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
                        padding: const EdgeInsets.symmetric(vertical: 24),
                        children: [
                          // Highlight Credentials Display Card
                          Padding(
                            padding: const EdgeInsets.symmetric(horizontal: 20),
                            child: _buildCredentialsCard(user),
                          ),
                          const SizedBox(height: 24),

                          // Active Rental Highlight Card (Kodi na Mikataba)
                          _buildRentStatusCard(context),
                          const SizedBox(height: 24),

                          // Quick Actions (Scrollable horizontally)
                          _buildQuickActions(context),
                          const SizedBox(height: 28),

                          // Statistics Grid
                          Padding(
                            padding: const EdgeInsets.symmetric(horizontal: 20),
                            child: Text(
                              'Takwimu za Mpangaji (My Statistics)',
                              style: GoogleFonts.inter(
                                fontSize: 16,
                                fontWeight: FontWeight.w700,
                                color: const Color(0xFF1F2937),
                              ),
                            ),
                          ),
                          const SizedBox(height: 12),
                          Padding(
                            padding: const EdgeInsets.symmetric(horizontal: 20),
                            child: _buildStatsGrid(_stats!.stats),
                          ),
                          const SizedBox(height: 28),

                          // Recent Activities (History)
                          if (_stats!.recentItems != null && _stats!.recentItems!.isNotEmpty) ...[
                            Padding(
                              padding: const EdgeInsets.symmetric(horizontal: 20),
                              child: Text(
                                'Shughuli za Hivi Karibuni (History)',
                                style: GoogleFonts.inter(
                                  fontSize: 16,
                                  fontWeight: FontWeight.w700,
                                  color: const Color(0xFF1F2937),
                                ),
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
                  style: GoogleFonts.inter(
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
                  style: GoogleFonts.inter(
                    fontSize: 11,
                    color: Colors.grey[500],
                    fontWeight: FontWeight.w600,
                  ),
                ),
                const SizedBox(height: 2),
                Text(
                  user.name,
                  style: GoogleFonts.inter(
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
      margin: const EdgeInsets.symmetric(horizontal: 20),
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: const Color(0xffe2e8f0)),
        boxShadow: [
          BoxShadow(
            color: const Color(0xff10b981).withOpacity(0.04),
            blurRadius: 16,
            offset: const Offset(0, 8),
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
                      color: const Color(0xff10b981).withOpacity(0.1),
                      shape: BoxShape.circle,
                    ),
                    child: const Icon(Icons.gite_rounded, color: Color(0xff10b981), size: 22),
                  ),
                  const SizedBox(width: 12),
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        'Palm Heights - Apt A4',
                        style: GoogleFonts.inter(
                          fontSize: 15,
                          fontWeight: FontWeight.w800,
                          color: const Color(0xff1f2937),
                        ),
                      ),
                      Text(
                        'Mkataba ulio hai (Active Lease)',
                        style: GoogleFonts.inter(
                          fontSize: 10,
                          color: Colors.grey[500],
                          fontWeight: FontWeight.w500,
                        ),
                      ),
                    ],
                  ),
                ],
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
                    fontSize: 9,
                    fontWeight: FontWeight.w700,
                    letterSpacing: 0.5,
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 20),
          const Divider(height: 1, color: Color(0xfff1f5f9)),
          const SizedBox(height: 16),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              _buildLeaseDetailItem(
                label: 'Kodi ya Mwezi huu',
                value: 'TSh 450,000',
                valueColor: const Color(0xff1f2937),
              ),
              _buildLeaseDetailItem(
                label: 'Siku Zilizobaki',
                value: 'Siku 23 zimebaki',
                valueColor: const Color(0xffef4444),
              ),
            ],
          ),
          const SizedBox(height: 16),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
            decoration: BoxDecoration(
              color: const Color(0xfff8fafc),
              borderRadius: BorderRadius.circular(12),
            ),
            child: Row(
              children: [
                const Icon(Icons.info_outline_rounded, color: Color(0xff64748b), size: 16),
                const SizedBox(width: 8),
                Expanded(
                  child: Text(
                    'Kodi inayofuata inapaswa kulipwa kabla ya tarehe 30 June 2026.',
                    style: GoogleFonts.inter(
                      fontSize: 10,
                      color: const Color(0xff64748b),
                      fontWeight: FontWeight.w500,
                    ),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildLeaseDetailItem({required String label, required String value, required Color valueColor}) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: GoogleFonts.inter(fontSize: 11, color: Colors.grey[500], fontWeight: FontWeight.w500),
        ),
        const SizedBox(height: 4),
        Text(
          value,
          style: GoogleFonts.inter(
            fontSize: 15,
            fontWeight: FontWeight.w800,
            color: valueColor,
          ),
        ),
      ],
    );
  }

  Widget _buildQuickActions(BuildContext context) {
    final actions = [
      {'label': 'Lipa Kodi', 'icon': Icons.payment, 'color': const Color(0xff10b981), 'subtitle': 'Malipo ya haraka'},
      {'label': 'Omba Fundi', 'icon': Icons.build_rounded, 'color': const Color(0xff6366f1), 'subtitle': 'Matengenezo'},
      {'label': 'Wasiliana', 'icon': Icons.chat_bubble_rounded, 'color': const Color(0xfff59e0b), 'subtitle': 'Chat na Landlord'},
      {'label': 'Mikataba', 'icon': Icons.description_rounded, 'color': const Color(0xff3b82f6), 'subtitle': 'Nyaraka zangu'},
    ];

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 20),
          child: Text(
            'Njia za Mkato (Quick Actions)',
            style: GoogleFonts.inter(
              fontSize: 16,
              fontWeight: FontWeight.w700,
              color: const Color(0xFF1F2937),
            ),
          ),
        ),
        const SizedBox(height: 12),
        SizedBox(
          height: 100,
          child: ListView.builder(
            scrollDirection: Axis.horizontal,
            physics: const BouncingScrollPhysics(),
            padding: const EdgeInsets.symmetric(horizontal: 16),
            itemCount: actions.length,
            itemBuilder: (context, index) {
              final act = actions[index];
              return Container(
                width: 140,
                margin: const EdgeInsets.only(right: 12),
                child: InkWell(
                  onTap: () {
                    Helpers.showSnackBar(context, 'Kazi ya "${act['label']}" inakuja hivi karibuni!');
                  },
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 12),
                    decoration: BoxDecoration(
                      color: Colors.white.withOpacity(0.9),
                      borderRadius: BorderRadius.circular(16),
                      border: Border.all(color: const Color(0xffe2e8f0)),
                      boxShadow: [
                        BoxShadow(
                          color: Colors.black.withOpacity(0.02),
                          blurRadius: 6,
                          offset: const Offset(0, 3),
                        ),
                      ],
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Icon(act['icon'] as IconData, color: act['color'] as Color, size: 24),
                        const Spacer(),
                        Text(
                          act['label'] as String,
                          style: GoogleFonts.inter(
                            fontSize: 12,
                            fontWeight: FontWeight.w700,
                            color: const Color(0xff1f2937),
                          ),
                        ),
                        const SizedBox(height: 2),
                        Text(
                          act['subtitle'] as String,
                          style: GoogleFonts.inter(
                            fontSize: 9,
                            fontWeight: FontWeight.w500,
                            color: Colors.grey[500],
                          ),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),
                      ],
                    ),
                  ),
                ),
              );
            },
          ),
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
    final isSuccess = map['title']?.toString().toLowerCase().contains('fanikiwa') ?? false;

    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 20, vertical: 4),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xfff1f5f9)),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.015),
            blurRadius: 8,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(
              color: isSuccess ? const Color(0xffecfdf5) : const Color(0xffeff6ff),
              borderRadius: BorderRadius.circular(12),
            ),
            child: Icon(
              isSuccess ? Icons.check_circle_outline_rounded : Icons.build_circle_outlined,
              color: isSuccess ? const Color(0xff10b981) : const Color(0xff3b82f6),
              size: 20,
            ),
          ),
          const SizedBox(width: 14),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  map['title'] ?? 'Activity',
                  style: GoogleFonts.inter(
                    fontSize: 13,
                    fontWeight: FontWeight.w600,
                    color: const Color(0xFF1F2937),
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  map['time'] ?? 'Hivi sasa',
                  style: GoogleFonts.inter(
                    fontSize: 10,
                    color: Colors.grey[400],
                    fontWeight: FontWeight.w500,
                  ),
                ),
              ],
            ),
          ),
          const Icon(Icons.arrow_forward_ios_rounded, color: Color(0xffcbd5e1), size: 12),
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
