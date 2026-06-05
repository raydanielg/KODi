<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        return view('agent.applications.index');
    }

    public function show($id)
    {
        return view('agent.applications.show', compact('id'));
    }
}
