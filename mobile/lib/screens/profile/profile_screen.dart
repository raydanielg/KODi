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
                  // Avatar Section
                  Center(
                    child: Stack(
                      children: [
                        Container(
                          width: 120,
                          height: 120,
                          decoration: BoxDecoration(
                            color: AppColors.primary.withOpacity(0.1),
                            shape: BoxShape.circle,
                            border: Border.all(
                              color: AppColors.primary,
                              width: 2,
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
                              padding: const EdgeInsets.all(8),
                              decoration: BoxDecoration(
                                color: AppColors.primary,
                                shape: BoxShape.circle,
                                border: Border.all(color: Colors.white, width: 2),
                              ),
                              child: const Icon(
                                Icons.camera_alt_rounded,
                                color: Colors.white,
                                size: 20,
                              ),
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 24),
                  
                  // Name Field
                  Container(
                    padding: const EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(12),
                      border: Border.all(color: const Color(0xffe5e7eb)),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'Jina Kamili (Full Name)',
                          style: GoogleFonts.poppins(
                            fontSize: 13,
                            fontWeight: FontWeight.w600,
                            color: Colors.grey[700],
                          ),
                        ),
                        const SizedBox(height: 8),
                        TextField(
                          controller: _nameController,
                          style: GoogleFonts.poppins(
                            fontSize: 15,
                            color: const Color(0xff111827),
                          ),
                          decoration: InputDecoration(
                            hintText: 'Weka jina lako',
                            hintStyle: GoogleFonts.poppins(
                              color: Colors.grey[400],
                            ),
                            border: InputBorder.none,
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 16),
                  
                  // Phone Field
                  Container(
                    padding: const EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(12),
                      border: Border.all(color: const Color(0xffe5e7eb)),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'Namba ya Simu (Phone)',
                          style: GoogleFonts.poppins(
                            fontSize: 13,
                            fontWeight: FontWeight.w600,
                            color: Colors.grey[700],
                          ),
                        ),
                        const SizedBox(height: 8),
                        TextField(
                          controller: _phoneController,
                          keyboardType: TextInputType.phone,
                          style: GoogleFonts.poppins(
                            fontSize: 15,
                            color: const Color(0xff111827),
                          ),
                          decoration: InputDecoration(
                            hintText: 'Weka namba ya simu',
                            hintStyle: GoogleFonts.poppins(
                              color: Colors.grey[400],
                            ),
                            border: InputBorder.none,
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 16),
                  
                  // Email (Read-only)
                  Container(
                    padding: const EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      color: Colors.grey[100],
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'Barua Pepe (Email)',
                          style: GoogleFonts.poppins(
                            fontSize: 13,
                            fontWeight: FontWeight.w600,
                            color: Colors.grey[700],
                          ),
                        ),
                        const SizedBox(height: 8),
                        Text(
                          user?.email ?? '',
                          style: GoogleFonts.poppins(
                            fontSize: 15,
                            color: Colors.grey[600],
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 16),
                  
                  // Role (Read-only)
                  Container(
                    padding: const EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      color: Colors.grey[100],
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: Row(
                      children: [
                        Text(
                          'Jukumu (Role): ',
                          style: GoogleFonts.poppins(
                            fontSize: 13,
                            fontWeight: FontWeight.w600,
                            color: Colors.grey[700],
                          ),
                        ),
                        Text(
                          user?.roleLabel ?? '',
                          style: GoogleFonts.poppins(
                            fontSize: 15,
                            fontWeight: FontWeight.w700,
                            color: AppColors.primary,
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 32),
                  
                  // Update Button
                  SizedBox(
                    width: double.infinity,
                    height: 54,
                    child: ElevatedButton(
                      onPressed: _isUploading ? null : _updateProfile,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppColors.primary,
                        foregroundColor: Colors.white,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(12),
                        ),
                      ),
                      child: _isUploading
                          ? const SizedBox(
                              height: 24,
                              width: 24,
                              child: CircularProgressIndicator(
                                strokeWidth: 2,
                                color: Colors.white,
                              ),
                            )
                          : Text(
                              'Sasisha Wasifu (Update Profile)',
                              style: GoogleFonts.poppins(
                                fontWeight: FontWeight.w600,
                                fontSize: 15,
                              ),
                            ),
                    ),
                  ),
                ],
              ),
            ),
    );
  }
}
