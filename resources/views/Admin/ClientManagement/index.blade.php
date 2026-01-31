<x-admin-layout 
    title="Clients" 
    :active="'clients'"
    :breadcrumbs="[['label' => 'Clients']]"
>
    <!-- Page Header -->
    <x-admin.page-header 
        title="Client Management" 
        subtitle="Overview of all active clients for <span class='fw-bold text-primary'>{{ now()->translatedFormat('F Y') }}</span>"
        action-url="{{ route('clients.create') }}"
        action-label="Add New Client"
    />

    <!-- Main Content Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        
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
                
                <!-- Kirim Semua Reminder Button -->
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm d-flex align-items-center gap-2 px-3 py-2 rounded-3 shadow-sm" 
                            id="sendAllReminderBtn"
                            onclick="sendAllReminders()"
                            style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none;">
                        <i class="bi bi-whatsapp"></i>
                        <span class="fw-medium small">Kirim Semua Invoice</span>
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
                                    <a href="{{ route('clients.whatsapp-reminder', $client) }}" target="_blank" class="btn btn-sm btn-success text-white d-flex align-items-center gap-1 px-3 py-1 rounded-2 shadow-sm text-decoration-none" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase;">
                                        <i class="bi bi-whatsapp" style="font-size: 14px;"></i> Kirim
                                    </a>
                                    
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

@push('scripts')
<script>
    // Kirim Semua Reminder - Membuka WhatsApp untuk semua client secara berurutan
    function sendAllReminders() {
        const btn = document.getElementById('sendAllReminderBtn');
        const originalHTML = btn.innerHTML;
        
        // Disable button dan tampilkan loading
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> <span class="fw-medium small">Mengirim...</span>';
        
        // Ambil semua link WhatsApp reminder
        const reminderLinks = document.querySelectorAll('a[href*="whatsapp-reminder"]');
        
        if (reminderLinks.length === 0) {
            showToast('Tidak ada client yang bisa dikirim reminder', 'warning');
            btn.disabled = false;
            btn.innerHTML = originalHTML;
            return;
        }
        
        // Konfirmasi sebelum mengirim
        if (!confirm(`Apakah Anda yakin ingin mengirim reminder ke ${reminderLinks.length} client?\n\nSistem akan membuka ${reminderLinks.length} tab WhatsApp.`)) {
            btn.disabled = false;
            btn.innerHTML = originalHTML;
            return;
        }
        
        // Buka semua link WhatsApp dengan delay untuk menghindari blocking
        let successCount = 0;
        let index = 0;
        
        function openNextReminder() {
            if (index < reminderLinks.length) {
                const link = reminderLinks[index];
                const newWindow = window.open(link.href, '_blank');
                
                if (newWindow) {
                    successCount++;
                }
                
                index++;
                // Delay 500ms antar pembukaan untuk menghindari browser blocking
                setTimeout(openNextReminder, 500);
            } else {
                // Selesai mengirim semua
                btn.disabled = false;
                btn.innerHTML = originalHTML;
                showToast(`Berhasil membuka ${successCount} dari ${reminderLinks.length} reminder WhatsApp`, 'success');
            }
        }
        
        openNextReminder();
    }
    
    // Simple Toast Notification
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'info'} position-fixed shadow-lg`;
        toast.style.cssText = 'top: 80px; right: 20px; z-index: 9999; min-width: 250px; animation: slideIn 0.3s ease;';
        toast.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>${message}`;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>

<style>
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
</style>
@endpush
</x-admin-layout>
