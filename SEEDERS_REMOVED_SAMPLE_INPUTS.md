# Removed Seeders — Sample Inputs for Manual Testing

All database seeders have been removed so the system can be tested manually from scratch.
This file contains the sample data that was previously inserted by the seeders, organized per module.
Use these as reference inputs when creating records through the UI or filling the tables.

> Note: If your database still has the old seeded rows, clear them first
> (e.g. `php artisan migrate:fresh`) before manual testing.

---

## 1. Users (`users`)

| Email | Password | Name |
|---|---|---|
| `admin@dict.gov.ph` | `password` | DICT SDN Administrator |

---

## 2. DTC Hubs (`dtc_hubs`)

| Name | Municipality | Latitude | Longitude | Status |
|---|---|---|---|---|
| Surigao City DTC Main Hub | Surigao City | 9.7894 | 125.4958 | Active |
| Claver Digital Hub | Claver | 9.5714 | 125.5925 | Active |
| Siargao Tech Hub (Dapa) | Dapa, Siargao | 9.7562 | 126.0543 | Active |
| Mainit Tech Hub | Mainit | 9.5372 | 125.5231 | Active |

---

## 3. DTC Services (`dtc_services`)

Create all 5 services for each hub (5 × 4 hubs = 20 rows).

| Service Name | Category | Active |
|---|---|---|
| Free High-Speed Internet | Internet/Connectivity | Yes |
| eGov PH & Government Portal Access | Internet/Connectivity | Yes |
| Printing & Document Scanning | Productivity | Yes |
| Co-working & Freelance Space | Productivity | Yes |
| Tech Assistance & Consultation | Support | Yes |

---

## 4. DTC Visitors (`visitors`)

| Name | Gender | Age | Demographic Sector |
|---|---|---|---|
| Maria Clara Santos | Female | 22 | Student / Youth |
| Juan Dela Cruz | Male | 34 | MSME / Freelancer |
| Ronalyn Petallo | Female | 19 | Student / Youth |
| Ana Reyne Calago | Female | 28 | Jobseeker / Out-of-School Youth |
| Mark Anthony Vega | Male | 45 | LGU / Govt Employee |
| Elena Ramos | Female | 67 | Senior Citizen / PWD |
| Kevin Roy Tagalog | Male | 17 | Student / Youth |
| Grace Gonzaga | Female | 31 | MSME / Freelancer |

---

## 5. DTC Visits (`visits` + `visit_services`)

`visit_code` format: `DTC-VIS-<year>-<seq 3 digits>` (e.g. `DTC-VIS-2026-001`).

Sample rows — pick a hub, an existing visitor, 1–3 services, check-in/check-out, status `Completed`:

| Visitor | Hub ID | Services Used | Check-in → Check-out |
|---|---|---|---|
| Maria Clara Santos | 1 | Free High-Speed Internet | e.g. 45 mins |
| Juan Dela Cruz | 1 | Free High-Speed Internet; eGov PH Access | 1 hr 15 mins |
| Ronalyn Petallo | 2 | Free High-Speed Internet; Printing & Scanning | 2 hrs |
| Ana Reyne Calago | 1 | Internet; Co-working; Tech Assistance | 1 hr 30 mins |
| Mark Anthony Vega | 3 | eGov Access; Printing & Scanning | 3 hrs |
| Elena Ramos | 4 | Internet; Tech Assistance | 2 hrs 45 mins |
| Kevin Roy Tagalog | 2 | Co-working & Freelance Space | 1 hr |
| Grace Gonzaga | 1 | Internet; eGov Access; Printing & Scanning | 1 hr 45 mins |

---

## 6. Courses (`courses`)

| Course Code | Title | Specialty Track | Format | Duration | Credentials |
|---|---|---|---|---|---|
| BCIL-101 | Basic Computer & Internet Literacy | Digital Literacy | In-Person | 24h | `DICT Certificate of Completion` |
| CYBER-201 | Cybersecurity Awareness & Network Defense | Cybersecurity | In-Person | 40h | `DICT Certificate`, `CompTIA Security+ Prep` |
| WEBD-301 | Full-Stack Web Development with PHP & Laravel | Web Development | In-Person | 48h | `DICT Certificate`, `Laravel Developer` |
| DATA-401 | Data Analytics with Python | Data Analytics | Hybrid | 36h | `DICT Certificate`, `Python Data Analyst` |
| NETA-501 | Network Administration & Linux | Networking | In-Person | 40h | `DICT Certificate`, `Linux Essentials` |
| AICL-601 | Applied AI & Machine Learning with Python | Artificial Intelligence | In-Person | 48h | `DICT Certificate`, `AWS Cloud Practitioner Prep` |

