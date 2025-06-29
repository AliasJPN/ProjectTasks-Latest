@auth
    <div class="container mx-auto p-4">
        @if (isset($members))
            <h1 class="text-4xl font-bold mb-4">メンバー一覧</h1>
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>メンバー名</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($members as $member)
                            <tr class="hover">
                                <td>{{ $member->name }}</td>
                                <td class="flex justify-end gap-2">
                                    @if(Auth::user()->id != $member->id )
                                        @include('members.delete_modal', ['project' => $project, 'member' => $member])
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        <form action="{{ route('users.store', ['project' => $project->id]) }}" method="POST">
            @csrf
            <div class="form-control w-full mt-3">
                <input type="text" name="member" placeholder="メンバー名を入力" class="input input-bordered w-full" required />
            </div>

            <div class="form-control mt-6 text-center">
                <button type="submit" class="btn btn-success">作成</button>
            </div>
        </form>
    </div>
@endauth
