@if (Auth::check())
    {{-- ログアウトへのリンク --}}
    <li><a class="link link-hover" href="#" onclick="event.preventDefault();this.closest('form').submit();">Logout</a></li>
@else
    {{-- ユーザー登録ページへのリンク --}}
    <li><a class="link link-hover" href="{{ route('register') }}">Signup</a></li>
    <div class="divider lg:hidden"></div>
    {{-- ログインページへのリンク --}}
    <li><a class="link link-hover" href="{{ route('login') }}">Login</a></li>
@endif