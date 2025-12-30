{{-- Menu Periksa Pasien --}}
<li class="nav-item">
    <a href="{{ route('periksa-pasien.index') }}" 
       class="nav-link {{ request()->routeIs('periksa-pasien.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-stethoscope"></i>
        <p>Periksa Pasien</p>
    </a>
</li>