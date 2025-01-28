@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <!-- Header Section -->
    <div class="text-left mb-8">
        <h1 class="text-3xl font-bold mb-4"> Tugas Sesi 1</h1>
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

        <h1 class="text-xl font-bold mb-4 mt-5">Add Submission</h1>

        <!-- File Submission Container -->
        <form action="{{ route('taskSubmit.store', $task->task_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">File Submission</label>
                <div id="file-upload-area"
                    class="mt-1 flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-md p-4 cursor-pointer hover:border-blue-500">
                    <input type="file" name="file" @change="handleFileChange" multiple>
                </div>
                <p x-text="fileName" class="mt-2 text-sm text-gray-500"></p>
            </div>

            <!-- Buttons -->
            <div class="mt-4 flex gap-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Save change</button>
                <button type="reset" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400">Cancel</button>
            </div>
        </form>
    </div>

</div>
@endsection