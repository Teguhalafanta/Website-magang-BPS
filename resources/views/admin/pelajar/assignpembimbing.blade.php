@extends('kerangka.master')

@section('content')
    <div class="container mt-4">
        <h4 class="mb-4">Assign Pembimbing ke Pelajar</h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($pelajars->isEmpty())
            <div class="alert alert-info">Belum ada pelajar yang disetujui magang.</div>
        @else
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Pelajar</th>
                        <th>Asal Institusi</th>
                        <th>Jurusan</th>
                        <th>Pembimbing Saat Ini</th>
                        <th>Assign Pembimbing</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pelajars as $index => $pelajar)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $pelajar->nama }}</td>
                            <td>{{ $pelajar->asal_institusi }}</td>
                            <td>{{ $pelajar->jurusan }}</td>
                            <td>
                                {{ $pelajar->pembimbing ? $pelajar->pembimbing->nama . ' (' . $pelajar->pembimbing->tim . ')' : 'Belum Ditentukan' }}
                            </td>
                            <td>
                                <form action="{{ route('admin.assignpembimbing.assign', $pelajar->id) }}" method="POST"
                                    class="d-flex align-items-center">
                                    @csrf
                                    <select name="pembimbing_id" class="form-select me-2" required>
                                        <option value="">-- Pilih Pembimbing --</option>
                                        @foreach ($pembimbings as $pembimbing)
                                            <option value="{{ $pembimbing->id }}"
                                                {{ $pelajar->pembimbing_id == $pembimbing->id ? 'selected' : '' }}>
                                                {{ $pembimbing->nama }} ({{ $pembimbing->tim ?? 'Tanpa Tim' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
