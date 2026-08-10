<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\JWK;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'idToken' => ['required', 'string'],
        ]);

        $idToken = $request->input('idToken');
        $projectId = config('firebase.project_id');

        if (! $projectId) {
            return response()->json(['message' => 'Firebase project ID not configured.'], 500);
        }

        if (config('app.debug')) {
            Log::info('Firebase login token received', ['token_length' => strlen($idToken)]);
        }

        try {
            $decoded = $this->verifyToken($idToken, $projectId);
        } catch (\Throwable $e) {
            Log::error('Firebase token verification failed', [
                'message' => $e->getMessage(),
                'project_id' => $projectId,
            ]);

            $errorResponse = ['message' => 'Invalid Firebase token.'];
            if (config('app.debug')) {
                $errorResponse['error'] = $e->getMessage();
            }

            return response()->json($errorResponse, 401);
        }

        $email = $decoded->email ?? null;
        if (! $email) {
            return response()->json(['message' => 'Firebase token does not contain an email.'], 401);
        }

        $user = User::firstOrCreate([
            'email' => $email,
        ], [
            'name' => $decoded->name ?? $email,
            'google_id' => $decoded->sub,
            'avatar' => $decoded->picture ?? null,
            'role' => 'user',
            'password' => null,
        ]);

        Auth::login($user, true);
        $request->session()->regenerate();

        return response()->json(['redirect' => route('dashboard')]);
    }

    protected function verifyToken(string $idToken, string $projectId)
    {
        $jwks = Cache::remember('firebase_jwks', 60 * 24, fn () => Http::get('https://www.googleapis.com/service_accounts/v1/jwk/securetoken@system.gserviceaccount.com')->json());
        if (! is_array($jwks) || ! isset($jwks['keys'])) {
            return null;
        }

        try {
            $publicKeys = JWK::parseKeySet($jwks, 'RS256');
            $decoded = JWT::decode($idToken, $publicKeys);
        } catch (\Throwable $e) {
            throw $e;
        }

        if (! isset($decoded->aud, $decoded->iss, $decoded->exp, $decoded->sub)) {
            return null;
        }

        if ($decoded->aud !== $projectId) {
            return null;
        }

        if ($decoded->iss !== 'https://securetoken.google.com/' . $projectId) {
            return null;
        }

        if ($decoded->exp < time()) {
            return null;
        }

        return $decoded;
    }
}
