<div class="container py-2 px-0 d-flex justify-content-between align-items-center text-dark">
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold" id="editProfileModalLabel">Edit Profil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Foto Profil -->
                    <div class="mb-3 text-center">
                        <img src="{{ Auth::user()->foto_profile ? url('/storage/' . Auth::user()->foto_profile) : url('/images/profile.png') }}"
                            alt="Profile"
                            class="rounded-circle mb-3"
                            style="width: 100px; height: 100px; object-fit: cover;">
                        <div>
                            <label for="foto_profile" class="form-label">Ganti Foto Profil</label>
                            <input type="file" class="form-control" id="foto_profile" name="foto_profile">
                            @error('foto_profile')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- Nama -->
                    <div class="mb-3">
                        <label for="nama" class="form-label fw-bold">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', Auth::user()->nama) }}" required>
                        @error('nama')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @endif
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                        @error('email')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @endif
                    </div>

                    <!-- Field Khusus Berdasarkan Role -->
                    @if (Auth::user()->role === 'dosen')
                    @php
                    $dosen = \App\Models\DosenModel::where('user_id', Auth::user()->user_id)->first();
                    @endphp
                    @if ($dosen)
                    <div class="mb-3">
                        <label for="nik" class="form-label fw-bold">NIK</label>
                        <input type="text" class="form-control" id="nik" name="nik" value="{{ old('nik', $dosen->nik) }}" required>
                        @error('nik')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="prodi_id" class="form-label fw-bold">Program Studi</label>
                        <select class="form-control" id="prodi_id" name="prodi_id" required>
                            @foreach (\App\Models\ProgramStudiModel::all() as $prodi)
                            <option value="{{ $prodi->prodi_id }}" {{ old('prodi_id', $dosen->prodi_id) == $prodi->prodi_id ? 'selected' : '' }}>
                                {{ $prodi->nama_prodi }}
                            </option>
                            @endforeach
                        </select>
                        @error('prodi_id')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @endif
                    </div>
                    @else
                    <div class="alert alert-warning">
                        Data dosen belum lengkap. Silahkan lengkapi data di bawah ini.
                    </div>
                    <div class="mb-3">
                        <label for="nik" class="form-label fw-bold">NIK</label>
                        <input type="text" class="form-control" id="nik" name="nik" value="{{ old('nik') }}" required>
                        @error('nik')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="prodi_id" class="form-label fw-bold">Program Studi</label>
                        <select class="form-control" id="prodi_id" name="prodi_id" required>
                            @foreach (\App\Models\ProgramStudiModel::all() as $prodi)
                            <option value="{{ $prodi->prodi_id }}" {{ old('prodi_id') == $prodi->prodi_id ? 'selected' : '' }}>
                                {{ $prodi->nama_prodi }}
                            </option>
                            @endforeach
                        </select>
                        @error('prodi_id')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @endif
                    </div>
                    @endif
                    @elseif (Auth::user()->role === 'mahasiswa')
                    @php
                    $mahasiswa = \App\Models\MahasiswaModel::where('user_id', Auth::user()->user_id)->first();
                    @endphp
                    <div class="mb-3">
                        <label for="nim" class="form-label fw-bold">NIM</label>
                        <input type="text" class="form-control" id="nim" name="nim" value="{{ old('nim', $mahasiswa->nim) }}" required>
                        @error('nim')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="program_studi_id" class="form-label fw-bold">Program Studi</label>
                        <select class="form-control" id="program_studi_id" name="program_studi_id" required>
                            @foreach (\App\Models\ProgramStudiModel::all() as $prodi)
                            <option value="{{ $prodi->prodi_id }}" {{ old('program_studi_id', $mahasiswa->program_studi_id) == $prodi->prodi_id ? 'selected' : '' }}>
                                {{ $prodi->nama_prodi }}
                            </option>
                            @endforeach
                        </select>
                        @error('program_studi_id')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="wilayah_id" class="form-label fw-bold">Wilayah</label>
                        <select class="form-control" id="wilayah_id" name="wilayah_id" required>
                            @foreach (\App\Models\WilayahModel::all() as $wilayah)
                            <option value="{{ $wilayah->wilayah_id }}" {{ old('wilayah_id', $mahasiswa->wilayah_id) == $wilayah->wilayah_id ? 'selected' : '' }}>
                                {{ $wilayah->nama_wilayah }}
                            </option>
                            @endforeach
                        </select>
                        @error('wilayah_id')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="ipk" class="form-label fw-bold">IPK</label>
                        <input type="number" step="0.01" class="form-control" id="ipk" name="ipk" value="{{ old('ipk', $mahasiswa->ipk) }}" required>
                        @error('ipk')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @endif
                    </div>
                    @elseif (Auth::user()->role === 'perusahaan')
                    @php
                    $perusahaan = \App\Models\PerusahaanModel::where('user_id', Auth::user()->user_id)->first();
                    @endphp
                    <div class="mb-3">
                        <label for="nama_perusahaan" class="form-label fw-bold">Nama Perusahaan</label>
                        <input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan" value="{{ old('nama_perusahaan', $perusahaan->nama_perusahaan) }}" required>
                        @error('nama_perusahaan')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @endif
                    </div>
                    @endif

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">Password Baru (opsional)</label>
                        <input type="password" class="form-control" id="password" name="password">
                        @error('password')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @endif
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label fw-bold">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>

                    <!-- Tombol -->
                    <div class="d-flex justify-content-center gap-3">
                        <button type="submit" class="btn btn-primary text-light fw-bold px-5">Simpan</button>
                        <button type="button" class="btn btn-outline-secondary px-5" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>