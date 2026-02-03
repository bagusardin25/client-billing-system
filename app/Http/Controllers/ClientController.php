<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $clients = Client::with(['invoices', 'cabangUsaha'])->latest()->paginate(10);
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
        $bulan = $client->bulan ?? now()->translatedFormat('F Y');
        $tagihan = 'Rp ' . number_format($client->tagihan ?? 0, 0, ',', '.');

        return "Halo *{$nama}* ({$perusahaan}),\n\n"
            . "Kami ingin mengingatkan mengenai tagihan Anda untuk periode *{$bulan}*.\n\n"
            . "💰 *Total Tagihan:* {$tagihan}\n\n"
            . "Mohon segera lakukan pembayaran untuk menghindari keterlambatan.\n\n"
            . "Terima kasih atas kerjasamanya.\n"
            . "— *PyramidSoft*";
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
