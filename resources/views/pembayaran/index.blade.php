@extends('layouts.main')

@section('contents')
@include('layouts.sidebar')
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
                    <h3>Laporan Pembayaran</h3>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Data Pembayaran</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('pembayaran.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="bulan">Bulan</label>
                                <select class="form-control" name="bulan" id="bulan">
                                    <option value="">Semua</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $i, 10)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="tahun">Tahun</label>
                                <select class="form-control" name="tahun" id="tahun">
                                    <option value="">Semua</option>
                                    @for($i = 2020; $i <= date('Y'); $i++)
                                        <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2 align-self-end">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                        <a href="{{ route('export.pembayaran') }}" class="btn btn-success">Export to Excel</a>
                    </form>
                    <table class="table table-striped mt-3" id="table1">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Reseller</th>
                                <th>Tanggal</th>
                                <th>Bandwidth</th>
                                <th>Keterangan</th>
                                <th>Spare</th>
                                <th>Tunggakan</th>
                                <th>Total Tagihan</th>
                                <th>Total Pembayaran</th>
                                <th>Harga BW</th>
                                <th>Biaya Aktivasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pembayarans as $pembayaran)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $pembayaran->reseller->nama }}</td>
                                <td>{{ $pembayaran->tanggal }}</td>
                                <td>{{ $pembayaran->bandwith }}</td>
                                <td>{{ $pembayaran->keterangan }}</td>
                                <td>{{ $pembayaran->spare }}</td>
                                <td>Rp {{ number_format($pembayaran->tunggakan, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($pembayaran->total_tagihan, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($pembayaran->total_pembayaran, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($pembayaran->harga_bw, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($pembayaran->biaya_aktivasi, 0, ',', '.') }}</td>
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
@endsection
