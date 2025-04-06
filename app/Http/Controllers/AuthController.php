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
            session(['firebase_token' => $token]);
            session(['firebase_user' => $user]);


            Cookie::queue('firebase_user', json_encode($user), 60, null, null, true, true);

            return redirect()->route('admin.dashboard')->with('success', 'Login successful! Welcome to the dashboard.');
        }

        return redirect()->back()->with('error', 'Invalid credentials');
    }


    public function logout(Request $request, FirebaseAuthService $firebaseAuth)
    {
        $token = $request->cookie('firebase_user');
        if ($token) {
            $user = $firebaseAuth->getUserDetailsFromToken($token);
            if ($user && isset($user['uid'])) {
                $firebaseAuth->logoutUser($user['uid']);
            }
        }

        return redirect('/login')->withCookie(Cookie::forget('firebase_user'));
    }
}
