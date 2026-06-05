<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintenanceRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_id',
        'tenant_id',
        'landlord_id',
        'assigned_to',
        'title',
        'description',
        'category',
        'priority',
        'status',
        'scheduled_at',
        'completed_at',
        'estimated_cost',
        'actual_cost',
        'landlord_approved',
        'images',
        'resolution_notes',
        'tenant_rating',
    ];

    protected $casts = [
        'images' => 'array',
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

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeUrgent($query)
    {
        return $query->where('priority', 'urgent')->whereNotIn('status', ['completed', 'cancelled']);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['pending', 'assigned', 'in_progress']);
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Inasubiri',
            'assigned' => 'Imetengwa',
            'in_progress' => 'Inafanywa',
            'completed' => 'Imekamilika',
            'cancelled' => 'Imeghairiwa',
            'on_hold' => 'Imesimamishwa',
        ];

        return $labels[$this->status] ?? $this->status;
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

    public function getCategoryLabelAttribute()
    {
        $labels = [
            'plumbing' => 'Mabomba',
            'electrical' => 'Umeme',
            'structural' => 'Muundo',
            'appliance' => 'Vifaa',
            'pest_control' => 'Udhibiti wa Wadudu',
            'cleaning' => 'Usafishaji',
            'painting' => 'Upakaji Rangi',
            'other' => 'Nyingine',
        ];

        return $labels[$this->category] ?? $this->category;
    }

    public function getIsOverdueAttribute()
    {
        if (in_array($this->status, ['completed', 'cancelled'])) {
            return false;
        }

        if ($this->scheduled_at && $this->scheduled_at->isPast()) {
            return true;
        }

        return $this->priority === 'urgent' && $this->created_at->diffInHours(now()) > 24;
    }
}
