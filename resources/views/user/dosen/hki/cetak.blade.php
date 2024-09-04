<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keterangan Permohonan HKI</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            position: relative;
        }

        .header,
        .footer {
            position: fixed;
            left: 0;
            right: 0;
            width: 100%;
            text-align: center;
            z-index: 999;
        }

        .header {
            top: 0;
            height: 120px;
            /* Sesuaikan dengan tinggi header */
        }

        .footer {
            bottom: 0;
            height: 36px;
            /* Sesuaikan dengan tinggi footer */
        }

        .content {
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
            padding-top: 140px;
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
            margin-bottom: 10px;
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

        /* Print styles */
        @media print {

            .header,
            .footer {
                position: fixed;
                width: 100%;
                left: 0;
                right: 0;
                text-align: center;
            }

            .header {
                top: 0;
                height: 120px;
                /* Sesuaikan dengan tinggi header */
            }

            .footer {
                bottom: 0;
                height: 36px;
                /* Sesuaikan dengan tinggi footer */
            }

            .content {
                padding-top: 140px;
                /* Sesuaikan dengan tinggi header */
                padding-bottom: 56px;
                /* Sesuaikan dengan tinggi footer */
                margin: 0;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <?php
        // Mengambil gambar dari direktori 'public' dan mengonversi ke base64
        $headerPath = public_path('cop_surat.svg');
        
        // Konversi gambar ke base64
        $headerBase64 = 'data:image/svg+xml;base64,' . base64_encode(file_get_contents($headerPath));
        ?>
        <img src="{{ $headerBase64 }}" alt="Header Image" width="700" height="120">
    </div>

    <div class="footer">
        <?php
        // Mengambil gambar dari direktori 'public' dan mengonversi ke base64
        $footerPath = public_path('footer.png');
        
        // Konversi gambar ke base64
        $footerBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($footerPath));
        ?>
        <img src="{{ $footerBase64 }}" width="700" height="36" alt="">
    </div>

    <div class="container">
        <div class="content">
            <p class="center-text title-adjust"><strong><em><u>SURAT KETERANGAN PERMOHONAN HKI</u></em></strong></p>
            <p class="center-text">Nomor : 738 /PL43. P01/2023</p>
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
                    <td class="value">Kepala Pusat Penelitian dan Pengabdian kepada Masyarakat</td>
                </tr>
            </table>
            <p class="center-text highlight-background"><strong>MENERANGKAN BAHWA</strong></p>
            &nbsp;
            <table class="info-table">
                <tr>
                    <td>a)</td>
                    <td style="width: 25%">Nama Pemegang Hak</td>
                    <td style="width: 2%">:</td>
                    <td style="width: 70%">{{ $hki->namaPemHki }}</td>
                </tr>
                <tr>
                    <td>b)</td>
                    <td>Alamat Pemegang Hak</td>
                    <td>:</td>
                    <td>{{ $hki->alamatPemHki }}</td>
                </tr>
                <tr>
                    <td>c)</td>
                    <td>Judul Invensi</td>
                    <td>:</td>
                    <td><strong>{{ $hki->judulInvensi }}</strong></td>
                </tr>
                <tr>
                    <td>d)</td>
                    <td>Nama Inventor</td>
                    <td>:</td>
                    <td colspan="3">
                        <table class="inventor-list">
                            <tr>
                                <td>1.</td>
                                <td>{{ $hki->namaInventor1 }}</td>
                                <td>{{ $hki->bidangStudi1 }}</td>
                            </tr>
                            <tr>
                                <td>2.</td>
                                <td>{{ $hki->namaInventor2 }}</td>
                                <td>{{ $hki->bidangStudi2 }}</td>
                            </tr>
                            <tr>
                                <td>3.</td>
                                <td>{{ $hki->namaInventor3 }}</td>
                                <td>{{ $hki->bidangStudi3 }}</td>
                            </tr>
                            <tr>
                                <td>4.</td>
                                <td>{{ $hki->namaInventor4 }}</td>
                                <td>{{ $hki->bidangStudi4 }}</td>
                            </tr>
                            <tr>
                                <td>5.</td>
                                <td>{{ $hki->namaInventor5 }}</td>
                                <td>{{ $hki->bidangStudi5 }}</td>
                            </tr>
                            <tr>
                                <td>6.</td>
                                <td>{{ $hki->namaInventor6 }}</td>
                                <td>{{ $hki->bidangStudi6 }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <p class="justify-text">Telah mengajukan atas permohonan <strong>Hak Cipta</strong> pada Direktorat Jenderal
                Hak Kekayaan Intelektual Kementerian Hukum Dan Hak Asasi Manusia. Demikian Surat Keterangan Permohonan
                <em>HKI</em> dibuat, untuk digunakan sebagaimana mestinya.
            </p>
            <div style="float: right; text-align: right;">
                <p class="signature">Cilacap,
                    <strong>{{ \Carbon\Carbon::parse($hki->tanggalPemHki)->translatedFormat('d F Y') }}</strong><br>Kepala
                    P3M Politeknik Negeri Cilacap
                </p>&nbsp;&nbsp;
                <p class="signature"><strong><u>Ganjar Ndaru Ikhtiagung, M. M.</u></strong><br>NIP. 198307282021211002
                </p>
            </div>

            <!-- Add a clearfix to clear the float -->
            <div style="clear: both;"></div>
            <p style="text-align: left; margin: 0;">Tembusan : Direktur, Wakil Direktur I, dan Arsip</p>
        </div>
    </div>
</body>

</html>
