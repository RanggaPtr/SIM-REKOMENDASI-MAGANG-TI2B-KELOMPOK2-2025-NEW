<form id="formLog" method="POST">
    <input type="hidden" name="pengajuan_id" value="{{ $pengajuan_id }}">
    <div class="mb-3">
        <label for="aktivitas" class="form-label">Aktivitas</label>
        <textarea class="form-control" id="aktivitas" name="aktivitas" rows="5">{{ $aktivitas ?? '' }}</textarea>
        <div id="aktivitas-error" class="invalid-feedback"></div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>