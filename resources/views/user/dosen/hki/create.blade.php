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

                            <!-- Inveotor HKI -->
                            <div class="card mb-4">
                                <div class="card-header">Inventor (Maksimal 10)</div>
                                <div class="card-body">
                                    <div id="inventor-fields">
                                        @for ($i = 0; $i < 10; $i++)
                                            <div class="row mb-3 inventor-form mb-3 @if ($i >= 3) d-none @endif"
                                                id="inventor-{{ $i }}">
                                                <div class="col-md-6">
                                                    <label for="inventor[{{ $i }}][nama]">Nama inventor
                                                        {{ $i + 1 }}</label>
                                                    <input type="text" name="inventor[{{ $i }}][nama]"
                                                        class="form-control" value="{{ old("inventor.$i.nama") }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="inventor[{{ $i }}][prodi_id]">Prodi</label>
                                                    <select name="inventor[{{ $i }}][prodi_id]"
                                                        class="form-control">
                                                        <option value="">-- Pilih Prodi --</option>
                                                        @foreach ($prodis as $prodi)
                                                            <option value="{{ $prodi->id }}"
                                                                {{ old("inventor.$i.prodi_id") == $prodi->id ? 'selected' : '' }}>
                                                                {{ $prodi->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                    <button type="button" class="btn btn-secondary" id="tampilkan-semua-inventor">Tampilkan
                                        Semua Inventor</button>
                                </div>
                            </div>


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

    <script>
        // Script untuk menampilkan semua input inventor
        document.getElementById('tampilkan-semua-inventor').addEventListener('click', function() {
            for (let i = 3; i < 10; i++) {
                document.getElementById(`inventor-${i}`).classList.remove('d-none');
            }
            this.style.display = 'none'; // Sembunyikan tombol setelah diklik
        });
    </script>
@endsection
