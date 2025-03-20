<?php

namespace App\Http\Controllers\jadwal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class JadwalController extends Controller
{
    protected $database;
    // protected $tableName = 'jadwal_patroli';
    protected $jadwalRef;
    protected $lokasiRef;
    protected $satpamRef;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->jadwalRef = $this->database->getReference('jadwal_patroli');
        $this->lokasiRef = $this->database->getReference('lokasi');
        $this->satpamRef = $this->database->getReference('satpam');
    }
    public function jadwal()
    {
        $jadwalData = $this->jadwalRef->getValue() ?? [];

        $lokasiData = $this->lokasiRef->getValue() ?? [];
        $satpamData = $this->satpamRef->getValue() ?? [];

        $result = [];


        if ($jadwalData) {
            foreach ($jadwalData as $key => $jadwal) {


                $lokasiId = $jadwal['lokasi'] ?? null;
                $satpamPagiId = $jadwal['satpam_shift_pagi'] ?? null;
                $satpamMalamId = $jadwal['satpam_shift_malam'] ?? null;


                $namaLokasi = $lokasiData[$lokasiId]['nama_lokasi'] ?? '-';
                $namaSatpamPagi = $satpamData[$satpamPagiId]['nama'] ?? '-';
                $namaSatpamMalam = $satpamData[$satpamMalamId]['nama'] ?? '-';

                $result[] = [
                    'id' => $key ?? '-',
                    'lokasi_id' => $lokasiId ?? '-',
                    'nama_lokasi' => $namaLokasi ?? '-',
                    'satpam_pagi_id' => $satpamPagiId,
                    'nama_satpam_pagi' => $namaSatpamPagi,
                    'satpam_malam_id' => $satpamMalamId,
                    'nama_satpam_malam' => $namaSatpamMalam,
                    'created_at' => $jadwal['created_at'] ?? '-',
                    'updated_at' => $jadwal['updated_at'] ?? '-',
                ];
            }
        }


        return view('admin.jadwal_patroli.jadwal_patroli', ['jadwalData' => $result]);
    }

    public function create()
    {
        $lokasiData = $this->lokasiRef->getValue() ?? [];
        $satpamData = $this->satpamRef->getValue() ?? [];

        $satpamPagi = [];
        $satpamMalam = [];

        foreach ($satpamData as $id => $satpam) {
            if (isset($satpam['shift']) && $satpam['shift'] == 'Pagi') {
                $satpamPagi[$id] = $satpam;
            } else if (isset($satpam['shift']) && $satpam['shift'] == 'Malam') {
                $satpamMalam[$id] = $satpam;
            }
        }



        return view('admin.jadwal_patroli.create', compact('lokasiData', 'satpamPagi', 'satpamMalam'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lokasi' => 'required',
            'satpam_shift_pagi' => 'required',
            'satpam_shift_malam' => 'required',
            'titik_patrol' => 'required|string',
            'interval_patroli' => 'required|numeric|min:1',
        ]);

        $titikPatrolArray = json_decode($request->titik_patrol, true);
        if (!is_array($titikPatrolArray) || count($titikPatrolArray) < 1) {
            return redirect()->back()->withErrors(['titik_patrol' => 'Titik patrol harus berisi setidaknya satu titik.'])->withInput();
        }

        $this->jadwalRef->push([
            'lokasi' => $request->lokasi,
            'satpam_shift_pagi' => $request->satpam_shift_pagi,
            'satpam_shift_malam' => $request->satpam_shift_malam,
            'titik_patrol' => $titikPatrolArray,
            'interval_patroli' => $request->interval_patroli,
        ]);


        $satpamData = $this->satpamRef->getValue() ?? [];

        if ($satpamData) {
            foreach ($satpamData as $id => $satpam) {

                if ($id === $request->satpam_shift_pagi) {
                    $this->database->getReference('satpam/' . $id)
                        ->update(['lokasi_id' => $request->lokasi]);
                }
        
                if ($id === $request->satpam_shift_malam && $request->satpam_shift_malam !== $request->satpam_shift_pagi) {
                    $this->database->getReference('satpam/' . $id)
                        ->update(['lokasi_id' => $request->lokasi]);
                }
            }
        }

        return redirect()->route('admin.jadwal_patroli.jadwal_patroli')->with('success', 'Jadwal Patroli berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'lokasi' => 'required|string',
            'satpam_shift_pagi' => 'required|string',
            'satpam_shift_malam' => 'required|string',
            'titik_patrol' => 'required|string',
            'interval_patroli' => 'required|numeric|min:1',
        ]);

        $data = [
            'lokasi_id' => $request->lokasi,
            'satpam_shift_pagi' => $request->satpam_shift_pagi,
            'satpam_shift_malam' => $request->satpam_shift_malam,
            'titik_patrol' => json_decode($request->titik_patrol, true),
            'interval_patroli' => $request->interval_patroli,
            'updated_at' => now()->toDateTimeString()
        ];

        try {
            $this->database
                ->getReference('jadwal_patroli/' . $id)
                ->update($data);

            return redirect()->route('admin.jadwal_patroli.jadwal_patroli')
                ->with('success', 'Jadwal patroli berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withErrors(['firebase' => 'Gagal memperbarui data: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        try {
            $jadwal = $this->database
                ->getReference('jadwal_patroli/' . $id)
                ->getValue();

            if (!$jadwal) {
                return redirect()->route('admin.jadwal_patroli.jadwal_patroli')
                    ->withErrors(['not_found' => 'Jadwal tidak ditemukan.']);
            }

            $lokasiData = $this->database->getReference('lokasi')->getValue() ?? [];
            $satpamData = $this->database->getReference('satpam')->getValue() ?? [];

            $satpamPagi = $satpamData;
            $satpamMalam = $satpamData;

            return view('admin.jadwal_patroli.update', [
                'id' => $id,
                'jadwal' => $jadwal,
                'lokasiData' => $lokasiData,
                'satpamPagi' => $satpamPagi,
                'satpamMalam' => $satpamMalam,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.jadwal_patroli.jadwal_patroli')
                ->withErrors(['firebase' => 'Gagal mengambil data: ' . $e->getMessage()]);
        }
    }
    public function destroy($id)
    {

        try {
            $this->database->getReference('jadwal_patroli/' . $id)->remove();

            return redirect()->route('admin.jadwal_patroli.jadwal_patroli')
                ->with('success', 'Jadwal patroli berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.jadwal_patroli.jadwal_patroli')
                ->withErrors(['firebase' => 'Gagal menghapus data: ' . $e->getMessage()]);
        }
    }
}
