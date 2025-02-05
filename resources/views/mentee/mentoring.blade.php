@extends('layouts.app')

@section('content')
<div class="bg-blue-600 text-white">
    <div class="container mx-auto flex justify-center items-center py-4 px-6">
        <a href="{{ route('courses.show', $course->course_slug) }}" class="text-lg font-bold mx-4 underline">Mentoring</a>
        <a href="{{ route('participant', $course->course_slug) }}" class="text-lg font-bold mx-4">Participants</a>
    </div>
</div>

<div class="container mx-auto p-4">
    <!-- Header Section -->
    <div class="text-left mb-8">
        <h1 class="text-3xl font-bold mb-4">{{ $course->course_title }}</h1>
    </div>

    <!-- Accordion Section -->
    <div class="accordion bg-white shadow rounded-lg p-6 ">
        <div class="flex justify-end items-center mb-4">
            <button id="expandAllBtn" class="text-blue-500 hover:underline">Expand all</button>
        </div>

        @foreach ($modules as $key => $module)
        <div class="accordion-item outline-2 outline outline-gray-200 rounded-lg mb-6">
            <h2 class="accordion-header">
                <button class="accordion-button w-full text-left bg-white p-4 flex items-center focus:outline-none"
                    type="button" data-target="#module{{ $key }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="31" viewBox="0 0 30 31"
                        fill="none" class="accordion-icon transition-transform duration-300">
                        <circle cx="15" cy="15.5" r="14" stroke="black" stroke-width="1.5" />
                        <path d="M11 6 L19 15.5 L11 25" stroke="black" stroke-width="1.875" stroke-linecap="round"
                            stroke-linejoin="round" class="arrow-path" />
                    </svg>
                    <span class="text-lg font-semibold ml-2">{{ $module->module_title }}</span>
                </button>
            </h2>
            <div id="module{{ $key }}" class="accordion-collapse hidden">
                <div class="accordion-body p-4">
                    <p class="mb-4">
                        {{ $module->content }}
                    </p>
                    <div class="mt-4">
                        @foreach ($module->attendances as $attendance)
                        @if(!empty($attendance))
                        <a href="{{ route('presence', ['module_id' => $module->module_id]) }}" class="flex items-center gap-2 text-blue-500 hover:underline">
                            <img src="/images/presence.svg" alt="PDF Icon" class="w-5 h-5">
                            Presensi Kehadiran
                        </a>
                        @endif
                        @endforeach

                        @if (!empty($module->file_path))
                        <a href="{{ route('module.downloadByFileName', $module->file_path) }}" class="flex items-center gap-2 text-blue-500 hover:underline">
                            <img src="/images/task.svg" alt="PDF Icon" class="w-5 h-5">
                            {{ $module->file_path }}
                        </a>
                        @endif

                        @foreach ($module->tasks as $task)
                        @if (!empty($task->file))
                        <a href="{{route('task.download', $task->task_id)}}" class="flex items-center gap-2 text-blue-500 hover:underline">
                            <img src="/images/task.svg" alt="PDF Icon" class="w-5 h-5">
                            {{ $task->file }}
                        </a>
                        @endif

                        @if (!empty($task))
                        <a href="{{ route('mentee.task', ['task_id' => $task->task_id]) }}" class="flex items-center gap-2 text-blue-500 hover:underline">
                            <img src="/images/file.svg" alt="PDF Icon" class="w-5 h-5">
                            {{ $task->description }}
                        </a>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    document.querySelectorAll('.accordion-button').forEach(button => {
        button.addEventListener('click', function() {
            const target = document.querySelector(this.getAttribute('data-target'));
            const icon = this.querySelector('.accordion-icon');

            // Toggle visibility of the content
            target.classList.toggle('hidden');

            // Rotate the icon
            if (target.classList.contains('hidden')) {
                icon.style.transform = 'rotate(0deg)'; // Panah ke kanan
            } else {
                icon.style.transform = 'rotate(90deg)'; // Panah ke bawah
            }
        });
    });
</script>
@endsection