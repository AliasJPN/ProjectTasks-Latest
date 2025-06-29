<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Project $project)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();

        // 認証ユーザーがプロジェクトのメンバーではない場合はルート (/) へリダイレクト
        $projectMember = $user->projects()->where('project_id', $project->id)->first();
        if (!$projectMember) {
            return redirect('/');
        }

        // プロジェクトに紐づくユーザー（メンバー）を取得
        $users = $project->users()->get();

        return view('members.index', [
            'members' => $users,
            'project' => $projectMember,
        ]);
    }

    public function store(Request $request, Project $project)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();

        // 認証ユーザーがプロジェクトのメンバーではない場合はルート (/) へリダイレクト
        $projectMember = $user->projects()->where('project_id', $project->id)->first();
        if (!$projectMember) {
            return redirect('/');
        }

        // 認証ユーザーがプロジェクトのオーナーではない場合はルート (/) へリダイレクト
        if ($projectMember->pivot->role !== 'Owner') {
            return redirect('/');
        }

        // 追加するユーザーを検索
        $userToAdd = User::where('name', $request->member)->first();

        // ユーザーが見つからない場合はメンバー管理画面に戻ってエラーメッセージを表示
        if (!$userToAdd) {
            return redirect()->route('users.index', ['project' => $project->id])->withErrors(['member' => '指定された名前のユーザーは見つかりませんでした。']);
        }

        // すでにプロジェクトメンバーか確認
        if ($project->users()->where('user_id', $userToAdd->id)->exists()) {
            // すでにメンバーの場合はメンバー管理画面に戻って警告メッセージを表示
            return redirect()->route('users.index', ['project' => $project->id])->withErrors(['member' => '指定されたユーザーは既にこのプロジェクトのメンバーです。']);
        }

        $project->users()->attach($userToAdd->id, ['role' => 'General']);

        return redirect()->route('users.index', [
            'project' => $project->id,
        ]);
    }

    public function destroy(Project $project, User $user)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $actingUser = Auth::user();

        // ログイン中のユーザーがプロジェクトのメンバーではない場合はルート (/) へリダイレクト
        $actingProjectMember = $actingUser->projects()->where('project_id', $project->id)->first();
        if (!$actingProjectMember) {
            return redirect('/');
        }

        // ログイン中のユーザーがプロジェクトのオーナーではない場合はルート (/) へリダイレクト
        if ($actingProjectMember->pivot->role !== 'Owner') {
            return redirect('/');
        }

        // 削除対象のユーザーがプロジェクトに属しているか確認
        if (!$project->users()->where('user_id', $user->id)->exists()) {
            // タスクがプロジェクトに属していない場合も、メンバー管理画面に戻るのが適切
            return redirect()->route('users.index', ['project' => $project->id])->withErrors(['general' => '削除しようとしたユーザーはこのプロジェクトのメンバーではありません。']);
        }

        // オーナーが自分自身を削除しようとしている場合は、他のオーナーがいるか確認
        if ($actingUser->id === $user->id && $project->users()->where('role', 'Owner')->count() === 1) {
             // 最後のオーナーが自分自身を削除しようとしている場合は、メンバー管理画面に戻ってエラーメッセージを表示
             return redirect()->route('users.index', ['project' => $project->id])->withErrors(['general' => 'プロジェクトには少なくとも1人のオーナーが必要です。自分自身を削除する前に、別のオーナーを設定してください。']);
        }

        $project->users()->detach($user->id);

        return redirect()->route('users.index', ['project' => $project->id]);
    }
}
