{{-- filepath: resources/views/roles/mahasiswa/dashboard.blade.php --}}
@extends('layouts.template')

@section('content')
    <div class="bg-transparent min-vh-100 pt-1">
        <div class="row g-2">
            {{-- Main Content --}}
            <div class="col-md-9">
                <div class="d-flex mb-3">
                    <input type="text" class="form-control me-2 rounded-3" placeholder="Search" style="flex: 12">
                    <select class="form-select w-auto me-2 rounded-3" style="flex: 2">
                        <option>Sort by: Newest</option>
                    </select>
                    <button class="btn btn-outline-secondary rounded-3" style="flex: 1;"><i class="bi bi-bookmark"></i></button>
                </div>
                @include('component.intern-cards', ['lowongans' => $lowongans])
            </div>

            {{-- Sidebar Filter --}}
            <div class="col-md-3 rounded-3">
                <div class="bg-white rounded p-3 shadow-sm rounded-3">
                    <h5>Kategori</h5>
                    <input type="text" class="form-control mb-2" placeholder="Cari kategori...">
                    <div class="bg-transparent">
                        <div class="form-check bg-transparent" style="align-content: center; align-items:center" >
                            <input class="form-check-input" type="checkbox" id="react">
                            <label class="form-check-label" for="react">React</label>
                        </div>
                        <div class="form-check bg-transparent">
                            <input class="form-check-input" type="checkbox" id="laravel">
                            <label class="form-check-label" for="laravel">Laravel</label>
                        </div>
                        <div class="form-check bg-transparent">
                            <input class="form-check-input" type="checkbox" id="nextjs">
                            <label class="form-check-label" for="nextjs">Next Js</label>
                        </div>
                        <div class="form-check bg-transparent">
                            <input class="form-check-input" type="checkbox" id="spring">
                            <label class="form-check-label" for="spring">Spring</label>
                        </div>
                        <div class="form-check bg-transparent">
                            <input class="form-check-input" type="checkbox" id="rust">
                            <label class="form-check-label" for="rust">Rust</label>
                        </div>
                        <div class="form-check bg-transparent">
                            <input class="form-check-input" type="checkbox" id="golang">
                            <label class="form-check-label" for="golang">Golang</label>
                        </div>
                    </div>
                    <hr>
                    <h6>Tunjangan</h6>
                    <div class="form-check bg-transparent">
                        <input class="form-check-input" type="checkbox" id="tunjangan0">
                        <label class="form-check-label" for="tunjangan0">Rp. 0</label>
                    </div>
                    <div class="form-check bg-transparent">
                        <input class="form-check-input" type="checkbox" id="tunjangan1">
                        <label class="form-check-label" for="tunjangan1">Rp. 0 - Rp. 500k</label>
                    </div>
                    <div class="form-check bg-transparent">
                        <input class="form-check-input" type="checkbox" id="tunjangan2">
                        <label class="form-check-label" for="tunjangan2">Rp. 500k - Rp. 1.500k</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
