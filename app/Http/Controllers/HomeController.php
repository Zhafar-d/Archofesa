<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $ctaLabel = 'Booking Sekarang';

        if (! Auth::check()) {
            $ctaRoute = route('login');
        } elseif (Auth::user()->role === 'user') {
            $ctaRoute = route('booking');
        } else {
            $ctaRoute = route('dashboard');
        }

        return view('pages.landing', compact('ctaRoute', 'ctaLabel'));
    }
}
