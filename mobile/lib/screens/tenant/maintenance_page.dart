import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../constants/app_colors.dart';
import '../../services/auth_service.dart';
import '../../services/maintenance_service.dart';
import '../../utils/helpers.dart';

class MaintenancePage extends StatefulWidget {
  const MaintenancePage({super.key});

  @override
  State<MaintenancePage> createState() => _MaintenancePageState();
}

class _MaintenancePageState extends State<MaintenancePage> {
  final AuthService _authService = AuthService();
  final MaintenanceService _maintenanceService = MaintenanceService();
  final _issueController = TextEditingController();
  final _descriptionController = TextEditingController();
  String _selectedCategory = 'Plumbing';
  bool _isSubmitting = false;
  bool _isEnglish = false;
  bool _isLoading = true;
  List<Map<String, dynamic>> _activeRequests = [];
  List<Map<String, dynamic>> _requestHistory = [];

  String _t(String sw, String en) {
    return _isEnglish ? en : sw;
  }

  final List<String> _categories = [
    'Plumbing',
    'Electrical',
    'Painting',
    'Carpentry',
    'General',
  ];

  @override
  void initState() {
    super.initState();
    _loadMaintenanceData();
  }

  Future<void> _loadMaintenanceData() async {
    setState(() => _isLoading = true);
    
    try {
      final requests = await _maintenanceService.fetchMaintenanceRequests();
      final history = await _maintenanceService.getRequestHistory();
      
      setState(() {
        _activeRequests = requests.where((r) => 
          r['status'] == 'pending' || r['status'] == 'in_progress'
        ).toList();
        _requestHistory = history;
        _isLoading = false;
      });
    } catch (e) {
      setState(() => _isLoading = false);
      if (mounted) {
        Helpers.showSnackBar(
          context,
          _t('Imeshindikana kupata data ya matengenezo', 'Failed to load maintenance data'),
        );
      }
    }
  }

