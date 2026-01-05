@extends('layouts.admin')

@section('content')
<h1 class="mb-4">📊 Dashboard (Admin)</h1>

<div class="row g-4">

    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5>Tổng người dùng</h5>
                <h2>{{ $totalUsers }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5>Admin</h5>
                <h2>{{ $totalAdmins }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5>Teacher</h5>
                <h2>{{ $totalTeachers }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5>Student</h5>
                <h2>{{ $totalStudents }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card text-center">
            <div class="card-body">
                <h5>Tổng số khóa học</h5>
                <h2>{{ $totalCourses }}</h2>
            </div>
        </div>
    </div>

</div>
@endsection
