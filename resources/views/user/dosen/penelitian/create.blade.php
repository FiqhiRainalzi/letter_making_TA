@extends('layouts.admin')
@section('content')
    <div class="pagetitle">
        <h1>Surat Tugas Penelitian</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Surat Tugas Penelitan</li>
                <li class="breadcrumb-item active">Membuat Surat Tugas Penelitan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <form action="{{ route('penelitian.store') }}" method="POST" enctype="multipart/form-data">
                            <a href="{{ url()->previous() }}" class="btn btn-dark mb-3 mt-3">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">Nama Ketua</label>
                                        <input type="text" class="form-control @error('namaKetua') is-invalid @enderror"
                                            name="namaKetua" value="{{ old('namaKetua') }}"
                                            placeholder="Masukkan Nama Ketua">

                                        <!-- error message untuk title -->
                                        @error('namaKetua')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">NIP/NIDN</label>
                                        <input class="form-control @error('nipNidn') is-invalid @enderror" name="nipNidn"
                                            rows="5" value="{{ old('nipNidn') }}"
                                            placeholder="Masukkan Alamat Pemegang Hak">
                                        <!-- error message untuk nipNidn -->
                                        @error('nipNidn')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">Jabatan Akademik</label>
                                        <input type="text"
                                            class="form-control @error('jabatanAkademik') is-invalid @enderror"
                                            name="jabatanAkademik" value="{{ old('jabatanAkademik') }}"
                                            placeholder="Masukkan Jabatan Akademik">

                                        <!-- error message untuk jabatanAkad -->
                                        @error('jabatanAkademik')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">Jurusan/Program Studi</label>
                                        <input type="text"
                                            class="form-control @error('jurusanProdi') is-invalid @enderror"
                                            name="jurusanProdi" value="{{ old('jurusanProdi') }}"
                                            placeholder="Masukkan Jabatan Akademik">
                                        {{-- <select class="form-control @error('jurusanProdi') is-invalid @enderror"
                                            name="jurusanProdi"> --}}
                                        {{-- <option value="">-- Pilih Jurusan/Prodi --</option>
                                            @foreach ($prodis as $prodi)
                                                <option value="{{ $prodi->id }}"
                                                    {{ old('jurusanProdi') == $prodi->id ? 'selected' : '' }}>
                                                    {{ $prodi->nama }}
                                                </option>
                                            @endforeach --}}
                                        </select>

                                        @error('jurusanProdi')
                                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Anggota Penelitian -->
                            <div class="card mb-4">
                                <div class="card-header">Anggota Penelitian (Maksimal 10)</div>
                                <div class="card-body">
                                    <div id="anggota-fields">
                                        @for ($i = 0; $i < 10; $i++)
                                            <div class="row mb-3 anggota-form mb-3 @if ($i >= 3) d-none @endif"
                                                id="anggota-{{ $i }}">
                                                <div class="col-md-6">
                                                    <label for="anggota[{{ $i }}][nama]">Nama Anggota
                                                        {{ $i + 1 }}</label>
                                                    <input type="text" name="anggota[{{ $i }}][nama]"
                                                        class="form-control" value="{{ old("anggota.$i.nama") }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="anggota[{{ $i }}][prodi_id]">Prodi</label>
                                                    <select name="anggota[{{ $i }}][prodi_id]"
                                                        class="form-control">
                                                        <option value="">-- Pilih Prodi --</option>
                                                        @foreach ($prodis as $prodi)
                                                            <option value="{{ $prodi->id }}"
                                                                {{ old("anggota.$i.prodi_id") == $prodi->id ? 'selected' : '' }}>
                                                                {{ $prodi->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                    <button type="button" class="btn btn-secondary" id="tampilkan-semua-anggota">Tampilkan
                                        Semua Anggota</button>
                                </div>
                            </div>

                            <!-- Tenaga Pembantu -->
                            <div class="card mb-4">
                                <div class="card-header">Tenaga Pembantu (Maksimal 10)</div>
                                <div class="card-body">
                                    <div id="tenaga-fields">
                                        @for ($i = 0; $i < 10; $i++)
                                            <div class="row mb-3 tenaga-form mb-3 @if ($i >= 3) d-none @endif"
                                                id="tenaga-{{ $i }}">
                                                <div class="col-md-6">
                                                    <label for="tenaga_pembantu[{{ $i }}][nama]">Nama Tenaga
                                                        Pembantu {{ $i + 1 }}</label>
                                                    <input type="text" name="tenaga_pembantu[{{ $i }}][nama]"
                                                        class="form-control" value="{{ old("tenaga_pembantu.$i.nama") }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label
                                                        for="tenaga_pembantu[{{ $i }}][prodi_id]">Prodi</label>
                                                    <select name="tenaga_pembantu[{{ $i }}][prodi_id]"
                                                        class="form-control">
                                                        <option value="">-- Pilih Prodi --</option>
                                                        @foreach ($prodis as $prodi)
                                                            <option value="{{ $prodi->id }}"
                                                                {{ old("anggota.$i.prodi_id") == $prodi->id ? 'selected' : '' }}>
                                                                {{ $prodi->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                    <button type="button" class="btn btn-secondary" id="tampilkan-semua-tenaga">Tampilkan
                                        Semua Tenaga Pembantu</button>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Judul</label>
                                <input type="text" class="form-control @error('judul') is-invalid @enderror"
                                    name="judul" value="{{ old('judul') }}" placeholder="Masukkan Judul Penelitian">

                                <!-- error message untuk judul -->
                                @error('judul')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">Skim</label>
                                        <input type="text" class="form-control @error('skim') is-invalid @enderror"
                                            name="skim" value="{{ old('skim') }}"
                                            placeholder="Masukkan Skim penelitian">

                                        <!-- error message untuk skim -->
                                        @error('skim')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">Dasar Pelaksanaan</label>
                                        <input type="text"
                                            class="form-control @error('dasarPelaksanaan') is-invalid @enderror"
                                            name="dasarPelaksanaan" value="{{ old('dasarPelaksanaan') }}"
                                            placeholder="Masukkan Dasar Pelaksanaan">

                                        <!-- error message untuk dasarPelaksanaan -->
                                        @error('dasarPelaksanaan')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Lokasi</label>
                                <input type="text" class="form-control @error('lokasi') is-invalid @enderror"
                                    name="lokasi" value="{{ old('lokasi') }}" placeholder="Masukkan Lokasi Penelitian">

                                <!-- error message untuk lokasi -->
                                @error('lokasi')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            {{-- bulan pelaksanaan --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">Bulan Pelaksanaan</label>
                                        <input type="date"
                                            class="form-control @error('bulanPelaksanaan') is-invalid @enderror"
                                            name="bulanPelaksanaan" value="{{ old('bulanPelaksanaan') }}"
                                            placeholder="Masukkan Tanggal Bulan Pelaksanaan">

                                        <!-- error message untuk bulanPelaksanaan -->
                                        @error('bulanPelaksanaan')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">Bulan Akhir Pelaksanaan</label>
                                        <input type="date"
                                            class="form-control @error('bulanAkhirPelaksanaan') is-invalid @enderror"
                                            name="bulanAkhirPelaksanaan" value="{{ old('bulanAkhirPelaksanaan') }}"
                                            placeholder="Masukkan Tanggal Bulan Akhir Pelaksanaan">

                                        <!-- error message untuk bulanPelaksanaan -->
                                        @error('bulanAkhirPelaksanaan')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            {{-- tanggal --}}
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Tanggal Pembuatan Surat</label>
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
    </div>
    </div>
    <script>
        // Script untuk menampilkan semua input anggota
        document.getElementById('tampilkan-semua-anggota').addEventListener('click', function() {
            for (let i = 3; i < 10; i++) {
                document.getElementById(`anggota-${i}`).classList.remove('d-none');
            }
            this.style.display = 'none'; // Sembunyikan tombol setelah diklik
        });

        // Script untuk menampilkan semua input tenaga pembantu
        document.getElementById('tampilkan-semua-tenaga').addEventListener('click', function() {
            for (let i = 3; i < 10; i++) {
                document.getElementById(`tenaga-${i}`).classList.remove('d-none');
            }
            this.style.display = 'none'; // Sembunyikan tombol setelah diklik
        });
    </script>

@endsection
