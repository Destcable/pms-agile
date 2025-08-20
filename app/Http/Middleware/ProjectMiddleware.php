<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ProjectMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip middleware for project management routes, auth routes, and project settings
        if ($request->routeIs('projects.*') || $request->routeIs('login') || $request->routeIs('register') || $request->routeIs('logout') || $request->routeIs('project.settings')) {
            return $next($request);
        }

        // Check if user is authenticated
        if (!Auth::check()) {
            return $next($request);
        }

        // Check if user has any projects
        $user = Auth::user();
        if ($user->projects()->count() === 0) {
            // Allow access but show message about creating projects
            return $next($request);
        }

        // Check if current project is selected
        $currentProjectId = session('current_project_id');
        if ($currentProjectId) {
            // Verify the selected project belongs to the user
            $project = $user->projects()->find($currentProjectId);
            if ($project) {
                // Share current project with all views and ensure session has name
                view()->share('currentProject', $project);
                if (!session('current_project_name')) {
                    session(['current_project_name' => $project->name]);
                }
            } else {
                session()->forget(['current_project_id', 'current_project_name']);
            }
        }

        return $next($request);
    }
}
