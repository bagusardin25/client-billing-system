<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with statistics.
     */
    public function index(): View
    {
        $stats = [
            'total_clients' => Client::count(),
            'total_invoices' => Invoice::count(),
            'invoices_lunas' => Invoice::where('status_pembayaran', 1)->count(),
            'invoices_belum_lunas' => Invoice::where('status_pembayaran', 0)->count(),
            'total_tagihan' => Invoice::sum('tagihan'),
            'tagihan_lunas' => Invoice::where('status_pembayaran', 1)->sum('tagihan'),
            'tagihan_belum_lunas' => Invoice::where('status_pembayaran', 0)->sum('tagihan'),
            'total_pemasukan' => Pemasukan::sum('nominal'),
            'total_pengeluaran' => Pengeluaran::sum('nominal'),
        ];

        $stats['saldo'] = $stats['total_pemasukan'] - $stats['total_pengeluaran'];

        // Recent data
        $recentClients = Client::latest()->take(5)->get();
        $recentInvoices = Invoice::with('client')->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'recentClients', 'recentInvoices'));
    }
}
