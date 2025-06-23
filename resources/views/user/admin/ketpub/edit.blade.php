@extends('layouts.admin')
@section('content')
    @php
        $nomorSurat = $ketpub->nomorSurat ?? '';
        preg_match('/^(\d{3})/', $nomorSurat, $match);
        $nomorUrut = $match[1] ?? '';
    @endphp
    <div class="pagetitle">
        <h1>Surat Keterangan Publikasi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Surat Keterangan Publikasi</li>
                <li class="breadcrumb-item active">Edit Surat Keterangan Publikasi</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <form action="{{ route('admin.ketpubUpdate', $ketpub->id) }}" method="POST"
                            enctype="multipart/form-data">
                            <a href="{{ url()->previous() }}" class="btn btn-dark mb-3 mt-3">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="dosen_id" value="{{ $ketpub->user_id }}">
                            {{-- Pilih Kode Surat --}}
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Kode Surat</label>
                                <select name="kode_surat_id" class="form-control" required>
                                    <option value="">-- Pilih Kode Surat --</option>
                                    @foreach ($kodeSurats as $kode)
                                        <option value="{{ $kode->id }}"
                                            {{ old('kode_surat_id', $ketpub->kode_surat_id) == $kode->id ? 'selected' : '' }}>
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
                                    value="{{ old('nomorSurat', $ketpub->nomorSurat) }}" required>
                                @error('nomorSurat')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="statusSurat">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="disetujui"
                                        {{ $ketpub->ajuanSurat->status == 'disetujui' ? 'selected' : '' }}>Disetujui
                                    </option>
                                    <option value="siap diambil"
                                        {{ $ketpub->ajuanSurat->status == 'siap diambil' ? 'selected' : '' }}>Siap Diambil
                                    </option>
                                    <option value="sudah diambil"
                                        {{ $ketpub->ajuanSurat->status == 'sudah diambil' ? 'selected' : '' }}>Sudah
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
