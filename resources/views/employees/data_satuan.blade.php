 @extends('layouts.master')
@section('content')

    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title"> Data Satuan</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Data Satuan Barang</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_databarang"><i class="fa fa-plus"></i> Tambah Data Barang</a>
                    </div>
                </div>
            </div>

            <!-- Pencarian Data Satuan -->
            <form action="{{ route('data/satuan/cari') }}" method="GET" id="search-form">
                @csrf
                <div class="row filter-row">
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" id="keyword_kode_barang" name="keyword_kode_barang">
                            <label class="focus-label">Kode Barang</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" id="keyword_nama_barang" name="keyword_nama_barang">
                            <label class="focus-label">Nama Barang</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <button type="submit" class="btn btn-success btn-block btn_search">Cari</button>
                    </div>
                </div>
            </form>
            <!-- /Pencarian Data Satuan -->

            <!-- /Page Header -->
            {!! Toastr::message() !!}

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table" id="tableDataSatuan" style="width: 100%">
                            <thead>
                                <tr>
                                    <th class="no">No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th class="aksi">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->

        <!-- Add Data Satuan Modal -->
        <div id="add_databarang" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Data Barang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('data/satuan/tambah-data') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Kode Barang<span class="text-danger">*</span></label>
                                <input class="form-control @error('kode_barang') is-invalid @enderror" type="text"
                                    id="kode_barang" name="kode_barang" placeholder="Masukkan kode barang">
                                @error('kode_barang')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Nama Barang<span class="text-danger">*</span></label>
                                <input class="form-control @error('nama_barang') is-invalid @enderror" type="text"
                                    id="nama_barang" name="nama_barang" placeholder="Masukkan nama barang">
                                @error('nama_barang')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Data Satuan Modal -->

        <!-- Edit Data Satuan Modal -->
        <div id="edit_databarang" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Data Barang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('data/satuan/edit-data') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="e_id" value="">
                            <div class="form-group">
                                <label>Kode Barang<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="e_kode_barang" name="kode_barang" placeholder="Masukkan kode barang" value="">
                            </div>
                            <div class="form-group">
                                <label>Nama Barang<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="e_nama_barang" name="nama_barang" placeholder="Masukkan nama barang" value="">
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Perbaharui</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Data Satuan Modal -->

        <!-- Delete Data Satuan Modal -->
        <div class="modal custom-modal fade" id="delete_databarang" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Hapus Data Barang</h3>
                            <p>Apakah anda yakin ingin menghapus data ini?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <form action="{{ route('data/satuan/hapus-data') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" class="e_id" value="">
                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary continue-btn submit-btn">Hapus</button>
                                    </div>
                                    <div class="col-6">
                                        <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Kembali</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete Data Satuan Modal -->
    </div>

    <!-- /Page Wrapper -->
    @push('js')
        <script src="https://cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap4.min.js"></script>
        <script>
            $(document).ready(function() {
                var table = $('#tableDataSatuan').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "{{ route('get-data-satuan') }}",
                        "data": function(d) {
                            d.keyword_kode_barang = $('#keyword_kode_barang').val();
                            d.keyword_nama_barang = $('#keyword_nama_barang').val();
                            d._token = "{{ csrf_token() }}";
                        }
                    },
                    "columns": [
                        {
                            "data": "id"
                        },
                        {
                            "data": "kode_barang"
                        },
                        {
                            "data": "nama_barang"
                        },
                        {
                            "data": "action"
                        },
                    ],
                    "language": {
                        "lengthMenu": "Show _MENU_ entries",
                        "zeroRecords": "No data available in table",
                        "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                        "infoEmpty": "Showing 0 to 0 of 0 entries",
                        "infoFiltered": "(filtered from _MAX_ total records)",
                        "search": "Cari:",
                        "searchPlaceholder": "Nama Barang",
                        "paginate": {
                            "previous": "Previous",
                            "next": "Next",
                            "first": "<i class='fa-solid fa-backward-fast'></i>",
                            "last": "<i class='fa-solid fa-forward-fast'></i>",
                        }
                    },
                    "order": [
                        [0, "asc"]
                    ]
                });

                // Live search
                $('#search-form').on('submit', function(e) {
                    e.preventDefault();
                    var keyword_kode_barang = $('#keyword_kode_barang').val();
                    var keyword_nama_barang = $('#keyword_nama_barang').val();
                    table
                        .search(keyword_kode_barang + ' ' + keyword_nama_barang)
                        .draw();
                })
            });
        </script>
    @endpush
    @push('js')
        <script src="{{ asset('assets/js/datasatuan.js') }}"></script>
        <script src="{{ asset('assets/js/memuat-ulang.js') }}"></script>
        <script>
            history.pushState({}, "", '/data/satuan');
        </script>
        <script>
            document.getElementById('pageTitle').innerHTML = 'Data Satuan Barang - Admin | Loghub - PT TATI ';
        </script>
    @endpush
@endsection