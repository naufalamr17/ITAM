<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="inventory"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="EDIT ASSET"></x-navbars.navs.auth>
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
                            <form method="POST" action="{{ route('update_inventory', $asset->id) }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Kode Asset -->
                                        <div class="form-group">
                                            <label for="asset_code">Kode Asset</label>
                                            <input id="asset_code" class="form-control border p-2" type="text" name="asset_code" value="{{ old('asset_code', $asset->asset_code) }}" autofocus>
                                            @if ($errors->has('asset_code'))
                                            <div class="text-danger mt-2">{{ $errors->first('asset_code') }}</div>
                                            @endif
                                        </div>

                                        <!-- Lokasi -->
                                        <div class="form-group">
                                            <label for="location">Location</label>
                                            <select id="location" class="form-control border p-2" name="location" required readonly>
                                                <option value="" selected disabled>Select Location</option>
                                                <option value="Head Office" {{ $asset->location == 'Head Office' ? 'selected' : 'disabled' }}>Head Office</option>
                                                <option value="Office Kendari" {{ $asset->location == 'Office Kendari' ? 'selected' : 'disabled' }}>Office Kendari</option>
                                                <option value="Site Molore" {{ $asset->location == 'Site Molore' ? 'selected' : 'disabled' }}>Site Molore</option>
                                            </select>
                                            @if ($errors->has('location'))
                                            <div class="text-danger mt-2">{{ $errors->first('location') }}</div>
                                            @endif
                                        </div>

                                        <!-- Deskripsi -->
                                        <div class="form-group">
                                            <label for="description">Category</label>
                                            <input list="asset_categories" id="description" class="form-control border p-2" name="description" required value="{{ old('description', $asset->description) }}">
                                            <datalist id="asset_categories">
                                                <option value="Laptop">
                                                <option value="PC">
                                                <option value="Wireless">
                                                <option value="Printer">
                                            </datalist>
                                            @if ($errors->has('description'))
                                            <div class="text-danger mt-2">{{ $errors->first('description') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="comp_name">Comp Name</label>
                                            <input id="comp_name" class="form-control border p-2" type="text" name="comp_name" value="{{ old('comp_name', $asset->comp_name) }}" autofocus>
                                            @if ($errors->has('comp_name'))
                                            <div class="text-danger mt-2">{{ $errors->first('comp_name') }}</div>
                                            @endif
                                        </div>

                                        <!-- Merk -->
                                        <div class="form-group">
                                            <label for="merk">Merk</label>
                                            <input list="merk-list" id="merk" name="merk" class="form-control border p-2" value="{{ old('merk', $asset->merk) }}">
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

                                        <!-- Type -->
                                        <div class="form-group">
                                            <label for="type">Type</label>
                                            <input id="type" class="form-control border p-2" type="text" name="type" value="{{ old('type', $asset->type) }}">
                                            @if ($errors->has('type'))
                                            <div class="text-danger mt-2">{{ $errors->first('type') }}</div>
                                            @endif
                                        </div>

                                        <!-- Spesifikasi -->
                                        <div class="form-group">
                                            <label for="specification">Specification</label>
                                            <input id="specification" class="form-control border p-2" type="text" name="specification" value="{{ old('specification', $asset->specification) }}">
                                            @if ($errors->has('specification'))
                                            <div class="text-danger mt-2">{{ $errors->first('specification') }}</div>
                                            @endif
                                        </div>

                                        <!-- Nomor Seri -->
                                        <div class="form-group">
                                            <label for="serial_number">Serial Number</label>
                                            <input id="serial_number" class="form-control border p-2" type="text" name="serial_number" value="{{ old('serial_number', $asset->serial_number) }}">
                                            @if ($errors->has('serial_number'))
                                            <div class="text-danger mt-2">{{ $errors->first('serial_number') }}</div>
                                            @endif
                                        </div>

                                        <!-- OS -->
                                        <div class="form-group">
                                            <label for="os">OS</label>
                                            <input id="os" class="form-control border p-2" type="text" name="os" value="{{ old('os', $asset->os) }}">
                                            @if ($errors->has('os'))
                                            <div class="text-danger mt-2">{{ $errors->first('os') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="installed_apps">Installed Apps</label>
                                            <input id="installed_apps" class="form-control border p-2" type="text" name="installed_apps" value="{{ old('installed_apps', $asset->installed_apps) }}">
                                            @if ($errors->has('os'))
                                            <div class="text-danger mt-2">{{ $errors->first('installed_apps') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Tanggal Perolehan -->
                                        <div class="form-group">
                                            <label for="acquisition_date">Tanggal Perolehan</label>
                                            <input type="date" class="form-control border p-2" id="acquisition_date" name="acquisition_date" value="{{ old('acquisition_date', $asset->acquisition_date) }}" required>
                                            @if ($errors->has('acquisition_date'))
                                            <div class="text-danger mt-2">{{ $errors->first('acquisition_date') }}</div>
                                            @endif
                                        </div>

                                        <!-- Nilai Perolehan -->
                                        <div class="form-group">
                                            <label for="acquisition_value">Nilai Perolehan</label>
                                            <input id="acquisition_value" class="form-control border p-2" type="number" name="acquisition_value" value="{{ old('acquisition_value', $asset->acquisition_value) }}">
                                            @if ($errors->has('acquisition_value'))
                                            <div class="text-danger mt-2">{{ $errors->first('acquisition_value') }}</div>
                                            @endif
                                        </div>

                                        <!-- Tanggal Serah Terima -->
                                        <div class="form-group" style="display: none;">
                                            <label for="hand_over_date">Tanggal Serah Terima</label>
                                            <input type="date" class="form-control border p-2" id="hand_over_date" name="hand_over_date" value="{{ now()->format('Y-m-d') }}">
                                            @if ($errors->has('hand_over_date'))
                                            <div class="text-danger mt-2">{{ $errors->first('hand_over_date') }}</div>
                                            @endif
                                        </div>

                                        <!-- NIK -->
                                        <div class="form-group">
                                            <label for="nik">NIK</label>
                                            <input id="nik" class="form-control border p-2" type="text" name="nik" value="{{ old('nik', $asset->nik) }}">
                                            @if ($errors->has('nik'))
                                            <div class="text-danger mt-2">{{ $errors->first('nik') }}</div>
                                            @endif
                                        </div>

                                        <!-- User -->
                                        <div class="form-group">
                                            <label for="user">User</label>
                                            <input id="user" class="form-control border p-2" type="text" name="user" value="{{ old('user', $asset->user) }}">
                                            @if ($errors->has('user'))
                                            <div class="text-danger mt-2">{{ $errors->first('user') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="job_position">Job Position</label>
                                            <input id="job_position" class="form-control border p-2" type="text" name="job_position" value="{{ old('job_position', $asset->job_position) }}">
                                            @if ($errors->has('job_position'))
                                            <div class="text-danger mt-2">{{ $errors->first('job_position') }}</div>
                                            @endif
                                        </div>

                                        <!-- Dept -->
                                        <div class="form-group">
                                            <label for="dept">Dept</label>
                                            <input id="dept" class="form-control border p-2" type="text" name="dept" value="{{ old('dept', $asset->dept) }}">
                                            @if ($errors->has('dept'))
                                            <div class="text-danger mt-2">{{ $errors->first('dept') }}</div>
                                            @endif
                                        </div>

                                        <!-- Remarks -->
                                        <div class="form-group">
                                            <label for="note">Remarks</label>
                                            <input id="note" class="form-control border p-2" type="text" name="note" value="{{ old('note', $userhist ? $userhist->note : '') }}">
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

                                <!-- Submit Button -->
                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-success btn-block">Update Asset</button>
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
</x-layout>