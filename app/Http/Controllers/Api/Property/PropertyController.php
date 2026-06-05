<?php

namespace App\Http\Controllers\Api\Property;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Favorite;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Property::with(['landlord', 'images', 'amenities', 'activeLease']);

            if ($request->has('type')) {
                $query->byType($request->type);
            }

            if ($request->has('city')) {
                $query->byCity($request->city);
            }

            if ($request->has('region')) {
                $query->byRegion($request->region);
            }

            if ($request->has('bedrooms')) {
                $query->bedrooms($request->bedrooms);
            }

            if ($request->has('min_price') && $request->has('max_price')) {
                $query->priceRange($request->min_price, $request->max_price);
            } elseif ($request->has('min_price')) {
                $query->where('price', '>=', $request->min_price);
            } elseif ($request->has('max_price')) {
                $query->where('price', '<=', $request->max_price);
            }

            if ($request->has('furnished')) {
                $query->furnished();
            }

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('sort')) {
                switch ($request->sort) {
                    case 'price_asc':
                        $query->orderBy('price', 'asc');
                        break;
                    case 'price_desc':
                        $query->orderBy('price', 'desc');
                        break;
                    case 'newest':
                        $query->latest();
                        break;
                    case 'oldest':
                        $query->oldest();
                        break;
                    default:
                        $query->latest();
                }
            } else {
                $query->latest();
            }

            $properties = $query->paginate($request->per_page ?? 12);

            return response()->json([
                'success' => true,
                'data' => $properties
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $property = Property::with([
                'landlord',
                'agent',
                'images',
                'amenities',
                'activeLease.tenant',
                'reviews.reviewer',
            ])->findOrFail($id);

            $property->increment('views_count');

            return response()->json([
                'success' => true,
                'data' => $property
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Property not found'], 404);
        }
    }

    public function search(Request $request)
    {
        try {
            $query = Property::with(['landlord', 'images', 'amenities']);

            if ($request->has('q')) {
                $searchTerm = $request->q;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'like', "%{$searchTerm}%")
                      ->orWhere('description', 'like', "%{$searchTerm}%")
                      ->orWhere('location_area', 'like', "%{$searchTerm}%")
                      ->orWhere('location_city', 'like', "%{$searchTerm}%")
                      ->orWhere('location_region', 'like', "%{$searchTerm}%")
                      ->orWhere('location_address', 'like', "%{$searchTerm}%");
                });
            }

            if ($request->has('city')) {
                $query->byCity($request->city);
            }

            if ($request->has('type')) {
                $query->byType($request->type);
            }

            if ($request->has('bedrooms')) {
                $query->bedrooms($request->bedrooms);
            }

            if ($request->has('min_price') && $request->has('max_price')) {
                $query->priceRange($request->min_price, $request->max_price);
            }

            if ($request->has('furnished')) {
                $query->furnished();
            }

            $properties = $query->latest()->paginate($request->per_page ?? 12);

            return response()->json([
                'success' => true,
                'data' => $properties
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function featured()
    {
        try {
            $properties = Property::with(['landlord', 'images', 'amenities'])
                ->featured()
                ->where('status', 'available')
                ->latest()
                ->take(6)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $properties
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'property_type' => 'required|in:house,apartment,room,commercial,land',
                'price' => 'required|numeric|min:0',
                'currency' => 'required|string|max:10',
                'bedrooms' => 'nullable|integer|min:0',
                'bathrooms' => 'nullable|integer|min:0',
                'area_sqft' => 'nullable|numeric|min:0',
                'location_area' => 'nullable|string|max:255',
                'location_city' => 'required|string|max:255',
                'location_region' => 'nullable|string|max:255',
                'location_address' => 'nullable|string',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'deposit' => 'nullable|numeric|min:0',
                'is_furnished' => 'boolean',
                'has_internet' => 'boolean',
                'has_water' => 'boolean',
                'has_electricity' => 'boolean',
                'has_parking' => 'boolean',
                'has_security' => 'boolean',
                'has_generator' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $data = $request->all();
            $data['user_id'] = $request->user()->id;
            $data['slug'] = Str::slug($request->title) . '-' . Str::random(6);

            if (in_array($request->user()->role, ['admin', 'super_admin', 'agent'])) {
                $data['agent_id'] = $request->user()->id;
            }

            $property = Property::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Property imeongezwa.',
                'data' => $property->load(['landlord', 'images', 'amenities'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $property = Property::findOrFail($id);

            if ($property->user_id !== $request->user()->id && !in_array($request->user()->role, ['admin', 'super_admin'])) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'property_type' => 'sometimes|in:house,apartment,room,commercial,land',
                'price' => 'sometimes|numeric|min:0',
                'currency' => 'sometimes|string|max:10',
                'bedrooms' => 'nullable|integer|min:0',
                'bathrooms' => 'nullable|integer|min:0',
                'area_sqft' => 'nullable|numeric|min:0',
                'location_area' => 'nullable|string|max:255',
                'location_city' => 'sometimes|string|max:255',
                'location_region' => 'nullable|string|max:255',
                'location_address' => 'nullable|string',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'deposit' => 'nullable|numeric|min:0',
                'status' => 'sometimes|in:available,rented,under_maintenance,unavailable',
                'is_furnished' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $property->update($request->all());

            if ($request->has('title')) {
                $property->slug = Str::slug($request->title) . '-' . Str::random(6);
                $property->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Property imesasishwa.',
                'data' => $property->fresh()->load(['landlord', 'images', 'amenities'])
            ]);
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response()->json(['success' => false, 'message' => 'Property not found'], 404);
            }
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $property = Property::findOrFail($id);

            if ($property->user_id !== $request->user()->id && !in_array($request->user()->role, ['admin', 'super_admin'])) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $property->delete();

            return response()->json([
                'success' => true,
                'message' => 'Property imefutwa.'
            ]);
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response()->json(['success' => false, 'message' => 'Property not found'], 404);
            }
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function toggleFavorite(Request $request, $id)
    {
        try {
            $property = Property::findOrFail($id);
            $user = $request->user();

            $favorite = Favorite::where('user_id', $user->id)
                ->where('property_id', $property->id)
                ->first();

            if ($favorite) {
                $favorite->delete();
                $message = 'Property imeondolewa kwenye favorites.';
                $is_favorited = false;
            } else {
                Favorite::create([
                    'user_id' => $user->id,
                    'property_id' => $property->id,
                ]);
                $message = 'Property imeongezwa kwenye favorites.';
                $is_favorited = true;
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => ['is_favorited' => $is_favorited]
            ]);
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response()->json(['success' => false, 'message' => 'Property not found'], 404);
            }
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function favorites(Request $request)
    {
        try {
            $favorites = $request->user()->favorites()
                ->with('property.images', 'property.landlord')
                ->latest()
                ->paginate($request->per_page ?? 12);

            return response()->json([
                'success' => true,
                'data' => $favorites
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function applications(Request $request)
    {
        try {
            $user = $request->user();

            if (in_array($user->role, ['admin', 'super_admin'])) {
                $applications = Application::with(['property', 'tenant', 'landlord'])
                    ->latest()
                    ->paginate($request->per_page ?? 15);
            } elseif ($user->role === 'landlord') {
                $applications = Application::where('landlord_id', $user->id)
                    ->with(['property', 'tenant'])
                    ->latest()
                    ->paginate($request->per_page ?? 15);
            } elseif ($user->role === 'agent') {
                $applications = Application::where('agent_id', $user->id)
                    ->with(['property', 'tenant', 'landlord'])
                    ->latest()
                    ->paginate($request->per_page ?? 15);
            } else {
                $applications = Application::where('tenant_id', $user->id)
                    ->with(['property', 'landlord'])
                    ->latest()
                    ->paginate($request->per_page ?? 15);
            }

            return response()->json([
                'success' => true,
                'data' => $applications
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function apply(Request $request, $id)
    {
        try {
            $property = Property::findOrFail($id);
            $user = $request->user();

            $existing = Application::where('property_id', $property->id)
                ->where('tenant_id', $user->id)
                ->whereIn('status', ['pending', 'approved'])
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Umeshatuma ombi la nyumba hii.'
                ], 409);
            }

            $validator = Validator::make($request->all(), [
                'expected_move_in' => 'nullable|date',
                'monthly_offer' => 'nullable|numeric|min:0',
                'tenant_message' => 'nullable|string|max:2000',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $application = Application::create([
                'property_id' => $property->id,
                'tenant_id' => $user->id,
                'landlord_id' => $property->user_id,
                'agent_id' => $property->agent_id,
                'status' => 'pending',
                'expected_move_in' => $request->expected_move_in,
                'monthly_offer' => $request->monthly_offer,
                'tenant_message' => $request->tenant_message,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ombi limewasilishwa. Subiri majibu.',
                'data' => $application->load('property', 'landlord')
            ], 201);
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response()->json(['success' => false, 'message' => 'Property not found'], 404);
            }
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
