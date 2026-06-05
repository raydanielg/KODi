<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'agent_id',
        'title',
        'slug',
        'description',
        'property_type',
        'status',
        'price',
        'currency',
        'deposit',
        'bedrooms',
        'bathrooms',
        'area_sqft',
        'location_area',
        'location_city',
        'location_region',
        'location_address',
        'latitude',
        'longitude',
        'is_furnished',
        'has_internet',
        'has_water',
        'has_electricity',
        'has_parking',
        'has_security',
        'has_generator',
        'is_featured',
        'views_count',
        'approved_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($property) {
            if (empty($property->slug)) {
                $property->slug = Str::slug($property->title) . '-' . Str::random(6);
            }
        });
    }

    public function landlord()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function amenities()
    {
        return $this->hasMany(PropertyAmenity::class);
    }

    public function leases()
    {
        return $this->hasMany(Lease::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    public function activeLease()
    {
        return $this->hasOne(Lease::class)->where('status', 'active');
    }

    public function favoriteByUsers()
    {
        return $this->hasMany(Favorite::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('property_type', $type);
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('location_city', $city);
    }

    public function scopeByRegion($query, $region)
    {
        return $query->where('location_region', $region);
    }

    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    public function scopeBedrooms($query, $count)
    {
        return $query->where('bedrooms', $count);
    }

    public function scopeFurnished($query)
    {
        return $query->where('is_furnished', true);
    }

    public function getThumbnailAttribute()
    {
        $image = $this->images()->where('is_primary', true)->first();

        if ($image) {
            return asset('storage/' . $image->image_path);
        }

        $firstImage = $this->images()->first();

        if ($firstImage) {
            return asset('storage/' . $firstImage->image_path);
        }

        return asset('images/default-property.jpg');
    }

    public function getMainImageAttribute()
    {
        return $this->thumbnail;
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'available' => 'Inapatikana',
            'rented' => 'Imekodiwa',
            'under_maintenance' => 'Inatengenezwa',
            'unavailable' => 'Haipatikani',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getTypeLabelAttribute()
    {
        $labels = [
            'house' => 'Nyumba',
            'apartment' => 'Ghorofa',
            'room' => 'Chumba',
            'commercial' => 'Biashara',
            'land' => 'Ardhi',
        ];

        return $labels[$this->property_type] ?? $this->property_type;
    }

    public function getFormattedPriceAttribute()
    {
        $formatted = number_format($this->price, 2);

        return $formatted . ' ' . $this->currency;
    }

    public function getFullLocationAttribute()
    {
        $parts = array_filter([
            $this->location_area,
            $this->location_city,
            $this->location_region,
        ]);

        return implode(', ', $parts);
    }
}
