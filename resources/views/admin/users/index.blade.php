@extends('layouts.admin')

@section('content')
<h2 style="margin-bottom:20px;">👤 Quản lý tài khoản</h2>

@if ($errors->any())
    <div style="color:red; margin-bottom:10px;">
        {{ $errors->first() }}
    </div>
@endif

@if (session('success'))
    <div style="color:green; margin-bottom:10px;">
        {{ session('success') }}
    </div>
@endif

<table width="100%" border="1" cellpadding="10" cellspacing="0">
    <thead style="background:#eee;">
        <tr>
            <th>Họ tên</th>
            <th>Email</th>
            <th>Chức vụ</th>
            <th align="center">Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>

                {{-- HIỂN THỊ QUYỀN (ROLE) --}}
                <td>
                    <strong>
                        {{ strtoupper($user->role->name ?? 'N/A') }}
                    </strong>
                </td>

                {{-- HÀNH ĐỘNG --}}
                <td align="center">
                    {{-- ❌ KHÔNG CHO XOÁ ADMIN --}}
                    {{-- ❌ KHÔNG CHO XOÁ CHÍNH MÌNH --}}
                    @if (
                        $user->role &&
                        strtoupper($user->role->name) !== 'ADMIN' &&
                        $user->id !== auth()->id()
                    )
                        <form method="POST"
                              action="{{ route('admin.users.destroy', $user) }}">
                            @csrf
                            @method('DELETE')
                            <button
                                style="background:red;color:#fff;border:none;padding:6px 12px;cursor:pointer"
                                onclick="return confirm('Xoá tài khoản này?')">
                                Xoá
                            </button>
                        </form>
                    @else
                        <span style="color:#999;">Không thể xoá</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
