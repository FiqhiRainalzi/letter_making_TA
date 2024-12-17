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
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Nama Ketua</label>
                                <input type="text" class="form-control @error('namaKetua') is-invalid @enderror"
                                    name="namaKetua" value="{{ old('namaKetua') }}" placeholder="Masukkan Nama Ketua">

                                <!-- error message untuk title -->
                                @error('namaKetua')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">NIP/NIDN</label>
                                <input class="form-control @error('nipNidn') is-invalid @enderror" name="nipNidn"
                                    rows="5" value="{{ old('nipNidn') }}" placeholder="Masukkan Alamat Pemegang Hak">
                                <!-- error message untuk nipNidn -->
                                @error('nipNidn')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Jabatan Akademik</label>
                                <input type="text" class="form-control @error('jabatanAkademik') is-invalid @enderror"
                                    name="jabatanAkademik" value="{{ old('jabatanAkademik') }}"
                                    placeholder="Masukkan Jabatan Akademik">

                                <!-- error message untuk jabatanAkad -->
                                @error('jabatanAkademik')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Jurusan/Program Studi</label>
                                <input type="text" class="form-control @error('jurusanProdi') is-invalid @enderror"
                                    name="jurusanProdi" value="{{ old('jurusanProdi') }}"
                                    placeholder="Masukkan Jurusan/Program Studi">

                                <!-- error message untuk jurusanProdi -->
                                @error('jurusanProdi')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Anggot dan tenaga pembantu -->
                            <div id="anggota-fields">
                                <div class="row tenaga-row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold">Anggota</label>
                                            <input type="text" class="form-control" name="anggota[0][nama]"
                                                placeholder="Masukkan Nama Anggota">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold">Prodi</label>
                                            <input type="text" class="form-control" name="anggota[0][prodi]"
                                                placeholder="Masukkan Prodi Anggota">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <button type="button" class="btn btn-success mb-3" id="add-anggota-btn">Tambah Anggota</button>

                            <div id="tenaga-fields">
                                <div class="row tenaga-row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold">Tenaga Pembantu</label>
                                            <input type="text" class="form-control" name="tenaga[0][nama]"
                                                placeholder="Masukkan Nama Tenaga Pembantu">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold">Status</label>
                                            <input type="text" class="form-control" name="tenaga[0][status]"
                                                placeholder="Masukkan Status Tenaga Pembantu">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success mb-3" id="add-tenaga-btn">Tambah Tenaga
                                Pembantu</button>

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
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Skim</label>
                                <input type="text" class="form-control @error('skim') is-invalid @enderror"
                                    name="skim" value="{{ old('skim') }}" placeholder="Masukkan Skim penelitian">

                                <!-- error message untuk skim -->
                                @error('skim')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Dasar Pelaksanaan</label>
                                <input type="text" class="form-control @error('dasarPelaksanaan') is-invalid @enderror"
                                    name="dasarPelaksanaan" value="{{ old('dasarPelaksanaan') }}"
                                    placeholder="Masukkan Dasar Pelaksanaan">

                                <!-- error message untuk dasarPelaksanaan -->
                                @error('dasarPelaksanaan')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
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

    <script>
        let anggotaCount = 1;
        const maxAnggota = 10; // Batas maksimal anggota

        document.getElementById('add-anggota-btn').addEventListener('click', function() {
            if (anggotaCount >= maxAnggota) {
                alert("Maksimal hanya dapat menambahkan 10 anggota.");
                return;
            }

            let newAnggotaField = document.createElement('div');
            newAnggotaField.classList.add('row', 'tenaga-row');

            newAnggotaField.innerHTML = `
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="font-weight-bold">Anggota ${anggotaCount + 1}</label>
                    <input type="text" class="form-control" name="anggota[${anggotaCount}][nama]" placeholder="Masukkan Nama Anggota">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="font-weight-bold">Prodi ${anggotaCount + 1}</label>
                    <input type="text" class="form-control" name="anggota[${anggotaCount}][prodi]" placeholder="Masukkan Prodi Anggota">
                </div>
            </div>
            `;

            document.getElementById('anggota-fields').appendChild(newAnggotaField);
            anggotaCount++;

            if (anggotaCount >= maxAnggota) {
                alert("Anda sudah mencapai batas maksimal 10 anggota.");
                document.getElementById('add-anggota-btn').disabled = true;
            }
        });

        let tenagaCount = 1;
        const maxTenaga = 10; // Batas maksimal tenaga pembantu

        document.getElementById('add-tenaga-btn').addEventListener('click', function() {
            if (tenagaCount >= maxTenaga) {
                alert("Maksimal hanya dapat menambahkan 10 tenaga pembantu.");
                return;
            }

            let newTenagaField = document.createElement('div');
            newTenagaField.classList.add('row', 'tenaga-row');

            newTenagaField.innerHTML = `
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Tenaga Pembantu ${tenagaCount + 1}</label>
                        <input type="text" class="form-control" name="tenaga[${tenagaCount}][nama]" placeholder="Masukkan Nama Tenaga Pembantu">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Status ${tenagaCount + 1}</label>
                        <input type="text" class="form-control" name="tenaga[${tenagaCount}][status]" placeholder="Masukkan Status Tenaga Pembantu">
                    </div>
                </div>
            `;

            document.getElementById('tenaga-fields').appendChild(newTenagaField);
            tenagaCount++;

            if (tenagaCount >= maxTenaga) {
                alert("Anda sudah mencapai batas maksimal 10 tenaga pembantu.");
                document.getElementById('add-tenaga-btn').disabled = true;
            }
        });
    </script>
@endsection
