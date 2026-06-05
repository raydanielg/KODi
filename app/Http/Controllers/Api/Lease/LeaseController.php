<?php

namespace App\Http\Controllers\Api\Lease;

use App\Http\Controllers\Controller;
use App\Models\Lease;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeaseController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            $query = Lease::with(['property', 'tenant', 'landlord']);

            if ($user->role === 'tenant') {
                $query->where('tenant_id', $user->id);
            } elseif ($user->role === 'landlord') {
                $query->where('landlord_id', $user->id);
            } elseif ($user->role === 'agent') {
                $query->where('agent_id', $user->id);
            } elseif (!in_array($user->role, ['admin', 'super_admin'])) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            $leases = $query->latest()->paginate($request->per_page ?? 15);

            return response()->json([
                'success' => true,
                'data' => $leases
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $lease = Lease::with([
                'property.images',
                'property.landlord',
                'tenant',
                'landlord',
                'payments',
                'commissions',
            ])->findOrFail($id);

            $user = $request->user();
            if (!in_array($user->role, ['admin', 'super_admin']) &&
                $lease->tenant_id !== $user->id &&
                $lease->landlord_id !== $user->id &&
                $lease->agent_id !== $user->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            return response()->json([
                'success' => true,
                'data' => $lease
            ]);
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response()->json(['success' => false, 'message' => 'Lease not found'], 404);
            }
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'property_id' => 'required|exists:properties,id',
                'tenant_id' => 'required|exists:users,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'rent_amount' => 'required|numeric|min:0',
                'deposit_amount' => 'nullable|numeric|min:0',
                'deposit_paid' => 'boolean',
                'payment_frequency' => 'required|in:monthly,quarterly,yearly',
                'due_day' => 'required|integer|min:1|max:31',
                'late_fee' => 'nullable|numeric|min:0',
                'penalty_percent' => 'nullable|numeric|min:0|max:100',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $property = Property::findOrFail($request->property_id);

            $data = $request->all();
            $data['landlord_id'] = $property->user_id;
            $data['agent_id'] = $property->agent_id;
            $data['status'] = 'active';

            $lease = Lease::create($data);

            $property->update(['status' => 'rented']);

            return response()->json([
                'success' => true,
                'message' => 'Lease imeundwa.',
                'data' => $lease->load(['property', 'tenant', 'landlord'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $lease = Lease::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'end_date' => 'sometimes|date|after:start_date',
                'rent_amount' => 'sometimes|numeric|min:0',
                'deposit_amount' => 'nullable|numeric|min:0',
                'deposit_paid' => 'boolean',
                'status' => 'sometimes|in:active,expired,terminated,renewed',
                'late_fee' => 'nullable|numeric|min:0',
                'penalty_percent' => 'nullable|numeric|min:0|max:100',
                'notes' => 'nullable|string',
                'termination_reason' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            if ($request->has('status') && $request->status === 'terminated') {
                $data = $request->all();
                $data['terminated_at'] = now();

                if ($request->has('termination_reason')) {
                    $data['termination_reason'] = $request->termination_reason;
                }

                $lease->update($data);

                $lease->property->update(['status' => 'available']);
            } else {
                $lease->update($request->all());
            }

            return response()->json([
                'success' => true,
                'message' => 'Lease imesasishwa.',
                'data' => $lease->fresh()->load(['property', 'tenant', 'landlord'])
            ]);
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response()->json(['success' => false, 'message' => 'Lease not found'], 404);
            }
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
