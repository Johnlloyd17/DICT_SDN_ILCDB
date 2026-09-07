# DESIGN REVIEW — Proposed Booking/Queue System for DTC Hub Visitor & Service Availed Register

**Project:** DICT SDN ILCDB (Laravel 12)
**Feature Area:** DTC Hub — Visitor & Service Availed Register
**Status:** Planning only — no code changes

---

## 1. CURRENT DATABASE SCHEMA & STATE

### 1.1 `dtc_hubs` table (the "centers" table)

| Column | Type | Notes |
|---|---|---|
| `id` | bigIncrements | PK |
| `name` | string | Hub name |
| `municipality` | string | |
| `latitude` | decimal(10,7) | |
| `longitude` | decimal(10,7) | |
| `status` | enum(Active, Inactive) | |
| `created_at` / `updated_at` | timestamps | |

**4 seeded hubs:** Surigao City DTC Main Hub, Claver Digital Hub, Siargao Tech Hub (Dapa), Mainit Tech Hub.

### 1.2 `dtc_visitor_logs` table (existing visitor logging)

| Column | Type | Notes |
|---|---|---|
| `id` | bigIncrements | PK |
| `log_code` | string(50) | Unique, auto-generated `DTC-{YEAR}-{SEQ}` |
| `visitor_name` | string | Inlined — no FK to a visitors table |
| `gender` | enum(Male, Female) | |
| `age` | integer | |
| `demographic_sector` | string(100) | Hardcoded 5-value list |
| `dtc_hub_id` | foreignId | FK → `dtc_hubs.id`, cascadeOnDelete |
| `services_ailed` | json | JSON array of service name strings |
| `session_duration` | string(50) | Free-text, e.g. "1 hr 30 mins" |
| `visit_date` | dateTime | |
| `created_at` / `updated_at` | timestamps | |

**8 seeded visitor log entries** across the 4 hubs.

### 1.3 `dtc_center_inventories` table

22 columns covering location (congressional_district, province, municipality_city, barangay, center_name), GPS (longitude, latitude), verification/status fields (verified, moa_date_of_signing, date_of_launching, date_of_platform_registration), TCMS fields (tcms_status, tcms_key, tcms_identifier, tcms_verification_status), odk_status, connectivity_status, type_of_center_host, operational_status, and timestamps.

This is a **separate registry table** — not the same as `dtc_hubs`.

### 1.4 Other key facts

- **No `visitors` table** — visitor identity is just a `visitor_name` string repeated on every log row
- **No `services` table** — the 5 services are hardcoded in 4 separate Blade/PHP files and stored as a JSON array
- **No queue/ticket system exists** — zero infrastructure for it
- **No `visit_services` pivot** — services are a flat JSON blob

### 1.5 Hardcoded service list (5 services, duplicated in 4 files)

1. Free High-Speed Internet
2. eGov PH & Government Portal Access
3. Printing & Document Scanning
4. Co-working & Freelance Space
5. Tech Assistance & Consultation

**Locations:**
- `resources/views/dtc/visitors/index.blade.php` (lines ~128 and ~197) — Add and Edit visitor modals
- `resources/views/dtc/visitors/partials/dashboard.blade.php` (line ~205) — Services filter dropdown
- `database/seeders/DtcVisitorLogSeeder.php` (lines 12-21) — Seeder data
- `app/Http/Controllers/Dtc/VisitorController.php` (line ~466) — Default fallback

### 1.6 Hardcoded demographic sector list

`Student / Youth`, `Senior Citizen / PWD`, `Jobseeker / Out-of-School Youth`, `MSME / Freelancer`, `LGU / Govt Employee`

### 1.7 All existing tables in the project (21 total)

| Table | Module |
|---|---|
| `users`, `password_reset_tokens`, `sessions` | Auth |
| `cache`, `cache_locks` | Laravel infra |
| `jobs`, `job_batches`, `failed_jobs` | Laravel infra |
| `training_batches` | TMD |
| `courses` | TMD |
| `trainers` | TMD |
| `participants` | TMD |
| `dtc_hubs` | DTC Hub |
| `dtc_visitor_logs` | DTC Hub |
| `spark_trainings` | SPARK |
| `spark_trainees` | SPARK |
| `click_devices` | PROJECT CLICK |
| `funding_records` | Funding |
| `tmd_penetration` | TMD |
| `dtc_center_inventories` | DTC Hub |

