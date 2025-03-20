<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PatrolRoutingController extends Controller
{
    public function getRoute(Request $request)
    {

        $profile = $request->input('profile', 'driving-car');

        $response = Http::withHeaders([
            'Authorization' => env('ORS_API_KEY'),
            'Content-Type' => 'application/json'
        ])->post('https://api.openrouteservice.org/v2/directions/driving-car/geojson', [
            'coordinates' => $request->input('coordinates')
        ]);

        return response()->json($response->json(), $response->status());
    }
}

