<?php

namespace App\Http\Controllers\kejadian;

use App\Http\Controllers\Controller;
use App\Http\Resources\KejadianResource;
use App\Http\Resources\KejadianCollection;
use Kreait\Firebase\Contract\Database;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            $user = session('firebase_user');
            $manajemenId = $user['uid'] ?? null;
    
            if (!$manajemenId) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => 'User not found in session.'
                ], 401);
            }
    
            $kejadian = $this->kejadianRef->getChild($id)->getValue();
            if (!$kejadian) {
                throw new \Exception('Kejadian not found.');
            }
    

            $fotos = $this->getFotoForKejadian($id);

            $tindakan = $this->getTindakanForKejadian($id);
    
            $kejadianData = [
                'id' => $id,
                'data' => $kejadian,
                'foto_bukti_kejadian' => $fotos,
                'tindakan' => $tindakan,
            ];
            return new KejadianResource((object)$kejadianData);
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Kejadian not found',
                'message' => $e->getMessage()
            ], 404);
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

    $data = [
        'tindakan_id' => $tindakanId,
        'manajemen_id' => $manajemenId,
        'kejadian_id' => $kejadianId,
        'tindakan' => $tindakanText,
        'waktu_tindakan' => $waktuTindakan,
    ];

    try {
        $this->database->getReference("tindakan/{$tindakanId}")->set($data);

        return redirect()->route('admin.kejadian.kejadian')->with('success', 'Tindakan berhasil disimpan.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal menyimpan tindakan: ' . $e->getMessage());
    }
}


}