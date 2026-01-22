<x-admin-layout 
    title="Laporan Pemasukan" 
    :active="'laporan.pemasukan'"
    :breadcrumbs="[['label' => 'Laporan'], ['label' => 'Pemasukan']]"
>
    <x-admin.page-header 
        title="Laporan Pemasukan" 
        subtitle="Analisa arus kas masuk dari pelunasan invoice dan sumber lainnya."
    />

    <!-- Filter Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('laporan.pemasukan') }}" method="GET" class="row align-items-end g-3">
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

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Income -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-success bg-gradient text-white h-100 position-relative overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-white bg-opacity-25 rounded-3 p-2 me-3">
                            <i class="bi bi-wallet2 fs-4"></i>
                        </div>
                        <h6 class="mb-0 text-white text-opacity-75 fw-medium">Total Pemasukan</h6>
                    </div>
                    <h3 class="fw-bold mb-0">Rp {{ number_format($grandTotal, 0, ',', '.') }}</h3>
                    <div class="mt-2 small text-white text-opacity-75">
                         Periode Terpilih
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Breakdown -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 me-3">
                            <i class="bi bi-receipt fs-4"></i>
                        </div>
                        <h6 class="mb-0 text-secondary fw-medium">Dari Invoice (Lunas)</h6>
                    </div>
                    <h4 class="fw-bold text-dark mb-0">Rp {{ number_format($totalInvoice, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        <!-- Manual Breakdown -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-2 me-3">
                            <i class="bi bi-cash-coin fs-4"></i>
                        </div>
                        <h6 class="mb-0 text-secondary fw-medium">Pemasukan Lainnya</h6>
                    </div>
                    <h4 class="fw-bold text-dark mb-0">Rp {{ number_format($totalManual, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed List -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-bottom p-3">
            <ul class="nav nav-pills card-header-pills" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill px-4" id="pills-invoice-tab" data-bs-toggle="pill" data-bs-target="#pills-invoice" type="button" role="tab">Riwayat Invoice</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-4" id="pills-manual-tab" data-bs-toggle="pill" data-bs-target="#pills-manual" type="button" role="tab">Pemasukan Lainnya</button>
                </li>
            </ul>
        </div>
        <div class="card-body p-0">
            <div class="tab-content" id="pills-tabContent">
                
                <!-- Invoice Table -->
                <div class="tab-pane fade show active" id="pills-invoice" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 text-secondary text-xs fw-bold text-uppercase">Tgl Bayar</th>
                                    <th class="px-4 py-3 text-secondary text-xs fw-bold text-uppercase">No. Invoice</th>
                                    <th class="px-4 py-3 text-secondary text-xs fw-bold text-uppercase">Client</th>
                                    <th class="px-4 py-3 text-secondary text-xs fw-bold text-uppercase text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $inv)
                                    <tr>
                                        <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($inv->tanggal_pembayaran)->format('d M Y') }}</td>
                                        <td class="px-4 py-3 text-sm fw-medium text-primary">{{ $inv->kode_invoive }}</td>
                                        <td class="px-4 py-3 text-sm fw-bold text-dark">{{ $inv->client->nama_client }}</td>
                                        <td class="px-4 py-3 text-sm fw-bold text-success text-end">Rp {{ number_format($inv->tagihan, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-secondary">Tidak ada data pembayaran invoice pada periode ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Manual Table -->
                <div class="tab-pane fade" id="pills-manual" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 text-secondary text-xs fw-bold text-uppercase">Tanggal</th>
                                    <th class="px-4 py-3 text-secondary text-xs fw-bold text-uppercase">Keterangan</th>
                                    <th class="px-4 py-3 text-secondary text-xs fw-bold text-uppercase">Kategori</th>
                                    <th class="px-4 py-3 text-secondary text-xs fw-bold text-uppercase text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($manualIncomes as $minc)
                                    <tr>
                                        <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($minc->tanggal)->format('d M Y') }}</td>
                                        <td class="px-4 py-3 text-sm text-dark">{{ $minc->keterangan }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="badge bg-light text-secondary border">{{ $minc->jenisBiaya->nama_biaya }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-sm fw-bold text-success text-end">Rp {{ number_format($minc->nominal, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-secondary">Tidak ada data pemasukan lainnya pada periode ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
