<x-admin-layout 
    title="Laporan Laba & Rugi" 
    :active="'laporan.laba-rugi'"
    :breadcrumbs="[['label' => 'Laporan'], ['label' => 'Laba & Rugi']]"
>
    <x-admin.page-header 
        title="Laporan Laba & Rugi" 
        subtitle="Ringkasan performa keuangan dan keuntungan bersih."
    />

    <!-- Filter Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-5">
        <div class="card-body p-4">
            <form action="{{ route('laporan.laba-rugi') }}" method="GET" class="row align-items-end g-3">
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

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Net Profit Card -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
                <div class="card-body p-5 text-center {{ $netProfit >= 0 ? 'bg-primary bg-gradient text-white' : 'bg-danger bg-gradient text-white' }}">
                    <h6 class="text-uppercase ls-2 mb-3 text-white text-opacity-75 fw-bold">Keuntungan Bersih (Net Profit)</h6>
                    <h1 class="display-3 fw-bold mb-0">Rp {{ number_format($netProfit, 0, ',', '.') }}</h1>
                    <div class="mt-3 badge bg-white bg-opacity-25 px-3 py-2 rounded-pill fw-normal">
                         {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} — {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                    </div>
                </div>
            </div>

            <!-- Breakdown Card -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white p-4 border-bottom">
                    <h5 class="fw-bold mb-0">Rincian Keuangan</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <tbody>
                                <!-- Income Section -->
                                <tr class="bg-light bg-opacity-50">
                                    <td colspan="2" class="px-4 py-2 text-primary fw-bold text-uppercase text-xs ls-1">Pemasukan</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3">Pendapatan Invoice (Lunas)</td>
                                    <td class="px-4 py-3 text-end fw-bold text-dark">Rp {{ number_format($invoiceIncome, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3">Pemasukan Lainnya</td>
                                    <td class="px-4 py-3 text-end fw-bold text-dark">Rp {{ number_format($manualIncome, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="table-success">
                                    <td class="px-4 py-3 fw-bold text-success">Total Pemasukan</td>
                                    <td class="px-4 py-3 text-end fw-bold text-success">Rp {{ number_format($totalIncome, 0, ',', '.') }}</td>
                                </tr>

                                <!-- Expense Section -->
                                <tr class="bg-light bg-opacity-50">
                                    <td colspan="2" class="px-4 py-2 text-danger fw-bold text-uppercase text-xs ls-1">Pengeluaran</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3">Biaya Operasional & Lainnya</td>
                                    <td class="px-4 py-3 text-end fw-bold text-dark">Rp {{ number_format($totalExpense, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="table-danger">
                                    <td class="px-4 py-3 fw-bold text-danger">Total Pengeluaran</td>
                                    <td class="px-4 py-3 text-end fw-bold text-danger">(Rp {{ number_format($totalExpense, 0, ',', '.') }})</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
