<h1>Admin Dashboard</h1>
<p>Xin chào Admin</p>
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button>Logout</button>
</form>
