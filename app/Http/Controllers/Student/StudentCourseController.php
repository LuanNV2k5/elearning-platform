<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class StudentCourseController extends Controller
{
    /**
     * Danh sách khóa học đã đăng ký
     */
    public function index()
    {
        $courses = Auth::user()->enrolledCourses;

        return view('student.courses.index', compact('courses'));
    }

    /**
     * Chi tiết khóa học + TIẾN ĐỘ (PHẦN E)
     */
    public function show(Course $course)
    {
        $user = Auth::user();

        // 1️⃣ Kiểm tra đã đăng ký khóa học
        if (!$user->enrolledCourses->contains($course->id)) {
            abort(403, 'Bạn chưa đăng ký khóa học này');
        }

        // 2️⃣ Lấy danh sách bài học
        $lessons = $course->lessons()
            ->orderBy('order')
            ->get();

        $totalLessons = $lessons->count();

        // 3️⃣ Đếm số bài đã hoàn thành (lesson_user)
        $completedLessons = $course->lessons()
            ->whereHas('students', function ($q) use ($user) {
                $q->where('users.id', $user->id)
                  ->where('completed', true);
            })
            ->count();

        // 4️⃣ Tính % tiến độ khóa học
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
        $courses = Course::all();

        return view('student.courses.explore', compact('courses'));
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
