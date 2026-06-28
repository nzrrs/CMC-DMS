<aside class="w-60 min-h-screen bg-gray-900 text-white flex flex-col">
    <div class="px-6 py-5 text-lg font-bold tracking-wide border-b border-gray-700">
        CMC-DMS
    </div>
    <nav class="flex-1 py-4">
        <a href="{{ route('documents.index') }}"
           class="flex items-center gap-3 px-6 py-3 text-sm hover:bg-gray-800 transition {{ request()->routeIs('documents.index') ? 'bg-gray-800 text-white' : 'text-gray-400' }}">
            Documents
        </a>
        <a href="{{ route('documents.history') }}"
           class="flex items-center gap-3 px-6 py-3 text-sm hover:bg-gray-800 transition {{ request()->routeIs('documents.history') ? 'bg-gray-800 text-white' : 'text-gray-400' }}">
            History
        </a>
    </nav>
</aside>