<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        return view('tenant.applications.index');
    }

    public function apply($property)
    {
        return view('tenant.applications.apply', compact('property'));
    }
}
