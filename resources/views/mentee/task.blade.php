@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <!-- Header Section -->
    <div class="text-left mb-8">
        <h1 class="text-3xl font-bold mb-4">{{$task->title}}</h1>
    </div>

    <!-- Mentoring Section -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <div class="border-b pb-4 mb-4">
            <p class="text-sm text-gray-700">
                <strong>Opened:</strong> {{$opened}} <br>
                <strong>Due:</strong> {{ \Carbon\Carbon::parse($task->deadline)->setTimezone('Asia/Jakarta')->format('l, d F Y, g:i A') }}
            </p>
        </div>

        <p class="mt-4 text-sm text-gray-700">{{$task->description}}</p>
        <a href="{{ route('taskSubmit') }}">
            <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mt-4">
                Add Submission
            </button>
        </a>

        <div class="mt-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Submission Status</h2>
            <table class="table-auto w-full text-sm text-left text-gray-700 border-collapse">
                <tbody>
                    <tr class="border-b {{ $lastModified ? 'bg-green-100' : '' }}">
                        <td class="py-2 px-4 font-medium text-gray-900">Submission status</td>
                        <td class="py-2 px-4">{{$submissionStatus}}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 px-4 font-medium text-gray-900">Grading status</td>
                        <td class="py-2 px-4">{{$gradingStatus}}</td>
                    </tr>
                    <tr class="border-b {{ $lastModified ? 'bg-green-100' : '' }}">
                        <td class="py-2 px-4 font-medium text-gray-900">Time remaining</td>
                        <td class="py-2 px-4">{{$timeRemaining}}</td>
                    </tr>
                    @if ($lastModified)
                    <tr class="border-b">
                        <td class="py-2 px-4 font-medium text-gray-900">Last modified</td>
                        <td class="py-2 px-4">{{$lastModified}}</td>
                    </tr>
                    @endif
                    @if ($file)
                    <tr>
                        <td class="py-2 px-4 font-medium text-gray-900">File submission</td>
                        <td class="py-2 px-4">
                            <a href="#" class="flex items-center gap-2 text-blue-500 hover:underline">
                                <img src="/images/file.svg" alt="PDF Icon" class="w-5 h-5">
                                download tugas
                            </a>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection