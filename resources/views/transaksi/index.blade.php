@extends('layouts.main')

@section('contents')
@include('layouts.sidebar')
<style>
    .modal-dialog-scrollable .modal-body {
    max-height: 70vh;
    overflow-y: auto;
}

</style>
<div id="main">
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Data Reseller Belum Transaksi Bulan Ini</h3>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>No HP</th>
                                <th>Tunggakan</th>
                                <th>Area</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($resellers as $reseller)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $reseller->nama }}</td>
                                <td>{{ $reseller->alamat }}</td>
                                <td>{{ $reseller->nohp }}</td>
                                <td>Rp {{ number_format($reseller->tunggakan, 0, ',', '.') }}</td>
                                <td>{{ $reseller->area }}</td>
                                <td>
                                    <!-- Tombol Tambah Transaksi -->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modalTambahTransaksi{{ $reseller->id }}">
                                        <i class="bi bi-plus-circle"></i> Tambah Transaksi
                                    </button>

                                    <!-- Modal Tambah Transaksi -->
                                    <div class="modal fade" id="modalTambahTransaksi{{ $reseller->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="modalTambahTransaksiLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalTambahTransaksiLabel">Tambah Transaksi untuk {{ $reseller->nama }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('transaksi.store', $reseller->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="tanggal">Tanggal</label>
                                                            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="bandwith">Bandwith</label>
                                                            <input type="number" class="form-control" id="bandwith" name="bandwith" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="keterangan">Keterangan</label>
                                                            <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="spare">Spare</label>
                                                            <input type="number" class="form-control" id="spare" name="spare">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="harga_bw">Harga BW</label>
                                                            <input type="number" class="form-control" id="harga_bw" name="harga_bw" required oninput="hitungTotalTagihan()">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="tunggakan">Tunggakan</label>
                                                            <input type="number" class="form-control" id="tunggakan" name="tunggakan" value="{{ $reseller->tunggakan }}" required oninput="hitungTotalTagihan()" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="biaya_aktivasi">Biaya Aktivasi</label>
                                                            <input type="number" class="form-control" id="biaya_aktivasi" name="biaya_aktivasi" required oninput="hitungTotalTagihan()">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="total_tagihan">Total Tagihan</label>
                                                            <input type="number" class="form-control" id="total_tagihan" name="total_tagihan" required readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="total_pembayaran">Total Pembayaran</label>
                                                            <input type="number" class="form-control" id="total_pembayaran" name="total_pembayaran" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                        <a href="{{ route('transaksi.invoice', $reseller->id) }}" class="btn btn-success">Invoice</a>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
    <script>
        function hitungTotalTagihan() {
            // Ambil nilai dari input
            var harga_bw = parseFloat(document.getElementById('harga_bw').value) || 0;
            var tunggakan = parseFloat(document.getElementById('tunggakan').value) || 0;
            var biaya_aktivasi = parseFloat(document.getElementById('biaya_aktivasi').value) || 0;
    
            // Hitung total tagihan
            var total_tagihan = harga_bw + tunggakan + biaya_aktivasi;
    
            // Masukkan hasil total tagihan ke input "total_tagihan"
            document.getElementById('total_tagihan').value = total_tagihan;
        }
    </script>
    
    @include('layouts.footer')
</div>
@endsection
