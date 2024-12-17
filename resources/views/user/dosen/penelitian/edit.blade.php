@extends('layouts.admin')
@section('content')
    <div class="pagetitle">
        <h1>Surat Penelitian</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Surat Penelitian</li>
                <li class="breadcrumb-item active">Edit Surat Penelitian</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <form action="{{ route('penelitian.update', $penelitian->id) }}" method="POST"
                            enctype="multipart/form-data">
                            <a href="{{ url()->previous() }}" class="btn btn-dark mb-3 mt-3">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            @csrf
                            @method('PUT')
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Nama Ketua</label>
                                <input type="text" class="form-control @error('namaKetua') is-invalid @enderror"
                                    name="namaKetua" value="{{ old('namaKetua', $penelitian->namaKetua ?? '') }}"
                                    placeholder="Masukkan Nama Ketua">

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
                                    rows="5" value="{{ old('nipNidn', $penelitian->nipNidn ?? '') }}"
                                    placeholder="Masukkan Alamat Pemegang Hak">
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
                                    name="jabatanAkademik"
                                    value="{{ old('jabatanAkademik', $penelitian->jabatanAkademik ?? '') }}"
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
                                    name="jurusanProdi" value="{{ old('jurusanProdi', $penelitian->jurusanProdi ?? '') }}"
                                    placeholder="Masukkan Jurusan/Program Studi">

                                <!-- error message untuk jurusanProdi -->
                                @error('jurusanProdi')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Anggota Section -->
                            <div id="anggota-fields">
                                @foreach ($penelitian->anggota as $index => $anggota)
                                    <div class="row anggota-row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="font-weight-bold">Anggota</label>
                                                <input type="hidden" name="anggota[{{ $index }}][id]"
                                                    value="{{ $anggota->id }}">
                                                <input type="text" class="form-control"
                                                    name="anggota[{{ $index }}][nama]"
                                                    value="{{ old('anggota.' . $index . '.nama', $anggota->nama ?? '') }}"
                                                    placeholder="Masukkan Nama Anggota">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="font-weight-bold">Prodi</label>
                                                <input type="text" class="form-control"
                                                    name="anggota[{{ $index }}][prodi]"
                                                    value="{{ old('anggota.' . $index . '.prodi', $anggota->prodi ?? '') }}"
                                                    placeholder="Masukkan Prodi Anggota">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" class="btn btn-success mb-3" id="add-anggota-btn">Tambah Anggota</button>

                            <!-- Tenaga Pembantu Section -->
                            <div id="tenaga-fields">
                                @foreach ($penelitian->tenagaPembantu as $index => $tenaga)
                                    <div class="row tenaga-row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="font-weight-bold">Tenaga Pembantu</label>
                                                <input type="hidden" name="tenagaPembantu[{{ $index }}][id]"
                                                    value="{{ $tenaga->id }}">
                                                <input type="text" class="form-control"
                                                    name="tenagaPembantu[{{ $index }}][nama]"
                                                    value="{{ old('tenagaPembantu.' . $index . '.nama', $tenaga->nama ?? '') }}"
                                                    placeholder="Masukkan Nama Tenaga Pembantu">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="font-weight-bold">Status</label>
                                                <input type="text" class="form-control"
                                                    name="tenagaPembantu[{{ $index }}][status]"
                                                    value="{{ old('tenagaPembantu.' . $index . '.status', $tenaga->status ?? '') }}"
                                                    placeholder="Masukkan Status Tenaga Pembantu">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" class="btn btn-success mb-3" id="add-tenaga-btn">Tambah Tenaga
                                Pembantu</button>



                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Judul</label>
                                <input type="text" class="form-control @error('judul') is-invalid @enderror"
                                    name="judul" value="{{ old('judul', $penelitian->judul ?? '') }}"
                                    placeholder="Masukkan Judul Penelitian">

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
                                    name="skim" value="{{ old('skim', $penelitian->skim ?? '') }}"
                                    placeholder="Masukkan Skim penelitian">

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
                                    name="dasarPelaksanaan"
                                    value="{{ old('dasarPelaksanaan', $penelitian->dasarPelaksanaan ?? '') }}"
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
                                    name="lokasi" value="{{ old('lokasi', $penelitian->lokasi ?? '') }}"
                                    placeholder="Masukkan Lokasi Penelitian">

                                <!-- error message untuk lokasi -->
                                @error('lokasi')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">Bulan Pelaksanaan</label>
                                        <input type="date"
                                            class="form-control @error('bulanPelaksanaan') is-invalid @enderror"
                                            name="bulanPelaksanaan"
                                            value="{{ old('bulanPelaksanaan', $penelitian->bulanPelaksanaan ?? '') }}"
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
                                            name="bulanAkhirPelaksanaan"
                                            value="{{ old('bulanAkhirPelaksanaan', $penelitian->bulanAkhirPelaksanaan ?? '') }}"
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

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Tanggal Pembuatan Surat</label>
                                <input type="date" class="form-control @error('tanggal') is-invalid @enderror"
                                    name="tanggal" rows="5"
                                    value="{{ old('tanggal', $today ?? (date('Y-m-d') ?? '')) }}"
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
        let anggotaCount = {{ count($penelitian->anggota) }};
        const maxAnggota = 10; // Batas maksimal anggota

        document.getElementById('add-anggota-btn').addEventListener('click', function() {
            if (anggotaCount >= maxAnggota) {
                alert("Maksimal hanya dapat menambahkan 10 anggota.");
                return;
            }

            let newAnggotaField = document.createElement('div');
            newAnggotaField.classList.add('row', 'anggota-row');

            newAnggotaField.innerHTML = `
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Anggota</label>
                        <input type="hidden" name="anggota[${anggotaCount}][id]" value="">
                        <input type="text" class="form-control" name="anggota[${anggotaCount}][nama]" placeholder="Masukkan Nama Anggota">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Prodi</label>
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

        let tenagaCount = {{ count($penelitian->tenagaPembantu) }};
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
                        <label class="font-weight-bold">Tenaga Pembantu</label>
                        <input type="hidden" name="tenagaPembantu[${tenagaCount}][id]" value="">
                        <input type="text" class="form-control" name="tenagaPembantu[${tenagaCount}][nama]" placeholder="Masukkan Nama Tenaga Pembantu">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Status</label>
                        <input type="text" class="form-control" name="tenagaPembantu[${tenagaCount}][status]" placeholder="Masukkan Status Tenaga Pembantu">
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
