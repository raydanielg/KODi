import '../utils/app_settings.dart';

class AppStrings {
  AppStrings._();

  // App Name
  static const String appName = 'Manna';
  static const String appTagline = 'Find Your Perfect Home';

  // Auth - Swahili | English
  static const String signIn = 'Ingia|Sign In';
  static const String signUp = 'Jiunge|Sign Up';
  static const String createAccount = 'Unda Akaunti|Create Account';
  static const String welcomeBack = 'Karibu tena|Welcome back';
  static const String signInSubtitle = 'Ingia kuendelea na akaunti yako|Sign in to continue to your account';
  static const String createAccountSubtitle = 'Anza safari yako leo kwa kuunda akaunti mpya|Start your journey today by creating a new account';
  static const String emailAddress = 'Anwani ya barua pepe|Email address';
  static const String password = 'Nenosiri|Password';
  static const String confirmPassword = 'Thibitisha nenosiri|Confirm password';
  static const String fullName = 'Jina kamili|Full name';
  static const String phoneNumber = 'Namba ya simu|Phone number';
  static const String forgotPassword = 'Umesahau nenosiri?|Forgot password?';
  static const String dontHaveAccount = 'Huna akaunti? |Don\'t have an account? ';
  static const String alreadyHaveAccount = 'Tayari una akaunti? |Already have an account? ';
  static const String agreeToTerms = 'Nakubali Masharti ya Huduma na Sera ya Faragha|I agree to the Terms of Service and Privacy Policy';
  static const String rememberMe = 'Nikumbuke|Remember me';

  // Roles
  static const String iAmA = 'Mimi ni|I am a';
  static const String selectYourRole = 'Chagua jukumu lako|Select your role';
  static const String tenant = 'Mpangaji|Tenant';
  static const String landlord = 'Mmiliki wa Nyumba|Landlord';
  static const String agent = 'Wakala wa Nyumba|Agent';
  static const String supportAgent = 'Msaada wa Wateja|Support Agent';
  static const String maintenanceStaff = 'Wafanyakazi wa Matengenezo|Maintenance Staff';
  static const String accountant = 'Mhasibu|Accountant';

  // Home
  static const String getStarted = 'Anza|Get Started';
  static const String findYourPerfectHome = 'Tafuta Nyumba Yako Bora|Find Your Perfect Home';
  static const String homeDescription = 'Jukwaa la kisasa la upangaji wa muda mrefu linalounganisha wapangaji na wamiliki wa nyumba barani Afrika.|The modern long-term rental platform connecting tenants with landlords across Africa.';

  // Validation
  static const String pleaseEnterEmail = 'Tafadhali ingiza barua pepe yako|Please enter your email';
  static const String pleaseEnterValidEmail = 'Tafadhali ingiza barua pepe halali|Please enter a valid email';
  static const String pleaseEnterPassword = 'Tafadhali ingiza nenosiri lako|Please enter your password';
  static const String passwordTooShort = 'Nenosiri lazima liwe na angalau herufi 6|Password must be at least 6 characters';
  static const String pleaseEnterName = 'Tafadhali ingiza jina lako kamili|Please enter your full name';
  static const String pleaseEnterPhone = 'Tafadhali ingiza namba ya simu yako|Please enter your phone number';
  static const String pleaseSelectRole = 'Tafadhali chagua jukumu lako|Please select your role';
  static const String pleaseConfirmPassword = 'Tafadhali thibitisha nenosiri lako|Please confirm your password';
  static const String passwordsDoNotMatch = 'Nenosiri hazilingani|Passwords do not match';
  static const String pleaseAgreeToTerms = 'Tafadhali kukubali masharti|Please agree to the terms';

  // General
  static const String loading = 'Inapakia...|Loading...';
  static const String error = 'Hitilafu|Error';
  static const String success = 'Mafanikio|Success';
  static const String tryAgain = 'Jaribu Tena|Try Again';
  static const String cancel = 'Ghairi|Cancel';
  static const String ok = 'Sawa|OK';
  static const String save = 'Hifadhi|Save';
  static const String delete = 'Futa|Delete';
  static const String edit = 'Hariri|Edit';
  static const String search = 'Tafuta|Search';
  static const String filter = 'Chuja|Filter';
  static const String sort = 'Panga|Sort';

  // API Configuration
  static const String apiBaseUrl = 'https://manna.co.tz/api';

  // Helper method to get translated string
  static String t(String key) {
    final parts = key.split('|');
    if (parts.length == 2) {
      return AppSettings.instance.isEnglish ? parts[1] : parts[0];
    }
    return key;
  }
}
