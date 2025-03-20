<?php

namespace App\Http\Controllers\satpam;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Database;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SatpamController extends Controller
{
    protected $database;
    protected $satpamRef;
    protected $lokasiRef;
    
    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->satpamRef = $this->database->getReference('satpam');
        $this->lokasiRef = $this->database->getReference('lokasi');
    }

    public function satpam() {
        $satpamData = $this->satpamRef->getValue();
    
        $satpamList = [];
        if ($satpamData) {
            foreach ($satpamData as $key => $satpam) {
                $satpamList[] = $satpam;
            }
        }
    
        return view('admin.satpam.satpam', ['satpamData' => $satpamList]);
    }

    public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'nama' => 'required|string',
            'nip' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'nomor_telepon' => 'required|string',
            'alamat' => 'required|string',
            'jabatan' => 'required|string',
            'shift' => 'required|string',
            'lokasi' => 'nullable|string',
            'status' => 'nullable|string',
            'supervisor' => 'nullable|string',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string',
            'tanggal_bergabung' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'status_pernikahan' => 'required|string',
            'pendidikan_terakhir' => 'required|string',
            'photo' => 'nullable|image|max:2048',
        ]);
    
        $ref = app('firebase.database')->getReference('satpam');
        $newSatpamRef = $ref->push();
        $satpam_id = $newSatpamRef->getKey();
    
        $fotoProfileUrl = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/satpam'), $filename);
            $fotoProfileUrl = url('uploads/satpam/' . $filename);
        }
    
        $satpamData = [
            'satpam_id' => $satpam_id,
            'nama' => $validated['nama'],
            'nip' => $validated['nip'],
            'shift' => $validated['shift'],
            'lokasi_id' => 0,
            'status' => $validated['status'] ?? 'Aktif',
            'jabatan' => $validated['jabatan'],
            'foto_profile' => $fotoProfileUrl,
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'supervisor_id' => $validated['supervisor'] ?? 0,
            'penugasan_id' => 0,
            'nomor_telepon' => $validated['nomor_telepon'],
            'alamat' => $validated['alamat'],
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? '',
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? '',
            'tempat_lahir' => $validated['tempat_lahir'] ?? '',
            'tanggal_bergabung' => $validated['tanggal_bergabung'] ?? '',
            'status_pernikahan' => $validated['status_pernikahan'] ?? '',
            'pendidikan_terakhir' => $validated['pendidikan_terakhir'] ?? '',
            'satpam_id' => $satpam_id
        ];
    
        $newSatpamRef->set($satpamData); 
    
    
        return redirect()->back()->with('success', 'Data Satpam berhasil ditambahkan!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal menambahkan Satpam: ' . $e->getMessage());
    }
    
}

public function detail($id)
{
    $satpamDetail = $this->satpamRef->getChild($id)->getValue();

    if (!$satpamDetail) {
        return abort(404, 'Satpam not found.');
    }

    $lokasiId = $satpamDetail['lokasi_id'] ?? null;

    $lokasiName = '-';
    if ($lokasiId) {
        $lokasiData = $this->lokasiRef->getChild($lokasiId)->getValue();
        if ($lokasiData && isset($lokasiData['nama_lokasi'])) {
            $lokasiName = $lokasiData['nama_lokasi'];
        }
    }

    $satpamDetail['nama_lokasi'] = $lokasiName;

    return view('admin.satpam.detail', ['satpam' => $satpamDetail]);
}

public function create() {
    $kepalaSatpamData = $this->satpamRef->getValue() ?? [];

    $filteredKepalaSatpam = [];

        foreach($kepalaSatpamData as $key => $kepalaSatpam){
            if(isset($kepalaSatpam['jabatan']) && $kepalaSatpam['jabatan'] == 'Kepala Shift') {
                $filteredKepalaSatpam[] = $kepalaSatpam;
            }
        }

    return view('admin.satpam.create', ['kepalaSatpamData' => $filteredKepalaSatpam]);
}


}
