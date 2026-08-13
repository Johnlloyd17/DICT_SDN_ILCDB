# DICT SDN ILCDB — Step-by-Step Guide

> **Project:** DICT Provincial Implementation Portal — Surigao del Norte  
> **System:** ICT Literacy & Competency Development Bureau (ILCDB)  
> **Stack:** Laravel 12 + MySQL + Blade + Alpine.js + Tailwind CSS

---

## 1. System Overview

This is a **provincial ICT program monitoring dashboard** with **6 modules**:

| Module | Purpose |
|--------|---------|
| **Overview** | Dashboard with KPIs, Leaflet map, FullCalendar, historical data |
| **DWIA-TMD** | Training participant management, batch tracking, penetration analysis, course/trainer hub |
| **DTC HUB** | Visitor log registry, foot traffic analytics, demographics & services charts |
| **SPARK** | Specialized training tracks, trainee records, financials |
| **PROJECT CLICK** | Device donation tracking, beneficiary management |
| **Funding Monitoring** | Financial ledger with CRUD, project cards, category/historical analysis |

---

## 2. Local Setup (XAMPP)

### Prerequisites
- **XAMPP** (PHP 8.2+, MySQL 8)
- **Composer**
- **Node.js** (v20+)

### Step-by-step

1. **Clone or place the project**
   ```
   The project is at: C:\xampp\htdocs\DICT_SDN_ILCDB\dict_sdn_ilcdb
   ```

2. **Configure environment**
   ```
   cp .env.example .env
   ```
   Edit `.env` with your database settings:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=dict_sdn_ilcdb
   DB_USERNAME=root
   DB_PASSWORD=
   ```

3. **Create MySQL database**
   Open phpMyAdmin or MySQL CLI and create:
   ```sql
   CREATE DATABASE dict_sdn_ilcdb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

4. **Run the setup script**
   ```bash
   composer install
   npm install
   php artisan key:generate
   php artisan migrate --seed
   npm run build
   ```

5. **Start the development server**
   ```bash
   php artisan serve
   ```
   The app runs at **http://127.0.0.1:8000**

6. **Login credentials** (from seeder)
   - Email: `admin@dict.gov.ph`
   - Password: `password`
   *(Check `database/seeders/DatabaseSeeder.php` for exact credentials)*

---

## 3. Directory Structure (Key Paths)

```
dict_sdn_ilcdb/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── DashboardController.php        # Main overview KPIs
│   │   │   ├── FundingController.php           # Funding CRUD
│   │   │   ├── Tmd/
│   │   │   │   ├── ParticipantController.php   # TMD participants + certificates
│   │   │   │   └── CourseController.php        # Course CRUD
│   │   │   ├── Dtc/
│   │   │   │   └── VisitorController.php       # DTC visitor CRUD
│   │   │   ├── Spark/
│   │   │   │   ├── TrainingController.php
│   │   │   │   └── TraineeController.php
│   │   │   ├── Click/
│   │   │   │   └── DeviceController.php
│   │   │   └── Api/                            # Chart data endpoints
│   │   └── Requests/
│   ├── Models/                                 # 12 Eloquent models
│   └── Providers/
├── resources/views/
│   ├── dashboard.blade.php                     # Overview dashboard
│   ├── funding/index.blade.php                  # Funding module
│   ├── tmd/participants/index.blade.php         # TMD module (all 4 sub-tabs)
│   ├── dtc/visitors/index.blade.php             # DTC module
│   ├── spark/trainings/index.blade.php          # SPARK trainings
│   ├── spark/trainees/index.blade.php           # SPARK trainees
│   ├── click/devices/index.blade.php            # CLICK module
│   ├── layouts/app.blade.php                   # Main layout
│   ├── components/                             # Reusable Blade components
│   └── auth/                                   # Login/register views
├── routes/
│   ├── web.php                                 # All application routes
│   └── auth.php                                # Auth routes
├── database/
│   ├── migrations/                             # 15 migration files
│   └── seeders/                                # 12 seeders with sample data
├── public/                                      # Entry point
└── package.json
```

---

## 4. Routes & URLs

| URL | Description |
|-----|-------------|
| `/` | Redirects to login |
| `/login` | Authentication page |
| `/dashboard` | Main overview |
| `/tmd/participants` | TMD participant management |
| `/dtc/visitors` | DTC hub visitor logs |
| `/spark/trainings` | SPARK trainings |
| `/spark/trainees` | SPARK trainees |
| `/click/devices` | PROJECT CLICK devices |
| `/funding` | Funding monitoring |
| `/export/{module}/csv` | CSV exports |
| `/api/*` | JSON endpoints for charts |

---

## 5. How Each Module Works

### 5.1 Overview Dashboard (`/dashboard`)
- **Controller:** `DashboardController@index`
- **View:** `resources/views/dashboard.blade.php`
- **Shows:** KPI cards (trainees, budget, foot traffic, beneficiaries), Leaflet map with DTC hub markers, FullCalendar with training events, per-project funding cards, annual historical table
- **Data sources:** All models aggregated

### 5.2 DWIA-TMD (`/tmd/participants`)
- **Controller:** `Tmd\ParticipantController`
- **View:** `resources/views/tmd/participants/index.blade.php`
- **4 sub-tabs (Alpine.js driven):**
  - **Participants** — Table with filters (batch, status, search), add/upload cert/view cert
  - **Tracker** — Batch training schedule with download template/export
  - **Penetration** — Charts (municipal bar chart, demographics doughnut), gender table
  - **Hub** — Course & trainer registry with add/edit/delete
