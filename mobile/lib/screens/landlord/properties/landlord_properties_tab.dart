import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import '../../../constants/app_colors.dart';
import '../../../models/property_model.dart';
import '../../../services/auth_service.dart';
import '../../../services/landlord_service.dart';
import '../../../utils/helpers.dart';

class LandlordPropertiesTab extends StatefulWidget {
  const LandlordPropertiesTab({super.key});

  @override
  State<LandlordPropertiesTab> createState() => _LandlordPropertiesTabState();
}

class _LandlordPropertiesTabState extends State<LandlordPropertiesTab> {
  final LandlordService _service  = LandlordService();
  final AuthService _auth         = AuthService();
  List<PropertyModel> _properties = [];
  bool _isLoading = true;
  String _filter  = 'all'; // all | available | rented | pending | verified

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() => _isLoading = true);
    try {
      final res = await _service.getProperties();
      if (res['data'] != null) {
        final raw  = res['data'];
        final list = raw is List ? raw : (raw['data'] ?? []);
        setState(() => _properties = (list as List).map((j) => PropertyModel.fromJson(j)).toList());
      }
    } catch (_) {}
    if (mounted) setState(() => _isLoading = false);
  }

  List<PropertyModel> get _filtered {
    switch (_filter) {
      case 'available':  return _properties.where((p) => p.status == 'available').toList();
      case 'rented':     return _properties.where((p) => p.status == 'rented').toList();
      case 'verified':   return _properties.where((p) => p.approvedAt != null).toList();
      case 'pending':    return _properties.where((p) => p.approvedAt == null).toList();
      default:           return _properties;
    }
  }

  int get _verifiedCount  => _properties.where((p) => p.approvedAt != null).length;
  int get _pendingCount   => _properties.where((p) => p.approvedAt == null).length;
  int get _availableCount => _properties.where((p) => p.status == 'available').length;
  int get _rentedCount    => _properties.where((p) => p.status == 'rented').length;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF1F5F9),
      body: SafeArea(
        child: Column(
          children: [
            _buildHeader(context),
            _buildStatsBar(),
            _buildFilterChips(),
            Expanded(
              child: _isLoading
                  ? const Center(child: CircularProgressIndicator(color: AppColors.primary))
                  : _filtered.isEmpty
                      ? _buildEmpty(context)
                      : RefreshIndicator(
                          onRefresh: _load,
                          color: AppColors.primary,
                          child: ListView.builder(
                            padding: const EdgeInsets.fromLTRB(16, 6, 16, 100),
                            itemCount: _filtered.length,
                            itemBuilder: (ctx, i) => _buildPropertyCard(ctx, _filtered[i]),
                          ),
                        ),
            ),
          ],
        ),
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () => _showAddPropertySheet(context),
        backgroundColor: AppColors.primary,
        elevation: 4,
        icon: const Icon(Icons.add_rounded, color: Colors.white),
        label: const Text('Mali Mpya', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w700)),
      ),
    );
  }

  // ─── Header ────────────────────────────────────────────────────────────────

  Widget _buildHeader(BuildContext context) {
    final user = _auth.currentUser;
    final name = user?.name ?? 'Mwenye Nyumba';

    return Container(
      color: Colors.white,
      padding: const EdgeInsets.fromLTRB(16, 14, 16, 10),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(
              gradient: const LinearGradient(colors: [AppColors.primary, AppColors.primaryDark]),
              borderRadius: BorderRadius.circular(12),
            ),
            child: const Icon(Icons.apartment_rounded, color: Colors.white, size: 20),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
              const Text('Mali Zangu',
                  style: TextStyle(fontSize: 20, fontWeight: FontWeight.w900, color: Color(0xFF0F172A))),
              Text('${_properties.length} mali kwenye portfolio ya $name',
                  style: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 11),
                  overflow: TextOverflow.ellipsis),
            ]),
          ),
          GestureDetector(
            onTap: _load,
            child: Container(
              width: 38, height: 38,
              decoration: BoxDecoration(
                color: const Color(0xFFF4F6F8), borderRadius: BorderRadius.circular(11),
                border: Border.all(color: const Color(0xFFE2E8F0)),
              ),
              child: const Icon(Icons.refresh_rounded, color: Color(0xFF64748B), size: 18),
            ),
          ),
        ],
      ),
    );
  }

  // ─── Stats Bar ─────────────────────────────────────────────────────────────

  Widget _buildStatsBar() {
    return Container(
      color: Colors.white,
      padding: const EdgeInsets.fromLTRB(16, 0, 16, 12),
      child: Row(
        children: [
          _statChip('${_properties.length}', 'Zote', const Color(0xFF64748B), const Color(0xFFF1F5F9), Icons.grid_view_rounded),
          const SizedBox(width: 8),
          _statChip('$_verifiedCount', 'Zilizoidhinishwa', const Color(0xFF10B981), const Color(0xFFDCFCE7), Icons.verified_rounded),
          const SizedBox(width: 8),
          _statChip('$_pendingCount', 'Zinasubiri', const Color(0xFFF59E0B), const Color(0xFFFEF9C3), Icons.pending_rounded),
          const SizedBox(width: 8),
          _statChip('$_rentedCount', 'Zimekodiwa', AppColors.primary, AppColors.primaryLight, Icons.key_rounded),
        ],
      ),
    );
  }

  Widget _statChip(String count, String label, Color color, Color bg, IconData icon) {
    return Expanded(
      child: GestureDetector(
        onTap: () {
          if (label == 'Zilizoidhinishwa') setState(() => _filter = 'verified');
          else if (label == 'Zinasubiri') setState(() => _filter = 'pending');
          else if (label == 'Zimekodiwa') setState(() => _filter = 'rented');
          else setState(() => _filter = 'all');
        },
        child: Container(
          padding: const EdgeInsets.symmetric(vertical: 9, horizontal: 8),
          decoration: BoxDecoration(
            color: bg, borderRadius: BorderRadius.circular(10),
            border: Border.all(color: color.withValues(alpha: 0.25)),
          ),
          child: Column(children: [
            Icon(icon, color: color, size: 14),
            const SizedBox(height: 3),
            Text(count, style: TextStyle(color: color, fontWeight: FontWeight.w900, fontSize: 16)),
            Text(label, style: const TextStyle(color: Color(0xFF64748B), fontSize: 8, fontWeight: FontWeight.w500),
                textAlign: TextAlign.center, overflow: TextOverflow.ellipsis),
          ]),
        ),
      ),
    );
  }

  // ─── Filter Chips ───────────────────────────────────────────────────────────

  Widget _buildFilterChips() {
    final filters = [
      {'key': 'all',       'label': 'Zote'},
      {'key': 'available', 'label': 'Zinapatikana'},
      {'key': 'rented',    'label': 'Zimekodiwa'},
      {'key': 'verified',  'label': 'Zilizoidhinishwa'},
      {'key': 'pending',   'label': 'Zinasubiri'},
    ];

    return Container(
      color: Colors.white,
      height: 40,
      padding: const EdgeInsets.only(bottom: 6),
      child: ListView.builder(
        scrollDirection: Axis.horizontal,
        padding: const EdgeInsets.symmetric(horizontal: 14),
        itemCount: filters.length,
        itemBuilder: (ctx, i) {
          final f   = filters[i];
          final sel = _filter == f['key'];
          return GestureDetector(
            onTap: () => setState(() => _filter = f['key']!),
            child: Container(
              margin: const EdgeInsets.only(right: 8),
              padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 5),
              decoration: BoxDecoration(
                color: sel ? AppColors.primary : const Color(0xFFF4F6F8),
                borderRadius: BorderRadius.circular(20),
                border: Border.all(color: sel ? AppColors.primary : const Color(0xFFE5E7EB)),
              ),
              child: Text(f['label']!,
                  style: TextStyle(
                    color: sel ? Colors.white : const Color(0xFF6B7280),
                    fontWeight: sel ? FontWeight.w700 : FontWeight.w500,
                    fontSize: 12,
                  )),
            ),
          );
        },
      ),
    );
  }

  // ─── Empty State ────────────────────────────────────────────────────────────

  Widget _buildEmpty(BuildContext context) {
    return Center(
      child: Column(mainAxisAlignment: MainAxisAlignment.center, children: [
        Container(padding: const EdgeInsets.all(24),
          decoration: BoxDecoration(color: AppColors.primaryLight, shape: BoxShape.circle),
          child: const Icon(Icons.apartment_outlined, color: AppColors.primary, size: 40)),
        const SizedBox(height: 16),
        const Text('Hakuna mali iliyo hapa', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 16, color: Color(0xFF374151))),
        const SizedBox(height: 6),
        const Text('Ongeza mali yako ya kwanza kuanza', style: TextStyle(color: Color(0xFF9CA3AF), fontSize: 13)),
        const SizedBox(height: 20),
        GestureDetector(
          onTap: () => _showAddPropertySheet(context),
          child: Container(
            padding: const EdgeInsets.symmetric(horizontal: 28, vertical: 13),
            decoration: BoxDecoration(
              gradient: const LinearGradient(colors: [AppColors.primary, AppColors.primaryDark]),
              borderRadius: BorderRadius.circular(14),
              boxShadow: [BoxShadow(color: AppColors.primary.withValues(alpha: 0.3), blurRadius: 10, offset: const Offset(0, 4))],
            ),
            child: const Text('Ongeza Mali', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w800, fontSize: 14)),
          ),
        ),
      ]),
    );
  }

  // ─── Property Card ──────────────────────────────────────────────────────────

  Widget _buildPropertyCard(BuildContext context, PropertyModel p) {
    final isVerified   = p.approvedAt != null;
    final isAvailable  = p.status == 'available';
    final isRented     = p.status == 'rented';

    // Status
    Color statusColor;
    Color statusBg;
    String statusLabel;
    IconData statusIcon;
    if (isRented) {
      statusColor = AppColors.primary; statusBg = AppColors.primaryLight;
      statusLabel = 'Imekodiwa'; statusIcon = Icons.key_rounded;
    } else if (isAvailable) {
      statusColor = const Color(0xFF10B981); statusBg = const Color(0xFFDCFCE7);
      statusLabel = 'Inapatikana'; statusIcon = Icons.check_circle_rounded;
    } else {
      statusColor = const Color(0xFF64748B); statusBg = const Color(0xFFF1F5F9);
      statusLabel = p.statusLabel; statusIcon = Icons.info_rounded;
    }

    // Property type icon
    IconData typeIcon;
    switch (p.propertyType) {
      case 'apartment': typeIcon = Icons.apartment_rounded; break;
      case 'room':      typeIcon = Icons.meeting_room_rounded; break;
      case 'commercial': typeIcon = Icons.store_rounded; break;
      case 'land':      typeIcon = Icons.terrain_rounded; break;
      default:          typeIcon = Icons.home_rounded;
    }

    return Container(
      margin: const EdgeInsets.only(bottom: 14),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(18),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.05), blurRadius: 12, offset: const Offset(0, 3))],
        border: Border(
          left: BorderSide(
            color: isVerified ? const Color(0xFF10B981) : const Color(0xFFF59E0B),
            width: 4,
          ),
        ),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [

          // ── Top row: icon + title + status badge
          Padding(
            padding: const EdgeInsets.fromLTRB(14, 14, 14, 0),
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Container(
                  width: 48, height: 48,
                  decoration: BoxDecoration(
                    gradient: LinearGradient(
                      colors: [AppColors.primaryLight, AppColors.primaryLight.withValues(alpha: 0.5)],
                    ),
                    borderRadius: BorderRadius.circular(13),
                    border: Border.all(color: AppColors.primary.withValues(alpha: 0.2)),
                  ),
                  child: Icon(typeIcon, color: AppColors.primary, size: 22),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                    // Property title
                    Text(p.title, style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 15, color: Color(0xFF0F172A)),
                        overflow: TextOverflow.ellipsis),
                    const SizedBox(height: 3),
                    // Location
                    Row(children: [
                      const Icon(Icons.location_on_rounded, color: Color(0xFF9CA3AF), size: 12),
                      const SizedBox(width: 3),
                      Flexible(child: Text(p.fullLocation,
                          style: const TextStyle(color: Color(0xFF64748B), fontSize: 12),
                          overflow: TextOverflow.ellipsis)),
                    ]),
                    const SizedBox(height: 5),
                    // Owner row
                    if (p.landlordName != null) _ownerRow(p.landlordName!, p.landlordPhone),
                  ]),
                ),
                const SizedBox(width: 8),
                Column(crossAxisAlignment: CrossAxisAlignment.end, children: [
                  // Status badge
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 9, vertical: 5),
                    decoration: BoxDecoration(color: statusBg, borderRadius: BorderRadius.circular(20),
                      border: Border.all(color: statusColor.withValues(alpha: 0.3))),
                    child: Row(mainAxisSize: MainAxisSize.min, children: [
                      Icon(statusIcon, size: 10, color: statusColor),
                      const SizedBox(width: 4),
                      Text(statusLabel, style: TextStyle(color: statusColor, fontSize: 10, fontWeight: FontWeight.w800)),
                    ]),
                  ),
                  const SizedBox(height: 5),
                  // Verified / Pending badge
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 9, vertical: 5),
                    decoration: BoxDecoration(
                      color: isVerified ? const Color(0xFFDCFCE7) : const Color(0xFFFEF9C3),
                      borderRadius: BorderRadius.circular(20),
                      border: Border.all(color: isVerified
                          ? const Color(0xFF10B981).withValues(alpha: 0.3)
                          : const Color(0xFFF59E0B).withValues(alpha: 0.3)),
                    ),
                    child: Row(mainAxisSize: MainAxisSize.min, children: [
                      Icon(isVerified ? Icons.verified_rounded : Icons.pending_rounded,
                          size: 10, color: isVerified ? const Color(0xFF10B981) : const Color(0xFFF59E0B)),
                      const SizedBox(width: 4),
                      Text(isVerified ? 'Imeidhinishwa' : 'Inasubiri',
                          style: TextStyle(
                            color: isVerified ? const Color(0xFF10B981) : const Color(0xFFF59E0B),
                            fontSize: 9, fontWeight: FontWeight.w800,
                          )),
                    ]),
                  ),
                ]),
              ],
            ),
          ),

          const SizedBox(height: 12),

          // ── Info chips row
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 14),
            child: Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: const Color(0xFFF8FAFC),
                borderRadius: BorderRadius.circular(12),
                border: Border.all(color: const Color(0xFFE2E8F0)),
              ),
              child: Row(
                children: [
                  _infoChip(Icons.home_rounded, p.typeLabel),
                  _infoChip(Icons.bed_rounded, '${p.bedrooms} Bed'),
                  _infoChip(Icons.bathtub_rounded, '${p.bathrooms} Bath'),
                  if (p.hasWater) _infoChip(Icons.water_drop_rounded, 'Maji'),
                  if (p.hasElectricity) _infoChip(Icons.bolt_rounded, 'Umeme'),
                  const Spacer(),
                  Column(crossAxisAlignment: CrossAxisAlignment.end, children: [
                    Text(p.formattedPrice,
                        style: const TextStyle(color: AppColors.primary, fontWeight: FontWeight.w900, fontSize: 15)),
                    const Text('/mwezi', style: TextStyle(color: Color(0xFF9CA3AF), fontSize: 10)),
                  ]),
                ],
              ),
            ),
          ),

          // ── Action buttons
          const SizedBox(height: 10),
          Padding(
            padding: const EdgeInsets.fromLTRB(14, 0, 14, 14),
            child: Row(
              children: [
                Expanded(child: _actionBtn(
                  Icons.visibility_rounded, 'Angalia',
                  const Color(0xFF3B82F6), const Color(0xFFDBEAFE),
                  () => _showPropertyDetails(context, p),
                )),
                const SizedBox(width: 8),
                Expanded(child: _actionBtn(
                  Icons.edit_rounded, 'Hariri',
                  const Color(0xFF8B5CF6), const Color(0xFFF3E8FF),
                  () => _showSuccess(context, '✏️ Hariri mali inaendelea hivi karibuni!'),
                )),
                const SizedBox(width: 8),
                Expanded(child: _actionBtn(
                  Icons.share_rounded, 'Shiriki',
                  const Color(0xFF10B981), const Color(0xFFDCFCE7),
                  () => _shareProperty(context, p),
                )),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _ownerRow(String name, String? phone) {
    return Row(children: [
      Container(width: 18, height: 18,
        decoration: BoxDecoration(
          color: AppColors.primary.withValues(alpha: 0.12),
          shape: BoxShape.circle,
        ),
        child: Center(child: Text(name.isNotEmpty ? name[0].toUpperCase() : 'L',
            style: const TextStyle(color: AppColors.primary, fontSize: 9, fontWeight: FontWeight.w900)))),
      const SizedBox(width: 5),
      Flexible(child: Text(name,
          style: const TextStyle(color: Color(0xFF374151), fontSize: 11, fontWeight: FontWeight.w700),
          overflow: TextOverflow.ellipsis)),
      if (phone != null) ...[
        const SizedBox(width: 6),
        const Icon(Icons.phone_rounded, size: 10, color: Color(0xFF9CA3AF)),
        const SizedBox(width: 2),
        Text(phone, style: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 10)),
      ],
    ]);
  }

  Widget _infoChip(IconData icon, String label) {
    return Container(
      margin: const EdgeInsets.only(right: 6),
      padding: const EdgeInsets.symmetric(horizontal: 7, vertical: 4),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(7),
        border: Border.all(color: const Color(0xFFE2E8F0)),
      ),
      child: Row(mainAxisSize: MainAxisSize.min, children: [
        Icon(icon, size: 10, color: const Color(0xFF9CA3AF)),
        const SizedBox(width: 3),
        Text(label, style: const TextStyle(color: Color(0xFF64748B), fontSize: 10, fontWeight: FontWeight.w500)),
      ]),
    );
  }

  Widget _actionBtn(IconData icon, String label, Color color, Color bg, VoidCallback onTap) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 9),
        decoration: BoxDecoration(
          color: bg, borderRadius: BorderRadius.circular(10),
          border: Border.all(color: color.withValues(alpha: 0.3)),
        ),
        child: Row(mainAxisAlignment: MainAxisAlignment.center, children: [
          Icon(icon, size: 14, color: color),
          const SizedBox(width: 5),
          Text(label, style: TextStyle(fontSize: 11, fontWeight: FontWeight.w700, color: color)),
        ]),
      ),
    );
  }

  // ─── Property Detail Bottom Sheet ──────────────────────────────────────────

  void _showPropertyDetails(BuildContext context, PropertyModel p) {
    final isVerified = p.approvedAt != null;
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (_) => DraggableScrollableSheet(
        initialChildSize: 0.85,
        maxChildSize: 0.95,
        minChildSize: 0.5,
        builder: (ctx, controller) => Container(
          decoration: const BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
          ),
          child: Column(
            children: [
              Container(margin: const EdgeInsets.only(top: 12), width: 40, height: 4,
                decoration: BoxDecoration(color: const Color(0xFFE5E7EB), borderRadius: BorderRadius.circular(2))),
              Expanded(
                child: ListView(
                  controller: controller,
                  padding: const EdgeInsets.fromLTRB(20, 16, 20, 30),
                  children: [
                    // Header
                    Row(children: [
                      Container(padding: const EdgeInsets.all(10),
                        decoration: BoxDecoration(
                          gradient: const LinearGradient(colors: [AppColors.primary, AppColors.primaryDark]),
                          borderRadius: BorderRadius.circular(12)),
                        child: const Icon(Icons.apartment_rounded, color: Colors.white, size: 20)),
                      const SizedBox(width: 12),
                      Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                        Text(p.title, style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 16, color: Color(0xFF0F172A))),
                        Text(p.fullLocation, style: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 12)),
                      ])),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                        decoration: BoxDecoration(
                          color: isVerified ? const Color(0xFFDCFCE7) : const Color(0xFFFEF9C3),
                          borderRadius: BorderRadius.circular(20),
                          border: Border.all(color: isVerified ? const Color(0xFF10B981) : const Color(0xFFF59E0B), width: 1.5),
                        ),
                        child: Row(mainAxisSize: MainAxisSize.min, children: [
                          Icon(isVerified ? Icons.verified_rounded : Icons.pending_rounded,
                              size: 13, color: isVerified ? const Color(0xFF10B981) : const Color(0xFFF59E0B)),
                          const SizedBox(width: 5),
                          Text(isVerified ? 'Imeidhinishwa' : 'Inasubiri',
                              style: TextStyle(
                                color: isVerified ? const Color(0xFF10B981) : const Color(0xFFF59E0B),
                                fontSize: 11, fontWeight: FontWeight.w800,
                              )),
                        ]),
                      ),
                    ]),

                    const SizedBox(height: 18),

                    // Rent hero
                    Container(
                      padding: const EdgeInsets.all(18),
                      decoration: BoxDecoration(
                        gradient: const LinearGradient(colors: [Color(0xFFB44040), Color(0xFF7E2B2B)]),
                        borderRadius: BorderRadius.circular(16),
                      ),
                      child: Row(children: [
                        Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                          const Text('Kodi ya Mwezi', style: TextStyle(color: Colors.white60, fontSize: 11)),
                          const SizedBox(height: 4),
                          Text(p.formattedPrice, style: const TextStyle(color: Colors.white, fontSize: 26, fontWeight: FontWeight.w900)),
                        ]),
                        const Spacer(),
                        Container(padding: const EdgeInsets.all(12),
                          decoration: BoxDecoration(color: Colors.white.withValues(alpha: 0.15), borderRadius: BorderRadius.circular(12)),
                          child: const Icon(Icons.account_balance_wallet_rounded, color: Colors.white, size: 26)),
                      ]),
                    ),

                    const SizedBox(height: 16),

                    // Owner info (prominent)
                    if (p.landlordName != null)
                      Container(
                        padding: const EdgeInsets.all(14),
                        decoration: BoxDecoration(
                          color: AppColors.primaryLight,
                          borderRadius: BorderRadius.circular(14),
                          border: Border.all(color: AppColors.primary.withValues(alpha: 0.2)),
                        ),
                        child: Row(children: [
                          Container(width: 42, height: 42,
                            decoration: BoxDecoration(
                              gradient: const LinearGradient(colors: [AppColors.primary, AppColors.primaryDark]),
                              borderRadius: BorderRadius.circular(12)),
                            child: Center(child: Text(
                              p.landlordName!.isNotEmpty ? p.landlordName![0].toUpperCase() : 'L',
                              style: const TextStyle(color: Colors.white, fontWeight: FontWeight.w900, fontSize: 18)))),
                          const SizedBox(width: 12),
                          Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                            const Text('Mwenye Mali', style: TextStyle(color: Color(0xFF9CA3AF), fontSize: 10, fontWeight: FontWeight.w600)),
                            const SizedBox(height: 2),
                            Text(p.landlordName!, style: const TextStyle(color: Color(0xFF0F172A), fontWeight: FontWeight.w800, fontSize: 14)),
                            if (p.landlordPhone != null) ...[
                              const SizedBox(height: 2),
                              Text(p.landlordPhone!, style: const TextStyle(color: Color(0xFF64748B), fontSize: 12)),
                            ],
                          ])),
                          if (p.landlordPhone != null)
                            GestureDetector(
                              onTap: () {
                                HapticFeedback.lightImpact();
                                Helpers.showSnackBar(context, 'Simu: ${p.landlordPhone}');
                              },
                              child: Container(
                                padding: const EdgeInsets.all(10),
                                decoration: BoxDecoration(
                                  color: AppColors.primary, borderRadius: BorderRadius.circular(10),
                                ),
                                child: const Icon(Icons.phone_rounded, color: Colors.white, size: 18)),
                            ),
                        ]),
                      ),

                    const SizedBox(height: 16),

                    // Details grid
                    _detailSection('Maelezo ya Mali', [
                      _detailRow(Icons.home_rounded, 'Aina', p.typeLabel),
                      _detailRow(Icons.bed_rounded, 'Vyumba vya Kulala', '${p.bedrooms}'),
                      _detailRow(Icons.bathtub_rounded, 'Bafu', '${p.bathrooms}'),
                      if (p.areaSqft != null)
                        _detailRow(Icons.square_foot_rounded, 'Ukubwa', '${p.areaSqft!.toStringAsFixed(0)} sqft'),
                      _detailRow(Icons.location_city_rounded, 'Jiji', p.locationCity),
                      _detailRow(Icons.place_rounded, 'Mtaa', p.locationArea),
                      if (p.locationAddress.isNotEmpty)
                        _detailRow(Icons.pin_drop_rounded, 'Anwani', p.locationAddress),
                    ]),

                    const SizedBox(height: 12),

                    // Amenities
                    _detailSection('Huduma Zilizopo', [
                      _boolRow(Icons.water_drop_rounded, 'Maji', p.hasWater),
                      _boolRow(Icons.bolt_rounded, 'Umeme', p.hasElectricity),
                      _boolRow(Icons.wifi_rounded, 'Internet', p.hasInternet),
                      _boolRow(Icons.local_parking_rounded, 'Parking', p.hasParking),
                      _boolRow(Icons.security_rounded, 'Ulinzi', p.hasSecurity),
                      _boolRow(Icons.power_rounded, 'Jenereta', p.hasGenerator),
                      _boolRow(Icons.weekend_rounded, 'Samani', p.isFurnished),
                    ]),

                    const SizedBox(height: 16),

                    // Deposit info
                    if (p.deposit != null)
                      Container(
                        padding: const EdgeInsets.all(14),
                        decoration: BoxDecoration(
                          color: const Color(0xFFFEF9C3),
                          borderRadius: BorderRadius.circular(12),
                          border: Border.all(color: const Color(0xFFF59E0B).withValues(alpha: 0.3)),
                        ),
                        child: Row(children: [
                          const Icon(Icons.account_balance_rounded, color: Color(0xFFF59E0B), size: 18),
                          const SizedBox(width: 10),
                          const Text('Amana inayohitajika:',
                              style: TextStyle(color: Color(0xFF64748B), fontSize: 13, fontWeight: FontWeight.w600)),
                          const Spacer(),
                          Text('TZS ${p.deposit!.toStringAsFixed(0)}',
                              style: const TextStyle(color: Color(0xFF92400E), fontWeight: FontWeight.w900, fontSize: 14)),
                        ]),
                      ),

                    // Verified date
                    if (isVerified && p.approvedAt != null) ...[
                      const SizedBox(height: 10),
                      Container(
                        padding: const EdgeInsets.all(14),
                        decoration: BoxDecoration(
                          color: const Color(0xFFDCFCE7),
                          borderRadius: BorderRadius.circular(12),
                          border: Border.all(color: const Color(0xFF10B981).withValues(alpha: 0.3)),
                        ),
                        child: Row(children: [
                          const Icon(Icons.verified_rounded, color: Color(0xFF10B981), size: 18),
                          const SizedBox(width: 10),
                          Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                            const Text('Mali Imeidhinishwa',
                                style: TextStyle(color: Color(0xFF065F46), fontWeight: FontWeight.w800, fontSize: 13)),
                            Text('Tarehe: ${p.approvedAt!.split('T').first}',
                                style: const TextStyle(color: Color(0xFF065F46), fontSize: 11)),
                          ])),
                        ]),
                      ),
                    ],
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _detailSection(String title, List<Widget> rows) {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white, borderRadius: BorderRadius.circular(14),
        border: Border.all(color: const Color(0xFFE2E8F0)),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.03), blurRadius: 6)],
      ),
      child: Column(children: [
        Padding(
          padding: const EdgeInsets.fromLTRB(14, 12, 14, 8),
          child: Row(children: [
            Text(title, style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 13, color: Color(0xFF0F172A))),
          ]),
        ),
        const Divider(height: 1, color: Color(0xFFF1F5F9)),
        ...rows,
      ]),
    );
  }

  Widget _detailRow(IconData icon, String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 11),
      child: Row(children: [
        Icon(icon, size: 16, color: const Color(0xFF9CA3AF)),
        const SizedBox(width: 10),
        Text(label, style: const TextStyle(color: Color(0xFF64748B), fontSize: 13, fontWeight: FontWeight.w600)),
        const Spacer(),
        Text(value, style: const TextStyle(color: Color(0xFF0F172A), fontWeight: FontWeight.w700, fontSize: 13)),
      ]),
    );
  }

  Widget _boolRow(IconData icon, String label, bool val) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
      child: Row(children: [
        Icon(icon, size: 15, color: val ? const Color(0xFF10B981) : const Color(0xFFD1D5DB)),
        const SizedBox(width: 10),
        Text(label, style: TextStyle(
            color: val ? const Color(0xFF374151) : const Color(0xFFD1D5DB),
            fontSize: 13, fontWeight: FontWeight.w600)),
        const Spacer(),
        Container(
          padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
          decoration: BoxDecoration(
            color: val ? const Color(0xFFDCFCE7) : const Color(0xFFF1F5F9),
            borderRadius: BorderRadius.circular(20),
          ),
          child: Text(val ? 'Inayo' : 'Haina',
              style: TextStyle(
                color: val ? const Color(0xFF10B981) : const Color(0xFF9CA3AF),
                fontSize: 10, fontWeight: FontWeight.w700,
              )),
        ),
      ]),
    );
  }

  // ─── Share Property ─────────────────────────────────────────────────────────

  void _shareProperty(BuildContext context, PropertyModel p) {
    HapticFeedback.mediumImpact();
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      builder: (_) => Container(
        padding: const EdgeInsets.all(24),
        decoration: const BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
        ),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Container(margin: const EdgeInsets.only(bottom: 16), width: 40, height: 4, alignment: Alignment.center,
              decoration: BoxDecoration(color: const Color(0xFFE5E7EB), borderRadius: BorderRadius.circular(2))),
            const Text('Shiriki Mali', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 17, color: Color(0xFF0F172A))),
            const SizedBox(height: 4),
            Text(p.title, style: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 13)),
            const SizedBox(height: 20),
            Row(children: [
              _shareBtn(Icons.link_rounded, 'Nakili Kiungo', const Color(0xFF3B82F6), const Color(0xFFDBEAFE), () {
                HapticFeedback.lightImpact();
                Navigator.pop(context);
                _showSuccess(context, '🔗 Kiungo kimenakilishwa!');
              }),
              const SizedBox(width: 12),
              _shareBtn(Icons.sms_rounded, 'Tuma SMS', AppColors.primary, AppColors.primaryLight, () {
                HapticFeedback.lightImpact();
                Navigator.pop(context);
                _showSuccess(context, '📱 Inatuma SMS...');
              }),
              const SizedBox(width: 12),
              _shareBtn(Icons.share_rounded, 'Shiriki', const Color(0xFF10B981), const Color(0xFFDCFCE7), () {
                HapticFeedback.lightImpact();
                Navigator.pop(context);
                _showSuccess(context, '✓ Kiungo kimetumwa!');
              }),
            ]),
            const SizedBox(height: 8),
          ],
        ),
      ),
    );
  }

  Widget _shareBtn(IconData icon, String label, Color color, Color bg, VoidCallback onTap) {
    return Expanded(
      child: GestureDetector(
        onTap: onTap,
        child: Container(
          padding: const EdgeInsets.symmetric(vertical: 14),
          decoration: BoxDecoration(
            color: bg, borderRadius: BorderRadius.circular(12),
            border: Border.all(color: color.withValues(alpha: 0.3)),
          ),
          child: Column(children: [
            Icon(icon, color: color, size: 22),
            const SizedBox(height: 6),
            Text(label, style: TextStyle(color: color, fontWeight: FontWeight.w700, fontSize: 11), textAlign: TextAlign.center),
          ]),
        ),
      ),
    );
  }

  void _showSuccess(BuildContext context, String msg) {
    HapticFeedback.lightImpact();
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Row(children: [
          const Icon(Icons.check_circle_rounded, color: Colors.white, size: 18),
          const SizedBox(width: 10),
          Text(msg, style: const TextStyle(fontWeight: FontWeight.w600)),
        ]),
        backgroundColor: const Color(0xFF10B981),
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        margin: const EdgeInsets.all(16),
        duration: const Duration(seconds: 2),
      ),
    );
  }

  void _showAddPropertySheet(BuildContext context) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (_) => const _AddPropertySheet(),
    ).then((_) => _load());
  }
}

