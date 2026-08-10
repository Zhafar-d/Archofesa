<?php

use Illuminate\Http\File as HttpFile;

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Simple CLI helper
$argv = $_SERVER['argv'];
array_shift($argv); // script name

$dir = $argv[0] ?? null;
$roomCode = $argv[1] ?? null;

if (! $dir) {
    echo "Usage: php scripts/import_room_images.php <directory> [room_code]\n";
    exit(1);
}

if (! is_dir($dir)) {
    echo "Directory not found: $dir\n";
    exit(1);
}

/** @var \Illuminate\Filesystem\FilesystemAdapter $storage */
$storage = Illuminate\Support\Facades\Storage::disk('public');

$files = array_values(array_filter(glob(rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '*'), function($f) {
    $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
    return is_file($f) && in_array($ext, ['jpg','jpeg','png','webp']);
}));

if (empty($files)) {
    echo "No image files found in directory.\n";
    exit(1);
}

// limit to 7 photos
$files = array_slice($files, 0, 7);

$imageUrls = [];
foreach ($files as $filePath) {
    $basename = basename($filePath);
    $timestamp = time();
    $targetName = $timestamp . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $basename);
    $targetPath = $storage->putFileAs('rooms', new HttpFile($filePath), $targetName);
    $imageUrls[] = $storage->url($targetPath);
    echo "Uploaded: $basename -> $targetPath\n";
}

// create or update room
if (! $roomCode) {
    $roomCode = 'AUTO-' . date('YmdHis');
}

$room = App\Models\Room::where('room_code', $roomCode)->first();
if (! $room) {
    $room = App\Models\Room::create([
        'room_code' => $roomCode,
        'size' => '3x4m',
        'price_monthly' => 0,
        'status' => 'available',
        'description' => 'Imported room',
        'image_url' => $imageUrls[0] ?? null,
        'image_urls' => $imageUrls,
    ]);
    echo "Created room: {$room->room_code}\n";
} else {
    $room->update([
        'image_url' => $imageUrls[0] ?? $room->image_url,
        'image_urls' => $imageUrls,
    ]);
    echo "Updated room: {$room->room_code}\n";
}

echo "Import finished. Room code: {$room->room_code}\n";
