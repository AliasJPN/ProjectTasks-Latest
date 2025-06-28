<div x-data="{ openModal: false }" class="inline">
    <button @click="openModal = true" class="btn btn-primary">編集</button>

    <template x-if="openModal">
        <div class="modal" :class="{ 'modal-open': openModal }">
            <div class="modal-box relative">
                <button class="btn btn-sm btn-circle absolute right-2 top-2" @click="openModal = false">✕</button>

                <h3 class="font-bold text-lg">タスク編集</h3>
                <p class="py-4">タスクの詳細を編集してください</p>

                <form action="{{ route('tasks.update', ['task' => $task->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="project_id" value="{{ $task->project_id }}">

                    <div class="form-control w-full mb-4">
                        <label class="label">
                            <span class="label-text">タスク名</span>
                        </label>
                        <input type="text" name="task_name" value="{{ $task->task_name }}" class="input input-bordered w-full" />
                    </div>

                    <div class="form-control w-full mb-4">
                        <label class="label">
                            <span class="label-text">ステータス</span>
                        </label>
                        <select name="status" class="select select-bordered w-full">
                            <option value="" @selected($task->status == '')>選択してください</option>
                            <option value="未着手" @selected($task->status == '未着手')>未着手</option>
                            <option value="進行中" @selected($task->status == '進行中')>進行中</option>
                            <option value="完了" @selected($task->status == '完了')>完了</option>
                        </select>
                    </div>

                    <div class="form-control w-full mb-4">
                        <label class="label">
                            <span class="label-text">優先順位</span>
                        </label>
                        <select name="priority" class="select select-bordered w-full">
                            <option value="" @selected($task->priority == '')>選択してください</option>
                            <option value="低" @selected($task->priority == '低')>低</option>
                            <option value="中" @selected($task->priority == '中')>中</option>
                            <option value="高" @selected($task->priority == '高')>高</option>
                        </select>
                    </div>

                    <div class="form-control w-full mb-4">
                        <label class="label">
                            <span class="label-text">担当者</span>
                        </label>
                        <select name="assigned_project_member_id" class="select select-bordered w-full">
                            <option value="" @selected($task->assigned_project_member_id == null)>未割り当て</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected($task->assigned_project_member_id == $user->id)>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-control w-full mb-4">
                        <label class="label">
                            <span class="label-text">期限開始日</span>
                        </label>
                        <input type="date" name="due_date_start"
                            value="{{ $task->due_date_start ? \Carbon\Carbon::parse($task->due_date_start)->format('Y-m-d') : '' }}"
                            class="input input-bordered w-full" />
                    </div>
                    <div class="form-control w-full mb-4">
                        <label class="label">
                            <span class="label-text">期限終了日</span>
                        </label>
                        <input type="date" name="due_date_end"
                            value="{{ $task->due_date_end ? \Carbon\Carbon::parse($task->due_date_end)->format('Y-m-d') : '' }}"
                            class="input input-bordered w-full" />
                    </div>

                    <div class="modal-action">
                        <button type="submit" class="btn btn-primary">更新</button>
                        <button type="button" class="btn" @click="openModal = false">キャンセル</button>
                    </div>
                </form>
            </div>
            <div class="modal-backdrop" @click.self="openModal = false"></div>
        </div>
    </template>
</div>