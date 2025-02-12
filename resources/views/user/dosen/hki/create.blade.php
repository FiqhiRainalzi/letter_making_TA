@extends('layouts.admin')
@section('content')
    <div class="pagetitle">
        <h1>Membuat Surat HKI</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">HKI</li>
                <li class="breadcrumb-item active">Membuat Surat HKI</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <form action="{{ route('hki.store') }}" method="POST" enctype="multipart/form-data">
                            <a href="{{ url()->previous() }}" class="btn btn-dark mb-3 mt-3">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            @csrf

                            {{-- nama pemegang hak --}}
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Nama Pemegang Hak</label>
                                <input type="text" class="form-control @error('namaPemegang') is-invalid @enderror"
                                    name="namaPemegang" value="{{ old('namaPemegang') }}"
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
                                    placeholder="Masukkan Alamat Pemegang Hak">{{ old('alamat') }}</textarea>
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
                                    name="judul" value="{{ old('judul') }}" placeholder="Masukkan Judul Invensi">

                                <!-- error message untuk judul -->
                                @error('judul')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Inventor 1 (Default Tampil) --}}
                            <div class="row inventor-row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">Nama Inventor 1</label>
                                        <input type="text" class="form-control @error('inventor1') is-invalid @enderror"
                                            name="inventor1" value="{{ old('inventor1') }}"
                                            placeholder="Masukkan Nama Inventor 1">
                                        <!-- error message untuk inventor1 -->
                                        @error('inventor1')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">Bidang Studi 1</label>
                                        <input type="text"
                                            class="form-control @error('bidangStudi1') is-invalid @enderror"
                                            name="bidangStudi1" value="{{ old('bidangStudi1') }}"
                                            placeholder="Masukkan Bidang Studi 1">
                                        <!-- error message untuk bidangStudi1 -->
                                        @error('bidangStudi1')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Tombol Tampilkan Semua Inventor --}}
                            <button type="button" id="tampilkanSemuaInventor" class="btn btn-info mb-3">
                                <i class="bi bi-plus-circle"></i> Tampilkan Semua Inventor
                            </button>

                            {{-- Inventor 2 sampai 10 (Awalnya Disembunyikan) --}}
                            @for ($i = 2; $i <= 10; $i++)
                                <div class="row inventor-row" id="inventor{{ $i }}" style="display: none;">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold">Nama Inventor {{ $i }}</label>
                                            <input type="text"
                                                class="form-control @error('inventor' . $i) is-invalid @enderror"
                                                name="inventor{{ $i }}" value="{{ old('inventor' . $i) }}"
                                                placeholder="Masukkan Nama Inventor {{ $i }}">
                                            <!-- error message untuk inventor -->
                                            @error('inventor' . $i)
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold">Bidang Studi {{ $i }}</label>
                                            <input type="text"
                                                class="form-control @error('bidangStudi' . $i) is-invalid @enderror"
                                                name="bidangStudi{{ $i }}"
                                                value="{{ old('bidangStudi' . $i) }}"
                                                placeholder="Masukkan Bidang Studi {{ $i }}">
                                            <!-- error message untuk bidangStudi -->
                                            @error('bidangStudi' . $i)
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endfor
                            {{-- ENDS Inventor --}}

                            {{-- tanggal --}}
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Tanggal Pembuatan</label>
                                <input type="date" class="form-control @error('tanggal') is-invalid @enderror"
                                    name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}">
                                <!-- error message untuk tanggal -->
                                @error('tanggal')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-md btn-primary me-3">SIMPAN</button>
                            <button type="reset" class="btn btn-md btn-warning">RESET</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript untuk Menampilkan Semua Inventor -->
    <script>
        document.getElementById('tampilkanSemuaInventor').addEventListener('click', function() {
            // Tampilkan semua inventor (2 sampai 10)
            for (let i = 2; i <= 10; i++) {
                document.getElementById('inventor' + i).style.display = 'flex';
            }
            // Sembunyikan tombol "Tampilkan Semua Inventor"
            this.style.display = 'none';
        });
    </script>
@endsection
