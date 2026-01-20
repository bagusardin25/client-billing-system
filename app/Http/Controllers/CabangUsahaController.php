<?php

namespace App\Http\Controllers;

use App\Models\CabangUsaha;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CabangUsahaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $cabangUsaha = CabangUsaha::with('client')->latest()->paginate(10);
        return view('Admin.cabang-usaha.index', compact('cabangUsaha'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $clients = Client::orderBy('nama_client')->get();
        return view('Admin.cabang-usaha.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_client' => 'nullable|exists:clients,id',
            'nama_perusahaan' => 'required|string|max:255',
            'website' => 'nullable|string|max:255',
            'no_telepon' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
        ]);

        CabangUsaha::create($validated);

        return redirect()->route('cabang-usaha.index')
            ->with('success', 'Cabang usaha berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CabangUsaha $cabangUsaha): View
    {
        $cabangUsaha->load('client');
        return view('Admin.cabang-usaha.show', compact('cabangUsaha'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CabangUsaha $cabangUsaha): View
    {
        $clients = Client::orderBy('nama_client')->get();
        return view('Admin.cabang-usaha.edit', compact('cabangUsaha', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CabangUsaha $cabangUsaha): RedirectResponse
    {
        $validated = $request->validate([
            'id_client' => 'nullable|exists:clients,id',
            'nama_perusahaan' => 'required|string|max:255',
            'website' => 'nullable|string|max:255',
            'no_telepon' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
        ]);

        $cabangUsaha->update($validated);

        return redirect()->route('cabang-usaha.index')
            ->with('success', 'Cabang usaha berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CabangUsaha $cabangUsaha): RedirectResponse
    {
        $cabangUsaha->delete();

        return redirect()->route('cabang-usaha.index')
            ->with('success', 'Cabang usaha berhasil dihapus.');
    }
}
