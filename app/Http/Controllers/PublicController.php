<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PublicController extends Controller
{
    public function home()
    {
        $totalRooms     = Room::count();
        $availableRooms = Room::where('status', 'available')->count();
        $occupancyRate  = $totalRooms > 0
            ? round((($totalRooms - $availableRooms) / $totalRooms) * 100) : 0;

        $avgStayDays = Booking::whereNotNull('move_in_date')->whereNotNull('move_out_date')
            ->get(['move_in_date', 'move_out_date'])
            ->avg(fn ($b) => Carbon::parse($b->move_in_date)->diffInDays(Carbon::parse($b->move_out_date)));
        $avgStayMonths = $avgStayDays ? (int) round($avgStayDays / 30) : 0;

        $stats = [
            ['label' => 'Kamar Tersedia',    'value' => (string) $availableRooms, 'detail' => "Dari total {$totalRooms} kamar"],
            ['label' => 'Tingkat Hunian',    'value' => $occupancyRate . '%',     'detail' => 'Tingkat hunian saat ini'],
            ['label' => 'Rata-rata Tinggal', 'value' => $avgStayMonths > 0 ? $avgStayMonths . ' bulan' : '-', 'detail' => 'Rata-rata durasi tinggal'],
        ];

        $rawMinPrice   = Room::min('price_monthly');
        $minPrice      = ($rawMinPrice && $rawMinPrice < 10000) ? $rawMinPrice * 1000 : ($rawMinPrice ?: 1400000);
        $roomTypeCount = Room::select('price_monthly')->distinct()->count();

        $facilities = [
            ['title' => 'Kamar Mandi Pribadi', 'description' => 'Sanitasi pribadi yang bersih dan nyaman untuk setiap kamar.'],
            ['title' => 'Dapur Bersama',        'description' => 'Dapur yang tertata rapi untuk kebutuhan memasak sehari-hari.'],
            ['title' => 'Rooftop Lounge',       'description' => 'Area bersantai dengan pemandangan kota yang tenang.'],
            ['title' => 'Keamanan 24/7',        'description' => 'Akses aman dan dukungan penuh sepanjang hari.'],
        ];

        $testimonials = [
            ['name' => 'Anisa P.', 'role' => 'Mahasiswi',          'quote' => 'Desain kamarnya terasa premium dan suasananya tenang. Saya bisa belajar lebih baik dan merasa betah.'],
            ['name' => 'Rudi H.', 'role' => 'Software Engineer',    'quote' => 'Semuanya tertata dan terorganisir dengan baik. Rasanya seperti tinggal di hunian butik yang modern.'],
        ];

        $faq = [
            ['question' => 'Siapa yang bisa tinggal di sini?',        'answer' => 'Properti ini diperuntukkan bagi mahasiswa dan karyawan yang mencari hunian yang aman, tertib, dan nyaman.'],
            ['question' => 'Apakah tamu diizinkan menginap?',         'answer' => 'Tamu tidak diperkenankan menginap, sesuai dengan peraturan kos yang berlaku.'],
            ['question' => 'Apakah ada minimal masa tinggal?',        'answer' => 'Properti ini dirancang untuk sewa bulanan dan hunian jangka panjang.'],
        ];

        $ctaLabel = 'Booking Sekarang';
        $ctaRoute = Auth::check()
            ? (Auth::user()->role === 'user' ? route('booking') : route('dashboard'))
            : route('login');

        $heroImage = Room::whereNotNull('image_url')->first()?->image_url;
        if (! $heroImage) {
            $file = collect(Storage::disk('public')->files('rooms'))
                ->filter(fn ($f) => preg_match('/\.(jpg|jpeg|png|webp)$/i', $f))->first();
            $heroImage = $file ? Room::formatImageUrl('storage/' . $file) : null;
        }

        return view('pages.landing', compact(
            'stats', 'facilities', 'testimonials', 'faq',
            'ctaLabel', 'ctaRoute', 'totalRooms', 'minPrice', 'roomTypeCount', 'heroImage'
        ));
    }

    public function about()     { return view('pages.about'); }
    public function facilities(){ return view('pages.facilities'); }
    public function contact()   { return view('pages.contact'); }

    public function rooms()
    {
        $rooms = Room::orderBy('price_monthly', 'desc')->get();
        foreach ($rooms as $room) { $room->price_monthly = 1400000; }
        return view('pages.rooms', compact('rooms'));
    }

    public function roomDetail(Request $request)
    {
        $room = Room::where('room_code', $request->query('code'))->firstOrFail();
        $room->price_monthly = 1400000;

        $images      = $room->all_images;
        $mainImage   = $images[0] ?? null;
        $thumbImages = array_slice($images, 1);
        $isAvailable = $room->status === 'available';
        $roomType    = $room->price_monthly >= 1400000 ? 'Family Room' : 'Student Room';

        return view('pages.room-detail', compact('room', 'images', 'mainImage', 'thumbImages', 'isAvailable', 'roomType'));
    }

    public function gallery()
    {
        // Prioritas 1: image_urls (JSON array multi-foto) dari DB
        $fromMulti = Room::all()->flatMap(function ($r) {
            $raw = $r->getRawOriginal('image_urls');
            if (empty($raw)) return [];
            $decoded = json_decode($raw, true);
            return is_array($decoded) ? array_map([Room::class, 'formatImageUrl'], $decoded) : [];
        })->filter()->values()->toArray();

        // Prioritas 2: image_url (single) dari DB
        $fromSingle = Room::all()->map(function ($r) {
            return $r->image_url;
        })->filter()->unique()->values()->toArray();

        // Prioritas 3: semua file langsung dari storage/rooms
        $fromStorage = collect(Storage::disk('public')->files('rooms'))
            ->filter(fn ($f) => preg_match('/\.(jpg|jpeg|png|webp)$/i', $f))
            ->map(fn ($f) => Room::formatImageUrl('storage/' . $f))
            ->values()->toArray();

        // Gabung semua, buang duplikat
        $images = collect(array_merge($fromMulti, $fromSingle, $fromStorage))
            ->filter()
            ->unique()
            ->values()
            ->take(24)
            ->toArray();

        return view('pages.gallery', compact('images'));
    }

    public function login()
    {
        if (Auth::check()) return redirect()->route('dashboard');
        return view('pages.login');
    }

    public function storeLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(match (Auth::user()->role ?? 'user') {
                'admin' => route('admin.dashboard'),
                'owner' => route('owner.dashboard'),
                default => route('dashboard'),
            });
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function register()
    {
        if (Auth::check()) return redirect()->route('dashboard');
        return view('pages.register');
    }

    public function storeRegister(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users')],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = \App\Models\User::create([...$validated, 'role' => 'user']);
        Auth::login($user);
        return redirect()->route('dashboard');
    }

    public function forgotPassword()
    {
        if (Auth::check()) return redirect()->route('dashboard');
        return view('pages.forgot-password');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('status', 'Anda telah berhasil keluar.');
    }

    public function dashboard()
    {
        return redirect()->route('customer.dashboard');
    }
}
