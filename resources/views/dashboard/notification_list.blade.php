@extends('layouts.master')
@push('style')
    <style>
        .notification-list.empty {
            display: block;
        }

        .notification-list:not(.empty) {
            display: none;
        }

        .card {
            border: 1px solid #ededed;
            border-radius: 5px;
            margin-bottom: 20px;
            overflow: hidden;
            box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
        }

        .card-header {
            padding: 15px;
            background-color: rgb(70 70 70 / 17%);
            cursor: pointer;
        }

        .card-body {
            padding: 15px;
            display: none;
        }

        .arrow-notif {
            -webkit-transition: -webkit-transform 0.15s;
            -o-transition: -o-transform 0.15s;
            transition: transform .15s;
            position: absolute;
            right: 25px;
            display: inline-block;
            font-family: 'FontAwesome';
            text-rendering: auto;
            line-height: 40px;
            font-size: 18px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            -webkit-transform: translate(0, 0);
            -ms-transform: translate(0, 0);
            -o-transform: translate(0, 0);
            transform: translate(0, 0);
            line-height: 18px;
            top: 30px;
        }

        .arrow-notif.up {
            content: "\f105";
        }

        .arrow-notif.down {
            -ms-transform: rotate(90deg);
            -webkit-transform: rotate(90deg);
            -o-transform: rotate(90deg);
            transform: rotate(90deg);
        }

        .arrow-notif:before {
            content: "\f105";
        }

        .notification-time {
            font-size: 13px;
            line-height: 1.35;
            color: #979797;
        }

        .fa-clock {
            font-size: 12px;
        }

        .fa-check-double {
            font-size: 12px;
        }

        .icon-comment {
            color: #626F86;
            transition: color 0.3s;
        }

        .icon-comment:hover {
            color: #157347;
        }

        .icon-comment:active {
            color: #146c43;
        }

        .icon-trash {
            color: #626F86;
            transition: color 0.3s;
        }

        .icon-trash:hover {
            color: #dc3546e1;
        }

        .icon-trash:active {
            color: #e62034;
        }
    </style>
