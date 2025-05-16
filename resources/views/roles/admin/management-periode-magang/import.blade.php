<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Data Periode</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="importForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file">Pilih File Excel</label>
                        <input type="file" class="form-control-file" id="file" name="file" required accept=".xlsx, .xls, .csv">
                        <small class="form-text text-muted">Format file harus .xlsx, .xls, atau .csv</small>
                    </div>
                    <div class="form-group">
                        <a href="{{ route('admin.periode.download-template') }}" class="btn btn-sm btn-info">
                            <i class="fas fa-download"></i> Download Template
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$('#importForm').submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    
    $.ajax({
        url: "{{ route('admin.periode.import') }}",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            $('#importModal').modal('hide');
            $('#periodeTable').DataTable().ajax.reload();
            toastr.success(response.success);
        },
        error: function(xhr) {
            if(xhr.status === 422) {
                var errors = xhr.responseJSON.errors;
                $.each(errors, function(key, value) {
                    toastr.error(value[0]);
                });
            }
        }
    });
});
</script>