// ─── Add Property Sheet ───────────────────────────────────────────────────────

class _AddPropertySheet extends StatefulWidget {
  const _AddPropertySheet();
  @override
  State<_AddPropertySheet> createState() => _AddPropertySheetState();
}

class _AddPropertySheetState extends State<_AddPropertySheet> {
  final LandlordService _service = LandlordService();
  int _step = 1;
  bool _saving = false;

  final _nameCtrl    = TextEditingController();
  String? _type;
  final _priceCtrl   = TextEditingController();
  final _depositCtrl = TextEditingController();
  final _bedsCtrl    = TextEditingController(text: '1');
  final _bathsCtrl   = TextEditingController(text: '1');
  final _cityCtrl    = TextEditingController();
  final _areaCtrl    = TextEditingController();
  final _addressCtrl = TextEditingController();
  bool _hasWater = true, _hasElectricity = true, _hasInternet = false, _hasParking = false;

  static const _types = ['Apartment', 'Standalone', 'Commercial', 'Room', 'Land'];

  String _typeVal(String label) {
    switch (label) {
      case 'Apartment':   return 'apartment';
      case 'Commercial':  return 'commercial';
      case 'Room':        return 'room';
      case 'Land':        return 'land';
      default:            return 'house';
    }
  }

  @override
  void dispose() {
    _nameCtrl.dispose(); _priceCtrl.dispose(); _depositCtrl.dispose();
    _bedsCtrl.dispose(); _bathsCtrl.dispose(); _cityCtrl.dispose();
    _areaCtrl.dispose(); _addressCtrl.dispose();
    super.dispose();
  }

