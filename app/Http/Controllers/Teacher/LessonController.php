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

    private function extractYoutubeId(string $url): ?string
    {
        preg_match(
            '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/|youtube\.com/shorts/)([^"&?/ ]{11})%i',
            $url,
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
        $lesson = $course->lessons()
            ->where('lessons.id', $lesson->id)
            ->firstOrFail();

        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'youtube_id'   => 'nullable|string',
            'pdf'          => 'nullable|file|mimes:pdf',
        ]);

        // upload pdf mới
        if ($request->hasFile('pdf')) {
            $data['pdf_path'] = $request
                ->file('pdf')
                ->store('lessons', 'public');
        }

        $lesson->update($data);

        return redirect()
            ->route('teacher.courses.edit', $course)
            ->with('success', '✅ Cập nhật bài học thành công');
    }
}
