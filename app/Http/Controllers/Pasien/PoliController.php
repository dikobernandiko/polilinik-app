<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Poli;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PoliController extends Controller
{
    /**
     * Menampilkan halaman form pendaftaran poli.
     */
    public function get()
    {
        // 1. Ambil data pasien yang sedang login
        $user = Auth::user();

        // 2. Ambil semua data poli
        $polis = Poli::all();

        // 3. Ambil semua jadwal periksa, 
        //    sekaligus data dokter dan poli terkait dokter tersebut (Eager Loading)
        $jadwals = JadwalPeriksa::with('dokter', 'dokter.poli')->get();

        // 4. Kirim semua data ke view 'pasien.daftar'
        return view('pasien.daftar', [
            'user' => $user,
            'polis' => $polis,
            'jadwals' => $jadwals,
        ]);
    }

    /**
     * Memproses data pendaftaran poli dari pasien.
     */
    public function submit(Request $request)
    {
        // 1. Validasi data input dari form
        $request->validate([
            'id_poli' => 'required|exists:poli,id',
            'id_jadwal' => 'required|exists:jadwal_periksa,id',
            'keluhan' => 'nullable|string',
            'id_pasien' => 'required|exists:users,id',
        ]);

        // 2. Hitung nomor antrian
        // Cari jumlah pasien yang sudah mendaftar di jadwal yang sama
        $jumlahSudahDaftar = DaftarPoli::where('id_jadwal', $request->id_jadwal)->count();
        $nomorAntrian = $jumlahSudahDaftar + 1; // Nomor antrian adalah jumlah + 1

        // 3. Simpan data pendaftaran ke database
        DaftarPoli::create([
            'id_pasien' => $request->id_pasien, // ID pasien dari form (hidden input)
            'id_jadwal' => $request->id_jadwal,
            'keluhan' => $request->keluhan,
            'no_antrian' => $nomorAntrian,
        ]);

        // 4. Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()
                         ->with('message', 'Berhasil Mendaftar ke Poli.')
                         ->with('type', 'success');
    }
}