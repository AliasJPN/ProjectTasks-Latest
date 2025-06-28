<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $project = $user->projects()->find($request->project_id);
        $users = $project->users()->get();

        return view('members.index',[
            'members' => $users,
            'project' => $project,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ユーザ名を取得し、ユーザを検索
        $user = User::where('name', $request->member)->firstOrFail();

        $project = Project::find($request->project_id);

        // ユーザをプロジェクトに追加
        $project->users()->attach($user->id, ['role' => 'General']);

        return redirect()->route('users.index',[
            'project_id' => $project->id,
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $user = Auth::user();

        // プロジェクトIDを取得
        $projectId = $request->input('projectId');

        // プロジェクトを取得
        $project = $user->projects()->findOrFail($projectId);

        // ユーザーをプロジェクトから削除
        $project->users()->detach($id); // $idは削除するユーザーのID

        // 成功メッセージをセッションに保存してリダイレクト
        return redirect()->route('users.index', ['project_id' => $projectId]);
    }
}