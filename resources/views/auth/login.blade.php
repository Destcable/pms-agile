@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-6 col-lg-5">
    <h1 class="mb-3 h4">Вход</h1>
    <form action="{{ route('login.attempt') }}" method="POST" class="vstack gap-3">
        @csrf
        <div>
            <label class="form-label" for="email">Email</label>
            <input class="form-control" type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label class="form-label" for="password">Пароль</label>
            <input class="form-control" type="password" id="password" name="password" required>
            @error('password')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="d-flex align-items-center justify-content-between">
            <div class="form-check">
                <input type="checkbox" name="remember" id="remember" class="form-check-input">
                <label for="remember" class="form-check-label">Запомнить меня</label>
            </div>
            <a class="small" href="{{ route('register') }}">Регистрация</a>
        </div>
        <button class="btn btn-primary w-100">Войти</button>
    </form>
    </div>
</div>
@endsection


