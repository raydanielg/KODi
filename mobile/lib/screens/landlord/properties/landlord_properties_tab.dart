import 'package:flutter/material.dart';
import '../../../constants/app_colors.dart';
import '../../../models/property_model.dart';
import '../../../services/landlord_service.dart';
import '../../../utils/helpers.dart';

class LandlordPropertiesTab extends StatefulWidget {
  const LandlordPropertiesTab({super.key});

  @override
  State<LandlordPropertiesTab> createState() => _LandlordPropertiesTabState();
}

class _LandlordPropertiesTabState extends State<LandlordPropertiesTab> {
  final LandlordService _service = LandlordService();
  List<PropertyModel> _properties = [];
  bool _isLoading = true;
  String _filter = 'all'; // all | available | rented

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
        final raw = res['data'];
        final list = raw is List ? raw : (raw['data'] ?? []);
        setState(() => _properties = (list as List).map((j) => PropertyModel.fromJson(j)).toList());
      }
    } catch (_) {}
    if (mounted) setState(() => _isLoading = false);
  }

  List<PropertyModel> get _filtered {
    if (_filter == 'all') return _properties;
    return _properties.where((p) => p.status == _filter).toList();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF4F6F8),
      body: SafeArea(
        child: Column(
          children: [
            _buildHeader(context),
            _buildFilterBar(),
            Expanded(
              child: _isLoading
                  ? const Center(child: CircularProgressIndicator(color: AppColors.primary))
                  : _filtered.isEmpty
                      ? _buildEmpty(context)
                      : RefreshIndicator(
                          onRefresh: _load,
                          color: AppColors.primary,
                          child: ListView.builder(
                            padding: const EdgeInsets.fromLTRB(16, 8, 16, 100),
                            itemCount: _filtered.length,
                            itemBuilder: (ctx, i) => _buildPropertyCard(_filtered[i]),
                          ),
                        ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildHeader(BuildContext context) {
    return Container(
      color: Colors.white,
      padding: const EdgeInsets.fromLTRB(16, 14, 16, 14),
      child: Row(
        children: [
          const Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text('Properties',
                  style: TextStyle(fontSize: 22, fontWeight: FontWeight.w800, color: Color(0xFF111827))),
              Text('Manage your portfolio',
                  style: TextStyle(color: Color(0xFF9CA3AF), fontSize: 12)),
            ],
          ),
          const Spacer(),
          GestureDetector(
            onTap: () => _showAddPropertySheet(context),
            child: Container(
              padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 9),
              decoration: BoxDecoration(
                color: AppColors.primary,
                borderRadius: BorderRadius.circular(12),
                boxShadow: [
                  BoxShadow(color: AppColors.primary.withValues(alpha: 0.3), blurRadius: 8, offset: const Offset(0, 3)),
                ],
              ),
              child: const Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Icon(Icons.add_rounded, color: Colors.white, size: 18),
                  SizedBox(width: 4),
                  Text('Add', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w700, fontSize: 13)),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildFilterBar() {
    final filters = [
      {'key': 'all', 'label': 'All (${_properties.length})'},
      {'key': 'available', 'label': 'Available'},
      {'key': 'rented', 'label': 'Rented'},
    ];
    return Container(
      color: Colors.white,
      padding: const EdgeInsets.fromLTRB(16, 0, 16, 12),
      child: Row(
        children: filters.map((f) {
          final sel = _filter == f['key'];
          return GestureDetector(
            onTap: () => setState(() => _filter = f['key']!),
            child: Container(
              margin: const EdgeInsets.only(right: 8),
              padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 7),
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
        }).toList(),
      ),
    );
  }

  Widget _buildEmpty(BuildContext context) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Container(
            padding: const EdgeInsets.all(20),
            decoration: BoxDecoration(color: AppColors.primaryLight, shape: BoxShape.circle),
            child: const Icon(Icons.apartment_outlined, color: AppColors.primary, size: 40),
          ),
          const SizedBox(height: 16),
          const Text('No properties found.',
              style: TextStyle(fontWeight: FontWeight.w700, fontSize: 16, color: Color(0xFF374151))),
          const SizedBox(height: 6),
          const Text('Add your first property to get started',
              style: TextStyle(color: Color(0xFF9CA3AF), fontSize: 13)),
          const SizedBox(height: 20),
          GestureDetector(
            onTap: () => _showAddPropertySheet(context),
            child: Container(
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
              decoration: BoxDecoration(color: AppColors.primary, borderRadius: BorderRadius.circular(12)),
              child: const Text('Add Property', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w700)),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildPropertyCard(PropertyModel p) {
    final statusColor = p.status == 'available'
        ? const Color(0xFF10B981)
        : p.status == 'rented'
            ? AppColors.primary
            : const Color(0xFFF59E0B);
    final statusBg = p.status == 'available'
        ? const Color(0xFFF0FDF4)
        : p.status == 'rented'
            ? AppColors.primaryLight
            : const Color(0xFFFFFBEB);

    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.05), blurRadius: 10, offset: const Offset(0, 2))],
      ),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Container(
                  padding: const EdgeInsets.all(10),
                  decoration: BoxDecoration(
                    color: AppColors.primaryLight,
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: const Icon(Icons.apartment_rounded, color: AppColors.primary, size: 20),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(p.title,
                          style: const TextStyle(
                            fontWeight: FontWeight.w800,
                            fontSize: 15,
                            color: Color(0xFF111827),
                          )),
                      const SizedBox(height: 3),
                      Row(
                        children: [
                          const Icon(Icons.location_on_rounded, color: Color(0xFF9CA3AF), size: 13),
                          const SizedBox(width: 3),
                          Text(p.fullLocation,
                              style: const TextStyle(color: Color(0xFF6B7280), fontSize: 12)),
                        ],
                      ),
                    ],
                  ),
                ),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                  decoration: BoxDecoration(color: statusBg, borderRadius: BorderRadius.circular(20)),
                  child: Text(p.statusLabel,
                      style: TextStyle(color: statusColor, fontSize: 11, fontWeight: FontWeight.w700)),
                ),
              ],
            ),
            const SizedBox(height: 12),
            const Divider(height: 1, color: Color(0xFFF3F4F6)),
            const SizedBox(height: 12),
            Row(
              children: [
                _propTag(p.typeLabel, Icons.home_outlined),
                _propTag('${p.bedrooms} Bed', Icons.bed_outlined),
                _propTag('${p.bathrooms} Bath', Icons.bathtub_outlined),
                if (p.hasWater) _propTag('Maji', Icons.water_drop_rounded),
                if (p.hasElectricity) _propTag('Umeme', Icons.bolt_rounded),
                const Spacer(),
                Column(
                  crossAxisAlignment: CrossAxisAlignment.end,
                  children: [
                    Text(p.formattedPrice,
                        style: const TextStyle(color: AppColors.primary, fontWeight: FontWeight.w900, fontSize: 15)),
                    const Text('/mwezi', style: TextStyle(color: Color(0xFF9CA3AF), fontSize: 10)),
                  ],
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _propTag(String label, IconData icon) {
    return Container(
      margin: const EdgeInsets.only(right: 6),
      padding: const EdgeInsets.symmetric(horizontal: 7, vertical: 4),
      decoration: BoxDecoration(
        color: const Color(0xFFF4F6F8),
        borderRadius: BorderRadius.circular(8),
        border: Border.all(color: const Color(0xFFE5E7EB)),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, size: 11, color: const Color(0xFF9CA3AF)),
          const SizedBox(width: 3),
          Text(label, style: const TextStyle(color: Color(0xFF6B7280), fontSize: 10, fontWeight: FontWeight.w500)),
        ],
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

  final _nameCtrl = TextEditingController();
  String? _type;
  final _priceCtrl = TextEditingController();
  final _depositCtrl = TextEditingController();
  final _bedsCtrl = TextEditingController(text: '1');
  final _bathsCtrl = TextEditingController(text: '1');
  final _cityCtrl = TextEditingController();
  final _areaCtrl = TextEditingController();
  final _addressCtrl = TextEditingController();
  bool _hasWater = true, _hasElectricity = true, _hasInternet = false, _hasParking = false;

  static const _types = ['Apartment', 'Standalone', 'Commercial building', 'Room', 'Land'];

  String _typeVal(String label) {
    switch (label) {
      case 'Apartment': return 'apartment';
      case 'Commercial building': return 'commercial';
      case 'Room': return 'room';
      case 'Land': return 'land';
      default: return 'house';
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
      Helpers.showSnackBar(context, 'Jaza sehemu zinazohitajika (Kodi, Jiji)');
      return;
    }
    setState(() => _saving = true);
    try {
      final res = await _service.createProperty({
        'title': _nameCtrl.text.trim(),
        'property_type': _typeVal(_type ?? 'Standalone'),
        'price': double.tryParse(_priceCtrl.text) ?? 0,
        'deposit': double.tryParse(_depositCtrl.text) ?? 0,
        'bedrooms': int.tryParse(_bedsCtrl.text) ?? 1,
        'bathrooms': int.tryParse(_bathsCtrl.text) ?? 1,
        'location_city': _cityCtrl.text.trim(),
        'location_area': _areaCtrl.text.trim(),
        'location_address': _addressCtrl.text.trim(),
        'has_water': _hasWater, 'has_electricity': _hasElectricity,
        'has_internet': _hasInternet, 'has_parking': _hasParking,
        'description': '', 'currency': 'TZS', 'status': 'available',
      });
      if (mounted) {
        if (res['success'] == true) {
          Helpers.showSnackBar(context, '✓ Nyumba imeongezwa!');
          Navigator.pop(context);
        } else {
          Helpers.showSnackBar(context, res['message'] ?? 'Imeshindikana');
        }
      }
    } catch (e) {
      if (mounted) Helpers.showSnackBar(context, e.toString());
    }
    if (mounted) setState(() => _saving = false);
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
          _sheetHandle(),
          _sheetHeader('Add Property', _step),
          Flexible(
            child: SingleChildScrollView(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: _step == 1 ? _buildStep1() : _buildStep2(),
            ),
          ),
          _sheetButtons(
            onNext: () {
              if (_step == 1) {
                if (_nameCtrl.text.isEmpty || _type == null) {
                  Helpers.showSnackBar(context, 'Jaza jina na aina ya nyumba');
                  return;
                }
                setState(() => _step = 2);
              } else {
                _save();
              }
            },
            onBack: () => _step == 2 ? setState(() => _step = 1) : Navigator.pop(context),
            nextLabel: _step == 1 ? 'Next' : 'Save Property',
            backLabel: _step == 2 ? 'Back' : 'Cancel',
            saving: _saving,
          ),
        ],
      ),
    );
  }

  Widget _buildStep1() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        _label('Property Name'),
        _field(_nameCtrl, 'e.g. Sunset Apartments'),
        const SizedBox(height: 14),
        _label('Property Type'),
        _dropdown(value: _type, items: _types, onChanged: (v) => setState(() => _type = v)),
        const SizedBox(height: 8),
      ],
    );
  }

  Widget _buildStep2() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
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
            _label('Vyumba vya kulala'),
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
          spacing: 8,
          runSpacing: 8,
          children: [
            _amenityChip('Maji', Icons.water_drop_rounded, _hasWater, (v) => setState(() => _hasWater = v)),
            _amenityChip('Umeme', Icons.bolt_rounded, _hasElectricity, (v) => setState(() => _hasElectricity = v)),
            _amenityChip('Internet', Icons.wifi_rounded, _hasInternet, (v) => setState(() => _hasInternet = v)),
            _amenityChip('Parking', Icons.local_parking_rounded, _hasParking, (v) => setState(() => _hasParking = v)),
          ],
        ),
        const SizedBox(height: 8),
      ],
    );
  }

  Widget _amenityChip(String label, IconData icon, bool val, ValueChanged<bool> onChange) {
    return GestureDetector(
      onTap: () => onChange(!val),
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 150),
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
        decoration: BoxDecoration(
          color: val ? AppColors.primaryLight : const Color(0xFFF4F6F8),
          border: Border.all(color: val ? AppColors.primary : const Color(0xFFE5E7EB), width: val ? 1.5 : 1),
          borderRadius: BorderRadius.circular(10),
        ),
        child: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(icon, size: 14, color: val ? AppColors.primary : const Color(0xFF9CA3AF)),
            const SizedBox(width: 5),
            Text(label,
                style: TextStyle(
                  color: val ? AppColors.primary : const Color(0xFF6B7280),
                  fontSize: 12,
                  fontWeight: val ? FontWeight.w700 : FontWeight.w400,
                )),
            if (val) ...[
              const SizedBox(width: 4),
              const Icon(Icons.check_rounded, size: 12, color: AppColors.primary),
            ],
          ],
        ),
      ),
    );
  }
}

