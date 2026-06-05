<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'lease_id',
        'agent_id',
        'property_id',
        'landlord_id',
        'amount',
        'rate_percent',
        'status',
        'notes',
    ];

    public function lease()
    {
        return $this->belongsTo(Lease::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    public function paidBy()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Inasubiri',
            'paid' => 'Imelipwa',
            'cancelled' => 'Imeghairiwa',
        ];

        return $labels[$this->status] ?? $this->status;
    }
}
