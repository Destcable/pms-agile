@extends('layouts.app')

@section('content')
<style>
    .gantt-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    .gantt-header {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 1rem;
    }
    .gantt-controls {
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }
    .gantt-chart {
        min-height: 600px;
        overflow-x: auto;
        overflow-y: auto;
        border: 1px solid #dee2e6;
        border-radius: 4px;
    }
    .gantt-timeline {
        display: flex;
        flex-direction: column;
        min-width: 0;
        position: relative;
    }
    .gantt-timeline-header {
        display: flex;
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    .gantt-task-names {
        width: 300px;
        background: #fff;
        border-right: 1px solid #dee2e6;
        font-weight: 600;
        padding: 10px 15px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .gantt-task-names-header {
        width: 300px;
        background: #fff;
        border-right: 1px solid #dee2e6;
        font-weight: 600;
        padding: 10px 15px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        flex-shrink: 0;
        box-sizing: border-box;
    }
    .gantt-dates-container {
        flex: 1;
        display: flex;
        flex-direction: column;
        margin-left: 0;
        background-image: repeating-linear-gradient(
            90deg,
            transparent 0px,
            transparent 29px,
            #dee2e6 29px,
            #dee2e6 30px
        );
        background-size: 30px 100%;
        background-repeat: repeat-x;
    }
    .gantt-months {
        display: flex;
        border-bottom: 1px solid #dee2e6;
        background: #e9ecef;
    }
    .gantt-month {
        text-align: center;
        font-weight: 600;
        color: #495057;
        padding: 8px;
        border-right: 1px solid #dee2e6;
        font-size: 14px;
    }
    .gantt-days {
        display: flex;
        background: #f8f9fa;
        border-top: 1px solid #dee2e6;
        position: relative;
        z-index: 2;
    }
    .gantt-day {
        width: 30px;
        text-align: center;
        font-size: 12px;
        padding: 8px 4px;
        border-right: 1px solid #dee2e6;
        color: #6c757d;
        font-weight: 500;
        position: relative;
    }
    /* Persistent bottom line under each day cell (prevents disappearance on large ranges) */
    .gantt-day::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -1px;
        width: 100%;
        height: 1px;
        background-color: #dee2e6;
    }

    .gantt-chart::-webkit-scrollbar {
        height: 12px;
    }
    .gantt-chart::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 6px;
    }
    .gantt-chart::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 6px;
    }
    .gantt-chart::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    .gantt-day.weekend {
        background: #ffe6e6;
        color: #dc3545;
    }
    .gantt-day.today {
        background: #d4edda;
        color: #155724;
        font-weight: 700;
    }
    .gantt-row {
        display: flex;
        min-height: 45px;
        align-items: center;
        position: relative;
        z-index: 1;
    }
    .gantt-row:hover {
        background: #f8f9fa;
        z-index: 2;
    }
    /* Ensure a top border for the first row to match header separation */
    .gantt-row:first-of-type .gantt-task-timeline::before {
        top: 0;
        bottom: auto;
    }
    .gantt-task-name {
        width: 300px;
        padding: 10px 15px;
        border-right: 1px solid #dee2e6;
        font-weight: 500;
        flex-shrink: 0;
        box-sizing: border-box;
    }
    .gantt-task-timeline {
        flex: 1;
        position: relative;
        height: 45px;
        display: flex;
        align-items: center;
        z-index: 1;
        background-image: repeating-linear-gradient(
            90deg,
            transparent 0px,
            transparent 29px,
            #dee2e6 29px,
            #dee2e6 30px
        );
        background-size: 30px 100%;
        background-repeat: repeat-x;
    }
    /* Persistent horizontal line across the entire row */
    .gantt-task-timeline::before {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 1px;
        background-color: #dee2e6;
        z-index: 0;
    }
    .gantt-task-bar {
        height: 24px;
        border-radius: 12px;
        position: absolute;
        color: white;
        font-size: 11px;
        display: flex;
        align-items: center;
        padding: 0 8px;
        font-weight: 500;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        cursor: grab;
        transition: transform 0.2s;
        min-width: 60px;
        z-index: 2;
    }
    .gantt-task-bar:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }
    .gantt-task-bar:active {
        cursor: grabbing;
    }
    .gantt-progress {
        height: 100%;
        background: rgba(255,255,255,0.3);
        border-radius: 12px;
        position: absolute;
        top: 0;
        left: 0;
    }
    .gantt-legend {
        display: flex;
        gap: 20px;
        margin-top: 1rem;
        flex-wrap: wrap;
    }
    .gantt-legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .gantt-legend-color {
        width: 16px;
        height: 16px;
        border-radius: 8px;
    }
    .gantt-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .gantt-stat-card {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 1rem;
        text-align: center;
    }
    .gantt-stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: #495057;
    }
    .gantt-stat-label {
        color: #6c757d;
        font-size: 0.875rem;
    }
    .date-input {
        width: 150px;
    }
    .gantt-zoom-controls {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }
    .zoom-btn {
        padding: 4px 8px;
        font-size: 12px;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="bi bi-bar-chart-line me-2"></i>Диаграмма Ганта
                </h1>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary">
                        <i class="bi bi-plus me-2"></i>Добавить задачу
                    </button>
                    <button class="btn btn-outline-secondary">
                        <i class="bi bi-download me-2"></i>Экспорт
                    </button>
                </div>
            </div>

            <!-- Gantt Controls -->
            <div class="gantt-controls">
                <div class="d-flex align-items-center gap-2">
                    <label for="startDate" class="form-label mb-0"><strong>Период:</strong></label>
                    <input type="date" id="startDate" class="form-control date-input" value="{{ $startDate }}">
                    <span>—</span>
                    <input type="date" id="endDate" class="form-control date-input" value="{{ $endDate }}">
                    <button class="btn btn-primary btn-sm" onclick="updateGanttPeriod()">
                        <i class="bi bi-arrow-clockwise me-1"></i>Обновить
                    </button>
                </div>
                
                <div class="gantt-zoom-controls">
                    <label class="form-label mb-0"><strong>Масштаб:</strong></label>
                    <button class="btn btn-outline-secondary zoom-btn" onclick="changeZoom('day')">День</button>
                    <button class="btn btn-secondary zoom-btn active" onclick="changeZoom('week')">Неделя</button>
                    <button class="btn btn-outline-secondary zoom-btn" onclick="changeZoom('month')">Месяц</button>
                </div>
                
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-outline-info btn-sm" onclick="scrollToToday()">
                        <i class="bi bi-calendar-date me-1"></i>Сегодня
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="resetZoom()">
                        <i class="bi bi-zoom-out me-1"></i>Сбросить
                    </button>
                </div>
            </div>
            
            <!-- Project Info -->
            <div class="alert alert-info">
                <div class="d-flex align-items-center">
                    <i class="bi bi-info-circle me-2"></i>
                    <div>
                        <strong>Проект:</strong> {{ $project->name }}
                        @if($project->description)
                            <br><small class="text-muted">{{ $project->description }}</small>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="gantt-stats">
                <div class="gantt-stat-card">
                    <div class="gantt-stat-number">{{ count($tasks) }}</div>
                    <div class="gantt-stat-label">Всего задач</div>
                </div>
                <div class="gantt-stat-card">
                    <div class="gantt-stat-number">{{ collect($tasks)->where('progress', 100)->count() }}</div>
                    <div class="gantt-stat-label">Завершено</div>
                </div>
                <div class="gantt-stat-card">
                    <div class="gantt-stat-number">{{ collect($tasks)->where('progress', '>', 0)->where('progress', '<', 100)->count() }}</div>
                    <div class="gantt-stat-label">В работе</div>
                </div>
                <div class="gantt-stat-card">
                    <div class="gantt-stat-number">{{ round(collect($tasks)->avg('progress')) }}%</div>
                    <div class="gantt-stat-label">Средний прогресс</div>
                </div>
            </div>

            <!-- Gantt Chart -->
            <div class="gantt-container">
                <div class="gantt-header">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar3 me-2"></i>График выполнения задач
                    </h5>
                </div>
                
                <div class="gantt-chart" id="ganttChart">
                    <div class="gantt-timeline" id="ganttTimeline">
                        <!-- Timeline Header -->
                        <div class="gantt-timeline-header">
                            <div class="gantt-task-names-header">
                                <div>Задача</div>
                                <small class="text-muted">Прогресс</small>
                            </div>
                            <div class="gantt-dates-container" style="min-width: {{ $timelineWidthPx }}px; width: {{ $timelineWidthPx }}px;">
                                <!-- Months Row -->
                                <div class="gantt-months" id="ganttMonths">
                                    @foreach($timelineData['months'] as $month)
                                        <div class="gantt-month" style="width: {{ $month['width'] }}px;">{{ $month['name'] }}</div>
                                    @endforeach
                                </div>
                                <!-- Days Row -->
                                <div class="gantt-days" id="ganttDays">
                                    @foreach($timelineData['days'] as $day)
                                        <div class="gantt-day{{ $day['isWeekend'] ? ' weekend' : '' }}{{ $day['isToday'] ? ' today' : '' }}" 
                                             data-date="{{ $day['date'] }}">{{ $day['day'] }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Tasks -->
                        @foreach($tasks as $task)
                        <div class="gantt-row">
                            <div class="gantt-task-name">
                                <div>{{ $task['name'] }}</div>
                                <small class="text-muted">{{ $task['progress'] }}% завершено</small>
                            </div>
                            <div class="gantt-task-timeline" style="min-width: {{ $timelineWidthPx }}px; width: {{ $timelineWidthPx }}px;">
                                <div class="gantt-task-bar" style="
                                    background-color: {{ $task['color'] }};
                                    width: {{ $task['width'] }}px;
                                    left: {{ $task['left'] }}px;
                                " 
                                data-task-id="{{ $task['id'] }}"
                                draggable="true"
                                title="{{ $task['name'] }} ({{ $task['start'] }} - {{ $task['end'] }})">
                                    <div class="gantt-progress" style="width: {{ $task['progress'] }}%;"></div>
                                    <span>{{ $task['name'] }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Legend -->
            <div class="gantt-legend">
                <div class="gantt-legend-item">
                    <div class="gantt-legend-color" style="background-color: #3498db;"></div>
                    <span>Планирование</span>
                </div>
                <div class="gantt-legend-item">
                    <div class="gantt-legend-color" style="background-color: #e74c3c;"></div>
                    <span>Анализ</span>
                </div>
                <div class="gantt-legend-item">
                    <div class="gantt-legend-color" style="background-color: #f39c12;"></div>
                    <span>Проектирование</span>
                </div>
                <div class="gantt-legend-item">
                    <div class="gantt-legend-color" style="background-color: #27ae60;"></div>
                    <span>Разработка</span>
                </div>
                <div class="gantt-legend-item">
                    <div class="gantt-legend-color" style="background-color: #9b59b6;"></div>
                    <span>Frontend</span>
                </div>
                <div class="gantt-legend-item">
                    <div class="gantt-legend-color" style="background-color: #34495e;"></div>
                    <span>Тестирование</span>
                </div>
                <div class="gantt-legend-item">
                    <div class="gantt-legend-color" style="background-color: #e67e22;"></div>
                    <span>Деплой</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Highlight today
    highlightToday();
    
    // Initialize drag and drop
    initializeDragAndDrop();
    
    // Scroll to current time period
    scrollToCurrentPeriod();
});

function updateGanttPeriod() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    if (!startDate || !endDate) {
        alert('Пожалуйста, выберите начальную и конечную даты');
        return;
    }
    
    if (new Date(startDate) >= new Date(endDate)) {
        alert('Начальная дата должна быть раньше конечной');
        return;
    }
    
    // Redirect to the same page with new parameters
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('start_date', startDate);
    currentUrl.searchParams.set('end_date', endDate);
    
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="bi bi-arrow-repeat me-1 spin"></i>Загрузка...';
    button.disabled = true;
    
    // Add CSS for spinning animation if not exists
    if (!document.querySelector('#spin-animation')) {
        const style = document.createElement('style');
        style.id = 'spin-animation';
        style.textContent = `
            .spin {
                animation: spin 1s linear infinite;
            }
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    }
    
    // Redirect after short delay
    setTimeout(() => {
        window.location.href = currentUrl.toString();
    }, 200);
}

function changeZoom(type) {
    // Remove active class from all zoom buttons
    document.querySelectorAll('.zoom-btn').forEach(btn => {
        btn.classList.remove('btn-secondary', 'active');
        btn.classList.add('btn-outline-secondary');
    });
    
    // Add active class to clicked button
    event.target.classList.remove('btn-outline-secondary');
    event.target.classList.add('btn-secondary', 'active');
    
    const timeline = document.getElementById('ganttTimeline');
    const chart = document.getElementById('ganttChart');
    
    // Adjust timeline width based on zoom level
    switch(type) {
        case 'day':
            timeline.style.minWidth = '4000px';
            chart.style.fontSize = '11px';
            break;
        case 'week':
            timeline.style.minWidth = '2000px';
            chart.style.fontSize = '12px';
            break;
        case 'month':
            timeline.style.minWidth = '1200px';
            chart.style.fontSize = '13px';
            break;
    }
    
    // Show feedback
    showToast(`Масштаб изменен: ${type === 'day' ? 'День' : type === 'week' ? 'Неделя' : 'Месяц'}`);
}

function scrollToToday() {
    const chart = document.getElementById('ganttChart');
    const today = new Date();
    const todayElements = document.querySelectorAll('.gantt-day.today');
    
    if (todayElements.length > 0) {
        todayElements[0].scrollIntoView({ 
            behavior: 'smooth', 
            block: 'nearest', 
            inline: 'center' 
        });
    } else {
        // If today is not visible, scroll to approximate position
        const scrollPosition = (today.getDate() - 1) * 30; // Approximate
        chart.scrollLeft = scrollPosition;
    }
    
    showToast('Переход к сегодняшней дате');
}

function resetZoom() {
    const timeline = document.getElementById('ganttTimeline');
    const chart = document.getElementById('ganttChart');
    
    timeline.style.minWidth = '2000px';
    chart.style.fontSize = '12px';
    chart.scrollLeft = 0;
    
    // Reset zoom buttons
    document.querySelectorAll('.zoom-btn').forEach(btn => {
        btn.classList.remove('btn-secondary', 'active');
        btn.classList.add('btn-outline-secondary');
    });
    
    document.querySelector('.zoom-btn:nth-child(3)').classList.remove('btn-outline-secondary');
    document.querySelector('.zoom-btn:nth-child(3)').classList.add('btn-secondary', 'active');
    
    showToast('Масштаб и позиция сброшены');
}

function highlightToday() {
    const today = new Date();
    const currentDay = today.getDate();
    const currentMonth = today.getMonth() + 1; // JavaScript months are 0-indexed
    const currentYear = today.getFullYear();
    
    // This is a simplified version - in a real app, you'd match based on actual dates
    if (currentYear === 2024 && currentMonth >= 3 && currentMonth <= 6) {
        const dayElements = document.querySelectorAll('.gantt-day');
        // Find and highlight today (simplified logic)
        dayElements.forEach((day, index) => {
            if (day.textContent == currentDay && index > 20 && index < 50) { // Rough approximation
                day.classList.add('today');
            }
        });
    }
}

function initializeDragAndDrop() {
    const taskBars = document.querySelectorAll('.gantt-task-bar');
    
    taskBars.forEach(bar => {
        bar.addEventListener('dragstart', function(e) {
            e.dataTransfer.setData('text/plain', '');
            this.style.opacity = '0.5';
        });
        
        bar.addEventListener('dragend', function(e) {
            this.style.opacity = '1';
            showToast(`Задача "${this.textContent.trim()}" перемещена`);
        });
        
        // Make parent timeline a drop zone
        const timeline = bar.closest('.gantt-task-timeline');
        timeline.addEventListener('dragover', function(e) {
            e.preventDefault();
        });
        
        timeline.addEventListener('drop', function(e) {
            e.preventDefault();
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const draggedBar = document.querySelector('[draggable="true"][style*="opacity: 0.5"]');
            
            if (draggedBar) {
                draggedBar.style.left = Math.max(0, x - 50) + 'px';
            }
        });
    });
}

function scrollToCurrentPeriod() {
    // Auto-scroll to current month
    const chart = document.getElementById('ganttChart');
    const today = new Date();
    const currentMonth = today.getMonth() + 1;
    
    if (currentMonth >= 3 && currentMonth <= 6) {
        const scrollPosition = (currentMonth - 3) * 300; // Approximate scroll position
        setTimeout(() => {
            chart.scrollLeft = scrollPosition;
        }, 100);
    }
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('ru-RU');
}



function showToast(message) {
    // Create a simple toast notification
    const toast = document.createElement('div');
    toast.className = 'alert alert-info alert-dismissible fade show position-fixed';
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <i class="bi bi-info-circle me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    // Auto-remove after 3 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 3000);
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey) {
        switch(e.key) {
            case '1':
                e.preventDefault();
                changeZoom('day');
                break;
            case '2':
                e.preventDefault();
                changeZoom('week');
                break;
            case '3':
                e.preventDefault();
                changeZoom('month');
                break;
            case 't':
                e.preventDefault();
                scrollToToday();
                break;
            case 'r':
                e.preventDefault();
                resetZoom();
                break;
        }
    }
});
</script>
@endpush
