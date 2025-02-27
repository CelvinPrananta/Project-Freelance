@extends('layouts.master')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="page-title">Change Password</h3>
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->

                    {{-- message --}}
                    {!! Toastr::message() !!}

                    <form id="changePasswordForm" method="POST" action="{{ route('change/password/db') }}">
                        @csrf
                        <div class="form-group">
                            <div class="info-status1">
                                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="info-circle"
                                    class="svg-inline--fa fa-info-circle fa-w-16 float-right h-100" role="img"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                                    style="width: 1em; margin-top: 4px;">
                                    <path fill="currentColor"
                                        d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z">
                                    </path>
                                </svg>
                                <span class="text-status1"><b>Password Indicator :</b><br>
                                    <i class="fa-solid fa-circle" style="font-size: 5px"></i> Weak Password : [a-z]<br>
                                    <i class="fa-solid fa-circle" style="font-size: 5px"></i> Medium Password : [a-z] +
                                    [number]<br>
                                    <i class="fa-solid fa-circle" style="font-size: 5px"></i> Strong Password : [a-z] +
                                    [number] + [!,@,#,$,%,^,&,*,?,_,~,(,)]
                                </span>
                            </div>
                            <label>Old Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="passwordInput1" name="current_password" value="{{ old('current_password') }}" placeholder="Enter the old password">
                                <div class="input-group-append" style="position: sticky">
                                    <button type="button" id="tampilkanPassword1" class="btn btn-outline-secondary">
                                        <i id="icon1" class="fa fa-eye-slash"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="info-status2">
                                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="info-circle"
                                    class="svg-inline--fa fa-info-circle fa-w-16 float-right h-100" role="img"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                                    style="width: 1em; margin-top: 4px;">
                                    <path fill="currentColor"
                                        d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z">
                                    </path>
                                </svg>
                                <span class="text-status2"><b>Password Indicator :</b><br>
                                    <i class="fa-solid fa-circle" style="font-size: 5px"></i> Weak Password : [a-z]<br>
                                    <i class="fa-solid fa-circle" style="font-size: 5px"></i> Medium Password : [a-z] +
                                    [number]<br>
                                    <i class="fa-solid fa-circle" style="font-size: 5px"></i> Strong Password : [a-z] +
                                    [number] + [!,@,#,$,%,^,&,*,?,_,~,(,)]
                                </span>
                            </div>
                            <label>New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="passwordInput2" name="new_password" placeholder="Enter a new password">
                                <div class="input-group-append" style="position: sticky">
                                    <button type="button" id="tampilkanPassword2" class="btn btn-outline-secondary">
                                        <i id="icon2" class="fa fa-eye-slash"></i>
                                    </button>
                                </div>
                                @error('new_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="kekuatan-indicator">
                                <div class="kata-sandi-lemah-after-2"></div>
                                <div class="kata-sandi-sedang-after-2"></div>
                                <div id="indicator-kata-sandi-2"></div>
                                <div class="kata-sandi-lemah-before-2"></div>
                                <div class="kata-sandi-sedang-before-2"></div>
                            </div>
                            <div id="indicator-kata-sandi-tulisan-2"></div>
                        </div>
                        <div class="form-group">
                            <div class="info-status3">
                                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="info-circle"
                                    class="svg-inline--fa fa-info-circle fa-w-16 float-right h-100" role="img"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                                    style="width: 1em; margin-top: 4px;">
                                    <path fill="currentColor"
                                        d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z">
                                    </path>
                                </svg>
                                <span class="text-status3"><b>Password Indicator :</b><br>
                                    <i class="fa-solid fa-circle" style="font-size: 5px"></i> Weak Password : [a-z]<br>
                                    <i class="fa-solid fa-circle" style="font-size: 5px"></i> Medium Password : [a-z] +
                                    [number]<br>
                                    <i class="fa-solid fa-circle" style="font-size: 5px"></i> Strong Password : [a-z] +
                                    [number] + [!,@,#,$,%,^,&,*,?,_,~,(,)]
                                </span>
                            </div>
                            <label>Confirm Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('new_confirm_password') is-invalid @enderror" id="passwordInput3" name="new_confirm_password" placeholder="Confirm the new password">
                                <div class="input-group-append">
                                    <button type="button" id="tampilkanPassword3" class="btn btn-outline-secondary">
                                        <i id="icon3" class="fa fa-eye-slash"></i>
                                    </button>
                                </div>
                                @error('new_confirm_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="kekuatan-indicator">
                                <div class="kata-sandi-lemah-after-3"></div>
                                <div class="kata-sandi-sedang-after-3"></div>
                                <div id="indicator-kata-sandi-3"></div>
                                <div class="kata-sandi-lemah-before-3"></div>
                                <div class="kata-sandi-sedang-before-3"></div>
                            </div>
                            <div id="indicator-kata-sandi-tulisan-3"></div>
                        </div>
                        <div class="submit-section">
                            <button type="submit" class="btn btn-primary submit-btn">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->
    @push('js')
        <script>
            document.addEventListener('keydown', function (event) {
                if (event.ctrlKey && (event.key === 's' || event.key === 'S')) {
                    event.preventDefault();
                    event.stopPropagation();

                    const form = document.getElementById('changePasswordForm');
                    if (form) {
                        form.submit();
                    }
                }
            });
            document.getElementById('pageTitle').innerHTML = 'Change Password | Loghub - PT TATI ';
            $(document).ready(function() {
                const togglePassword = function(passwordInput, icon) {
                    if (passwordInput && icon) {
                        if (passwordInput.type === 'password') {
                            passwordInput.type = 'text';
                            icon.classList.remove('fa-eye-slash');
                            icon.classList.add('fa-eye');
                        } else {
                            passwordInput.type = 'password';
                            icon.classList.remove('fa-eye');
                            icon.classList.add('fa-eye-slash');
                        }
                    }
                };

                const showPassword = function(inputField, icon, toggleButton) {
                    if (toggleButton) {
                        toggleButton.addEventListener('click', function() {
                            togglePassword(inputField, icon);
                        });
                    }
                };

                const passwordInput1 = document.getElementById('passwordInput1');
                const icon1 = document.getElementById('icon1');
                const showPasswordToggle1 = document.getElementById('tampilkanPassword1');
                showPassword(passwordInput1, icon1, showPasswordToggle1);

                const passwordInput2 = document.getElementById('passwordInput2');
                const icon2 = document.getElementById('icon2');
                const showPasswordToggle2 = document.getElementById('tampilkanPassword2');
                showPassword(passwordInput2, icon2, showPasswordToggle2);

                const passwordInput3 = document.getElementById('passwordInput3');
                const icon3 = document.getElementById('icon3');
                const showPasswordToggle3 = document.getElementById('tampilkanPassword3');
                showPassword(passwordInput3, icon3, showPasswordToggle3);
            });
        </script>
        <script>
            $(document).ready(function() {
                // Indicator Kata Sandi 2 //
                const passwordInput2 = document.getElementById('passwordInput2');
                const IndicatorKekuatan2 = document.getElementById('indicator-kata-sandi-2');
                var IndicatorLemahBefore2 = document.querySelector(".kata-sandi-lemah-before-2");
                var IndicatorSedangBefore2 = document.querySelector(".kata-sandi-sedang-before-2");
                var IndicatorLemahAfter2 = document.querySelector(".kata-sandi-lemah-after-2");
                var IndicatorSedangAfter2 = document.querySelector(".kata-sandi-sedang-after-2");
                const IndicatorTulisan2 = document.getElementById('indicator-kata-sandi-tulisan-2');

                passwordInput2.addEventListener('input', function() {
                    const password2 = passwordInput2.value.trim();
                    if (password2 === '') {
                        IndicatorKekuatan2.style.display = 'none';
                        IndicatorLemahBefore2.style.display = 'none';
                        IndicatorSedangBefore2.style.display = 'none';
                        IndicatorLemahAfter2.style.display = 'none';
                        IndicatorSedangAfter2.style.display = 'none';
                        IndicatorTulisan2.textContent = '';
                    } else {
                        const strength2 = kekuatanKataSandi2(password2);
                        perbaharuiIndicatorKataSandi2(strength2);
                    }
                });

                function kekuatanKataSandi2(password2) {
                    if (/[a-z]/.test(password2)) {
                        if (/\d+/.test(password2)) {
                            if (/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/.test(password2)) {
                                return 'kuat';
                            } else {
                                return 'sedang';
                            }
                        } else {
                            return 'lemah';
                        }
                    } else {
                        return 'lemah';
                    }
                }

                function perbaharuiIndicatorKataSandi2(strength2) {
                    IndicatorKekuatan2.style.display = '';
                    IndicatorKekuatan2.className = '';
                    IndicatorLemahBefore2.style.display = 'block';
                    IndicatorSedangBefore2.style.display = 'block';
                    IndicatorLemahAfter2.style.display = 'block';
                    IndicatorSedangAfter2.style.display = 'block';

                    if (strength2 === 'lemah') {
                        IndicatorKekuatan2.classList.add('kata-sandi-lemah');
                        IndicatorLemahBefore2.classList.add('kata-sandi-lemah-before-2');
                        IndicatorSedangBefore2.classList.add('kata-sandi-sedang-before-2');
                        IndicatorLemahAfter2.classList.remove('kata-sandi-lemah-after-2');
                        IndicatorSedangAfter2.classList.remove('kata-sandi-sedang-after-2');
                        IndicatorTulisan2.textContent = 'Kata Sandi Lemah';
                        IndicatorTulisan2.style.color = '#ff4757';

                    } else if (strength2 === 'sedang') {
                        IndicatorKekuatan2.classList.add('kata-sandi-sedang');
                        IndicatorLemahAfter2.classList.add('kata-sandi-lemah-after-2');
                        IndicatorSedangBefore2.classList.add('kata-sandi-sedang-before-2');
                        IndicatorLemahBefore2.classList.remove('kata-sandi-lemah-before-2');
                        IndicatorSedangAfter2.classList.remove('kata-sandi-sedang-after-2');
                        IndicatorTulisan2.textContent = 'Kata Sandi Sedang';
                        IndicatorTulisan2.style.color = 'orange';

                    } else {
                        IndicatorKekuatan2.classList.add('kata-sandi-kuat');
                        IndicatorLemahAfter2.classList.add('kata-sandi-lemah-after-2');
                        IndicatorSedangAfter2.classList.add('kata-sandi-sedang-after-2');
                        IndicatorLemahBefore2.classList.remove('kata-sandi-lemah-before-2');
                        IndicatorSedangBefore2.classList.remove('kata-sandi-sedang-before-2');
                        IndicatorTulisan2.textContent = 'Kata Sandi Kuat';
                        IndicatorTulisan2.style.color = '#23ad5c';
                    }
                }
                // /Indicator Kata Sandi 2 //

                // Indicator Kata Sandi 3 //
                const passwordInput3 = document.getElementById('passwordInput3');
                const IndicatorKekuatan3 = document.getElementById('indicator-kata-sandi-3');
                var IndicatorLemahBefore3 = document.querySelector(".kata-sandi-lemah-before-3");
                var IndicatorSedangBefore3 = document.querySelector(".kata-sandi-sedang-before-3");
                var IndicatorLemahAfter3 = document.querySelector(".kata-sandi-lemah-after-3");
                var IndicatorSedangAfter3 = document.querySelector(".kata-sandi-sedang-after-3");
                const IndicatorTulisan3 = document.getElementById('indicator-kata-sandi-tulisan-3');

                passwordInput3.addEventListener('input', function() {
                    const password3 = passwordInput3.value.trim();
                    if (password3 === '') {
                        IndicatorKekuatan3.style.display = 'none';
                        IndicatorLemahBefore3.style.display = 'none';
                        IndicatorSedangBefore3.style.display = 'none';
                        IndicatorLemahAfter3.style.display = 'none';
                        IndicatorSedangAfter3.style.display = 'none';
                        IndicatorTulisan3.textContent = '';
                    } else {
                        const strength3 = kekuatanKataSandi3(password3);
                        perbaharuiIndicatorKataSandi3(strength3);
                    }
                });
                
                function kekuatanKataSandi3(password3) {
                    if (/[a-z]/.test(password3)) {
                        if (/\d+/.test(password3)) {
                            if (/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/.test(password3)) {
                                return 'kuat';
                            } else {
                                return 'sedang';
                            }
                        } else {
                            return 'lemah';
                        }
                    } else {
                        return 'lemah';
                    }
                }

                function perbaharuiIndicatorKataSandi3(strength3) {
                    IndicatorKekuatan3.style.display = '';
                    IndicatorKekuatan3.className = '';
                    IndicatorLemahBefore3.style.display = 'block';
                    IndicatorSedangBefore3.style.display = 'block';
                    IndicatorLemahAfter3.style.display = 'block';
                    IndicatorSedangAfter3.style.display = 'block';

                    if (strength3 === 'lemah') {
                        IndicatorKekuatan3.classList.add('kata-sandi-lemah');
                        IndicatorLemahBefore3.classList.add('kata-sandi-lemah-before-3');
                        IndicatorSedangBefore3.classList.add('kata-sandi-sedang-before-3');
                        IndicatorLemahAfter3.classList.remove('kata-sandi-lemah-after-3');
                        IndicatorSedangAfter3.classList.remove('kata-sandi-sedang-after-3');
                        IndicatorTulisan3.textContent = 'Kata Sandi Lemah';
                        IndicatorTulisan3.style.color = '#ff4757';

                    } else if (strength3 === 'sedang') {
                        IndicatorKekuatan3.classList.add('kata-sandi-sedang');
                        IndicatorLemahAfter3.classList.add('kata-sandi-lemah-after-3');
                        IndicatorSedangBefore3.classList.add('kata-sandi-sedang-before-3');
                        IndicatorLemahBefore3.classList.remove('kata-sandi-lemah-before-3');
                        IndicatorSedangAfter3.classList.remove('kata-sandi-sedang-after-3');
                        IndicatorTulisan3.textContent = 'Kata Sandi Sedang';
                        IndicatorTulisan3.style.color = 'orange';

                    } else {
                        IndicatorKekuatan3.classList.add('kata-sandi-kuat');
                        IndicatorLemahAfter3.classList.add('kata-sandi-lemah-after-3');
                        IndicatorSedangAfter3.classList.add('kata-sandi-sedang-after-3');
                        IndicatorLemahBefore3.classList.remove('kata-sandi-lemah-before-3');
                        IndicatorSedangBefore3.classList.remove('kata-sandi-sedang-before-3');
                        IndicatorTulisan3.textContent = 'Kata Sandi Kuat';
                        IndicatorTulisan3.style.color = '#23ad5c';
                    }
                }
                // /Indicator Kata Sandi 3 //
            });
        </script>
    @endpush
@endsection