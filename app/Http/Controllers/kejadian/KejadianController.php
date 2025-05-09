<?php

namespace App\Http\Controllers\kejadian;

use App\Http\Controllers\Controller;
use App\Http\Resources\KejadianResource;
use App\Http\Resources\KejadianCollection;
use Kreait\Firebase\Contract\Database;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Kejadian;
use App\Models\FotoBuktiKejadian;
use App\Models\Tindakan;

class KejadianController extends Controller
{
    protected $database;
    protected $kejadianRef;
    protected $fotoRef;
    protected $tindakanRef;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->kejadianRef = $this->database->getReference('kejadian');
        $this->fotoRef = $this->database->getReference('foto_bukti_kejadian');
        $this->tindakanRef = $this->database->getReference('tindakan');
    }

    public function view(){
        return view('admin.kejadian.kejadian');
    }

    public function show($id)
    {
        try {
            $kejadian = $this->kejadianRef->getChild($id)->getValue();
            
            if (!$kejadian) {
                return redirect()->route('admin.kejadian.kejadian')->with('error', 'Kejadian tidak ditemukan.');
            }

            // Get notifications for this kejadian
            $notifications = $this->database->getReference('notifications')
                ->orderByChild('kejadian_id')
                ->equalTo($id)
                ->getValue();

            // Mark notifications as read
            if ($notifications) {
                foreach ($notifications as $notificationId => $notification) {
                    if (!$notification['read']) {
                        $this->database->getReference('notifications')
                            ->child($notificationId)
                            ->update(['read' => true]);
                    }
                }
            }

            $fotos = $this->getFotoForKejadian($id);
            $tindakan = $this->getTindakanForKejadian($id);

            $kejadianData = array_merge($kejadian, [
                'id' => $id,
                'foto_bukti_kejadian' => $fotos,
                'tindakan' => $tindakan,
            ]);

            return view('admin.kejadian.detail', compact('kejadianData'));
        } catch (\Exception $e) {
            return redirect()->route('admin.kejadian.kejadian')->with('error', 'Gagal memuat detail kejadian: ' . $e->getMessage());
        }
    }

    protected function getTindakanForKejadian($kejadianId)
    {
        $tindakanRef = $this->database->getReference('tindakan')->getValue() ?? [];
    
        $filtered = array_filter($tindakanRef, function ($item) use ($kejadianId) {
            return isset($item['kejadian_id']) && $item['kejadian_id'] === $kejadianId;
        });
    
        return array_values($filtered);
    }
    

    

    public function index()
    {
        try {
            $kejadianData = $this->kejadianRef->getValue() ?? [];
            $fotoData = $this->fotoRef->getValue() ?? [];
    
            $result = [];
            foreach ($kejadianData as $kejadianId => $kejadian) {
                $fotos = array_filter($fotoData, function($foto) use ($kejadianId) {
                    return ($foto['kejadian_id'] ?? null) === $kejadianId;
                });
    
                $resource = new \App\Http\Resources\KejadianResource((object)[
                    'id' => $kejadianId,
                    'data' => $kejadian,
                    'foto_bukti_kejadian' => array_column($fotos, 'url')
                ]);
    
                $result[] = $resource->toArray(request());
            }
    
            return response()->json(['data' => $result]);
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch kejadian data',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    

    protected function getFotoForKejadian($kejadianId)
    {
        $fotoData = $this->fotoRef->getValue() ?? [];
        $fotos = array_filter($fotoData, function($foto) use ($kejadianId) {
            return ($foto['kejadian_id'] ?? null) === $kejadianId;
        });
        
        return array_column($fotos, 'url');
    }

    public function storeTindakan(Request $request)
{
    $request->validate([
        'kejadian_id' => 'required|string',
        'tindakan' => 'required|string',
        'status' => 'nullable|integer|in:0,1,2'
    ]);

    $user = session('firebase_user');
    if (!$user || !isset($user['uid'])) {
        return response()->json(['error' => 'Unauthorized. User not found in session.'], 401);
    }

    $tindakanId = (string) Str::uuid();
    $manajemenId = $user['uid'];
    $kejadianId = $request->input('kejadian_id');
    $tindakanText = $request->input('tindakan');
    $waktuTindakan = Carbon::now()->toDateTimeString();
    $status = $request->input('status');

    $data = [
        'tindakan_id' => $tindakanId,
        'manajemen_id' => $manajemenId,
        'kejadian_id' => $kejadianId,
        'tindakan' => $tindakanText,
        'waktu_tindakan' => $waktuTindakan,
        'created_at' => now()->toDateTimeString(),
    ];

    $statusLabels = [
        0 => 'Baru',
        1 => 'Proses', 
        2 => 'Selesai'
    ];

    $selectedStatus = $statusLabels[$status] ?? 'Baru';

    $this->kejadianRef->getChild($kejadianId)->update([
        'status' => $selectedStatus,
        'waktu_selesai' => now()->toDateTimeString()
    ]);

    try {
        $this->database->getReference("tindakan/{$tindakanId}")->set($data);

        return redirect()->route('admin.kejadian.kejadian')->with('success', 'Tindakan berhasil disimpan.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal menyimpan tindakan: ' . $e->getMessage());
    }
}

public function delete($id)
{
    try {
        $kejadian = $this->kejadianRef->getChild($id)->getValue();

        if (!$kejadian) {
            return response()->json([
                'error' => 'Not Found',
                'message' => 'Kejadian not found.'
            ], 404);
        }

        $this->kejadianRef->getChild($id)->remove();

        $tindakanRef = $this->database->getReference('tindakan');
        $allTindakan = $tindakanRef->getValue();

        if ($allTindakan) {
            foreach ($allTindakan as $key => $tindakan) {
                if (isset($tindakan['kejadian_id']) && $tindakan['kejadian_id'] === $id) {
                    $tindakanRef->getChild($key)->remove();
                }
            }
        }

return redirect()->route('admin.kejadian.kejadian')->with('success', 'Kejadian berhasil dihapus.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal menghapus kejadian: ' . $e->getMessage());
    }
}

public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'nama_kejadian' => 'required|string',
            'lokasi_kejadian' => 'required|string',
            'tanggal_kejadian' => 'required|date',
            'tipe_kejadian' => 'required|string',
            'keterangan' => 'required|string',
            'nama_korban' => 'nullable|string',
            'alamat_korban' => 'nullable|string',
            'keterangan_korban' => 'nullable|string',
            'status' => 'required|string',
            'satpam_id' => 'required|string',
            'foto_bukti' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $kejadianId = (string) Str::uuid();

        $fotoBuktiUrl = null;
        if ($request->hasFile('foto_bukti')) {
            $file = $request->file('foto_bukti');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            $uploadPath = public_path('uploads/kejadian');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            if ($file->move($uploadPath, $filename)) {
                // Generate the URL for the uploaded file
                $fotoBuktiUrl = url('uploads/kejadian/' . $filename);

                $fotoData = [
                    'kejadian_id' => $kejadianId,
                    'url' => $fotoBuktiUrl,
                    'created_at' => now()->toDateTimeString()
                ];
                $this->fotoRef->push($fotoData);
            } else {
                throw new \Exception('Failed to upload photo');
            }
        }

        $user = session('firebase_user');
        $manajemenId = $user['uid'] ?? null;

        if (!$manajemenId) {
            throw new \Exception('User not found in session');
        }

        $kejadianData = [
            'id' => $kejadianId,
            'nama_kejadian' => $validated['nama_kejadian'],
            'lokasi_kejadian' => $validated['lokasi_kejadian'],
            'tanggal_kejadian' => $validated['tanggal_kejadian'],
            'tipe_kejadian' => $validated['tipe_kejadian'],
            'keterangan' => $validated['keterangan'],
            'nama_korban' => $validated['nama_korban'] ?? null,
            'alamat_korban' => $validated['alamat_korban'] ?? null,
            'keterangan_korban' => $validated['keterangan_korban'] ?? null,
            'status' => $validated['status'],
            'satpam_id' => $validated['satpam_id'],
            'foto_bukti_kejadian' => $fotoBuktiUrl,
            'manajemen_id' => $manajemenId,
            'is_notifikasi' => true,
            'is_pencurian' => false,
            'is_kecelakaan' => false,
            'waktu_laporan' => now()->toDateTimeString(),
            'waktu_selesai' => null,
            'created_at' => now()->toDateTimeString(),
        ];

        $this->kejadianRef->getChild($kejadianId)->set($kejadianData);

        return redirect()->route('admin.kejadian.kejadian')->with('success', 'Data Kejadian berhasil ditambahkan!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal menambahkan Kejadian: ' . $e->getMessage());
    }
}

