<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi Pembayaran</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
        }

        .container {
            width: 100%;
            padding: 20px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
        }

        .header img {
            width: 150px;
        }
        .no-border {
            border: none;
            border-collapse: collapse;
            width: auto;
        }

        .no-border td {
            border: none;
            /* Menghilangkan border di sel */
            padding: 3px 5px;
            /* Menambahkan padding jika diperlukan */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th {
            padding: 10px;
            text-align: center;
        }

        td {
            padding: 10px;
            text-align: justify;
        }

        .signature {
            margin-top: 50px;
        }

        .signature div {
            display: inline-block;
            width: 45%;
            text-align: center;
        }

        div.a {
            text-decoration: underline;
        }

        .signature div p {
            margin-top: 60px;
            padding-top: 5px;
        }

        footer {
            text-align: center;
            font-weight: normal;
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            margin-bottom: 0px;
        }

        footer pre {
            text-align: right;
            font-weight: bold;
            font-family: 'Times New Roman', Times, serif;
        }
        .signature {
        display: flex;
        justify-content: space-between;
        margin-top: 50px;
    }

    .signature div {
        width: 45%;
    }

    .signature-with-stamp {
        position: relative;
        display: inline-block;
        text-align: center;
    }

    .signature-with-stamp .stamp {
        position: absolute;
        top: 20px;
        left: 70%;
        transform: translate(-50%, -50%);
        width: 150px; /* Sesuaikan ukuran stempel */
        z-index: -1; /* Letakkan stempel di belakang teks */
    }

    .signature-with-stamp .signed-name {
        position: relative;
        z-index: 1; /* Letakkan teks di depan stempel */
        margin-top: 50px; /* Tambahkan ruang agar stempel tidak menutupi teks */
    }

    .watermark {
            position: fixed;
            top: 50%;
            left: 35%;
            transform: translate(-50%, -50%);
            opacity: 0.1;
        }

        .watermark img {
            width: 150%;
            height: auto;
        }


    </style>
</head>

<body>
    <div class="watermark">
        <img src="{{ public_path('images/wm.png') }}" alt="Watermark">
    </div>
    <div class="container">
        <div class="header">
            <table style="width: 100%; table-layout: fixed; border: none; margin-bottom: 0px;">
                <tr>
                    <td style="width: 20%; vertical-align: top; border: none;">
                        <img src="{{ public_path('images/logo1.png') }}" alt="Logo" style="max-width: 100%;">
                    </td>
                    <td style="width: 80%; text-align: center; vertical-align: middle; border: none;">
                        <em><strong>PT. FANS VISION JEMBER</strong></em><br>
                        Perum Griya Mangli Indah DF. 1A Mangli, RT.03 RW.04 Kelurahan Mangli.<br>
                        Kec. Kaliwates. Kab. Jember. Jawa Timur, Kode Pos 68136 <br>
                        Telp./HP. 081231877433, Email: fansvision2020@gmail.com
                    </td>
                </tr>
            </table>
            <hr>
            <h3>Kwitansi Pembayaran</h3>
            <table class="no-border">
                <tr>
                    <td>Tanggal</td>
                    <td>: {{ $tanggal }}</td>
                </tr>
                <tr>
                    <td>Kwitansi No.</td>
                    <td>: {{ $nomor_kwitansi }}</td>
                </tr>
            </table>
        </div>

        <div class="content">
            <table class="no-border">
                <tr>
                    <td><strong>Terima dari</strong></td>
                    <td><strong>: {{ $reseller->nama }}</strong></td>
                </tr>
                <tr>
                    <td><strong>Jumlah Pembayaran</strong></td>
                    <td><strong>: Rp {{ number_format($sub_total, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td><strong>Terbilang</strong></td>
                    <td><strong>: {{ $terbilang }} Rupiah</strong></td>
                </tr>
            </table>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Deskripsi</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Bandwidth Dedicated {{ $bandwith }} Mbps</td>
                        <td>Rp {{ number_format($harga, 0, ',', '.') }}</td>
                        <td>1</td>
                        <td>Rp {{ number_format($harga, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Kekurangan Pembayaran Bulan Sebelumnya</td>
                        <td>Rp {{ number_format($tunggakan, 0, ',', '.') }}</td>
                        <td>1</td>
                        <td>Rp {{ number_format($tunggakan, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Biaya Aktivasi</td>
                        <td>Rp {{ number_format($biaya_aktivasi, 0, ',', '.') }}</td>
                        <td>1</td>
                        <td>Rp {{ number_format($biaya_aktivasi, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
    
                </tfoot>
            </table>
        </div>
        <div class="signature">
            <div style="text-align: center;">
                <p><strong>Penerima </strong></p>
                <div class="signature-with-stamp">
                    <img src="{{ public_path('images/logodanttd.png') }}" alt="Stempel" class="stamp">
                    <p><strong><span style="text-decoration: underline;">Silvyana Claudya</span><br> Admin</strong></p>
                </div>
            </div>
            <div style="text-align: center;">
                <p><strong>Pembayar <br><br><br></strong></p>
                <p class="signed-name" style="text-decoration: underline;"><strong>{{ $reseller->nama }}</strong></p>
            </div>
        </div>
        <footer>
            <p>Thank you for your business!</p>
            <hr>
            <pre>Perum Griya Mangli Indah Blok DF 01, Kaliwates, Jember, Jawa Timur, 68136
                Telp: 08123187433
            </pre>
        </footer>
    </div>
</body>

</html>
