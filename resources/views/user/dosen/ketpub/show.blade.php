@extends('layouts.admin')
@section('content')
    <div class="pagetitle">
        <h1>Surat Keterangan Publikasi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Surat Keterangan Publikasi</li>
                <li class="breadcrumb-item active">Preview Surat Keterangan Publikasi</li>
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
            text-align: center;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
            margin-top: 5px;
            line-height: 1;
        }

        .info-table td {
            padding: 5px;
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
                <p style="margin-top: 3cm" class="center-text"><strong><em><u>SURAT KETERANGAN</u></em></strong></p>
                <p class="center-text">Nomor : {{ $ketpub->nomorSurat ?: '-' }}/{{ $year }}</p>
                <p class="center-text">&nbsp;</p>
                <p>Yang bertandatangan di bawah ini;</p>
                <table class="info-table">
                    <tr>
                        <td style="width: 25%">Nama</td>
                        <td style="width: 3%">:</td>
                        <td>Ganjar Ndaru Ikhtiagung, M.M.</td>
                    </tr>
                    <tr>
                        <td>NIP</td>
                        <td>:</td>
                        <td>198307282021211002</td>
                    </tr>
                    <tr>
                        <td>Jabatan</td>
                        <td>:</td>
                        <td>Kepala Pusat Penelitian dan Pengabdian kepada Masyarakat</td>
                    </tr>
                </table>
                <p>Dengan ini menerangkan bahwa {{ $ketpub->kategori_publikasi }} dibawah ini; </p>

                <table class="info-table">
                    <tr>
                        <td style="width: 25%">Judul</td>
                        <td style="width: 3%">:</td>
                        <td>{{ $ketpub->judul }}</td>
                    </tr>
                    <tr>
                        <td>Nama Penerbit</td>
                        <td>:</td>
                        <td>{{ $ketpub->namaPenerbit }}</td>
                    </tr>
                    <tr>
                        <td>Penerbit</td>
                        <td>:</td>
                        <td>{{ $ketpub->penerbit }}</td>
                    </tr>
                    <tr>
                        <td>Jilid atau Edisi</td>
                        <td>:</td>
                        <td>Jilid : {{ $ketpub->jilid }} / Edisi : {{ $ketpub->edisi }}</td>
                    </tr>
                    <tr>
                        <td>Bulan</td>
                        <td>:</td>
                        <td>{{ $ketpub->bulan }}</td>
                    </tr>
                    <tr>
                        <td>ISSN/ISBN</td>
                        <td>:</td>
                        <td>{{ $ketpub->issn }}</td>
                    </tr>
                    <tr>
                        <td>Tahun</td>
                        <td>:</td>
                        <td>{{ $ketpub->tahun }}</td>
                    </tr>
                </table>
                <p>Merupakan {{ $ketpub->kategori_publikasi }} yang telah ditulis dan dipublikasikan oleh; </p>
                <table class="info-table">
                    @foreach ($ketpub->penulis as $index => $penulis)
                        <tr>
                            <td style="width: 25%">Nama Penulis {{ $index + 1 }}</td>
                            <td style="width: 3%">:</td>
                            <td>{{ $penulis->nama }}</td>
                            <td>{{ $penulis->prodi->nama }}</td>
                        </tr>
                    @endforeach
                </table>

                </table>
                <p class="justify-text">Demikian surat keterangan ini ditandatangi dan disampikan untuk dapat dipergunakan
                    sebagaimana mestinya</p>
                <div style="float: right; text-align: right;">
                    <p class="signature">Cilacap, {{ \Carbon\Carbon::parse($ketpub->tanggal)->translatedFormat('d F Y') }}
                        <strong></strong><br>Kepala Pusat Penelitian dan Pengabdian kepada Masyarakat
                    </p>&nbsp;&nbsp;
                    <p class="signature"><strong><u>Ganjar Ndaru Ikhtiagung, M. M.</u></strong><br>NIP. 198307282021211002
                    </p>
                </div>

                <!-- Add a clearfix to clear the float -->
                <div style="clear: both;"></div>
                <p style="text-align: left; margin: 0;">Tembusan: 1) Direktur; 2) Wakil Direktur 1 Bidang Akademik; dan 3)
                    Arsip</p>
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
