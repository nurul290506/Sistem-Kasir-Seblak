@extends('layouts.app')

@section('page_title', 'Kelola Supplier')

@section('content')
<div class="card-custom">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="m-0"><i class="fa-solid fa-truck-ramp-box text-primary me-2"></i> Daftar Pemasok (Supplier)</h5>
        <button type="button" class="btn btn-primary-custom btn-sm" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
            <i class="fa-solid fa-plus me-1"></i> Tambah Supplier
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
                    <th>Nama Supplier</th>
                    <th>No. Telepon / HP</th>
                    <th>Alamat</th>
                    <th>Total Transaksi Pembelian</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $sup)
                    <tr>
                        <td><strong>#{{ $sup->id_supplier }}</strong></td>
                        <td><div class="fw-bold">{{ $sup->nama_supplier }}</div></td>
                        <td><code>{{ $sup->no_hp }}</code></td>
                        <td>{{ $sup->alamat }}</td>
                        <td>
                            <span class="badge bg-light text-dark border px-2 py-1">
                                {{ $sup->pembelian()->count() }} Kali
                            </span>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn-action btn-action-edit me-1 edit-btn" 
                                    data-id="{{ $sup->id_supplier }}"
                                    data-nama="{{ $sup->nama_supplier }}"
                                    data-nohp="{{ $sup->no_hp }}"
                                    data-alamat="{{ $sup->alamat }}"
                                    data-bs-toggle="modal" data-bs-target="#editSupplierModal" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <form action="{{ route('supplier.destroy', $sup->id_supplier) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus supplier ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-action-delete" title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data supplier.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Supplier -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: var(--border-radius);">
            <form action="{{ route('supplier.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addSupplierModalLabel"><i class="fa-solid fa-truck-ramp-box text-primary me-2"></i> Tambah Supplier Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_supplier" class="form-label fw-semibold">Nama Supplier</label>
                        <input type="text" class="form-control" name="nama_supplier" required placeholder="Contoh: CV. Berkah Frozen">
                    </div>
                    <div class="mb-3">
                        <label for="no_hp" class="form-label fw-semibold">No. Telepon / HP</label>
                        <input type="text" class="form-control" name="no_hp" required placeholder="Contoh: 08123456789">
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label fw-semibold">Alamat Lengkap</label>
                        <textarea class="form-control" name="alamat" rows="3" required placeholder="Masukkan alamat lengkap supplier"></textarea>
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

<!-- Modal Edit Supplier -->
<div class="modal fade" id="editSupplierModal" tabindex="-1" aria-labelledby="editSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: var(--border-radius);">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editSupplierModalLabel"><i class="fa-solid fa-truck-field-unarchive text-primary me-2"></i> Edit Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nama_supplier" class="form-label fw-semibold">Nama Supplier</label>
                        <input type="text" class="form-control" name="nama_supplier" id="edit_nama_supplier" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_no_hp" class="form-label fw-semibold">No. Telepon / HP</label>
                        <input type="text" class="form-control" name="no_hp" id="edit_no_hp" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_alamat" class="form-label fw-semibold">Alamat Lengkap</label>
                        <textarea class="form-control" name="alamat" id="edit_alamat" rows="3" required></textarea>
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
                const nohp = this.getAttribute('data-nohp');
                const alamat = this.getAttribute('data-alamat');

                // Set form action URL
                document.getElementById('editForm').action = `/supplier/${id}`;

                // Pre-fill inputs
                document.getElementById('edit_nama_supplier').value = nama;
                document.getElementById('edit_no_hp').value = nohp;
                document.getElementById('edit_alamat').value = alamat;
            });
        });
    });
</script>
@endsection
