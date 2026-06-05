<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        return view('landlord.applications.index');
    }

    public function show($id)
    {
        return view('landlord.applications.show', compact('id'));
    }

    public function approve($id)
    {
        return redirect()->route('landlord.applications.index');
    }

    public function reject($id)
    {
        return redirect()->route('landlord.applications.index');
    }
}
