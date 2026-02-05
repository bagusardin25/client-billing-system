<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Client::with(['invoices', 'cabangUsaha'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_client', 'like', "%{$search}%")
                    ->orWhere('perusahaan', 'like', "%{$search}%")
                    ->orWhere('no_telepon', 'like', "%{$search}%")
                    ->orWhere('kode_client', 'like', "%{$search}%");
            });
        }

        $clients = $query->paginate(10)->withQueryString();
        return view('Admin.ClientManagement.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('Admin.ClientManagement.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_client' => 'required|string|max:255',
            'perusahaan' => 'nullable|string|max:255',
            'no_telepon' => 'nullable|string|max:50',
            'tagihan' => 'nullable|numeric|min:0',
            'kode_client' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'bulan' => 'nullable|string|max:255',
            'status_pembayaran' => 'nullable|integer|in:0,1',
            'jenis_layanan' => 'nullable|string|in:mitra,server',
        ]);

        $validated['status_pembayaran'] = $validated['status_pembayaran'] ?? 0;
        $validated['tagihan'] = $validated['tagihan'] ?? 0;

        Client::create($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client): View
    {
        $client->load(['invoices', 'cabangUsaha']);
        return view('Admin.ClientManagement.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client): View
    {
        return view('Admin.ClientManagement.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client): RedirectResponse
    {
        $validated = $request->validate([
            'nama_client' => 'required|string|max:255',
            'perusahaan' => 'nullable|string|max:255',
            'no_telepon' => 'nullable|string|max:50',
            'tagihan' => 'nullable|numeric|min:0',
            'kode_client' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'bulan' => 'nullable|string|max:255',
            'status_pembayaran' => 'nullable|integer|in:0,1',
            'jenis_layanan' => 'nullable|string|in:mitra,server',
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client berhasil dihapus.');
    }

    /**
     * Send WhatsApp reminder to client.
     */
    public function sendWhatsAppReminder(Client $client)
    {
        // Validate phone number exists
        if (empty($client->no_telepon)) {
            return redirect()->route('clients.index')
                ->with('error', 'Nomor telepon client tidak tersedia.');
        }

        // Format phone number (ensure it starts with 62)
        $phone = $this->formatPhoneNumber($client->no_telepon);

        // Generate message
        $message = $this->generateReminderMessage($client);

        // Build WhatsApp URL
        $whatsappUrl = 'https://wa.me/' . $phone . '?text=' . urlencode($message);

        return redirect()->away($whatsappUrl);
    }

    /**
     * Format phone number to international format (62xxx).
     */
    private function formatPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Convert leading 0 to 62
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // Add 62 if not present
        if (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }

    /**
     * Generate reminder message for WhatsApp.
     */
    private function generateReminderMessage(Client $client): string
    {
        $nama = $client->nama_client;
        $perusahaan = $client->perusahaan ?? 'Personal';
        
        // Find the latest unpaid invoice for this client
        $invoice = Invoice::where('id_client', $client->id)
            ->where('status_pembayaran', 0)
            ->latest()
            ->first();
        
        // Use invoice data if available, otherwise fall back to client data
        if ($invoice) {
            $bulanFormatted = \DateTime::createFromFormat('!m', $invoice->bulan)->format('F');
            $bulan = $bulanFormatted . ' ' . $invoice->tahun;
            $tagihan = 'Rp. ' . number_format($invoice->tagihan ?? 0, 0, ',', '.');
            $invoiceUrl = route('public.invoice.view', ['kodeInvoice' => $invoice->kode_invoive]);
        } else {
            $bulan = $client->bulan ?? now()->translatedFormat('F Y');
            $tagihan = 'Rp. ' . number_format($client->tagihan ?? 0, 0, ',', '.');
            $invoiceUrl = route('public.invoice', $client->kode_client);
        }
        
        // Customize message based on service type
        $jenisLayanan = $client->jenis_layanan ?? 'server';
        $layananText = $jenisLayanan === 'mitra' 
            ? 'tagihan bermitra dengan Pyramidsoft' 
            : 'tagihan maintenance/server';

        return "Bismillah...\n\n"
            . "Haloo {$perusahaan},\n\n"
            . "Izin mengingatkan, untuk {$layananText}\n"
            . "Nama Usaha/Instansi: {$nama}/{$perusahaan}\n"
            . "Bulan: {$bulan}\n"
            . "Biaya: {$tagihan}\n\n"
            . "doa terbaik untuk kebahagiaan {$perusahaan} :)\n\n"
            . "⚠ PERHATIAN : \n"
            . "REKENING BARU : BSI 7262 970 714 a.n Buceu Sandri Prihatun\n\n"
            . "Best Regards,\n"
            . "Pyramidsoft & all team\n"
            . "Cek Nota Tagihan:\n"
            . $invoiceUrl;
    }

    /**
     * Send WhatsApp reminder via API (single client).
     */
    public function sendWhatsAppAPI(Client $client): RedirectResponse
    {
        // Validate phone number exists
        if (empty($client->no_telepon)) {
            return redirect()->route('clients.index')
                ->with('error', 'Nomor telepon client tidak tersedia.');
        }

        $whatsapp = new \App\Services\WhatsAppService();

        // Check if service is configured
        if (!$whatsapp->isConfigured()) {
            return redirect()->route('clients.index')
                ->with('error', 'FONNTE_TOKEN belum dikonfigurasi. Silakan set di file .env');
        }

        $message = $this->generateReminderMessage($client);
        $result = $whatsapp->sendMessage($client->no_telepon, $message);

        // Log the message
        \App\Models\WhatsAppLog::create([
            'client_id' => $client->id,
            'phone' => $client->no_telepon,
            'message' => $message,
            'status' => ($result['status'] ?? false) ? 'sent' : 'failed',
            'response' => $result,
        ]);

        if ($result['status'] ?? false) {
            return redirect()->route('clients.index')
                ->with('success', "Pesan berhasil dikirim ke {$client->nama_client} via WhatsApp API.");
        }

        return redirect()->route('clients.index')
            ->with('error', 'Gagal mengirim pesan: ' . ($result['reason'] ?? 'Unknown error'));
    }

    /**
     * Send WhatsApp reminders to all clients via API.
     */
    public function sendBulkWhatsApp(): RedirectResponse
    {
        $whatsapp = new \App\Services\WhatsAppService();

        // Check if service is configured
        if (!$whatsapp->isConfigured()) {
            return redirect()->route('clients.index')
                ->with('error', 'FONNTE_TOKEN belum dikonfigurasi. Silakan set di file .env');
        }

        $clients = Client::whereNotNull('no_telepon')
            ->where('no_telepon', '!=', '')
            ->get();

        if ($clients->isEmpty()) {
            return redirect()->route('clients.index')
                ->with('warning', 'Tidak ada client dengan nomor telepon yang valid.');
        }

        $successCount = 0;
        $failCount = 0;

        foreach ($clients as $client) {
            $message = $this->generateReminderMessage($client);
            $result = $whatsapp->sendMessage($client->no_telepon, $message);

            // Log the message
            \App\Models\WhatsAppLog::create([
                'client_id' => $client->id,
                'phone' => $client->no_telepon,
                'message' => $message,
                'status' => ($result['status'] ?? false) ? 'sent' : 'failed',
                'response' => $result,
            ]);

            if ($result['status'] ?? false) {
                $successCount++;
            } else {
                $failCount++;
            }
        }

        $totalClients = $clients->count();
        return redirect()->route('clients.index')
            ->with('success', "Selesai mengirim pesan ke {$totalClients} client. Berhasil: {$successCount}, Gagal: {$failCount}");
    }
}
