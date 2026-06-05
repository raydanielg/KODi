<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        return view('tenant.messages.index');
    }

    public function show($id)
    {
        return view('tenant.messages.show', compact('id'));
    }

    public function store(Request $request)
    {
        return redirect()->route('tenant.messages.index');
    }
}
