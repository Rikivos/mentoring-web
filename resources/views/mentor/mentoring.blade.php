@extends('layouts.app')

@section('content')
    <div class="bg-blue-600 text-white">
        <div class="container mx-auto flex justify-center items-center py-4 px-6">
            <a href="{{ route('mentor.mentoring', $course->course_slug) }}"
                class="text-lg font-bold mx-4 underline">Mentoring</a>
            <a href="{{ route('mentor.mentoring.participant', $course->course_slug) }}"
                class="text-lg font-bold mx-4">Participants</a>
        </div>
    </div>

    <div class="container mx-auto p-4 "x-data="{
        isSubmissionModalOpen: false,
        isAttendanceModalOpen: false,
        closeSubmissionModal() { this.isSubmissionModalOpen = false; },
        closeAttendanceModal() { this.isAttendanceModalOpen = false; }
    }">
        <div class ="text-left mb-8">
            <h1 class="text-3xl font-bold mb-4">Mentoring</h1>
        </div>
        <div x-data="mentoringForm" class="accordion bg-white shadow rounded-lg p-6">
            <div class="flex justify-end items-center mb-4">
                <button id="expandAllBtn" class="text-blue-500 hover:underline">Expand all</button>
            </div>

            <div class="accordion-item border rounded-lg mb-6">
                <h2 class="accordion-header flex justify-between items-center px-4 py-3 bg-gray-100">
                    <button class="accordion-button text-left text-lg font-semibold flex items-center focus:outline-none">
                        <span class="ml-2">General</span>
                    </button>
                    <button @click="showForm = !showForm" class="add-form-button text-blue-500 hover:underline">
                        Add Activity or Resources
                    </button>
                </h2>
                <div x-show="showForm" class="accordion-collapse p-6 bg-white border-t">
                    <form @submit.prevent="submitForm" class="space-y-6">
                        <!-- Module Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Module Title</label>
                            <input x-model="formData.module_title" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                required />
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea x-model="formData.content" rows="4"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" required></textarea>
                        </div>

                        <!-- File Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">File</label>
                            <div id="file-upload-area"
                                class="mt-1 flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-md p-4 cursor-pointer hover:border-blue-500">
                                <input type="file" @change="handleFileChange" multiple>
                            </div>
                            <p x-text="fileName" class="mt-2 text-sm text-gray-500"></p>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end space-x-4">
                            <button type="button" @click="showForm = false"
                                class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Cancel</button>
                            <button type="submit"
                                class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Existing Modules -->
            @foreach ($modules as $key => $module)
                <div class="accordion-item outline-2 outline outline-gray-200 rounded-lg mb-6 relative">
                    <h2 class="accordion-header flex justify-between items-center p-4">
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
                            <p>{{ $module->content }}</p>
                            <div class="mt-4">
                                @foreach ($module->attendances as $attendance)
                                    @if (!empty($attendance))
                                        <a href="/presence" class="flex items-center gap-2 text-blue-500 hover:underline">
                                            <img src="/images/presence.svg" alt="PDF Icon" class="w-5 h-5">
                                            Presensi Kehadiran
                                        </a>
                                    @endif
                                @endforeach

                                @if (!empty($module->file_path))
                                    <a href="{{ route('module.downloadByFileName', $module->file_path) }}"
                                        class="flex items-center gap-2 text-blue-500 hover:underline">
                                        <img src="/images/task.svg" alt="PDF Icon" class="w-5 h-5">
                                        {{ $module->file_path }}
                                    </a>
                                @endif

                                @foreach ($module->tasks as $task)
                                    @if (!empty($task->file))
                                        <a href="/task" class="flex items-center gap-2 text-blue-500 hover:underline">
                                            <img src="/images/task.svg" alt="PDF Icon" class="w-5 h-5">
                                            download tugas
                                        </a>
                                    @endif

                                    @if (!empty($task))
                                        <a href="#" class="flex items-center gap-2 text-blue-500 hover:underline">
                                            <img src="/images/file.svg" alt="PDF Icon" class="w-5 h-5">
                                            {{ $task->description }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Dropdown Menu modules -->
                    <div x-data="{ openDropdown: null }" class="relative z-10">
                        <button @click="openDropdown = openDropdown === {{ $key }} ? null : {{ $key }}"
                            class="text-gray-600 hover:bg-gray-200 rounded-full p-2">
                            ⋮
                        </button>
                        <div x-show="openDropdown === {{ $key }}" x-transition
                            class="absolute right-0 mt-2 w-40 bg-white shadow-lg rounded-lg py-2">
                            <button
                                @click="openEditModal({ id: '{{ $module->id }}', module_title: '{{ $module->module_title }}', content: '{{ $module->content }}', file_path: '{{ $module->file_path }}' })"
                                class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                Edit Modules
                            </button>
                            <button @click="isSubmissionModalOpen = true"
                                class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                Tambah Submission
                            </button>
                            <button @click="isAttendanceModalOpen = true"
                                class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                Tambah Attendance
                            </button>
                        </div>
                    </div>
                    </h2>
                </div>
            @endforeach

            <!-- Add/Edit Attendance Modal -->
            <div x-show="isAttendanceModalOpen" @keydown.window.escape="closeAttendanceModal"
                class="fixed inset-0 flex items-center justify-center z-50">
                <div class="bg-black opacity-50 absolute inset-0"></div>
                <div class="bg-white rounded-lg shadow-lg w-1/3 relative z-10 p-6 max-h-full overflow-y-auto">
                    <h3 class="text-lg font-bold mb-4">Attendance</h3>
                    <form @submit.prevent="submitAttendanceForm" class="space-y-4">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input x-model="formData.attendance.name" type="text"
                                class="block w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Date and Time -->
                        <div class="mt-2 flex space-x-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date</label>
                                <input x-model="formData.attendance.date" type="date"
                                    class="block w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Time</label>
                                <div class="flex space-x-2">
                                    <input x-model="formData.attendance.time_start" type="time"
                                        class="block w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                    <span class="self-center">to</span>
                                    <input x-model="formData.attendance.time_end" type="time"
                                        class="block w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Date and Time -->
                        <div class="mt-2 flex space-x-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date</label>
                                <input x-model="formData.attendance.date" type="date"
                                    class="block w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Time</label>
                                <div class="flex space-x-2">
                                    <input x-model="formData.attendance.time_start" type="time"
                                        class="block w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                    <span class="self-center">to</span>
                                    <input x-model="formData.attendance.time_end" type="time"
                                        class="block w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-2">
                            <button type="button" @click="closeAttendanceModal"
                                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Add/Edit Submission Modal -->
            <div x-show="isSubmissionModalOpen" @keydown.window.escape="closeSubmissionModal"
                class="fixed inset-0 flex items-center justify-center z-50">
                <div class="bg-black opacity-50 absolute inset-0"></div>
                <div class="bg-white rounded-lg shadow-lg w-1/3 relative z-10 p-6 max-h-full overflow-y-auto">
                    <h3 class="text-lg font-bold mb-4">Submission Types</h3>
                    <form @submit.prevent="submitSubmissionForm" class="space-y-4">
                        <!-- File Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">File</label>
                            <div id="file-upload-area"
                                class="mt-1 flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-md p-4 cursor-pointer hover:border-blue-500">
                                <input type="file" @change="handleFileChange" multiple>
                            </div>
                            <p x-text="fileName" class="mt-2 text-sm text-gray-500"></p>
                        </div>

                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title</label>
                            <input x-model="formData.submissions.title" type="text"
                                class="block w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea x-model="formData.submissions.description" rows="4"
                                class="block w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" required></textarea>
                        </div>

                        <!-- Date and Time -->
                        <div class="mt-2 flex space-x-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date</label>
                                <input x-model="formData.attendance.date" type="date"
                                    class="block w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Time</label>
                                <div class="flex space-x-2">
                                    <input x-model="formData.attendance.time_start" type="time"
                                        class="block w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                    <span class="self-center">to</span>
                                    <input x-model="formData.attendance.time_end" type="time"
                                        class="block w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-2">
                            <button type="button" @click="closeSubmissionModal"
                                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
                        </div>
                    </form>
                </div>
            </div>


            <!-- Edit Moduls -->
            <div x-show="isModalOpen" @keydown.window.escape="closeEditModal"
                class="fixed inset-0 flex items-center justify-center z-50">
                <div class="bg-black opacity-50 absolute inset-0"></div>

                <div class="bg-white rounded-lg shadow-lg w-1/3 relative z-10 p-6 max-h-full overflow-y-auto">
                    <h3 class="text-lg font-bold mb-4">Edit Module</h3>
                    <form @submit.prevent="submitForm" class="space-y-4">
                        <!-- Module Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Module Title</label>
                            <input x-model="formData.module_title" type="text"
                                class="block w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>
                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea x-model="formData.content" rows="4"
                                class="block w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" required></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">File</label>
                            <div id="file-upload-area"
                                class="mt-1 flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-md p-4 cursor-pointer hover:border-blue-500">
                                <input type="file" @change="handleFileChange" multiple>
                            </div>
                            <p x-text="fileName" class="mt-2 text-sm text-gray-500"></p>
                        </div>
                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-2">
                            <button type="button" @click="closeEditModal"
                                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('mentoringForm', () => ({
                showForm: false,
                isModalOpen: false,
                file: null,
                fileName: '',
                formData: {
                    module_title: '',
                    content: '',
                    course_id: '{{ $course->course_id }}',
                },
                editingModule: null,

                openEditModal(module) {
                    this.editingModule = module;
                    this.formData.module_title = module.module_title;
                    this.formData.content = module.content;
                    this.isModalOpen = true;
                },

                closeEditModal() {
                    this.isModalOpen = false;
                    this.editingModule = null;
                    this.formData.module_title = '';
                    this.formData.content = '';
                },

                handleFileChange(event) {
                    const files = event.target.files;
                    if (files.length > 0) {
                        this.file = files[0];
                        this.fileName = this.file.name;
                    } else {
                        this.file = null;
                        this.fileName = '';
                    }
                },

                async submitForm() {
                    try {
                        const formData = new FormData();
                        formData.append('module_title', this.formData.module_title);
                        formData.append('content', this.formData.content);
                        formData.append('course_id', this.formData.course_id);
                        if (this.file) {
                            formData.append('file_path', this.file);
                        }

                        const response = this.editingModule ?
                            await axios.post(`/module/${this.editingModule.id}`, formData, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                },
                                method: 'POST',
                            }) :
                            await axios.post('{{ route('module.store') }}', formData, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                },
                            });

                        alert(this.editingModule ? 'Module updated successfully' :
                            'Module created successfully');
                        window.location.reload();
                    } catch (error) {
                        console.error(error);
                        alert('An error occurred');
                    }
                },
            }));
        });
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

        //script dropdown
    </script>
@endsection
