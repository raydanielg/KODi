double _d(dynamic v, [double def = 0.0]) =>
    v == null ? def : double.tryParse(v.toString()) ?? def;

class PropertyModel {
  final int id;
  final int userId;
  final int? agentId;
  final String title;
  final String slug;
  final String description;
  final String propertyType;
  final String status;
  final double price;
  final String currency;
  final double? deposit;
  final int bedrooms;
  final int bathrooms;
  final double? areaSqft;
  final String locationArea;
  final String locationCity;
  final String locationRegion;
  final String locationAddress;
  final bool isFurnished;
  final bool hasInternet;
  final bool hasWater;
  final bool hasElectricity;
  final bool hasParking;
  final bool hasSecurity;
  final bool hasGenerator;
  final bool isFeatured;
  final int viewsCount;
  final String? approvedAt;
  final String? createdAt;
  final String? updatedAt;
  final String? landlordName;
  final String? landlordPhone;
  final String? mainImage;
  final List<String> images;
  final List<String> amenities;

  PropertyModel({
    required this.id, required this.userId, this.agentId,
    required this.title, required this.slug, required this.description,
    required this.propertyType, required this.status,
    required this.price, required this.currency, this.deposit,
    required this.bedrooms, required this.bathrooms, this.areaSqft,
    required this.locationArea, required this.locationCity,
    required this.locationRegion, required this.locationAddress,
    this.isFurnished = false, this.hasInternet = false,
    this.hasWater = true, this.hasElectricity = true,
    this.hasParking = false, this.hasSecurity = false,
    this.hasGenerator = false, this.isFeatured = false,
    this.viewsCount = 0, this.approvedAt, this.createdAt, this.updatedAt,
    this.landlordName, this.landlordPhone, this.mainImage,
    this.images = const [], this.amenities = const [],
  });

  factory PropertyModel.fromJson(Map<String, dynamic> json) {
    // Helper to parse numbers from strings or numbers
    double parseDouble(dynamic value) {
      if (value == null) return 0.0;
      if (value is double) return value;
      if (value is int) return value.toDouble();
      if (value is String) return double.tryParse(value) ?? 0.0;
      return 0.0;
    }

    return PropertyModel(
      id: json['id'], userId: json['user_id'], agentId: json['agent_id'],
      title: json['title'] ?? '', slug: json['slug'] ?? '',
      description: json['description'] ?? '',
      propertyType: json['property_type'] ?? 'house',
      status: json['status'] ?? 'available',
      price: parseDouble(json['price']),
      currency: json['currency'] ?? 'TZS',
      deposit: parseDouble(json['deposit']),
      bedrooms: json['bedrooms'] ?? 1,
      bathrooms: json['bathrooms'] ?? 1,
      areaSqft: parseDouble(json['area_sqft']),
      locationArea: json['location_area'] ?? '',
      locationCity: json['location_city'] ?? '',
      locationRegion: json['location_region'] ?? '',
      locationAddress: json['location_address'] ?? '',
      isFurnished: json['is_furnished'] ?? false,
      hasInternet: json['has_internet'] ?? false,
      hasWater: json['has_water'] ?? true,
      hasElectricity: json['has_electricity'] ?? true,
      hasParking: json['has_parking'] ?? false,
      hasSecurity: json['has_security'] ?? false,
      hasGenerator: json['has_generator'] ?? false,
      isFeatured: json['is_featured'] ?? false,
      viewsCount: json['views_count'] ?? 0,
      approvedAt: json['approved_at'], createdAt: json['created_at'],
      updatedAt: json['updated_at'],
      landlordName: json['landlord'] != null ? json['landlord']['name'] : null,
      landlordPhone: json['landlord'] != null ? json['landlord']['phone'] : null,
      mainImage: json['main_image'],
      images: json['images'] != null ? List<String>.from(json['images']) : [],
      amenities: json['amenities'] != null ? List<String>.from(json['amenities']) : [],
    );
  }

  String get statusLabel {
    switch (status) {
      case 'available': return 'Inapatikana';
      case 'rented': return 'Imekodiwa';
      case 'under_maintenance': return 'Inatengenezwa';
      case 'unavailable': return 'Haipatikani';
      default: return status;
    }
  }

  String get typeLabel {
    switch (propertyType) {
      case 'house': return 'Nyumba';
      case 'apartment': return 'Gorofa';
      case 'room': return 'Chumba';
      case 'commercial': return 'Biashara';
      case 'land': return 'Shamba';
      default: return propertyType;
    }
  }

  String get formattedPrice {
    if (price >= 1000000) {
      return 'TSh ${(price / 1000000).toStringAsFixed(1)}M';
    }
    return 'TSh ${price.toStringAsFixed(0)}';
  }

  String get fullLocation => '$locationArea, $locationCity';
}
