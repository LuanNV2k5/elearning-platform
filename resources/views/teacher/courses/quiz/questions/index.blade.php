@extends('layouts.teacher')

@section('content')
<h3>❓ Câu hỏi cho quiz: {{ $quiz->title }}</h3>

<a href="{{ route('teacher.courses.quiz.questions.create', $course) }}"
   class="btn btn-success mb-3">
    ➕ Thêm câu hỏi
</a>

@if($questions->isEmpty())
    <p>Chưa có câu hỏi nào.</p>
@else
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Câu hỏi</th>
            <th>Điểm</th>
        </tr>
        </thead>
        <tbody>
        @foreach($questions as $q)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $q->content }}</td>
                <td>{{ $q->score }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
@endsection
