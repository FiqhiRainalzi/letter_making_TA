@extends('layouts.admin')

@section('content')
    <h3>Data Kode Surat</h3>
    <a href="{{ route('kode_surat.create') }}">Tambah Kode</a>

    <form action="{{ isset($kode_surat) ? route('kode_surat.update', $kode_surat->id) : route('kode_surat.store') }}"
        method="POST">
        @csrf
        @if (isset($kode_surat))
            @method('PUT')
        @endif

        <label>Kode Instansi:</label>
        <input type="text" name="kode_instansi" value="{{ old('kode_instansi', $kode_surat->kode_instansi ?? '') }}"
            required>

        <label>Kode Layanan:</label>
        <input type="text" name="kode_layanan" value="{{ old('kode_layanan', $kode_surat->kode_layanan ?? '') }}" required>

        <button type="submit">{{ isset($kode_surat) ? 'Update' : 'Simpan' }}</button>
    </form>
@endsection
