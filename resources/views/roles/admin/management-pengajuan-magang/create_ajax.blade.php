<form action="{{ url('/admin/management-pengajuan-magang/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Pengajuan Magang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Mahasiswa</label>
                    <select name="mahasiswa_id" id="mahasiswa_id" class="form-control" required>
                        <option value="">- Pilih Mahasiswa -</option>
                        @foreach($mahasiswa as $m)
                            <option value="{{ $m->mahasiswa_id }}">{{ $m->user->nama }} - {{ $m->nim }}</option>
                        @endforeach
                    </select>
                    <small id="error-mahasiswa_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Lowongan Magang</label>
                    <select name="lowongan_id" id="lowongan_id" class="form-control" required>
                        <option value="">- Pilih Lowongan -</option>
                        @foreach($lowongan as $l)
                            <option value="{{ $l->lowongan_id }}">{{ $l->posisi }} - {{ $l->perusahaan->nama_perusahaan }}</option>
                        @endforeach
                    </select>
                    <small id="error-lowongan_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Dosen Pembimbing</label>
                    <select name="dosen_id" id="dosen_id" class="form-control" required>
                        <option value="">- Pilih Dosen -</option>
                        @foreach($dosen as $d)
                            <option value="{{ $d->dosen_id }}">{{ $d->user->nama }} - {{ $d->nip }}</option>
                        @endforeach
                    </select>
                    <small id="error-dosen_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Periode Magang</label>
                    <select name="periode_id" id="periode_id" class="form-control" required>
                        <option value="">- Pilih Periode -</option>
                        @foreach($periode as $p)
                            <option value="{{ $p->periode_id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-periode_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="">- Pilih Status -</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                        <option value="completed">Completed</option>
                    </select>
                    <small id="error-status" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    $("#form-tambah").validate({
        rules: {
            mahasiswa_id: {
                required: true,
                number: true
            },
            lowongan_id: {
                required: true,
                number: true
            },
            dosen_id: {
                required: true,
                number: true
            },
            periode_id: {
                required: true,
                number: true
            },
            status: {
                required: true
            }
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        dataTable.ajax.reload();
                    } else {
                        $('.error-text').text('');
                        $.each(response.msgField, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message
                        });
                    }
                }
            });
            return false;
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
});
</script>