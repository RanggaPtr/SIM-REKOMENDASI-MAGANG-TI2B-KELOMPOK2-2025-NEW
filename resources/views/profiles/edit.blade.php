@extends('layouts.template')

@section('title', 'Edit Profil')

@section('content')
    <div class="container">
        <h1>Edit Profil</h1>
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}">
            </div>
            <!-- Tambah field lain sesuai kebutuhan -->
            <button type="submit" class="btn btn-success">Simpan</button>
        </form>
    </div>
@endsection
