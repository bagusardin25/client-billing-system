<x-admin-layout 
    title="Clients" 
    :active="'clients'"
    :breadcrumbs="[['label' => 'Clients']]"
>
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h2 fw-bold text-body-emphasis mb-1">Client Management</h1>
            <p class="text-secondary small mb-0">Overview of all active clients for <span class='fw-bold text-primary'>{{ now()->translatedFormat('F Y') }}</span></p>
        </div>
        <button type="button" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addClientModal">
            <i class="bi bi-plus-lg"></i>
            <span>Add New Client</span>
        </button>
    </div>

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
                                    
                                    <button type="button" class="btn btn-sm bg-warning bg-opacity-10 text-warning px-2 py-1 rounded-2 hover-bg-warning hover-text-white transition-colors" onclick="openEditModal({{ json_encode($client) }})">
                                        <i class="material-icons-round fs-6">edit</i>
                                    </button>
                                    
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

    <!-- Add Client Modal -->
    <div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold" id="addClientModalLabel">
                        <i class="bi bi-person-plus-fill text-primary me-2"></i>Tambah Client Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form id="addClientForm" action="{{ route('clients.store') }}" method="POST">
                    @csrf
                    <div class="modal-body px-4 py-3">
                        <p class="text-secondary small mb-4">Masukkan informasi bisnis dan kontak client di bawah ini.</p>
                        
                        <h6 class="text-uppercase text-secondary fw-bold small mb-3 tracking-wide">Informasi Client</h6>
                        
                        <div class="row g-3">
                            <!-- Basic Info -->
                            <div class="col-md-6">
                                <label for="nama_client" class="form-label fw-medium text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama_client" id="nama_client" class="form-control" placeholder="Contoh: Pak Budi" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="perusahaan" class="form-label fw-medium text-dark">Nama Perusahaan</label>
                                <input type="text" name="perusahaan" id="perusahaan" class="form-control" placeholder="Contoh: PT Maju Jaya">
                            </div>

                            <!-- Contact Info -->
                            <div class="col-md-6">
                                <label for="no_telepon" class="form-label fw-medium text-dark">Nomor Telepon</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-secondary"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="no_telepon" id="no_telepon" class="form-control" placeholder="08...">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="jabatan" class="form-label fw-medium text-dark">Jabatan</label>
                                <input type="text" name="jabatan" id="jabatan" class="form-control" placeholder="Contoh: Manager">
                            </div>

                            <div class="col-12">
                                <label for="alamat" class="form-label fw-medium text-dark">Alamat</label>
                                <textarea name="alamat" id="alamat" rows="2" class="form-control" placeholder="Alamat lengkap kantor"></textarea>
                            </div>
                        </div>

                        <hr class="my-4 border-secondary-subtle">

                        <h6 class="text-uppercase text-secondary fw-bold small mb-3 tracking-wide">Detail Tagihan</h6>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="kode_client" class="form-label fw-medium text-dark">Kode Client</label>
                                <input type="text" name="kode_client" id="kode_client" class="form-control" placeholder="Contoh: CL-001">
                            </div>

                            <div class="col-md-6">
                                <label for="tagihan" class="form-label fw-medium text-dark">Tagihan per Bulan</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light fw-bold text-secondary">Rp</span>
                                    <input type="number" name="tagihan" id="tagihan" class="form-control" value="0" min="0">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-2">
                        <button type="button" class="btn btn-light text-secondary px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4" id="submitClientBtn">
                            <i class="bi bi-check-lg me-1"></i> Simpan Client
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Client Modal -->
    <div class="modal fade" id="editClientModal" tabindex="-1" aria-labelledby="editClientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold" id="editClientModalLabel">
                        <i class="bi bi-pencil-square text-warning me-2"></i>Edit Client
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form id="editClientForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body px-4 py-3">
                        <p class="text-secondary small mb-4">Perbarui informasi bisnis dan kontak client di bawah ini.</p>
                        
                        <h6 class="text-uppercase text-secondary fw-bold small mb-3 tracking-wide">Informasi Client</h6>
                        
                        <div class="row g-3">
                            <!-- Basic Info -->
                            <div class="col-md-6">
                                <label for="edit_nama_client" class="form-label fw-medium text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama_client" id="edit_nama_client" class="form-control" placeholder="Contoh: Pak Budi" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="edit_perusahaan" class="form-label fw-medium text-dark">Nama Perusahaan</label>
                                <input type="text" name="perusahaan" id="edit_perusahaan" class="form-control" placeholder="Contoh: PT Maju Jaya">
                            </div>

                            <!-- Contact Info -->
                            <div class="col-md-6">
                                <label for="edit_no_telepon" class="form-label fw-medium text-dark">Nomor Telepon</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-secondary"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="no_telepon" id="edit_no_telepon" class="form-control" placeholder="08...">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="edit_jabatan" class="form-label fw-medium text-dark">Jabatan</label>
                                <input type="text" name="jabatan" id="edit_jabatan" class="form-control" placeholder="Contoh: Manager">
                            </div>

                            <div class="col-12">
                                <label for="edit_alamat" class="form-label fw-medium text-dark">Alamat</label>
                                <textarea name="alamat" id="edit_alamat" rows="2" class="form-control" placeholder="Alamat lengkap kantor"></textarea>
                            </div>
                        </div>

                        <hr class="my-4 border-secondary-subtle">

                        <h6 class="text-uppercase text-secondary fw-bold small mb-3 tracking-wide">Detail Tagihan</h6>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="edit_kode_client" class="form-label fw-medium text-dark">Kode Client</label>
                                <input type="text" name="kode_client" id="edit_kode_client" class="form-control" placeholder="Contoh: CL-001">
                            </div>

                            <div class="col-md-6">
                                <label for="edit_tagihan" class="form-label fw-medium text-dark">Tagihan per Bulan</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light fw-bold text-secondary">Rp</span>
                                    <input type="number" name="tagihan" id="edit_tagihan" class="form-control" value="0" min="0">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="edit_status_pembayaran" class="form-label fw-medium text-dark">Status Pembayaran</label>
                                <select name="status_pembayaran" id="edit_status_pembayaran" class="form-select">
                                    <option value="0">Belum Lunas</option>
                                    <option value="1">Lunas</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="edit_bulan" class="form-label fw-medium text-dark">Bulan Tagihan</label>
                                <select name="bulan" id="edit_bulan" class="form-select">
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">
                                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-2">
                        <button type="button" class="btn btn-light text-secondary px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning text-white px-4" id="updateClientBtn">
                            <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
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

    // Open Edit Modal and populate with client data
    function openEditModal(client) {
        // Set form action URL
        const form = document.getElementById('editClientForm');
        form.action = `/clients/${client.id}`;
        
        // Populate form fields
        document.getElementById('edit_nama_client').value = client.nama_client || '';
        document.getElementById('edit_perusahaan').value = client.perusahaan || '';
        document.getElementById('edit_no_telepon').value = client.no_telepon || '';
        document.getElementById('edit_jabatan').value = client.jabatan || '';
        document.getElementById('edit_alamat').value = client.alamat || '';
        document.getElementById('edit_kode_client').value = client.kode_client || '';
        document.getElementById('edit_tagihan').value = client.tagihan || 0;
        document.getElementById('edit_status_pembayaran').value = client.status_pembayaran || 0;
        document.getElementById('edit_bulan').value = client.bulan || '01';
        
        // Open modal
        const modal = new bootstrap.Modal(document.getElementById('editClientModal'));
        modal.show();
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