---

## 7. Trainers (`trainers`)

| Full Name | Designation | Specialty | Agency | Contact | Phone | Status | Courses | Rating |
|---|---|---|---|---|---|---|---|---|
| Engr. Rodel T. Balintong | Lead Resource Speaker | ICT Literacy & Digital Transformation | DICT - Surigao del Norte | rodel.balintong@dict.gov.ph | 0917 000 0000 | Active | 12 | 4.9 |
| Maria Lourdes V. Cariño | Resource Speaker | E-Government & Digital Services | DICT - Caraga Regional Office | mlc.carino@dict.gov.ph | 0918 000 0000 | Active | 8 | 4.8 |
| Jason Paul S. Dizon | Resource Speaker | Cybersecurity & Data Privacy | DICT - Surigao del Norte | jp.dizon@dict.gov.ph | 0919 000 0000 | Active | 10 | 4.7 |
| Karen Grace M. Eclarin | Resource Speaker | Digital Marketing & E-Commerce | LGU Surigao City | kg.eclarin@gmail.com | 0920 000 0000 | Active | 6 | 4.6 |
| Rolando M. Fuentes | Resource Speaker | Basic Programming & Web Development | Surigao State College of Technology | rm.fuentes@ssct.edu.ph | 0921 000 0000 | Active | 9 | 4.8 |
| Diana Rose P. Galido | Resource Speaker | E-Learning & Educational Technology | DepEd - Surigao del Norte | dr.galido@deped.gov.ph | 0922 000 0000 | Active | 7 | 4.5 |
| Mark Anthony C. Lim | Resource Speaker | Data Analytics & Spreadsheet Tools | DICT - Surigao del Norte | ma.lim@dict.gov.ph | 0923 000 0000 | Active | 5 | 4.7 |
| Christine Joy D. Oporto | Resource Speaker | ICT for Farmers & Fisherfolk | Provincial Agriculturist Office | cj.oporto@sdn.gov.ph | 0924 000 0000 | Active | 4 | 4.6 |

---

## 8. Training Batches (`training_batches`)

Columns: batch_code, course_title, venue, target_count, enrolled_count, trainer_name, start_date, end_date, program, status

| Batch Code | Course Title | Venue | Target/Enrolled | Trainer | Start → End | Program | Status |
|---|---|---|---|---|---|---|---|
| TMD-SDN-2026-001 | Basic Computer & Internet Literacy | Surigao City DTC Main Hub | 30 / 25 | Mr. Juan B. Madrigal | 2026-01-15 → 2026-02-10 | TMD | Completed |
| TMD-SDN-2026-002 | Cybersecurity Awareness & Network Defense | Claver Digital Hub | 25 / 22 | Engr. Alex Santos | 2026-02-15 → 2026-03-10 | TMD | Completed |
| TMD-SDN-2026-003 | Full-Stack Web Development with PHP & Laravel | Surigao City DTC Main Hub | 20 / 18 | Ms. Maria Clara Cruz | 2026-03-15 → 2026-04-30 | TMD | Ongoing |
| TMD-SDN-2026-005 | Data Analytics with Python | Mainit Tech Hub | 20 / 15 | Dr. Ramon Reyes | 2026-04-01 → 2026-05-15 | TMD | Upcoming |
| SPARK-SDN-2026-001 | Applied AI & Machine Learning with Python | Siargao Tech Hub (Dapa) | 30 / 28 | Dr. Ramon Reyes | 2026-01-20 → 2026-03-20 | SPARK | Ongoing |

---

## 9. Participants (`participants`)

`participant_code` format: `TMD-2026-00X`

| Participant Code | Full Name | Training Batch ID | Agency/Sector | Municipality | Completion Status | Completion Date |
|---|---|---|---|---|---|---|
| TMD-2026-001 | Maria Santos | 1 | LGU Mainit | Mainit | Completed | 2026-02-10 |
| TMD-2026-002 | Juan Dela Cruz | 2 | DepEd SDN | Surigao City | Completed | 2026-03-10 |
| TMD-2026-003 | Ronalyn Petallo | 1 | SK Council | Claver | Completed | 2026-02-10 |
| TMD-2026-004 | Ana Reyne Calago | 3 | LGU Surigao City | Surigao City | Ongoing | — |
| TMD-2026-005 | Mark Anthony Vega | 2 | DICT Scholar | Surigao City | Completed | 2026-03-10 |
| TMD-2026-006 | Elena Ramos | 4 | LGU Mainit | Mainit | Pending | — |
| TMD-2026-007 | Kevin Roy Tagalog | 5 | LGU Claver | Claver | Ongoing | — |
| TMD-2026-008 | Grace Gonzaga | 5 | DICT Trainee | Surigao City | Ongoing | — |

