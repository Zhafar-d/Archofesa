<?php

use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KamarController as AdminKamarController;
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaranController;
use App\Http\Controllers\Admin\PenghuniController as AdminPenghuniController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\FirebaseAuthController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\KonfirmasiController;
use App\Http\Controllers\Owner\LaporanController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\SocialAuthController;
use Illuminate\Support\Facades\Route;

// ── Emergency Debug Route (Access tanpa Laravel bootstrap) ────────────────
// Akses langsung file PHP untuk bypass Laravel error
// Lihat: public/test-railway.php

// ── Diagnostic Routes (Hapus setelah selesai debugging) ─────────────────────
Route::get('/diagnostic/vite-check', function() {
    $manifestPath = public_path('build/manifest.json');
    $buildPath = public_path('build');
    
    return response()->json([
        'build_directory_exists' => file_exists($buildPath),
        'manifest_exists' => file_exists($manifestPath),
        'manifest_path' => $manifestPath,
        'public_path' => public_path(),
        'build_files' => file_exists($buildPath) ? scandir($buildPath) : [],
        'npm_build_needed' => !file_exists($manifestPath),
    ]);
});

Route::get('/diagnostic/basic', function() {
    return response()->json([
        'status' => 'ok',
        'php_version' => phpversion(),
        'laravel_version' => app()->version(),
        'env' => config('app.env'),
    ]);
});

Route::get('/diagnostic/db', function() {
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $roomCount = \App\Models\Room::count();
        return response()->json([
            'db_connection' => 'ok',
            'room_count' => $roomCount,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'db_connection' => 'failed',
            'error' => $e->getMessage(),
        ], 500);
    }
});

