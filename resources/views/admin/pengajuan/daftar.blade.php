@extends('kerangka.master')

@section('content')
<div class="container">
    <h2 class="mb-4">Daftar Pengajuan Magang</h2>

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Asal Institusi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengajuans as $key => $p)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $p->nama }}</td>
                <td>{{ $p->asal_institusi }}</td>
                <td>
                    @if ($p->status == 'diajukan')
                    <span class="badge bg-primary">Diajukan</span>
                    @elseif($p->status == 'disetujui')
                    <span class="badge bg-success">Disetujui</span>
                    @elseif($p->status == 'ditolak')
                    <span class="badge bg-danger">Ditolak</span>
                    @endif
                </td>

                <td class="text-center">
                    <!-- Tombol Lihat (Modal) -->
                    <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                        data-bs-target="#detailModal{{ $p->id }}" title="Lihat Detail">
                        <i class="bi bi-eye"></i>
                    </button>

                    <!-- Edit -->
                    <a href="{{ route('admin.pengajuan.edit', $p->id) }}" class="btn btn-sm btn-warning"
                        title="Edit">
                        <i class="bi bi-pencil-square"></i>
                    </a>

                    <!-- Hapus -->
                    <form action="{{ route('admin.pengajuan.destroy', $p->id) }}" method="POST"
                        class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>

            <!-- Modal Detail -->
            <div class="modal fade" id="detailModal{{ $p->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-dark text-white">
                            <h5 class="modal-title">Detail Pengajuan - {{ $p->nama }}</h5>
                            <button type="button" class="btn-close btn-close-white"
                                data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>NIM/NISN:</strong> {{ $p->nim_nisn }}</p>
                            <p><strong>Jurusan:</strong> {{ $p->jurusan }}</p>
                            <p><strong>Rencana Mulai:</strong> {{ $p->rencana_mulai }}</p>
                            <p><strong>Rencana Selesai:</strong> {{ $p->rencana_selesai }}</p>
                            <p><strong>Email:</strong> {{ $p->email }}</p>
                            <p><strong>Telepon:</strong> {{ $p->telepon }}</p>

                            {{-- Proposal --}}
                            <p><strong>Proposal:</strong>
                                @if ($p->proposal)
                                <a href="{{ asset('storage/' . $p->proposal) }}" target="_blank"
                                    class="btn btn-sm btn-secondary">Lihat Proposal</a>
                                @else
                                <span class="text-muted">Belum ada</span>
                                @endif
                            </p>

                            {{-- Surat Pengajuan --}}
                            <p><strong>Surat Pengajuan:</strong>
                                @if ($p->surat_pengajuan)
                                <a href="{{ asset('storage/' . $p->surat_pengajuan) }}" target="_blank"
                                    class="btn btn-sm btn-secondary">Lihat Surat</a>
                                @else
                                <span class="text-muted">Belum ada</span>
                                @endif
                            </p>

                            {{-- Form Update Status --}}
                            <form action="{{ route('admin.pengajuan.updateStatus', $p->id) }}"
                                method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-2">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select" required>
                                        <option value="diajukan" {{ $p->status == 'diajukan' ? 'selected' : '' }}
                                            disabled>
                                            Diajukan
                                        </option>
                                        <option value="disetujui"
                                            {{ $p->status == 'disetujui' ? 'selected' : '' }}>
                                            Disetujui
                                        </option>
                                        <option value="ditolak" {{ $p->status == 'ditolak' ? 'selected' : '' }}>
                                            Ditolak
                                        </option>
                                    </select>

                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Alasan (jika ditolak)</label>
                                    <textarea name="alasan" class="form-control">{{ $p->alasan }}</textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <tr>
                <td colspan="5" class="text-center">Belum ada pengajuan magang</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection