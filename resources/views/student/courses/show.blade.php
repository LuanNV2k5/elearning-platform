@extends('layouts.student')

@section('content')
<h3 class="mb-3">📘 {{ $course->title }}</h3>

{{-- ===== PROGRESS CHUNG KHOÁ HỌC ===== --}}
@php
    // Xác định màu progress
    if ($courseProgress === 100) {
        $progressClass = 'bg-success';
    } elseif ($courseProgress >= 50) {
        $progressClass = 'bg-info';
    } else {
        $progressClass = 'bg-warning';
    }

    // Lần làm quiz gần nhất (nếu có)
    $latestAttempt = auth()->user()
        ->quizAttempts()
        ->whereHas('quiz', fn($q) => $q->where('course_id', $course->id))
        ->latest()
        ->first();
@endphp

<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title mb-3">📊 Tiến độ khóa học</h5>

        <div class="progress mb-2" style="height: 22px;">
            <div class="progress-bar {{ $progressClass }}"
                 role="progressbar"
                 style="width: {{ $courseProgress }}%;"
                 aria-valuenow="{{ $courseProgress }}"
                 aria-valuemin="0"
                 aria-valuemax="100">
                {{ $courseProgress }}%
            </div>
        </div>

        <small class="text-muted d-block mb-2">
            Đã hoàn thành {{ $completedLessons }} / {{ $totalLessons }} bài học
        </small>

        {{-- ===== TRẠNG THÁI QUIZ / KHÓA HỌC ===== --}}
        <div class="mt-2">
            {{-- ĐÃ PASS QUIZ --}}
            @if($latestAttempt && $latestAttempt->status === 'passed')
                <span class="badge bg-success fs-6">
                    🎉 Bạn đã hoàn thành khóa học
                </span>

            {{-- ĐÃ LÀM QUIZ NHƯNG FAIL --}}
            @elseif($latestAttempt && $latestAttempt->status === 'failed')
                <span class="badge bg-danger fs-6 d-block mb-2">
                    ❌ Chưa đạt 50% bài kiểm tra
                </span>

                <a href="{{ route('student.courses.quiz.show', $course) }}"
                   class="btn btn-warning">
                    🔁 Làm lại bài kiểm tra
                </a>

            {{-- CHƯA LÀM QUIZ --}}
            @else
                @if($courseProgress === 100 && $course->quiz)
                    <a href="{{ route('student.courses.quiz.show', $course) }}"
                       class="btn btn-success">
                        🧪 Làm bài kiểm tra
                    </a>
                @elseif($courseProgress < 100)
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
@php
    // Các lesson user đã từng mở
    $openedLessonIds = auth()->user()
        ->lessons
        ->pluck('id');

    // Các lesson đã hoàn thành
    $completedLessonIds = auth()->user()
        ->completedLessons
        ->pluck('id');
@endphp

@foreach ($lessons as $index => $lesson)
    @php
        $prevLesson = $lessons[$index - 1] ?? null;

        // 🔑 Rule: chỉ cần MỞ bài trước là mở bài sau
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