Route::get('/diagnostic/room/{id}', function($id) {
    try {
        $room = \App\Models\Room::findOrFail($id);
        return response()->json([
            'room_found' => true,
            'room_code' => $room->room_code,
            'price_raw' => $room->getRawOriginal('price_monthly'),
            'image_url_raw' => $room->getRawOriginal('image_url'),
            'status' => $room->status,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'room_found' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
});

Route::get('/diagnostic/view-test', function() {
    try {
        $room = \App\Models\Room::first();
        if (!$room) {
            return "No rooms in database";
        }
        
        // Test render view dengan data minimal
        $html = view('admin.kamar.edit', compact('room'))->render();
        return response("View rendered OK - Length: " . strlen($html) . " bytes");
    } catch (\Exception $e) {
        return response()->json([
            'view_render' => 'failed',
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => explode("\n", $e->getTraceAsString()),
        ], 500);
    }
});

Route::middleware(['auth'])->get('/diagnostic/auth-test', function() {
    $user = auth()->user();
    return response()->json([
        'authenticated' => true,
        'user_id' => $user->id,
        'user_email' => $user->email,
        'user_role' => $user->role,
    ]);
});

// ── Public pages ────────────────────────────────────────────────────────────
Route::get('/',               [PublicController::class, 'home'])->name('home');
Route::get('/about',          [PublicController::class, 'about'])->name('about');
Route::get('/facilities',     [PublicController::class, 'facilities'])->name('facilities');
Route::get('/rooms',          [PublicController::class, 'rooms'])->name('rooms');
Route::get('/room-detail',    [PublicController::class, 'roomDetail'])->name('room-detail');
Route::get('/gallery',        [PublicController::class, 'gallery'])->name('gallery');
Route::get('/contact',        [PublicController::class, 'contact'])->name('contact');

// ── Auth ────────────────────────────────────────────────────────────────────
Route::get('/login',           [PublicController::class, 'login'])->name('login');
Route::post('/login',          [PublicController::class, 'storeLogin'])->name('login.store');
Route::get('/register',        [PublicController::class, 'register'])->name('register');
Route::post('/register',       [PublicController::class, 'storeRegister'])->name('register.store');
Route::match(['get', 'post'], '/logout', [PublicController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [PublicController::class, 'forgotPassword'])->name('forgot-password');

Route::post('/firebase-login',          [FirebaseAuthController::class, 'login'])->name('firebase.login');
Route::get('/auth/google',              [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback',     [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// ── Midtrans webhook (CSRF exempt handled in middleware) ────────────────────
Route::post('/midtrans/callback', [MidtransController::class, 'callback'])->name('midtrans.callback');

// ── Customer ────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'check.role:user'])->group(function () {
    Route::get('/dashboard',                  [PublicController::class, 'dashboard'])->name('dashboard');
    Route::get('/customer/dashboard',         [CustomerDashboardController::class, 'index'])->name('customer.dashboard');
    Route::get('/customer/rooms',             [CustomerDashboardController::class, 'rooms'])->name('customer.rooms');
    Route::get('/customer/bookings',          [CustomerDashboardController::class, 'bookings'])->name('customer.bookings');
    Route::get('/customer/payments',          [CustomerDashboardController::class, 'payments'])->name('customer.payments');
    Route::get('/customer/messages',          [CustomerDashboardController::class, 'messages'])->name('customer.messages');
    Route::get('/customer/notifications',     [CustomerDashboardController::class, 'notifications'])->name('customer.notifications');
    Route::get('/customer/profile',           fn () => redirect()->route('profile.edit'))->name('customer.profile');

    Route::get('/booking',                    [BookingController::class, 'index'])->name('booking');
    Route::post('/booking',                   [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/status',             [BookingController::class, 'status'])->name('booking.status');
    Route::get('/booking/{booking}/status',   [BookingController::class, 'statusDetail'])->name('booking.status.detail');
    Route::delete('/booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
    Route::get('/riwayat-booking',            [BookingController::class, 'riwayat'])->name('riwayat-booking');
    Route::get('/customer/extend/{booking}',  [BookingController::class, 'extendForm'])->name('customer.extend.form');
    Route::post('/customer/extend/{booking}', [BookingController::class, 'extend'])->name('customer.extend');
    Route::get('/booking/{booking}/bukti',    [BookingController::class, 'bukti'])->name('booking.bukti');

    Route::get('/payment/{payment}/pay',      [PaymentController::class, 'pay'])->name('payment.pay');
    Route::post('/payment/{payment}/confirm', [PaymentController::class, 'confirm'])->name('payment.confirm');
    Route::get('/payment/{payment}/success',  [PaymentController::class, 'success'])->name('payment.success');
});

// ── Admin ────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',                    [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/chat',                         [ChatController::class, 'adminChat'])->name('chat');

    Route::resource('bookings', AdminBookingController::class)->only(['index', 'show', 'destroy']);
    Route::post('/bookings/{booking}/process-payment',        [AdminBookingController::class, 'processPayment'])->name('bookings.process-payment');
    Route::post('/bookings/{booking}/confirm-to-owner',       [AdminBookingController::class, 'confirmToOwner'])->name('bookings.confirm-to-owner');
    Route::post('/bookings/{booking}/confirm-ready-to-occupy',[AdminBookingController::class, 'confirmReadyToOccupy'])->name('bookings.confirm-ready-to-occupy');

    Route::resource('kamar', AdminKamarController::class)->parameters(['kamar' => 'room']);
    Route::resource('pembayaran', AdminPembayaranController::class)->only(['index', 'destroy']);
    Route::post('/pembayaran/{payment}/mark-paid',            [AdminPembayaranController::class, 'markPaid'])->name('pembayaran.mark-paid');
    Route::resource('penghuni', AdminPenghuniController::class)->only(['index', 'destroy']);
    Route::put('/penghuni/{booking}/update-due-date',         [AdminPenghuniController::class, 'updateDueDate'])->name('penghuni.update-due-date');
    Route::post('/penghuni/{booking}/checkout',               [AdminPenghuniController::class, 'checkout'])->name('penghuni.checkout');
    Route::post('/penghuni/{booking}/send-reminder',          [AdminPenghuniController::class, 'sendReminder'])->name('penghuni.send-reminder');
});

// ── Owner ────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard',            [OwnerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/chat',                 [ChatController::class, 'ownerChat'])->name('chat');
    Route::get('/konfirmasi',           [KonfirmasiController::class, 'index'])->name('konfirmasi.index');
    Route::post('/konfirmasi/{booking}',[KonfirmasiController::class, 'confirm'])->name('konfirmasi.confirm');
    Route::post('/konfirmasi/{booking}/reject', [KonfirmasiController::class, 'reject'])->name('konfirmasi.reject');
    Route::get('/laporan',              [LaporanController::class, 'index'])->name('laporan.index');
});

// ── Profile ──────────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
