<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GanttController extends Controller
{
    /**
     * Display the Gantt chart for the current project.
     */
    public function index(Request $request)
    {
        $currentProjectId = session('current_project_id');
        
        if (!$currentProjectId) {
            return redirect()->route('dashboard')->with('error', 'Проект не выбран.');
        }
        
        $project = Auth::user()->projects()->findOrFail($currentProjectId);
        
        // Get date range from request or use defaults
        $startDate = $request->get('start_date', '2024-03-01');
        $endDate = $request->get('end_date', '2024-06-30');
        
        // Generate timeline data
        $timelineData = $this->generateTimelineData($startDate, $endDate);
        $daysCount = count($timelineData['days']);
        $timelineWidthPx = $daysCount * 30; // 30px per day
        
        // Sample tasks data for demonstration
        $tasks = [
            [
                'id' => 1,
                'name' => 'Планирование проекта',
                'start' => '2024-03-01',
                'end' => '2024-03-15',
                'progress' => 100,
                'dependencies' => [],
                'color' => '#3498db'
            ],
            [
                'id' => 2,
                'name' => 'Анализ требований',
                'start' => '2024-03-10',
                'end' => '2024-03-25',
                'progress' => 80,
                'dependencies' => [1],
                'color' => '#e74c3c'
            ],
            [
                'id' => 3,
                'name' => 'Проектирование системы',
                'start' => '2024-03-20',
                'end' => '2024-04-10',
                'progress' => 60,
                'dependencies' => [2],
                'color' => '#f39c12'
            ],
            [
                'id' => 4,
                'name' => 'Разработка backend',
                'start' => '2024-04-01',
                'end' => '2024-05-15',
                'progress' => 40,
                'dependencies' => [3],
                'color' => '#27ae60'
            ],
            [
                'id' => 5,
                'name' => 'Разработка frontend',
                'start' => '2024-04-15',
                'end' => '2024-05-20',
                'progress' => 30,
                'dependencies' => [3],
                'color' => '#9b59b6'
            ],
            [
                'id' => 6,
                'name' => 'Тестирование',
                'start' => '2024-05-10',
                'end' => '2024-06-05',
                'progress' => 10,
                'dependencies' => [4, 5],
                'color' => '#34495e'
            ],
            [
                'id' => 7,
                'name' => 'Деплой и запуск',
                'start' => '2024-06-01',
                'end' => '2024-06-15',
                'progress' => 0,
                'dependencies' => [6],
                'color' => '#e67e22'
            ]
        ];
        
        // Calculate task positions based on timeline
        $tasks = $this->calculateTaskPositions($tasks, $startDate, $endDate);
        
        return view('gantt.index', compact('project', 'tasks', 'timelineData', 'startDate', 'endDate', 'daysCount', 'timelineWidthPx'));
    }
    
    /**
     * Generate timeline data for the given date range
     */
    private function generateTimelineData($startDate, $endDate)
    {
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $months = [];
        $days = [];
        
        $current = clone $start;
        $current->modify('first day of this month');
        
        while ($current <= $end) {
            $monthName = $current->format('F Y');
            $monthNameRu = $this->translateMonth($monthName);
            $daysInMonth = $current->format('t');
            
            $months[] = [
                'name' => $monthNameRu,
                'days' => $daysInMonth,
                'width' => $daysInMonth * 30 // 30px per day
            ];
            
            // Generate days for this month
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = clone $current;
                $date->setDate($current->format('Y'), $current->format('n'), $day);
                
                if ($date >= $start && $date <= $end) {
                    $isWeekend = in_array($date->format('w'), [0, 6]); // Sunday = 0, Saturday = 6
                    $isToday = $date->format('Y-m-d') === date('Y-m-d');
                    
                    $days[] = [
                        'day' => $day,
                        'date' => $date->format('Y-m-d'),
                        'isWeekend' => $isWeekend,
                        'isToday' => $isToday
                    ];
                }
            }
            
            $current->modify('+1 month');
        }
        
        return ['months' => $months, 'days' => $days];
    }
    
    /**
     * Translate month name to Russian
     */
    private function translateMonth($monthName)
    {
        $translations = [
            'January' => 'Январь',
            'February' => 'Февраль',
            'March' => 'Март',
            'April' => 'Апрель',
            'May' => 'Май',
            'June' => 'Июнь',
            'July' => 'Июль',
            'August' => 'Август',
            'September' => 'Сентябрь',
            'October' => 'Октябрь',
            'November' => 'Ноябрь',
            'December' => 'Декабрь'
        ];
        
        foreach ($translations as $en => $ru) {
            $monthName = str_replace($en, $ru, $monthName);
        }
        
        return $monthName;
    }
    
    /**
     * Calculate task positions based on timeline
     */
    private function calculateTaskPositions($tasks, $startDate, $endDate)
    {
        $timelineStart = new \DateTime($startDate);
        $timelineEnd = new \DateTime($endDate);
        $totalDays = $timelineStart->diff($timelineEnd)->days + 1;
        
        foreach ($tasks as &$task) {
            $taskStart = new \DateTime($task['start']);
            $taskEnd = new \DateTime($task['end']);
            
            // Calculate days from timeline start
            $daysFromStart = max(0, $timelineStart->diff($taskStart)->days);
            if ($taskStart < $timelineStart) {
                $daysFromStart = 0;
            }
            
            // Calculate task duration in days
            $taskDuration = $taskStart->diff($taskEnd)->days + 1;
            
            // Convert to pixels (30px per day)
            $task['left'] = $daysFromStart * 30;
            $task['width'] = $taskDuration * 30;
        }
        
        return $tasks;
    }
}