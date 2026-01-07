@extends('layouts.student')

@section('content')
    <h3>📖 Bài học – {{ $course->title }}</h3>

    @if($lessons->isEmpty())
        <p>Chưa có bài học.</p>
    @else
        <ul class="list-group">
            @foreach($lessons as $lesson)
                <li class="list-group-item">
                    <strong>{{ $lesson->order }}.</strong> {{ $lesson->title }}

                    <div class="mt-2">
                        @if($lesson->video_url)
                            <a href="{{ $lesson->video_url }}" target="_blank"
                               class="btn btn-sm btn-outline-primary">
                                ▶ Video
                            </a>
                        @endif

                        @if($lesson->pdf_path)
                            <a href="{{ asset('storage/'.$lesson->pdf_path) }}" target="_blank"
                               class="btn btn-sm btn-outline-secondary">
                                📄 PDF
                            </a>
                        @endif
                    </div>
                </li>
            @endforeach
            @foreach($course->lessons as $index => $lesson)

@php
    $prevLesson = $course->lessons[$index - 1] ?? null;

    $locked = $prevLesson &&
        !auth()->user()
            ->completedLessons
            ->contains($prevLesson->id);
@endphp

<tr>
    <td>{{ $lesson->title }}</td>

    <td>
        @if($locked)
            🔒 Chưa mở
        @else
            <a href="{{ route('student.lessons.show', [$course, $lesson]) }}"
               class="btn btn-sm btn-primary">
                Học bài
            </a>
        @endif
    </td>
</tr>

@endforeach

        </ul>
    @endif
@endsection
