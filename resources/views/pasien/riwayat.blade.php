<x-layouts.app title="Riwayat Periksa">
    <div class="container-fluid p-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h3 class="card-title mb-0">Riwayat Periksa Saya</h3>
            </div>
            
            <div class="card-body table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal Periksa</th>
                            <th>Poli</th>
                            <th>Dokter</th>
                            <th>Keluhan</th>
                            <th>Catatan Dokter</th>
                            <th style="width: 30%;">Obat & Biaya</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($data->periksa->tgl_periksa)->format('d-m-Y') }}
                            </td> 
                            <td>
                                {{ $data->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}
                            </td>
                            <td>
                                {{ $data->jadwalPeriksa->dokter->nama ?? '-' }}
                            </td>
                            <td>{{ $data->keluhan }}</td>
                            <td>{{ $data->periksa->catatan }}</td>
                            <td>
                                <ul class="pl-3 mb-2">
                                    @foreach($data->periksa->detailPeriksa as $detail)
                                        <li>
                                            {{ $detail->obat->nama_obat }}
                                            <span class="text-muted" style="font-size: 0.85em;">
                                                (Rp {{ number_format($detail->obat->harga, 0, ',', '.') }})
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                                
                                <div class="alert alert-light border mt-2 p-2">
                                    <strong>Total Bayar:</strong> <br>
                                    <span class="text-success font-weight-bold" style="font-size: 1.1em;">
                                        Rp {{ number_format($data->periksa->biaya_periksa, 0, ',', '.') }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                Belum ada riwayat pemeriksaan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>