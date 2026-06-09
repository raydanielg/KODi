class DashboardStatsModel {
  final Map<String, dynamic> stats;
  final Map<String, dynamic>? activeLease;
  final List<dynamic>? recentPayments;
  final List<dynamic>? maintenanceRequests;
  final List<dynamic>? recentItems;
  final List<dynamic>? myProperties;
  final List<dynamic>? recentCommissions;

  DashboardStatsModel({
    required this.stats,
    this.activeLease,
    this.recentPayments,
    this.maintenanceRequests,
    this.recentItems,
    this.myProperties,
    this.recentCommissions,
  });

  factory DashboardStatsModel.fromJson(Map<String, dynamic> json) {
    return DashboardStatsModel(
      stats: Map<String, dynamic>.from(json['stats'] ?? {}),
      activeLease: json['active_lease'] as Map<String, dynamic>?,
      recentPayments: json['recent_payments'] as List<dynamic>?,
      maintenanceRequests: json['maintenance_requests'] as List<dynamic>?,
      recentItems: json['recent_items'] as List<dynamic>?,
      myProperties: json['my_properties'] as List<dynamic>?,
      recentCommissions: json['recent_commissions'] as List<dynamic>?,
    );
  }
}
