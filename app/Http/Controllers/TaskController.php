<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 認証チェック
        if (Auth::check()) {
            // タスクの作成
            $task = new Task();
            $task->fill([
                'task_name' => $request->task_name,
                'project_id' => $request->project_id,
                'status' => '',
                'priority' => '',
            ]);

            // タスクを保存
            $task->save();
        }

        // プロジェクトを取得
        $project = Project::with(['tasks', 'users'])->findOrFail($request->project_id);

        // リダイレクトしてプロジェクトの詳細を表示
        return redirect()->route('projects.show', $project->id)->with([
            'project' => $project,
            'tasks' => $project->tasks,
            'users' => $project->users,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        // 1. バリデーション
        // 送信されたデータが有効であるかを確認
        $validatedData = $request->validate([
            'task_name' => 'nullable|string|max:255',
            'project_id' => 'required|exists:projects,id', // 必須で、projectsテーブルに存在するIDであること
            'status' => 'nullable|in:未着手,進行中,完了', // nullを許容し、指定された値のみを許可
            'priority' => 'nullable|in:低,中,高',      // nullを許容し、指定された値のみを許可
            'due_date_start' => 'nullable|date',
            'due_date_end' => 'nullable|date|after_or_equal:due_date_start', // 終了日が開始日以降であること
            'assigned_project_member_id' => 'nullable|exists:users,id', // nullを許容し、usersテーブルに存在するIDか確認
        ]);

        // 2. タスクの更新
        // バリデーション済みデータでタスクを更新します。
        // $fillableにこれらのカラムが設定されていることを確認してください。
        $task->update($validatedData);
        // 3. リダイレクト
        // 更新後、タスクが属するプロジェクトの詳細ページへリダイレクトします。
        // フラッシュメッセージで成功をユーザーに伝えます。
        return redirect()->route('projects.show', [
            'project' => $validatedData['project_id'],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $user = Auth::user();

        // タスクを取得
        $task = Task::with('project')->findOrFail($id);
        // ユーザーがプロジェクトに属しているか確認
        if ($user->projects()->where('project_id', $task->project->id)->exists()) {
            $task->delete();
        }

        return redirect()->route('projects.show', $task->project->id);
    }

}