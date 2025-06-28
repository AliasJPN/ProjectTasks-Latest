<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth; // Authファサードをインポート

use Illuminate\Http\Request;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = null;     // デフォルトでユーザーはnull
        $projects = collect(); // デフォルトでプロジェクトは空のコレクション

        // ユーザーが認証されているかチェック
        if (Auth::check()) {
            $user = Auth::user(); // 認証済みユーザーを取得

            // ユーザーに紐づくプロジェクトを直接取得
            // Userモデルに 'projects' というリレーション（例: belongsToMany）が定義されていることを前提
            $projects = $user->projects()->get();
        }

        // ログイン状態に関わらずビューを返す
        return view('projects.index', [
            'projects' => $projects,
            'user' => $user,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'project_name' => 'required|string|max:255'
        ]);

        // ユーザーIDの確認
        if (Auth::check()) {
            $project = new Project;
            $project->project_name = $request->project_name;

            // プロジェクトの保存
            if ($project->save()) {
                // プロジェクトの関連付け
                $user = Auth::user();
                $user->projects()->attach($project->id, ['role' => 'Owner']);
            }
        }

        return redirect('/');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $project = $user->projects()->find($id);
            $tasks = $project->tasks()->get();
            $users = $project->users()->get();
        }

        return view('projects.show', [
            'project' => $project,
            'tasks' => $tasks,
            'users' => $users,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();

        $projectIds = $user->projects()->pluck('project_id')->toArray();

        if(in_array($id,$projectIds)) {
            $project = Project::findOrFail($id);
            $project->project_name = $request->project_name;
            $project->save();
        }

        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();

        $projectIds = $user->projects()->pluck('project_id')->toArray();

        if(in_array($id,$projectIds)) {
            $project = Project::findOrFail($id);
            $project->delete();
        }

        return redirect('/');
    }
}