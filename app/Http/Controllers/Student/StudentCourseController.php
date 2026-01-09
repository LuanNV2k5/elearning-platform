<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentCourseController extends Controller
{
    private function logEvent(
        string $eventType,
        ?int $courseId = null,
        ?int $lessonId = null,
        ?int $watchSeconds = null,
        int $dedupeMinutes = 5
    ): void {
        $userId = Auth::id();
        if (!$userId) return;

        $exists = DB::table('user_events')
            ->where('user_id', $userId)
            ->where('event_type', $eventType)
            ->when($courseId !== null, fn($q) => $q->where('course_id', $courseId), fn($q) => $q->whereNull('course_id'))
            ->when($lessonId !== null, fn($q) => $q->where('lesson_id', $lessonId), fn($q) => $q->whereNull('lesson_id'))
            ->where('created_at', '>=', now()->subMinutes($dedupeMinutes))
            ->exists();

        if ($exists) return;

        DB::table('user_events')->insert([
            'user_id' => $userId,
            'event_type' => $eventType,
            'course_id' => $courseId,
            'lesson_id' => $lessonId,
            'watch_seconds' => $watchSeconds,
            'created_at' => now(),
        ]);
    }

    public function index()
    {
        $userId = Auth::id();

        $enrolledIds = Auth::user()
            ->enrolledCourses()
            ->pluck('courses.id')
            ->toArray();

        $courses = Auth::user()
            ->enrolledCourses()
            ->with('firstLesson:id,course_id,youtube_id,order')
            ->get();

        // ===== Up next (ưu tiên theo khóa vừa xem) =====
        $lastCourseId = DB::table('user_events')
            ->where('user_id', $userId)
            ->whereIn('event_type', ['view_lesson', 'view_course'])
            ->whereNotNull('course_id')
            ->orderByDesc('created_at')
            ->value('course_id');

        $recommendedIds = DB::table('recommend_results')
            ->where('user_id', $userId)
            ->orderBy('rank_no')
            ->pluck('course_id')
            ->toArray();

        $candidateIds = array_values(array_diff($recommendedIds, $enrolledIds));

        $relatedIds = [];
        if (!empty($lastCourseId)) {
            $userIdsAlso = DB::table('user_events')
                ->where('course_id', $lastCourseId)
                ->whereIn('event_type', ['view_lesson', 'view_course', 'enroll'])
                ->pluck('user_id')
                ->unique()
                ->toArray();

            if (!empty($userIdsAlso)) {
                $relatedIds = DB::table('user_events')
                    ->select('course_id', DB::raw('COUNT(*) as cnt'))
                    ->whereIn('user_id', $userIdsAlso)
                    ->whereIn('event_type', ['view_lesson', 'view_course', 'enroll'])
                    ->whereNotNull('course_id')
                    ->where('course_id', '!=', $lastCourseId)
                    ->groupBy('course_id')
                    ->orderByDesc('cnt')
                    ->limit(20)
                    ->pluck('course_id')
                    ->toArray();

                $relatedIds = array_values(array_intersect($relatedIds, $candidateIds));
            }
        }

        $orderedNextIds = [];
        foreach ($relatedIds as $cid) {
            if (!in_array($cid, $orderedNextIds)) $orderedNextIds[] = $cid;
        }
        foreach ($candidateIds as $cid) {
            if (!in_array($cid, $orderedNextIds)) $orderedNextIds[] = $cid;
        }

        $orderedNextIds = array_slice($orderedNextIds, 0, 8);

        $nextCourses = collect();
        if (!empty($orderedNextIds)) {
            $nextCourses = Course::query()
                ->where('status', 'published')
                ->whereIn('id', $orderedNextIds)
                ->with('firstLesson:id,course_id,youtube_id,order')
                ->get()
                ->sortBy(fn($c) => array_search($c->id, $orderedNextIds))
                ->values();
        }

        $lastCourseTitle = null;
        if (!empty($lastCourseId)) {
            $lastCourseTitle = Course::where('id', $lastCourseId)->value('title');
        }

        return view('student.courses.index', compact(
            'courses',
            'nextCourses',
            'enrolledIds',
            'lastCourseId',
            'lastCourseTitle'
        ));
    }

    public function explore()
    {
        $userId = Auth::id();

        $enrolledIds = Auth::user()
            ->enrolledCourses()
            ->pluck('courses.id')
            ->toArray();

        $recommendedIds = DB::table('recommend_results')
            ->where('user_id', $userId)
            ->orderBy('rank_no')
            ->pluck('course_id')
            ->toArray();

        $recommendedCourses = collect();
        if (!empty($recommendedIds)) {
            $recommendedCourses = Course::query()
                ->where('status', 'published')
                ->whereIn('id', $recommendedIds)
                ->with('firstLesson:id,course_id,youtube_id,order')
                ->get()
                ->sortBy(fn($c) => array_search($c->id, $recommendedIds))
                ->values();
        }

        $courses = Course::query()
            ->where('status', 'published')
            ->when(!empty($recommendedIds), fn($q) => $q->whereNotIn('id', $recommendedIds))
            ->with('firstLesson:id,course_id,youtube_id,order')
            ->get();

        return view('student.courses.explore', compact('recommendedCourses', 'courses', 'enrolledIds'));
    }

    public function show(Course $course)
    {
        if (($course->status ?? null) !== 'published') {
            abort(404);
        }

        $this->logEvent('view_course', $course->id);

        // lessons theo order
        $lessons = $course->lessons()->orderBy('order')->get();

        // progress tổng: lấy từ course_user.progress (0..100)
        $courseProgress = (int) (DB::table('course_user')
            ->where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->value('progress') ?? 0);

        if ($courseProgress < 0) $courseProgress = 0;
        if ($courseProgress > 100) $courseProgress = 100;

        // total lessons
        $totalLessons = $lessons->count();

        // completed lessons: dựa lesson_user.completed = 1
        $completedLessons = (int) (DB::table('lesson_user')
            ->where('user_id', Auth::id())
            ->whereIn('lesson_id', $lessons->pluck('id')->toArray())
            ->where('completed', 1)
            ->count());

        // lesson đã mở (chỉ cần có record trong lesson_user là coi như opened)
        $openedLessonIds = DB::table('lesson_user')
            ->where('user_id', Auth::id())
            ->whereIn('lesson_id', $lessons->pluck('id')->toArray())
            ->pluck('lesson_id');

        // lesson đã hoàn thành
        $completedLessonIds = DB::table('lesson_user')
            ->where('user_id', Auth::id())
            ->whereIn('lesson_id', $lessons->pluck('id')->toArray())
            ->where('completed', 1)
            ->pluck('lesson_id');

        // quiz attempt gần nhất (đúng theo quiz_id)
        $quizId = DB::table('quizzes')->where('course_id', $course->id)->value('id');
        $latestAttempt = null;
        if ($quizId) {
            $latestAttempt = DB::table('quiz_attempts')
                ->where('user_id', Auth::id())
                ->where('quiz_id', $quizId)
                ->orderByDesc('created_at')
                ->first();
        }

        // load quiz relation (nếu view check $course->quiz)
        $course->load('quiz');

        return view('student.courses.show', compact(
            'course',
            'lessons',
            'courseProgress',
            'completedLessons',
            'totalLessons',
            'latestAttempt',
            'openedLessonIds',
            'completedLessonIds'
        ));
    }

    public function enroll(Course $course)
    {
        if (($course->status ?? null) !== 'published') {
            return redirect()->back()->with('error', 'Khóa học chưa được mở.');
        }

        Auth::user()->enrolledCourses()->syncWithoutDetaching([$course->id]);

        $this->logEvent('enroll', $course->id);

        return redirect()->back()->with('success', 'Đăng ký thành công!');
    }
}
