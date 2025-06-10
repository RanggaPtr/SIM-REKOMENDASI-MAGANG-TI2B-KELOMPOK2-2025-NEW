<div class="btn-group" role="group">
    <button type="button" class="btn btn-sm btn-outline-primary btn-edit" 
            data-id="{{ $log->log_id }}" 
            data-aktivitas="{{ $log->aktivitas }}"
            title="Edit">
        <i class="fas fa-edit"></i>
    </button>
    <button type="button" class="btn btn-sm btn-outline-danger btn-delete" 
            data-url="{{ route('mahasiswa.log-harian.destroy', $log->log_id) }}"
            title="Hapus">
        <i class="fas fa-trash"></i>
    </button>
</div>