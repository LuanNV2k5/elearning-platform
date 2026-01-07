@extends('layouts.student')

@section('content')
<h3 class="mb-3">📘 {{ $course->title }}</h3>

{{-- ===== PROGRESS CHUNG KHOÁ HỌC ===== --}}
<div class="mt-4 mb-3">
    <div class="progress mb-2">
        <div class="progress-bar"
             style="width: {{ $progress }}%">
            {{ $progress }}%
        </div>
    </div>

    {{-- QUIZ --}}
    @if($progress === 100 && $course->quiz)
        <a href="{{ route('student.courses.quiz.show', $course) }}"
           class="btn btn-success mt-2">
            🧪 Làm bài kiểm tra
        </a>
    @elseif($progress < 100)
        <span class="text-muted d-block mt-2">
            Hoàn thành khoá học để mở bài kiểm tra
        </span>
    @endif
</div>

{{-- ===== DANH SÁCH BÀI HỌC ===== --}}
<ul class="list-group">
@php
    $completedLessonIds = auth()->user()
        ->completedLessons
        ->pluck('id');
@endphp

@foreach ($lessons as $index => $lesson)
    @php
        $prevLesson = $lessons[$index - 1] ?? null;
        $locked = $prevLesson && !$completedLessonIds->contains($prevLesson->id);
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
