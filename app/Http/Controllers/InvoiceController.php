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

        // Check availability (Prevent Duplicate)
        $exists = Invoice::where('id_client', $validated['id_client'])
            ->where('bulan', $validated['bulan'])
            ->where('tahun', $validated['tahun'])
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Invoice untuk client ini pada bulan dan tahun tersebut sudah ada.');
        }

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

    /**
     * Mark invoice as paid and optionally send receipt via WhatsApp.
     */
    public function markAsPaid(Request $request, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validate([
            'tanggal_pembayaran' => 'required|date',
            'send_whatsapp' => 'nullable',
        ]);

        // Update invoice status to paid
        $invoice->update([
            'status_pembayaran' => 1,
            'tanggal_pembayaran' => $validated['tanggal_pembayaran'],
        ]);

        // Also update client's payment status so public invoice page reflects the change
        if ($invoice->client) {
            $invoice->client->update([
                'status_pembayaran' => 1,
                'bulan' => DateTime::createFromFormat('!m', $invoice->bulan)->format('F') . ' ' . $invoice->tahun,
            ]);
        }

        // Send WhatsApp receipt if checkbox is checked
        if ($request->has('send_whatsapp')) {
            return $this->sendReceiptWhatsApp($invoice);
        }

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice berhasil ditandai lunas.');
    }

    /**
     * Send receipt via WhatsApp with public invoice link.
     */
    private function sendReceiptWhatsApp(Invoice $invoice): RedirectResponse
    {
        $client = $invoice->client;

        if (!$client || empty($client->no_telepon)) {
            return redirect()->route('invoices.index')
                ->with('warning', 'Invoice berhasil dilunasi, tetapi client tidak memiliki nomor telepon.');
        }

        $whatsapp = new \App\Services\WhatsAppService();

        if (!$whatsapp->isConfigured()) {
            return redirect()->route('invoices.index')
                ->with('warning', 'Invoice berhasil dilunasi, tetapi FONNTE_TOKEN belum dikonfigurasi.');
        }

        $message = $this->generateReceiptMessage($invoice);
        $result = $whatsapp->sendMessage($client->no_telepon, $message);

        // Log the message
        \App\Models\WhatsAppLog::create([
            'client_id' => $client->id,
            'invoice_id' => $invoice->id,
            'phone' => $client->no_telepon,
            'message' => $message,
            'status' => ($result['status'] ?? false) ? 'sent' : 'failed',
            'response' => $result,
        ]);

        if ($result['status'] ?? false) {
            return redirect()->route('invoices.index')
                ->with('success', "Invoice berhasil dilunasi dan kwitansi dikirim ke {$client->nama_client} via WhatsApp.");
        }

        return redirect()->route('invoices.index')
            ->with('warning', 'Invoice berhasil dilunasi, tetapi gagal mengirim kwitansi: ' . ($result['reason'] ?? 'Unknown error'));
    }

    /**
     * Generate receipt message for WhatsApp with public invoice link.
     */
    private function generateReceiptMessage(Invoice $invoice): string
    {
        $client = $invoice->client;
        $nama = $client->nama_client ?? 'Pelanggan';
        $perusahaan = $client->perusahaan ?? 'Personal';
        $bulanFormatted = DateTime::createFromFormat('!m', $invoice->bulan)->format('F');
        $periode = $bulanFormatted . ' ' . $invoice->tahun;
        $tagihan = 'Rp ' . number_format($invoice->tagihan, 0, ',', '.');
        $tanggal = $invoice->tanggal_pembayaran;
        
        // Generate public invoice URL using invoice code
        $publicUrl = route('public.invoice.view', ['kodeInvoice' => $invoice->kode_invoive]);

        return "Halo *{$nama}* ({$perusahaan}),\n\n"
            . "Terima kasih atas pembayaran Anda! 🙏\n\n"
            . "✅ *PEMBAYARAN LUNAS*\n\n"
            . "📋 *No. Invoice:* {$invoice->kode_invoive}\n"
            . "📅 *Periode:* {$periode}\n"
            . "💰 *Jumlah:* {$tagihan}\n"
            . "🗓️ *Tanggal Bayar:* {$tanggal}\n\n"
            . "Lihat kwitansi lengkap di:\n{$publicUrl}\n\n"
            . "Terima kasih atas kerjasamanya.\n"
            . "— *PyramidSoft*";
    }

    /**
     * Send single invoice via WhatsApp API.
     */
    public function sendInvoiceWhatsApp(Invoice $invoice): RedirectResponse
    {
        $client = $invoice->client;

        if (!$client || empty($client->no_telepon)) {
            return redirect()->route('invoices.index')
                ->with('error', 'Client tidak memiliki nomor telepon.');
        }

        $whatsapp = new \App\Services\WhatsAppService();

        // Check if service is configured
        if (!$whatsapp->isConfigured()) {
            return redirect()->route('invoices.index')
                ->with('error', 'FONNTE_TOKEN belum dikonfigurasi. Silakan set di file .env');
        }

        $message = $this->generateInvoiceMessage($invoice);
        $result = $whatsapp->sendMessage($client->no_telepon, $message);

        // Log the message
        \App\Models\WhatsAppLog::create([
            'client_id' => $client->id,
            'invoice_id' => $invoice->id,
            'phone' => $client->no_telepon,
            'message' => $message,
            'status' => ($result['status'] ?? false) ? 'sent' : 'failed',
            'response' => $result,
        ]);

        if ($result['status'] ?? false) {
            return redirect()->route('invoices.index')
                ->with('success', "Invoice berhasil dikirim ke {$client->nama_client} via WhatsApp.");
        }

        return redirect()->route('invoices.index')
            ->with('error', 'Gagal mengirim invoice: ' . ($result['reason'] ?? 'Unknown error'));
    }

    /**
     * Send all unpaid invoices via WhatsApp API (Queued).
     */
    public function sendBulkInvoiceWhatsApp(Request $request): RedirectResponse
    {
        $whatsapp = new \App\Services\WhatsAppService();

        // Check if service is configured
        if (!$whatsapp->isConfigured()) {
            return redirect()->route('invoices.index')
                ->with('error', 'FONNTE_TOKEN belum dikonfigurasi. Silakan set di file .env');
        }

        // Get unpaid invoices with clients that have phone numbers
        $invoices = Invoice::with('client')
            ->where('status_pembayaran', 0)
            ->whereHas('client', function ($query) {
                $query->whereNotNull('no_telepon')
                    ->where('no_telepon', '!=', '');
            })
            ->get();

        if ($invoices->isEmpty()) {
            return redirect()->route('invoices.index')
                ->with('warning', 'Tidak ada invoice belum lunas dengan client yang memiliki nomor telepon.');
        }

        $queuedCount = 0;

        foreach ($invoices as $index => $invoice) {
            $client = $invoice->client;
            $message = $this->generateInvoiceMessage($invoice);
            
            // Dispatch Job to Queue
            // Add delay to spread out the messages (e.g. 5 seconds apart)
            \App\Jobs\SendWhatsAppJob::dispatch($client->no_telepon, $message)
                ->delay(now()->addSeconds($index * 5));
                
            $queuedCount++;
        }

        return redirect()->route('invoices.index')
            ->with('success', "Proses pengiriman invoice ke {$queuedCount} client sedang berjalan di background.");
    }

    /**
     * Generate invoice message for WhatsApp.
     */
    private function generateInvoiceMessage(Invoice $invoice): string
    {
        $client = $invoice->client;
        $nama = $client->nama_client ?? 'Pelanggan';
        $perusahaan = $client->perusahaan ?? 'Personal';
        $periode = $invoice->bulan . ' ' . $invoice->tahun;
        $tagihan = 'Rp ' . number_format($invoice->tagihan, 0, ',', '.');
        $kode = $invoice->kode_invoive;

        return "Halo *{$nama}* ({$perusahaan}),\n\n"
            . "Berikut adalah invoice Anda:\n\n"
            . "📋 *No. Invoice:* {$kode}\n"
            . "📅 *Periode:* {$periode}\n"
            . "💰 *Total Tagihan:* {$tagihan}\n\n"
            . "Mohon segera lakukan pembayaran.\n\n"
            . "Terima kasih atas kerjasamanya.\n"
            . "— *PyramidSoft*";
    }
}
