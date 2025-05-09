<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $database;
    protected $patroliRef;
    protected $kejadianRef;
    protected $lokasiRef;
    protected $satpamRef;
    protected $patroliCheckpointRef;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->patroliRef = $this->database->getReference('patroli');
        $this->kejadianRef = $this->database->getReference('kejadian');
        $this->lokasiRef = $this->database->getReference('lokasi');
        $this->satpamRef = $this->database->getReference('satpam');
        $this->patroliCheckpointRef = $this->database->getReference('patroli_checkpoint');
    }

    public function dashboard()
    {
        $today = Carbon::now()->format('Y-m-d');
        $yesterday = Carbon::now()->subDay()->format('Y-m-d');

        $patroli = $this->patroliRef->getValue();
        $kejadian = $this->kejadianRef->getValue();
        $lokasi = $this->lokasiRef->getValue();
        $satpam = $this->satpamRef->getValue();
        $patroliCheckpoint = $this->patroliCheckpointRef->getValue();

        $totalPatroliToday = 0;
        $totalKejadianToday = 0;
        $totalPatroliYesterday = 0;
        $totalKejadianYesterday = 0;

        $patroliData = [];
        foreach ($patroli as $id => $data) {
            if (isset($data['tanggal'])) {
                $tanggal = Carbon::parse($data['tanggal'])->format('Y-m-d');
                if ($tanggal == $today) {
                    $totalPatroliToday++;
                } else if ($tanggal == $yesterday) {
                    $totalPatroliYesterday++;
                }
                $lokasiData = $lokasi[$data['lokasiId']] ?? [];
                $satpamData = $satpam[$data['satpamId']] ?? [];

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

        usort($patroliData, function($a, $b) {
            $dateA = Carbon::parse($a['tanggal'] . ' ' . $a['jam_mulai']);
            $dateB = Carbon::parse($b['tanggal'] . ' ' . $b['jam_mulai']);
            return $dateB->timestamp - $dateA->timestamp;
        });

        $recentPatroli = array_slice($patroliData, 0, 5);

        $kejadianData = [];
        foreach ($kejadian as $id => $data) {
            if (isset($data['tanggal_kejadian'])) {
                $tanggal = Carbon::parse($data['tanggal_kejadian'])->format('Y-m-d');
                if ($tanggal == $today) {
                    $totalKejadianToday++;
                } else if ($tanggal == $yesterday) {
                    $totalKejadianYesterday++;
                }

                $kejadianData[] = [
                    'id' => $id,
                    'nama_kejadian' => $data['nama_kejadian'],
                    'lokasi_kejadian' => $data['lokasi_kejadian'],
                    'tanggal' => Carbon::parse($data['tanggal_kejadian'])->format('d M Y')
                ];
            }
        }

        usort($kejadianData, function($a, $b) {
            $dateA = Carbon::parse($a['tanggal']);
            $dateB = Carbon::parse($b['tanggal']);
            return $dateB->timestamp - $dateA->timestamp;
        });

        $recentKejadian = array_slice($kejadianData, 0, 5);

        $result = [
            'totalPatroli' => $totalPatroliToday,
            'totalKejadian' => $totalKejadianToday,
            'totalPatroliYesterday' => $totalPatroliYesterday,
            'totalKejadianYesterday' => $totalKejadianYesterday,
            'patroli' => $recentPatroli,
            'kejadian' => $recentKejadian
        ];
        return view('admin.dashboard', compact('result'));
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
