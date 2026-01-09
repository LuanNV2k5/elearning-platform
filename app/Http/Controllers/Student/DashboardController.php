<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Course;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        /**
         * ===== CONTINUE WATCHING (lesson chưa completed mới nhất) =====
         */
        $continue = DB::table('lesson_user')
            ->join('lessons', 'lessons.id', '=', 'lesson_user.lesson_id')
            ->join('courses', 'courses.id', '=', 'lessons.course_id')
            ->where('lesson_user.user_id', $userId)
            ->where('lesson_user.completed', 0)
            ->where('courses.status', 'published')
            ->orderByDesc('lesson_user.updated_at')
            ->select(
                'courses.id as course_id',
                'courses.title as course_title',
                'lessons.id as lesson_id',
                'lessons.title as lesson_title',
                'lessons.youtube_id'
            )
            ->first();

        /**
         * ===== KHÓA HỌC ĐÃ ENROLL + PROGRESS =====
         */
        $courses = Course::query()
            ->join('course_user', 'course_user.course_id', '=', 'courses.id')
            ->where('course_user.user_id', $userId)
            ->where('courses.status', 'published')
            ->select('courses.*', 'course_user.progress')
            ->with('firstLesson:id,course_id,youtube_id,order')
            ->orderByDesc('course_user.updated_at')
            ->limit(6)
            ->get();

        return view('student.dashboard', compact('continue', 'courses'));
    }
}
