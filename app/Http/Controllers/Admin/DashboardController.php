<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marga;

class DashboardController extends Controller
{
    public function index()
    {
        session(['activeMenu' => 'dashboard']);
        session(['activeParentMenu' => '']);
        session(['activeSubParentMenu' => '']);

        // Gather statistics for dashboard
        $stats = [
            'total_margas' => Marga::count(),
            // 'total_people' => Person::count(),
            // 'total_marriages' => Marriage::count(),
            // 'total_family_groups' => FamilyGroup::count(),
            // 'male_count' => Person::where('gender', 'male')->count(),
            // 'female_count' => Person::where('gender', 'female')->count(),
        ];
        
        // Recent additions
        // $recentPeople = Person::latest()->take(5)->get();
        // $recentMarriages = Marriage::with(['husband', 'wife'])->latest()->take(5)->get();

        return view('admin.pages.dashboard', [
            'stats'             => $stats,
            // 'recentPeople'      => $recentPeople,
            // 'recentMarriages'   => $recentMarriages
        ]);
    }
}
