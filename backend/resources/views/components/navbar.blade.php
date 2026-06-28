<nav class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between">
    <span class="text-sm text-gray-500">
        @yield('title', ucfirst(request()->segment(1)))
    </span>
    <div class="flex items-center gap-4">
        <span class="text-sm font-medium text-gray-700">
            {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
        </span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="text-sm text-red-500 hover:text-red-700 transition">
                Logout
            </button>
        </form>
    </div>
</nav>
