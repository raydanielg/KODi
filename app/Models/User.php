<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Check if user is a super admin.
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if user is an admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a landlord.
     *
     * @return bool
     */
    public function isLandlord()
    {
        return $this->role === 'landlord';
    }

    /**
     * Check if user is an agent.
     *
     * @return bool
     */
    public function isAgent()
    {
        return $this->role === 'agent';
    }

    /**
     * Check if user is a tenant.
     *
     * @return bool
     */
    public function isTenant()
    {
        return $this->role === 'tenant';
    }

    /**
     * Check if user is support staff.
     *
     * @return bool
     */
    public function isSupport()
    {
        return $this->role === 'support';
    }

    /**
     * Check if user is maintenance staff.
     *
     * @return bool
     */
    public function isMaintenance()
    {
        return $this->role === 'maintenance';
    }

    /**
     * Check if user is an accountant.
     *
     * @return bool
     */
    public function isAccountant()
    {
        return $this->role === 'accountant';
    }

    /**
     * Check if user is an investor.
     *
     * @return bool
     */
    public function isInvestor()
    {
        return $this->role === 'investor';
    }

    /**
     * Get the dashboard URL for the user based on their role.
     *
     * @return string
     */
    public function getDashboardUrl()
    {
        switch ($this->role) {
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
                return '/home';
        }
    }
}
