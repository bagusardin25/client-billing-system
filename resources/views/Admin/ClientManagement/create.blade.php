<x-admin-layout 
    title="Create Client" 
    :active="'clients'"
    :breadcrumbs="[
        ['label' => 'Clients', 'url' => route('clients.index')],
        ['label' => 'New Client']
    ]"
>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="mb-4">
                <a href="{{ route('clients.index') }}" class="text-decoration-none text-secondary small fw-bold mb-2 d-inline-block">
                    <i class="bi bi-arrow-left me-1"></i> Back to List
                </a>
                <h1 class="h3 fw-bold text-dark">Add New Client</h1>
                <p class="text-secondary">Enter the client's business and contact details below.</p>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('clients.store') }}" method="POST">
                        @csrf
                        
                        <h6 class="text-uppercase text-secondary fw-bold small mb-4 tracking-wide">Client Information</h6>
                        
                        <div class="row g-4">
                            <!-- Basic Info -->
                            <div class="col-md-6">
                                <label for="nama_client" class="form-label fw-medium text-dark">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="nama_client" id="nama_client" class="form-control @error('nama_client') is-invalid @enderror" value="{{ old('nama_client') }}" placeholder="e.g. John Doe" required>
                                @error('nama_client') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="perusahaan" class="form-label fw-medium text-dark">Company Name</label>
                                <input type="text" name="perusahaan" id="perusahaan" class="form-control @error('perusahaan') is-invalid @enderror" value="{{ old('perusahaan') }}" placeholder="e.g. Acme Corp">
                                @error('perusahaan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Contact Info -->
                            <div class="col-md-6">
                                <label for="no_telepon" class="form-label fw-medium text-dark">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-secondary"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="no_telepon" id="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror" value="{{ old('no_telepon') }}" placeholder="62...">
                                </div>
                                @error('no_telepon') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="jabatan" class="form-label fw-medium text-dark">Job Title / Position</label>
                                <input type="text" name="jabatan" id="jabatan" class="form-control @error('jabatan') is-invalid @enderror" value="{{ old('jabatan') }}" placeholder="e.g. Manager">
                                @error('jabatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label for="alamat" class="form-label fw-medium text-dark">Address</label>
                                <textarea name="alamat" id="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror" placeholder="Full office address">{{ old('alamat') }}</textarea>
                                @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <hr class="my-5 border-secondary-subtle">

                        <h6 class="text-uppercase text-secondary fw-bold small mb-4 tracking-wide">Billing Details</h6>

                        <div class="row g-4">
                             <div class="col-md-6">
                                <label for="kode_client" class="form-label fw-medium text-dark">Client Code / ID</label>
                                <input type="text" name="kode_client" id="kode_client" class="form-control @error('kode_client') is-invalid @enderror" value="{{ old('kode_client') }}" placeholder="e.g. CL-001">
                                @error('kode_client') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="tagihan" class="form-label fw-medium text-dark">Monthly Fee (IDR)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light fw-bold text-secondary">Rp</span>
                                    <input type="number" name="tagihan" id="tagihan" class="form-control @error('tagihan') is-invalid @enderror" value="{{ old('tagihan', 0) }}" min="0">
                                </div>
                                @error('tagihan') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3 mt-5">
                            <a href="{{ route('clients.index') }}" class="btn btn-light text-secondary px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5">Create Client</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
