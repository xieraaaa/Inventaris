<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getDashboard(Request $request) {
        $user = $request->user();
        
        if ($user->hasRole('user')) {
            return view('content.dashboard.user');
        }
        else if ($user->hasRole('admin')) {
            return view('content.dashboard.admin');
        }
    }
}
