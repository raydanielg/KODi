<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        return view('landlord.maintenance.index');
    }

    public function show($id)
    {
        return view('landlord.maintenance.show', compact('id'));
    }
}
