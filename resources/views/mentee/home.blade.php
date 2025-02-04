@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <!-- Header Section -->
    <div class="text-left mb-8">
        <h1 class="text-3xl font-bold mb-4">Site Mentoring</h1>
    </div>

    <!-- Announcement Section -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Site Announcements</h2>
        <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-lg border border-gray-200" x-data="announcementData()">
            <!-- Announcement Content -->
            <div>
                @forelse ($announcements as $announcement)
                <a href="{{ route('announcement.download', $announcement->file_path) }}">
                    <div class="flex items-center gap-4">
                        <img src="/images/pdf.svg" alt="Announcement Icon" class="w-8 h-8">
                        <p class="text-blue-600">{{$announcement->title}}</p>
                    </div>
                </a>
                @empty
                <p class="text-gray-500">Tidak ada pengumuman saat ini.</p>
                @endforelse
            </div>
        </div>
        <div class="mt-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Daftar Kelompok</h2>
                <div class="relative">
                    <input type="text" placeholder="Search..."
                        class="pl-4 pr-10 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-200 focus:outline-none" />
                    <span class="absolute right-3 top-2.5 text-gray-400">
                    </span>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($courses as $course)
                <a href="{{ route('enroll', $course->course_slug) }}" class="block">
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
        </div>

    </div>
    <!-- Group List Section -->
</div>
@endsection