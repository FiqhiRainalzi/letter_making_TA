@extends('layouts.admin')
@section('content')
    <div class="pagetitle">
        <h1>Ajuan Surat</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Ajuan Surat</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div>
                    <h3 class="text-center my-1">Data Ajuan Surat</h3>
                    <hr>
                </div>
                <div class="card">
                    <div class="card-body">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>Jenis Surat</th>
                                    <th>Judul</th>
                                    <th>Nomor Surat</th>
                                    <th>Dosen</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($surats as $surat)
                                    <tr>
                                        <td>{{ $surat->jenis }}</td>
                                        <td>{{ $surat->judul }}</td>
                                        <td>{{ $surat->nomor_surat }}</td>
                                        <td>{{ $surat->dosen }}</td>
                                        <td>{{ $surat->created_at->format('d M Y') }}</td>
                                        <td>
                                            @if ($surat->status == 'disetujui')
                                                <form
                                                    action="{{ route('ketua.tandatangani', ['jenis' => strtolower($surat->jenis), 'id' => $surat->id]) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin ingin menandatangani surat ini?')">
                                                    @csrf
                                                    <button class="btn btn-sm btn-primary">Tandatangani</button>
                                                </form>
                                            @else
                                                <span class="badge bg-success">Sudah Ditandatangani</span>
                                            @endif
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">Tidak ada surat yang disetujui.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{-- <div class="d-flex justify-content-end">
                            <!-- Pagination or additional content -->
                            {{ $hki->links() }}
                        </div> --}}
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
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim form dengan ID sesuai
                    document.getElementById(`deleteForm-${id}`).submit();
                }
            });
        }
    </script>

@endsection
