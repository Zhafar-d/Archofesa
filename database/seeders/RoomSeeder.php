<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua foto yang ada di storage/rooms
        $storagePhotos = collect(Storage::disk('public')->files('rooms'))
            ->filter(fn ($f) => preg_match('/\.(jpg|jpeg|png|webp)$/i', $f))
            ->map(fn ($f) => '/storage/' . $f)
            ->values();

        for ($i = 1; $i <= 18; $i++) {
            $roomCode = sprintf('K%03d', $i);

            // Ambil foto dari storage secara bergilir, fallback null
            $imageUrl = $storagePhotos->isNotEmpty()
                ? $storagePhotos[($i - 1) % $storagePhotos->count()]
                : null;

            $existing = Room::where('room_code', $roomCode)->first();

            if ($existing) {
                $existing->update([
                    'size'          => '3x4m',
                    'price_monthly' => 1400000,
                    'status'        => $i <= 14 ? 'available' : 'occupied',
                    'description'   => 'Kamar kos mahasiswa, semua spesifikasi sama, nyaman untuk belajar dan istirahat.',
                    'image_url'     => $imageUrl ?: $existing->image_url,
                ]);
            } else {
                Room::create([
                    'room_code'     => $roomCode,
                    'size'          => '3x4m',
                    'price_monthly' => 1400000,
                    'status'        => $i <= 14 ? 'available' : 'occupied',
                    'description'   => 'Kamar kos mahasiswa, semua spesifikasi sama, nyaman untuk belajar dan istirahat.',
                    'image_url'     => $imageUrl,
                ]);
            }
        }
    }
}
