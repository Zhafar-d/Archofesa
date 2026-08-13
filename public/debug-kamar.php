<?php
// Proteksi
if (($_GET['key'] ?? '') !== 'debug2026') { http_response_code(403); die('Forbidden'); }

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

header('Content-Type: text/plain');

try {
    $room = \App\Models\Room::find(18) ?? \App\Models\Room::first();
    if (!$room) { die("No rooms found in DB"); }

    echo "Room found: {$room->room_code}\n";
    echo "Raw image_url: " . $room->getRawOriginal('image_url') . "\n";
    echo "Raw price_monthly: " . $room->getRawOriginal('price_monthly') . "\n";
    echo "Status: {$room->status}\n\n";

    // Test accessor
    echo "Testing image_url accessor...\n";
    $img = $room->image_url;
    echo "image_url via accessor: " . ($img ?? 'null') . "\n\n";

    // Test view render
    echo "Testing view render...\n";
    $view = view('admin.kamar.edit', compact('room'))->render();
    echo "View rendered OK (" . strlen($view) . " chars)\n";

} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
    echo $e->getTraceAsString();
}
