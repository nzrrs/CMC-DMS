<div class="bg-white rounded-xl border border-gray-200 p-5 flex flex-col gap-3 hover:shadow-md transition">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-lg">
            📄
        </div>
        <h4 class="font-semibold text-gray-800">{{ $document->template_name }}</h4>
    </div>

    @if($document->is_active)
        <a href="{{ route('documents.show', $document) }}"
           class="mt-auto inline-block text-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition">
            Generate
        </a>
    @else
        <button disabled
            class="mt-auto inline-block text-center bg-gray-100 text-gray-400 text-sm font-medium py-2 px-4 rounded-lg cursor-not-allowed">
            Unavailable
        </button>
    @endif
</div>