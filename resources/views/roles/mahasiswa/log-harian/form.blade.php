<div class="container px-3">
    <div class="card shadow rounded-4 border-0">
        <div class="card-header bg-gradient bg-primary text-black rounded-top-4">
            <h5 class="mb-0 py-2">Catatan Log Harian</h5>
        </div>
        <div class="card-body p-4">
            <form id="formLog" method="POST">
                <input type="hidden" name="pengajuan_id" value="{{ $pengajuan_id }}">

                <div class="mb-4">
                    <label for="aktivitas" class="form-label fw-bold text-secondary">Aktivitas</label>
                    <textarea 
                        class="form-control border border-2 rounded-3 shadow-sm p-3 {{ $errors->has('aktivitas') ? 'is-invalid' : '' }}" 
                        id="aktivitas" 
                        name="aktivitas" 
                        rows="6"
                        placeholder="Ceritakan aktivitas yang kamu lakukan hari ini..."
                    >{{ $aktivitas ?? '' }}</textarea>
                    <div id="aktivitas-error" class="invalid-feedback">
                        {{ $errors->first('aktivitas') }}
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-outline-secondary me-2 rounded-pill px-4" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
