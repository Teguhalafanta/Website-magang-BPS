@extends('kerangka.master')

@section('content')
    <div class="container py-2">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold text-primary mb-0">
                <i class="bi bi-person-check-fill me-2"></i>Assign Pembimbing
            </h3>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Summary Cards --}}
        <div class="row g-3 mb-2">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-1 small text-uppercase">Total Peserta</p>
                                <h4 class="mb-0 fw-bold">{{ $pelajars->count() }}</h4>
                            </div>
                            <i class="bi bi-people fs-3 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-1 small text-uppercase">Sudah Ditentukan</p>
                                <h4 class="mb-0 fw-bold">{{ $pelajars->whereNotNull('pembimbing_id')->count() }}</h4>
                            </div>
                            <i class="bi bi-check-circle fs-3 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-warning text-white">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-1 small text-uppercase">Belum Ditentukan</p>
                                <h4 class="mb-0 fw-bold">{{ $pelajars->whereNull('pembimbing_id')->count() }}</h4>
                            </div>
                            <i class="bi bi-exclamation-circle fs-3 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Table --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 text-muted">
                    Total: <span class="badge bg-primary">{{ $pelajars->count() }}</span> peserta
                </h6>
            </div>

            <div class="card-body p-0">
                @if ($pelajars->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th class="ps-3" style="width: 4%;">No</th>
                                    <th style="width: 20%;">Nama Peserta</th>
                                    <th style="width: 18%;">Asal Institusi</th>
                                    <th style="width: 12%;">Jurusan</th>
                                    <th style="width: 15%;">Pembimbing Saat Ini</th>
                                    <th style="width: 31%;">Assign Pembimbing</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pelajars as $index => $pelajar)
                                    <tr>
                                        <td class="text-center ps-3">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2"
                                                    style="width: 36px; height: 36px; flex-shrink: 0;">
                                                    <i class="bi bi-person-fill text-primary"></i>
                                                </div>
                                                <div style="min-width: 0;">
                                                    <div class="fw-semibold text-truncate" style="font-size: 0.875rem;">
                                                        {{ $pelajar->nama }}
                                                    </div>
                                                    <small class="text-muted text-truncate d-block">
                                                        {{ $pelajar->email }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-muted small">{{ $pelajar->asal_institusi }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-info bg-opacity-10 text-info">{{ $pelajar->jurusan }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if ($pelajar->pembimbing)
                                                <div>
                                                    <span
                                                        class="fw-semibold d-block small">{{ $pelajar->pembimbing->nama }}</span>
                                                    <small class="text-muted">
                                                        <i
                                                            class="bi bi-people-fill me-1"></i>{{ $pelajar->pembimbing->tim }}
                                                    </small>
                                                </div>
                                            @else
                                                <span class="badge bg-warning">Belum Ditentukan</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form
                                                action="{{ route('admin.assignpembimbing.assign', ['id' => $pelajar->id]) }}"
                                                method="POST" class="d-flex gap-2">
                                                @csrf
                                                <select name="pembimbing_id" class="form-select form-select-sm flex-1"
                                                    required>
                                                    <option value="">-- Pilih Pembimbing --</option>
                                                    @foreach ($pembimbings as $pembimbing)
                                                        <option value="{{ $pembimbing->id }}"
                                                            {{ $pelajar->pembimbing_id == $pembimbing->id ? 'selected' : '' }}>
                                                            {{ $pembimbing->nama }}
                                                            ({{ $pembimbing->tim ?? 'Tanpa Tim' }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-primary btn-sm px-3">
                                                    <i class="bi bi-check-lg me-1"></i>Simpan
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3 mb-0">Belum ada peserta yang disetujui magang</p>
                    </div>
                @endif
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
    </style>
@endpush
