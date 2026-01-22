@props(['active' => ''])

<nav class="sidebar d-flex flex-column text-secondary">
    <!-- Brand -->
    <div class="sidebar-brand">
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center justify-content-center rounded-3 bg-primary" style="width: 40px; height: 40px;">
                <i class="material-icons-round text-white fs-4">layers</i>
            </div>
            <span class="fs-5 fw-bold text-white tracking-tight">PYRAMID<span class="text-primary">SOFT</span></span>
        </div>
    </div>
    
    <!-- User Profile Panel -->
    <div class="sidebar-user-panel">
        <div class="d-flex align-items-center gap-3">
            <img src="https://ui-avatars.com/api/?name=Owner&background=e74c3c&color=fff" 
                 alt="Admin Profile" class="rounded-circle border border-2 border-primary border-opacity-25" style="width: 40px; height: 40px;">
            <div>
                <p class="mb-0 text-sm fw-bold text-white">Owner</p>
                <p class="mb-0 text-xs text-secondary opacity-75">Owner Account</p>
            </div>
        </div>
    </div>
    
    <!-- Navigation -->
    <div class="flex-grow-1 overflow-auto sidebar-nav custom-scrollbar">
        <!-- Menu Pembayaran -->
        <p class="text-xs fw-bold text-secondary text-uppercase mb-2 px-3 mt-3 tracking-widest opacity-50">Menu Pembayaran</p>
        
        <ul class="nav flex-column mb-4">
            <li class="nav-item">
                <a href="{{ route('clients.index') }}" class="nav-link {{ $active === 'clients' ? 'active' : '' }}">
                    <i class="material-icons-round">groups</i> <span>Clients</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('invoices.index') }}" class="nav-link {{ $active === 'invoices' ? 'active' : '' }}">
                    <i class="material-icons-round">receipt_long</i> <span>Invoice</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link {{ $active === 'users' ? 'active' : '' }}">
                    <i class="material-icons-round">person_outline</i> <span>User</span>
                </a>
            </li>
        </ul>

        <!-- Menu Keuangan -->
        <p class="text-xs fw-bold text-secondary text-uppercase mb-2 px-3 tracking-widest opacity-50">Menu Keuangan</p>
        
        <ul class="nav flex-column mb-4">
            <li class="nav-item">
                <a href="{{ route('jenis-biaya.index') }}" class="nav-link {{ $active === 'jenis-biaya' ? 'active' : '' }}">
                    <i class="material-icons-round">account_balance_wallet</i> <span>Jenis Biaya</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('pemasukan.index') }}" class="nav-link {{ $active === 'pemasukan' ? 'active' : '' }}">
                    <i class="material-icons-round">trending_up</i> <span>Pemasukan</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('pengeluaran.index') }}" class="nav-link {{ $active === 'pengeluaran' ? 'active' : '' }}">
                    <i class="material-icons-round">trending_down</i> <span>Pengeluaran</span>
                </a>
            </li>
        </ul>

        <!-- Laporan -->
        <p class="text-xs fw-bold text-secondary text-uppercase mb-2 px-3 tracking-widest opacity-50">Laporan</p>
        
        <ul class="nav flex-column mb-4">
            <li class="nav-item">
                <a href="{{ route('laporan.pemasukan') }}" class="nav-link {{ $active === 'laporan.pemasukan' ? 'active' : '' }}">
                    <i class="material-icons-round">description</i> <span>Laporan Pemasukan</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('laporan.pengeluaran') }}" class="nav-link {{ $active === 'laporan.pengeluaran' ? 'active' : '' }}">
                    <i class="material-icons-round">request_quote</i> <span>Laporan Pengeluaran</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('laporan.laba-rugi') }}" class="nav-link {{ $active === 'laporan.laba-rugi' ? 'active' : '' }}">
                    <i class="material-icons-round">pie_chart</i> <span>Laba & Rugi</span>
                </a>
            </li>
        </ul>
    </div>
    
    <!-- Logout -->
    <div class="px-3 pb-3 pt-2 border-top border-secondary border-opacity-10">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link text-danger w-100 text-start border-0 bg-transparent px-0 py-2 d-flex align-items-center hover-text-white transition-colors">
                <i class="material-icons-round me-2">logout</i> <span class="fw-medium">Logout</span>
            </button>
        </form>
    </div>
</nav>
