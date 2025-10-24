{{-- sidebar.blade.php - MODIFIED VERSION --}}
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
                                <span style="color:#007DC3;">Badan Pusat Statistik</span>
                            </div>
                        </a>
                    @elseif(auth()->user()->role == 'pelajar')
                        @if (auth()->user()->isApproved())
                            <a href="{{ route('pelajar.dashboard') }}" class="text-decoration-none d-block">
                                <img src="{{ $logoUrl }}" alt="Logo"
                                    style="width:120px; height:auto; display:block; margin:0 auto;">
                                <div
                                    style="font-size:20px; font-weight:bold; white-space:nowrap; margin-top:8px; text-align:center; font-family:'Poppins', sans-serif;">
                                    <span style="color:#007DC3;">Badan Pusat Statistik</span>
                                </div>
                            </a>
                        @else
                            <a href="{{ route('pelajar.pengajuan.index') }}" class="text-decoration-none d-block">
                                <img src="{{ $logoUrl }}" alt="Logo"
                                    style="width:120px; height:auto; display:block; margin:0 auto;">
                                <div
                                    style="font-size:20px; font-weight:bold; white-space:nowrap; margin-top:8px; text-align:center; font-family:'Poppins', sans-serif;">
                                    <span style="color:#007DC3;">Badan Pusat Statistik</span>
                                </div>
                            </a>
                        @endif
                    @else
                        <a href="{{ url('/') }}" class="text-decoration-none d-block">
                            <img src="{{ $logoUrl }}" alt="Logo"
                                style="width:120px; height:auto; display:block; margin:0 auto;">
                            <div
                                style="font-size:20px; font-weight:bold; white-space:nowrap; margin-top:8px; text-align:center; font-family:'Poppins', sans-serif;">
                                <span style="color:#007DC3;">Badan Pusat Statistik</span>
                            </div>
                        </a>
                    @endif
                @else
                    <a href="{{ url('/') }}" class="text-decoration-none d-block">
                        <img src="{{ $logoUrl }}" alt="Logo"
                            style="width:120px; height:auto; display:block; margin:0 auto;">
                        <div
                            style="font-size:20px; font-weight:bold; white-space:nowrap; margin-top:8px; text-align:center; font-family:'Poppins', sans-serif;">
                            <span style="color:#007DC3;">Badan Pusat Statistik</span>
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

                        <li class="sidebar-item {{ request()->routeIs('admin.pengajuan.*') ? 'active' : '' }}">
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

                        <li class="sidebar-item {{ request()->routeIs('admin.presensi.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.presensi.index') }}" class="sidebar-link">
                                <i class="bi bi-calendar-check"></i>
                                <span>Presensi</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('admin.assignpembimbing.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.assignpembimbing.view') }}" class="sidebar-link">
                                <i class="bi bi-person-check"></i>
                                <span>Assign Pembimbing</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('pelajar.laporan.index') ? 'active' : '' }}">
                            <a href="{{ route('pelajar.laporan.index') }}" class="sidebar-link">
                                <i class="bi bi-file-earmark-text"></i> <!-- icon laporan -->
                                <span>Laporan Akhir</span>
                            </a>
                        </li>

                        {{-- FITUR BARU: Upload Sertifikat --}}
                        <li class="sidebar-item {{ request()->routeIs('admin.sertifikat.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.sertifikat.index') }}" class="sidebar-link">
                                <i class="bi bi-award-fill"></i>
                                <span>Upload Sertifikat Magang</span>
                            </a>
                        </li>

                        {{-- Role: Pelajar --}}
                    @elseif(auth()->user()->role == 'pelajar')
                        @if (auth()->user()->isApproved())
                            {{-- MENU UNTUK PELAJAR YANG SUDAH DISETUJUI --}}
                            <li class="sidebar-item {{ request()->routeIs('pelajar.dashboard') ? 'active' : '' }}">
                                <a href="{{ route('pelajar.dashboard') }}" class="sidebar-link">
                                    <i class="bi bi-speedometer2"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>

                            <li class="sidebar-item {{ request()->routeIs('pelajar.presensi.*') ? 'active' : '' }}">
                                <a href="{{ route('pelajar.presensi.index') }}" class="sidebar-link">
                                    <i class="bi bi-calendar-check"></i>
                                    <span>Presensi</span>
                                </a>
                            </li>

                            <li
                                class="sidebar-item has-sub {{ request()->routeIs('pelajar.kegiatan.*') ? 'active' : '' }}">
                                <a href="#" class="sidebar-link">
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

                            <li class="sidebar-item {{ request()->routeIs('pelajar.laporan.*') ? 'active' : '' }}">
                                <a href="{{ route('pelajar.laporan.index') }}" class="sidebar-link">
                                    <i class="bi bi-upload"></i>
                                    <span>Upload Laporan Akhir</span>
                                </a>
                            </li>

                            <li class="sidebar-item {{ request()->routeIs('pelajar.pengajuan.*') ? 'active' : '' }}">
                                <a href="{{ route('pelajar.pengajuan.index') }}" class="sidebar-link">
                                    <i class="bi bi-file-earmark-text"></i>
                                    <span>Daftar Pengajuan</span>
                                    <span class="badge bg-success ms-auto" style="font-size: 10px;">Disetujui</span>
                                </a>
                            </li>
                        @else
                            {{-- MENU UNTUK PELAJAR YANG BELUM DISETUJUI --}}
                            <li class="sidebar-item {{ request()->routeIs('pelajar.pengajuan.*') ? 'active' : '' }}">
                                <a href="{{ route('pelajar.pengajuan.index') }}" class="sidebar-link">
                                    <i class="bi bi-file-earmark-text"></i>
                                    <span>Daftar Pengajuan</span>
                                    @if (auth()->user()->pelajar)
                                        @if (auth()->user()->pelajar->status === 'menunggu')
                                            <span class="badge bg-warning text-dark ms-auto"
                                                style="font-size: 10px;">Diajukan</span>
                                        @elseif(auth()->user()->pelajar->status === 'ditolak')
                                            <span class="badge bg-danger ms-auto" style="font-size: 10px;">Ditolak</span>
                                        @else
                                            <span class="badge bg-secondary ms-auto" style="font-size: 10px;">Draft</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary ms-auto" style="font-size: 10px;">Belum</span>
                                    @endif
                                </a>
                            </li>

                            <li class="sidebar-item" style="pointer-events: none;">
                                <div class="p-3 mt-3"
                                    style="background: linear-gradient(135deg, #007DC3 0%, #004D7A 100%);
                           border-radius: 12px;
                           box-shadow: 0 4px 15px rgba(0, 125, 195, 0.4);">
                                    <div class="text-center mb-2">
                                        <i class="bi bi-lock-fill" style="font-size: 32px; color: #fff;"></i>
                                    </div>
                                    <p class="text-white text-center mb-1"
                                        style="font-size: 12px; font-weight: 600; line-height: 1.4;">
                                        Menu Terkunci
                                    </p>
                                    <p class="text-white text-center mb-0"
                                        style="font-size: 10px; line-height: 1.3; opacity: 0.9;">
                                        Selesaikan pengajuan dan dapatkan persetujuan untuk mengakses fitur lengkap
                                    </p>
                                </div>
                            </li>

                            {{-- Menu yang dikunci --}}
                            <li class="sidebar-item disabled" style="opacity: 0.5; pointer-events: none;">
                                <a href="#" class="sidebar-link">
                                    <i class="bi bi-speedometer2"></i>
                                    <span>Dashboard</span>
                                    <i class="bi bi-lock-fill ms-auto" style="font-size: 14px;"></i>
                                </a>
                            </li>
                        @endif

                        {{-- Role: Pembimbing --}}
                    @elseif(auth()->user()->role == 'pembimbing')
                        <li class="sidebar-item {{ request()->routeIs('pembimbing.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('pembimbing.dashboard') }}" class="sidebar-link">
                                <i class="bi bi-speedometer2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('pembimbing.bimbingan') ? 'active' : '' }}">
                            <a href="{{ route('pembimbing.bimbingan') }}" class="sidebar-link">
                                <i class="bi bi-people"></i>
                                <span>Bimbingan</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('pembimbing.kegiatan') ? 'active' : '' }}">
                            <a href="{{ route('pembimbing.kegiatan') }}" class="sidebar-link">
                                <i class="bi bi-journal-text"></i>
                                <span>Laporan Kegiatan</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('pembimbing.presensi') ? 'active' : '' }}">
                            <a href="{{ route('pembimbing.presensi') }}" class="sidebar-link">
                                <i class="bi bi-clipboard-check"></i>
                                <span>Presensi</span>
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

<style>
    /* Style untuk badge status */
    .sidebar-link .badge {
        padding: 2px 8px;
        border-radius: 10px;
        font-weight: 600;
    }

    /* Style untuk menu yang disabled */
    .sidebar-item.disabled {
        cursor: not-allowed;
    }

    .sidebar-item.disabled .sidebar-link {
        color: #6c757d !important;
    }

    /* Animation untuk info box */
    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.02);
        }
    }

    .sidebar-item [style*="background: linear-gradient"] {
        animation: pulse 3s ease-in-out infinite;
    }
</style>
