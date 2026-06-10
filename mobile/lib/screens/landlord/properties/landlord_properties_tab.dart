import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
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
        final list = res['data'] is List ? res['data'] : (res['data']['data'] ?? []);
        setState(() => _properties = (list as List).map((j) => PropertyModel.fromJson(j)).toList());
      }
    } catch (_) {}
    if (mounted) setState(() => _isLoading = false);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF4F6F8),
      body: SafeArea(
        child: Column(
          children: [
            _buildHeader(context),
            Expanded(
              child: _isLoading
                  ? const Center(child: CircularProgressIndicator(color: AppColors.primary))
                  : _properties.isEmpty
                      ? _buildEmpty(context)
                      : RefreshIndicator(
                          onRefresh: _load,
                          color: AppColors.primary,
                          child: ListView.builder(
                            padding: const EdgeInsets.fromLTRB(16, 8, 16, 100),
                            itemCount: _properties.length,
                            itemBuilder: (ctx, i) => _buildPropertyCard(_properties[i]),
                          ),
                        ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildHeader(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.fromLTRB(16, 16, 16, 8),
      child: Row(
        children: [
          const Text('Properties', style: TextStyle(fontSize: 22, fontWeight: FontWeight.w800)),
          const Spacer(),
          GestureDetector(
            onTap: () => _showAddPropertySheet(context),
            child: Container(
              width: 36,
              height: 36,
              decoration: BoxDecoration(
                color: AppColors.primaryLight,
                border: Border.all(color: AppColors.primary.withOpacity(0.3)),
                borderRadius: BorderRadius.circular(10),
              ),
              child: const Icon(Icons.add, color: AppColors.primary, size: 22),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildEmpty(BuildContext context) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          const Text('No properties found.', style: TextStyle(color: Color(0xFF9CA3AF), fontSize: 15)),
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

    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(14),
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.04), blurRadius: 6)],
      ),
      child: Padding(
        padding: const EdgeInsets.all(14),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Expanded(
                  child: Text(p.title, style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 15)),
                ),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                  decoration: BoxDecoration(
                    color: statusColor.withOpacity(0.1),
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: Text(p.statusLabel, style: TextStyle(color: statusColor, fontSize: 11, fontWeight: FontWeight.w600)),
                ),
              ],
            ),
            const SizedBox(height: 6),
            Row(
              children: [
                const Icon(Icons.location_on_outlined, color: Color(0xFF9CA3AF), size: 14),
                const SizedBox(width: 4),
                Text(p.fullLocation, style: const TextStyle(color: Color(0xFF6B7280), fontSize: 12)),
              ],
            ),
            const SizedBox(height: 8),
            Row(
              children: [
                _tag(p.typeLabel, Icons.home_outlined),
                const SizedBox(width: 8),
                _tag('${p.bedrooms} bed', Icons.bed_outlined),
                const SizedBox(width: 8),
                _tag('${p.bathrooms} bath', Icons.bathtub_outlined),
                const Spacer(),
                Text(p.formattedPrice, style: const TextStyle(color: AppColors.primary, fontWeight: FontWeight.w700, fontSize: 14)),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _tag(String label, IconData icon) {
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        Icon(icon, size: 13, color: const Color(0xFF9CA3AF)),
        const SizedBox(width: 3),
        Text(label, style: const TextStyle(color: Color(0xFF6B7280), fontSize: 11)),
      ],
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

// ─── Add Property Multi-Step Bottom Sheet ──────────────────────────────────

class _AddPropertySheet extends StatefulWidget {
  const _AddPropertySheet();

  @override
  State<_AddPropertySheet> createState() => _AddPropertySheetState();
}

class _AddPropertySheetState extends State<_AddPropertySheet> {
  final LandlordService _service = LandlordService();
  int _step = 1;
  bool _saving = false;

  // Step 1 fields
  final _nameCtrl = TextEditingController();
  String? _propertyType;

  // Step 2 fields
  final _priceCtrl = TextEditingController();
  final _depositCtrl = TextEditingController();
  final _bedroomsCtrl = TextEditingController(text: '1');
  final _bathroomsCtrl = TextEditingController(text: '1');
  final _cityCtrl = TextEditingController();
  final _areaCtrl = TextEditingController();
  final _addressCtrl = TextEditingController();
  bool _hasWater = true;
  bool _hasElectricity = true;
  bool _hasInternet = false;

  final _types = ['Apartment', 'Standalone', 'Commercial building', 'Room', 'Land'];

  @override
  void dispose() {
    _nameCtrl.dispose();
    _priceCtrl.dispose();
    _depositCtrl.dispose();
    _bedroomsCtrl.dispose();
    _bathroomsCtrl.dispose();
    _cityCtrl.dispose();
    _areaCtrl.dispose();
    _addressCtrl.dispose();
    super.dispose();
  }

  String _typeValue(String label) {
    switch (label) {
      case 'Apartment': return 'apartment';
      case 'Standalone': return 'house';
      case 'Commercial building': return 'commercial';
      case 'Room': return 'room';
      case 'Land': return 'land';
      default: return 'house';
    }
  }

  Future<void> _save() async {
    if (_priceCtrl.text.isEmpty || _cityCtrl.text.isEmpty) {
      Helpers.showSnackBar(context, 'Tafadhali jaza sehemu zote zinazohitajika');
      return;
    }
    setState(() => _saving = true);
    try {
      final res = await _service.createProperty({
        'title': _nameCtrl.text.trim(),
        'property_type': _typeValue(_propertyType ?? 'Standalone'),
        'price': double.tryParse(_priceCtrl.text) ?? 0,
        'deposit': double.tryParse(_depositCtrl.text) ?? 0,
        'bedrooms': int.tryParse(_bedroomsCtrl.text) ?? 1,
        'bathrooms': int.tryParse(_bathroomsCtrl.text) ?? 1,
        'location_city': _cityCtrl.text.trim(),
        'location_area': _areaCtrl.text.trim(),
        'location_address': _addressCtrl.text.trim(),
        'has_water': _hasWater,
        'has_electricity': _hasElectricity,
        'has_internet': _hasInternet,
        'description': '',
        'currency': 'TZS',
        'status': 'available',
      });
      if (mounted) {
        if (res['success'] == true) {
          Helpers.showSnackBar(context, 'Nyumba imeongezwa!');
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
          // Handle bar
          Container(
            margin: const EdgeInsets.only(top: 10),
            width: 40,
            height: 4,
            decoration: BoxDecoration(color: const Color(0xFFE5E7EB), borderRadius: BorderRadius.circular(2)),
          ),
          // Title row
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 14, 16, 0),
            child: Row(
              children: [
                GestureDetector(
                  onTap: () => Navigator.pop(context),
                  child: Container(
                    width: 34,
                    height: 34,
                    decoration: BoxDecoration(
                      color: AppColors.primaryLight,
                      borderRadius: BorderRadius.circular(10),
                    ),
                    child: const Icon(Icons.close, color: AppColors.primary, size: 18),
                  ),
                ),
                const SizedBox(width: 12),
                const Text('Add Property', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 17)),
                const Spacer(),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                  decoration: BoxDecoration(
                    color: AppColors.primaryLight,
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: Text(
                    'Step $_step/2',
                    style: const TextStyle(color: AppColors.primary, fontSize: 12, fontWeight: FontWeight.w700),
                  ),
                ),
              ],
            ),
          ),
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 8, 16, 4),
            child: Text(
              _step == 1 ? 'Start with your property basics.' : 'Add location and amenities.',
              style: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 12),
            ),
          ),
          // Progress bar
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16),
            child: ClipRRect(
              borderRadius: BorderRadius.circular(4),
              child: LinearProgressIndicator(
                value: _step / 2,
                minHeight: 5,
                backgroundColor: const Color(0xFFE5E7EB),
                valueColor: const AlwaysStoppedAnimation<Color>(AppColors.primary),
              ),
            ),
          ),
          const SizedBox(height: 16),
          // Form content
          Flexible(
            child: SingleChildScrollView(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: _step == 1 ? _buildStep1() : _buildStep2(),
            ),
          ),
          // Buttons
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 12, 16, 20),
            child: Column(
              children: [
                SizedBox(
                  width: double.infinity,
                  height: 50,
                  child: ElevatedButton(
                    onPressed: _saving
                        ? null
                        : () {
                            if (_step == 1) {
                              if (_nameCtrl.text.isEmpty || _propertyType == null) {
                                Helpers.showSnackBar(context, 'Jaza jina na aina ya nyumba');
                                return;
                              }
                              setState(() => _step = 2);
                            } else {
                              _save();
                            }
                          },
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppColors.primary,
                      foregroundColor: Colors.white,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                      elevation: 0,
                    ),
                    child: _saving
                        ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                        : Text(_step == 1 ? 'Next' : 'Save Property',
                            style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 15)),
                  ),
                ),
                const SizedBox(height: 8),
                SizedBox(
                  width: double.infinity,
                  height: 50,
                  child: OutlinedButton(
                    onPressed: () {
                      if (_step == 2) {
                        setState(() => _step = 1);
                      } else {
                        Navigator.pop(context);
                      }
                    },
                    style: OutlinedButton.styleFrom(
                      side: const BorderSide(color: Color(0xFFE5E7EB)),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                    ),
                    child: Text(_step == 2 ? 'Back' : 'Cancel',
                        style: const TextStyle(color: Color(0xFF374151), fontWeight: FontWeight.w600)),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildStep1() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        _fieldLabel('Property Name'),
        _inputField(_nameCtrl, 'e.g. Sunset Apartments'),
        const SizedBox(height: 16),
        _fieldLabel('Property Type'),
        _dropdownField(
          value: _propertyType,
          hint: '',
          items: _types,
          onChanged: (v) => setState(() => _propertyType = v),
        ),
        const SizedBox(height: 8),
      ],
    );
  }

  Widget _buildStep2() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          children: [
            Expanded(child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                _fieldLabel('Monthly Rent (TZS)'),
                _inputField(_priceCtrl, 'e.g. 300000', keyboard: TextInputType.number),
              ],
            )),
            const SizedBox(width: 12),
            Expanded(child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                _fieldLabel('Deposit (TZS)'),
                _inputField(_depositCtrl, 'e.g. 600000', keyboard: TextInputType.number),
              ],
            )),
          ],
        ),
        const SizedBox(height: 14),
        Row(
          children: [
            Expanded(child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                _fieldLabel('Bedrooms'),
                _inputField(_bedroomsCtrl, '1', keyboard: TextInputType.number),
              ],
            )),
            const SizedBox(width: 12),
            Expanded(child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                _fieldLabel('Bathrooms'),
                _inputField(_bathroomsCtrl, '1', keyboard: TextInputType.number),
              ],
            )),
          ],
        ),
        const SizedBox(height: 14),
        _fieldLabel('City'),
        _inputField(_cityCtrl, 'e.g. Dar es Salaam'),
        const SizedBox(height: 14),
        _fieldLabel('Area / Mtaa'),
        _inputField(_areaCtrl, 'e.g. Kinondoni'),
        const SizedBox(height: 14),
        _fieldLabel('Full Address'),
        _inputField(_addressCtrl, 'e.g. Barabara ya Kawawa, Nyumba 12'),
        const SizedBox(height: 16),
        _fieldLabel('Huduma (Amenities)'),
        const SizedBox(height: 8),
        Row(
          children: [
            _toggleChip('Maji', _hasWater, (v) => setState(() => _hasWater = v)),
            const SizedBox(width: 8),
            _toggleChip('Umeme', _hasElectricity, (v) => setState(() => _hasElectricity = v)),
            const SizedBox(width: 8),
            _toggleChip('Internet', _hasInternet, (v) => setState(() => _hasInternet = v)),
          ],
        ),
        const SizedBox(height: 8),
      ],
    );
  }

  Widget _fieldLabel(String text) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 6),
      child: Text(text, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13, color: Color(0xFF374151))),
    );
  }

  Widget _inputField(TextEditingController ctrl, String hint, {TextInputType? keyboard}) {
    return TextField(
      controller: ctrl,
      keyboardType: keyboard,
      style: const TextStyle(fontSize: 14),
      decoration: InputDecoration(
        hintText: hint,
        hintStyle: const TextStyle(color: Color(0xFFD1D5DB), fontSize: 13),
        contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
        enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
        focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: AppColors.primary)),
        filled: true,
        fillColor: Colors.white,
      ),
    );
  }

  Widget _dropdownField({
    required String? value,
    required String hint,
    required List<String> items,
    required ValueChanged<String?> onChanged,
  }) {
    return DropdownButtonFormField<String>(
      value: value,
      hint: Text(hint, style: const TextStyle(color: Color(0xFFD1D5DB), fontSize: 13)),
      items: items.map((e) => DropdownMenuItem(value: e, child: Text(e, style: const TextStyle(fontSize: 14)))).toList(),
      onChanged: onChanged,
      decoration: InputDecoration(
        contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
        enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFE5E7EB))),
        focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: AppColors.primary)),
        filled: true,
        fillColor: Colors.white,
      ),
    );
  }

  Widget _toggleChip(String label, bool value, ValueChanged<bool> onChanged) {
    return GestureDetector(
      onTap: () => onChanged(!value),
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
        decoration: BoxDecoration(
          color: value ? AppColors.primaryLight : const Color(0xFFF9FAFB),
          border: Border.all(color: value ? AppColors.primary : const Color(0xFFE5E7EB)),
          borderRadius: BorderRadius.circular(8),
        ),
        child: Text(label, style: TextStyle(
          color: value ? AppColors.primary : const Color(0xFF6B7280),
          fontSize: 12,
          fontWeight: value ? FontWeight.w700 : FontWeight.w400,
        )),
      ),
    );
  }
}
