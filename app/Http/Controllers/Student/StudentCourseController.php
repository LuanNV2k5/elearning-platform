<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentCourseController extends Controller
{
    /**
     * Danh sách khóa học đã đăng ký
     */
    public function index()
    {
        $courses = auth()->user()
            ->enrolledCourses()
            ->with('firstLesson:id,course_id,youtube_id,order')
            ->get();

        return view('student.courses.index', compact('courses'));
    }



    /**
     * Chi tiết khóa học + TIẾN ĐỘ (FIX CHUẨN)
     */
    public function show(Course $course)
    {
        $user = Auth::user();

        // 1️⃣ Kiểm tra đã đăng ký khóa học
        if (!$user->enrolledCourses->contains($course->id)) {
            abort(403, 'Bạn chưa đăng ký khóa học này');
        }

        // 2️⃣ Lấy danh sách bài học của course
        $lessons = $course->lessons()
            ->orderBy('order')
            ->get();

        // 👉 LẤY DANH SÁCH lesson_id CỦA COURSE
        $lessonIds = $lessons->pluck('id');

        $totalLessons = $lessonIds->count();

        // 3️⃣ ĐẾM SỐ BÀI USER ĐÃ HOÀN THÀNH (lesson_user)
        $completedLessons = DB::table('lesson_user')
            ->where('user_id', $user->id)
            ->whereIn('lesson_id', $lessonIds)
            ->where('completed', 1)
            ->count();

        // 4️⃣ TÍNH % TIẾN ĐỘ (CHUẨN 100%)
        $courseProgress = $totalLessons > 0
            ? round(($completedLessons / $totalLessons) * 100)
            : 0;

        return view('student.courses.show', compact(
            'course',
            'lessons',
            'courseProgress',
            'completedLessons',
            'totalLessons'
        ));
    }

    /**
     * Danh sách khóa học để khám phá
     */

    public function explore()
    {
        $courses = \App\Models\Course::query()
            ->where('status', 'published') // hoặc sửa theo cột bạn đang dùng
            ->with('firstLesson:id,course_id,youtube_id,order')
            ->get();

        $enrolledIds = auth()->user()
            ->enrolledCourses()
            ->pluck('courses.id')
            ->toArray();

        return view('student.courses.explore', compact('courses', 'enrolledIds'));
    }




    /**
     * Đăng ký khóa học
     */
    public function enroll(Course $course)
    {
        Auth::user()
            ->enrolledCourses()
            ->syncWithoutDetaching($course->id);

        return redirect()
            ->route('student.courses.index')
            ->with('success', 'Đăng ký khóa học thành công');
    }
}
