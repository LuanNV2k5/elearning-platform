@extends('layouts.student')

@section('content')
    <h3 class="mb-3">📌 Khóa học của tôi</h3>

    @if($courses->isEmpty())
        <div class="alert alert-warning">
            Bạn chưa đăng ký khóa học nào. Hãy qua <b>Khám phá khóa học</b> để đăng ký nhé!
        </div>
    @else
        <div class="row">
            @foreach ($courses as $course)
                @php
                    $thumb = $course->thumbnail_url ?? null;
                    if (!$thumb && $course->firstLesson && $course->firstLesson->youtube_id) {
                        $thumb = "https://img.youtube.com/vi/{$course->firstLesson->youtube_id}/hqdefault.jpg";
                    }
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
                            <p class="card-text text-muted" style="min-height: 48px;">
                                {{ \Illuminate\Support\Str::limit($course->description, 90) }}
                            </p>

                            <div class="mt-auto d-flex align-items-center gap-2">
                                <a href="{{ route('student.courses.show', $course) }}" class="btn btn-primary btn-sm">
                                    Vào học
                                </a>
                                <span class="badge bg-success">Đã đăng ký</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <hr class="my-4">

    @if(!empty($lastCourseTitle))
        <h4 class="mb-3">✨ Gợi ý tiếp theo vì bạn vừa xem: <span class="text-primary">{{ $lastCourseTitle }}</span></h4>
    @else
        <h4 class="mb-3">✨ Gợi ý tiếp theo</h4>
    @endif

    @if(!isset($nextCourses) || $nextCourses->isEmpty())
        <p class="text-muted">Chưa có gợi ý tiếp theo (cần thêm dữ liệu học tập hoặc chạy train ML).</p>
    @else
        <div class="row">
            @foreach ($nextCourses as $course)
                @php
                    $thumb = $course->thumbnail_url ?? null;
                    if (!$thumb && $course->firstLesson && $course->firstLesson->youtube_id) {
                        $thumb = "https://img.youtube.com/vi/{$course->firstLesson->youtube_id}/hqdefault.jpg";
                    }
                    $isEnrolled = in_array($course->id, $enrolledIds ?? []);
                @endphp

                <div class="col-md-4 mb-3">
                    <div class="card h-100 border-primary">
                        @if($thumb)
                            <img src="{{ $thumb }}" class="card-img-top" alt="Thumbnail">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                <span class="text-muted">Chưa có video</span>
                            </div>
                        @endif

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-2">{{ $course->title }}</h5>
                            <p class="card-text text-muted" style="min-height: 48px;">
                                {{ \Illuminate\Support\Str::limit($course->description, 90) }}
                            </p>

                            <div class="mt-auto d-flex align-items-center gap-2">
                                @if($isEnrolled)
                                    <a href="{{ route('student.courses.show', $course) }}" class="btn btn-primary btn-sm">Vào học</a>
                                    <span class="badge bg-secondary">Đã đăng ký</span>
                                @else
                                    <form method="POST" action="{{ route('student.courses.enroll', $course) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Đăng ký</button>
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
