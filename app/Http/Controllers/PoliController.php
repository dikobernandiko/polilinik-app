<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Poli;

class PoliController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $polis = Poli::all();
        return view('admin.polis.index', compact('polis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.polis.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
        'nama_poli' => 'required|string|max:255',
        'keterangan' => 'nullable|string',
        ]);

        // 2. Simpan data ke database
        Poli::create($validated);

        // 3. Redirect ke halaman index dengan pesan sukses
        return redirect()->route('polis.index')->with('message', 'Poli berhasil ditambahkan.')->with('type', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $poli = Poli::findOrFail($id); // Cari poli berdasarkan ID, jika tidak ada akan error 404
        return view('admin.polis.edit', compact('poli'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // 1. Validasi Input
        $validated = $request->validate([
        'nama_poli' => 'required|string|max:255',
        'keterangan' => 'nullable|string',
        ]);

        // 2. Cari data dan update
        $poli = Poli::findOrFail($id);
        $poli->update($validated);

        // 3. Redirect ke halaman index dengan pesan sukses
        return redirect()->route('polis.index')->with('message', 'Poli berhasil diupdate.')->with('type', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // 1. Cari data poli berdasarkan ID
        $poli = Poli::findOrFail($id);

        // 2. Hapus data dari database
        $poli->delete();

        // 3. Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('polis.index')->with('message', 'Poli berhasil dihapus.')->with('type', 'success');
    }
}
