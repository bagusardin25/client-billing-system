<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                min-height: 100vh;
            }
            .hero-section {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .card {
                border: none;
                background: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(10px);
            }
        </style>
    </head>
    <body>
        <div class="container hero-section">
            <div class="row justify-content-center w-100">
                <div class="col-md-8 col-lg-6 text-center">
                    <div class="card shadow-lg rounded-4 p-5">
                        <div class="mb-4">
                            <i class="bi bi-briefcase-fill text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h1 class="fw-bold mb-3 display-5">{{ config('app.name', 'Client Billing') }}</h1>
                        <p class="text-secondary mb-5 lead">
                            Sistem Manajemen Klien & Tagihan Terintegrasi
                        </p>
                        
                        @if (Route::has('login'))
                            <div class="d-grid gap-3 col-sm-8 mx-auto">
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg rounded-3 fw-semibold">
                                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg rounded-3 fw-semibold">
                                        <i class="bi bi-box-arrow-in-right me-2"></i> Log in
                                    </a>

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg rounded-3 fw-semibold">
                                            <i class="bi bi-person-plus me-2"></i> Register
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                        
                        <div class="mt-5 text-muted small">
                            &copy; {{ date('Y') }} PyramidSoft. All rights reserved.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
