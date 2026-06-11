<?php

namespace App\Http\Controllers\Api\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Lease;
use App\Models\RentPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LandlordApiController extends Controller
{
    // ─── Dashboard ────────────────────────────────────────────────────────────

    public function dashboard(Request $request)
    {
        $user = $request->user();
        $properties = Property::where('user_id', $user->id)->withCount(['leases as active_leases_count' => function ($q) {
            $q->where('status', 'active');
        }])->get();

        $propertyIds = $properties->pluck('id');
        $activeLeases = Lease::whereIn('property_id', $propertyIds)->where('status', 'active')->with('tenant')->get();

        $now = now();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();

        $monthPayments = RentPayment::whereIn('lease_id', $activeLeases->pluck('id'))
            ->whereBetween('paid_at', [$monthStart, $monthEnd])
            ->where('status', 'completed')
            ->get();

        $expectedRent = $activeLeases->sum('rent_amount');
        $collectedRevenue = $monthPayments->sum('amount');
        $outstandingRevenue = max(0, $expectedRent - $collectedRevenue);

        $totalUnits = $properties->count();
        $occupiedUnits = $activeLeases->count();
        $vacantUnits = $totalUnits - $occupiedUnits;
        $occupancyRate = $totalUnits > 0 ? round(($occupiedUnits / $totalUnits) * 100) : 0;

        // Pending payments (leases without payment this month)
        $paidLeaseIds = $monthPayments->pluck('lease_id');
        $pendingPayments = $activeLeases->whereNotIn('id', $paidLeaseIds)->count();

        $maintenanceRequests = \App\Models\MaintenanceRequest::whereIn('property_id', $propertyIds)
            ->whereIn('status', ['open', 'in_progress'])->count();

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'my_properties' => $properties->count(),
                    'total_units' => $totalUnits,
                    'occupied_units' => $occupiedUnits,
                    'vacant_units' => $vacantUnits,
                    'occupancy_rate' => $occupancyRate,
                    'collected_revenue' => $collectedRevenue,
                    'outstanding_revenue' => $outstandingRevenue,
                    'expected_rent' => $expectedRent,
                    'active_leases' => $activeLeases->count(),
                    'pending_payments' => $pendingPayments,
                    'maintenance_requests' => $maintenanceRequests,
                ],
                'my_properties' => $properties,
            ]
        ]);
    }

    // ─── Properties ───────────────────────────────────────────────────────────

    public function properties(Request $request)
    {
        $user = $request->user();
        $properties = Property::where('user_id', $user->id)
            ->with(['activeLease.tenant'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $properties
        ]);
    }

    public function storeProperty(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'property_type' => 'required|in:house,apartment,room,commercial,land',
            'price' => 'required|numeric|min:0',
            'location_city' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors(), 'message' => $validator->errors()->first()], 422);
        }

        $user = $request->user();

        $property = Property::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'slug' => \Str::slug($request->title) . '-' . uniqid(),
            'description' => $request->description ?? '',
            'property_type' => $request->property_type,
            'status' => 'available',
            'price' => $request->price,
            'currency' => $request->currency ?? 'TZS',
            'deposit' => $request->deposit ?? 0,
            'bedrooms' => $request->bedrooms ?? 1,
            'bathrooms' => $request->bathrooms ?? 1,
            'area_sqft' => $request->area_sqft,
            'location_area' => $request->location_area ?? '',
            'location_city' => $request->location_city,
            'location_region' => $request->location_region ?? '',
            'location_address' => $request->location_address ?? '',
            'is_furnished' => $request->is_furnished ?? false,
            'has_internet' => $request->has_internet ?? false,
            'has_water' => $request->has_water ?? true,
            'has_electricity' => $request->has_electricity ?? true,
            'has_parking' => $request->has_parking ?? false,
            'has_security' => $request->has_security ?? false,
            'has_generator' => $request->has_generator ?? false,
        ]);

        return response()->json(['success' => true, 'message' => 'Property created', 'data' => $property], 201);
    }

    public function updateProperty(Request $request, $id)
    {
        $user = $request->user();
        $property = Property::where('id', $id)->where('user_id', $user->id)->firstOrFail();
        $property->update($request->only([
            'title', 'description', 'property_type', 'status', 'price', 'deposit',
            'bedrooms', 'bathrooms', 'location_area', 'location_city', 'location_address',
            'is_furnished', 'has_internet', 'has_water', 'has_electricity', 'has_parking',
        ]));

        return response()->json(['success' => true, 'message' => 'Property updated', 'data' => $property]);
    }

    public function deleteProperty(Request $request, $id)
    {
        $user = $request->user();
        $property = Property::where('id', $id)->where('user_id', $user->id)->firstOrFail();
        $property->delete();

        return response()->json(['success' => true, 'message' => 'Property deleted']);
    }

    // ─── Tenants ──────────────────────────────────────────────────────────────

    public function tenants(Request $request)
    {
        $user = $request->user();
        $propertyIds = Property::where('user_id', $user->id)->pluck('id');

        $query = User::whereHas('leasesAsTenant', function ($q) use ($propertyIds) {
            $q->whereIn('property_id', $propertyIds);
        });

        if ($request->search) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('email', 'like', $search)
                  ->orWhere('phone', 'like', $search);
            });
        }

        $tenants = $query->with(['leasesAsTenant' => function ($q) use ($propertyIds) {
            $q->whereIn('property_id', $propertyIds)->with('property')->latest()->limit(1);
        }])->get();

        $now = now();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();

        $result = $tenants->map(function ($tenant) use ($propertyIds, $monthStart, $monthEnd) {
            $lease = $tenant->leasesAsTenant->first();
            $hasPaid = false;
            $balance = 0;

            if ($lease) {
                $hasPaid = RentPayment::where('lease_id', $lease->id)
                    ->whereBetween('paid_at', [$monthStart, $monthEnd])
                    ->where('status', 'completed')
                    ->exists();
                $balance = $lease->rent_amount - RentPayment::where('lease_id', $lease->id)
                    ->whereBetween('paid_at', [$monthStart, $monthEnd])
                    ->where('status', 'completed')
                    ->sum('amount');
            }

            return [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'first_name' => explode(' ', $tenant->name)[0] ?? $tenant->name,
                'last_name' => implode(' ', array_slice(explode(' ', $tenant->name), 1)) ?: '',
                'email' => $tenant->email,
                'phone' => $tenant->phone ?? '',
                'gender' => $tenant->gender ?? null,
                'avatar' => $tenant->avatar ?? null,
                'property_title' => $lease?->property?->title,
                'lease_status' => $lease?->status,
                'rent_amount' => $lease?->rent_amount,
                'lease_end' => $lease?->end_date,
                'has_paid_this_month' => $hasPaid,
                'outstanding_balance' => max(0, $balance),
                'created_at' => $tenant->created_at,
            ];
        });

        return response()->json(['success' => true, 'data' => $result]);
    }

    public function storeTenant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors(), 'message' => $validator->errors()->first()], 422);
        }

        $tenant = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'password' => Hash::make(\Str::random(12)),
            'role' => 'tenant',
        ]);

        // If property_id and lease info provided, create a lease too
        if ($request->property_id && $request->rent_amount) {
            $landlordId = $request->user()->id;
            Lease::create([
                'property_id' => $request->property_id,
                'tenant_id' => $tenant->id,
                'landlord_id' => $landlordId,
                'lease_number' => 'LS-' . strtoupper(uniqid()),
                'status' => 'active',
                'start_date' => $request->lease_start ?? now()->toDateString(),
                'end_date' => $request->lease_end ?? now()->addYear()->toDateString(),
                'rent_amount' => $request->rent_amount,
                'deposit_amount' => $request->deposit_amount ?? 0,
                'currency' => 'TZS',
                'payment_frequency' => 'monthly',
                'due_day' => 1,
            ]);

            // Mark property as rented
            Property::where('id', $request->property_id)->update(['status' => 'rented']);
        }

        return response()->json(['success' => true, 'message' => 'Tenant added successfully', 'data' => $tenant], 201);
    }

    public function tenantDetail(Request $request, $id)
    {
        $user = $request->user();
        $propertyIds = Property::where('user_id', $user->id)->pluck('id');

        $tenant = User::whereHas('leasesAsTenant', function ($q) use ($propertyIds) {
            $q->whereIn('property_id', $propertyIds);
        })->with(['leasesAsTenant' => function ($q) use ($propertyIds) {
            $q->whereIn('property_id', $propertyIds)->with('property');
        }])->findOrFail($id);

        return response()->json(['success' => true, 'data' => $tenant]);
    }

    // ─── Leases ───────────────────────────────────────────────────────────────

    public function leases(Request $request)
    {
        $user = $request->user();
        $propertyIds = Property::where('user_id', $user->id)->pluck('id');

        $query = Lease::whereIn('property_id', $propertyIds)->with(['property', 'tenant']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $leases = $query->orderByDesc('created_at')->get();

        return response()->json(['success' => true, 'data' => $leases]);
    }

    public function storeLease(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required|integer',
            'tenant_id' => 'required|integer',
            'rent_amount' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors(), 'message' => $validator->errors()->first()], 422);
        }

        $user = $request->user();
        $property = Property::where('id', $request->property_id)->where('user_id', $user->id)->firstOrFail();

        $lease = Lease::create([
            'property_id' => $request->property_id,
            'tenant_id' => $request->tenant_id,
            'landlord_id' => $user->id,
            'lease_number' => 'LS-' . strtoupper(uniqid()),
            'status' => 'active',
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'rent_amount' => $request->rent_amount,
            'deposit_amount' => $request->deposit_amount ?? 0,
            'deposit_paid' => false,
            'currency' => $request->currency ?? 'TZS',
            'payment_frequency' => $request->payment_frequency ?? 'monthly',
            'due_day' => $request->due_day ?? 1,
            'late_fee' => $request->late_fee ?? 0,
        ]);

        $property->update(['status' => 'rented']);

        return response()->json(['success' => true, 'message' => 'Lease created', 'data' => $lease->load(['property', 'tenant'])], 201);
    }

    public function terminateLease(Request $request, $id)
    {
        $user = $request->user();
        $propertyIds = Property::where('user_id', $user->id)->pluck('id');
        $lease = Lease::whereIn('property_id', $propertyIds)->findOrFail($id);

        $lease->update([
            'status' => 'terminated',
            'terminated_at' => now(),
            'termination_reason' => $request->reason ?? 'Terminated by landlord',
        ]);

        Property::where('id', $lease->property_id)->update(['status' => 'available']);

        return response()->json(['success' => true, 'message' => 'Lease terminated']);
    }

    // ─── Payments ─────────────────────────────────────────────────────────────

    public function payments(Request $request)
    {
        $user = $request->user();
        $propertyIds = Property::where('user_id', $user->id)->pluck('id');
        $leaseIds = Lease::whereIn('property_id', $propertyIds)->pluck('id');

        $query = RentPayment::whereIn('lease_id', $leaseIds)->with(['lease.property', 'lease.tenant']);

        if ($request->status) $query->where('status', $request->status);
        if ($request->month) {
            $query->whereYear('paid_at', substr($request->month, 0, 4))
                  ->whereMonth('paid_at', substr($request->month, 5, 2));
        }

        $payments = $query->orderByDesc('paid_at')->paginate(20);

        return response()->json(['success' => true, 'data' => $payments]);
    }

    public function recordPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lease_id' => 'required|integer',
            'amount' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $user = $request->user();
        $propertyIds = Property::where('user_id', $user->id)->pluck('id');
        $lease = Lease::whereIn('property_id', $propertyIds)->findOrFail($request->lease_id);

        $payment = RentPayment::create([
            'lease_id' => $lease->id,
            'tenant_id' => $lease->tenant_id,
            'landlord_id' => $user->id,
            'amount' => $request->amount,
            'currency' => $lease->currency ?? 'TZS',
            'payment_method' => $request->payment_method ?? 'cash',
            'status' => 'completed',
            'paid_at' => $request->paid_at ?? now(),
            'notes' => $request->notes,
            'transaction_id' => 'MANUAL-' . strtoupper(uniqid()),
        ]);

        return response()->json(['success' => true, 'message' => 'Payment recorded', 'data' => $payment], 201);
    }

    // ─── Reports ──────────────────────────────────────────────────────────────

    public function revenueReport(Request $request)
    {
        $user = $request->user();
        $year = $request->year ?? now()->year;
        $propertyIds = Property::where('user_id', $user->id)->pluck('id');
        $leaseIds = Lease::whereIn('property_id', $propertyIds)->pluck('id');

        $monthlyData = [];
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $totalCollected = 0;
        $totalExpected = 0;

        for ($m = 1; $m <= 12; $m++) {
            $collected = RentPayment::whereIn('lease_id', $leaseIds)
                ->where('status', 'completed')
                ->whereYear('paid_at', $year)
                ->whereMonth('paid_at', $m)
                ->sum('amount');

            $expected = Lease::whereIn('id', $leaseIds)
                ->where('status', 'active')
                ->sum('rent_amount');

            $monthlyData[] = [
                'month' => $monthNames[$m - 1] . ' ' . $year,
                'collected' => (float) $collected,
                'expected' => (float) $expected,
            ];

            $totalCollected += $collected;
            $totalExpected += $expected;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'year' => $year,
                'total_collected' => $totalCollected,
                'total_expected' => $totalExpected,
                'monthly' => $monthlyData,
            ]
        ]);
    }

    public function occupancyReport(Request $request)
    {
        $user = $request->user();
        $propertyIds = Property::where('user_id', $user->id)->pluck('id');
        $total = count($propertyIds);
        $occupied = Lease::whereIn('property_id', $propertyIds)->where('status', 'active')->count();
        $vacant = $total - $occupied;

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'occupied' => $occupied,
                'vacant' => $vacant,
                'occupancy_rate' => $total > 0 ? round(($occupied / $total) * 100) : 0,
            ]
        ]);
    }
}
