double _d(dynamic v, [double def = 0.0]) =>
    v == null ? def : double.tryParse(v.toString()) ?? def;

class LeaseModel {
  final int id;
  final int propertyId;
  final int tenantId;
  final int landlordId;
  final String leaseNumber;
  final String status;
  final String startDate;
  final String endDate;
  final double rentAmount;
  final double depositAmount;
  final String? currency;
  final String? paymentFrequency;
  final int? durationMonths;
  final String? signedAt;
  final String? terminatedAt;
  final String? createdAt;
  final String? updatedAt;
  final String? propertyTitle;
  final String? tenantName;
  final String? tenantPhone;
  final String? landlordName;
  final String? landlordPhone;

  LeaseModel({
    required this.id, required this.propertyId, required this.tenantId,
    required this.landlordId, required this.leaseNumber, required this.status,
    required this.startDate, required this.endDate,
    required this.rentAmount, required this.depositAmount,
    this.currency, this.paymentFrequency, this.durationMonths,
    this.signedAt, this.terminatedAt, this.createdAt, this.updatedAt,
    this.propertyTitle, this.tenantName, this.tenantPhone,
    this.landlordName, this.landlordPhone,
  });

  factory LeaseModel.fromJson(Map<String, dynamic> json) {
    return LeaseModel(
      id: json['id'], propertyId: json['property_id'], tenantId: json['tenant_id'],
      landlordId: json['landlord_id'], leaseNumber: json['lease_number'] ?? '',
      status: json['status'] ?? 'active',
      startDate: json['start_date'] ?? '', endDate: json['end_date'] ?? '',
      rentAmount: _d(json['rent_amount']),
      depositAmount: _d(json['deposit_amount']),
      currency: json['currency'], paymentFrequency: json['payment_frequency'],
      durationMonths: json['duration_months'],
      signedAt: json['signed_at'], terminatedAt: json['terminated_at'],
      createdAt: json['created_at'], updatedAt: json['updated_at'],
      propertyTitle: json['property'] != null ? json['property']['title'] : null,
      tenantName: json['tenant'] != null ? json['tenant']['name'] : null,
      tenantPhone: json['tenant'] != null ? json['tenant']['phone'] : null,
      landlordName: json['landlord'] != null ? json['landlord']['name'] : null,
      landlordPhone: json['landlord'] != null ? json['landlord']['phone'] : null,
    );
  }

  String get statusLabel {
    switch (status) {
      case 'active': return 'Inatumika';
      case 'expired': return 'Imeisha';
      case 'terminated': return 'Imekatishwa';
      case 'pending': return 'Inasubiri';
      default: return status;
    }
  }

  bool get isActive => status == 'active';
  bool get isExpired => status == 'expired';
}