---

## 2. EXISTING CODE PATTERNS & CONVENTIONS

### 2.1 Auto-generated codes

Every module uses unique sequential codes:

| Module | Pattern | Example |
|---|---|---|
| TMD Participants | `TMD-{YEAR}-{SEQ}` | TMD-2026-001 |
| TMD Batches | `TMD-SDN-{YEAR}-{SEQ}` | TMD-SDN-2026-001 |
| DTC Visitor Logs | `DTC-{YEAR}-{SEQ}` | DTC-2026-001 |
| SPARK Trainees | `SPK-{YEAR}-{SEQ}` | SPK-2026-001 |
| CLICK Devices | `CLK-{YEAR}-{SEQ}` style | CLK-2026-A1 |

### 2.2 Foreign key conventions

- Always `cascadeOnDelete`
- Always explicit indexes on FK columns
- Consistent naming: `{entity}_id` with FK to `{table}.id`

### 2.3 Status fields

- Use `enum` columns for status fields (e.g., `completion_status`, `operational_status`)
- Explicit indexes on status columns used for filtering

### 2.4 Routing conventions

- Explicit routes (not `Route::resource`)
- Module group with prefix + name + middleware: `Route::middleware(['auth', 'verified'])->prefix('dtc')->name('dtc.')->group(...)`
- Route model binding for show/update/destroy
- Controllers check `$request->wantsJson()` for AJAX responses

### 2.5 View conventions

- Single-page Alpine.js CRUD views with tabs
- `<x-app-layout>`, `<x-breadcrumbs>`, `<x-page-header>` components
- KPI stat cards → interactive table → Chart.js charts
- Modal forms dispatched via `$dispatch('open-modal', ...)`
- Scripts in `@push('scripts')`

### 2.6 Import/Export patterns

- CSV/XLSX import with fuzzy header matching and alias maps
- Export via `ExportController` with module string switch
- Template downloads for import formats

### 2.7 Controllers are module-namespaced

