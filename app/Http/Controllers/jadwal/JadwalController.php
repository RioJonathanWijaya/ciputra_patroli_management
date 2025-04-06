<?php

namespace App\Http\Controllers\jadwal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class JadwalController extends Controller
{
    protected $database;
    protected $jadwalRef;
    protected $lokasiRef;
    protected $satpamRef;
    protected $penugasanRef;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->jadwalRef = $this->database->getReference('jadwal_patroli');
        $this->lokasiRef = $this->database->getReference('lokasi');
        $this->satpamRef = $this->database->getReference('satpam');
        $this->penugasanRef = $this->database->getReference('penugasan_patroli');
    }
    public function jadwal()
{
    $jadwalData = $this->jadwalRef->getValue() ?? [];
    $lokasiData = $this->lokasiRef->getValue() ?? [];
    $satpamData = $this->satpamRef->getValue() ?? [];
    $jadwalSatpamData = $this->penugasanRef->getValue() ?? [];

    $result = [];

    if ($jadwalData) {
        foreach ($jadwalData as $key => $jadwal) {
            $lokasiId = $jadwal['lokasi'] ?? null;
            $namaLokasi = $lokasiData[$lokasiId]['nama_lokasi'] ?? '-';

            $satpamList = [];
            if ($jadwalSatpamData) {
                foreach ($jadwalSatpamData as $jadwalSatpamId => $jadwalSatpam) {
                    if ($jadwalSatpam['jadwal_patroli_id'] == $key) {
                        $satpamId = $jadwalSatpam['satpam_id'];
                        $shift = $jadwalSatpam['shift'] ?? 'tidak diketahui';
                        $jamPatroli = $jadwalSatpam['jam_patroli'] ?? '-';

                        $satpamList[] = [
                            'id' => $satpamId,
                            'nama' => $satpamData[$satpamId]['nama'] ?? 'Tidak Diketahui',
                            'shift' => $shift,
                            'jam_patroli' => $jamPatroli,
                        ];
                    }
                }
            }

            $result[] = [
                'id' => $key ?? '-',
                'lokasi_id' => $lokasiId ?? '-',
                'nama_lokasi' => $namaLokasi ?? '-',
                'satpam_list' => $satpamList,
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
            if($satpam['status'] == 0){
                if (isset($satpam['shift']) && $satpam['shift'] == 0) {
                    $satpamPagi[$id] = $satpam;
                } else if (isset($satpam['shift']) && $satpam['shift'] == 1) {
                    $satpamMalam[$id] = $satpam;
                }
            }
        }



        return view('admin.jadwal_patroli.create', compact('lokasiData', 'satpamPagi', 'satpamMalam'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'required',
            'satpam_shift_pagi' => 'nullable|string',
            'satpam_shift_malam' => 'nullable|string',
            'interval_patroli' => 'required|numeric|min:1',
            'titik_patrol' => 'required|string',
        ]);
    
        $titikPatrolArray = json_decode($request->titik_patrol, true);
        if (!is_array($titikPatrolArray) || count($titikPatrolArray) < 1) {
            return redirect()->back()->withErrors(['titik_patrol' => 'Titik patrol harus berisi setidaknya satu titik.'])->withInput();
        }
    
        $interval = (int) $request->interval_patroli;
    
        $jadwalRef = $this->jadwalRef->push([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
            'titik_patrol' => $titikPatrolArray,
            'interval_patroli' => $interval,
        ]);
        $jadwalId = $jadwalRef->getKey();
    
        $shifts = [
            'pagi' => ['start' => 7, 'end' => 19, 'satpam_id' => $request->satpam_shift_pagi],
            'malam' => ['start' => 19, 'end' => 7, 'satpam_id' => $request->satpam_shift_malam]
        ];
    
        foreach ($shifts as $shift => $data) {
            if (!$data['satpam_id']) continue;
            
            $jamPatroliList = [];
            $currentHour = $data['start'];
            while (true) {
                $jamPatroliList[] = sprintf('%02d:00', $currentHour);
                $currentHour += $interval;
                if ($shift == 'pagi' && $currentHour >= 19) break;
                if ($shift == 'malam') {
                    if($currentHour >= 24) $currentHour -= 24;
                    if($currentHour >= 7) break;
                }
            }
    
            foreach ($jamPatroliList as $jam) {
                $this->penugasanRef->push([
                    'jadwal_patroli_id' => $jadwalId,
                    'satpam_id' => $data['satpam_id'],
                    'shift' => $shift,
                    'jam_patroli' => $jam,
                ]);
            }
    
            $this->database->getReference('satpam/' . $data['satpam_id'])->update(['lokasi_id' => $request->lokasi]);
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
