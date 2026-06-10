import 'package:flutter/material.dart';

class AppColors {
  AppColors._();

  // Primary Colors - Brick Red
  static const Color primary = Color(0xFFB44040);
  static const Color primaryDark = Color(0xFF9A3535);
  static const Color primaryDarker = Color(0xFF7E2B2B);
  static const Color primaryDarkest = Color(0xFF621F1F);
  static const Color primaryLight = Color(0xFFFFEEEE);

  // Secondary Colors - Blue
  static const Color secondary = Color(0xFF3B82F6);
  static const Color secondaryDark = Color(0xFF2563EB);
  static const Color secondaryDarker = Color(0xFF1D4ED8);
  static const Color secondaryDarkest = Color(0xFF1E40AF);

  // Neutral Colors
  static const Color white = Color(0xFFFFFFFF);
  static const Color black = Color(0xFF000000);
  static const Color grey50 = Color(0xFFF9FAFB);
  static const Color grey100 = Color(0xFFF3F4F6);
  static const Color grey200 = Color(0xFFE5E7EB);
  static const Color grey300 = Color(0xFFD1D5DB);
  static const Color grey400 = Color(0xFF9CA3AF);
  static const Color grey500 = Color(0xFF6B7280);
  static const Color grey600 = Color(0xFF4B5563);
  static const Color grey700 = Color(0xFF374151);
  static const Color grey800 = Color(0xFF1F2937);
  static const Color grey900 = Color(0xFF111827);

  // Status Colors
  static const Color success = Color(0xFF10B981);
  static const Color warning = Color(0xFFF59E0B);
  static const Color error = Color(0xFFEF4444);
  static const Color info = Color(0xFF3B82F6);

  // Gradient Colors - Gold to Blue
  static const List<Color> primaryGradient = [
    primary,
    primaryDark,
    secondary,
    secondaryDark,
  ];
  
  // Gold Gradient
  static const List<Color> goldGradient = [
    primary,
    primaryDark,
    primaryDarker,
    primaryDarkest,
  ];
  
  // Blue Gradient
  static const List<Color> blueGradient = [
    secondary,
    secondaryDark,
    secondaryDarker,
    secondaryDarkest,
  ];
}
