@extends('layouts.template')

@section('title', 'Dosen Dashboard')

@section('content')
    <h1>Dashboard Dosen Pembimbing</h1>
    <p>Selamat datang, {{ Auth::user()->nama }}!</p>
@endsection

@push('scripts')
    <script src="{{ asset('js/dosen.js') }}"></script>
@endpush