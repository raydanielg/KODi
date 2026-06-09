<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\User;
use App\Models\Lease;
use App\Models\RentPayment;
use App\Models\MaintenanceRequest;
use App\Models\Application;
use App\Models\Commission;
use App\Models\Payout;
use App\Models\Message;
use App\Models\Dispute;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $role = $user->role;

            switch ($role) {
                case 'super_admin':
                    return $this->superAdminDashboard();
                case 'admin':
                    return $this->adminDashboard();
                case 'landlord':
                    return $this->landlordDashboard($user);
                case 'agent':
                    return $this->agentDashboard($user);
                case 'tenant':
                    return $this->tenantDashboard($user);
                case 'support':
                    return $this->supportDashboard();
                case 'maintenance':
                    return $this->maintenanceDashboard();
                case 'accountant':
                    return $this->accountantDashboard();
                case 'investor':
                    return $this->investorDashboard();
                default:
                    return response()->json(['success' => false, 'message' => 'Role not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function superAdminDashboard()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'total_users' => User::count(),
                    'total_properties' => Property::count(),
                    'total_landlords' => User::where('role', 'landlord')->count(),
                    'total_tenants' => User::where('role', 'tenant')->count(),
                    'total_agents' => User::where('role', 'agent')->count(),
                    'active_leases' => Lease::where('status', 'active')->count(),
                    'pending_maintenance' => MaintenanceRequest::where('status', 'pending')->count(),
                    'total_revenue' => RentPayment::where('status', 'completed')->sum('amount'),
                ],
                'recent_users' => User::latest()->take(5)->get(),
                'recent_properties' => Property::with('landlord')->latest()->take(5)->get(),
            ]
        ]);
    }

    private function adminDashboard()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'total_properties' => Property::count(),
                    'total_tenants' => User::where('role', 'tenant')->count(),
                    'total_landlords' => User::where('role', 'landlord')->count(),
                    'pending_applications' => Application::where('status', 'pending')->count(),
                    'active_leases' => Lease::where('status', 'active')->count(),
                    'today_payments' => RentPayment::whereDate('created_at', today())->count(),
                ],
                'recent_properties' => Property::with('landlord')->latest()->take(5)->get(),
            ]
        ]);
    }

    private function landlordDashboard($user)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'my_properties' => Property::where('user_id', $user->id)->count(),
                    'total_tenants' => Lease::where('landlord_id', $user->id)->where('status', 'active')->distinct('tenant_id')->count('tenant_id'),
                    'active_leases' => Lease::where('landlord_id', $user->id)->where('status', 'active')->count(),
                    'monthly_rent' => RentPayment::where('landlord_id', $user->id)->where('status', 'completed')->whereMonth('created_at', now()->month)->sum('amount'),
                    'pending_maintenance' => MaintenanceRequest::where('landlord_id', $user->id)->whereIn('status', ['pending', 'assigned'])->count(),
                    'overdue_payments' => RentPayment::where('landlord_id', $user->id)->where('status', 'pending')->count(),
                ],
                'my_properties' => Property::where('user_id', $user->id)->with('activeLease.tenant')->get(),
                'recent_payments' => RentPayment::where('landlord_id', $user->id)->latest()->take(5)->get(),
            ]
        ]);
    }

    private function agentDashboard($user)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'managed_properties' => Property::where('agent_id', $user->id)->count(),
                    'total_clients' => User::where('role', 'tenant')->whereHas('applicationsAsTenant', function ($q) use ($user) {
                        $q->where('agent_id', $user->id);
                    })->count(),
                    'pending_commissions' => Commission::where('agent_id', $user->id)->where('status', 'pending')->sum('amount'),
                    'total_commissions' => Commission::where('agent_id', $user->id)->where('status', 'paid')->sum('amount'),
                ],
                'properties' => Property::where('agent_id', $user->id)->get(),
                'recent_commissions' => Commission::where('agent_id', $user->id)->latest()->take(5)->get(),
            ]
        ]);
    }

    private function tenantDashboard($user)
    {
        $activeLease = Lease::where('tenant_id', $user->id)->where('status', 'active')->first();

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'has_active_lease' => $activeLease ? true : false,
                    'property_name' => $activeLease ? $activeLease->property->title : null,
                    'monthly_rent' => $activeLease ? $activeLease->rent_amount : 0,
                    'next_payment_due' => $activeLease ? $activeLease->end_date : null,
                    'pending_maintenance' => MaintenanceRequest::where('tenant_id', $user->id)->whereIn('status', ['pending', 'assigned', 'in_progress'])->count(),
                    'unread_messages' => Message::where('receiver_id', $user->id)->where('is_read', false)->count(),
                ],
                'active_lease' => $activeLease ? $activeLease->load('property', 'landlord') : null,
                'recent_payments' => RentPayment::where('tenant_id', $user->id)->latest()->take(5)->get(),
                'maintenance_requests' => MaintenanceRequest::where('tenant_id', $user->id)->latest()->take(3)->get(),
                'pending_applications' => Application::where('tenant_id', $user->id)
                    ->whereIn('status', ['pending', 'approved'])
                    ->with(['property', 'landlord'])
                    ->latest()
                    ->get(),
            ]
        ]);
    }

    private function supportDashboard()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'open_tickets' => Dispute::where('status', 'open')->count(),
                    'pending_tickets' => Dispute::whereIn('status', ['open', 'under_review'])->count(),
                    'resolved_today' => Dispute::where('status', 'resolved')->whereDate('resolved_at', today())->count(),
                    'total_messages' => Message::whereDate('created_at', today())->count(),
                ],
                'recent_tickets' => Dispute::with('raisedBy')->latest()->take(5)->get(),
            ]
        ]);
    }

    private function maintenanceDashboard()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'assigned' => MaintenanceRequest::where('status', 'assigned')->count(),
                    'in_progress' => MaintenanceRequest::where('status', 'in_progress')->count(),
                    'completed_today' => MaintenanceRequest::where('status', 'completed')->whereDate('completed_at', today())->count(),
                    'pending' => MaintenanceRequest::where('status', 'pending')->count(),
                    'urgent' => MaintenanceRequest::where('priority', 'urgent')->whereIn('status', ['pending', 'assigned', 'in_progress'])->count(),
                ],
                'recent_requests' => MaintenanceRequest::with('property', 'tenant')->latest()->take(5)->get(),
            ]
        ]);
    }

    private function accountantDashboard()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'total_payments_month' => RentPayment::where('status', 'completed')->whereMonth('created_at', now()->month)->sum('amount'),
                    'total_payouts_month' => Payout::where('status', 'completed')->whereMonth('created_at', now()->month)->sum('amount'),
                    'pending_payouts' => Payout::where('status', 'pending')->count(),
                    'total_revenue' => RentPayment::where('status', 'completed')->sum('amount'),
                    'pending_commissions' => Commission::where('status', 'pending')->sum('amount'),
                ],
                'recent_payments' => RentPayment::with('tenant', 'property')->latest()->take(5)->get(),
            ]
        ]);
    }

    private function investorDashboard()
    {
        $totalProperties = Property::count();

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'total_properties' => $totalProperties,
                    'active_leases' => Lease::where('status', 'active')->count(),
                    'total_revenue' => RentPayment::where('status', 'completed')->sum('amount'),
                    'monthly_revenue' => RentPayment::where('status', 'completed')->whereMonth('created_at', now()->month)->sum('amount'),
                    'total_tenants' => User::where('role', 'tenant')->count(),
                    'occupancy_rate' => $totalProperties > 0
                        ? round((Property::where('status', 'rented')->count() / $totalProperties) * 100)
                        : 0,
                ],
                'revenue_by_month' => RentPayment::where('status', 'completed')
                    ->selectRaw('strftime("%m", created_at) as month, sum(amount) as total')
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get(),
            ]
        ]);
    }
}
