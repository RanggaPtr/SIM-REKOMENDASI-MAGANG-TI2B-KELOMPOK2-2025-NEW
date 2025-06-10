<style>
    .active-menu {
        color: #092C6B !important;
    }
</style>

<div class="container-fluid py-2 px-0 d-flex justify-content-between align-items-center text-dark">
    <!-- Kiri: Beranda -->
    <div class="fs-4 text-secondary active-menu"><b>{{$activeMenu}}</b></div>

    <!-- Kanan: Bell, Profile, dan Edit Profile -->
    <div class="d-flex align-items-center gap-2 me-2">
        <!-- Bell Icon: Only show for mahasiswa -->
        @if (Auth::user()->role === 'mahasiswa')
        <div style="position: relative;">
            <i class="fas fa-bell me-5" id="notifBell" style="cursor:pointer;"></i>
            <div id="notifDropdown" class="shadow rounded-4"
                style="display:none; position:absolute; top:40px; right:0; width:320px; z-index:2000;">
                <div class="p-3 border-bottom fw-bold d-flex justify-content-between align-items-center bg-white rounded-top-4">
                    <span class="bg-white">Notifikasi</span>
                    <a href="#" id="markAllRead" style="font-size:13px;" class="bg-white">Tandai semua dibaca</a>
                </div>
                <div id="notifList" style="max-height:300px; overflow-y:auto;" class="bg-white">
                    <div class="text-center py-3 bg-white" id="notifLoading">    
                        <div class="spinner-border text-primary bg-white" role="status" style="width: 2rem; height: 2rem;">
                            <span class="visually-hidden bg-white">Loading...</span>
                        </div>
                    </div>
                    <!-- Notifikasi akan dimuat via JS -->
                </div>
                <div class="text-center p-2 bg-white rounded-bottom-4">
                </div>
            </div>
        </div>
        @endif

        <img src="{{ Auth::user()->foto_profile ? url('/storage/' . Auth::user()->foto_profile) : url('/images/profile.png') }}"
            alt="Profile" style="width: 40px; height: 40px; border-radius: 50%;">
        <div class="column ms-2"> 
            <b class="mb-0">{{ Auth::user()->nama }}</b>
            <p class="mb-0" style="font-size: 13px;">{{ ucfirst(Auth::user()->role) }}</p>
        </div>
        <!-- Tombol Edit Profile -->
        <button class="btn btn-sm btn-outline-primary ms-2" data-bs-toggle="modal" data-bs-target="#editProfileModal">
            <i class="fas fa-edit bg-transparent"></i> Edit
        </button>
    </div>
</div>

