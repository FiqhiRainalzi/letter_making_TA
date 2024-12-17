@extends('layouts.admin')
@section('content')
    <div class="pagetitle">
        <h1>Surat PKM</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Surat PKM</li>
                <li class="breadcrumb-item active">Edit Surat PKM</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <form action="{{ route('admin.pkmUpdate', $pkm->id) }}" method="POST" enctype="multipart/form-data">
                            <a href="{{ url()->previous() }}" class="btn btn-dark mb-3 mt-3">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            @csrf
                            @method('PUT')
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Nomor Surat</label>
                                <input type="hidden" name="dosen_id" value="{{ $pkm->user_id }}">
                                <input type="text" class="form-control @error('nomorSurat') is-invalid @enderror"
                                    name="nomorSurat" value="{{ old('nomorSurat', $pkm->nomorSurat ?? '') }}"
                                    placeholder="Masukkan Nomor Surat">

                                <!-- error message untuk title -->
                                @error('nomorSurat')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="statusSurat">Status</label>
                                <select name="statusSurat" id="statusSurat" class="form-control">
                                    <option value="pending" {{ $pkm->statusSurat == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $pkm->statusSurat == 'approved' ? 'selected' : '' }}>Approved
                                    </option>
                                    <option value="rejected" {{ $pkm->statusSurat == 'rejected' ? 'selected' : '' }}>Rejected
                                    </option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-md btn-primary me-3">SAVE</button>
                            <button type="reset" class="btn btn-md btn-warning">RESET</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
@endsection
