<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 

class PeriksaPasienController extends Controller
{
    // Menampilkan daftar pasien yang harus diperiksa
    public function index()
    {
        $dokterId = Auth::id();

        $daftarPasien = DaftarPoli::with(['pasien', 'jadwalPeriksa', 'periksas'])
            ->whereHas('jadwalPeriksa', function ($query) use ($dokterId) {
                $query->where('id_dokter', $dokterId);
            })
            ->orderBy('no_antrian')
            ->get();

        return view('dokter.periksa-pasien.index', compact('daftarPasien'));
    }

    // Menampilkan form periksa pasien
    public function create($id)
    {
        $obats = Obat::all();
        // Mengirim data obat dan ID daftar poli ke view
        return view('dokter.periksa-pasien.create', compact('obats', 'id'));
    }

    // Menyimpan hasil pemeriksaan dengan pengurangan stok otomatis
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'obat_json' => 'required', // Data obat dikirim dalam format JSON string
            'catatan' => 'nullable|string',
            'biaya_periksa' => 'required|integer',
            'id_daftar_poli' => 'required',
        ]);

        $obatIds = json_decode($request->obat_json, true);

        // Memulai Transaksi Database 
        DB::beginTransaction();

        try {
            // 2. Validasi Stok Terlebih Dahulu
            if ($obatIds) {
                foreach ($obatIds as $idObat) {
                    $obat = Obat::findOrFail($idObat);
                    
                    if ($obat->stok <= 0) {
                        // Jika ada satu obat habis, batalkan semua proses
                        DB::rollBack();
                        return back()->withInput()->with([
                            'message' => "Gagal! Stok obat {$obat->nama_obat} sudah habis.",
                            'type' => 'danger'
                        ]);
                    }
                }
            }

            // 3. Simpan data ke tabel 'periksa' 
            $periksa = Periksa::create([
                'id_daftar_poli' => $request->id_daftar_poli,
                'tgl_periksa' => now(),
                'catatan' => $request->catatan,
                'biaya_periksa' => $request->biaya_periksa + 150000, // Biaya obat + Jasa Dokter
            ]);

            // 4. Simpan detail obat & Kurangi Stok Otomatis 
            if ($obatIds) {
                foreach ($obatIds as $idObat) {
                    DetailPeriksa::create([
                        'id_periksa' => $periksa->id,
                        'id_obat' => $idObat,
                    ]);

                    // LOGIKA PENGURANGAN STOK REAL-TIME
                    $obat = Obat::find($idObat);
                    $obat->decrement('stok', 1); // Mengurangi kolom stok sebanyak 1
                }
            }

            DB::commit();

            return redirect()->route('periksa-pasien.index')
                             ->with('message', 'Data periksa berhasil disimpan dan stok obat diperbarui.')
                             ->with('type', 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with([
                'message' => "Terjadi kesalahan sistem: " . $e->getMessage(),
                'type' => 'danger'
            ]);
        }
    }
}