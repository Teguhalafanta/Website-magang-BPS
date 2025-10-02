{{-- sidebar.blade.php --}}
<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">

        {{-- Sidebar Header --}}
        <div class="sidebar-header position-relative">
            <div class="logo text-center my-3">
                @php
                    $logoUrl = asset('assets/images/logo/logo.png');
                @endphp

                @if (auth()->check())
                    @if (auth()->user()->role == 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none d-block">
                            <img src="{{ $logoUrl }}" alt="Logo"
                                style="width:120px; height:auto; display:block; margin:0 auto;">
                            <div
                                style="font-size:20px; font-weight:bold; white-space:nowrap; margin-top:8px; text-align:center; font-family:'Poppins', sans-serif;">
                                <span style="color:#007bff;">Badan</span>
                                <span style="color:#28a745;">Pusat</span>
                                <span style="color:#fd7e14;">Statistik</span>
                            </div>
                        </a>
                    @elseif(auth()->user()->role == 'pelajar')
                        <a href="{{ route('pelajar.dashboard') }}" class="text-decoration-none d-block">
                            <img src="{{ $logoUrl }}" alt="Logo"
                                style="width:120px; height:auto; display:block; margin:0 auto;">
                            <div
                                style="font-size:20px; font-weight:bold; white-space:nowrap; margin-top:8px; text-align:center; font-family:'Poppins', sans-serif;">
                                <span style="color:#007bff;">Badan</span>
                                <span style="color:#28a745;">Pusat</span>
                                <span style="color:#fd7e14;">Statistik</span>
                            </div>
                        </a>
                    @else
                        <a href="{{ url('/') }}" class="text-decoration-none d-block">
                            <img src="{{ $logoUrl }}" alt="Logo"
                                style="width:120px; height:auto; display:block; margin:0 auto;">
                            <div
                                style="font-size:20px; font-weight:bold; white-space:nowrap; margin-top:8px; text-align:center; font-family:'Poppins', sans-serif;">
                                <span style="color:#007bff;">Badan</span>
                                <span style="color:#28a745;">Pusat</span>
                                <span style="color:#fd7e14;">Statistik</span>
                            </div>
                        </a>
                    @endif
                @else
                    <a href="{{ url('/') }}" class="text-decoration-none d-block">
                        <img src="{{ $logoUrl }}" alt="Logo"
                            style="width:120px; height:auto; display:block; margin:0 auto;">
                        <div
                            style="font-size:20px; font-weight:bold; white-space:nowrap; margin-top:8px; text-align:center; font-family:'Poppins', sans-serif;">
                            <span style="color:#007bff;">Badan</span>
                            <span style="color:#28a745;">Pusat</span>
                            <span style="color:#fd7e14;">Statistik</span>
                        </div>
                    </a>
                @endif
            </div>

            {{-- Tombol Close Sidebar --}}
            <div class="absolute top-4 right-4 z-50">
                <a href="#" class="sidebar-hide" id="sidebar-close"
                    style="position:absolute; top:15px; right:15px; font-size:28px; color:#0d6efd; cursor:pointer;">
                    <i class="bi bi-x"></i>
                </a>
            </div>
        </div>

        {{-- Sidebar Menu --}}
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                @auth
                    {{-- Role: Admin --}}
                    @if (auth()->user()->role == 'admin')
                        <li class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
                                <i class="bi bi-speedometer2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('admin.pengajuan.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.pengajuan.index') }}" class="sidebar-link">
                                <i class="bi bi-file-earmark-text"></i>
                                <span>Pengajuan Magang</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('admin.kegiatan.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.kegiatan.index') }}" class="sidebar-link">
                                <i class="bi bi-journal-text"></i>
                                <span>Kegiatan</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('admin.absensi.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.absensi.index') }}" class="sidebar-link">
                                <i class="bi bi-calendar-check"></i>
                                <span>Absensi</span>
                            </a>
                        </li>

                        {{-- Role: Pelajar --}}
                    @elseif(auth()->user()->role == 'pelajar')
                        <li class="sidebar-item {{ request()->routeIs('pelajar.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('pelajar.dashboard') }}" class="sidebar-link">
                                <i class="bi bi-speedometer2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('absensi.*') ? 'active' : '' }}">
                            <a href="{{ route('absensi.index') }}" class="sidebar-link">
                                <i class="bi bi-calendar-check"></i>
                                <span>Absensi</span>
                            </a>
                        </li>

                        <li class="sidebar-item has-sub {{ request()->routeIs('pelajar.kegiatan.*') ? 'active' : '' }}">
                            <a href="{{ route('pelajar.kegiatan.index') }}" class="sidebar-link">
                                <i class="bi bi-journal-text"></i>
                                <span>Kegiatan</span>
                            </a>
                            <ul class="submenu"
                                style="{{ request()->routeIs('pelajar.kegiatan.*') ? 'display:block;' : 'display:none;' }}">
                                <li
                                    class="submenu-item {{ request()->routeIs('pelajar.kegiatan.harian') ? 'active' : '' }}">
                                    <a href="{{ route('pelajar.kegiatan.harian') }}">Kegiatan Harian</a>
                                </li>
                                <li
                                    class="submenu-item {{ request()->routeIs('pelajar.kegiatan.bulanan') ? 'active' : '' }}">
                                    <a href="{{ route('pelajar.kegiatan.bulanan') }}">Kegiatan Bulanan</a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('pelajar.pengajuan.index') ? 'active' : '' }}">
                            <a href="{{ route('pelajar.pengajuan.index') }}" class="sidebar-link">
                                <i class="bi bi-file-earmark-text"></i>
                                <span>Daftar Pengajuan</span>
                            </a>
                        </li>

                        {{-- Role: Pembimbing --}}
                    @elseif(auth()->user()->role == 'pembimbing')
                        <li class="sidebar-item {{ request()->routeIs('pembimbing.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('pembimbing.dashboard') }}" class="sidebar-link">
                                <i class="bi bi-speedometer2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('absensi.*') ? 'active' : '' }}">
                            <a href="{{ route('absensi.index') }}" class="sidebar-link">
                                <i class="bi bi-calendar-check"></i>
                                <span>Absensi</span>
                            </a>
                        </li>

                        <li class="sidebar-item has-sub {{ request()->routeIs('pembimbing.kegiatan.*') ? 'active' : '' }}">
                            <a href="{{ route('pembimbing.kegiatan.index') }}" class="sidebar-link">
                                <i class="bi bi-journal-text"></i>
                                <span>Kegiatan</span>
                            </a>
                            <ul class="submenu"
                                style="{{ request()->routeIs('pembimbing.kegiatan.*') ? 'display:block;' : 'display:none;' }}">
                                <li
                                    class="submenu-item {{ request()->routeIs('pembimbing.kegiatan.harian') ? 'active' : '' }}">
                                    <a href="{{ route('pembimbing.kegiatan.harian') }}">Kegiatan Harian</a>
                                </li>
                                <li
                                    class="submenu-item {{ request()->routeIs('pembimbing.kegiatan.bulanan') ? 'active' : '' }}">
                                    <a href="{{ route('pembimbing.kegiatan.bulanan') }}">Kegiatan Bulanan</a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('pembimbing.pengajuan.index') ? 'active' : '' }}">
                            <a href="{{ route('pembimbing.pengajuan.index') }}" class="sidebar-link">
                                <i class="bi bi-file-earmark-text"></i>
                                <span>Daftar Pengajuan</span>
                            </a>
                        </li>

                        {{-- Role Lain --}}
                    @else
                        <li class="sidebar-item">
                            <a href="{{ url('/') }}" class="sidebar-link">
                                <i class="bi bi-house"></i>
                                <span>Home</span>
                            </a>
                        </li>
                    @endif
                @else
                    {{-- Guest --}}
                    <li class="sidebar-item">
                        <a href="{{ route('login') }}" class="sidebar-link">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <span>Login</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('register') }}" class="sidebar-link">
                            <i class="bi bi-person-plus"></i>
                            <span>Register</span>
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</div>
