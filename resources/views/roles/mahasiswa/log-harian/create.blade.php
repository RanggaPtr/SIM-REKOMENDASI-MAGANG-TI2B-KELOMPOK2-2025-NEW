@extends('layouts.template')

@section('title', 'Tambah Log Harian')

@section('content')
    <style>
        .custom-form-container {
            max-width: 500px;
            margin: 30px auto;
            padding: 28px 24px;
            background: #f9f9f9;
            border-radius: 12px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.08);
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .custom-form-container h2 {
            text-align: center;
            margin-bottom: 24px;
            color: #2c3e50;
        }
        .custom-form-group {
            margin-bottom: 18px;
        }
        .custom-form-label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
            color: #34495e;
        }
        .custom-form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
            background: #fff;
            transition: border-color 0.2s;
        }
        .custom-form-control:focus {
            border-color: #007bff;
            outline: none;
        }
        textarea.custom-form-control {
            resize: vertical;
            min-height: 80px;
        }
        .text-danger {
            font-size: 13px;
        }
        .custom-btn-primary {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 12px 28px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-right: 10px;
            transition: background 0.2s;
        }
        .custom-btn-primary:hover {
            background: #0056b3;
        }
        .custom-btn-secondary {
            background: #6c757d;
            color: #fff;
            border: none;
            padding: 12px 28px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
        }
        .custom-btn-secondary:hover {
            background: #495057;
            color: #fff;
        }
        @media (max-width: 600px) {
            .custom-form-container {
                padding: 14px 5px;
            }
        }
    </style>

    <div class="custom-form-container">
        <h2>Tambah Log Harian</h2>
        <form action="{{ route('mahasiswa.log-harian.store') }}" method="POST">
            @csrf

            <div class="custom-form-group">
                <label for="tanggal" class="custom-form-label">Tanggal</label>
                <input type="date" class="custom-form-control" name="tanggal" id="tanggal" value="{{ old('tanggal') }}" required>
                @error('tanggal')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="custom-form-group">
                <label for="kegiatan" class="custom-form-label">Kegiatan</label>
                <input type="text" class="custom-form-control" name="kegiatan" id="kegiatan" value="{{ old('kegiatan') }}" required>
                @error('kegiatan')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="custom-form-group">
                <label for="keterangan" class="custom-form-label">Keterangan</label>
                <textarea class="custom-form-control" name="keterangan" id="keterangan">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="custom-btn-primary">Simpan</button>
            <a href="{{ route('mahasiswa.log-harian.index') }}" class="custom-btn-secondary">Batal</a>
        </form>
    </div>
@endsection
