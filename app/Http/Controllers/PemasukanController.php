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
    public function index(): View
    {
        $pemasukan = Pemasukan::with('jenisBiaya')->latest()->paginate(10);
        $totalPemasukan = Pemasukan::sum('nominal');
        return view('Admin.pemasukan.index', compact('pemasukan', 'totalPemasukan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $jenisBiaya = JenisBiaya::orderBy('nama_biaya')->get();
        return view('Admin.pemasukan.create', compact('jenisBiaya'));
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
     * Show the form for editing the specified resource.
     */
    public function edit(Pemasukan $pemasukan): View
    {
        $jenisBiaya = JenisBiaya::orderBy('nama_biaya')->get();
        return view('Admin.pemasukan.edit', compact('pemasukan', 'jenisBiaya'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pemasukan $pemasukan): RedirectResponse
    {
        $validated = $request->validate([
            'id_jenis_biaya' => 'required|exists:jenis_biaya,id',
            'nama_biaya1' => 'required|string|max:50',
            'keterangan' => 'required|string|max:225',
            'nominal' => 'required|numeric|min:0',
            'tanggal' => 'required|string|max:50',
        ]);

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
