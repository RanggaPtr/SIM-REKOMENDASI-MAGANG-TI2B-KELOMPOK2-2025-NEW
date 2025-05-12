@vite(['resources/css/sidebar.css'])

<aside class="bg-light-softer vh-99" id="sidebar">
    <div class="d-flex justify-content-center my-3 bg-light-softer pb-3">
        <img src="{{url('/images/logo.png')}}" style="height: 50px;" class="bg-light-softer" alt="">
    </div>

    <ul class="sidebar-nav nav flex-column gap-1">
        <li class="nav-item bg-light-softer">
            <a href="{{ url('/roles/admin/dashboard') }}"
                class="nav-link d-flex align-items-center {{ $activeMenu == 'dashboard' ? 'active bg-primary text-light-softer rounded' : 'text-muted' }}">
                <i class="fas fa-tachometer-alt me-4"></i><b>Dashboard</b>
            </a>
        </li>

        {{-- Manajemen Magang --}}
        <li class="nav-item bg-light-softer">
            <div
                class="nav-link toggle-submenu d-flex align-items-center {{ $activeMenu == 'manajemenMagang' ? 'active bg-primary text-light-softer rounded' : 'text-muted' }}">
                <i class="fas fa-briefcase me-4"></i><b>Manajemen Magang</b> <i class="fas fa-caret-right ms-auto pe-2"></i>
            </div>
            <ul class="nav-submenu ps-5 bg-light-softer">
                <li class="hover: text-light-softer bg-primary rounded"><a href="{{ url('/roles/admin/management-lowongan-magang') }}"
                        class="nav-link text-muted bg-light-softer">Lowongan Magang</a></li>
                <li><a href="{{ url('/roles/admin/management-pengajuan-magang') }}"
                        class="nav-link text-muted bg-light-softer">Pengajuan Magang</a></li>
            </ul>
        </li>

        {{-- Manajemen Data --}}
        <li class="nav-item bg-light-softer">
            <div
                class="nav-link toggle-submenu d-flex align-items-center {{ $activeMenu == 'manajemenData' ? 'active bg-primary text-light-softer rounded' : 'text-muted' }}">
                <i class="fas fa-database me-4"></i><b>Manajemen Data</b> <i class="fas fa-caret-right ms-auto pe-2"></i>
            </div>
            <ul class="nav-submenu ps-5 bg-light-softer">
                <li><a href="{{ url('/roles/admin/management-mitra') }}"
                        class="nav-link text-muted bg-light-softer">Mitra</a></li>
                <li><a href="{{ url('/roles/admin/management-pengguna') }}"
                        class="nav-link text-muted bg-light-softer">Pengguna</a></li>
                <li><a href="{{ url('/roles/admin/management-periode') }}"
                        class="nav-link text-muted bg-light-softer">Periode</a></li>
                <li><a href="{{ url('/roles/admin/management-prodi') }}"
                        class="nav-link text-muted bg-light-softer">Program Studi</a></li>
            </ul>
        </li>

        {{-- Statistik --}}
        <li class="nav-item bg-light-softer">
            <a href="{{ url('/roles/admin/statistik-data-tren') }}"
                class="nav-link d-flex align-items-center {{ $activeMenu == 'analitik' ? 'active bg-primary text-light-softer rounded' : 'text-muted' }}">
                <i class="fas fa-chart-line me-4"></i><b>Statistik Tren</b>
            </a>
        </li>
        
        {{-- Logout --}}
        <li class="nav-item bg-light-softer mt-auto" style="position: absolute; bottom: 10vh;">
            <a href="{{ url('/logout') }}"
                class="nav-link d-flex align-items-center {{ $activeMenu == 'logout' ? 'active bg-primary text-light-softer rounded' : 'text-muted' }}">
                <i class="fas fa-sign-out-alt me-4"></i><b>Logout</b>
            </a>
        </li>
    </ul>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const headers = document.querySelectorAll('.toggle-submenu');

        headers.forEach(header => {
            header.addEventListener('click', () => {
                const parentLi = header.parentElement;
                const submenu = parentLi.querySelector('.nav-submenu');
                const caretIcon = header.querySelector('.fa-caret-right'); // pastikan ini tepat

                if (submenu) {
                    submenu.classList.toggle('show');

                    if (caretIcon) {
                        caretIcon.classList.toggle('rotate-90');
                    }
                }
            });
        });

        const toggleButton = document.querySelector('.toggle-sidebar');
        if (toggleButton) {
            toggleButton.addEventListener('click', function() {
                const sidebar = document.getElementById('sidebar');
                sidebar.classList.toggle('active');
                sidebar.classList.add('fade');
                setTimeout(() => sidebar.classList.remove('fade'), 300);
            });
        }
    });
</script>
