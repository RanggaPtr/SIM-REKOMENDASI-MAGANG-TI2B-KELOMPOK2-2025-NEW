@extends('layouts.template')

@section('content')
<h3>Detail Mahasiswa: {{ $pengajuan->mahasiswa->user->nama ?? $pengajuan->mahasiswa->nim ?? 'Nama Tidak Tersedia' }}</h3>
<p>NIM: {{ $pengajuan->mahasiswa->nim ?? '-' }}</p>
<p>Lowongan: {{ $pengajuan->lowongan->judul ?? '-' }}</p>
<p>Periode: {{ $pengajuan->lowongan->periode->nama ?? 'Tidak Tersedia' }}</p>
<p>Status: {{ $pengajuan->status ?? '-' }}</p>

<h4>Log Aktivitas</h4>
@foreach($logs as $log)
    <div class="card mb-3 p-3">
        <p><strong>Tanggal:</strong> {{ $log->feedback ? $log->feedback->created_at->format('d M Y') : '-' }}</p>
        <p>{{ $log->aktivitas ?? 'Tidak Ada Aktivitas' }}</p>

        <h5>Feedback</h5>
        @if($log->feedback)
            <div class="border-start ps-3 mb-2">
                <p><strong>{{ $log->feedback->dosen->user->nama ?? 'Dosen' }}:</strong> {{ $log->feedback->komentar ?? 'Tidak Ada Komentar' }}</p>
                @if($log->feedback->nilai)
                    <p>Nilai: {{ $log->feedback->nilai }}</p>
                @endif
                <small><i>{{ $log->feedback->created_at->format('d M Y H:i') ?? '-' }}</i></small>
            </div>
        @else
            <p>Belum ada feedback.</p>
        @endif

        @if(!$log->feedback)
            <form action="{{ route('dosen.monitoring.feedback.store', $log->log_id) }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="komentar">Tambah Feedback</label>
                    <textarea name="komentar" class="form-control" required></textarea>
                    @error('komentar')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="nilai">Nilai (opsional, 0-100)</label>
                    <input type="number" name="nilai" min="0" max="100" class="form-control" value="{{ old('nilai') }}">
                    @error('nilai')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-success btn-sm mt-2">Simpan Feedback</button>
            </form>
        @endif
    </div>
@endforeach
@endsection