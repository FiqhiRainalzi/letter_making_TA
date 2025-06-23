@extends('layouts.admin')
@section('content')
    <div class="pagetitle">
        <h1>Surat Tugas Publikasi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Surat Tugas Publikasi</li>
                <li class="breadcrumb-item active">Preview Surat Tugas Publikasi</li>
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
            margin-bottom: 5px;
            margin-top: 5px;
        }

        .center-text {
            text-align: center;
        }

        .left-text {
            text-align: left;
            padding: 3px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3px;
            line-height: 1.15;
        }

        .info-table td {
            padding: 5px;
            vertical-align: top;
        }

        .signature {
            margin-top: 40px;
            text-align: justify;
        }

        .highlight-background {
            background-color: #d9d9d9;
        }

        .inventor-list {
            margin-left: flex;
        }

        .inventor-list td {
            padding-right: 10px;
        }

        .title-adjust {
            padding-top: 0px;
            /* Atur sesuai dengan kebutuhan */
            margin-top: -30px;
            /* Atur sesuai dengan kebutuhan */
        }
    </style>

    <body>
        <a href="{{ url()->previous() }}" class="btn btn-dark mb-3 mt-3">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <div class="container">
            <div class="content">
                <p style="margin-top:0pt; margin-bottom:0pt; line-height:normal;">
                    <span style="height:0pt; display:block; position:absolute; z-index:-65537;">
                        <img style="margin: 0 0 0 auto; display: block;" width="700" height="120"
                            src="{{ asset('copsurat.png') }}" alt="">
                    </span>&nbsp;
                </p>
                <p style="margin-top: 3cm" class="center-text"><strong><em><u>SURAT TUGAS</u></em></strong></p>
                <p class="center-text">Nomor : {{ $tugaspub->nomorSurat ?: '-' }}</p>
                <p class="center-text">&nbsp;</p>
                <p>Yang bertandatangan di bawah ini;</p>
                <table class="info-table">
                    <tr>
                        <td class="label">Nama</td>
                        <td>:</td>
                        <td class="value">Ganjar Ndaru Ikhtiagung, M.M.</td>
                    </tr>
                    <tr>
                        <td class="label">NIP</td>
                        <td>:</td>
                        <td class="value">198307282021211002</td>
                    </tr>
                    <tr>
                        <td class="label">Jabatan</td>
                        <td>:</td>
                        <td class="value">Kepala Pusat Penelitian dan Pengabdian kepada Masyarakat (P3M)</td>
                    </tr>
                </table>
                <p class="justify-text">Dengan ini menugaskan kepada Dosen Politeknik Negeri Cilacap dalam daftar dibawah
                    ini;</p>
                <table style="width: 100%;">
                    <thead class="highlight-background">
                        <tr>
                            <th scope="col" class="center-text" style="width: 10%;">Penulis Ke-</th>
                            <th scope="col" class="center-text" style="width: 30%;">Nama</th>
                            <th scope="col" class="center-text" style="width: 33.3333%;">Jurusan/Prodi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tugaspub->penulis as $index => $penulis)
                            <tr>
                                <td class="center-text">{{ $index + 1 }}.</td>
                                <td>{{ $penulis->nama }}</td>
                                <td>{{ $penulis->prodi->nama ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <p class="justify-text">Untuk melaksanakan publikasi artikel ilmiah pada
                    <strong>{{ $tugaspub->kategori_jurnal }}</strong>,
                    dengan keterangan sebagai berikut:
                </p>

                <table class="info-table">
                    <tr>
                        <td style="width: 25%">Nama Publikasi</td>
                        <td style="width: 3%">:</td>
                        <td>{{ $tugaspub->namaPublikasi }}</td>
                    </tr>
                    <tr>
                        <td>Penerbit</td>
                        <td>:</td>
                        <td>{{ $tugaspub->penerbit }}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>:</td>
                        <td>{{ $tugaspub->alamat }}</td>
                    </tr>
                    <tr>
                        <td>Link</td>
                        <td>:</td>
                        <td>{{ $tugaspub->link }}</td>
                    </tr>
                    <tr>
                        <td>Volume dan Nomor</td>
                        <td>:</td>
                        <td>{{ $tugaspub->volume }} ({{ $tugaspub->nomor }})</td>
                    </tr>
                    <tr>
                        <td>Bulan</td>
                        <td>:</td>
                        <td>{{ $tugaspub->bulan }}</td>
                    </tr>
                    <tr>
                        <td>Terakreditasi</td>
                        <td>:</td>
                        <td>{{ $tugaspub->akreditas }}</td>
                    </tr>
                    <tr>
                        <td>ISSN/ISBN</td>
                        <td>:</td>
                        <td>{{ $tugaspub->issn }}</td>
                    </tr>
                </table>
                <p>Dengan judul </p>
                <p class="text-center">
                    <strong>{{ $tugaspub->judul }}</strong>
                </p>
                <p style="text-center">Demikian surat tugas ini dibuat, untuk digunakan sebagaimana mestinya.</p>
                <div style="float: right; text-align: right;">
                    <p class="signature">Cilacap,
                        {{ \Carbon\Carbon::parse($tugaspub->tanggal)->translatedFormat('d F Y') }}
                        <br>Kepala
                        P3M Politeknik Negeri Cilacap
                    </p>&nbsp;&nbsp;
                    <p class="signature"><strong><u>Ganjar Ndaru Ikhtiagung, M. M.</u></strong><br>NIP. 198307282021211002
                    </p>
                </div>

                <!-- Add a clearfix to clear the float -->
                <div style="clear: both;"></div>
                <p style="text-align: left; margin: 0;">Tembusan : Direktur, Wakil Direktur I, dan Arsip</p>
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
@endsection