// ─── Shared Sheet Widgets ─────────────────────────────────────────────────────

Widget _sheetHandle() {
  return Container(
    margin: const EdgeInsets.only(top: 10),
    width: 40, height: 4,
    decoration: BoxDecoration(color: const Color(0xFFE5E7EB), borderRadius: BorderRadius.circular(2)),
  );
}

Widget _sheetHeader(String title, int step) {
  return Padding(
    padding: const EdgeInsets.fromLTRB(16, 14, 16, 0),
    child: Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          children: [
            Text(title, style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 18, color: Color(0xFF111827))),
            const Spacer(),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 5),
              decoration: BoxDecoration(color: AppColors.primaryLight, borderRadius: BorderRadius.circular(20)),
              child: Text('Step $step/2', style: const TextStyle(color: AppColors.primary, fontSize: 12, fontWeight: FontWeight.w700)),
            ),
          ],
        ),
        const SizedBox(height: 8),
        ClipRRect(
          borderRadius: BorderRadius.circular(4),
          child: LinearProgressIndicator(
            value: step / 2,
            minHeight: 4,
            backgroundColor: const Color(0xFFE5E7EB),
            valueColor: const AlwaysStoppedAnimation<Color>(AppColors.primary),
          ),
        ),
        const SizedBox(height: 14),
      ],
    ),
  );
}

