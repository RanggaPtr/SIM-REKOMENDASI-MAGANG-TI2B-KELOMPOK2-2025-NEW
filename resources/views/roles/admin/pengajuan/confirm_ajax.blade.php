<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Hapus Pengajuan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus pengajuan dengan ID: {{ $pengajuan->pengajuan_id }}?</p>
        <p><strong>Nama Mahasiswa:</strong> {{ $pengajuan->mahasiswa->user->name ?? '-' }}</p>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" onclick="deletePengajuan({{ $pengajuan->pengajuan_id }})">Hapus</button>
    </div>
</div>

<script>
    function deletePengajuan(id) {
        $.ajax({
            url: '{{ route("admin.pengajuan.delete_ajax", "") }}/' + id,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
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
    }
</script>