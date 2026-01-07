<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;

class CourseController extends Controller
{
    public function index()
    {
        // ADMIN: xem tất cả khóa học + giáo viên + số người học
        $courses = Course::with('teacher')
            ->withCount('students')   // ✅ DÒNG QUYẾT ĐỊNH
            ->get();

        return view('admin.courses.index', compact('courses'));
    }

    public function destroy(Course $course): RedirectResponse
    {
        // ADMIN: có quyền xóa mọi khóa học
        $course->delete();

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Xóa khóa học thành công');
    }
}
