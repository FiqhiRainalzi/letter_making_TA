@extends('layouts.admin')
@section('content')
    <div class="pagetitle">
        <h1>Surat HKI</h1>
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
                                <input type="text" class="form-control @error('namaPemHki') is-invalid @enderror"
                                    name="namaPemHki" value="{{ old('namaPemHki') }}"
                                    placeholder="Masukkan Nama Pemegang hak">

                                <!-- error message untuk title -->
                                @error('namaPemHki')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            {{-- alamat --}}
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Alamat Pemegang Hak</label>
                                <textarea class="form-control @error('alamatPemHki') is-invalid @enderror" name="alamatPemHki" rows="5"
                                    placeholder="Masukkan Alamat Pemegang Hak">{{ old('alamatPemHki') }}</textarea>
                                <!-- error message untuk alamatPemHki -->
                                @error('alamatPemHki')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>
                            {{-- judul --}}
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Judul Invensi</label>
                                <input type="text" class="form-control @error('judulInvensi') is-invalid @enderror"
                                    name="judulInvensi" value="{{ old('judulInvensi') }}"
                                    placeholder="Masukkan Judul Invensi">

                                <!-- error message untuk judulInvensi -->
                                @error('judulInvensi')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            {{-- inventors --}}
                            <div id="inventor-fields">
                                @if (old('inventors'))
                                    @foreach (old('inventors') as $index => $inventor)
                                        <div class="row inventor-row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="font-weight-bold">Nama Inventor</label>
                                                    <input value="{{ $inventor['nama'] }}" type="text"
                                                        class="form-control" name="inventors[{{ $index }}][nama]"
                                                        placeholder="Masukkan Nama Inventor">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="font-weight-bold">Bidang Studi</label>
                                                    <input value="{{ $inventor['bidang_studi'] }}" type="text"
                                                        class="form-control"
                                                        name="inventors[{{ $index }}][bidang_studi]"
                                                        placeholder="Masukkan Bidang Studi">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <!-- Field default jika old() kosong -->
                                    <div class="row inventor-row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="font-weight-bold">Nama Inventor</label>
                                                <input value="" type="text" class="form-control"
                                                    name="inventors[0][nama]" placeholder="Masukkan Nama Inventor">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="font-weight-bold">Bidang Studi</label>
                                                <input value="" type="text" class="form-control"
                                                    name="inventors[0][bidang_studi]" placeholder="Masukkan Bidang Studi">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <button type="button" class="btn btn-success mb-3" id="add-inventor-btn">Tambah
                                Inventor</button>
                            {{-- tanggal --}}
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Tanggal Pembuatan</label>
                                <input type="date" class="form-control @error('tanggalPemHki') is-invalid @enderror"
                                    name="tanggalPemHki" rows="5"
                                    value="{{ old('tanggalPemHki', $today ?? date('Y-m-d')) }}"
                                    placeholder="Masukkan Alamat Pemegang Hak">
                                <!-- error message untuk tanggalPemHki -->
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

    <script>
        let inventorIndex = 1; // Indeks awal
        const maxInventors = 10; // Batas maksimal penambahan

        document.getElementById('add-inventor-btn').addEventListener('click', function() {
            // Periksa apakah sudah mencapai batas maksimal
            if (inventorIndex >= maxInventors) {
                alert("Maksimal hanya dapat menambahkan 10 inventor.");
                return;
            }

            let newRow = document.createElement('div');
            newRow.classList.add('row', 'inventor-row');

            newRow.innerHTML = `
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Nama Inventor</label>
                        <input type="text" class="form-control" name="inventors[${inventorIndex}][nama]" placeholder="Masukkan Nama Inventor">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Bidang Studi</label>
                        <input type="text" class="form-control" name="inventors[${inventorIndex}][bidang_studi]" placeholder="Masukkan Bidang Studi">
                    </div>
                </div>
            `;

            document.getElementById('inventor-fields').appendChild(newRow);
            inventorIndex++;

            // Nonaktifkan tombol jika sudah mencapai batas
            if (inventorIndex >= maxInventors) {
                alert("Anda sudah mencapai batas maksimal 10 inventor. Tidak bisa menambah lagi.");
                document.getElementById('add-inventor-btn').disabled = true;
            }
        });
    </script>

@endsection
