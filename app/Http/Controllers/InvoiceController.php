<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use DateTime;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Invoice::with('client')->latest();

        // Filter by Client Name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('client', function ($q) use ($search) {
                $q->where('nama_client', 'like', "%{$search}%")
                  ->orWhere('perusahaan', 'like', "%{$search}%");
            });
        }

        // Filter by Month
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }

        // Filter by Year
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $invoices = $query->paginate(10)->withQueryString();
        $clients = Client::orderBy('nama_client')->get();
        
        return view('Admin.invoice.index', compact('invoices', 'clients'));
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
        // Custom validation based on selection
        if ($request->id_client === 'all') {
            $request->validate([
                'id_client' => 'required',
                'bulan' => 'nullable|string|max:10',
                'tahun' => 'nullable|string|max:10',
                'tagihan' => 'nullable|numeric|min:0', // Optional fallback
                'status_pembayaran' => 'nullable|integer|in:0,1',
            ]);

            // Bulk Creation Logic
            $clients = Client::all();
            $count = 0;
            $fallbackAmount = $request->tagihan ?? 0;

            foreach ($clients as $client) {
                // Determine amount: Client specific > Fallback > 0
                $amount = $client->tagihan > 0 ? $client->tagihan : $fallbackAmount;

                // Check if invoice already exists for this period
                $exists = Invoice::where('id_client', $client->id)
                    ->where('bulan', $request->bulan)
                    ->where('tahun', $request->tahun)
                    ->exists();

                if (!$exists) {
                    Invoice::create([
                        'id_client' => $client->id,
                        'nama_client' => $client->nama_client,
                        'kode_invoive' => 'INV-' . date('Ymd') . '-' . mt_rand(1000, 9999), // Simple unique code
                        'bulan' => $request->bulan,
                        'tahun' => $request->tahun,
                        'tagihan' => $amount,
                        'status_pembayaran' => $request->status_pembayaran ?? 0,
                    ]);
                    $count++;
                }
            }

            return redirect()->route('invoices.index')
                ->with('success', "Berhasil membuat $count invoice baru secara otomatis.");
        }

        // Single Client Logic (Existing)
        $validated = $request->validate([
            'id_client' => 'required|exists:clients,id',
            'kode_invoive' => 'nullable|string|max:255',
            'bulan' => 'nullable|string|max:10',
            'tahun' => 'nullable|string|max:10',
            'tagihan' => 'nullable|numeric|min:0',
            'tanggal_pembayaran' => 'nullable|string|max:255',
            'status_pembayaran' => 'nullable|integer|in:0,1',
        ]);

        // Get client data
        $client = Client::find($validated['id_client']);
        $validated['nama_client'] = $client->nama_client;
        $validated['status_pembayaran'] = $validated['status_pembayaran'] ?? 0;
        
        // Use client's default bill if tagihan is not provided
        if (!isset($validated['tagihan'])) {
            $validated['tagihan'] = $client->tagihan ?? 0;
        }

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
