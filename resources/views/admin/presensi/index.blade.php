@extends('kerangka.master')

@section('content')
    <div class="container py-2">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-primary mb-0">
                <i class="bi bi-calendar-check me-2"></i>Daftar Presensi Pelajar
            </h3>
        </div>

        {{-- Filter & Summary Cards --}}
        <div class="row g-3 mb-2">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-1 small text-uppercase">Tepat Waktu</p>
                                <h4 class="mb-0 fw-bold">{{ $presensis->where('status', 'Tepat Waktu')->count() }}</h4>
                            </div>
                            <i class="bi bi-check-circle fs-3 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-warning text-white">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-1 small text-uppercase">Terlambat</p>
                                <h4 class="mb-0 fw-bold">{{ $presensis->where('status', 'Terlambat')->count() }}</h4>
                            </div>
                            <i class="bi bi-clock-history fs-3 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-info text-white">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-1 small text-uppercase">Izin</p>
                                <h4 class="mb-0 fw-bold">{{ $presensis->where('status', 'Izin')->count() }}</h4>
                            </div>
                            <i class="bi bi-file-text fs-3 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-danger text-white">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-1 small text-uppercase">Alfa/Sakit</p>
                                <h4 class="mb-0 fw-bold">{{ $presensis->whereIn('status', ['Alfa', 'Sakit'])->count() }}
                                </h4>
                            </div>
                            <i class="bi bi-x-circle fs-3 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab Navigation --}}
        <ul class="nav nav-tabs mb-3" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="semua-tab" data-bs-toggle="tab" data-bs-target="#semua" type="button">
                    <i class="bi bi-list-ul me-1"></i>Semua Presensi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="perpelajar-tab" data-bs-toggle="tab" data-bs-target="#perpelajar"
                    type="button">
                    <i class="bi bi-people me-1"></i>Per Pelajar
                </button>
            </li>
        </ul>

        {{-- Tab Content --}}
        <div class="tab-content">
            {{-- Tab 1: Semua Presensi --}}
            <div class="tab-pane fade show active" id="semua" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h6 class="mb-0 text-muted">
                                    Total: <span class="badge bg-primary">{{ $presensis->count() }}</span> presensi
                                    @if (request('today'))
                                        <span class="badge bg-info ms-1">Hari Ini</span>
                                    @endif
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-end gap-2">
                                    @if (request('today'))
                                        <a href="{{ route('admin.presensi.index') }}" class="btn btn-sm btn-secondary">
                                            <i class="bi bi-arrow-left me-1"></i>Semua Presensi
                                        </a>
                                    @else
                                        <a href="{{ route('admin.presensi.index', ['today' => 1]) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="bi bi-calendar-day me-1"></i>Hari Ini
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        @if ($presensis->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr class="text-center">
                                            <th class="ps-3" style="width: 5%;">No</th>
                                            <th style="width: 20%;">Pelajar</th>
                                            <th style="width: 12%;">Tanggal</th>
                                            <th style="width: 12%;">Waktu Datang</th>
                                            <th style="width: 12%;">Waktu Pulang</th>
                                            <th style="width: 12%;">Status</th>
                                            <th style="width: 10%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($presensis as $index => $presensi)
                                            <tr>
                                                <td class="text-center ps-3">{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2"
                                                            style="width: 36px; height: 36px; flex-shrink: 0;">
                                                            <i class="bi bi-person-fill text-primary"></i>
                                                        </div>
                                                        <div style="min-width: 0;">
                                                            <div class="fw-semibold text-truncate"
                                                                style="font-size: 0.875rem;">
                                                                {{ $presensi->pelajar->nama ?? 'N/A' }}
                                                            </div>
                                                            <small class="text-muted text-truncate d-block">
                                                                {{ $presensi->user->email ?? '' }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div>
                                                        {{ \Carbon\Carbon::parse($presensi->tanggal)->format('d M Y') }}
                                                    </div>
                                                    <small class="text-muted">
                                                        {{ \Carbon\Carbon::parse($presensi->tanggal)->locale('id')->isoFormat('dddd') }}
                                                    </small>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-light text-dark border">
                                                        <i class="bi bi-clock me-1"></i>
                                                        {{ \Carbon\Carbon::parse($presensi->waktu_datang)->format('H:i') }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if ($presensi->waktu_pulang)
                                                        <span class="badge bg-light text-dark border">
                                                            <i class="bi bi-clock me-1"></i>
                                                            {{ \Carbon\Carbon::parse($presensi->waktu_pulang)->format('H:i') }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $statusClass = match ($presensi->status) {
                                                            'Tepat Waktu' => 'success',
                                                            'Terlambat' => 'warning',
                                                            'Izin' => 'info',
                                                            'Sakit' => 'secondary',
                                                            'Alfa' => 'danger',
                                                            default => 'secondary',
                                                        };
                                                    @endphp
                                                    <span class="badge bg-{{ $statusClass }}">
                                                        {{ $presensi->status }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-info"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#detailModal{{ $presensi->id }}"
                                                        title="Lihat Detail">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                            {{-- Modal Detail --}}
                                            <div class="modal fade" id="detailModal{{ $presensi->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary text-white">
                                                            <h5 class="modal-title">
                                                                <i class="bi bi-info-circle me-2"></i>Detail Presensi
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="text-muted small mb-1">Nama Pelajar</label>
                                                                <p class="fw-semibold mb-0">
                                                                    {{ $presensi->pelajar->nama ?? 'N/A' }}</p>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="text-muted small mb-1">Email</label>
                                                                <p class="mb-0">{{ $presensi->user->email ?? '-' }}</p>
                                                            </div>
                                                            <hr>
                                                            <div class="row mb-3">
                                                                <div class="col-6">
                                                                    <label class="text-muted small mb-1">Tanggal</label>
                                                                    <p class="mb-0">
                                                                        {{ \Carbon\Carbon::parse($presensi->tanggal)->format('d F Y') }}
                                                                    </p>
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="text-muted small mb-1">Hari</label>
                                                                    <p class="mb-0">
                                                                        {{ \Carbon\Carbon::parse($presensi->tanggal)->locale('id')->isoFormat('dddd') }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <div class="col-6">
                                                                    <label class="text-muted small mb-1">Waktu
                                                                        Datang</label>
                                                                    <p class="mb-0">
                                                                        <span class="badge bg-light text-dark border">
                                                                            <i
                                                                                class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($presensi->waktu_datang)->format('H:i:s') }}
                                                                        </span>
                                                                    </p>
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="text-muted small mb-1">Waktu
                                                                        Pulang</label>
                                                                    <p class="mb-0">
                                                                        @if ($presensi->waktu_pulang)
                                                                            <span class="badge bg-light text-dark border">
                                                                                <i
                                                                                    class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($presensi->waktu_pulang)->format('H:i:s') }}
                                                                            </span>
                                                                        @else
                                                                            <span class="text-muted">Belum pulang</span>
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="text-muted small mb-1">Status
                                                                    Kehadiran</label>
                                                                <p class="mb-0">
                                                                    <span class="badge bg-{{ $statusClass }}">
                                                                        {{ $presensi->status }}
                                                                    </span>
                                                                </p>
                                                            </div>
                                                            @if ($presensi->keterangan)
                                                                <hr>
                                                                <div class="mb-3">
                                                                    <label class="text-muted small mb-1">Keterangan</label>
                                                                    <p class="mb-0">{{ $presensi->keterangan }}</p>
                                                                </div>
                                                            @endif
                                                            <hr>
                                                            <div class="small text-muted">
                                                                <p class="mb-1">
                                                                    <i class="bi bi-calendar-plus me-2"></i>Dicatat:
                                                                    {{ $presensi->created_at->format('d M Y, H:i') }}
                                                                </p>
                                                                @if ($presensi->updated_at != $presensi->created_at)
                                                                    <p class="mb-0">
                                                                        <i class="bi bi-pencil me-2"></i>Diupdate:
                                                                        {{ $presensi->updated_at->format('d M Y, H:i') }}
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3 mb-0">
                                    @if (request('today'))
                                        Belum ada presensi hari ini
                                    @else
                                        Belum ada data presensi
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Tab 2: Per Pelajar --}}
            <div class="tab-pane fade" id="perpelajar" role="tabpanel">
                @php
                    // Group presensi by pelajar
                    $presensiPerPelajar = $presensis->groupBy('pelajar_id')->map(function ($items) {
                        $pelajar = $items->first()->pelajar;
                        return [
                            'pelajar' => $pelajar,
                            'total' => $items->count(),
                            'tepat_waktu' => $items->where('status', 'Tepat Waktu')->count(),
                            'terlambat' => $items->where('status', 'Terlambat')->count(),
                            'izin' => $items->where('status', 'Izin')->count(),
                            'sakit' => $items->where('status', 'Sakit')->count(),
                            'alfa' => $items->where('status', 'Alfa')->count(),
                            'presensis' => $items->sortByDesc('tanggal'),
                        ];
                    });
                @endphp

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="mb-0 text-muted">
                            Total: <span class="badge bg-primary">{{ $presensiPerPelajar->count() }}</span> pelajar
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        @if ($presensiPerPelajar->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4" style="width: 5%;">No</th>
                                            <th style="width: 25%;">Nama Pelajar</th>
                                            <th style="width: 10%;" class="text-center">Total</th>
                                            <th style="width: 10%;" class="text-center">Tepat Waktu</th>
                                            <th style="width: 10%;" class="text-center">Terlambat</th>
                                            <th style="width: 8%;" class="text-center">Izin</th>
                                            <th style="width: 8%;" class="text-center">Sakit</th>
                                            <th style="width: 8%;" class="text-center">Alfa</th>
                                            <th style="width: 10%;" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($presensiPerPelajar as $index => $data)
                                            <tr>
                                                <td class="ps-4">{{ $loop->iteration }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2"
                                                            style="width: 36px; height: 36px; flex-shrink: 0;">
                                                            <i class="bi bi-person-fill text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold">{{ $data['pelajar']->nama ?? 'N/A' }}
                                                            </div>
                                                            <small
                                                                class="text-muted">{{ $data['pelajar']->email ?? '-' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary fs-6">{{ $data['total'] }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-success">{{ $data['tepat_waktu'] }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-warning">{{ $data['terlambat'] }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-info">{{ $data['izin'] }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-secondary">{{ $data['sakit'] }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-danger">{{ $data['alfa'] }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-primary" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse{{ $index }}"
                                                        title="Lihat Detail">
                                                        <i class="bi bi-eye"></i> Detail
                                                    </button>
                                                </td>
                                            </tr>
                                            {{-- Collapsible Detail Row --}}
                                            <tr class="collapse" id="collapse{{ $index }}">
                                                <td colspan="9" class="p-0">
                                                    <div class="bg-light p-3">
                                                        <h6 class="mb-3">Riwayat Presensi {{ $data['pelajar']->nama }}</h6>
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-bordered mb-0">
                                                                <thead class="table-secondary">
                                                                    <tr>
                                                                        <th style="width: 5%;">No</th>
                                                                        <th style="width: 20%;">Tanggal</th>
                                                                        <th style="width: 15%;">Waktu Datang</th>
                                                                        <th style="width: 15%;">Waktu Pulang</th>
                                                                        <th style="width: 15%;">Status</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($data['presensis'] as $p)
                                                                        <tr>
                                                                            <td>{{ $loop->iteration }}</td>
                                                                            <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}
                                                                            </td>
                                                                            <td>{{ \Carbon\Carbon::parse($p->waktu_datang)->format('H:i') }}
                                                                            </td>
                                                                            <td>{{ $p->waktu_pulang ? \Carbon\Carbon::parse($p->waktu_pulang)->format('H:i') : '-' }}
                                                                            </td>
                                                                            <td>
                                                                                @php
                                                                                    $statusClass = match ($p->status) {
                                                                                        'Tepat Waktu' => 'success',
                                                                                        'Terlambat' => 'warning',
                                                                                        'Izin' => 'info',
                                                                                        'Sakit' => 'secondary',
                                                                                        'Alfa' => 'danger',
                                                                                        default => 'secondary',
                                                                                    };
                                                                                @endphp
                                                                                <span
                                                                                    class="badge bg-{{ $statusClass }}">{{ $p->status }}</span>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3 mb-0">Belum ada data presensi pelajar</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .table> :not(caption)>*>* {
            padding: 0.75rem 0.5rem;
            vertical-align: middle;
        }

        .avatar-sm {
            flex-shrink: 0;
        }

        .modal-body label {
            font-weight: 600;
        }

        .nav-tabs .nav-link {
            color: #6c757d;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            font-weight: 600;
        }
    </style>
@endpush
