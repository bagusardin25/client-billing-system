<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\View\View;

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
}
