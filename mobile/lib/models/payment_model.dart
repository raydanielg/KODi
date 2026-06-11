double _pd(dynamic v, [double def = 0.0]) =>
    v == null ? def : double.tryParse(v.toString()) ?? def;

class PaymentModel {
  final int id;
  final int leaseId;
  final int tenantId;
  final int landlordId;
  final int propertyId;
  final String paymentNumber;
  final double amount;
  final String status;
  final String paymentMethod;
  final String periodStart;
  final String periodEnd;
  final String? paidAt;
  final String? createdAt;
  final String? updatedAt;
  final String? tenantName;
  final String? propertyTitle;
  final String? receiptUrl;

  PaymentModel({
    required this.id, required this.leaseId, required this.tenantId,
    required this.landlordId, required this.propertyId,
    required this.paymentNumber, required this.amount, required this.status,
    required this.paymentMethod, required this.periodStart, required this.periodEnd,
    this.paidAt, this.createdAt, this.updatedAt,
    this.tenantName, this.propertyTitle, this.receiptUrl,
  });

  factory PaymentModel.fromJson(Map<String, dynamic> json) {
    return PaymentModel(
      id: json['id'], leaseId: json['lease_id'], tenantId: json['tenant_id'],
      landlordId: json['landlord_id'], propertyId: json['property_id'],
      paymentNumber: json['payment_number'] ?? '',
      amount: (json['amount'] ?? 0).toDouble(),
      status: json['status'] ?? 'pending',
      paymentMethod: json['payment_method'] ?? '',
      periodStart: json['period_start'] ?? '',
      periodEnd: json['period_end'] ?? '',
      paidAt: json['paid_at'], createdAt: json['created_at'],
      updatedAt: json['updated_at'],
      tenantName: json['tenant'] != null ? json['tenant']['name'] : null,
      propertyTitle: json['property'] != null ? json['property']['title'] : null,
      receiptUrl: json['receipt_url'],
    );
  }

  String get statusLabel {
    switch (status) {
      case 'paid': return 'Imelipwa';
      case 'pending': return 'Inasubiri';
      case 'overdue': return 'Imechelewa';
      case 'failed': return 'Imeshindwa';
      case 'refunded': return 'Imerejeshwa';
      default: return status;
    }
  }

  String get formattedAmount {
    return 'TSh ${amount.toStringAsFixed(0)}';
  }

  bool get isPaid => status == 'paid';
  bool get isPending => status == 'pending';
  bool get isOverdue => status == 'overdue';
}
