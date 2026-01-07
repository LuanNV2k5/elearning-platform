<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index(Course $course)
    {
        $lessons = Lesson::where('course_id', $course->id)
            ->orderBy('order')
            ->get();

        return view('teacher.lessons.index', compact('course', 'lessons'));
    }

    public function create(Course $course)
    {
        return view('teacher.lessons.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required',
            'video_url' => 'nullable|url',
            'pdf' => 'nullable|file|mimes:pdf',
        ]);

        /* ===== PDF ===== */
        $pdfPath = null;
        if ($request->hasFile('pdf')) {
            $pdfPath = $request->file('pdf')->store('lessons', 'public');
        }

        /* ===== YOUTUBE ===== */
        $youtubeId = null;
        if ($request->video_url) {
            $youtubeId = $this->extractYoutubeId($request->video_url);
        }

        Lesson::create([
            'course_id' => $course->id,
            'title' => $request->title,
            'youtube_id' => $youtubeId,
            'pdf_path' => $pdfPath,
            'order' => Lesson::where('course_id', $course->id)->count() + 1,
        ]);

        return redirect()
            ->route('teacher.courses.lessons.index', $course)
            ->with('success', 'Thêm bài học thành công');
    }

    /* =========================================
       HELPER
    ========================================= */

    private function extractYoutubeId($input)
    {
        if (!$input) return null;

        // Nếu nhập thẳng video ID
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $input)) {
            return $input;
        }

        // Các dạng link YouTube phổ biến
        preg_match(
            '/(?:youtube\.com\/(?:.*v=|v\/|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/',
            $input,
            $matches
        );

        return $matches[1] ?? null;
    }
    public function edit(Course $course, Lesson $lesson)
    {
        // đảm bảo lesson thuộc course
        $lesson = $course->lessons()
            ->where('lessons.id', $lesson->id)
            ->firstOrFail();

        return view('teacher.lessons.edit', compact('course', 'lesson'));
    }

    public function update(Request $request, Course $course, Lesson $lesson)
{
    // Đảm bảo lesson thuộc đúng course
    $lesson = $course->lessons()
        ->where('lessons.id', $lesson->id)
        ->firstOrFail();

    // Validate dữ liệu
    $data = $request->validate([
        'title'        => 'required|string|max:255',
        'description'  => 'nullable|string',
        'youtube_id'   => 'nullable|string',
        'pdf'          => 'nullable|file|mimes:pdf',
    ]);

    /* ================= YOUTUBE LINK → VIDEO ID ================= */

    $data['youtube_id'] = $this->extractYoutubeId($request->youtube_id);

    // Nếu có nhập nhưng link sai → báo lỗi
    if ($request->youtube_id && !$data['youtube_id']) {
        return back()
            ->withErrors(['youtube_id' => 'Link YouTube không hợp lệ'])
            ->withInput();
    }

    /* ================= UPLOAD PDF (NẾU CÓ) ================= */

    if ($request->hasFile('pdf')) {
        $data['pdf_path'] = $request
            ->file('pdf')
            ->store('lessons', 'public');
    }

    /* ================= UPDATE DB ================= */

    $lesson->update($data);

    return redirect()
        ->route('teacher.courses.edit', $course)
        ->with('success', '✅ Cập nhật bài học thành công');
}

}
