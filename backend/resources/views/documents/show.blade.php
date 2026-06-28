@extends('layouts.app')
@section('title', $document->template_name)
@section('content')
    <div class="max-w-xl">
        <h1 class="text-2xl font-bold text-gray-800 mb-1">{{ $document->template_name }}</h1>
        <p class="text-sm text-gray-500 mb-6">Fill in the fields below to generate your document</p>

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center justify-between">
                <span class="text-sm text-green-700 font-medium">{{ session('success') }}</span>
                <button onclick="document.getElementById('dl').src='{{ route('documents.download') }}'"
                    class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition cursor-pointer">
                    Download
                </button>
            </div>
            <iframe id="dl" class="hidden"></iframe>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                <ul class="text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('documents.generate', $document) }}"
            class="bg-white border border-gray-200 rounded-xl p-6 flex flex-col gap-4">
            @csrf

            @foreach ($fields as $field)
                @include('components.form-field', ['field' => $field])
            @endforeach

            <button class="mt-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition cursor-pointer">
                Generate
            </button>
        </form>
    </div>
@endsection


