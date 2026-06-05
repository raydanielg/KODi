<?php

namespace App\Http\Controllers\Api\Maintenance;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Models\Lease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            $query = MaintenanceRequest::with(['property', 'tenant', 'landlord', 'assignedTo']);

            if ($user->role === 'tenant') {
                $query->where('tenant_id', $user->id);
            } elseif ($user->role === 'landlord') {
                $query->where('landlord_id', $user->id);
            } elseif ($user->role === 'maintenance') {
                $query->where(function ($q) use ($user) {
                    $q->where('assigned_to', $user->id)
                      ->orWhereIn('status', ['pending', 'assigned']);
                });
            } elseif (!in_array($user->role, ['admin', 'super_admin'])) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('priority')) {
                $query->where('priority', $request->priority);
            }

            if ($request->has('category')) {
                $query->where('category', $request->category);
            }

            $requests = $query->latest()->paginate($request->per_page ?? 15);

            return response()->json([
                'success' => true,
                'data' => $requests
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'property_id' => 'required|exists:properties,id',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'category' => 'required|in:plumbing,electrical,structural,appliance,pest_control,cleaning,painting,other',
                'priority' => 'required|in:low,medium,high,urgent',
                'scheduled_at' => 'nullable|date|after:now',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $user = $request->user();
            $property = Property::findOrFail($request->property_id);
            $lease = Lease::where('property_id', $property->id)
                ->where('tenant_id', $user->id)
                ->where('status', 'active')
                ->first();

            if (!$lease && $user->role === 'tenant') {
                return response()->json([
                    'success' => false,
                    'message' => 'Huna lease active kwa property hii.'
                ], 403);
            }

            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePaths[] = $image->store('maintenance', 'public');
                }
            }

            $maintenanceRequest = MaintenanceRequest::create([
                'property_id' => $property->id,
                'tenant_id' => $user->id,
                'landlord_id' => $property->user_id,
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'priority' => $request->priority,
                'status' => 'pending',
                'scheduled_at' => $request->scheduled_at,
                'images' => $imagePaths,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ombi la matengenezo limewasilishwa.',
                'data' => $maintenanceRequest->load(['property', 'tenant'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $maintenanceRequest = MaintenanceRequest::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'status' => 'required|in:pending,assigned,in_progress,completed,cancelled,on_hold',
                'assigned_to' => 'required_if:status,assigned|exists:users,id',
                'resolution_notes' => 'nullable|string|max:5000',
                'actual_cost' => 'nullable|numeric|min:0',
                'tenant_rating' => 'nullable|integer|min:1|max:5',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $data = $request->only(['status', 'assigned_to', 'resolution_notes', 'actual_cost', 'tenant_rating']);

            if ($request->status === 'completed') {
                $data['completed_at'] = now();
            }

            $maintenanceRequest->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Status ya matengenezo imesasishwa.',
                'data' => $maintenanceRequest->fresh()->load(['property', 'tenant', 'assignedTo'])
            ]);
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response()->json(['success' => false, 'message' => 'Maintenance request not found'], 404);
            }
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
