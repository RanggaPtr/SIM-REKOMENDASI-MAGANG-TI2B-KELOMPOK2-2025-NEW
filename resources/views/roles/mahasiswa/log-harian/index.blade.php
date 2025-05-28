@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">{{ $page->title ?? 'Log Harian Aktivitas' }}</h3>
        <div>
            <!-- Tambah Log -->
            <a href="{{ route('mahasiswa.log-harian.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Log
            </a>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="table_log">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Aktivitas</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data akan diisi oleh DataTables -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.min.css">
    <style>
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            const table = $('#table_log').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('mahasiswa.log-harian.list') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    { 
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    { 
                        data: 'aktivitas',
                        name: 'aktivitas',
                        render: function(data, type, row) {
                            return data && data.length > 100 ? 
                                data.substr(0, 100) + '...' : 
                                data || '';
                        }
                    },
                    { 
                        data: 'tanggal',
                        name: 'created_at',
                        className: 'text-center',
                        render: function(data) {
                            return new Date(data).toLocaleString('id-ID', {
                                day: '2-digit',
                                month: 'short',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                        }
                    },
                    { 
                        data: 'aksi',
                        name: 'aksi',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [[2, 'desc']],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json"
                }
            });

            // Handle delete button
            $(document).on('click', '.delete-btn', function() {
                const url = $(this).data('url');
                
                Swal.fire({
                    title: 'Hapus Log Aktivitas?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                table.ajax.reload();
                                Swal.fire(
                                    'Terhapus!',
                                    response.message || 'Log aktivitas telah dihapus.',
                                    'success'
                                );
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Gagal!',
                                    xhr.responseJSON?.message || 'Terjadi kesalahan.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush