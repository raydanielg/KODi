```
███╗   ███╗ █████╗ ███╗   ██╗███╗   ██╗ █████╗
████╗ ████║██╔══██╗████╗  ██║████╗  ██║██╔══██╗
██╔████╔██║███████║██╔██╗ ██║██╔██╗ ██║███████║
██║╚██╔╝██║██╔══██║██║╚██╗██║██║╚██╗██║██╔══██║
██║ ╚═╝ ██║██║  ██║██║ ╚████║██║ ╚████║██║  ██║
╚═╝     ╚═╝╚═╝  ╚═╝╚═╝  ╚═══╝╚═╝  ╚═══╝╚═╝  ╚═╝

██████╗ ██████╗ ██████╗ ██╗
██╔══██╗██╔══██╗╚════██╗██║
██║  ██║██████╔╝ █████╔╝██║
██║  ██║██╔══██╗ ╚═══██╗██║
██████╔╝██████╔╝██████╔╝███████╗
╚═════╝ ╚═════╝ ╚═════╝ ╚══════╝
```

<div align="center">

# KODI — Mfumo wa Usimamizi wa Nyumba za Kupanga

### Long-Term Rental Property Management Platform

[![Laravel](https://img.shields.io/badge/Laravel-8.x-red?style=flat-square&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-7.4+-blue?style=flat-square&logo=php)](https://php.net)
[![Bootstrap 5](https://img.shields.io/badge/Bootstrap-5.1-purple?style=flat-square&logo=bootstrap)](https://getbootstrap.com)
[![SQLite](https://img.shields.io/badge/SQLite-003B57?style=flat-square&logo=sqlite)](https://sqlite.org)
[![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange?style=flat-square&logo=mysql)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)](#license)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen?style=flat-square)](#contributing)

<br>

> **KODI** ni mfumo wa usimamizi wa nyumba za kupanga (long-term rental) ulioundwa hasa kwa ajili ya soko la Afrika.
> Haitumiki kwa kuuza nyumba — inawezesha mawasiliano na usimamizi kati ya wapangaji, wenyewe nyumba, na wakala.

<br>

**🌍 English:** KODI is a full-featured communication and management platform for long-term rental properties.
This is **not** a house-selling platform. Landlords list properties, tenants apply and pay rent, agents earn commissions, and parties communicate through the platform — all in one place.

</div>

<br>

---

## ✨ Features

| # | Feature | Description |
|---|---------|-------------|
| 🏠 | **Property Management** | List, edit, feature, and manage rental properties with images, amenities, maps, and status tracking |
| 👤 | **9 Role-Based Dashboards** | Super Admin, Admin, Landlord, Agent, Tenant, Support, Maintenance, Accountant, Investor |
| 📋 | **Tenant Applications** | Submit, review, approve/reject applications with status tracking and messaging |
| 📄 | **Lease Management** | Create leases with automatic renewals, digital documents, payment schedules, and late fee calculation |
| 💰 | **Rent Collection** | Track payments via M-Pesa, bank transfer, card, or cash; automatic reminders and receipts |
| 🔧 | **Maintenance Requests** | Tenants submit requests; landlords approve; staff get assigned; full lifecycle tracking |
| 💬 | **Real-Time Messaging** | Direct messaging between tenants, landlords, agents, and support |
| 🏆 | **Commission System** | Agents earn commissions on leases; track pending, approved, and paid commissions |
| 💸 | **Payout System** | Landlords and agents request payouts; admin review and processing flow |
| ⚖️ | **Dispute Resolution** | Raise disputes on payments, maintenance, deposits; assigned to support/admin |
| 💳 | **Wallet System** | Digital wallet for payouts, deposits, and platform transactions |
| 📁 | **Document Management** | Upload, verify, and manage lease agreements, receipts, and identification documents |
| ⭐ | **Reviews & Ratings** | Post-move-out reviews for properties and landlords with helpfulness voting |
| 📊 | **Audit Logging** | Full action audit trail with user, IP, user agent, and old/new value tracking |
| 📱 | **Mobile-Responsive UI** | Fully responsive design works on desktop, tablet, and mobile |

<br>

---

## 🛠 Tech Stack

| Layer | Technology |
|-------|-----------|
| **Backend** | Laravel 8.x (PHP 7.4+/8.0+) |
| **Frontend** | Blade Templates, Bootstrap 5.1, SCSS |
| **Database** | SQLite (dev) / MySQL 5.7+ (production) |
| **Build Tools** | Laravel Mix 6, Webpack 5, Sass |
| **Typography** | Google Fonts — Inter |
| **Auth** | Laravel Sanctum, Spatie Permission |
| **Payments** | M-Pesa (Daraja API), Stripe |
| **Notifications** | SweetAlert2, In-app notifications |
| **Assets** | Axios, Lodash, Popper.js |

<br>

---

## 📋 Requirements

| Dependency | Version |
|------------|---------|
| **PHP** | ^7.3 or ^8.0 |
| **Composer** | Latest stable |
| **Node.js** | 12+ (for asset compilation) |
| **NPM** | 6+ |
| **Database** | SQLite (default) or MySQL 5.7+ |

<br>

---

## 🚀 Installation

```bash
# 1. Clone the repository
git clone https://github.com/your-username/kodi.git
cd kodi

# 2. Install PHP dependencies
composer install

# 3. Configure environment
cp .env.example .env
php artisan key:generate

# 4. Configure database in .env (SQLite by default)
#    DB_CONNECTION=sqlite  (default, no changes needed)
#    OR for MySQL:
#    DB_CONNECTION=mysql
#    DB_HOST=127.0.0.1
#    DB_PORT=3306
#    DB_DATABASE=kodi
#    DB_USERNAME=root
#    DB_PASSWORD=

# 5. Run migrations and seeders
php artisan migrate --seed

# 6. Install and compile frontend assets
npm install && npm run dev

# 7. Start the development server
php artisan serve
```

Now access the app at **http://localhost:8000**

<br>

---

## 👤 Default Users & Roles

| Role | Email | Password | Dashboard URL |
|------|-------|----------|---------------|
| **Super Admin** | superadmin@manna.com | password | `/super-admin/dashboard` |
| **Admin** | admin@manna.com | password | `/admin/dashboard` |
| **Landlord** | landlord1@manna.com | password | `/landlord/dashboard` |
| **Agent** | agent1@manna.com | password | `/agent/dashboard` |
| **Tenant** | tenant1@manna.com | password | `/tenant/dashboard` |
| **Support** | support@manna.com | password | `/support/dashboard` |
| **Maintenance** | maintenance@manna.com | password | `/maintenance/dashboard` |
| **Accountant** | accountant@manna.com | password | `/accountant/dashboard` |
| **Investor** | investor@manna.com | password | `/investor/dashboard` |

> **⚠️ Important:** Change default passwords immediately after first login in a production environment.

<br>

---

## 🗄 Database Schema

The platform uses 21 migration tables. Here are the main entities:

| Table | Purpose | Key Relationships |
|-------|---------|-------------------|
| **users** | All user types (9 roles) | Has many properties, leases, payments, messages |
| **properties** | Rental property listings | Belongs to landlord, agent; has images, amenities |
| **property_images** | Property photos | Belongs to property |
| **property_amenities** | Amenities (WiFi, parking, etc.) | Belongs to property |
| **applications** | Tenant rental applications | Belongs to property, tenant, landlord |
| **leases** | Lease agreements | Belongs to property, tenant, landlord, agent |
| **rent_payments** | Monthly rent transactions | Belongs to lease, tenant, landlord, property |
| **maintenance_requests** | Maintenance issue tickets | Belongs to property, tenant; assigned to staff |
| **messages** | Direct messaging | Has sender, receiver; threaded via parent_id |
| **documents** | Uploaded files (leases, receipts, IDs) | Polymorphic; has uploader, verifier |
| **notifications** | In-app notifications | Morphs to notifiable (users) |
| **reviews** | Post-move-out reviews | Belongs to reviewer, property, landlord |
| **commissions** | Agent commission tracking | Belongs to lease, agent, landlord |
| **payouts** | Withdrawal requests | Belongs to user; has processing workflow |
| **disputes** | Conflict resolution | Raised by user, against another user; assigned to resolver |
| **audit_logs** | Full activity trail | Belongs to user; tracks action, entity, old/new values |
| **contact_messages** | Public contact form submissions | Standalone |

<br>

---

## 📁 Project Structure

```
kodi/
├── app/
│   ├── Console/           # Artisan commands
│   ├── Exceptions/        # Error handlers
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── SuperAdmin/   # Super admin dashboard & user mgmt
│   │   │   ├── Admin/        # Admin property, user, dispute mgmt
│   │   │   ├── Landlord/     # Landlord properties, leases, tenants
│   │   │   ├── Agent/        # Agent listings, applications, commissions
│   │   │   ├── Tenant/       # Tenant applications, payments, maintenance
│   │   │   ├── Support/      # Support tickets, chat, knowledge base
│   │   │   ├── Maintenance/  # Maintenance task assignment & completion
│   │   │   ├── Accountant/   # Financial reports, payouts, revenue
│   │   │   └── Investor/     # Read-only financial & growth metrics
│   │   └── Middleware/    # Auth, role-based middleware
│   ├── Models/            # Eloquent models (16 models)
│   ├── Providers/         # Service providers
│   └── Services/          # Business logic services
├── config/                # Application configuration
│   ├── permission.php     # Spatie Permission config
│   ├── mpesa.php          # M-Pesa Daraja API config
│   └── stripe.php         # Stripe payment config
├── database/
│   ├── migrations/        # 21 migration files
│   └── seeders/           # Database seeders
├── resources/
│   ├── views/             # Blade templates (role-based directories)
│   │   ├── layouts/
│   │   ├── super-admin/
│   │   ├── admin/
│   │   ├── landlord/
│   │   ├── agent/
│   │   ├── tenant/
│   │   ├── support/
│   │   ├── maintenance/
│   │   ├── accountant/
│   │   └── investor/
│   └── sass/              # SCSS stylesheets
├── routes/
│   ├── web.php            # 449 lines of role-based route definitions
│   ├── api.php            # API routes (extensible)
│   ├── admin.php
│   ├── agent.php
│   ├── landlord.php
│   ├── tenant.php
│   └── super-admin.php
├── public/                # Public assets
├── storage/               # Logs, cache, uploads
└── tests/                 # PHPUnit tests
```

<br>

---

## 👑 Roles & Permissions

### 1. Super Admin
**Msimamizi Mkuu** — Full system control
- Manage all admins and users
- System settings, feature flags, maintenance mode
- View logs, backups, database management
- Configure payment gateways (M-Pesa, Stripe)
- Role management

### 2. Admin
**Msimamizi** — Day-to-day operations
- Manage landlords, tenants, and agents
- Verify and approve new properties
- Handle disputes and announcements
- View reports and payment analytics

### 3. Landlord
**Mwenye Nyumba** — Property owner
- Add, edit, manage own properties
- Review and approve/reject tenant applications
- Create leases, track rent payments
- Respond to maintenance requests
- Request payouts

### 4. Agent
**Wakala** — Property agent
- Manage properties on behalf of multiple landlords
- Submit applications, earn commissions
- Track commission status and request payouts

### 5. Tenant
**Mpangaji** — Renter
- Search and browse properties
- Submit rental applications
- Sign leases, pay rent (M-Pesa/Card/Bank)
- Submit maintenance requests
- Send messages, write reviews

### 6. Support
**Msaada** — Customer support
- Handle support tickets
- FAQ and knowledge base management
- Direct messaging with users

### 7. Maintenance
**Matengenezo** — Maintenance staff
- View assigned maintenance tasks
- Update task status (in-progress, completed)
- Add resolution notes

### 8. Accountant
**Mhasibu** — Financial management
- View all payments and payouts
- Track platform revenue and commissions
- Generate financial and tax reports

### 9. Investor
**Mwekezaji** — Read-only investor dashboard
- View financial performance and KPIs
- Track growth metrics and user statistics
- Cap table access

<br>

---

## 📸 Screenshots

> Screenshots coming soon — stay tuned!

<br>

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

<br>

---

## 📄 License

This project is open source and available under the **MIT License**.

<br>

---

<div align="center">

**Built with ❤️ for Tanzania and the African rental market**

<i>KODI — Kodi ya Nyumba, Usimamizi Rahisi.</i>

</div>
