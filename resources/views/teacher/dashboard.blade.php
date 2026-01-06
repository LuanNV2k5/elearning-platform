@extends('layouts.teacher')

@section('content')
@php
    use App\Models\Course;
    use Illuminate\Support\Facades\DB;

    $courses = Course::where('teacher_id', auth()->id())->get();

     $teacherId = auth()->id();

    // Lấy danh sách khóa học của giáo viên
    $courses = Course::where('teacher_id', $teacherId)->get();

    // Tổng số người học (distinct để 1 học viên chỉ tính 1 lần)
    $totalStudents = DB::table('course_user')
        ->join('courses', 'course_user.course_id', '=', 'courses.id')
        ->where('courses.teacher_id', $teacherId)
        ->distinct('course_user.user_id')
        ->count('course_user.user_id');
@endphp


<h2>Xin chào Teacher 👋</h2>
<p>Chào mừng bạn đến trang quản lý giảng viên.</p>

{{-- ====== THÔNG TIN GIÁO VIÊN ====== --}}
<div class="card mb-4">
    <div class="card-header">
        📌 Thông tin giáo viên
    </div>
    <div class="card-body">
        <p><strong>Họ tên:</strong> {{ auth()->user()->name }}</p>
        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
        <p><strong>Vai trò:</strong> Giáo viên</p>
    </div>
</div>

{{-- ====== THỐNG KÊ NHANH ====== --}}
<div class="card mb-4">
    <div class="card-header">
        📊 Thống kê nhanh
    </div>
    <div class="card-body">
        <p>
            <strong>Tổng số khóa học:</strong>
            {{ $courses->count() }}
        </p>
        <p>
            <strong>Tổng số người học:</strong>
            {{ $totalStudents }}
        </p>
    </div>
</div>

{{-- ====== DANH SÁCH KHÓA HỌC ====== --}}
<div class="card">
    <div class="card-header">
        📚 Khóa học của bạn
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tên khóa học</th>
                    <th>Giá</th>
                    <th>Ngày tạo</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($courses as $index => $course)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $course->title }}</td>
                        <td>{{ number_format($course->price) }} đ</td>
                        <td>{{ $course->created_at->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">
                            Bạn chưa tạo khóa học nào
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
