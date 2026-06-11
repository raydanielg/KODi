import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';

class AppSettings extends ChangeNotifier {
  static final AppSettings _i = AppSettings._();
  static AppSettings get instance => _i;
  AppSettings._();

  ThemeMode _themeMode = ThemeMode.light;
  String _locale = 'sw'; // 'sw' | 'en'

  ThemeMode get themeMode => _themeMode;
  bool get isDark => _themeMode == ThemeMode.dark;
  String get locale => _locale;
  bool get isEnglish => _locale == 'en';

  Future<void> init() async {
    final prefs = await SharedPreferences.getInstance();
    _themeMode = (prefs.getString('theme') == 'dark') ? ThemeMode.dark : ThemeMode.light;
    _locale = prefs.getString('locale') ?? 'sw';
    notifyListeners();
  }

  Future<void> toggleTheme() async {
    _themeMode = isDark ? ThemeMode.light : ThemeMode.dark;
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('theme', isDark ? 'dark' : 'light');
    notifyListeners();
  }

  Future<void> toggleLocale() async {
    _locale = isEnglish ? 'sw' : 'en';
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('locale', _locale);
    notifyListeners();
  }

  /// Translate: pass Swahili + English. Returns based on current locale.
  String t(String sw, String en) => isEnglish ? en : sw;
}
