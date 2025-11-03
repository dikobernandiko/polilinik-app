<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalPeriksa;
use Illuminate\Support\Facades\Auth;

class JadwalPeriksaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dokter = Auth::user(); 

        $jadwalPeriksas = JadwalPeriksa::where('id_dokter', $dokter->id) 
                                        ->orderBy('hari')
                                        ->get();

        return view('dokter.jadwal-periksa.index', compact('jadwalPeriksas')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dokter.jadwal-periksa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([ 
            'hari' => 'required', 
            'jam_mulai' => 'required', 
            'jam_selesai' => 'required', 
        ]);

        // 2. Simpan data ke database
        JadwalPeriksa::create([ 
            'id_dokter' => Auth::id(), 
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai
        ]);

        // 3. Redirect ke halaman index dengan pesan sukses
        return redirect()->route('jadwal-periksa.index')
                        ->with('message', 'Data Berhasil di Simpan')
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
        // Temukan jadwal berdasarkan ID 
        $jadwalPeriksa = JadwalPeriksa::findOrFail($id);

        // Kirim data jadwal ke view 
        return view('dokter.jadwal-periksa.edit', compact('jadwalPeriksa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // 1. Validasi Input 
        $request->validate([
            'hari' => 'required', 
            'jam_mulai' => 'required', 
            'jam_selesai' => 'required', 
        ]);

        // 2. Temukan data dan update
        $jadwalPeriksa = JadwalPeriksa::findOrFail($id); 
        $jadwalPeriksa->update([ 
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai, 
            'jam_selesai' => $request->jam_selesai 
        ]);

        // 3. Redirect ke halaman index dengan pesan sukses
        return redirect()->route('jadwal-periksa.index')
                        ->with('message', 'Berhasil Melakukan Update Data') 
                        ->with('type', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // 1. Temukan jadwal berdasarkan ID
        $jadwalPeriksa = JadwalPeriksa::findOrFail($id); 

        // 2. Hapus data dari database
        $jadwalPeriksa->delete(); 

        // 3. Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('jadwal-periksa.index')
                        ->with('message', 'Berhasil Melakukan Hapus Data')
                        ->with('type', 'success'); 
    }
}
