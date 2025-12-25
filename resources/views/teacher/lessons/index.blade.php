@extends('layouts.teacher')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>📘 Bài học – {{ $course->title }}</h3>

        <a href="{{ route('teacher.courses.lessons.create', $course) }}"
           class="btn btn-success">
            ➕ Thêm bài học
        </a>
    </div>

    @if($lessons->isEmpty())
        <p>Chưa có bài học nào.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Thứ tự</th>
                    <th>Tiêu đề</th>
                    <th>Video</th>
                    <th>PDF</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lessons as $lesson)
                <tr>
                    <td>{{ $lesson->order }}</td>
                    <td>{{ $lesson->title }}</td>
                    <td>
                        @if($lesson->video_url)
                            <a href="{{ $lesson->video_url }}" target="_blank">
                                ▶ Xem
                            </a>
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        @if($lesson->pdf_path)
                            <a href="{{ asset('storage/'.$lesson->pdf_path) }}"
                               target="_blank">
                                📄 PDF
                            </a>
                        @else
                            —
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
