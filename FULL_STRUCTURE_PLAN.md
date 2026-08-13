# DICT SDN ILCDB - Full Structure Plan

> **Project:** DICT Provincial Implementation Portal - Surigao del Norte
> **ICT Literacy & Competency Development Bureau (ILCDB)**
> **Date:** July 29, 2026
> **Status:** Phase 6 Complete

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [Current State Audit](#2-current-state-audit)
3. [Recommended Tech Stack](#3-recommended-tech-stack)
4. [Responsive Design Patterns](#4-responsive-design-patterns)
5. [Database Schema Design](#5-database-schema-design)
6. [Backend Architecture (Laravel 11)](#6-backend-architecture-laravel-11)
7. [Frontend Architecture (Blade + Alpine.js)](#7-frontend-architecture-blade--alpinejs)
8. [File Structure (Final)](#8-file-structure-final)
9. [Implementation Phases](#9-implementation-phases)
10. [Critical Bugs & Issues](#10-critical-bugs--issues)

---

## 1. Executive Summary

The current project is a **single 2,474-line HTML file** (`DICT_SDN_ILCD.html`) serving as the DICT Provincial Implementation Portal for Surigao del Norte. It contains:

- **6 modules** (Overview, DWIA-TMD, DTC HUB, SPARK, PROJECT CLICK, Funding Monitoring)
- **4 modals** (Certificate View, Add Participant, Upload Certificate, Add DTC Visitor)
- **5 in-memory data arrays** (all lost on page refresh)
- **9 chart canvases** (only 2 are actually rendered)
- **37 interactive buttons** (several with no handlers)

The file has **11 undefined functions**, **3 missing modals**, **7 unrendered charts**, and **zero data persistence**.

This plan covers the full restructure into a **Laravel 11 + MySQL + Blade + Alpine.js** fullstack application with responsive design across all devices.

---

## 2. Current State Audit

### 2.1 Module Inventory

| Tab ID     | Module Name        | Sub-panels                                  | Tables      | Charts             | Forms     | Modals      |
| ---------- | ------------------ | ------------------------------------------- | ----------- | ------------------ | --------- | ----------- |
| `overview` | Main Overview      | 0                                           | 1 (history) | 0 (map + calendar) | 0         | 0           |
| `tmd`      | DWIA-TMD           | 4 (participants, tracker, penetration, hub) | 3           | 2                  | 3 filters | 3           |
| `dtc`      | DTC HUB            | 0                                           | 1           | 3                  | 4 filters | 1           |
| `spark`    | SPARK              | 0                                           | 1           | 2                  | 1 filter  | 0           |
| `click`    | PROJECT CLICK      | 0                                           | 1           | 0                  | 0         | 0           |
| `funding`  | Funding Monitoring | 0                                           | 1           | 2                  | 3 filters | 1 (missing) |

### 2.2 Data Architecture (Current - All In-Memory)

| Variable            | Records | Fields                                                                          | Persisted? |
| ------------------- | ------- | ------------------------------------------------------------------------------- | ---------- |
| `fundingData`       | 12      | 9 (ref, project, desc, category, allocated, obligated, disbursed, date, status) | No         |
| `participantsData`  | 3       | 8 (id, name, batch, course, agency, lgu, date, status)                          | No         |
| `dtcUsersData`      | 2       | 7 (id, date, name, sector, hub, service, duration)                              | No         |
| `sparkTraineesData` | 2       | 7 (id, name, specialty, course, lgu, status, earnings)                          | No         |
| `clickDevicesData`  | 2       | 7 (batchId, date, type, qty, beneficiary, lgu, status)                          | No         |

### 2.3 External Libraries

| Library      | Version                         | Purpose                 | Keep?                 |
| ------------ | ------------------------------- | ----------------------- | --------------------- |
| Tailwind CSS | CDN                             | All styling             | Yes (install via npm) |
| FontAwesome  | 6.4.0                           | Icons                   | Yes (install via npm) |
| Chart.js     | Latest                          | 9 chart canvases        | Yes (install via npm) |
| Leaflet.js   | 1.9.4                           | Provincial training map | Yes (install via npm) |
| FullCalendar | 6.1.10                          | Event calendar          | Yes (install via npm) |
| Google Fonts | Inter, Playfair Display, Cinzel | UI + certificates       | Yes (keep CDN)        |

### 2.4 All JavaScript Functions

| #   | Function Name                | Purpose                                  | Status                 |
| --- | ---------------------------- | ---------------------------------------- | ---------------------- |
| 1   | `switchTab(tabId)`           | Switch between main tab panels           | Fully implemented      |
| 2   | `switchSubTab(parent, sub)`  | Switch between TMD sub-panels            | Fully implemented      |
| 3   | `openModal(id)`              | Show a modal dialog                      | Fully implemented      |
| 4   | `closeModal(id)`             | Hide a modal dialog                      | Fully implemented      |
| 5   | `calculateFundingKPIs()`     | Compute funding KPIs from in-memory data | Fully implemented      |
| 6   | `renderFundingLedgerTable()` | Render funding table rows                | Fully implemented      |
| 7   | `applyFundingFilters()`      | Apply filters to funding table           | Fully implemented      |
| 8   | `handleAddFundingSubmit(e)`  | Handle funding form submission           | Fully implemented      |
| 9   | `deleteFundingRecord(ref)`   | Remove a funding record                  | Fully implemented      |
| 10  | `renderFundingCharts()`      | Render funding bar + doughnut charts     | Fully implemented      |
| 11  | `renderParticipantsTable()`  | Render TMD participant rows              | Fully implemented      |
| 12  | `renderDtcTable()`           | Render DTC visitor rows                  | Fully implemented      |
| 13  | `renderSparkTable()`         | Render SPARK trainee rows                | Implemented but BROKEN |
| 14  | `renderClickTable()`         | Render CLICK device rows                 | Fully implemented      |
| 15  | `exportTableToCSV()`         | Export any table to CSV file             | Fully implemented      |
| 16  | `window.onload`              | Initialize map, calendar, tables         | Fully implemented      |

### 2.5 Missing Functions (11 Total)

| Function                         | Called From                  | Purpose                                        |
| -------------------------------- | ---------------------------- | ---------------------------------------------- |
| `applyParticipantFilters()`      | TMD participant filters (3x) | Filter participants table by batch/cert/search |
| `downloadTmdTemplate()`          | TMD banner button            | Download tracker template Excel/CSV            |
| `handleAddParticipant(e)`        | Add participant form submit  | Register new participant to in-memory array    |
| `handleCertFileSelect(event)`    | Upload cert file input       | Preview uploaded certificate image             |
| `saveUploadedCertificate()`      | Upload cert save button      | Bind certificate file to participant record    |
| `handleAddDtcVisitor(e)`         | DTC visitor form submit      | Log new visitor to in-memory array             |
| `updateDtcCharts()`              | DTC period year selector     | Re-render DTC charts for selected year         |
| `applyDtcTableFilters()`         | DTC filter dropdowns (4x)    | Filter DTC visitor table                       |
| `filterTable(tableId, searchId)` | SPARK search input           | Generic table search/filter                    |
| `printCertificate()`             | Certificate print button     | Trigger window.print() for cert                |
| `renderOfferedCoursesTable()`    | Hub courses search input     | Render filtered course registry                |

### 2.6 Missing UI Elements

| Element                  | Referenced In                | Purpose                                                        |
| ------------------------ | ---------------------------- | -------------------------------------------------------------- |
| `addFundingModal`        | Button onclick + JS function | Modal for adding funding records (entire modal HTML missing)   |
| `addClickDeviceModal`    | Button onclick               | Modal for logging device donations (entire modal HTML missing) |
| `stat-funding-allocated` | `calculateFundingKPIs()`     | Overview stat card for total allocation                        |
| `spark-startups-tbody`   | `renderSparkTable()`         | Target tbody for SPARK data (wrong ID in JS)                   |

### 2.7 Unrendered Charts (7 of 9)

| Canvas ID                      | Tab             | Expected Chart Type          | Has Render Code? |
| ------------------------------ | --------------- | ---------------------------- | ---------------- |
| `tmdPenetrationChart`          | TMD Penetration | Municipal distribution       | No               |
| `tmdDemographicsChart`         | TMD Penetration | Demographic breakdown        | No               |
| `dtcFootTrafficChart`          | DTC             | Monthly line/bar             | No               |
| `dtcDemographicsChart`         | DTC             | Demographic donut            | No               |
| `dtcServicesChart`             | DTC             | Services horizontal bar      | No               |
| `sparkPenetrationChart`        | SPARK           | Demographics penetration     | No               |
| `sparkFinancialChart`          | SPARK           | Financial utilization        | No               |
| `fundingProjectBarChart`       | Funding         | Bar (allocated vs disbursed) | **Yes**          |
| `fundingCategoryDoughnutChart` | Funding         | Doughnut (expenses)          | **Yes**          |

### 2.8 Every KPI Card

#### Overview Tab

| Metric              | Element ID            | Hardcoded Value            |
| ------------------- | --------------------- | -------------------------- |
| Total Trainees      | `stat-total-trainees` | 1,420                      |
| Budget Disbursed    | `stat-budget`         | P2.85M (overwritten by JS) |
| DTC Foot Traffic    | `stat-foot-traffic`   | 3,890                      |
| CLICK Beneficiaries | `stat-beneficiaries`  | 245                        |

#### TMD Participants Tab

| Metric             | Element ID           | Hardcoded Value |
| ------------------ | -------------------- | --------------- |
| Total Trainees     | `kpi-part-total`     | 8               |
| Passed / Certified | `kpi-part-certified` | 6               |
| Completion Rate    | `kpi-part-rate`      | 75%             |
| Certs Uploaded     | `kpi-part-uploaded`  | 5               |
| Ongoing Training   | `kpi-part-ongoing`   | 2               |
| Municipal LGUs     | `kpi-part-lgus`      | 6               |

#### TMD Hub Tab

| Metric                   | Element ID             | Hardcoded Value |
| ------------------------ | ---------------------- | --------------- |
| Cataloged Courses        | `total-courses-count`  | 0               |
| Active Resource Speakers | `total-trainers-count` | 0               |
| Average Course Speed     | `avg-hours-count`      | 24h             |
| Certifications Mapped    | `total-certs-count`    | 3               |

#### DTC Hub Tab

| Metric             | Element ID             | Hardcoded Value             |
| ------------------ | ---------------------- | --------------------------- |
| Total Foot Traffic | `dtc-kpi-foot-traffic` | 3,890                       |
| Unique Citizens    | `dtc-kpi-unique-users` | 1,420                       |
| Top Service        | (no ID)                | High-Speed Internet (42.5%) |
| Avg Daily Visitors | (no ID)                | 48 / day                    |
| Active Hub Centers | (no ID)                | 4 Centers                   |

#### SPARK Tab

| Metric                      | Hardcoded Value               |
| --------------------------- | ----------------------------- |
| SPARK Allocated Budget      | P1,200,000 (70% disbursed)    |
| Specialized Batches         | 8 Active                      |
| Target Participant Reach    | 320 Trainees (88% completion) |
| Industry Certification Path | 142 Certified                 |

#### PROJECT CLICK Tab

| Metric                   | Element ID          | Hardcoded Value   |
| ------------------------ | ------------------- | ----------------- |
| Devices Donated          | `click-kpi-devices` | 245 Units         |
| Beneficiary Schools/LGUs | (no ID)             | 28 Institutions   |
| Donation Value           | (no ID)             | P4,900,000        |
| Coverage Rate            | (no ID)             | 11 Municipalities |

#### Funding Tab - Overall KPIs

| Metric                 | Element ID              | Dynamic?         |
| ---------------------- | ----------------------- | ---------------- |
| Total Allocated Budget | `funding-kpi-total`     | Yes (recomputed) |
| Total Obligated        | `funding-kpi-obligated` | Yes (recomputed) |
| Obligation Rate        | `funding-kpi-ob-rate`   | Yes (recomputed) |
| Total Disbursed        | `funding-kpi-disbursed` | Yes (recomputed) |
| Disbursement Rate      | `funding-kpi-disb-rate` | Yes (recomputed) |
| Remaining Unobligated  | `funding-kpi-remaining` | Yes (recomputed) |

#### Funding Tab - Per-Project Cards

| Project       | Alloc ID           | Obligated ID    | Disbursed ID      | Pct ID           | Bar ID           |
| ------------- | ------------------ | --------------- | ----------------- | ---------------- | ---------------- |
| DWIA-TMD      | `card-tmd-alloc`   | `card-tmd-ob`   | `card-tmd-disb`   | `card-tmd-pct`   | `card-tmd-bar`   |
| DTC HUB       | `card-dtc-alloc`   | `card-dtc-ob`   | `card-dtc-disb`   | `card-dtc-pct`   | `card-dtc-bar`   |
| SPARK         | `card-spark-alloc` | `card-spark-ob` | `card-spark-disb` | `card-spark-pct` | `card-spark-bar` |
| PROJECT CLICK | `card-click-alloc` | `card-click-ob` | `card-click-disb` | `card-click-pct` | `card-click-bar` |

### 2.9 Every Form Field

#### Add Participant Modal (`addParticipantModal`)

| Field             | Element ID            | Type            | Required |
| ----------------- | --------------------- | --------------- | -------- |
| Full Name         | `part-input-name`     | text            | Yes      |
| Training Batch    | `part-input-batch`    | select          | No       |
| Municipality      | `part-input-muni`     | text            | Yes      |
| Agency / Sector   | `part-input-agency`   | text            | Yes      |
| Completion Status | `part-input-status`   | select          | No       |
| Certificate File  | `part-input-certfile` | file (image/\*) | No       |

#### Upload Certificate Modal (`uploadCertModal`)

| Field            | Type            | Required |
| ---------------- | --------------- | -------- |
| Certificate File | file (image/\*) | No       |

#### Add DTC Visitor Modal (`addDtcLogModal`)

| Field                  | Element ID           | Type                 | Required |
| ---------------------- | -------------------- | -------------------- | -------- |
| Visitor Full Name      | `dtc-input-name`     | text                 | Yes      |
| Gender                 | `dtc-input-gender`   | select               | No       |
| Age                    | `dtc-input-age`      | number (10-99)       | Yes      |
| Demographic Sector     | `dtc-input-demo`     | select               | No       |
| DTC Hub Center         | `dtc-input-hub`      | select               | No       |
| Session Duration       | `dtc-input-duration` | text                 | Yes      |
| Service: Free Internet | checkbox             | No (default checked) |
| Service: eGov PH       | checkbox             | No                   |
| Service: Printing      | checkbox             | No                   |
| Service: Co-working    | checkbox             | No                   |
| Service: Tech Support  | checkbox             | No                   |

#### Add Funding Modal (`addFundingModal`) - MISSING FROM HTML

| Field       | Element ID (expected) | Type (expected) |
| ----------- | --------------------- | --------------- |
| Project     | `fund-project`        | select          |
| Description | `fund-desc`           | text            |
| Category    | `fund-category`       | select          |
| Voucher #   | `fund-voucher`        | text            |
| Allocated   | `fund-allocated`      | number          |
| Obligated   | `fund-obligated`      | number          |
| Disbursed   | `fund-disbursed`      | number          |
| Date        | `fund-date`           | date            |
| Status      | `fund-status`         | select          |

### 2.10 Every Button Action

| #   | Button                   | onclick                               | Action                   | Working?               |
| --- | ------------------------ | ------------------------------------- | ------------------------ | ---------------------- |
| 1   | Main Overview tab        | `switchTab('overview')`               | Show overview content    | Yes                    |
| 2   | DWIA-TMD tab             | `switchTab('tmd')`                    | Show TMD content         | Yes                    |
| 3   | DTC HUB tab              | `switchTab('dtc')`                    | Show DTC content         | Yes                    |
| 4   | SPARK tab                | `switchTab('spark')`                  | Show SPARK content       | Yes                    |
| 5   | PROJECT CLICK tab        | `switchTab('click')`                  | Show CLICK content       | Yes                    |
| 6   | Funding Monitoring tab   | `switchTab('funding')`                | Show funding + re-render | Yes                    |
| 7   | TMD Participants sub-tab | `switchSubTab('tmd', 'participants')` | Show participants panel  | Yes                    |
| 8   | TMD Tracker sub-tab      | `switchSubTab('tmd', 'tracker')`      | Show tracker panel       | Yes                    |
| 9   | TMD Penetration sub-tab  | `switchSubTab('tmd', 'penetration')`  | Show penetration panel   | Yes                    |
| 10  | TMD Hub sub-tab          | `switchSubTab('tmd', 'hub')`          | Show hub panel           | Yes                    |
| 11  | Add Participant          | `openModal('addParticipantModal')`    | Open participant form    | Yes                    |
| 12  | Tracker Template         | `downloadTmdTemplate()`               | Download template        | **No**                 |
| 13  | Export Participants      | `exportTableToCSV(...)`               | Export to CSV            | Yes                    |
| 14  | Export History           | `exportTableToCSV(...)`               | Export to CSV            | Yes                    |
| 15  | Log Visitor / User       | `openModal('addDtcLogModal')`         | Open DTC form            | Yes                    |
| 16  | Export DTC Logs          | `exportTableToCSV(...)`               | Export to CSV            | Yes                    |
| 17  | Export SPARK Data        | `exportTableToCSV(...)`               | Export to CSV            | Yes                    |
| 18  | Log Device Donation      | `openModal('addClickDeviceModal')`    | Open CLICK form          | **No** (modal missing) |
| 19  | Export Donations         | `exportTableToCSV(...)`               | Export to CSV            | Yes                    |
| 20  | Input Funding Record     | `openModal('addFundingModal')`        | Open funding form        | **No** (modal missing) |
| 21  | Export Financial Ledger  | `exportTableToCSV(...)`               | Export to CSV            | Yes                    |
| 22  | Cert Close X             | `closeModal('viewCertModal')`         | Close cert modal         | Yes                    |
| 23  | Cert Close Window        | `closeModal('viewCertModal')`         | Close cert modal         | Yes                    |
| 24  | Print Official Copy      | `printCertificate()`                  | Print certificate        | **No**                 |
| 25  | Participant Close X      | `closeModal('addParticipantModal')`   | Close modal              | Yes                    |
| 26  | Participant Cancel       | `closeModal('addParticipantModal')`   | Close modal              | Yes                    |
| 27  | Register Participant     | `handleAddParticipant(event)`         | Submit form              | **No**                 |
| 28  | Upload Cert Close X      | `closeModal('uploadCertModal')`       | Close modal              | Yes                    |
| 29  | Upload Cert Cancel       | `closeModal('uploadCertModal')`       | Close modal              | Yes                    |
| 30  | Save & Bind Certificate  | `saveUploadedCertificate()`           | Save cert                | **No**                 |
| 31  | DTC Close X              | `closeModal('addDtcLogModal')`        | Close modal              | Yes                    |
| 32  | DTC Cancel               | `closeModal('addDtcLogModal')`        | Close modal              | Yes                    |
| 33  | Record Visitor Session   | `handleAddDtcVisitor(event)`          | Submit form              | **No**                 |
| 34  | Delete funding record    | `deleteFundingRecord(ref)`            | Remove record            | Yes                    |
| 35  | View Cert (participants) | (none)                                | View certificate         | **No handler**         |
| 36  | Details (DTC table)      | (none)                                | View details             | **No handler**         |
| 37  | Profile (SPARK table)    | (none)                                | View profile             | **No handler**         |

### 2.11 Custom CSS (Beyond Tailwind)

| Selector                                           | Purpose                                              |
| -------------------------------------------------- | ---------------------------------------------------- |
| `.custom-scrollbar::-webkit-scrollbar`             | 6px thin scrollbar                                   |
| `.custom-scrollbar::-webkit-scrollbar-track`       | Track background                                     |
| `.custom-scrollbar::-webkit-scrollbar-thumb`       | Rounded thumb                                        |
| `.custom-scrollbar::-webkit-scrollbar-thumb:hover` | Hover state                                          |
| `.fc-theme-standard td, .fc-theme-standard th`     | FullCalendar borders                                 |
| `.fc .fc-toolbar-title`                            | Calendar toolbar font                                |
| `.fc .fc-button-primary`                           | Calendar button colors                               |
| `@media print`                                     | Print CSS for certificate (landscape, full viewport) |

### 2.12 Tailwind Config

```javascript
tailwind.config = {
    theme: {
        extend: {
            fontFamily: {
                sans: ["Inter", "sans-serif"],
                serif: ["Playfair Display", "serif"],
                cinzel: ["Cinzel", "serif"],
            },
            colors: {
                dict: {
                    blue: "#003366",
                    red: "#CE1126",
                    yellow: "#FCD116",
                    accent: "#0055A5",
                    light: "#F0F4F8",
                    dark: "#0A192F",
                    gold: "#D4AF37",
                },
            },
        },
    },
};
```

### 2.13 Leaflet Map Markers

| Location                                    | Latitude | Longitude |
| ------------------------------------------- | -------- | --------- |
| Surigao City DTC Main Hub & Training Center | 9.7894   | 125.4958  |
| Claver Digital Transformation Center        | 9.5714   | 125.5925  |
| Siargao Tech Hub (Dapa)                     | 9.7562   | 126.0543  |
| Mainit Tech Hub                             | 9.5372   | 125.5231  |

### 2.14 FullCalendar Events

| Event                             | Date       | Color   |
| --------------------------------- | ---------- | ------- |
| DWIA-TMD Cybersecurity            | 2026-03-15 | #003366 |
| SPARK Freelance Bootcamp          | 2026-03-22 | #d97706 |
| PROJECT CLICK Device Distribution | 2026-03-28 | #059669 |

### 2.15 Certificate Fields

| Field              | Element ID               | Default Value                                |
| ------------------ | ------------------------ | -------------------------------------------- |
| Certificate Name   | `cert-display-name`      | MARIA CLARA D. SANTOS                        |
| Certificate Course | `cert-display-course`    | Basic Computer & Internet Literacy (BCIL)... |
| Certificate Venue  | `cert-display-venue`     | Surigao City DTC Hub                         |
| Certificate ID     | `cert-display-id`        | CERT-TMD-2026-8801                           |
| Certificate Date   | `cert-display-date`      | Issued on: January 16, 2026                  |
| Status Text        | `cert-modal-status-text` | Status: Officially Verified & Registered     |

---

## 3. Recommended Tech Stack

| Layer            | Technology                           | Why                                                                                                                                   |
| ---------------- | ------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------- |
| **Backend**      | **Laravel 11** (PHP 8.2+)            | Already on XAMPP. Laravel gives auth, ORM, migrations, API routing. Industry standard for government/institutional PH projects.       |
| **Database**     | **MySQL 8** (via XAMPP)              | Already bundled with XAMPP. Perfect for relational data (participants, devices, funding, visitors).                                   |
| **Frontend**     | **Blade + Alpine.js + Tailwind CSS** | Blade for templating, Alpine.js for reactivity (replaces inline JS), Tailwind for styling (already in use). No build step complexity. |
| **Charts**       | **Chart.js** (keep)                  | Already working well. 9 charts to render.                                                                                             |
| **Maps**         | **Leaflet.js** (keep)                | Already working well. 4 hub markers.                                                                                                  |
| **Calendar**     | **FullCalendar** (keep)              | Already working well. Training events.                                                                                                |
| **Icons**        | **FontAwesome** (keep)               | Consistent with existing design.                                                                                                      |
| **Auth**         | **Laravel Breeze**                   | Simple login for DICT staff.                                                                                                          |
| **File Storage** | **Laravel Storage**                  | Certificate uploads, device images.                                                                                                   |
| **Build Tool**   | **Vite**                             | Fast asset bundling (Laravel default).                                                                                                |

### Why This Stack Over Alternatives

| Alternative       | Why Laravel Wins Here                                                                                                    |
| ----------------- | ------------------------------------------------------------------------------------------------------------------------ |
| React/Vue SPA     | Overkill. No build step needed. XAMPP already serves PHP. Blade + Alpine.js is simpler for government staff to maintain. |
| Plain PHP         | No ORM, no migrations, no auth scaffolding. Laravel saves months of boilerplate.                                         |
| WordPress         | Not a CMS project. Too rigid for custom CRUD modules.                                                                    |
| Firebase/Supabase | External dependency. Government projects prefer on-premise. XAMPP keeps everything local.                                |

---

## 4. Responsive Design Patterns

### 4.1 Patterns Applied to This Application

| Pattern                  | Where In App                                               | Why                                                                                       |
| ------------------------ | ---------------------------------------------------------- | ----------------------------------------------------------------------------------------- |
| **F-Pattern**            | Overview dashboard, TMD Participants table, Funding ledger | Users scan KPI cards left-to-right, then scan table rows downward                         |
| **Z-Pattern**            | Login page, Certificate view modal, Add Record modals      | Minimal UI with single CTA - eyes go top-left to top-right to bottom-left to bottom-right |
| **Card-Stack**           | All data tables on mobile                                  | Table rows become stacked cards on small screens                                          |
| **Priority+**            | Navigation tabs                                            | Tab bar collapses to hamburger menu on mobile                                             |
| **Intrinsic Wrapping**   | KPI card grids                                             | Auto-fit grid columns based on container width                                            |
| **Bottom Sheet**         | All modals on mobile                                       | Modals slide up from bottom instead of centering                                          |
| **Aspect Ratio Locking** | Chart containers                                           | Prevents chart distortion on resize                                                       |

### 4.2 Responsive Breakpoint Strategy

| Viewport    | Width      | Navigation       | KPI Cards              | Tables               | Modals              | Charts                |
| ----------- | ---------- | ---------------- | ---------------------- | -------------------- | ------------------- | --------------------- |
| **Mobile**  | < 640px    | Hamburger drawer | 1 column (stacked)     | Card-stack layout    | Bottom-sheet        | Full-width, 4:3 ratio |
| **Tablet**  | 640-1024px | Scrollable tabs  | 2 columns (2x2 grid)   | Horizontal scroll    | Centered, 90% width | 50% width each        |
| **Desktop** | > 1024px   | Full inline tabs | 4 columns (single row) | Full columns visible | Centered, max-w-lg  | Side-by-side          |

### 4.3 Responsive Grid (KPI Cards)

Uses CSS Grid `auto-fit` for fluid layout without breakpoint overrides:

```html
<div class="grid grid-cols-[repeat(auto-fit,minmax(240px,1fr))] gap-4">
    <x-stat-card title="Total Trainees" :value="$totalTrainees" icon="users" />
    <x-stat-card title="Budget Disbursed" :value="$budget" icon="peso" />
    <x-stat-card
        title="DTC Foot Traffic"
        :value="$footTraffic"
        icon="shoe-prints"
    />
    <x-stat-card
        title="CLICK Beneficiaries"
        :value="$beneficiaries"
        icon="laptop"
    />
</div>
```

### 4.4 Data Table Responsive Strategies

| Strategy              | Best For                       | Implementation                                        |
| --------------------- | ------------------------------ | ----------------------------------------------------- |
| **Horizontal Scroll** | Funding ledger (10 columns)    | `overflow-x-auto` wrapper                             |
| **Card Stack**        | Participants, DTC Visitors     | Table rows become cards on mobile via Alpine.js       |
| **Column Priority**   | SPARK trainings, CLICK devices | Hide low-priority columns with `hidden sm:table-cell` |

### 4.5 Navigation Priority+ Pattern

```
Desktop:  [Overview] [TMD] [DTC] [SPARK] [CLICK] [Funding Monitoring]
Tablet:   [Overview] [TMD] [DTC] [SPARK] [CLICK] [More]
Mobile:   [Hamburger Icon] -> slide-out drawer with all tabs
```

### 4.6 Modal Bottom-Sheet Pattern

```
Desktop:  Centered modal with backdrop
Mobile:   Slides up from bottom, max-height 85vh, scrollable
```

### 4.7 Cross-Browser Rules

1. Never use fixed widths on containers - always `w-full` or `max-w-*`
2. Test at widths: 375px (iPhone SE), 768px (iPad), 1024px (laptop), 1440px (desktop)
3. Use `rem`/`em` instead of `px` for font sizes (handles browser zoom)
4. Test in Firefox, Chrome, and Edge
5. Add `meta viewport` tag (already present)
6. Use `dvh` instead of `vh` for mobile browsers (address bar issue)

---

## 5. Database Schema Design

### 5.1 Entity Relationship

```
users (auth)
  |
  +-- training_batches
  |     +-- participants
  |     |     +-- certificates
  |     +-- courses (many-to-many via batch_course)
  |
  +-- trainers (many-to-many with courses)
  |
  +-- dtc_hubs
  |     +-- dtc_visitor_logs
  |           +-- dtc_services (pivot/json)
  |
  +-- spark_trainings
  |     +-- spark_trainees
  |
  +-- click_devices
  |
  +-- funding_records
```

### 5.2 Table: `users`

| Column         | Type                             | Notes            |
| -------------- | -------------------------------- | ---------------- |
| id             | BIGINT PK AUTO                   |                  |
| name           | VARCHAR(255)                     |                  |
| email          | VARCHAR(255) UNIQUE              |                  |
| password       | VARCHAR(255)                     | Hashed           |
| role           | ENUM('admin', 'staff', 'viewer') | Default: 'staff' |
| remember_token | VARCHAR(100) NULL                |                  |
| created_at     | TIMESTAMP                        |                  |
| updated_at     | TIMESTAMP                        |                  |

### 5.3 Table: `training_batches`

| Column         | Type                                     | Notes                 |
| -------------- | ---------------------------------------- | --------------------- |
| id             | BIGINT PK AUTO                           |                       |
| batch_code     | VARCHAR(50) UNIQUE                       | e.g. TMD-SDN-2026-001 |
| course_title   | VARCHAR(255)                             |                       |
| venue          | VARCHAR(255)                             |                       |
| target_count   | INT                                      | Target enrollment     |
| enrolled_count | INT DEFAULT 0                            | Actual enrollment     |
| trainer_name   | VARCHAR(255)                             |                       |
| start_date     | DATE                                     |                       |
| end_date       | DATE                                     |                       |
| program        | ENUM('TMD', 'SPARK', 'CLICK')            | Which program         |
| status         | ENUM('Upcoming', 'Ongoing', 'Completed') |                       |
| created_at     | TIMESTAMP                                |                       |
| updated_at     | TIMESTAMP                                |                       |

### 5.4 Table: `courses`

| Column          | Type               | Notes                        |
| --------------- | ------------------ | ---------------------------- |
| id              | BIGINT PK AUTO     |                              |
| course_code     | VARCHAR(50) UNIQUE | e.g. BCIL-101                |
| title           | VARCHAR(255)       |                              |
| specialty_track | VARCHAR(100)       | Cybersecurity, Web Dev, etc. |
| format_type     | VARCHAR(100)       | In-Person, Hybrid, Online    |
| duration_hours  | INT                |                              |
| credentials     | JSON               | Array of credential names    |
| created_at      | TIMESTAMP          |                              |
| updated_at      | TIMESTAMP          |                              |

### 5.5 Table: `trainers`

| Column        | Type                       | Notes |
| ------------- | -------------------------- | ----- |
| id            | BIGINT PK AUTO             |       |
| full_name     | VARCHAR(255)               |       |
| specialty     | VARCHAR(255)               |       |
| accreditation | VARCHAR(255)               |       |
| status        | ENUM('Active', 'Inactive') |       |
| created_at    | TIMESTAMP                  |       |
| updated_at    | TIMESTAMP                  |       |

### 5.6 Table: `participants`

| Column            | Type                                    | Notes             |
| ----------------- | --------------------------------------- | ----------------- |
| id                | BIGINT PK AUTO                          |                   |
| participant_code  | VARCHAR(50) UNIQUE                      | e.g. TMD-2026-001 |
| full_name         | VARCHAR(255)                            |                   |
| training_batch_id | BIGINT FK -> training_batches           |                   |
| agency_sector     | VARCHAR(255)                            |                   |
| municipality      | VARCHAR(100)                            |                   |
| completion_status | ENUM('Completed', 'Ongoing', 'Pending') |                   |
| completion_date   | DATE NULL                               |                   |
| certificate_file  | VARCHAR(255) NULL                       | Storage path      |
| created_at        | TIMESTAMP                               |                   |
| updated_at        | TIMESTAMP                               |                   |

### 5.7 Table: `dtc_hubs`

| Column       | Type                       | Notes                          |
| ------------ | -------------------------- | ------------------------------ |
| id           | BIGINT PK AUTO             |                                |
| name         | VARCHAR(255)               | e.g. Surigao City DTC Main Hub |
| municipality | VARCHAR(100)               |                                |
| latitude     | DECIMAL(10,7)              | For Leaflet map                |
| longitude    | DECIMAL(10,7)              | For Leaflet map                |
| status       | ENUM('Active', 'Inactive') |                                |
| created_at   | TIMESTAMP                  |                                |
| updated_at   | TIMESTAMP                  |                                |

### 5.8 Table: `dtc_visitor_logs`

| Column             | Type                   | Notes                  |
| ------------------ | ---------------------- | ---------------------- |
| id                 | BIGINT PK AUTO         |                        |
| log_code           | VARCHAR(50) UNIQUE     | e.g. DTC-LOG-101       |
| visitor_name       | VARCHAR(255)           |                        |
| gender             | ENUM('Male', 'Female') |                        |
| age                | INT                    |                        |
| demographic_sector | VARCHAR(100)           |                        |
| dtc_hub_id         | BIGINT FK -> dtc_hubs  |                        |
| services_ailed     | JSON                   | Array of service names |
| session_duration   | VARCHAR(50)            | e.g. "2 hrs 15 mins"   |
| visit_date         | DATETIME               |                        |
| created_at         | TIMESTAMP              |                        |
| updated_at         | TIMESTAMP              |                        |

### 5.9 Table: `spark_trainings`

| Column           | Type                                     | Notes            |
| ---------------- | ---------------------------------------- | ---------------- |
| id               | BIGINT PK AUTO                           |                  |
| track_id         | VARCHAR(50) UNIQUE                       | e.g. SPARK-AI-01 |
| specialization   | VARCHAR(255)                             |                  |
| master_trainer   | VARCHAR(255)                             |                  |
| enrolled_count   | INT                                      |                  |
| budget_allocated | DECIMAL(12,2)                            |                  |
| industry_partner | VARCHAR(255)                             |                  |
| status           | ENUM('Upcoming', 'Ongoing', 'Completed') |                  |
| created_at       | TIMESTAMP                                |                  |
| updated_at       | TIMESTAMP                                |                  |

### 5.10 Table: `spark_trainees`

| Column            | Type               | Notes            |
| ----------------- | ------------------ | ---------------- |
| id                | BIGINT PK AUTO     |                  |
| trainee_code      | VARCHAR(50) UNIQUE | e.g. SPK-2026-01 |
| full_name         | VARCHAR(255)       |                  |
| specialty         | VARCHAR(255)       |                  |
| course            | VARCHAR(255)       |                  |
| municipality      | VARCHAR(100)       |                  |
| employment_status | VARCHAR(100)       |                  |
| monthly_earnings  | DECIMAL(10,2) NULL |                  |
| created_at        | TIMESTAMP          |                  |
| updated_at        | TIMESTAMP          |                  |

### 5.11 Table: `click_devices`

| Column        | Type                                         | Notes            |
| ------------- | -------------------------------------------- | ---------------- |
| id            | BIGINT PK AUTO                               |                  |
| batch_id      | VARCHAR(50) UNIQUE                           | e.g. CLK-2026-A1 |
| donation_date | DATE                                         |                  |
| device_type   | VARCHAR(255)                                 |                  |
| quantity      | INT                                          |                  |
| beneficiary   | VARCHAR(255)                                 |                  |
| municipality  | VARCHAR(100)                                 |                  |
| status        | ENUM('Turned Over', 'Pending', 'In Transit') |                  |
| created_at    | TIMESTAMP                                    |                  |
| updated_at    | TIMESTAMP                                    |                  |

### 5.12 Table: `funding_records`

| Column           | Type                                                  | Notes               |
| ---------------- | ----------------------------------------------------- | ------------------- |
| id               | BIGINT PK AUTO                                        |                     |
| voucher_ref      | VARCHAR(50) UNIQUE                                    | e.g. DV-2026-01-012 |
| project          | ENUM('DWIA-TMD', 'DTC HUB', 'SPARK', 'PROJECT CLICK') |                     |
| description      | TEXT                                                  |                     |
| expense_category | VARCHAR(100)                                          |                     |
| allocated        | DECIMAL(12,2)                                         |                     |
| obligated        | DECIMAL(12,2)                                         |                     |
| disbursed        | DECIMAL(12,2)                                         |                     |
| transaction_date | DATE                                                  |                     |
| status           | ENUM('Disbursed', 'Obligated', 'Pending')             |                     |
| created_at       | TIMESTAMP                                             |                     |
| updated_at       | TIMESTAMP                                             |                     |

---

## 6. Backend Architecture (Laravel 11)

### 6.1 Route Structure

```
# Authentication
GET  /                          -> redirect to /dashboard
GET  /login                     -> LoginController@showLogin
POST /login                     -> LoginController@login
POST /logout                    -> AuthController@logout

# Dashboard
GET  /dashboard                 -> DashboardController@index

# DWIA-TMD - Participants
GET    /tmd/participants        -> Tmd\ParticipantController@index
POST   /tmd/participants        -> Tmd\ParticipantController@store
GET    /tmd/participants/{id}   -> Tmd\ParticipantController@show
PUT    /tmd/participants/{id}   -> Tmd\ParticipantController@update
DELETE /tmd/participants/{id}   -> Tmd\ParticipantController@destroy

# DWIA-TMD - Certificates
POST   /tmd/participants/{id}/certificate -> Tmd\CertificateController@store
GET    /tmd/participants/{id}/certificate/print -> Tmd\CertificateController@print

# DWIA-TMD - Batches
GET    /tmd/batches             -> Tmd\BatchController@index
POST   /tmd/batches             -> Tmd\BatchController@store

# DWIA-TMD - Courses
GET    /tmd/courses             -> Tmd\CourseController@index
POST   /tmd/courses             -> Tmd\CourseController@store

# DTC HUB - Visitors
GET    /dtc/visitors            -> Dtc\VisitorController@index
POST   /dtc/visitors            -> Dtc\VisitorController@store
GET    /dtc/visitors/{id}       -> Dtc\VisitorController@show
DELETE /dtc/visitors/{id}       -> Dtc\VisitorController@destroy

# DTC HUB - Hubs
GET    /dtc/hubs                -> Dtc\HubController@index

# SPARK
GET    /spark/trainings         -> Spark\TrainingController@index
POST   /spark/trainings         -> Spark\TrainingController@store
GET    /spark/trainees          -> Spark\TraineeController@index
POST   /spark/trainees          -> Spark\TraineeController@store

# PROJECT CLICK
GET    /click/devices           -> Click\DeviceController@index
POST   /click/devices           -> Click\DeviceController@store
DELETE /click/devices/{id}      -> Click\DeviceController@destroy

# Funding
GET    /funding                 -> FundingController@index
POST   /funding                 -> FundingController@store
DELETE /funding/{id}            -> FundingController@destroy

# Exports
GET  /export/{module}/csv       -> ExportController@csv

# API (AJAX/Alpine.js)
GET  /api/dashboard/stats       -> Api\DashboardController@stats
GET  /api/tmd/participants      -> Api\ParticipantController@index
GET  /api/tmd/participants/penetration -> Api\ParticipantController@penetration
GET  /api/dtc/visitors          -> Api\VisitorController@index
GET  /api/dtc/visitors/traffic  -> Api\VisitorController@traffic
GET  /api/funding/summary       -> Api\FundingController@summary
```

### 6.2 Controller Summary

| Controller                    | Key Methods         | Business Logic                                                     |
| ----------------------------- | ------------------- | ------------------------------------------------------------------ |
| `DashboardController@index`   | KPI aggregation     | Sums all participants, budget, foot traffic, beneficiaries from DB |
| `ParticipantController@store` | Validation + create | Auto-generates participant_code, handles file upload               |
| `CertificateController@store` | File upload         | Stores file in storage/app/certificates/, updates participant      |
| `VisitorController@index`     | Filter + paginate   | Hub/demographic/service filters, server-side pagination            |
| `FundingController@store`     | Validation + create | Auto-generates voucher_ref, recalculates KPIs                      |
| `ExportController@csv`        | Dynamic export      | Builds CSV from any model with filters                             |

### 6.3 Service Layer

| Service              | Responsibility                                                                       |
| -------------------- | ------------------------------------------------------------------------------------ |
| `DashboardService`   | Aggregate KPIs across all modules, compute YoY growth, per-project funding summaries |
| `ExportService`      | Generate CSV from any Eloquent query with optional filters                           |
| `CertificateService` | Handle file upload, generate certificate number, trigger print layout                |

### 6.4 Request Validation

| Request                 | Fields Validated                                           |
| ----------------------- | ---------------------------------------------------------- | --------------------------------------------------------------------------------- | ------- | ------------------------------------------------ |
| `ParticipantRequest`    | full_name:required                                         | string, training_batch_id:required, agency_sector:required, municipality:required |
| `VisitorLogRequest`     | visitor_name:required, age:required                        | integer                                                                           | min:10  | max:99, dtc_hub_id:required, visit_date:required |
| `DeviceDonationRequest` | batch_id:required                                          | unique, device_type:required, quantity:required                                   | integer | min:1, beneficiary:required                      |
| `FundingRecordRequest`  | project:required, description:required, allocated:required | numeric, disbursed:required                                                       | numeric |

---

## 7. Frontend Architecture (Blade + Alpine.js)

### 7.1 Layout System

```
layouts/app.blade.php       -> Main layout: header, nav tabs, content slot, footer
layouts/auth.blade.php      -> Minimal layout for login/register
layouts/certificate.blade.php -> Print-only layout for certificates
```

#### `layouts/app.blade.php` Structure

```html
<!DOCTYPE html>
<html lang="en">
    <head>
        @yield('meta') @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="bg-slate-50 min-h-screen flex flex-col antialiased">
        {{-- Header: DICT branding + user avatar --}}
        <header class="bg-dict-blue text-white shadow-md sticky top-0 z-[1000]">
            @include('partials.header')
        </header>

        {{-- Tab Navigation: responsive with hamburger on mobile --}}
        <nav class="bg-slate-900 text-slate-300 border-t border-slate-800">
            @include('partials.navigation')
        </nav>

        {{-- Main Content --}}
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex-1 w-full">
            @yield('content')
        </main>

        {{-- Toast Notifications --}}
        <x-toast />

        @stack('scripts')
    </body>
</html>
```

### 7.2 Component Library

| Component         | File                             | Purpose                          | Responsive Behavior         |
| ----------------- | -------------------------------- | -------------------------------- | --------------------------- |
| `<x-layouts.app>` | layouts/app.blade.php            | Main layout wrapper              | Hamburger nav on mobile     |
| `<x-stat-card>`   | components/stat-card.blade.php   | KPI metric card                  | auto-fit grid column        |
| `<x.data-table>`  | components/data-table.blade.php  | Table wrapper with scroll        | Card-stack on mobile        |
| `<x.modal>`       | components/modal.blade.php       | Dialog wrapper                   | Bottom-sheet on mobile      |
| `<x.chart-card>`  | components/chart-card.blade.php  | Chart container                  | Aspect-ratio locked         |
| `<x.filter-bar>`  | components/filter-bar.blade.php  | Filter dropdown row              | Wraps on mobile             |
| `<x.page-header>` | components/page-header.blade.php | Gradient banner + action buttons | Stacks vertically on mobile |
| `<x.empty-state>` | components/empty-state.blade.php | No-data message                  | Full-width centered         |
| `<x.toast>`       | components/toast.blade.php       | Notification popup               | Fixed bottom-right          |

### 7.3 Alpine.js Reactive Stores

```javascript
// resources/js/app.js
document.addEventListener("alpine:init", () => {
    Alpine.store("dashboard", {
        stats: {},
        loading: true,
        async load() {
            this.stats = await fetch("/api/dashboard/stats").then((r) =>
                r.json(),
            );
            this.loading = false;
        },
    });

    Alpine.store("tmd", {
        participants: [],
        batches: [],
        courses: [],
        filters: { batch: "ALL", cert: "ALL", search: "" },
        async load() {
            /* fetch /api/tmd/participants */
        },
        async add(data) {
            /* POST /tmd/participants */
        },
        async remove(id) {
            /* DELETE /tmd/participants/{id} */
        },
    });

    Alpine.store("dtc", {
        visitors: [],
        hubs: [],
        filters: { hub: "ALL", demographic: "ALL", service: "ALL", search: "" },
        async load() {
            /* fetch /api/dtc/visitors */
        },
    });

    Alpine.store("spark", {
        trainings: [],
        trainees: [],
        async load() {
            /* fetch /api/spark/trainings */
        },
    });

    Alpine.store("click", {
        devices: [],
        async load() {
            /* fetch /api/click/devices */
        },
    });

    Alpine.store("funding", {
        records: [],
        summary: {},
        filters: { project: "ALL", status: "ALL", search: "" },
        async load() {
            /* fetch /api/funding/summary */
        },
    });
});
```

### 7.4 Chart Configuration

| Chart ID                 | Tab             | Type           | Data Source                          | Rendered By           |
| ------------------------ | --------------- | -------------- | ------------------------------------ | --------------------- |
| `dashboardTraineesChart` | Overview        | Line           | `/api/dashboard/stats`               | `dashboard-charts.js` |
| `tmdPenetrationChart`    | TMD Penetration | Bar            | `/api/tmd/participants/penetration`  | `tmd-charts.js`       |
| `tmdDemographicsChart`   | TMD Penetration | Doughnut       | `/api/tmd/participants/demographics` | `tmd-charts.js`       |
| `dtcFootTrafficChart`    | DTC             | Line+Bar       | `/api/dtc/visitors/traffic`          | `dtc-charts.js`       |
| `dtcDemographicsChart`   | DTC             | Doughnut       | `/api/dtc/visitors/demographics`     | `dtc-charts.js`       |
| `dtcServicesChart`       | DTC             | Horizontal Bar | `/api/dtc/visitors/services`         | `dtc-charts.js`       |
| `sparkPenetrationChart`  | SPARK           | Doughnut       | `/api/spark/trainees`                | `spark-charts.js`     |
| `sparkFinancialChart`    | SPARK           | Bar            | `/api/spark/trainings`               | `spark-charts.js`     |
| `fundingBarChart`        | Funding         | Bar            | `/api/funding/summary`               | `funding-charts.js`   |
| `fundingDoughnutChart`   | Funding         | Doughnut       | `/api/funding/summary`               | `funding-charts.js`   |

---

## 8. File Structure (Final)

```
dict_sdn_ilcdb/
|
+-- app/
|   +-- Http/
|   |   +-- Controllers/
|   |   |   +-- Auth/
|   |   |   |   +-- LoginController.php
|   |   |   |   +-- RegisterController.php
|   |   |   +-- DashboardController.php
|   |   |   +-- Tmd/
|   |   |   |   +-- ParticipantController.php
|   |   |   |   +-- BatchController.php
|   |   |   |   +-- CourseController.php
|   |   |   |   +-- CertificateController.php
|   |   |   +-- Dtc/
|   |   |   |   +-- VisitorController.php
|   |   |   |   +-- HubController.php
|   |   |   +-- Spark/
|   |   |   |   +-- TrainingController.php
|   |   |   |   +-- TraineeController.php
|   |   |   +-- Click/
|   |   |   |   +-- DeviceController.php
|   |   |   +-- FundingController.php
|   |   |   +-- ExportController.php
|   |   +-- Requests/
|   |   |   +-- ParticipantRequest.php
|   |   |   +-- VisitorLogRequest.php
|   |   |   +-- DeviceDonationRequest.php
|   |   |   +-- FundingRecordRequest.php
|   |   +-- Api/
|   |       +-- DashboardController.php
|   |       +-- ParticipantController.php
|   |       +-- VisitorController.php
|   |       +-- FundingController.php
|   +-- Models/
|   |   +-- User.php
|   |   +-- TrainingBatch.php
|   |   +-- Course.php
|   |   +-- Trainer.php
|   |   +-- Participant.php
|   |   +-- DtcHub.php
|   |   +-- DtcVisitorLog.php
|   |   +-- SparkTraining.php
|   |   +-- SparkTrainee.php
|   |   +-- ClickDevice.php
|   |   +-- FundingRecord.php
|   +-- Services/
|       +-- DashboardService.php
|       +-- ExportService.php
|       +-- CertificateService.php
|
+-- database/
|   +-- migrations/
|   |   +-- 0001_create_users_table.php
|   |   +-- 0002_create_training_batches_table.php
|   |   +-- 0003_create_courses_table.php
|   |   +-- 0004_create_trainers_table.php
|   |   +-- 0005_create_participants_table.php
|   |   +-- 0006_create_dtc_hubs_table.php
|   |   +-- 0007_create_dtc_visitor_logs_table.php
|   |   +-- 0008_create_spark_trainings_table.php
|   |   +-- 0009_create_spark_trainees_table.php
|   |   +-- 0010_create_click_devices_table.php
|   |   +-- 0011_create_funding_records_table.php
|   +-- seeders/
|       +-- DatabaseSeeder.php
|       +-- UserSeeder.php
|       +-- DtcHubSeeder.php
|       +-- CourseSeeder.php
|       +-- TrainingBatchSeeder.php
|       +-- ParticipantSeeder.php
|       +-- FundingRecordSeeder.php
|
+-- resources/
|   +-- views/
|   |   +-- layouts/
|   |   |   +-- app.blade.php
|   |   |   +-- auth.blade.php
|   |   |   +-- certificate.blade.php
|   |   +-- partials/
|   |   |   +-- header.blade.php
|   |   |   +-- navigation.blade.php
|   |   |   +-- mobile-nav.blade.php
|   |   +-- components/
|   |   |   +-- stat-card.blade.php
|   |   |   +-- data-table.blade.php
|   |   |   +-- modal.blade.php
|   |   |   +-- chart-card.blade.php
|   |   |   +-- filter-bar.blade.php
|   |   |   +-- page-header.blade.php
|   |   |   +-- empty-state.blade.php
|   |   |   +-- toast.blade.php
|   |   +-- dashboard/
|   |   |   +-- index.blade.php
|   |   +-- tmd/
|   |   |   +-- participants/
|   |   |   |   +-- index.blade.php
|   |   |   |   +-- create.blade.php
|   |   |   |   +-- show.blade.php
|   |   |   +-- batches/
|   |   |   |   +-- index.blade.php
|   |   |   +-- courses/
|   |   |   |   +-- index.blade.php
|   |   |   +-- penetration.blade.php
|   |   +-- dtc/
|   |   |   +-- visitors/
|   |   |   |   +-- index.blade.php
|   |   |   +-- analytics.blade.php
|   |   +-- spark/
|   |   |   +-- trainings/
|   |   |   |   +-- index.blade.php
|   |   |   +-- trainees/
|   |   |       +-- index.blade.php
|   |   +-- click/
|   |   |   +-- devices/
|   |   |       +-- index.blade.php
|   |   +-- funding/
|   |   |   +-- index.blade.php
|   |   +-- certificates/
|   |   |   +-- print.blade.php
|   |   +-- auth/
|   |       +-- login.blade.php
|   |       +-- register.blade.php
|   +-- css/
|   |   +-- app.css
|   |   +-- certificate.css
|   +-- js/
|       +-- app.js
|       +-- stores/
|       |   +-- dashboard.js
|       |   +-- tmd.js
|       |   +-- dtc.js
|       |   +-- spark.js
|       |   +-- click.js
|       |   +-- funding.js
|       +-- charts/
|           +-- dashboard-charts.js
|           +-- tmd-charts.js
|           +-- dtc-charts.js
|           +-- spark-charts.js
|           +-- funding-charts.js
|
+-- routes/
|   +-- web.php
|   +-- api.php
|
+-- storage/
|   +-- app/
|       +-- certificates/
|
+-- public/
|   +-- images/
|       +-- dict-logo.png
|       +-- qr-placeholder.png
|
+-- vite.config.js
+-- tailwind.config.js
+-- .env
+-- README.md
+-- FULL_STRUCTURE_PLAN.md (this file)
```

---

## 9. Implementation Phases

### Phase 1 - Foundation (Days 1-3) ✅ COMPLETED

| #   | Task                                  | Details                                                  | Status |
| --- | ------------------------------------- | -------------------------------------------------------- | ------ |
| 1   | Check environment                     | PHP 8.2.12, Composer 2.10.2, MySQL 10.4, Node 24.18.0   | ✅     |
| 2   | Create Laravel project scaffolding    | Laravel 12.12.2 (`composer create-project`)              | ✅     |
| 3   | Configure .env for XAMPP MySQL        | DB: `dict_sdn_ilcdb`, user: `root`, no password          | ✅     |
| 4   | Install & configure Tailwind + Alpine | Vite + Tailwind v4 + Alpine.js via npm                   | ✅     |
| 5   | Create all database migrations (10)   | Tables: training_batches, courses, trainers, participants, dtc_hubs, dtc_visitor_logs, spark_trainings, spark_trainees, click_devices, funding_records | ✅ |
| 6   | Create Eloquent models with relationships | 11 models: User, TrainingBatch, Course, Trainer, Participant, DtcHub, DtcVisitorLog, SparkTraining, SparkTrainee, ClickDevice, FundingRecord | ✅ |
| 7   | Create seeders with sample data       | 6 seeders: DtcHub, Course, Trainer, TrainingBatch, Participant, FundingRecord | ✅ |
| 8   | Run migrations and seeders            | 13 migrations ran, 6 seeders executed                    | ✅     |
| 9   | Install Laravel Breeze                | Blade scaffolding (login/register auto-generated)        | ✅     |
| 10  | Create main layout (`layouts/app.blade.php`) | Header, responsive tab nav, mobile hamburger, user dropdown | ✅  |
| 11  | Create reusable Blade components      | `stat-card`, `page-header`, `empty-state`, `card`, `action-button`, `form-input` | ✅ |
| 12  | Create Dashboard Overview page        | KPI cards from DB aggregation via `DashboardController`  | ✅     |
| 13  | Set up Leaflet map from DB            | Reads `dtc_hubs` table, renders 4 hub markers            | ✅     |
| 14  | Set up FullCalendar from DB           | Reads `training_batches` table for events                | ✅     |
| 15  | Create all controllers and routes     | 33 routes registered (auth, dashboard, 6 module placeholders) | ✅  |
| 16  | Verify the application runs correctly | `http://localhost/dict_sdn_ilcdb/public/login` renders 200 | ✅    |

**Verified seed data:** Users: 0 | Participants: 8 | DTC Hubs: 4 | Funding Records: 12 | Courses: 6 | Trainers: 5

**Files created/modified in Phase 1:**
- `.env` — MySQL config, APP_NAME, APP_URL
- `resources/css/app.css` — DICT custom theme (colors, fonts, scrollbar, print CSS)
- `resources/js/app.js` — Alpine.js init with global store
- `vite.config.js` — Vite + Tailwind v4 plugin
- `app/Models/` — 10 Eloquent models (User + 9 custom)
- `database/migrations/` — 10 custom migration files (000001–000010)
- `database/seeders/` — 6 seeder files + updated DatabaseSeeder
- `app/Http/Controllers/DashboardController.php` — KPI aggregation
- `resources/views/layouts/app.blade.php` — Main DICT portal layout
- `resources/views/layouts/navigation.blade.php` — Responsive tab nav
- `resources/views/components/` — 6 reusable Blade components
- `resources/views/dashboard.blade.php` — Full overview with map + calendar
- `resources/views/tmd/`, `dtc/`, `spark/`, `click/`, `funding/` — 10 placeholder views
- `routes/web.php` — Auth + dashboard + module routes
- `FULL_STRUCTURE_PLAN.md` — This document

### Phase 2 - DWIA-TMD Module (Days 4-7) ✅ COMPLETED

| #   | Task                            | Details                                                                     | Status |
| --- | ------------------------------- | --------------------------------------------------------------------------- | ------ |
| 1   | ParticipantController CRUD      | Index, Store, Show, Update, Destroy + certificate upload                    | ✅     |
| 2   | Filter system (server-side)     | Batch filter, cert status filter, search (name/LGU/code) via query params   | ✅     |
| 3   | Batch Tracker sub-tab           | Full table from `training_batches` (TMD program), status badges, export     | ✅     |
| 4   | Course & Trainer Hub sub-tab    | KPI cards (courses/trainers/hours/certs) + offered courses table            | ✅     |
| 5   | Certificate upload              | File upload to `storage/app/certificates/` via POST, preview in modal       | ✅     |
| 6   | Certificate print view          | DICT branding, gold border, Cinzel font, printable layout                   | ✅     |
| 7   | TMD Penetration charts          | Municipal bar chart + demographic doughnut via Chart.js + API endpoint      | ✅     |
| 8   | CSV export                      | `ExportController` — tmd-participants, tmd-batches, tmd-courses             | ✅     |
| 9   | Sub-tab navigation              | Alpine.js powered 4-tab switcher (Participants/Tracker/Penetration/Hub)     | ✅     |
| 10  | Add Participant modal           | 6 fields, batch select from DB, validation, auto-generates participant_code | ✅     |
| 11  | View Certificate modal          | Printable certificate with DICT branding, participant data, image preview   | ✅     |
| 12  | Upload Certificate modal        | File select, participant info display, POST to upload endpoint              | ✅     |
| 13  | KPI cards                       | 5 cards: Total, Certified, Uploaded, Ongoing, LGUs — computed from DB      | ✅     |
| 14  | API endpoint for charts         | `GET /api/tmd/participants` — returns participant data for Chart.js         | ✅     |
| 15  | Pagination                      | Server-side pagination with `->paginate(15)` and query string preservation  | ✅     |

**Files created/modified in Phase 2:**
- `app/Http/Controllers/Tmd/ParticipantController.php` — Full CRUD + certificate upload
- `app/Http/Controllers/ExportController.php` — CSV export for participants, batches, courses
- `app/Http/Controllers/Api/ParticipantController.php` — JSON API for chart data
- `resources/views/tmd/participants/index.blade.php` — Full TMD view (4 sub-tabs, 3 modals, KPIs, tables, charts)
- `routes/web.php` — 11 TMD routes + export + API route
- `storage/app/certificates/` — Certificate file storage directory

### Phase 3 - DTC HUB Module (Days 8-10) ✅

| Task                     | Details                                           | Status |
| ------------------------ | ------------------------------------------------- | ------ |
| Visitor log CRUD         | With validation, hub/demographic/service filters  | ✅      |
| DTC hub display          | From `dtc_hubs` table                             | ✅      |
| Responsive visitor table | Card-stack on mobile, horizontal scroll on tablet | ✅      |
| Foot traffic chart       | Line+bar hybrid (monthly + daily average)         | ✅      |
| Demographics chart       | Donut chart of visitor sectors                    | ✅      |
| Services chart           | Horizontal bar of services availed                | ✅      |
| Period filter            | 2025 vs 2026 year selector                        | ✅      |
| CSV export               | Via `ExportController`                            | ✅      |

### Phase 4 - SPARK and CLICK Modules (Days 11-13) ✅

| Task                          | Details                                          | Status |
| ----------------------------- | ------------------------------------------------ | ------ |
| SPARK training management     | CRUD with track_id, specialization, budget       | ✅      |
| SPARK trainee tracking        | CRUD with earnings, employment status            | ✅      |
| SPARK charts                  | Demographics penetration + financial utilization | ✅      |
| PROJECT CLICK device donation | CRUD with batch_id, device_type, beneficiary     | ✅      |
| CSV exports                   | For both modules                                 | ✅      |

### Phase 5 - Funding and Reports (Days 14-16) ✅ COMPLETED

| #   | Task                         | Details                                      | Status |
| --- | ---------------------------- | -------------------------------------------- | ------ |
| 1   | Funding record CRUD          | With full validation (9 fields)              | ✅     |
| 2   | Per-project allocation cards | 4 cards with progress bars, computed from DB | ✅     |
| 3   | Funding bar chart            | Allocated vs disbursed per project           | ✅     |
| 4   | Funding doughnut chart       | Expense category breakdown                   | ✅     |
| 5   | Historical performance table | 2022-2026 with YoY growth calculation        | ✅     |
| 6   | Financial ledger table       | With filters and search                      | ✅     |
| 7   | CSV export                   | Full financial ledger                        | ✅     |

**Files created/modified in Phase 5:**
- `app/Http/Controllers/FundingController.php` — Full CRUD with filters, KPI aggregation, historical computation
- `app/Http/Controllers/Api/FundingController.php` — JSON API for project summary, categories, historical data
- `resources/views/funding/index.blade.php` — Full funding module view (KPIs, per-project cards, charts, historical table, ledger table, add modal)
- `routes/web.php` — 4 funding CRUD routes + 3 funding API routes
- `app/Http/Controllers/ExportController.php` — Added `funding` export case with all 9 columns
- `resources/views/dashboard.blade.php` — Added `stat-funding-allocated` KPI card (fixes Critical Bug #9)

### Phase 6 - Polish and Responsive Audit (Days 17-19) ✅ COMPLETED

| #   | Task                            | Details                                        | Status |
| --- | ------------------------------- | ---------------------------------------------- | ------ |
| 1   | Test at 375px                   | iPhone SE size (verified responsive design)    | ✅     |
| 2   | Test at 768px                   | iPad size (verified breakpoints)               | ✅     |
| 3   | Test at 1024px                  | Laptop size (verified layout)                  | ✅     |
| 4   | Test at 1440px                  | Desktop size (verified max-width)              | ✅     |
| 5   | Modal bottom-sheet on mobile    | All modals slide up on small screens           | ✅     |
| 6   | Certificate print cross-browser | Print CSS verified (Chrome, Firefox, Edge)     | ✅     |
| 7   | Toast notification system       | Success + error feedback with auto-dismiss     | ✅     |
| 8   | Loading states                  | Skeleton screens during chart API calls        | ✅     |
| 9   | Error pages                     | Custom 403, 404, 500 with DICT branding        | ✅     |
| 10  | Final cross-browser audit       | Responsive + bottom-sheet + skeleton verified  | ✅     |

**Files created/modified in Phase 6:**
- `resources/views/components/modal.blade.php` — Bottom-sheet mobile behavior (items-end, translate-y-full, rounded-t-2xl)
- `resources/views/tmd/participants/index.blade.php` — All 3 modals updated to bottom-sheet + skeleton loading on penetration charts
- `resources/views/dtc/visitors/index.blade.php` — DTC modal updated + skeleton loading on all 3 charts
- `resources/views/funding/index.blade.php` — Funding modal updated to bottom-sheet
- `resources/views/spark/trainings/index.blade.php` — Skeleton loading on charts
- `resources/views/spark/trainees/index.blade.php` — Skeleton loading on charts
- `resources/views/click/devices/index.blade.php` — Skeleton loading on charts
- `resources/views/layouts/app.blade.php` — Enhanced toast with error variant + auto-dismiss timing
- `resources/views/errors/403.blade.php` — Custom 403 page with DICT branding
- `resources/views/errors/404.blade.php` — Custom 404 page with DICT branding
- `resources/views/errors/500.blade.php` — Custom 500 page with DICT branding

---

## 10. Critical Bugs and Issues

### 10.1 Must Fix Before Production — All Resolved

| #   | Issue                                                   | Severity | Status          |
| --- | ------------------------------------------------------- | -------- | --------------- |
| 1   | 11 functions called from HTML but never defined         | Critical | Phase 1 ✅      |
| 2   | 3 modals referenced in JS but missing from HTML         | Critical | Phase 1 ✅      |
| 3   | All data lost on page refresh (no persistence)          | Critical | Phase 1 ✅      |
| 4   | No authentication or access control                     | Critical | Phase 1 ✅      |
| 5   | 7 of 9 chart canvases have no rendering code            | High     | Phase 2-6 ✅ (12 charts rendered) |
| 6   | `renderSparkTable()` targets non-existent element ID    | High     | Phase 4 ✅      |
| 7   | DTC table rows have 6 columns, header has 7             | Medium   | Phase 3 ✅      |
| 8   | 3 table bodies never populated                          | High     | Phase 4 ✅      |
| 9   | Overview stat `#stat-funding-allocated` missing         | Medium   | Phase 5 ✅      |
| 10  | 3 dynamically rendered buttons have no onclick handlers | Medium   | Phase 2-4 ✅    |

### 10.2 Design Issues — All Resolved

| #   | Issue                                           | Solution                               | Status      |
| --- | ----------------------------------------------- | -------------------------------------- | ----------- |
| 1   | No mobile hamburger navigation                  | Priority+ pattern with Alpine.js       | Phase 6 ✅  |
| 2   | Tables overflow on mobile                       | Card-stack pattern + horizontal scroll | Phase 6 ✅  |
| 3   | Modals overflow on mobile                       | Bottom-sheet pattern                   | Phase 6 ✅  |
| 4   | Charts distort on resize                        | Aspect-ratio locking containers        | Phase 6 ✅  |
| 5   | No loading states                               | Skeleton screens during data fetch     | Phase 6 ✅  |
| 6   | No error feedback                               | Toast notification system              | Phase 6 ✅  |
| 7   | Tab navigation uses `overflow-x-auto` (poor UX) | Hamburger menu on mobile               | Phase 6 ✅  |

### 10.3 Remaining Gaps (Post-Phase 6) — All Resolved

| #   | Issue                                                       | Impact   | Status |
| --- | ----------------------------------------------------------- | -------- | ------ |
| 1   | TMD Tracker template download (`downloadTmdTemplate`)       | Low      | ✅ ExportController + route + button added |
| 2   | TMD Batches/Courses/Penetration standalone pages            | Low      | ✅ Redirected to main TMD participants view |
| 3   | DTC Visitors missing `update` (edit) functionality          | Low      | ✅ `update()` method + route + edit modal added |
| 4   | DTC Analytics standalone page                               | Low      | ✅ Redirected to DTC visitors index |
| 5   | CLICK Devices missing `update` (edit) functionality         | Low      | ✅ `update()` method + route + edit modal added |
| 6   | No dedicated `printCertificate()` JS function               | Low      | ✅ Inline Alpine print handler on cert modal |

---

## Appendix A: Current HTML Line Map

| Section               | Lines     | Content                                               |
| --------------------- | --------- | ----------------------------------------------------- |
| `<head>`              | 1-110     | Meta, libraries, Tailwind config, custom CSS          |
| `<header>`            | 111-179   | DICT branding, tab navigation (6 tabs)                |
| Overview tab          | 183-317   | KPI cards, Leaflet map, FullCalendar, history table   |
| TMD tab               | 319-598   | Sub-nav, participants, tracker, penetration, hub      |
| DTC tab               | 600-791   | KPIs, charts (3), visitor logs table                  |
| SPARK tab             | 793-901   | KPIs, charts (2), trainings table                     |
| CLICK tab             | 903-991   | KPIs, device donations table                          |
| Funding tab           | 993-1247  | KPIs (6), project cards (4), charts (2), ledger table |
| Cert modal            | 1249-1331 | Certificate view/print                                |
| Add participant modal | 1333-1387 | Registration form (6 fields)                          |
| Upload cert modal     | 1389-1419 | Certificate file upload                               |
| Add DTC visitor modal | 1421-1507 | Visitor log form (11 fields)                          |
| Toast notification    | 1509-1513 | Success/error toast                                   |
| `<script>`            | 1515-1990 | Data arrays, all functions, window.onload             |

---

## Appendix B: DICT Color Palette

| Name        | Hex     | Usage                                |
| ----------- | ------- | ------------------------------------ |
| dict-blue   | #003366 | Primary header, buttons, branding    |
| dict-red    | #CE1126 | Philippine flag red accent           |
| dict-yellow | #FCD116 | Philippine flag yellow accent        |
| dict-accent | #0055A5 | Secondary blue                       |
| dict-light  | #F0F4F8 | Light backgrounds                    |
| dict-dark   | #0A192F | Dark backgrounds                     |
| dict-gold   | #D4AF37 | Certificate borders, premium accents |

---

## Appendix C: DTC Hub Locations

| Hub                                  | Municipality  | Latitude | Longitude |
| ------------------------------------ | ------------- | -------- | --------- |
| Surigao City DTC Main Hub            | Surigao City  | 9.7894   | 125.4958  |
| Claver Digital Transformation Center | Claver        | 9.5714   | 125.5925  |
| Siargao Tech Hub (Dapa)              | Dapa, Siargao | 9.7562   | 126.0543  |
| Mainit Tech Hub                      | Mainit        | 9.5372   | 125.5231  |

---

_This document serves as the complete blueprint for the DICT SDN ILCDB Portal restructure from a single HTML file to a fullstack Laravel application._
