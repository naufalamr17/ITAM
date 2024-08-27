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
        <x-navbars.navs.auth titlePage="EMPLOYEE"></x-navbars.navs.auth>
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

                        <div class="d-flex flex-wrap align-items-center mb-4 p-3">
                            <div class="mb-2 me-2">
                                <input type="text" class="form-control border p-2" name="searchbox" id="searchbox" placeholder="Search..." style="max-width: 300px;" autofocus>
                            </div>
                            @if (Auth::check() && Auth::user()->status != 'Viewers')
                            <div class="ms-auto mb-2">
                                <a class="btn bg-gradient-dark mb-0" id="openModalButton" data-bs-toggle="modal" data-bs-target="#bastModal">
                                    <i class="material-icons text-sm">add</i>&nbsp;&nbsp;Add Employee
                                </a>
                            </div>
                            @endif
                        </div>

                        <!-- The Modal -->
                        <div id="myModal" class="modal">
                            <div class="modal-content">
                                <span class="close">&times;</span>
                                <form action="{{ route('employee.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row"> <!-- Additional Fields Column -->
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="nik" class="form-label">NIK</label>
                                                    <input type="text" name="nik" id="nik" class="form-control border p-2" required>
                                                    @error('nik')
                                                    <span class="text-danger text-sm">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="nama" class="form-label">Nama</label>
                                                    <input type="text" name="nama" id="nama" class="form-control border p-2" required>
                                                    @error('nama')
                                                    <span class="text-danger text-sm">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="organization" class="form-label">Organization</label>
                                                    <input type="text" name="organization" id="organization" class="form-control border p-2" list="organization-list" required>
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
                                                    <input type="text" name="job_position" id="job_position" class="form-control border p-2" list="job-position-list" required>
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
                                                    <input type="text" name="job_level" id="job_level" class="form-control border p-2" list="job-level-list" required>
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
                                                        @foreach($branch_names as $branch)
                                                        <option value="{{ $branch }}">{{ $branch }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('branch_name')
                                                    <span class="text-danger text-sm">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="poh" class="form-label">POH</label>
                                                    <input type="text" name="poh" id="poh" class="form-control border p-2">
                                                    @error('poh')
                                                    <span class="text-danger text-sm">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card-body px-2 pb-2">
                            <div class="table-responsive p-0">
                                <table id="inventoryTable" class="table text-center align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-secondary text-xxs font-weight-bolder opacity-7">{{ __('NIK') }}</th>
                                            <th class="text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Nama') }}</th>
                                            <th class="text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Organization') }}</th>
                                            <th class="text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Job Position') }}</th>
                                            <th class="text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Job Level') }}</th>
                                            <th class="text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Branch Name') }}</th>
                                            <th class="text-secondary text-xxs font-weight-bolder opacity-7">{{ __('POH') }}</th>
                                            <th class="text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
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

    <script>
        $(document).ready(function() {
            var table = $('#inventoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('employee') }}",
                columns: [{
                        data: 'nik',
                        name: 'nik'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'organization',
                        name: 'organization'
                    },
                    {
                        data: 'job_position',
                        name: 'job_position'
                    },
                    {
                        data: 'job_level',
                        name: 'job_level'
                    },
                    {
                        data: 'branch_name',
                        name: 'branch_name'
                    },
                    {
                        data: 'poh',
                        name: 'poh'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                "pageLength": 50,
                "columnDefs": [{
                        "orderable": true,
                        "targets": 0
                    }, // Enable ordering on the 8th column (index 7)
                    {
                        "orderable": false,
                        "targets": '_all'
                    } // Disable ordering on all other columns
                ],
                "order": [
                    [0, 'desc']
                ],
                "dom": '<"top">rt<"bottom"ip><"clear">',
            });

            // Add the search functionality
            $('#searchbox').on('keyup', function() {
                table.search(this.value).draw();

                if (this.value.length >= 13) {
                    setTimeout(() => {
                        this.select(); // Seleksi seluruh teks di dalam kotak pencarian
                    }, 2000);
                }
            });
        });
    </script>

    <script>
        document.getElementById('openModalButton').addEventListener('click', function() {
            var modal = document.getElementById('myModal');
            modal.style.display = "block";
        });

        document.getElementsByClassName('close')[0].addEventListener('click', function() {
            var modal = document.getElementById('myModal');
            modal.style.display = "none";
        });

        window.onclick = function(event) {
            var modal = document.getElementById('myModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };
    </script>
</x-layout>