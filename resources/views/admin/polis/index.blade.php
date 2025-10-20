<x-layouts.app title="Data Polis">

    {{-- SECTION: Content Header --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Polis</h1>
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
                            <a href="{{ route('polis.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Tambah Poli
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Nama Poli</th>
                                            <th>Keterangan</th>
                                            <th style="width: 150px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($polis as $poli)
                                        <tr>
                                            <td>{{ $poli->nama_poli }}</td>
                                            <td>{{ $poli->keterangan }}</td>
                                            <td>
                                                <a href="{{ route('polis.edit', $poli->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('polis.destroy', $poli->id) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus Poli ini?')">
                                                        <i class="fa fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td class="text-center" colspan="3">Belum ada Poli.</td>
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

    @push('scripts')
    <script>
        setTimeout(() => {
            const alert = document.getElementById('alert');
            if (alert) {
                alert.remove();
            }
        }, 3000);
    </script>
    @endpush
</x-layouts.app>