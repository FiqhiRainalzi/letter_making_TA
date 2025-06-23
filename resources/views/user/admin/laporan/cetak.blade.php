<!DOCTYPE html>
<html>

<head>
    <title>Laporan</title>
    <style>
        body {
            font-family: Arial;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }
    </style>
</head>

<body>
    <h3 style="text-align: center;">Laporan Surat</h3>
    @if ($catatan)
        <p><strong>Catatan:</strong> {{ $catatan }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis</th>
                <th>Tanggal</th>
                <th>Judul</th>
                <th>Status</th>
                <th>Nomor Surat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ strtoupper($item->jenis_surat) }}</td>
                    <td>{{ $item->created_at->format('d M Y') }}</td>
                    <td>{{ $item->getSurat()?->judul ?? '-' }}</td>
                    <td>{{ ucfirst($item->status) }}</td>
                    <td>{{ $item->getSurat()?->nomorSurat ?? '-' }}</td>

                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
