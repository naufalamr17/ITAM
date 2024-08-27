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

    <x-navbars.sidebar activePage="employee"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="EDIT EMPLOYEE"></x-navbars.navs.auth>
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
                            <form action="{{ route('employee.update', $employee->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mt-3 mb-3">
                                    <label for="nik" class="form-label">NIK</label>
                                    <input type="text" name="nik" id="nik" class="form-control border p-2" value="{{ $employee->nik }}" required>
                                    @error('nik')
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" name="nama" id="nama" class="form-control border p-2" value="{{ $employee->nama }}" required>
                                    @error('nama')
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="organization" class="form-label">Organization</label>
                                    <input type="text" name="organization" id="organization" class="form-control border p-2" list="organization-list" value="{{ $employee->organization }}" required>
                                    <datalist id="organization-list">
                                        @foreach($organizations as $organization)
                                        <option value="{{ $organization }}">{{ $organization }}</option>
                                        @endforeach
                                    </datalist>
                                    @error('organization')
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="job_position" class="form-label">Job Position</label>
                                    <input type="text" name="job_position" id="job_position" class="form-control border p-2" list="job-position-list" value="{{ $employee->job_position }}" required>
                                    <datalist id="job-position-list">
                                        @foreach($job_positions as $position)
                                        <option value="{{ $position }}">{{ $position }}</option>
                                        @endforeach
                                    </datalist>
                                    @error('job_position')
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="job_level" class="form-label">Job Level</label>
                                    <input type="text" name="job_level" id="job_level" class="form-control border p-2" list="job-level-list" value="{{ $employee->job_level }}" required>
                                    <datalist id="job-level-list">
                                        @foreach($job_levels as $level)
                                        <option value="{{ $level }}">{{ $level }}</option>
                                        @endforeach
                                    </datalist>
                                    @error('job_level')
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="branch_name" class="form-label">Branch Name</label>
                                    <select name="branch_name" id="branch_name" class="form-control border p-2" required>
                                        <option value="Head Office" {{ $employee->branch_name == 'Head Office' ? 'selected' : '' }}>Head Office</option>
                                        <option value="Office Kendari" {{ $employee->branch_name == 'Office Kendari' ? 'selected' : '' }}>Office Kendari</option>
                                        <option value="MLP Site Molore" {{ $employee->branch_name == 'MLP Site Molore' ? 'selected' : '' }}>MLP Site Molore</option>
                                    </select>
                                    @error('branch_name')
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="poh" class="form-label">POH</label>
                                    <input type="text" name="poh" id="poh" class="form-control border p-2" value="{{ $employee->poh }}">
                                    @error('poh')
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Update</button>
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