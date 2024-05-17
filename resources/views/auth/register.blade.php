@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center">
        <!----------------------- Login Container -------------------------->
        <div class="row border rounded-5 p-3 bg-white shadow box-area">
            <!--------------------------- Left Box ----------------------------->
            <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box"
                style="background: #6bc1f3;">
                <div class="featured-image mb-3">
                    <img src="images/logo_length.png" class="img-fluid" style="width: 250px;">
                </div>
                <p class="text-white fs-2" style="font-family: 'Courier New', Courier, monospace; font-weight: 600;">Let's
                    Support</p>
                <small class="text-white text-wrap text-center"
                    style="width: 17rem;font-family: 'Courier New', Courier, monospace;">Dukung Program Dakwah Syiar Quran
                    Project</small>
            </div>
            <!-------------------- ------ Right Box ---------------------------->

            <div class="col-md-6 right-box">
                <div class="row align-items-center">
                    <div class="header-text mb-4 text-center">
                        <h2>Hello,Again</h2>
                        <p>We are happy to have you back.</p>
                    </div>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="input-group mb-3">
                            <input type="text" class="form-control form-control-lg bg-light fs-6" placeholder="Name"
                                name="name">
                        </div>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control form-control-lg bg-light fs-6"
                                placeholder="Email address" name="email">
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control form-control-lg bg-light fs-6" placeholder="Password"
                                name="password">
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control form-control-lg bg-light fs-6"
                                placeholder="Password Confirmation" name="password_confirmation">
                        </div>
                        <div class="input-group mt-3">
                            <button class="btn btn-lg btn-primary w-100 fs-6" type="submit"
                                style="background-color: #6bc1f3; border: #6bc1f3">Login</button>
                        </div>
                    </form>
                    <p class="text-center mt-3">punya akun ? <a href="/login" style="color: #6bc1f3">silahkan
                            Login</a> </p>
                </div>
            </div>
        </div>
    </div>
@endsection
