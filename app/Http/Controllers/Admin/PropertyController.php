<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index()
    {
        return view('admin.properties.index');
    }

    public function show($id)
    {
        return view('admin.properties.show', compact('id'));
    }

    public function verify($id)
    {
        return redirect()->route('admin.properties.index');
    }
}
