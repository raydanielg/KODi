# 🏠 KODI Long-Term Rental Platform

<div align="center">

**KODI is a comprehensive long-term rental platform for the African market, connecting tenants and landlords for monthly/yearly rentals.

[![Laravel](https://img.shields.io/badge/Laravel-8.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-7.4+-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange.svg)](https://mysql.com)

</div>

---

## 📌 About KODI

**KODI is annn AfriAan nara m anrng "Th mSystam" - n "Th mSystam" - n "The System" - a platform designed specifically for long-term rentals (3 months to 1+ years), unlike short-term vacation rentals like Airbnb.

### Core Purpose
- Connect **Tenants** (wapangaji) with **Landlords** (wamiliki) for long-term property rentals
- Streamline the rental process with applications, lease agreements, and automated rent collection
- Provide secure payment processing via M-Pesa and Stripe
- Enable transparent communication and maintenance management

### Key Features
- 🔍 **Property Search** - Find properties by location, budget, and amenities
- 📝 **Application Process** - Submit rental applications with background checks
- 📄 **Lease Agreements** - Generate and sign electronic lease agreements
- 💰 **Rent Payments** - Monthly rent collection via M-Pesa, Bank, or Card
- 🔧 **Maintenance Requests** - Report and track property maintenance issues
- 💬 **Messaging System** - Direct communication between tenants and landlords
- ⭐ **Reviews** - Post-move-out reviews for transparency
- 📊 **Analytics** - Track rent collection, occupancy rates, and financial performance

---

## 👥 User Roles & Panels

Manna provides dedicated panels for different user types with role-based access control.

### 📊 ROLES ZOTE ZA MFUMO WA MANNA

| # | Role | Kiwango | Kazi Kuu | Dashboard URL |
|---|------|---------|----------|---------------|
| 1 | **Super Admin** | Juu Kabisa | Kudhibiti mfumo mzima | `/super-admin/dashboard` |
| 2 | **Admin** | Juu | Kusimamia operations za kila siku | `/admin/dashboard` |
| 3 | **Landlord** | Wastani | Mmiliki wa nyumba / Property owner | `/landlord/dashboard` |
| 4 | **Agent** | Wastani | Wakala wa nyumba | `/agent/dashboard` |
| 5 | **Tenant** | Msingi | Mpangaji / Mtu anayekodisha nyumba | `/tenant/dashboard` |
| 6 | **Guest** | Msingi | Mtembeleaji (hajajisajili) | `/` (public) |
| 7 | **Support Agent** | Wastani | Kujibu maswali ya wateja | `/support/dashboard` |
| 8 | **Maintenance Staff** | Msingi | Fundi wa kukarabati nyumba | `/maintenance/dashboard` |
| 9 | **Accountant** | Wastani | Kushughulikia pesa | `/accountant/dashboard` |
| 10 | **Investor** | Read-Only | Kuona utendaji wa kampuni | `/investor/dashboard` |

---

### 👑 1. SUPER ADMIN

**Maelezo:** Mwenyewe wa mfumo / Technical founder

**Majukumu:**
- Kudhibiti admins wengine
- Kuweka na kubadilisha system settings
- Kuona logs zote za mfumo
- Kufanya backups za database
- Kuweka feature flags (kuwasha/kuzima features)
- Kudhibiti maintenance mode
- Kufanya migrations za database
- Kuweka payment gateways (M-Pesa, Stripe)
- Kuweka email/SMS settings

**Access Level:** 🔴 **Full System Control**

**Screens:** 9 screens including System Dashboard, Database, Backups, Logs, Features, Payment Gateways, Email/SMS, Roles, Maintenance

---

### 🛡️ 2. ADMIN

**Maelezo:** Wasimamizi wa mfumo wa kila siku

**Majukumu:**
- Kusimamia landlords wote
- Kusimamia tenants wote
- Kusimamia agents wote
- Kuidhinisha properties mpya
- Kushughulikia disputes (migogoro)
- Kuangalia reports za platform
- Kutuma announcements kwa users
- Kuweka commission rates
- Kuona payment analytics
- Kudhibiti property verifications

**Access Level:** 🟠 **High Level Management**

**Screens:** 9 screens including Dashboard, Users, Properties, Payments, Disputes, Announcements, Reports, Settings

---

### 🏘️ 3. LANDLORD (Mmiliki wa Nyumba)

**Maelezo:** Mtu aliye na nyumba anayotaka kukodisha

**Majukumu:**
- Kuongeza properties zake
- Kuedit properties zake
- Kuona applications za tenants
- Kukubali au kukataa tenants
- Kuunda lease agreements
- Kuona rent payments
- Kutuma reminders za kodi kwa tenants
- Kujibu maintenance requests
- Kuona rent collection reports
- Kuomba payout (pesa zikutumwe)
- Kuwasiliana na tenants

**Access Level:** 🟡 **Property Owner Access**

**Screens:** 17 screens including Dashboard, My Properties, Applications, Current Tenants, Rent Payments, Maintenance, Reports, Settings

---

### 🤝 4. AGENT (Wakala wa Nyumba)

**Maelezo:** Mtu anayesimamia properties kwa niaba ya landlords

**Majukumu:**
- Kusimamia properties za landlords wengi
- Kupokea applications kwa niaba ya landlord
- Kuwasaidia tenants kupata nyumba
- Kuona commission yake
- Kuomba payout ya commission
- Kuripoti kwa landlords
- Kuwasiliana na tenants na landlords

**Access Level:** 🟢 **Agent Access**

**Screens:** 6 screens including Dashboard, My Listings, Applications, Commission, Payouts, Reports

---

### � 5. TENANT (Mpangaji)

**Maelezo:** Mtu anayetafuta au amekodisha nyumba

**Majukumu:**
- Kutafuta properties
- Kutuma application kwa property
- Kuangalia status ya application
- Kusaini lease agreement (online)
- Kulipa kodi kila mwezi (M-Pesa / Card)
- Kuona payment history
- Kutuma maintenance requests
- Kuwasiliana na landlord / agent
- Kuandika review baada ya kuhama
- Kupakua receipts na documents

**Access Level:** 🔵 **Basic User Access**

**Screens:** 15 screens including Homepage, Property Search, Application Form, My Rentals, Pay Rent, Maintenance, Messages, Profile

---

### 🚶 6. GUEST (Mtembeleji)

**Maelezo:** Mtu ambaye hajajisajili bado

**Majukumu:**
- Kuona properties (read-only)
- Kutafuta properties
- Kuona details za property (picha, bei, location)
- Kuona reviews
- Kuwasiliana na support

**Access Level:** ⚪ **Public Read-Only**

**Screens:** 4 screens including Homepage, Property Search, Property Details, Contact Support

---

### 🎧 7. SUPPORT AGENT

**Maelezo:** Mtu anayejibu maswali ya wateja

**Majukumu:**
- Kujibu tickets za tenants
- Kujibu tickets za landlords
- Kutatua matatizo ya kiufundi
- Kuwasiliana na Admin kwa issues kubwa
- Kuona FAQ na knowledge base

**Access Level:** 🟢 **Customer Support Access**

**Screens:** 6 screens including Dashboard, Tickets, Knowledge Base, FAQ, User Messages, Reports

---

### 🔧 8. MAINTENANCE STAFF (Fundi)

**Maelezo:** Mtu anayefanya ukarabati wa nyumba

**Majukumu:**
- Kuona maintenance requests zilizopangiwa
- Kubadilisha status ya request (in-progress, completed)
- Kuongeza notes za ukarabati uliofanywa
- Kuwasiliana na landlord au tenant

**Access Level:** 🔵 **Task-Based Access**

**Screens:** 5 screens including Dashboard, Assigned Tasks, Task Details, History, Messages

---

### 💰 9. ACCOUNTANT (Mhasibu)

**Maelezo:** Mtu anayeshughulikia pesa za mfumo

**Majukumu:**
- Kuona payments zote za tenants
- Kuona payouts za landlords
- Kuona platform revenue (commission)
- Kuzalisha financial reports
- Kuona tax calculations
- Kuverify payouts kabla ya kuidhinishwa na Admin

**Access Level:** 🟡 **Financial Read/Write**

**Screens:** 8 screens including Dashboard, Payments, Payouts, Revenue, Tax Reports, Financial Reports, Settings, Audit Logs

---

### 📊 10. INVESTOR (Mwekezaji)

**Maelezo:** Mtu aliyewekeza pesa kwenye kampuni

**Majukumu:**
- Kuona financial performance
- Kuona Key Performance Indicators (KPIs)
- Kuona growth metrics
- Kuona user statistics
- Kupakua reports (read-only)

**Access Level:** 🟣 **Read-Only Financial Access**

**Screens:** 5 screens including Financial Dashboard, Key Metrics, User Growth, Reports, Cap Table

---

## 🚀 Installation

### Prerequisites
- PHP >= 7.4
- Composer
- MySQL >= 5.7
- Node.js & NPM

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/manna.git
   cd manna
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database in `.env`**
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=manna
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run migrations**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Compile assets**
   ```bash
   npm run dev
   ```

7. **Start development server**
   ```bash
   php artisan serve
   ```

8. **Access the application**
   - Frontend: `http://localhost:8000`
   - Tenant Panel: `http://localhost:8000/tenant/dashboard`
   - Landlord Panel: `http://localhost:8000/landlord/dashboard`
   - Admin Panel: `http://localhost:8000/admin/dashboard`

---

## 🔐 Default Access Credentials

### Super Admin
- **Email:** `superadmin@kodi.com`
- **Password:** `password`
- **Access:** `/super-admin/dashboard`

### Admin
- **Email:** `admin@kodi.com`
- **Password:** `password`
- **Access:** `/admin/dashboard`

### Test Tenant
- **Email:** `tenant@kodi.com`
- **Password:** `password`
- **Access:** `/tenant/dashboard`

### Test Landlord
- **Email:** `landlord@kodi.com`
- **Password:** `password`
- **Access:** `/landlord/dashboard`

### Test Agent
- **Email:** `agent@kodi.com`
- **Password:** `password`
- **Access:** `/agent/dashboard`

⚠️ **Important:** Change default passwords after first login!

---

## 💳 Payment Integration

### M-Pesa (Daraja API)
- Configure in `.env`:
  ```
  MPESA_CONSUMER_KEY=your_consumer_key
  MPESA_CONSUMER_SECRET=your_consumer_secret
  MPESA_PASSKEY=your_passkey
  MPESA_SHORTCODE=your_shortcode
  ```

### Stripe
- Configure in `.env`:
  ```
  STRIPE_KEY=your_stripe_key
  STRIPE_SECRET=your_stripe_secret
  STRIPE_WEBHOOK_SECRET=your_webhook_secret
  ```

---

## 📊 Database Structure

Key tables:
- `users` - All user types (tenants, landlords, agents, admins)
- `properties` - Property listings
- `property_photos` - Property images
- `applications` - Rental applications
- `leases` - Lease agreements
- `rent_payments` - Monthly rent payments
- `maintenance_requests` - Maintenance issues
- `messages` - User communications
- `disputes` - Dispute records
- `reviews` - Landlord reviews
- `notifications` - System notifications
- `documents` - Lease agreements, receipts

---

## 🔄 Rental Process Flow

1. **Search** - Tenant searches for properties
2. **Apply** - Tenant submits application with personal info
3. **Review** - Landlord reviews application
4. **Approve** - Landlord approves/rejects application
5. **Lease** - System generates lease agreement (PDF)
6. **Deposit** - Tenant pays security deposit (2-3 months rent)
7. **Sign** - Tenant signs lease electronically
8. **Move-in** - Move-in inspection with photos
9. **Pay Rent** - Monthly rent payments with reminders
10. **Maintain** - Maintenance requests handled by landlord
11. **Move-out** - Move-out inspection
12. **Refund** - Deposit refund (minus damages)
13. **Review** - Tenant writes review

---

## 💰 Payment Structure

| Payment Type | Amount | Payer | Receiver |
|--------------|--------|-------|----------|
| Security Deposit | 2-3 months rent | Tenant | Landlord (held) |
| Monthly Rent | Rent amount | Tenant | Landlord (minus fees) |
| Platform Fee | 5-10% of rent | Landlord | Platform Manna |
| Agent Commission | 5-10% of rent | Landlord | Agent (optional) |
| Late Fee | Percentage (e.g., 5%) | Tenant | Landlord |

---

## 🛠️ Tech Stack

- **Backend:** Laravel 8.x
- **Frontend:** Blade Templates, Alpine.js
- **Database:** MySQL 5.7+
- **Payment:** M-Pesa Daraja API, Stripe
- **Maps:** Google Maps API
- **Communication:** Mailgun (Email), Twilio (SMS)
- **Authentication:** Laravel Sanctum
- **File Storage:** Local/S3
- **PDF Generation:** DomPDF/Snappy

---

## 📁 Project Structure

```
manna_appartments/
├── app/Http/Controllers/
│   ├── Tenant/
│   ├── Landlord/
│   ├── Agent/
│   ├── Admin/
│   └── SuperAdmin/
├── database/migrations/
├── resources/views/
│   ├── tenant/
│   ├── landlord/
│   ├── agent/
│   ├── admin/
│   └── super-admin/
└── public/
```

---

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📄 License

This project is proprietary software. All rights reserved.

---

## 📞 Support

For support, email support@manna.com or join our Slack channel.

---

**Built with ❤️ for Africa**
**Manna - Long-Term Rental Platform**
