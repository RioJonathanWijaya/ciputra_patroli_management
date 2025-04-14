<?php

namespace App\Http\Controllers\satpam;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Database;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\FirebaseAuthService;

class SatpamController extends Controller
{
    protected $firebaseAuth;
    protected $database;
    protected $satpamRef;
    protected $lokasiRef;
    
    public function __construct(Database $database, FirebaseAuthService $firebaseAuth)
    {
        $this->database = $database;
        $this->firebaseAuth = $firebaseAuth;
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

        $ref = $this->satpamRef;
        $newSatpamRef = $ref->push();
        $satpam_id = $newSatpamRef->getKey();

        $firebaseUser = $this->firebaseAuth->registerUser($validated['email'], $validated['password'], $satpam_id);
    
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
            'nip' => strtoupper($validated['nip']),
            'shift' => (int) $validated['shift'] ?? 0,
            'lokasi_id' => 0,
            'status' => $validated['status'] ?? 0,
            'jabatan' => (int) $validated['jabatan'] ?? 0,
            'foto_profile' => $fotoProfileUrl ?? '',
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'supervisor_id' => $validated['supervisor'] ?? '',
            'penugasan_id' => '',
            'nomor_telepon' => $validated['nomor_telepon'],
            'alamat' => $validated['alamat'],
            'jenis_kelamin' => (int) $validated['jenis_kelamin'] ?? 0,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? '',
            'tempat_lahir' => $validated['tempat_lahir'] ?? '',
            'tanggal_bergabung' => $validated['tanggal_bergabung'] ?? '',
            'status_pernikahan' => (int) $validated['status_pernikahan'] ?? 0,
            'pendidikan_terakhir' => $validated['pendidikan_terakhir'] ?? '',
            'created_at' => now()->toDateTimeString(),
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
            if(isset($kepalaSatpam['jabatan']) && $kepalaSatpam['jabatan'] == 1) {
                $filteredKepalaSatpam[] = $kepalaSatpam;
            }
        }

    return view('admin.satpam.create', ['kepalaSatpamData' => $filteredKepalaSatpam]);
}

}
