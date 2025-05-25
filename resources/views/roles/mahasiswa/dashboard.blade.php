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
                        <div class="form-check bg-transparent">
                            <input class="form-check-input" type="checkbox" id="rust">
                            <label class="form-check-label bg-transparent" for="rust">Rust</label>
                        </div>
                        <div class="form-check bg-transparent">
                            <input class="form-check-input" type="checkbox" id="golang">
                            <label class="form-check-label bg-transparent" for="golang">Golang</label>
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
                </div>
            </div>
        </div>
    </div>
@endsection
