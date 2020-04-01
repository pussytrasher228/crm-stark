@extends('layouts.guest')

@section('content')

    <div class="blur-bg-images"></div>
    <div class="auth-wrapper">
        <div class="auth-content container">
            <div class="card">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="card-body">
                            <h2 class="mb-4">Приветствуем в <span class="text-c-blue">Stark CRM</span></h2>
                            <p>Здесь должно быть описание, которое видимо будет позже, по этому я просто напишу сюда какой-то рандомный текст.
                                Не горю желанием переводить lorem ipsum на русский. :D</p>

                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="input-group mb-2 mt-5">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="feather icon-mail"></i></span>
                                    </div>
                                    <input type="email" name="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="Почта" required autofocus>
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="input-group mb-4">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="feather icon-lock"></i></span>
                                    </div>
                                    <input type="password" name="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="Пароль">
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group text-left">
                                    <div class="checkbox checkbox-primary d-inline">
                                        <input type="checkbox" name="remember" id="checkbox-fill-a1" {{ old('remember') ? 'checked' : '' }}>
                                        <label for="checkbox-fill-a1" class="cr"> Запомнить</label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mb-4">Войти</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6 d-none d-md-block">
                        <img src="/assets/images/auth/img-auth-2.jpg" alt="" class="img-fluid bd-placeholder-img bd-placeholder-img-lg d-block w-100">
                        <img src="/assets/images/logo.svg" alt="" class="img-fluid img-logo-overlay" width="150px">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
