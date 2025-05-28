{{-- filepath: resources/views/roles/mahasiswa/dashboard.blade.php --}}
@extends('layouts.template')

@section('content')
    <div class="bg-transparent pt-1">
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
                <div class="bg-white rounded p-3 shadow-sm rounded-3">
                    {{-- Kompetensi Dropdown --}}
                    <div class="mb-3 bg-transparent">
                        <button
                            class="d-flex align-items-center justify-content-between w-100 mb-2 bg-transparent pr-2 py-2"
                            type="button" data-bs-toggle="collapse" data-bs-target="#kompetensiDropdown"
                            aria-expanded="false" aria-controls="kompetensiDropdown"
                            style="border: none; box-shadow: none;">
                            <span class="bg-transparent fw-bold">Pilih Kompetensi</span>
                            <i class="fa-solid fa-chevron-down bg-transparent"></i>
                        </button>
                        <div class="collapse bg-transparent show" id="kompetensiDropdown">
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
                    </div>
                    {{-- Keahlian Dropdown --}}
                    <div class="mb-3 bg-transparent">
                        <button
                            class="d-flex align-items-center justify-content-between w-100 mb-2 bg-transparent pr-2 py-2"
                            type="button" data-bs-toggle="collapse" data-bs-target="#keahlianDropdown"
                            aria-expanded="false" aria-controls="keahlianDropdown" style="border: none; box-shadow: none;">
                            <span class="bg-transparent fw-bold">Pilih Keahlian</span>
                            <i class="fa-solid fa-chevron-down bg-transparent"></i>
                        </button>
                        <div class="collapse bg-transparent" id="keahlianDropdown">
                            <input type="text" class="form-control mb-2" placeholder="Cari keahlian..."
                                id="searchKeahlian" onkeyup="filterList('keahlian')">
                            <div style="max-height: 200px; overflow-y: auto; background-color:transparent;"
                                id="keahlianList">
                                @foreach ($keahlians as $keahlian)
                                    <div class="form-check bg-transparent">
                                        <input class="form-check-input" type="checkbox" value="{{ $keahlian->keahlian_id }}"
                                            id="keahlian{{ $keahlian->keahlian_id }}">
                                        <label class="form-check-label bg-transparent"
                                            for="keahlian{{ $keahlian->keahlian_id }}">{{ $keahlian->nama }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    {{-- Regencies (Kabupaten/Kota) Dropdown --}}
                    <div class="mb-3 bg-transparent">
                        <button
                            class="d-flex align-items-center justify-content-between w-100 mb-2 bg-transparent pr-2 py-2"
                            type="button" data-bs-toggle="collapse" data-bs-target="#regencyDropdown" aria-expanded="false"
                            aria-controls="regencyDropdown" style="border: none; box-shadow: none;">
                            <span class="bg-transparent fw-bold">Pilih Kabupaten/Kota</span>
                            <i class="fa-solid fa-chevron-down bg-transparent"></i>
                        </button>
                        <div class="collapse bg-transparent" id="regencyDropdown">
                            <input type="text" class="form-control mb-2" placeholder="Cari kabupaten..."
                                id="searchRegency">
                            <div class="bg-transparent" style="max-height: 200px; overflow-y: auto;" id="regencyList">
                                {{-- Data dari JS akan ditampilkan di sini --}}
                            </div>
                        </div>
                    </div>
                    {{-- Tunjangan --}}
                    <span class="bg-transparent fw-bold">Tunjangan</span>
                    <div class="form-check bg-transparent">
                        <input class="form-check-input" type="checkbox" id="tunjangan1">
                        <label class="form-check-label bg-transparent" for="tunjangan1">Rp. 0 - Rp. 300k</label>
                    </div>
                    <div class="form-check bg-transparent">
                        <input class="form-check-input" type="checkbox" id="tunjangan2">
                        <label class="form-check-label bg-transparent" for="tunjangan2">Rp. 300k - Rp. 750k</label>
                    </div>
                    <div class="form-check bg-transparent">
                        <input class="form-check-input" type="checkbox" id="tunjangan2">
                        <label class="form-check-label bg-transparent" for="tunjangan2">Rp. 750k - Rp. 1.000k</label>
                    </div>
                    <div class="form-check bg-transparent mb-3">
                        <input class="form-check-input" type="checkbox" id="tunjangan2">
                        <label class="form-check-label bg-transparent" for="tunjangan2">Rp. 1.000k <</label>
                    </div>

                    {{-- Periode --}}
                    <span class="bg-transparent fw-bold">Periode</span>
                    @foreach ($periodes as $periode)
                        <div class="form-check bg-transparent">
                            <input class="form-check-input" type="checkbox" value="{{ $periode->periode_id }}" id="periode{{ $periode->periode_id }}">
                            <label class="form-check-label bg-transparent" for="periode{{ $periode->periode_id }}">
                                {{ $periode->nama }}
                            </label>
                        </div>
                    @endforeach

                    {{-- Skema --}}
                    <div class="bg-transparent fw-bold mt-3">Skema</div>
                    @foreach ($skemas as $skema)
                        <div class="form-check bg-transparent">
                            <input class="form-check-input" type="checkbox" value="{{ $skema->skema_id }}" id="skema{{ $skema->skema_id }}">
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
<script>
    function toTitleCase(str) {
        return str.toLowerCase().replace(/\b\w/g, c => c.toUpperCase());
    }

    const apiToken = document.querySelector('meta[name="api-token"]')?.content;

    document.getElementById('searchRegency').addEventListener('keyup', function() {
        const query = this.value;
        fetch(`/api/regencies?q=${encodeURIComponent(query)}`, {
            headers: {
                'Authorization': `Bearer ${apiToken}`,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const wrapper = document.getElementById('regencyList');
            wrapper.innerHTML = '';
            data.forEach(item => {
                const div = document.createElement('div');
                div.className = 'form-check bg-transparent';
                div.innerHTML = `
                    <input class="form-check-input" type="radio" name="regency" value="${item.id}" id="regency${item.id}">
                    <label class="form-check-label bg-transparent" for="regency${item.id}">${toTitleCase(item.name)}</label>
                `;
                wrapper.appendChild(div);
            });
        });
    });

    // Trigger fetch awal saat load
    document.getElementById('searchRegency').dispatchEvent(new Event('keyup'));
</script>
@endpush
