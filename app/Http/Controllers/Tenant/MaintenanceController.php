<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        return view('tenant.maintenance.index');
    }

    public function create()
    {
        return view('tenant.maintenance.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('tenant.maintenance.index');
    }

    public function show($id)
    {
        return view('tenant.maintenance.show', compact('id'));
    }
}
