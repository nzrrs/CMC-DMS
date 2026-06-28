@extends('layouts.app')

@section('title', 'Generated Documents')

@section('content')

<div class="max-w-5xl mx-auto">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Generated Documents</h1>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">Document </th>
                    <th class="px-6 py-3 text-left">Version</th>
                    <th class="px-6 py-3 text-left">Date</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($documents as $document)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-800">
                        {{ $document->documentType->template_name }}
                    </td>
                    <td class="px-6 py-4 text-gray-500">v{{ $document->version }}</td>
                    <td class="px-6 py-4 text-gray-500">
                        {{ $document->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4">
                        @if($document->status === 'SUCCESS')
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                Success
                            </span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                Failed
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 flex gap-2">
                        @if($document->status === 'SUCCESS')
                            <a href="{{ route('generated.download', $document) }}"
                               class="px-3 py-1 rounded-lg text-xs font-medium bg-blue-600 text-white hover:bg-blue-700 transition">
                                Download
                            </a>
                        @endif
                        <a href="{{ route('documents.show', $document->documentType) }}"
                           class="px-3 py-1 rounded-lg text-xs font-medium bg-gray-200 text-gray-700 hover:bg-gray-300 transition">
                            Regenerate
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($documents->isEmpty())
            <p class="text-center text-gray-400 py-12">No documents generated yet.</p>
        @endif
    </div>

</div>

@endsection