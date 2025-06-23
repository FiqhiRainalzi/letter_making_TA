@extends('layouts.admin')

@section('content')
<h3>Data Kode Surat</h3>
<a href="{{ route('kode_surat.create') }}">Tambah Kode</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Kode Instansi</th>
            <th>Kode Layanan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($kodesurat as $kode)
        <tr>
            <td>{{ $kode->id }}</td>
            <td>{{ $kode->kode_instansi }}</td>
            <td>{{ $kode->kode_layanan }}</td>
            <td>
                <a href="{{ route('kode_surat.edit', $kode->id) }}">Edit</a>
                <form action="{{ route('kode_surat.destroy', $kode->id) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" onclick="return confirm('Yakin mau hapus?')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