public function create(){
    return view('admin.kejadian.create');
}

public function edit($id)
{
    try {
        $kejadian = $this->kejadianRef->getChild($id)->getValue();
        
        if (!$kejadian) {
            return redirect()->route('admin.kejadian.kejadian')->with('error', 'Kejadian tidak ditemukan');
        }

        $fotos = $this->getFotoForKejadian($id);
        $kejadian['foto_bukti_kejadian'] = $fotos;
        $kejadian['id'] = $id;

        return view('admin.kejadian.edit', compact('kejadian'));
    } catch (\Exception $e) {
        return redirect()->route('admin.kejadian.kejadian')->with('error', 'Gagal memuat data kejadian: ' . $e->getMessage());
    }
}

public function update(Request $request, $id)
{
    try {
        $validated = $request->validate([
            'nama_kejadian' => 'required|string',
            'lokasi_kejadian' => 'required|string',
            'tanggal_kejadian' => 'required|date',
            'tipe_kejadian' => 'required|string',
            'keterangan' => 'required|string',
            'nama_korban' => 'nullable|string',
            'alamat_korban' => 'nullable|string',
            'keterangan_korban' => 'nullable|string',
            'status' => 'required|string',
            'satpam_id' => 'required|string',
            'foto_bukti' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $kejadianData = [
            'nama_kejadian' => $validated['nama_kejadian'],
            'lokasi_kejadian' => $validated['lokasi_kejadian'],
            'tanggal_kejadian' => $validated['tanggal_kejadian'],
            'tipe_kejadian' => $validated['tipe_kejadian'],
            'keterangan' => $validated['keterangan'],
            'nama_korban' => $validated['nama_korban'] ?? null,
            'alamat_korban' => $validated['alamat_korban'] ?? null,
            'keterangan_korban' => $validated['keterangan_korban'] ?? null,
            'status' => $validated['status'],
            'satpam_id' => $validated['satpam_id'],
            'updated_at' => now()->toDateTimeString(),
        ];

        if ($request->hasFile('foto_bukti')) {
            $file = $request->file('foto_bukti');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            $uploadPath = public_path('uploads/kejadian');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            if ($file->move($uploadPath, $filename)) {
                $fotoBuktiUrl = url('uploads/kejadian/' . $filename);
                $kejadianData['foto_bukti_kejadian'] = $fotoBuktiUrl;

                $fotoData = [
                    'kejadian_id' => $id,
                    'url' => $fotoBuktiUrl,
                    'created_at' => now()->toDateTimeString()
                ];
                $this->fotoRef->push($fotoData);
            }
        }

        $this->kejadianRef->getChild($id)->update($kejadianData);

        return redirect()->route('admin.kejadian.kejadian')->with('success', 'Data Kejadian berhasil diperbarui!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal memperbarui Kejadian: ' . $e->getMessage());
    }
}

}