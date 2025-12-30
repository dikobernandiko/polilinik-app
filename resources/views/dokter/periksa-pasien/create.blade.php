<x-layouts.app title="Periksa Pasien">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Periksa Pasien</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="card card-primary card-outline shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title">Form Pemeriksaan</h3>
                        </div>
                        <div class="card-body">
                            
                            {{-- BLOK ERROR MESSAGE & VALIDASI STOK (Challenge b.1) --}}
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Gagal Menyimpan!</strong>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            {{-- Notifikasi Flash Message dari Controller --}}
                            @if (session('message'))
                                <div class="alert alert-{{ session('type') }} alert-dismissible fade show" role="alert">
                                    {{ session('message') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('periksa-pasien.store') }}" method="POST" id="form-pemeriksaan">
                                @csrf
                                <input type="hidden" name="id_daftar_poli" value="{{ $id }}">
                                
                                {{-- Bagian Pilih Obat dengan Indikator Stok (Challenge b.2) --}}
                                <div class="form-group mb-3">
                                    <label for="obat" class="form-label">Pilih Obat</label>
                                    <div class="input-group">
                                        <select id="select-obat" class="form-control">
                                            <option value="">-- Pilih Obat --</option>
                                            @foreach ($obats as $obat)
                                                @php
                                                    $stokMenipis = $obat->stok <= 5 && $obat->stok > 0;
                                                    $stokHabis = $obat->stok <= 0;
                                                @endphp
                                                <option value="{{ $obat->id }}" 
                                                        data-nama="{{ $obat->nama_obat }}" 
                                                        data-harga="{{ $obat->harga }}"
                                                        data-stok="{{ $obat->stok }}"
                                                        class="{{ $stokHabis ? 'text-danger' : ($stokMenipis ? 'text-warning' : '') }}"
                                                        {{ $stokHabis ? 'disabled' : '' }}>
                                                    {{ $obat->nama_obat }} 
                                                    - Rp {{ number_format($obat->harga, 0, ',', '.') }}
                                                    @if($stokHabis)
                                                        (STOK HABIS)
                                                    @elseif($stokMenipis)
                                                        (Stok Menipis: {{ $obat->stok }})
                                                    @else
                                                        (Tersedia: {{ $obat->stok }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-primary" id="btn-tambah-obat">
                                            <i class="fas fa-plus"></i> Tambah
                                        </button>
                                    </div>
                                    <small class="text-muted mt-1 d-block italic">Obat yang stoknya habis tidak dapat dipilih.</small>
                                </div>

                                {{-- Bagian Catatan Dokter --}}
                                <div class="form-group mb-3">
                                    <label for="catatan" class="form-label">Catatan Pemeriksaan</label>
                                    <textarea name="catatan" id="catatan" class="form-control" rows="3" placeholder="Diagnosa atau keterangan resep..." required>{{ old('catatan') }}</textarea>
                                </div>

                                {{-- List Obat Terpilih (Dinamis) --}}
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Daftar Resep Obat:</label>
                                    <ul id="list-obat-terpilih" class="list-group mb-3">
                                        {{-- Item obat akan muncul disini lewat JS --}}
                                    </ul>
                                    
                                    {{-- Input Hidden untuk dikirim ke Controller (Modul 13) --}}
                                    <input type="hidden" name="biaya_periksa" id="biaya_periksa" value="0">
                                    <input type="hidden" name="obat_json" id="obat_json">
                                    
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light border rounded shadow-sm">
                                        <div>
                                            <span class="text-muted d-block">Rincian: Biaya Obat + Jasa Dokter (Rp 150.000)</span>
                                            <span class="fw-bold">Total Biaya Akhir:</span>
                                        </div>
                                        <span id="total-harga-display" class="fw-bold text-primary h4 mb-0">Rp 150.000</span> 
                                    </div>
                                </div>

                                <div class="d-grid gap-2 mt-4">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-check-circle"></i> Simpan & Selesaikan Pemeriksaan
                                    </button>
                                    <a href="{{ route('periksa-pasien.index') }}" class="btn btn-outline-secondary">Kembali</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT LOGIC DENGAN VALIDASI STOK (Challenge b.1) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectObat = document.getElementById('select-obat');
            const btnTambahObat = document.getElementById('btn-tambah-obat');
            const listObat = document.getElementById('list-obat-terpilih');
            const inputBiaya = document.getElementById('biaya_periksa');
            const inputObatJson = document.getElementById('obat_json');
            const totalHargaDisplay = document.getElementById('total-harga-display');
            
            let daftarObat = [];
            const jasaDokter = 150000;

            // Event saat menekan tombol Tambah Obat
            btnTambahObat.addEventListener('click', function() {
                const selectedOption = selectObat.options[selectObat.selectedIndex];
                
                if (selectedOption.value === "") {
                    alert('Silakan pilih obat terlebih dahulu!');
                    return;
                }

                const id = selectedOption.value;
                const nama = selectedOption.dataset.nama;
                const harga = parseInt(selectedOption.dataset.harga);
                const stok = parseInt(selectedOption.dataset.stok);

                // Validasi Stok Habis (Challenge b.1)
                if (stok <= 0) {
                    alert('Maaf, stok obat ini sudah habis dan tidak bisa dipilih!');
                    return;
                }

                // Cek agar obat tidak ganda dalam satu resep
                if (daftarObat.some(obat => obat.id === id)) {
                    alert('Obat ini sudah ada dalam daftar resep!');
                    return;
                }

                // Tambahkan ke array local
                daftarObat.push({ id, nama, harga });
                
                renderObat();
                selectObat.value = ""; // Reset dropdown setelah memilih
            });

            // Fungsi Render List Obat & Hitung Biaya (Modul 13)
            window.renderObat = function() { 
                listObat.innerHTML = ''; 
                let totalHargaObat = 0;

                daftarObat.forEach((obat, index) => {
                    totalHargaObat += obat.harga;

                    const li = document.createElement('li');
                    li.className = 'list-group-item d-flex justify-content-between align-items-center animate__animated animate__fadeIn';
                    li.innerHTML = `
                        <div>
                            <span class="fw-bold">${obat.nama}</span>
                            <small class="text-muted d-block">Rp ${obat.harga.toLocaleString('id-ID')}</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusObat(${index})">
                            <i class="fas fa-times"></i> Hapus
                        </button>
                    `;
                    listObat.appendChild(li);
                });

                // Update Total Harga (Jasa Dokter 150k + Total Harga Obat)
                const grandTotal = totalHargaObat + jasaDokter;
                
                // Update value ke input hidden untuk dikirim ke Controller
                inputBiaya.value = totalHargaObat; 
                inputObatJson.value = JSON.stringify(daftarObat.map(o => o.id)); 
                
                // Tampilan Visual
                totalHargaDisplay.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
            };

            // Fungsi Hapus Obat dari daftar sementara
            window.hapusObat = function(index) {
                daftarObat.splice(index, 1);
                renderObat();
            };
        });
    </script>
</x-layouts.app>