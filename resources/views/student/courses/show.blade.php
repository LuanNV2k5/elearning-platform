@extends('layouts.student')

@section('content')
    <h3 class="mb-3">📘 {{ $course->title }}</h3>

    <ul class="list-group">
      @foreach ($lessons as $lesson)
          <li class="list-group-item">
              <a href="{{ route('student.lessons.show', [$course, $lesson]) }}">
                  ▶ {{ $lesson->title }}
              </a>
          </li>
      @endforeach
    </ul>
@endsection
