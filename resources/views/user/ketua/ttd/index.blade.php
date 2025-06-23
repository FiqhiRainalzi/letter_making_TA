@extends('layouts.admin')
@section('content')
    <div class="pagetitle">
        <h1>Tanda Tangan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Tanda Tangan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div>
                    <h3 class="text-center my-1">Upload Tanda Tangan </h3>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label">Tanda Tangan Saat Ini:</label>
                        <div>
                            <img src="{{ asset('storage/' . auth()->user()->ketua->tanda_tangan) }}"
                                alt="Tanda Tangan Ketua" style="max-height: 150px;">
                        </div>
                    </div>
                </div>
                <div class="container mt-4">
                    <form action="{{ route('ketua.uploadTtd') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="tanda_tangan">Upload (PNG/JPG, maks. 2 MB).</label>
                            <input type="file" name="tanda_tangan" class="form-control" required accept="image/*">
                        </div>
                        <button class="btn btn-success">Simpan</button>
                    </form>
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
