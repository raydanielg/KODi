<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DisputeController extends Controller
{
    public function index()
    {
        return view('admin.disputes.index');
    }

    public function show($id)
    {
        return view('admin.disputes.show', compact('id'));
    }
}