- **Modals:** Add Participant, View Certificate, Upload Certificate, Add/Edit Course

### 5.3 DTC HUB (`/dtc/visitors`)
- **Controller:** `Dtc\VisitorController`
- **View:** `resources/views/dtc/visitors/index.blade.php`
- **Shows:** KPI cards, 3 Chart.js charts (foot traffic bar, demographics doughnut, services horizontal bar), visitor table with 4 filters, add/edit modals
- **Chart API endpoints:** `api/dtc/traffic`, `api/dtc/visitors`, `api/dtc/services`

### 5.4 SPARK (`/spark/trainings` & `/spark/trainees`)
- **Controllers:** `Spark\TrainingController`, `Spark\TraineeController`
- **Views:** `resources/views/spark/trainings/index.blade.php`, `resources/views/spark/trainees/index.blade.php`

### 5.5 PROJECT CLICK (`/click/devices`)
- **Controller:** `Click\DeviceController`
- **View:** `resources/views/click/devices/index.blade.php`

### 5.6 Funding Monitoring (`/funding`)
- **Controller:** `FundingController`
- **View:** `resources/views/funding/index.blade.php`
- **Shows:** KPI cards, 2 Chart.js charts (project bar, category doughnut), paginated table with filters, add/edit modal, historical data table

---

## 6. Database Schema (12 Tables)

| Table | Key Fields |
|-------|-----------|
| `users` | name, email, password, role |
| `training_batches` | batch_code, course_title, venue, target_count, enrolled_count, start_date, end_date, program, status |
| `courses` | course_code, title, specialty_track, format_type, duration_hours, credentials (JSON) |
| `trainers` | full_name, specialty, accreditation, status |
| `participants` | participant_code, full_name, training_batch_id (FK), agency_sector, municipality, completion_status, certificate_file |
| `dtc_hubs` | name, municipality, latitude, longitude, status |
| `dtc_visitor_logs` | log_code, visitor_name, gender, age, demographic_sector, dtc_hub_id (FK), services_ailed (JSON), session_duration, visit_date |
| `spark_trainings` | track_id, specialization, master_trainer, enrolled_count, budget_allocated, industry_partner, status |
| `spark_trainees` | trainee_code, full_name, specialty, course, municipality, employment_status, monthly_earnings |
| `click_devices` | batch_id, donation_date, device_type, quantity, beneficiary, municipality, status |
| `funding_records` | voucher_ref, project, description, expense_category, allocated, obligated, disbursed, transaction_date, status |
| `tmd_penetration` | municipality, male, female, total |

---

## 7. Frontend Architecture

| Technology | Usage |
|-----------|-------|
| **Blade** | Server-side templating (PHP) |
| **Alpine.js** | Client-side interactivity (modals, tabs, form bindings) |
| **Tailwind CSS** | Utility-first styling (responsive via `sm:`, `lg:` breakpoints) |
| **Chart.js** | 9 charts across modules (loaded from CDN) |
| **Leaflet.js** | Provincial map on dashboard (CDN) |
| **FullCalendar** | Training events calendar (CDN) |
| **FontAwesome** | Icons (CDN, v6.4.0) |

### Layout hierarchy:
```
layouts/app.blade.php       # Main shell (header, nav, content, footer)
  components/app-layout.blade.php  # Props wrapper
    dashboard.blade.php            # Overview
    tmd/participants/index.blade.php
    dtc/visitors/index.blade.php
    spark/trainings/index.blade.php
    spark/trainees/index.blade.php
    click/devices/index.blade.php
    funding/index.blade.php
```

---

## 8. Key Commands

```bash
# Development
php artisan serve              # Start PHP dev server
npm run dev                    # Start Vite dev (hot reload)
npm run build                  # Build frontend assets

# Database
php artisan migrate            # Run migrations
php artisan migrate:fresh      # Reset + run migrations
php artisan db:seed            # Seed sample data (12 seeders)
php artisan migrate:fresh --seed  # Reset + seed

# Tests
php artisan test               # Run PHPUnit tests

# Utilities
php artisan tinker             # Interactive PHP shell
php artisan make:model -m      # Create model + migration
php artisan make:controller    # Create controller
```

---

## 9. Responsive Breakpoints

| Viewport | Width | Behavior |
|----------|-------|----------|
| Mobile | < 640px | Hamburger drawer, stacked KPI cards, card-stack tables, bottom-sheet modals |
| Tablet | 640-1024px | Scrollable tabs, 2-column grid, 90% modal width |
| Desktop | > 1024px | Full inline tabs, 4-column KPI grid, full tables |

---

## 10. CSV Exports

Available at: `/export/{module}/csv`

Supported modules:
- `dashboard-history`
- `tmd-participants`
- `tmd-batches`
- `tmd-courses`
- `dtc-visitors`
- `spark-trainings`
- `spark-trainees`
- `click-devices`
- `funding`

---

## 11. Troubleshooting

| Issue | Solution |
|-------|----------|
| `APP_KEY` missing | Run `php artisan key:generate` |
| Blank page after login | Run `npm install && npm run build` |
| Database connection error | Check `.env` DB settings; ensure MySQL is running |
| Migration errors | Run `php artisan migrate:fresh` to reset |
| Charts not rendering | Check browser console for JS errors; ensure chart canvases have IDs |
| 419 Page Expired | Add `@csrf` to forms or clear session (`php artisan cache:clear`) |
| Asset 404 (Vite) | Run `npm run build` or `npm run dev` in separate terminal |
