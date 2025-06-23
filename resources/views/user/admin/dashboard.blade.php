@extends('layouts.admin')
@section('content')
    <section class="section dashboard">
        <style>
            #categoryChart {
                max-height: 400px;
            }
        </style>

        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-12">
                <div class="row">
                    <!-- HKI Card 1 -->
                    <div class="col-xxl-4 col-md-4">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Surat HKI</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $countHki }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End HKI Card 1 -->

                    <!-- Penelitian Card 2 -->
                    <div class="col-xxl-4 col-md-4">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Surat Tugas Penelitian</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $countPenelitian }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Penelitian Card 2 -->

                    <!-- PKM Card 3 -->
                    <div class="col-xxl-4 col-md-4">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Surat Tugas PKM</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $countPkm }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End PKM Card 3 -->

                    <!-- KEtPub Card 4 -->
                    <div class="col-xxl-4 col-md-4">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Surat Keterangan Publikasi</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $countKetPub }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End KetPub Card 4 -->

                    <!-- TugasPub Card 5 -->
                    <div class="col-xxl-4 col-md-4">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Surat Tugas Publikasi</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $countTugaspub }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End TugasPub Card 5 -->
                </div>
            </div>
            @if (Auth::user()->role == 'admin')
                <hr>
                <div class="container">
                    <h2 class="text-center mb-4">Statistik Jumlah Surat per Jenis</h2>
                    {!! $chart->container() !!}
                    {!! $chartBulanan->container() !!}
                    {!! $chartTopUser->container() !!}
                </div>
                {{-- Include Chart JS --}}
                <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.41.0"></script>
                {{ $chart->script() }}
                {{ $chartBulanan->script() }}
                {{ $chartTopUser->script() }}
            @endif
    </section>
@endsection
