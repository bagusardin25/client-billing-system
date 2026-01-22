<x-admin-layout 
    title="Create Invoice" 
    :active="'invoices'"
    :breadcrumbs="[
        ['label' => 'Invoices', 'url' => route('invoices.index')],
        ['label' => 'Create Invoice']
    ]"
>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <x-admin.page-header 
                title="Create Invoice" 
                subtitle="Generate a new invoice for a client."
            >
                <x-slot:actions>
                    <a href="{{ route('invoices.index') }}" class="btn btn-light text-secondary d-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </x-slot:actions>
            </x-admin.page-header>

            <!-- Form Card -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('invoices.store') }}" method="POST">
                        @csrf
                        
                        <!-- Client Selection -->
                        <div class="mb-4">
                            <label for="id_client" class="form-label fw-medium text-dark">Client <span class="text-danger">*</span></label>
                            <select name="id_client" id="id_client" class="form-select rounded-3 p-2.5" required onchange="updateClientBill(this)">
                                <option value="" selected disabled>Select Client...</option>
                                <option value="all" class="fw-bold bg-light">-- Semua Client --</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" data-bill="{{ $client->tagihan }}">
                                        {{ $client->nama_client }} {{ $client->perusahaan ? ' - ' . $client->perusahaan : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text" id="clientHelpParam">Select the client to bill.</div>
                        </div>

                        <div class="row g-4 mb-4">
                            <!-- Month -->
                            <div class="col-md-6">
                                <label for="bulan" class="form-label fw-medium text-dark">Bulan <span class="text-danger">*</span></label>
                                <select name="bulan" id="bulan" class="form-select rounded-3 p-2.5" required>
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
                                <select name="tahun" id="tahun" class="form-select rounded-3 p-2.5" required>
                                    @foreach(range(date('Y') - 1, date('Y') + 2) as $y)
                                        <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-5">
                            <label for="status_pembayaran" class="form-label fw-medium text-dark">Status Pembayaran</label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status_pembayaran" id="status_0" value="0" checked>
                                    <label class="form-check-label" for="status_0">
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-2">Belum Lunas</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status_pembayaran" id="status_1" value="1">
                                    <label class="form-check-label" for="status_1">
                                        <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-2">Lunas</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('invoices.index') }}" class="btn btn-light text-secondary px-4 py-2 rounded-3">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 shadow-sm fw-bold">
                                <i class="bi bi-check-lg me-1"></i> Create Invoice
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function updateClientBill(select) {
            const helpText = document.getElementById('clientHelpParam');
            
            if (select.value === 'all') {
                helpText.innerHTML = '<span class="text-primary fw-medium"><i class="bi bi-info-circle me-1"></i>Bulk Mode:</span> System will generate invoices for ALL clients using their default bill amount.';
            } else {
                helpText.textContent = 'Select the client to bill. Invoice amount will be auto-filled from client data.';
            }
        }
    </script>
    @endpush
</x-admin-layout>
