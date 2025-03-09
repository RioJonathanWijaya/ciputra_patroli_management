<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {

        $firebaseUser = $request->cookie('firebase_user');

        if ($firebaseUser) {
            $firebaseUserData = json_decode($firebaseUser, true); // Convert the JSON string to an array
            return view('admin.dashboard', compact('firebaseUserData'));  // Pass the array as a variable
        }

        return redirect('/login');
    }

    
}
