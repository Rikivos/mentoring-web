@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">

    <!-- Participants Table -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Attendances</h2>
        <table class="min-w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="py-3 px-6 border border-gray-300 text-left">Nama</th>
                    <th class="py-3 px-6 border border-gray-300 text-left">NIM</th>
                    <th class="py-3 px-6 border border-gray-300 text-left">Keterangan</th>
                    <th class="py-3 px-6 border border-gray-300 text-left">Waktu Presensi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($attendances as $attendance)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-6 border border-gray-300 flex items-center">
                        <img src="/images/user.svg" alt="Avatar" class="w-8 h-8 rounded-full mr-4">
                        {{ $attendance->user->name }}
                    </td>
                    <td class="py-3 px-6 border border-gray-300">{{ $attendance->user->nim }}</td>
                    <td class="py-3 px-6 border border-gray-300 capitalize">
                        {{ $attendance->status }}
                    </td>
                    <td class="py-3 px-6 border border-gray-300">
                        {{ $attendance->formatted_date  }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-3 px-6 border border-gray-300 text-center font-semibold">No attendances found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination (optional, if applicable) -->
        <div class="flex justify-center mt-4">
            <nav>
                <ul class="flex items-center space-x-2">
                    <li><a href="#" class="px-3 py-2  hover:bg-gray-100">&lt;</a></li>
                    <li><a href="#" class="px-3 py-2  hover:bg-gray-100">1</a></li>
                    <li><a href="#" class="px-3 py-2  hover:bg-gray-100">2</a></li>
                    <li><a href="#" class="px-3 py-2  hover:bg-gray-100">3</a></li>
                    <li><a href="#" class="px-3 py-2 hover:bg-gray-100">&gt;</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection