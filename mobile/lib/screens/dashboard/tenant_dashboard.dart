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
  final TextEditingController _phoneInputController = TextEditingController();

  DashboardStatsModel? _stats;
  bool _isLoading = true;
  int _currentTab = 0;
  bool _isEnglish = false; // Language Toggler State (Default Swahili)
  
  // Lease Connection Flow States
  bool _isLeaseConnected = false; 
  bool _hasIncomingRequest = true; 
  bool _hasPendingSentRequest = false;
  String _sentRequestPhone = '';

  String _t(String sw, String en) {
    return _isEnglish ? en : sw;
  }

  @override
  void dispose() {
    _phoneInputController.dispose();
    super.dispose();
  }

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
      backgroundColor: const Color(0xFFF8FAFC), // Premium Light Slate (Off-white)
      body: _isLoading
          ? const Center(
              child: CircularProgressIndicator(color: Color(0xFFFE5D37)),
            )
          : SafeArea(
              bottom: false,
              child: Column(
                children: [
                  // 1. Beautiful Premium White Header (Hidden in Profile tab)
                  if (_currentTab != 3) _buildTopHeader(user),
                  
                  // 2. Dynamic body content based on selected tab
                  Expanded(
                    child: _buildBodyContent(user),
                  ),
                ],
              ),
            ),
      bottomNavigationBar: _buildBottomNavigationBar(),
    );
  }

  Widget _buildTopHeader(UserModel user) {
    return Container(
      color: Colors.white,
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 14),
      child: Row(
        children: [
          // 1. Premium Avatar with Unsplash photo and gold border
          GestureDetector(
            onTap: () {
              setState(() {
                _currentTab = 3; // Switch directly to the Profile/Mimi tab
              });
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
                    color: const Color(0xFF64748B),
                    fontWeight: FontWeight.w500,
                  ),
                ),
                const SizedBox(height: 2),
                Text(
                  user.name,
                  style: GoogleFonts.inter(
                    fontSize: 16,
                    fontWeight: FontWeight.w800,
                    color: const Color(0xFF1E293B),
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
                icon: const Icon(Icons.notifications_none_rounded, color: Color(0xFF1E293B), size: 26),
                style: IconButton.styleFrom(
                  backgroundColor: const Color(0xFFF1F5F9),
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
              color: const Color(0xFF64748B),
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
                  color: const Color(0xFF1E293B),
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
                  borderRadius: BorderRadius.circular(12),
                ),
                elevation: 0,
              ),
            ),
          ),
          const SizedBox(width: 12),
          
          // Action 2: Omba Fundi (Request / Light Background)
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
                backgroundColor: Colors.white,
                foregroundColor: const Color(0xFF1E293B),
                padding: const EdgeInsets.symmetric(vertical: 16),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(12),
                  side: const BorderSide(color: Color(0xFFE2E8F0), width: 1),
                ),
                elevation: 0,
              ),
            ),
          ),
          const SizedBox(width: 12),
          
          // Action 3: Menu Grid Icon (Huduma Zaidi Sliding Panel)
          IconButton(
            onPressed: () {
              _showMoreServicesPanel(context);
            },
            icon: const Icon(Icons.grid_view_rounded, color: Color(0xFF1E293B), size: 20),
            style: IconButton.styleFrom(
              backgroundColor: Colors.white,
              padding: const EdgeInsets.all(16),
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(12),
                side: const BorderSide(color: Color(0xFFE2E8F0), width: 1),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildQuickServicesSection(BuildContext context) {
    final services = [
      {
        'name': 'Umeme',
        'desc': 'Lipa Token',
        'icon': Icons.bolt_rounded,
        'color': const Color(0xFFF59E0B),
      },
      {
        'name': 'Maji',
        'desc': 'Bili ya Maji',
        'icon': Icons.water_drop_rounded,
        'color': const Color(0xFF3B82F6),
      },
      {
        'name': 'Usafi',
        'desc': 'Garbage',
        'icon': Icons.delete_outline_rounded,
        'color': const Color(0xFF10B981),
      },
      {
        'name': 'Ulinzi',
        'desc': 'Ulinzi Gate',
        'icon': Icons.shield_outlined,
        'color': const Color(0xFFEF4444),
      },
      {
        'name': 'Stakabadhi',
        'desc': 'Risiti Zote',
        'icon': Icons.receipt_long_rounded,
        'color': const Color(0xFF6366F1),
      },
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
                'Huduma za Haraka (Tenant Services)',
                style: GoogleFonts.inter(
                  fontSize: 14,
                  fontWeight: FontWeight.w800,
                  color: const Color(0xFF1E293B),
                ),
              ),
              Text(
                'Angalia Zote',
                style: GoogleFonts.inter(
                  fontSize: 12,
                  fontWeight: FontWeight.w700,
                  color: const Color(0xFFFE5D37),
                ),
              ),
            ],
          ),
        ),
        const SizedBox(height: 14),
        SizedBox(
          height: 100,
          child: ListView.builder(
            scrollDirection: Axis.horizontal,
            physics: const BouncingScrollPhysics(),
            padding: const EdgeInsets.symmetric(horizontal: 16),
            itemCount: services.length,
            itemBuilder: (context, index) {
              final item = services[index];
              final brandColor = item['color'] as Color;

              return Container(
                width: 105,
                margin: const EdgeInsets.only(right: 12),
                child: InkWell(
                  onTap: () {
                    Helpers.showSnackBar(context, 'Malipo ya huduma ya ${item['name']} yanashughulikiwa...');
                  },
                  borderRadius: BorderRadius.circular(16),
                  child: Container(
                    padding: const EdgeInsets.all(12),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(16),
                      border: Border.all(
                        color: const Color(0xFFE2E8F0), // Crisp, high-contrast subtle border
                        width: 1,
                      ),
                      boxShadow: [
                        BoxShadow(
                          color: Colors.black.withOpacity(0.02),
                          blurRadius: 10,
                          offset: const Offset(0, 4),
                        ),
                      ],
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        // Highly premium squircle container for icon (Apple/Stripe style)
                        Container(
                          padding: const EdgeInsets.all(8),
                          decoration: BoxDecoration(
                            color: brandColor.withOpacity(0.08), // Pastel accent background
                            borderRadius: BorderRadius.circular(12), // Squircle shape
                            border: Border.all(
                              color: brandColor.withOpacity(0.16),
                              width: 1,
                            ),
                          ),
                          child: Icon(
                            item['icon'] as IconData,
                            color: brandColor,
                            size: 18,
                          ),
                        ),
                        const Spacer(),
                        // Name and short description
                        Text(
                          item['name'] as String,
                          style: GoogleFonts.inter(
                            fontSize: 12,
                            fontWeight: FontWeight.w800,
                            color: const Color(0xFF1E293B),
                          ),
                        ),
                        const SizedBox(height: 1),
                        Text(
                          item['desc'] as String,
                          style: GoogleFonts.inter(
                            fontSize: 9,
                            fontWeight: FontWeight.w600,
                            color: const Color(0xFF64748B),
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

  Widget _buildWhiteBottomSheet(BuildContext context) {
    final recentItems = _stats!.recentItems ?? [];

    return Container(
      width: double.infinity,
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: const BorderRadius.vertical(top: Radius.circular(16)),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.04),
            blurRadius: 16,
            offset: const Offset(0, -6),
          ),
        ],
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
                'Shughuli za Karibuni',
                style: GoogleFonts.inter(
                  fontSize: 16,
                  fontWeight: FontWeight.w800,
                  color: const Color(0xFF1E293B),
                ),
              ),
              TextButton(
                onPressed: () {
                  Helpers.showSnackBar(context, 'Ripoti nzima ya malipo inafunguka...');
                },
                child: Row(
                  children: [
                    Text(
                      'Ona Zote',
                      style: GoogleFonts.inter(
                        fontSize: 12,
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
          const SizedBox(height: 12),
          
          // Transaction List Items (Extremely Premium Look)
          if (recentItems.isEmpty)
            Padding(
              padding: const EdgeInsets.symmetric(vertical: 20),
              child: Center(
                child: Text(
                  'Hakuna malipo ya karibuni.',
                  style: GoogleFonts.inter(fontSize: 13, color: const Color(0xFF64748B)),
                ),
              ),
            )
          else
            ...recentItems.map((item) => _buildRecentItemWidget(item)),
          
          const SizedBox(height: 32),

          // 📢 Landlord Alerts & Notifications Section (NEW!)
          _buildLandlordAlertsSection(),
        ],
      ),
    );
  }

  Widget _buildRecentItemWidget(dynamic item) {
    final map = item is Map<String, dynamic> ? item : <String, dynamic>{};
    final bool isCredit = map['isCredit'] ?? false;
    final String amount = map['amount'] ?? '-TSh 0';
    final String category = map['category'] ?? 'rent';

    // Premium Category Icon mapping with high contrast
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

    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: const Color(0xFFF8FAFC), // Off-white modern card background
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: const Color(0xFFF1F5F9), width: 1),
      ),
      child: Row(
        children: [
          // Elegant Squircle Icon Background
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(
              color: iconBg,
              borderRadius: BorderRadius.circular(10),
            ),
            child: Icon(icon, color: iconColor, size: 22),
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
                    fontSize: 13,
                    fontWeight: FontWeight.w800,
                    color: const Color(0xFF1E293B),
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
                const SizedBox(height: 3),
                Row(
                  children: [
                    // Faint active success dot indicator
                    Container(
                      width: 6,
                      height: 6,
                      decoration: BoxDecoration(
                        color: isCredit ? const Color(0xFF10B981) : const Color(0xFF64748B),
                        shape: BoxShape.circle,
                      ),
                    ),
                    const SizedBox(width: 6),
                    Text(
                      map['time'] ?? 'Today',
                      style: GoogleFonts.inter(
                        fontSize: 10,
                        color: const Color(0xFF64748B),
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
          
          // Amount in premium bold formats
          Column(
            crossAxisAlignment: CrossAxisAlignment.end,
            children: [
              Text(
                amount,
                style: GoogleFonts.spaceGrotesk(
                  fontSize: 14,
                  fontWeight: FontWeight.w700,
                  color: isCredit ? const Color(0xFF137333) : const Color(0xFF1E293B),
                ),
              ),
              const SizedBox(height: 3),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                decoration: BoxDecoration(
                  color: isCredit ? const Color(0xFFE6F4EA) : const Color(0xFFF1F5F9),
                  borderRadius: BorderRadius.circular(6),
                ),
                child: Text(
                  isCredit ? 'IMEFANIKIWA' : 'KUTOKA',
                  style: GoogleFonts.inter(
                    fontSize: 8,
                    fontWeight: FontWeight.w800,
                    color: isCredit ? const Color(0xFF137333) : const Color(0xFF64748B),
                  ),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildLandlordAlertsSection() {
    final alerts = [
      {
        'title': 'Ukarabati wa Pampu ya Maji',
        'desc': 'Pampu kuu ya maji itafungwa kesho kuanzia saa 3 asubuhi hadi saa 6 mchana kwa matengenezo.',
        'time': 'Leo, 11:30 AM',
        'priority': 'URGENT',
        'color': const Color(0xFFEF4444),
        'bg': const Color(0xFFFEE2E2),
        'icon': Icons.water_damage_rounded,
      },
      {
        'title': 'Kupulizia Dawa ya Wadudu (Fumigation)',
        'desc': 'Zoezi la kupulizia dawa litafanyika Jumamosi asubuhi. Tafadhali funga madirisha na kutoa mifugo.',
        'time': 'Juzi, 2:15 PM',
        'priority': 'TAARIFA',
        'color': const Color(0xFF3B82F6),
        'bg': const Color(0xFFDBEAFE),
        'icon': Icons.bug_report_rounded,
      },
    ];

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        // Section Header
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(
              'Arifa za Mwenye Nyumba (Landlord Alerts)',
              style: GoogleFonts.inter(
                fontSize: 16,
                fontWeight: FontWeight.w800,
                color: const Color(0xFF1E293B),
              ),
            ),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
              decoration: BoxDecoration(
                color: const Color(0xFFFEE2E2),
                borderRadius: BorderRadius.circular(10),
              ),
              child: Row(
                children: [
                  const Icon(Icons.campaign_rounded, color: Color(0xFFEF4444), size: 12),
                  const SizedBox(width: 4),
                  Text(
                    '2 NEW',
                    style: GoogleFonts.inter(
                      fontSize: 8,
                      fontWeight: FontWeight.w900,
                      color: const Color(0xFFEF4444),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
        const SizedBox(height: 16),

        // List of Landlord Alert Cards
        ...alerts.map((alert) {
          final color = alert['color'] as Color;
          final bg = alert['bg'] as Color;

          return Container(
            margin: const EdgeInsets.only(bottom: 12),
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(12),
              border: Border.all(
                color: const Color(0xFFE2E8F0), // Standard crisp gray border
                width: 1,
              ),
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withOpacity(0.01),
                  blurRadius: 8,
                  offset: const Offset(0, 2),
                ),
              ],
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  children: [
                    // Indicator tag
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                      decoration: BoxDecoration(
                        color: bg,
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: Text(
                        alert['priority'] as String,
                        style: GoogleFonts.inter(
                          fontSize: 8,
                          fontWeight: FontWeight.w900,
                          color: color,
                          letterSpacing: 0.5,
                        ),
                      ),
                    ),
                    const Spacer(),
                    Text(
                      alert['time'] as String,
                      style: GoogleFonts.inter(
                        fontSize: 10,
                        color: const Color(0xFF94A3B8),
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 10),
                Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Container(
                      padding: const EdgeInsets.all(8),
                      decoration: BoxDecoration(
                        color: color.withOpacity(0.08),
                        shape: BoxShape.circle,
                      ),
                      child: Icon(alert['icon'] as IconData, color: color, size: 18),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            alert['title'] as String,
                            style: GoogleFonts.inter(
                              fontSize: 13,
                              fontWeight: FontWeight.w800,
                              color: const Color(0xFF1E293B),
                            ),
                          ),
                          const SizedBox(height: 4),
                          Text(
                            alert['desc'] as String,
                            style: GoogleFonts.inter(
                              fontSize: 11,
                              color: const Color(0xFF64748B),
                              fontWeight: FontWeight.w500,
                              height: 1.4,
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ],
            ),
          );
        }).toList(),
      ],
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

  Widget _buildBodyContent(UserModel user) {
    switch (_currentTab) {
      case 0:
        return _buildHomeTabContent();
      case 1:
        return _buildRequestsTabContent(); // Custom requests connection router
      case 2:
        return _buildPaymentsTabContent();
      case 3:
        return _buildProfileTabContent(user);
      default:
        return _buildHomeTabContent();
    }
  }

  Widget _buildBottomNavigationBar() {
    final items = [
      {'icon': Icons.home_rounded, 'label': _t('Nyumbani', 'Home')},
      {'icon': Icons.swap_horiz_rounded, 'label': _t('Maombi', 'Requests')},
      {'icon': Icons.receipt_long_rounded, 'label': _t('Malipo', 'Payments')},
      {'icon': Icons.person_rounded, 'label': _t('Wasifu', 'Profile')},
    ];

    return Container(
      padding: const EdgeInsets.symmetric(vertical: 8, horizontal: 16),
      decoration: BoxDecoration(
        color: const Color(0xFF0D0F12), // Sleek Dark Floating Bar from Mockup
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.18),
            blurRadius: 16,
            offset: const Offset(0, 6),
          ),
        ],
      ),
      margin: const EdgeInsets.only(left: 20, right: 20, bottom: 20),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: List.generate(items.length, (index) {
          final isSelected = _currentTab == index;
          return GestureDetector(
            onTap: () {
              setState(() {
                _currentTab = index;
              });
            },
            child: AnimatedContainer(
              duration: const Duration(milliseconds: 250),
              curve: Curves.easeInOut,
              padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
              decoration: BoxDecoration(
                color: isSelected ? const Color(0xFFFE5D37) : Colors.transparent, // sliding capsule
                borderRadius: BorderRadius.circular(12),
              ),
              child: Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Icon(
                    items[index]['icon'] as IconData,
                    color: isSelected ? Colors.white : const Color(0xFF8E929B),
                    size: 20,
                  ),
                  if (isSelected) ...[
                    const SizedBox(width: 8),
                    Text(
                      items[index]['label'] as String,
                      style: GoogleFonts.inter(
                        color: Colors.white,
                        fontSize: 11,
                        fontWeight: FontWeight.w700,
                      ),
                    ),
                  ],
                ],
              ),
            ),
          );
        }),
      ),
    );
  }

  Widget _buildHomeTabContent() {
    if (!_isLeaseConnected) {
      return _buildUnconnectedPlaceholder();
    }

    return RefreshIndicator(
      onRefresh: _loadDashboard,
      color: const Color(0xFFFE5D37),
      child: ListView(
        physics: const BouncingScrollPhysics(),
        padding: const EdgeInsets.symmetric(vertical: 24),
        children: [
          // Your Balance / Rent Due
          _buildHeroBalanceSection(),
          const SizedBox(height: 24),

          // Quick Actions
          _buildQuickActionsRow(context),
          const SizedBox(height: 32),

          // Scrollable Services
          _buildQuickServicesSection(context),
          const SizedBox(height: 32),

          // Recent Activity Sheet
          _buildWhiteBottomSheet(context),
        ],
      ),
    );
  }

  Widget _buildUnconnectedPlaceholder() {
    return Center(
      child: SingleChildScrollView(
        physics: const BouncingScrollPhysics(),
        padding: const EdgeInsets.all(24),
        child: Container(
          padding: const EdgeInsets.all(24),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(16),
            border: Border.all(color: const Color(0xFFE2E8F0)),
            boxShadow: [
              BoxShadow(
                color: Colors.black.withOpacity(0.01),
                blurRadius: 10,
                offset: const Offset(0, 4),
              ),
            ],
          ),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: const Color(0xFFFFF1F0),
                  shape: BoxShape.circle,
                ),
                child: const Icon(
                  Icons.link_off_rounded,
                  color: Color(0xFFFE5D37),
                  size: 32,
                ),
              ),
              const SizedBox(height: 20),
              Text(
                _t('Mkataba Haujaunganishwa', 'Lease Not Connected'),
                style: GoogleFonts.inter(
                  fontSize: 16,
                  fontWeight: FontWeight.w800,
                  color: const Color(0xFF1E293B),
                ),
                textAlign: TextAlign.center,
              ),
              const SizedBox(height: 10),
              Text(
                _t(
                  'Ili kuona taarifa za kodi na kulipa, tafadhali unganisha akaunti yako na Mwenye Nyumba wako kwanza.',
                  'To view rent information and pay bills, please connect your account with your Landlord first.'
                ),
                style: GoogleFonts.inter(
                  fontSize: 12,
                  color: const Color(0xFF64748B),
                  fontWeight: FontWeight.w500,
                  height: 1.5,
                ),
                textAlign: TextAlign.center,
              ),
              const SizedBox(height: 24),
              ElevatedButton.icon(
                onPressed: () {
                  setState(() {
                    _currentTab = 1; // Direct jump to Requests Tab!
                  });
                },
                icon: const Icon(Icons.swap_horiz_rounded, size: 16),
                label: Text(
                  _t('Nenda Kwenye Maombi', 'Go to Requests'),
                  style: GoogleFonts.inter(fontWeight: FontWeight.w700),
                ),
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFFFE5D37),
                  foregroundColor: Colors.white,
                  minimumSize: const Size(double.infinity, 48),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                  elevation: 0,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildRequestsTabContent() {
    return ListView(
      physics: const BouncingScrollPhysics(),
      padding: const EdgeInsets.all(20),
      children: [
        Text(
          _t('Maombi ya Mkataba', 'Lease Connection Requests'),
          style: GoogleFonts.inter(
            fontSize: 20,
            fontWeight: FontWeight.w800,
            color: const Color(0xFF1E293B),
          ),
        ),
        const SizedBox(height: 20),

        // Section 1: Incoming Landlord Requests
        _buildRequestsSectionHeader(_t('MAOMBI MAPYA YA KUINGIA', 'INCOMING LEASE REQUESTS')),
        const SizedBox(height: 12),
        if (_hasIncomingRequest)
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: const Color(0xFFE2E8F0)),
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withOpacity(0.01),
                  blurRadius: 8,
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
                      decoration: const BoxDecoration(
                        color: Color(0xFFFFF1F0),
                        shape: BoxShape.circle,
                      ),
                      child: const Icon(Icons.gite_rounded, color: Color(0xFFFE5D37), size: 20),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            _t('Ombi la Mkataba Kutoka kwa Landlord', 'Lease Connection from Landlord'),
                            style: GoogleFonts.inter(fontSize: 10, fontWeight: FontWeight.bold, color: const Color(0xFF64748B)),
                          ),
                          const SizedBox(height: 2),
                          Text(
                            'Mama Ken',
                            style: GoogleFonts.inter(fontSize: 14, fontWeight: FontWeight.w800, color: const Color(0xFF1E293B)),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 14),
                const Divider(color: Color(0xFFF1F5F9), height: 1),
                const SizedBox(height: 14),
                _buildRequestInfoRow(_t('Nyumba aliyopo', 'Lease Property'), 'Palm Heights - Apt A4'),
                _buildRequestInfoRow(_t('Kodi ya Mwezi', 'Monthly Rent'), 'TSh 450,000'),
                const SizedBox(height: 16),
                Row(
                  children: [
                    Expanded(
                      child: OutlinedButton(
                        onPressed: () {
                          setState(() {
                            _hasIncomingRequest = false;
                          });
                          Helpers.showSnackBar(
                            context,
                            _t('Ombi la mwenye nyumba limekataliwa.', 'Landlord request declined.'),
                          );
                        },
                        style: OutlinedButton.styleFrom(
                          foregroundColor: const Color(0xFF64748B),
                          side: const BorderSide(color: Color(0xFFE2E8F0)),
                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                          padding: const EdgeInsets.symmetric(vertical: 12),
                        ),
                        child: Text(_t('Kataa', 'Decline'), style: GoogleFonts.inter(fontWeight: FontWeight.w700, fontSize: 13)),
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: ElevatedButton(
                        onPressed: () {
                          setState(() {
                            _isLeaseConnected = true;
                            _hasIncomingRequest = false;
                          });
                          Helpers.showSnackBar(
                            context,
                            _t(
                              'Hongera! Mkataba umeunganishwa kikamilifu. Sasa unaweza kuona nyumba yako na kulipa kodi.',
                              'Success! Your lease has been connected. You can now access your house stats and pay rent.'
                            ),
                          );
                        },
                        style: ElevatedButton.styleFrom(
                          backgroundColor: const Color(0xFF10B981), // Solid crisp success green
                          foregroundColor: Colors.white,
                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                          padding: const EdgeInsets.symmetric(vertical: 12),
                          elevation: 0,
                        ),
                        child: Text(_t('Kubali', 'Accept'), style: GoogleFonts.inter(fontWeight: FontWeight.w700, fontSize: 13)),
                      ),
                    ),
                  ],
                ),
              ],
            ),
          )
        else
          Padding(
            padding: const EdgeInsets.symmetric(vertical: 12),
            child: Text(
              _t('Hakuna maombi mapya ya kuingia.', 'No new incoming lease connection requests.'),
              style: GoogleFonts.inter(fontSize: 12, color: const Color(0xFF64748B), fontWeight: FontWeight.w500),
            ),
          ),
        
        const SizedBox(height: 28),

        // Section 2: Send request form
        _buildRequestsSectionHeader(_t('TUMA OMBI KWA MWENYE NYUMBA', 'SEND REQUEST TO LANDLORD')),
        const SizedBox(height: 12),
        Container(
          padding: const EdgeInsets.all(16),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(12),
            border: Border.all(color: const Color(0xFFE2E8F0)),
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                _t('Weka namba ya simu ya Mwenye Nyumba kuomba kuunganishwa', 'Enter Landlord phone number to request a lease connection'),
                style: GoogleFonts.inter(fontSize: 11, color: const Color(0xFF64748B), fontWeight: FontWeight.w500, height: 1.4),
              ),
              const SizedBox(height: 12),
              TextField(
                controller: _phoneInputController,
                keyboardType: TextInputType.phone,
                style: GoogleFonts.inter(fontSize: 14, fontWeight: FontWeight.w600, color: const Color(0xFF1E293B)),
                decoration: InputDecoration(
                  hintText: '+255 765 432 109',
                  hintStyle: GoogleFonts.inter(fontSize: 14, color: const Color(0xFF94A3B8)),
                  contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                  filled: true,
                  fillColor: const Color(0xFFF8FAFC),
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(10),
                    borderSide: const BorderSide(color: Color(0xFFE2E8F0)),
                  ),
                  enabledBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(10),
                    borderSide: const BorderSide(color: Color(0xFFE2E8F0)),
                  ),
                  focusedBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(10),
                    borderSide: const BorderSide(color: Color(0xFFFE5D37)),
                  ),
                ),
              ),
              const SizedBox(height: 16),
              ElevatedButton.icon(
                onPressed: () {
                  final phone = _phoneInputController.text.trim();
                  if (phone.isEmpty) {
                    Helpers.showSnackBar(
                      context,
                      _t('Tafadhali ingiza namba ya simu ya Landlord', 'Please enter Landlord phone number first'),
                    );
                    return;
                  }
                  setState(() {
                    _hasPendingSentRequest = true;
                    _sentRequestPhone = phone;
                    _phoneInputController.clear();
                  });
                  Helpers.showSnackBar(
                    context,
                    _t('Ombi limetumwa kwa namba ' + phone, 'Request successfully sent to ' + phone),
                  );
                },
                icon: const Icon(Icons.send_rounded, size: 16),
                label: Text(_t('Tuma Ombi', 'Send Connection Request'), style: GoogleFonts.inter(fontWeight: FontWeight.w700)),
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFFFE5D37),
                  foregroundColor: Colors.white,
                  minimumSize: const Size(double.infinity, 44),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                  elevation: 0,
                ),
              ),
            ],
          ),
        ),

        const SizedBox(height: 28),

        // Section 3: Pending Sent Requests
        _buildRequestsSectionHeader(_t('MAOMBI YALIYOTUMWA', 'SENT REQUESTS HISTORY')),
        const SizedBox(height: 12),
        if (_hasPendingSentRequest)
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: const Color(0xFFE2E8F0)),
            ),
            child: Row(
              children: [
                Container(
                  padding: const EdgeInsets.all(8),
                  decoration: const BoxDecoration(
                    color: Color(0xFFFEF3C7), // soft amber
                    shape: BoxShape.circle,
                  ),
                  child: const Icon(Icons.hourglass_empty_rounded, color: Color(0xFFD97706), size: 18),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        _t('Inasubiri Uthibitisho', 'Awaiting Confirmation'),
                        style: GoogleFonts.inter(fontSize: 12, fontWeight: FontWeight.w800, color: const Color(0xFF1E293B)),
                      ),
                      const SizedBox(height: 2),
                      Text(
                        _t('Mwenye Nyumba: ', 'Landlord Phone: ') + _sentRequestPhone,
                        style: GoogleFonts.inter(fontSize: 10, color: const Color(0xFF64748B), fontWeight: FontWeight.w600),
                      ),
                    ],
                  ),
                ),
                TextButton(
                  onPressed: () {
                    setState(() {
                      _hasPendingSentRequest = false;
                    });
                    Helpers.showSnackBar(
                      context,
                      _t('Ombi la unganisho limefutwa.', 'Connection request canceled.'),
                    );
                  },
                  child: Text(
                    _t('Ghairi', 'Cancel'),
                    style: GoogleFonts.inter(fontSize: 11, fontWeight: FontWeight.bold, color: const Color(0xFFEF4444)),
                  ),
                ),
              ],
            ),
          )
        else
          Padding(
            padding: const EdgeInsets.symmetric(vertical: 12),
            child: Text(
              _t('Hakuna maombi yaliyotumwa kwa sasa.', 'No pending sent connection requests at the moment.'),
              style: GoogleFonts.inter(fontSize: 12, color: const Color(0xFF64748B), fontWeight: FontWeight.w500),
            ),
          ),
      ],
    );
  }

  Widget _buildRequestsSectionHeader(String title) {
    return Text(
      title,
      style: GoogleFonts.inter(
        fontSize: 11,
        fontWeight: FontWeight.w900,
        color: const Color(0xFF94A3B8),
        letterSpacing: 1.0,
      ),
    );
  }

  Widget _buildRequestInfoRow(String label, String val) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: GoogleFonts.inter(fontSize: 12, color: const Color(0xFF64748B), fontWeight: FontWeight.w600)),
          Text(val, style: GoogleFonts.inter(fontSize: 12, color: const Color(0xFF1E293B), fontWeight: FontWeight.w800)),
        ],
      ),
    );
  }

  Widget _buildPaymentsTabContent() {
    if (!_isLeaseConnected) {
      return _buildUnconnectedPlaceholder();
    }

    final invoices = [
      {'title': _t('Kodi ya Pango - June 2026', 'Rent Payment - June 2026'), 'desc': 'Palm Heights • Apt A4', 'amount': 'TSh 450,000', 'status': 'Paid', 'date': '05 Jun 2026'},
      {'title': _t('Kodi ya Pango - May 2026', 'Rent Payment - May 2026'), 'desc': 'Palm Heights • Apt A4', 'amount': 'TSh 450,000', 'status': 'Paid', 'date': '02 May 2026'},
      {'title': _t('Kodi ya Pango - April 2026', 'Rent Payment - April 2026'), 'desc': 'Palm Heights • Apt A4', 'amount': 'TSh 450,000', 'status': 'Paid', 'date': '01 Apr 2026'},
    ];

    return ListView(
      physics: const BouncingScrollPhysics(),
      padding: const EdgeInsets.all(20),
      children: [
        Text(
          _t('Historia ya Malipo (Invoices)', 'Payment History (Invoices)'),
          style: GoogleFonts.inter(
            fontSize: 20,
            fontWeight: FontWeight.w800,
            color: const Color(0xFF1E293B),
          ),
        ),
        const SizedBox(height: 20),
        ...invoices.map((inv) {
          return Container(
            margin: const EdgeInsets.only(bottom: 12),
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: const Color(0xFFE2E8F0)),
            ),
            child: Row(
              children: [
                Container(
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(
                    color: const Color(0xFFE6F4EA),
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: const Icon(Icons.receipt_long_rounded, color: Color(0xFF137333), size: 20),
                ),
                const SizedBox(width: 14),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        inv['title']!,
                        style: GoogleFonts.inter(fontSize: 13, fontWeight: FontWeight.w700, color: const Color(0xFF1E293B)),
                      ),
                      const SizedBox(height: 4),
                      Text(
                        '${inv['date']} • ${inv['desc']}',
                        style: GoogleFonts.inter(fontSize: 11, color: const Color(0xFF64748B), fontWeight: FontWeight.w500),
                      ),
                    ],
                  ),
                ),
                Column(
                  crossAxisAlignment: CrossAxisAlignment.end,
                  children: [
                    Text(
                      inv['amount']!,
                      style: GoogleFonts.spaceGrotesk(fontSize: 14, fontWeight: FontWeight.bold, color: const Color(0xFF1E293B)),
                    ),
                    const SizedBox(height: 4),
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                      decoration: BoxDecoration(
                        color: const Color(0xFFE6F4EA),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: Text(
                        'PAID',
                        style: GoogleFonts.inter(fontSize: 9, fontWeight: FontWeight.bold, color: const Color(0xFF137333)),
                      ),
                    ),
                  ],
                ),
              ],
            ),
          );
        }).toList(),
      ],
    );
  }

  Widget _buildProfileTabContent(UserModel user) {
    return ListView(
      physics: const BouncingScrollPhysics(),
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 24),
      children: [
        // 1. Premium Avatar Layout with Edit Camera Overlay Badge
        Center(
          child: Column(
            children: [
              Stack(
                children: [
                  Container(
                    padding: const EdgeInsets.all(3),
                    decoration: BoxDecoration(
                      shape: BoxShape.circle,
                      border: Border.all(color: const Color(0xFFFE5D37), width: 1.5),
                    ),
                    child: const CircleAvatar(
                      radius: 46,
                      backgroundImage: NetworkImage(
                        'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&h=100&fit=crop',
                      ),
                    ),
                  ),
                  Positioned(
                    bottom: 0,
                    right: 4,
                    child: GestureDetector(
                      onTap: () {
                        Helpers.showSnackBar(
                          context,
                          _t('Tafadhali chagua picha kutoka kwenye maktaba...', 'Please select a photo from your gallery...'),
                        );
                      },
                      child: Container(
                        padding: const EdgeInsets.all(6),
                        decoration: const BoxDecoration(
                          color: Color(0xFFFE5D37),
                          shape: BoxShape.circle,
                        ),
                        child: const Icon(
                          Icons.camera_alt_rounded,
                          color: Colors.white,
                          size: 16,
                        ),
                      ),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 16),
              
              // Name, Phone and Role Display
              Text(
                user.name,
                style: GoogleFonts.inter(
                  fontSize: 18,
                  fontWeight: FontWeight.w800,
                  color: const Color(0xFF1E293B),
                ),
              ),
              const SizedBox(height: 4),
              Text(
                user.phone.isNotEmpty ? user.phone : '+255 712 345 678',
                style: GoogleFonts.inter(
                  fontSize: 13,
                  color: const Color(0xFF64748B),
                  fontWeight: FontWeight.w600,
                ),
              ),
              const SizedBox(height: 8),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
                decoration: BoxDecoration(
                  color: const Color(0xFFF1F5F9),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Text(
                  _t('Mpangaji', 'Tenant'),
                  style: GoogleFonts.inter(
                    fontSize: 11,
                    fontWeight: FontWeight.w800,
                    color: const Color(0xFF64748B),
                  ),
                ),
              ),
            ],
          ),
        ),
        const SizedBox(height: 32),

        // 2. Settings Menu Items
        _buildProfileSettingTile(
          icon: Icons.person_outline_rounded,
          title: _t('Taarifa Binafsi', 'My Personal Info'),
          subtitle: _t('Taarifa zako na za mwenye nyumba', 'Your details & landlord details'),
          onTap: () => _showMyInfoBottomSheet(user),
        ),
        _buildProfileSettingTile(
          icon: Icons.history_edu_rounded,
          title: _t('Mkataba Wangu', 'My Lease Contract'),
          subtitle: _t('Angalia na pakua mkataba wako', 'View and download your contract'),
          onTap: () => _showMyContractBottomSheet(),
        ),
        _buildProfileSettingTile(
          icon: Icons.help_outline_rounded,
          title: _t('Msaada na Maswali', 'Help & Support FAQs'),
          subtitle: _t('Maswali ya mara kwa mara na msaada', 'Frequently asked questions & help'),
          onTap: () => _showSupportBottomSheet(),
        ),
        
        // 3. LANGUAGE SELECTION TILE (Fully Functional!)
        _buildProfileSettingTile(
          icon: Icons.g_translate_rounded,
          title: _t('Lugha / Language', 'Lugha / Language'),
          subtitle: _isEnglish ? 'English' : 'Kiswahili',
          trailing: Switch(
            value: _isEnglish,
            activeColor: const Color(0xFFFE5D37),
            onChanged: (val) {
              setState(() {
                _isEnglish = val;
              });
              Helpers.showSnackBar(
                context,
                _t('Lugha imebadilishwa kuwa Kiswahili', 'Language switched to English'),
              );
            },
          ),
          onTap: () {
            setState(() {
              _isEnglish = !_isEnglish;
            });
            Helpers.showSnackBar(
              context,
              _t('Lugha imebadilishwa kuwa Kiswahili', 'Language switched to English'),
            );
          },
        ),
        
        const SizedBox(height: 24),
        
        // 4. Logout Button (No Emojis, Rounded 12)
        Container(
          decoration: BoxDecoration(
            color: const Color(0xFFFEE2E2),
            borderRadius: BorderRadius.circular(12),
          ),
          child: ListTile(
            onTap: () async {
              final confirm = await Helpers.showConfirmationDialog(
                context,
                title: _t('Kutoka Kwenye App', 'Logout'),
                message: _t('Je, una uhakika unataka kutoka kwenye akaunti yako?', 'Are you sure you want to log out of your account?'),
                confirmText: _t('Kutoka', 'Logout'),
                cancelText: _t('Baki hapa', 'Cancel'),
              );
              if (confirm) {
                await _authService.logout();
                if (mounted) {
                  Navigator.pushNamedAndRemoveUntil(context, '/login', (route) => false);
                }
              }
            },
            leading: const Icon(Icons.logout_rounded, color: Color(0xFFEF4444), size: 20),
            title: Text(
              _t('Kutoka Kwenye Akaunti', 'Logout Account'),
              style: GoogleFonts.inter(
                fontSize: 13,
                fontWeight: FontWeight.w800,
                color: const Color(0xFFEF4444),
              ),
            ),
            trailing: const Icon(Icons.chevron_right_rounded, color: Color(0xFFEF4444)),
          ),
        ),
      ],
    );
  }

  Widget _buildProfileSettingTile({
    required IconData icon,
    required String title,
    required String subtitle,
    required VoidCallback onTap,
    Widget? trailing,
  }) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: const Color(0xFFE2E8F0)),
      ),
      child: ListTile(
        onTap: onTap,
        leading: Container(
          padding: const EdgeInsets.all(8),
          decoration: const BoxDecoration(
            color: Color(0xFFF1F5F9),
            shape: BoxShape.circle,
          ),
          child: Icon(icon, color: const Color(0xFF64748B), size: 20),
        ),
        title: Text(
          title,
          style: GoogleFonts.inter(
            fontSize: 13,
            fontWeight: FontWeight.w800,
            color: const Color(0xFF1E293B),
          ),
        ),
        subtitle: Text(
          subtitle,
          style: GoogleFonts.inter(
            fontSize: 10,
            fontWeight: FontWeight.w500,
            color: const Color(0xFF64748B),
          ),
        ),
        trailing: trailing ?? const Icon(Icons.chevron_right_rounded, color: Color(0xFF94A3B8)),
      ),
    );
  }

  void _showMyInfoBottomSheet(UserModel user) {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      isScrollControlled: true,
      builder: (context) {
        return Container(
          padding: const EdgeInsets.all(24),
          decoration: const BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.vertical(top: Radius.circular(16)),
          ),
          child: SingleChildScrollView(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Center(
                  child: Container(
                    width: 40,
                    height: 5,
                    decoration: BoxDecoration(
                      color: Colors.grey[300],
                      borderRadius: BorderRadius.circular(2.5),
                    ),
                  ),
                ),
                const SizedBox(height: 16),
                Text(
                  _t('Taarifa Binafsi', 'My Personal Info'),
                  style: GoogleFonts.inter(fontSize: 16, fontWeight: FontWeight.w800, color: const Color(0xFF1E293B)),
                ),
                const SizedBox(height: 20),
                
                // Section 1: My Details
                _buildInfoSectionHeader(_t('TAARIFA ZANGU', 'MY DETAILS')),
                const SizedBox(height: 8),
                _buildInfoRow(_t('Jina Kamili', 'Full Name'), user.name),
                _buildInfoRow(_t('Barua Pepe', 'Email Address'), user.email),
                _buildInfoRow(_t('Namba ya Simu', 'Phone Number'), user.phone.isNotEmpty ? user.phone : '+255 712 345 678'),
                _buildInfoRow(_t('Namba ya NIDA', 'National ID (NIDA)'), '19950312-XXXXX-XXXXX-XX'),
                _buildInfoRow(_t('Mwanzo wa Pango', 'Tenant Since'), '01 Jan 2025'),
                
                const SizedBox(height: 24),
                
                // Section 2: Landlord Details
                _buildInfoSectionHeader(_t('TAARIFA ZA MWENYE NYUMBA', 'LANDLORD DETAILS')),
                const SizedBox(height: 8),
                _buildInfoRow(_t('Jina la Landlord', 'Landlord Name'), 'Mama Ken'),
                _buildInfoRow(_t('Namba ya Simu', 'Phone Number'), '+255 765 432 109'),
                _buildInfoRow(_t('Nyumba / Ghorofa', 'Apartment Unit'), 'Palm Heights - Apt A4'),
                _buildInfoRow(_t('Eneo', 'Location'), 'Mikochem, Dar es Salaam'),
                
                const SizedBox(height: 24),
                ElevatedButton(
                  onPressed: () => Navigator.pop(context),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: const Color(0xFFFE5D37),
                    foregroundColor: Colors.white,
                    minimumSize: const Size(double.infinity, 48),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                    elevation: 0,
                  ),
                  child: Text(_t('Funga', 'Close'), style: GoogleFonts.inter(fontWeight: FontWeight.w700)),
                ),
              ],
            ),
          ),
        );
      },
    );
  }

  void _showMyContractBottomSheet() {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      isScrollControlled: true,
      builder: (context) {
        return Container(
          padding: const EdgeInsets.all(24),
          decoration: const BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.vertical(top: Radius.circular(16)),
          ),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Center(
                child: Container(
                  width: 40,
                  height: 5,
                  decoration: BoxDecoration(
                    color: Colors.grey[300],
                    borderRadius: BorderRadius.circular(2.5),
                  ),
                ),
              ),
              const SizedBox(height: 16),
              Text(
                _t('Mkataba Wangu', 'My Lease Contract'),
                style: GoogleFonts.inter(fontSize: 16, fontWeight: FontWeight.w800, color: const Color(0xFF1E293B)),
              ),
              const SizedBox(height: 20),
              
              _buildInfoRow(_t('Namba ya Mkataba', 'Contract ID'), 'MNA-2025-A4'),
              _buildInfoRow(_t('Muda wa Mkataba', 'Lease Period'), '12 ' + _t('Miezi', 'Months') + ' (01 Jan 2025 - 31 Dec 2026)'),
              _buildInfoRow(_t('Kodi ya Mwezi', 'Monthly Rent'), 'TSh 450,000'),
              _buildInfoRow(_t('Dhamana ya Nyumba', 'Security Deposit'), 'TSh 900,000'),
              _buildInfoRow(_t('Hali ya Mkataba', 'Contract Status'), 'ACTIVE'),
              
              const SizedBox(height: 24),
              Row(
                children: [
                  Expanded(
                    child: OutlinedButton.icon(
                      onPressed: () {
                        Navigator.pop(context);
                        _showMockPdfPreviewDialog();
                      },
                      icon: const Icon(Icons.picture_as_pdf_rounded, size: 18),
                      label: Text(_t('Hakiki PDF', 'Preview')),
                      style: OutlinedButton.styleFrom(
                        foregroundColor: const Color(0xFFFE5D37),
                        side: const BorderSide(color: Color(0xFFFE5D37)),
                        padding: const EdgeInsets.symmetric(vertical: 14),
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                      ),
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: ElevatedButton.icon(
                      onPressed: () {
                        Navigator.pop(context);
                        _handlePdfDownload();
                      },
                      icon: const Icon(Icons.file_download_rounded, size: 18),
                      label: Text(_t('Pakua', 'Download')),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xFFFE5D37),
                        foregroundColor: Colors.white,
                        padding: const EdgeInsets.symmetric(vertical: 14),
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                        elevation: 0,
                      ),
                    ),
                  ),
                ],
              ),
            ],
          ),
        );
      },
    );
  }

  void _showMockPdfPreviewDialog() {
    final user = _authService.currentUser!;
    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (context) => FullscreenPdfViewerPage(
          isEnglish: _isEnglish,
          user: user,
        ),
      ),
    );
  }

  void _handlePdfDownload() {
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (context) {
        Future.delayed(const Duration(seconds: 2), () {
          Navigator.pop(context);
          Helpers.showSnackBar(
            context,
            _t('Mkataba umepakuliwa kikamilifu: MNA-2025-A4.pdf', 'Contract downloaded successfully: MNA-2025-A4.pdf'),
          );
        });
        return AlertDialog(
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
          content: Row(
            children: [
              const CircularProgressIndicator(color: Color(0xFFFE5D37)),
              const SizedBox(width: 20),
              Expanded(
                child: Text(
                  _t('Inapakua mkataba kama PDF...', 'Downloading contract as PDF...'),
                  style: GoogleFonts.inter(fontSize: 13, fontWeight: FontWeight.w600),
                ),
              ),
            ],
          ),
        );
      },
    );
  }

  void _showSupportBottomSheet() {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      isScrollControlled: true,
      builder: (context) {
        return Container(
          padding: const EdgeInsets.all(24),
          decoration: const BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.vertical(top: Radius.circular(16)),
          ),
          child: SingleChildScrollView(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Center(
                  child: Container(
                    width: 40,
                    height: 5,
                    decoration: BoxDecoration(
                      color: Colors.grey[300],
                      borderRadius: BorderRadius.circular(2.5),
                    ),
                  ),
                ),
                const SizedBox(height: 16),
                Text(
                  _t('Msaada na Maswali', 'Help & Support FAQs'),
                  style: GoogleFonts.inter(fontSize: 16, fontWeight: FontWeight.w800, color: const Color(0xFF1E293B)),
                ),
                const SizedBox(height: 20),
                
                // FAQs
                _buildInfoSectionHeader(_t('MASWALI YA MARA KWA MARA', 'FREQUENTLY ASKED QUESTIONS')),
                const SizedBox(height: 10),
                _buildFaqItem(_t('Nalipaje kodi ya mwezi?', 'How do I pay monthly rent?'), _t('Bonyeza kitufe cha "Lipa Kodi" kwenye skrini kuu ili kufanya malipo ya kodi kupitia mitandao ya simu au benki.', 'Tap the "Lipa Kodi" button on the home screen to pay your rent via mobile money or bank transfer.')),
                _buildFaqItem(_t('Nikitaka mafundi inakuwaje?', 'How do I request repairs?'), _t('Tuma ombi kupitia kitufe cha "Omba Fundi" au nenda kwenye huduma zaidi kupata fundi husika wa maji, umeme nk.', 'Send a request via the "Omba Fundi" button or go to More Services to request a specific plumber, electrician, etc.')),
                _buildFaqItem(_t('Nitapakua wapi risiti yangu?', 'Where can I download my receipt?'), _t('Nenda kwenye ukurasa wa "Malipo" kupata list ya risiti zako zote na kuzipakua kama PDF.', 'Go to the "Payments" tab to see all your invoices and download them as PDF.')),
                
                const SizedBox(height: 24),
                
                // Emergency Actions
                _buildInfoSectionHeader(_t('MAWASILIANO YA DHARURA', 'EMERGENCY CONTACTS')),
                const SizedBox(height: 10),
                ListTile(
                  onTap: () {
                    Helpers.showSnackBar(context, _t('Inapiga simu kwa Mwenye Nyumba: +255 765 432 109', 'Calling Landlord: +255 765 432 109'));
                  },
                  leading: const Icon(Icons.phone_in_talk_rounded, color: Color(0xFF10B981)),
                  title: Text(_t('Piga simu kwa Landlord', 'Call Landlord'), style: GoogleFonts.inter(fontSize: 13, fontWeight: FontWeight.bold)),
                  trailing: const Icon(Icons.chevron_right_rounded),
                ),
                ListTile(
                  onTap: () {
                    Helpers.showSnackBar(context, _t('Inafungua soga ya WhatsApp na Wakala...', 'Opening WhatsApp chat with Agent...'));
                  },
                  leading: const Icon(Icons.chat_bubble_outline_rounded, color: Color(0xFF3B82F6)),
                  title: Text(_t('Tuma ujumbe kwa Wakala', 'Chat with Agent'), style: GoogleFonts.inter(fontSize: 13, fontWeight: FontWeight.bold)),
                  trailing: const Icon(Icons.chevron_right_rounded),
                ),
                
                const SizedBox(height: 24),
                ElevatedButton(
                  onPressed: () => Navigator.pop(context),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: const Color(0xFFFE5D37),
                    foregroundColor: Colors.white,
                    minimumSize: const Size(double.infinity, 48),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                    elevation: 0,
                  ),
                  child: Text(_t('Funga', 'Close'), style: GoogleFonts.inter(fontWeight: FontWeight.w700)),
                ),
              ],
            ),
          ),
        );
      },
    );
  }

  Widget _buildInfoSectionHeader(String title) {
    return Text(
      title,
      style: GoogleFonts.inter(fontSize: 11, fontWeight: FontWeight.w900, color: const Color(0xFF94A3B8), letterSpacing: 1),
    );
  }

  Widget _buildInfoRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: GoogleFonts.inter(fontSize: 12, color: const Color(0xFF64748B), fontWeight: FontWeight.w600)),
          Text(value, style: GoogleFonts.inter(fontSize: 12, color: const Color(0xFF1E293B), fontWeight: FontWeight.w800)),
        ],
      ),
    );
  }

  Widget _buildFaqItem(String question, String answer) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: const Color(0xFFF8FAFC),
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: const Color(0xFFF1F5F9)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(question, style: GoogleFonts.inter(fontSize: 12, fontWeight: FontWeight.bold, color: const Color(0xFF1E293B))),
          const SizedBox(height: 6),
          Text(answer, style: GoogleFonts.inter(fontSize: 11, color: const Color(0xFF64748B), height: 1.4, fontWeight: FontWeight.w500)),
        ],
      ),
    );
  }

  void _showMoreServicesPanel(BuildContext context) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) {
        return DraggableScrollableSheet(
          initialChildSize: 0.75,
          minChildSize: 0.5,
          maxChildSize: 0.9,
          builder: (context, scrollController) {
            return Container(
              decoration: const BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.vertical(top: Radius.circular(16)),
              ),
              child: Column(
                children: [
                  // Drag Handle
                  Center(
                    child: Container(
                      margin: const EdgeInsets.only(top: 12, bottom: 8),
                      width: 40,
                      height: 5,
                      decoration: BoxDecoration(
                        color: Colors.grey[300],
                        borderRadius: BorderRadius.circular(2.5),
                      ),
                    ),
                  ),
                  // Header
                  Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 8),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text(
                          'Huduma Zaidi (More Services)',
                          style: GoogleFonts.inter(
                            fontSize: 18,
                            fontWeight: FontWeight.w800,
                            color: const Color(0xff1e293b),
                          ),
                        ),
                        IconButton(
                          onPressed: () => Navigator.pop(context),
                          icon: const Icon(Icons.close, color: Colors.grey),
                          style: IconButton.styleFrom(
                            backgroundColor: const Color(0xfff1f5f9),
                            padding: const EdgeInsets.all(8),
                          ),
                        ),
                      ],
                    ),
                  ),
                  const Divider(color: Color(0xfff1f5f9)),
                  // Content
                  Expanded(
                    child: ListView(
                      controller: scrollController,
                      padding: const EdgeInsets.all(24),
                      children: [
                        // Section 1: Kuomba (Requests)
                        _buildPanelSectionHeader('MAOMBI YA HUDUMA (REQUESTS)'),
                        const SizedBox(height: 12),
                        _buildPanelItem(
                          context,
                          icon: Icons.plumbing_rounded,
                          color: const Color(0xff3b82f6),
                          title: 'Omba Mafundi (Maintenance Request)',
                          subtitle: 'Fungua tiketi ya kurekebisha maji, umeme, nk.',
                        ),
                        _buildPanelItem(
                          context,
                          icon: Icons.person_add_alt_1_rounded,
                          color: const Color(0xff10b981),
                          title: 'Sajili Mpangaji Mwenza',
                          subtitle: 'Ongeza ndugu au rafiki anayeishi nawe',
                        ),
                        _buildPanelItem(
                          context,
                          icon: Icons.logout_rounded,
                          color: const Color(0xffef4444),
                          title: 'Omba Kibali cha Kuhama',
                          subtitle: 'Tuma taarifa ya kusitisha mkataba',
                        ),
                        _buildPanelItem(
                          context,
                          icon: Icons.discount_rounded,
                          color: const Color(0xfff59e0b),
                          title: 'Omba Punguzo la Kodi',
                          subtitle: 'Tuma ombi la majadiliano ya bei na Landlord',
                        ),
                        const SizedBox(height: 24),
                        
                        // Section 2: Kuangalia (Views)
                        _buildPanelSectionHeader('TAARIFA NA NYARAKA (VIEW INFO)'),
                        const SizedBox(height: 12),
                        _buildPanelItem(
                          context,
                          icon: Icons.gavel_rounded,
                          color: const Color(0xff6366f1),
                          title: 'Sheria za Nyumba (House Rules)',
                          subtitle: 'Angalia kanuni na taratibu za mpangaji',
                        ),
                        _buildPanelItem(
                          context,
                          icon: Icons.receipt_long_rounded,
                          color: const Color(0xff06b6d4),
                          title: 'Nyaraka za Mikataba',
                          subtitle: 'Pakua na kusoma mikataba yako yote',
                        ),
                        _buildPanelItem(
                          context,
                          icon: Icons.contact_emergency_rounded,
                          color: const Color(0xffec4899),
                          title: 'Namba za Dharura',
                          subtitle: 'Namba za ulinzi, zima moto, dharura za daktari',
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            );
          },
        );
      },
    );
  }

  Widget _buildPanelSectionHeader(String title) {
    return Text(
      title,
      style: GoogleFonts.inter(
        fontSize: 11,
        fontWeight: FontWeight.w800,
        color: const Color(0xff64748b),
        letterSpacing: 1.2,
      ),
    );
  }

  Widget _buildPanelItem(
    BuildContext context, {
    required IconData icon,
    required Color color,
    required String title,
    required String subtitle,
  }) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xfff1f5f9)),
      ),
      child: ListTile(
        onTap: () {
          Navigator.pop(context);
          Helpers.showSnackBar(context, 'Ombi la "$title" limeshirikishwa kikamilifu!');
        },
        leading: Container(
          padding: const EdgeInsets.all(10),
          decoration: BoxDecoration(
            color: color.withOpacity(0.1),
            borderRadius: BorderRadius.circular(12),
          ),
          child: Icon(icon, color: color, size: 20),
        ),
        title: Text(
          title,
          style: GoogleFonts.inter(
            fontSize: 13,
            fontWeight: FontWeight.w700,
            color: const Color(0xff1e293b),
          ),
        ),
        subtitle: Padding(
          padding: const EdgeInsets.only(top: 2),
          child: Text(
            subtitle,
            style: GoogleFonts.inter(
              fontSize: 10,
              color: const Color(0xff64748b),
              fontWeight: FontWeight.w500,
            ),
          ),
        ),
        trailing: const Icon(Icons.arrow_forward_ios_rounded, color: Color(0xffcbd5e1), size: 12),
      ),
    );
  }
}

