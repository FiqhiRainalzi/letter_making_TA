@extends('layouts.admin')
@section('content')
    <div class="pagetitle">
        <h1>Surat PKM</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Surat PKM</li>
                <li class="breadcrumb-item active">Preview Surat PKM</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            position: relative;
        }

        .content {
            font-family: 'Times New Roman', Times, serif;
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
            /* Sesuaikan dengan tinggi header + margin tambahan */
            padding-bottom: 56px;
            /* Sesuaikan dengan tinggi footer + margin tambahan */
        }

        p {
            margin: 0;
        }

        .justify-text {
            text-align: justify;
        }

        .center-text {
            line-height: 1.00;
            text-align: center;
        }

        .inventor-list {
            margin-left: flex;
        }

        .inventor-list td {
            padding-right: 10px;
        }

        .info-table {
            width: 100%;
            /* Set the table width to 100% of its container */
            border-collapse: collapse;
            margin-bottom: 10px;
            line-height: 1.15;
        }

        .info-table td {
            padding: 3px;
            vertical-align: top;
        }

        .signature {
            max-width: 50%;
            margin-top: 40px;
            margin-left: auto;
            margin-right: 0;
            text-align: justify;
            /* Menyusun teks secara justify */
            position: relative;
            /* Menyusun elemen secara relatif */
        }

        .signature::after {
            content: '';
            /* Pseudo-elemen untuk menyusun teks justify */
            display: block;
            width: 100%;
            /* Lebar elemen pseudo harus sama dengan elemen parent */
            height: 0;
            /* Tinggi elemen pseudo diset ke 0 */
        }

        .highlight-background {
            background-color: #d9d9d9;
        }

        .inline-list {
            display: inline;
            /* Membuat daftar tampil dalam satu baris */
            padding-left: 10px;
            /* Jarak antara ":" dan daftar */
        }

        .title-adjust {
            padding-top: 0px;
            /* Atur sesuai dengan kebutuhan */
            margin-top: -30px;
            /* Atur sesuai dengan kebutuhan */
        }
    </style>

    <body>
        <a href="{{ url()->previous() }}" class="btn btn-sm btn-dark mb-3 mt-3">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('admin.pkmDownload', $pkm->id) }}"class="btn btn-sm btn-primary">Download <i
                class="bi bi-download"></i></a>
        <div class="container">
            <div class="content">
                <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal;">
                    <span style="height:0pt; display:block; position:absolute; z-index:-65537;">
                        <img style="margin: 0 0 0 auto; display: block;" width="700" height="120"
                            src="{{ asset('copsurat.png') }}" alt="">
                    </span>&nbsp;
                </p>
                <p style="margin-top: 3cm" class="center-text"><strong>SURAT TUGAS</strong></p>
                <p class="center-text"><strong>MELAKSANAKAN KEGIATAN PENGABDIAN KEPADA MASYARAKAT (PKM)</strong></p>
                <p class="center-text"><strong>BAGI DOSEN POLITEKNIK NEGERI CILACAP</strong></p>
                <p class="center-text"><strong>Nomor :
                        {{ $pkm->nomorSurat ?: '-' }}/{{ \Carbon\Carbon::parse($pkm->tanggalPemHki)->translatedFormat('Y') }}</strong>
                </p>
                <p class="center-text">&nbsp;</p>
                <p class="justify-text" style="text-indent: 30px">Berdasarkan kewajiban Dosen dalam Tridharma Perguruan
                    Tinggi, Politeknik Negeri Cilacap memberikan tugas
                    kepada ;</p>
                <table class="info-table">
                    <tr>
                        <td>Nama Ketua</td>
                        <td>: {{ $pkm->namaKetua }}</td>
                    </tr>
                    <tr>
                        <td>NIP/NIDN</td>
                        <td>: {{ $pkm->nipNidn }}</td>
                    </tr>
                    <tr>
                        <td>Jabatan Akademik</td>
                        <td>: {{ $pkm->jabatanAkademik }}</td>
                    </tr>
                    <tr>
                        <td>Jurusan/Program Studi</td>
                        <td>: {{ $pkm->jurusanProdi }}</td>
                    </tr>
                </table>
                <p class="justify-text">untuk melaksanakan PkM dengan judul : <strong>{{ $pkm->judul }}</strong> sesuai
                    skim PKM :
                    <strong>{{ $pkm->skim }}</strong>, dengan
                    dasar <strong>{{ $pkm->dasarPelaksanaan }}</strong> di lokasi PKM :
                    <strong>{{ $pkm->lokasi }}</strong> yang dilaksanakan
                    pada bulan :
                    <strong>{{ \Carbon\Carbon::parse($pkm->bulanPelaksanaan)->translatedFormat('d F Y') }}</strong>
                    sampai dengan bulan :
                    <strong>{{ \Carbon\Carbon::parse($pkm->bulanAkhirPelaksanaan)->translatedFormat('d F Y') }}</strong>.
                </p>
                &nbsp;
                <p class="justify-text" style="text-indent: 30px">Hal-hal yang terkait dengan pelaksanaan ini dapat dilihat
                    pada Surat Perjanjian Melaksanakan Pengabdian Kepada Masyarakat. Di samping itu, pelaksanaan PKM tidak
                    disalah gunakan untuk tujuan lain yang berakibat pelanggaran peraturan Perundang-undangan yang berlaku
                    dan bersedia menaati ketentuan yang berlaku dalam pelaksanaan PKM dimaksud. Demikian surat tugas ini
                    dibuat untuk dapat dilaksanakan dan dipergunakan sebagaimana mestinya.
                </p>
                <div style="float: right; text-align: right;">
                    <p class="signature">Cilacap, {{ \Carbon\Carbon::parse($pkm->tanggal)->translatedFormat('d F Y') }}
                        <strong></strong><br>Kepala Pusat Penelitian dan Pengabdian Kepada Masyarakat
                    </p>&nbsp;&nbsp;
                    <p class="signature"><strong><u>Ganjar Ndaru Ikhtiagung, M. M.</u></strong><br>NIP. 198307282021211002
                    </p>
                </div>

                <!-- Add a clearfix to clear the float -->
                <div style="clear: both;"></div>
                <p style="text-align: left; margin: 0;">Direktur;<strong>{{ $pkm->namaKetua }} (yang bersangkutan)</strong>
                    ; dan
                    Arsip </p>
                <div style="clear: both;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal;">
                        <span style="height:0pt; display:block; position:absolute; z-index:-65536;">
                            <img src="{{ asset('footer.png') }}" width="700" height="36" alt=""
                                style="margin: 0 0 0 auto; display: block;">
                        </span>&nbsp;
                    </p>
                </div>
            </div>
        </div>
    </body>
    <hr>

    <body>
        <div class="container">
            <div class="content">
                <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal;">
                    <span style="height:0pt; display:block; position:absolute; z-index:-65537;">
                        <img style="margin: 0 0 0 auto; display: block;" width="700" height="120"
                            src="{{ asset('copsurat.png') }}" alt="">
                    </span>&nbsp;
                </p>
                <p style="margin-top: 3cm" class="left-text"><strong>Lampiran Surat tugas :
                        {{ $pkm->nomorSurat ?: '-' }}/{{ \Carbon\Carbon::parse($pkm->tanggal)->translatedFormat('Y') }}</strong>
                </p>

                {{-- tenaga anggota --}}
                <table style="width: 100%; margin-top: 1cm">
                    <thead class="highlight-background">
                        <tr>
                            <th scope="col" class="center-text" style="width: 10%;">No</th>
                            <th scope="col" class="center-text" style="width: 30%;">Nama Anggota Peneliti</th>
                            <th scope="col" class="center-text" style="width: 33.3333%;">Program Studi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pkm->anggota as $index => $anggota)
                            <tr>
                                <td class="center-text">{{ $index + 1 }}</td>
                                <td>{{ $anggota->nama }}</td>
                                <td>{{ $anggota->prodi->nama ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada data anggota.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- table tenaga --}}
                <table style="width: 100%; margin-top: 1cm">
                    <thead class="highlight-background">
                        <tr>
                            <th scope="col" class="center-text" style="width: 10%;">No</th>
                            <th scope="col" class="center-text" style="width: 30%;">Nama Tenaga Pembantu Peneliti</th>
                            <th scope="col" class="center-text" style="width: 33.3333%;">Program Studi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pkm->tenagaPembantu as $index => $tenagaPembantu)
                            <tr>
                                <td class="center-text">{{ $index + 1 }}</td>
                                <td>{{ $tenagaPembantu->nama }}</td>
                                <td>{{ $tenagaPembantu->prodi->nama ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada data anggota.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div style="clear: both; margin-top:15cm">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal;">
                        <span style="height:0pt; display:block; position:absolute; z-index:-65536;">
                            <img src="{{ asset('footer.png') }}" width="700" height="36" alt=""
                                style="margin: 0 0 0 auto; display: block;">
                        </span>&nbsp;
                    </p>
                </div>
            </div>
        </div>
    </body>
@endsection
