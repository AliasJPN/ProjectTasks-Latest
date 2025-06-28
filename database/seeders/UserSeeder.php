<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\DB; // DBファサードを使用

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // アクセスしやすいテストユーザーを1人作成
        User::factory()->testUser()->create();

        // 追加のダミーユーザーを作成 (合計10人になるように)
        User::factory(9)->create();

        // --- 中間テーブル (project_members) のデータ挿入 ---
        // Project のデータが既に存在することを前提とします。
        $users = User::all();
        $projects = Project::all();

        // 各プロジェクトに対して、ランダムな数のユーザーをメンバーとして割り当てる
        $projects->each(function ($project) use ($users) {
            // 各プロジェクトに最低1人、最大でユーザー数の半分（または3人まで）のメンバーを割り当てる
            $assignedUsersCount = rand(1, min($users->count() / 2, 3));
            $assignedUsers = $users->random($assignedUsersCount);

            foreach ($assignedUsers as $user) {
                // project_membersテーブルに直接データを挿入
                // idとtimestampsはLaravelが自動で管理します。
                // unique制約を考慮し、既に存在する場合は更新、存在しない場合は新規追加
                DB::table('project_members')->updateOrInsert(
                    ['user_id' => $user->id, 'project_id' => $project->id],
                    [
                        'role' => ['Owner', 'General'][array_rand(['Owner', 'General'])],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        });
    }
}