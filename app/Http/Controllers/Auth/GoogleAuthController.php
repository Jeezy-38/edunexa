<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\DeviceIp;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    /**
     * Get Google OAuth credentials from config or settings.
     */
    protected function getGoogleConfig(): array
    {
        $clientId = config('services.google.client_id') ?: (function_exists('get_settings') ? get_settings('google_client_id') : env('GOOGLE_CLIENT_ID'));
        $clientSecret = config('services.google.client_secret') ?: (function_exists('get_settings') ? get_settings('google_client_secret') : env('GOOGLE_CLIENT_SECRET'));

        $host = request()->getHttpHost();
        $scheme = request()->getScheme();
        $currentCallback = "{$scheme}://{$host}/auth/google/callback";

        $configuredRedirect = config('services.google.redirect') ?: (config('services.google.redirect_uri') ?: env('GOOGLE_REDIRECT_URI'));

        if (!empty($configuredRedirect)) {
            if (str_contains($host, 'localhost') && str_contains($configuredRedirect, '127.0.0.1')) {
                $redirectUri = str_replace('127.0.0.1', 'localhost', $configuredRedirect);
            } elseif (str_contains($host, '127.0.0.1') && str_contains($configuredRedirect, 'localhost')) {
                $redirectUri = str_replace('localhost', '127.0.0.1', $configuredRedirect);
            } else {
                $redirectUri = $configuredRedirect;
            }
        } else {
            $redirectUri = $currentCallback;
        }

        return [
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri'  => $redirectUri,
        ];
    }

    /**
     * Redirect the user to the Google OAuth authentication page.
     */
    public function redirectToGoogle(Request $request)
    {
        $config = $this->getGoogleConfig();

        if (empty($config['client_id']) || empty($config['client_secret'])) {
            Session::flash('error', get_phrase('Google Login is not configured yet. Please configure your Google Client ID and Secret in settings or .env file.'));
            return redirect(route('login'));
        }

        $state = Str::random(40);
        $request->session()->put('google_oauth_state', $state);

        $query = http_build_query([
            'client_id'       => $config['client_id'],
            'redirect_uri'    => $config['redirect_uri'],
            'response_type'   => 'code',
            'scope'           => 'openid email profile',
            'state'           => $state,
            'access_type'     => 'offline',
            'prompt'          => 'select_account',
        ]);

        return redirect('https://accounts.google.com/o/oauth2/v2/auth?' . $query);
    }

    /**
     * Obtain the user information from Google and authenticate.
     */
    public function handleGoogleCallback(Request $request)
    {
        if ($request->has('error')) {
            Log::warning('Google OAuth cancelled or returned error: ' . $request->get('error'));
            Session::flash('error', get_phrase('Google login was cancelled or denied.'));
            return redirect(route('login'));
        }

        $state = $request->input('state');
        $savedState = $request->session()->pull('google_oauth_state');

        if (!$state || !$savedState || !hash_equals($savedState, $state)) {
            Log::warning('Google OAuth state mismatch');
            Session::flash('error', get_phrase('Invalid authentication state. Please try again.'));
            return redirect(route('login'));
        }

        $code = $request->input('code');
        if (!$code) {
            Session::flash('error', get_phrase('Authorization code not received from Google.'));
            return redirect(route('login'));
        }

        $config = $this->getGoogleConfig();

        try {
            // Exchange authorization code for access token
            $tokenResponse = Http::asForm()->timeout(15)->post('https://oauth2.googleapis.com/token', [
                'code'          => $code,
                'client_id'     => $config['client_id'],
                'client_secret' => $config['client_secret'],
                'redirect_uri'  => $config['redirect_uri'],
                'grant_type'    => 'authorization_code',
            ]);

            if ($tokenResponse->failed()) {
                Log::error('Google OAuth token request failed: ' . $tokenResponse->body());
                Session::flash('error', get_phrase('Unable to connect with Google. Please check your credentials.'));
                return redirect(route('login'));
            }

            $tokenData = $tokenResponse->json();
            $accessToken = $tokenData['access_token'] ?? null;

            if (!$accessToken) {
                Log::error('Google OAuth token missing in response: ' . json_encode($tokenData));
                Session::flash('error', get_phrase('Failed to retrieve authentication token from Google.'));
                return redirect(route('login'));
            }

            // Fetch user profile from Google UserInfo endpoint
            $userResponse = Http::withToken($accessToken)
                ->timeout(15)
                ->get('https://www.googleapis.com/oauth2/v3/userinfo');

            if ($userResponse->failed()) {
                Log::error('Google OAuth userinfo request failed: ' . $userResponse->body());
                Session::flash('error', get_phrase('Failed to retrieve profile information from Google.'));
                return redirect(route('login'));
            }

            $googleUser = $userResponse->json();
            $googleId = $googleUser['sub'] ?? null;
            $email = $googleUser['email'] ?? null;
            $name = $googleUser['name'] ?? null;
            $picture = $googleUser['picture'] ?? null;

            if (!$googleId || !$email) {
                Log::error('Google OAuth user profile missing ID or email: ' . json_encode($googleUser));
                Session::flash('error', get_phrase('Invalid user profile received from Google.'));
                return redirect(route('login'));
            }

            // 1. Check if user already exists with this google_id
            $user = User::where('google_id', $googleId)->first();

            if (!$user) {
                // 2. Check if user exists with the same email
                $user = User::where('email', $email)->first();

                if ($user) {
                    // Link existing account with Google
                    $user->google_id = $googleId;
                    if (empty($user->email_verified_at)) {
                        $user->email_verified_at = now();
                    }
                    if (empty($user->photo) && $picture) {
                        $user->photo = $picture;
                    }
                    $user->save();
                } else {
                    // 3. Register a new student account
                    $user = User::create([
                        'name'              => $name ?: explode('@', $email)[0],
                        'email'             => $email,
                        'google_id'         => $googleId,
                        'role'              => 'student',
                        'status'            => 1,
                        'password'          => Hash::make(Str::random(32)),
                        'email_verified_at' => now(),
                        'photo'             => $picture,
                    ]);
                }
            } else {
                // Ensure email_verified_at is set
                if (empty($user->email_verified_at)) {
                    $user->email_verified_at = now();
                    $user->save();
                }
            }

            // Check if user is active
            if (isset($user->status) && $user->status != 1) {
                Session::flash('error', get_phrase('Your account has been deactivated. Please contact support.'));
                return redirect(route('login'));
            }

            // Log in the user
            Auth::login($user, true);
            $request->session()->regenerate();

            // Track device limitation if applicable
            if ($user->role !== 'admin') {
                $currentIp = $request->getClientIp();
                $sessionId = $request->session()->getId();
                $currentUserAgent = base64_encode($user->id . $request->header('user-agent'));

                DeviceIp::updateOrCreate(
                    ['user_id' => $user->id, 'user_agent' => $currentUserAgent],
                    ['ip_address' => $currentIp, 'session_id' => $sessionId, 'updated_at' => now()]
                );
            }

            Session::flash('success', get_phrase('Welcome, ') . ($user->name ?? $user->email) . '!');
            return redirect()->intended(RouteServiceProvider::HOME);

        } catch (\Throwable $e) {
            Log::error('Google OAuth callback exception: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            Session::flash('error', get_phrase('An error occurred during Google authentication. Please try again.'));
            return redirect(route('login'));
        }
    }
}
