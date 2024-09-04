@extends('layouts.admin')
@section('content')
    <div class="pagetitle">
        <h1>Surat HKI</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="">Home</a></li>
                <li class="breadcrumb-item active">HKI</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <form action="{{ route('hki.update', $hki->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Nama Pemegang Hak</label>
                                <input type="text" class="form-control @error('namaPemHki') is-invalid @enderror"
                                    name="namaPemHki" 
                                    value="{{ old('namaPemHki', $hki->namaPemHki ?? '') }}" 
                                    placeholder="Masukkan Nama Pemegang hak">

                                @error('namaPemHki')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Alamat Pemegang Hak</label>
                                <textarea class="form-control @error('alamatPemHki') is-invalid @enderror" 
                                    name="alamatPemHki" rows="5" placeholder="Masukkan Alamat Pemegang Hak">{{ old('alamatPemHki', $hki->alamatPemHki ?? '') }}</textarea>

                                @error('alamatPemHki')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Judul Invensi</label>
                                <input type="text" class="form-control @error('judulInvensi') is-invalid @enderror"
                                    name="judulInvensi" 
                                    value="{{ old('judulInvensi', $hki->judulInvensi ?? '') }}"
                                    placeholder="Masukkan Judul Invensi">

                                @error('judulInvensi')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div id="inventor-fields">
                                @for ($i = 1; $i <= 8; $i++)
                                    @if (!empty($hki->{'namaInventor' . $i}) && !empty($hki->{'bidangStudi' . $i}))
                                        <div class="row inventor-row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="font-weight-bold">Nama Inventor {{ $i }}</label>
                                                    <input type="text"
                                                        class="form-control @error('namaInventor{{ $i }}') is-invalid @enderror"
                                                        name="namaInventor{{ $i }}"
                                                        value="{{ old('namaInventor' . $i, $hki->{'namaInventor' . $i} ?? '') }}"
                                                        placeholder="Masukkan Nama Inventor">

                                                    @error('namaInventor{{ $i }}')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="font-weight-bold">Bidang Studi</label>
                                                    <input type="text"
                                                        class="form-control @error('bidangStudi{{ $i }}') is-invalid @enderror"
                                                        name="bidangStudi{{ $i }}"
                                                        value="{{ old('bidangStudi' . $i, $hki->{'bidangStudi' . $i} ?? '') }}"
                                                        placeholder="Masukkan Bidang Studi">

                                                    @error('bidangStudi{{ $i }}')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endfor
                            </div>                

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Tanggal Pembuatan</label>
                                <input type="date"
                                    class="form-control @error('tanggalPemHki') is-invalid @enderror"
                                    name="tanggalPemHki" 
                                    value="{{ old('tanggalPemHki', $hki->tanggalPemHki ?? '') }}"
                                    placeholder="Masukkan Tanggal Pembuatan">

                                @error('tanggalPemHki')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
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