@endpush
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
                        <h3 class="page-title">All Notifications - {{ Session::get('name') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Notifications</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            {!! Toastr::message() !!}

            <div class="card tab-box">
                <div class="row user-tabs">
                    <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                        <ul class="nav nav-tabs nav-tabs-bottom nav-justified" style="text-align: center">
                            <li class="nav-item"><a href="#semua_notif" data-toggle="tab" class="nav-link active">All</a>
                            </li>
                            <li class="nav-item"><a href="#mention_description" data-toggle="tab" class="nav-link">Mention
                                    Description</a></li>
                            <li class="nav-item"><a href="#mention_checklist" data-toggle="tab" class="nav-link">Mention
                                    Checklist</a></li>
                            <li class="nav-item"><a href="#mention_comment" data-toggle="tab" class="nav-link">Mention
                                    Comment</a></li>
                            <li class="nav-item"><a href="#ulang_tahun" data-toggle="tab" class="nav-link">Birthday</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <a id="delete-all" class='btn btn-outline-danger'><i class="fa fa-trash m-r-5"></i>Delete
                All Notification</a>
            <!-- Content Notifikasi -->
            <div class="tab-content">
                <!-- Semua Notifikasi -->
                <div id="semua_notif" class="pro-overview tab-pane fade show active">
                    <ul class="notification-list @if (auth()->user()->unreadNotifications->isEmpty() && auth()->user()->readNotifications->isEmpty()) empty @endif" id="allNotificationList"
                        style="margin-top: 10%;">
                        @if (auth()->user()->unreadNotifications->isEmpty() && auth()->user()->readNotifications->isEmpty())
                            <li class="notification-message noti-unread">
                                <p class="noti-details" style="margin-top: 30px; text-align: center;">
                                    <img src="{{ URL::to('/assets/images/notification-icon.svg') }}"
                                        style="position: relative; width: 120px;" loading="lazy">
                                </p>
                                <p class="noti-details" style="margin-top: 20px; text-align: center; font-size: 22px;">No
                                    new notifications</p>
                            </li>
                        @endif
                    </ul>
                    <div class="row">
                        <div class="col-md-12">
                            {{-- @dd($usernotifikasi) --}}
                            @foreach ($usernotifikasi as $notifikasi)
                                @php
                                    $notifikasiData = json_decode($notifikasi->data);
                                    $created_at = \Carbon\Carbon::parse($notifikasi->created_at);
                                    $read_at = \Carbon\Carbon::parse($notifikasi->read_at);
                                @endphp
                                <div class="card" data-notification="{{ json_encode($notifikasiData) }}">
                                    <div class="card-header" onclick="toggleCardBody(this)">
                                        {{ $notifikasiData->message }}
                                        <span class="arrow-notif"></span><br>
                                        <i class="fa-solid fa-clock" style="color: #808080;" aria-hidden="true"></i>
                                        <span class="notification-time">{{ $created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="card-body" style="display:none">
                                        {{-- @if ($notifikasiData->message == 'Happy Birthday')
                                            <p><b>{{ $notifikasiData->message3 }}</b>, provide notifications
                                                {{ strtolower($notifikasiData->message) }} to You. You can view and delete
                                                these notifications.</p>
                                            @if ($notifikasi->read_at)
                                                <i class="fa-solid fa-check-double" style="color: #4999de;"></i>
                                                <span class="notification-time">{{ $read_at->diffForHumans() }}</span>
                                            @endif
                                            <a class="simbol-hapus hapus_notifikasi_{{ $notifikasi->id }}" href="#"
                                                data-toggle="modal"
                                                data-target="#hapus_notifikasi_{{ $notifikasi->id }}"><i
                                                    class="fa fa-trash-o icon-trash"></i></a>
                                            <a class="simbol-lihat lihat_notifikasi_{{ $notifikasi->id }}" href="#"
                                                data-toggle="modal"
                                                data-target="#lihat_notifikasi_{{ $notifikasi->id }}"><i
                                                    class="fa-solid fa-eye fa-lg icon-comment"></i></a>
                                        @endif
                                        @if ($notifikasiData->message == 'Mention Tag Description')
                                            <p><b>{{ $notifikasiData->message2 }}</b>, provide notifications
                                                {{ strtolower($notifikasiData->message) }} to You. You can view and delete
                                                these notifications.</p>
                                            @if ($notifikasi->read_at)
                                                <i class="fa-solid fa-check-double" style="color: #4999de;"></i>
                                                <span class="notification-time">{{ $read_at->diffForHumans() }}</span>
                                            @endif
                                            <a class="simbol-hapus hapus_notifikasi_{{ $notifikasi->id }}" href="#"
                                                data-toggle="modal"
                                                data-target="#hapus_notifikasi_{{ $notifikasi->id }}"><i
                                                    class="fa fa-trash-o icon-trash"></i></a>
                                            <a class="simbol-lihat lihat_notifikasi_{{ $notifikasi->id }}" href="#"
                                                data-toggle="modal"
                                                data-target="#lihat_notifikasi_{{ $notifikasi->id }}"><i
                                                    class="fa-solid fa-eye fa-lg icon-comment"></i></a>
                                        @endif
                                        @if ($notifikasiData->message == 'Mention Tag Checklist')
                                            <p><b>{{ $notifikasiData->message2 }}</b>, provide notifications
                                                {{ strtolower($notifikasiData->message) }} to You. You can view and delete
                                                these notifications.</p>
                                            @if ($notifikasi->read_at)
                                                <i class="fa-solid fa-check-double" style="color: #4999de;"></i>
                                                <span class="notification-time">{{ $read_at->diffForHumans() }}</span>
                                            @endif
                                            <a class="simbol-hapus hapus_notifikasi_{{ $notifikasi->id }}" href="#"
                                                data-toggle="modal"
                                                data-target="#hapus_notifikasi_{{ $notifikasi->id }}"><i
                                                    class="fa fa-trash-o icon-trash"></i></a>
                                            <a class="simbol-lihat lihat_notifikasi_{{ $notifikasi->id }}" href="#"
                                                data-toggle="modal"
                                                data-target="#lihat_notifikasi_{{ $notifikasi->id }}"><i
                                                    class="fa-solid fa-eye fa-lg icon-comment"></i></a>
                                        @endif
                                        @if ($notifikasiData->message == 'Mention Tag Comment')
                                            <p><b>{{ $notifikasiData->message2 }}</b>, provide notifications
                                                {{ strtolower($notifikasiData->message) }} to You. You can view and delete
                                                these notifications.</p>
                                            @if ($notifikasi->read_at)
                                                <i class="fa-solid fa-check-double" style="color: #4999de;"></i>
                                                <span class="notification-time">{{ $read_at->diffForHumans() }}</span>
                                            @endif
                                            <a class="simbol-hapus hapus_notifikasi_{{ $notifikasi->id }}" href="#"
                                                data-toggle="modal"
                                                data-target="#hapus_notifikasi_{{ $notifikasi->id }}"><i
                                                    class="fa fa-trash-o icon-trash"></i></a>
                                            <a class="simbol-lihat lihat_notifikasi_{{ $notifikasi->id }}" href="#"
                                                data-toggle="modal"
                                                data-target="#lihat_notifikasi_{{ $notifikasi->id }}"><i
                                                    class="fa-solid fa-eye fa-lg icon-comment"></i></a>
                                        @endif --}}

                                        @if ($notifikasiData->message == 'Happy Birthday')
                                            @php $message = $notifikasiData->message3; @endphp
                                        @else
                                            @php $message = $notifikasiData->message2; @endphp
                                        @endif
                                        <p><b>{{ $message }}</b>, provide notifications
                                            {{ strtolower($notifikasiData->message) }} to You. You can view and delete
                                            these notifications.</p>
                                        @if ($notifikasi->read_at)
                                            <i class="fa-solid fa-check-double" style="color: #4999de;"></i>
                                            <span class="notification-time">{{ $read_at->diffForHumans() }}</span>
                                        @endif
                                        <a class="simbol-hapus delete-notifikasi hapus_notifikasi_{{ $notifikasi->id }}"
                                            href="#" {{-- data-toggle="modal" --}}
                                            data-target="#hapus_notifikasi_{{ $notifikasi->id }}"
                                            data-id="{{ $notifikasi->id }}"
                                            data-url="{{ route('tampilan-semua-notifikasi-hapus-data', $notifikasi->id) }}">
                                            <i class="fa fa-trash-o icon-trash"></i>
                                        </a>
                                        <a class="simbol-lihat show-notifikasi lihat_notifikasi_{{ $notifikasi->id }}"
                                            href="#" {{-- data-toggle="modal"  --}} data-id="{{ $notifikasi->id }}"
                                            data-url="{{ route('get-detail-notif', $notifikasi->id) }}"
                                            data-target="#lihat_notifikasi_{{ $notifikasi->id }}">
                                            <i class="fa-solid fa-eye fa-lg icon-comment"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- /Semua Notifikasi -->

                <!-- Mention Description -->
                <div id="mention_description" class="tab-pane fade">
                    @php
                        $mentionTagDescriptionUnread = auth()
                            ->user()
                            ->unreadNotifications->filter(function ($notification) {
                                return $notification->data['message'] === 'Mention Tag Description';
                            })
                            ->isEmpty();

                        $mentionTagDescriptionRead = auth()
                            ->user()
                            ->readNotifications->filter(function ($notification) {
                                return $notification->data['message'] === 'Mention Tag Description';
                            })
                            ->isEmpty();
                    @endphp

                    <ul class="notification-list {{ $mentionTagDescriptionUnread && $mentionTagDescriptionRead ? 'empty' : '' }}"
                        style="margin-top: 10%;">
                        @if ($mentionTagDescriptionUnread && $mentionTagDescriptionRead)
                            <li class="notification-message noti-unread">
                                <p class="noti-details" style="margin-top: 30px; text-align: center;">
                                    <img src="{{ URL::to('/assets/images/notification-icon.svg') }}"
                                        style="position: relative; width: 120px;" loading="lazy">
                                </p>
                                <p class="noti-details" style="margin-top: 20px; text-align: center; font-size: 22px;">No
                                    new notifications</p>
                            </li>
                        @endif
                    </ul>
                    <div class="row">
                        <div class="col-md-12">
                            @foreach ($usernotifikasi as $notifikasi)
                                @php
                                    $notifikasiData = json_decode($notifikasi->data);
                                    $created_at = \Carbon\Carbon::parse($notifikasi->created_at);
                                    $read_at = \Carbon\Carbon::parse($notifikasi->read_at);
                                @endphp
                                @if ($notifikasiData->message == 'Mention Tag Description')
                                    <div class="card" data-notification="{{ json_encode($notifikasiData) }}">
                                        <div class="card-header" onclick="toggleCardBody(this)">
                                            {{ $notifikasiData->message }}
                                            <span class="arrow-notif"></span><br>
                                            <i class="fa-solid fa-clock" style="color: #808080;" aria-hidden="true"></i>
                                            <span class="notification-time">{{ $created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="card-body" style="display:none">
                                            <p><b>{{ $notifikasiData->message2 }}</b>, provide notifications
                                                {{ strtolower($notifikasiData->message) }} to You. You can view and delete
                                                these notifications.</p>
                                            @if ($notifikasi->read_at)
                                                <i class="fa-solid fa-check-double" style="color: #4999de;"></i>
                                                <span class="notification-time">{{ $read_at->diffForHumans() }}</span>
                                            @endif
                                            <a class="simbol-hapus hapus_notifikasi_{{ $notifikasi->id }}" href="#"
                                                data-toggle="modal"
                                                data-target="#hapus_notifikasi_{{ $notifikasi->id }}"><i
                                                    class="fa fa-trash-o icon-trash"></i></a>
                                            <a class="simbol-lihat lihat_notifikasi_{{ $notifikasi->id }}" href="#"
                                                data-toggle="modal"
                                                data-target="#lihat_notifikasi_{{ $notifikasi->id }}"><i
                                                    class="fa-solid fa-eye fa-lg icon-comment"></i></a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- /Mention Description -->

                <!-- Mention Checklist -->
                <div id="mention_checklist" class="tab-pane fade">
                    @php
                        $mentionTagChecklistUnread = auth()
                            ->user()
                            ->unreadNotifications->filter(function ($notification) {
                                return $notification->data['message'] === 'Mention Tag Checklist';
                            })
                            ->isEmpty();

                        $mentionTagChecklistRead = auth()
                            ->user()
                            ->readNotifications->filter(function ($notification) {
                                return $notification->data['message'] === 'Mention Tag Checklist';
                            })
                            ->isEmpty();
                    @endphp

                    <ul class="notification-list {{ $mentionTagChecklistUnread && $mentionTagChecklistRead ? 'empty' : '' }}"
                        style="margin-top: 10%;">
                        @if ($mentionTagChecklistUnread && $mentionTagChecklistRead)
                            <li class="notification-message noti-unread">
                                <p class="noti-details" style="margin-top: 30px; text-align: center;">
                                    <img src="{{ URL::to('/assets/images/notification-icon.svg') }}"
                                        style="position: relative; width: 120px;" loading="lazy">
                                </p>
                                <p class="noti-details" style="margin-top: 20px; text-align: center; font-size: 22px;">No
                                    new notifications</p>
                            </li>
                        @endif
                    </ul>
                    <div class="row">
                        <div class="col-md-12">
                            @foreach ($usernotifikasi as $notifikasi)
                                @php
                                    $notifikasiData = json_decode($notifikasi->data);
                                    $created_at = \Carbon\Carbon::parse($notifikasi->created_at);
                                    $read_at = \Carbon\Carbon::parse($notifikasi->read_at);
                                @endphp
                                @if ($notifikasiData->message == 'Mention Tag Checklist')
                                    <div class="card" data-notification="{{ json_encode($notifikasiData) }}">
                                        <div class="card-header" onclick="toggleCardBody(this)">
                                            {{ $notifikasiData->message }}
                                            <span class="arrow-notif"></span><br>
                                            <i class="fa-solid fa-clock" style="color: #808080;" aria-hidden="true"></i>
                                            <span class="notification-time">{{ $created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="card-body" style="display:none">
                                            <p><b>{{ $notifikasiData->message2 }}</b>, provide notifications
                                                {{ strtolower($notifikasiData->message) }} to You. You can view and delete
                                                these notifications.</p>
                                            @if ($notifikasi->read_at)
                                                <i class="fa-solid fa-check-double" style="color: #4999de;"></i>
                                                <span class="notification-time">{{ $read_at->diffForHumans() }}</span>
                                            @endif
                                            <a class="simbol-hapus hapus_notifikasi_{{ $notifikasi->id }}" href="#"
                                                data-toggle="modal"
                                                data-target="#hapus_notifikasi_{{ $notifikasi->id }}"><i
                                                    class="fa fa-trash-o icon-trash"></i></a>
                                            <a class="simbol-lihat lihat_notifikasi_{{ $notifikasi->id }}" href="#"
                                                data-toggle="modal"
                                                data-target="#lihat_notifikasi_{{ $notifikasi->id }}"><i
                                                    class="fa-solid fa-eye fa-lg icon-comment"></i></a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- /Mention Checklist -->

                <!-- Mention Comment -->
                <div id="mention_comment" class="tab-pane fade">
                    @php
                        $mentionTagCommentUnread = auth()
                            ->user()
                            ->unreadNotifications->filter(function ($notification) {
                                return $notification->data['message'] === 'Mention Tag Comment';
                            })
                            ->isEmpty();

                        $mentionTagCommentRead = auth()
                            ->user()
                            ->readNotifications->filter(function ($notification) {
                                return $notification->data['message'] === 'Mention Tag Comment';
                            })
                            ->isEmpty();
                    @endphp

                    <ul class="notification-list {{ $mentionTagCommentUnread && $mentionTagCommentRead ? 'empty' : '' }}"
                        style="margin-top: 10%;">
                        @if ($mentionTagCommentUnread && $mentionTagCommentRead)
                            <li class="notification-message noti-unread">
                                <p class="noti-details" style="margin-top: 30px; text-align: center;">
                                    <img src="{{ URL::to('/assets/images/notification-icon.svg') }}"
                                        style="position: relative; width: 120px;" loading="lazy">
                                </p>
                                <p class="noti-details" style="margin-top: 20px; text-align: center; font-size: 22px;">No
                                    new notifications</p>
                            </li>
                        @endif
                    </ul>
                    <div class="row">
                        <div class="col-md-12">
                            @foreach ($usernotifikasi as $notifikasi)
                                @php
                                    $notifikasiData = json_decode($notifikasi->data);
                                    $created_at = \Carbon\Carbon::parse($notifikasi->created_at);
                                    $read_at = \Carbon\Carbon::parse($notifikasi->read_at);
                                @endphp
                                @if ($notifikasiData->message == 'Mention Tag Comment')
                                    <div class="card" data-notification="{{ json_encode($notifikasiData) }}">
                                        <div class="card-header" onclick="toggleCardBody(this)">
                                            {{ $notifikasiData->message }}
                                            <span class="arrow-notif"></span><br>
                                            <i class="fa-solid fa-clock" style="color: #808080;" aria-hidden="true"></i>
                                            <span class="notification-time">{{ $created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="card-body" style="display:none">
                                            <p><b>{{ $notifikasiData->message2 }}</b>, provide notifications
                                                {{ strtolower($notifikasiData->message) }} to You. You can view and delete
                                                these notifications.</p>
                                            @if ($notifikasi->read_at)
                                                <i class="fa-solid fa-check-double" style="color: #4999de;"></i>
                                                <span class="notification-time">{{ $read_at->diffForHumans() }}</span>
                                            @endif
                                            <a class="simbol-hapus hapus_notifikasi_{{ $notifikasi->id }}" href="#"
                                                data-toggle="modal"
                                                data-target="#hapus_notifikasi_{{ $notifikasi->id }}"><i
                                                    class="fa fa-trash-o icon-trash"></i></a>
                                            <a class="simbol-lihat lihat_notifikasi_{{ $notifikasi->id }}" href="#"
                                                data-toggle="modal"
                                                data-target="#lihat_notifikasi_{{ $notifikasi->id }}"><i
                                                    class="fa-solid fa-eye fa-lg icon-comment"></i></a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- /Mention Comment -->

                <!-- Notifikasi Ulang Tahun -->
                <div id="ulang_tahun" class="tab-pane fade">
                    @php
                        $mentionTagHappyBirthdayUnread = auth()
                            ->user()
                            ->unreadNotifications->filter(function ($notification) {
                                return $notification->data['message'] === 'Happy Birthday';
                            })
                            ->isEmpty();

                        $mentionTagHappyBirthdayRead = auth()
                            ->user()
                            ->readNotifications->filter(function ($notification) {
                                return $notification->data['message'] === 'Happy Birthday';
                            })
                            ->isEmpty();
                    @endphp

                    <ul class="notification-list {{ $mentionTagHappyBirthdayUnread && $mentionTagHappyBirthdayRead ? 'empty' : '' }}"
                        style="margin-top: 10%;">
                        @if ($mentionTagHappyBirthdayUnread && $mentionTagHappyBirthdayRead)
                            <li class="notification-message noti-unread">
                                <p class="noti-details" style="margin-top: 30px; text-align: center;">
                                    <img src="{{ URL::to('/assets/images/notification-icon.svg') }}"
                                        style="position: relative; width: 120px;" loading="lazy">
                                </p>
                                <p class="noti-details" style="margin-top: 20px; text-align: center; font-size: 22px;">No
                                    new notifications</p>
                            </li>
                        @endif
                    </ul>
                    <div class="row">
                        <div class="col-md-12">
                            @foreach ($usernotifikasi as $notifikasi)
                                @php
                                    $notifikasiData = json_decode($notifikasi->data);
                                    $created_at = \Carbon\Carbon::parse($notifikasi->created_at);
                                    $read_at = \Carbon\Carbon::parse($notifikasi->read_at);
                                @endphp
                                @if ($notifikasiData->message == 'Happy Birthday')
                                    <div class="card" data-notification="{{ json_encode($notifikasiData) }}">
                                        <div class="card-header" onclick="toggleCardBody(this)">
                                            {{ $notifikasiData->message }}
                                            <span class="arrow-notif"></span><br>
                                            <i class="fa-solid fa-clock" style="color: #808080;" aria-hidden="true"></i>
                                            <span class="notification-time">{{ $created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="card-body" style="display:none">
                                            <p><b>{{ $notifikasiData->message3 }}</b>, provide notifications
                                                {{ strtolower($notifikasiData->message) }} to You. You can view and delete
                                                these notifications.</p>
                                            @if ($notifikasi->read_at)
                                                <i class="fa-solid fa-check-double" style="color: #4999de;"></i>
                                                <span class="notification-time">{{ $read_at->diffForHumans() }}</span>
                                            @endif
                                            <a class="simbol-hapus hapus_notifikasi_{{ $notifikasi->id }}" href="#"
                                                data-toggle="modal"
                                                data-target="#hapus_notifikasi_{{ $notifikasi->id }}"><i
                                                    class="fa fa-trash-o icon-trash"></i></a>
                                            <a class="simbol-lihat lihat_notifikasi_{{ $notifikasi->id }}" href="#"
                                                data-toggle="modal"
                                                data-target="#lihat_notifikasi_{{ $notifikasi->id }}"><i
                                                    class="fa-solid fa-eye fa-lg icon-comment"></i></a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- /Notifikasi Ulang Tahun -->

            </div>
            <!-- /Content Notifikasi -->

        </div>
        <!-- /Page Content -->

        <!-- Preview Notifikasi Modal -->
        {{-- @foreach ($usernotifikasi->where('notifiable_id', auth()->id()) as $notifikasi)
            @php
                $notifikasiData = json_decode($notifikasi->data);
                $created_at = \Carbon\Carbon::parse($notifikasi->created_at);
                $read_at = \Carbon\Carbon::parse($notifikasi->read_at);
            @endphp
            <div class="modal custom-modal fade" id="lihat_notifikasi_{{ $notifikasi->id }}" role="dialog"
                data-backdrop="static" data-keyboard="false" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="text-align: center ">Notifikasi
                                <br>{{ $notifikasiData->message }}
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="notification-message noti-read">
                                <div class="media">
                                    <div class="media-body">
                                        <p class="noti-details3">
                                            <a><b>{{ $notifikasiData->name }}</b></a><br>
                                            <a style="font-size: 15px">Surabaya / <span
                                                    id="tanggal-semua-notifikasi_{{ $notifikasi->id }}"></span> | <span
                                                    id="waktu-semua-notifikasi_{{ $notifikasi->id }}"></span></a><br>
                                            <a style="color: #808080; font-weight: 500; font-size: 12px">Notification ID:
                                                {{ substr($notifikasi->id, 0, 8) }}</a>
                                        </p><br>
                                        <p class="noti-details4">
                                            @if ($notifikasiData->message == 'Happy Birthday')
                                                {{ $notifikasiData->message2 }} <b>{{ $notifikasiData->message3 }}</b>
                                                {{ $notifikasiData->message4 }}
                                                {{ $notifikasiData->message5 }}<b>{{ $notifikasiData->message6 }}th</b>
                                                year to <b>{{ $notifikasiData->name }}</b>
                                                {{ $notifikasiData->message7 }}
                                            @endif
                                            @if ($notifikasiData->message == 'Mention Tag Description')
                                                <div class="mention-tag-container"
                                                    style="width: 398px; margin: 0px 0px 0px 20px;">
                                                    <div class="header-mention-tag">
                                                        @php
                                                            $userAvatar = '';
                                                            if (!empty($notifikasiData->message6)) {
                                                                $user = \App\Models\User::find(
                                                                    $notifikasiData->message6,
                                                                );
                                                                if ($user) {
                                                                    $userAvatar = URL::to(
                                                                        '/assets/images/' . $user->avatar,
                                                                    );
                                                                }
                                                            }
                                                        @endphp
                                                        <a href="{{ $userAvatar }}" data-fancybox="mention-foto">
                                                            <img class="avatar-notif" src="{{ $userAvatar }}"
                                                                loading="lazy">
                                                        </a>
                                                        <p class="mention-nama">{{ $notifikasiData->message4 }}</p>
                                                        <p class="mention-waktu">
                                                            {{ \Carbon\Carbon::parse($notifikasiData->message5)->isoFormat('D MMMM [at] h:mm') }}
                                                        </p>
                                                    </div>
                                                    <div class="isian-mention-tag">
                                                        {{ $notifikasiData->message3 }}
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($notifikasiData->message == 'Mention Tag Checklist')
                                                <div class="mention-tag-container"
                                                    style="width: 398px; margin: 0px 0px 0px 20px;">
                                                    <div class="header-mention-tag">
                                                        @php
                                                            $userAvatar = '';
                                                            if (!empty($notifikasiData->message6)) {
                                                                $user = \App\Models\User::find(
                                                                    $notifikasiData->message6,
                                                                );
                                                                if ($user) {
                                                                    $userAvatar = URL::to(
                                                                        '/assets/images/' . $user->avatar,
                                                                    );
                                                                }
                                                            }
                                                        @endphp
                                                        <a href="{{ $userAvatar }}" data-fancybox="mention-foto">
                                                            <img class="avatar-notif" src="{{ $userAvatar }}"
                                                                loading="lazy">
                                                        </a>
                                                        <p class="mention-nama">{{ $notifikasiData->message4 }}</p>
                                                        <p class="mention-waktu">
                                                            {{ \Carbon\Carbon::parse($notifikasiData->message5)->isoFormat('D MMMM [at] h:mm') }}
                                                        </p>
                                                    </div>
                                                    <div class="isian-mention-tag">
                                                        {{ $notifikasiData->message3 }}
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($notifikasiData->message == 'Mention Tag Comment')
                                                <div class="mention-tag-container"
                                                    style="width: 398px; margin: 0px 0px 0px 20px;">
                                                    <div class="header-mention-tag">
                                                        @php
                                                            $userAvatar = '';
                                                            if (!empty($notifikasiData->message6)) {
                                                                $user = \App\Models\User::find(
                                                                    $notifikasiData->message6,
                                                                );
                                                                if ($user) {
                                                                    $userAvatar = URL::to(
                                                                        '/assets/images/' . $user->avatar,
                                                                    );
                                                                }
                                                            }
                                                        @endphp
                                                        <a href="{{ $userAvatar }}" data-fancybox="mention-foto">
                                                            <img class="avatar-notif" src="{{ $userAvatar }}"
                                                                loading="lazy">
                                                        </a>
                                                        <p class="mention-nama">{{ $notifikasiData->message4 }}</p>
                                                        <p class="mention-waktu">
                                                            {{ \Carbon\Carbon::parse($notifikasiData->message5)->isoFormat('D MMMM [at] h:mm') }}
                                                        </p>
                                                    </div>
                                                    <div class="isian-mention-tag">
                                                        {{ $notifikasiData->message3 }}
                                                    </div>
                                                </div>
                                            @endif
                                        </p><br>
                                        <p
                                            class="{{ $notifikasiData->message == 'Happy Birthday' ? 'logo-rsud' : 'logo-tati' }}">
                                            @if ($result_tema->tema_aplikasi == 'Terang')
                                                <img src="{{ asset('assets/images/Logo_Perusahaan_Merah.png') }}"
                                                    alt="Logo PT TATI" loading="lazy">
                                            @elseif ($result_tema->tema_aplikasi == 'Gelap')
                                                <img src="{{ asset('assets/images/Logo_Perusahaan_Putih.png') }}"
                                                    alt="Logo PT TATI" loading="lazy">
                                            @endif
                                        </p>
                                        <p class="noti-time2">
                                            <i class="fa-solid fa-clock" style="color: #808080;" aria-hidden="true"></i>
                                            <span class="notification-time">{{ $created_at->diffForHumans() }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach --}}
        <!-- /Preview Notifikasi Modal -->

        <!-- Delete Notifikasi Modal -->
        {{-- @foreach ($usernotifikasi->where('notifiable_id', auth()->id()) as $notifikasi)
            @php
                $notifikasiData = json_decode($notifikasi->data);
                $created_at = \Carbon\Carbon::parse($notifikasi->created_at);
                $read_at = \Carbon\Carbon::parse($notifikasi->read_at);
            @endphp
            <div class="modal custom-modal fade" id="hapus_notifikasi_{{ $notifikasi->id }}" role="dialog"
                data-backdrop="static" data-keyboard="false" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="form-header">
                                <h3>Delete Notification</h3>
                                <p>Are you sure you want to delete this notification?</p>
                            </div>
                            <div class="modal-btn delete-action">
                                <div class="row">
                                    <div class="col-6">
                                        <a href="{{ route('tampilan-semua-notifikasi-hapus-data', $notifikasi->id) }}">
                                            <button type="button"
                                                class="btn btn-primary continue-btn submit-btn">Delete</button>
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="javascript:void(0);" data-dismiss="modal"
                                            class="btn btn-primary cancel-btn">Back</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach --}}
        <!-- Delete Notifikasi Modal -->

        {{-- NON FOREACH MODAL PREVIEW --}}
        <div class="modal custom-modal fade" id="lihat_notifikasi" role="dialog" data-backdrop="static"
            data-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="text-align: center ">Notifikasi
                            <br>
                            <div id="notifikasi-message">
                            </div>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="notification-message noti-read">
                            <div class="media">
                                <div class="media-body">
                                    <p class="noti-details3">
                                        <a><b id="notifikasi-name"></b></a><br>
                                        <a style="font-size: 15px">Surabaya / <span
                                                @isset($notifikasi->id)
                                            id="tanggal-semua-notifikasi_{{ $notifikasi->id }}"></span> | <span
                                            id="waktu-semua-notifikasi_{{ $notifikasi->id }}"></span></a><br>
                                    <a style="color: #808080; font-weight: 500; font-size: 12px">Notification ID:
                                        {{ substr($notifikasi->id, 0, 8) }}</a>
                                            @endisset
                                                </p><br>
                                                <p class="noti-details4">
                                                    {{-- @if ($notifikasiData->message == 'Happy Birthday')
                                            {{ $notifikasiData->message2 }} <b>{{ $notifikasiData->message3 }}</b>
                                            {{ $notifikasiData->message4 }}
                                            {{ $notifikasiData->message5 }}<b>{{ $notifikasiData->message6 }}th</b>
                                            year to <b>{{ $notifikasiData->name }}</b>
                                            {{ $notifikasiData->message7 }}
                                        @endif --}}
                                                <div class="mention-tag-container"
                                                    style="width: 398px; margin: 0px 0px 0px 20px;">
                                                    <div class="header-mention-tag">
                                                        <a id="profil" href="" data-fancybox="mention-foto">
                                                            <img class="avatar-notif" src="" loading="lazy">
                                                        </a>
                                                        <p class="mention-nama"></p>
                                                        <p class="mention-waktu">
                                                        </p>
                                                    </div>
                                                    <div class="isian-mention-tag">
                                                    </div>
                                                </div>
                                    </p><br>
                                    <p id="img">
                                        @if ($result_tema->tema_aplikasi == 'Terang')
                                            <img src="{{ asset('assets/images/Logo_Perusahaan_Merah.png') }}"
                                                alt="Logo PT TATI" loading="lazy">
                                        @elseif ($result_tema->tema_aplikasi == 'Gelap')
                                            <img src="{{ asset('assets/images/Logo_Perusahaan_Putih.png') }}"
                                                alt="Logo PT TATI" loading="lazy">
                                        @endif
                                    </p>
                                    @isset($created_at)
                                        <p class="noti-time2">
                                            <i class="fa-solid fa-clock" style="color: #808080;" aria-hidden="true"></i>
                                            <span class="notification-time">{{ $created_at->diffForHumans() }}</span>
                                        </p>
                                    @endisset

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        {{-- NON FOREACH MODAL DELETE --}}
        <div class="modal custom-modal fade" id="hapus_notifikasi" role="dialog" data-backdrop="static"
            data-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Notification</h3>
                            <p>Are you sure you want to delete this notification?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    {{-- @isset($notifikasi->id) --}}
                                    <a id="hapus_notifikasi_action" href="">
                                        <button type="button"
                                            class="btn btn-primary continue-btn submit-btn">Delete</button>
                                    </a>
                                    {{-- @endisset --}}
                                </div>
                                <div class="col-6">
                                    <a href="javascript:void(0);" data-dismiss="modal"
                                        class="btn btn-primary cancel-btn">Back</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        {{-- <script src="{{ asset('assets/js/memuat-shortcut.js?v=' . time()) }}"></script> --}}
        <script>
            document.getElementById('pageTitle').innerHTML = 'All Notifications | Loghub - PT TATI ';
        </script>
        <script>
            $(document).ready(function() {
                $('.show-notifikasi').click(function(e) {
                    e.preventDefault();
                    // alert('test');
                    var id = $(this).data('id');
                    var title = $(this).data('title');
                    var url = $(this).data('url');
                    // alert(url);
                    $.ajax({
                        type: "GET",
                        url: url,
                        success: function(response) {
                            // alert('tes');
                            let formattedTime = dayjs(response.data.message5).format(
                                'D MMMM [at] h:mm');
                            $("#lihat_notifikasi").modal('show');
                            $('#notifikasi-message').html(response.data.message);
                            $('#notifikasi-name').html(response.data.name);
                            if (response.data.message == 'Happy Birthday') {
                                $('#img').addClass('logo-rsud');
                            } else {
                                $('#img').addClass('logo-tati');
                            }
                            $('.isian-mention-tag').html(response.data.message3);
                            $('.mention-nama').html(response.data.message4);
                            $('.mention-waktu').html(formattedTime);
                            $('#profil').attr('href', response.userAvatar);
                            $('.avatar-notif').attr('src', response.userAvatar);

                        },
                        error: function(xhr, status, error) {
                            alert(xhr.responseText);
                        }
                    });
                });
            });
            $(document).ready(function() {
                $('.delete-notifikasi').on('click', function() {
                    // alert('test');
                    var id = $(this).data('id');
                    var url = $(this).data('url');
                    // alert(id);
                    $("#hapus_notifikasi").modal('show');
                    $("#hapus_notifikasi_action").attr('href', url);
                });
            });
            $(document).ready(function() {
                $('#delete-all').click(function(e) {
                    e.preventDefault();
                    $.ajax({
                        type: "POST",
                        url: "{{ route('delete-all-notif') }}",
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
        <script>
            function toggleCardBody(header) {
                var cardBody = header.nextElementSibling;
                var arrow = header.querySelector('.arrow-notif');
                if (cardBody.style.display === "none") {
                    cardBody.style.display = "block";
                    arrow.classList.remove('up');
                    arrow.classList.add('down');
                } else if (cardBody.style.display === "block") {
                    cardBody.style.display = "none";
                    arrow.classList.remove('down');
                    arrow.classList.add('up');
                }
            }
        </script>

        <script>
            var unreadNotifications = {!! json_encode($unreadNotifications) !!};

            var allNotificationList = document.getElementById('allNotificationList');
            if (unreadNotifications.length > 0 || readNotifications.length > 0) {
                allNotificationList.classList.add('hiddens');
            } else {
                allNotificationList.classList.remove('hiddens');
            }
        </script>
    @endpush
@endsection
