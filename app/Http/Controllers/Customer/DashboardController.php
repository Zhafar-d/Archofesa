<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $booking = Booking::where('user_id', Auth::id())
            ->with('room')
            ->whereIn('status', ['dihuni', 'siap_huni', 'menunggu_konfirmasi_owner', 'menunggu_pembayaran', 'dibayar'])
            ->latest()
            ->first();

        $payments      = $user->payments()->with('booking')->latest()->take(3)->get();
        $reviews       = $user->reviews()->latest()->take(3)->get();
        $announcements = [];

        $totalRooms     = Room::count();
        $availableRooms = Room::where('status', 'available')->count();
        $roomPrice      = 1400000;

        $firstRoomImage = self::firstStorageImage();
        $propertyName   = config('archofesa.property_name', 'ARCHOFESA KOST');
        $location       = config('archofesa.location', 'Pedurungan, Semarang');
        $roomSize       = config('archofesa.default_room_size', '3 x 4 meters');

        $remainingDays = 0;
        if ($booking && $booking->move_out_date) {
            $remainingDays = max(0, (int) now()->diffInDays($booking->move_out_date, false));
        }

        $avgRating   = Review::avg('rating');
        $reviewCount = Review::count();

        $galleryImages = Room::all()->flatMap(fn ($r) => $r->all_images)->unique()->take(6)->values()->toArray();
        if (empty($galleryImages)) {
            $galleryImages = collect(Storage::disk('public')->files('rooms'))
                ->filter(fn ($f) => preg_match('/\.(jpg|jpeg|png|webp)$/i', $f))
                ->map(fn ($f) => Storage::disk('public')->url($f))
                ->values()->take(6)->toArray();
        }

        $heroImage = $galleryImages[0] ?? null;

        return view('dashboard.customer.index', compact(
            'user', 'booking', 'payments', 'reviews', 'announcements',
            'totalRooms', 'availableRooms', 'roomPrice', 'propertyName', 'location', 'roomSize',
            'remainingDays', 'avgRating', 'reviewCount', 'galleryImages', 'heroImage', 'firstRoomImage'
        ));
    }

    public function rooms()
    {
        $rooms = Room::orderBy('room_code')->get();
        foreach ($rooms as $room) {
            $room->price_monthly = 1400000;
        }
        return view('dashboard.customer.rooms', compact('rooms'));
    }

    public function bookings()
    {
        $user     = Auth::user();
        $bookings = $user->bookings()->with('room')->latest()->get();
        return view('dashboard.customer.bookings', compact('bookings'));
    }

    public function payments()
    {
        $user    = Auth::user();
        $payments = $user->payments()->with('booking')->latest()->get();

        $currentPayment = $user->payments()->with('booking')
            ->whereIn('status', ['pending', 'menunggu_pembayaran'])->latest()->first();

        $totalPaid = $user->payments()->where('status', 'dibayar')->sum('amount');

        $hasOverdue = $user->payments()
            ->where('status', 'pending')
            ->where('created_at', '<', now()->subDays(30))
            ->exists();

        $upcomingPayments = $user->payments()->with('booking')
            ->whereIn('status', ['pending', 'menunggu_pembayaran'])->latest()->take(3)->get();

        return view('dashboard.customer.payments', compact(
            'payments', 'currentPayment', 'totalPaid', 'hasOverdue', 'upcomingPayments'
        ));
    }

    public function messages()
    {
        $user = Auth::user();
        return view('dashboard.customer.messages', compact('user'));
    }

    public function notifications()
    {
        $user          = Auth::user();
        $notifications = $user->notifications()->latest()->take(20)->get();
        return view('dashboard.customer.notifications', compact('notifications'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('dashboard.customer.profile', compact('user'));
    }

    private static function firstStorageImage(): ?string
    {
        $image = Room::whereNotNull('image_url')->value('image_url');
        if ($image) return $image;

        $file = collect(Storage::disk('public')->files('rooms'))
            ->filter(fn ($f) => preg_match('/\.(jpg|jpeg|png|webp)$/i', $f))
            ->first();
        return $file ? Storage::disk('public')->url($file) : null;
    }
}
