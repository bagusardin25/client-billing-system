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
        $clients = Client::latest()->paginate(10);
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
}
