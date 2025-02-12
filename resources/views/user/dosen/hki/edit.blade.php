@extends('layouts.admin')
@section('content')
    <div class="pagetitle">
        <h1>Edit Surat HKI</h1>
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
                        <form action="{{ route('hki.update', $hki->id) }}" method="POST" enctype="multipart/form-data">
                            <a href="{{ url()->previous() }}" class="btn btn-dark mb-3 mt-3">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            @csrf
                            @method('PUT') <!-- Method untuk update -->

                            {{-- nama pemegang hak --}}
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Nama Pemegang Hak</label>
                                <input type="text" class="form-control @error('namaPemegang') is-invalid @enderror"
                                    name="namaPemegang" value="{{ old('namaPemegang', $hki->namaPemegang) }}"
                                    placeholder="Masukkan Nama Pemegang hak">

                                <!-- error message untuk namaPemegang -->
                                @error('namaPemegang')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- alamat --}}
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Alamat Pemegang Hak</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="5"
                                    placeholder="Masukkan Alamat Pemegang Hak">{{ old('alamat', $hki->alamat) }}</textarea>
                                <!-- error message untuk alamat -->
                                @error('alamat')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- judul --}}
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Judul Invensi</label>
                                <input type="text" class="form-control @error('judul') is-invalid @enderror"
                                    name="judul" value="{{ old('judul', $hki->judul) }}"
                                    placeholder="Masukkan Judul Invensi">

                                <!-- error message untuk judul -->
                                @error('judul')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- inventors 1 sampai 10 --}}
                            @for ($i = 1; $i <= 10; $i++)
                                <div class="row inventor-row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold">Nama Inventor</label>
                                            <input type="text" class="form-control" name="inventor{{ $i }}"
                                                value="{{ old('inventor' . $i, $hki->{'inventor' . $i}) }}"
                                                placeholder="Masukkan Nama Inventor">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold">Bidang Studi</label>
                                            <input type="text" class="form-control"
                                                name="bidangStudi{{ $i }}"
                                                value="{{ old('bidangStudi' . $i, $hki->{'bidangStudi' . $i}) }}"
                                                placeholder="Masukkan Bidang Studi">
                                        </div>
                                    </div>
                                </div>
                            @endfor
                            {{-- ENDS inventors 1 sampai 10 --}}

                            {{-- tanggal --}}
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Tanggal Pembuatan</label>
                                <input type="date" class="form-control @error('tanggal') is-invalid @enderror"
                                    name="tanggal" value="{{ old('tanggal', $hki->tanggal) }}">
                                <!-- error message untuk tanggal -->
                                @error('tanggal')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-md btn-primary me-3">UPDATE</button>
                            <button type="reset" class="btn btn-md btn-warning">RESET</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
