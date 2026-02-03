<x-admin-layout 
    title="Invoice" 
    :active="'invoices'"
    :breadcrumbs="[['label' => 'Invoices']]"
>
    <!-- Page Header -->
    <x-admin.page-header 
        title="Invoice Management" 
        subtitle="Manage and track all client billing invoices."
    >
        <x-slot:actions>
            <button type="button" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#createInvoiceModal">
                <i class="bi bi-plus-lg"></i>
                Create Invoice
            </button>
        </x-slot:actions>
    </x-admin.page-header>

    <!-- Main Content Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        
        <!-- Filter Bar -->
        <div class="card-header bg-light bg-opacity-50 border-bottom border-secondary border-opacity-10 p-3">
            <form action="{{ route('invoices.index') }}" method="GET">
                <div class="row g-3 align-items-center">
                    <!-- Search Client -->
                    <div class="col-md-4">
                        <div class="position-relative">
                            <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-secondary">
                                <i class="material-icons-round fs-5">search</i>
                            </span>
                            <input type="text" name="search" class="form-control border-secondary border-opacity-25 bg-white ps-5 py-2 rounded-3 focus-ring focus-ring-primary" 
                                   placeholder="Search by Client Name..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Month Filter -->
                    <div class="col-md-3">
                        <select name="bulan" class="form-select border-secondary border-opacity-25 rounded-3 py-2">
                            <option value="">All Months</option>
                            @foreach(range(1, 12) as $m)
                                <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ request('bulan') == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Year Filter -->
                    <div class="col-md-3">
                        <select name="tahun" class="form-select border-secondary border-opacity-25 rounded-3 py-2">
                            <option value="">All Years</option>
                            @foreach(range(date('Y') - 1, date('Y') + 2) as $y)
                                <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Actions -->
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 shadow-sm fw-medium">
                            <i class="bi bi-funnel-fill me-1"></i> Filter
                        </button>
                        <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary py-2 rounded-3" title="Reset Filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light bg-opacity-50">
                    <tr>
                        <th class="px-4 py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide text-center" style="width: 60px;">No</th>
                        <th class="px-4 py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide">Nama Client</th>
                        <th class="px-4 py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide text-end">Tagihan/Bln</th>
                        <th class="px-4 py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide text-center">Bulan</th>
                        <th class="px-4 py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide text-center">Status</th>
                        <th class="px-4 py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide text-center" style="width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-secondary divide-opacity-10">
                    @forelse($invoices as $index => $invoice)
                        <tr>
                            <td class="px-4 py-3 text-center text-sm text-secondary">{{ $invoices->firstItem() + $index }}</td>
                            <td class="px-4 py-3">
                                <div class="fw-bold text-dark">{{ $invoice->client->nama_client }}</div>
                                <div class="text-xs text-secondary">{{ $invoice->client->perusahaan ?? 'Personal' }}</div>
                            </td>
                            <td class="px-4 py-3 text-end fw-bold text-dark">
                                Rp {{ number_format($invoice->tagihan, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-secondary">
                                {{ DateTime::createFromFormat('!m', $invoice->bulan)->format('F') }} {{ $invoice->tahun }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="badge {{ ($invoice->status_pembayaran ?? 0) == 1 ? 'bg-success' : 'bg-danger' }} rounded-pill px-3 py-2">
                                    {{ ($invoice->status_pembayaran ?? 0) == 1 ? 'Lunas' : 'Belum Lunas' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="d-flex justify-content-center gap-2">
                                    @if(($invoice->status_pembayaran ?? 0) == 0)
                                        
                                        <button 
                                            onclick="openPaymentModal(
                                                '{{ $invoice->id }}', 
                                                '{{ addslashes($invoice->client->nama_client) }}', 
                                                '{{ DateTime::createFromFormat('!m', $invoice->bulan)->format('F') }} {{ $invoice->tahun }}', 
                                                'Rp {{ number_format($invoice->tagihan, 0, ',', '.') }}',
                                                '{{ route('invoices.mark-paid', $invoice) }}'
                                            )"
                                            class="btn btn-sm btn-primary text-white d-flex align-items-center gap-1 px-3 py-1 rounded-2 shadow-sm text-decoration-none" 
                                            style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase;">
                                            <i class="material-icons-round" style="font-size: 14px;">payments</i> Pembayaran
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-light text-secondary d-flex align-items-center gap-1 px-3 py-1 rounded-2 border disabled" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase;" disabled>
                                            <i class="bi bi-check-circle-fill text-success" style="font-size: 14px;"></i> Lunas
                                        </button>
                                    @endif
                                    
                                    <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus invoice ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-2 hover-bg-danger hover-text-white transition-colors" title="Hapus Invoice">
                                            <i class="material-icons-round fs-6">delete</i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-secondary">
                                <i class="material-icons-round display-4 mb-2 d-block opacity-25">receipt_long</i>
                                <p class="mb-0">Tidak ada data invoice ditemukan.</p>
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
                    Showing <span class="fw-bold text-dark">{{ $invoices->firstItem() ?? 0 }}</span> to <span class="fw-bold text-dark">{{ $invoices->lastItem() ?? 0 }}</span> of <span class="fw-bold text-dark">{{ $invoices->total() }}</span> invoices
                </p>
                <div>
                     @if($invoices->hasPages())
                        {{ $invoices->links('pagination::bootstrap-5') }}
                    @endif
                </div>
            </div>
        </div>
    <!-- Create Invoice Modal -->
    <div class="modal fade" id="createInvoiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle-fill text-primary me-2"></i>Buat Invoice Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('invoices.store') }}" method="POST">
                        @csrf
                        
                        <!-- Client Selection -->
                        <div class="mb-4">
                            <label for="id_client" class="form-label fw-medium text-dark">Client <span class="text-danger">*</span></label>
                            <select name="id_client" id="create_id_client" class="form-select rounded-3 p-2.5" required onchange="updateCreateClientBill(this)">
                                <option value="" selected disabled>Select Client...</option>
                                <option value="all" class="fw-bold bg-light">-- Semua Client --</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">
                                        {{ $client->nama_client }} {{ $client->perusahaan ? ' - ' . $client->perusahaan : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text" id="createClientHelpParam">Select the client to bill.</div>
                        </div>

                        <div class="row g-4 mb-4">
                            <!-- Month -->
                            <div class="col-md-6">
                                <label for="bulan" class="form-label fw-medium text-dark">Bulan <span class="text-danger">*</span></label>
                                <select name="bulan" id="create_bulan" class="form-select rounded-3 p-2.5" required>
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ date('m') == $m ? 'selected' : '' }}>
                                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Year -->
                            <div class="col-md-6">
                                <label for="tahun" class="form-label fw-medium text-dark">Tahun <span class="text-danger">*</span></label>
                                <select name="tahun" id="create_tahun" class="form-select rounded-3 p-2.5" required>
                                    @foreach(range(date('Y') - 1, date('Y') + 2) as $y)
                                        <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-5">
                            <label class="form-label fw-medium text-dark">Status Pembayaran</label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status_pembayaran" id="create_status_0" value="0" checked>
                                    <label class="form-check-label" for="create_status_0">
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-2">Belum Lunas</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status_pembayaran" id="create_status_1" value="1">
                                    <label class="form-check-label" for="create_status_1">
                                        <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-2">Lunas</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3">
                            <button type="button" class="btn btn-light text-secondary px-4 py-2 rounded-3" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 shadow-sm fw-bold">
                                <i class="bi bi-check-lg me-1"></i> Create Invoice
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-wallet2 text-primary me-2"></i>Catat Pembayaran
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="paymentForm" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Invoice Info Summary -->
                        <div class="bg-light p-3 rounded-3 mb-4 border border-secondary border-opacity-10">
                            <h6 class="fw-bold text-dark mb-1" id="modalClientName">Client Name</h6>
                            <div class="d-flex justify-content-between text-sm text-secondary">
                                <span id="modalPeriod">Januari 2026</span>
                                <span class="fw-bold text-dark" id="modalAmount">Rp 500.000</span>
                            </div>
                        </div>

                        <!-- Date Input -->
                        <div class="mb-4">
                            <label for="tanggal_pembayaran" class="form-label fw-medium text-dark">Tanggal Pembayaran</label>
                            <input type="date" name="tanggal_pembayaran" class="form-control rounded-3" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <!-- WhatsApp Checkbox -->
                        <div class="form-check bg-success bg-opacity-10 p-3 rounded-3 border border-success border-opacity-25">
                            <input class="form-check-input" type="checkbox" name="send_whatsapp" id="sendWhatsapp" checked>
                            <label class="form-check-label fw-medium text-success" for="sendWhatsapp">
                                <i class="bi bi-whatsapp me-1"></i> Kirim Kwitansi via WhatsApp
                            </label>
                            <div class="text-xs text-secondary mt-1 ms-1">
                                Otomatis mengirim pesan konfirmasi pembayaran ke nomor klien.
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary py-2 rounded-3 fw-bold shadow-sm">
                                <i class="bi bi-check-lg me-1"></i> Simpan & Lunasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function updateCreateClientBill(select) {
            const helpText = document.getElementById('createClientHelpParam');
            
            if (select.value === 'all') {
                helpText.innerHTML = '<span class="text-primary fw-medium"><i class="bi bi-info-circle me-1"></i>Bulk Mode:</span> System will generate invoices for ALL clients using their default bill amount.';
            } else {
                helpText.textContent = 'Select the client to bill. Invoice amount will be auto-filled from client data.';
            }
        }

        function openPaymentModal(id, clientName, period, amount, updateUrl) {
            // Populate Modal Data
            document.getElementById('modalClientName').textContent = clientName;
            document.getElementById('modalPeriod').textContent = period;
            document.getElementById('modalAmount').textContent = amount;
            
            // Set Form Action
            const form = document.getElementById('paymentForm');
            form.action = updateUrl;
            
            // Show Modal
            const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
            modal.show();
        }
    </script>
    @endpush
</x-admin-layout>
