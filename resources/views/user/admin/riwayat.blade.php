@extends('layouts.admin')

@section('content')
    <div class="pagetitle">
        <h1>Riwayat</h1>
    </div>

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
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Waktu</th>
                                    <th>Aktivitas</th>
                                    <th>Nomor Surat</th>
                                    <th>Catatan</th>
                                    <th>aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayats as $index => $riwayat)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($riwayat->waktu_perubahan)->format('d M Y H:i') }}</td>
                                        <td>{{ $riwayat->aksi }}</td>
                                        <td>
                                            {{ optional($riwayat->ajuanSurat?->getSurat())->nomorSurat ?? 'belum diberi nomor' }}
                                        </td>
                                        <td>{{ $riwayat->catatan }}</td>
                                        <td>
                                            <form id="deleteForm-{{ $riwayat->id }}"
                                                action="{{ route('riwayat.destroy', $riwayat->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete({{ $riwayat->id }})"
                                                    class="btn btn-sm btn-danger"><i class="bi bi-trash3"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada riwayat aktivitas</td>
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
