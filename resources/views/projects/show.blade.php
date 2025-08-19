@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ $project->name }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('projects.edit', $project) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-pencil"></i> Редактировать
            </a>
            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Назад
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Описание проекта</h5>
            </div>
            <div class="card-body">
                <p class="card-text">{{ $project->description ?: 'Описание проекта не указано.' }}</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Детали проекта</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-4">Статус:</dt>
                            <dd class="col-sm-8">
                                <span class="badge bg-{{ $project->status === 'active' ? 'success' : ($project->status === 'paused' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($project->status) }}
                                </span>
                            </dd>
                            
                            <dt class="col-sm-4">Владелец:</dt>
                            <dd class="col-sm-8">{{ $project->owner->name }}</dd>
                            
                            <dt class="col-sm-4">Создан:</dt>
                            <dd class="col-sm-8">{{ $project->created_at->format('d.m.Y H:i') }}</dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-4">Дата начала:</dt>
                            <dd class="col-sm-8">{{ $project->start_date ? $project->start_date->format('d.m.Y') : 'Не указана' }}</dd>
                            
                            <dt class="col-sm-4">Дата окончания:</dt>
                            <dd class="col-sm-8">{{ $project->end_date ? $project->end_date->format('d.m.Y') : 'Не указана' }}</dd>
                            
                            <dt class="col-sm-4">Обновлен:</dt>
                            <dd class="col-sm-8">{{ $project->updated_at->format('d.m.Y H:i') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Быстрые действия</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus"></i> Создать задачу
                    </a>
                    <a href="#" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-people"></i> Добавить участника
                    </a>
                    <a href="#" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-file-earmark-text"></i> Создать отчет
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Статистика</h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <div class="h4 text-primary mb-1">0</div>
                    <small class="text-muted">Всего задач</small>
                </div>
                <hr>
                <div class="text-center">
                    <div class="h4 text-success mb-1">0</div>
                    <small class="text-muted">Завершено</small>
                </div>
                <hr>
                <div class="text-center">
                    <div class="h4 text-warning mb-1">0</div>
                    <small class="text-muted">В работе</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
