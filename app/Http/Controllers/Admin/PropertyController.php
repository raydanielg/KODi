<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with('landlord');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('location_area', 'like', "%$search%")
                  ->orWhere('location_city', 'like', "%$search%");
            });
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($type = $request->get('type')) {
            $query->where('property_type', $type);
        }

        $properties = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total'     => Property::count(),
            'available' => Property::where('status', 'available')->count(),
            'rented'    => Property::where('status', 'rented')->count(),
            'pending'   => Property::whereNull('approved_at')->count(),
        ];

        return view('admin.properties.index', compact('properties', 'stats'));
    }

    public function show($id)
    {
        $property = Property::with('landlord')->findOrFail($id);
        return view('admin.properties.show', compact('property'));
    }

    public function edit($id)
    {
        $property = Property::findOrFail($id);
        return view('admin.properties.edit', compact('property'));
    }

    public function update(Request $request, $id)
    {
        $property = Property::findOrFail($id);
        $property->update($request->only(['title', 'status', 'price', 'property_type']));
        return redirect()->route('admin.properties.index')->with('success', 'Property updated.');
    }

    public function destroy($id)
    {
        Property::findOrFail($id)->delete();
        return redirect()->route('admin.properties.index')->with('success', 'Property deleted.');
    }

    public function verify($id)
    {
        $property = Property::findOrFail($id);
        $property->update(['approved_at' => now()]);
        return redirect()->route('admin.properties.index')->with('success', 'Property verified and approved.');
    }
}