Widget _sheetButtons({
  required VoidCallback onNext,
  required VoidCallback onBack,
  required String nextLabel,
  required String backLabel,
  required bool saving,
}) {
  return Padding(
    padding: const EdgeInsets.fromLTRB(16, 8, 16, 20),
    child: Column(
      children: [
        SizedBox(
          width: double.infinity, height: 50,
          child: ElevatedButton(
            onPressed: saving ? null : onNext,
            style: ElevatedButton.styleFrom(
              backgroundColor: AppColors.primary, foregroundColor: Colors.white,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(13)), elevation: 0,
            ),
            child: saving
                ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                : Text(nextLabel, style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 15)),
          ),
        ),
        const SizedBox(height: 8),
        SizedBox(
          width: double.infinity, height: 50,
          child: OutlinedButton(
            onPressed: onBack,
            style: OutlinedButton.styleFrom(
              side: const BorderSide(color: Color(0xFFE5E7EB)),
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(13)),
            ),
            child: Text(backLabel, style: const TextStyle(color: Color(0xFF374151), fontWeight: FontWeight.w600, fontSize: 15)),
          ),
        ),
      ],
    ),
  );
}

Widget _label(String text) {
  return Padding(
    padding: const EdgeInsets.only(bottom: 6),
    child: Text(text, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13, color: Color(0xFF374151))),
  );
}

Widget _field(TextEditingController ctrl, String hint, {TextInputType? keyboard}) {
  return TextField(
    controller: ctrl, keyboardType: keyboard,
    style: const TextStyle(fontSize: 14, color: Color(0xFF111827)),
    decoration: InputDecoration(
      hintText: hint,
      hintStyle: const TextStyle(color: Color(0xFFD1D5DB), fontSize: 13),
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
