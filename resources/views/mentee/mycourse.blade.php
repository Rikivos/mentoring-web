@extends('layouts.app')

@section('content')

<div class="container mx-auto p-4">
    <div class="text-left mb-8">
        <h1 class="text-3xl font-bold mb-4">Mentoring</h1>
    </div>
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Class Mentoring</h2>
            <div>
                <select id="viewMode" class="border border-gray-300 rounded-md p-2 text-sm shadow-sm">
                    <option value="card" {{ request('view') === 'card' ? 'selected' : '' }}>Card</option>
                    <option value="list" {{ request('view') === 'list' ? 'selected' : '' }}>List</option>
                </select>
            </div>
        </div>

        @if (request('view') === 'list')
        <div class="space-y-4">
            @foreach ($courses as $course)
            <a href="{{ route('courses.show', $course->course_slug) }}" class="block">
                <div class="flex items-center bg-white  hover:shadow-lg transition-shadow duration-300">
                    <img src="/images/Rectangle.svg" alt="Group Image" class="w-1/3 h-32 object-cover">
                    <div class="ml-4">
                        <h3 class="text-lg font-bold mb-1 text-blue-600">{{ $course->course_title }}</h3>
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <img src="/images/user.svg" alt="Mentor Icon" class="w-8 h-8 rounded-full">
                            <div>
                                <span class="font-medium">{{ $course->mentor->name }}</span>
                                <p>Mentor</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($courses as $course)
            <a href="{{ session('role') === 'mentor' 
            ? route('mentor.mentoring', $course->course_slug) 
            : route('courses.show', $course->course_slug) }}" class="block">
                <div
                    class="bg-white shadow rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <img src="/images/Rectangle.svg" alt="Group Image" class="w-full h-32 object-cover">
                    <div class="p-4">
                        <h3 class="text-lg font-bold mb-2">{{ $course->course_title }}</h3>
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <img src="/images/user.svg" alt="Mentor Icon" class="w-8 h-8 rounded-full">
                            <span>{{ $course->mentor->name }}</span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</div>

<script>
    document.getElementById('viewMode').addEventListener('change', function() {
        const view = this.value;
        const url = new URL(window.location.href);
        url.searchParams.set('view', view);
        window.location.href = url.toString();
    });
</script>

@endsection