@extends('layouts.app')

@section('page_title', 'Kelola Pengguna (Users)')

@section('content')
<div class="card-custom">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="m-0"><i class="fa-solid fa-users text-primary me-2"></i> Daftar Pengguna</h5>
        <button type="button" class="btn btn-primary-custom btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fa-solid fa-plus me-1"></i> Tambah User baru
        </button>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-custom table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama User</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td><strong>#{{ $user->id_user }}</strong></td>
                        <td>{{ $user->nama_user }}</td>
                        <td><code>{{ $user->username }}</code></td>
                        <td>
                            <span class="badge badge-custom {{ $user->role === 'admin' ? 'badge-role-admin' : 'badge-role-kasir' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-custom {{ $user->status === 'aktif' ? 'badge-active' : 'badge-inactive' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn-action btn-action-edit me-1 edit-btn" 
                                    data-id="{{ $user->id_user }}"
                                    data-nama="{{ $user->nama_user }}"
                                    data-username="{{ $user->username }}"
                                    data-role="{{ $user->role }}"
                                    data-status="{{ $user->status }}"
                                    data-bs-toggle="modal" data-bs-target="#editUserModal" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            @if($user->id_user !== auth()->id())
                                <form action="{{ route('users.destroy', $user->id_user) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-action-delete" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            @else
                                <button type="button" class="btn-action text-muted" style="cursor: not-allowed;" title="Tidak bisa menghapus diri sendiri" disabled>
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah User -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: var(--border-radius);">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel"><i class="fa-solid fa-user-plus text-primary me-2"></i> Tambah Pengguna Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_user" class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama_user" required placeholder="Nama lengkap user">
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label fw-semibold">Username</label>
                        <input type="text" class="form-control" name="username" required placeholder="Username untuk login">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <input type="password" class="form-control" name="password" required placeholder="Minimal 6 karakter">
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label fw-semibold">Role / Hak Akses</label>
                        <select class="form-select" name="role" required>
                            <option value="kasir">Kasir</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label fw-semibold">Status</label>
                        <select class="form-select" name="status" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit User -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: var(--border-radius);">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel"><i class="fa-solid fa-user-pen text-primary me-2"></i> Edit Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nama_user" class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama_user" id="edit_nama_user" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_username" class="form-label fw-semibold">Username</label>
                        <input type="text" class="form-control" name="username" id="edit_username" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label fw-semibold">Password <small class="text-muted">(Kosongkan jika tidak ingin diubah)</small></label>
                        <input type="password" class="form-control" name="password" id="edit_password" placeholder="Isi hanya jika ingin ganti password">
                    </div>
                    <div class="mb-3">
                        <label for="edit_role" class="form-label fw-semibold">Role / Hak Akses</label>
                        <select class="form-select" name="role" id="edit_role" required>
                            <option value="kasir">Kasir</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_status" class="form-label fw-semibold">Status</label>
                        <select class="form-select" name="status" id="edit_status" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.edit-btn');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                const username = this.getAttribute('data-username');
                const role = this.getAttribute('data-role');
                const status = this.getAttribute('data-status');

                // Set form action URL
                document.getElementById('editForm').action = `/users/${id}`;

                // Pre-fill inputs
                document.getElementById('edit_nama_user').value = nama;
                document.getElementById('edit_username').value = username;
                document.getElementById('edit_role').value = role;
                document.getElementById('edit_status').value = status;
                document.getElementById('edit_password').value = '';
            });
        });
    });
</script>
@endsection
