<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Edit Pengajuan Magang</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <form id="editPengajuanForm" action="{{ route('admin.pengajuan.update_ajax', $pengajuan->pengajuan_id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="diajukan" {{ $pengajuan->status == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                    <option value="diterima" {{ $pengajuan->status == 'diterima' ? 'selected' : '' }}>Diterima</option>
                    <option value="ditolak" {{ $pengajuan->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="selesai" {{ $pengajuan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div class="form-group">
                <label for="dosen_id">Dosen Pembimbing</label>
                <select name="dosen_id" id="dosen_id" class="form-control">
                    <option value="">Pilih Dosen</option>
                    @foreach ($dosens as $dosen)
                        <option value="{{ $dosen->dosen_id }}"
                            {{ $pengajuan->dosen_id == $dosen->dosen_id ? 'selected' : '' }}
                            data-kompetensi="{{ $dosen->kompetensi->nama ?? 'Tidak Ada' }}">
                            {{ $dosen->user->name }} ({{ $dosen->kompetensi->nama ?? 'Tidak Ada' }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Kompetensi Lowongan</label>
                <p>
                    @foreach ($pengajuan->lowongan->kompetensis as $kompetensi)
                        <span class="badge badge-info">{{ $kompetensi->nama }}</span>
                    @endforeach
                </p>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#editPengajuanForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#pengajuanTable').DataTable().ajax.reload();
                        $('#modal').modal('hide');
                        alert(response.message);
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan: ' + xhr.responseJSON.message);
                }
            });
        });
    });
</script>