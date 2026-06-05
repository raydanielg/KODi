<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index()
    {
        return view('super-admin.user-management.index');
    }

    public function create()
    {
        return view('super-admin.user-management.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('super-admin.user-management.index');
    }

    public function edit($user)
    {
        return view('super-admin.user-management.edit', compact('user'));
    }

    public function update(Request $request, $user)
    {
        return redirect()->route('super-admin.user-management.index');
    }

    public function destroy($user)
    {
        return redirect()->route('super-admin.user-management.index');
    }

    public function admins()
    {
        return view('super-admin.user-management.admins');
    }

    public function landlords()
    {
        return view('super-admin.user-management.landlords');
    }

    public function agents()
    {
        return view('super-admin.user-management.agents');
    }

    public function tenants()
    {
        return view('super-admin.user-management.tenants');
    }
}
