<x-admin-layout 
    title="Pengeluaran" 
    :active="'pengeluaran'"
    :breadcrumbs="[['label' => 'Pengeluaran']]"
>
    <!-- Page Header -->
    <x-admin.page-header 
        title="Pengeluaran" 
        subtitle="Catat dan kelola semua pengeluaran operasional."
    >
        <x-slot:actions>
            <button type="button" class="btn btn-danger d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#createPengeluaranModal">
                <i class="bi bi-dash-lg"></i>
                Catat Pengeluaran
            </button>
        </x-slot:actions>
    </x-admin.page-header>

    <!-- Main Content Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <!-- Filter Bar -->
        <div class="card-header bg-light bg-opacity-50 border-bottom border-secondary border-opacity-10 p-3">
            <form action="{{ route('pengeluaran.index') }}" method="GET">
                <div class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <select name="bulan" class="form-select border-secondary border-opacity-25 rounded-3 py-2">
                            <option value="">Semua Bulan</option>
                            @foreach(range(1, 12) as $m)
                                <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ request('bulan') == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="tahun" class="form-select border-secondary border-opacity-25 rounded-3 py-2">
                            <option value="">Semua Tahun</option>
                            @foreach(range(date('Y') - 1, date('Y') + 2) as $y)
                                <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-outline-primary w-100 py-2 rounded-3 fw-medium">
                            <i class="bi bi-funnel-fill me-1"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light bg-opacity-50">
                    <tr>
                        <th class="px-4 py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide text-center" style="width: 60px;">No</th>
                        <th class="px-4 py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide text-center">Tanggal</th>
                        <th class="px-4 py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide">Keterangan</th>
                        <th class="px-4 py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide">Kategori</th>
                        <th class="px-4 py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide text-end">Jumlah</th>
                        <th class="px-4 py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide text-center" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-secondary divide-opacity-10">
                    @forelse($pengeluaran as $index => $item)
                        <tr>
                            <td class="px-4 py-3 text-center text-sm text-secondary">{{ $pengeluaran->firstItem() + $index }}</td>
                            <td class="px-4 py-3 text-center text-sm text-dark">{{ \Carbon\Carbon::parse($item->tanggal)->format('dM Y') }}</td>
                            <td class="px-4 py-3">
                                <div class="fw-bold text-dark">{{ $item->nama_biaya1 }}</div>
                                <div class="text-xs text-secondary">{{ $item->keterangan }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-secondary">
                                <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-2">{{ $item->jenisBiaya->nama_biaya ?? 'Umum' }}</span>
                            </td>
                            <td class="px-4 py-3 text-end fw-bold text-danger">
                                Rp {{ number_format($item->nominal, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button 
                                        onclick="openEditModal('{{ $item->id }}', '{{ $item->nama_biaya1 }}', '{{ $item->keterangan }}', '{{ $item->nominal }}', '{{ $item->tanggal }}', '{{ $item->id_jenis_biaya }}', '{{ route('pengeluaran.update', $item) }}')"
                                        class="btn btn-sm btn-outline-primary px-2 py-1 rounded-2 shadow-sm"
                                        title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    
                                    <form action="{{ route('pengeluaran.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengeluaran ini?')">
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
                            <td colspan="6" class="text-center py-5 text-secondary">
                                <i class="bi bi-bag-x display-4 mb-2 d-block opacity-25"></i>
                                <p class="mb-0">Belum ada data pengeluaran.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination & Total -->
        <div class="card-footer bg-light bg-opacity-50 border-top border-secondary border-opacity-10 py-3">
            <div class="d-flex justify-content-between align-items-center gap-4">
                <div class="d-flex flex-column">
                    <span class="text-xs text-secondary">Total Pengeluaran (Halaman Ini)</span>
                    <span class="fw-bold text-danger">Rp {{ number_format($pengeluaran->sum('nominal'), 0, ',', '.') }}</span>
                </div>
                <div>
                     @if($pengeluaran->hasPages())
                        {{ $pengeluaran->links('pagination::bootstrap-5') }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createPengeluaranModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Catat Pengeluaran Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('pengeluaran.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="nama_biaya1" value="Pengeluaran Manual"> <!-- Default Name -->
                        
                        <div class="mb-3">
                            <label class="form-label fw-medium text-dark">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control rounded-3" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium text-dark">Kategori</label>
                            <select name="id_jenis_biaya" class="form-select rounded-3" required>
                                <option value="" selected disabled>Pilih Kategori...</option>
                                @foreach(\App\Models\JenisBiaya::all() as $jb)
                                    <option value="{{ $jb->id }}">{{ $jb->nama_biaya }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-medium text-dark">Jumlah (Rp)</label>
                            <input type="number" name="nominal" class="form-control rounded-3" required min="0" placeholder="0">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium text-dark">Keterangan</label>
                            <textarea name="keterangan" class="form-control rounded-3" rows="2" required placeholder="Contoh: Beli Kertas A4"></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger py-2 rounded-3 fw-bold shadow-sm">Simpan Pengeluaran</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editPengeluaranModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Edit Pengeluaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="nama_biaya1" value="Pengeluaran Manual"> 

                        <div class="mb-3">
                            <label class="form-label fw-medium text-dark">Tanggal</label>
                            <input type="date" name="tanggal" id="edit_tanggal" class="form-control rounded-3" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium text-dark">Kategori</label>
                            <select name="id_jenis_biaya" id="edit_jenis_biaya" class="form-select rounded-3" required>
                                @foreach(\App\Models\JenisBiaya::all() as $jb)
                                    <option value="{{ $jb->id }}">{{ $jb->nama_biaya }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-medium text-dark">Jumlah (Rp)</label>
                            <input type="number" name="nominal" id="edit_nominal" class="form-control rounded-3" required min="0">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium text-dark">Keterangan</label>
                            <textarea name="keterangan" id="edit_keterangan" class="form-control rounded-3" rows="2" required></textarea>
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
        function openEditModal(id, nama, ket, nominal, tanggal, jbId, updateUrl) {
            document.getElementById('edit_tanggal').value = tanggal;
            document.getElementById('edit_keterangan').value = ket;
            document.getElementById('edit_nominal').value = nominal;
            document.getElementById('edit_jenis_biaya').value = jbId;
            document.getElementById('editForm').action = updateUrl;
            
            const modal = new bootstrap.Modal(document.getElementById('editPengeluaranModal'));
            modal.show();
        }
    </script>
    @endpush
</x-admin-layout>
