<x-admin-layout 
    title="Edit Invoice" 
    :active="'invoices'"
    :breadcrumbs="[
        ['label' => 'Invoices', 'url' => route('invoices.index')],
        ['label' => 'Edit Invoice']
    ]"
>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <x-admin.page-header 
                title="Edit Invoice" 
                subtitle="Update invoice details and payment status."
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
                    <form action="{{ route('invoices.update', $invoice) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Client Selection -->
                        <div class="mb-4">
                            <label for="id_client" class="form-label fw-medium text-dark">Client <span class="text-danger">*</span></label>
                            <select name="id_client" id="id_client" class="form-select rounded-3 p-2.5" required>
                                <option value="" disabled>Select Client...</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ $invoice->id_client == $client->id ? 'selected' : '' }}>
                                        {{ $client->nama_client }} {{ $client->perusahaan ? ' - ' . $client->perusahaan : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-4 mb-4">
                            <!-- Month -->
                            <div class="col-md-6">
                                <label for="bulan" class="form-label fw-medium text-dark">Bulan <span class="text-danger">*</span></label>
                                <select name="bulan" id="bulan" class="form-select rounded-3 p-2.5" required>
                                    @foreach(range(1, 12) as $m)
                                        @php $monthVal = str_pad($m, 2, '0', STR_PAD_LEFT); @endphp
                                        <option value="{{ $monthVal }}" {{ $invoice->bulan == $monthVal ? 'selected' : '' }}>
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
                                        <option value="{{ $y }}" {{ $invoice->tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Amount -->
                        <div class="mb-4">
                            <label for="tagihan" class="form-label fw-medium text-dark">Jumlah Tagihan (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="tagihan" id="tagihan" class="form-control rounded-3 p-2.5" min="0" required value="{{ $invoice->tagihan }}">
                        </div>

                        <!-- Status -->
                        <div class="mb-5">
                            <label for="status_pembayaran" class="form-label fw-medium text-dark">Status Pembayaran</label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status_pembayaran" id="status_0" value="0" {{ $invoice->status_pembayaran == 0 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status_0">
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-2">Belum Lunas</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status_pembayaran" id="status_1" value="1" {{ $invoice->status_pembayaran == 1 ? 'checked' : '' }}>
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
                                <i class="bi bi-check-lg me-1"></i> Update Invoice
                            </button>
                        </div>
                    </form>
                </div>
            </div>
             <!-- Delete Zone -->
             <div class="text-center mt-5">
                <p class="text-secondary small mb-3">Does this invoice need to be removed?</p>
                <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this invoice permanently?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-link link-danger text-decoration-none small fw-bold">
                        <i class="bi bi-trash me-1"></i> Delete Invoice Permanently
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
