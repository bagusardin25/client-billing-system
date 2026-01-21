<x-admin-layout 
    title="Client Profile" 
    :active="'clients'"
    :breadcrumbs="[
        ['label' => 'Clients', 'url' => route('clients.index')],
        ['label' => 'Profile']
    ]"
>
    <!-- Profile Header -->
    <div class="card border-0 shadow-sm mb-4 overflow-hidden rounded-4">
        <div class="card-body p-0">
            <div class="bg-primary bg-gradient p-5 text-white">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div class="d-flex align-items-center mb-3 mb-md-0">
                        <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-4 shadow" style="width: 80px; height: 80px; font-size: 2rem; fontWeight: bold;">
                            {{ substr($client->nama_client, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="fw-bold mb-1">{{ $client->nama_client }}</h2>
                            <p class="mb-0 text-white-50"><i class="bi bi-building me-2"></i>{{ $client->perusahaan ?? 'Personal Account' }}</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('clients.edit', $client) }}" class="btn btn-light text-primary fw-medium px-4 shadow-sm">
                            <i class="bi bi-pencil me-2"></i>Edit Profile
                        </a>
                        <!-- More Actions Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-primary bg-white bg-opacity-25 border-0 text-white" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li>
                                    <form action="{{ route('clients.destroy', $client) }}" method="POST" onsubmit="return confirm('Delete client?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>Delete Client</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Key Stats -->
            <div class="row g-0 bg-white">
                <div class="col-md-3 border-end">
                    <div class="p-4 text-center">
                        <label class="text-uppercase text-secondary fw-bold small tracking-wide mb-1">Status</label>
                        <div>
                            <span class="badge {{ $client->status_pembayaran ? 'bg-success' : 'bg-warning' }} px-3 py-2 rounded-pill">
                                {{ $client->status_pembayaran ? 'PAID' : 'UNPAID' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 border-end">
                    <div class="p-4 text-center">
                        <label class="text-uppercase text-secondary fw-bold small tracking-wide mb-1">Monthly Fee</label>
                        <h5 class="fw-bold text-dark mb-0">Rp {{ number_format($client->tagihan, 0, ',', '.') }}</h5>
                    </div>
                </div>
                <div class="col-md-3 border-end">
                    <div class="p-4 text-center">
                        <label class="text-uppercase text-secondary fw-bold small tracking-wide mb-1">Client Code</label>
                        <h5 class="fw-bold text-dark mb-0">{{ $client->kode_client ?? '-' }}</h5>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-4 text-center">
                        <label class="text-uppercase text-secondary fw-bold small tracking-wide mb-1">Phone</label>
                        <div>
                            @if($client->no_telepon)
                                <a href="https://wa.me/{{ $client->no_telepon }}" target="_blank" class="text-decoration-none fw-bold text-dark">
                                    <i class="bi bi-whatsapp text-success me-1"></i> {{ $client->no_telepon }}
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0 text-dark">About Client</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="text-secondary small fw-bold text-uppercase mb-1">Job Title</label>
                        <p class="fw-medium text-dark mb-0">{{ $client->jabatan ?? 'Not specified' }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="text-secondary small fw-bold text-uppercase mb-1">Address</label>
                        <p class="fw-medium text-dark mb-0">{{ $client->alamat ?? 'No address provided' }}</p>
                    </div>
                    <div>
                        <label class="text-secondary small fw-bold text-uppercase mb-1">Last Updated</label>
                        <p class="fw-medium text-dark mb-0">{{ $client->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Tabs -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white p-0 border-bottom-0">
                    <ul class="nav nav-tabs nav-fill border-bottom" id="clientTabs" role="tablist">
                         <li class="nav-item" role="presentation">
                            <button class="nav-link active py-3 fw-bold text-uppercase small" id="branches-tab" data-bs-toggle="tab" data-bs-target="#branches" type="button" role="tab">Business Branches</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-3 fw-bold text-uppercase small" id="invoices-tab" data-bs-toggle="tab" data-bs-target="#invoices" type="button" role="tab">Invoices</button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-0">
                    <div class="tab-content" id="clientTabsContent">
                        <!-- Branches Tab -->
                        <div class="tab-pane fade show active" id="branches" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom bg-light bg-opacity-50">
                                <h6 class="mb-0 fw-bold text-secondary normal-case">Manage Branches</h6>
                                <a href="{{ route('cabang-usaha.create', ['client_id' => $client->id]) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg"></i> Add Branch
                                </a>
                            </div>
                            
                            @if($client->cabangUsaha->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="ps-4">Branch Name</th>
                                                <th>Location</th>
                                                <th class="text-end pe-4">Bill</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($client->cabangUsaha as $cabang)
                                                <tr>
                                                    <td class="ps-4 fw-medium text-dark">{{ $cabang->nama_cabang }}</td>
                                                    <td class="text-secondary">{{ $cabang->alamat ?? '-' }}</td>
                                                    <td class="text-end pe-4 text-success fw-bold">Rp {{ number_format($cabang->tagihan ?? 0, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="p-5 text-center">
                                    <i class="bi bi-shop fs-1 text-muted mb-3 d-block"></i>
                                    <p class="text-muted mb-0">No business branches added yet.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Invoices Tab -->
                        <div class="tab-pane fade" id="invoices" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom bg-light bg-opacity-50">
                                <h6 class="mb-0 fw-bold text-secondary text-uppercase small">TAGIHAN INVOICE</h6>
                                <a href="{{ route('invoices.create', ['client_id' => $client->id]) }}" class="btn btn-sm btn-primary shadow-sm">
                                    <i class="bi bi-plus-lg"></i> Create Invoice
                                </a>
                            </div>
                            
                            @if($client->invoices->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light bg-opacity-50">
                                            <tr>
                                                <th class="ps-4 py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide text-center" style="width: 60px;">No</th>
                                                <th class="py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide text-center">Bulan/Tahun</th>
                                                <th class="py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide text-center">Status</th>
                                                <th class="text-end pe-4 py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide">Tagihan</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-secondary divide-opacity-10">
                                            @foreach($client->invoices as $index => $invoice)
                                                <tr>
                                                    <td class="ps-4 text-center text-secondary">{{ $index + 1 }}</td>
                                                    <td class="text-center fw-medium text-dark">
                                                        {{ str_pad($invoice->bulan, 2, '0', STR_PAD_LEFT) }} / {{ $invoice->tahun }}
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge {{ ($invoice->status_pembayaran ?? 0) == 1 ? 'bg-success' : 'bg-danger' }} rounded-pill px-3">
                                                            {{ ($invoice->status_pembayaran ?? 0) == 1 ? 'Lunas' : 'Belum Lunas' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end pe-4 fw-medium text-dark">
                                                        Rp {{ number_format($invoice->tagihan ?? 0, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <!-- Total Row -->
                                            <tr class="bg-light bg-opacity-25">
                                                <td colspan="3" class="text-end py-3 fw-bold text-secondary">Total Tagihan :</td>
                                                <td class="text-end pe-4 py-3 fs-5 fw-bold text-dark">
                                                    Rp. {{ number_format($client->invoices->sum('tagihan'), 2, ',', '.') }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="p-5 text-center">
                                    <i class="bi bi-receipt fs-1 text-muted mb-3 d-block"></i>
                                    <p class="text-muted mb-0">No invoices generated yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
