<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $user = null;
        $projects = collect();

        if (Auth::check()) {
            $user = Auth::user();
            $projects = $user->projects()->get();
        }

        return view('projects.index', [
            'projects' => $projects,
            'user' => $user,
        ]);
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $request->validate([
            'project_name' => 'required|string|max:255'
        ]);

        $project = new Project;
        $project->project_name = $request->project_name;

        if ($project->save()) {
            $user = Auth::user();
            $user->projects()->attach($project->id, ['role' => 'Owner']);
        }

        return redirect('/');
    }

    public function show(string $id)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();
        $project = $user->projects()->find($id);

        if (!$project) {
            return redirect('/');
        }

        $tasks = $project->tasks()->get();
        $users = $project->users()->get();

        return view('projects.show', [
            'project' => $project,
            'tasks' => $tasks,
            'users' => $users,
        ]);
    }

    public function update(Request $request, string $id)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();

        $project = $user->projects()->find($id);

        if (!$project) {
            return redirect('/');
        }

        $project->project_name = $request->project_name;
        $project->save();

        return redirect('/');
    }

    public function destroy(string $id)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();

        $project = $user->projects()->find($id);

        if (!$project) {
            return redirect('/');
        }

        $project->delete();

        return redirect('/');
    }
}
