<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use Illuminate\View\View;
use DateTime;

class PublicInvoiceController extends Controller
{
    /**
     * Display public invoice page for client.
     */
    public function show(string $kodeClient): View
    {
        $client = Client::where('kode_client', $kodeClient)->firstOrFail();

        return view('public-invoice', compact('client'));
    }

    /**
     * Display public invoice/receipt page by invoice code.
     */
    public function showInvoice(string $kodeInvoice): View
    {
        $invoice = Invoice::where('kode_invoice', $kodeInvoice)->firstOrFail();
        $invoice->load('client');
        
        // Prepare data for the view (merge invoice data with client)
        $data = (object) [
            'nama_client' => $invoice->client->nama_client,
            'perusahaan' => $invoice->client->perusahaan,
            'kode_client' => $invoice->client->kode_client,
            'status_pembayaran' => $invoice->status_pembayaran,
            'tagihan' => $invoice->tagihan,
            'bulan' => DateTime::createFromFormat('!m', $invoice->bulan)->format('F') . ' ' . $invoice->tahun,
            'tanggal_pembayaran' => $invoice->tanggal_pembayaran,
            'kode_invoice' => $invoice->kode_invoice,
        ];

        return view('public-invoice', ['client' => $data]);
    }
}
