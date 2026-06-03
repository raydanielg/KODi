# 🏠 KODI - Long-Term Rental Platform

<div align="center">

**KODI** is a comprehensive long-term rental platform for the African market, connecting tenants and landlords for monthly/yearly rentals.

[![Laravel](https://img.shields.io/badge/Laravel-8.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-7.4+-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange.svg)](https://mysql.com)

</div>

---

## 📌 About KODI

**KODI** is an African name meaning "The System" - a platform designed specifically for long-term rentals (3 months to 1+ years), unlike short-term vacation rentals like Airbnb.

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

## 👥 User Panels

KODI provides dedicated panels for different user types:

### 1. 🧑 Tenant Panel (Wapangaji)
**Access:** `/tenant/dashboard`

**Features:**
- Search and browse properties
- Submit rental applications
- View application status
- Pay monthly rent (M-Pesa/Card/Bank)
- View payment history
- Submit maintenance requests
- Message landlords
- Download lease agreements
- Write reviews after move-out

**Screens:** 15 screens including Homepage, Property Search, Application Form, My Rentals, Pay Rent, Maintenance, Messages, Profile

---

### 2. 🏘️ Landlord Panel (Wamiliki)
**Access:** `/landlord/dashboard`

**Features:**
- Manage property listings
- Review tenant applications
- Approve/reject applications
- Generate lease agreements
- Track rent collection
- Send payment reminders
- Handle maintenance requests
- View tenant profiles
- Access financial reports

**Screens:** 17 screens including Dashboard, My Properties, Applications, Current Tenants, Rent Payments, Maintenance, Reports, Settings

---

### 3. 🏢 Agent Panel (Wakala) - Optional
**Access:** `/agent/dashboard`

**Features:**
- Manage property listings for multiple landlords
- Process tenant applications
- Track commission earnings (5-10% of rent)
- Request payouts
- Generate reports for landlords

**Screens:** 6 screens including Dashboard, My Listings, Applications, Commission, Payouts, Reports

---

### 4. 🛡️ Admin Panel (Wasimamizi)
**Access:** `/admin/dashboard`

**Features:**
- Manage all users (tenants, landlords, agents)
- Verify property listings
- Handle disputes between parties
- Send platform announcements
- View platform analytics
- Configure commission rates
- Manage payment gateways

**Screens:** 9 screens including Dashboard, Users, Properties, Payments, Disputes, Announcements, Reports, Settings

---

### 5. 👑 Super Admin Panel
**Access:** `/super-admin/dashboard`

**Features:**
- System health monitoring
- Database management
- Backup and restore
- System logs and error tracking
- Feature flags
- Payment gateway configuration
- Email/SMS settings
- Roles and permissions
- Maintenance mode

**Screens:** 9 screens including System Dashboard, Database, Backups, Logs, Features, Payment Gateways, Email/SMS, Roles, Maintenance

---

### 6. 💰 Investor Panel (Wawekezaji)
**Access:** `/investor/dashboard`

**Features:**
- Financial dashboard
- Key metrics (GMV, total rent collected)
- User growth analytics
- Financial reports (read-only)
- Cap table and ownership

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
   git clone https://github.com/yourusername/kodi.git
   cd kodi
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
   DB_DATABASE=kodi
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
| Platform Fee | 5-10% of rent | Landlord | Platform KODI |
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
kodi/
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

For support, email support@kodi.com or join our Slack channel.

---

**Built with ❤️ for Africa**
**KODI - Long-Term Rental Platform**
