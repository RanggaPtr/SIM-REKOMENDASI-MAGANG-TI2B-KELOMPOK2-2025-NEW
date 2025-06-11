<div class="modal-header">
    <h5 class="modal-title" id="logModalLabel">{{ $isEdit ?? false ? 'Edit' : 'Tambah' }} Log Aktivitas</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form id="formLog" method="POST">
    <div class="modal-body">
        @csrf
        <div class="form-group mb-3">
            <label for="aktivitas" class="form-label">Aktivitas <span class="text-danger">*</span></label>
            <textarea name="aktivitas" id="aktivitas" class="form-control" rows="5" 
                      placeholder="Masukkan aktivitas yang dilakukan hari ini..." 
                      required maxlength="1000">{{ $aktivitas ?? '' }}</textarea>
            <small class="form-text text-muted">Maksimal 1000 karakter</small>
            <div class="invalid-feedback" id="aktivitas-error"></div>
        </div>
    </div>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan
        </button>
    </div>
</form>