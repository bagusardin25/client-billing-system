<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PyramidSoft - {{ $title ?? 'Dashboard' }}</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Round&display=swap" rel="stylesheet"/>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/admin-dark-mode.css') }}" rel="stylesheet">
    
    <style>
        :root {
            /* Theme Colors based on Reference Image */
            --bs-primary: #3498db; /* Light Blue for Header/Buttons */
            --bs-primary-dark: #2980b9;
            --bs-secondary: #64748b;
            --bs-success: #10b981; /* Emerald Green */
            --bs-info: #0ea5e9; /* Sky Blue */
            --bs-warning: #f59e0b; /* Amber */
            --bs-danger: #ef4444; /* Rose */
            
            --sidebar-bg: #1e293b; /* Dark Slate Sidebar */
            --sidebar-text: #cbd5e1;
            --sidebar-hover: #334155;
            --sidebar-active: #3498db;
            
            --header-bg: #ffffff;
            --header-text: #0f172a;
            
            --body-bg: #f8fafc; /* Slate 50 */
            
            --sidebar-width: 260px;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--body-bg);
            color: #334155;
        }
        
        /* Utilities Custom (Tailwind-like) */
        .text-xs { font-size: 0.75rem; }
        .text-sm { font-size: 0.875rem; }
        .tracking-wide { letter-spacing: 0.025em; }
        .tracking-widest { letter-spacing: 0.1em; }
        
        .hover-bg-light:hover { background-color: #f1f5f9 !important; }
        .hover-bg-warning:hover { background-color: #f59e0b !important; }
        .hover-text-white:hover { color: white !important; }
        .hover-bg-danger:hover { background-color: #ef4444 !important; }
        
        .focus-ring:focus { box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.15); outline: none; border-color: var(--bs-primary); }
        
        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background-color: var(--sidebar-bg);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-right: 1px solid #334155;
        }
        
        .sidebar-brand {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            height: 70px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        
        .sidebar-user-panel {
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            margin-top: 1rem;
        }
        
        .sidebar-nav {
            padding: 0 1rem;
        }
        
        .sidebar-nav .nav-link {
            color: #94a3b8;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            transition: all 0.2s;
            margin-bottom: 4px;
        }
        
        .sidebar-nav .nav-link:hover {
            color: white;
            background-color: var(--sidebar-hover);
        }
        
        .sidebar-nav .nav-link.active {
            color: white;
            background-color: var(--bs-primary);
        }
        
        .sidebar-nav .nav-link i {
            margin-right: 12px;
            font-size: 1.25rem;
            /* Material Icons alignment */
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .sidebar-heading {
            font-size: 0.65rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.1em;
            color: #64748b;
            padding: 1.5rem 1rem 0.5rem;
        }
        
        /* Main Content Styling */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s;
            display: flex;
            flex-direction: column;
        }
        
        .main-header {
            background-color: var(--header-bg);
            color: var(--header-text);
            padding: 0 1.5rem;
            height: 64px;
            display: flex;
            align-items: center;
            z-index: 900;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .content-wrapper {
            padding: 1.5rem 2rem;
            flex-grow: 1;
        }
        
        /* Mobile Responsive */
        @media (max-width: 991.98px) {
            .sidebar {
                margin-left: -100%;
            }
            .sidebar.show {
                margin-left: 0;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <x-admin.sidebar :active="$active ?? ''" />
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <x-admin.header :breadcrumbs="$breadcrumbs ?? []" />
        
        <!-- Page Content -->
        <div class="content-wrapper">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            {{ $slot }}
        </div>
        
        <!-- Footer -->
        <x-admin.footer />
    </div>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Mobile sidebar toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });
    </script>
    
    @stack('scripts')
</body>
</html>
