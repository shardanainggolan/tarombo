<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        session(['activeMenu' => 'dashboard']);
        session(['activeParentMenu' => '']);
        session(['activeSubParentMenu' => '']);

        return view('admin.pages.dashboard', [
            
        ]);
    }
}
