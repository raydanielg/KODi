double? _md(dynamic v) =>
    v == null ? null : double.tryParse(v.toString());

class MaintenanceModel {
  final int id;
  final int propertyId;
  final int tenantId;
  final int? landlordId;
  final int? assignedTo;
  final String title;
  final String description;
  final String category;
  final String priority;
  final String status;
  final String? scheduledAt;
  final String? completedAt;
  final double? estimatedCost;
  final double? actualCost;
  final String? notes;
  final String? createdAt;
  final String? updatedAt;
  final String? propertyTitle;
  final String? tenantName;
  final String? assignedName;

  MaintenanceModel({
    required this.id, required this.propertyId, required this.tenantId,
    this.landlordId, this.assignedTo, required this.title,
    required this.description, required this.category, required this.priority,
    required this.status, this.scheduledAt, this.completedAt,
    this.estimatedCost, this.actualCost, this.notes,
    this.createdAt, this.updatedAt,
    this.propertyTitle, this.tenantName, this.assignedName,
  });

  factory MaintenanceModel.fromJson(Map<String, dynamic> json) {
    return MaintenanceModel(
      id: json['id'], propertyId: json['property_id'], tenantId: json['tenant_id'],
      landlordId: json['landlord_id'], assignedTo: json['assigned_to'],
      title: json['title'] ?? '', description: json['description'] ?? '',
      category: json['category'] ?? 'general',
      priority: json['priority'] ?? 'medium',
      status: json['status'] ?? 'pending',
      scheduledAt: json['scheduled_at'], completedAt: json['completed_at'],
      estimatedCost: _md(json['estimated_cost']),
      actualCost: _md(json['actual_cost']),
      notes: json['notes'], createdAt: json['created_at'], updatedAt: json['updated_at'],
      propertyTitle: json['property'] != null ? json['property']['title'] : null,
      tenantName: json['tenant'] != null ? json['tenant']['name'] : null,
      assignedName: json['assigned'] != null ? json['assigned']['name'] : null,
    );
  }

  String get statusLabel {
    switch (status) {
      case 'pending': return 'Haijaanza';
      case 'in_progress': return 'Inafanyika';
      case 'completed': return 'Imekamilika';
      case 'cancelled': return 'Imefutwa';
      default: return status;
    }
  }

  String get priorityLabel {
    switch (priority) {
      case 'low': return 'Chini';
      case 'medium': return 'Kati';
      case 'high': return 'Juu';
      case 'urgent': return 'Dharura';
      default: return priority;
    }
  }

  String get categoryLabel {
    switch (category) {
      case 'plumbing': return 'Bomba';
      case 'electrical': return 'Umeme';
      case 'structural': return 'Muundo';
      case 'appliance': return 'Vifaa';
      case 'cleaning': return 'Usafi';
      case 'pest_control': return 'Wadudu';
      case 'general': return 'Mengineyo';
      default: return category;
    }
  }
}
