<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $title = 'Dashboard';

        $activeMenu = 'dashboard';
        return view('roles.admin.dashboard', compact('activeMenu'));
    }
}
