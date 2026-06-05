<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'fee',
        'total',
        'payment_method',
        'account_details',
        'status',
        'reference',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Inasubiri',
            'processing' => 'Inachakatwa',
            'completed' => 'Imekamilika',
            'failed' => 'Imeshindwa',
            'cancelled' => 'Imeghairiwa',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getPaymentMethodLabelAttribute()
    {
        $labels = [
            'mobile_money' => 'Pesa kwa Simu',
            'bank_transfer' => 'Uhamisho wa Benki',
            'cash' => 'Fedha Taslimu',
            'card' => 'Kadi',
        ];

        return $labels[$this->payment_method] ?? $this->payment_method;
    }
}
