@extends('layouts.admin')
@section('content')
    <div class="pagetitle">
        <h1>Surat HKI</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="">Home</a></li>
                <li class="breadcrumb-item active">HKI</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <form action="{{ route('hki.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Nama Pemegang Hak</label>
                                <input type="text" class="form-control @error('namaPemHki') is-invalid @enderror"
                                    name="namaPemHki" value="" placeholder="Masukkan Nama Pemegang hak">

                                <!-- error message untuk title -->
                                @error('namaPemHki')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Alamat Pemegang Hak</label>
                                <textarea class="form-control @error('alamatPemHki') is-invalid @enderror" name="alamatPemHki"
                                    rows="5" placeholder="Masukkan Alamat Pemegang Hak">{{ old('alamatPemHki') }}</textarea>
                                <!-- error message untuk alamatPemHki -->
                                @error('alamatPemHki')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>
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

                            <div id="inventor-fields">
                                <div class="row inventor-row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold">Nama Inventor 1</label>
                                            <input type="text"
                                                class="form-control @error('namaInventor1') is-invalid @enderror"
                                                name="namaInventor1" value="{{ old('namaInventor1') }}"
                                                placeholder="Masukkan Nama Inventor">

                                            <!-- error message untuk nama_inventor -->
                                            @error('namaInventor1')
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold">Bidang Studi</label>
                                            <input type="text"
                                                class="form-control @error('bidangStudi1') is-invalid @enderror"
                                                name="bidangStudi1" value="{{ old('bidangStudi1') }}"
                                                placeholder="Masukkan Bidang Studi">

                                            <!-- error message untuk bidang_studi -->
                                            @error('bidangStudi1')
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-success mb-3" id="add-inventor-btn">Tambah
                                Inventor</button>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Tanggal Pembuatan</label>
                                <input type="date"
                                    class="form-control @error('tanggalPemHki') is-invalid @enderror"
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
        let inventorCount = 1; // Start with one inventor field set
        const maxInventors = 8; // Set the maximum number of inventors

        document.getElementById('add-inventor-btn').addEventListener('click', function() {
            if (inventorCount < maxInventors) {
                inventorCount++;

                // Create a new row for the inventor and bidang studi
                let newRow = document.createElement('div');
                newRow.classList.add('row', 'inventor-row');

                newRow.innerHTML = `
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Nama Inventor ${inventorCount}</label>
                            <input type="text" class="form-control" name="namaInventor${inventorCount}" placeholder="Masukkan Nama Inventor">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Bidang Studi</label>
                            <input type="text" class="form-control" name="bidangStudi${inventorCount}" placeholder="Masukkan Bidang Studi">
                        </div>
                    </div>
                `;

                // Append the new row to the inventor fields
                document.getElementById('inventor-fields').appendChild(newRow);

                // If we reach the max limit, disable the "Tambah Inventor" button
                if (inventorCount === maxInventors) {
                    document.getElementById('add-inventor-btn').disabled = true;
                }
            }
        });
    </script>
@endsection
