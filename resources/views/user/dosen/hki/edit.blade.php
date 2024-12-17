@extends('layouts.admin')

@section('content')
    <div class="pagetitle">
        <h1>Surat HKI</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('hki.index') }}">Surat HKI</a></li>
                <li class="breadcrumb-item active">Edit Surat HKI</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <form action="{{ route('hki.update', $hki->id) }}" method="POST">
                            <a href="{{ url()->previous() }}" class="btn btn-dark mb-3 mt-3">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            @csrf
                            @method('PUT')

                            <!-- Nama Pemegang Hak -->
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Nama Pemegang Hak</label>
                                <input type="text" class="form-control @error('namaPemHki') is-invalid @enderror"
                                    name="namaPemHki" value="{{ old('namaPemHki', $hki->namaPemHki) }}"
                                    placeholder="Masukkan Nama Pemegang Hak">
                                @error('namaPemHki')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Alamat Pemegang Hak -->
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Alamat Pemegang Hak</label>
                                <textarea class="form-control @error('alamatPemHki') is-invalid @enderror" name="alamatPemHki" rows="5"
                                    placeholder="Masukkan Alamat Pemegang Hak">{{ old('alamatPemHki', $hki->alamatPemHki) }}</textarea>
                                @error('alamatPemHki')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Judul Invensi -->
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Judul Invensi</label>
                                <input type="text" class="form-control @error('judulInvensi') is-invalid @enderror"
                                    name="judulInvensi" value="{{ old('judulInvensi', $hki->judulInvensi) }}"
                                    placeholder="Masukkan Judul Invensi">
                                @error('judulInvensi')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Daftar Inventors -->
                            <div id="inventor-fields">
                                <label class="font-weight-bold">Daftar Inventor</label>
                                @forelse ($hki->inventors as $index => $inventor)
                                    <div class="row inventor-row mb-3">
                                        <div class="col-md-6">
                                            <input type="text"
                                                class="form-control @error('inventors.{{ $index }}.nama') is-invalid @enderror"
                                                name="inventors[{{ $index }}][nama]"
                                                value="{{ old('inventors.' . $index . '.nama', $inventor->nama) }}"
                                                placeholder="Masukkan Nama Inventor">
                                            @error("inventors.{$index}.nama")
                                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text"
                                                class="form-control @error('inventors.{{ $index }}.bidang_studi') is-invalid @enderror"
                                                name="inventors[{{ $index }}][bidang_studi]"
                                                value="{{ old('inventors.' . $index . '.bidang_studi', $inventor->bidang_studi) }}"
                                                placeholder="Masukkan Bidang Studi">
                                            @error("inventors.{$index}.bidang_studi")
                                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @empty
                                    <div class="alert alert-warning">Belum ada inventor terdaftar.</div>
                                @endforelse
                            </div>

                            <!-- Tambah Inventor Baru -->
                            <button type="button" class="btn btn-sm btn-success mb-3" id="add-inventor-btn">+ Tambah
                                Inventor</button>

                            <!-- Tanggal Pembuatan -->
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Tanggal Pembuatan</label>
                                <input type="date" class="form-control @error('tanggalPemHki') is-invalid @enderror"
                                    name="tanggalPemHki" value="{{ old('tanggalPemHki', $hki->tanggalPemHki) }}">
                                @error('tanggalPemHki')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit -->
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
                // Menampilkan alert jika sudah mencapai batas
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
