@extends('layouts.student')

@section('content')
<div class="container">
    <h4 class="mb-3">{{ $lesson->title }}</h4>

    <div class="ratio ratio-16x9 mb-3">
        <iframe
            src="https://www.youtube.com/embed/{{ $lesson->youtube_id }}"
            title="YouTube video"
            allowfullscreen>
        </iframe>
    </div>

    <p>{{ $lesson->description }}</p>

    <a href="{{ route('student.courses.show', $course) }}"
       class="btn btn-secondary mt-3">
        ← Quay lại khóa học
    </a>
</div>
@endsection
