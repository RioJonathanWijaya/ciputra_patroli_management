<?php

namespace App\Http\Controllers\patroli;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Database;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PatroliController extends Controller
{
    protected $database;
    protected $patroliRef;
    protected $lokasiRef;
    protected $satpamRef;
    protected $jadwalRef;
    protected $penugasanRef;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->patroliRef = $this->database->getReference('patroli');
        $this->lokasiRef = $this->database->getReference('lokasi');
        $this->satpamRef = $this->database->getReference('satpam');
        $this->jadwalRef = $this->database->getReference('jadwal_patroli');
        $this->penugasanRef = $this->database->getReference('penugasan');
    }

    public function patroli()
    {
        try {
            $patroliData = [];
            $lokasiData = [];
            $satpamData = [];
            $jadwalData = [];
            $penugasanData = [];

            $patroliSnapshot = $this->patroliRef->getSnapshot();
            if ($patroliSnapshot->exists()) {
                $patroliData = $patroliSnapshot->getValue() ?? [];
            }

            $lokasiSnapshot = $this->lokasiRef->getSnapshot();
            if ($lokasiSnapshot->exists()) {
                $lokasiData = $lokasiSnapshot->getValue() ?? [];
            }

            $satpamSnapshot = $this->satpamRef->getSnapshot();
            if ($satpamSnapshot->exists()) {
                $satpamData = $satpamSnapshot->getValue() ?? [];
            }

            $jadwalSnapshot = $this->jadwalRef->getSnapshot();
            if ($jadwalSnapshot->exists()) {
                $jadwalData = $jadwalSnapshot->getValue() ?? [];
            }

            $penugasanSnapshot = $this->penugasanRef->getSnapshot();
            if ($penugasanSnapshot->exists()) {
                $penugasanData = $penugasanSnapshot->getValue() ?? [];
            }

            $result = [];
            foreach ($patroliData as $patroliId => $patroli) {
                $lokasi = $lokasiData[$patroli['lokasiId']] ?? null;
                $namaLokasi = $lokasi ? $lokasi['nama_lokasi'] : 'Unknown Location';
                $satpam = $satpamData[$patroli['satpamId']] ?? null;
                $namaSatpam = $satpam ? $satpam['nama'] : 'Unknown Satpam';
                $jadwal = $jadwalData[$patroli['jadwalPatroliId']] ?? null;
                $namaJadwal = $jadwal ? 'Jadwal ' . ($jadwal['shift'] ?? 'Unknown') : 'Unknown Jadwal';
                $penugasan = $penugasanData[$patroli['penugasanId']] ?? null;
                $namaPenugasan = $penugasan ? 'Penugasan ' . ($penugasan['shift'] ?? 'Unknown') : 'Unknown Penugasan';

                $result[] = [
                    'id' => $patroliId,
                    'nama_lokasi' => $namaLokasi,
                    'nama_satpam' => $namaSatpam,
                    'nama_jadwal' => $namaJadwal,
                    'nama_penugasan' => $namaPenugasan,
                    'jam_mulai' => $patroli['jamMulai'] ?? '',
                    'durasi_patroli' => $patroli['durasiPatroli'] ?? 0,
                    'catatan_patroli' => $patroli['catatanPatroli'] ?? '',
                    'is_terlambat' => $patroli['isTerlambat'] ?? false,
                    'rute_patroli' => $patroli['rutePatroli'] ?? '',
                    'status' => $this->getStatus($patroli['durasiPatroli'] ?? 0, $patroli['isTerlambat'] ?? false)
                ];
            }

            usort($result, function($a, $b) {
                return strtotime($b['jam_mulai']) - strtotime($a['jam_mulai']);
            });

            return view('admin.patroli.patroli', ['patroliData' => $result]);

        } catch (\Exception $e) {
            return view('admin.patroli.patroli', ['result' => []])
                ->with('error', 'Failed to fetch patrol data: ' . $e->getMessage());
        }
    }

    protected function getStatus($durasi, $isTerlambat)
    {
        if ($durasi > 0) {
            return $isTerlambat ? 'Terlambat' : 'Selesai';
        }
        return 'Dalam Proses';
    }

    public function destroy($id)
    {
        try {
            $patroli = $this->patroliRef->getChild($id)->getValue();

            if (!$patroli) {
                return redirect()->route('admin.patroli.patroli')
                    ->with('error', 'Patrol data not found.');
            }

            $this->patroliRef->getChild($id)->remove();

            return redirect()->route('admin.patroli.patroli')
                ->with('success', 'Patrol data successfully deleted.');

        } catch (\Exception $e) {
            return redirect()->route('admin.patroli.patroli')
                ->with('error', 'Failed to delete patrol data: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $patroli = $this->patroliRef->getChild($id)->getValue();

            if (!$patroli) {
                return redirect()->route('admin.patroli.patroli')
                    ->with('error', 'Patrol data not found.');
            }

            // Get related data
            $lokasi = $this->lokasiRef->getChild($patroli['lokasiId'])->getValue();
            $satpam = $this->satpamRef->getChild($patroli['satpamId'])->getValue();
            $jadwal = $this->jadwalRef->getChild($patroli['jadwalPatroliId'])->getValue();
            $penugasan = $this->penugasanRef->getChild($patroli['penugasanId'])->getValue();

            // Format the data for the view
            $patroliData = [
                'id' => $id,
                'nama_lokasi' => $lokasi['nama_lokasi'] ?? 'Unknown Location',
                'nama_satpam' => $satpam['nama'] ?? 'Unknown Satpam',
                'nama_jadwal' => $jadwal ? 'Jadwal ' . ($jadwal['shift'] ?? 'Unknown') : 'Unknown Jadwal',
                'nama_penugasan' => $penugasan ? 'Penugasan ' . ($penugasan['shift'] ?? 'Unknown') : 'Unknown Penugasan',
                'tanggal' => Carbon::parse($patroli['jamMulai'])->format('d M Y'),
                'waktu_mulai' => Carbon::parse($patroli['jamMulai'])->format('H:i'),
                'waktu_selesai' => Carbon::parse($patroli['jamMulai'])->addMinutes($patroli['durasiPatroli'] ?? 0)->format('H:i'),
                'durasi_patroli' => $patroli['durasiPatroli'] ?? 0,
                'catatan' => $patroli['catatanPatroli'] ?? 'Tidak ada catatan',
                'status' => $this->getStatus($patroli['durasiPatroli'] ?? 0, $patroli['isTerlambat'] ?? false),
                'checkpoints' => $this->formatCheckpoints($patroli['rutePatroli'] ?? [])
            ];

            return view('admin.patroli.patroli_detail', ['patroli' => $patroliData]);

        } catch (\Exception $e) {
            return redirect()->route('admin.patroli.patroli')
                ->with('error', 'Failed to fetch patrol detail: ' . $e->getMessage());
        }
    }

    protected function formatCheckpoints($checkpoints)
    {
        if (!is_array($checkpoints)) {
            return [];
        }

        $formatted = [];
        foreach ($checkpoints as $checkpoint) {
            $formatted[] = [
                'nama' => $checkpoint['nama'] ?? 'Unknown Checkpoint',
                'status' => $checkpoint['status'] ?? 'Pending',
                'waktu' => isset($checkpoint['waktu']) ? Carbon::parse($checkpoint['waktu'])->format('H:i') : '-'
            ];
        }

        return $formatted;
    }
}
