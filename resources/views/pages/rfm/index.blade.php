<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <style>
        /* Ukuran font dan padding DataTable */
        #rfmTable_wrapper .dataTables_length,
        #rfmTable_wrapper .dataTables_filter,
        #rfmTable_wrapper .dataTables_info,
        #rfmTable_wrapper .dataTables_paginate {
            font-size: 0.75rem;
        }

        #rfmTable {
            font-size: 0.75rem;
        }

        #rfmTable th,
        #rfmTable td {
            padding: 4px 8px;
        }

        /* Scroll table */
        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>

    <x-navbars.sidebar activePage="rfm"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="RFM"></x-navbars.navs.auth>
        <!-- End Navbar -->

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <!-- Notifikasi Error -->
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

                        <!-- Filter dan tombol tambah -->
                        <div class="d-flex flex-wrap align-items-center mb-4 p-3">
                            <div class="mb-2 me-2">
                                <input type="text" class="form-control border p-2" id="searchbox"
                                       placeholder="Cari RFM..." style="max-width: 300px;" autofocus>
                            </div>
                            <div class="mb-2 me-2">
                                <input type="number" class="form-control border p-2" id="yearFilter"
                                       placeholder="Filter Tahun (created_at)">
                            </div>

                            @if (Auth::check() && Auth::user()->status != 'Viewers')
                                <div class="ms-auto mb-2">
                                    <a class="btn bg-gradient-dark mb-0" data-bs-toggle="modal" data-bs-target="#rfmModal">
                                        <i class="material-icons text-sm">add</i>&nbsp;&nbsp;Tambah RFM
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Modal Tambah RFM -->
                        <div class="modal fade" id="rfmModal" tabindex="-1" aria-labelledby="rfmModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="rfmModalLabel">Tambah RFM</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('rfms.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <!-- Kolom Kiri -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="no_rfm" class="form-label">No RFM</label>
                                                        <input type="text" class="form-control border p-2" id="no_rfm" name="no_rfm" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="dokumen_pdf" class="form-label">Dokumen PDF (Opsional)</label>
                                                        <input type="file" class="form-control border p-2" id="dokumen_pdf" name="dokumen_pdf" accept="application/pdf">
                                                        <small class="text-muted">Maks 10MB, format PDF</small>
                                                    </div>
                                                </div>

                                                <!-- Kolom Kanan -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="deskripsi" class="form-label">Deskripsi</label>
                                                        <textarea class="form-control border p-2" id="deskripsi" name="deskripsi" rows="5" required></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Tabel Data -->
                        <div class="card-body px-2 pb-2">
                            <div class="table-responsive p-0">
                                <table id="rfmTable" class="table text-center align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                            <th class="text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                            <th class="text-secondary text-xxs font-weight-bolder opacity-7">No RFM</th>
                                            <th class="text-secondary text-xxs font-weight-bolder opacity-7">Deskripsi</th>
                                            <th class="text-secondary text-xxs font-weight-bolder opacity-7">Dokumen</th>
                                            <th class="text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
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

    <!-- jQuery & DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            const table = $('#rfmTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('rfms.data') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'no_rfm', name: 'no_rfm' },
                    {
                        data: 'deskripsi',
                        name: 'deskripsi',
                        render: function (data, type, row) {
                            if (type === 'display' && data) {
                                const text = $('<div>').text(data).html();
                                return text.length > 120 ? text.substring(0, 120) + '…' : text;
                            }
                            return data ?? '-';
                        }
                    },
                    {
                        data: 'dokumen_pdf',
                        name: 'dokumen_pdf',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            if (!data) return '-';
                            return `<a href="/rfm/${row.id}/download" class="btn btn-sm btn-info mt-3" target="_blank">Lihat PDF</a>`;
                        }
                    },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                pageLength: 50,
                columnDefs: [
                    { orderable: true, targets: 1 },
                    { orderable: false, targets: '_all' }
                ],
                order: [[1, 'desc']],
                dom: '<"top">rt<"bottom"ip><"clear">'
            });

            // Pencarian global
            $('#searchbox').on('keyup', function () {
                table.search(this.value).draw();
            });

            // Filter tahun (berdasarkan kolom created_at)
            $('#yearFilter').on('keyup', function () {
                const year = $(this).val().trim();
                if (year !== '') {
                    table.columns(1).search('^' + year, true, false).draw();
                } else {
                    table.columns(1).search('').draw();
                }
            });
        });
    </script>
</x-layout>
