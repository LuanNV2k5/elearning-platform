@extends('layouts.admin')

@section('content')
<h1 style="margin-bottom: 20px;">📚 Quản lý khóa học (Admin)</h1>

<div style="background:#fff; padding:20px; border-radius:6px;">
    <table width="100%" border="1" cellpadding="10" cellspacing="0">
        <thead style="background:#eee;">
            <tr>
                <th align="left">Tên khóa học</th>
                <th align="left">Giá</th>
                <th align="left">Giáo viên</th>
                <th align="center">Số người học</th>
                <th align="center">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($courses as $course)
                <tr>
                    <td>{{ $course->title }}</td>
                    <td>{{ number_format($course->price) }} đ</td>
                    <td>{{ $course->teacher->name ?? 'N/A' }}</td>

                    {{-- SỐ NGƯỜI HỌC --}}
                    <td align="center">
                        <strong>{{ $course->students_count }}</strong>
                    </td>

                    {{-- HÀNH ĐỘNG --}}
                    <td align="center">
                        <form method="POST"
                              action="{{ route('admin.courses.destroy', $course) }}">
                            @csrf
                            @method('DELETE')
                            <button
                                style="background:red;color:#fff;border:none;padding:6px 12px;cursor:pointer"
                                onclick="return confirm('Xóa khóa học này?')">
                                Xóa
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" align="center">Chưa có khóa học nào</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
