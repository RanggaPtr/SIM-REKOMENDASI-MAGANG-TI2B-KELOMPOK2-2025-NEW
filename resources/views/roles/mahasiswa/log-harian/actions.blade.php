<div class="d-flex justify-content-center">
    {{-- Tombol Edit --}}
    <a href="{{ route('mahasiswa.log-harian.edit', $row->id) }}" 
       class="btn btn-sm btn-warning btn-action me-1" 
       title="Edit">
        <i class="fas fa-edit"></i>
    </a>

    {{-- Tombol Hapus --}}
    <button 
        class="btn btn-sm btn-danger btn-action btn-delete" 
        data-url="{{ route('mahasiswa.log-harian.destroy', $row->id) }}"
        title="Hapus">
        <i class="fas fa-trash"></i>
    </button>
</div>
