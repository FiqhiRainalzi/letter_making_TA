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
                                <h5 class="card-title">Surat Tugas Publikasi Nasional</h5>
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
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Statistik Surat <span>| Kategori Publikasi</span></h5>
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                </div>
            @endif
            <hr>
            <!-- HKI -->
            <div class="col-12">
                <div class="card recent-sales overflow-auto">
                    <div class="card-body">
                        <h5 class="card-title">Surat HKI <span>| Today </span></h5>
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Inventor</th>
                                    <th scope="col">Judul Invensi</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Dibuat Pada</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dataHki as $h)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $h->inventors->first()->nama }}</td>
                                        <td>{{ $h->judulInvensi }}</td>
                                        <td>
                                            @if ($h->statusSurat == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($h->statusSurat == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($h->statusSurat == 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>{{ $h->created_at->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Data belum tersedia.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- End Recent HKI -->
            <hr>
            <!-- Recent Penelitian -->
            <div class="col-12">
                <div class="card recent-sales overflow-auto">

                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                        {{-- <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                        <h6>Filter</h6>
                                    </li>

                                    <li><a class="dropdown-item" href="#">Today</a></li>
                                    <li><a class="dropdown-item" href="#">This Month</a></li>
                                    <li><a class="dropdown-item" href="#">This Year</a></li>
                                </ul> --}}
                    </div>

                    <div class="card-body">
                        <h5 class="card-title">Surat Tugas Penelitian <span>| Today</span></h5>

                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Ketua</th>
                                    <th scope="col">Judul</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Dibuat Pada</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dataPenelitian as $h)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $h->namaKetua }}</td>
                                        <td>{{ $h->judul }}</td>
                                        <td>
                                            @if ($h->statusSurat == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($h->statusSurat == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($h->statusSurat == 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>{{ $h->created_at->diffForHumans() }}
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Data belum tersedia.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>

                </div>
            </div><!-- End Recent Penelitian -->
            <hr>
            <!-- Recent PKM -->
            <div class="col-12">
                <div class="card recent-sales overflow-auto">

                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                        {{-- <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                        <h6>Filter</h6>
                                    </li>

                                    <li><a class="dropdown-item" href="#">Today</a></li>
                                    <li><a class="dropdown-item" href="#">This Month</a></li>
                                    <li><a class="dropdown-item" href="#">This Year</a></li>
                                </ul> --}}
                    </div>

                    <div class="card-body">
                        <h5 class="card-title">Surat Tugas PKM <span>| Today</span></h5>

                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Ketua</th>
                                    <th scope="col">Judul</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Dibuat Pada</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dataPkm as $h)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $h->namaKetua }}</td>
                                        <td>{{ $h->judul }}</td>
                                        <td>
                                            @if ($h->statusSurat == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($h->statusSurat == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($h->statusSurat == 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>{{ $h->created_at->diffForHumans() }}
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Data belum tersedia.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>

                </div>
            </div><!-- End Recent PKM -->
            <hr>

            <!-- Recent Keterangan Publikasi -->
            <div class="col-12">
                <div class="card recent-sales overflow-auto">

                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title">Surat Keterangan Publikasi <span>| Today</span></h5>

                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Penulis</th>
                                    <th scope="col">Judul</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Dibuat Pada</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dataKetPub as $h)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $h->penulis->first()->nama }}</td>
                                        <td>{{ $h->judul }}</td>
                                        <td>
                                            @if ($h->statusSurat == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($h->statusSurat == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($h->statusSurat == 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>{{ $h->created_at->diffForHumans() }}
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Data belum tersedia.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>

                </div>
            </div><!-- End Recent Keterangan Publikasi -->
            <hr>
            <!-- Recent Tugas Publikasi Nasional -->
            <div class="col-12">
                <div class="card recent-sales overflow-auto">

                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title">Surat Tugas Publikasi Nasional <span>| Today</span></h5>

                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Judul</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Dibuat Pada</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dataTugaspub as $h)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $h->penulis->first()->nama }}</td>
                                        <td>{{ $h->judul }}</td>
                                        <td>
                                            @if ($h->statusSurat == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($h->statusSurat == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($h->statusSurat == 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>{{ $h->created_at->diffForHumans() }}
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Data belum tersedia.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>

                </div>
            </div><!-- End Recent Tugas Publikasi Nasional -->
        </div>
        </div><!-- End Left side columns -->

        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('categoryChart').getContext('2d');

            // Mengambil data yang dikirim dari PHP
            const prodiLabels = @json($prodiData->pluck('prodi')); // Nama Prodi
            const prodiDataValues = @json($prodiData->pluck('total')); // Total Surat per Prodi
            const kategoriPublikasi = @json($prodiData->pluck('kategori')); // Data kategori publikasi

            // Menyusun data untuk grafik
            const categoryChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: prodiLabels, // Prodi sebagai label
                    datasets: [{
                        label: 'Jumlah Surat',
                        data: prodiDataValues, // Total surat per prodi
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                // Menampilkan Total Surat di bagian title
                                title: function(tooltipItem) {
                                    const totalSurat = tooltipItem[0].raw; // Ambil total surat
                                    return `Total Surat: ${totalSurat}`; // Tampilkan di title
                                },
                                // Menampilkan kategori publikasi di bagian footer
                                footer: function(tooltipItem) {
                                    const index = tooltipItem[0].dataIndex; // Dapatkan indeks data
                                    const kategoriData = kategoriPublikasi[
                                    index]; // Ambil data kategori berdasarkan indeks

                                    // Cek jika ada data kategori
                                    let footerText = 'Kategori Publikasi:';
                                    if (kategoriData) {
                                        // Loop untuk menampilkan kategori publikasi
                                        for (const [kategori, jumlah] of Object.entries(kategoriData)) {
                                            footerText +=
                                            `\n- ${kategori}: ${jumlah}`; // Tampilkan kategori dan jumlahnya
                                        }
                                    } else {
                                        footerText +=
                                        '\nTidak ada data kategori'; // Menangani jika tidak ada data kategori
                                    }
                                    return footerText; // Tampilkan kategori di footer
                                },
                                label: function(tooltipItem) {
                                    return ''; // Kosongkan label, karena kita memisahkannya ke title dan footer
                                }
                            },
                            // Menambahkan pengaturan tooltip
                            mode: 'index',
                            intersect: false,
                            displayColors: false,
                            bodySpacing: 10,
                            titleSpacing: 10,
                            footerSpacing: 10
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endsection
