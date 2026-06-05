<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'email_verified_at',
        'avatar',
        'bio',
        'language',
        'timezone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isLandlord()
    {
        return $this->role === 'landlord';
    }

    public function isAgent()
    {
        return $this->role === 'agent';
    }

    public function isTenant()
    {
        return $this->role === 'tenant';
    }

    public function isSupport()
    {
        return $this->role === 'support';
    }

    public function isMaintenance()
    {
        return $this->role === 'maintenance';
    }

    public function isAccountant()
    {
        return $this->role === 'accountant';
    }

    public function isInvestor()
    {
        return $this->role === 'investor';
    }

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

    public function properties()
    {
        return $this->hasMany(Property::class, 'user_id');
    }

    public function agentProperties()
    {
        return $this->hasMany(Property::class, 'agent_id');
    }

    public function leasesAsTenant()
    {
        return $this->hasMany(Lease::class, 'tenant_id');
    }

    public function leasesAsLandlord()
    {
        return $this->hasMany(Lease::class, 'landlord_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function applicationsAsTenant()
    {
        return $this->hasMany(Application::class, 'tenant_id');
    }

    public function paymentsAsTenant()
    {
        return $this->hasMany(RentPayment::class, 'tenant_id');
    }

    public function maintenanceRequestsAsTenant()
    {
        return $this->hasMany(MaintenanceRequest::class, 'tenant_id');
    }

    public function maintenanceAssigned()
    {
        return $this->hasMany(MaintenanceRequest::class, 'assigned_to');
    }

    public function reviewsGiven()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class, 'agent_id');
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class, 'user_id');
    }

    public function disputesRaised()
    {
        return $this->hasMany(Dispute::class, 'raised_by');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'uploader_id');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeActive($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    public function scopeLandlords($query)
    {
        return $query->where('role', 'landlord');
    }

    public function scopeTenants($query)
    {
        return $query->where('role', 'tenant');
    }

    public function scopeAgents($query)
    {
        return $query->where('role', 'agent');
    }

    public function scopeAdmins($query)
    {
        return $query->whereIn('role', ['admin', 'super_admin']);
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email))) . '?d=mp';
    }

    public function getInitialsAttribute()
    {
        $parts = explode(' ', $this->name);
        $initials = '';

        foreach ($parts as $part) {
            $initials .= strtoupper(substr($part, 0, 1));
        }

        return substr($initials, 0, 2);
    }

    public function getDashboardUrlAttribute()
    {
        return $this->getDashboardUrl();
    }

    public function getRoleLabelAttribute()
    {
        $labels = [
            'super_admin' => 'Msimamizi Mkuu',
            'admin' => 'Msimamizi',
            'landlord' => 'Mwenye Nyumba',
            'agent' => 'Wakala',
            'tenant' => 'Mpangaji',
            'support' => 'Msaada',
            'maintenance' => 'Matengenezo',
            'accountant' => 'Mhasibu',
            'investor' => 'Mwekezaji',
        ];

        return $labels[$this->role] ?? $this->role;
    }
}
