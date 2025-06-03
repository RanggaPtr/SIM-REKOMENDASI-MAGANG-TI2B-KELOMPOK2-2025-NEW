<div class="btn-group">
    <button class="btn btn-sm btn-info" onclick="showPengajuan({{ $row->pengajuan_id }})">Detail</button>
    <button class="btn btn-sm btn-warning" onclick="editPengajuan({{ $row->pengajuan_id }})">Edit</button>
    <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $row->pengajuan_id }})">Hapus</button>
</div>

<script>
    function showPengajuan(id) {
        $.get('{{ route("admin.pengajuan.show_ajax", "") }}/' + id, function(data) {
            $('#myModal').html(data);
            var myModal = new bootstrap.Modal(document.getElementById('myModal'), {
                keyboard: false,
                backdrop: 'static'
            });
            myModal.show();
        });
    }

    function editPengajuan(id) {
        $.get('{{ route("admin.pengajuan.edit_ajax", "") }}/' + id, function(data) {
            $('#myModal').html(data);
            var myModal = new bootstrap.Modal(document.getElementById('myModal'), {
                keyboard: false,
                backdrop: 'static'
            });
            myModal.show();
        });
    }

    function confirmDelete(id) {
        $.get('{{ route("admin.pengajuan.confirm_ajax", "") }}/' + id, function(data) {
            $('#myModal').html(data);
            var myModal = new bootstrap.Modal(document.getElementById('myModal'), {
                keyboard: false,
                backdrop: 'static'
            });
            myModal.show();
        });
    }
</script>