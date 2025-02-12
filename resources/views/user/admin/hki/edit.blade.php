@extends('layouts.admin')
@section('content')
    <div class="pagetitle">
        <h1>Surat HKI</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">HKI</li>
                <li class="breadcrumb-item active">Edit Surat HKI</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <form action="{{ route('admin.hkiUpdate', $hki->id) }}" method="POST">
                            <a href="{{ url()->previous() }}" class="btn btn-dark mb-3 mt-3">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="dosen_id" value="{{ $hki->user_id }}">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Nomor Surat</label>
                                <input type="text" class="form-control @error('nomorSurat') is-invalid @enderror"
                                    name="nomorSurat" value="{{ old('nomorSurat', $hki->nomorSurat ?? '') }}"
                                    placeholder="Masukkan Nomor Surat">

                                @error('nomorSurat')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="statusSurat">Status</label>
                                <select name="statusSurat" id="statusSurat" class="form-control">
                                    <option value="draft" {{ $hki->statusSurat == 'draft' ? 'selected' : '' }}>Draf
                                    </option>
                                    <option value="approved" {{ $hki->statusSurat == 'approved' ? 'selected' : '' }}>
                                        DIterima</option>
                                    <option value="ready_for_pickup"
                                        {{ $hki->statusSurat == 'ready_for_pickup' ? 'selected' : '' }}>Siap Diambil
                                    </option>
                                    <option value="picked_up" {{ $hki->statusSurat == 'picked_up' ? 'selected' : '' }}>
                                        Sudah Diambil</option>
                                    <option value="rejected" {{ $hki->statusSurat == 'rejected' ? 'selected' : '' }}>
                                        Ditolak</option>
                                </select>

                            </div>
                            <button type="submit" class="btn btn-md btn-primary me-3">SAVE</button>
                            <button type="reset" class="btn btn-md btn-warning">RESET</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
