<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uploader_id',
        'property_id',
        'lease_id',
        'document_type',
        'title',
        'description',
        'file_path',
        'file_type',
        'file_size',
        'is_verified',
        'verified_at',
        'verified_by',
        'is_public',
        'expires_at',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function lease()
    {
        return $this->belongsTo(Lease::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('document_type', $type);
    }

    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        }

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' B';
    }

    public function getDocumentTypeLabelAttribute()
    {
        $labels = [
            'contract' => 'Mkataba',
            'identification' => 'Kitambulisho',
            'receipt' => 'Risiti',
            'invoice' => 'Ankara',
            'agreement' => 'Makubaliano',
            'report' => 'Ripoti',
            'other' => 'Nyingine',
        ];

        return $labels[$this->document_type] ?? $this->document_type;
    }
}
