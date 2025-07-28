<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'filename',
        'path',
        'mime_type',
        'size',
        'alt_text',
        'description',
        'caption',
        'metadata',
        'disk',
        'is_public',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_public' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }

    public function getThumbnailUrlAttribute()
    {
        // For now, return the same URL. In a real implementation, you'd generate thumbnails
        return $this->url;
    }

    public function getFormattedSizeAttribute()
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getFileTypeAttribute()
    {
        $mime = $this->mime_type;
        if (str_starts_with($mime, 'image/')) return 'image';
        if (str_starts_with($mime, 'video/')) return 'video';
        if (str_starts_with($mime, 'audio/')) return 'audio';
        if (str_starts_with($mime, 'application/pdf')) return 'pdf';
        return 'document';
    }

    public function scopeImages($query)
    {
        return $query->where('mime_type', 'like', 'image/%');
    }

    public function scopeVideos($query)
    {
        return $query->where('mime_type', 'like', 'video/%');
    }

    public function scopeDocuments($query)
    {
        return $query->where('mime_type', 'not like', 'image/%')
                    ->where('mime_type', 'not like', 'video/%')
                    ->where('mime_type', 'not like', 'audio/%');
    }
}