  Future<void> _save() async {
    if (_priceCtrl.text.isEmpty || _cityCtrl.text.isEmpty) {
      Helpers.showSnackBar(context, 'Jaza kodi na jiji la mali');
      return;
    }
    setState(() => _saving = true);
    try {
      final res = await _service.createProperty({
        'title':            _nameCtrl.text.trim(),
        'property_type':    _typeVal(_type ?? 'Standalone'),
        'price':            double.tryParse(_priceCtrl.text) ?? 0,
        'deposit':          double.tryParse(_depositCtrl.text) ?? 0,
        'bedrooms':         int.tryParse(_bedsCtrl.text) ?? 1,
        'bathrooms':        int.tryParse(_bathsCtrl.text) ?? 1,
        'location_city':    _cityCtrl.text.trim(),
        'location_area':    _areaCtrl.text.trim(),
        'location_address': _addressCtrl.text.trim(),
        'has_water':        _hasWater,
        'has_electricity':  _hasElectricity,
        'has_internet':     _hasInternet,
        'has_parking':      _hasParking,
        'description':      '',
        'currency':         'TZS',
        'status':           'available',
      });
      if (mounted) {
        if (res['success'] == true) {
          HapticFeedback.mediumImpact();
          _showDoneDialog();
        } else {
          Helpers.showSnackBar(context, res['message'] ?? 'Imeshindikana');
        }
      }
    } catch (e) {
      if (mounted) Helpers.showSnackBar(context, e.toString());
    }
    if (mounted) setState(() => _saving = false);
  }

