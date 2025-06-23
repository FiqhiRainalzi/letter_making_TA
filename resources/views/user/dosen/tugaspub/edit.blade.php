@extends('layouts.admin')
@section('content')
    <div class="pagetitle">
        <h1>Surat Tugas Publikasi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="">Home</a></li>
                <li class="breadcrumb-item active">Surat Tugas Publikasi</li>
                <li class="breadcrumb-item active">Edit Surat Tugas Publikasi</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <form action="{{ route('tugaspub.update', $tugaspub->id) }}" method="POST"
                            enctype="multipart/form-data">
                            <a href="{{ url()->previous() }}" class="btn btn-dark mb-3 mt-3">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            @method('PUT')
                            @csrf
                            {{-- kategori jurnal --}}
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Kategori Jurnal</label>
                                <select class="form-control @error('kategori_jurnal') is-invalid @enderror"
                                    id="kategori_jurnal" name="kategori_jurnal">
                                    <option value="" disabled
                                        {{ old('kategori_jurnal', $tugaspub->kategori_jurnal ?? '') == '' ? 'selected' : '' }}>
                                        Pilih Kategori Jurnal
                                    </option>
                                    <option value="Jurnal Nasional Terakreditasi Sinta 1"
                                        {{ old('kategori_jurnal', $tugaspub->kategori_jurnal ?? '') == 'Jurnal Nasional Terakreditasi Sinta 1' ? 'selected' : '' }}>
                                        Jurnal Nasional Terakreditasi Sinta 1
                                    </option>
                                    <option value="Jurnal Nasional Terakreditasi Sinta 2"
                                        {{ old('kategori_jurnal', $tugaspub->kategori_jurnal ?? '') == 'Jurnal Nasional Terakreditasi Sinta 2' ? 'selected' : '' }}>
                                        Jurnal Nasional Terakreditasi Sinta 2
                                    </option>
                                    <option value="Jurnal Nasional Terakreditasi Sinta 3"
                                        {{ old('kategori_jurnal', $tugaspub->kategori_jurnal ?? '') == 'Jurnal Nasional Terakreditasi Sinta 3' ? 'selected' : '' }}>
                                        Jurnal Nasional Terakreditasi Sinta 3
                                    </option>
                                    <option value="Jurnal Nasional Terakreditasi Sinta 4"
                                        {{ old('kategori_jurnal', $tugaspub->kategori_jurnal ?? '') == 'Jurnal Nasional Terakreditasi Sinta 4' ? 'selected' : '' }}>
                                        Jurnal Nasional Terakreditasi Sinta 4
                                    </option>
                                    <option value="Jurnal Nasional Terakreditasi Sinta 5"
                                        {{ old('kategori_jurnal', $tugaspub->kategori_jurnal ?? '') == 'Jurnal Nasional Terakreditasi Sinta 5' ? 'selected' : '' }}>
                                        Jurnal Nasional Terakreditasi Sinta 5
                                    </option>
                                    <option value="Jurnal Nasional Terakreditasi Sinta 6"
                                        {{ old('kategori_jurnal', $tugaspub->kategori_jurnal ?? '') == 'Jurnal Nasional Terakreditasi Sinta 6' ? 'selected' : '' }}>
                                        Jurnal Nasional Terakreditasi Sinta 6
                                    </option>
                                    <option value="Jurnal Tidak Terakreditasi"
                                        {{ old('kategori_jurnal', $tugaspub->kategori_jurnal ?? '') == 'Jurnal Tidak Terakreditasi' ? 'selected' : '' }}>
                                        Jurnal Tidak Terakreditasi
                                    </option>
                                    <option value="Jurnal Internasional Bereputasi"
                                        {{ old('kategori_jurnal', $tugaspub->kategori_jurnal ?? '') == 'Jurnal Internasional Bereputasi' ? 'selected' : '' }}>
                                        Jurnal Internasional Bereputasi
                                    </option>
                                    <option value="Jurnal Internasional Tidak Bereputasi"
                                        {{ old('kategori_jurnal', $tugaspub->kategori_jurnal ?? '') == 'Jurnal Internasional Tidak Bereputasi' ? 'selected' : '' }}>
                                        Jurnal Internasional Tidak Bereputasi
                                    </option>
                                    <option value="Seminar Internasional"
                                        {{ old('kategori_jurnal', $tugaspub->kategori_jurnal ?? '') == 'Seminar Internasional' ? 'selected' : '' }}>
                                        Seminar Internasional
                                    </option>
                                    <option value="Seminar Nasional"
                                        {{ old('kategori_jurnal', $tugaspub->kategori_jurnal ?? '') == 'Seminar Nasional' ? 'selected' : '' }}>
                                        Seminar Nasional
                                    </option>
                                </select>
                                <!-- error message untuk kategori_jurnal -->
                                @error('kategori_jurnal')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">Nama Publikasi</label>
                                        <input type="text"
                                            class="form-control @error('namaPublikasi') is-invalid @enderror"
                                            name="namaPublikasi"
                                            value="{{ old('namaPublikasi', $tugaspub->namaPublikasi) }}"
                                            placeholder="Masukkan Nama Publikasi">

                                        <!-- error message untuk title -->
                                        @error('namaPublikasi')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">Penerbit</label>
                                        <input type="text" class="form-control @error('penerbit') is-invalid @enderror"
                                            name="penerbit" value="{{ old('penerbit', $tugaspub->penerbit) }}"
                                            placeholder="Masukkan Nama Penerbit">

                                        <!-- error message untuk title -->
                                        @error('penerbit')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Alamat</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="5"
                                    placeholder="Masukkan Alamat">{{ old('alamat', $tugaspub->alamat) }}</textarea>
                                <!-- error message untuk alamat -->
                                @error('alamat')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Link</label>
                                <input type="text" class="form-control @error('link') is-invalid @enderror"
                                    name="link" value="{{ old('link', $tugaspub->link) }}" placeholder="Masukkan Link">

                                <!-- error message untuk title -->
                                @error('link')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="row inventor-row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">Volume</label>
                                        <input type="text" class="form-control @error('volume') is-invalid @enderror"
                                            name="volume" value="{{ old('volume', $tugaspub->volume) }}"
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
                                        <input type="text" class="form-control @error('nomor') is-invalid @enderror"
                                            name="nomor" value="{{ old('nomor', $tugaspub->nomor) }}"
                                            placeholder="Masukkan Nomor Artkel">

                                        <!-- error message untuk bidang_studi -->
                                        @error('nomor')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row inventor-row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">Bulan</label>
                                        <input type="text" class="form-control @error('bulan') is-invalid @enderror"
                                            name="bulan" value="{{ old('bulan', $tugaspub->bulan) }}"
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
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">Terakreditas</label>
                                        <input type="text"
                                            class="form-control @error('akreditas') is-invalid @enderror" name="akreditas"
                                            value="{{ old('akreditas', $tugaspub->akreditas) }}"
                                            placeholder="Masukkan Akreditas">

                                        <!-- error message untuk akreditas -->
                                        @error('akreditas')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">ISSN/ISBN</label>
                                <input type="text" class="form-control @error('issn') is-invalid @enderror"
                                    name="issn" value="{{ old('issn', $tugaspub->issn) }}"
                                    placeholder="Masukkan ISSN/ISBN">

                                <!-- error message untuk issn -->
                                @error('issn')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Judul</label>
                                <input type="text" class="form-control @error('judul') is-invalid @enderror"
                                    name="judul" value="{{ old('judul', $tugaspub->judul) }}"
                                    placeholder="Masukkan Judul">

                                <!-- error message untuk title -->
                                @error('judul')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Penulis -->
                            <div class="card mb-4">
                                <div class="card-header">Penulis (Maksimal 10)</div>
                                <div class="card-body">
                                    <div id="penulis-fields">
                                        @for ($i = 0; $i < 10; $i++)
                                            @php
                                                $penulis = $tugaspub->penulis->get($i); // Ambil data Penulis ke-$i
                                            @endphp
                                            <div class="row mb-3 penulis-form @if ($i >= 3) d-none @endif"
                                                id="penulis-{{ $i }}">
                                                <div class="col-md-6">
                                                    <label for="penulis[{{ $i }}][nama]">Nama Penulis
                                                         {{ $i + 1 }}</label>
                                                    <input type="text" name="penulis[{{ $i }}][nama]"
                                                        class="form-control"
                                                        value="{{ old("penulis.$i.nama", $penulis->nama ?? '') }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="penulis[{{ $i }}][prodi_id]">Prodi</label>
                                                    <select name="penulis[{{ $i }}][prodi_id]"
                                                        class="form-control">
                                                        <option value="">-- Pilih Prodi --</option>
                                                        @foreach ($prodis as $prodi)
                                                            <option value="{{ $prodi->id }}"
                                                                {{ old("penulis.$i.prodi_id", $penulis->prodi_id ?? '') == $prodi->id ? 'selected' : '' }}>
                                                                {{ $prodi->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                    <button type="button" class="btn btn-secondary"
                                        id="tampilkan-semua-penulis">Tampilkan
                                        Semua Penulis</button>
                                </div>
                            </div>


                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Tanggal Pembuatan</label>
                                <input type="date" class="form-control @error('tanggal') is-invalid @enderror"
                                    name="tanggal" rows="5" value="{{ old('tanggal', $today ?? date('Y-m-d')) }}"
                                    placeholder="Masukkan Alamat Pemegang Hak">
                                <!-- error message untuk tanggal -->
                                @error('tanggal')
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
    <script>
        document.getElementById("tampilkan-semua-penulis").addEventListener("click", function() {
            let penulisForms = document.querySelectorAll(".penulis-form.d-none");

            penulisForms.forEach(function(el) {
                el.classList.remove("d-none"); // Hapus kelas d-none agar elemen muncul
            });

            this.style.display = "none"; // Sembunyikan tombol setelah diklik
        });
    </script>

@endsection
