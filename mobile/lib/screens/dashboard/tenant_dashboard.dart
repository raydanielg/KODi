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
  int _currentTab = 0;

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
                  // 1. Beautiful Premium White Header
                  _buildTopHeader(user),
                  
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
                  borderRadius: BorderRadius.circular(24),
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
                  borderRadius: BorderRadius.circular(24),
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
                borderRadius: BorderRadius.circular(24),
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
      {'name': 'Umeme', 'icon': Icons.bolt_rounded, 'color': const Color(0xFFF59E0B), 'bg': const Color(0xFFFEF3C7)},
      {'name': 'Maji', 'icon': Icons.water_drop_rounded, 'color': const Color(0xFF3B82F6), 'bg': const Color(0xFFDBEAFE)},
      {'name': 'Usafi', 'icon': Icons.delete_outline_rounded, 'color': const Color(0xFF10B981), 'bg': const Color(0xFFD1FAE5)},
      {'name': 'Ulinzi', 'icon': Icons.shield_outlined, 'color': const Color(0xFFEF4444), 'bg': const Color(0xFFFEE2E2)},
      {'name': 'Stakabadhi', 'icon': Icons.receipt_long_rounded, 'color': const Color(0xFF6366F1), 'bg': const Color(0xFFE0E7FF)},
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
                  fontWeight: FontWeight.w700,
                  color: const Color(0xFF1E293B),
                ),
              ),
              Text(
                'Angalia Zote',
                style: GoogleFonts.inter(
                  fontSize: 12,
                  fontWeight: FontWeight.w600,
                  color: const Color(0xFFFE5D37),
                ),
              ),
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
            itemCount: services.length,
            itemBuilder: (context, index) {
              final item = services[index];
              return Container(
                margin: const EdgeInsets.only(right: 20),
                child: Column(
                  children: [
                    InkWell(
                      onTap: () {
                        Helpers.showSnackBar(context, 'Malipo ya huduma ya ${item['name']} yanashughulikiwa...');
                      },
                      child: Container(
                        padding: const EdgeInsets.all(14),
                        decoration: BoxDecoration(
                          color: item['bg'] as Color,
                          shape: BoxShape.circle,
                        ),
                        child: Icon(
                          item['icon'] as IconData,
                          color: item['color'] as Color,
                          size: 24,
                        ),
                      ),
                    ),
                    const SizedBox(height: 6),
                    Text(
                      item['name'] as String,
                      style: GoogleFonts.inter(
                        fontSize: 11,
                        fontWeight: FontWeight.w600,
                        color: const Color(0xFF4B5563),
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
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: const BorderRadius.vertical(top: Radius.circular(32)),
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
                'Shughuli za Hivi Karibuni',
                style: GoogleFonts.inter(
                  fontSize: 18,
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

  Widget _buildBodyContent(UserModel user) {
    switch (_currentTab) {
      case 0:
        return _buildHomeTabContent();
      case 1:
        return _buildSearchTabContent();
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
      {'icon': Icons.home_rounded, 'label': 'Nyumbani'},
      {'icon': Icons.explore_rounded, 'label': 'Tafuta'},
      {'icon': Icons.receipt_long_rounded, 'label': 'Malipo'},
      {'icon': Icons.person_rounded, 'label': 'Mimi'},
    ];

    return Container(
      padding: const EdgeInsets.symmetric(vertical: 8, horizontal: 16),
      decoration: BoxDecoration(
        color: const Color(0xFF0D0F12), // Sleek Dark Floating Bar from Mockup
        borderRadius: BorderRadius.circular(30),
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
                borderRadius: BorderRadius.circular(20),
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
    final user = _authService.currentUser!;
    return RefreshIndicator(
      onRefresh: _loadDashboard,
      color: const Color(0xFFFE5D37),
      child: ListView(
        physics: const BouncingScrollPhysics(),
        padding: const EdgeInsets.symmetric(vertical: 24),
        children: [
          // Credentials Card
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 20),
            child: _buildCredentialsCard(user),
          ),
          const SizedBox(height: 24),

          // Active Rental Highlight Card
          _buildRentStatusCard(context),
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

  Widget _buildSearchTabContent() {
    final properties = [
      {'title': 'Palm Heights Apartment', 'price': 'TSh 450,000/mo', 'location': 'Mikocheni, Dar es Salaam', 'beds': 2, 'baths': 2, 'image': 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=300&fit=crop'},
      {'title': 'Bahari Beach Villa', 'price': 'TSh 1,200,000/mo', 'location': 'Tegeta, Dar es Salaam', 'beds': 4, 'baths': 3, 'image': 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=300&fit=crop'},
      {'title': 'Oysterbay Executive Studio', 'price': 'TSh 800,000/mo', 'location': 'Oysterbay, Dar es Salaam', 'beds': 1, 'baths': 1, 'image': 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=300&fit=crop'},
    ];

    return ListView(
      physics: const BouncingScrollPhysics(),
      padding: const EdgeInsets.all(20),
      children: [
        Text(
          'Tafuta Nyumba za Kukodisha',
          style: GoogleFonts.inter(
            fontSize: 22,
            fontWeight: FontWeight.w800,
            color: const Color(0xFF1E293B),
          ),
        ),
        const SizedBox(height: 16),
        // Premium Search Bar
        Container(
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(16),
            border: Border.all(color: const Color(0xFFE2E8F0)),
            boxShadow: [
              BoxShadow(
                color: Colors.black.withOpacity(0.02),
                blurRadius: 10,
                offset: const Offset(0, 4),
              ),
            ],
          ),
          child: Row(
            children: [
              const Icon(Icons.search_rounded, color: Color(0xFF64748B)),
              const SizedBox(width: 12),
              Expanded(
                child: TextField(
                  decoration: InputDecoration(
                    hintText: 'Tafuta kwa eneo au jina...',
                    hintStyle: GoogleFonts.inter(color: const Color(0xFF94A3B8), fontSize: 13),
                    border: InputBorder.none,
                  ),
                ),
              ),
              IconButton(
                onPressed: () {},
                icon: const Icon(Icons.tune_rounded, color: Color(0xFFFE5D37)),
              ),
            ],
          ),
        ),
        const SizedBox(height: 24),
        Text(
          'Inayopendekezwa (Recommended)',
          style: GoogleFonts.inter(
            fontSize: 15,
            fontWeight: FontWeight.w700,
            color: const Color(0xFF1E293B),
          ),
        ),
        const SizedBox(height: 12),
        ...properties.map((prop) {
          return Container(
            margin: const EdgeInsets.only(bottom: 16),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(20),
              border: Border.all(color: const Color(0xFFE2E8F0)),
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withOpacity(0.02),
                  blurRadius: 8,
                  offset: const Offset(0, 4),
                ),
              ],
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                ClipRRect(
                  borderRadius: const BorderRadius.vertical(top: Radius.circular(20)),
                  child: Image.network(
                    prop['image'] as String,
                    height: 160,
                    width: double.infinity,
                    fit: BoxTheme.fitWidth ?? BoxFit.cover,
                  ),
                ),
                Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text(
                            prop['price'] as String,
                            style: GoogleFonts.spaceGrotesk(
                              fontSize: 16,
                              fontWeight: FontWeight.w700,
                              color: const Color(0xFFFE5D37),
                            ),
                          ),
                          Row(
                            children: [
                              const Icon(Icons.star_rounded, color: Color(0xFFF59E0B), size: 16),
                              const SizedBox(width: 4),
                              Text('4.8', style: GoogleFonts.inter(fontSize: 12, fontWeight: FontWeight.bold)),
                            ],
                          ),
                        ],
                      ),
                      const SizedBox(height: 6),
                      Text(
                        prop['title'] as String,
                        style: GoogleFonts.inter(
                          fontSize: 14,
                          fontWeight: FontWeight.w800,
                          color: const Color(0xFF1E293B),
                        ),
                      ),
                      const SizedBox(height: 4),
                      Row(
                        children: [
                          const Icon(Icons.location_on_rounded, color: Color(0xFF64748B), size: 12),
                          const SizedBox(width: 4),
                          Expanded(
                            child: Text(
                              prop['location'] as String,
                              style: GoogleFonts.inter(fontSize: 11, color: const Color(0xFF64748B), fontWeight: FontWeight.w500),
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
              ],
            ),
          );
        }).toList(),
      ],
    );
  }

  Widget _buildPaymentsTabContent() {
    final invoices = [
      {'title': 'Kodi ya Pango - June 2026', 'desc': 'Palm Heights • Apt A4', 'amount': 'TSh 450,000', 'status': 'Paid', 'date': '05 Jun 2026'},
      {'title': 'Kodi ya Pango - May 2026', 'desc': 'Palm Heights • Apt A4', 'amount': 'TSh 450,000', 'status': 'Paid', 'date': '02 May 2026'},
      {'title': 'Kodi ya Pango - April 2026', 'desc': 'Palm Heights • Apt A4', 'amount': 'TSh 450,000', 'status': 'Paid', 'date': '01 Apr 2026'},
    ];

    return ListView(
      physics: const BouncingScrollPhysics(),
      padding: const EdgeInsets.all(20),
      children: [
        Text(
          'Historia ya Malipo (Invoices)',
          style: GoogleFonts.inter(
            fontSize: 22,
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
              borderRadius: BorderRadius.circular(16),
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
      padding: const EdgeInsets.all(20),
      children: [
        // Profile Info Card
        Center(
          child: Column(
            children: [
              Container(
                padding: const EdgeInsets.all(3),
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  border: Border.all(color: const Color(0xFFFE5D37), width: 2),
                ),
                child: const CircleAvatar(
                  radius: 40,
                  backgroundImage: NetworkImage(
                    'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&h=100&fit=crop',
                  ),
                ),
              ),
              const SizedBox(height: 12),
              Text(
                user.name,
                style: GoogleFonts.inter(fontSize: 18, fontWeight: FontWeight.w800, color: const Color(0xFF1E293B)),
              ),
              const SizedBox(height: 4),
              Text(
                user.email,
                style: GoogleFonts.inter(fontSize: 13, color: const Color(0xFF64748B), fontWeight: FontWeight.w500),
              ),
              const SizedBox(height: 8),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
                decoration: BoxDecoration(
                  color: const Color(0xFFF1F5F9),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Text(
                  user.roleLabel,
                  style: GoogleFonts.inter(fontSize: 11, fontWeight: FontWeight.bold, color: const Color(0xFF64748B)),
                ),
              ),
            ],
          ),
        ),
        const SizedBox(height: 28),
        // Settings Menu
        _buildProfileMenuItem(icon: Icons.person_outline_rounded, label: 'Taarifa Binafsi (My Info)', color: const Color(0xFF1E293B)),
        _buildProfileMenuItem(icon: Icons.credit_card_rounded, label: 'Kadi za Malipo (Payment Methods)', color: const Color(0xFF1E293B)),
        _buildProfileMenuItem(icon: Icons.history_edu_rounded, label: 'Mkataba Wangu (My Lease Contract)', color: const Color(0xFF1E293B)),
        _buildProfileMenuItem(icon: Icons.help_outline_rounded, label: 'Msaada na Maswali (Support)', color: const Color(0xFF1E293B)),
        const SizedBox(height: 20),
        // Logout Button
        ListTile(
          onTap: () async {
            final confirm = await Helpers.showConfirmationDialog(
              context,
              title: 'Kutoka Kwenye App',
              message: 'Je, una uhakika unataka kutoka kwenye akaunti yako?',
              confirmText: 'Kutoka',
              cancelText: 'Baki hapa',
            );
            if (confirm) {
              await _authService.logout();
              if (mounted) {
                Navigator.pushNamedAndRemoveUntil(context, '/login', (route) => false);
              }
            }
          },
          leading: Container(
            padding: const EdgeInsets.all(8),
            decoration: const BoxDecoration(
              color: Color(0xFFFEE2E2),
              shape: BoxShape.circle,
            ),
            child: const Icon(Icons.logout_rounded, color: Color(0xFFEF4444), size: 20),
          ),
          title: Text(
            'Kutoka Kwenye Akaunti (Logout)',
            style: GoogleFonts.inter(fontSize: 13, fontWeight: FontWeight.w700, color: const Color(0xFFEF4444)),
          ),
          trailing: const Icon(Icons.chevron_right_rounded, color: Color(0xFF94A3B8)),
        ),
      ],
    );
  }

  Widget _buildProfileMenuItem({required IconData icon, required String label, required Color color}) {
    return Container(
      margin: const EdgeInsets.only(bottom: 8),
      child: ListTile(
        onTap: () {
          Helpers.showSnackBar(context, 'Kazi ya "$label" inakuja hivi karibuni!');
        },
        leading: Container(
          padding: const EdgeInsets.all(8),
          decoration: const BoxDecoration(
            color: Color(0xFFF1F5F9),
            shape: BoxShape.circle,
          ),
          child: Icon(icon, color: const Color(0xFF64748B), size: 20),
        ),
        title: Text(
          label,
          style: GoogleFonts.inter(fontSize: 13, fontWeight: FontWeight.w700, color: color),
        ),
        trailing: const Icon(Icons.chevron_right_rounded, color: Color(0xFF94A3B8)),
      ),
    );
  }
}