  void _showDoneDialog() {
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (_) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        content: Column(mainAxisSize: MainAxisSize.min, children: [
          Container(
            width: 70, height: 70,
            decoration: BoxDecoration(
              gradient: const LinearGradient(colors: [Color(0xFF10B981), Color(0xFF047857)]),
              shape: BoxShape.circle,
            ),
            child: const Icon(Icons.check_rounded, color: Colors.white, size: 38),
          ),
          const SizedBox(height: 16),
          const Text('Mali Imeongezwa!', style: TextStyle(fontWeight: FontWeight.w900, fontSize: 18, color: Color(0xFF0F172A))),
          const SizedBox(height: 6),
          const Text('Mali yako imewasilishwa na inasubiri idhini ya msimamizi.',
              style: TextStyle(color: Color(0xFF64748B), fontSize: 12), textAlign: TextAlign.center),
          const SizedBox(height: 20),
          SizedBox(
            width: double.infinity,
            child: ElevatedButton(
              onPressed: () { Navigator.pop(context); Navigator.pop(context); },
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFF10B981), foregroundColor: Colors.white,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)), elevation: 0,
                padding: const EdgeInsets.symmetric(vertical: 14),
              ),
              child: const Text('Vizuri!', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 15)),
            ),
          ),
        ]),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      padding: EdgeInsets.only(bottom: MediaQuery.of(context).viewInsets.bottom),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          // Handle
          Container(margin: const EdgeInsets.only(top: 12), width: 40, height: 4,
            decoration: BoxDecoration(color: const Color(0xFFE5E7EB), borderRadius: BorderRadius.circular(2))),

          // Header
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 14, 16, 0),
            child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
              Row(children: [
                Container(padding: const EdgeInsets.all(8),
                  decoration: BoxDecoration(
                    gradient: const LinearGradient(colors: [AppColors.primary, AppColors.primaryDark]),
                    borderRadius: BorderRadius.circular(10)),
                  child: const Icon(Icons.add_home_rounded, color: Colors.white, size: 18)),
                const SizedBox(width: 12),
                const Text('Ongeza Mali Mpya', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 17, color: Color(0xFF0F172A))),
                const Spacer(),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                  decoration: BoxDecoration(color: AppColors.primaryLight, borderRadius: BorderRadius.circular(20)),
                  child: Text('Hatua $_step/2', style: const TextStyle(color: AppColors.primary, fontSize: 12, fontWeight: FontWeight.w700)),
                ),
              ]),
              const SizedBox(height: 10),
              Text(_step == 1 ? 'Jaza jina na aina ya mali' : 'Weka bei, mahali na huduma',
                  style: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 12)),
              const SizedBox(height: 8),
              ClipRRect(
                borderRadius: BorderRadius.circular(4),
                child: LinearProgressIndicator(
                  value: _step / 2, minHeight: 5,
                  backgroundColor: const Color(0xFFE5E7EB),
                  valueColor: const AlwaysStoppedAnimation<Color>(AppColors.primary),
                ),
              ),
              const SizedBox(height: 14),
            ]),
          ),

          Flexible(
            child: SingleChildScrollView(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: _step == 1 ? _buildStep1() : _buildStep2(),
            ),
          ),

          // Buttons
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 8, 16, 20),
            child: Row(children: [
              if (_step == 2)
                Expanded(child: OutlinedButton(
                  onPressed: () => setState(() => _step = 1),
                  style: OutlinedButton.styleFrom(side: const BorderSide(color: Color(0xFFE5E7EB)),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(13)),
                    minimumSize: const Size(0, 50)),
                  child: const Text('Nyuma', style: TextStyle(color: Color(0xFF374151), fontWeight: FontWeight.w600)),
                )),
              if (_step == 2) const SizedBox(width: 12),
              Expanded(
                flex: 2,
                child: ElevatedButton(
                  onPressed: _saving ? null : () {
                    if (_step == 1) {
                      if (_nameCtrl.text.isEmpty || _type == null) {
                        Helpers.showSnackBar(context, 'Jaza jina na aina ya mali');
                        return;
                      }
                      HapticFeedback.lightImpact();
                      setState(() => _step = 2);
                    } else { _save(); }
                  },
                  style: ElevatedButton.styleFrom(
                    backgroundColor: AppColors.primary, foregroundColor: Colors.white,
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(13)),
                    elevation: 0, minimumSize: const Size(0, 50),
                  ),
                  child: _saving
                      ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                      : Text(_step == 1 ? 'Endelea' : 'Hifadhi Mali',
                          style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 15)),
                ),
              ),
            ]),
          ),
        ],
      ),
    );
  }

  Widget _buildStep1() {
    return Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
      _label('Jina la Mali'),
      _field(_nameCtrl, 'e.g. Sunset Apartments, Kinondoni'),
      const SizedBox(height: 14),
      _label('Aina ya Mali'),
      _dropdown(value: _type, items: _types, onChanged: (v) => setState(() => _type = v)),
      const SizedBox(height: 8),
    ]);
  }

  Widget _buildStep2() {
    return Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
      Row(children: [
        Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
          _label('Kodi ya Mwezi (TZS) *'),
          _field(_priceCtrl, '300,000', keyboard: TextInputType.number),
        ])),
        const SizedBox(width: 12),
        Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
          _label('Amana (TZS)'),
          _field(_depositCtrl, '600,000', keyboard: TextInputType.number),
        ])),
      ]),
      const SizedBox(height: 14),
      Row(children: [
        Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
          _label('Vyumba vya Kulala'),
          _field(_bedsCtrl, '1', keyboard: TextInputType.number),
        ])),
        const SizedBox(width: 12),
        Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
          _label('Bafu'),
          _field(_bathsCtrl, '1', keyboard: TextInputType.number),
        ])),
      ]),
      const SizedBox(height: 14),
      _label('Jiji *'),
      _field(_cityCtrl, 'e.g. Dar es Salaam'),
      const SizedBox(height: 14),
      _label('Mtaa / Eneo'),
      _field(_areaCtrl, 'e.g. Kinondoni'),
      const SizedBox(height: 14),
      _label('Anwani Kamili'),
      _field(_addressCtrl, 'e.g. Barabara ya Kawawa No.12'),
      const SizedBox(height: 16),
      _label('Huduma Zilizopo'),
      const SizedBox(height: 10),
      Wrap(
        spacing: 8, runSpacing: 8,
        children: [
          _amenityChip('Maji',     Icons.water_drop_rounded,    _hasWater,       (v) => setState(() => _hasWater = v)),
          _amenityChip('Umeme',    Icons.bolt_rounded,          _hasElectricity, (v) => setState(() => _hasElectricity = v)),
          _amenityChip('Internet', Icons.wifi_rounded,          _hasInternet,    (v) => setState(() => _hasInternet = v)),
          _amenityChip('Parking',  Icons.local_parking_rounded, _hasParking,     (v) => setState(() => _hasParking = v)),
        ],
      ),
      const SizedBox(height: 8),
    ]);
  }

  Widget _amenityChip(String label, IconData icon, bool val, ValueChanged<bool> onChange) {
    return GestureDetector(
      onTap: () { HapticFeedback.selectionClick(); onChange(!val); },
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 150),
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 9),
        decoration: BoxDecoration(
          color: val ? AppColors.primaryLight : const Color(0xFFF4F6F8),
          border: Border.all(color: val ? AppColors.primary : const Color(0xFFE5E7EB), width: val ? 1.5 : 1),
          borderRadius: BorderRadius.circular(10),
        ),
        child: Row(mainAxisSize: MainAxisSize.min, children: [
          Icon(icon, size: 14, color: val ? AppColors.primary : const Color(0xFF9CA3AF)),
          const SizedBox(width: 5),
          Text(label, style: TextStyle(color: val ? AppColors.primary : const Color(0xFF6B7280),
              fontSize: 12, fontWeight: val ? FontWeight.w700 : FontWeight.w400)),
          if (val) ...[
            const SizedBox(width: 5),
            const Icon(Icons.check_circle_rounded, size: 13, color: AppColors.primary),
          ],
        ]),
      ),
    );
  }
}

