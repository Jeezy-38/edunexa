@extends('layouts' . '.' . get_frontend_settings('theme'))
@push('title', get_phrase('Log In'))
@push('meta')@endpush
@push('css')
    <style>
        .form-icons .right {
            right: 20px;
            cursor: pointer !important;
        }
        .login-img img {
            max-height: 560px !important;
            width: auto !important;
            max-width: 100% !important;
            object-fit: contain !important;
            margin: 0 auto;
            display: block;
        }
    </style>
@endpush
@section('content')
    @if (get_frontend_settings('theme') == 'default')
        <!------------------- Login Area Start  ------>
        <section class="login-area">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-7 col-md-6">
                        <div class="login-img">
                            <img src="{{ asset('assets/frontend/default/image/login.png') }}" alt="EduNexa">
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-6">
                        <form action="{{ route('login') }}" class="global-form login-form mt-25" id="login-form" method="POST">
                            @csrf
                            <h4 class="g-title">{{ get_phrase('Login') }}</h4>
                            <p class="description">{{ get_phrase('See your growth and get consulting support!') }} </p>
                            <div class="form-group">
                                <label for="email" class="form-label">{{ get_phrase('Email') }}</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="{{ get_phrase('Your Email') }}">
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">{{ get_phrase('Password') }}</label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="*********">
                            </div>
                            <div class="form-group mb-25 d-flex justify-content-between align-items-center remember-me">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                                    <label class="form-check-label" for="flexCheckChecked">{{ get_phrase('Remember Me') }}</label>
                                </div>
                                <a href="{{route('password.request')}}">{{ get_phrase('Forget Password?') }}</a>
                            </div>

                            @if(get_frontend_settings('recaptcha_status'))
                                <button class="eBtn gradient w-100 g-recaptcha" data-sitekey="{{ get_frontend_settings('recaptcha_sitekey') }}" data-callback='onLoginSubmit' data-action='submit'>{{ get_phrase('Login') }}</button>
                            @else
                                <button type="submit" class="eBtn gradient w-100">{{ get_phrase('Login') }}</button>
                            @endif

                            <div class="position-relative my-4 text-center">
                                <hr class="border-secondary opacity-25">
                                <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted" style="font-size: 14px;">{{ get_phrase('or') }}</span>
                            </div>

                            <a href="{{ route('google.login') }}" class="btn btn-light w-100 d-flex align-items-center justify-content-center py-2 mb-3 shadow-sm border" style="border-radius: 8px; font-weight: 500; font-size: 15px; background: #ffffff; border-color: #e2e8f0; color: #1e293b; transition: all 0.3s ease;">
                                <svg class="me-2" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/>
                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" fill="#EA4335"/>
                                </svg>
                                {{ get_phrase('Continue with Google') }}
                            </a>

                            <p class="mt-20">{{ get_phrase('Not have an account yet?') }}
                                <a href="{{ route('register.form') }}">{{ get_phrase('Create Account') }}</a>
                            </p>

                            {{-- <p class="my-3">Login As -</p>
                            <button type="button" class="eBtn gradient w-100 mb-3 py-3 custom-btn" id="admin">Admin</button>
                            <button type="button" class="eBtn gradient w-100 mb-3 py-3 custom-btn" id="student">Student</button>
                            <button type="button" class="eBtn gradient w-100 mb-3 py-3 custom-btn" id="instructor">Instructor</button> --}}
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!------------------- Login Area End  ------>
    @endif
@endsection
@push('js')

    <script>
        "use strict";

        $(document).ready(function() {
            $('.custom-btn').on('click', function(e) {
                e.preventDefault();

                var role = $(this).attr('id');
                if (role == 'admin') {
                    $('#email').val('admin@example.com');
                    $('#password').val('12345678');
                } else if (role == 'student') {
                    $('#email').val('student@example.com');
                    $('#password').val('12345678');
                } else {
                    $('#email').val('instructor@example.com');
                    $('#password').val('12345678');
                }
                $('#login').trigger('click');
            });
        });

        $(document).ready(function() {
            $('#showpassword').on('click', function(e) {
                e.preventDefault();
                const type = $('#password').attr('type');

                if (type == 'password') {
                    $('#password').attr('type', 'text');
                } else {
                    $('#password').attr('type', 'password');
                }
            });
        });

        function onLoginSubmit(token) {
            document.getElementById("login-form").submit();
        }

    </script>
@endpush
