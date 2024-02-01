<ul class="nav nav-pills flex-column flex-md-row mb-3">
    <li class="nav-item">
        <a class="nav-link {{ menuAktifProfil('profil') }}" href="{{ route('profil.show') }}"><i class='bx bx-user-circle'></i> Ganti
            Profil</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ menuAktifProfil('password') }}" href="{{ route('password.index') }}"><i class='bx bxs-key'></i> Ganti Password</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ menuAktifProfil('aktivitas') }}" href=""><i class='bx bx-bar-chart'></i> Aktivitas</a>
    </li>
</ul>
