<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\TrainingBatch;
use App\Models\DtcHub;
use App\Models\DtcCenterInventory;
use App\Models\DtcVisitorLog;
use App\Models\ClickDevice;
use App\Models\FundingRecord;
use App\Models\Course;
use App\Models\Trainer;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTrainees = Participant::count();
        $certifiedCount = Participant::where('completion_status', 'Completed')->count();
        $ongoingCount = Participant::where('completion_status', 'Ongoing')->count();
        $pendingCount = Participant::where('completion_status', 'Pending')->count();
        $municipalLGUs = Participant::distinct('municipality')->count('municipality');
        $totalCourses = Course::count();
        $totalTrainers = Trainer::where('status', 'Active')->count();

        $totalBudget = FundingRecord::sum('disbursed');
        $totalAllocated = FundingRecord::sum('allocated');
        $totalObligated = FundingRecord::sum('obligated');
        $totalFootTraffic = DtcVisitorLog::count();
        $clickBeneficiaries = ClickDevice::sum('quantity');

        $projectFunding = FundingRecord::select('project')
            ->selectRaw('SUM(allocated) as total_allocated')
            ->selectRaw('SUM(obligated) as total_obligated')
            ->selectRaw('SUM(disbursed) as total_disbursed')
            ->groupBy('project')
            ->get();

        $hubs = DtcHub::where('status', 'Active')->get([
            'id', 'name', 'municipality', 'latitude', 'longitude'
        ]);

        // Tech4ED / DTC centers from PDI center inventory with verified GPS coordinates
        $tech4edCenters = DtcCenterInventory::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get([
                'id', 'center_name', 'province', 'municipality_city', 'barangay',
                'longitude', 'latitude', 'operational_status',
            ]);

        $calendarEvents = TrainingBatch::where('status', '!=', 'Completed')
            ->get(['id', 'batch_code', 'course_title', 'start_date', 'end_date', 'program', 'status'])
            ->map(function ($batch) {
                $colors = [
                    'TMD' => '#003366',
                    'SPARK' => '#d97706',
                    'CLICK' => '#059669',
                ];
                return [
                    'title' => $batch->course_title,
                    'start' => $batch->start_date->format('Y-m-d'),
                    'end' => $batch->end_date->format('Y-m-d'),
                    'color' => $colors[$batch->program] ?? '#6366f1',
                ];
            });

        $currentYear = date('Y');
        $historicalData = [];
        for ($y = 2022; $y <= $currentYear; $y++) {
            $year = (string) $y;
            $trainees = Participant::whereYear('created_at', $year)->count();
            $budgetDisbursed = FundingRecord::whereYear('transaction_date', $year)->sum('disbursed');
            $footTraffic = DtcVisitorLog::whereYear('visit_date', $year)->count();
            $beneficiaries = ClickDevice::whereYear('donation_date', $year)->sum('quantity');

            $historicalData[] = (object) [
                'year' => $y == $currentYear ? $y . ' (YTD)' : $year,
                'trainees' => $trainees,
                'budget' => $budgetDisbursed,
                'foot_traffic' => $footTraffic,
                'beneficiaries' => $beneficiaries,
            ];
        }
        for ($i = 0; $i < count($historicalData); $i++) {
            $prev = $i > 0 ? $historicalData[$i - 1]->trainees : 0;
            $curr = $historicalData[$i]->trainees;
            $historicalData[$i]->growth = $prev > 0 ? round(($curr - $prev) / $prev * 100) : null;
        }

        return view('dashboard', compact(
            'totalTrainees', 'certifiedCount', 'ongoingCount', 'pendingCount',
            'municipalLGUs', 'totalCourses', 'totalTrainers',
            'totalBudget', 'totalAllocated', 'totalObligated',
            'totalFootTraffic', 'clickBeneficiaries',
            'projectFunding',
            'hubs', 'tech4edCenters', 'calendarEvents', 'historicalData'
        ));
    }
}
