@extends('layouts.app')

@section('content')
<style>
.settings-nav .nav-link {
    color: #6c757d;
    border: none;
    border-radius: 0;
    padding: 0.75rem 1rem;
    border-left: 3px solid transparent;
    transition: all 0.2s ease;
}

.settings-nav .nav-link:hover {
    color: #495057;
    background-color: #f8f9fa;
    border-left-color: #dee2e6;
}

.settings-nav .nav-link.active {
    color: #0d6efd;
    background-color: #f8f9fa;
    border-left-color: #0d6efd;
    font-weight: 600;
}

.settings-nav .nav-link i {
    width: 20px;
    text-align: center;
}

.tab-content .tab-pane {
    display: none;
}

.tab-content .tab-pane.show.active {
    display: block;
}

.settings-card {
    border: 1px solid #dee2e6;
    border-radius: 6px;
}

.settings-card .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    padding: 1rem;
}

.settings-card .card-body {
    padding: 1.5rem;
}

.integration-card {
    border: 1px solid #dee2e6;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.integration-card:hover {
    border-color: #0d6efd;
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.1);
}
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="bi bi-gear me-2"></i>Настройки проекта
                </h1>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Назад к панели
                </a>
            </div>
            
            <div class="row">
                <div class="col-lg-3">
                    <!-- Settings Navigation -->
                    <div class="card">
                        <div class="card-body p-0">
                            <nav class="nav flex-column settings-nav">
                                <a class="nav-link active" href="#general" data-bs-toggle="tab" role="tab">
                                    <i class="bi bi-gear me-2"></i>Общие
                                </a>
                                <a class="nav-link" href="#access" data-bs-toggle="tab" role="tab">
                                    <i class="bi bi-people me-2"></i>Доступ
                                </a>
                                <a class="nav-link" href="#integrations" data-bs-toggle="tab" role="tab">
                                    <i class="bi bi-plug me-2"></i>Интеграции
                                </a>
                                <a class="nav-link" href="#webhooks" data-bs-toggle="tab" role="tab">
                                    <i class="bi bi-link-45deg me-2"></i>Webhooks
                                </a>
                                <a class="nav-link" href="#danger" data-bs-toggle="tab" role="tab">
                                    <i class="bi bi-exclamation-triangle me-2 text-danger"></i>Опасная зона
                                </a>
                            </nav>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-9">
                    <!-- Settings Content -->
                    <div class="tab-content">
                        <!-- General Settings -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel">
                            <div class="card settings-card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Общие настройки</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        <label for="projectName" class="form-label">Название проекта</label>
                                        <input type="text" class="form-control" id="projectName" value="{{ $project->name }}" readonly>
                                        <div class="form-text">Название проекта можно изменить в <a href="{{ route('projects.edit', $project) }}">редактировании проекта</a></div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="projectDescription" class="form-label">Описание</label>
                                        <textarea class="form-control" id="projectDescription" rows="3" readonly>{{ $project->description ?? 'Описание не указано' }}</textarea>
                                        <div class="form-text">Описание можно изменить в <a href="{{ route('projects.edit', $project) }}">редактировании проекта</a></div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="projectStatus" class="form-label">Статус проекта</label>
                                            <select class="form-select" id="projectStatus" disabled>
                                                <option value="active" {{ $project->status === 'active' ? 'selected' : '' }}>Активный</option>
                                                <option value="paused" {{ $project->status === 'paused' ? 'selected' : '' }}>Приостановлен</option>
                                                <option value="completed" {{ $project->status === 'completed' ? 'selected' : '' }}>Завершен</option>
                                                <option value="cancelled" {{ $project->status === 'cancelled' ? 'selected' : '' }}>Отменен</option>
                                            </select>
                                            <div class="form-text">Статус можно изменить в <a href="{{ route('projects.edit', $project) }}">редактировании проекта</a></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="projectVisibility" class="form-label">Видимость</label>
                                            <select class="form-select" id="projectVisibility">
                                                <option value="public">Публичный</option>
                                                <option value="private" selected>Приватный</option>
                                            </select>
                                            <div class="form-text">Приватные проекты видны только участникам команды</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Access Settings -->
                        <div class="tab-pane fade" id="access" role="tabpanel">
                            <div class="card settings-card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Управление доступом</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        <h6>Участники команды</h6>
                                        <p class="text-muted">Управляйте доступом к проекту</p>
                                        <button class="btn btn-primary btn-sm">
                                            <i class="bi bi-person-plus me-2"></i>Добавить участника
                                        </button>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Пользователь</th>
                                                    <th>Роль</th>
                                                    <th>Действия</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                                <span class="text-white fw-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                                            </div>
                                                            <div>
                                                                <div class="fw-semibold">{{ Auth::user()->name }}</div>
                                                                <small class="text-muted">{{ Auth::user()->email }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary">Владелец</span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-outline-secondary btn-sm" disabled>Изменить роль</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Integrations Settings -->
                        <div class="tab-pane fade" id="integrations" role="tabpanel">
                            <div class="card settings-card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Интеграции</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        <h6>Доступные интеграции</h6>
                                        <p class="text-muted">Подключите внешние сервисы к вашему проекту</p>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="card integration-card">
                                                <div class="card-body text-center">
                                                    <i class="bi bi-github fs-1 text-dark mb-3"></i>
                                                    <h6>GitHub</h6>
                                                    <p class="text-muted small">Синхронизация с репозиторием</p>
                                                    <button class="btn btn-outline-primary btn-sm">Подключить</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="card integration-card">
                                                <div class="card-body text-center">
                                                    <i class="bi bi-slack fs-1 text-primary mb-3"></i>
                                                    <h6>Slack</h6>
                                                    <p class="text-muted small">Уведомления в чат</p>
                                                    <button class="btn btn-outline-primary btn-sm">Подключить</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Webhooks Settings -->
                        <div class="tab-pane fade" id="webhooks" role="tabpanel">
                            <div class="card settings-card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Webhooks</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        <h6>Настройка webhooks</h6>
                                        <p class="text-muted">Получайте уведомления о событиях проекта</p>
                                        <button class="btn btn-primary btn-sm">
                                            <i class="bi bi-plus me-2"></i>Добавить webhook
                                        </button>
                                    </div>
                                    
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Webhooks позволяют отправлять HTTP-запросы при наступлении определенных событий в проекте
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Danger Zone -->
                        <div class="tab-pane fade" id="danger" role="tabpanel">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-exclamation-triangle me-2"></i>Опасная зона
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        <h6 class="text-danger">Удаление проекта</h6>
                                        <p class="text-muted">
                                            Удаление проекта необратимо. Все данные, задачи и документы будут потеряны навсегда.
                                        </p>
                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteProjectModal">
                                            <i class="bi bi-trash me-2"></i>Удалить проект
                                        </button>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <h6 class="text-warning">Архивирование проекта</h6>
                                        <p class="text-muted">
                                            Архивирование проекта скроет его из активных, но данные сохранятся.
                                        </p>
                                        <button class="btn btn-outline-warning" disabled>
                                            <i class="bi bi-archive me-2"></i>Архивировать проект
                                        </button>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <h6 class="text-info">Экспорт данных</h6>
                                        <p class="text-muted">
                                            Экспортируйте все данные проекта перед удалением.
                                        </p>
                                        <button class="btn btn-outline-info" disabled>
                                            <i class="bi bi-download me-2"></i>Экспортировать данные
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Project Modal -->
<div class="modal fade" id="deleteProjectModal" tabindex="-1" aria-labelledby="deleteProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-danger">
                <h5 class="modal-title text-danger" id="deleteProjectModalLabel">
                    <i class="bi bi-exclamation-triangle me-2"></i>Удаление проекта
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h6 class="alert-heading">
                        <i class="bi bi-exclamation-triangle me-2"></i>Внимание!
                    </h6>
                    <p class="mb-0">
                        Удаление проекта <strong>"{{ $project->name }}"</strong> необратимо. 
                        Все данные, задачи и документы будут потеряны навсегда.
                    </p>
                </div>
                
                <form action="{{ route('projects.destroy', $project) }}" method="POST" id="deleteProjectForm">
                    @csrf
                    @method('DELETE')
                    
                    <div class="mb-3">
                        <label for="projectNameConfirmation" class="form-label">
                            Для подтверждения удаления введите название проекта:
                        </label>
                        <input type="text" class="form-control" id="projectNameConfirmation" 
                               placeholder="Введите название проекта для подтверждения" required>
                        <div class="form-text text-danger">
                            Введите точно: <strong>{{ $project->name }}</strong>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-danger" id="deleteProjectBtn" disabled>
                            <i class="bi bi-trash me-2"></i>Удалить проект навсегда
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Settings navigation
    const settingsNav = document.querySelectorAll('#general, #access, #integrations, #webhooks, #danger');
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all links and tabs
            navLinks.forEach(l => l.classList.remove('active'));
            settingsNav.forEach(tab => tab.classList.remove('show', 'active'));
            
            // Add active class to clicked link
            this.classList.add('active');
            
            // Show corresponding tab
            const targetId = this.getAttribute('href').substring(1);
            const targetTab = document.getElementById(targetId);
            if (targetTab) {
                targetTab.classList.add('show', 'active');
            }
        });
    });
    
    // Project deletion confirmation validation
    const projectNameConfirmation = document.getElementById('projectNameConfirmation');
    const deleteProjectBtn = document.getElementById('deleteProjectBtn');
    const deleteProjectForm = document.getElementById('deleteProjectForm');
    
    if (projectNameConfirmation && deleteProjectBtn) {
        const expectedProjectName = '{{ $project->name }}';
        
        projectNameConfirmation.addEventListener('input', function() {
            const enteredName = this.value.trim();
            if (enteredName === expectedProjectName) {
                deleteProjectBtn.disabled = false;
                deleteProjectBtn.classList.remove('btn-secondary');
                deleteProjectBtn.classList.add('btn-danger');
            } else {
                deleteProjectBtn.disabled = true;
                deleteProjectBtn.classList.remove('btn-danger');
                deleteProjectBtn.classList.add('btn-secondary');
            }
        });
        
        // Prevent form submission if validation fails
        deleteProjectForm.addEventListener('submit', function(e) {
            const enteredName = projectNameConfirmation.value.trim();
            if (enteredName !== expectedProjectName) {
                e.preventDefault();
                alert('Пожалуйста, введите точное название проекта для подтверждения удаления.');
                return false;
            }
            
            // Final confirmation
            if (!confirm('Вы уверены, что хотите удалить проект "' + expectedProjectName + '"? Это действие нельзя отменить!')) {
                e.preventDefault();
                return false;
            }
        });
    }
});
</script>
@endpush