  @override
  void dispose() {
    _issueController.dispose();
    _descriptionController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xfff9fafb),
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back, color: Color(0xff111827)),
          onPressed: () => Navigator.pop(context),
        ),
        title: Text(
          _t('Matengenezo', 'Maintenance'),
          style: GoogleFonts.poppins(
            fontSize: 18,
            fontWeight: FontWeight.w700,
            color: const Color(0xff111827),
          ),
        ),
        actions: [
          // Language toggle
          Container(
            margin: const EdgeInsets.only(right: 16),
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
            decoration: BoxDecoration(
              color: const Color(0xfff3f4f6),
              borderRadius: BorderRadius.circular(20),
            ),
            child: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                GestureDetector(
                  onTap: () {
                    setState(() {
                      _isEnglish = false;
                    });
                  },
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                    decoration: BoxDecoration(
                      color: !_isEnglish ? AppColors.primary : Colors.transparent,
                      borderRadius: BorderRadius.circular(16),
                    ),
                    child: Text(
                      'SW',
                      style: GoogleFonts.poppins(
                        fontSize: 11,
                        fontWeight: !_isEnglish ? FontWeight.bold : FontWeight.w500,
                        color: !_isEnglish ? Colors.black : Colors.grey[600],
                      ),
                    ),
                  ),
                ),
                const SizedBox(width: 4),
                GestureDetector(
                  onTap: () {
                    setState(() {
                      _isEnglish = true;
                    });
                  },
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                    decoration: BoxDecoration(
                      color: _isEnglish ? AppColors.primary : Colors.transparent,
                      borderRadius: BorderRadius.circular(16),
                    ),
                    child: Text(
                      'EN',
                      style: GoogleFonts.poppins(
                        fontSize: 11,
                        fontWeight: _isEnglish ? FontWeight.bold : FontWeight.w500,
                        color: _isEnglish ? Colors.black : Colors.grey[600],
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
      body: _isLoading
          ? const Center(
              child: CircularProgressIndicator(color: AppColors.primary),
            )
          : RefreshIndicator(
              onRefresh: _loadMaintenanceData,
              color: AppColors.primary,
              child: SingleChildScrollView(
                padding: const EdgeInsets.all(20),
                child: Column(
                  children: [
                    // Quick Actions
                    Row(
                      children: [
                        Expanded(
                          child: _quickActionCard(
                            _t('Ripoti Tatizo', 'Report Issue'),
                            Icons.report_problem_outlined,
                            () => _showReportDialog(),
                          ),
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: _quickActionCard(
                            _t('Ombi Zangu', 'My Requests'),
                            Icons.list_alt_outlined,
                            () {},
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 24),

                    // Active Requests
                    _buildSectionCard(
                      title: _t('Ombi Linalofanya Kazi', 'Active Requests'),
                      child: _activeRequests.isEmpty
                          ? Padding(
                              padding: const EdgeInsets.all(20),
                              child: Center(
                                child: Text(
                                  _t('Hakuna ombi la kufanya kazi', 'No active requests'),
                                  style: GoogleFonts.poppins(
                                    color: Colors.grey[500],
                                    fontSize: 14,
                                  ),
                                ),
                              ),
                            )
                          : Column(
                              children: _activeRequests.map((request) {
                                return Column(
                                  children: [
                                    _requestCard(
                                      request['category'] ?? 'General',
                                      request['title'] ?? 'No title',
                                      _getStatusText(request['status']),
                                      _getStatusColor(request['status']),
                                    ),
                                    if (request != _activeRequests.last)
                                      const SizedBox(height: 12),
                                  ],
                                );
                              }).toList(),
                            ),
                    ),
                    const SizedBox(height: 24),

                    // Recent History
                    _buildSectionCard(
                      title: _t('Historia ya Karibuni', 'Recent History'),
                      child: _requestHistory.isEmpty
                          ? Padding(
                              padding: const EdgeInsets.all(20),
                              child: Center(
                                child: Text(
                                  _t('Hakuna historia ya ombi', 'No request history'),
                                  style: GoogleFonts.poppins(
                                    color: Colors.grey[500],
                                    fontSize: 14,
                                  ),
                                ),
                              ),
                            )
                          : Column(
                              children: _requestHistory.map((request) {
                                return Column(
                                  children: [
                                    _requestCard(
                                      request['category'] ?? 'General',
                                      request['title'] ?? 'No title',
                                      _getStatusText(request['status']),
                                      _getStatusColor(request['status']),
                                    ),
                                    if (request != _requestHistory.last)
                                      const SizedBox(height: 12),
                                  ],
                                );
                              }).toList(),
                            ),
                    ),
                  ],
                ),
              ),
            ),
    );
  }

  Widget _quickActionCard(String title, IconData icon, VoidCallback onTap) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.all(20),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.05),
              blurRadius: 8,
              offset: const Offset(0, 2),
            ),
          ],
        ),
        child: Column(
          children: [
            Icon(icon, color: AppColors.primary, size: 32),
            const SizedBox(height: 12),
            Text(
              title,
              style: GoogleFonts.poppins(
                fontSize: 14,
                fontWeight: FontWeight.w600,
                color: const Color(0xff111827),
              ),
              textAlign: TextAlign.center,
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildSectionCard({required String title, required Widget child}) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 8,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            title,
            style: GoogleFonts.poppins(
              fontSize: 16,
              fontWeight: FontWeight.bold,
              color: const Color(0xff111827),
            ),
          ),
          const SizedBox(height: 16),
          child,
        ],
      ),
    );
  }

  Widget _requestCard(String title, String description, String status, Color statusColor) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.grey[50],
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: const Color(0xffe5e7eb)),
      ),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(
              color: AppColors.primary.withOpacity(0.1),
              borderRadius: BorderRadius.circular(10),
            ),
            child: Icon(
              Icons.build_outlined,
              color: AppColors.primary,
              size: 20,
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  title,
                  style: GoogleFonts.poppins(
                    fontSize: 14,
                    fontWeight: FontWeight.w600,
                    color: const Color(0xff111827),
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  description,
                  style: GoogleFonts.poppins(
                    fontSize: 12,
                    color: Colors.grey[600],
                  ),
                ),
              ],
            ),
          ),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
            decoration: BoxDecoration(
              color: statusColor.withOpacity(0.1),
              borderRadius: BorderRadius.circular(8),
            ),
            child: Text(
              status,
              style: GoogleFonts.poppins(
                fontSize: 11,
                fontWeight: FontWeight.w600,
                color: statusColor,
              ),
            ),
          ),
        ],
      ),
    );
  }

  void _showReportDialog() {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: Text(
          _t('Ripoti Tatizo', 'Report Issue'),
          style: GoogleFonts.poppins(
            fontWeight: FontWeight.bold,
          ),
        ),
        content: SingleChildScrollView(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                _t('Kundi', 'Category'),
                style: GoogleFonts.poppins(
                  fontSize: 14,
                  fontWeight: FontWeight.w600,
                ),
              ),
              const SizedBox(height: 8),
              Wrap(
                spacing: 8,
                runSpacing: 8,
                children: _categories.map((category) {
                  final isSelected = _selectedCategory == category;
                  return GestureDetector(
                    onTap: () {
                      setState(() {
                        _selectedCategory = category;
                      });
                      Navigator.pop(context);
                      _showReportDialog();
                    },
                    child: Container(
                      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                      decoration: BoxDecoration(
                        color: isSelected ? AppColors.primary : Colors.grey[200],
                        borderRadius: BorderRadius.circular(20),
                      ),
                      child: Text(
                        category,
                        style: GoogleFonts.poppins(
                          fontSize: 12,
                          color: isSelected ? Colors.black : Colors.black87,
                          fontWeight: isSelected ? FontWeight.w600 : FontWeight.w500,
                        ),
                      ),
                    ),
                  );
                }).toList(),
              ),
              const SizedBox(height: 16),
              Text(
                _t('Kichwa cha Tatizo', 'Issue Title'),
                style: GoogleFonts.poppins(
                  fontSize: 14,
                  fontWeight: FontWeight.w600,
                ),
              ),
              const SizedBox(height: 8),
              TextField(
                controller: _issueController,
                decoration: InputDecoration(
                  hintText: _t('Weka kichwa cha tatizo', 'Enter issue title'),
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(8),
                  ),
                ),
              ),
              const SizedBox(height: 16),
              Text(
                _t('Maelezo', 'Description'),
                style: GoogleFonts.poppins(
                  fontSize: 14,
                  fontWeight: FontWeight.w600,
                ),
              ),
              const SizedBox(height: 8),
              TextField(
                controller: _descriptionController,
                maxLines: 3,
                decoration: InputDecoration(
                  hintText: _t('Elezea tatizo', 'Describe the issue'),
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(8),
                  ),
                ),
              ),
            ],
          ),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: Text(
              _t('Ghairi', 'Cancel'),
              style: GoogleFonts.poppins(),
            ),
          ),
          ElevatedButton(
            onPressed: _isSubmitting ? null : _submitRequest,
            style: ElevatedButton.styleFrom(
              backgroundColor: AppColors.primary,
              foregroundColor: Colors.black,
            ),
            child: _isSubmitting
                ? const SizedBox(
                    height: 20,
                    width: 20,
                    child: CircularProgressIndicator(
                      strokeWidth: 2,
                      color: Colors.black,
                    ),
                  )
                : Text(
                    _t('Tuma', 'Submit'),
                    style: GoogleFonts.poppins(fontWeight: FontWeight.w600),
                  ),
          ),
        ],
      ),
    );
  }

  Future<void> _submitRequest() async {
    if (_issueController.text.trim().isEmpty) {
      Helpers.showSnackBar(context, _t('Tafadhali weka kichwa cha tatizo', 'Please enter issue title'));
      return;
    }

    setState(() => _isSubmitting = true);
    Navigator.pop(context);

    try {
      final response = await _maintenanceService.submitMaintenanceRequest(
        category: _selectedCategory,
        title: _issueController.text.trim(),
        description: _descriptionController.text.trim(),
      );

      setState(() => _isSubmitting = false);

      if (mounted) {
        if (response['success'] == true) {
          Helpers.showSnackBar(
            context,
            _t('Ombi limetumwa!', 'Request submitted!'),
            isError: false,
          );
          _issueController.clear();
          _descriptionController.clear();
          _loadMaintenanceData(); // Refresh data
        } else {
          Helpers.showSnackBar(
            context,
            response['message'] ?? _t('Imeshindikana kutuma ombi', 'Failed to submit request'),
          );
        }
      }
    } catch (e) {
      setState(() => _isSubmitting = false);
      if (mounted) {
        Helpers.showSnackBar(
          context,
          _t('Imeshindikana kutuma ombi', 'Failed to submit request'),
        );
      }
    }
  }

  String _getStatusText(String? status) {
    switch (status?.toLowerCase()) {
      case 'pending':
        return _t('Inasubiri', 'Pending');
      case 'in_progress':
        return _t('Inaendelea', 'In Progress');
      case 'completed':
        return _t('Imekamilika', 'Completed');
      case 'rejected':
        return _t('Imekataliwa', 'Rejected');
      default:
        return _t('Haijulikani', 'Unknown');
    }
  }

  Color _getStatusColor(String? status) {
    switch (status?.toLowerCase()) {
      case 'pending':
        return Colors.grey;
      case 'in_progress':
        return Colors.orange;
      case 'completed':
        return Colors.green;
      case 'rejected':
        return Colors.red;
      default:
        return Colors.grey;
    }
  }
}
