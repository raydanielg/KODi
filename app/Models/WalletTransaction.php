<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'user_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'reference_type',
        'reference_id',
        'description',
        'status',
        'payment_method',
        'transaction_reference',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function getTypeLabelAttribute()
    {
        $labels = [
            'deposit' => 'Amana',
            'withdrawal' => 'Utoaji',
            'payment' => 'Malipo',
            'refund' => 'Marejesho',
            'commission' => 'Tume',
            'adjustment' => 'Marekebisho',
        ];

        return $labels[$this->type] ?? $this->type;
    }
}
