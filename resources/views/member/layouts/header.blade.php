<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        {{ $tanggal_sekarang }}


        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- Place this tag where you want the button to render. -->
            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ Auth::user()->foto === null || Auth::user()->foto == '' ? asset('images/anggota/pria.jpg') : url('/storage' . '/' . Auth::user()->foto) }}"
                            alt class="w-px-40 h-px-40 rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ Auth::user()->foto === null || Auth::user()->foto == '' ? asset('images/anggota/pria.jpg') : url('/storage' . '/' . Auth::user()->foto) }}"
                                            alt class="w-px-40 h-px-40 rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ Auth::user()->nama }}</span>
                                    <small class="text-muted">{{ Auth::user()->roles->first()->name }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profil.show') }}">
                            <i class='bx bx-user-circle me-2 mt-1 float-start'></i>
                            <span class="align-middle">Ganti Profil</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('password.index') }}">
                            <i class='bx bxs-key me-2 mt-1 float-start'></i>
                            <span class="align-middle">Ganti Password</span>
                        </a>
                    </li>
                    {{-- <li>
                        <a class="dropdown-item" href="{{ route('aktivitas') }}">
                            <span class="d-flex align-items-center align-middle">
                                <i class="flex-shrink-0 bx bx-bar-chart me-2"></i>
                                <span class="flex-grow-1 align-middle">Aktivitas</span>
                            </span>
                        </a>
                    </li> --}}
                    <li>
                        <a class="dropdown-item" href="{{ route('auth.keluar') }}" id="btn-logout">
                            <i class="bx bx-power-off me-2 mt-1 float-start"></i>
                            <span class="align-middle">Keluar</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>
