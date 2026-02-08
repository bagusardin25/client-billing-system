<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use App\Models\JenisBiaya;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PemasukanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Pemasukan::with('jenisBiaya')->latest();

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $totalPemasukan = $query->sum('nominal'); // Sum of filtered results (before pagination)
        $pemasukan = $query->paginate(10)->withQueryString();
        
        return view('Admin.pemasukan.index', compact('pemasukan', 'totalPemasukan'));
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

        $validated['nama_biaya1'] = $request->input('nama_biaya1', 'Pemasukan Manual');
        $validated['id_user'] = Auth::id();

        Pemasukan::create($validated);

        return redirect()->route('pemasukan.index')
            ->with('success', 'Pemasukan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pemasukan $pemasukan): View
    {
        $pemasukan->load(['jenisBiaya', 'user']);
        return view('Admin.pemasukan.show', compact('pemasukan'));
    }

    /**
     * Update the specified resource in storage.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pemasukan $pemasukan): RedirectResponse
    {
        $validated = $request->validate([
            'id_jenis_biaya' => 'required|exists:jenis_biaya,id',
            'keterangan' => 'required|string|max:225',
            'nominal' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
        ]);

        $validated['nama_biaya1'] = $request->input('nama_biaya1', 'Pemasukan Manual');

        $pemasukan->update($validated);

        return redirect()->route('pemasukan.index')
            ->with('success', 'Pemasukan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pemasukan $pemasukan): RedirectResponse
    {
        $pemasukan->delete();

        return redirect()->route('pemasukan.index')
            ->with('success', 'Pemasukan berhasil dihapus.');
    }
}
