@extends('kerangka.master')

@section('content')
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center mb-3">
                    <div class="me-3">
                        <i class="bi bi-person-check-fill fs-1 text-primary"></i>
                    </div>
                    <div>
                        <h3 class="mb-1 fw-bold">Assign Pembimbing</h3>
                        <p class="text-muted mb-0">Kelola pembimbing untuk pelajar magang</p>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong>Berhasil!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($pelajars->isEmpty())
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
                    <h5 class="text-muted">Belum ada pelajar yang disetujui magang</h5>
                    <p class="text-muted small mb-0">Data akan muncul setelah ada pelajar yang disetujui</p>
                </div>
            </div>
        @else
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gray border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">Daftar Pelajar Magang</h5>
                        <span class="badge bg-primary rounded-pill">{{ $pelajars->count() }} Pelajar</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="fw-semibold text-center" style="width: 60px;">No</th>
                                    <th class="fw-semibold">Nama Pelajar</th>
                                    <th class="fw-semibold">Asal Institusi</th>
                                    <th class="fw-semibold">Jurusan</th>
                                    <th class="fw-semibold">Pembimbing Saat Ini</th>
                                    <th class="fw-semibold" style="min-width: 350px;">Assign Pembimbing</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pelajars as $index => $pelajar)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2"
                                                    style="width: 35px; height: 35px;">
                                                    <span
                                                        class="text-primary fw-bold small">{{ strtoupper(substr($pelajar->nama, 0, 1)) }}</span>
                                                </div>
                                                <span class="fw-semibold">{{ $pelajar->nama }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted small">{{ $pelajar->asal_institusi }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-info bg-opacity-10 text-info">{{ $pelajar->jurusan }}</span>
                                        </td>
                                        <td>
                                            @if ($pelajar->pembimbing)
                                                <div>
                                                    <span
                                                        class="fw-semibold d-block">{{ $pelajar->pembimbing->nama }}</span>
                                                    <small class="text-muted">
                                                        <i
                                                            class="bi bi-people-fill me-1"></i>{{ $pelajar->pembimbing->tim }}
                                                    </small>
                                                </div>
                                            @else
                                                <span class="badge bg-warning bg-opacity-10 text-warning">
                                                    <i class="bi bi-exclamation-circle me-1"></i>Belum Ditentukan
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <form
                                                action="{{ route('admin.assignpembimbing.assign', ['id' => $pelajar->id]) }}"
                                                method="POST" class="d-flex gap-2">
                                                @csrf
                                                <select name="pembimbing_id" class="form-select form-select-sm flex-grow-1"
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
                </div>
            </div>
        @endif
    </div>
@endsection
