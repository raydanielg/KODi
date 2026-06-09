<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        return view('admin.maintenance.index');
    }

    public function show($id)
    {
        return view('admin.maintenance.show', compact('id'));
    }

    public function edit($id)
    {
        return view('admin.maintenance.edit', compact('id'));
    }

    public function updateStatus(Request $request, $id)
    {
        return back();
    }
}
