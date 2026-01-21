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
                
                <!-- Auto Reminder Toggle -->
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm d-flex align-items-center gap-2 px-3 py-2 rounded-3 shadow-sm" 
                            id="autoReminderToggle"
                            data-active="false"
                            onclick="toggleAutoReminder(this)"
                            style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none;">
                        <i class="bi bi-bell-fill"></i>
                        <span class="fw-medium small">Auto Reminder</span>
                        <span class="badge bg-white text-success ms-1 small" id="reminderStatus">OFF</span>
                    </button>
                    
                    <!-- Settings Button (opens modal) -->
                    <button type="button" class="btn btn-sm btn-outline-secondary border-opacity-25 bg-white text-secondary px-2 py-2 rounded-3" 
                            data-bs-toggle="modal" 
                            data-bs-target="#reminderSettingsModal"
                            title="Pengaturan Reminder">
                        <i class="bi bi-gear"></i>
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
                                        <i class="material-icons-round" style="font-size: 14px;">send</i> Kirim
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

    <!-- Reminder Settings Modal -->
    <div class="modal fade" id="reminderSettingsModal" tabindex="-1" aria-labelledby="reminderSettingsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="reminderSettingsModalLabel">
                        <i class="bi bi-bell-fill text-success me-2"></i>Pengaturan Auto Reminder
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-2">
                    <p class="text-secondary small mb-4">Atur jadwal dan template pesan untuk pengiriman reminder otomatis ke semua klien.</p>
                    
                    <form id="reminderSettingsForm">
                        <!-- Send Day -->
                        <div class="mb-4">
                            <label for="sendDay" class="form-label fw-medium text-dark">Tanggal Pengiriman</label>
                            <select id="sendDay" class="form-select rounded-3">
                                @for($i = 1; $i <= 28; $i++)
                                    <option value="{{ $i }}" {{ $i == 1 ? 'selected' : '' }}>Tanggal {{ $i }} setiap bulan</option>
                                @endfor
                            </select>
                            <div class="form-text">Reminder akan dikirim pada tanggal ini setiap bulannya.</div>
                        </div>
                        
                        <!-- Message Template -->
                        <div class="mb-4">
                            <label for="messageTemplate" class="form-label fw-medium text-dark">Template Pesan</label>
                            <textarea id="messageTemplate" class="form-control rounded-3" rows="6" placeholder="Tulis template pesan...">Halo *{nama}* ({perusahaan}),

Kami ingin mengingatkan mengenai tagihan Anda untuk periode *{bulan}*.

💰 *Total Tagihan:* {tagihan}

Mohon segera lakukan pembayaran untuk menghindari keterlambatan.

Terima kasih atas kerjasamanya.
— *PyramidSoft*</textarea>
                            <div class="form-text">
                                Placeholder yang tersedia: <code>{nama}</code>, <code>{perusahaan}</code>, <code>{bulan}</code>, <code>{tagihan}</code>
                            </div>
                        </div>
                        
                        <!-- Preview -->
                        <div class="mb-3">
                            <label class="form-label fw-medium text-dark">Preview Pesan</label>
                            <div id="messagePreview" class="p-3 bg-light rounded-3 small" style="white-space: pre-wrap; font-family: inherit;">
                                Halo <strong>Pak Herry</strong> (Orzora Kosmetic),

Kami ingin mengingatkan mengenai tagihan Anda untuk periode <strong>Januari 2026</strong>.

💰 <strong>Total Tagihan:</strong> Rp 1.150.000

Mohon segera lakukan pembayaran untuk menghindari keterlambatan.

Terima kasih atas kerjasamanya.
— <strong>PyramidSoft</strong>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light text-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary px-4" onclick="saveReminderSettings()">
                        <i class="bi bi-check-lg me-1"></i> Simpan Pengaturan
                    </button>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    // Toggle Auto Reminder
    function toggleAutoReminder(btn) {
        const isActive = btn.dataset.active === 'true';
        const newState = !isActive;
        
        btn.dataset.active = newState;
        const statusBadge = document.getElementById('reminderStatus');
        
        if (newState) {
            btn.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
            statusBadge.textContent = 'ON';
            statusBadge.classList.remove('text-danger', 'text-secondary');
            statusBadge.classList.add('text-success');
            showToast('Auto Reminder diaktifkan', 'success');
        } else {
            btn.style.background = 'linear-gradient(135deg, #64748b 0%, #475569 100%)';
            statusBadge.textContent = 'OFF';
            statusBadge.classList.remove('text-success');
            statusBadge.classList.add('text-secondary');
            showToast('Auto Reminder dinonaktifkan', 'warning');
        }
        
        // TODO: Save state to backend
        // fetch('/api/reminder-settings', { method: 'POST', body: JSON.stringify({ is_active: newState }) });
    }
    
    // Save Reminder Settings
    function saveReminderSettings() {
        const sendDay = document.getElementById('sendDay').value;
        const messageTemplate = document.getElementById('messageTemplate').value;
        
        // TODO: Save to backend
        console.log('Saving settings:', { sendDay, messageTemplate });
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('reminderSettingsModal'));
        modal.hide();
        
        showToast('Pengaturan berhasil disimpan', 'success');
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
        }, 2500);
    }
    
    // Update preview when template changes
    document.getElementById('messageTemplate')?.addEventListener('input', function() {
        const template = this.value;
        const preview = template
            .replace('{nama}', '<strong>Pak Herry</strong>')
            .replace('{perusahaan}', 'Orzora Kosmetic')
            .replace('{bulan}', '<strong>Januari 2026</strong>')
            .replace('{tagihan}', 'Rp 1.150.000')
            .replace(/\*(.*?)\*/g, '<strong>$1</strong>');
        document.getElementById('messagePreview').innerHTML = preview;
    });
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
