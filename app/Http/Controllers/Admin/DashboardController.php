<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\RentPayment;
use App\Models\MaintenanceRequest;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', '30d');
        $now = Carbon::now();
        
        // Calculate date range based on period
        $startDate = match($period) {
            '7d' => $now->copy()->subDays(7),
            '30d' => $now->copy()->subDays(30),
            '90d' => $now->copy()->subDays(90),
            '1y' => $now->copy()->subYear(),
            default => $now->copy()->subDays(30),
        };
        
        // Previous period for comparison
        $prevStartDate = $startDate->copy()->subDays($startDate->diffInDays($now));
        $prevEndDate = $startDate;
        
        // Stats
        $totalProperties = Property::count();
        $totalTenants = User::where('role', 'tenant')->count();
        $totalPayments = RentPayment::whereBetween('created_at', [$startDate, $now])->sum('amount');
        $totalRequests = MaintenanceRequest::count();
        $pendingRequests = MaintenanceRequest::where('status', 'pending')->count();
        
        // Previous period stats for growth calculation
        $prevPayments = RentPayment::whereBetween('created_at', [$prevStartDate, $prevEndDate])->sum('amount');
        $prevTenants = User::where('role', 'tenant')->whereBetween('created_at', [$prevStartDate, $prevEndDate])->count();
        $prevProperties = Property::whereBetween('created_at', [$prevStartDate, $prevEndDate])->count();
        
        // Growth percentages
        $revenueGrowth = $prevPayments > 0 ? (($totalPayments - $prevPayments) / $prevPayments) * 100 : 0;
        $tenantsGrowth = $prevTenants > 0 ? (($totalTenants - $prevTenants) / $prevTenants) * 100 : 0;
        $propertiesGrowth = $prevProperties > 0 ? (($totalProperties - $prevProperties) / $prevProperties) * 100 : 0;
        
        // Recent activity
        $recentPayments = RentPayment::with('user')->orderBy('created_at', 'desc')->limit(5)->get();
        $recentTenants = User::where('role', 'tenant')->orderBy('created_at', 'desc')->limit(5)->get();
        $recentRequests = MaintenanceRequest::orderBy('created_at', 'desc')->limit(5)->get();
        
        // Payment methods
        $paymentMethods = RentPayment::select('payment_method')
            ->selectRaw('COUNT(*) as count, SUM(amount) as total')
            ->whereBetween('created_at', [$startDate, $now])
            ->groupBy('payment_method')
            ->get();
        
        // Chart data - monthly
        $monthLabels = [];
        $revenueData = [];
        $tenantsData = [];
        $requestsData = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $monthLabels[] = $month->format('M');
            $revenueData[] = RentPayment::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('amount');
            $tenantsData[] = User::where('role', 'tenant')->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $requestsData[] = MaintenanceRequest::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }
        
        // Pie colors for charts
        $pieColors = ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1', '#0dcaf0', '#fd7e14', '#20c997'];
        
        return view('admin.dashboard', compact(
            'period',
            'totalProperties',
            'totalTenants', 
            'totalPayments',
            'totalRequests',
            'pendingRequests',
            'revenueGrowth',
            'tenantsGrowth',
            'propertiesGrowth',
            'recentPayments',
            'recentTenants',
            'recentRequests',
            'paymentMethods',
            'monthLabels',
            'revenueData',
            'tenantsData',
            'requestsData',
            'pieColors'
        ));
    }
}
