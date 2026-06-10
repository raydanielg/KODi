<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->withCount(['properties', 'leasesAsTenant']);

        if ($role = $request->get('role')) {
            $query->where('role', $role);
        }
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }
        if ($status = $request->get('status')) {
            if ($status === 'active') {
                $query->whereNull('suspended_at');
            } elseif ($status === 'suspended') {
                $query->whereNotNull('suspended_at');
            }
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total'     => User::count(),
            'tenants'   => User::where('role', 'tenant')->count(),
            'landlords' => User::where('role', 'landlord')->count(),
            'agents'    => User::where('role', 'agent')->count(),
            'admins'    => User::whereIn('role', ['admin', 'super_admin'])->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role'     => 'required|in:tenant,landlord,agent,admin',
        ]);
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);
        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show($id)
    {
        $user = User::withCount(['properties', 'leasesAsTenant'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->only(['name', 'email', 'phone', 'role', 'gender']));
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }

    public function suspend($id)
    {
        $user = User::findOrFail($id);
        if ($user->suspended_at) {
            $user->update(['suspended_at' => null]);
            $msg = 'User activated successfully.';
        } else {
            $user->update(['suspended_at' => now()]);
            $msg = 'User suspended successfully.';
        }
        return redirect()->route('admin.users.index')->with('success', $msg);
    }

    public function verify()
    {
        return view('admin.users.verify');
    }
}
