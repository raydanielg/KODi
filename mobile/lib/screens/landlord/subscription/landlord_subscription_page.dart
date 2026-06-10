import 'package:flutter/material.dart';
import '../../../constants/app_colors.dart';
import '../../../services/api_service.dart';
import '../../../utils/helpers.dart';

class LandlordSubscriptionPage extends StatefulWidget {
  const LandlordSubscriptionPage({super.key});

  @override
  State<LandlordSubscriptionPage> createState() => _LandlordSubscriptionPageState();
}

class _LandlordSubscriptionPageState extends State<LandlordSubscriptionPage>
    with SingleTickerProviderStateMixin {
  final ApiService _api = ApiService();
  late TabController _tabCtrl;
  String _billing = 'monthly'; // 'monthly' | 'yearly'
  String? _selectedPlan;
  bool _subscribing = false;

  @override
  void initState() {
    super.initState();
    _tabCtrl = TabController(length: 2, vsync: this);
    _tabCtrl.addListener(() => setState(() {}));
  }

  @override
  void dispose() {
    _tabCtrl.dispose();
    super.dispose();
  }

  // ─── Plan Data ────────────────────────────────────────────────────────────

  static const _plans = [
    {
      'id': 'starter',
      'name': 'Starter',
      'tagline': 'Kwa mwenye nyumba mmoja mmoja',
      'icon': Icons.home_rounded,
      'color': Color(0xFF3B82F6),
      'monthlyPrice': 15000,
      'yearlyPrice': 150000,
      'properties': 1,
      'units': 5,
      'sms': 50,
      'features': [
        'Nyumba moja (1 property)',
        'Hadi vyumba 5',
        'SMS 50/mwezi',
        'Wapangaji bila kikomo',
        'Mikataba ya kimsingi',
        'Taarifa za msingi',
        'Usaidizi wa email',
      ],
      'missing': ['Ripoti za kina', 'SMS nyingi', 'Nyumba nyingi'],
    },
    {
      'id': 'professional',
      'name': 'Professional',
      'tagline': 'Bora kwa wamiliki wa nyumba 2–10',
      'icon': Icons.apartment_rounded,
      'color': Color(0xFFB44040),
      'monthlyPrice': 45000,
      'yearlyPrice': 450000,
      'properties': 10,
      'units': 50,
      'sms': 500,
      'popular': true,
      'features': [
        'Nyumba 10 (properties)',
        'Hadi vyumba 50',
        'SMS 500/mwezi',
        'Wapangaji bila kikomo',
        'Mikataba ya kina (PDF)',
        'Taarifa za kina',
        'Bili za umeme & maji',
        'Ripoti za Excel & PDF',
        'Usaidizi wa simu',
      ],
      'missing': ['SMS nyingi zaidi'],
    },
    {
      'id': 'enterprise',
      'name': 'Enterprise',
      'tagline': 'Kwa wamiliki wakubwa & kampuni',
      'icon': Icons.business_rounded,
      'color': Color(0xFF7C3AED),
      'monthlyPrice': 120000,
      'yearlyPrice': 1200000,
      'properties': 9999,
      'units': 9999,
      'sms': 9999,
      'features': [
        'Nyumba bila kikomo',
        'Vyumba bila kikomo',
        'SMS bila kikomo',
        'Wapangaji bila kikomo',
        'Mikataba yote (PDF)',
        'Taarifa za kina',
        'Bili za umeme & maji',
        'Ripoti za Excel & PDF',
        'API integration',
        'Usaidizi 24/7',
        'Dedicated account manager',
      ],
      'missing': [],
    },
  ];

  // ─── Build ────────────────────────────────────────────────────────────────

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF4F6F8),
      body: Column(
        children: [
          _buildHeader(context),
          Expanded(
            child: SingleChildScrollView(
              padding: const EdgeInsets.fromLTRB(16, 0, 16, 100),
              child: Column(
                children: [
                  const SizedBox(height: 20),
                  _buildHeroBanner(),
                  const SizedBox(height: 20),
                  _buildBillingToggle(),
                  const SizedBox(height: 20),
                  _buildCategoryTabs(),
                  const SizedBox(height: 16),
                  ..._plans.map((p) => _buildPlanCard(p)),
                  const SizedBox(height: 20),
                  _buildFeaturesComparison(),
                  const SizedBox(height: 20),
                  _buildFAQ(),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildHeader(BuildContext context) {
    return Container(
      color: Colors.white,
      child: SafeArea(
        bottom: false,
        child: Padding(
          padding: const EdgeInsets.fromLTRB(16, 12, 16, 12),
          child: Row(
            children: [
              GestureDetector(
                onTap: () => Navigator.pop(context),
                child: Container(
                  width: 38, height: 38,
                  decoration: BoxDecoration(
                    color: const Color(0xFFF4F6F8),
                    borderRadius: BorderRadius.circular(10),
                    border: Border.all(color: const Color(0xFFE5E7EB)),
                  ),
                  child: const Icon(Icons.arrow_back_ios_new_rounded, size: 16, color: Color(0xFF374151)),
                ),
              ),
              const SizedBox(width: 12),
              const Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text('Subscription Plans',
                      style: TextStyle(fontWeight: FontWeight.w800, fontSize: 18, color: Color(0xFF111827))),
                  Text('Chagua mpango unaokufaa',
                      style: TextStyle(color: Color(0xFF9CA3AF), fontSize: 11)),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildHeroBanner() {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          colors: [Color(0xFFB44040), Color(0xFF7E2B2B)],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(color: AppColors.primary.withValues(alpha: 0.3), blurRadius: 20, offset: const Offset(0, 8)),
        ],
      ),
      child: Row(
        children: [
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                  decoration: BoxDecoration(
                    color: Colors.white.withValues(alpha: 0.2),
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: const Text('🔥 Ofa Maalum', style: TextStyle(color: Colors.white, fontSize: 11, fontWeight: FontWeight.w700)),
                ),
                const SizedBox(height: 10),
                const Text(
                  'Hifadhi 17%\nkwa malipo ya mwaka',
                  style: TextStyle(color: Colors.white, fontSize: 20, fontWeight: FontWeight.w900, height: 1.2),
                ),
                const SizedBox(height: 8),
                const Text(
                  'Jiunge leo na upate miezi 2 bila malipo',
                  style: TextStyle(color: Colors.white70, fontSize: 12),
                ),
              ],
            ),
          ),
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: Colors.white.withValues(alpha: 0.15),
              borderRadius: BorderRadius.circular(16),
            ),
            child: const Icon(Icons.star_rounded, color: Colors.white, size: 40),
          ),
        ],
      ),
    );
  }

  Widget _buildBillingToggle() {
    return Container(
      padding: const EdgeInsets.all(4),
      decoration: BoxDecoration(
        color: const Color(0xFFE5E7EB),
        borderRadius: BorderRadius.circular(14),
      ),
      child: Row(
        children: [
          _billingOption('monthly', 'Kila Mwezi', null),
          _billingOption('yearly', 'Kila Mwaka', 'Hifadhi 17%'),
        ],
      ),
    );
  }

  Widget _billingOption(String value, String label, String? badge) {
    final sel = _billing == value;
    return Expanded(
      child: GestureDetector(
        onTap: () => setState(() => _billing = value),
        child: AnimatedContainer(
          duration: const Duration(milliseconds: 200),
          padding: const EdgeInsets.symmetric(vertical: 10),
          decoration: BoxDecoration(
            color: sel ? Colors.white : Colors.transparent,
            borderRadius: BorderRadius.circular(10),
            boxShadow: sel ? [BoxShadow(color: Colors.black.withValues(alpha: 0.08), blurRadius: 6)] : [],
          ),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Text(label,
                  style: TextStyle(
                    color: sel ? const Color(0xFF111827) : const Color(0xFF9CA3AF),
                    fontWeight: sel ? FontWeight.w700 : FontWeight.w500,
                    fontSize: 13,
                  )),
              if (badge != null) ...[
                const SizedBox(width: 6),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                  decoration: BoxDecoration(
                    color: const Color(0xFF10B981),
                    borderRadius: BorderRadius.circular(10),
                  ),
                  child: Text(badge, style: const TextStyle(color: Colors.white, fontSize: 9, fontWeight: FontWeight.w800)),
                ),
              ],
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildCategoryTabs() {
    return SizedBox(
      height: 36,
      child: ListView(
        scrollDirection: Axis.horizontal,
        children: [
          _catTab('Mwenye Nyumba', true),
          _catTab('Mchanganyiko', false),
          _catTab('Kampuni', false),
        ],
      ),
    );
  }

  Widget _catTab(String label, bool active) {
    return Container(
      margin: const EdgeInsets.only(right: 8),
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      decoration: BoxDecoration(
        color: active ? AppColors.primary : Colors.white,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: active ? AppColors.primary : const Color(0xFFE5E7EB)),
      ),
      child: Text(
        label,
        style: TextStyle(
          color: active ? Colors.white : const Color(0xFF6B7280),
          fontWeight: active ? FontWeight.w700 : FontWeight.w500,
          fontSize: 12,
        ),
      ),
    );
  }

  Widget _buildPlanCard(Map<String, dynamic> plan) {
    final isPopular = plan['popular'] == true;
    final isSelected = _selectedPlan == plan['id'];
    final color = plan['color'] as Color;
    final monthlyPrice = plan['monthlyPrice'] as int;
    final yearlyPrice = plan['yearlyPrice'] as int;
    final price = _billing == 'monthly' ? monthlyPrice : (yearlyPrice ~/ 12);
    final features = plan['features'] as List;
    final missing = plan['missing'] as List;

    return GestureDetector(
      onTap: () => setState(() => _selectedPlan = plan['id'] as String),
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 200),
        margin: const EdgeInsets.only(bottom: 14),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(20),
          border: Border.all(
            color: isSelected ? color : (isPopular ? color.withValues(alpha: 0.3) : const Color(0xFFE5E7EB)),
            width: isSelected ? 2 : 1,
          ),
          boxShadow: [
            BoxShadow(
              color: isSelected ? color.withValues(alpha: 0.12) : Colors.black.withValues(alpha: 0.04),
              blurRadius: isSelected ? 16 : 8,
              offset: const Offset(0, 4),
            ),
          ],
        ),
        child: Column(
          children: [
            // ── Plan header
            Container(
              padding: const EdgeInsets.all(18),
              decoration: BoxDecoration(
                color: isSelected ? color.withValues(alpha: 0.06) : Colors.transparent,
                borderRadius: const BorderRadius.vertical(top: Radius.circular(19)),
              ),
              child: Row(
                children: [
                  Container(
                    padding: const EdgeInsets.all(10),
                    decoration: BoxDecoration(
                      color: color.withValues(alpha: 0.1),
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: Icon(plan['icon'] as IconData, color: color, size: 22),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          children: [
                            Text(plan['name'] as String,
                                style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 16, color: Color(0xFF111827))),
                            if (isPopular) ...[
                              const SizedBox(width: 8),
                              Container(
                                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                                decoration: BoxDecoration(
                                  gradient: const LinearGradient(colors: [Color(0xFFF59E0B), Color(0xFFEF4444)]),
                                  borderRadius: BorderRadius.circular(10),
                                ),
                                child: const Text('⭐ Maarufu', style: TextStyle(color: Colors.white, fontSize: 9, fontWeight: FontWeight.w800)),
                              ),
                            ],
                          ],
                        ),
                        Text(plan['tagline'] as String,
                            style: const TextStyle(color: Color(0xFF6B7280), fontSize: 12)),
                      ],
                    ),
                  ),
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.end,
                    children: [
                      Row(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text('TZS ', style: TextStyle(color: Color(0xFF6B7280), fontSize: 11, fontWeight: FontWeight.w600)),
                          Text(
                            Helpers.formatMoney(price.toDouble()),
                            style: TextStyle(color: color, fontWeight: FontWeight.w900, fontSize: 20),
                          ),
                        ],
                      ),
                      Text('/mwezi', style: TextStyle(color: color.withValues(alpha: 0.7), fontSize: 10)),
                      if (_billing == 'yearly')
                        Text(
                          'TZS ${Helpers.formatMoney(yearlyPrice.toDouble())}/mwaka',
                          style: const TextStyle(color: Color(0xFF10B981), fontSize: 9, fontWeight: FontWeight.w600),
                        ),
                    ],
                  ),
                ],
              ),
            ),

            // ── Quick stats
            Container(
              margin: const EdgeInsets.symmetric(horizontal: 16),
              padding: const EdgeInsets.symmetric(vertical: 10),
              decoration: BoxDecoration(
                color: const Color(0xFFF9FAFB),
                borderRadius: BorderRadius.circular(12),
                border: Border.all(color: const Color(0xFFE5E7EB)),
              ),
              child: Row(
                children: [
                  _planStat(plan['properties'] == 9999 ? '∞' : '${plan['properties']}', 'Properties', color),
                  _divider(),
                  _planStat(plan['units'] == 9999 ? '∞' : '${plan['units']}', 'Units', color),
                  _divider(),
                  _planStat(plan['sms'] == 9999 ? '∞' : '${plan['sms']}', 'SMS/mo', color),
                ],
              ),
            ),

            // ── Features list
            Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                children: [
                  ...features.map((f) => _featureRow(f as String, true, color)),
                  ...missing.map((f) => _featureRow(f as String, false, color)),
                ],
              ),
            ),

            // ── Subscribe button
            Padding(
              padding: const EdgeInsets.fromLTRB(16, 0, 16, 16),
              child: SizedBox(
                width: double.infinity,
                height: 50,
                child: ElevatedButton(
                  onPressed: _subscribing ? null : () => _subscribe(plan),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: isSelected ? color : Colors.white,
                    foregroundColor: isSelected ? Colors.white : color,
                    side: BorderSide(color: color, width: 1.5),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                    elevation: 0,
                  ),
                  child: _subscribing && _selectedPlan == plan['id']
                      ? SizedBox(width: 20, height: 20, child: CircularProgressIndicator(color: isSelected ? Colors.white : color, strokeWidth: 2))
                      : Text(
                          isSelected ? 'Endelea na ${plan['name']}' : 'Chagua ${plan['name']}',
                          style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 14),
                        ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _planStat(String value, String label, Color color) {
    return Expanded(
      child: Column(
        children: [
          Text(value, style: TextStyle(color: color, fontWeight: FontWeight.w900, fontSize: 18)),
          Text(label, style: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 10)),
        ],
      ),
    );
  }

  Widget _divider() => Container(width: 1, height: 30, color: const Color(0xFFE5E7EB));

  Widget _featureRow(String label, bool included, Color color) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8),
      child: Row(
        children: [
          Container(
            width: 20,
            height: 20,
            decoration: BoxDecoration(
              color: included ? color.withValues(alpha: 0.1) : const Color(0xFFF3F4F6),
              shape: BoxShape.circle,
            ),
            child: Icon(
              included ? Icons.check_rounded : Icons.close_rounded,
              size: 12,
              color: included ? color : const Color(0xFFD1D5DB),
            ),
          ),
          const SizedBox(width: 10),
          Expanded(
            child: Text(
              label,
              style: TextStyle(
                fontSize: 13,
                color: included ? const Color(0xFF374151) : const Color(0xFFD1D5DB),
                fontWeight: included ? FontWeight.w500 : FontWeight.w400,
                decoration: included ? null : TextDecoration.lineThrough,
              ),
            ),
          ),
        ],
      ),
    );
  }

  // ─── Features Comparison Table ────────────────────────────────────────────

  Widget _buildFeaturesComparison() {
    final rows = [
      {'feature': 'Properties', 'starter': '1', 'pro': '10', 'enterprise': '∞'},
      {'feature': 'Units/Vyumba', 'starter': '5', 'pro': '50', 'enterprise': '∞'},
      {'feature': 'SMS/mwezi', 'starter': '50', 'pro': '500', 'enterprise': '∞'},
      {'feature': 'Wapangaji', 'starter': '✓', 'pro': '✓', 'enterprise': '✓'},
      {'feature': 'Mikataba PDF', 'starter': '—', 'pro': '✓', 'enterprise': '✓'},
      {'feature': 'Bili za umeme', 'starter': '—', 'pro': '✓', 'enterprise': '✓'},
      {'feature': 'Bili za maji', 'starter': '—', 'pro': '✓', 'enterprise': '✓'},
      {'feature': 'API Access', 'starter': '—', 'pro': '—', 'enterprise': '✓'},
      {'feature': 'Usaidizi 24/7', 'starter': '—', 'pro': '—', 'enterprise': '✓'},
    ];

    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.04), blurRadius: 10)],
      ),
      child: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(16),
            child: Row(
              children: [
                Container(
                  padding: const EdgeInsets.all(8),
                  decoration: BoxDecoration(color: AppColors.primaryLight, borderRadius: BorderRadius.circular(10)),
                  child: const Icon(Icons.compare_rounded, color: AppColors.primary, size: 18),
                ),
                const SizedBox(width: 10),
                const Text('Ulinganisho wa Mipango', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 15, color: Color(0xFF111827))),
              ],
            ),
          ),
          const Divider(height: 1, color: Color(0xFFF3F4F6)),
          // Header
          _comparisonRow(
            feature: 'Kipengele',
            starter: 'Starter',
            pro: 'Pro',
            enterprise: 'Enterprise',
            isHeader: true,
          ),
          const Divider(height: 1, color: Color(0xFFF3F4F6)),
          ...rows.asMap().entries.map((e) => Column(
                children: [
                  _comparisonRow(
                    feature: e.value['feature']!,
                    starter: e.value['starter']!,
                    pro: e.value['pro']!,
                    enterprise: e.value['enterprise']!,
                    shade: e.key.isEven,
                  ),
                ],
              )),
        ],
      ),
    );
  }

  Widget _comparisonRow({
    required String feature,
    required String starter,
    required String pro,
    required String enterprise,
    bool isHeader = false,
    bool shade = false,
  }) {
    return Container(
      color: shade && !isHeader ? const Color(0xFFFAFAFA) : Colors.transparent,
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
      child: Row(
        children: [
          Expanded(
            flex: 3,
            child: Text(feature,
                style: TextStyle(
                  fontSize: isHeader ? 11 : 12,
                  fontWeight: isHeader ? FontWeight.w800 : FontWeight.w500,
                  color: isHeader ? const Color(0xFF9CA3AF) : const Color(0xFF374151),
                )),
          ),
          Expanded(child: _cellVal(starter, isHeader, const Color(0xFF3B82F6))),
          Expanded(child: _cellVal(pro, isHeader, AppColors.primary)),
          Expanded(child: _cellVal(enterprise, isHeader, const Color(0xFF7C3AED))),
        ],
      ),
    );
  }

  Widget _cellVal(String val, bool isHeader, Color color) {
    final isCheck = val == '✓';
    final isDash = val == '—';
    return Center(
      child: isCheck
          ? Container(
              width: 20, height: 20,
              decoration: BoxDecoration(color: color.withValues(alpha: 0.1), shape: BoxShape.circle),
              child: Icon(Icons.check_rounded, size: 12, color: color),
            )
          : isDash
              ? const Text('—', style: TextStyle(color: Color(0xFFD1D5DB), fontSize: 14))
              : Text(val,
                  style: TextStyle(
                    fontSize: isHeader ? 11 : 12,
                    fontWeight: isHeader ? FontWeight.w800 : FontWeight.w700,
                    color: isHeader ? color : const Color(0xFF374151),
                  )),
    );
  }

  // ─── FAQ ─────────────────────────────────────────────────────────────────

  Widget _buildFAQ() {
    final faqs = [
      {
        'q': 'Ninaweza kubadilisha mpango wakati wowote?',
        'a': 'Ndiyo! Unaweza kupandisha au kushuka mpango wako wakati wowote. Mabadiliko yatafanywa mara moja.',
      },
      {
        'q': 'Je, kuna majaribio ya bure?',
        'a': 'Ndiyo, tunakupa siku 14 za majaribio bila malipo. Hakuna kadi ya benki inayohitajika.',
      },
      {
        'q': 'Ninaweza kulipa kwa M-Pesa?',
        'a': 'Ndiyo! Tunakubali M-Pesa, kadi za benki, na uhamisho wa benki.',
      },
      {
        'q': 'SMS zinazidi kiasi gani zinafanywa nini?',
        'a': 'SMS za ziada zinatozwa TZS 100 kila moja. Unaweza pia kupandisha mpango wako wakati wowote.',
      },
    ];

    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.04), blurRadius: 10)],
      ),
      child: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(16),
            child: Row(
              children: [
                Container(
                  padding: const EdgeInsets.all(8),
                  decoration: BoxDecoration(color: AppColors.primaryLight, borderRadius: BorderRadius.circular(10)),
                  child: const Icon(Icons.help_outline_rounded, color: AppColors.primary, size: 18),
                ),
                const SizedBox(width: 10),
                const Text('Maswali Yanayoulizwa', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 15, color: Color(0xFF111827))),
              ],
            ),
          ),
          const Divider(height: 1, color: Color(0xFFF3F4F6)),
          ...faqs.map((faq) => Theme(
                data: Theme.of(context).copyWith(dividerColor: Colors.transparent),
                child: ExpansionTile(
                  title: Text(faq['q']!,
                      style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13, color: Color(0xFF374151))),
                  iconColor: AppColors.primary,
                  collapsedIconColor: const Color(0xFF9CA3AF),
                  childrenPadding: const EdgeInsets.fromLTRB(16, 0, 16, 14),
                  children: [
                    Text(faq['a']!,
                        style: const TextStyle(color: Color(0xFF6B7280), fontSize: 13, height: 1.5)),
                  ],
                ),
              )),
        ],
      ),
    );
  }

  // ─── Subscribe Action ─────────────────────────────────────────────────────

  Future<void> _subscribe(Map<String, dynamic> plan) async {
    setState(() {
      _selectedPlan = plan['id'] as String;
      _subscribing = true;
    });

    try {
      final res = await _api.post('subscription/subscribe', body: {
        'plan': plan['id'],
        'billing_cycle': _billing,
      });

      if (mounted) {
        if (res['success'] == true) {
          _showSuccessDialog(plan);
        } else {
          Helpers.showSnackBar(context, res['message'] ?? 'Imeshindikana. Jaribu tena.');
        }
      }
    } catch (e) {
      if (mounted) Helpers.showSnackBar(context, 'Imeshindikana kuunganisha. Angalia mtandao.');
    }
    if (mounted) setState(() => _subscribing = false);
  }

  void _showSuccessDialog(Map<String, dynamic> plan) {
    final color = plan['color'] as Color;
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (_) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        contentPadding: const EdgeInsets.all(24),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(
              width: 70, height: 70,
              decoration: BoxDecoration(color: color.withValues(alpha: 0.1), shape: BoxShape.circle),
              child: Icon(Icons.check_circle_rounded, color: color, size: 40),
            ),
            const SizedBox(height: 16),
            Text('Umefanikisha!', style: TextStyle(fontWeight: FontWeight.w900, fontSize: 20, color: color)),
            const SizedBox(height: 8),
            Text(
              'Umejisajili kwa mpango wa ${plan['name']}.\nBurudani na mipango yako!',
              textAlign: TextAlign.center,
              style: const TextStyle(color: Color(0xFF6B7280), fontSize: 13, height: 1.5),
            ),
            const SizedBox(height: 20),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () { Navigator.pop(context); Navigator.pop(context); },
                style: ElevatedButton.styleFrom(
                  backgroundColor: color, foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  elevation: 0,
                ),
                child: const Text('Rudi Nyumbani', style: TextStyle(fontWeight: FontWeight.w700)),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
