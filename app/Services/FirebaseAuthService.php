<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;

class FirebaseAuthService
{
    private Auth $auth;

    public function __construct()
    {
        $firebase = (new Factory)
            ->withServiceAccount(config('firebase.projects.app.credentials'))
            ->createAuth();

        $this->auth = $firebase;
    }

    public function registerUser($email, $password)
    {
        return $this->auth->createUserWithEmailAndPassword($email, $password);
    }

    public function loginUser($email, $password)
    {
        try {
            $signInResult = $this->auth->signInWithEmailAndPassword($email, $password);
            return $signInResult->idToken();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getUserDetailsFromToken($token)
    {
        try {
            $user = $this->auth->verifyIdToken($token); // Verify and decode the token
            $user_claims = $user->claims();
            return [
                'uid' => $user_claims->get('sub'),
                'email' => $user_claims->get('email'),
            ];
        } catch (\Throwable $e) {
            return null; // Handle exception or log the error
        }
    }

    public function logoutUser(string $uid)
{
    try {
        $this->auth->revokeRefreshTokens($uid);
        return true;
    } catch (\Throwable $e) {
        return false;
    }
}
}
