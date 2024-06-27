<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="inventory"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="INPUT ASSET"></x-navbars.navs.auth>
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
                            <form method="POST" action="{{ route('store_inventory') }}">
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
                                            <select id="location" class="form-control border p-2" name="location" required readonly>
                                                <option value="" selected disabled>Select Location</option>
                                                <option value="Head Office" {{ $userLocation == 'Head Office' ? 'selected' : 'disabled' }}>Head Office</option>
                                                <option value="Office Kendari" {{ $userLocation == 'Office Kendari' ? 'selected' : 'disabled' }}>Office Kendari</option>
                                                <option value="Site Molore" {{ $userLocation == 'Site Molore' ? 'selected' : 'disabled' }}>Site Molore</option>
                                            </select>
                                            @if ($errors->has('location'))
                                            <div class="text-danger mt-2">{{ $errors->first('location') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Deskripsi</label>
                                            <textarea id="description" class="form-control border p-2" name="description" required>{{ old('description') }}</textarea>
                                            @if ($errors->has('description'))
                                            <div class="text-danger mt-2">{{ $errors->first('description') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="merk">Merk</label>
                                            <input list="merk-list" id="merk" name="merk" class="form-control border p-2" value="{{ old('merk') }}">
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
                                            <input id="type" class="form-control border p-2" type="text" name="type" value="{{ old('type') }}">
                                            @if ($errors->has('type'))
                                            <div class="text-danger mt-2">{{ $errors->first('type') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="specification">Specification</label>
                                            <input id="specification" class="form-control border p-2" type="text" name="specification" value="{{ old('specification') }}">
                                            @if ($errors->has('specification'))
                                            <div class="text-danger mt-2">{{ $errors->first('specification') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="serial_number">Serial Number</label>
                                            <input id="serial_number" class="form-control border p-2" type="text" name="serial_number" value="{{ old('serial_number') }}">
                                            @if ($errors->has('serial_number'))
                                            <div class="text-danger mt-2">{{ $errors->first('serial_number') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="os">OS</label>
                                            <input id="os" class="form-control border p-2" type="text" name="os" value="{{ old('os') }}">
                                            @if ($errors->has('os'))
                                            <div class="text-danger mt-2">{{ $errors->first('os') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="installed_apps">Installed Apps</label>
                                            <input id="installed_apps" class="form-control border p-2" type="text" name="installed_apps" value="{{ old('installed_apps') }}">
                                            @if ($errors->has('os'))
                                            <div class="text-danger mt-2">{{ $errors->first('installed_apps') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="acquisition_date">Tanggal Perolehan</label>
                                            <input type="date" class="form-control border p-2" id="acquisition_date" name="acquisition_date" value="{{ old('acquisition_date') }}" required>
                                            @if ($errors->has('acquisition_date'))
                                            <div class="text-danger mt-2">{{ $errors->first('acquisition_date') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="acquisition_value">Nilai Perolehan</label>
                                            <input id="acquisition_value" class="form-control border p-2" type="number" name="acquisition_value" value="{{ old('acquisition_value', 0) }}">
                                            @if ($errors->has('acquisition_value'))
                                            <div class="text-danger mt-2">{{ $errors->first('acquisition_value') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="hand_over_date">Tanggal Serah Terima</label>
                                            <input type="date" class="form-control border p-2" id="hand_over_date" name="hand_over_date" value="{{ old('hand_over_date') }}">
                                            @if ($errors->has('hand_over_date'))
                                            <div class="text-danger mt-2">{{ $errors->first('hand_over_date') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="nik">NIK</label>
                                            <input id="nik" class="form-control border p-2" type="text" name="nik" value="{{ old('nik') }}">
                                            @if ($errors->has('nik'))
                                            <div class="text-danger mt-2">{{ $errors->first('nik') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="user">User</label>
                                            <input id="user" class="form-control border p-2" type="text" name="user" value="{{ old('user') }}">
                                            @if ($errors->has('user'))
                                            <div class="text-danger mt-2">{{ $errors->first('user') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="job_position">Job Position</label>
                                            <input id="job_position" class="form-control border p-2" type="text" name="job_position" value="{{ old('job_position') }}">
                                            @if ($errors->has('job_position'))
                                            <div class="text-danger mt-2">{{ $errors->first('job_position') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="dept">Dept</label>
                                            <input id="dept" class="form-control border p-2" type="text" name="dept" value="{{ old('dept') }}">
                                            @if ($errors->has('dept'))
                                            <div class="text-danger mt-2">{{ $errors->first('dept') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="note">Remarks</label>
                                            <input id="note" class="form-control border p-2" type="text" name="note" value="{{ old('note') }}">
                                            @if ($errors->has('note'))
                                            <div class="text-danger mt-2">{{ $errors->first('note') }}</div>
                                            @endif
                                            <div class="form-check mt-2">
                                                <input id="store_to_database" type="checkbox" class="form-check-input" name="store_to_database" value="true" @if(old('store_to_database')=='true' ) checked @endif>
                                                <label for="store_to_database" class="form-check-label">Add user to history</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-success btn-block">Add Asset</button>
                                    <a href="{{ route('inventory') }}" class="btn btn-danger">Cancel</a>
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

    <script>
        document.getElementById('asset_category').addEventListener('change', function() {
            var usefulLifeInput = document.getElementById('useful_life');
            var selectedCategory = this.value;
            var usefulLife;

            switch (selectedCategory) {
                case 'Kendaraan':
                    usefulLife = 8;
                    break;
                case 'Peralatan':
                    usefulLife = 4;
                    break;
                case 'Bangunan':
                    usefulLife = 20;
                    break;
                case 'Mesin':
                    usefulLife = 16;
                    break;
                case 'Alat Berat':
                    usefulLife = 8;
                    break;
                case 'Alat Lab & Preparasi':
                    usefulLife = 16;
                    break;
                default:
                    usefulLife = '';
            }

            usefulLifeInput.value = usefulLife;
        });

        // Trigger change event to set initial value if a category is already selected
        document.getElementById('asset_category').dispatchEvent(new Event('change'));
    </script>
</x-layout>