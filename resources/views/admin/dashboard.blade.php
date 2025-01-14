@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="flex bg-gray-200">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Dashboard Content -->
    <div class="w-3/4 p-4 justify-between items-center container mx-auto">
        <!-- Dashboard Stats -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="p-4 bg-white shadow-md rounded-md text-center">
                <i class="fas fa-school text-blue-500 text-2xl mb-2"></i>
                <p class="text-gray-600">Jumlah Kelas</p>
                <h2 class="text-2xl font-bold">{{ $totalClasses }}</h2>
            </div>
            <div class="p-4 bg-white shadow-md rounded-md text-center">
                <i class="fas fa-users text-green-500 text-2xl mb-2"></i>
                <p class="text-gray-600">Jumlah Mahasiswa</p>
                <h2 class="text-2xl font-bold">{{ $totalMentees }}</h2>
            </div>
            <div class="p-4 bg-white shadow-md rounded-md text-center">
                <i class="fas fa-chalkboard-teacher text-purple-500 text-2xl mb-2"></i>
                <p class="text-gray-600">Jumlah Mentor</p>
                <h2 class="text-2xl font-bold">{{ $totalMentors }}</h2>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white shadow-md rounded-md p-4">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="text-left p-2">Kelompok</th>
                        <th class="text-left p-2">Mentor</th>
                        <th class="text-left p-2">Jumlah Peserta</th>
                        <th class="text-left p-2">Unduh Data</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($courses as $course)
                    <tr>
                        <td class="p-2">{{$course['name']}}</td>
                        <td class="p-2">{{$course['mentor_name']}}</td>
                        <td class="p-2">{{$course['participants_count']}}</td>
                        <td class="p-2">
                            <button class="bg-blue-500 text-white px-4 py-2 rounded">Unduh</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection