<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Support\Facades\DB; // DBファサードをインポート

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();

        if ($projects->isEmpty()) {
            $this->command->warn('No Project records found. Skipping Task seeding.');
            return;
        }

        // 各プロジェクトに対して複数のタスクを作成
        $projects->each(function ($project) {
            // このプロジェクトに関連する project_members の ID を取得
            $projectMemberIds = DB::table('project_members')
                                ->where('project_id', $project->id)
                                ->pluck('id')
                                ->toArray();

            // 生成するタスクの数
            $numberOfTasks = rand(2, 7);

            for ($i = 0; $i < $numberOfTasks; $i++) {
                $assignedId = null;
                // 利用可能な project_member_id があれば、ランダムに割り当てる
                // ただし、nullable なので常に割り当てる必要はない
                if (!empty($projectMemberIds) && rand(0, 1)) { // 50%の確率で割り当てるかnullにする
                    $assignedId = $projectMemberIds[array_rand($projectMemberIds)];
                }

                Task::factory()->create([
                    'project_id' => $project->id,
                    'assigned_project_member_id' => $assignedId, // ランダムなIDかnull
                ]);
            }
        });
    }
}