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
            ->withServiceAccount(config('firebase.projects.app.credentials'));

        $this->auth = $firebase->createAuth();
    }

    public function registerUser($email, $password, $uid = null)
    {
        try {
            $user_info = [
                'email' => $email,
                'password' => $password,
            ];
            
            if ($uid) {
                $user_info['uid'] = $uid;
            }

            $user = $this->auth->createUser($user_info);

            $this->auth->setCustomUserClaims($user->uid, ['role' => 'Satpam']);

            return $user; 

        } catch (\Throwable $e) {
            throw new \Exception('Firebase Auth User Creation Failed: ' . $e->getMessage());
        }
    }

    public function registerUserManajemen($email, $password, $uid = null)
    {
        try {
            $user_info = [
                'email' => $email,
                'password' => $password,
            ];
            
            if ($uid) {
                $user_info['uid'] = $uid;
            }

            $user = $this->auth->createUser($user_info);

            $this->auth->setCustomUserClaims($user->uid, ['role' => 'Manajemen']);

            return $user; 

        } catch (\Throwable $e) {
            throw new \Exception('Firebase Auth User Creation Failed: ' . $e->getMessage());
        }
    }

    public function verifyIdToken($token)
    {
        return $this->auth->verifyIdToken($token);
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
            $user = $this->auth->verifyIdToken($token);
            $user_claims = $user->claims();
            return [
                'uid' => $user_claims->get('sub'),
                'email' => $user_claims->get('email'),
            ];
        } catch (\Throwable $e) {
            return null;
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
