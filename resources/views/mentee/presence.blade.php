@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <!-- Header Section -->
    <div class="text-left mb-8">
        <h1 class="text-3xl font-bold mb-4"> Presensi Sesi 1</h1>
    </div>

    <!-- Mentoring Section -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <!-- Navigation Tabs -->
        <div class="flex justify-between items-center border-b pb-4 mb-4">
            <div class="flex gap-4">
                <button class="px-4 py-2 text-blue-600 font-medium rounded-md underline">This course</button>
                <button class="px-4 py-2 text-gray-600 font-medium rounded-md">All course</button>
            </div>
            <div class="flex gap-4">
                <button class="px-4 py-2 text-blue-600 font-bold border-b-2 border-blue-600 underline">Today</button>
                <button class="px-4 py-2 text-gray-600 font-medium">Week</button>
                <button class="px-4 py-2 text-gray-600 font-medium">Month</button>
            </div>
        </div>
        <button class="px-4 py-2 text-blue-600 font-bold bg-blue-100 rounded-md mb-6">Januari</button>

        <!-- Table -->
        <table class="w-full text-left border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-4 border border-gray-200">Date</th>
                    <th class="p-4 border border-gray-200">Description</th>
                    <th class="p-4 border border-gray-200">Status</th>
                    <th class="p-4 border border-gray-200">Remarks</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="p-4 border border-gray-200">{!! nl2br(e($attendanceDetails)) !!}</td>
                    <td class="p-4 border border-gray-200">Presensi Sesi 1</td>
                    <td class="p-4 border border-gray-200 {{ $status === null ? 'text-center' : 'text-left' }}">
                        {{ $status === null ? '?' : $status }}
                    </td>
                    <td class="p-4 border border-gray-200 text-600">
                        @if($status === null)
                        <a href="#" class="underline cursor-pointer text-blue-600">Submit attendance</a>
                        @else
                        Self-record
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Summary -->
        <div class="mt-4">
            <p class="text-sm text-gray-700"><strong>Taken session:</strong> 0</p>
            <p class="text-sm text-gray-700"><strong>Percentage session:</strong> 0%</p>
        </div>
    </div>


</div>
@endsection