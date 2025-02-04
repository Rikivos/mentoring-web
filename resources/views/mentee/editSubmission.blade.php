@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <!-- Header Section -->
    <div class="text-left mb-8">
        <h1 class="text-3xl font-bold mb-4">Edit Submission</h1>
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

        <h1 class="text-xl font-bold mb-4 mt-5">Edit Submission</h1>

        <!-- Form untuk Edit Submission -->
        <form action="{{ route('assignment.update', $submission->assignment_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Current File</label>
                @if ($submission->file)
                    <div class="flex items-center gap-2 mt-2">
                        <a href="{{ route('assignment.download', $submission->assignment_id) }}" class="text-blue-500 hover:underline flex items-center">
                            <img src="/images/file.svg" alt="File Icon" class="w-5 h-5">
                            {{ basename($submission->file) }}
                        </a>
                    </div>
                @else
                    <p class="text-sm text-gray-500">No file uploaded yet.</p>
                @endif
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700">Upload New File</label>
                <input type="file" name="file" class="mt-2 border p-2 rounded w-full">
            </div>

            <!-- Buttons -->
            <div class="mt-4 flex gap-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Save Changes</button>
                <a href="{{ route('taskSubmit', $task->task_id) }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
