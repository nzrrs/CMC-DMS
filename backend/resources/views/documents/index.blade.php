@extends('layouts.app')
@section('title', 'Documents')
@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Available Documents</h1>
    <p class="text-sm text-gray-500 mt-1">Select a document type to generate</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach ($documents as $document)
        @include('components.document-card', ['document' => $document])
    @endforeach
</div>

@endsection