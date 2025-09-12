<x-layout bodyClass="g-sidenav-show bg-gray-200">

    <!-- (Opsional) DataTables CSS bila halaman lain ikut pakai -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <style>
        /* Kompak seperti referensi */
        .form-control, .form-select, textarea { font-size: .9rem; }
        .modal-footer { padding: 1rem 0; }
        .help { font-size:.8rem; color:#6c757d; }
        .card .container { padding: 1rem 1.25rem; }
    </style>

    <x-navbars.sidebar activePage="rfm"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="EDIT RFM"></x-navbars.navs.auth>
        <!-- End Navbar -->

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">

                        {{-- Alert Validasi --}}
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Flash success --}}
                        @if(session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="container">
                            <!-- Edit Form -->
                            <form action="{{ route('rfms.update', $rfm->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row mt-3">
                                    <!-- Kolom kiri -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="no_rfm" class="form-label">No RFM</label>
                                            <input
                                                type="text"
                                                class="form-control border p-2 @error('no_rfm') is-invalid @enderror"
                                                id="no_rfm"
                                                name="no_rfm"
                                                value="{{ old('no_rfm', $rfm->no_rfm) }}"
                                                readonly
                                            >
                                            @error('no_rfm')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="dokumen_pdf" class="form-label">Dokumen PDF (opsional)</label>
                                            <input
                                                type="file"
                                                class="form-control border p-2 @error('dokumen_pdf') is-invalid @enderror"
                                                id="dokumen_pdf"
                                                name="dokumen_pdf"
                                                accept="application/pdf"
                                            >
                                            <div class="help">Maks 10MB, format PDF. Mengunggah file baru akan <b>menggantikan</b> file lama.</div>
                                            @error('dokumen_pdf')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror

                                            @if($rfm->dokumen_pdf)
                                                <div class="mt-2">
                                                    File saat ini:
                                                    <a href="{{ route('rfms.download', $rfm->id) }}" target="_blank" class="btn btn-sm btn-info mt-3">
                                                        Lihat/Unduh PDF
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Kolom kanan -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="deskripsi" class="form-label">Deskripsi</label>
                                            <textarea
                                                class="form-control border p-2 @error('deskripsi') is-invalid @enderror"
                                                id="deskripsi"
                                                name="deskripsi"
                                                rows="8"
                                                required
                                            >{{ old('deskripsi', $rfm->deskripsi) }}</textarea>
                                            @error('deskripsi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                    <a href="{{ route('rfm') }}" class="btn btn-secondary">Cancel</a>
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

    <!-- (Opsional) jQuery & DataTables JS bila dibutuhkan di halaman ini -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</x-layout>
