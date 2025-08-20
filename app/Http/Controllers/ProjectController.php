<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the project settings page.
     */
    public function settings()
    {
        $currentProjectId = session('current_project_id');
        
        if (!$currentProjectId) {
            return redirect()->route('dashboard')->with('error', 'Проект не выбран.');
        }
        
        $project = Auth::user()->projects()->findOrFail($currentProjectId);
        
        return view('projects.settings', compact('project'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Auth::user()->projects()->orderBy('name')->get();
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $project = Auth::user()->projects()->create([
            ...$validated,
            'status' => 'active',
        ]);

        return redirect()->route('projects.index')->with('status', 'Проект создан успешно!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $this->authorize('view', $project);
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $this->authorize('update', $project);
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,paused,completed,cancelled',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')->with('status', 'Проект обновлен успешно!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        
        $project->delete();
        
        // Clear current project from session if it was the deleted one
        if (session('current_project_id') == $project->id) {
            session()->forget(['current_project_id', 'current_project_name']);
        }
        
        return redirect()->route('dashboard')->with('status', 'Проект "' . $project->name . '" успешно удален!');
    }

    /**
     * Get projects for the current user (for sidebar)
     */
    public function getUserProjects()
    {
        $projects = Auth::user()->projects()->orderBy('name')->get(['id', 'name']);
        return response()->json($projects);
    }

    /**
     * Set the current project in session
     */
    public function setCurrentProject(Request $request)
    {
        $projectId = $request->input('project_id');
        
        if ($projectId) {
            $project = Auth::user()->projects()->findOrFail($projectId);
            session(['current_project_id' => $project->id]);
            session(['current_project_name' => $project->name]);
        } else {
            session()->forget(['current_project_id', 'current_project_name']);
        }

        return response()->json(['success' => true]);
    }
}
