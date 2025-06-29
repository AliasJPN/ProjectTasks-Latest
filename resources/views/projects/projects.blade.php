@if(Auth::check())
    @if (isset($projects))
        <h1 class="text-4xl font-bold mb-4">プロジェクト一覧</h1>
        <div class="overflow-x-auto">
        <table class="table w-full">
            <!-- head -->
            <thead>
            <tr>
                <th>プロジェクト名</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($projects as $project)
                <tr class="hover">
                <td>{{ $project->project_name }}</td>
                <td class="flex justify-end gap-2">
                    {{-- 詳細ボタン --}}
                    <a href="{{ route('projects.show', $project->id) }}" class="btn">詳細</a>

                    @include('projects.edit_modal')
                    @include('projects.delete_modal')
                </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        </div>
    @endif
    <form action="{{ route('projects.store')}}" method="POST">
        @csrf
        <div class="form-control w-full mt-3">
            <input type="text" name="project_name" placeholder="プロジェクト名を入力" class="input input-bordered w-full" required />
            @error('project_name')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-control mt-6 text-center">
            <button type="submit" class="btn btn-success">作成</button>
        </div>
    </form>
@else
    <div class="prose mx-auto text-center">
        <h1 class="text-5xl">ようこそ！</h1>
    </div>

    <div class="flex justify-center">
        <div class="w-1/2">
            <p class="text-center mt-8 mb-8">ログインまたはユーザー登録をしてください</p>
            <div class="flex flex-col space-y-4">
                <a href="{{ route('register') }}" class="btn btn-primary btn-block normal-case">ユーザー登録</a>
                <a href="{{ route('login') }}" class="btn btn-primary btn-block normal-case">ログイン</a>
            </div>
        </div>
    </div>
@endif
