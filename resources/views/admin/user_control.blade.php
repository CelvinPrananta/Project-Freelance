@extends('layouts.master')
@push('style')
    <style>
        .status_online {
            position: relative;
            width: 10px;
            height: 10px;
            display: inline-block !important;
            border-radius: 50%;
            top: 10px;
            right: 18px;
            background-color: #55ce63;
            border: 1px solid #fff;
        }

        .status_offline {
            position: relative;
            width: 10px;
            height: 10px;
            display: inline-block !important;
            border-radius: 50%;
            top: 10px;
            right: 18px;
            background-color: #f62d51;
            border: 1px solid #fff;
        }
    </style>
@endpush
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">User Management</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">User</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Search Filter -->
            <div class="row filter-row">
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <input type="text" class="form-control floating" id="user_name" name="user_name">
                        <label class="focus-label">Username</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus select-focus">
                        <select class="select floating" id="type_role">
                            <option selected disabled>-- Select Role --</option>
                            @foreach ($role_name as $name)
                                <option value="{{ $name->role_type }}">{{ $name->role_type }}</option>
                            @endforeach
                        </select>
                        <label class="focus-label">Role</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus select-focus">
                        <select class="select floating" id="type_status">
                            <option selected disabled>-- Select Status --</option>
                            @foreach ($status_user as $status)
                                <option value="{{ $status->type_name }}">{{ $status->type_name }}</option>
                            @endforeach
                        </select>
                        <label class="focus-label">Status</label>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <button type="submit" class="btn btn-success btn-block btn_search"> Search</button>
                </div>
            </div>

            {{-- message --}}
            {!! Toastr::message() !!}

            <!-- /Page Header -->
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table" id="userDataList" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pengguna</th>
                                    <th>ID Pengguna</th>
                                    <th>E-mail</th>
                                    <th>Username</th>
                                    <th>ID Employee</th>
                                    <th>Tanggal Bergabung</th>
                                    <th>Peran</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->

        <!-- Edit Daftar Pengguna Modal -->
        <div id="edit_user" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <br>
                    <div class="modal-body">
                        <form action="{{ route('data/pengguna/perbaharui') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="user_id" id="e_id" value="">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Full Name</label>
                                        <input class="form-control" type="text" name="name" id="e_name"
                                            value="" readonly placeholder="Enter full name" />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>E-mail Address</label>
                                    <input class="form-control" type="email" name="email" id="e_email" value=""
                                        readonly placeholder="Enter e-mail" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input class="form-control" type="text" id="e_username" name="username"
                                            value="" readonly placeholder="Enter username" />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Employee ID</label>
                                        <input class="form-control" type="text" id="e_employee_id" name="employee_id"
                                            value="" readonly placeholder="Enter employee id" />
                                    </div>
                                </div>
                                <input type="hidden" class="form-control" id="image" name="images"
                                    value="photo_defaults.jpg">
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Role </label>
                                    <select class="select" name="role_name" id="e_role_name">
                                        @foreach ($role_name as $role)
                                            <option value="{{ $role->role_type }}" disabled>{{ $role->role_type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label>Status</label>
                                    <select class="select" name="status" id="e_status">
                                        @foreach ($status_user as $status)
                                            <option value="{{ $status->type_name }}" disabled>{{ $status->type_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Daftar Pengguna Modal -->

    </div>
    <!-- /Page Wrapper -->


    @push('js')
        <script src="https://cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap4.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#pageTitle').html('User Management - Admin | Loghub - PT TATI');
                $(document).on('click', '.userUpdate', function() {
                    var _this = $(this).parents('tr');
                    $('#e_id').val(_this.find('.user_id').text());
                    $('#e_name').val(_this.find('.name').text());
                    $('#e_email').val(_this.find('.email').text());
                    $('#e_role_name').val(_this.find('.role_name').text()).change();
                    $('#e_username').val(_this.find('.username').text());
                    $('#e_employee_id').val(_this.find('.employee_id').text());
                    $('#e_status').val(_this.find('.status_s').text()).change();
                });
                $(document).on('click', '.userDelete', function() {
                    var _this = $(this).parents('tr');
                    $('.e_id').val(_this.find('.id').data('id'));
                    $('#e_avatar').val(_this.find('.avatar').data('avatar'));
                });

                table = $('#userDataList').DataTable({

                    lengthMenu: [
                        [10, 25, 50, 100, 150],
                        [10, 25, 50, 100, 150]
                    ],
                    buttons: [
                        'pageLength',
                    ],
                    "pageLength": 10,
                    order: [
                        [5, 'desc']
                    ],
                    processing: true,
                    serverSide: true,
                    ordering: true,
                    searching: true,
                    ajax: {
                        url: "{{ route('get-users-data') }}",
                        data: function(data) {
                            // read valus for search
                            var user_name = $('#user_name').val();
                            var type_role = $('#type_role').val();
                            var type_status = $('#type_status').val();
                            data.user_name = user_name;
                            data.type_role = type_role;
                            data.type_status = type_status;
                        }
                    },

                    columns: [{
                            data: 'no',
                            name: 'no',
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'user_id',
                            name: 'user_id'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'username',
                            name: 'username'
                        },
                        {
                            data: 'employee_id',
                            name: 'employee_id'
                        },
                        {
                            data: 'join_date',
                            name: 'join_date',
                        },
                        {
                            data: 'role_name',
                            name: 'role_name',
                        },
                        {
                            data: 'status',
                            name: 'status',
                        },
                        {
                            data: 'action',
                            name: 'action',
                        },
                    ],
                    "language": {
                        "lengthMenu": "Show _MENU_ entries",
                        "zeroRecords": "No data available in table",
                        "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                        "infoEmpty": "Showing 0 to 0 of 0 entries",
                        "infoFiltered": "(filtered from _MAX_ total records)",
                        "search": "Search:",
                        "searchPlaceholder": "Name, Role, Status ",
                        "paginate": {
                            "previous": "Previous",
                            "next": "Next",
                            "first": "<i class='fa-solid fa-backward-fast'></i>",
                            "last": "<i class='fa-solid fa-forward-fast'></i>",
                        }
                    }
                });

                $('.btn_search').on('click', function() {
                    table.draw();
                });
            });
        </script>
    @endpush
@endsection
