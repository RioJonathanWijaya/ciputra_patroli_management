<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class FirebaseSatpamService
{

    public function checkSatpamNodeExist()
    {
        try {
            $firebaseDatabaseUrl = config('firebase.projects.app.database.url') . '/satpam.json';

            $response = Http::get($firebaseDatabaseUrl);

            if (!$response->successful()) {
                Log::error('Failed to check satpam table in Firebase.');
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Error connecting to Firebase: ' . $e->getMessage());
            return false;
        }
    }
}