`App\Http\Controllers\Tmd\`, `Dtc\`, `Spark\`, `Click\`, and `Api\`

---

## 3. PROPOSED FLOW & SCHEMA (for review)

### 3.1 Proposed flow

1. Visitor arrives → registration/check-in (capture visitor details, reusable for returning visitors)
2. Visitor selects one or more services offered at that center
3. Each selected service becomes a queue ticket (status: Pending)
4. Staff marks a ticket In Progress when serving the visitor
5. Staff closes the ticket as Completed/Cancelled/No-show, logging check-out time
6. The visit (with all its services) becomes a row in the Visitor & Service Availed Register report

### 3.2 Proposed schema

```
visitors:        visitor_id, name, contact_number, address, id_type/number, created_at
services:        service_id, center_id (FK), service_name, category, is_active
visits:          visit_id, visitor_id (FK), center_id (FK), purpose_of_visit, check_in_time, check_out_time, created_at
visit_services:  visit_service_id, visit_id (FK), service_id (FK), status, queue_number, staff_assisted, remarks, started_at, ended_at
```

---

## 4. DESIGN ASSESSMENT

### 4.1 Fit with existing data model

**Partially clean, but needs adjustments:**

- **Your `services` table** — fits well. Currently services are hardcoded in 4 files. Extracting them into a proper table is a clear improvement. However, since each hub currently offers the same 5 services with no per-hub differentiation in the data model, you need to decide: does `center_id` FK on `services` mean "this center offers this service"? If so, you'd need to seed all 5 services × 4 hubs = 20 rows. That's fine, but be aware the current UI doesn't filter services by hub anywhere.

- **Your `visitors` table** — creates a problem. The existing `dtc_visitor_logs` stores `visitor_name` as a plain string with no uniqueness. If you introduce a `visitors` table, you need a strategy for the **existing rows of orphan data** that have no `visitor_id`. You'd either need a migration to deduplicate and backfill, or keep `dtc_visitor_logs` as legacy and build the new system alongside it.

- **Your `visits` table** — is essentially what `dtc_visitor_logs` already is, but normalized. The existing table has `visit_date` + `session_duration` where yours has `check_in_time` + `check_out_time`. That's a strictly better design (structured timestamps vs. free-text duration), but it means `dtc_visitor_logs` becomes redundant or needs a migration path.

- **Your FK targets** — you reference `center_id` on `services` and `visits`. The existing hub table is `dtc_hubs` (not `centers`). Use `dtc_hub_id` to match the established convention.

**Verdict:** Your schema is a cleaner, normalized version of what already exists. But you're essentially proposing to **replace** `dtc_visitor_logs` — not add alongside it. You need an explicit migration strategy for the existing data.

### 4.2 Queue/ticket flow vs. simpler model

**The queue/ticket approach is overengineered for how this project is actually used.** Here's why:

1. **This project is a reporting/analytics tool, not an operational system.** Every module (TMD, SPARK, CLICK, Funding) is structured as: staff enters data after the fact → dashboard shows aggregated stats. No module has real-time status tracking, no staff-facing workflow, no live queue display.

2. **The existing visitor log is a flat entry.** Staff logs a visit after it happens (or imports CSV data from field reports). There's no concept of "the visitor is currently here" — it's retrospective record-keeping.

3. **No auth roles exist.** There's a single `admin@dict.gov.ph` user. There's no "staff at Hub X" concept that would support a queue display. A queue system needs role-based access (staff at Hub A can't see Hub B's queue).

4. **The "session_duration" field is free-text.** This suggests the data is entered after the visit, not tracked in real-time. A queue system with `Pending → In Progress → Completed` states implies live operational use, which doesn't match the current pattern.

5. **No real-time infrastructure.** No WebSockets, no broadcasting, no polling. Alpine.js is used for client-side UI interactivity, not server push.

**Recommendation:** A **simplified single-row-per-visit model** would match the existing patterns much better:

```
visitors:          visitor_id, name, contact_number, gender, age, demographic_sector
dtc_hubs:          (existing, unchanged)
dtc_services:      service_id, dtc_hub_id(FK), service_name, category, is_active
visits:            visit_id, visitor_id(FK), dtc_hub_id(FK), check_in_time, check_out_time, purpose, status(Active|Completed|Cancelled)
visit_services:    visit_id(FK), service_id(FK), status(Pending|Completed|Cancelled), remarks
```

This gives you:
- **Reusable visitor records** (returning visitors matched by name+contact or ID)
- **Normalized services** (no more hardcoded arrays)
- **Multi-service per visit** (the JSON → pivot conversion)
- **Basic status tracking** (without the queue-number complexity)
- **Clean export/report data** for the Visitor & Service Availed Register

Drop `queue_number` and `staff_assigned` unless there is a concrete plan for a staff queue display screen. If later needed, they're easy adds.

### 4.3 Technical concerns

| Issue | Detail |
|---|---|
| **Naming: `center_id` vs `dtc_hub_id`** | The project consistently uses `dtc_hub_id` with FK to `dtc_hubs`. The proposed `services.center_id` and `visits.center_id` should be `dtc_hub_id` to match. |
| **Indexing** | Every FK in this project gets an explicit index. Ensure `visitor_id` on `visits`, `dtc_hub_id` on `visits` and `services`, and `status` columns all get indexed. |
| **Missing pivot migrations** | The existing `TrainingBatch ↔ Course` and `Trainer ↔ Course` `BelongsToMany` relationships have **no pivot table migrations** — they'll fail at runtime. Don't repeat this mistake; make sure the `visit_services` pivot has a migration. |
| **No `unique` constraint on visitor identity** | Need a way to prevent duplicate visitor records. Consider a unique index on `(name, contact_number)` or introduce a visitor code like the existing `DTC-{YEAR}-{SEQ}` pattern. |
| **Soft deletes** | No module in the project uses soft deletes. The `cascadeOnDelete` convention means deleting a hub wipes all its visits. Consider whether `dtc_hubs` deletion should cascade or if visits should be preserved. |
| **The `services_ailed` JSON column** | The `ExportController` and `Api\DtcVisitorController` both read `services_ailed` as a JSON array. Any migration to the new schema needs to update these consumers or provide backward-compatible responses. |

### 4.4 Additional fields/considerations likely overlooked

Based on how other modules are structured:

1. **Auto-generated codes** — Every module uses them. The `visits` table should have a `visit_code` following the same pattern (`DTC-VIS-{YEAR}-{SEQ}` or similar).

2. **Demographic sector** — Currently hardcoded in `dtc_visitor_logs`. If normalizing visitors, put `demographic_sector` on the `visitors` table (it describes the person, not the visit). The current values: `Student / Youth`, `Senior Citizen / PWD`, `Jobseeker / Out-of-School Youth`, `MSME / Freelancer`, `LGU / Govt Employee`.

3. **Gender and age** — Currently on `dtc_visitor_logs` per-visit. If a person visits multiple times, their gender/age shouldn't change. Put these on `visitors`, not `visits`.

4. **CSV/Excel import** — This project heavily uses import functionality. The new tables need import support. The existing import in `VisitorController` uses fuzzy header matching with alias maps — follow that pattern.

5. **Export compatibility** — `ExportController` has a `dtc-visitors` export case. The new schema needs to produce the same column structure in exports (or the export format is updated, which downstream consumers may depend on).

6. **Dashboard integration** — The main `DashboardController` queries `DtcVisitorLog` for foot traffic counts and the DTC map. The new schema needs to either replace those queries or provide compatible aggregations.

7. **SDN & PDI Tech4ED page** (`SdnPdiController`) — This page reads from `dtc_hubs`, `dtc_visitor_logs`, and `dtc_center_inventories` for municipality-level analytics. It would break if `dtc_visitor_logs` is dropped without replacement queries.

8. **Purpose of visit** — The proposed schema includes this, which is good. The current system has no equivalent. Consider making it an enum or controlled list rather than free text, to enable reporting.

9. **Category on services** — The proposed schema includes this. The current hardcoded list could be categorized: `Internet/Connectivity` (Internet, eGov), `Productivity` (Printing, Coworking), `Support` (Tech Assistance). This enables service-category-level reporting.

---

## 5. RECOMMENDED FILES TO UPDATE

If the new schema is adopted, these files would need changes:

| File | Impact |
|---|---|
| `app/Http/Controllers/Dtc/VisitorController.php` | Major rewrite — new models, new CRUD logic |
| `app/Http/Controllers/Api/DtcVisitorController.php` | Update chart queries for new schema |
| `app/Http/Controllers/ExportController.php` | Update `dtc-visitors` export for new column structure |
| `app/Http/Controllers/DashboardController.php` | Update foot traffic queries |
| `app/Http/Controllers/SdnPdiController.php` | Update municipality analytics queries |
| `resources/views/dtc/visitors/index.blade.php` | Major rewrite — new modals, new table |
| `resources/views/dtc/visitors/partials/dashboard.blade.php` | Update service filter, visitor table |
| `resources/views/dtc/visitors/partials/centers.blade.php` | Minor updates if hub relationship changes |
| `resources/views/dtc/visitors/partials/services.blade.php` | Update if service data structure changes |
| `database/seeders/DtcVisitorLogSeeder.php` | Replace with new seeder(s) |
| `routes/web.php` | Add new route groups for services, visits |

---

## 6. SUMMARY OF RECOMMENDATIONS

| Aspect | Recommendation |
|---|---|
| **Schema direction** | Normalized design is better than the current flat JSON approach. Proceed, but treat this as a **replacement** for `dtc_visitor_logs`, not an addition alongside it. |
| **Queue/ticket system** | Drop it. Use a simplified visit + visit_services pivot with basic status tracking. The project has no real-time infrastructure, no staff roles, and operates as retrospective data entry. |
| **FK naming** | Use `dtc_hub_id` everywhere, not `center_id`. |
| **Visitor identity** | Put demographic fields (gender, age, sector) on `visitors`, not `visits`. Add a unique constraint or code pattern. |
| **Migration strategy** | Plan for migrating existing `dtc_visitor_logs` rows into the new schema, or keep the old table as legacy while building the new system. |
| **Affected consumers** | At minimum, update `VisitorController`, `Api\DtcVisitorController`, `ExportController`, `DashboardController`, `SdnPdiController`, and all 4 hardcoded service lists in Blade views. |
