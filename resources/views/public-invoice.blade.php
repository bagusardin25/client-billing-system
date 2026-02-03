<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice# {{ $client->kode_client ?? 'INV' }} - Pyramidsoft</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .invoice-badge {
            position: fixed;
            top: 20px;
            right: 20px;
            font-size: 14px;
            color: #5a67d8;
            font-weight: 600;
        }

        .main-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            padding: 50px 40px;
            max-width: 550px;
            width: 100%;
            text-align: center;
        }

        .illustration {
            width: 200px;
            height: 180px;
            margin: 0 auto 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .illustration svg {
            width: 100%;
            height: 100%;
        }

        /* Lunas styles */
        .status-lunas .header-title {
            color: #5a67d8;
        }

        .status-lunas .status-badge {
            color: #22c55e;
        }

        /* Belum Lunas styles */
        .status-pending .header-title {
            color: #f59e0b;
        }

        .status-pending .status-badge {
            color: #ef4444;
        }

        .header-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .info-label {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .client-name {
            font-size: 16px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 5px;
        }

        .period {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 20px;
        }

        .period span {
            color: #5a67d8;
            font-weight: 500;
        }

        .status-badge {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 25px;
        }

        .amount-section {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .amount-label {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 8px;
        }

        .amount {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
        }

        .bank-info {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid #fbbf24;
        }

        .bank-info .warning-icon {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .bank-info .bank-title {
            font-size: 13px;
            font-weight: 600;
            color: #92400e;
            margin-bottom: 8px;
        }

        .bank-info .bank-detail {
            font-size: 15px;
            font-weight: 700;
            color: #78350f;
        }

        .message {
            font-size: 14px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .message strong {
            color: #334155;
        }

        .signature {
            font-size: 13px;
            color: #94a3b8;
            margin-bottom: 25px;
        }

        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.4);
        }

        .footer {
            text-align: center;
            padding: 20px;
            font-size: 13px;
            color: #94a3b8;
        }

        @media print {
            body {
                background: white;
            }
            .invoice-badge,
            .btn-print,
            .footer {
                display: none;
            }
            .card {
                box-shadow: none;
            }
        }

        @media (max-width: 480px) {
            .card {
                padding: 30px 20px;
            }
            .header-title {
                font-size: 22px;
            }
            .amount {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-badge">
        Invoice# <strong>{{ $client->kode_client ?? 'INV' }}</strong>
    </div>

    <div class="main-container">
        <div class="card {{ $client->status_pembayaran == 1 ? 'status-lunas' : 'status-pending' }}">
            <div class="illustration">
                @if($client->status_pembayaran == 1)
                <!-- Lunas Illustration -->
                <svg viewBox="0 0 200 180" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <ellipse cx="100" cy="165" rx="80" ry="12" fill="#e0e7ff"/>
                    <rect x="55" y="40" width="90" height="110" rx="8" fill="#c7d2fe" stroke="#6366f1" stroke-width="2"/>
                    <rect x="65" y="55" width="40" height="8" rx="2" fill="#a5b4fc"/>
                    <rect x="65" y="70" width="60" height="6" rx="2" fill="#c7d2fe"/>
                    <rect x="65" y="82" width="55" height="6" rx="2" fill="#c7d2fe"/>
                    <rect x="65" y="94" width="50" height="6" rx="2" fill="#c7d2fe"/>
                    <circle cx="145" cy="45" r="30" fill="#22c55e"/>
                    <path d="M132 45L142 55L160 37" stroke="white" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="40" cy="80" r="25" fill="#fde68a"/>
                    <circle cx="40" cy="80" r="18" fill="#fbbf24"/>
                    <text x="40" y="85" text-anchor="middle" fill="white" font-size="14" font-weight="bold">Rp</text>
                </svg>
                @else
                <!-- Pending Illustration -->
                <svg viewBox="0 0 200 180" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <ellipse cx="100" cy="165" rx="80" ry="12" fill="#fef3c7"/>
                    <rect x="55" y="40" width="90" height="110" rx="8" fill="#fef3c7" stroke="#f59e0b" stroke-width="2"/>
                    <rect x="65" y="55" width="40" height="8" rx="2" fill="#fde68a"/>
                    <rect x="65" y="70" width="60" height="6" rx="2" fill="#fef3c7"/>
                    <rect x="65" y="82" width="55" height="6" rx="2" fill="#fef3c7"/>
                    <rect x="65" y="94" width="50" height="6" rx="2" fill="#fef3c7"/>
                    <circle cx="145" cy="45" r="30" fill="#f59e0b"/>
                    <text x="145" y="52" text-anchor="middle" fill="white" font-size="28" font-weight="bold">!</text>
                    <circle cx="40" cy="80" r="25" fill="#fee2e2"/>
                    <circle cx="40" cy="80" r="18" fill="#fca5a5"/>
                    <text x="40" y="85" text-anchor="middle" fill="white" font-size="14" font-weight="bold">Rp</text>
                </svg>
                @endif
            </div>

            <h1 class="header-title">
                {{ $client->status_pembayaran == 1 ? 'Pembayaran Lunas' : 'Menunggu Pembayaran' }}
            </h1>

            <div class="info-label">TAGIHAN KEPADA</div>
            <div class="client-name">{{ $client->nama_client }} / {{ $client->perusahaan ?? 'Personal' }}</div>
            <div class="period">Bulan <span>{{ $client->bulan ?? now()->translatedFormat('F Y') }}</span></div>

            <div class="status-badge">
                "{{ $client->status_pembayaran == 1 ? 'TELAH LUNAS' : 'BELUM LUNAS' }}"
            </div>

            <div class="amount-section">
                <div class="amount-label">Total Tagihan</div>
                <div class="amount">Rp. {{ number_format($client->tagihan ?? 0, 0, ',', '.') }}</div>
            </div>

            @if($client->status_pembayaran == 0)
            <div class="bank-info">
                <div class="warning-icon">⚠️</div>
                <div class="bank-title">PERHATIAN - REKENING BARU</div>
                <div class="bank-detail">BSI 7262 970 714 a.n Buceu Sandri Prihatun</div>
            </div>
            @endif

            <p class="message">
                Terimakasih Atas Kerjasamanya doa terbaik untuk kebahagiaan <strong>{{ $client->perusahaan ?? $client->nama_client }}</strong> :)
            </p>

            <div class="signature">
                Best Regards,<br>
                Pyramidsoft & all team
            </div>

            <button class="btn-print" onclick="window.print()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                    <rect x="6" y="14" width="12" height="8"/>
                </svg>
                Cetak Invoice
            </button>
        </div>
    </div>

    <footer class="footer">
        Copyright © {{ date('Y') }} by Pyramidsoft
    </footer>
</body>
</html>