---

## 10. Funding Records (`funding_records`)

Columns: voucher_ref, project, description, expense_category, allocated, obligated, disbursed, transaction_date, status

### DWIA-TMD

| Voucher Ref | Description | Expense Category | Allocated | Obligated | Disbursed | Date | Status |
|---|---|---|---|---|---|---|---|
| DV-2026-01-012 | Cybersecurity Essentials Workshop Training Materials & Honoraria | MOOE - Training & Seminars | 500000 | 450000 | 420000 | 2026-01-18 | Disbursed |
| DV-2026-02-045 | Basic Computer Literacy Program LGU Mainit Venue & Food Logistics | Supplies & Logistics | 400000 | 350000 | 310000 | 2026-02-12 | Disbursed |
| DV-2026-03-088 | Full-Stack Web Dev Bootcamp Advanced Mod 2 Consultancy | Honorarium & Consultancy | 900000 | 650000 | 520000 | 2026-03-05 | Disbursed |

### DTC HUB

| Voucher Ref | Description | Expense Category | Allocated | Obligated | Disbursed | Date | Status |
|---|---|---|---|---|---|---|---|
| DV-2026-01-029 | Surigao City Main Hub Fiber Broadband Internet Annual Subscription | MOOE - Training & Seminars | 350000 | 350000 | 300000 | 2026-01-25 | Disbursed |
| DV-2026-02-061 | Claver Digital Hub Workstations Memory & Peripherals Upgrade | Capital Outlay - Equipment | 450000 | 380000 | 330000 | 2026-02-20 | Disbursed |
| DV-2026-03-102 | Siargao Tech Hub Dapa Solar Backup Generator Installation | Capital Outlay - Equipment | 400000 | 250000 | 220000 | 2026-03-14 | Disbursed |

### SPARK

| Voucher Ref | Description | Expense Category | Allocated | Obligated | Disbursed | Date | Status |
|---|---|---|---|---|---|---|---|
| DV-2026-01-033 | Freelance Virtual Assistance Bootcamp Specialist Coaches Fee | Honorarium & Consultancy | 600000 | 500000 | 480000 | 2026-01-30 | Disbursed |
| DV-2026-02-074 | SEO & Digital Marketing Masterclass Starter Kit Seed Support | MOOE - Training & Seminars | 500000 | 420000 | 380000 | 2026-02-28 | Disbursed |
| DV-2026-03-115 | Freelancer Mentorship Program Platform Licenses Procurement | Supplies & Logistics | 400000 | 280000 | 240000 | 2026-03-22 | Disbursed |

### PROJECT CLICK

| Voucher Ref | Description | Expense Category | Allocated | Obligated | Disbursed | Date | Status |
|---|---|---|---|---|---|---|---|
| DV-2026-01-005 | Batch 1 Chromebook Units Procurement for Public High Schools | Capital Outlay - Equipment | 1000000 | 950000 | 880000 | 2026-01-10 | Disbursed |
| DV-2026-02-052 | Siargao Island Schools Educational Tablets Logistics & Turnover | Supplies & Logistics | 600000 | 550000 | 490000 | 2026-02-15 | Disbursed |
| DV-2026-03-094 | Refurbished Laptop Distribution for OSY & LGU Tech Centers | Capital Outlay - Equipment | 400000 | 350000 | 280000 | 2026-03-10 | Disbursed |

---

## 11. SPARK Trainings (`spark_trainings`)

Columns: track_id, specialization, master_trainer, enrolled_count, budget_allocated, industry_partner, status

| Track ID | Specialization | Master Trainer | Enrolled | Budget | Industry Partner | Status |
|---|---|---|---|---|---|---|
| SPARK-AI-01 | Applied AI & Machine Learning with Python | Dr. Ramon Reyes | 30 | 250000 | DICT National / Analytics Council | Ongoing |
| SPARK-CC-02 | Cloud Architecture & AWS Foundations | Engr. Alex Santos | 25 | 200000 | AWS Academy | Completed |
| SPARK-CY-03 | Ethical Hacking & Network Defense | Ms. Maria Clara Cruz | 20 | 180000 | Cybersecurity Alliance | Upcoming |
| SPARK-VA-04 | Virtual Assistance & Digital Marketing | Mr. James Fernandez | 35 | 150000 | OnlineJobs.ph Academy | Ongoing |
| SPARK-WD-05 | Full-Stack Web Development (PHP/Laravel) | Mr. Kevin Roy Tagalog | 28 | 200000 | Laravel Philippines | Ongoing |
| SPARK-DM-06 | SEO & Search Engine Marketing Mastery | Ms. Ana Reyne Calago | 22 | 120000 | Google Digital Garage | Completed |
| SPARK-GD-07 | Graphic Design & Creative Multimedia | Mr. Mark Anthony Vega | 18 | 100000 | Adobe Creative Network | Upcoming |
| SPARK-DS-08 | Data Science & Analytics Fundamentals | Dr. Ramon Reyes | 32 | 180000 | DICT National / Analytics Council | Ongoing |

