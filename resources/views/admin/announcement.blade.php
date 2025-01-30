@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="flex  bg-gray-200">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Data Content -->
    <div class="w-2/3 p-4 justify-between items-center container mx-auto">
        <div x-data="courseManager()">
            <!-- Kelola Announcement -->
            <div class="bg-white shadow-md rounded-md p-4 mt-6">
                <!-- Judul -->
                <h2 class="text-xl font-bold mb-4">Announcement</h2>

                <!-- Tombol Tambah dan Pencarian -->

                <!-- Header dengan Tombol Tambah -->
                <div class="flex justify-between items-center mb-4">
                    <button class="bg-blue-500 text-white px-4 py-2 rounded flex items-center"
                        @click="showAddModal = true; newCourse = { course_title: '', mentor: { name: '' } };">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah
                    </button>
                    <div class="relative">
                        <input type="text" placeholder="Search" class="border rounded-md px-3 py-2 pl-10">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 absolute top-2.5 left-3 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-4.35-4.35m1.35-6.15a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Modal Tambah Data Kelas -->
                <div x-show="showAddModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center"
                    style="display: none;" x-transition>
                    <div class="bg-white p-6 rounded shadow-md w-1/3">
                        <h2 class="text-lg font-semibold mb-4">Tambah Data</h2>
                        <form method="POST" action="{{ route('store.course') }}">
                            @csrf
                            <!-- File Upload -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">File</label>
                                <div id="file-upload-area"
                                    class="mt-1 flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-md p-4 cursor-pointer hover:border-blue-500">
                                    <input type="file" @change="handleFileChange" multiple>
                                </div>
                                <p x-text="fileName" class="mt-2 text-sm text-gray-500"></p>
                            </div>
                            <!-- Announcement Input Field -->
                            <div class="mb-4">
                                <label for="add_announcement" class="block text-sm font-medium text-gray-700">Announcement</label>
                                <input type="text" id="add_course_title" name="course_title"
                                    x-model="newCourse.course_title"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="Announcement" />
                            </div>

                            <div class="flex justify-end">
                                <button type="button" class="bg-gray-300 text-black px-4 py-2 rounded mr-2"
                                    @click="showAddModal = false">
                                    Batal
                                </button>
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>


                <div class="mb-4">
                    @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                        @endforeach
                    </div>
                    @endif
                </div>


                <!-- Tabel Kelas -->
                <table class="w-full table-auto mt-4">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="text-left p-2">File</th>
                            <th class="text-left p-2">Judul</th>
                            <th class="text-left p-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td class="p-2">{}</td>
                            <td class="p-2">{{'Tidak ada mentor' }}</td>
                            <td class="p-2">
                                <button class="bg-blue-500 text-white px-4 py-2 rounded"
                                    data-id="{}"
                                    data-title="{}"
                                    data-mentor-id="{}"
                                    @click="openEditModal($event)">
                                    Edit
                                </button>

                                <button class="bg-red-500 text-white px-4 py-2 rounded"
                                    @click="deleteCourse()">
                                    Hapus
                                </button>
                            </td>
                        </tr>

                    </tbody>
                </table>

                <!-- Modal Edit Data Kelas -->
                <div x-show="showEditModal"
                    class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center"
                    style="display: none;" x-transition>
                    <div class="bg-white p-6 rounded shadow-md w-1/3">
                        <h2 class="text-lg font-semibold mb-4">Edit Data</h2>
                        <form @submit.prevent="updateData">
                            @csrf
                            <input type="hidden" x-model="editCourse.course_id" />
                            <!-- File Upload -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">File</label>
                                <div id="file-upload-area"
                                    class="mt-1 flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-md p-4 cursor-pointer hover:border-blue-500">
                                    <input type="file" @change="handleFileChange" multiple>
                                </div>
                                <p x-text="fileName" class="mt-2 text-sm text-gray-500"></p>
                            </div>
                            <!-- Announcement Input Field -->
                            <div class="mb-4">
                                <label for="add_announcement" class="block text-sm font-medium text-gray-700">Announcement</label>
                                <input type="text" id="add_course_title" name="course_title"
                                    x-model="newCourse.course_title"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="Announcement" />
                            </div>
                            <div class="flex justify-end">
                                <button type="button" class="bg-gray-300 text-black px-4 py-2 rounded mr-2"
                                    @click="showEditModal = false">
                                    Batal
                                </button>
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script>
    function courseManager() {
        return {
            showAddModal: false,
            showEditModal: false,
            editCourse: {
                course_id: null,
                course_title: '',
                mentor_id: null,
            },

            openEditModal(event) {
                const button = event.currentTarget;

                this.editCourse.course_id = button.getAttribute('data-id');
                this.editCourse.course_title = button.getAttribute('data-title');
                this.editCourse.mentor_id = button.getAttribute('data-mentor-id');

                this.showEditModal = true;
            },

            updateData() {
                const csrfToken = document.querySelector('form input[name="_token"]').value;

                if (!this.editCourse.course_id) {
                    console.error('Course ID tidak ditemukan!');
                    return;
                }

                fetch(`/admin/course/update/${this.editCourse.course_id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({
                            course_title: this.editCourse.course_title,
                            mentor_id: this.editCourse.mentor_id,
                        }),
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        this.showEditModal = false;
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Data berhasil diperbarui.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false,
                        });
                        setTimeout(() => location.reload(), 1500);
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        Swal.fire('Error!', 'Gagal memperbarui data.', 'error');
                    });
            },

            deleteCourse(id) {
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: 'Data akan dihapus secara permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios
                            .delete(`/admin/course/delete/${id}`)
                            .then((response) => {
                                if (response.status === 200) {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: response.data.message,
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false,
                                    });
                                    setTimeout(() => location.reload(), 1500);
                                } else {
                                    Swal.fire('Gagal!', response.data.message, 'error');
                                }
                            })
                            .catch((error) => {
                                Swal.fire(
                                    'Gagal!',
                                    error.response?.data?.message || 'Terjadi kesalahan.',
                                    'error'
                                );
                            });
                    }
                });
            }


        };
    }

    function courseForm() {
        return {
            showAddModal: false,
            newCourse: {
                course_title: '',
                mentor_id: '',
            },
        };
    }
</script>
@endsection