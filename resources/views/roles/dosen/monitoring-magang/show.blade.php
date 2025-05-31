@extends('layouts.template')

@section('content')
<h3>Detail Mahasiswa: {{ $pengajuan->mahasiswa->user->nama }}</h3>
<p>NIM: {{ $pengajuan->mahasiswa->nim }}</p>
<p>Lowongan: {{ $pengajuan->lowongan->judul }}</p>
<p>Periode: {{ $pengajuan->periode->nama }}</p>
<p>Status: {{ $pengajuan->status }}</p>

<h4>Log Aktivitas</h4>
@foreach($logs as $log)
    <div class="card mb-3 p-3">
        <p><strong>Tanggal:</strong> {{ $log->created_at->format('d M Y') }}</p>
        <p>{{ $log->aktivitas }}</p>

        <h5>Feedback</h5>
        @foreach($log->feedbacks as $feedback)
            <div class="border-left pl-3 mb-2">
                <p><strong>{{ $feedback->dosen->user->nama ?? 'Dosen' }}:</strong> {{ $feedback->komentar }}</p>
                @if($feedback->nilai)
                    <p>Nilai: {{ $feedback->nilai }}</p>
                @endif
                <small><i>{{ $feedback->created_at->format('d M Y H:i') }}</i></small>
            </div>
        @endforeach

        <form action="{{ route('dosen.monitoring.feedback.store', $log->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="komentar">Tambah Feedback</label>
                <textarea name="komentar" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="nilai">Nilai (opsional)</label>
                <input type="number" name="nilai" min="0" max="100" class="form-control">
            </div>
            <button type="submit" class="btn btn-success btn-sm mt-2">Simpan Feedback</button>
        </form>
    </div>
@endforeach
@endsection
