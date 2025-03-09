<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseAuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    private $firebaseAuth;

    public function __construct(FirebaseAuthService $firebaseAuth)
    {
        $this->firebaseAuth = $firebaseAuth;
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate the input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Authenticate the user with Firebase
        $token = $this->firebaseAuth->loginUser($request->email, $request->password);

        if ($token) {

            $user = $this->firebaseAuth->getUserDetailsFromToken($token);  // You might need to create this method in FirebaseAuthService

            session(['firebase_token' => $token]);
            session(['firebase_user' => $user]);
            
            Cookie::queue('firebase_user', json_encode($user), 60, null, null, true, true);


            // Redirect to the admin dashboard
            return redirect()->route('admin.dashboard');
        }

        // If authentication fails, redirect back with error
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout(Request $request, FirebaseAuthService $firebaseAuth)
    {
        // (Optional) Revoke user session from Firebase
        $token = $request->cookie('firebase_user');
        if ($token) {
            $user = $firebaseAuth->getUserDetailsFromToken($token);
            if ($user && isset($user['uid'])) {
                $firebaseAuth->logoutUser($user['uid']); // revoke refresh tokens
            }
        }
    
        // Clear the HttpOnly cookie
        return redirect('/login')->withCookie(Cookie::forget('firebase_user'));
    }
}

