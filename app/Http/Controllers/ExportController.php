<?php

namespace App\Http\Controllers;

use App\Models\TrainingBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportController extends Controller
{
    public function csv(string $module)
    {
        $filename = $module . '_export_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($module) {
            $handle = fopen('php://output', 'w');

            switch ($module) {
                case 'tmd-participants':
                    fputcsv($handle, ['Participant ID', 'Full Name', 'Batch Code', 'Course Title', 'Agency/LGU/Sector', 'Municipality', 'Completion Date', 'Certificate Status', 'Certificate Actions']);
                    \App\Models\Participant::with('trainingBatch')->orderByDesc('id')->each(function ($p) use ($handle) {
                        fputcsv($handle, [
                            $p->participant_code,
                            $p->full_name,
                            $p->trainingBatch->batch_code ?? '',
                            $p->trainingBatch->course_title ?? '',
                            $p->agency_sector,
                            $p->municipality,
                            $p->completion_status,
                            $p->completion_date?->format('M d, Y') ?? '',
                            $p->certificate_file ? 'Uploaded' : 'Pending',
                        ]);
                    });
                    break;

                case 'tmd-batches':
                    fputcsv($handle, ['Batch Code', 'Course Title', 'Venue', 'Target', 'Enrolled', 'Trainer', 'Start Date', 'End Date', 'Status']);
                    \App\Models\TrainingBatch::where('program', 'TMD')->orderByDesc('id')->each(function ($b) use ($handle) {
                        fputcsv($handle, [
                            $b->batch_code, $b->course_title, $b->venue,
                            $b->target_count, $b->enrolled_count, $b->trainer_name,
                            $b->start_date->format('M d, Y'), $b->end_date->format('M d, Y'), $b->status,
                        ]);
                    });
                    break;

                case 'tmd-courses':
                    fputcsv($handle, ['Course Code', 'Syllabus Title & Curriculum Details', 'Specialty Track', 'Format / Type', 'Duration', 'Accredited Credentials', 'Live Runs (Completed/Total)', 'Reference Folders', 'Action Deck']);
                    \App\Models\Course::orderBy('course_code')->each(function ($c) use ($handle) {
                        fputcsv($handle, [
                            $c->course_code, $c->title, $c->specialty_track,
                            $c->format_type, $c->duration_hours,
                            implode('; ', $c->credentials ?? []),
                        ]);
                    });
                    break;

                case 'dtc-visitors':
                    fputcsv($handle, ['Log ID & Date', 'User Name', 'Gender', 'Age', 'Demographic Sector', 'DTC Hub Location', 'Services Availed', 'Duration', 'Action']);
                    \App\Models\Visit::with('visitor', 'dtcHub', 'services')->orderByDesc('check_in_time')->each(function ($v) use ($handle) {
                        fputcsv($handle, [
                            $v->visit_code,
                            $v->check_in_time?->format('M d, Y'),
                            $v->visitor->name ?? '',
                            $v->visitor->gender ?? '',
                            $v->visitor->age ?? '',
                            $v->visitor->demographic_sector ?? '',
                            $v->dtcHub->name ?? '',
                            implode('; ', $v->services->pluck('service_name')->all()),
                            $this->formatDuration($v->check_in_time, $v->check_out_time),
                        ]);
                    });
                    break;

                case 'spark-trainings':
                    fputcsv($handle, ['Track ID', 'Specialization Course', 'Master Trainer', 'Enrolled Trainees', 'Budget Allocated', 'Industry Partner', 'Status']);
                    \App\Models\SparkTraining::orderByDesc('id')->each(function ($t) use ($handle) {
                        fputcsv($handle, [
                            $t->track_id, $t->specialization, $t->master_trainer,
                            $t->enrolled_count, $t->budget_allocated, $t->industry_partner, $t->status,
                        ]);
                    });
                    break;

                case 'spark-trainees':
                    fputcsv($handle, ['Trainee Code', 'Full Name', 'Specialty', 'Course', 'Municipality', 'Employment Status', 'Monthly Earnings']);
                    \App\Models\SparkTrainee::orderByDesc('id')->each(function ($t) use ($handle) {
                        fputcsv($handle, [
                            $t->trainee_code, $t->full_name, $t->specialty, $t->course,
                            $t->municipality, $t->employment_status, $t->monthly_earnings,
                        ]);
                    });
                    break;

                case 'click-devices':
                    fputcsv($handle, ['Batch ID', 'Donation Date', 'Device Type', 'Quantity', 'Beneficiary', 'Municipality', 'Status']);
                    \App\Models\ClickDevice::orderByDesc('donation_date')->each(function ($d) use ($handle) {
                        fputcsv($handle, [
                            $d->batch_id, $d->donation_date->format('M d, Y'), $d->device_type,
                            $d->quantity, $d->beneficiary, $d->municipality, $d->status,
                        ]);
                    });
                    break;

                case 'dashboard-history':
                    fputcsv($handle, ['Year', 'Total Trainees', 'Budget Disbursed', 'DTC Foot Traffic', 'CLICK Beneficiaries', 'YoY Growth Rate']);
                    $years = [];
                    for ($y = 2022; $y <= date('Y'); $y++) {
                        $years[] = $y;
                    }
                    $prev = 0;
                    foreach ($years as $year) {
                        $trainees = \App\Models\Participant::whereYear('created_at', $year)->count();
                        $budget = \App\Models\FundingRecord::whereYear('transaction_date', $year)->sum('disbursed');
                        $traffic = \App\Models\Visit::whereYear('check_in_time', $year)->count();
                        $beneficiaries = \App\Models\ClickDevice::whereYear('donation_date', $year)->sum('quantity');
                        $growth = $prev > 0 ? round(($trainees - $prev) / $prev * 100) : 0;
                        fputcsv($handle, [
                            $year == date('Y') ? $year . ' (YTD)' : $year,
                            $trainees, $budget, $traffic, $beneficiaries,
                            $growth ? $growth . '%' : '0%',
                        ]);
                        $prev = $trainees;
                    }
                    break;

                case 'centers':
                    fputcsv($handle, ['No.', 'Congressional District', 'Province', 'Municipality/City', 'Barangay', 'Center Name', 'Longitude', 'Latitude', 'Verified', 'MOA Date of Signing', 'Date of Launching', 'Date of Platform Registration', 'TCMS Status', 'TCMS Key', 'TCMS Identifier', 'TCMS Verification Status', 'ODK Status', 'Connectivity Status', 'Type of Center Host', 'Operational Status']);
                    \App\Models\DtcCenterInventory::orderBy('municipality_city')->orderBy('barangay')->each(function ($c) use ($handle) {
                        fputcsv($handle, [
                            $c->id,
                            $c->congressional_district ?? '',
                            $c->province ?? '',
                            $c->municipality_city,
                            $c->barangay ?? '',
                            $c->center_name,
                            $c->longitude ?? '',
                            $c->latitude ?? '',
                            $c->verified ? 'Yes' : 'No',
                            $c->moa_date_of_signing?->format('Y-m-d') ?? '',
                            $c->date_of_launching?->format('Y-m-d') ?? '',
                            $c->date_of_platform_registration?->format('Y-m-d') ?? '',
                            $c->tcms_status ?? '',
                            $c->tcms_key ?? '',
                            $c->tcms_identifier ?? '',
                            $c->tcms_verification_status ?? '',
                            $c->odk_status ?? '',
                            $c->connectivity_status ?? '',
                            $c->type_of_center_host ?? '',
                            $c->operational_status ?? '',
                        ]);
                    });
                    break;

                case 'funding':
                    fputcsv($handle, ['Voucher Ref', 'Project', 'Description', 'Category', 'Allocated', 'Obligated', 'Disbursed', 'Transaction Date', 'Status']);
                    \App\Models\FundingRecord::orderByDesc('transaction_date')->each(function ($r) use ($handle) {
                        fputcsv($handle, [
                            $r->voucher_ref, $r->project, $r->description, $r->expense_category,
                            $r->allocated, $r->obligated, $r->disbursed,
                            $r->transaction_date->format('M d, Y'), $r->status,
                        ]);
                    });
                    break;
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function xlsx(string $module)
    {
        if ($module !== 'centers') {
            abort(404);
        }

        $filename = 'centers_export_' . date('Y-m-d_His') . '.xlsx';

        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'max-age=0',
        ];

        $callback = function () {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('DTC Center Inventory');

            $headerStyle = [
                'font' => ['bold' => true, 'size' => 10],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '9DC3E6']],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ];

            // Flat single-row header with disambiguated column names
            $headers = [
                'A' => 'No.',
                'B' => 'Congressional District',
                'C' => 'Province',
                'D' => 'Municipality/City',
                'E' => 'Barangay',
                'F' => 'Center Name',
                'G' => 'Longitude',
                'H' => 'Latitude',
                'I' => 'Verified',
                'J' => 'MOA Date of Signing',
                'K' => 'Date of Launching',
                'L' => 'Date of Platform Registration',
                'M' => 'TCMS Status',
                'N' => 'TCMS Key',
                'O' => 'TCMS Identifier',
                'P' => 'TCMS Verification Status',
                'Q' => 'ODK Status',
                'R' => 'Connectivity Status',
                'S' => 'Type of Center Host',
                'T' => 'Operational Status',
            ];
            foreach ($headers as $col => $label) {
                $sheet->setCellValue("{$col}1", $label);
            }

            $sheet->getStyle('A1:T1')->applyFromArray($headerStyle);

            // Data rows
            $row = 2;
            $idx = 1;
            \App\Models\DtcCenterInventory::orderBy('municipality_city')->orderBy('barangay')->each(function ($c) use ($sheet, &$row, &$idx) {
                $sheet->setCellValue("A{$row}", $idx++);
                $sheet->setCellValue("B{$row}", $c->congressional_district ?? '');
                $sheet->setCellValue("C{$row}", $c->province ?? '');
                $sheet->setCellValue("D{$row}", $c->municipality_city);
                $sheet->setCellValue("E{$row}", $c->barangay ?? '');
                $sheet->setCellValue("F{$row}", $c->center_name);
                $sheet->setCellValue("G{$row}", $c->longitude ?? '');
                $sheet->setCellValue("H{$row}", $c->latitude ?? '');
                $sheet->setCellValue("I{$row}", $c->verified ? 'Yes' : 'No');
                $sheet->setCellValue("J{$row}", $c->moa_date_of_signing?->format('Y-m-d') ?? '');
                $sheet->setCellValue("K{$row}", $c->date_of_launching?->format('Y-m-d') ?? '');
                $sheet->setCellValue("L{$row}", $c->date_of_platform_registration?->format('Y-m-d') ?? '');
                $sheet->setCellValue("M{$row}", $c->tcms_status ?? '');
                $sheet->setCellValue("N{$row}", $c->tcms_key ?? '');
                $sheet->setCellValue("O{$row}", $c->tcms_identifier ?? '');
                $sheet->setCellValue("P{$row}", $c->tcms_verification_status ?? '');
                $sheet->setCellValue("Q{$row}", $c->odk_status ?? '');
                $sheet->setCellValue("R{$row}", $c->connectivity_status ?? '');
                $sheet->setCellValue("S{$row}", $c->type_of_center_host ?? '');
                $sheet->setCellValue("T{$row}", $c->operational_status ?? '');
                $row++;
            });

            $lastRow = $row - 1;
            if ($lastRow >= 2) {
                $sheet->getStyle("A2:T{$lastRow}")->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                ]);
                $sheet->getStyle("A2:A{$lastRow}")->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
            }

            $widths = [
                'A' => 6, 'B' => 18, 'C' => 18, 'D' => 20, 'E' => 20, 'F' => 34,
                'G' => 12, 'H' => 12, 'I' => 10, 'J' => 18, 'K' => 18, 'L' => 24,
                'M' => 14, 'N' => 18, 'O' => 18, 'P' => 24, 'Q' => 12, 'R' => 16,
                'S' => 22, 'T' => 16,
            ];
            foreach ($widths as $col => $width) {
                $sheet->getColumnDimension($col)->setWidth($width);
            }
            $sheet->setAutoFilter('A1:T' . max(2, $lastRow));
            $sheet->freezePane('A2');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        };

        return response()->stream($callback, 200, $headers);
    }

    protected function formatDuration($checkIn, $checkOut): string
    {
        if (!$checkIn || !$checkOut) {
            return '—';
        }
        $minutes = (int) round($checkOut->diffInMinutes($checkIn));
        if ($minutes < 60) {
            return $minutes > 0 ? "{$minutes} mins" : '—';
        }
        $h = intdiv($minutes, 60);
        $m = $minutes % 60;
        return $m > 0 ? "{$h} hr {$m} mins" : "{$h} hrs";
    }

    public function template(string $module)
    {
        $filename = $module . '_template_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($module) {
            $handle = fopen('php://output', 'w');

            match ($module) {
                'tmd-batches' => fputcsv($handle, ['Batch Code', 'Course Title', 'Venue', 'Target Count', 'Trainer Name', 'Start Date', 'End Date', 'Status']),
                'tmd-participants' => fputcsv($handle, ['Full Name', 'Training Batch ID', 'Municipality', 'Agency/Sector', 'Completion Status']),
                'dtc-visitors' => fputcsv($handle, ['Visitor Name', 'Contact Number', 'Gender', 'Age', 'Demographic Sector', 'DTC Hub', 'Services', 'Purpose of Visit', 'Visit Date']),
                'spark-trainees' => fputcsv($handle, ['Full Name', 'Specialty', 'Course', 'Municipality', 'Employment Status', 'Monthly Earnings']),
                'click-devices' => fputcsv($handle, ['Batch ID', 'Donation Date', 'Device Type', 'Quantity', 'Beneficiary', 'Municipality', 'Status']),
                'centers' => fputcsv($handle, ['Congressional District', 'Province', 'Municipality/City', 'Barangay', 'Center Name', 'Longitude', 'Latitude', 'Verified', 'MOA Date of Signing', 'Date of Launching', 'Date of Platform Registration', 'TCMS Status', 'TCMS Key', 'TCMS Identifier', 'TCMS Verification Status', 'ODK Status', 'Connectivity Status', 'Type of Center Host', 'Operational Status']),
                'funding' => fputcsv($handle, ['Voucher Ref', 'Project', 'Description', 'Expense Category', 'Allocated', 'Obligated', 'Disbursed', 'Transaction Date', 'Status']),
                default => fputcsv($handle, ['Column A', 'Column B', 'Column C']),
            };

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
