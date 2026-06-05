<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'total_earned',
        'total_withdrawn',
        'currency',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function addBalance($amount)
    {
        $this->increment('balance', $amount);
        $this->increment('total_earned', $amount);

        return $this;
    }

    public function subtractBalance($amount)
    {
        $this->decrement('balance', $amount);
        $this->increment('total_withdrawn', $amount);

        return $this;
    }

    public function canWithdraw($amount)
    {
        return $this->is_active && $this->balance >= $amount;
    }
}
