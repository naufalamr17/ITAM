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

                        <div class="d-flex flex-wrap align-items-center mb-4 p-3">
                            <div class="mb-2 me-2">
                                <input type="text" class="form-control border p-2" name="searchbox" id="searchbox" placeholder="Search..." style="max-width: 300px;" autofocus>
                            </div>
                            @if (Auth::check() && Auth::user()->status != 'Viewers')
                            <div class="ms-auto mb-2">
                                <a class="btn bg-gradient-dark mb-0" id="openModalButton" data-bs-toggle="modal" data-bs-target="#bastModal">
                                    <i class="material-icons text-sm">add</i>&nbsp;&nbsp;Generate BAST
                                </a>
                            </div>
                            @endif
                        </div>

                        <!-- The Modal -->
                        <div id="myModal" class="modal">
                            <div class="modal-content">
                                <span class="close">&times;</span>
                                <form action="" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <!-- First Column -->
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="date" class="form-label">Date</label>
                                                    <input type="date" class="form-control border" id="date" name="date" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="pic" class="form-label">PIC</label>
                                                    <input type="text" class="form-control border" id="pic" name="pic" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="nik_user" class="form-label">NIK User</label>
                                                    <input type="text" class="form-control border" id="nik_user" name="nik_user" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="nama_user" class="form-label">Nama User</label>
                                                    <input type="text" class="form-control border" id="nama_user" name="nama_user" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="jabatan_user" class="form-label">Jabatan User</label>
                                                    <input type="text" class="form-control border" id="jabatan_user" name="jabatan_user" required>
                                                </div>
                                            </div>
                                            <!-- Second Column -->
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="jenis_barang" class="form-label">Jenis Barang</label>
                                                    <input type="text" class="form-control border" id="jenis_barang" name="jenis_barang" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="merk" class="form-label">Merk</label>
                                                    <input type="text" class="form-control border" id="merk" name="merk" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="type_model" class="form-label">Type/Model</label>
                                                    <input type="text" class="form-control border" id="type_model" name="type_model" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="serial_number" class="form-label">Serial Number</label>
                                                    <input type="text" class="form-control border" id="serial_number" name="serial_number" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="spesifikasi" class="form-label">Spesifikasi</label>
                                                    <textarea class="form-control border" id="spesifikasi" name="spesifikasi"></textarea>
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
                                <table id="inventoryTable" class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('No') }}</th>
                                            <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Date') }}</th>
                                            <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('PIC') }}</th>
                                            <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('NIK User') }}</th>
                                            <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Nama User') }}</th>
                                            <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Jabatan User') }}</th>
                                            <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Jenis Barang') }}</th>
                                            <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Merk') }}</th>
                                            <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Type') }}</th>
                                            <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Serial Number') }}</th>
                                            <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Spesifikasi') }}</th>
                                            <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Action') }}</th>
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
    <!-- Initialize DataTable -->
    <script>
        $(document).ready(function() {
            var table = $('#inventoryTable').DataTable({
                "pageLength": 50,
                "columnDefs": [{
                        "orderable": true,
                        "targets": 6
                    }, // Enable ordering on the 8th column (index 7)
                    {
                        "orderable": false,
                        "targets": '_all'
                    } // Disable ordering on all other columns
                ],
                "order": [
                    [5, 'desc']
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