@if ($project->pivot->role === 'Owner')
    <div x-data="{ openModal: false }" class="inline">
        <button @click="openModal = true" class="btn btn-error">削除</button>

        <template x-if="openModal">
            <div class="modal" :class="{ 'modal-open': openModal }">
                <div class="modal-box relative">
                    <button class="btn btn-sm btn-circle absolute right-2 top-2" @click="openModal = false">✕</button>

                    <h3 class="font-bold text-lg text-red-600">削除</h3> {{-- 警告色にするため text-red-600 を追加 --}}

                    {{-- 削除確認メッセージ --}}
                    <p class="py-4">このプロジェクト「{{ $project->project_name }}」を本当に削除しますか？<br>この操作は元に戻せません。</p>

                    {{-- 削除フォームを組み込みます --}}
                    {{-- プロジェクトの削除は通常 DELETE メソッドを使います --}}
                    <form action="{{ route('projects.destroy', ['project' => $project->id]) }}" method="POST">
                        @csrf {{-- CSRF保護トークン --}}
                        @method('DELETE') {{-- DELETEメソッドで削除リクエストを送信 --}}

                        <div class="modal-action justify-center sm:justify-end"> {{-- ボタンを中央寄せまたは右寄せにする調整 --}}
                            {{-- 削除実行ボタン --}}
                            <button type="submit" class="btn btn-error">削除する</button>
                            {{-- モーダルを閉じるボタン（フォーム送信とは別） --}}
                            <button type="button" class="btn btn-ghost" @click="openModal = false">キャンセル</button>
                        </div>
                    </form>
                    {{-- 削除フォームの組み込みここまで --}}

                </div>
                <div class="modal-backdrop" @click.self="openModal = false"></div>
            </div>
        </template>
    </div>
@endif
