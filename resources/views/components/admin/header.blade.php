@props(['breadcrumbs' => []])

<header class="main-header d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
        <!-- Mobile Toggle -->
        <button class="btn btn-link text-dark d-lg-none me-2" id="sidebarToggle">
            <i class="bi bi-list fs-4"></i>
        </button>
        
        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('clients.index') }}" class="text-decoration-none">
                        <i class="bi bi-house"></i>
                    </a>
                </li>
                @foreach($breadcrumbs as $crumb)
                    @if(isset($crumb['url']))
                        <li class="breadcrumb-item">
                            <a href="{{ $crumb['url'] }}" class="text-decoration-none">{{ $crumb['label'] }}</a>
                        </li>
                    @else
                        <li class="breadcrumb-item active" aria-current="page">{{ $crumb['label'] }}</li>
                    @endif
                @endforeach
            </ol>
        </nav>
    </div>
    
    <div class="d-flex align-items-center gap-3">
        <!-- Dark Mode Toggle -->
        <button class="btn btn-link text-secondary" id="darkModeToggle" title="Toggle Dark Mode">
            <i class="bi bi-moon fs-5"></i>
        </button>

        <!-- Notifications -->
        <div class="dropdown">
            <button class="btn btn-link text-secondary position-relative" data-bs-toggle="dropdown">
                <i class="bi bi-bell fs-5"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <h6 class="dropdown-header">Notifikasi</h6>
                <a class="dropdown-item text-muted small" href="#">Belum ada notifikasi</a>
            </div>
        </div>
        
        <!-- User Dropdown -->
        <div class="dropdown">
            <button class="btn btn-link text-dark text-decoration-none d-flex align-items-center" data-bs-toggle="dropdown">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=3498db&color=fff&size=32" 
                     alt="Avatar" class="rounded-circle me-2" width="32" height="32">
                <span class="d-none d-sm-inline">{{ Auth::user()->name ?? 'Admin' }}</span>
                <i class="bi bi-chevron-down ms-1 small"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-left me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('darkModeToggle');
        const icon = toggle.querySelector('i');
        const html = document.documentElement;

        // Check local storage
        if (localStorage.getItem('theme') === 'dark') {
            html.setAttribute('data-bs-theme', 'dark');
            icon.classList.replace('bi-moon', 'bi-sun');
            icon.classList.add('text-warning');
        }

        toggle.addEventListener('click', () => {
            if (html.getAttribute('data-bs-theme') === 'dark') {
                html.setAttribute('data-bs-theme', 'light');
                localStorage.setItem('theme', 'light');
                icon.classList.replace('bi-sun', 'bi-moon');
                icon.classList.remove('text-warning');
            } else {
                html.setAttribute('data-bs-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                icon.classList.replace('bi-moon', 'bi-sun');
                icon.classList.add('text-warning');
            }
        });
    });
</script>
@endpush
