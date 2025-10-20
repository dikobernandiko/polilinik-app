<x-layouts.app title="Data Dokter">
    {{-- SECTION: Content Header --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Dokter</h1>
                </div></div></div></div>
    {{-- END SECTION: Content Header --}}

    {{-- SECTION: Main Content --}}
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">

                    {{-- Alert Flash Message --}}
                    @if (session('message'))
                    <div class="alert alert-{{ session('type', 'success') }} alert-dismissible fade show" role="alert" id="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('dokters.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Tambah Dokter
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Nama Dokter</th>
                                            <th>Email</th>
                                            <th>No. HP</th>
                                            <th>Poli</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($dokters as $dokter)
                                        <tr>
                                            <td>{{ $dokter->nama }}</td>
                                            <td>{{ $dokter->email }}</td>
                                            <td>{{ $dokter->no_hp }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $dokter->poli->nama_poli ?? 'Belum ada poli' }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('dokters.edit', $dokter->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('dokters.destroy', $dokter->id) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus Dokter ini?')">
                                                        <i class="fa fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td class="text-center" colspan="5">Belum ada Dokter.</td>
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
    {{-- END SECTION: Main Content --}}
</x-layouts.app>