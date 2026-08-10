<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_code',
        'size',
        'price_monthly',
        'status',
        'description',
        'image_url',
        'image_urls',
    ];

    protected $casts = [
        'image_urls' => 'array',
    ];

    /**
     * Return the first available image URL for this room.
     * Falls back to the first photo found in storage/app/public/rooms.
     */
    public function getImageUrlAttribute(?string $value): ?string
    {
        if ($value) {
            return $value;
        }

        // Fallback: grab any .jpg in storage/rooms
        $fallback = collect(Storage::disk('public')->files('rooms'))
            ->filter(fn ($f) => preg_match('/\.(jpg|jpeg|png|webp)$/i', $f))
            ->first();

        return $fallback ? Storage::disk('public')->url($fallback) : null;
    }

    /**
     * Return all image URLs for this room (multi-photo).
     * Falls back to all photos in storage/app/public/rooms when none are set.
     */
    public function getAllImagesAttribute(): array
    {
        if (! empty($this->attributes['image_urls'])) {
            $urls = json_decode($this->attributes['image_urls'], true);
            if (! empty($urls)) {
                return $urls;
            }
        }

        if (! empty($this->attributes['image_url'])) {
            return [$this->attributes['image_url']];
        }

        // Fallback: every photo in storage/rooms
        return collect(Storage::disk('public')->files('rooms'))
            ->filter(fn ($f) => preg_match('/\.(jpg|jpeg|png|webp)$/i', $f))
            ->map(fn ($f) => Storage::disk('public')->url($f))
            ->values()
            ->toArray();
    }

    public function getIsAvailableAttribute(): bool
    {
        if (strtolower($this->status) !== 'available') {
            return false;
        }

        return ! $this->bookings()
            ->whereIn('status', ['pending', 'menunggu_pembayaran', 'dibayar', 'menunggu_konfirmasi_owner', 'siap_huni', 'dihuni'])
            ->exists();
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
