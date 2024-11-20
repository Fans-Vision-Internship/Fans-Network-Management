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
                    <h3>Cetak Invoice</h3>
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
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modalTambahTransaksi{{ $reseller->id }}">
                                        <i class="bi bi-plus-circle"></i> Cetak Invoice
                                    </button>

                                    <div class="modal fade" id="modalTambahTransaksi{{ $reseller->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="modalTambahTransaksiLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalTambahTransaksiLabel">
                                                        Cetak Invoice untuk {{ $reseller->nama }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('invoice.generate', $reseller->id) }}" method="POST" target="_blank">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="tanggal">Tanggal</label>
                                                            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="jatuh_tempo">Jatuh Tempo</label>
                                                            <input type="date" class="form-control" id="jatuh_tempo" name="jatuh_tempo" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="bandwith">Bandwith</label>
                                                            <input type="number" class="form-control" id="bandwith" name="bandwith" value="{{ $reseller->bandwith }}" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="harga">Harga</label>
                                                            <input type="number" class="form-control" id="harga" name="harga" required oninput="hitungSubtotal()">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="biaya_aktivasi">Biaya Aktivasi</label>
                                                            <input type="number" class="form-control" id="biaya_aktivasi" name="biaya_aktivasi" required oninput="hitungSubtotal()">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="tunggakan">Tunggakan</label>
                                                            <input type="number" class="form-control" id="tunggakan" name="tunggakan" value="{{ $reseller->tunggakan }}" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="sub_total">Sub Total</label>
                                                            <input type="number" class="form-control" id="sub_total" name="sub_total" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                        <button type="submit" class="btn btn-primary">Simpan & Cetak Invoice</button>
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

    @include('layouts.footer')
</div>

<script>
    function hitungSubtotal() {
    let harga = parseFloat(document.getElementById('harga').value) || 0;
    let biayaAktivasi = parseFloat(document.getElementById('biaya_aktivasi').value) || 0;
    let tunggakan = parseFloat(document.getElementById('tunggakan').value) || 0;

    let subTotal = harga + biayaAktivasi + tunggakan;
    document.getElementById('sub_total').value = subTotal;
}

</script>

@endsection
