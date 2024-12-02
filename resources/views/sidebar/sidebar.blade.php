<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <div class="sidebar-left">
                    <a href="{{ route('home') }}" class="logo" style="font-size: 24px; color: black; font-weight: bold;">
                        <img src="{{ URL::to('/assets/images/logo.png') }}"
                            style="width: 70%;display: inline-block; font-weight: 900 !important" loading="lazy"
                            class="logo-text">
                    </a>
                </div>
                <li class="@if (Route::is('home') || Route::is('tampilan-semua-notifikasi')) active @endif">
                    <a href="{{ route('home') }}">
                        <i class="fa-solid fa-building-columns"></i>
                        <span style="font-weight: 900">Home</span>
                    </a>
                </li>
                @can('admin')
                    <li class="menu-title"> <span style="font-weight: 900">System Management</span> </li>
                    <li class="@if (Route::is('manajemen-pengguna') || Route::is('showProfile')) active @endif">
                        <a href="{{ route('manajemen-pengguna') }}">
                            <i class="fa-solid fa-person"></i>
                            <span style="font-weight: 900">List User</span>
                        </a>
                    </li>
                    <li class="{{ set_active(['riwayat/aktivitas']) }} active-riwayat-aktivitas">
                        <a href="{{ route('riwayat-aktivitas') }}">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            <span style="font-weight: 900">History Activity</span>
                        </a>
                    </li>
                    <li class="{{ set_active(['riwayat/otentikasi']) }} active-riwayat-aktivitas">
                        <a href="{{ route('riwayat-aktivitas-otentikasi') }}">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            <span style="font-weight: 900">History Otentikasi</span>
                        </a>
                    </li>
                @endcan

                <li class="menu-title"> <span style="font-weight: 900">Unit Barang</span> </li>
                <li class="{{ set_active(['data/satuan']) }} active-data-satuan">
                    <a href="{{ route('data-satuan') }}">
                        <i class="la la-map"></i>
                        <span style="font-weight: 900">Data Satuan</span>
                    </a>
                </li>
                
                <li class="menu-title"> <span style="font-weight: 900">Setting</span> </li>
                <li class="@if (Route::is('profile') || Route::is('rubah-kata-sandi')) active @endif">
                    <a href="{{ route('profile') }}">
                        <i class="la la-user"></i>
                        <span style="font-weight: 900"> Profile</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->