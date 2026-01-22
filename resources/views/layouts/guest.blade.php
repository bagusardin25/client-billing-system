<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
        
        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');
            
            :root {
                --bs-primary: #4361ee;
                --bs-primary-rgb: 67, 97, 238;
                --bs-body-font-family: 'Plus Jakarta Sans', sans-serif;
            }
            
            body {
                font-family: var(--bs-body-font-family);
                background-color: #f3f4f6;
                background-color: #f3f4f6;
                background-size: 100% 100%;
                background-attachment: fixed;
                color: #fff;
            }
            
            .auth-card {
                max-width: 420px;
                width: 100%;
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 20px;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
            }

            .form-floating > .form-control {
                border-radius: 12px;
                border: 1px solid #e5e7eb;
                background-color: #f9fafb;
            }

            .form-floating > .form-control:focus {
                border-color: var(--bs-primary);
                background-color: #fff;
                box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
            }
            
            .btn-primary {
                background: linear-gradient(135deg, #4361ee 0%, #3f37c9 100%);
                border: none;
                padding: 12px;
                border-radius: 12px;
                font-weight: 600;
                letter-spacing: 0.5px;
                transition: transform 0.2s;
            }
            
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 15px -3px rgba(67, 97, 238, 0.3);
            }

            .input-group-text {
                border-radius: 12px 0 0 12px;
                border: 1px solid #e5e7eb;
                background-color: #f9fafb;
            }
        </style>
    </head>
    <body class="d-flex align-items-center min-vh-100 py-4">
        <div class="container d-flex justify-content-center">
            {{ $slot }}
        </div>
        
        <!-- Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
