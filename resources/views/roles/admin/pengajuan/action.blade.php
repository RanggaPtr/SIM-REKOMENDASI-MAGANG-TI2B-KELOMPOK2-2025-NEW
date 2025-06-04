<!-- resources/views/roles/admin/pengajuan/action.blade.php -->
<div class="btn-group" role="group">
    <button class="btn btn-sm btn-info" onclick="showPengajuan({{ $row->pengajuan_id }})" title="Detail">
        <i class="fa fa-eye"></i> Detail
    </button>
    <button class="btn btn-sm btn-warning" onclick="editPengajuan({{ $row->pengajuan_id }})" title="Edit">
        <i class="fa fa-edit"></i> Edit
    </button>
    <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $row->pengajuan_id }})" title="Hapus">
        <i class="fa fa-trash"></i> Hapus
    </button>
</div>

<script>
    function showPengajuan(id) {
        $.get('{{ route("admin.pengajuan.show_ajax", ":id") }}'.replace(':id', id), function(data) {
            // Dispose existing modal instance if exists
            var existingModalEl = document.getElementById('myModal');
            if (existingModalEl) {
                var existingModal = bootstrap.Modal.getInstance(existingModalEl);
                if (existingModal) {
                    existingModal.dispose();
                }
            }
            
            $('#myModal').html(data);
            
            // Create and show new modal
            var modalEl = document.getElementById('myModal');
            var myModal = new bootstrap.Modal(modalEl, {
                keyboard: false,
                backdrop: 'static'
            });
            myModal.show();
        }).fail(function(xhr, status, error) {
            console.error('Error loading modal:', error);
            alert('Terjadi kesalahan saat memuat data');
        });
    }

    function editPengajuan(id) {
        $.get('{{ route("admin.pengajuan.edit_ajax", ":id") }}'.replace(':id', id), function(data) {
            // Dispose existing modal instance if exists
            var existingModalEl = document.getElementById('myModal');
            if (existingModalEl) {
                var existingModal = bootstrap.Modal.getInstance(existingModalEl);
                if (existingModal) {
                    existingModal.dispose();
                }
            }
            
            $('#myModal').html(data);
            
            // Create and show new modal
            var modalEl = document.getElementById('myModal');
            var myModal = new bootstrap.Modal(modalEl, {
                keyboard: false,
                backdrop: 'static'
            });
            myModal.show();
        }).fail(function(xhr, status, error) {
            console.error('Error loading modal:', error);
            alert('Terjadi kesalahan saat memuat data');
        });
    }

    function confirmDelete(id) {
        $.get('{{ route("admin.pengajuan.confirm_ajax", ":id") }}'.replace(':id', id), function(data) {
            // Dispose existing modal instance if exists
            var existingModalEl = document.getElementById('myModal');
            if (existingModalEl) {
                var existingModal = bootstrap.Modal.getInstance(existingModalEl);
                if (existingModal) {
                    existingModal.dispose();
                }
            }
            
            $('#myModal').html(data);
            
            // Create and show new modal
            var modalEl = document.getElementById('myModal');
            var myModal = new bootstrap.Modal(modalEl, {
                keyboard: false,
                backdrop: 'static'
            });
            myModal.show();
        }).fail(function(xhr, status, error) {
            console.error('Error loading modal:', error);
            alert('Terjadi kesalahan saat memuat data');
        });
    }
</script>