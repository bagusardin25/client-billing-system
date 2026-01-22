<?php

namespace App\Http\Controllers;

use App\Models\JenisBiaya;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class JenisBiayaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $jenisBiaya = JenisBiaya::latest()->paginate(10);
        return view('Admin.jenis-biaya.index', compact('jenisBiaya'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_biaya' => 'required|string|max:100',
        ]);

        JenisBiaya::create($validated);

        return redirect()->route('jenis-biaya.index')
            ->with('success', 'Jenis biaya berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisBiaya $jenisBiaya): View
    {
        return view('Admin.jenis-biaya.show', compact('jenisBiaya'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JenisBiaya $jenisBiaya): RedirectResponse
    {
        $validated = $request->validate([
            'nama_biaya' => 'required|string|max:100',
        ]);

        $jenisBiaya->update($validated);

        return redirect()->route('jenis-biaya.index')
            ->with('success', 'Jenis biaya berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisBiaya $jenisBiaya): RedirectResponse
    {
        $jenisBiaya->delete();

        return redirect()->route('jenis-biaya.index')
            ->with('success', 'Jenis biaya berhasil dihapus.');
    }
}
