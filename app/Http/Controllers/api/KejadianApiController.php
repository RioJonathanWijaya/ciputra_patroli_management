<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class KejadianApiController extends Controller
{
    protected $database;
    protected $kejadianRef;
    protected $fotoRef;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->kejadianRef = $this->database->getReference('kejadian');
        $this->fotoRef = $this->database->getReference('foto_bukti_kejadian');
    }

    public function getAllKejadian()
    {
        $kejadianData = $this->kejadianRef->getValue() ?? [];
        $fotoData = $this->fotoRef->getValue() ?? [];

        if (empty($kejadianData)) {
            return response()->json(['error' => 'No kejadian found'], 404);
        }

        $fotoByKejadian = [];
        foreach ($fotoData as $foto) {
            if (isset($foto['kejadian_id'])) {
                $fotoByKejadian[$foto['kejadian_id']][] = $foto['url'];
            }
        }

        $result = [];

        foreach ($kejadianData as $key => $kejadian) {
            $result[] = [
                'id' => $key,
                'nama_kejadian' => $kejadian['nama_kejadian'],
                'tanggal_kejadian' => $kejadian['tanggal_kejadian'],
                'lokasi_kejadian' => $kejadian['lokasi_kejadian'],
                'tipe_kejadian' => $kejadian['tipe_kejadian'],
                'keterangan' => $kejadian['keterangan'],
                'is_kecelakaan' => $kejadian['is_kecelakaan'],
                'is_pencurian' => $kejadian['is_pencurian'],
                'is_notifikasi' => $kejadian['is_notifikasi'] ?? false,
                'nama_korban' => $kejadian['nama_korban'] ?? " ",
                'alamat_korban' => $kejadian['alamat_korban'] ?? " ",
                'keterangan_korban' => $kejadian['keterangan_korban'] ?? " ",
                'satpam_id' => $kejadian['satpam_id'],
                'satpam_nama' => $kejadian['satpam_nama'],
                'foto_bukti_kejadian' => $fotoByKejadian[$key] ?? [],
                'waktu_laporan' => $kejadian['waktu_laporan'],
                'waktu_selesai' => $kejadian['waktu_selesai'] ?? " ",
                'status' => $kejadian['status']
            ];
        }

        return response()->json($result);
    }

    public function saveKejadian(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|string',
            'nama_kejadian' => 'required|string',
            'tanggal_kejadian' => 'required|string',
            'lokasi_kejadian' => 'required|string',
            'tipe_kejadian' => 'required|string',
            'keterangan' => 'nullable|string',
            'is_kecelakaan' => 'required|boolean',
            'is_pencurian' => 'required|boolean',
            'is_notifikasi' => 'required|boolean',
            'nama_korban' => 'nullable|string',
            'alamat_korban' => 'nullable|string',
            'keterangan_korban' => 'nullable|string',
            'satpam_id' => 'required|string',
            'satpam_nama' => 'required|string',
            'status' => 'required|string',
            'waktu_selesai' => 'nullable|string',
            'waktu_laporan' => 'required|string',
            'foto_bukti_kejadian' => 'nullable|array', 
            'foto_bukti_kejadian.*' => 'string',
            'created_at' => now()->toDateTimeString(),
        ]);

        $kejadianId = $data['id'];

        $fotos = $data['foto_bukti_kejadian'] ?? [];
        unset($data['foto_bukti_kejadian']);

        $data['nama_korban'] = $data['nama_korban'] ?? 'null';
        $data['alamat_korban'] = $data['alamat_korban'] ?? 'null';
        $data['keterangan_korban'] = $data['keterangan_korban'] ?? 'null';
        $data['waktu_selesai'] = $data['waktu_selesai'] ?? 'null';

        try {

            $this->kejadianRef->getChild($kejadianId)->set($data);

            foreach ($fotos as $fotoUrl) {
                $this->fotoRef->push([
                    'kejadian_id' => $kejadianId,
                    'url' => $fotoUrl,
                    'uploaded_at' => now()->toDateTimeString()
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Kejadian and photos successfully saved']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to save kejadian', 'error' => $e->getMessage()], 500);
        }
    }
}
