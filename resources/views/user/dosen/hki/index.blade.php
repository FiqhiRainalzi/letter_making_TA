@extends('layouts.admin')
@section('content')
    <div class="pagetitle">
        <h1>Surat HKI</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Surat HKI</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div>
                    <h3 class="text-center my-1">Data Surat HKI</h3>
                    <hr>
                </div>
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <a href="{{ route('hki.create') }}" class="mt-2 btn btn-md btn btn-dark mb-3">Buat Surat HKI</a>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Inventor</th>
                                    <th scope="col">Judul Invensi</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" style="width: 20%">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($hki as $h)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $h->namaInventor1 }}</td>
                                        <td>{{ $h->judulInvensi }}</td>
                                        <td></td>
                                        <td class="text-center">
                                            <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                                                action="{{ route('hki.destroy', $h->id) }}" method="POST">
                                                <a href="{{ route('hki.show', $h->id) }}"
                                                    class="btn btn-sm btn-dark">SHOW</a>
                                                <a href="{{ route('hki.edit', $h->id) }}"
                                                    class="btn btn-sm btn-primary">EDIT</a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">HAPUS</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Data belum tersedia.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end">
                            <!-- Pagination or additional content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif
@endsection


@endsection
