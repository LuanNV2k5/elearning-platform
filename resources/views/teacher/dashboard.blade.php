@extends('layouts.app')

@section('content')
    <h1 class="mb-3">Teacher Dashboard</h1>
    <p>Xin chào {{ auth()->user()->name }}</p>

    <a href="{{ route('teacher.courses.index') }}" class="btn btn-primary">
        Quản lý khóa học
    </a>
@endsection
