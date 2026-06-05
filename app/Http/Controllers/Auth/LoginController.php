<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect users after login based on their role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo()
    {
        $user = auth()->user();

        if (!$user) {
            return RouteServiceProvider::HOME;
        }

        switch ($user->role) {
            case 'super_admin':
                return '/super-admin/dashboard';
            case 'admin':
                return '/admin/dashboard';
            case 'landlord':
                return '/landlord/dashboard';
            case 'agent':
                return '/agent/dashboard';
            case 'tenant':
                return '/tenant/dashboard';
            case 'support':
                return '/support/dashboard';
            case 'maintenance':
                return '/maintenance/dashboard';
            case 'accountant':
                return '/accountant/dashboard';
            case 'investor':
                return '/investor/dashboard';
            default:
                return RouteServiceProvider::HOME;
        }
    }
}
