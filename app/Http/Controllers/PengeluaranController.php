<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\JenisBiaya;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PengeluaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $pengeluaran = Pengeluaran::with('jenisBiaya')->latest()->paginate(10);
        $totalPengeluaran = Pengeluaran::sum('nominal');
        return view('Admin.pengeluaran.index', compact('pengeluaran', 'totalPengeluaran'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $jenisBiaya = JenisBiaya::orderBy('nama_biaya')->get();
        return view('Admin.pengeluaran.create', compact('jenisBiaya'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_jenis_biaya' => 'required|exists:jenis_biaya,id',
            'nama_biaya1' => 'required|string|max:50',
            'keterangan' => 'required|string|max:225',
            'nominal' => 'required|numeric|min:0',
            'tanggal' => 'required|string|max:50',
        ]);

        $validated['id_user'] = Auth::id();

        Pengeluaran::create($validated);

        return redirect()->route('pengeluaran.index')
            ->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengeluaran $pengeluaran): View
    {
        $pengeluaran->load(['jenisBiaya', 'user']);
        return view('Admin.pengeluaran.show', compact('pengeluaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengeluaran $pengeluaran): View
    {
        $jenisBiaya = JenisBiaya::orderBy('nama_biaya')->get();
        return view('Admin.pengeluaran.edit', compact('pengeluaran', 'jenisBiaya'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengeluaran $pengeluaran): RedirectResponse
    {
        $validated = $request->validate([
            'id_jenis_biaya' => 'required|exists:jenis_biaya,id',
            'nama_biaya1' => 'required|string|max:50',
            'keterangan' => 'required|string|max:225',
            'nominal' => 'required|numeric|min:0',
            'tanggal' => 'required|string|max:50',
        ]);

        $pengeluaran->update($validated);

        return redirect()->route('pengeluaran.index')
            ->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengeluaran $pengeluaran): RedirectResponse
    {
        $pengeluaran->delete();

        return redirect()->route('pengeluaran.index')
            ->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
