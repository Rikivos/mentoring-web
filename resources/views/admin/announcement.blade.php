@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="flex bg-gray-200" x-data="{ showAddModal: false, showEditModal: false, selectedAnnouncement: {} }">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Data Content -->
    <div class="w-2/3 p-4 justify-between items-center container mx-auto">
        <div>
            <!-- Kelola Announcement -->
            <div class="bg-white shadow-md rounded-md p-4 mt-6">
                <h2 class="text-xl font-bold mb-4">Announcement</h2>

                <!-- Header dengan Tombol Tambah -->
                <div class="flex justify-between items-center mb-4">
                    <button @click="showAddModal = true" class="bg-blue-500 text-white px-4 py-2 rounded flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah
                    </button>
                </div>

                <!-- Modal Tambah -->
                <div x-show="showAddModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center"
                    style="display: none;" x-transition>
                    <div class="bg-white p-6 rounded shadow-md w-1/3">
                        <h2 class="text-lg font-semibold mb-4">Tambah Data</h2>
                        <form method="POST" action="/upload-announcement" enctype="multipart/form-data">
                            @csrf
                            <!-- File Upload -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">File</label>
                                <input type="file" name="announcement" class="border rounded-md w-full p-2" required>
                            </div>
                            <!-- Announcement Input Field -->
                            <div class="mb-4">
                                <label for="title" class="block text-sm font-medium text-gray-700">Judul Announcement</label>
                                <input type="text" id="title" name="title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Masukkan judul" required>
                            </div>
                            <div class="flex justify-end">
                                <button type="button" class="bg-gray-300 text-black px-4 py-2 rounded mr-2" @click="showAddModal = false">
                                    Batal
                                </button>
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabel -->
                <table class="w-full table-auto mt-4">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="text-left p-2">File</th>
                            <th class="text-left p-2">Judul</th>
                            <th class="text-left p-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($announcements as $announcement)
                        <tr>
                            <td class="p-2">
                                {{ $announcement->file_path }}
                            </td>
                            <td class="p-2">{{ $announcement->title }}</td>
                            <td class="p-2">
                                <button @click="showEditModal = true; selectedAnnouncement = @js($announcement)" class="bg-blue-500 text-white px-4 py-2 rounded">
                                    Edit
                                </button>
                                <form action="{{ route('announcement.delete', $announcement->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="p-2 text-center">Tidak ada pengumuman</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Modal Edit -->
                <div x-show="showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center"
                    style="display: none;" x-transition>
                    <div class="bg-white p-6 rounded shadow-md w-1/3">
                        <h2 class="text-lg font-semibold mb-4">Edit Data</h2>
                        <form method="POST" :action="`{{ route('announcement.update', '') }}/${selectedAnnouncement.id}`" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label for="edit_title" class="block text-sm font-medium text-gray-700">Judul Announcement</label>
                                <input type="text" id="edit_title" name="title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" x-bind:value="selectedAnnouncement.title" required>
                            </div>
                            <div class="flex justify-end">
                                <button type="button" class="bg-gray-300 text-black px-4 py-2 rounded mr-2" @click="showEditModal = false">
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
@endsection