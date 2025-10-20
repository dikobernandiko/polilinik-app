<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Poli;

class DokterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dokters = User::where('role', 'dokter')->with('poli')->get();
        return view('admin.dokter.index', compact('dokters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $polis = Poli::all(); 
        return view('admin.dokter.create', compact('polis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //1. membuat validasi
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_ktp' => 'required|string|max:16|unique:users,no_ktp',
            'no_hp' => 'required|string|max:15',
            'id_poli' => 'required|string|exists:poli,id', // intinya id nya ada di poli
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|min:6',
        ]);
        // dd($data);

        User::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_ktp' => $request->no_ktp,
            'no_hp' => $request->no_hp,
            'id_poli' => $request->id_poli,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'dokter',
        ]);

        return redirect()->route('dokter.index')
            ->with('message', 'Data Dokter Berhasil di tambahkan')
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
         $dokter = User::findOrFail($id);
         $polis = Poli::all();
         return view('admin.dokter.edit', compact('dokter', 'polis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Temukan dokter yang akan diupdate
        $dokter = User::findOrFail($id);

        // 1. Validasi data
        $request->validate([
        'nama' => 'required|string|max:255',
        'alamat' => 'required|string',
        'no_ktp' => 'required|string|max:16|unique:users,no_ktp,' . $dokter->id, // unik, tapi abaikan ID dokter ini
        'no_hp' => 'required|string|max:15',
        'id_poli' => 'required|string|exists:poli,id',
        'email' => 'required|string|email|max:255|unique:users,email,' . $dokter->id, // unik, tapi abaikan ID dokter ini
        'password' => 'nullable|string|min:6', // Boleh kosong
        ]);

        // 2. Siapkan data untuk diupdate
        $updateData = [
        'nama' => $request->nama,
        'alamat' => $request->alamat,
        'no_ktp' => $request->no_ktp,
        'no_hp' => $request->no_hp,
        'id_poli' => $request->id_poli,
        'email' => $request->email,
        ];

        // 3. Cek jika password diisi
        if ($request->filled('password')) {
        $updateData['password'] = Hash::make($request->password);
        }

        // 4. Update data di database
        $dokter->update($updateData);

        // 5. Redirect kembali ke halaman index
        return redirect()->route('dokters.index')
                     ->with('message', 'Data Dokter Berhasil diubah.')
                     ->with('type', 'success');
        }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dokter = User::findOrFail($id);

        $dokter->delete();

        return redirect()->route('dokters.index')
                     ->with('message', 'Data Dokter Berhasil dihapus.')
                     ->with('type', 'success');
    }
}
