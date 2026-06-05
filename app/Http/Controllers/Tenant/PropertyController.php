<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index()
    {
        return view('tenant.properties.index');
    }

    public function show($id)
    {
        return view('tenant.properties.show', compact('id'));
    }
}
