@extends('layouts.app')
@section('title', 'Documents')
@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-end gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Available Documents</h1>
        <p class="text-sm text-gray-500 mt-1">Select a document type to generate</p>
    </div>

    <div class="flex gap-2 sm:ml-auto">
        <input
            type="text"
            id="search"
            placeholder="Search..."
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
        <select id="groupFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All groups</option>
            @foreach ($groups as $group)
                <option value="{{ $group->id }}">{{ $group->name }}</option>
            @endforeach
        </select>
    </div>
</div>

{{-- Group cards --}}
@if($groups->isNotEmpty())
<div class="mb-8">
    <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Groups</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" id="groupGrid">
        @foreach ($groups as $group)
            <a href="{{ route('groups.show', $group) }}"
               data-group-id="{{ $group->id }}"
               data-name="{{ strtolower($group->name) }}"
               class="group-card block bg-white border border-gray-200 rounded-xl p-5 hover:border-blue-400 hover:shadow-sm transition">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold text-gray-800">{{ $group->name }}</span>
                    <span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full">
                        {{ $group->documentTypes->count() }} docs
                    </span>
                </div>
                <p class="text-xs text-gray-400">Generate all as ZIP</p>
            </a>
        @endforeach
    </div>
</div>
@endif

{{-- Individual documents --}}
<div>
    <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Individual Documents</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" id="docGrid">
        @foreach ($documents as $document)
            <div class="doc-card"
                 data-name="{{ strtolower($document->template_name) }}"
                 data-group-ids="{{ $document->groups->pluck('id')->join(',') }}">
                @include('components.document-card', ['document' => $document])
            </div>
        @endforeach
    </div>
</div>

<script>
    const search = document.getElementById('search');
    const groupFilter = document.getElementById('groupFilter');

    function filter() {
        const q = search.value.toLowerCase();
        const gid = groupFilter.value;

        document.querySelectorAll('.doc-card').forEach(el => {
            const nameMatch = el.dataset.name.includes(q);
            const groupMatch = !gid || el.dataset.groupIds.split(',').includes(gid);
            el.style.display = nameMatch && groupMatch ? '' : 'none';
        });

        document.querySelectorAll('.group-card').forEach(el => {
            const nameMatch = el.dataset.name.includes(q);
            const groupMatch = !gid || el.dataset.groupId === gid;
            el.style.display = nameMatch && groupMatch ? '' : 'none';
        });
    }

    search.addEventListener('input', filter);
    groupFilter.addEventListener('change', filter);
</script>

@endsection