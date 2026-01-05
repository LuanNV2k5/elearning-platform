@extends('layouts.admin')

@section('content')
<h1 class="mb-4">📊 Dashboard (Admin)</h1>

{{-- ====== THỐNG KÊ TỔNG QUAN ====== --}}
<div class="row g-4 mb-4">

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

<hr class="my-5">

{{-- ====== THỐNG KÊ KHÓA HỌC THEO GIÁO VIÊN ====== --}}
<h3 class="mb-3">📚 Thống kê khóa học theo giáo viên</h3>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Tên giáo viên</th>
                    <th>Email</th>
                    <th>Số khóa học</th>
                </tr>
            </thead>
            <tbody>
                @isset($coursesByTeacher)
                    @forelse ($coursesByTeacher as $index => $teacher)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $teacher->name }}</td>
                            <td>{{ $teacher->email }}</td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $teacher->courses_count }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                Chưa có giáo viên nào
                            </td>
                        </tr>
                    @endforelse
                @else
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            Không có dữ liệu thống kê
                        </td>
                    </tr>
                @endisset
            </tbody>
        </table>
    </div>
</div>

@endsection
