<div class="container mx-auto p-4">
    @if (isset($tasks))
        <div class="flex items-center justify-between mt-6 mb-4">
            <h1 class="text-4xl font-bold">タスク一覧</h1>
            @if ($project->pivot->role === 'Owner')
                <a href="{{ route('users.index', ['project' => $project->id]) }}" class="btn btn-info btn-sm ">
                    メンバー管理
                </a>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>タスク名</th>
                        <th>ステータス</th>
                        <th>優先順位</th>
                        <th>担当者</th>
                        <th>期限開始</th>
                        <th>期限終了</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $task)
                        <tr class="hover">
                            <td>{{ $task->task_name }}</td>
                            <td>{{ $task->status }}</td>
                            <td>{{ $task->priority }}</td>
                            <td>
                                @foreach ( $users as $user )
                                    @if ($task->assigned_project_member_id == $user->id)
                                        {{ $user->name }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                {{-- 統一されたメソッドで表示 --}}
                                {{ $task->getFormattedDate('due_date_start') }}
                            </td>
                            <td>
                                {{-- 統一されたメソッドで表示 --}}
                                {{ $task->getFormattedDate('due_date_end') }}
                            </td>
                            <td class="flex justify-end gap-2">
                                @include('tasks.edit_modal', ['task' => $task])
                                @include('tasks.delete_modal', ['task' => $task])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <form action="{{ route('tasks.store', ['project' => $project->id]) }}" method="POST">
        @csrf
        <input type="hidden" name="project_id" value="{{ $project->id }}">
        <div class="form-control w-full mt-3">
            <input type="text" name="task_name" placeholder="タスク名を入力" class="input input-bordered w-full" required />
            @error('task_name')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-control mt-6 text-center">
            <button type="submit" class="btn btn-success">作成</button>
        </div>
    </form>
</div>
