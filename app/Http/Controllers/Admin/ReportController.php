<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\RentPayment;
use App\Models\MaintenanceRequest;
use App\Models\User;
use App\Models\Lease;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);
        $now  = Carbon::now();

        // Monthly revenue for selected year
        $monthlyRevenue = [];
        $monthLabels    = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthLabels[]    = Carbon::createFromDate($year, $m, 1)->format('M');
            $monthlyRevenue[] = RentPayment::where('status', 'completed')
                ->whereYear('paid_at', $year)
                ->whereMonth('paid_at', $m)
                ->sum('amount');
        }

        // YTD summary
        $ytdRevenue  = RentPayment::where('status', 'completed')->whereYear('paid_at', $year)->sum('amount');
        $ytdTxns     = RentPayment::where('status', 'completed')->whereYear('paid_at', $year)->count();
        $ytdTenants  = User::where('role', 'tenant')->whereYear('created_at', $year)->count();
        $ytdProps    = Property::whereYear('created_at', $year)->count();

        // User growth monthly
        $tenantGrowth = [];
        for ($m = 1; $m <= 12; $m++) {
            $tenantGrowth[] = User::where('role', 'tenant')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $m)
                ->count();
        }

        // Property type breakdown
        $propTypes = Property::selectRaw('property_type, COUNT(*) as count')
            ->groupBy('property_type')
            ->pluck('count', 'property_type');

        // Payment method breakdown
        $payMethods = RentPayment::where('status', 'completed')
            ->whereYear('paid_at', $year)
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get();

        $availableYears = range(now()->year, max(2023, now()->year - 4));

        return view('admin.reports.index', compact(
            'year', 'monthLabels', 'monthlyRevenue',
            'ytdRevenue', 'ytdTxns', 'ytdTenants', 'ytdProps',
            'tenantGrowth', 'propTypes', 'payMethods', 'availableYears'
        ));
    }

    public function generate()
    {
        return view('admin.reports.generate');
    }
}
