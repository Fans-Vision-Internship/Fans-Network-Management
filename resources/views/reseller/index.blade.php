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
                                <th>Bandwith</th>
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
                                <td>{{ $reseller->bandwith }} Mbps</td>
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
                                                        <div class="form-group">
                                                            <label for="device_type{{ $reseller->id }}">Pilih Jenis Perangkat</label>
                                                            <select class="form-select" id="device_type{{ $reseller->id }}" name="device_type" onchange="toggleDeviceInputs({{ $reseller->id }})">
                                                                <option value="olt" {{ $reseller->olt_sn ? 'selected' : '' }}>OLT</option>
                                                                <option value="switch" {{ $reseller->switch_type_sfp ? 'selected' : '' }}>Switch</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <!-- OLT Fields -->
                                                        <div id="olt_inputs{{ $reseller->id }}" style="display: {{ $reseller->olt_sn ? 'block' : 'none' }};">
                                                            <div class="form-group">
                                                                <label for="olt_sn">OLT SN</label>
                                                                <input type="text" class="form-control" id="olt_sn" name="olt_sn" value="{{ $reseller->olt_sn }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="olt_type_modem">OLT Type Modem</label>
                                                                <input type="text" class="form-control" id="olt_type_modem" name="olt_type_modem" value="{{ $reseller->olt_type_modem }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="olt_lokasi_pop">OLT Lokasi POP</label>
                                                                <input type="text" class="form-control" id="olt_lokasi_pop" name="olt_lokasi_pop" value="{{ $reseller->olt_lokasi_pop }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="olt_secret">Secret</label>
                                                                <input type="text" class="form-control" id="olt_secret" name="olt_secret" value="{{ $reseller->olt_secret }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="olt_ip_address">OLT IP Address</label>
                                                                <input type="text" class="form-control" id="olt_ip_address" name="olt_ip_address" value="{{ $reseller->olt_ip_address }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="olt_statik">OLT Statik</label>
                                                                <select class="form-select" id="olt_statik" name="olt_statik">
                                                                    <option value="private" {{ $reseller->olt_statik == 'private' ? 'selected' : '' }}>Private</option>
                                                                    <option value="public" {{ $reseller->olt_statik == 'public' ? 'selected' : '' }}>Public</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Switch Fields -->
                                                        <div id="switch_inputs{{ $reseller->id }}" style="display: {{ $reseller->switch_type_sfp ? 'block' : 'none' }};">
                                                            <div class="form-group">
                                                                <label for="switch_type_sfp">Switch Type SFP</label>
                                                                <input type="text" class="form-control" id="switch_type_sfp" name="switch_type_sfp" value="{{ $reseller->switch_type_sfp }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="switch_sn_sfp">Switch SN SFP</label>
                                                                <input type="text" class="form-control" id="switch_sn_sfp" name="switch_sn_sfp" value="{{ $reseller->switch_sn_sfp }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="switch_lokasi_pop">Switch Lokasi POP</label>
                                                                <input type="text" class="form-control" id="switch_lokasi_pop" name="switch_lokasi_pop" value="{{ $reseller->switch_lokasi_pop }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="switch_port_number">Switch Port Number</label>
                                                                <input type="number" class="form-control" id="switch_port_number" name="switch_port_number" value="{{ $reseller->switch_port_number }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="switch_statik">Switch Statik</label>
                                                                <select class="form-select" id="switch_statik" name="switch_statik">
                                                                    <option value="private" {{ $reseller->switch_statik == 'private' ? 'selected' : '' }}>Private</option>
                                                                    <option value="public" {{ $reseller->switch_statik == 'public' ? 'selected' : '' }}>Public</option>
                                                                </select>
                                                            </div>
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
                        <div class="form-group">
                            <label for="device_type">Pilih Jenis Perangkat</label>
                            <select class="form-select" id="device_type" name="device_type" onchange="toggleDeviceInputsAdd()">
                                <option value="olt">OLT</option>
                                <option value="switch">Switch</option>
                            </select>
                        </div>
                        
                        <!-- OLT Fields -->
                        <div id="olt_inputs_add" >
                            <div class="form-group">
                                <label for="olt_sn">OLT SN</label>
                                <input type="text" class="form-control" id="olt_sn" name="olt_sn">
                            </div>
                            <div class="form-group">
                                <label for="olt_type_modem">OLT Type Modem</label>
                                <input type="text" class="form-control" id="olt_type_modem" name="olt_type_modem">
                            </div>
                            <div class="form-group">
                                <label for="olt_lokasi_pop">OLT Lokasi POP</label>
                                <input type="text" class="form-control" id="olt_lokasi_pop" name="olt_lokasi_pop">
                            </div>
                            <div class="form-group">
                                <label for="olt_secret">Secret</label>
                                <input type="text" class="form-control" id="olt_secret" name="olt_secret">
                            </div>
                            <div class="form-group">
                                <label for="olt_ip_address">OLT IP Address</label>
                                <input type="text" class="form-control" id="olt_ip_address" name="olt_ip_address">
                            </div>
                            <div class="form-group">
                                <label for="olt_statik">OLT Statik</label>
                                <select class="form-select" id="olt_statik" name="olt_statik">
                                    <option value="private">Private</option>
                                    <option value="public">Public</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Switch Fields -->
                        <div id="switch_inputs_add" style="display: none;">
                            <div class="form-group">
                                <label for="switch_type_sfp">Switch Type SFP</label>
                                <input type="text" class="form-control" id="switch_type_sfp" name="switch_type_sfp">
                            </div>
                            <div class="form-group">
                                <label for="switch_sn_sfp">Switch SN SFP</label>
                                <input type="text" class="form-control" id="switch_sn_sfp" name="switch_sn_sfp">
                            </div>
                            <div class="form-group">
                                <label for="switch_lokasi_pop">Switch Lokasi POP</label>
                                <input type="text" class="form-control" id="switch_lokasi_pop" name="switch_lokasi_pop">
                            </div>
                            <div class="form-group">
                                <label for="switch_port_number">Switch Port Number</label>
                                <input type="number" class="form-control" id="switch_port_number" name="switch_port_number">
                            </div>
                            <div class="form-group">
                                <label for="switch_statik">Switch Statik</label>
                                <select class="form-select" id="switch_statik" name="switch_statik">
                                    <option value="private">Private</option>
                                    <option value="public">Public</option>
                                </select>
                            </div>
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
<script>
    function toggleDeviceInputs(id) {
    const deviceType = document.getElementById('device_type' + id).value;
    const oltInputs = document.getElementById('olt_inputs' + id);
    const switchInputs = document.getElementById('switch_inputs' + id);

    if (deviceType === 'olt') {
        oltInputs.style.display = 'block';
        switchInputs.style.display = 'none';
    } else if (deviceType === 'switch') {
        oltInputs.style.display = 'none';
        switchInputs.style.display = 'block';
    }
}
function toggleDeviceInputsAdd() {
    const deviceType = document.getElementById('device_type').value;
    const oltInputs = document.getElementById('olt_inputs_add');
    const switchInputs = document.getElementById('switch_inputs_add');

    if (deviceType === 'olt') {
        oltInputs.style.display = 'block';
        switchInputs.style.display = 'none';
    } else if (deviceType === 'switch') {
        oltInputs.style.display = 'none';
        switchInputs.style.display = 'block';
    }
}
</script>


</div>
@endsection