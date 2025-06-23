@extends('layouts.admin')
@section('content')
    <div class="pagetitle">
        <h1>Surat Keterangan Publikasi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Surat Keterangan Publikasi</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div>
                    <h3 class="text-center my-1">Data Surat Keterangan Publikasi</h3>
                    <hr>
                </div>
                <div class="card">
                    <div class="card-body">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Tanggal Pembuatan</th>
                                    <th scope="col">Penulis</th>
                                    <th scope="col">Judul</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Lama Proses (hari)</th>
                                    <th scope="col" style="width: 20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ketpub as $h)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $h->created_at->translatedFormat('d F Y') }}</td>
                                        <td>{{ $h->penulis->first()->nama }}</td>
                                        <td>{{ $h->judul }}</td>
                                        <td>
                                            @if ($h->statusSurat == 'draft')
                                                <span class="badge bg-secondary">Draf</span>
                                            @elseif ($h->statusSurat == 'approved')
                                                <span class="badge bg-success">Diterima</span>
                                            @elseif ($h->statusSurat == 'ready_for_pickup')
                                                <span class="badge bg-warning">Siap Diambil</span>
                                            @elseif ($h->statusSurat == 'picked_up')
                                                <span class="badge bg-success">Sudah Diambil</span>
                                            @elseif ($h->statusSurat == 'rejected')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{ floor($h->lama_proses) }} hari
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.ketpubShow', $h->id) }}" class="btn btn-sm btn-dark"><i
                                                    class="bi bi-file-earmark-word"></i></a>
                                            <a href="{{ route('admin.ketpubEdit', $h->id) }}"
                                                class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
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
                        position: 'top-end', // Mengatur posisi di pojok atas kanan
                        showConfirmButton: false, // Tidak menampilkan tombol OK
                        timer: 3000, // Notifikasi akan hilang otomatis dalam 3 detik
                        timerProgressBar: true, // Menampilkan progress bar saat hitung mundur
                        toast: true // Menyesuaikan tampilan menjadi kecil seperti toast
                    });
                });
            </script>
        @endif
    @endsection

@endsection
