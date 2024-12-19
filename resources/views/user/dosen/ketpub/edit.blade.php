@extends('layouts.admin')
@section('content')
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
                        <form action="{{ route('ketpub.update', $ketpub->id) }}" method="POST"
                            enctype="multipart/form-data">
                            <a href="{{ url()->previous() }}" class="btn btn-dark mb-3 mt-3">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            @csrf
                            @method('PUT')
                            {{-- kategori publikasi --}}
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Kategori Publikasi Akademik</label>
                                <select class="form-control @error('kategori_publikasi') is-invalid @enderror"
                                    id="kategori_publikasi" name="kategori_publikasi">
                                    <option value="" disabled>Pilih Kategori Publikasi</option>
                                    <option value="Artikel Ilmiah"
                                        {{ old('kategori_publikasi', $ketpub->kategori_publikasi) == 'Artikel Ilmiah' ? 'selected' : '' }}>
                                        Artikel Ilmiah</option>
                                    <option value="Bahan Ajar"
                                        {{ old('kategori_publikasi', $ketpub->kategori_publikasi) == 'Bahan Ajar' ? 'selected' : '' }}>
                                        Bahan Ajar</option>
                                    <option value="Monografi"
                                        {{ old('kategori_publikasi', $ketpub->kategori_publikasi) == 'Monografi' ? 'selected' : '' }}>
                                        Monografi</option>
                                    <option value="Buku Referensi"
                                        {{ old('kategori_publikasi', $ketpub->kategori_publikasi) == 'Buku Referensi' ? 'selected' : '' }}>
                                        Buku Referensi</option>
                                    <option value="Prosiding"
                                        {{ old('kategori_publikasi', $ketpub->kategori_publikasi) == 'Prosiding' ? 'selected' : '' }}>
                                        Prosiding</option>
                                    <option value="Laporan Penelitian"
                                        {{ old('kategori_publikasi', $ketpub->kategori_publikasi) == 'Laporan Penelitian' ? 'selected' : '' }}>
                                        Laporan Penelitian</option>
                                    <option value="Review Artikel"
                                        {{ old('kategori_publikasi', $ketpub->kategori_publikasi) == 'Review Artikel' ? 'selected' : '' }}>
                                        Review Artikel</option>
                                    <option value="Bab Buku"
                                        {{ old('kategori_publikasi', $ketpub->kategori_publikasi) == 'Bab Buku' ? 'selected' : '' }}>
                                        Bab Buku</option>
                                    <option value="Modul Pembelajaran"
                                        {{ old('kategori_publikasi', $ketpub->kategori_publikasi) == 'Modul Pembelajaran' ? 'selected' : '' }}>
                                        Modul Pembelajaran</option>
                                    <option value="Karya Ilmiah Populer"
                                        {{ old('kategori_publikasi', $ketpub->kategori_publikasi) == 'Karya Ilmiah Populer' ? 'selected' : '' }}>
                                        Karya Ilmiah Populer</option>
                                    <option value="Manual atau Panduan Teknis"
                                        {{ old('kategori_publikasi', $ketpub->kategori_publikasi) == 'Manual atau Panduan Teknis' ? 'selected' : '' }}>
                                        Manual atau Panduan Teknis</option>
                                    <option value="Hak Kekayaan Intelektual (HKI)"
                                        {{ old('kategori_publikasi', $ketpub->kategori_publikasi) == 'Hak Kekayaan Intelektual (HKI)' ? 'selected' : '' }}>
                                        Hak Kekayaan Intelektual (HKI)</option>
                                </select>

                                <!-- error message untuk kategori_publikasi -->
                                @error('kategori_publikasi')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            {{-- judul --}}
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Judul</label>
                                <input type="text" class="form-control @error('judul') is-invalid @enderror"
                                    name="judul" value="{{ old('judul', $ketpub->judul) }}"
                                    placeholder="Masukkan Judul Artikel Ilmiah">

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
                                    value="{{ old('namaPenerbit', $ketpub->namaPenerbit) }}">
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
                                    name="penerbit" value="{{ old('penerbit', $ketpub->penerbit) }}"
                                    placeholder="Masukkan Penerbit">

                                <!-- error message untuk penerbit -->
                                @error('penerbit')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            {{-- edisi atau jilid --}}
                            <div class="row inventor-row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">Jilid</label>
                                        <input type="number" class="form-control @error('jilid') is-invalid @enderror"
                                            name="jilid" value="{{ old('jilid', $ketpub->jilid) }}"
                                            placeholder="Masukkan Jilid">

                                        <!-- error message untuk jilid -->
                                        @error('jilid')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">Edisi</label>
                                        <input type="text" class="form-control @error('edisi') is-invalid @enderror"
                                            name="edisi" value="{{ old('edisi', $ketpub->edisi) }}"
                                            placeholder="Masukkan Edisi">

                                        <!-- error message untuk edisi -->
                                        @error('edisi')
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
                                            name="bulan" value="{{ old('bulan', $ketpub->bulan) }}"
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
                                            name="tahun" value="{{ old('tahun', $ketpub->tahun) }}"
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
                            {{-- issn --}}
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">ISSN/ISBN</label>
                                <input type="text" class="form-control @error('issn') is-invalid @enderror"
                                    name="issn" value="{{ old('issn', $ketpub->issn) }}"
                                    placeholder="Masukkan ISSN/ISBN">

                                <!-- error message untuk issn -->
                                @error('issn')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div id="penulis-fields">
                                <!-- Inisialisasi penulis dengan data yang ada -->
                                @foreach ($ketpub->penulis as $index => $penulis)
                                    <div class="row penulis-prodi-row mb-3">
                                        <div class="col-md-6">
                                            <label class="font-weight-bold">Nama Penulis Ke-{{ $index + 1 }}</label>
                                            <input type="text" class="form-control"
                                                name="penulis[{{ $index }}][nama]"
                                                value="{{ old('penulis.' . $index . '.nama', $penulis->nama) }}"
                                                placeholder="Masukkan Nama Penulis">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="font-weight-bold">Jurusan/Prodi</label>
                                            <input type="text" class="form-control"
                                                name="penulis[{{ $index }}][prodi]"
                                                value="{{ old('penulis.' . $index . '.prodi', $penulis->jurusan_prodi) }}"
                                                placeholder="Masukkan Jurusan/Prodi">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-success" id="add-penulis-prodi-btn">Tambah
                                Penulis</button>
                            {{-- tanggal --}}
                            <div class="row inventor-row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label hidden class="font-weight-bold">Tanggal Pembuatan</label>
                                        <input hidden type="date"
                                            class="form-control @error('tanggal') is-invalid @enderror" name="tanggal"
                                            rows="5" value="{{ old('tanggal', $today ?? date('Y-m-d')) }}"
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
        let penulisIndex = {{ count($ketpub->penulis) }}; // Mulai dari jumlah penulis yang sudah ada

        document.getElementById('add-penulis-prodi-btn').addEventListener('click', function() {
            // Membuat elemen div baru untuk baris penulis dan prodi
            let newRow = document.createElement('div');
            newRow.classList.add('row', 'penulis-prodi-row', 'mb-3');

            // Isi HTML untuk input nama penulis dan jurusan/prodi
            newRow.innerHTML = `
        <div class="col-md-6">
            <label class="font-weight-bold">Nama Penulis</label>
            <input type="text" class="form-control" name="penulis[${penulisIndex}][nama]" placeholder="Masukkan Nama Penulis">
        </div>
        <div class="col-md-6">
            <label class="font-weight-bold">Jurusan/Prodi</label>
            <input type="text" class="form-control" name="penulis[${penulisIndex}][prodi]" placeholder="Masukkan Jurusan/Prodi">
        </div>
    `;

            // Menambahkan baris input baru ke container penulis
            document.getElementById('penulis-fields').appendChild(newRow);
            penulisIndex++;
        });
    </script>
@endsection
