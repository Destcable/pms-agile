@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Панель управления</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">Экспорт</button>
            <button type="button" class="btn btn-sm btn-outline-secondary">Печать</button>
        </div>
    </div>
</div>

@if(isset($currentProject))
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Текущий проект: {{ $currentProject->name }}</h5>
            </div>
            <div class="card-body">
                <p class="card-text">{{ $currentProject->description ?: 'Описание проекта не указано.' }}</p>
                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted">Статус:</small>
                        <span class="badge bg-{{ $currentProject->status === 'active' ? 'success' : ($currentProject->status === 'paused' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($currentProject->status) }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Владелец:</small>
                        <span class="fw-bold">{{ $currentProject->owner->name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Быстрые действия</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-primary btn-sm">Создать задачу</a>
                    <a href="#" class="btn btn-outline-primary btn-sm">Добавить участника</a>
                    <a href="#" class="btn btn-outline-primary btn-sm">Создать отчет</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Статистика проекта</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="h4 text-primary">0</div>
                        <small class="text-muted">Задач</small>
                    </div>
                    <div class="col-4">
                        <div class="h4 text-success">0</div>
                        <small class="text-muted">Завершено</small>
                    </div>
                    <div class="col-4">
                        <div class="h4 text-warning">0</div>
                        <small class="text-muted">В работе</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Последние активности</h6>
            </div>
            <div class="card-body">
                <p class="text-muted small">Активности пока не зарегистрированы</p>
            </div>
        </div>
    </div>
</div>
@else
<div class="alert alert-info" role="alert">
    <h4 class="alert-heading">Добро пожаловать в PMS!</h4>
    <p>Для начала работы с системой необходимо создать проект или выбрать существующий.</p>
    <hr>
    <p class="mb-0">
        <a href="{{ route('projects.create') }}" class="btn btn-primary">Создать проект</a>
        <a href="{{ route('projects.index') }}" class="btn btn-outline-primary ms-2">Мои проекты</a>
    </p>
</div>
@endif
@endsection


