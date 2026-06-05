class DashboardStatsModel {
  final Map<String, dynamic> stats;
  final List<dynamic>? recentItems;

  DashboardStatsModel({required this.stats, this.recentItems});

  factory DashboardStatsModel.fromJson(Map<String, dynamic> json) {
    return DashboardStatsModel(
      stats: Map<String, dynamic>.from(json['stats'] ?? {}),
      recentItems: json['recent_items'] as List<dynamic>?,
    );
  }
}
