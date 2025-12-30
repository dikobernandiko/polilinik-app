<x-layouts.app title="Pendaftaran Poli">
    {{-- Header Konten --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pendaftaran Layanan Poli</h1>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <section class="content">
        <div class="container-fluid">
            {{-- Alert flash message --}}
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    @if (session('message'))
                        <div class="alert alert-{{ session('type', 'success') }} alert-dismissible fade show" role="alert">
                            {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Form Daftar Poli --}}
                    <div class="card card-primary card-outline shadow">
                        <div class="card-header">
                            <h5 class="card-title m-0">Formulir Pendaftaran</h5>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('pasien.daftar.submit') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_pasien" value="{{ $user->id }}">

                                <div class="mb-3">
                                    <label for="no_rm" class="form-label">Nomor Rekam Medis</label>
                                    <input type="text" class="form-control bg-light" id="no_rm" name="no_rm"
                                        value="{{ $user->no_rm }}" disabled>
                                    <small class="text-muted italic">*Nomor RM terisi otomatis sesuai profil Anda.</small>
                                </div>

                                <div class="mb-3">
                                    <label for="selectPoli" class="form-label font-weight-bold">Pilih Poli</label>
                                    <select name="id_poli" id="selectPoli" class="form-control shadow-sm">
                                        <option value="">-- Pilih Poli --</option>
                                        @foreach ($polis as $poli)
                                            <option value="{{ $poli->id }}">{{ $poli->nama_poli }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="selectJadwal" class="form-label font-weight-bold">Pilih Jadwal Periksa</label>
                                    <select name="id_jadwal" id="selectJadwal" class="form-control shadow-sm">
                                        <option value="">-- Pilih Jadwal --</option>
                                        @foreach ($jadwals as $jadwal)
                                            <option value="{{ $jadwal->id }}"
                                                data-id-poli="{{ $jadwal->dokter->poli->id ?? '' }}">
                                                {{ $jadwal->hari }}, {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }} | {{ $jadwal->dokter->nama ?? '--' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="keluhan" class="form-label font-weight-bold">Keluhan Pasien</label>
                                    <textarea name="keluhan" id="keluhan" rows="4" class="form-control shadow-sm" placeholder="Contoh: Batuk berdahak sejak 3 hari lalu..."></textarea>
                                </div>

                                <div class="d-grid mt-4">
                                    <button type="submit" name="submit" class="btn btn-primary btn-block shadow">
                                        <i class="fas fa-paper-plane mr-2"></i> Daftar Sekarang
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>

{{-- Script JS --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectPoli = document.getElementById('selectPoli');
        const selectJadwal = document.getElementById('selectJadwal');

        selectPoli.addEventListener('change', function() {
            const poliId = this.value;
            Array.from(selectJadwal.options).forEach(option => {
                if (option.value === "") return;
                option.hidden = !(option.dataset.idPoli == poliId && poliId !== '');
            });
            selectJadwal.value = "";
        });

        selectJadwal.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const poliId = selected.dataset.idPoli;
            if (!selectPoli.value && poliId) {
                selectPoli.value = poliId;
                selectPoli.dispatchEvent(new Event('change'));
            }
        });
    });
</script>