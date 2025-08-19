@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-6 col-lg-5">
    <h1 class="mb-3 h4">Регистрация</h1>
    <form action="{{ route('register.attempt') }}" method="POST" class="vstack gap-3">
        @csrf
        <div>
            <label class="form-label" for="name">Имя</label>
            <input class="form-control" type="text" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label class="form-label" for="email">Email</label>
            <input class="form-control" type="email" id="email" name="email" value="{{ old('email') }}" required>
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
        <div>
            <label class="form-label" for="password_confirmation">Подтверждение пароля</label>
            <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" required>
        </div>
        <button class="btn btn-primary w-100">Создать аккаунт</button>
        <p class="text-secondary small mt-2">Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a></p>
    </form>
    </div>
</div>
@endsection


