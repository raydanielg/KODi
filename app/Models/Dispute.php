<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    use HasFactory;

    protected $fillable = [
        'raised_by',
        'against_user_id',
        'property_id',
        'lease_id',
        'dispute_type',
        'title',
        'description',
        'priority',
        'status',
        'assigned_to',
        'resolution',
    ];

    public function raisedBy()
    {
        return $this->belongsTo(User::class, 'raised_by');
    }

    public function againstUser()
    {
        return $this->belongsTo(User::class, 'against_user_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function lease()
    {
        return $this->belongsTo(Lease::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'investigating']);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('dispute_type', $type);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'open' => 'Funguliwa',
            'investigating' => 'Inachunguzwa',
            'resolved' => 'Imetatuliwa',
            'dismissed' => 'Imetupiliwa Mbali',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getTypeLabelAttribute()
    {
        $labels = [
            'damage' => 'Uharibifu',
            'payment' => 'Malipo',
            'noise' => 'Kelele',
            'maintenance' => 'Matengenezo',
            'deposit' => 'Dhamana',
            'contract' => 'Mkataba',
            'other' => 'Nyingine',
        ];

        return $labels[$this->dispute_type] ?? $this->dispute_type;
    }

    public function getPriorityLabelAttribute()
    {
        $labels = [
            'low' => 'Chini',
            'medium' => 'Kati',
            'high' => 'Juu',
            'urgent' => 'Dharura',
        ];

        return $labels[$this->priority] ?? $this->priority;
    }
}
