<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header, .footer {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>PT. FANS VISION JEMBER - GMDP</h1>
        <p>Alamat: Perum Griya Mangli Indah DF 1A, Kaliwates, Jember, Jawa Timur, 68136</p>
        <p>Telp: 082231678985 / 081231877433</p>
        <p>Email: fansvision2020@gmail.com</p>
    </div>

    <hr>

    <h2>Invoice Tagihan</h2>
    <p>Tanggal: {{ $tanggal }}</p>
    <p>Invoice No.: {{ $invoiceNo }}</p>
    <p>Jatuh Tempo: {{ $jatuhTempo }}</p>

    <h3>Bill to:</h3>
    <p>{{ $reseller->nama }}<br>{{ $reseller->alamat }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Deskripsi</th>
                <th>Harga</th>
                <th>Qty/Hr</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Bandwith Dedicated GMDP {{ $bandwith }} Mbps</td>
                <td>Rp. {{ number_format($harga_bw, 0, ',', '.') }}</td>
                <td>1</td>
                <td>Rp. {{ number_format($harga_bw, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Kekurangan pembayaran bulan sebelumnya</td>
                <td>Rp. {{ number_format($tunggakan, 0, ',', '.') }}</td>
                <td>1</td>
                <td>Rp. {{ number_format($tunggakan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Biaya aktivasi reseller baru</td>
                <td>Rp. {{ number_format($biaya_aktivasi, 0, ',', '.') }}</td>
                <td>1</td>
                <td>Rp. {{ number_format($biaya_aktivasi, 0, ',', '.') }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4">Subtotal</th>
                <td>Rp. {{ number_format($total_tagihan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th colspan="4">Total</th>
                <td>Rp. {{ number_format($total_tagihan, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <p><strong>Note:</strong> Transfer ke rekening BCA 0240428491 a/n Silyana Claudya.</p>

    <div class="footer">
        <p>Thank you for your business!</p>
    </div>
</body>
</html>
