<?php

namespace App\Http\Controllers\lokasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Str;

class LokasiController extends Controller
{

    protected $database;
    protected $tableName = 'lokasi';
    protected $lokasiRef;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->lokasiRef = $this->database->getReference('lokasi');
    }
    public function lokasi()
    {
        
        $lokasiData = $this->lokasiRef->getValue();

        $result = [];

        if ($lokasiData) {
            foreach ($lokasiData as $key => $lokasi) {
                $result[] = [
                    'id' => $lokasi['id'] ?? '-',
                    'nama_lokasi' => $lokasi['nama_lokasi'] ?? '-',
                    'alamat' => $lokasi['alamat'] ?? '-',
                    'deskripsi' => $lokasi['deskripsi'] ?? '-',
                    'latitude' => $lokasi['latitude'] ?? '-',
                    'longitude' => $lokasi['longitude'] ?? '-',
                ];
            }
        }
        return view('admin.lokasi.lokasi', ['lokasiData' => $result]);
    }

    public function create()
    {
        return view('admin.lokasi.create');
    }


    public function destroy($id)
{
    try {
        $this->database->getReference('lokasi/' . $id)->remove();

        return redirect()->back()->with('success', 'Lokasi berhasil dihapus.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal menghapus lokasi: ' . $e->getMessage());
    }
}

    public function store(Request $request)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $uuid = Str::uuid()->toString();

        $data = [
            'nama_lokasi' => $request->nama_lokasi,
            'alamat' => $request->alamat,
            'deskripsi' => $request->deskripsi,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'created_at' => now()->toDateTimeString(),
        ];

        $this->lokasiRef->set($data);

        return redirect()->route('admin.lokasi.lokasi')->with('success', 'Lokasi berhasil disimpan ke Firebase!');
    }
}
