@extends('layouts.app')

@section('content')
@if (session('message'))
<div class="bg-green-100 text-green-700 p-4 text-center rounded-lg mb-6">
    {{ session('message') }}
</div>
@endif

@if (session('error'))
<div class="bg-red-100 text-red-700 p-4 text-center rounded-lg mb-6">
    {{ session('error') }}
</div>
@endif


<div class="container mx-auto p-4">
    <!-- Header Section -->
    <div class="text-left mb-8">
        <h1 class="text-3xl font-bold mb-4">{{ $courses->course_title }}</h1>
    </div>

    <!-- Mentoring Enrollment Section -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Enrolment Option</h2>

        <div class="grid grid-cols-1 md:grid-cols-[1fr_auto_1fr] gap-6 items-center">
            <!-- Left Section: Group Info -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <img src="/images/Rectangle.svg" alt="Group Image" class="w-full h-32 object-cover">
                <div class="p-4">
                    <h3 class="text-lg font-bold mb-2">{{ $courses->course_title }}</h3>
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <img src="/images/user.svg" alt="Mentor Icon" class="w-8 h-8 rounded-full">
                        <span>{{ $courses->mentor->name }}</span>
                    </div>
                </div>
            </div>

            <!-- Middle Divider -->
            <div class="hidden md:block w-px h-full bg-gray-300"></div>

            <!-- Right Section: Enroll Button -->
            <div class="flex flex-col items-center justify-center  rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4 text-center">Self Enroll</h3>
                <p class="text-sm text-gray-500 mb-6 text-center">Enroll to access</p>
                <form action="{{ route('courses.enroll', $courses->course_slug) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-300">
                        Enroll
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection