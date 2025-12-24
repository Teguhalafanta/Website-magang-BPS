@extends('kerangka.master')

@section('content')
    <div class="container py-2">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold text-primary mb-0">
                <i class="bi bi-pencil-square me-2"></i>Edit User
            </h3>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning border-0 py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bi bi-person-fill me-2"></i>{{ $user->username }}
                </h6>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Username</label>
                            <input type="text" class="form-control" value="{{ $user->username }}" readonly
                                style="background-color: #f8f9fa;">
                            <small class="text-muted">Username tidak dapat diubah</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $user->email) }}" required placeholder="contoh@email.com">
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                            <select name="role" class="form-select" required>
                                <option value="" disabled>-- Pilih Role --</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                    Admin
                                </option>
                                <option value="pembimbing" {{ old('role', $user->role) == 'pembimbing' ? 'selected' : '' }}>
                                    Pembimbing
                                </option>
                                <option value="pelajar" {{ old('role', $user->role) == 'pelajar' ? 'selected' : '' }}>
                                    Peserta
                                </option>
                            </select>
                            @error('role')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Password Baru</label>
                            <input type="password" name="password" class="form-control"
                                placeholder="Kosongkan jika tidak ingin mengubah password">
                            <small class="text-muted">Minimal 8 karakter jika diisi</small>
                            @error('password')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-12">
                            <div class="alert alert-info mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Informasi:</strong> Password hanya perlu diisi jika ingin mengubahnya.
                            </div>
                        </div>

                        <div class="col-12">
                            <hr class="my-2">
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-save me-2"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