class FullscreenPdfViewerPage extends StatelessWidget {
  final bool isEnglish;
  final UserModel user;

  const FullscreenPdfViewerPage({Key? key, required this.isEnglish, required this.user}) : super(key: key);

  String _t(String sw, String en) {
    return isEnglish ? en : sw;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFF525659), // Realistic dark grey PDF viewer background
      appBar: AppBar(
        backgroundColor: const Color(0xFF323639), // Dark chrome bar
        foregroundColor: Colors.white,
        elevation: 4,
        title: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'mkataba_pango_MNA4.pdf',
              style: GoogleFonts.inter(fontSize: 14, fontWeight: FontWeight.bold, color: Colors.white),
            ),
            const SizedBox(height: 2),
            Text(
              _t('Uhakiki wa Mkataba • Kurasa 1 ya 1', 'Contract Preview • Page 1 of 1'),
              style: GoogleFonts.inter(fontSize: 10, color: const Color(0xFFCBD5E1)),
            ),
          ],
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.share_rounded, size: 20),
            onPressed: () {},
          ),
          IconButton(
            icon: const Icon(Icons.print_rounded, size: 20),
            onPressed: () {},
          ),
        ],
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          physics: const BouncingScrollPhysics(),
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 24),
          child: Center(
            child: AspectRatio(
              aspectRatio: 0.707, // Standard A4 Aspect Ratio (highly realistic!)
              child: Container(
                padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 32),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(4), // Realistic paper sharp corners
                  boxShadow: [
                    BoxShadow(
                      color: Colors.black.withOpacity(0.3),
                      blurRadius: 16,
                      offset: const Offset(0, 8),
                    ),
                  ],
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Official Government/Authority Header (Realistic Seal Logo Mockup)
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              'MANNA REAL ESTATE LTD',
                              style: GoogleFonts.spaceGrotesk(
                                fontSize: 13,
                                fontWeight: FontWeight.w900,
                                color: const Color(0xFFFE5D37),
                                letterSpacing: 1.5,
                              ),
                            ),
                            Text(
                              _t('Mikocheni, Dar es Salaam, Tanzania', 'Mikocheni, Dar es Salaam, Tanzania'),
                              style: GoogleFonts.inter(fontSize: 8, fontWeight: FontWeight.w500, color: Colors.grey[500]),
                            ),
                          ],
                        ),
                        // Circular Stamp representation on top
                        Container(
                          padding: const EdgeInsets.all(6),
                          decoration: BoxDecoration(
                            shape: BoxShape.circle,
                            border: Border.all(color: const Color(0xFFFE5D37).withOpacity(0.25), width: 1.5),
                          ),
                          child: const Icon(Icons.verified_user_rounded, color: Color(0xFFFE5D37), size: 18),
                        ),
                      ],
                    ),
                    const SizedBox(height: 14),
                    const Divider(color: Colors.black87, thickness: 1.2),
                    const SizedBox(height: 14),
                    
                    // Main Title
                    Center(
                      child: Text(
                        _t('MKATABA WA KUKODISHA NYUMBA YA MAKAZI', 'RESIDENTIAL LEASE AGREEMENT'),
                        style: GoogleFonts.inter(
                          fontSize: 12,
                          fontWeight: FontWeight.w900,
                          color: Colors.black87,
                          letterSpacing: 0.5,
                        ),
                        textAlign: TextAlign.center,
                      ),
                    ),
                    const SizedBox(height: 20),
                    
                    // Section 1: Parties Involved
                    _buildDocSectionHeader(_t('1. PANDE HUSIKA (PARTIES)', '1. CONTRACT PARTIES')),
                    const SizedBox(height: 6),
                    _buildDocParagraph(
                      _t(
                        'Mkataba huu umeingia leo tarehe 01 Jan 2025 kati ya Mwenye Nyumba: MAMA KEN (Miliki) na Mpangaji: ${user.name.toUpperCase()} (Mteja).',
                        'This Lease Agreement is made on 01 Jan 2025 between Landlord: MAMA KEN (Owner) and Tenant: ${user.name.toUpperCase()} (Client).'
                      ),
                    ),
                    const SizedBox(height: 14),
                    
                    // Section 2: Property Description
                    _buildDocSectionHeader(_t('2. NYUMBA NA KODI (PROPERTY & RENT)', '2. PROPERTY & RENT DETAILS')),
                    const SizedBox(height: 6),
                    _buildDocParagraph(
                      _t(
                        'Mpangaji amekodishiwa nyumba ya makazi (Palm Heights - Apt A4) iliyopo Mikocheni, kwa kodi ya TSh 450,000 kwa mwezi inayolipwa kila tarehe 5 ya kila mwezi.',
                        'The Landlord leases to the Tenant residential unit (Palm Heights - Apt A4) situated in Mikocheni, for a monthly rent of TSh 450,000 payable on the 5th of each month.'
                      ),
                    ),
                    const SizedBox(height: 14),
                    
                    // Section 3: Terms and conditions table
                    _buildDocSectionHeader(_t('3. MASHARTI MAALUM (TERMS & CONDITIONS)', '3. TERMS & CONDITIONS')),
                    const SizedBox(height: 6),
                    _buildDocTermRow('1.', _t('Kodi ilipwe kwa wakati kila mwezi.', 'Rent must be paid strictly on time.')),
                    _buildDocTermRow('2.', _t('Hakuna ruhusa ya kufuga wanyama yoyote.', 'No pets are allowed inside the premises.')),
                    _buildDocTermRow('3.', _t('Mteja atalipia bili ya umeme na maji ya matumizi yake.', 'Tenant is fully responsible for electricity & water utility bills.')),
                    const Spacer(),
                    
                    // Signatures & Official Stamp
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              _t('Sahihi ya Mwenye Nyumba:', 'Landlord Signature:'),
                              style: GoogleFonts.inter(fontSize: 8, fontWeight: FontWeight.bold, color: Colors.grey[700]),
                            ),
                            const SizedBox(height: 12),
                            // Simulated handwriting signature
                            Text(
                              'Mama Ken',
                              style: GoogleFonts.dancingScript(fontSize: 16, fontWeight: FontWeight.bold, color: Colors.blue[900]),
                            ),
                            Container(width: 80, height: 1, color: Colors.black45),
                          ],
                        ),
                        // Official Stamp Seal image representation
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                          decoration: BoxDecoration(
                            border: Border.all(color: const Color(0xFFFE5D37).withOpacity(0.6), width: 1.5),
                            borderRadius: BorderRadius.circular(8),
                          ),
                          child: Text(
                            _t('IMETHIBITISHWA\nMANNA ESTATE', 'APPROVED\nMANNA ESTATE'),
                            style: GoogleFonts.inter(fontSize: 7, fontWeight: FontWeight.w900, color: const Color(0xFFFE5D37)),
                            textAlign: TextAlign.center,
                          ),
                        ),
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              _t('Sahihi ya Mpangaji:', 'Tenant Signature:'),
                              style: GoogleFonts.inter(fontSize: 8, fontWeight: FontWeight.bold, color: Colors.grey[700]),
                            ),
                            const SizedBox(height: 12),
                            Text(
                              user.name.split(' ').first,
                              style: GoogleFonts.dancingScript(fontSize: 16, fontWeight: FontWeight.bold, color: Colors.blue[900]),
                            ),
                            Container(width: 80, height: 1, color: Colors.black45),
                          ],
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildDocSectionHeader(String title) {
    return Text(
      title,
      style: GoogleFonts.inter(fontSize: 9, fontWeight: FontWeight.w900, color: Colors.black87, letterSpacing: 0.5),
    );
  }

  Widget _buildDocParagraph(String text) {
    return Text(
      text,
      style: GoogleFonts.inter(fontSize: 8.5, color: Colors.black54, height: 1.4, fontWeight: FontWeight.w500),
      textAlign: TextAlign.justify,
    );
  }

  Widget _buildDocTermRow(String index, String term) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 4),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text('$index ', style: GoogleFonts.inter(fontSize: 8.5, fontWeight: FontWeight.bold, color: Colors.black87)),
          const SizedBox(width: 4),
          Expanded(
            child: Text(
              term,
              style: GoogleFonts.inter(fontSize: 8.5, color: Colors.black54, height: 1.3, fontWeight: FontWeight.w500),
            ),
          ),
        ],
      ),
    );
  }
}
