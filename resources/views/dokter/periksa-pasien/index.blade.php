<x-layouts.app title="Daftar Periksa Pasien">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Daftar Periksa Pasien</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    
                    {{-- Alert Message --}}
                    @if (session('message'))
                    <div class="alert alert-{{ session('type') }} alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Antrian Pasien</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>No. Urut</th>
                                            <th>Nama Pasien</th>
                                            <th>Keluhan</th>
                                            <th>No. Antrian</th>
                                            <th style="width: 150px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($daftarPasien as $index => $dp)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $dp->pasien->nama }}</td>
                                                <td>{{ $dp->keluhan }}</td>
                                                <td>{{ $dp->no_antrian }}</td>
                                                <td>
                                                    {{-- Cek apakah sudah diperiksa --}}
                                                    @if ($dp->periksas->isNotEmpty())
                                                        <span class="badge bg-success">Sudah Diperiksa</span>
                                                    @else
                                                        <a href="{{ route('periksa-pasien.create', $dp->id) }}" 
                                                           class="btn btn-sm btn-primary">
                                                            <i class="fas fa-stethoscope"></i> Periksa
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Tidak ada antrian pasien saat ini.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>