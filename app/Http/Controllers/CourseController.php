<?php

namespace App\Http\Controllers;
use Illuminate\Http\RedirectResponse;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::where('teacher_id', auth()->id())->get();
        return view('teacher.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('teacher.courses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'nullable|integer|min:0',
        ]);

        Course::create([
            'teacher_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price ?? 0,
        ]);

        return redirect()
            ->route('teacher.courses.index')
            ->with('success', 'Tạo khóa học thành công');
    }
    public function destroy(Course $course): RedirectResponse
    {
        // (tuỳ chọn) kiểm tra quyền sở hữu
        if ($course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $course->delete();

        return redirect()
            ->route('teacher.courses.index')
            ->with('success', 'Xóa khóa học thành công');
    }
    public function edit(Course $course)
    {
        return view('teacher.courses.edit', compact('course'));
    }
    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image',
            'status' => 'required|in:draft,published',
        ]);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request
                ->file('thumbnail')
                ->store('courses', 'public');
        }

        $course->update($data);

        return redirect()
            ->route('teacher.courses.edit', $course)
            ->with('success', 'Cập nhật khóa học thành công');
    }
}
