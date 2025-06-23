@extends('layouts.admin')
@section('content')
    @php
        $nomorSurat = $tugaspub->nomorSurat ?? '';
        preg_match('/^(\d{3})/', $nomorSurat, $match);
        $nomorUrut = $match[1] ?? '';
    @endphp
    <div class="pagetitle">
        <h1>Surat Tugas Publikasi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Edit Surat Tugas Publikasi</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <form action="{{ route('admin.tugaspubUpdate', $tugaspub->id) }}" method="POST"
                            enctype="multipart/form-data">
                            <a href="{{ url()->previous() }}" class="btn btn-dark mb-3 mt-3">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            @method('PUT')
                            @csrf
                            <input type="hidden" name="dosen_id" value="{{ $tugaspub->user_id }}">
                            {{-- Pilih Kode Surat --}}
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Kode Surat</label>
                                <select name="kode_surat_id" class="form-control" required>
                                    <option value="">-- Pilih Kode Surat --</option>
                                    @foreach ($kodeSurats as $kode)
                                        <option value="{{ $kode->id }}"
                                            {{ old('kode_surat_id', $tugaspsub->kode_surat_id) == $kode->id ? 'selected' : '' }}>
                                            {{ $kode->kode_instansi }}{{ $kode->kode_layanan ? '/' . $kode->kode_layanan : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kode_surat_id')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror

                            </div>

                            {{-- NOMOR SURAT --}}
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Nomor Surat</label>
                                <input type="number" class="form-control" name="nomorSurat"
                                    value="{{ old('nomorSurat', $tugaspub->nomorSurat) }}" required>
                                @error('nomorSurat')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="statusSurat">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="disetujui"
                                        {{ $tugaspub->ajuanSurat->status == 'disetujui' ? 'selected' : '' }}>Disetujui
                                    </option>
                                    <option value="siap_diambil"
                                        {{ $tugaspub->ajuanSurat->status == 'siap_diambil' ? 'selected' : '' }}>Siap
                                        Diambil
                                    </option>
                                    <option value="sudah diambil"
                                        {{ $tugaspub->ajuanSurat->status == 'sudah diambil' ? 'selected' : '' }}>Sudah
                                        Diambil
                                    </option>
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
