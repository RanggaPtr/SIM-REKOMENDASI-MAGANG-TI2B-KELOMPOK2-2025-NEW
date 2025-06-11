@vite(['resources/css/sidebar.css'])

<aside>
    <ul class="sidebar-nav nav flex-column gap-1 p-3"> <!-- Logo -->
        <div class="d-flex justify-content-center my-3 bg-light-softer pb-3">
            <img src="{{ asset('images/logo.png') }}" style="height: 50px;" class="bg-light-softer" alt="Logo">
        </div>

        <!-- Dashboard (Tampil untuk semua role) -->
        <li class="nav-item bg-light-softer">
            <a href="{{ route(strtolower(Auth::user()->role) . '.dashboard') }}"
                class="nav-link d-flex align-items-center {{ isset($activeMenu) && $activeMenu == 'Dashboard' ? 'active bg-primary text-light-softer rounded' : 'text-muted' }}"
                style="padding: 10px 15px;">
                <i class="fas fa-tachometer-alt me-4"></i><b>Dashboard</b>
            </a>
        </li>

        <!-- Admin Menu -->
        @if (Auth::user()->role === 'admin')
            <!-- Manajemen Magang -->
            <li class="nav-item bg-light-softer">
                <div
                    class="nav-link toggle-submenu d-flex align-items-center {{ isset($activeMenu) && ($activeMenu == 'Lowongan Magang' || $activeMenu == 'Pengajuan Magang') ? 'active bg-primary text-light-softer rounded' : 'text-muted' }}">
                    <i class="fas fa-briefcase me-4"></i><b>Manajemen Magang</b>
                    <i class="fas fa-caret-right ms-auto pe-2"></i>
                </div>
                <ul class="nav-submenu ps-5 bg-light-softer">
                    <li>
                        <a href="{{ route('admin.lowongan.index') }}"
                            class="nav-link text-muted bg-light-softer">Lowongan Magang</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.pengajuan.index') }}"
                            class="nav-link text-muted bg-light-softer">Pengajuan Magang</a>
                    </li>
                </ul>
            </li>

            <!-- Manajemen Data -->
            <li class="nav-item bg-light-softer">
                <div
                    class="nav-link toggle-submenu d-flex align-items-center {{ isset($activeMenu) && ($activeMenu == 'Manajemen Perusahaan Mitra' || $activeMenu == 'Manajemen Program Studi' || $activeMenu == 'Manajemen Pengguna' || $activeMenu == 'Manajemen Periode Magang') ? 'active bg-primary text-light-softer rounded' : 'text-muted' }}">
                    <i class="fas fa-database me-4"></i><b>Manajemen Data</b>
                    <i class="fas fa-caret-right ms-auto pe-2"></i>
                </div>
                <ul class="nav-submenu ps-5 bg-light-softer">
                    <li>
                        <a href="{{ route('admin.perusahaan.index') }}"
                            class="nav-link text-muted bg-light-softer">Mitra</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.user.index') }}"
                            class="nav-link text-muted bg-light-softer">Pengguna</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.periode.index') }}"
                            class="nav-link text-muted bg-light-softer">Periode</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.programstudi.index') }}"
                            class="nav-link text-muted bg-light-softer">Program Studi</a>
                    </li>
                </ul>
            </li>
        @endif

        <!-- Dosen Menu -->
        @if (Auth::user()->role === 'dosen')
            <li class="nav-item bg-light-softer">
                <a href="{{ route('dosen.monitoring.mahasiswa') }}"
                    class="nav-link d-flex align-items-center {{ isset($activeMenu) && $activeMenu == 'Monitoring Magang' ? 'active bg-primary text-light-softer rounded' : 'text-muted' }}">
                    <i class="fas fa-clipboard-check me-4"></i><b>Monitoring & Evaluasi </b>
                </a>
            </li>
            <li class="nav-item bg-light-softer">
                <a href="{{ route('dosen.sertifikat.index') }}"
                    class="nav-link d-flex align-items-center {{ isset($activeMenu) && $activeMenu == 'Sertifikat' ? 'active bg-primary text-light-softer rounded' : 'text-muted' }}">
                    <i class="fas fa-upload me-4"></i><b>Upload Sertifikat</b>
                </a>
            </li>
        @endif

        <!-- Mahasiswa Menu -->
        @if (Auth::user()->role === 'mahasiswa')
            <li class="nav-item bg-light-softer">
                <a href="{{ Route::has('mahasiswa.pengajuan-magang.index') ? route('mahasiswa.pengajuan-magang.index') : '#' }}"
                    class="nav-link d-flex align-items-center {{ isset($activeMenu) && $activeMenu == 'Pengajuan Magang' ? 'active bg-primary text-light-softer rounded' : 'text-muted' }}"
                    style="padding: 10px 15px;">
                    <i class="fas fa-user me-4"></i><b>Pengajuan Magang</b>
                </a>
            </li>
            <li class="nav-item bg-light-softer">
                <div class="nav-link toggle-submenu d-flex align-items-center {{ isset($activeMenu) && $activeMenu == 'Log Harian' ? 'active bg-primary text-light-softer rounded' : 'text-muted' }}"
                    style="padding: 10px 15px;">
                    <i class="fas fa-building me-4"></i><b>Aktivitas Magang</b>
                    <i class="fas fa-caret-right ms-auto pe-2"></i>
                </div>
                <ul class="nav-submenu ps-5 bg-light-softer">
                    <li>
                        <a href="{{ route('mahasiswa.log-harian.index') }}"
                            class="nav-link text-muted bg-light-softer">Log Harian</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item bg-light-softer">
                <a href="{{ Route::has('mahasiswa.feedback') ? route('mahasiswa.feedback') : '#' }}"
                    class="nav-link d-flex align-items-center {{ isset($activeMenu) && $activeMenu == 'Feedback' ? 'active bg-primary text-light-softer rounded' : 'text-muted' }}"
                    style="padding: 10px 15px;">
                    <i class="fas fa-file-alt me-4"></i><b>Feedback</b>
                </a>
            </li>
        @endif

        <!-- Logout (Tampil untuk semua role) -->
        <li class="nav-item bg-light-softer mt-auto" style="position: absolute; bottom: 10vh;">
            <a href="{{ route('logout') }}"
                class="nav-link logout d-flex align-items-center {{ isset($activeMenu) && $activeMenu == 'logout' ? 'active bg-primary text-light-softer rounded' : 'text-muted' }}">
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
                const caretIcon = header.querySelector('.fa-caret-right');

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
