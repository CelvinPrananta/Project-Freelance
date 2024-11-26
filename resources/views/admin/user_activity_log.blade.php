@extends('layouts.master')
@section('content')
    @if (session()->has('success'))
        <script>
            toastr.success("{{ session('success') }}", 'Success');
        </script>
    @endif
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">History Activity</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">History Activity</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            <a id="delete-all" class='btn btn-outline-danger'><i class="fa fa-trash m-r-5"></i>Delete
                All History</a>
            <!-- /Search Filter -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table" id="tableHistoryActivity" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Card ID</th>
                                    <th>Type</th>
                                    <th>Content</th>
                                    <th>Time History</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
    </div>

    @push('js')
        <script src="{{ asset('assets/js/atur-tanggal-indo.js') }}"></script>
        <script src="https://cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap4.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#pageTitle').html('History Activity - Admin | Loghub - PT TATI');
                var table = $('#tableHistoryActivity').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "{{ route('get-history-activity') }}",
                        "data": function(d) {
                            d.keyword = $('#keyword').val();
                            d._token = "{{ csrf_token() }}";
                        }
                    },
                    "columns": [{
                            "data": "id"
                        },
                        {
                            "data": "user_id"
                        },
                        {
                            "data": "card_id"
                        },
                        {
                            "data": "type"
                        },
                        {
                            "data": "content"
                        },
                        {
                            "data": "created_at",
                        },
                    ],
                    "language": {
                        "lengthMenu": "Show _MENU_ entries",
                        "zeroRecords": "No data available in table",
                        "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                        "infoEmpty": "Showing 0 to 0 of 0 entries",
                        "infoFiltered": "(filtered from _MAX_ total records)",
                        "search": "Cari:",
                        "searchPlaceholder": "Name",
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
                    table
                        .search($('#keyword').val())
                        .draw();
                })
            });
        </script>
    @endpush
    @push('js')
        <script>
            $(document).ready(function() {
                $('#delete-all').click(function(e) {
                    e.preventDefault();
                    $.ajax({
                        type: "POST",
                        url: "{{ route('delete-all-history') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "_method": "DELETE"
                        },
                        success: function(response) {
                            if (response.redirect) {
                                // Redirect ke halaman baru
                                window.location.href = response.redirect;
                            }
                        },
                        error: function(xhr, status, error) {}
                    });
                });
            });
        </script>
    @endpush
@endsection
