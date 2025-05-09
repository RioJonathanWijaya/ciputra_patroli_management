<?php

namespace App\Http\Controllers\satpam;

use App\Http\Controllers\Controller;
use App\Enums\ShiftEnum;
use App\Enums\JabatanEnum;
use Kreait\Firebase\Contract\Database;

class KepalaSatpamController extends Controller
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

    public function kepala_satpam()
    {
        $satpamData = $this->satpamRef->getValue();
    
        $satpamList = [];
        if ($satpamData) {
            foreach ($satpamData as $key => $satpam) {
                if ((int)$satpam['jabatan'] === JabatanEnum::KEPALA_SATPAM->value) {
                    $satpam['shift_text'] = ShiftEnum::getLabelByValue((int)$satpam['shift']);
                    $satpam['jabatan_text'] = JabatanEnum::getLabelByValue((int)$satpam['jabatan']);
                    $satpamList[] = $satpam;
                }
            }
        }
    
        return view('admin.kepala_satpam.kepala_satpam', ['satpamData' => $satpamList]);
    }

    public function detail($id)
    {
        $satpamDetail = $this->satpamRef->getChild($id)->getValue();

        if (!$satpamDetail) {
            return abort(404, 'Satpam not found.');
        }

        if ((int)$satpamDetail['jabatan'] !== JabatanEnum::KEPALA_SATPAM->value) {
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
        $satpamDetail['shift_text'] = ShiftEnum::getLabelByValue((int)$satpamDetail['shift']);
        $satpamDetail['jabatan_text'] = JabatanEnum::getLabelByValue((int)$satpamDetail['jabatan']);
        

        return view('admin.kepala_satpam.detail', ['satpam' => $satpamDetail]);
    }
}
