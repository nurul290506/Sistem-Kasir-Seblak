@extends('layouts.app')

@section('page_title', 'Kelola Stok & Menu')

@section('content')
<div class="card-custom">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="m-0"><i class="fa-solid fa-boxes-stacked text-primary me-2"></i> Persediaan Barang</h5>
        <button type="button" class="btn btn-primary-custom btn-sm" data-bs-toggle="modal" data-bs-target="#addItemModal">
            <i class="fa-solid fa-plus me-1"></i> Tambah Menu/Barang
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
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Harga Jual</th>
                    <th class="text-center">Stok</th>
                    <th>Satuan</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr class="{{ $item->stok < 15 ? 'table-warning-custom' : '' }}">
                        <td><strong>#{{ $item->id_barang }}</strong></td>
                        <td>
                            <div class="fw-bold">{{ $item->nama_barang }}</div>
                            @if($item->stok < 15)
                                <small class="text-danger fw-semibold"><i class="fa-solid fa-circle-exclamation me-1"></i> Stok Menipis</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $item->kategori->nama_kategori }}
                            </span>
                        </td>
                        <td><strong>Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</strong></td>
                        <td class="text-center">
                            <span class="badge rounded-pill {{ $item->stok < 15 ? 'bg-danger' : 'bg-success' }} px-3 py-1">
                                {{ $item->stok }}
                            </span>
                        </td>
                        <td><code>{{ $item->satuan }}</code></td>
                        <td>
                            <span class="badge badge-custom {{ $item->status === 'aktif' ? 'badge-active' : 'badge-inactive' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn-action btn-action-edit me-1 edit-btn" 
                                    data-id="{{ $item->id_barang }}"
                                    data-nama="{{ $item->nama_barang }}"
                                    data-kategori="{{ $item->id_kategori }}"
                                    data-harga="{{ $item->harga_jual }}"
                                    data-stok="{{ $item->stok }}"
                                    data-satuan="{{ $item->satuan }}"
                                    data-status="{{ $item->status }}"
                                    data-bs-toggle="modal" data-bs-target="#editItemModal" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <form action="{{ route('barang.destroy', $item->id_barang) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?');">
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
                        <td colspan="8" class="text-center py-4 text-muted">Belum ada data barang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Barang -->
<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: var(--border-radius);">
            <form action="{{ route('barang.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addItemModalLabel"><i class="fa-solid fa-folder-plus text-primary me-2"></i> Tambah Barang/Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_barang" class="form-label fw-semibold">Nama Barang/Menu</label>
                        <input type="text" class="form-control" name="nama_barang" required placeholder="Contoh: Dumpling Keju, Es Teh">
                    </div>
                    <div class="mb-3">
                        <label for="id_kategori" class="form-label fw-semibold">Kategori</label>
                        <select class="form-select" name="id_kategori" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id_kategori }}">{{ $cat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="harga_jual" class="form-label fw-semibold">Harga Jual (Rp)</label>
                            <input type="number" step="0.01" class="form-control" name="harga_jual" required placeholder="Contoh: 2500">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="stok" class="form-label fw-semibold">Stok Awal</label>
                            <input type="number" class="form-control" name="stok" required placeholder="Contoh: 50">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="satuan" class="form-label fw-semibold">Satuan</label>
                            <input type="text" class="form-control" name="satuan" required placeholder="Contoh: pcs, porsi, gelas, botol">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label fw-semibold">Status</label>
                            <select class="form-select" name="status" required>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
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

<!-- Modal Edit Barang -->
<div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: var(--border-radius);">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editItemModalLabel"><i class="fa-solid fa-pen text-primary me-2"></i> Edit Barang/Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nama_barang" class="form-label fw-semibold">Nama Barang/Menu</label>
                        <input type="text" class="form-control" name="nama_barang" id="edit_nama_barang" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_id_kategori" class="form-label fw-semibold">Kategori</label>
                        <select class="form-select" name="id_kategori" id="edit_id_kategori" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id_kategori }}">{{ $cat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_harga_jual" class="form-label fw-semibold">Harga Jual (Rp)</label>
                            <input type="number" step="0.01" class="form-control" name="harga_jual" id="edit_harga_jual" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_stok" class="form-label fw-semibold">Stok</label>
                            <input type="number" class="form-control" name="stok" id="edit_stok" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_satuan" class="form-label fw-semibold">Satuan</label>
                            <input type="text" class="form-control" name="satuan" id="edit_satuan" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_status" class="form-label fw-semibold">Status</label>
                            <select class="form-select" name="status" id="edit_status" required>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
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

@section('styles')
<style>
    .table-warning-custom {
        background-color: #fff9f0 !important;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.edit-btn');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                const kategori = this.getAttribute('data-kategori');
                const harga = this.getAttribute('data-harga');
                const stok = this.getAttribute('data-stok');
                const satuan = this.getAttribute('data-satuan');
                const status = this.getAttribute('data-status');

                // Set form action URL
                document.getElementById('editForm').action = `/barang/${id}`;

                // Pre-fill inputs
                document.getElementById('edit_nama_barang').value = nama;
                document.getElementById('edit_id_kategori').value = kategori;
                document.getElementById('edit_harga_jual').value = parseFloat(harga);
                document.getElementById('edit_stok').value = parseInt(stok);
                document.getElementById('edit_satuan').value = satuan;
                document.getElementById('edit_status').value = status;
            });
        });
    });
</script>
@endsection
