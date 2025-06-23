@extends('layouts.admin')

@section('content')
    <div class="pagetitle">
        <h1>Riwayat</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Riwayat Aktifitas</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div>
                    <h3 class="text-center my-1">{{ $title }}</h3>
                    <hr>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form id="deleteAllForm" action="{{ route('riwayat.destroyAll') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDeleteAll()" class="btn btn-danger mb-3 mt-1">Hapus Semua
                                Riwayat</button>
                        </form>
                        </tr>
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Waktu</th>
                                    <th>aktifitas</th>
                                    <th>Nomor Surat</th>
                                    <th>Catatan</th>
                                    <th>aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayats as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->waktu_perubahan)->format('d M Y H:i') }}</td>
                                        <td>{{ $item->aksi }}</td>
                                        <td class="text-center">
                                            {{ $item->ajuanSurat->getSurat()?->nomor_surat ?? 'belum diberi nomor' }}
                                        </td>
                                        <td>{{ $item->catatan }}</td>
                                        <td>
                                            <form id="deleteForm-{{ $item->id }}"
                                                action="{{ route('riwayat.destroy', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete({{ $item->id }})"
                                                    class="btn btn-sm btn-danger"><i class="bi bi-trash3"></i></button>
                                            </form>
                                        </td>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada aktivitas yang tercatat</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Riwayat ini akan dihapus permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`deleteForm-${id}`).submit();
            }
        });
    }

    function confirmDeleteAll() {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Semua riwayat akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus semua!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteAllForm').submit();
            }
        });
    }
</script>
