@extends('layouts.student')

@section('content')
    <h3 class="mb-3">🎓 Khóa học của tôi</h3>

    @if($courses->isEmpty())
        <div class="alert alert-warning">
            Bạn chưa đăng ký khóa học nào.
        </div>
    @else
        <div class="row">
            @foreach ($courses as $course)
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5>{{ $course->title }}</h5>
                            <p>{{ $course->description }}</p>

                            <a href="{{ route('student.courses.show', $course) }}"
                               class="btn btn-primary btn-sm">
                                Xem bài học
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
