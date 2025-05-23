@empty($user)
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">Data user tidak ditemukan.</div>
            </div>
        </div>
    </div>
@else
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Data User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th>ID User</th>
                        <td>{{ $user->user_id }}</td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td>{{ ucfirst($user->role) }}</td>
                    </tr>
                    <tr>
                        <th>Username</th>
                        <td>{{ $user->username }}</td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td>{{ $user->nama }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>No. Telepon</th>
                        <td>{{ $user->no_telepon ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-bs-dismiss="modal" class="btn btn-primary">Tutup</button>
            </div>
        </div>
    </div>
@endempty