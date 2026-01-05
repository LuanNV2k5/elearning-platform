<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

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

        $coursesByTeacher = User::where('role_id', 2)
            ->leftJoin('courses', 'users.id', '=', 'courses.teacher_id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('COUNT(courses.id) as courses_count')
            )
            ->groupBy('users.id', 'users.name', 'users.email')
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'totalTeachers',
            'totalStudents',
            'totalCourses',
            'coursesByTeacher'
        ));
    }
}
