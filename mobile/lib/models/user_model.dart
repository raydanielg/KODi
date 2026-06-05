class UserModel {
  final int id;
  final String name;
  final String email;
  final String phone;
  final String role;
  final String? avatar;
  final String? emailVerifiedAt;
  final String? createdAt;

  UserModel({
    required this.id,
    required this.name,
    required this.email,
    required this.phone,
    required this.role,
    this.avatar,
    this.emailVerifiedAt,
    this.createdAt,
  });

  factory UserModel.fromJson(Map<String, dynamic> json) {
    return UserModel(
      id: json['id'],
      name: json['name'] ?? '',
      email: json['email'] ?? '',
      phone: json['phone'] ?? '',
      role: json['role'] ?? 'tenant',
      avatar: json['avatar'],
      emailVerifiedAt: json['email_verified_at'],
      createdAt: json['created_at'],
    );
  }

  Map<String, dynamic> toJson() => {
    'id': id,
    'name': name,
    'email': email,
    'phone': phone,
    'role': role,
    'avatar': avatar,
  };

  String get roleLabel {
    switch (role) {
      case 'super_admin': return 'Super Admin';
      case 'admin': return 'Admin';
      case 'landlord': return 'Mwenye Nyumba';
      case 'agent': return 'Wakala';
      case 'tenant': return 'Mpangaji';
      case 'support': return 'Msaidizi';
      case 'maintenance': return 'Fundi';
      case 'accountant': return 'Mhasibu';
      case 'investor': return 'Mwekezaji';
      default: return role;
    }
  }

  String get initials {
    if (name.isEmpty) return '?';
    final parts = name.split(' ');
    if (parts.length >= 2) return '${parts[0][0]}${parts[1][0]}'.toUpperCase();
    return name[0].toUpperCase();
  }
}
