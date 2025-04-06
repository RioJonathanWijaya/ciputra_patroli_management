<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Database;

class JadwalPatroliApiController extends Controller
{
    protected $database;
    protected $jadwalPatroliRef;
    protected $lokasiRef;
    protected $satpamRef;
    protected $penugasanRef;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->jadwalPatroliRef = $this->database->getReference('jadwal_patroli');
        $this->lokasiRef = $this->database->getReference('lokasi');
        $this->satpamRef = $this->database->getReference('satpam');
        $this->penugasanRef = $this->database->getReference('penugasan_patroli');
    }

    public function getPenugasanByUID($uid)
    {
        if (!$uid) {
            return response()->json(['error' => 'Satpam ID is required'], 400);
        }
    
        $penugasanData = $this->penugasanRef->getValue() ?? [];
        $lokasiData = $this->lokasiRef->getValue() ?? [];
        $jadwalPatroliData = $this->jadwalPatroliRef->getValue() ?? [];
    
        if (empty($penugasanData)) {
            return response()->json(['error' => 'No assignments found for this Satpam ID'], 404);
        }

        $result = [];
    
        foreach ($penugasanData as $key => $penugasan) {
            if (($penugasan['satpam_id'] ?? null) === $uid) {
                $jadwalId = $penugasan['jadwal_patroli_id'] ?? null;
                
                if ($jadwalId && isset($jadwalPatroliData[$jadwalId])) {
                    $jadwalPatroli = $jadwalPatroliData[$jadwalId];
                    $lokasiId = $jadwalPatroli['lokasi'] ?? null;

                    $namaLokasi = $lokasiData[$lokasiId]['nama_lokasi'] ?? '-';
                    $interval = $jadwalPatroli['interval_patroli'] ?? '-';
                    $listCheckpoint = $jadwalPatroli['titik_patrol'];
    
                    $result[] = [
                        'id' => $key,
                        'satpam_id' => $uid,
                        'jadwal_patroli_id' => $jadwalId,
                        'lokasi_id' => $lokasiId,
                        'nama_lokasi' => $namaLokasi,
                        'shift' => $penugasan['shift'] ?? '-',
                        'jam_patroli' => $penugasan['jam_patroli'] ?? '-',
                        'interval' => $interval,
                        'titik_patroli' => $listCheckpoint
                    ];
                }
            }
        }
    
        return response()->json($result);
    }
}
