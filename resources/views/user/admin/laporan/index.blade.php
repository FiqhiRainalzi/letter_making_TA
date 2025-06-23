@extends('layouts.admin')

@section('content')
    <div class="pagetitle">
        <h1>Filter & Cetak Laporan</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('laporan.cetak') }}" method="GET" target="_blank">
                <div class="row">
                    <div class="col-md-3">
                        <label>Jenis Surat</label>
                        <select name="jenis" class="form-control">
                            <option value="">Semua</option>
                            <option value="penelitian" {{ request('jenis') == 'penelitian' ? 'selected' : '' }}>Penelitian
                            </option>
                            <option value="hki" {{ request('jenis') == 'hki' ? 'selected' : '' }}>HKI</option>
                            <option value="pkm" {{ request('jenis') == 'pkm' ? 'selected' : '' }}>PKM</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="">Semua</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="sudah ditandatangani">Sudah Ditandatangani</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Dari Tanggal</label>
                        <input type="date" name="dari" class="form-control" value="{{ request('dari') }}">
                    </div>
                    <div class="col-md-3">
                        <label>Sampai Tanggal</label>
                        <input type="date" name="sampai" class="form-control">
                    </div>
                    <div class="col-md-12 mt-2">
                        <label>Catatan Laporan</label>
                        <textarea name="catatan" rows="2" class="form-control" placeholder="Tuliskan keterangan sebelum laporan dicetak">{{ request('catatan') }}</textarea>
                    </div>
                    <div class="col-md-12 mt-3">
                        <button type="submit" class="btn btn-success"><i class="bi bi-printer"></i> Cetak Laporan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
