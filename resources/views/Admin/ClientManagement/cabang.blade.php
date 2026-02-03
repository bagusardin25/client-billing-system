<x-admin-layout
    title="Cabang {{ $client->nama_client }}"
    :active="'clients'"
    :breadcrumbs="[
        ['label' => 'Clients', 'url' => route('clients.index')],
        ['label' => $client->nama_client, 'url' => route('clients.show', $client)],
        ['label' => 'Cabang Usaha']
    ]"
>
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h2 fw-bold text-body-emphasis mb-1">Cabang Usaha - {{ $client->nama_client }}</h1>
            <p class="text-secondary small mb-0">Daftar seluruh cabang usaha milik <span class='fw-bold text-primary'>{{ $client->perusahaan ?? $client->nama_client }}</span></p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali</span>
            </a>
            <button type="button" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addCabangModal">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Cabang</span>
            </button>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        
        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light bg-opacity-50">
                    <tr>
                        <th class="px-4 py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide text-center" style="width: 60px;">No</th>
                        <th class="px-4 py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide">Nama Perusahaan</th>
                        <th class="px-4 py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide">Website</th>
                        <th class="px-4 py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide">No Telepon</th>
                        <th class="px-4 py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide">Alamat</th>
                        <th class="px-4 py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide text-center" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-secondary divide-opacity-10">
                    @forelse($cabangUsaha as $index => $cabang)
                        <tr>
                            <td class="px-4 py-3 text-center text-sm text-secondary">{{ $cabangUsaha->firstItem() + $index }}</td>
                            <td class="px-4 py-3">
                                <div class="fw-bold text-dark">{{ $cabang->nama_perusahaan }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($cabang->website)
                                    <a href="{{ $cabang->website }}" target="_blank" class="text-primary text-decoration-none">
                                        <i class="bi bi-globe me-1"></i>{{ $cabang->website }}
                                    </a>
                                @else
                                    <span class="text-secondary">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-secondary">{{ $cabang->no_telepon ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-secondary">{{ Str::limit($cabang->alamat, 40) ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-sm bg-warning bg-opacity-10 text-warning px-2 py-1 rounded-2" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editCabangModal{{ $cabang->id }}"
                                            title="Edit">
                                        <i class="material-icons-round fs-6">edit</i>
                                    </button>
                                    
                                    <form action="{{ route('cabang-usaha.destroy', $cabang) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus cabang ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-2" title="Hapus">
                                            <i class="material-icons-round fs-6">delete</i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Edit Modal for each cabang -->
                        <div class="modal fade" id="editCabangModal{{ $cabang->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg rounded-4">
                                    <div class="modal-header border-0 pb-0 px-4 pt-4">
                                        <h5 class="modal-title fw-bold">
                                            <i class="bi bi-pencil-square text-warning me-2"></i>Edit Cabang
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                    </div>
                                    <form action="{{ route('cabang-usaha.update', $cabang) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="id_client" value="{{ $client->id }}">
                                        <div class="modal-body px-4 py-3">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label class="form-label fw-medium text-dark">Nama Perusahaan <span class="text-danger">*</span></label>
                                                    <input type="text" name="nama_perusahaan" class="form-control" value="{{ $cabang->nama_perusahaan }}" required>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-medium text-dark">Website</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light text-secondary"><i class="bi bi-globe"></i></span>
                                                        <input type="text" name="website" class="form-control" value="{{ $cabang->website }}" placeholder="https://...">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-medium text-dark">No Telepon</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light text-secondary"><i class="bi bi-telephone"></i></span>
                                                        <input type="text" name="no_telepon" class="form-control" value="{{ $cabang->no_telepon }}" placeholder="08...">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-medium text-dark">Alamat</label>
                                                    <textarea name="alamat" rows="2" class="form-control" placeholder="Alamat lengkap cabang">{{ $cabang->alamat }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 px-4 pb-4 pt-2">
                                            <button type="button" class="btn btn-light text-secondary px-4" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-warning px-4">
                                                <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-secondary">
                                <i class="material-icons-round display-4 mb-2 d-block opacity-25">store</i>
                                <p class="mb-0">Belum ada data cabang usaha.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="card-footer bg-light bg-opacity-50 border-top border-secondary border-opacity-10 py-3">
            <div class="d-flex justify-content-between align-items-center gap-4">
                <p class="text-xs text-secondary mb-0">
                    Menampilkan <span class="fw-bold text-dark">{{ $cabangUsaha->firstItem() ?? 0 }}</span> sampai <span class="fw-bold text-dark">{{ $cabangUsaha->lastItem() ?? 0 }}</span> dari <span class="fw-bold text-dark">{{ $cabangUsaha->total() }}</span> cabang
                </p>
                <div>
                    @if($cabangUsaha->hasPages())
                        {{ $cabangUsaha->links('pagination::bootstrap-5') }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add Cabang Modal -->
    <div class="modal fade" id="addCabangModal" tabindex="-1" aria-labelledby="addCabangModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold" id="addCabangModalLabel">
                        <i class="bi bi-building-add text-primary me-2"></i>Tambah Cabang Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form action="{{ route('cabang-usaha.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_client" value="{{ $client->id }}">
                    <div class="modal-body px-4 py-3">
                        <p class="text-secondary small mb-4">Masukkan informasi cabang usaha di bawah ini.</p>
                        
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="nama_perusahaan" class="form-label fw-medium text-dark">Nama Perusahaan <span class="text-danger">*</span></label>
                                <input type="text" name="nama_perusahaan" id="nama_perusahaan" class="form-control" placeholder="Contoh: PT Maju Jaya Cabang Surabaya" required>
                            </div>
                            
                            <div class="col-12">
                                <label for="website" class="form-label fw-medium text-dark">Website</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-secondary"><i class="bi bi-globe"></i></span>
                                    <input type="text" name="website" id="website" class="form-control" placeholder="https://www.example.com">
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="no_telepon" class="form-label fw-medium text-dark">No Telepon</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-secondary"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="no_telepon" id="no_telepon" class="form-control" placeholder="08...">
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="alamat" class="form-label fw-medium text-dark">Alamat</label>
                                <textarea name="alamat" id="alamat" rows="2" class="form-control" placeholder="Alamat lengkap cabang"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-2">
                        <button type="button" class="btn btn-light text-secondary px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i> Simpan Cabang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-admin-layout>