<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Carbon\Carbon;

class PatroliApiController extends Controller
{
    protected $database;
    protected $patroliRef;
    protected $lokasiRef;
    protected $satpamRef;
    protected $patroliCheckpointRef;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->patroliRef = $this->database->getReference('patroli');
        $this->lokasiRef = $this->database->getReference('lokasi');
        $this->satpamRef = $this->database->getReference('satpam');
        $this->patroliCheckpointRef = $this->database->getReference('patroli_checkpoint');
    }

    public function getRecentPatroli()
    {
        try {
            $patroli = $this->patroliRef->getValue();
            $lokasi = $this->lokasiRef->getValue();
            $satpam = $this->satpamRef->getValue();
            $patroliCheckpoint = $this->patroliCheckpointRef->getValue();

            $patroliData = [];
            foreach ($patroli as $id => $data) {
                if (isset($data['tanggal'])) {
                    // Get location and satpam data
                    $lokasiData = $lokasi[$data['lokasiId']] ?? [];
                    $satpamData = $satpam[$data['satpamId']] ?? [];

                    // Get checkpoints for this patrol
                    $checkpoints = [];
                    if (isset($patroliCheckpoint[$id])) {
                        foreach ($patroliCheckpoint[$id] as $checkpoint) {
                            $checkpoints[] = [
                                'latitude' => $checkpoint['latitude'],
                                'longitude' => $checkpoint['longitude'],
                                'timestamp' => $checkpoint['timestamp']
                            ];
                        }
                    }

                    $patroliData[] = [
                        'id' => $id,
                        'lokasi_nama' => $lokasiData['nama_lokasi'] ?? 'Unknown Location',
                        'satpam_nama' => $satpamData['nama'] ?? 'Unknown Security',
                        'latitude' => $lokasiData['latitude'] ?? 0,
                        'longitude' => $lokasiData['longitude'] ?? 0,
                        'tanggal' => Carbon::parse($data['tanggal'])->format('d M Y'),
                        'jam_mulai' => Carbon::parse($data['jamMulai'])->format('H:i'),
                        'status' => $this->getStatus($data['durasiPatroli'] ?? 0, $data['isTerlambat'] ?? false),
                        'checkpoints' => $checkpoints
                    ];
                }
            }

            // Sort patrol data by date and time
            usort($patroliData, function($a, $b) {
                $dateA = Carbon::parse($a['tanggal'] . ' ' . $a['jam_mulai']);
                $dateB = Carbon::parse($b['tanggal'] . ' ' . $b['jam_mulai']);
                return $dateB->timestamp - $dateA->timestamp;
            });

            // Get only the 5 most recent patrols
            $recentPatroli = array_slice($patroliData, 0, 5);

            return response()->json($recentPatroli);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch patrol data'], 500);
        }
    }

    protected function getStatus($durasi, $isTerlambat)
    {
        if ($durasi >= 60) {
            return 'Selesai';
        } else if ($isTerlambat) {
            return 'Terlambat';
        } else {
            return 'Dalam Patroli';
        }
    }
} 