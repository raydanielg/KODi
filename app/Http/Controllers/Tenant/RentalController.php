<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    public function index()
    {
        return view('tenant.rentals.index');
    }

    public function show($id)
    {
        return view('tenant.rentals.show', compact('id'));
    }
}
