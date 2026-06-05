<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index()
    {
        return view('super-admin.users.index');
    }

    public function create()
    {
        return view('super-admin.users.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('super-admin.users.index');
    }

    public function edit($user)
    {
        return view('super-admin.users.edit', compact('user'));
    }

    public function update(Request $request, $user)
    {
        return redirect()->route('super-admin.users.index');
    }

    public function destroy($user)
    {
        return redirect()->route('super-admin.users.index');
    }

    public function admins()
    {
        return view('super-admin.users.admins');
    }

    public function landlords()
    {
        return view('super-admin.users.landlords');
    }

    public function agents()
    {
        return view('super-admin.users.agents');
    }

    public function tenants()
    {
        return view('super-admin.users.tenants');
    }
}
