@extends('layouts.master')
@push('style')
    <style>
        .rounded-xl {
            --tw-border-opacity: 1;
            background-color: rgb(229 231 235 / var(--tw-border-opacity)) !important;
        }
    </style>
    @if ($result_tema->tema_aplikasi == 'Gelap')
        <link href="
    https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css
    " rel="stylesheet">
        <style>
            .rounded-xl {
                background-color: {{ $result_tema->warna_mode }} !important;
            }

            .border-dark {
                border-color: white !important;
            }

            .sidebar-menu li a:hover {
                color: #ffffff !important
            }

            .bg-grad-system.system {
                background: #464a5b !important
            }

            .member-selects {
                max-height: 100px !important;
                overflow-y: auto !important;
            }

            .select2-results__options {
                max-height: 50px !important;
                overflow-y: auto !important;
            }
        </style>
    @endif
@endpush
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <!-- Tampilan Foto & Nama Tim -->
                <div class="flex items-center gap-2">
                    <p class="text-xl font-bold" style="margin-bottom: 3px;">Team:
                    <p class="text-xl" style="margin-bottom: 3px;">{{ $team->name }}</p>
                    </p>
                </div>
                <div class="w-full h-24 flex items-center p-6 bg-pattern-{{ $team->pattern }} border-b border-gray-200">
                    <div class="w-20 h-20">
                        @if (Auth::user()->id == $owner->id)
                            <img class="avatar-papan" src="{{ URL::to('/assets/images/' . $owner->avatar) }}"
                                loading="lazy">
                        @endif
                    </div>
                </div>
                <!-- /Tampilan Foto & Nama Tim -->

            </div>
            <!-- /Page Header -->
            <div class="d-flex flex-column flex-md-row gap-4">
                <div class="flex flex-col gap-8 flex-1 w-full">
                    <div class="konten">
                        <!-- /Tampilan Papan dan Pencaharian Nama Papan -->
                        <div class="flex flex-col gap-6">
                            <div class="flex flex-col gap-4">
                                <div class="flex items-center gap-2 pl-1">
                                    <h2 class="text-2xl font-bold">Boards</h2>
                                </div>
                                <form action="{{ route('searchBoard', ['team_ids' => encrypt($team->id)]) }}"
                                    id="search-form" method="GET">
                                    @csrf
                                    <div class="row filter-row">
                                        <div class="col-sm-6 col-md-9 col-lg-9">
                                            <div class="form-group form-focus">
                                                <input type="hidden" name="team_id" value="{{ $team->id }}">
                                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                                <input type="text" class="form-control floating" name="board_name"
                                                    value="{{ session('__old_board_name') }}"
                                                    style="--tw-border-opacity: 1; border-color: rgb(0 0 0 / var(--tw-border-opacity)); border-radius: 15px">
                                                <label class="focus-label"><i class="fa-solid fa-table-columns"></i>
                                                    Boards's
                                                    Name</label>

                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-3 col-lg-3">
                                            <button type="submit" class="btn btn-success btn-block btn_search"><i
                                                    class="fa-solid fa-magnifying-glass"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /Tampilan Papan dan Pencaharian Nama Papan -->

                        <!-- Tampilan Papan -->
                        <div class="tampilan-papan">
                            @isset($boards)
                                @foreach ($boards as $board)
                                    <a href="{{ route('board', ['board_id' => $board->id, 'team_id' => encrypt($board->team_id)]) }}"
                                        class="flex cursor-pointer select-none flex-col transition duration-300 border border-gray-200 shadow-xl rounded-xl w-72 hover:shadow-2xl bg-grad-{{ $board->pattern }}"
                                        id="bgGrad" style="margin-bottom: 15px;">
                                        <div class="flex-grow w-full p-4" style="padding: 3rem !important;"></div>
                                        <article
                                            class="flex flex-col w-full gap-1 px-4 py-2 bg-white border-t rounded-b-lg border-t-gray-200">
                                            <h4 class="overflow-hidden font-semibold truncate text-bold"
                                                style="font-size: 15px">
                                                {{ $board->name }}</h4>
                                        </article>
                                    </a>
                                @endforeach
                                @endif
                            </div>
                            <!-- /Tampilan Papan -->

                            <div class="flex flex-wrap gap-x-8 gap-y-6">
                                <!-- Fitur Buat Papan -->
                                @if ($boards->isEmpty() && Auth::user()->id == $owner->id)
                                    <a href="#" data-toggle="modal" data-target="#createBoard">
                                        <div class="flex flex-col items-center justify-center gap-2 text-gray-400 transition duration-300 bg-gray-100 shadow-md cursor-pointer select-none w-72 h-52 rounded-xl hover:shadow-2xl"
                                            style="background-color: rgb(243 244 246 / 1) !important; @if ($result_tema->tema_aplikasi == 'Gelap') background-color: #292D3E !important; @endif ">
                                            <i class="fa-solid fa-plus fa-2xl"
                                                style="margin-top: 14px; margin-bottom: -16px;"></i><br>
                                            <h4>Create Board</h4>
                                        </div>
                                    </a>
                                @endif
                                <!-- /Fitur Buat Papan -->
                            </div>
                        </div>
                    </div>
                    {!! Toastr::message() !!}
                    <div class="member">
                        <!-- Tampilan Papan dan Anggota Tim -->
                        {{-- d-flex flex-column max-h-96 gap-4 w-100 w-md-25 --}}
                        <div class="d-flex flex-column max-h-96 gap-4 w-96 w-sm-100">
                            <h2 class="ml-4 text-2xl font-bold">Members</h2>
                            <div
                                class="isian-anggota  flex flex-col flex-grow w-full gap-2 p-4 border-2 border-gray-200 rounded-xl">
                                <div class="flex items-center gap-4">
                                    <a href="{{ URL::to('/assets/images/' . $owner->avatar) }}" data-fancybox="foto-profil">
                                        <img src="{{ URL::to('/assets/images/' . $owner->avatar) }}" loading="lazy"
                                            class="!flex-shrink-0 !flex-grow-0 w-12 avatar-undangan">
                                    </a>
                                    <p class="flex-grow truncate">{{ $owner->name }}</p>
                                    <i class="fa-solid fa-crown fa-lg w-6 h-6 text-yellow-400 !flex-shrink-0 !flex-grow-0"></i>
                                </div>
                                @foreach ($members as $member)
                                    <div class="flex items-center gap-4">
                                        <a href="{{ URL::to('/assets/images/' . $member->avatar) }}"
                                            data-fancybox="foto-profil">
                                            <img src="{{ URL::to('/assets/images/' . $member->avatar) }}" loading="lazy"
                                                class="!flex-shrink-0 !flex-grow-0 w-12 avatar-undangan">
                                        </a>
                                        <p class="w-50 truncate">{{ $member->name }}</p>
                                    </div>
                                @endforeach
                                @foreach ($membersPending as $memberP)
                                    <div class="flex items-center gap-4">
                                        <a href="{{ URL::to('/assets/images/' . $memberP->avatar) }}"
                                            data-fancybox="foto-profil">
                                            <img src="{{ URL::to('/assets/images/' . $memberP->avatar) }}" loading="lazy"
                                                class="!flex-shrink-0 !flex-grow-0 w-12 avatar-undangan">
                                        </a>
                                        <p class="w-50 truncate">{{ $memberP->name }}
                                        <p class="badge badge-warning">Pending</p>
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- /Tampilan Papan dan Anggota Tim -->
                    </div>
                </div>
            </div>
            <!-- /Page Content -->

            <!-- Perbaharui Tim Modal -->
            <div id="updateTeam" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Team</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('doTeamDataUpdate', ['team_ids' => encrypt($team->id)]) }}"
                                method="POST">
                                @csrf
                                <input type="hidden" name="team_id" value="{{ $team->id }}">
                                <div class="form-group">
                                    <label>Team's Name</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control @error('team_name') is-invalid @enderror"
                                        id="team_name" name="team_name" placeholder="Enter a team's name"
                                        value="{{ $team->name }}" required>
                                    @error('team_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Team's Description</label><span class="text-danger">*</span>
                                    <textarea class="form-control @error('team_description') is-invalid @enderror" id="team_description"
                                        name="team_description" placeholder="Enter a team's description" required></textarea>
                                    @error('team_description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="flex flex-col w-full gap-2">
                                    <label>Team's Background</label>
                                    <input type="hidden" id="pattern-field" name="team_pattern"
                                        value="{{ isset($patterns[0]) ? $patterns[0] : 'default_value' }}">
                                    <div
                                        class="flex items-center justify-start w-full max-w-2xl gap-2 px-4 py-2 overflow-hidden overflow-x-scroll border-2 border-gray-200 h-36 rounded-xl">
                                        @isset($patterns)
                                            @foreach ($patterns as $pattern)
                                                <div onclick="selectPattern('{{ $pattern }}')"
                                                    class="{{ $pattern == $patterns[0] ? 'order-first' : '' }} h-full flex-shrink-0 border-4 rounded-lg w-36 bg-pattern-{{ $pattern }} hover:border-black"
                                                    id="pattern-{{ $pattern }}" style="cursor: pointer">
                                                    <div id="check-{{ $pattern }}"
                                                        class="flex items-center justify-center w-full h-full {{ $pattern == $patterns[0] ? 'opacity-100' : 'opacity-0' }}">
                                                        <i class="fa-solid fa-circle-check"></i>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @endif
                                        </div>
                                        <small class="text-danger">*Please select again (Team's Background) when updating.</small>
                                    </div>
                                    <div class="submit-section">
                                        <button type="submit" class="btn btn-outline-info submit-btn">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Perbaharui Tim Modal -->

                <!-- Anggota Tim Modal -->
                <div id="manageMember" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Delete Members</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="d-flex justify-between">
                                    <div class="title">
                                        List Member
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="check-all">
                                        <label class="form-check-label" for="check-all">Check All</label>
                                    </div>
                                </div>
                                <select id="member-select" class="member-selects form-control" multiple="multiple"
                                    style="width:100%; height: 50px !important; overflow-y: auto;">
                                    @foreach ($members as $member)
                                        <option value="{{ $member->email }}">{{ $member->name }}</option>
                                    @endforeach
                                </select>

                                <div class="submit-section">
                                    <button type="button" class="btn btn-outline-danger submit-btn" id="save-btn"
                                        data-url="{{ route('deleteTeamMember', ['team_id' => encrypt($team->id)]) }}">Delete</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Anggota Tim Modal -->

                <!-- Undangan Anggota Modal -->
                <div id="inviteMember" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Invite People</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3 hidden">
                                    <label for="input-text-inv-email" class="form-label">E-mail Address</label>
                                    <div class="input-group gap-2">
                                        <input type="email" class="form-control" id="inv-email"
                                            placeholder="Enter member email">
                                        <button class="btn btn-outline-info hidden" type="button" id="add-btn">
                                            <i class="fa-solid fa-user-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <h5>Pilih Team Members</h5>
                                    <div class="input-group gap-2" style="flex-wrap: nowrap;">
                                        <select class="js-example-basic-single theSelect" id="inv-email2"
                                            style="width: 100% !important">
                                            {{-- <select data-live-search="true" class="theSelect" id="inv-email2"
                                            style="width: 100% !important"> --}}
                                            <option selected disabled>-- Select Team Members --</option>
                                            @foreach ($UserTeams as $result_team)
                                                <option value="{{ $result_team->email }}">
                                                    {{ $result_team->name }}</option>
                                            @endforeach
                                        </select>
                                        {{-- <button class="btn btn-outline-info" type="button" id="add-btn2">
                                            <i class="fa-solid fa-user-plus"></i>
                                        </button> --}}
                                    </div>
                                </div>
                                <h5>Team Members</h5>
                                <form method="POST" id="invite-members-form"
                                    action="{{ route('doInviteMembers', ['team_ids' => encrypt($team->id)]) }}">
                                    @csrf
                                    <input type="hidden" name="team_id" value="{{ $team->id }}">
                                    <div class="border border-2 border-dark p-2 rounded"
                                        style="max-height: 300px; overflow-y: auto;" id="invite-container"> <span
                                            id="message-team-members" style="color: red">--Belum Ada Team Members--</span>
                                    </div>
                                    <div class="submit-section">
                                        <button type="submit" class="btn btn-outline-info submit-btn"
                                            id="save-btn">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Undangan Anggota Modal -->

                <!-- Buat Papan Modal -->
                <div id="createBoard" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Create Board</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('createBoard', ['team_ids' => encrypt($team->id)]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="team_id" value="{{ $team->id }}">
                                    <div class="form-group">
                                        <label>Board's Name</label><span class="text-danger">*</span>
                                        <input type="text" class="form-control @error('board_name') is-invalid @enderror"
                                            id="board_name" name="board_name" placeholder="Enter a board's name" required>
                                        @error('board_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="flex flex-col w-full gap-2">
                                        <label>Board's Color</label>
                                        <input type="hidden" id="background-field" name="board_pattern"
                                            value="{{ isset($backgrounds[0]) ? $backgrounds[0] : 'default_value' }}">
                                        <div
                                            class="flex items-center justify-start w-full max-w-2xl gap-2 px-4 py-2 overflow-hidden overflow-x-scroll border-2 border-gray-200 h-36 rounded-xl">
                                            @isset($backgrounds)
                                                @foreach ($backgrounds as $background)
                                                    <div onclick="selectPattern2('{{ $background }}')"
                                                        class="{{ $background == $backgrounds[0] ? 'order-first' : '' }} h-full flex-shrink-0 border-4 rounded-lg w-36 bg-grad-{{ $background }} hover:border-black"
                                                        id="background-{{ $background }}" style="cursor: pointer">
                                                        <div id="check-{{ $background }}"
                                                            class="flex items-center justify-center w-full h-full {{ $background == $backgrounds[0] ? 'opacity-100' : 'opacity-0' }}">
                                                            <i class="fa-solid fa-circle-check"></i>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="submit-section">
                                            <button type="submit" class="btn btn-outline-info submit-btn">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Buat Papan Modal -->

                    <!-- Hapus Papan Modal -->
                    <div id="deleteTeam" class="modal custom-modal fade" role="dialog">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="form-header">
                                        <h3>Delete Team "{{ $team->name }}"?</h3>
                                        <p>Are you sure you want to delete this team?</p>
                                    </div>
                                    <div class="modal-btn delete-action">
                                        <form action="{{ route('doDeleteTeam', ['team_ids' => encrypt($team->id)]) }}"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="team_id" value="{{ $team->id }}">
                                            <div class="row">
                                                <div class="col-6">
                                                    <button type="submit"
                                                        class="btn btn-primary continue-btn submit-btn">Delete</button>
                                                </div>
                                                <div class="col-6">
                                                    <a href="javascript:void(0);" data-dismiss="modal"
                                                        class="btn btn-primary cancel-btn">Cancel</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Hapus Papan Modal -->

                    <!-- Keluar dari Tim Modal -->
                    <div id="leaveTeam" class="modal custom-modal fade" role="dialog">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="form-header">
                                        <h3>Leave the Team "{{ $team->name }}"?</h3>
                                        <p>Are you sure you want to leave this team?</p>
                                    </div>
                                    <div class="modal-btn delete-action">
                                        <form action="{{ route('doLeaveTeam', ['team_ids' => encrypt($team->id)]) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="team_id" value="{{ $team->id }}">
                                            <div class="row">
                                                <div class="col-6">
                                                    <button type="submit" class="btn btn-primary continue-btn submit-btn">Leave the
                                                        Team</button>
                                                </div>
                                                <div class="col-6">
                                                    <a href="javascript:void(0);" data-dismiss="modal"
                                                        class="btn btn-primary cancel-btn">Cancel</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Keluar dari Tim Modal -->

                </div>
                <!-- /Page Wrapper -->
                </div>
                @push('js')
                    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

                    <!-- FancyBox Foto Profil -->
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
                    <script>
                        $(document).ready(function() {
                            $('[data-fancybox="foto-profil"]').fancybox({});
                        });
                        $(document).ready(function() {
                            $('#member-select').select2({
                                dropdownParent: $('#manageMember'),
                                placeholder: '--- Pilih atau Cari Member yang Ingin Anda Hapus ---',
                                allowClear: true,
                                width: '100%',
                                // closeOnSelect: false
                            });
                            $('.js-example-basic-single').select2();
                        });
                    </script>
                    <!-- /FancyBox Foto Profil -->

                    <script>
                        function selectPattern(pattern) {
                            var selectedPattern = document.querySelector('#pattern-field');
                            selectedPattern.value = pattern;

                            var allPatterns = document.querySelectorAll('.h-full');
                            allPatterns.forEach(function(item) {
                                item.classList.remove('border-black');
                            });

                            var selectedPatternElement = document.getElementById('pattern-' + pattern);
                            selectedPatternElement.classList.add('border-black');

                            var allChecks = document.querySelectorAll('.fa-circle-check');
                            allChecks.forEach(function(item) {
                                item.parentElement.style.opacity = '0';
                            });

                            var selectedCheck = document.getElementById('check-' + pattern);
                            selectedCheck.style.opacity = '100';
                        }

                        function selectPattern2(background) {
                            var selectedPattern = document.querySelector('#background-field');
                            selectedPattern.value = background;

                            var allPatterns = document.querySelectorAll('.h-full');
                            allPatterns.forEach(function(item) {
                                item.classList.remove('border-black');
                            });

                            var selectedPatternElement = document.getElementById('background-' + background);
                            selectedPatternElement.classList.add('border-black');

                            var allChecks = document.querySelectorAll('.fa-circle-check');
                            allChecks.forEach(function(item) {
                                item.parentElement.style.opacity = '0';
                            });

                            var selectedCheck = document.getElementById('check-' + background);
                            selectedCheck.style.opacity = '100';
                        }
                        $(document).ready(function() {
                            $('#pageTitle').html('Team Board | Loghub - PT TATI ');
                            let selectedMembers = [];


                            $('#manageMember').on('shown.bs.modal', function() {
                                $('#member-name').trigger('focus');
                                $('#member-name2').trigger('focus');
                            });

                            $('#check-all').on('click', function() {
                                if ($(this).is(':checked')) {
                                    $('#member-select > option').prop('selected', true).trigger('change');
                                } else {
                                    $('#member-select > option').prop('selected', false).trigger('change');
                                }
                            });


                            $('#save-btn').on('click', function() {
                                const selectedMembers = $('#member-select').val();
                                // $('[data-role="member-card"].selected').each(function() {
                                //     selectedMembers.push($(this).data('email'));
                                // });
                                $('#save-btn').prop('disabled', true);
                                const url = $(this).data('url');
                                if (selectedMembers.length > 0) {
                                    $.ajax({
                                        url: url,
                                        method: 'POST',
                                        data: {
                                            emails: selectedMembers,
                                            _token: "{{ csrf_token() }}"
                                        },
                                        success: function(response) {
                                            $('#manageMember').modal('hide');
                                            toastr.success('Berhasil menghapus anggota tim Anda!');
                                            setTimeout(function() {
                                                location.reload();
                                            }, 1000);
                                            $('#save-btn').prop('disabled', false);
                                        },
                                        error: function(error) {
                                            toastr.error(
                                                'Terjadi kesalahan saat menghapus anggota tim Anda!'
                                            );
                                            $('#save-btn').prop('disabled', false);
                                        }

                                    });
                                } else {
                                    toastr.error('Tidak ada anggota tim yang Anda pilih!');
                                    $('#save-btn').prop('disabled', false);
                                }
                            });

                            $('[data-role="member-card"]').on('click', function() {
                                $(this).toggleClass('selected border-info bg-red-200');
                            });

                            // $('#member-name, #member-name2').on('change', function() {
                            //     let email = $(this).val().trim();
                            //     if (email !== "") {
                            //         let emailExists = false;
                            //         $('[data-role="member-card"]').each(function() {
                            //             if ($(this).data('email') === email) {
                            //                 emailExists = true;
                            //                 return false;
                            //             }
                            //         });
                            //         if (!emailExists) {
                            //             toastr.error(
                            //                 'Email yang Anda masukkan tidak tersedia, silahkan memasukkannya kembali!');
                            //             return;
                            //         }
                            //         if (!selectedMembers.includes(email)) {
                            //             selectedMembers.push(email);
                            //             $(this).val('');
                            //         }
                            //     } else {
                            //         toastr.error('Email tidak boleh kosong!');
                            //     }
                            // });
                        });
                    </script>
                    <script>
                        $(document).ready(function() {
                            const addBtn = document.getElementById('add-btn');
                            const addBtn2 = document.getElementById('add-btn2');
                            const emailInput = document.getElementById('inv-email');
                            const emailInput2 = document.getElementById('inv-email2');
                            const inviteContainer = document.getElementById('invite-container');
                            const form = document.getElementById('invite-members-form');

                            // addBtn.addEventListener('click', () => {
                            //     const email = emailInput.value.trim();
                            //     if (email && email !== '-- Select Team Members --') {
                            //         const emailDiv = document.createElement('div');
                            //         emailDiv.className =
                            //             'd-flex justify-content-between align-items-center mb-2 bg-red-200 rounded-lg';
                            //         emailDiv.innerHTML = `
        // <span>${email}</span>
        // <button type="button" class="btn btn-outline-danger btn-sm remove-btn"><i class="fa-solid fa-trash"></i></button>
        // <input type="hidden" name="emails[]" value="${email}">`;
                            //         inviteContainer.appendChild(emailDiv);
                            //         emailDiv.querySelector('.remove-btn').addEventListener('click', () => {
                            //             inviteContainer.removeChild(emailDiv);
                            //         });
                            //         emailInput.value = '';
                            //     }
                            // });

                            //     addBtn2.addEventListener('click', () => {
                            //         const email = emailInput2.value.trim();
                            //         if (email && email !== '-- Select Team Members --') {
                            //             const emailDiv = document.createElement('div');
                            //             emailDiv.className =
                            //                 'd-flex justify-content-between align-items-center mb-2 bg-red-200 rounded-lg';
                            //             emailDiv.innerHTML = `
        //     <span>${email}</span>
        //     <button type="button" class="btn btn-outline-danger btn-sm remove-btn"><i class="fa-solid fa-trash"></i></button>
        //     <input type="hidden" name="emails[]" value="${email}">
        // `;
                            //             inviteContainer.appendChild(emailDiv);
                            //             emailDiv.querySelector('.remove-btn').addEventListener('click', () => {
                            //                 inviteContainer.removeChild(emailDiv);
                            //             });
                            //             emailInput2.value = '';
                            //         }
                            //     });

                            // $('#inv-email2').on('change', function() {
                            //     // e.preventDefault();
                            //     const email = emailInput2.value.trim();
                            //     if (email && email !== '-- Select Team Members --') {
                            //         const emailDiv = document.createElement('div');
                            //         emailDiv.className =
                            //             'd-flex justify-content-between align-items-center mb-2 bg-red-200 rounded-lg';
                            //         emailDiv.innerHTML = `
        //             <span>${email}</span>
        //             <button type="button" class="btn btn-outline-danger btn-sm remove-btn"><i class="fa-solid fa-trash"></i></button>
        //             <input type="hidden" name="emails[]" value="${email}">
        //         `;
                            //         inviteContainer.appendChild(emailDiv);
                            //         emailDiv.querySelector('.remove-btn').addEventListener('click', () => {
                            //             inviteContainer.removeChild(emailDiv);
                            //         });
                            //         emailInput2.value = '';
                            //     }
                            // });
                            let emailArray = [];
                            $('#inv-email2').on('change', function() {
                                const email = emailInput2.value.trim();

                                if (email && email !== '-- Select Team Members --') {
                                    if (!emailArray.includes(email)) {
                                        emailArray.push(email);

                                        const emailDiv = document.createElement('div');
                                        emailDiv.className =
                                            'd-flex justify-content-between align-items-center mb-2 bg-red-200 rounded-lg';
                                        emailDiv.innerHTML = `
                                            <span>${email}</span>
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-btn">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                            <input type="hidden" name="emails[]" value="${email}">
                                        `;

                                        inviteContainer.appendChild(emailDiv);

                                        emailDiv.querySelector('.remove-btn').addEventListener('click', () => {
                                            inviteContainer.removeChild(emailDiv);

                                            emailArray = emailArray.filter(e => e !== email);

                                            $('#message-team-members').hide();
                                            if (emailArray.length == 0) {
                                                $('#message-team-members').show();
                                            }
                                        });

                                        emailInput2.value = '';
                                    } else {
                                        toastr.error('Nama Tersebut Sudah Ada Pada Team Members!');
                                        // Swal.fire({
                                        //     icon: "error",
                                        //     title: "Gagal",
                                        //     text: "Nama Tersebut Sudah Ada Pada Team Members!",
                                        //     confirmButtonText: 'OK'
                                        // });
                                    }
                                }

                                $('#message-team-members').hide();
                                if (emailArray.length == 0) {
                                    $('#message-team-members').show();
                                }
                            });

                            form.addEventListener('submit', (e) => {
                                $('.submit-btn').prop('disabled', true);
                                if (inviteContainer.children.length === 1) {
                                    e.preventDefault();
                                    toastr.error('Harap tambahkan email anggota tim Anda sebelum menyimpan!');
                                    $('.submit-btn').prop('disabled', false);
                                }
                            });
                        });
                    </script>
                    <script>
                        $(document).ready(function() {
                            var teamDescription = "{{ $team->description }}";
                            var textarea = document.getElementById("team_description");
                            textarea.value = teamDescription;
                        });
                    </script>
                @endpush
            @endsection
