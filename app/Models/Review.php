<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'reviewer_id',
        'property_id',
        'landlord_id',
        'rating',
        'title',
        'review',
        'is_verified',
        'flagged',
        'flagged_reason',
        'helpful_count',
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    public function scopePositive($query)
    {
        return $query->where('rating', '>=', 4);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeFlagged($query)
    {
        return $query->where('flagged', true);
    }

    public function getRatingStarsAttribute()
    {
        $full = floor($this->rating);
        $half = $this->rating - $full >= 0.5;
        $stars = str_repeat('★', $full);

        if ($half) {
            $stars .= '½';
        }

        $empty = 5 - $full - ($half ? 1 : 0);
        $stars .= str_repeat('☆', $empty > 0 ? $empty : 0);

        return $stars;
    }
}
