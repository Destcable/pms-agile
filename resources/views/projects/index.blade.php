@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Мои проекты</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('projects.create') }}" class="btn btn-primary">
            <i class="bi bi-plus"></i> Создать проект
        </a>
    </div>
</div>

@if($projects->count() > 0)
<div class="row">
    @foreach($projects as $project)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">{{ $project->name }}</h5>
                <span class="badge bg-{{ $project->status === 'active' ? 'success' : ($project->status === 'paused' ? 'warning' : 'secondary') }}">
                    {{ ucfirst($project->status) }}
                </span>
            </div>
            <div class="card-body">
                <p class="card-text">{{ Str::limit($project->description, 100) ?: 'Описание проекта не указано.' }}</p>
                
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted">Дата начала:</small><br>
                        <span class="fw-bold">{{ $project->start_date ? $project->start_date->format('d.m.Y') : 'Не указана' }}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Дата окончания:</small><br>
                        <span class="fw-bold">{{ $project->end_date ? $project->end_date->format('d.m.Y') : 'Не указана' }}</span>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">Создан: {{ $project->created_at->format('d.m.Y') }}</small>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('projects.show', $project) }}" class="btn btn-outline-primary">Просмотр</a>
                        <a href="{{ route('projects.edit', $project) }}" class="btn btn-outline-secondary">Редактировать</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="text-center py-5">
    <div class="mb-4">
        <i class="bi bi-folder-x display-1 text-muted"></i>
    </div>
    <h3>У вас пока нет проектов</h3>
    <p class="text-muted">Создайте первый проект для начала работы с системой</p>
    <a href="{{ route('projects.create') }}" class="btn btn-primary btn-lg">
        <i class="bi bi-plus"></i> Создать проект
    </a>
</div>
@endif
@endsection
