<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PasienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pasiens = User::where('role', 'pasien')->get();
        return view('admin.pasien.index', compact('pasiens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pasien.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_ktp' => 'required|string|max:16|unique:users,no_ktp',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        // 2. Simpan data ke database
        User::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_ktp' => $request->no_ktp,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password
            'role' => 'pasien' // Set role otomatis
        ]);

        // 3. Redirect ke halaman index dengan pesan sukses
        return redirect()->route('pasien.index')
                        ->with('message', 'Data Pasien berhasil di Tambah.')
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
        $pasien = User::findOrFail($id);
        return view('admin.pasien.edit', compact('pasien'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Temukan pasien yang akan diupdate
        $pasien = User::findOrFail($id);

        // 1. Validasi data
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_ktp' => 'required|string|max:16|unique:users,no_ktp,' . $pasien->id, // unik, tapi abaikan ID pasien ini
            'no_hp' => 'required|string|max:15',
            'email' => 'required|string|email|max:255|unique:users,email,' . $pasien->id, // unik, tapi abaikan ID pasien ini
            'password' => 'nullable|string|min:6', // Boleh kosong
        ]);

        // 2. Siapkan data untuk diupdate
        $updateData = [
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_ktp' => $request->no_ktp,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
        ];

        // 3. Cek jika password diisi
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        // 4. Update data di database
        $pasien->update($updateData);

        // 5. Redirect kembali ke halaman index
        return redirect()->route('pasien.index')
                        ->with('message', 'Data Pasien Berhasil di Update.')
                        ->with('type', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // 1. Find the patient by ID
        $pasien = User::findOrFail($id);

        // 2. Delete the data from the database [cite: 121]
        $pasien->delete();

        // 3. Redirect back to the index page with a success message [cite: 122, 123]
        return redirect()->route('pasien.index')
                        ->with('message', 'Data Pasien Berhasil Di Hapus.')
                        ->with('type', 'success');
    }
}
