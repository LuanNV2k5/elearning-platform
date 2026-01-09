@extends('layouts.student')

@section('content')
    <h3 class="mb-3">🔍 Khám phá khóa học</h3>

    @if($courses->count() === 0)
        <div class="alert alert-warning">
            Hiện chưa có khóa học nào được mở (published).
        </div>
    @else
        <div class="row">
            @foreach ($courses as $course)
                @php
                    // Ưu tiên dùng accessor thumbnail_url nếu bạn đã thêm trong Course model
                    $thumb = $course->thumbnail_url ?? null;

                    // Fallback: nếu chưa có accessor nhưng đã eager-load firstLesson
                    if (!$thumb && isset($course->firstLesson) && $course->firstLesson && $course->firstLesson->youtube_id) {
                        $thumb = "https://img.youtube.com/vi/{$course->firstLesson->youtube_id}/hqdefault.jpg";
                    }

                    $isEnrolled = isset($enrolledIds) && in_array($course->id, $enrolledIds);
                @endphp

                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        @if($thumb)
                            <img src="{{ $thumb }}" class="card-img-top" alt="Thumbnail">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                <span class="text-muted">Chưa có video</span>
                            </div>
                        @endif

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-2">{{ $course->title }}</h5>

                            @if(!empty($course->description))
                                <p class="card-text text-muted" style="min-height: 48px;">
                                    {{ \Illuminate\Support\Str::limit($course->description, 90) }}
                                </p>
                            @else
                                <p class="card-text text-muted" style="min-height: 48px;">
                                    Chưa có mô tả.
                                </p>
                            @endif

                            <div class="mt-auto d-flex align-items-center gap-2">
                                @if($isEnrolled)
                                    <a href="{{ route('student.courses.show', $course) }}" class="btn btn-primary btn-sm">
                                        Vào học
                                    </a>
                                    <span class="badge bg-secondary">Đã đăng ký</span>
                                @else
                                    <form method="POST" action="{{ route('student.courses.enroll', $course) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">
                                            Đăng ký
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