<!-- Modal Edit Profile -->
<div class="modal fade" id="editProfileModal" style="background-color: rgba(0, 0, 0, 0.5);" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg rounded-4">
        <div class="modal-content rounded-4 shadow-lg">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold" id="editProfileModalLabel" style="color: black;background-color:none;">
                    Edit Profil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @auth
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Foto Profil -->
                        <div class="mb-3 text-center">
                            <img src="{{ Auth::user()->foto_profile ? url('/storage/' . Auth::user()->foto_profile) : url('/images/profile.png') }}"
                                alt="Profile" class="rounded-circle mb-3"
                                style="width: 100px; height: 100px; object-fit: cover;">
                            <!-- <label for="foto_profile" class="form-label">Ganti Foto Profil</label> -->
                            <input type="file" class="form-control" id="foto_profile" name="foto_profile"
                                accept="image/*">
                            @error('foto_profile')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nama -->
                        <div class="mb-3">
                            <label for="nama" class="form-label fw-bold">Nama</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                name="nama" value="{{ old('nama', Auth::user()->nama) }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Username -->
                        <div class="mb-3">
                            <label for="username" class="form-label fw-bold">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                id="username" name="username" value="{{ old('username', Auth::user()->username) }}"
                                required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- No Telepon -->
                        <div class="mb-3">
                            <label for="no_telepon" class="form-label fw-bold">No Telepon</label>
                            <input type="text" class="form-control @error('no_telepon') is-invalid @enderror"
                                id="no_telepon" name="no_telepon"
                                value="{{ old('no_telepon', Auth::user()->no_telepon) }}">
                            @error('no_telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="mb-3">
                            <label for="alamat" class="form-label fw-bold">Alamat</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3">{{ old('alamat', Auth::user()->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    <!-- Role Spesifik Fields -->
                    @if (Auth::user()->role === 'dosen')
                    @php
                    $dosen = \App\Models\DosenModel::where('user_id', Auth::user()->user_id)->first();
                    $programStudiList = \App\Models\ProgramStudiModel::orderBy('nama')->get();
                    $kompetensiList = \App\Models\KompetensiModel::orderBy('nama')->get();
                    @endphp

                            <div class="mb-3">
                                <label for="nik" class="form-label fw-bold">NIK</label>
                                <input type="text" class="form-control @error('nik') is-invalid @enderror"
                                    id="nik" name="nik" value="{{ old('nik', $dosen->nik ?? '') }}" required>
                                @error('nik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                    <div class="mb-3">
                        <label for="prodi_id" class="form-label fw-bold">Program Studi</label>
                        <select class="form-select @error('prodi_id') is-invalid @enderror"
                            id="prodi_id" name="prodi_id" required>
                            <option value="">-- Pilih Program Studi --</option>
                            @foreach ($programStudiList as $prodi)
                            <option value="{{ $prodi->prodi_id }}"
                                {{ old('prodi_id', $dosen->prodi_id ?? '') == $prodi->prodi_id ? 'selected' : '' }}>
                                {{ $prodi->nama }}
                            </option>
                            @endforeach
                        </select>
                        @error('prodi_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="kompetensi_id" class="form-label fw-bold">Kompetensi</label>
                        <select class="form-select @error('kompetensi_id') is-invalid @enderror"
                            id="kompetensi_id" name="kompetensi_id">
                            <option value="">-- Pilih Kompetensi (Opsional) --</option>
                            @foreach ($kompetensiList as $kompetensi)
                            <option value="{{ $kompetensi->kompetensi_id }}"
                                {{ old('kompetensi_id', $dosen->kompetensi_id ?? '') == $kompetensi->kompetensi_id ? 'selected' : '' }}>
                                {{ $kompetensi->nama }}
                            </option>
                            @endforeach
                        </select>
                        @error('kompetensi_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @elseif (Auth::user()->role === 'mahasiswa')
                    @php
                    $mahasiswa = \App\Models\MahasiswaModel::where('user_id', Auth::user()->user_id)->first();
                    $programStudiList = \App\Models\ProgramStudiModel::orderBy('nama')->get();
                    $wilayahList = \App\Models\WilayahModel::orderBy('nama')->get();
                    $skemaList = \App\Models\SkemaModel::orderBy('nama')->get();
                    $periodeList = \App\Models\PeriodeMagangModel::orderBy('nama')->get();

                    $kompetensiList = \App\Models\KompetensiModel::orderBy('nama')->get();
                    $keahlianList = \App\Models\KeahlianModel::orderBy('nama')->get();

                    // Ambil data mahasiswa dan relasinya, ubah 'keahlian' menjadi 'mahasiswaKeahlian'
                    $mahasiswa = \App\Models\MahasiswaModel::where('user_id', Auth::user()->user_id)->with('mahasiswaKeahlian')->first();

                    // Ubah 'keahlian' menjadi 'mahasiswaKeahlian' saat mengambil keahlian_ids
                    $selectedKeahlianIds = old('keahlian_ids', $mahasiswa->mahasiswaKeahlian->pluck('keahlian_id')->toArray() ?? []);
                    @endphp

                            <div class="mb-3">
                                <label for="nim" class="form-label fw-bold">NIM</label>
                                <input type="text" class="form-control @error('nim') is-invalid @enderror"
                                    id="nim" name="nim" value="{{ old('nim', $mahasiswa->nim ?? '') }}"
                                    required>
                                @error('nim')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                    <div class="mb-3">
                        <label for="program_studi_id" class="form-label fw-bold">Program Studi</label>
                        <select class="form-select @error('program_studi_id') is-invalid @enderror"
                            id="program_studi_id" name="program_studi_id" required>
                            <option value="">-- Pilih Program Studi --</option>
                            @foreach ($programStudiList as $prodi)
                            <option value="{{ $prodi->prodi_id }}"
                                {{ old('program_studi_id', $mahasiswa->program_studi_id ?? '') == $prodi->prodi_id ? 'selected' : '' }}>
                                {{ $prodi->nama }}
                            </option>
                            @endforeach
                        </select>
                        @error('program_studi_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Kompetensi (single select checkbox) -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kompetensi</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($kompetensiList as $kompetensi)
                            <div class="form-check">
                                <input class="form-check-input @error('kompetensi_id') is-invalid @enderror"
                                    type="checkbox"
                                    name="kompetensi_id"
                                    value="{{ $kompetensi->kompetensi_id }}"
                                    id="kompetensi_{{ $kompetensi->kompetensi_id }}"
                                    {{ old('kompetensi_id', $mahasiswa->mahasiswaKompetensi->pluck('kompetensi_id')->first() ?? '') == $kompetensi->kompetensi_id ? 'checked' : '' }}
                                    onchange="ensureSingleKompetensi(this)">
                                <label class="form-check-label" for="kompetensi_{{ $kompetensi->kompetensi_id }}">
                                    {{ $kompetensi->nama }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @error('kompetensi_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Keahlian (multi select checkbox) -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Keahlian</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($keahlianList as $keahlian)
                            <div class="form-check">
                                <input class="form-check-input @error('keahlian_ids') is-invalid @enderror"
                                    type="checkbox"
                                    name="keahlian_ids[]"
                                    value="{{ $keahlian->keahlian_id }}"
                                    id="keahlian_{{ $keahlian->keahlian_id }}"
                                    {{ in_array($keahlian->keahlian_id, old('keahlian_ids', $mahasiswa->mahasiswaKeahlian->pluck('keahlian_id')->toArray() ?? [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="keahlian_{{ $keahlian->keahlian_id }}">
                                    {{ $keahlian->nama }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @error('keahlian_ids')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="file_cv" class="form-label fw-bold">Upload CV</label>
                        <input type="file" class="form-control @error('file_cv') is-invalid @enderror"
                            id="file_cv" name="file_cv" accept=".pdf">
                        @error('file_cv')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if ($mahasiswa->file_cv)
                        <small class="form-text text-muted">
                            CV saat ini: <a href="{{ url('/storage/' . $mahasiswa->file_cv) }}" target="_blank">Lihat CV</a>
                        </small>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="wilayah_id" class="form-label fw-bold">Wilayah</label>
                        <select class="form-select @error('wilayah_id') is-invalid @enderror"
                            id="wilayah_id" name="wilayah_id" required>
                            <option value="">-- Pilih Wilayah --</option>
                            @foreach ($wilayahList as $wilayah)
                            <option value="{{ $wilayah->wilayah_id }}"
                                {{ old('wilayah_id', $mahasiswa->wilayah_id ?? '') == $wilayah->wilayah_id ? 'selected' : '' }}>
                                {{ $wilayah->nama }}
                            </option>
                            @endforeach
                        </select>
                        @error('wilayah_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                            <div class="mb-3">
                                <label for="skema_id" class="form-label fw-bold">Skema</label>
                                <select class="form-select @error('skema_id') is-invalid @enderror" id="skema_id"
                                    name="skema_id" required>
                                    <option value="">-- Pilih Skema --</option>
                                    @foreach ($skemaList as $skema)
                                        <option value="{{ $skema->skema_id }}"
                                            {{ old('skema_id', $mahasiswa->skema_id ?? '') == $skema->skema_id ? 'selected' : '' }}>
                                            {{ $skema->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('skema_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Periode -->
                            <div class="mb-3">
                                <label for="periode_id" class="form-label fw-bold">Periode</label>
                                <select class="form-select @error('periode_id') is-invalid @enderror" id="periode_id"
                                    name="periode_id" required>
                                    <option value="">-- Pilih Periode --</option>
                                    @foreach ($periodeList as $periode)
                                        <option value="{{ $periode->periode_id }}"
                                            {{ old('periode_id', $mahasiswa->periode_id ?? '') == $periode->periode_id ? 'selected' : '' }}>
                                            {{ $periode->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('periode_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="ipk" class="form-label fw-bold">IPK</label>
                                <input type="number" step="0.01" min="0" max="4"
                                    class="form-control @error('ipk') is-invalid @enderror" id="ipk"
                                    name="ipk" value="{{ old('ipk', $mahasiswa->ipk ?? '') }}" required>
                                @error('ipk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @elseif (Auth::user()->role === 'perusahaan')
                            @php
                                $perusahaan = \App\Models\PerusahaanModel::where(
                                    'user_id',
                                    Auth::user()->user_id,
                                )->first();
                            @endphp

                            <div class="mb-3">
                                <label for="nama_perusahaan" class="form-label fw-bold">Nama Perusahaan</label>
                                <input type="text" class="form-control @error('nama_perusahaan') is-invalid @enderror"
                                    id="nama_perusahaan" name="nama_perusahaan"
                                    value="{{ old('nama_perusahaan', $perusahaan->nama_perusahaan ?? '') }}" required>
                                @error('nama_perusahaan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">Password Baru (opsional)</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password"
                                placeholder="Kosongkan jika tidak ingin mengubah password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label fw-bold">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" placeholder="Ulangi password baru">
                        </div>

                        <div class="d-flex justify-content-center gap-3 mt-4">
                            <button type="submit" class="btn btn-primary text-light fw-bold px-5">
                                <i class="fas fa-save me-2"></i>Simpan
                            </button>
                            <button type="button" class="btn btn-outline-secondary px-5" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>Batal
                            </button>
                        </div>
                    </form>
                @else
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Anda harus login untuk mengedit profil.
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function loadMahasiswaNotifikasi() {
        const notifList = document.getElementById('notifList');
        notifList.innerHTML = `<div class="text-center py-3" id="notifLoading">
            <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>`;
        fetch("{{ route('profile.notifikasi') }}")
            .then(res => res.json())
            .then(res => {
                notifList.innerHTML = '';
                if (res.data && res.data.length > 0) {
                    res.data.forEach(notif => {
                        let icon = '';
                        if (notif.status === 'diterima') {
                            icon = `<i class="fa-solid fa-circle-check text-success me-2"></i>`;
                        } else if (notif.status === 'ditolak') {
                            icon = `<i class="fa-solid fa-circle-xmark text-danger me-2"></i>`;
                        }
                        notifList.innerHTML += `
                            <div class="p-3 border-bottom bg-white d-flex justify-content-between bg-white align-items-center" data-id="${notif.mhs_notifikasi_id}">
                                <div class="d-flex align-items-center w-100 bg-white">
                                    <span class="d-flex align-items-center justify-content-center mx-2" style="font-size:1.7rem; min-width:2.2rem;">
                                        ${
                                            notif.status === 'diterima'
                                                ? `<i class="fa-solid fa-circle-check text-success"></i>`
                                                : `<i class="fa-solid fa-circle-xmark text-danger"></i>`
                                        }
                                    </span>
                                    <div class="flex-grow-1" style="background-color: white;">
                                        <div class="bg-white">${notif.deskripsi}</div>
                                        <small class="text-muted bg-white">${formatDate(notif.created_at)}</small>
                                    </div>
                                    <button class="btn btn-sm btn-link text-muted bg-white ms-2 px-1 py-0 align-self-center" onclick="deleteNotifikasi(${notif.mhs_notifikasi_id}, this)" title="Hapus">
                                        <i class="fas fa-times fa-lg"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    notifList.innerHTML = `<div class="text-center py-3 text-muted bg-white">Tidak ada notifikasi.</div>`;
                }
            })
            .catch(() => {
                notifList.innerHTML = `<div class="text-center py-3 text-danger">Gagal memuat notifikasi.</div>`;
            });
    }

    function deleteNotifikasi(id, btn) {
        btn.disabled = true;
        fetch(`/profile/notifikasi/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                // Remove the notification from the list
                const notifDiv = btn.closest('[data-id]');
                if (notifDiv) notifDiv.remove();
                // If no more notifications, show empty state
                if (document.querySelectorAll('#notifList [data-id]').length === 0) {
                    document.getElementById('notifList').innerHTML = `<div class="text-center py-3 text-muted bg-white">Tidak ada notifikasi.</div>`;
                }
            } else {
                alert(res.error || 'Gagal menghapus notifikasi.');
                btn.disabled = false;
            }
        })
        .catch(() => {
            alert('Gagal menghapus notifikasi.');
            btn.disabled = false;
        });
    }

    document.getElementById('markAllRead').addEventListener('click', function(e) {
    e.preventDefault();
    fetch("{{ route('profile.notifikasi.deleteAll') }}", {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            document.getElementById('notifList').innerHTML = `<div class="text-center py-3 text-muted bg-white">Tidak ada notifikasi.</div>`;
        } else {
            alert(res.error || 'Gagal menghapus semua notifikasi.');
        }
    })
    .catch(() => {
        alert('Gagal menghapus semua notifikasi.');
    });
});
    // Format date (simple, you can improve as needed)
    function formatDate(dateStr) {
        const date = new Date(dateStr);
        if (isNaN(date)) return '';
        const now = new Date();
        const diff = Math.floor((now - date) / 1000);
        if (diff < 60) return 'Baru saja';
        if (diff < 3600) return `${Math.floor(diff/60)} menit lalu`;
        if (diff < 86400) return `${Math.floor(diff/3600)} jam lalu`;
        if (diff < 604800) return `${Math.floor(diff/86400)} hari lalu`;
        return date.toLocaleDateString();
    }

    // Fetch notifications when bell is clicked
    document.getElementById('notifBell').addEventListener('click', function(e) {
        e.stopPropagation();
        const dropdown = document.getElementById('notifDropdown');
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        if (dropdown.style.display === 'block') {
            loadMahasiswaNotifikasi();
        }
    });

    document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('notifDropdown');
    const bell = document.getElementById('notifBell');
    if (!dropdown.contains(e.target) && e.target !== bell) {
        dropdown.style.display = 'none';
    }
});
    function loadMahasiswaNotifikasi() {
        const notifList = document.getElementById('notifList');
        notifList.innerHTML = `<div class="text-center py-3" id="notifLoading">
            <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>`;
        fetch("{{ route('profile.notifikasi') }}")
            .then(res => res.json())
            .then(res => {
                notifList.innerHTML = '';
                if (res.data && res.data.length > 0) {
                    res.data.forEach(notif => {
                        let icon = '';
                        if (notif.status === 'diterima') {
                            icon = `<i class="fa-solid fa-circle-check text-success me-2"></i>`;
                        } else if (notif.status === 'ditolak') {
                            icon = `<i class="fa-solid fa-circle-xmark text-danger me-2"></i>`;
                        }
                        notifList.innerHTML += `
                            <div class="p-3 border-bottom bg-white d-flex justify-content-between align-items-center" data-id="${notif.mhs_notifikasi_id}">
                                <div class="d-flex align-items-center w-100 bg-white">
                                    <span class="d-flex align-items-center justify-content-center mx-2 bg-transparent" style="font-size:1.7rem; min-width:2.2rem;">
                                        ${
                                            notif.status === 'diterima'
                                                ? `<i class="fa-solid fa-circle-check text-success bg-transparent"></i>`
                                                : `<i class="fa-solid fa-circle-xmark text-danger bg-transparent"></i>`
                                        }
                                    </span>
                                    <div class="flex-grow-1 bg-transparent">
                                        <div class="bg-transparent">${notif.deskripsi}</div>
                                        <small class="text-muted bg-transparent">${formatDate(notif.created_at)}</small>
                                    </div>
                                    <button class="btn btn-sm btn-link text-muted ms-2 px-1 py-0 align-self-center" onclick="deleteNotifikasi(${notif.mhs_notifikasi_id}, this)" title="Hapus">
                                        <i class="fas fa-times fa-lg"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    notifList.innerHTML = `<div class="text-center py-3 text-muted bg-white">Tidak ada notifikasi.</div>`;
                }
            })
            .catch(() => {
                notifList.innerHTML = `<div class="text-center py-3 text-danger">Gagal memuat notifikasi.</div>`;
            });
    }

    function deleteNotifikasi(id, btn) {
        btn.disabled = true;
        fetch(`/profile/notifikasi/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                // Remove the notification from the list
                const notifDiv = btn.closest('[data-id]');
                if (notifDiv) notifDiv.remove();
                // If no more notifications, show empty state
                if (document.querySelectorAll('#notifList [data-id]').length === 0) {
                    document.getElementById('notifList').innerHTML = `<div class="text-center py-3 text-muted bg-white">Tidak ada notifikasi.</div>`;
                }
            } else {
                alert(res.error || 'Gagal menghapus notifikasi.');
                btn.disabled = false;
            }
        })
        .catch(() => {
            alert('Gagal menghapus notifikasi.');
            btn.disabled = false;
        });
    }

    // Format date (simple, you can improve as needed)
    function formatDate(dateStr) {
        const date = new Date(dateStr);
        if (isNaN(date)) return '';
        const now = new Date();
        const diff = Math.floor((now - date) / 1000);
        if (diff < 60) return 'Baru saja';
        if (diff < 3600) return `${Math.floor(diff/60)} menit lalu`;
        if (diff < 86400) return `${Math.floor(diff/3600)} jam lalu`;
        if (diff < 604800) return `${Math.floor(diff/86400)} hari lalu`;
        return date.toLocaleDateString();
    }
</script>

<!-- Script untuk preview foto -->
<script>
    document.getElementById('foto_profile').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.querySelector('.modal-body img').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Auto close modal setelah sukses update
    @if (session('success'))
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('editProfileModal'));
                if (modal) {
                    modal.hide();
                }
            }, 2000);
        });
    @endif

    // Pastikan hanya satu kompetensi yang bisa dipilih
    function ensureSingleKompetensi(checkbox) {
        const checkboxes = document.querySelectorAll('input[name="kompetensi_id"]');
        checkboxes.forEach((cb) => {
            if (cb !== checkbox) {
                cb.checked = false;
            }
        });
    }
</script>
