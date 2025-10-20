<x-layouts.app title="Tambah Poli Baru">
    {{-- SECTION: Content Header --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tambah Poli Baru</h1>
                </div></div></div></div>
    {{-- END SECTION: Content Header --}}

    {{-- SECTION: Main Content --}}
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('polis.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="nama_poli" class="form-label">Nama Poli</label>
                                    <input type="text" name="nama_poli" id="nama_poli" class="form-control @error('nama_poli') is-invalid @enderror" value="{{ old('nama_poli') }}" required>
                                    @error('nama_poli')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <textarea name="keterangan" id="keterangan" class="form-control">{{ old('keterangan') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('polis.index') }}" class="btn btn-secondary">Batal</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- END SECTION: Main Content --}}
</x-layouts.app>