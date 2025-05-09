<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\FirebaseAuthService;
use Illuminate\Support\Facades\Cookie;

class FirebaseAuthMiddleware
{
    protected $firebaseAuth;

    public function __construct(FirebaseAuthService $firebaseAuth)
    {
        $this->firebaseAuth = $firebaseAuth;
    }

    public function handle(Request $request, Closure $next)
    {
        // Skip authentication check for login and logout routes
        if ($request->routeIs('login') || $request->routeIs('logout')) {
            return $next($request);
        }

        // Check for firebase_user in session
        $firebaseUser = session('firebase_user');
        $firebaseCookie = $request->cookie('firebase_user');

        // If no session or cookie, redirect to login
        if (!$firebaseUser || !$firebaseCookie) {
            return redirect()->route('login')->withErrors(['auth' => 'Please login to access this page.']);
        }

        try {
            // Verify cookie matches session
            $cookieData = json_decode($firebaseCookie, true);
            if (!$cookieData || $cookieData['uid'] !== $firebaseUser['uid']) {
                return redirect()->route('login')->withErrors(['auth' => 'Invalid session. Please login again.']);
            }

            return $next($request);
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['auth' => 'Authentication failed. Please login again.']);
        }
    }

    public function logout(Request $request)
    {
        // Clear Firebase token and logout user
        $token = $request->cookie('firebase_user');
        if ($token) {
            $user = $this->firebaseAuth->getUserDetailsFromToken($token);
            if ($user && isset($user['uid'])) {
                $this->firebaseAuth->logoutUser($user['uid']);
            }
        }

        // Clear firebase session
        session()->forget('firebase_user');
        session()->forget('firebase_uid');
        session()->forget('firebase_name');
        session()->forget('firebase_email');

        $domain = config('session.domain') ?? $request->getHost();
        $path = config('session.path') ?? '/';
        $secure = app()->environment('production');

        $response = redirect('/login');

        // Clear firebase user cookie
        $response->withCookie(
            Cookie::forget('firebase_user')
                ->withDomain($domain)
                ->withPath($path)
                ->withSecure($secure)
                ->withHttpOnly(true)
        );

        // Clear CSRF token
        $response->withCookie(
            Cookie::forget('XSRF-TOKEN')
                ->withDomain($domain)
                ->withPath($path)
                ->withSecure($secure)
                ->withHttpOnly(true)
        );

        return $response;
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $token = $this->firebaseAuth->loginUser($request->email, $request->password);

        if ($token) {
            $decodedToken = $this->firebaseAuth->verifyIdToken($token);
            $claims = $decodedToken->claims()->all();

            if (isset($claims['role']) && $claims['role'] === 'Satpam') {
                return redirect()->back()->with('error', 'Satpam is not allowed to login here.');
            }

            $user = $this->firebaseAuth->getUserDetailsFromToken($token);
            
            // Set firebase user data in session
            session(['firebase_user' => $user]);
            
            // Create cookie with proper settings
            $cookie = Cookie::make(
                'firebase_user',
                json_encode($user),
                60,
                '/',
                config('session.domain') ?? $request->getHost(),
                app()->environment('production'),
                true,
                false,
                'Lax'
            );

            // Create response with cookie
            $response = redirect()
                ->route('admin.dashboard')
                ->with('success', 'Login successful! Welcome to the dashboard.')
                ->withCookie($cookie);

            // Ensure session is saved
            session()->save();

            return $response;
        }

        return redirect()->back()->with('error', 'Invalid credentials');
    }

    public function showLogin()
    {
        // If already logged in, redirect to dashboard
        if (session('firebase_user') && request()->cookie('firebase_user')) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.login');
    }

    public function profile()
    {
        $user = session('firebase_user');
        if (!$user) {
            return redirect()->route('login');
        }

        return view('admin.profile', ['user' => $user]);
    }
}
