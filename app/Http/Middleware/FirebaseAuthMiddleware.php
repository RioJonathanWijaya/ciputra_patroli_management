<?php

namespace App\Http\Middleware;

use Closure;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth as FirebaseAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FirebaseAuthMiddleware
{
    protected $auth;

    public function __construct()
    {
        $this->auth = (new Factory)
            ->withServiceAccount(config('firebase.projects.app.credentials'))
            ->createAuth();
    }

    public function handle(Request $request, Closure $next)
    {
        if ($request->routeIs('login') || $request->routeIs('logout')) {
            return $next($request);
        }
    
        if ($request->routeIs(!'login')) {
            $token = $request->bearerToken(); 
    
            if (!$token) {
                return redirect()->route('login');
            }
    
            try {
                $verifiedIdToken = $this->auth->verifyIdToken($token);
                $firebaseUserId = $verifiedIdToken->claims()->get('sub'); 

            $user = $this->auth->getUser($firebaseUserId);

            session([
                'firebase_uid' => $firebaseUserId,
                'firebase_name' => $user->displayName ?? 'User',
                'firebase_email' => $user->email ?? 'No Email',
            ]);
    
                Auth::loginUsingId($firebaseUserId);
    
                return $next($request);
            } catch (\Exception $e) {
                return redirect()->route('login')->withErrors(['auth' => 'Invalid or expired token. Please login again.']);
            }
        }
    
        return $next($request);
    }
    

}
