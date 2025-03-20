<?php

namespace App\Http\Controllers\satpam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Kreait\Firebase\Contract\Database;

class KepalaSatpamController extends Controller
{
    protected $database;
    protected $tableName = 'satpam';

    public function __construct(Database $database)
    {
        $this->database = $database;
    }
    public function kepala_satpam() {
        $kepalaSatpamRef = $this->database->getReference('satpam');
        $kepalaSatpamData = $kepalaSatpamRef->getValue();
    
        $filteredKepalaSatpam = [];

        if($kepalaSatpamData) {
            foreach($kepalaSatpamData as $key => $kepalaSatpam){
                if(isset($kepalaSatpam['jabatan']) && $kepalaSatpam['jabatan'] == "Kepala Shift") {
                    $filteredKepalaSatpam[] = $kepalaSatpam;
                }
            }
        }
    
    
        return view('admin.kepala_satpam.kepala_satpam', ['satpamData' => $filteredKepalaSatpam]);
    }

    public function detail($id)
{
    $firebaseUrl = config('firebase.projects.app.database.url');
    
    $satpamResponse = Http::get("{$firebaseUrl}/satpam/{$id}.json");

    if (!$satpamResponse->successful()) {
        return abort(500, 'Error fetching satpam data from Firebase.');
    }

    $satpam = $satpamResponse->json();
    

    if (!$satpam) {
        return abort(404, 'Satpam not found.');
    }

    return view('admin.kepala_satpam.detail', compact('satpam'));
}
}
