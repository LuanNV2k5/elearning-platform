@extends('layouts.student')

@section('content')
@php
    use App\Models\Course;

    $student = auth()->user();

    // Lấy các khóa học sinh viên đã ghi danh
    $courses = Course::join('course_user', 'courses.id', '=', 'course_user.course_id')
        ->where('course_user.user_id', $student->id)
        ->select('courses.*')
        ->with('quiz') // load quiz
        ->get();
@endphp

<h3 class="mb-3">🎓 Student Dashboard</h3>
<p class="mb-4">Chào mừng bạn đến hệ thống học tập.</p>

{{-- ====== THÔNG TIN SINH VIÊN ====== --}}
<div class="card mb-4">
    <div class="card-header">
        👤 Thông tin sinh viên
    </div>
    <div class="card-body">
        <p><strong>Họ tên:</strong> {{ $student->name }}</p>
        <p><strong>Email:</strong> {{ $student->email }}</p>
        <p><strong>Vai trò:</strong> Sinh viên</p>
    </div>
</div>

{{-- ====== THỐNG KÊ ====== --}}
<div class="card mb-4">
    <div class="card-header">
        📊 Thống kê
    </div>
    <div class="card-body">
        <p>
            <strong>Số khóa học đang tham gia:</strong>
            {{ $courses->count() }}
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
                    <th>Ngày tham gia</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($courses as $index => $course)
                    @php
                        // Lần làm quiz gần nhất của user cho course này
                        $attempt = $student->quizAttempts()
                            ->whereHas('quiz', fn($q) => $q->where('course_id', $course->id))
                            ->latest()
                            ->first();
                    @endphp

                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $course->title }}</td>
                        <td>{{ number_format($course->price) }} đ</td>
                        <td>{{ $course->created_at->format('d/m/Y') }}</td>
                        <td>
                            {{-- CHƯA CÓ QUIZ --}}
                            @if(!$course->quiz)
                                <span class="badge bg-secondary">
                                    Chưa có bài kiểm tra
                                </span>

                            {{-- ĐÃ PASS QUIZ --}}
                            @elseif($attempt && $attempt->status === 'passed')
                                <span class="badge bg-success">
                                    🎉 Hoàn thành khóa học
                                </span>

                            {{-- ĐÃ LÀM QUIZ NHƯNG FAIL --}}
                            @elseif($attempt && $attempt->status === 'failed')
                                <span class="badge bg-danger">
                                    ❌ Chưa đạt bài kiểm tra
                                </span>

                            {{-- CHƯA ĐỦ ĐIỀU KIỆN --}}
                            @else
                                <span class="badge bg-warning text-dark">
                                    ⏳ Chưa đủ điều kiện làm bài kiểm tra
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            Bạn chưa tham gia khóa học nào
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
