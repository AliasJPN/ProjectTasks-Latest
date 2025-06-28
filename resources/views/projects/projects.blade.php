@if(Auth::check())
  <div class="container mx-auto p-4">
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
  </div>
@else
<div class="flex items-center justify-center min-h-screen bg-gradient-to-r bg-gray-100">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">ようこそ！</h2>
        <p class="text-gray-600 text-center mb-8">ログインまたはユーザー登録をしてください。</p>
        <div class="flex flex-col space-y-4">
            <a href="{{ route('login') }}" class="bg-blue-500 text-white py-2 rounded-lg text-center hover:bg-blue-600 transition duration-200">ログイン</a>
            <a href="{{ route('register') }}" class="bg-purple-500 text-white py-2 rounded-lg text-center hover:bg-purple-600 transition duration-200">ユーザー登録</a>
        </div>
    </div>
</div>
@endif