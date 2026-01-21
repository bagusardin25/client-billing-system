<x-admin-layout 
    title="Edit Client" 
    :active="'clients'"
    :breadcrumbs="[
        ['label' => 'Clients', 'url' => route('clients.index')],
        ['label' => $client->nama_client, 'url' => route('clients.show', $client)],
        ['label' => 'Edit']
    ]"
>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('clients.index') }}" class="text-decoration-none text-secondary small fw-bold mb-2 d-inline-block">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                    <h1 class="h3 fw-bold text-dark">Edit Client</h1>
                </div>
                <!-- Delete Action (optional place for it) -->
                <form action="{{ route('clients.destroy', $client) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-trash me-2"></i>Delete
                    </button>
                </form>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('clients.update', $client) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Client Info Section -->
                        <h6 class="text-uppercase text-secondary fw-bold small mb-4">Client Information</h6>
                        
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label for="nama_client" class="form-label fw-medium text-dark">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="nama_client" id="nama_client" class="form-control @error('nama_client') is-invalid @enderror" value="{{ old('nama_client', $client->nama_client) }}" required>
                                @error('nama_client') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="perusahaan" class="form-label fw-medium text-dark">Company Name</label>
                                <input type="text" name="perusahaan" id="perusahaan" class="form-control @error('perusahaan') is-invalid @enderror" value="{{ old('perusahaan', $client->perusahaan) }}">
                                @error('perusahaan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="no_telepon" class="form-label fw-medium text-dark">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-secondary"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="no_telepon" id="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror" value="{{ old('no_telepon', $client->no_telepon) }}">
                                </div>
                                @error('no_telepon') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="jabatan" class="form-label fw-medium text-dark">Job Title</label>
                                <input type="text" name="jabatan" id="jabatan" class="form-control @error('jabatan') is-invalid @enderror" value="{{ old('jabatan', $client->jabatan) }}">
                                @error('jabatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label for="alamat" class="form-label fw-medium text-dark">Address</label>
                                <textarea name="alamat" id="alamat" rows="2" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $client->alamat) }}</textarea>
                                @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <hr class="my-5 border-secondary-subtle">

                        <!-- Billing Details Section -->
                        <h6 class="text-uppercase text-secondary fw-bold small mb-4">Billing & Status Details</h6>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="kode_client" class="form-label fw-medium text-dark">Client Code</label>
                                <input type="text" name="kode_client" id="kode_client" class="form-control @error('kode_client') is-invalid @enderror" value="{{ old('kode_client', $client->kode_client) }}">
                                @error('kode_client') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="tagihan" class="form-label fw-medium text-dark">Monthly Fee (IDR)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light fw-bold text-secondary">Rp</span>
                                    <input type="number" name="tagihan" id="tagihan" class="form-control @error('tagihan') is-invalid @enderror" value="{{ old('tagihan', $client->tagihan) }}" min="0">
                                </div>
                                @error('tagihan') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="status_pembayaran" class="form-label fw-medium text-dark">Payment Status</label>
                                <select name="status_pembayaran" id="status_pembayaran" class="form-select @error('status_pembayaran') is-invalid @enderror">
                                    <option value="0" {{ old('status_pembayaran', $client->status_pembayaran) == 0 ? 'selected' : '' }}>Belum Lunas</option>
                                    <option value="1" {{ old('status_pembayaran', $client->status_pembayaran) == 1 ? 'selected' : '' }}>Lunas</option>
                                </select>
                                @error('status_pembayaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="bulan" class="form-label fw-medium text-dark">Billing Month</label>
                                <select name="bulan" id="bulan" class="form-select @error('bulan') is-invalid @enderror">
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" 
                                                {{ old('bulan', $client->bulan) == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                                @error('bulan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3 mt-5">
                            <a href="{{ route('clients.index') }}" class="btn btn-light text-secondary px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
