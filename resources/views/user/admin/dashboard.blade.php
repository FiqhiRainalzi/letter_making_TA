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
                {{-- container grafik --}}
                <div class="col-md-12">
                    <h5 class="card-title">Statistik Publikasi Berdasarkan Prodi dan Tahun</h5>
                    <div class="card">
                        <div class="card-body">
                            <div style="width: 800px; height: 400px;">
                                <canvas id="prodiChart"></canvas>                                
                            </div>
                        </div>
                    </div>
                </div>
            @endif
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('prodiChart').getContext('2d');

            // Mengambil data yang dikirim dari PHP
            const prodiData = @json($prodiData); // Data dari controller

            // Mengonversi data untuk Chart.js
            const years = Object.keys(prodiData); // Tahun sebagai label X
            const prodiNames = [...new Set(Object.values(prodiData).flatMap(year => Object.keys(year)))];

            const datasets = prodiNames.map((prodi, index) => {
                return {
                    label: prodi,
                    data: years.map(year => prodiData[year][prodi]?.total || 0),
                    backgroundColor: `hsl(${(index * 60) % 360}, 70%, 50%)`,
                    borderColor: `hsl(${(index * 60) % 360}, 70%, 40%)`,
                    borderWidth: 1,
                };
            });

            // Membuat grafik
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: years, // Tahun sebagai label X
                    datasets: datasets // Dataset berdasarkan prodi
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    const year = years[tooltipItem.dataIndex];
                                    const prodi = tooltipItem.dataset.label;
                                    const kategoriData = prodiData[year][prodi]?.kategori || {};

                                    let kategoriText =
                                        `Jumlah: ${tooltipItem.raw}\nKategori Publikasi:\n`;
                                    for (const [kategori, jumlah] of Object.entries(kategoriData)) {
                                        kategoriText += `- ${kategori}: ${jumlah}\n`;
                                    }

                                    return kategoriText.trim();
                                }
                            }
                        },
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah Publikasi'
                            },
                            ticks: {
                                stepSize: 1, // Naik satu per satu
                                precision: 0, // Pastikan angka bulat
                            },
                            suggestedMax: 10, // Saran batas maksimum sumbu Y
                            suggestedMin: 0, // Saran batas minimum sumbu Y
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Periode Tahun'
                            }
                        }
                    }
                }

            });
        });
    </script>
@endsection
