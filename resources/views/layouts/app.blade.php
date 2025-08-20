<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PMS') }}</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .navbar-brand {
            font-size: 1.5rem;
        }
        .project-selector {
            min-width: 200px;
        }
        @media (max-width: 768px) {
            .project-selector {
                min-width: 150px;
            }
            .navbar-brand {
                font-size: 1.2rem;
            }
        }
        .sidebar {
            transition: all 0.3s ease;
        }
        .sidebar.collapse:not(.show) {
            display: none;
        }
        .sidebar.show {
            display: block;
        }
        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
        }
        .navbar-nav .nav-link:hover {
            color: rgba(255, 255, 255, 1) !important;
        }
        .sidebar .nav-link {
            color: #6c757d !important;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin: 0.125rem 0.5rem;
            transition: all 0.2s ease;
        }
        .sidebar .nav-link:hover {
            color: #495057 !important;
            background-color: #e9ecef;
        }
        .sidebar .nav-link.active {
            color: #fff !important;
            background-color: #0d6efd;
        }
        .sidebar .nav-link.active:hover {
            color: #fff !important;
            background-color: #0b5ed7;
        }
        .sidebar-heading {
            font-size: 0.75rem;
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }
        .btn-group-sm > .btn, .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            border-radius: 0.375rem;
        }
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.7rem;
            padding: 0.2rem 0.4rem;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .notification-icon {
            position: relative;
            display: inline-block;
        }
        .project-selection-page {
            min-height: calc(100vh - 56px);
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        .project-selection-card {
            max-width: 600px;
            width: 100%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 15px;
        }
        .project-selection-icon {
            font-size: 4rem;
            color: #6c757d;
            margin-bottom: 1rem;
        }
    </style>
    @vite(['resources/js/app.js'])
</head>
<body>
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <!-- Brand -->
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                <i class="bi bi-kanban me-2"></i>PMS
            </a>

            @auth
            <!-- Mobile Toggle Button - Only show when project is selected or creating a project -->
            @if(session('current_project_id') || request()->routeIs('projects.create'))
            <button class="navbar-toggler d-lg-none me-2" type="button" data-bs-toggle="collapse" data-bs-target=".sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            @endif

            <!-- Project Selector -->
            <div class="navbar-nav me-auto">
                <div class="nav-item dropdown">
                    <select id="project-selector" class="form-select form-select-sm bg-light border-0 project-selector">
                        <option value="">Выберите проект</option>
                        @foreach(Auth::user()->projects()->orderBy('name')->get() as $project)
                            <option value="{{ $project->id }}" {{ session('current_project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Right side navbar items for authenticated users -->
            <div class="navbar-nav ms-auto">
                <!-- Notifications -->
                <div class="nav-item dropdown me-3">
                    <a class="nav-link notification-icon" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell fs-5"></i>
                        <span class="badge bg-danger notification-badge">
                            0
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">Уведомления</h6></li>
                        <li><a class="dropdown-item" href="#">Новых уведомлений нет</a></li>
                    </ul>
                </div>

                <!-- User Dropdown -->
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                            <span class="text-primary fw-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <span class="d-none d-lg-inline">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">{{ Auth::user()->email }}</h6></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Профиль</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Настройки</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button class="dropdown-item text-danger" type="submit">
                                    <i class="bi bi-box-arrow-right me-2"></i>Выйти
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            @else
            <!-- Right side navbar items for guests -->
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('login') }}">
                    <i class="bi bi-box-arrow-in-right me-1"></i>Войти
                </a>
                <a class="nav-link" href="{{ route('register') }}">
                    <i class="bi bi-person-plus me-1"></i>Регистрация
                </a>
            </div>
            @endauth
        </div>
    </nav>

    @auth
        @if(session('current_project_id') || request()->routeIs('projects.create'))
            <!-- Project is selected OR user is creating a project - Show sidebar and main content -->
            <div class="container-fluid">
                <div class="row">
                    <!-- Sidebar - Only show when project is actually selected -->
                    @if(session('current_project_id'))
                    <nav class="col-lg-2 d-none d-lg-block bg-light sidebar" style="min-height: calc(100vh - 56px);">
                        <div class="position-sticky pt-3">
                            <!-- Navigation Menu -->
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                        <i class="bi bi-house me-2"></i>
                                        Панель управления
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('project.settings') }}">
                                        <i class="bi bi-gear me-2"></i>
                                        Настройки проекта
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-kanban me-2"></i>
                                        Задачи
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-people me-2"></i>
                                        Команда
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-calendar me-2"></i>
                                        Календарь
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-graph-up me-2"></i>
                                        Отчеты
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-file-earmark-text me-2"></i>
                                        Документы
                                    </a>
                                </li>
                            </ul>

                            <!-- Quick Actions -->
                            <hr class="my-3">
                            <div class="px-3">
                                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 text-muted text-uppercase">
                                    <span>Быстрые действия</span>
                                </h6>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-plus me-1"></i>Новый проект
                                    </a>
                                    <a href="{{ route('projects.index') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-folder me-1"></i>Управление проектами
                                    </a>
                                    <a href="#" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-plus me-1"></i>Новая задача
                                    </a>
                                </div>
                            </div>
                        </div>
                    </nav>

                    <!-- Mobile Sidebar -->
                    <nav class="col-12 d-lg-none bg-light sidebar collapse" style="min-height: calc(100vh - 56px);">
                        <div class="pt-3">
                            <!-- Navigation Menu -->
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                        <i class="bi bi-house me-2"></i>
                                        Панель управления
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('project.settings') }}">
                                        <i class="bi bi-gear me-2"></i>
                                        Настройки проекта
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-kanban me-2"></i>
                                        Задачи
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-people me-2"></i>
                                        Команда
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-calendar me-2"></i>
                                        Календарь
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-graph-up me-2"></i>
                                        Отчеты
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-file-earmark-text me-2"></i>
                                        Документы
                                    </a>
                                </li>
                            </ul>

                            <!-- Quick Actions -->
                            <hr class="my-3">
                            <div class="px-3">
                                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 text-muted text-uppercase">
                                    <span>Быстрые действия</span>
                                </h6>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-plus me-1"></i>Новый проект
                                    </a>
                                    <a href="{{ route('projects.index') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-folder me-1"></i>Управление проектами
                                    </a>
                                    <a href="#" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-plus me-1"></i>Новая задача
                                    </a>
                                </div>
                            </div>
                        </div>
                    </nav>
                    @endif

                    <!-- Main content -->
                    <main class="{{ session('current_project_id') ? 'col-lg-10' : 'col-12' }} px-md-4" style="min-height: calc(100vh - 56px);">
                        <!-- Page content -->
                        <div class="pt-3">
                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            
                            @yield('content')
                        </div>
                    </main>
                </div>
            </div>
        @else
            <!-- No project selected - Show project selection page on other routes -->
            <div class="project-selection-page">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6">
                            <div class="card project-selection-card">
                                <div class="card-body text-center p-5">
                                    <div class="project-selection-icon">
                                        <i class="bi bi-folder-plus"></i>
                                    </div>
                                    <h3 class="card-title mb-3">Добро пожаловать в PMS!</h3>
                                    <p class="card-text text-muted mb-4">
                                        Для начала работы необходимо выбрать существующий проект или создать новый.
                                    </p>
                                    
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('projects.create') }}" class="btn btn-primary btn-lg">
                                            <i class="bi bi-plus-circle me-2"></i>Создать новый проект
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @else
        <!-- Guest user - Show main content without sidebar -->
        <main class="col-12 px-md-4" style="min-height: calc(100vh - 56px);">
            <!-- Page content -->
            <div class="pt-3">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </main>
    @endauth

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <!-- Project Selection Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Main project selector in navbar
            const projectSelector = document.getElementById('project-selector');
            if (projectSelector) {
                projectSelector.addEventListener('change', function() {
                    const projectId = this.value;
                    
                    fetch('{{ route("projects.set-current") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ project_id: projectId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (projectId) {
                                window.location.reload();
                            } else {
                                window.location.href = '{{ route("projects.index") }}';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });
            }
        });
    </script>
</body>
</html>


