{{-- filepath: resources/views/roles/mahasiswa/dashboard.blade.php --}}
@extends('layouts.template')

@section('content')
    <div class="bg-transparent min-vh-100 pt-1">
        <div class="row g-3">
            {{-- Main Content --}}
            <div class="col-md-9">
                <div class="d-flex mb-3">
                    <input type="text" class="form-control me-3 rounded-3" placeholder="Search" style="flex: 12">
                    <select class="form-select w-auto me-3 rounded-3" style="flex: 2; cursor: pointer;">
                        <option>Sort by: Newest</option>
                        <option>Sort by: Relevance</option>
                        <option>Sort by: Rating</option>
                    </select>
                    <button class="rounded-3" style="flex: 1; "><i class="fa-solid fa-book-bookmark"></i></button>
                </div>
                @include('component.intern-cards', ['lowongans' => $lowongans])
            </div>

            {{-- Sidebar Filter --}}
            <div class="col-md-3 rounded-3">
                <div class="bg-white rounded p-3 shadow-sm rounded-3">
                    <h5 class="bg-transparent fw-bold">Kategori</h5>
                    <input type="text" class="form-control mb-2" placeholder="Cari kategori...">
                    <div class="bg-transparent mb-3">
                        <div class="form-check bg-transparent" style="align-content: center; align-items:center" >
                            <input class="form-check-input" type="checkbox" id="react">
                            <label class="form-check-label bg-transparent" for="react">React</label>
                        </div>
                        <div class="form-check bg-transparent">
                            <input class="form-check-input" type="checkbox" id="laravel">
                            <label class="form-check-label bg-transparent" for="laravel">Laravel</label>
                        </div>
                        <div class="form-check bg-transparent">
                            <input class="form-check-input" type="checkbox" id="nextjs">
                            <label class="form-check-label bg-transparent" for="nextjs">Next Js</label>
                        </div>
                        <div class="form-check bg-transparent">
                            <input class="form-check-input" type="checkbox" id="spring">
                            <label class="form-check-label bg-transparent" for="spring">Spring</label>
                        </div>
                    </div>
                    {{-- Rating --}}
                    <div class="mb-3 bg-transparent">
                        <span class="bg-transparent fw-bold bb-transparent">Rating</span>
                        <div class="bg-transparent">
                            {{-- Loop untuk 5 bintang --}}
                            @for ($i = 1; $i <= 5; $i++)
                                <div class="form-check bg-transparent mb-1 bg-transparent">
                                    <input class="form-check-input" type="checkbox" id="rating{{ $i }}" value="{{ $i }}">
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
                    <h5 class="bg-transparent fw-bold">Tunjangan</h5>
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
                        <label class="form-check-label bg-transparent" for="tunjangan2">Rp. 500k - Rp. 1.500k</label>
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
