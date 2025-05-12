<aside class="sidebar">
    <ul class="sidebar-nav">
        @if(Auth::user()->role === 'admin')
            <li><a href="{{ url('/roles/admin/dashboard') }}">Dashboard</a></li>
            <li><a href="{{ url('/roles/admin/users') }}">Kelola Pengguna</a></li>
        @elseif(Auth::user()->role === 'dosen')
            <li><a href="{{ url('/roles/dosen/dashboard') }}">Dashboard</a></li>
            <li><a href="{{ url('/roles/dosen/evaluations') }}">Evaluasi</a></li>
        @elseif(Auth::user()->role === 'mahasiswa')
            <li><a href="{{ url('/roles/mahasiswa/dashboard') }}">Dashboard</a></li>
            <li><a href="{{ url('/roles/mahasiswa/applications') }}">Ajukan Magang</a></li>
        @endif
        <li><a href="{{ url('/logout') }}">Logout</a></li>
    </ul>
</aside>