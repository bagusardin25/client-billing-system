<x-admin-layout 
    title="Jenis Biaya" 
    :active="'jenis-biaya'"
    :breadcrumbs="[['label' => 'Jenis Biaya']]"
>
    <!-- Page Header -->
    <x-admin.page-header 
        title="Jenis Biaya" 
        subtitle="Kelola kategori biaya untuk pemasukan dan pengeluaran."
        action-label="Tambah Jenis Biaya"
        data-bs-toggle="modal"
        data-bs-target="#createJenisBiayaModal"
    />

    <!-- Main Content Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light bg-opacity-50">
                    <tr>
                        <th class="px-4 py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide text-center" style="width: 60px;">No</th>
                        <th class="px-4 py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide">Nama Biaya</th>
                        <th class="px-4 py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide text-center" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-secondary divide-opacity-10">
                    @forelse($jenisBiaya as $index => $item)
                        <tr>
                            <td class="px-4 py-3 text-center text-sm text-secondary">{{ $jenisBiaya->firstItem() + $index }}</td>
                            <td class="px-4 py-3">
                                <div class="fw-bold text-dark">{{ $item->nama_biaya }}</div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button 
                                        onclick="openEditModal('{{ $item->id }}', '{{ addslashes($item->nama_biaya) }}', '{{ route('jenis-biaya.update', $item) }}')"
                                        class="btn btn-sm btn-outline-primary px-2 py-1 rounded-2 shadow-sm"
                                        title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    
                                    <form action="{{ route('jenis-biaya.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis biaya ini? Data pemasukan/pengeluaran terkait mungkin akan terdampak.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-2 hover-bg-danger hover-text-white transition-colors" title="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-secondary">
                                <i class="bi bi-tags display-4 mb-2 d-block opacity-25"></i>
                                <p class="mb-0">Belum ada data jenis biaya.</p>
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
                    Showing <span class="fw-bold text-dark">{{ $jenisBiaya->firstItem() ?? 0 }}</span> to <span class="fw-bold text-dark">{{ $jenisBiaya->lastItem() ?? 0 }}</span> of <span class="fw-bold text-dark">{{ $jenisBiaya->total() }}</span> items
                </p>
                <div>
                     @if($jenisBiaya->hasPages())
                        {{ $jenisBiaya->links('pagination::bootstrap-5') }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createJenisBiayaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Tambah Jenis Biaya</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('jenis-biaya.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="nama_biaya" class="form-label fw-medium text-dark">Nama Biaya <span class="text-danger">*</span></label>
                            <input type="text" name="nama_biaya" class="form-control rounded-3" required placeholder="Contoh: Operasional Kantor">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-2 rounded-3 fw-bold shadow-sm">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editJenisBiayaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Edit Jenis Biaya</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="edit_nama_biaya" class="form-label fw-medium text-dark">Nama Biaya <span class="text-danger">*</span></label>
                            <input type="text" name="nama_biaya" id="edit_nama_biaya" class="form-control rounded-3" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-2 rounded-3 fw-bold shadow-sm">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openEditModal(id, name, updateUrl) {
            document.getElementById('edit_nama_biaya').value = name;
            document.getElementById('editForm').action = updateUrl;
            
            const modal = new bootstrap.Modal(document.getElementById('editJenisBiayaModal'));
            modal.show();
        }
    </script>
    @endpush
</x-admin-layout>
