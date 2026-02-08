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
    public function index(Request $request): View
    {
        $query = Pengeluaran::with('jenisBiaya')->latest();

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $totalPengeluaran = $query->sum('nominal'); // Sum of filtered results (before pagination)
        $pengeluaran = $query->paginate(10)->withQueryString();
        
        return view('Admin.pengeluaran.index', compact('pengeluaran', 'totalPengeluaran'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_jenis_biaya' => 'required|exists:jenis_biaya,id',
            'keterangan' => 'required|string|max:225',
            'nominal' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
        ]);

        $validated['nama_biaya1'] = $request->input('nama_biaya1', 'Pengeluaran Manual');
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
     * Update the specified resource in storage.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengeluaran $pengeluaran): RedirectResponse
    {
        $validated = $request->validate([
            'id_jenis_biaya' => 'required|exists:jenis_biaya,id',
            'keterangan' => 'required|string|max:225',
            'nominal' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
        ]);

        $validated['nama_biaya1'] = $request->input('nama_biaya1', 'Pengeluaran Manual');

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
