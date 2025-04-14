<?php

namespace App\Http\Controllers\manajemen;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Database;
use Illuminate\Http\Request;
use App\Services\FirebaseAuthService;


class ManajemenController extends Controller
{
    protected $firebaseAuth;
    protected $database;
    protected $manajemenRef;
    
    public function __construct(Database $database, FirebaseAuthService $firebaseAuth)
    {
        $this->database = $database;
        $this->firebaseAuth = $firebaseAuth;
        $this->manajemenRef = $this->database->getReference('manajemen');
    }

    public function manajemen() {
        $manajemenData = $this->manajemenRef->getValue();
    
        $manajemenList = [];
        if ($manajemenData) {
            foreach ($manajemenData as $key => $manajemen) {
                $manajemen[] = $manajemen;
            }
        }
    
        return view('admin.manajemen.manajemen', ['manajemenData' => $manajemenList]);
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
            'status' => 'nullable|string',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string',
            'tanggal_bergabung' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'status_pernikahan' => 'required|string',
            'pendidikan_terakhir' => 'required|string',
            'photo' => 'nullable|image|max:2048',
        ]);
    
        $ref = app('firebase.database')->getReference('manajemen');
        $newmanajemenRef = $ref->push();
        $manajemen_id = $newmanajemenRef->getKey();
    
        $fotoProfileUrl = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/manajemen'), $filename);
            $fotoProfileUrl = url('uploads/manajemen/' . $filename);
        }
    
        $manajemenData = [
            'manajemen_id' => $manajemen_id,
            'nama' => $validated['nama'],
            'nip' => $validated['nip'],
            'status' => $validated['status'] ?? 'Aktif',
            'jabatan' => $validated['jabatan'],
            'foto_profile' => $fotoProfileUrl,
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'nomor_telepon' => $validated['nomor_telepon'],
            'alamat' => $validated['alamat'],
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? '',
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? '',
            'tempat_lahir' => $validated['tempat_lahir'] ?? '',
            'tanggal_bergabung' => $validated['tanggal_bergabung'] ?? '',
            'status_pernikahan' => $validated['status_pernikahan'] ?? '',
            'pendidikan_terakhir' => $validated['pendidikan_terakhir'] ?? '',
            'manajemen_id' => $manajemen_id,
            'created_at' => now()->toDateTimeString(),
        ];
    
        $newmanajemenRef->set($manajemenData); 
        
        $firebaseUser = $this->firebaseAuth->registerUserManajemen($validated['email'], $validated['password'], $manajemen_id);
    
        return redirect()->back()->with('success', 'Data Manajemen berhasil ditambahkan!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal menambahkan Manajemen: ' . $e->getMessage());
    }
    
}
public function detail($id)
{
    $manajemenDetail = $this->manajemenRef->getChild($id)->getValue();

    if (!$manajemenDetail) {
        return abort(404, 'Manajemen not found.');
    }

    return view('admin.manajemen.manajemen', ['manajemen' => $manajemenDetail]);
}

public function create() {
    return view('admin.manajemen.create');
}





}

