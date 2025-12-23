<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        
        if ($user->hasRole('driver')) {
            return view('dashboard.driver');
        }
        
        if ($user->hasRole('dispatcher')) {
            return view('dashboard.dispatcher');
        }
        
        if ($user->hasRole('admin')) {
            return view('dashboard.admin');
        }
        
        return view('dashboard.default');
    }
}
