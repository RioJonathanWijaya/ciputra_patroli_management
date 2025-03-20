<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class FirebaseSatpamService
{

    public function checkSatpamNodeExist()
    {
        $firebaseDatabaseUrl = config('firebase.projects.app.database.url') . '/satpam.json';

        $response = Http::get($firebaseDatabaseUrl);

        if (!$response->successful()) {
            Log::error('Failed to check satpam table in Firebase.');
            return;
        }

        // $data = $response->json();

        // if (is_null($data)) {
        //     $createTable = Http::post($firebaseDatabaseUrl, json_encode([
        //         $satpamData = [
        //             'satpam_id' => (string) Str::uuid(),
        //             'nama' => 'Admin',
        //             'NIP' => 'placeholder',
        //             'nomor_telepon' => 'placeholder',
        //             'alamat' => 'placeholder',
        //             'jabatan' => 'Satpam',
        //             'foto_profile' => null,
        //             'email' => 'admin@example.com',
        //             'password' => bcrypt('admin123'),
        //         ]            
        //     ]));

        //     if ($createTable->successful()) {
        //         Log::info('Satpam table created in Firebase.');
        //     } else {
        //         Log::error('Failed to create satpam table in Firebase.');
        //     }
        // }
    }
}
