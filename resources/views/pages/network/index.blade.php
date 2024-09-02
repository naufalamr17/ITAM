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
            max-height: 300px;
            /* Set the desired maximum height */
            overflow-y: auto;
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

    <x-navbars.sidebar activePage="monitoring_network"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="MONITORING NETWORK"></x-navbars.navs.auth>
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
                            <div class="ms-auto mb-2">
                                <button id="openModalButton" class="btn bg-gradient-dark mb-0">
                                    <i class="material-icons text-sm">add</i>&nbsp;&nbsp;Add Problem
                                </button>
                            </div>

                            <!-- The Add Problem Modal -->
                            <div id="myModal" class="modal">
                                <div class="modal-content">
                                    <span class="close">&times;</span>
                                    <h5>Add Problem</h5>
                                    <form id="addProblemForm" action="{{ route('problems.store') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="provider" class="form-label">Provider</label>
                                            <select class="form-control" id="provider" name="provider" required style="border: 1px solid #ced4da; padding: 0.75rem 1.25rem; border-radius: 0.25rem;">
                                                <option value="Telkom">Telkom</option>
                                                <option value="Bomm Akses">Bomm Akses</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="issue" class="form-label">Issue</label>
                                            <input type="text" class="form-control" id="issue" name="issue" required style="border: 1px solid #ced4da; padding: 0.75rem 1.25rem; border-radius: 0.25rem;">
                                        </div>
                                        <div class="mb-3">
                                            <label for="details" class="form-label">Details</label>
                                            <textarea class="form-control" id="details" name="details" rows="3" required style="border: 1px solid #ced4da; padding: 0.75rem 1.25rem; border-radius: 0.25rem;"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="start_time" class="form-label">Start Time</label>
                                            <input type="datetime-local" class="form-control" id="start_time" name="start_time" required style="border: 1px solid #ced4da; padding: 0.75rem 1.25rem; border-radius: 0.25rem;">
                                        </div>
                                        <div class="mb-3">
                                            <label for="end_time" class="form-label">End Time</label>
                                            <input type="datetime-local" class="form-control" id="end_time" name="end_time" style="border: 1px solid #ced4da; padding: 0.75rem 1.25rem; border-radius: 0.25rem;">
                                        </div>
                                        <div class="mb-3">
                                            <label for="resolution" class="form-label">Resolution</label>
                                            <textarea class="form-control" id="resolution" name="resolution" rows="3" style="border: 1px solid #ced4da; padding: 0.75rem 1.25rem; border-radius: 0.25rem;"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Add Problem</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <h6 class="ms-3">Telkom</h6>
                        <div class="card-body px-2 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0 inventoryTable" id="network1">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('No') }}</th>
                                            <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Issue') }}</th>
                                            <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Details') }}</th>
                                            <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Start Time') }}</th>
                                            <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('End Time') }}</th>
                                            <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Duration') }}</th>
                                            <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Resolution') }}</th>
                                            <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                        <hr>

                        <h6 class="ms-3">Bomm Akses</h6>
                        <div class="card-body px-2 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0 inventoryTable" id="network2">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('No') }}</th>
                                            <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Issue') }}</th>
                                            <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Details') }}</th>
                                            <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Start Time') }}</th>
                                            <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('End Time') }}</th>
                                            <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Duration') }}</th>
                                            <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Resolution') }}</th>
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

    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('#network1').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('network1') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        class: 'text-center'
                    },
                    {
                        data: 'issue',
                        name: 'issue',
                        class: 'text-center'
                    },
                    {
                        data: 'details',
                        name: 'details',
                        class: 'text-center'
                    },
                    {
                        data: 'start_time',
                        name: 'start_time',
                        class: 'text-center'
                    },
                    {
                        data: 'end_time',
                        name: 'end_time',
                        class: 'text-center'
                    },
                    {
                        data: 'duration',
                        name: 'duration',
                        class: 'text-center'
                    },
                    {
                        data: 'resolution',
                        name: 'resolution',
                        class: 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        class: 'text-center'
                    }
                ],
                "dom": '<"top">rt<"bottom"ip><"clear">',
            });

            $('#network2').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('network2') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        class: 'text-center'
                    },
                    {
                        data: 'issue',
                        name: 'issue',
                        class: 'text-center'
                    },
                    {
                        data: 'details',
                        name: 'details',
                        class: 'text-center'
                    },
                    {
                        data: 'start_time',
                        name: 'start_time',
                        class: 'text-center'
                    },
                    {
                        data: 'end_time',
                        name: 'end_time',
                        class: 'text-center'
                    },
                    {
                        data: 'duration',
                        name: 'duration',
                        class: 'text-center'
                    },
                    {
                        data: 'resolution',
                        name: 'resolution',
                        class: 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        class: 'text-center'
                    }
                ],
                "dom": '<"top">rt<"bottom"ip><"clear">',
            });

            // Open Modal
            $('#openModalButton').click(function() {
                $('#myModal').show();
            });

            // Close Modal
            $('.close').click(function() {
                $('#myModal').hide();
            });

            // Close Modal when clicking outside of it
            $(window).click(function(event) {
                if ($(event.target).is('#myModal')) {
                    $('#myModal').hide();
                }
            });

            // Input search functionality
            $('#searchbox').on('keyup', function() {
                $('#network1').DataTable().search($(this).val()).draw();
                $('#network2').DataTable().search($(this).val()).draw();
            });
        });
    </script>
</x-layout>