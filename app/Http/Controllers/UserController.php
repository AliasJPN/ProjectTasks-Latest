<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Project $project) // $projectはルートからバインドされたProjectモデル
    {
        $user = Auth::user();

        // 認証ユーザーのプロジェクトリレーションシップから、指定されたプロジェクトを再取得
        // これにより、$projectインスタンスに$userとの関係におけるpivot情報が含まれる
        $projectWithPivot = $user->projects()->where('project_id', $project->id)->firstOrFail();

        // プロジェクトに紐づくユーザー（メンバー）を取得
        $users = $projectWithPivot->users()->get();

        return view('members.index',[
            'members' => $users,
            'project' => $projectWithPivot, // ピボット情報が含まれた$projectをビューに渡す
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project)
    {
        $userToAdd = User::where('name', $request->member)->firstOrFail();

        $project->users()->attach($userToAdd->id, ['role' => 'General']);

        return redirect()->route('users.index',[
            'project' => $project->id,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, User $user)
    {
        // ログイン中のユーザーがプロジェクトのオーナーか、または削除権限があるかを確認するロジックをここに追加することを検討

        $project->users()->detach($user->id);

        return redirect()->route('users.index', ['project' => $project->id]);
    }
}
