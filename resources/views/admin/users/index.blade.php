@extends('layouts.admin')

@section('content')
<h1 class="mb-4">👥 Quản lý người dùng (Admin)</h1>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if ($user->role_id == 1)
                                Admin
                            @elseif ($user->role_id == 2)
                                Teacher
                            @else
                                Student
                            @endif
                        </td>
                        <td>
                            {{-- sau này thêm sửa / khóa --}}
                            <span class="text-muted">—</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            Chưa có người dùng nào
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
