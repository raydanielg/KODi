<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index()
    {
        return view('agent.properties.index');
    }

    public function create()
    {
        return view('agent.properties.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('agent.properties.index');
    }
}
