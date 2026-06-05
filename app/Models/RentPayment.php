<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RentPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lease_id',
        'tenant_id',
        'landlord_id',
        'property_id',
        'payment_number',
        'amount',
        'late_fee_amount',
        'total_amount',
        'payment_method',
        'payment_reference',
        'transaction_id',
        'period_start',
        'period_end',
        'paid_at',
        'status',
        'confirmed_by',
        'receipt_path',
        'notes',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->payment_number)) {
                $payment->payment_number = 'PAY-' . strtoupper(uniqid());
            }
        });
    }

    public function lease()
    {
        return $this->belongsTo(Lease::class);
    }

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
            ->where('period_end', '<', now());
    }

    public function scopeByPeriod($query, $start, $end)
    {
        return $query->whereBetween('period_start', [$start, $end]);
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Inasubiri',
            'completed' => 'Imekamilika',
            'failed' => 'Imeshindwa',
            'refunded' => 'Imerejeshwa',
            'partially_refunded' => 'Imerejeshwa Kiasi',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getPaymentMethodLabelAttribute()
    {
        $labels = [
            'cash' => 'Fedha Taslimu',
            'mobile_money' => 'Pesa kwa Simu',
            'bank_transfer' => 'Uhamisho wa Benki',
            'card' => 'Kadi',
            'other' => 'Nyingine',
        ];

        return $labels[$this->payment_method] ?? $this->payment_method;
    }
}
