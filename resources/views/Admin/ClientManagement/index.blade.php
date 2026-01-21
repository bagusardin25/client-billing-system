<x-admin-layout 
    title="Clients" 
    :active="'clients'"
    :breadcrumbs="[['label' => 'Clients']]"
>
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Client Management</h1>
            <p class="text-secondary mb-0 small">Overview of all active clients for <span class="fw-bold text-primary">{{ now()->translatedFormat('F Y') }}</span></p>
        </div>
        <a href="{{ route('clients.create') }}" class="btn btn-primary d-flex align-items-center gap-2 px-4 py-2 rounded-3 shadow-sm">
            <i class="material-icons-round fs-5">add</i>
            <span class="fw-medium">Add New Client</span>
        </a>
    </div>

    <!-- Main Content Card -->
    <div class="card border border-secondary border-opacity-10 shadow-sm rounded-4 overflow-hidden">
        
        <!-- Filter Bar -->
        <div class="card-header bg-light bg-opacity-50 border-bottom border-secondary border-opacity-10 p-3">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <!-- Search -->
                <div class="position-relative flex-grow-1" style="min-width: 300px;">
                    <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-secondary">
                        <i class="material-icons-round fs-5">search</i>
                    </span>
                    <form action="{{ route('clients.index') }}" method="GET">
                        <input type="text" name="search" class="form-control border-secondary border-opacity-25 bg-white ps-5 py-2 rounded-3 focus-ring focus-ring-primary" placeholder="Search clients by name, company, or phone..." value="{{ request('search') }}">
                    </form>
                </div>
                
                <!-- Filter & Settings -->
                <div class="d-flex gap-2">
                    <select class="form-select border-secondary border-opacity-25 rounded-3 py-2 text-sm" style="width: auto;">
                        <option>All Status</option>
                        <option>Paid</option>
                        <option>Pending</option>
                    </select>
                    <button class="btn btn-outline-secondary border-opacity-25 bg-white text-secondary py-2 px-3 rounded-3 hover-bg-light">
                        <i class="material-icons-round fs-5">tune</i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light bg-opacity-50">
                    <tr>
                        <th class="px-4 py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide text-center" style="width: 60px;">No</th>
                        <th class="px-4 py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide">Nama Client</th>
                        <th class="px-4 py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide">No Telepon</th>
                        <th class="px-4 py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide">Tagihan/Bln</th>
                        <th class="px-4 py-3 text-secondary text-uppercase fw-bold text-xs tracking-wide text-center" style="width: 380px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-secondary divide-opacity-10">
                    @forelse($clients as $index => $client)
                        <tr>
                            <td class="px-4 py-3 text-center text-sm text-secondary">{{ $clients->firstItem() + $index }}</td>
                            <td class="px-4 py-3">
                                <div class="fw-bold text-dark">{{ $client->nama_client }}</div>
                                <div class="text-xs text-secondary">{{ $client->perusahaan ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-secondary">{{ $client->no_telepon ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm fw-bold text-success">
                                Rp. {{ number_format($client->tagihan, 2, ',', '.') }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-success text-white d-flex align-items-center gap-1 px-3 py-1 rounded-2 shadow-sm" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase;">
                                        <i class="material-icons-round" style="font-size: 14px;">send</i> Kirim
                                    </button>
                                    
                                    <a href="{{ route('cabang-usaha.index', ['client_id' => $client->id]) }}" class="btn btn-sm btn-primary text-white d-flex align-items-center gap-1 px-3 py-1 rounded-2 shadow-sm text-decoration-none" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase;">
                                        <i class="material-icons-round" style="font-size: 14px;">business</i> Cabang
                                    </a>
                                    
                                    <a href="{{ route('clients.show', $client) }}" class="btn btn-sm btn-info text-white d-flex align-items-center gap-1 px-3 py-1 rounded-2 shadow-sm text-decoration-none" style="background-color: #0ea5e9; border-color: #0ea5e9; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase;">
                                        <i class="material-icons-round" style="font-size: 14px;">visibility</i> Detail
                                    </a>
                                    
                                    <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm bg-warning bg-opacity-10 text-warning px-2 py-1 rounded-2 hover-bg-warning hover-text-white transition-colors">
                                        <i class="material-icons-round fs-6">edit</i>
                                    </a>
                                    
                                    <form action="{{ route('clients.destroy', $client) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus client ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-2 hover-bg-danger hover-text-white transition-colors">
                                            <i class="material-icons-round fs-6">delete</i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-secondary">
                                <i class="material-icons-round display-4 mb-2 d-block opacity-25">inbox</i>
                                <p class="mb-0">Belum ada data client.</p>
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
                    Showing <span class="fw-bold text-dark">{{ $clients->firstItem() ?? 0 }}</span> to <span class="fw-bold text-dark">{{ $clients->lastItem() ?? 0 }}</span> of <span class="fw-bold text-dark">{{ $clients->total() }}</span> clients
                </p>
                <div>
                    @if($clients->hasPages())
                        {{ $clients->links('pagination::bootstrap-5') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
