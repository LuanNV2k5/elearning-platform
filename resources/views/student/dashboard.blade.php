@extends('layouts.student')

@section('content')
<h3 class="mb-4">👋 Chào mừng bạn quay lại</h3>

{{-- ===== CONTINUE WATCHING ===== --}}
@if($continue)
<div class="card mb-4 border-primary">
    <div class="row g-0">
        <div class="col-md-4">
            @if($continue->youtube_id)
                <img
                    src="https://img.youtube.com/vi/{{ $continue->youtube_id }}/hqdefault.jpg"
                    class="img-fluid rounded-start"
                    style="height:100%;object-fit:cover"
                >
            @endif
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <h5 class="card-title">▶ Tiếp tục học</h5>
                <p class="mb-1"><strong>{{ $continue->course_title }}</strong></p>
                <p class="text-muted">{{ $continue->lesson_title }}</p>

                <a href="{{ route('student.lessons.show', [$continue->course_id, $continue->lesson_id]) }}"
                   class="btn btn-primary">
                    Tiếp tục
                </a>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ===== KHÓA HỌC CỦA TÔI ===== --}}
<h4 class="mb-3">📚 Khóa học của tôi</h4>

<div class="row">
@forelse($courses as $course)
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">

            {{-- Thumbnail --}}
            @if($course->firstLesson && $course->firstLesson->youtube_id)
                <img
                    src="https://img.youtube.com/vi/{{ $course->firstLesson->youtube_id }}/hqdefault.jpg"
                    class="card-img-top"
                    style="height:200px;object-fit:cover"
                >
            @endif

            <div class="card-body d-flex flex-column">
                <h5 class="card-title">{{ $course->title }}</h5>

                {{-- Progress --}}
                <div class="progress mb-2" style="height:18px">
                    <div class="progress-bar bg-success"
                         style="width: {{ (int)$course->progress }}%">
                        {{ (int)$course->progress }}%
                    </div>
                </div>

                <a href="{{ route('student.courses.show', $course) }}"
                   class="btn btn-outline-primary mt-auto">
                    Vào học
                </a>
            </div>
        </div>
    </div>
@empty
    <p class="text-muted">Bạn chưa đăng ký khóa học nào.</p>
@endforelse
</div>
@endsection
