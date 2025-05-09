<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Database;
use Illuminate\Http\Request;

class SatpamApiController extends Controller
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


    public function getSatpamByUID($uid)
    {
        $satpamDetail = $this->satpamRef->getChild($uid)->getValue();

        if (!$satpamDetail) {
            return response()->json(['message' => 'Satpam not found'], 404);
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

        return response()->json($satpamDetail, 200);
    }
}
