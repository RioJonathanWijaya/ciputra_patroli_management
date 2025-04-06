<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {

        $firebaseUser = $request->cookie('firebase_user');

        if ($firebaseUser) {
            $firebaseUserData = json_decode($firebaseUser, true); 
            return view('admin.dashboard', compact('firebaseUserData')); 
        }

        return redirect('/login');
    }

    
}
