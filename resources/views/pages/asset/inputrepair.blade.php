<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="repair_inventory"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="BREAKDOWN ASSET"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                        @endif
                        <div class="p-6">
                            <form method="POST" action="{{ route('store_repair') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="asset_code">Kode Asset</label>
                                            <input id="asset_code" class="form-control border p-2" type="text" name="asset_code" value="{{ old('asset_code') }}" autofocus>
                                            @if ($errors->has('asset_code'))
                                            <div class="text-danger mt-2">{{ $errors->first('asset_code') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="location">Location</label>
                                            <select id="location" class="form-control border p-2" name="location" disabled>
                                                <option value="" selected disabled>Select Location</option>
                                                <option value="Head Office">Head Office</option>
                                                <option value="Office Kendari">Office Kendari</option>
                                                <option value="Site Molore">Site Molore</option>
                                            </select>
                                            @if ($errors->has('location'))
                                            <div class="text-danger mt-2">{{ $errors->first('location') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="merk">Merk</label>
                                            <input list="merk-list" id="merk" name="merk" class="form-control border p-2" disabled>
                                            <datalist id="merk-list">
                                                <option value="Apple"></option>
                                                <option value="Dell"></option>
                                                <option value="HP"></option>
                                                <option value="Lenovo"></option>
                                                <option value="Asus"></option>
                                            </datalist>
                                            @if ($errors->has('merk'))
                                            <div class="text-danger mt-2">{{ $errors->first('merk') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="type">Type</label>
                                            <input id="type" class="form-control border p-2" type="text" name="type" value="{{ old('type') }}" disabled>
                                            @if ($errors->has('type'))
                                            <div class="text-danger mt-2">{{ $errors->first('type') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="specification">Specification</label>
                                            <input id="specification" class="form-control border p-2" type="text" name="specification" value="{{ old('specification') }}" disabled>
                                            @if ($errors->has('specification'))
                                            <div class="text-danger mt-2">{{ $errors->first('specification') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Deskripsi</label>
                                            <textarea id="description" class="form-control border p-2" name="description" disabled>{{ old('description') }}</textarea>
                                            @if ($errors->has('description'))
                                            <div class="text-danger mt-2">{{ $errors->first('description') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="serial_number">Serial Number</label>
                                            <input id="serial_number" class="form-control border p-2" type="text" name="serial_number" value="{{ old('serial_number') }}" disabled>
                                            @if ($errors->has('serial_number'))
                                            <div class="text-danger mt-2">{{ $errors->first('serial_number') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="acquisition_date">Tanggal Perolehan</label>
                                            <input type="date" class="form-control border p-2" id="acquisition_date" name="acquisition_date" value="{{ old('acquisition_date') }}" disabled>
                                            @if ($errors->has('acquisition_date'))
                                            <div class="text-danger mt-2">{{ $errors->first('acquisition_date') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="useful_life">Umur ekonomis (Tahun)</label>
                                            <input type="number" class="form-control border p-2" id="useful_life" name="useful_life" value="{{ old('useful_life') }}" disabled>
                                            @if ($errors->has('useful_life'))
                                            <div class="text-danger mt-2">{{ $errors->first('useful_life') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="acquisition_value">Nilai Perolehan</label>
                                            <input id="acquisition_value" class="form-control border p-2" type="number" name="acquisition_value" value="{{ old('acquisition_value') }}" disabled>
                                            @if ($errors->has('acquisition_value'))
                                            <div class="text-danger mt-2">{{ $errors->first('acquisition_value') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select id="status" class="form-control border p-2" type="number" name="status" value="{{ old('status') }}">
                                                <option value="" selected disabled>Select Status</option>
                                                <option value="Good">Good</option>
                                                <option value="Breakdown">Breakdown</option>
                                                <option value="Repair">Repair</option>
                                                <option value="Waiting Dispose" disabled>Waiting Dispose</option>
                                            </select>
                                        </div>

                                        <div id="additional-fields-repair" style="display:none;">
                                            <div class="form-group">
                                                <label for="tanggal_kerusakan_repair">Tanggal Kerusakan</label>
                                                <input id="tanggal_kerusakan_repair" class="form-control border p-2" type="date" name="tanggal_kerusakan_repair" value="{{ old('tanggal_kerusakan_repair') }}">
                                                <!-- Add the 'required' attribute to make the field mandatory -->
                                                @if ($errors->has('tanggal_kerusakan_repair'))
                                                <div class="text-danger mt-2">{{ $errors->first('tanggal_kerusakan_repair') }}</div>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label for="tanggal_pengembalian_repair">Tanggal Pengembalian</label>
                                                <input id="tanggal_pengembalian_repair" class="form-control border p-2" type="date" name="tanggal_pengembalian_repair" value="{{ old('tanggal_pengembalian_repair') }}">
                                                <!-- No need for 'required' attribute if this field is optional -->
                                                @if ($errors->has('tanggal_pengembalian_repair'))
                                                <div class="text-danger mt-2">{{ $errors->first('tanggal_pengembalian_repair') }}</div>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label for="remarks_repair">Remarks</label>
                                                <textarea id="remarks_repair" class="form-control border p-2" name="remarks_repair">{{ old('remarks_repair') }}</textarea>
                                                <!-- Add the 'required' attribute to make the field mandatory -->
                                                @if ($errors->has('remarks_repair'))
                                                <div class="text-danger mt-2">{{ $errors->first('remarks_repair') }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div id="additional-fields-breakdown" style="display:none;">
                                            <div class="form-group">
                                                <label for="tanggal_kerusakan_breakdown">Tanggal Kerusakan</label>
                                                <input id="tanggal_kerusakan_breakdown" class="form-control border p-2" type="date" name="tanggal_kerusakan_breakdown" value="{{ old('tanggal_kerusakan_breakdown') }}">
                                                @if ($errors->has('tanggal_kerusakan_breakdown'))
                                                <div class="text-danger mt-2">{{ $errors->first('tanggal_kerusakan_breakdown') }}</div>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label for="remarks_breakdown">Remarks</label>
                                                <textarea id="remarks_breakdown" class="form-control border p-2" name="remarks_breakdown">{{ old('remarks_breakdown') }}</textarea>
                                                @if ($errors->has('remarks_breakdown'))
                                                <div class="text-danger mt-2">{{ $errors->first('remarks_breakdown') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-success btn-block">Submit</button>
                                    <a href="{{ route('repair_inventory') }}" class="btn btn-danger">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <x-footers.auth></x-footers.auth>
        </div>
    </main>
    <x-plugins></x-plugins>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.getElementById('status').addEventListener('change', function() {
            var additionalFieldsRepair = document.getElementById('additional-fields-repair');
            var additionalFieldsBreakdown = document.getElementById('additional-fields-breakdown');

            // Hide both additional fields by default
            additionalFieldsRepair.style.display = 'none';
            additionalFieldsBreakdown.style.display = 'none';

            // Show the appropriate additional fields based on the selected status
            if (this.value === 'Repair') {
                additionalFieldsRepair.style.display = 'block';
            } else if (this.value === 'Breakdown') {
                additionalFieldsBreakdown.style.display = 'block';
            }
        });

        // Menggunakan jQuery untuk memudahkan pemilihan elemen dan manipulasi DOM
        $(document).ready(function() {
            // Event listener untuk input asset_code
            $('#asset_code').on('input', function() {
                // Ambil nilai yang dimasukkan ke dalam input asset_code
                var assetCode = $(this).val();

                if (assetCode.length >= 13) {
                    // Lakukan permintaan AJAX untuk mendapatkan data inventaris berdasarkan kode aset
                    $.ajax({
                        type: 'GET',
                        url: '/get-inventory-data', // Ganti dengan URL yang benar sesuai dengan rute Anda
                        data: {
                            asset_code: assetCode
                        },
                        success: function(response) {
                            // Cek jika status adalah 'dispose' dan tampilkan alert
                            if (response.status === 'Dispose' || response.status === 'Waiting Dispose') {
                                $('#location').val('');
                                $('#description').val('');
                                $('#serial_number').val('');
                                $('#acquisition_date').val('');
                                $('#useful_life').val('');
                                $('#acquisition_value').val('');
                                $('#status').val('');
                                $('#merk').val('')
                                $('#type').val('')
                                $('#specification').val('')
                                alert('Status is waiting dispose or dispose');
                            } else {
                                // Isi input lainnya dengan data yang diterima dari permintaan AJAX
                                $('#location').val(response.location);
                                $('#description').val(response.description);
                                $('#serial_number').val(response.serial_number);
                                $('#acquisition_date').val(response.acquisition_date);
                                $('#useful_life').val(response.useful_life);
                                $('#acquisition_value').val(response.acquisition_value);
                                $('#status').val(response.status);
                                $('#merk').val(response.merk);
                                $('#type').val(response.type);
                                $('#specification').val(response.specification);

                                console.log(response);
                            }
                        },
                        error: function(xhr, status, error) {
                            $('#location').val('');
                            $('#description').val('');
                            $('#serial_number').val('');
                            $('#acquisition_date').val('');
                            $('#useful_life').val('');
                            $('#acquisition_value').val('');
                            $('#status').val('');
                            $('#merk').val('')
                            $('#type').val('')
                            $('#specification').val('')
                            console.error(xhr.responseText); // Log pesan kesalahan ke konsol
                            // Atau tampilkan pesan kesalahan kepada pengguna
                            alert('Data tidak ditemukan. Silakan coba lagi.');
                        }
                    });
                }
            });
        });
    </script>
</x-layout>