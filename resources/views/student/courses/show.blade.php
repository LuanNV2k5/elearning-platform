@extends('layouts.student')

@section('content')
<h3 class="mb-3">📘 {{ $course->title }}</h3>

@php
    // ==== chống undefined ====
    $completedLessons = (int) ($completedLessons ?? 0);
    $totalLessons = (int) ($totalLessons ?? 0);
    $openedLessonIds = $openedLessonIds ?? collect();
    $completedLessonIds = $completedLessonIds ?? collect();

    // ==== TÍNH PROGRESS CHUẨN TỪ DB (completed/total) ====
    // Ưu tiên tính từ completedLessons/totalLessons để khỏi lệ thuộc course_user.progress
    if ($totalLessons > 0) {
        $computedProgress = (int) round(($completedLessons / $totalLessons) * 100);
    } else {
        $computedProgress = 0;
    }

    // Nếu controller có truyền $courseProgress thì vẫn dùng,
    // nhưng nếu nó sai (ví dụ 0) mà computedProgress > 0 => lấy computedProgress
    $courseProgress = (int) ($courseProgress ?? $computedProgress);
    if ($computedProgress > $courseProgress) $courseProgress = $computedProgress;

    if ($courseProgress < 0) $courseProgress = 0;
    if ($courseProgress > 100) $courseProgress = 100;

    // ==== màu progress ====
    if ($courseProgress === 100) {
        $progressClass = 'bg-success';
    } elseif ($courseProgress >= 50) {
        $progressClass = 'bg-info';
    } elseif ($courseProgress > 0) {
        $progressClass = 'bg-warning';
    } else {
        // 0% thì để xám cho khỏi "mất màu"
        $progressClass = 'bg-secondary';
    }
@endphp

<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title mb-3">📊 Tiến độ khóa học</h5>

        <div class="progress mb-2" style="height: 22px;">
            <div
                class="progress-bar {{ $progressClass }} progress-bar-striped
                    @if($courseProgress > 0 && $courseProgress < 100) progress-bar-animated @endif"
                role="progressbar"
                style="width: {{ $courseProgress }}%;"
                aria-valuenow="{{ $courseProgress }}"
                aria-valuemin="0"
                aria-valuemax="100"
            >
                {{ $courseProgress }}%
            </div>
        </div>

        <small class="text-muted d-block mb-2">
            Đã hoàn thành {{ $completedLessons }} / {{ $totalLessons }} bài học
        </small>

        {{-- ===== TRẠNG THÁI QUIZ / KHÓA HỌC ===== --}}
        <div class="mt-2">
            @if($latestAttempt && ($latestAttempt->status ?? null) === 'passed')
                <span class="badge bg-success fs-6">
                    🎉 Bạn đã hoàn thành khóa học
                </span>

            @elseif($latestAttempt && ($latestAttempt->status ?? null) === 'failed')
                <span class="badge bg-danger fs-6 d-block mb-2">
                    ❌ Chưa đạt yêu cầu bài kiểm tra
                </span>

                <a href="{{ route('student.courses.quiz.show', $course) }}"
                   class="btn btn-warning">
                    🔁 Làm lại bài kiểm tra
                </a>

            @else
                @if($courseProgress >= 100 && !empty($course->quiz))
                    <a href="{{ route('student.courses.quiz.show', $course) }}"
                       class="btn btn-success">
                        🧪 Làm bài kiểm tra
                    </a>
                @elseif($courseProgress < 100 && !empty($course->quiz))
                    <span class="text-muted">
                        Hoàn thành 100% khóa học để mở bài kiểm tra
                    </span>
                @endif
            @endif
        </div>
    </div>
</div>

{{-- ===== DANH SÁCH BÀI HỌC ===== --}}
<ul class="list-group">
@foreach ($lessons as $index => $lesson)
    @php
        $prevLesson = $lessons[$index - 1] ?? null;

        // Rule: chỉ cần MỞ bài trước là mở bài sau
        $locked = $prevLesson && !$openedLessonIds->contains($prevLesson->id);

        $completed = $completedLessonIds->contains($lesson->id);
    @endphp

    <li class="list-group-item d-flex justify-content-between align-items-center">
        <div>
            @if($locked)
                <span class="text-muted">
                    🔒 {{ $lesson->title }}
                </span>
            @else
                <a href="{{ route('student.lessons.show', [$course, $lesson]) }}">
                    ▶ {{ $lesson->title }}
                </a>
            @endif
        </div>

        @if($completed)
            <span class="badge bg-success">✔</span>
        @endif
    </li>
@endforeach
</ul>
@endsection
