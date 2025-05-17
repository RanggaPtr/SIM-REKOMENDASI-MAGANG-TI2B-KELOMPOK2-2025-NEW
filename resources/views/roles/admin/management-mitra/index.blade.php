@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/admin/management-mitra/import') }}')" class="btn btn-info">Import Perusahaan Mitra</button>
                <a href="{{ url('/admin/management-mitra/export_excel') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
                <a href="{{ url('/admin/management-mitra/export_pdf') }}" class="btn btn-danger btn-sm">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <button onclick="modalAction('{{ url('/admin/management-mitra/create_ajax') }}')" class="btn btn-success">
                    <i class="fa fa-plus"></i> Tambah Perusahaan Mitra
                </button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Filter --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="text" id="nama" class="form-control" placeholder="Cari nama perusahaan...">
                </div>
                <div class="col-md-6">
                    <input type="text" id="bidang_industri" class="form-control" placeholder="Cari bidang industri...">
                </div>
            </div>

            {{-- Tabel --}}
            <div class="table-responsive">
                <table id="table_perusahaan" class="table table-bordered table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Ringkasan</th>
                            <th>Deskripsi</th>
                            <th>Bidang Industri</th>
                            <th>Alamat</th>
                            <th>Wilayah</th>
                            <th>Kontak</th>
                            <th>Rating</th>
                            <th>Deskripsi Rating</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    </div>
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net@1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                var modal = new bootstrap.Modal(document.getElementById('myModal'), {
                    keyboard: false,
                    backdrop: 'static'
                });
                modal.show();
            });
        }

        $(document).ready(function () {
            var table = $('#table_perusahaan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/admin/management-mitra/list') }}",
                    type: "POST",
                    data: function (d) {
                        d._token = "{{ csrf_token() }}";
                        d.nama = $('#nama').val();
                        d.bidang_industri = $('#bidang_industri').val();
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                    }
                },
                columns: [
                    { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                    { data: "nama" },
                    { data: "ringkasan" },
                    { data: "deskripsi" },
                    { data: "bidang_industri" },
                    { data: "alamat" },
                    { data: "wilayah" },
                    { data: "kontak" },
                    {
                        data: "rating",
                        className: "text-center",
                        render: function (data, type, row) {
                            return data
                                ? `<span class="badge bg-info">${data} / 5</span>`
                                : '-';
                        }
                    },
                    {
                        data: "deskripsi_rating",
                        render: function (data) {
                            return data || '-';
                        }
                    },
                    {
                        data: "aksi",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    searchPlaceholder: "Cari...",
                    search: ""
                }
            });

            $('#nama, #bidang_industri').on('keyup change', function () {
                table.ajax.reload();
            });

            // Delete confirm
            $(document).on('submit', '#deleteForm', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: $(this).attr('action'),
                            type: 'POST',
                            data: $(this).serialize(),
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire('Berhasil!', response.message, 'success');
                                    table.ajax.reload();
                                    $('#myModal').modal('hide');
                                } else {
                                    Swal.fire('Gagal!', response.message, 'error');
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush