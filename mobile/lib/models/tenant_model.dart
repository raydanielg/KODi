class TenantModel {
  final int id;
  final String firstName;
  final String lastName;
  final String email;
  final String phone;
  final String? gender;
  final String? avatar;
  final String? propertyTitle;
  final String? leaseStatus;
  final double? rentAmount;
  final String? leaseEnd;
  final bool hasPaidThisMonth;
  final double? outstandingBalance;
  final String? createdAt;

  TenantModel({
    required this.id,
    required this.firstName,
    required this.lastName,
    required this.email,
    required this.phone,
    this.gender,
    this.avatar,
    this.propertyTitle,
    this.leaseStatus,
    this.rentAmount,
    this.leaseEnd,
    this.hasPaidThisMonth = false,
    this.outstandingBalance,
    this.createdAt,
  });

  String get fullName => '$firstName $lastName';
  String get initials {
    final f = firstName.isNotEmpty ? firstName[0].toUpperCase() : '';
    final l = lastName.isNotEmpty ? lastName[0].toUpperCase() : '';
    return '$f$l';
  }

  factory TenantModel.fromJson(Map<String, dynamic> json) {
    final name = (json['name'] ?? '').toString();
    final parts = name.split(' ');
    return TenantModel(
      id: json['id'],
      firstName: json['first_name'] ?? (parts.isNotEmpty ? parts[0] : name),
      lastName: json['last_name'] ?? (parts.length > 1 ? parts.sublist(1).join(' ') : ''),
      email: json['email'] ?? '',
      phone: json['phone'] ?? '',
      gender: json['gender'],
      avatar: json['avatar'],
      propertyTitle: json['property'] != null ? json['property']['title'] : json['property_title'],
      leaseStatus: json['lease_status'] ?? (json['lease'] != null ? json['lease']['status'] : null),
      rentAmount: json['rent_amount'] != null ? (json['rent_amount']).toDouble() :
          (json['lease'] != null && json['lease']['rent_amount'] != null
              ? (json['lease']['rent_amount']).toDouble()
              : null),
      leaseEnd: json['lease_end'] ?? (json['lease'] != null ? json['lease']['end_date'] : null),
      hasPaidThisMonth: json['has_paid_this_month'] ?? false,
      outstandingBalance: json['outstanding_balance']?.toDouble(),
      createdAt: json['created_at'],
    );
  }

  Map<String, dynamic> toJson() => {
    'first_name': firstName,
    'last_name': lastName,
    'email': email,
    'phone': phone,
    if (gender != null) 'gender': gender,
  };
}
