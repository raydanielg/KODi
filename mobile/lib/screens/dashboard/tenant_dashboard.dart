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

  @override
  void initState() {
    super.initState();
    _loadDashboard();
  }

  Future<void> _loadDashboard() async {
    setState(() {
      _isLoading = true;
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
            {'title': 'Malipo ya kodi ya pango (Palm Heights)', 'time': '12:33 PM • Kodi', 'amount': '-TSh 450,000', 'isCredit': false, 'category': 'rent'},
            {'title': 'Mrejesho wa Dhamana (Deposit Refund)', 'time': '08:56 AM • Mrejesho', 'amount': '+TSh 150,000', 'isCredit': true, 'category': 'refund'},
            {'title': 'Malipo ya Usafi na Ulinzi (Service Charge)', 'time': 'Jana • Huduma', 'amount': '-TSh 20,000', 'isCredit': false, 'category': 'service'},
          ],
        );
        _isLoading = false;
      });
    }
  }

  String _getGreeting() {
    final hour = DateTime.now().hour;
    if (hour < 12) {
      return 'Good Morning,';
    } else if (hour < 17) {
      return 'Good Afternoon,';
    } else {
      return 'Good Evening,';
    }
  }

  @override
  Widget build(BuildContext context) {
    final user = _authService.currentUser!;

    return Scaffold(
      drawer: RoleDrawer(authService: _authService),
      backgroundColor: const Color(0xFF0D0F12), // Premium Deep Dark Slate
      body: _isLoading
          ? const Center(
              child: CircularProgressIndicator(color: Color(0xFFFE5D37)),
            )
          : SafeArea(
              bottom: false,
              child: RefreshIndicator(
                onRefresh: _loadDashboard,
                color: const Color(0xFFFE5D37),
                child: Column(
                  children: [
                    // 1. Beautiful Premium Header (Fintech Style)
                    _buildTopHeader(user),
                    
                    // 2. Main scrollable area (Top Dark / Bottom White Sheet)
                    Expanded(
                      child: ListView(
                        physics: const BouncingScrollPhysics(),
                        padding: EdgeInsets.zero,
                        children: [
                          const SizedBox(height: 24),
                          
                          // Your Balance / Rent Due
                          _buildHeroBalanceSection(),
                          const SizedBox(height: 24),
                          
                          // Quick Actions Row (Send / Request / Menu)
                          _buildQuickActionsRow(context),
                          const SizedBox(height: 32),
                          
                          // Scrollable Contacts (Quick Send / Contacts)
                          _buildQuickSendSection(context),
                          const SizedBox(height: 32),
                          
                          // White Rounded Bottom Sheet transition
                          _buildWhiteBottomSheet(context),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
            ),
    );
  }

  Widget _buildTopHeader(UserModel user) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
      child: Row(
        children: [
          // 1. Premium Avatar with Unsplash photo and gold border
          Builder(
            builder: (context) => GestureDetector(
              onTap: () {
                Scaffold.of(context).openDrawer();
              },
              child: Container(
                padding: const EdgeInsets.all(2),
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  border: Border.all(color: const Color(0xFFFE5D37), width: 1.5),
                ),
                child: const CircleAvatar(
                  radius: 20,
                  backgroundImage: NetworkImage(
                    'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&h=100&fit=crop',
                  ),
                ),
              ),
            ),
          ),
          const SizedBox(width: 12),
          
          // 2. Greeting Column
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisSize: MainAxisSize.min,
              children: [
                Text(
                  _getGreeting(),
                  style: GoogleFonts.inter(
                    fontSize: 12,
                    color: const Color(0xFF8E929B),
                    fontWeight: FontWeight.w500,
                  ),
                ),
                const SizedBox(height: 2),
                Text(
                  user.name,
                  style: GoogleFonts.inter(
                    fontSize: 16,
                    fontWeight: FontWeight.w800,
                    color: Colors.white,
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
              ],
            ),
          ),
          
          // 3. Right Notification Icon with custom Badge
          Stack(
            children: [
              IconButton(
                onPressed: () {
                  Helpers.showSnackBar(context, 'Arifa (Notifications) zitafunguka hapa!');
                },
                icon: const Icon(Icons.notifications_none_rounded, color: Colors.white, size: 26),
                style: IconButton.styleFrom(
                  backgroundColor: const Color(0xFF181A1F),
                  padding: const EdgeInsets.all(10),
                  shape: const CircleBorder(),
                ),
              ),
              Positioned(
                top: 4,
                right: 4,
                child: Container(
                  padding: const EdgeInsets.all(4),
                  decoration: const BoxDecoration(
                    color: Color(0xFFFE5D37),
                    shape: BoxShape.circle,
                  ),
                  child: Text(
                    '3',
                    style: GoogleFonts.inter(
                      color: Colors.white,
                      fontSize: 8,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildHeroBalanceSection() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 20),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            'Kodi ya Mwezi Huu (Monthly Rent)',
            style: GoogleFonts.inter(
              fontSize: 13,
              color: const Color(0xFF8E929B),
              fontWeight: FontWeight.w600,
            ),
          ),
          const SizedBox(height: 8),
          Row(
            crossAxisAlignment: CrossAxisAlignment.baseline,
            textBaseline: TextBaseline.alphabetic,
            children: [
              Text(
                'TSh 450,000',
                style: GoogleFonts.spaceGrotesk(
                  fontSize: 34,
                  fontWeight: FontWeight.w700,
                  color: Colors.white,
                  letterSpacing: -1,
                ),
              ),
              const SizedBox(width: 8),
              Text(
                'Apt A4',
                style: GoogleFonts.inter(
                  fontSize: 14,
                  color: const Color(0xFFFE5D37),
                  fontWeight: FontWeight.w600,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildQuickActionsRow(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 20),
      child: Row(
        children: [
          // Action 1: Lipa Sasa (Send/Coral Background)
          Expanded(
            child: ElevatedButton.icon(
              onPressed: () {
                Helpers.showSnackBar(context, 'Malipo ya kodi yanashughulikiwa...');
              },
              icon: const Icon(Icons.arrow_outward_rounded, size: 18),
              label: Text(
                'Lipa Kodi',
                style: GoogleFonts.inter(fontWeight: FontWeight.w700, fontSize: 14),
              ),
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFFFE5D37),
                foregroundColor: Colors.white,
                padding: const EdgeInsets.symmetric(vertical: 16),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(24),
                ),
                elevation: 0,
              ),
            ),
          ),
          const SizedBox(width: 12),
          
          // Action 2: Omba Fundi (Request / Dark Background)
          Expanded(
            child: ElevatedButton.icon(
              onPressed: () {
                Helpers.showSnackBar(context, 'Fungua tiketi ya mafundi...');
              },
              icon: const Icon(Icons.build_rounded, size: 18),
              label: Text(
                'Omba Fundi',
                style: GoogleFonts.inter(fontWeight: FontWeight.w700, fontSize: 14),
              ),
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFF181A1F),
                foregroundColor: Colors.white,
                padding: const EdgeInsets.symmetric(vertical: 16),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(24),
                  side: const BorderSide(color: Color(0xFF262930), width: 1),
                ),
                elevation: 0,
              ),
            ),
          ),
          const SizedBox(width: 12),
          
          // Action 3: Menu Grid Icon
          IconButton(
            onPressed: () {
              Helpers.showSnackBar(context, 'Huduma zaidi zinafunguka...');
            },
            icon: const Icon(Icons.grid_view_rounded, color: Colors.white, size: 20),
            style: IconButton.styleFrom(
              backgroundColor: const Color(0xFF181A1F),
              padding: const EdgeInsets.all(16),
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(24),
                side: const BorderSide(color: Color(0xFF262930), width: 1),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildQuickSendSection(BuildContext context) {
    final contacts = [
      {'name': 'Mama Ken', 'role': 'Landlord', 'avatar': 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=80&h=80&fit=crop'},
      {'name': 'Salim', 'role': 'Wakala', 'avatar': 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=80&h=80&fit=crop'},
      {'name': 'Fundi Juma', 'role': 'Plumber', 'avatar': 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=80&h=80&fit=crop'},
      {'name': 'Sarah', 'role': 'Mhasibu', 'avatar': 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=80&h=80&fit=crop'},
      {'name': 'Hamis', 'role': 'Ulinzi', 'avatar': 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=80&h=80&fit=crop'},
    ];

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 20),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                'Wasiliana Haraka (Quick Chat)',
                style: GoogleFonts.inter(
                  fontSize: 14,
                  fontWeight: FontWeight.w700,
                  color: Colors.white,
                ),
              ),
              const Icon(Icons.search, color: Color(0xFF8E929B), size: 20),
            ],
          ),
        ),
        const SizedBox(height: 14),
        SizedBox(
          height: 90,
          child: ListView.builder(
            scrollDirection: Axis.horizontal,
            physics: const BouncingScrollPhysics(),
            padding: const EdgeInsets.symmetric(horizontal: 16),
            itemCount: contacts.length,
            itemBuilder: (context, index) {
              final item = contacts[index];
              return Container(
                margin: const EdgeInsets.only(right: 18),
                child: Column(
                  children: [
                    CircleAvatar(
                      radius: 26,
                      backgroundImage: NetworkImage(item['avatar']!),
                    ),
                    const SizedBox(height: 6),
                    Text(
                      item['name']!,
                      style: GoogleFonts.inter(
                        fontSize: 11,
                        fontWeight: FontWeight.w600,
                        color: const Color(0xFF8E929B),
                      ),
                    ),
                  ],
                ),
              );
            },
          ),
        ),
      ],
    );
  }

  Widget _buildWhiteBottomSheet(BuildContext context) {
    final recentItems = _stats!.recentItems ?? [];

    return Container(
      width: double.infinity,
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(32)),
      ),
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 28),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Header of Sheet
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                'Shughuli za Hivi Karibuni',
                style: GoogleFonts.inter(
                  fontSize: 18,
                  fontWeight: FontWeight.w800,
                  color: const Color(0xFF0D0F12),
                ),
              ),
              TextButton(
                onPressed: () {
                  Helpers.showSnackBar(context, 'Ripoti nzima ya malipo inafunguka...');
                },
                child: Row(
                  children: [
                    Text(
                      'View All',
                      style: GoogleFonts.inter(
                        fontSize: 13,
                        fontWeight: FontWeight.w700,
                        color: const Color(0xFFFE5D37),
                      ),
                    ),
                    const SizedBox(width: 4),
                    const Icon(Icons.arrow_outward_rounded, size: 14, color: Color(0xFFFE5D37)),
                  ],
                ),
              ),
            ],
          ),
          const SizedBox(height: 16),
          
          // Transaction List Items
          ...recentItems.map((item) => _buildRecentItemWidget(item)),
          
          const SizedBox(height: 24),
        ],
      ),
    );
  }

  Widget _buildRecentItemWidget(dynamic item) {
    final map = item is Map<String, dynamic> ? item : <String, dynamic>{};
    final bool isCredit = map['isCredit'] ?? false;
    final String amount = map['amount'] ?? '-TSh 0';
    final String category = map['category'] ?? 'rent';

    // Premium Category Icon mapping matching Unsplash mockup look
    IconData icon;
    Color iconColor;
    Color iconBg;
    if (category == 'rent') {
      icon = Icons.home_rounded;
      iconBg = const Color(0xFFFFF1F0);
      iconColor = const Color(0xFFFE5D37);
    } else if (category == 'refund') {
      icon = Icons.account_balance_wallet_rounded;
      iconBg = const Color(0xFFE6F4EA);
      iconColor = const Color(0xFF137333);
    } else {
      icon = Icons.electrical_services_rounded;
      iconBg = const Color(0xFFE8F0FE);
      iconColor = const Color(0xFF1A73E8);
    }

    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 12),
      child: Row(
        children: [
          // Clean Square icon with subtle color accents (Premium style)
          Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              color: iconBg,
              borderRadius: BorderRadius.circular(16),
            ),
            child: Icon(icon, color: iconColor, size: 24),
          ),
          const SizedBox(width: 14),
          
          // Title & Time Column
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  map['title'] ?? 'Transaction',
                  style: GoogleFonts.inter(
                    fontSize: 14,
                    fontWeight: FontWeight.w700,
                    color: const Color(0xFF0D0F12),
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
                const SizedBox(height: 4),
                Text(
                  map['time'] ?? 'Today',
                  style: GoogleFonts.inter(
                    fontSize: 11,
                    color: const Color(0xFF8E929B),
                    fontWeight: FontWeight.w500,
                  ),
                ),
              ],
            ),
          ),
          
          // Amount in premium colors
          Text(
            amount,
            style: GoogleFonts.spaceGrotesk(
              fontSize: 15,
              fontWeight: FontWeight.w700,
              color: isCredit ? const Color(0xFF137333) : const Color(0xFF0D0F12),
            ),
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
