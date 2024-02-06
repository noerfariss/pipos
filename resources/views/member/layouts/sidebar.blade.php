<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('auth.index') }}" class="app-brand-link">
            <h2 class="app-brand-text demo menu-text fw-bolder ms-2">
                {!! $logo !!}
            </h2>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    {{-- <div class="menu-inner-shadow"></div> --}}

    <ul class="menu-inner py-1 mt-2">
        <!-- Dashboard -->
        <li class="menu-item {{ menuAktif('') }}">
            <a href="{{ route('auth.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-pie-chart"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>

        @can('KASIR_READ')
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Aktivitas</span></li>
            <li class="menu-item {{ menuAktif('kasir') }}">
                <a href="{{ route('kasir.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-money-withdraw"></i>
                    <div data-i18n="Analytics">Kasir </div>
                </a>
            </li>
        @endcan

        @canany(['PRODUK_READ', 'KATEGORI_READ', 'SUPLIER_READ', 'UNIT_READ'])
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Data Produk</span></li>
        @endcanany

        @can('PRODUK_READ')
            <li class="menu-item {{ menuAktif('produk') }}">
                <a href="{{ route('produk.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-data"></i>
                    <div data-i18n="Analytics">Produk</div>
                </a>
            </li>
        @endcan

        @can('KATEGORI_READ')
            <li class="menu-item {{ menuAktif('kategori') }}">
                <a href="{{ route('kategori.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-category-alt"></i>
                    <div data-i18n="Analytics">Kategori</div>
                </a>
            </li>
        @endcan

        @can('SUPLIER_READ')
            <li class="menu-item {{ menuAktif('suplier') }}">
                <a href="{{ route('suplier.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-package"></i>
                    <div data-i18n="Analytics">Suplier</div>
                </a>
            </li>
        @endcan

        @can('UNIT_READ')
            <li class="menu-item {{ menuAktif('unit') }}">
                <a href="{{ route('unit.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cable-car"></i>
                    <div data-i18n="Analytics">Unit</div>
                </a>
            </li>
        @endcan


        @canany(['TRANSAKSI_READ', 'STOKIN_READ', 'STOKOUT_READ'])
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Laporan</span></li>
        @endcanany

        @can('TRANSAKSI_READ')
            <li class="menu-item {{ menuAktif('transaksi') }}">
                <a href="{{ route('transaksi.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-math"></i>
                    <div data-i18n="Analytics">Transaksi</div>
                </a>
            </li>
        @endcan


        @can('STOKIN_READ')
            <li class="menu-item {{ menuAktif('stokin') }}">
                <a href="{{ route('stokin.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cart-add"></i>
                    <div data-i18n="Analytics">Stok Masuk</div>
                </a>
            </li>
        @endcan

        @can('STOKOUT_READ')
            <li class="menu-item {{ menuAktif('stokout') }}">
                <a href="{{ route('stokout.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-link-external"></i>
                    <div data-i18n="Analytics">Stok Keluar</div>
                </a>
            </li>
        @endcan



        @canany(['PENGATURAN_READ', 'USERWEB_READ', 'MEMBER_READ', 'ROLE_READ'])
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Pengaturan</span></li>
        @endcanany

        @can('MEMBER_READ')
            <li class="menu-item {{ menuAktif('member') }}">
                <a href="{{ route('member.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-user-account"></i>
                    <div data-i18n="Analytics">Member</div>
                </a>
            </li>
        @endcan

        @can('USERWEB_READ')
            <li class="menu-item {{ menuAktif('user') }}">
                <a href="{{ route('user.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div data-i18n="Analytics">User</div>
                </a>
            </li>
        @endcan

        @can('ROLE_READ')
            <li class="menu-item {{ menuAktif('role') }}">
                <a href="{{ route('role.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-outline"></i>
                    <div data-i18n="Analytics">Role</div>
                </a>
            </li>
        @endcan

        @can('PENGATURAN_READ')
            <li class="menu-item {{ menuAktif('pengaturan') }}">
                <a href="{{ route('pengaturan.show') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cog"></i>
                    <div data-i18n="Analytics">Pengaturan</div>
                </a>
            </li>
        @endcan

    </ul>
</aside>
