<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Database;

class JadwalPatroliApiController extends Controller
{
    protected $database;
    protected $jadwalPatroliRef;
    protected $lokasiRef;
    protected $satpamRef;
    protected $penugasanRef;
    protected $patroliRef;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->jadwalPatroliRef = $this->database->getReference('jadwal_patroli');
        $this->lokasiRef = $this->database->getReference('lokasi');
        $this->satpamRef = $this->database->getReference('satpam');
        $this->penugasanRef = $this->database->getReference('penugasan_patroli');
        $this->patroliRef = $this->database->getReference('patroli');
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

    public function getStatsPatroliSatpam($uid)
    {
        $patroliData = $this->patroliRef->getValue() ?? [];

        $stats = [
            'total_patroli' => 0,
            'total_late' => 0,
            'total_completed' => 0,
        ];

        foreach ($patroliData as $key => $patroli) {
            if (($patroli['satpamId'] ?? $patroli['satpam_id'] ?? null) == $uid) {
                $stats['total_patroli']++;

                if ($patroli['isTerlambat'] == true) {
                    $stats['total_late']++;
                }

                if ($patroli['isTerlambat'] == false) {
                    $stats['total_completed']++;
                }
            }
        }

        return response()->json($stats);
    }
}
