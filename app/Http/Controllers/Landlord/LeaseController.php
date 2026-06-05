<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeaseController extends Controller
{
    public function index()
    {
        return view('landlord.leases.index');
    }

    public function show($id)
    {
        return view('landlord.leases.show', compact('id'));
    }

    public function create($property)
    {
        return view('landlord.leases.create', compact('property'));
    }

    public function store(Request $request)
    {
        return redirect()->route('landlord.leases.index');
    }
}
