<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceRequest;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = MaintenanceRequest::with(['tenant', 'property']);

        if ($search = $request->get('search')) {
            $query->where('title', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($priority = $request->get('priority')) {
            $query->where('priority', $priority);
        }

        $requests = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total'       => MaintenanceRequest::count(),
            'open'        => MaintenanceRequest::where('status', 'open')->count(),
            'in_progress' => MaintenanceRequest::where('status', 'in_progress')->count(),
            'completed'   => MaintenanceRequest::where('status', 'completed')->count(),
        ];

        return view('admin.maintenance.index', compact('requests', 'stats'));
    }

    public function show($id)
    {
        $request = MaintenanceRequest::with(['tenant', 'property', 'landlord'])->findOrFail($id);
        return view('admin.maintenance.show', compact('request'));
    }

    public function edit($id)
    {
        $request = MaintenanceRequest::findOrFail($id);
        return view('admin.maintenance.edit', compact('request'));
    }

    public function updateStatus(Request $request, $id)
    {
        $mr = MaintenanceRequest::findOrFail($id);
        $mr->update(['status' => $request->status]);
        return redirect()->route('admin.maintenance.index')->with('success', 'Status updated.');
    }
}
