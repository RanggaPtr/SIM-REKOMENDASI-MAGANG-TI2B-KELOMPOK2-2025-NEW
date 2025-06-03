<div class="container-fluid py-2 px-0 d-flex justify-content-between align-items-center text-dark">
    <!-- Kiri: Beranda -->
    <div class="fs-4 text-secondary"><b>Beranda</b></div>

    <!-- Kanan: Bell, Profile, dan Edit Profile -->
    <div class="d-flex align-items-center gap-2 me-2">
        <i class="fas fa-bell me-5"></i>
        <img src="{{ Auth::user()->foto_profile ? url('/storage/' . Auth::user()->foto_profile) : url('/images/profile.png') }}"
            alt="Profile"
            style="width: 40px; height: 40px; border-radius: 50%;">
        <div class="column ms-2">
            <b class="mb-0">{{ Auth::user()->nama }}</b>
            <p class="mb-0" style="font-size: 13px;">{{ ucfirst(Auth::user()->role) }}</p>
        </div>
        <!-- Tombol Edit Profile -->
        <button class="btn btn-sm btn-outline-primary ms-2" data-bs-toggle="modal" data-bs-target="#editProfileModal">
            <i class="fas fa-edit"></i> Edit
        </button>
    </div>
</div>

<!-- Modal Edit Profile -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow-lg">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold" id="editProfileModalLabel" style="color: black;background-color:none;">Edit Profil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
                            alt="Profile"
                            class="rounded-circle mb-3"
                            style="width: 100px; height: 100px; object-fit: cover;">
                        <!-- <label for="foto_profile" class="form-label">Ganti Foto Profil</label> -->
                        <input type="file" class="form-control" id="foto_profile" name="foto_profile" accept="image/*">
                        @error('foto_profile')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nama -->
                    <div class="mb-3">
                        <label for="nama" class="form-label fw-bold">Nama</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror"
                            id="nama" name="nama" value="{{ old('nama', Auth::user()->nama) }}" required>
                        @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label fw-bold">Username</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                            id="username" name="username" value="{{ old('username', Auth::user()->username) }}" required>
                        @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- No Telepon -->
                    <div class="mb-3">
                        <label for="no_telepon" class="form-label fw-bold">No Telepon</label>
                        <input type="text" class="form-control @error('no_telepon') is-invalid @enderror"
                            id="no_telepon" name="no_telepon" value="{{ old('no_telepon', Auth::user()->no_telepon) }}">
                        @error('no_telepon')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="mb-3">
                        <label for="alamat" class="form-label fw-bold">Alamat</label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror"
                            id="alamat" name="alamat" rows="3">{{ old('alamat', Auth::user()->alamat) }}</textarea>
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
                            id="nim" name="nim" value="{{ old('nim', $mahasiswa->nim ?? '') }}" required>
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
                        <select class="form-select @error('skema_id') is-invalid @enderror"
                            id="skema_id" name="skema_id" required>
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
                        <select class="form-select @error('periode_id') is-invalid @enderror"
                            id="periode_id" name="periode_id" required>
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
                            class="form-control @error('ipk') is-invalid @enderror"
                            id="ipk" name="ipk" value="{{ old('ipk', $mahasiswa->ipk ?? '') }}" required>
                        @error('ipk')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @elseif (Auth::user()->role === 'perusahaan')
                    @php
                    $perusahaan = \App\Models\PerusahaanModel::where('user_id', Auth::user()->user_id)->first();
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
                            id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label fw-bold">Konfirmasi Password</label>
                        <input type="password" class="form-control"
                            id="password_confirmation" name="password_confirmation"
                            placeholder="Ulangi password baru">
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
    @if(session('success'))
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