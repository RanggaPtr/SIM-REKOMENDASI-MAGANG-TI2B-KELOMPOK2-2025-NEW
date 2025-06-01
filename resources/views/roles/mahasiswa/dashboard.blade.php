{{-- filepath: resources/views/roles/mahasiswa/dashboard.blade.php --}}
@extends('layouts.template')
@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush
@section('content')
    @if (session('error'))
        <script>
            Swal.fire({
                title: 'Oops...',
                text: '{{ session('error') }}',
                confirmButtonColor: '#d33',
                background: '#fff0f0',
                timer: 7000,
                timerProgressBar: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            Swal.fire({
                title: 'Berhasil',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6',
                background: '#f0fff0',
                timer: 5000,
                timerProgressBar: true,
                allowOutsideClick: true,
                allowEscapeKey: true,
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                title: 'Validasi Gagal',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#d33',
                background: '#fff0f0',
                timer: 8000,
                timerProgressBar: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
            });
        </script>
    @endif


    <div class="bg-transparent min-vh-100 pt-1">
        <div class="row g-3">
            {{-- Main Content --}}
            <div class="col-md-9">
                <div class="d-flex mb-3">
                    <input type="text" class="form-control me-3 rounded-3" placeholder="Search" style="flex: 12">
                    <select class="form-select w-auto me-3 rounded-3" style="flex: 2; cursor: pointer;">
                        <option>Sort: Ascending</option>
                        <option>Sort: Descending</option>
                    </select>
                    <button class="rounded-3"
                        style="flex: 1; border: 1px solid #DEE2E6; background-color: #fff; color: #212529; padding: 0.375rem 0.75rem;">
                        <i class="fa-solid fa-book-bookmark"></i>
                    </button>
                </div>
                @include('component.intern-cards', ['lowongans' => $lowongans])
            </div>

            {{-- Sidebar Filter --}}
            <div class="col-md-3 rounded-3">
                <div class="bg-white rounded py-3 px-4 shadow-sm rounded-3">
                    <div class="mb-3 bg-transparent">
                        {{-- Kompetensi Dropdown --}}
                        <button
                            class="d-flex align-items-center justify-content-between w-100 mb-2 bg-transparent pr-2 py-2"
                            type="button" data-bs-toggle="collapse" data-bs-target="#kompetensiDropdown"
                            aria-expanded="false" aria-controls="kompetensiDropdown"
                            style="border: none; box-shadow: none;">
                            <span class="bg-transparent fw-bold">Pilih Kompetensi</span>
                            <i class="fa-solid fa-chevron-down bg-transparent"></i>
                        </button>
                        <div class="collapse bg-transparent show mb-3" id="kompetensiDropdown">
                            <input type="text" class="form-control mb-2" placeholder="Cari kompetensi..."
                                id="searchKompetensi" onkeyup="filterList('kompetensi')">
                            <div class="bg-transparent" style="max-height: 200px; overflow-y: auto;" id="kompetensiList">
                                @foreach ($kompetensis as $kompetensi)
                                    <div class="form-check bg-transparent">
                                        <input class="form-check-input" type="checkbox"
                                            value="{{ $kompetensi->kompetensi_id }}"
                                            id="kompetensi{{ $kompetensi->kompetensi_id }}">
                                        <label class="form-check-label bg-transparent"
                                            for="kompetensi{{ $kompetensi->kompetensi_id }}">{{ $kompetensi->nama }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        {{-- Keahlian Dropdown --}}
                        <div class="mb-3 bg-transparent">
                            <button
                                class="d-flex align-items-center justify-content-between w-100 mb-2 bg-transparent pr-2 py-2"
                                type="button" data-bs-toggle="collapse" data-bs-target="#keahlianDropdown"
                                aria-expanded="false" aria-controls="keahlianDropdown"
                                style="border: none; box-shadow: none;">
                                <span class="bg-transparent fw-bold">Pilih Keahlian</span>
                                <i class="fa-solid fa-chevron-down bg-transparent"></i>
                            </button>
                            <div class="collapse bg-transparent mb-3" id="keahlianDropdown">
                                <input type="text" class="form-control mb-2" placeholder="Cari keahlian..."
                                    id="searchKeahlian" onkeyup="filterList('keahlian')">
                                <div style="max-height: 200px; overflow-y: auto; background-color:transparent;"
                                    id="keahlianList">
                                    @foreach ($keahlians as $keahlian)
                                        <div class="form-check bg-transparent">
                                            <input class="form-check-input" type="checkbox"
                                                value="{{ $keahlian->keahlian_id }}"
                                                id="keahlian{{ $keahlian->keahlian_id }}">
                                            <label class="form-check-label bg-transparent"
                                                for="keahlian{{ $keahlian->keahlian_id }}">{{ $keahlian->nama }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            {{-- Rating --}}
                            <div class="mb-3 bg-transparent">
                                <span class="bg-transparent fw-bold bb-transparent">Rating</span>
                                <div class="bg-transparent">
                                    {{-- Loop untuk 5 bintang --}}
                                    @for ($i = 1; $i <= 5; $i++)
                                        <div class="form-check bg-transparent mb-1 bg-transparent">
                                            <input class="form-check-input" type="checkbox" id="rating{{ $i }}"
                                                value="{{ $i }}">
                                            <label class="form-check-label bg-transparent" for="rating{{ $i }}">
                                                @for ($star = 1; $star <= $i; $star++)
                                                    <i class="fa-solid fa-star text-warning bg-transparent"></i>
                                                @endfor
                                                ({{ $i }})
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                            {{-- Regencies (Kabupaten/Kota) Dropdown --}}
                            <div class="mb-3 bg-transparent">
                                <button
                                    class="d-flex align-items-center justify-content-between w-100 mb-2 bg-transparent pr-2 py-2"
                                    type="button" data-bs-toggle="collapse" data-bs-target="#regencyDropdown"
                                    aria-expanded="false" aria-controls="regencyDropdown"
                                    style="border: none; box-shadow: none;">
                                    <span class="bg-transparent fw-bold">Pilih Kabupaten/Kota</span>
                                    <i class="fa-solid fa-chevron-down bg-transparent"></i>
                                </button>
                                <div class="collapse bg-transparent" id="regencyDropdown">
                                    <input type="text" class="form-control mb-2" placeholder="Cari kabupaten..."
                                        id="searchRegency">
                                    <div class="bg-transparent" style="max-height: 200px; overflow-y: auto;"
                                        id="regencyList">
                                        {{-- Data dari JS akan ditampilkan di sini --}}
                                    </div>
                                </div>
                            </div>
                            <div class="bg-transparent fw-bold">Tunjangan</div>
                            <div class="form-check bg-transparent">
                                <input class="form-check-input" type="checkbox" id="tunjangan0">
                                <label class="form-check-label bg-transparent" for="tunjangan0">Rp. 0</label>
                            </div>
                            <div class="form-check bg-transparent">
                                <input class="form-check-input" type="checkbox" id="tunjangan1">
                                <label class="form-check-label bg-transparent" for="tunjangan1">Rp. 0 - Rp. 500k</label>
                            </div>
                            <div class="form-check bg-transparent">
                                <input class="form-check-input" type="checkbox" id="tunjangan2">
                                <label class="form-check-label bg-transparent" for="tunjangan2">Rp. 500k - Rp.
                                    1.500k</label>
                            </div>
                            {{-- Periode --}}
                            <div class="bg-transparent fw-bold mt-3">Periode</div>
                            @foreach ($periodes as $periode)
                                <div class="form-check bg-transparent">
                                    <input class="form-check-input" type="checkbox" value="{{ $periode->periode_id }}"
                                        id="periode{{ $periode->periode_id }}">
                                    <label class="form-check-label bg-transparent"
                                        for="periode{{ $periode->periode_id }}">
                                        {{ $periode->nama }}
                                    </label>
                                </div>
                            @endforeach

                            {{-- Skema --}}
                            <div class="bg-transparent fw-bold mt-3">Skema</div>
                            @foreach ($skemas as $skema)
                                <div class="form-check bg-transparent">
                                    <input class="form-check-input" type="checkbox" value="{{ $skema->skema_id }}"
                                        id="skema{{ $skema->skema_id }}">
                                    <label class="form-check-label bg-transparent" for="skema{{ $skema->skema_id }}">
                                        {{ $skema->nama }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endsection

        @push('scripts')
            <!-- SweetAlert2 -->
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <script>
                let regencyOffset = 0;
                const regencyLimit = 50;
                let regencyLoading = false;
                let regencyEnd = false;
                let regencyQuery = '';

                function toTitleCase(str) {
                    return str.toLowerCase().replace(/\b\w/g, c => c.toUpperCase());
                }

                const apiToken = document.querySelector('meta[name="api-token"]')?.content;
                const regencyList = document.getElementById('regencyList');
                const searchRegency = document.getElementById('searchRegency');

                function fetchRegencies(reset = false) {
                    if (regencyLoading || regencyEnd) return;
                    regencyLoading = true;
                    if (reset) {
                        regencyOffset = 0;
                        regencyEnd = false;
                        regencyList.innerHTML = '';
                    }
                    fetch(`/api/regencies?q=${encodeURIComponent(regencyQuery)}&offset=${regencyOffset}&limit=${regencyLimit}`, {
                            headers: {
                                'Authorization': `Bearer ${apiToken}`,
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.length < regencyLimit) regencyEnd = true;
                            data.forEach(item => {
                                const div = document.createElement('div');
                                div.className = 'form-check bg-transparent';
                                div.innerHTML = `
                <input class="form-check-input" type="radio" name="regency" value="${item.id}" id="regency${item.id}">
                <label class="form-check-label bg-transparent" for="regency${item.id}">${toTitleCase(item.name)}</label>
            `;
                                regencyList.appendChild(div);
                            });
                            regencyOffset += regencyLimit;
                            regencyLoading = false;
                        });
                }

                // Search event
                searchRegency.addEventListener('keyup', function() {
                    regencyQuery = this.value;
                    fetchRegencies(true);
                });

                // Infinite scroll event
                regencyList.addEventListener('scroll', function() {
                    if (regencyList.scrollTop + regencyList.clientHeight >= regencyList.scrollHeight - 10) {
                        fetchRegencies();
                    }
                });

                // Initial load
                fetchRegencies(true);
            </script>
            <script>
                function filterList(type) {
                    const input = document.getElementById('search' + capitalize(type));
                    const filter = input.value.toLowerCase();
                    const list = document.getElementById(type + 'List');
                    const items = list.getElementsByClassName('form-check');

                    for (let i = 0; i < items.length; i++) {
                        const label = items[i].querySelector('label');
                        if (label) {
                            const txtValue = label.textContent || label.innerText;
                            items[i].style.display = txtValue.toLowerCase().indexOf(filter) > -1 ? '' : 'none';
                        }
                    }
                }

                function capitalize(str) {
                    return str.charAt(0).toUpperCase() + str.slice(1);
                }
            </script>
        @endpush
