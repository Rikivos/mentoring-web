@extends('layouts.app')

@section('content')
<div class="bg-gray-200 min-h-screen flex items-center justify-center">
    <!-- Card Container -->
    <div class="bg-white w-96 p-8 rounded-lg shadow-md">
        <!-- Header -->
        <h1 class="text-xl font-bold text-gray-800 text-center mb-6">Single Sign On</h1>

        <!-- Form -->
        <form action="{{ route('login') }}" method="POST">
            @csrf

            <!-- NIM SIA -->
            <div class="mb-4">
                <label for="nim" class="block text-sm font-medium text-gray-700 mb-1">NIM SIA</label>
                <input
                    type="text"
                    id="nim"
                    name="nim"
                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="Masukkan NIM SIA"
                    value="{{ old('nim') }}">
                @error('nim')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kata Sandi -->
            <div class="mb-2">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="Masukkan Kata Sandi">
                @error('password')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Error Message jika login gagal -->
            @if(session('errors'))
                <p class="text-sm text-red-600 mt-2">{{ session('errors')->first('login') }}</p>
            @endif

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full mt-6 bg-blue-600 text-white font-bold py-2 rounded-md hover:bg-blue-500 focus:outline-none">
                Masuk
            </button>
        </form>
    </div>
</div>
@endsection