---

## 12. SPARK Trainees (`spark_trainees`)

Columns: trainee_code, full_name, specialty, course, municipality, employment_status, monthly_earnings

| Trainee Code | Full Name | Specialty | Course | Municipality | Employment Status | Monthly Earnings |
|---|---|---|---|---|---|---|
| SPK-2026-001 | Grace B. Gonzaga | Virtual Assistant & Email Mgmt | Virtual Assistance Masterclass | Surigao City | Full-Time Freelancer | 35000 |
| SPK-2026-002 | Kevin Roy D. Tagalog | Full-Stack Web Dev | PHP & Laravel Web Development | Claver | Full-Time Freelancer | 42000 |
| SPK-2026-003 | Maria Clara Santos | SEO & Digital Marketing | Digital Marketing Mastery | Surigao City | Self-Employed | 28000 |
| SPK-2026-004 | Ronalyn Petallo | AI & Python | Applied AI & Machine Learning | Dapa (Siargao) | Employed | 38000 |
| SPK-2026-005 | Juan Dela Cruz | Cloud Computing | AWS Cloud Foundations | Mainit | Part-Time Freelancer | 18000 |
| SPK-2026-006 | Elena Ramos | Graphic Design | Creative Multimedia Design | Surigao City | Self-Employed | 25000 |
| SPK-2026-007 | Mark Anthony Vega | Cybersecurity | Ethical Hacking & Defense | Claver | Employed | 45000 |
| SPK-2026-008 | Ana Reyne Calago | Data Science | Data Analytics Fundamentals | Surigao City | Full-Time Freelancer | 32000 |

---

## 13. Project CLICK Devices (`click_devices`)

Columns: batch_id, donation_date, device_type, quantity, beneficiary, municipality, status

| Batch ID | Donation Date | Device Type | Qty | Beneficiary | Municipality | Status |
|---|---|---|---|---|---|---|
| CLK-2026-A1 | 2026-01-12 | Lenovo Chromebook 300e | 35 | Surigao National High School | Surigao City | Turned Over |
| CLK-2026-A2 | 2026-02-18 | Samsung Galaxy Tab A8 | 25 | Dapa National High School | Dapa (Siargao) | Turned Over |
| CLK-2026-B1 | 2026-03-05 | HP Chromebook 11 G9 | 40 | Claver Central School | Claver | Turned Over |
| CLK-2026-B2 | 2026-03-20 | Acer Aspire Go 15 Laptop | 30 | Mainit Central School | Mainit | Pending |
| CLK-2026-C1 | 2026-04-08 | Lenovo Tab M10 Plus | 20 | Siargao Island Tech Center | Dapa (Siargao) | In Transit |
| CLK-2026-C2 | 2026-05-15 | Dell Latitude 3120 Chromebook | 45 | SDN Provincial Capitol Tech Lab | Surigao City | Turned Over |
| CLK-2026-D1 | 2026-06-01 | ASUS Chromebook Flip CX1 | 50 | Multiple LGUs - SDN-wide Distribution | Surigao City | Turned Over |

---

## 14. TMD Penetration (`tmd_penetrations`)

Columns: municipality, male, female, total

| Municipality | Male | Female | Total |
|---|---|---|---|
| Surigao City | 120 | 145 | 265 |
| Alegria | 18 | 22 | 40 |
| Bacuag | 22 | 28 | 50 |
| Burgos | 10 | 14 | 24 |
| Claver | 35 | 30 | 65 |
| Dapa | 28 | 32 | 60 |
| Del Carmen | 20 | 25 | 45 |
| General Luna | 25 | 30 | 55 |
| Gigaquit | 15 | 18 | 33 |
| Mainit | 30 | 35 | 65 |
| Malimono | 12 | 15 | 27 |
| Pilar | 14 | 16 | 30 |
| Placer | 40 | 45 | 85 |
| San Benito | 8 | 10 | 18 |
| San Francisco | 16 | 20 | 36 |
| San Isidro | 10 | 12 | 22 |
| Santa Monica | 9 | 11 | 20 |
| Sison | 24 | 26 | 50 |
| Socorro | 20 | 22 | 42 |
| Tagana-an | 14 | 18 | 32 |
| Tubod | 11 | 14 | 25 |