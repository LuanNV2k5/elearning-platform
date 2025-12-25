@extends('layouts.student')

@section('content')
    <h3>🔍 Khám phá khóa học</h3>

    <div class="row">
        @foreach ($courses as $course)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5>{{ $course->title }}</h5>
                        <p>{{ $course->description }}</p>

                        <form method="POST"
                              action="{{ route('student.courses.enroll', $course) }}">
                            @csrf
                            <button class="btn btn-success btn-sm">
                                Đăng ký
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
