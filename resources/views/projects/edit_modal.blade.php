<div x-data="{ openModal: false }" class="inline">
    <button @click="openModal = true" class="btn btn-primary">編集</button>

    <template x-if="openModal">
        <div class="modal" :class="{ 'modal-open': openModal }">
            <div class="modal-box relative">
                <button class="btn btn-sm btn-circle absolute right-2 top-2" @click="openModal = false">✕</button>

                <h3 class="font-bold text-lg">編集</h3>
                <p class="py-4">プロジェクト名を編集してください</p>

                {{-- ここから送信フォームを組み込みます --}}
                <form action="{{ route('projects.update', ['project' => $project->id]) }}" method="POST">
                    @csrf {{-- CSRF保護トークン --}}
                    @method('PUT') {{-- PUTメソッドで更新リクエストを送信 --}}

                    <div class="form-control w-fullmb-4">
                        <label class="label">
                            <span class="label-text">プロジェクト名</span>
                        </label>
                        {{-- 既存のプロジェクト名を初期値として設定 --}}
                        <input type="text" name="project_name" value="{{ $project->project_name}}" class="input input-bordered w-full" required />
                    </div>

                    {{-- その他のフォームフィールドを追加できます --}}

                    <div class="modal-action">
                        {{-- フォームの送信ボタン --}}
                        <button type="submit" class="btn btn-primary">更新</button>
                        {{-- モーダルを閉じるボタン（フォーム送信とは別） --}}
                        <button type="button" class="btn" @click="openModal = false">キャンセル</button>
                    </div>
                </form>
                {{-- 送信フォームの組み込みここまで --}}

            </div>
            <div class="modal-backdrop" @click.self="openModal = false"></div>
        </div>
    </template>
</div>