// ─── Shared helpers ──────────────────────────────────────────────────────────

Widget _label(String text) => Padding(
  padding: const EdgeInsets.only(bottom: 6),
  child: Text(text, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13, color: Color(0xFF374151))),
);

Widget _field(TextEditingController ctrl, String hint, {TextInputType? keyboard}) {
  return TextField(
    controller: ctrl, keyboardType: keyboard,
    style: const TextStyle(fontSize: 14, color: Color(0xFF111827)),
    decoration: InputDecoration(
      hintText: hint, hintStyle: const TextStyle(color: Color(0xFFD1D5DB), fontSize: 13),
      contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 13),
      border: OutlineInputBorder(borderRadius: BorderRadius.circular(11), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
      enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(11), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
      focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(11), borderSide: const BorderSide(color: AppColors.primary, width: 1.5)),
      filled: true, fillColor: Colors.white,
    ),
  );
}

Widget _dropdown({required String? value, required List<String> items, required ValueChanged<String?> onChanged}) {
  return DropdownButtonFormField<String>(
    value: value,
    hint: const Text('Chagua aina', style: TextStyle(color: Color(0xFFD1D5DB), fontSize: 13)),
    items: items.map((e) => DropdownMenuItem(value: e, child: Text(e, style: const TextStyle(fontSize: 14, color: Color(0xFF111827))))).toList(),
    onChanged: onChanged,
    dropdownColor: Colors.white,
    decoration: InputDecoration(
      contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 13),
      border: OutlineInputBorder(borderRadius: BorderRadius.circular(11), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
      enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(11), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
      focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(11), borderSide: const BorderSide(color: AppColors.primary, width: 1.5)),
      filled: true, fillColor: Colors.white,
    ),
  );
}
