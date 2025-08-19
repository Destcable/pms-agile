@extends('layouts.app')

@section('content')
<div>
    <h1 class="h4 mb-2">Панель управления</h1>
    <p class="text-secondary">Добро пожаловать, {{ auth()->user()->name }}!</p>
</div>
@endsection


