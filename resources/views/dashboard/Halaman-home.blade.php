@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">{{ $greeting }} {{ Session::get('name') }} &#128522;</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active"><b>{{ Session::get('name') }}'s</b> Dashboard </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            @can('admin')
                <div class="container">
                    <div class="action-buttons">
                        <button onclick="slideContent('left')" id="moveLeftButton"
                            class="action-button action-button-primary"><span class="arrow-kiri"></span></button>
                        <button onclick="slideContent('right')" id="moveRightButton"
                            class="action-button action-button-primary"><span class="arrow-kanan"></span></button>
                    </div>
                    <div id="slide-card-atas">
                        <a href="#" class="slide-card-atass">
                            <span class="dash-widget-icon"><i class="fa fa-users"></i></span>
                            <div>
                                <h2 role="presentation">{{ $dataPengguna }}</h2><br>
                                <div class="currency">
                                    Jumlah Seluruh Pengguna
                                </div>
                            </div>
                        </a>

                        <a href="#" class="slide-card-atass">
                            <span class="dash-widget-icon"><i class="fa fa-user-circle"></i></span>
                            <div>
                                <h2 role="presentation">{{ $dataOnline }}</h2><br>
                                <div class="currency">
                                    Pengguna Online
                                </div>
                            </div>
                        </a>

                        <a href="#" class="slide-card-atass">
                            <span class="dash-widget-icon"><i class="fa fa-user-circle-o"></i></span>
                            <div>
                                <h2 role="presentation">{{ $dataOffline }}</h2><br>
                                <div class="currency">
                                    Pengguna Offline
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endcan

            {{-- message --}}
            {!! Toastr::message() !!}
        </div>
    </div>
    </div>
    @push('js')
        <script src="{{ asset('assets/js/slide-card.js') }}"></script>
        <script>
            $('#pageTitle').html('Home | Loghub - PT TATI');
        </script>
    @endpush
@endsection
