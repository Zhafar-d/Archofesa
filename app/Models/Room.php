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
     * Format/normalize any image URL to work consistently on local and Railway HTTPS.
     */
    public static function formatImageUrl(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        // Jika URL mengandung host localhost/127.0.0.1 (dari seeder/upload local lama)
        if (preg_match('#https?://(?:localhost|127\.0\.0\.1)(?::\d+)?/(.*)#i', $url, $matches)) {
            $path = ltrim($matches[1], '/');
            return asset($path);
        }

        // Jika path berawalan /storage/ atau storage/
        if (str_starts_with($url, '/storage/') || str_starts_with($url, 'storage/')) {
            return asset(ltrim($url, '/'));
        }

        // Jika path relatif direktori rooms/
        if (str_starts_with($url, 'rooms/')) {
            return asset('storage/' . $url);
        }

        // Jika URL eksternal valid (Cloudinary, S3, Unsplash, Google Storage, dll)
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }

        return asset($url);
    }

    /**
     * Return the first available image URL for this room.
     * Falls back to the first photo found in storage/app/public/rooms.
     */
    public function getImageUrlAttribute(?string $value): ?string
    {
        if ($value) {
            return self::formatImageUrl($value);
        }

        // Fallback: hanya jika storage accessible
        try {
            $fallback = collect(Storage::disk('public')->files('rooms'))
                ->filter(fn ($f) => preg_match('/\.(jpg|jpeg|png|webp)$/i', $f))
                ->first();
            return $fallback ? self::formatImageUrl('storage/' . $fallback) : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Return all image URLs for this room (multi-photo).
     * Falls back to all photos in storage/app/public/rooms when none are set.
     */
    public function getAllImagesAttribute(): array
    {
        if (! empty($this->attributes['image_urls'])) {
            $urls = json_decode($this->attributes['image_urls'], true);
            if (! empty($urls) && is_array($urls)) {
                return array_values(array_filter(array_map([self::class, 'formatImageUrl'], $urls)));
            }
        }

        if (! empty($this->attributes['image_url'])) {
            $formatted = self::formatImageUrl($this->attributes['image_url']);
            return $formatted ? [$formatted] : [];
        }

        // Fallback: every photo in storage/rooms (safe)
        try {
            return collect(Storage::disk('public')->files('rooms'))
                ->filter(fn ($f) => preg_match('/\.(jpg|jpeg|png|webp)$/i', $f))
                ->map(fn ($f) => self::formatImageUrl('storage/' . $f))
                ->filter()
                ->values()
                ->toArray();
        } catch (\Throwable $e) {
            return [];
        }
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
