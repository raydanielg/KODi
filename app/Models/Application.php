<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_id',
        'tenant_id',
        'landlord_id',
        'agent_id',
        'status',
        'expected_move_in',
        'monthly_offer',
        'tenant_message',
        'landlord_notes',
        'viewed_at',
        'decided_at',
    ];

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

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeByTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Inasubiri',
            'approved' => 'Imekubaliwa',
            'rejected' => 'Imekataliwa',
            'cancelled' => 'Imeghairiwa',
            'waitlisted' => 'Kwenye Foleni',
        ];

        return $labels[$this->status] ?? $this->status;
    }
}
