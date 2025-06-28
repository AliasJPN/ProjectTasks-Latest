<div x-data="{ openModal: false }" class="inline">
    <button @click="openModal = true" class="btn btn-error">削除</button>

    <template x-if="openModal">
        <div class="modal" :class="{ 'modal-open': openModal }">
            <div class="modal-box relative">
                <button class="btn btn-sm btn-circle absolute right-2 top-2" @click="openModal = false">✕</button>

                <h3 class="font-bold text-lg text-red-600">削除</h3>

                <p class="py-4">このタスク「{{ $task->task_name }}」を本当に削除しますか？<br>この操作は元に戻せません。</p>

            <form action="{{ route('tasks.destroy', ['task' => $task->id]) }}" method="POST">
                @csrf
                @method('DELETE')

                <div class="modal-action justify-center sm:justify-end">
                    <button type="submit" class="btn btn-error">削除する</button>
                    <button type="button" class="btn btn-ghost" @click="openModal = false">キャンセル</button>
                </div>
            </form>
            </div>
            <div class="modal-backdrop" @click.self="openModal = false"></div>
        </div>
    </template>
</div>