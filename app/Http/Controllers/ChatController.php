<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function adminChat(Request $request)
    {
        $user = Auth::user();

        abort_unless($user && $user->role === 'admin', 403);

        return view('admin.chat', [
            'firebaseConfig' => config('firebase'),
            'userId' => $user->id,
            'userName' => $user->name,
            'userRole' => 'admin',
            'conversationId' => 'admin-owner-chat',
        ]);
    }

    public function ownerChat(Request $request)
    {
        $user = Auth::user();

        abort_unless($user && $user->role === 'owner', 403);

        return view('owner.chat', [
            'firebaseConfig' => config('firebase'),
            'userId' => $user->id,
            'userName' => $user->name,
            'userRole' => 'owner',
            'conversationId' => 'admin-owner-chat',
        ]);
    }
}
