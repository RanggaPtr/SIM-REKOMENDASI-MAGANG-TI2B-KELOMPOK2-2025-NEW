@extends('layouts.template')

@section('title', 'Profil Mahasiswa')

@section('content')
    <h1>Profil Mahasiswa</h1>
    <div class="card p-4">
        <div class="mb-3">
            <strong>Nama:</strong> {{ Auth::user()->nama }}
        </div>
        <div class="mb-3">
            <strong>Email:</strong> {{ Auth::user()->email }}
        </div>
        <div class="mb-3">
            <strong>NIM/NIK:</strong> {{ Auth::user()->nim_nik }}
        </div>
        <div class="mb-3">
            <strong>No. Telepon:</strong> {{ Auth::user()->no_telepon ?? 'Belum diisi' }}
        </div>
        <div class="mb-3">
            <strong>Alamat:</strong> {{ Auth::user()->alamat ?? 'Belum diisi' }}
        </div>

        <a href="{{ route('mahasiswa.edit_profile') }}" class="btn btn-primary">Edit Profil</a>
        <a href="{{ route('mahasiswa.change_password') }}" class="btn btn-warning">Ganti Password</a>
    </div>
@endsection
