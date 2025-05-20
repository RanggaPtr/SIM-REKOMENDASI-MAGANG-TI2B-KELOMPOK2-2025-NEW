@extends('layouts.template')

@section('title', 'Lihat Profil')

@section('content')
    <div class="container">
        <h1>Profil Saya</h1>
        <p>Nama: {{ $user->nama }}</p>
        <p>Email: {{ $user->email }}</p>
        <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profil</a>
    </div>
@endsection
