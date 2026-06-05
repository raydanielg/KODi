<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lease extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_id',
        'tenant_id',
        'landlord_id',
        'agent_id',
        'lease_number',
        'status',
        'start_date',
        'end_date',
        'rent_amount',
        'deposit_amount',
        'deposit_paid',
        'payment_frequency',
        'due_day',
        'late_fee',
        'penalty_percent',
        'lease_document',
        'renewal_count',
        'terminated_at',
        'termination_reason',
        'notes',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($lease) {
            if (empty($lease->lease_number)) {
                $lease->lease_number = 'LSE-' . strtoupper(uniqid());
            }
        });
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function payments()
    {
        return $this->hasMany(RentPayment::class);
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    public function disputes()
    {
        return $this->hasMany(Dispute::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('status', 'active')
            ->whereBetween('end_date', [now(), now()->addDays($days)]);
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'active' => 'Inatumika',
            'expired' => 'Imeisha',
            'terminated' => 'Imesitishwa',
            'renewed' => 'Imefanywa Upya',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active';
    }

    public function getRemainingDaysAttribute()
    {
        if ($this->status !== 'active') {
            return 0;
        }

        return max(0, now()->diffInDays($this->end_date, false));
    }
}
