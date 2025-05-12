@extends('layouts.template')

@section('title', 'Admin Dashboard')

@section('content')
    <h1>Dashboard Admin l</h1>
    <p>Selamat datang, Mahmduuuud!</p>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin.js') }}"></script>
@endpush