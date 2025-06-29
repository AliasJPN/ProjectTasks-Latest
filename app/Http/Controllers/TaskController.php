<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function store(Request $request, Project $project)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();

        if (!$user->projects()->find($project->id)) {
            return redirect('/');
        }

        $request->validate([
            'task_name' => 'required|string|max:255',
        ]);

        $task = new Task();
        $task->fill([
            'task_name' => $request->task_name,
            'project_id' => $project->id,
            'status' => '',
            'priority' => '',
        ]);

        $task->save();

        return redirect()->route('projects.show', $project->id);
    }

    public function update(Request $request, Project $project, Task $task)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();

        if (!$user->projects()->find($project->id)) {
            return redirect('/');
        }

        if ($task->project_id !== $project->id) {
            return redirect('/');
        }

        $validatedData = $request->validate([
            'task_name' => 'nullable|string|max:255',
            'project_id' => 'exists:projects,id',
            'status' => 'nullable|in:未着手,進行中,完了',
            'priority' => 'nullable|in:低,中,高',
            'due_date_start' => 'nullable|date',
            'due_date_end' => 'nullable|date|after_or_equal:due_date_start',
            'assigned_project_member_id' => 'nullable|exists:users,id',
        ]);

        $task->update($validatedData);

        return redirect()->route('projects.show', [
            'project' => $project->id,
        ]);
    }

    public function destroy(Project $project, Task $task)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();

        if (!$user->projects()->find($project->id)) {
            return redirect('/');
        }

        if ($task->project_id !== $project->id) {
            return redirect('/');
        }

        $task->delete();

        return redirect()->route('projects.show', $project->id);
    }
}
