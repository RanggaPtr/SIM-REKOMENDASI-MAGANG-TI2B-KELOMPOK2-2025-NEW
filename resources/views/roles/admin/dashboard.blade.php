@extends('layouts.template')

@section('title', 'Admin Dashboard')

@section('content')
    <h1>Dashboard Admin </h1>
    <p>Selamat datang, {{ Auth::user()->nama }}!</p>
    
@endsection

@push('scripts')
    <script src="{{ asset('js/admin.js') }}"></script>
@endpush