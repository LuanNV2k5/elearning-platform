<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers    = User::count();
        $totalAdmins   = User::where('role_id', 1)->count();
        $totalTeachers = User::where('role_id', 2)->count();
        $totalStudents = User::where('role_id', 3)->count();

        $totalCourses  = Course::count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'totalTeachers',
            'totalStudents',
            'totalCourses'
        ));
    }
}
