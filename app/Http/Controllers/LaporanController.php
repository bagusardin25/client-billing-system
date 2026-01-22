<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Laporan Pemasukan (Invoices + Manual Income)
     */
    public function pemasukan(Request $request): View
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // 1. Paid Invoices
        $invoices = Invoice::with('client')
            ->where('status_pembayaran', 1)
            ->whereBetween('tanggal_pembayaran', [$startDate, $endDate])
            ->latest('tanggal_pembayaran')
            ->get();

        // 2. Manual Income
        $manualIncomes = Pemasukan::with('jenisBiaya')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->latest('tanggal')
            ->get();

        $totalInvoice = $invoices->sum('tagihan');
        $totalManual = $manualIncomes->sum('nominal');
        $grandTotal = $totalInvoice + $totalManual;

        return view('Admin.laporan.pemasukan', compact(
            'invoices', 
            'manualIncomes', 
            'startDate', 
            'endDate', 
            'totalInvoice', 
            'totalManual', 
            'grandTotal'
        ));
    }

    /**
     * Laporan Pengeluaran
     */
    public function pengeluaran(Request $request): View
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $expenses = Pengeluaran::with('jenisBiaya')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->latest('tanggal')
            ->get();

        $totalExpense = $expenses->sum('nominal');

        return view('Admin.laporan.pengeluaran', compact(
            'expenses', 
            'startDate', 
            'endDate', 
            'totalExpense'
        ));
    }

    /**
     * Laporan Laba & Rugi
     */
    public function labaRugi(Request $request): View
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Income
        $invoiceIncome = Invoice::where('status_pembayaran', 1)
            ->whereBetween('tanggal_pembayaran', [$startDate, $endDate])
            ->sum('tagihan');
            
        $manualIncome = Pemasukan::whereBetween('tanggal', [$startDate, $endDate])
            ->sum('nominal');
            
        $totalIncome = $invoiceIncome + $manualIncome;

        // Expense
        $totalExpense = Pengeluaran::whereBetween('tanggal', [$startDate, $endDate])
            ->sum('nominal');

        $netProfit = $totalIncome - $totalExpense;

        return view('Admin.laporan.laba_rugi', compact(
            'startDate',
            'endDate',
            'invoiceIncome',
            'manualIncome',
            'totalIncome',
            'totalExpense',
            'netProfit'
        ));
    }
}
