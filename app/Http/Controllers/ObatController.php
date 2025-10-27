<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Obat;

class ObatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data dari tabel obat
        $obats = Obat::all(); 

        // Kirim data ke view
        return view('admin.obat.index', compact('obats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.obat.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required|string', 
            'kemasan' => 'required|string', 
            'harga' => 'required|integer', 
        ]);

        Obat::create([
            'nama_obat' => $request->nama_obat,
            'kemasan' => $request->kemasan,
            'harga' => $request->harga
        ]);

        return redirect()->route('obat.index')
                        ->with('message', 'Data Obat Berhasil dibuat.')
                        ->with('type', 'success');
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
        // Find the drug data based on the ID, throw 404 if not found
        $obat = Obat::findOrFail($id); 

        // Send the drug data to the edit view
        return view('admin.obat.edit', compact('obat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_obat' => 'required|string', // nama_obat must be filled and a string
            'kemasan' => 'nullable|string', // kemasan can be empty but must be a string
            'harga' => 'required|integer', // harga must be filled and an integer
        ]);

        $obat = Obat::findOrFail($id); 
            $obat->update([
                'nama_obat' => $request->nama_obat,
                'kemasan' => $request->kemasan,
                'harga' => $request->harga
            ]);

        return redirect()->route('obat.index')
                        ->with('message', 'Data Obat berhasil di edit.')
                        ->with('type', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $obat = Obat::findOrFail($id);

        $obat->delete(); 

        return redirect()->route('obat.index')
                        ->with('message', 'Data Obat berhasil di Hapus.')
                        ->with('type', 'success');
    }
}
