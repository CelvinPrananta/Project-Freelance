<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <div class="sidebar-left">
                    <a href="{{ route('home') }}" class="logo" style="font-size: 24px; color: black; font-weight: bold;">
                        <img src="{{ URL::to('/assets/images/logo.png') }}"
                            style="width: 70%;display: inline-block; font-weight: 900 !important" loading="lazy"
                            class="logo-text">
                    </a>
                </div>
                <li class="@if (Route::is('home') || Route::is('tampilan-semua-notifikasi')) active @endif">
                    <a href="{{ route('home') }}">
                        <i class="fa-solid fa-building-columns"></i>
                        <span style="font-weight: 900">Home</span>
                    </a>
                </li>
                @can('admin')
                    <li class="menu-title"> <span style="font-weight: 900">System Management</span> </li>
                    <li class="@if (Route::is('manajemen-pengguna') || Route::is('showProfile')) active @endif">
                        <a href="{{ route('manajemen-pengguna') }}">
                            <i class="fa-solid fa-person"></i>
                            <span style="font-weight: 900">List User</span>
                        </a>
                    </li>
                    <li class="{{ set_active(['riwayat/aktivitas']) }} active-riwayat-aktivitas">
                        <a href="{{ route('riwayat-aktivitas') }}">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            <span style="font-weight: 900">History Activity</span>
                        </a>
                    </li>
                    <li class="{{ set_active(['riwayat/otentikasi']) }} active-riwayat-aktivitas">
                        <a href="{{ route('riwayat-aktivitas-otentikasi') }}">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            <span style="font-weight: 900">History Otentikasi</span>
                        </a>
                    </li>
                @endcan

                <li class="menu-title"> <span style="font-weight: 900">Task Management</span> </li>
                <li @if (Route::is('showTeams') ||
                        Route::is('searchTeam') ||
                        Route::is('viewTeam') ||
                        Route::is('searchBoard') ||
                        Route::is('board')) class="active" @endif>
                    <a href="{{ route('showTeams') }}">
                        <i class="fa-solid fa-cube"></i>
                        <span style="font-weight: 900">Team</span>
                    </a>
                </li>
                @if (Route::is('showTeams') || Route::is('searchTeam'))
                    <li class="add-teams btn btn-outline-danger">
                        <a href="#" class="link-add-teams" data-toggle="modal" data-target="#createTeam">
                            <i class="icon-add-teams fa-solid fa-cubes"></i>
                            <span style="font-weight: 900">Add Teams</span>
                        </a>
                    </li>
                @endif
                @if (Route::is('viewTeam') || Route::is('searchBoard'))
                    @foreach ($actionTeams as $iconAction)
                        @if ($iconAction->status == 'Owner')
                            <div class="dropdown dropdown-action opsi-sidebar">
                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis" style="color: white"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right"
                                    style="margin-top: 15px !important; margin-left: 30px !important">
                                    <a href="#" class="dropdown-item" data-toggle="modal"
                                        data-target="#updateTeam">
                                        <i class="fa-solid fa-pencil m-r-5"></i>
                                        <span style="font-weight: 900">Edit</span>
                                    </a>
                                    @if ($statusTeams->contains('Member'))
                                        <a href="#" class="dropdown-item" data-toggle="modal"
                                            data-target="#manageMember">
                                            <i class="fa-solid fa-user-gear m-r-5"></i>
                                            <span style="font-weight: 900">Members</span>
                                        </a>
                                    @endif
                                    <a href="#" class="dropdown-item" data-toggle="modal"
                                        data-target="#inviteMember">
                                        <i class="fa-solid fa-user-plus m-r-5"></i>
                                        <span style="font-weight: 900">Invite</span>
                                    </a>
                                    <a href="#" class="dropdown-item" data-toggle="modal"
                                        data-target="#createBoard">
                                        <i class="fa-solid fa-table-columns m-r-5"></i>
                                        <span style="font-weight: 900">Add Board</span>
                                    </a>
                                    <a href="#" class="dropdown-item" data-toggle="modal"
                                        data-target="#deleteTeam">
                                        <i class="fa fa-trash-o m-r-5"></i>
                                        <span style="font-weight: 900">Delete</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    @foreach ($leaveTeams as $iconLeave)
                        @if ($iconLeave->status == 'Member')
                            <li class="add-teams btn btn-outline-danger">
                                <a href="#" data-toggle="modal" data-target="#leaveTeam" class="link-add-teams">
                                    <i class="icon-view-team fa-solid fa-right-from-bracket fa-rotate-180 m-r-5"></i>
                                    <span style="font-weight: 900">Leave Team</span>
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
                @if (Route::is('board'))
                    @foreach ($actionTeams as $iconAction)
                        @if ($iconAction->status == 'Owner')
                            <div class="dropdown dropdown-action opsi-sidebar">
                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis" style="color: white"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right"
                                    style="margin-top: 15px !important; margin-left: 30px !important">
                                    <a href="#" class="dropdown-item" data-toggle="modal"
                                        data-target="#updateBoard">
                                        <i class="fa-solid fa-pencil m-r-5"></i>
                                        <span style="font-weight: 900">Edit</span>
                                    </a>
                                    <a href="#" class="dropdown-item" data-toggle="modal"
                                        data-target="#deleteBoard">
                                        <i class="fa fa-trash-o m-r-5"></i>
                                        <span style="font-weight: 900">Delete</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif

                @if (Route::is('viewTeam') || Route::is('searchBoard'))
                    <li class="add-teams btn btn-outline-danger">
                        <a href="{{ route('showTeams') }}" class="link-add-teams">
                            <i class="icon-view-team fa-solid fa-right-from-bracket fa-rotate-180 m-r-5"></i>
                            <span style="font-weight: 900">Back to Team's</span>
                        </a>
                    </li>
                @endif
                @if (Route::is('board'))
                    <a href="#">
                        <div class="view-board btn btn-outline-danger">
                            <span style="font-weight: 900">
                                <h4>Board:</h4>
                            </span>
                            <span style="font-weight: 900">
                                <h5>{{ $board->name }}</h5>
                            </span>
                        </div>
                    </a>
                    <li class="add-teams btn btn-outline-danger">
                        <a href="{{ route('viewTeam', ['team_ids' => encrypt($team->id)]) }}" class="link-add-teams">
                            <i class="icon-view-team fa-solid fa-right-from-bracket fa-rotate-180 m-r-5"></i>
                            <span style="font-weight: 900">Back to Board's</span>
                        </a>
                    </li>
                @endif
                <li class="menu-title"> <span style="font-weight: 900">Setting</span> </li>
                <li class="@if (Route::is('profile') || Route::is('rubah-kata-sandi')) active @endif">
                    <a href="{{ route('profile') }}">
                        <i class="la la-user"></i>
                        <span style="font-weight: 900"> Profile</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->
