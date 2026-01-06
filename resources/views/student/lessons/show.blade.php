@extends('layouts.student')

@section('content')
<div class="container">
    <h4 class="mb-3">{{ $lesson->title }}</h4>

    {{-- VIDEO --}}
    @if($lesson->youtube_id)
    <div class="ratio ratio-16x9 mb-4">
        <iframe
            src="https://www.youtube.com/embed/{{ $lesson->youtube_id }}"
            allowfullscreen>
        </iframe>
    </div>
    @endif

    {{-- PDF --}}
    @if($lesson->pdf_path)
<div class="mb-3">
    <a href="{{ asset('storage/'.$lesson->pdf_path) }}"
       target="_blank"
       class="btn btn-outline-primary">
        📄 Mở tài liệu PDF
    </a>
</div>

<iframe
    src="{{ asset('storage/'.$lesson->pdf_path) }}"
    width="100%"
    height="600"
    style="border:1px solid #ccc">
</iframe>
@endif

    @if($lesson->description)
        <p>{{ $lesson->description }}</p>
    @endif

    <a href="{{ route('student.courses.show', $course) }}"
       class="btn btn-secondary mt-3">
        ← Quay lại khóa học
    </a>
</div>
@endsection
