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
                    <h3>Fans Network</h3>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Data Reseller</h5>
                    <button type="button" class="btn btn-primary btn-icon-text" data-bs-toggle="modal"
                        data-bs-target="#modalTambahReseller">
                        <i class="bi bi-plus-circle-fill me-2"></i> Tambah Reseller
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>alamat</th>
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
                                    <!-- Tombol Edit Reseller -->
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#modalEditReseller{{ $reseller->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    
                                    <!-- Modal Edit Reseller -->
                                    <div class="modal fade text-left" id="modalEditReseller{{ $reseller->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="modalEditResellerLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalEditResellerLabel">Edit Reseller</h5>
                                                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal"
                                                        aria-label="Close">
                                                        <i data-feather="x"></i>
                                                    </button>
                                                </div>
                                                <form action="{{ route('reseller.update', $reseller->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="nama">Nama</label>
                                                            <input type="text" class="form-control" id="nama" name="nama" required value="{{ $reseller->nama }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="alamat">Alamat</label>
                                                            <input type="text" class="form-control" id="alamat" name="alamat" required value="{{ $reseller->alamat }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="nohp">No HP</label>
                                                            <input type="number" class="form-control" id="nohp" name="nohp" required value="{{ $reseller->nohp }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="tunggakan">Tunggakan</label>
                                                            <!-- Input tampilan dengan format Rupiah -->
                                                            <input type="text" class="form-control" id="tunggakan_format" onkeyup="formatRupiah(this)" required value="Rp {{ number_format($reseller->tunggakan, 0, ',', '.') }}" readonly>
                                                            
                                                            <!-- Input yang akan disimpan ke database (hidden) -->
                                                            <input type="number" class="form-control" id="tunggakan" name="tunggakan" hidden value="{{ $reseller->tunggakan }}" >
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="area">Area</label>
                                                            <input type="text" class="form-control" id="area" name="area" required value="{{ $reseller->area }}">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tombol Hapus Reseller -->
                                    <button type="button" class="btn btn-danger" onclick="confirmDelete('{{ route('reseller.destroy', $reseller->id) }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <!-- Tombol WhatsApp Reseller -->
                                    <a href="https://api.whatsapp.com/send?phone=62{{ $reseller->nohp }}&text=Halo" target="_blank" class="btn btn-success">
                                        <i class="bi bi-whatsapp"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal Tambah Reseller -->
    <div class="modal fade text-left" id="modalTambahReseller" tabindex="-1" role="dialog"
        aria-labelledby="modalTambahResellerLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahResellerLabel">Tambah Reseller</h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <form action="{{ route('reseller.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input type="text" class="form-control" id="alamat" name="alamat" required>
                        </div>
                        <div class="form-group">
                            <label for="nohp">No HP</label>
                            <input type="number" class="form-control" id="nohp" name="nohp" required>
                        </div>
                        <div class="form-group">
                            <label for="tunggakan">Tunggakan</label>
                            <!-- Input tampilan dengan format Rupiah -->
                            <input type="text" class="form-control" id="tunggakan_format" onkeyup="formatRupiah(this)" required>
                            
                            <!-- Input yang akan disimpan ke database (hidden) -->
                            <input type="number" class="form-control" id="tunggakan_tambah" name="tunggakan" hidden >
                        </div>
                        <div class="form-group">
                            <label for="area">Area</label>
                            <input type="text" class="form-control" id="area" name="area" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('layouts.footer')


    <script>

        @if(session('success'))
        Swal.fire({
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'OK'
        });
        @endif

        // Konfirmasi penghapusan reseller dengan SweetAlert
        function confirmDelete(url) {
            Swal.fire({
                title: 'Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    var form = document.createElement('form');
                    form.action = url;
                    form.method = 'POST';
                    form.innerHTML = '@csrf @method('DELETE')';
                    document.body.appendChild(form);
                    form.submit();
                }
            })
        }
    </script>
   <script>
    function formatRupiah(element) {
        var angka = element.value.replace(/[^,\d]/g, "").toString();
        var split = angka.split(",");
        var sisa = split[0].length % 3;
        var rupiah = split[0].substr(0, sisa);
        var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            var separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }

        rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
        element.value = "Rp " + rupiah;

        // Update nilai yang akan disimpan ke dalam hidden input
        document.getElementById('tunggakan_tambah').value = angka.replace(/\./g, '');
        document.getElementById('tunggakan').value = angka.replace(/\./g, '');
    }
</script>


</div>
@endsection