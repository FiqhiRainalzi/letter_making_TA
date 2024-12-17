@extends('layouts.admin')
@section('content')
    <div class="pagetitle">
        <h1>Surat Keterangan Publikasi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Surat Keterangan Publikasi</li>
                <li class="breadcrumb-item active">Membuat Surat Keterangan Publikasi</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <form action="{{ route('ketpub.store') }}" method="POST" enctype="multipart/form-data">
                            <a href="{{ url()->previous() }}" class="btn btn-dark mb-3 mt-3">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            @csrf
                            {{-- judul --}}
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Judul</label>
                                <input type="text" class="form-control @error('judul') is-invalid @enderror"
                                    name="judul" value="{{ old('judul') }}" placeholder="Masukkan Judul Artikel Ilmiah">

                                <!-- error message untuk title -->
                                @error('judul')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            {{-- nama penerbit --}}
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Nama Penerbit</label>
                                <input type="text" class="form-control @error('namaPenerbit') is-invalid @enderror"
                                    name="namaPenerbit" rows="5" placeholder="Masukkan Nama Penerbit"
                                    value="{{ old('namaPenerbit') }}">
                                <!-- error message untuk namaPenerbit -->
                                @error('namaPenerbit')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>
                            {{-- penerbit --}}
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Penerbit</label>
                                <input type="text" class="form-control @error('penerbit') is-invalid @enderror"
                                    name="penerbit" value="{{ old('penerbit') }}" placeholder="Masukkan Penerbit">

                                <!-- error message untuk penerbit -->
                                @error('penerbit')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            {{-- volume dan nomor --}}
                            <div class="row inventor-row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">Volume</label>
                                        <input type="number" class="form-control @error('volume') is-invalid @enderror"
                                            name="volume" value="{{ old('volume') }}"
                                            placeholder="Masukkan Volume Artikel">

                                        <!-- error message untuk nama_inventor -->
                                        @error('volume')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">Nomor</label>
                                        <input type="number" class="form-control @error('nomor') is-invalid @enderror"
                                            name="nomor" value="{{ old('nomor') }}" placeholder="Masukkan Nomor Artkel">

                                        <!-- error message untuk bidang_studi -->
                                        @error('nomor')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            {{-- bulan --}}
                            <div class="row inventor-row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">Bulan</label>
                                        <input type="text" class="form-control @error('bulan') is-invalid @enderror"
                                            name="bulan" value="{{ old('bulan') }}"
                                            placeholder="Masukkan Bulan Pembuatan Artikel Ilmiah">

                                        <!-- error message untuk bulan -->
                                        @error('bulan')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">Tahun
                                        <input type="text" class="form-control @error('tahun') is-invalid @enderror"
                                            name="tahun" value="{{ old('tahun') }}"
                                            placeholder="Masukkan Tahun Pembuatan Artikel Ilmiah">

                                        <!-- error message untuk tahun -->
                                        @error('tahun')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            {{-- akreditas --}}
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Terakreditas</label>
                                <input type="text" class="form-control @error('akreditas') is-invalid @enderror"
                                    name="akreditas" value="{{ old('akreditas') }}" placeholder="Masukkan Akreditas">

                                <!-- error message untuk akreditas -->
                                @error('akreditas')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            {{-- issn --}}
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">ISSN/ISBN</label>
                                <input type="text" class="form-control @error('issn') is-invalid @enderror"
                                    name="issn" value="{{ old('issn') }}" placeholder="Masukkan ISSN/ISBN">

                                <!-- error message untuk issn -->
                                @error('issn')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            {{-- penulis --}}
                            <div id="penulis-fields">
                                <div class="form-group mb-3">
                                    <label>Nama Penulis</label>
                                    <input type="text" name="penulis[0][nama]" class="form-control"
                                        placeholder="Masukkan Nama Penulis">
                                </div>
                            </div>

                            <button type="button" class="btn btn-success mb-3" id="add-penulis-btn">Tambah
                                Penulis</button>
                            {{-- tanggal --}}
                            <div class="row inventor-row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">Tanggal Pembuatan</label>
                                        <input type="date" class="form-control @error('tanggal') is-invalid @enderror"
                                            name="tanggal" rows="5"
                                            value="{{ old('tanggal', $today ?? date('Y-m-d')) }}"
                                            placeholder="Masukkan Alamat Pemegang Hak">
                                        <!-- error message untuk tanggal -->
                                        @error('tanggal')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-md btn-primary me-3">SAVE</button>
                            <button type="reset" class="btn btn-md btn-warning">RESET</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let penulisIndex = 1; // Index dimulai dari 0 untuk form create

        document.getElementById('add-penulis-btn').addEventListener('click', function() {
            // Membuat elemen div baru untuk baris penulis
            let newRow = document.createElement('div');
            newRow.classList.add('form-group', 'mb-3', 'penulis-row');

            // Isi HTML untuk input nama penulis
            newRow.innerHTML = `
                <label class="font-weight-bold">Nama Penulis ${penulisIndex + 1}</label>
                <input type="text" class="form-control" name="penulis[${penulisIndex}][nama]" placeholder="Masukkan Nama Penulis">
            `;

            // Menambahkan baris input baru ke container penulis
            document.getElementById('penulis-fields').appendChild(newRow);
            penulisIndex++;
        });
    </script>
@endsection
