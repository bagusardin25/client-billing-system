<x-admin-layout 
    title="Laporan Pengeluaran" 
    :active="'laporan.pengeluaran'"
    :breadcrumbs="[['label' => 'Laporan'], ['label' => 'Pengeluaran']]"
>
    <x-admin.page-header 
        title="Laporan Pengeluaran" 
        subtitle="Analisa biaya operasional dan pengeluaran lainnya."
    />

    <!-- Filter Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('laporan.pengeluaran') }}" method="GET" class="row align-items-end g-3">
                <div class="col-md-4">
                    <label class="form-label fw-medium text-dark small text-uppercase ls-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control rounded-3" value="{{ $startDate }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium text-dark small text-uppercase ls-1">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control rounded-3" value="{{ $endDate }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 fw-bold shadow-sm">
                        <i class="bi bi-funnel-fill me-2"></i>Filter Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded-4 bg-danger bg-gradient text-white h-100 position-relative overflow-hidden">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-white bg-opacity-25 rounded-3 p-2 me-3">
                                <i class="bi bi-wallet2 fs-4"></i>
                            </div>
                            <h6 class="mb-0 text-white text-opacity-75 fw-medium">Total Pengeluaran</h6>
                        </div>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed List -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light bg-opacity-50">
                    <tr>
                        <th class="px-4 py-3 text-secondary text-xs fw-bold text-uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-secondary text-xs fw-bold text-uppercase">Keterangan</th>
                        <th class="px-4 py-3 text-secondary text-xs fw-bold text-uppercase">Kategori</th>
                        <th class="px-4 py-3 text-secondary text-xs fw-bold text-uppercase text-end">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-secondary divide-opacity-10">
                    @forelse($expenses as $exp)
                        <tr>
                            <td class="px-4 py-3 text-sm text-dark">{{ \Carbon\Carbon::parse($exp->tanggal)->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-sm text-dark">{{ $exp->keterangan }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-2 border border-danger border-opacity-10">{{ $exp->jenisBiaya->nama_biaya }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm fw-bold text-danger text-end">Rp {{ number_format($exp->nominal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-secondary">
                                <p class="mb-0">Tidak ada pengeluaran pada periode ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
