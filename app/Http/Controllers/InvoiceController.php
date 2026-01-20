<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $invoices = Invoice::with('client')->latest()->paginate(10);
        return view('Admin.invoice.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $clients = Client::orderBy('nama_client')->get();
        return view('Admin.invoice.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_client' => 'required|exists:clients,id',
            'kode_invoive' => 'nullable|string|max:255',
            'bulan' => 'nullable|string|max:10',
            'tahun' => 'nullable|string|max:10',
            'tagihan' => 'nullable|numeric|min:0',
            'tanggal_pembayaran' => 'nullable|string|max:255',
            'status_pembayaran' => 'nullable|integer|in:0,1',
        ]);

        // Get client name
        $client = Client::find($validated['id_client']);
        $validated['nama_client'] = $client->nama_client;
        $validated['status_pembayaran'] = $validated['status_pembayaran'] ?? 0;
        $validated['tagihan'] = $validated['tagihan'] ?? 0;

        // Generate invoice code if not provided
        if (empty($validated['kode_invoive'])) {
            $validated['kode_invoive'] = 'INV-' . date('Ymd') . '-' . str_pad(Invoice::count() + 1, 4, '0', STR_PAD_LEFT);
        }

        Invoice::create($validated);

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice): View
    {
        $invoice->load('client');
        return view('Admin.invoice.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice): View
    {
        $clients = Client::orderBy('nama_client')->get();
        return view('Admin.invoice.edit', compact('invoice', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validate([
            'id_client' => 'required|exists:clients,id',
            'kode_invoive' => 'nullable|string|max:255',
            'bulan' => 'nullable|string|max:10',
            'tahun' => 'nullable|string|max:10',
            'tagihan' => 'nullable|numeric|min:0',
            'tanggal_pembayaran' => 'nullable|string|max:255',
            'status_pembayaran' => 'nullable|integer|in:0,1',
        ]);

        // Get client name
        $client = Client::find($validated['id_client']);
        $validated['nama_client'] = $client->nama_client;

        $invoice->update($validated);

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice berhasil dihapus.');
    }
}
