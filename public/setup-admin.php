<?php
/**
 * Script sementara untuk membuat admin di production.
 * HAPUS file ini setelah selesai digunakan!
 */

// Proteksi sederhana dengan secret key
$secret = $_GET['key'] ?? '';
if ($secret !== 'archofesa-setup-2026') {
    http_response_code(403);
    die('Forbidden');
}

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$results = [];

$users = [
    ['name' => 'Admin Archofesa', 'email' => 'admin@archofesa.test', 'password' => 'admin12345', 'role' => 'admin'],
    ['name' => 'Owner Archofesa', 'email' => 'owner@archofesa.test', 'password' => 'owner12345', 'role' => 'owner'],
];

foreach ($users as $data) {
    User::where('email', $data['email'])->delete();
    $user = User::create($data);
    $results[] = "✓ {$user->role}: {$user->email}";
}

// Juga jalankan storage:link
try {
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    $results[] = "✓ storage:link done";
} catch (\Throwable $e) {
    $results[] = "- storage:link: " . $e->getMessage();
}

header('Content-Type: text/plain');
echo implode("\n", $results) . "\n\nSelesai. HAPUS file ini sekarang!";
