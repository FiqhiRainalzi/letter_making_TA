@extends('layouts.admin')
@section('content')
    <div class="pagetitle">
        <h1>Surat PKM</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Surat PKM</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div>
                    <h3 class="text-center my-1">Data Surat PKM</h3>
                    <hr>
                </div>
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('pkm.create') }}" class="mt-2 btn btn-md btn btn-dark mb-3">Buat Surat PKM</a>
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Ketua</th>
                                    <th scope="col">Judul</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" style="width: 20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pkm as $p)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $p->namaKetua }}</td>
                                        <td>{{ $p->judul }}</td>
                                        <td>
                                            @if ($p->statusSurat == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($p->statusSurat == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($p->statusSurat == 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <form id="deleteForm-{{ $p->id }}"
                                                action="{{ route('pkm.destroy', $p->id) }}" method="POST">
                                                <a href="{{ route('pkm.show', $p->id) }}" class="btn btn-sm btn-dark"><i
                                                        class="bi bi-file-earmark-word"></i></a>
                                                <a href="{{ route('pkm.edit', $p->id) }}" class="btn btn-sm btn-primary"><i
                                                        class="bi bi-pencil"></i></a>
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="confirmDelete({{ $p->id }})" type="button"
                                                    class="btn btn-sm btn-danger"><i class="bi bi-trash3"></i></button>
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
                            {{-- {{ $pkm->links() }} --}}
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
