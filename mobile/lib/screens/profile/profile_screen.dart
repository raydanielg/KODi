import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:image_picker/image_picker.dart';
import 'dart:io';
import '../../constants/app_colors.dart';
import '../../models/user_model.dart';
import '../../services/auth_service.dart';
import '../../services/api_service.dart';
import '../../utils/helpers.dart';

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({super.key});

  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  final AuthService _authService = AuthService();
  final ApiService _apiService = ApiService();
  final ImagePicker _imagePicker = ImagePicker();
  
  final _nameController = TextEditingController();
  final _phoneController = TextEditingController();
  
  File? _avatarFile;
  bool _isLoading = false;
  bool _isUploading = false;

  @override
  void initState() {
    super.initState();
    _loadUserData();
  }

  void _loadUserData() {
    final user = _authService.currentUser;
    if (user != null) {
      _nameController.text = user.name;
      _phoneController.text = user.phone;
    }
  }

  Future<void> _pickAvatar() async {
    try {
      final XFile? image = await _imagePicker.pickImage(
        source: ImageSource.gallery,
        imageQuality: 80,
        maxWidth: 512,
        maxHeight: 512,
      );
      
      if (image != null) {
        setState(() {
          _avatarFile = File(image.path);
        });
      }
    } catch (e) {
      if (mounted) {
        Helpers.showSnackBar(context, 'Imeshindikana kuchagua picha. Tafadhali jaribu tena.');
      }
    }
  }

  Future<void> _updateProfile() async {
    if (_nameController.text.trim().isEmpty) {
      Helpers.showSnackBar(context, 'Jina linahitajika.');
      return;
    }

    setState(() => _isUploading = true);

    try {
      final user = _authService.currentUser!;
      
      // Prepare form data
      Map<String, dynamic> data = {
        'name': _nameController.text.trim(),
        'phone': _phoneController.text.trim(),
      };

      // If avatar is selected, upload it
      if (_avatarFile != null) {
        final response = await _apiService.uploadFile('/auth/profile', _avatarFile!, fields: data);
        
        if (response['success'] == true) {
          // Update user data
          final updatedUser = UserModel.fromJson(response['data']);
          _authService.updateUser(updatedUser);
          
          if (mounted) {
            Helpers.showSnackBar(context, 'Wasifu umesasishwa.', isError: false);
            setState(() {
              _avatarFile = null;
            });
          }
        } else {
          throw Exception(response['message'] ?? 'Imeshindikana kusasisha wasifu.');
        }
      } else {
        // Update without avatar
        final response = await _apiService.put('/auth/profile', body: data);
        
        if (response['success'] == true) {
          final updatedUser = UserModel.fromJson(response['data']);
          _authService.updateUser(updatedUser);
          
          if (mounted) {
            Helpers.showSnackBar(context, 'Wasifu umesasishwa.', isError: false);
          }
        } else {
          throw Exception(response['message'] ?? 'Imeshindikana kusasisha wasifu.');
        }
      }
    } catch (e) {
      if (mounted) {
        Helpers.showSnackBar(context, e.toString());
      }
    } finally {
      if (mounted) {
        setState(() => _isUploading = false);
      }
    }
  }

  @override
  void dispose() {
    _nameController.dispose();
    _phoneController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final user = _authService.currentUser;

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
          'Wasifu Wangu (My Profile)',
          style: GoogleFonts.poppins(
            fontSize: 18,
            fontWeight: FontWeight.w700,
            color: const Color(0xff111827),
          ),
        ),
      ),
      body: _isLoading
          ? const Center(
              child: CircularProgressIndicator(color: AppColors.primary),
            )
          : SingleChildScrollView(
              padding: const EdgeInsets.all(20),
              child: Column(
                children: [
                  // Avatar Section with Card
                  Container(
                    padding: const EdgeInsets.all(24),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(20),
                      boxShadow: [
                        BoxShadow(
                          color: Colors.black.withOpacity(0.05),
                          blurRadius: 12,
                          offset: const Offset(0, 4),
                        ),
                      ],
                    ),
                    child: Column(
                      children: [
                        Center(
                          child: Stack(
                            children: [
                              Container(
                                width: 120,
                                height: 120,
                                decoration: BoxDecoration(
                                  gradient: LinearGradient(
                                    colors: [
                                      AppColors.primary.withOpacity(0.2),
                                      AppColors.primaryDark.withOpacity(0.2),
                                    ],
                                  ),
                                  shape: BoxShape.circle,
                                  border: Border.all(
                                    color: AppColors.primary,
                                    width: 3,
                                  ),
                                ),
                                child: _avatarFile != null
                                    ? ClipOval(
                                        child: Image.file(
                                          _avatarFile!,
                                          fit: BoxFit.cover,
                                          width: 120,
                                          height: 120,
                                        ),
                                      )
                                    : user?.avatar != null && user!.avatar!.isNotEmpty
                                        ? ClipOval(
                                            child: Image.network(
                                              user.avatar!,
                                              fit: BoxFit.cover,
                                              width: 120,
                                              height: 120,
                                              errorBuilder: (context, error, stackTrace) {
                                                return Center(
                                                  child: Text(
                                                    user.initials,
                                                    style: GoogleFonts.poppins(
                                                      color: AppColors.primary,
                                                      fontWeight: FontWeight.w700,
                                                      fontSize: 32,
                                                    ),
                                                  ),
                                                );
                                              },
                                            ),
                                          )
                                        : Center(
                                            child: Text(
                                              user?.initials ?? 'U',
                                              style: GoogleFonts.poppins(
                                                color: AppColors.primary,
                                                fontWeight: FontWeight.w700,
                                                fontSize: 32,
                                              ),
                                            ),
                                          ),
                              ),
                              Positioned(
                                bottom: 0,
                                right: 0,
                                child: GestureDetector(
                                  onTap: _pickAvatar,
                                  child: Container(
                                    padding: const EdgeInsets.all(10),
                                    decoration: BoxDecoration(
                                      gradient: LinearGradient(
                                        colors: [
                                          AppColors.primary,
                                          AppColors.primaryDark,
                                        ],
                                      ),
                                      shape: BoxShape.circle,
                                      border: Border.all(color: Colors.white, width: 3),
                                      boxShadow: [
                                        BoxShadow(
                                          color: Colors.black.withOpacity(0.2),
                                          blurRadius: 8,
                                          offset: const Offset(0, 2),
                                        ),
                                      ],
                                    ),
                                    child: const Icon(
                                      Icons.camera_alt_rounded,
                                      color: Colors.white,
                                      size: 22,
                                    ),
                                  ),
                                ),
                              ),
                            ],
                          ),
                        ),
                        const SizedBox(height: 16),
                        Text(
                          user?.name ?? 'User',
                          style: GoogleFonts.poppins(
                            fontSize: 20,
                            fontWeight: FontWeight.bold,
                            color: const Color(0xff111827),
                          ),
                        ),
                        const SizedBox(height: 4),
                        Text(
                          user?.roleLabel ?? '',
                          style: GoogleFonts.poppins(
                            fontSize: 14,
                            color: Colors.grey[600],
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 24),
                  
                  // Info Cards Row
                  Row(
                    children: [
                      Expanded(
                        child: _infoCard(
                          'Barua Pepe',
                          user?.email ?? 'N/A',
                          Icons.email_outlined,
                        ),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: _infoCard(
                          'Jukumu',
                          user?.roleLabel ?? 'N/A',
                          Icons.badge_outlined,
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 24),
                  
                  // Name Field
                  _inputCard(
                    'Jina Kamili (Full Name)',
                    'Weka jina lako',
                    _nameController,
                    TextInputType.text,
                    false,
                  ),
                  const SizedBox(height: 16),
                  
                  // Phone Field
                  _inputCard(
                    'Namba ya Simu (Phone)',
                    'Weka namba ya simu',
                    _phoneController,
                    TextInputType.phone,
                    false,
                  ),
                  const SizedBox(height: 32),
                  
                  // Update Button
                  SizedBox(
                    width: double.infinity,
                    height: 56,
                    child: ElevatedButton(
                      onPressed: _isUploading ? null : _updateProfile,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppColors.primary,
                        foregroundColor: Colors.black,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(16),
                        ),
                        elevation: 0,
                        shadowColor: AppColors.primary.withOpacity(0.3),
                      ),
                      child: _isUploading
                          ? const SizedBox(
                              height: 24,
                              width: 24,
                              child: CircularProgressIndicator(
                                strokeWidth: 2,
                                color: Colors.black,
                              ),
                            )
                          : Text(
                              'Sasisha Wasifu (Update Profile)',
                              style: GoogleFonts.poppins(
                                fontWeight: FontWeight.w600,
                                fontSize: 16,
                              ),
                            ),
                    ),
                  ),
                  const SizedBox(height: 20),
                ],
              ),
            ),
    );
  }

  Widget _infoCard(String title, String value, IconData icon) {
    return Container(
      padding: const EdgeInsets.all(16),
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
          Icon(icon, color: AppColors.primary, size: 20),
          const SizedBox(height: 8),
          Text(
            title,
            style: GoogleFonts.poppins(
              fontSize: 11,
              fontWeight: FontWeight.w600,
              color: Colors.grey[600],
            ),
          ),
          const SizedBox(height: 4),
          Text(
            value,
            style: GoogleFonts.poppins(
              fontSize: 13,
              fontWeight: FontWeight.w600,
              color: const Color(0xff111827),
            ),
            maxLines: 1,
            overflow: TextOverflow.ellipsis,
          ),
        ],
      ),
    );
  }

  Widget _inputCard(
    String label,
    String hint,
    TextEditingController controller,
    TextInputType keyboardType,
    bool readOnly,
  ) {
    return Container(
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
            label,
            style: GoogleFonts.poppins(
              fontSize: 13,
              fontWeight: FontWeight.w600,
              color: Colors.grey[700],
            ),
          ),
          const SizedBox(height: 12),
          TextField(
            controller: controller,
            keyboardType: keyboardType,
            readOnly: readOnly,
            style: GoogleFonts.poppins(
              fontSize: 15,
              color: const Color(0xff111827),
            ),
            decoration: InputDecoration(
              hintText: hint,
              hintStyle: GoogleFonts.poppins(
                color: Colors.grey[400],
              ),
              border: OutlineInputBorder(
                borderRadius: BorderRadius.circular(12),
                borderSide: BorderSide(color: Colors.grey[300]!),
              ),
              enabledBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(12),
                borderSide: BorderSide(color: Colors.grey[300]!),
              ),
              focusedBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(12),
                borderSide: const BorderSide(color: AppColors.primary, width: 2),
              ),
              contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
            ),
          ),
        ],
      ),
    );
  }
}
