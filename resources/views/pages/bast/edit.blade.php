<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <!-- Include DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <!-- Custom CSS to make the DataTable smaller -->
    <style>
        #inventoryTable_wrapper .dataTables_length,
        #inventoryTable_wrapper .dataTables_filter,
        #inventoryTable_wrapper .dataTables_info,
        #inventoryTable_wrapper .dataTables_paginate {
            font-size: 0.75rem;
        }

        #inventoryTable {
            font-size: 0.75rem;
        }

        #inventoryTable th,
        #inventoryTable td {
            padding: 4px 8px;
        }

        /* CSS to make the table scrollable */
        .table-responsive {
            max-height: 400px;
            /* Set the desired maximum height */
            overflow-y: auto;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/quagga/dist/quagga.min.js"></script>
    <style>
        #interactive {
            width: 100%;
            height: 400px;
            overflow: hidden;
            position: relative;
        }

        #interactive video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #result {
            margin-top: 20px;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 3% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Media query for landscape orientation on mobile devices */
        @media only screen and (max-width: 600px) {
            .modal-content {
                width: 90%;
                max-width: none;
                height: 40vh;
                overflow-y: auto;
            }
        }
    </style>

    <x-navbars.sidebar activePage="bast"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="GENERATE BAST"></x-navbars.navs.auth>
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

                        <div class="container">

                            <!-- Edit Form -->
                            <form action="{{ route('bast.update', $bast->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row mt-3">
                                    <!-- First Column -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="date" class="form-label">Date</label>
                                            <input type="date" class="form-control border p-2" id="date" name="date" value="{{ $bast->date }}" required readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="pic" class="form-label">PIC</label>
                                            <input type="text" class="form-control border p-2" id="pic" name="pic" value="{{ $bast->pic }}" required readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="nik_user" class="form-label">NIK User</label>
                                            <select class="form-control" id="nik_user" name="nik_user" required>
                                                <option value="">Select NIK User</option>
                                                @foreach ($employees as $employee)
                                                <option value="{{ $employee->nik }}" {{ $bast->nik_user == $employee->nik ? 'selected' : '' }}>
                                                    {{ $employee->nik }} - {{ $employee->nama }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="jenis_barang" class="form-label">Jenis Barang</label>
                                            <input type="text" class="form-control border p-2" id="jenis_barang" name="jenis_barang" value="{{ $bast->jenis_barang }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="merk" class="form-label">Merk</label>
                                            <input type="text" class="form-control border p-2" id="merk" name="merk" value="{{ $bast->merk }}" required>
                                        </div>
                                    </div>
                                    <!-- Second Column -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="type" class="form-label">Type/Model</label>
                                            <input type="text" class="form-control border p-2" id="type" name="type" value="{{ $bast->type }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="serial_number" class="form-label">Serial Number</label>
                                            <input type="text" class="form-control border p-2" id="serial_number" name="serial_number" value="{{ $bast->serial_number }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="spesifikasi" class="form-label">Spesifikasi</label>
                                            <textarea class="form-control border p-2" id="spesifikasi" name="spesifikasi">{{ $bast->spesifikasi }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="scan_file" class="form-label">Scan File</label>
                                            <input type="file" class="form-control border p-2" id="scan_file" name="scan_file">
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                    <a href="{{ route('bast') }}" class="btn btn-secondary">Cancel</a>
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

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <!-- Include Selectize.js CSS and JS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#nik_user').selectize({
                create: false,
                sortField: 'text'
            });
        });
    </script>
</x-layout>