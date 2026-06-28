@extends('layouts.guest')
@section('title', 'Login')
@section('content')

    <div class="w-full max-w-md bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
        <div class="mb-8 text-center">
            <h1 class="text-2xl font-bold text-gray-900">CMC-DMS</h1>
            <p class="text-sm text-gray-500 mt-1">Sign in to your account</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-4">
            @csrf

            <div class="flex flex-col gap-1">
                <label for="email" class="text-sm font-medium text-gray-700">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    autocomplete="email"
                    class="border @error('email') border-red-400 @else border-gray-300 @enderror rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('email')
                    <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex flex-col gap-1">
                <label for="password" class="text-sm font-medium text-gray-700">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="border @error('password') border-red-400 @else border-gray-300 @enderror rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('password')
                    <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <button class="mt-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">
                Sign in
            </button>
        </form>
    </div>

@endsection
