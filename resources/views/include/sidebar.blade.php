{{-- sidebar.blade.php --}}
<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    @if (auth()->check())
                        @if (auth()->user()->role == 'admin')
                            <a href="{{ route('admin.dashboard') }}">
                                <img src="{{ asset('template/assets/images/logo/logo.svg') }}" alt="Logo">
                            </a>
                        @elseif(auth()->user()->role == 'pelajar')
                            <a href="{{ route('pelajar.dashboard') }}">
                                <img src="{{ asset('template/assets/images/logo/logo.svg') }}" alt="Logo">
                            </a>
                        @else
                            <a href="{{ url('/') }}">
                                <img src="{{ asset('template/assets/images/logo/logo.svg') }}" alt="Logo">
                            </a>
                        @endif
                    @else
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('template/assets/images/logo/logo.svg') }}" alt="Logo">
                        </a>
                    @endif
                </div>
                <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                    {{-- Toggle Theme --}}
                    <svg class="iconify iconify--system-uicons" width="20" height="20">
                        <use xlink:href="#moon"></use>
                    </svg>
                    <div class="form-check form-switch fs-6">
                        <input class="form-check-input me-0" type="checkbox" id="toggle-dark">
                        <label class="form-check-label"></label>
                    </div>
                    <svg class="iconify iconify--system-uicons" width="20" height="20">
                        <use xlink:href="#sun"></use>
                    </svg>
                </div>
                <div class="sidebar-toggler x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                @auth
                    @if (auth()->user()->role == 'admin')
                        {{-- Admin Dashboard --}}
                        <li class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
                                <i class="bi bi-speedometer2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        {{-- Data Pelajar --}}
                        <li class="sidebar-item {{ request()->routeIs('admin.pelajar.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.pelajar.index') }}" class="sidebar-link">
                                <i class="bi bi-people"></i>
                                <span>Data Pelajar</span>
                            </a>
                        </li>

                        {{-- Pengajuan --}}
                        <li class="sidebar-item {{ request()->routeIs('admin.pengajuan.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.pengajuan.index') }}" class="sidebar-link">
                                <i class="bi bi-file-earmark-text"></i>
                                <span>Pengajuan Magang</span>
                            </a>
                        </li>

                        {{-- Kegiatan --}}
                        <li class="sidebar-item {{ request()->routeIs('admin.kegiatan.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.kegiatan.index') }}" class="sidebar-link">
                                <i class="bi bi-journal-text"></i>
                                <span>Kegiatan</span>
                            </a>
                        </li>

                        {{-- Absensi --}}
                        <li class="sidebar-item {{ request()->routeIs('admin.absensi.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.absensi.index') }}" class="sidebar-link">
                                <i class="bi bi-calendar-check"></i>
                                <span>Absensi</span>
                            </a>
                        </li>
                    @elseif(auth()->user()->role == 'pelajar')
                    
                        {{-- Pelajar Dashboard --}}
                        <li class="sidebar-item {{ request()->routeIs('pelajar.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('pelajar.dashboard') }}" class="sidebar-link">
                                <i class="bi bi-speedometer2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        {{-- Absensi --}}
                        <li class="sidebar-item {{ request()->routeIs('absensi.*') ? 'active' : '' }}">
                            <a href="{{ route('absensi.index') }}" class="sidebar-link">
                                <i class="bi bi-calendar-check"></i>
                                <span>Absensi</span>
                            </a>
                        </li>

                        {{-- Kegiatan --}}
                        <li class="sidebar-item has-sub {{ request()->routeIs('pelajar.kegiatan.*') ? 'active' : '' }}">
                            <a href="#" class="sidebar-link">
                                <i class="bi bi-journal-text"></i>
                                <span>Kegiatan</span>
                            </a>
                            <ul class="submenu">
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

                        {{-- Pelajar --}}
                        <li class="sidebar-item {{ request()->routeIs('absensi.*') ? 'active' : '' }}">
                            <a href="{{ route('absensi.index') }}" class="sidebar-link">
                                <i class="bi bi-person-lines-fill"></i>
                                <span>Pelajar</span>
                            </a>
                        </li>


                        {{-- Pengajuan Magang --}}
                        <li class="sidebar-item has-sub {{ request()->routeIs('pelajar.pengajuan.*') ? 'active' : '' }}">
                            <a href="#" class="sidebar-link">
                                <i class="bi bi-collection-fill"></i>
                                <span>Pengajuan Magang</span>
                            </a>
                            <ul class="submenu">
                                <li
                                    class="submenu-item {{ request()->routeIs('pelajar.pengajuan.index') ? 'active' : '' }}">
                                    <a href="{{ route('pelajar.pengajuan.index') }}">Daftar Pengajuan</a>
                                </li>
                                <li
                                    class="submenu-item {{ request()->routeIs('pelajar.pengajuan.create') ? 'active' : '' }}">
                                    <a href="{{ route('pelajar.pengajuan.create') }}">Ajukan Magang</a>
                                </li>
                            </ul>
                        </li>
                    @else
                        {{-- Menu untuk role lain atau tidak dikenal --}}
                        <li class="sidebar-item">
                            <a href="{{ url('/') }}" class="sidebar-link">
                                <i class="bi bi-house"></i>
                                <span>Home</span>
                            </a>
                        </li>
                    @endif
                @else
                    {{-- Menu untuk Guest --}}
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

                <li class="sidebar-title">Keluar</li>
                @auth
                    <li class="sidebar-item">
                        <form action="{{ route('logout') }}" method="POST" class="mb-0">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">Logout</button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</div>
