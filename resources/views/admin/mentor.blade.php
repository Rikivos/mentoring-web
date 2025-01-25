@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="flex  bg-gray-200">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Data Content -->
    <div class="w-2/3 p-4 justify-between items-center container mx-auto">
        <div x-data="mentorManager()">
            <!-- Table -->
            <div class="bg-white shadow-md rounded-md p-4">
                <!-- Judul -->
                <h2 class="text-xl font-bold mb-4">Kelola Mentor</h2>

                <!-- Tombol Tambah dan Pencarian -->
                <div class="flex justify-between items-center mb-4">
                    <button class="bg-blue-500 text-white px-4 py-2 rounded flex items-center"
                        @click="showAddMentor = true; newMentor = { name: '', nim: '', role: '' };"><svg
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah
                    </button>
                    <div class="relative">
                        <form method="GET" action="{{ route('admin.mentor') }}" class="relative">
                            <input type="text" name="search" placeholder="Cari mentor..." aria-label="Search"
                                value="{{ $search ?? '' }}"
                                class="border rounded-md px-3 py-2 pl-10">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-5 h-5 absolute top-2.5 left-3 text-gray-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-4.35-4.35m1.35-6.15a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z" />
                            </svg>
                        </form>
                    </div>
                </div>

                <!-- Modal Tambah Mentor -->
                <div x-show="showAddMentor"
                    class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center" style="display: none;"
                    x-transition>
                    <div class="bg-white p-6 rounded shadow-md w-1/3">
                        <h2 class="text-lg font-semibold mb-4">Tambah Mentor</h2>
                        <form method="POST" action="{{ route('addMentor') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="mentor_name" class="block text-sm font-medium text-gray-700">Nama
                                    Mentor</label>
                                <input type="text" id="mentor_name" name="name" x-model="newMentor.name"
                                    @input="fetchSuggestions(newMentor.name)"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="Masukkan nama mentor" autocomplete="off" />
                                <ul x-show="suggestions.length"
                                    class="bg-white border rounded-md mt-2 max-h-40 overflow-y-auto">
                                    <template x-for="suggestion in suggestions" :key="suggestion.id">
                                        <li @click="selectSuggestion(suggestion)"
                                            class="p-2 hover:bg-gray-100 cursor-pointer" x-text="suggestion.name"></li>
                                    </template>
                                </ul>
                            </div>
                            <div class="mb-4">
                                <label for="mentor_nim" class="block text-sm font-medium text-gray-700">NIM
                                    Mentor</label>
                                <input type="text" id="mentor_nim" name="nim" x-model="newMentor.nim"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="Masukkan NIM mentor" />
                            </div>
                            <div class="mb-4">
                                <label for="mentor_role" class="block text-sm font-medium text-gray-700">Role</label>
                                <select id="mentor_role" name="role" x-model="newMentor.role"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">Pilih Role</option>
                                    <option value="mentee">Mentee</option>
                                    <option value="mentor">Mentor</option>
                                </select>
                            </div>
                            <div class="flex justify-end">
                                <button type="button" class="bg-gray-300 text-black px-4 py-2 rounded mr-2"
                                    @click="showAddMentor = false">
                                    Batal
                                </button>
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                                    Simpan
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

                <!-- Modal Edit Mentor -->
                <div x-show="showEditMentor"
                    class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center" style="display: none;"
                    x-transition>
                    <div class="bg-white p-6 rounded shadow-md w-1/3">
                        <h2 class="text-lg font-semibold mb-4">Edit Mentor</h2>
                        <form action="{{ route('admin.mentor.editRole') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="edit_mentor_nim" class="block text-sm font-medium text-gray-700">NIM
                                    Mentor</label>
                                <input type="text" id="edit_mentor_nim" name="nim" x-model="editMentor.nim"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="Masukkan NIM mentor" required />
                            </div>
                            <div class="mb-4">
                                <label for="edit_mentor_role"
                                    class="block text-sm font-medium text-gray-700">Role</label>
                                <select id="edit_mentor_role" name="role" x-model="editMentor.role"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    required>
                                    <option value="mentor">Mentor</option>
                                    <option value="mente">Mente</option>
                                </select>
                            </div>
                            <div class="flex justify-end">
                                <button type="button" class="bg-gray-300 text-black px-4 py-2 rounded mr-2"
                                    @click="showEditMentor = false">
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
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                        role="alert">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                        role="alert">
                        @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Tabel -->
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="text-left p-2">Nama</th>
                            <th class="text-left p-2">NIM</th>
                            <th class="text-left p-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mentors as $mentor)
                        <tr>
                            <td class="p-2">{{ $mentor->name }}</td>
                            <td class="p-2">{{ $mentor->nim }}</td>
                            <td class="p-2">
                                <button class="bg-blue-500 text-white px-4 py-2 rounded"
                                    @click="showEditMentor = true; editMentor = { id: {{ $mentor->id }}, name: '{{ $mentor->name }}', nim: '{{ $mentor->nim }}', role: '{{ $mentor->role }}' }">
                                    Edit
                                </button>
                                <button class="bg-red-500 text-white px-4 py-2 rounded"
                                    @click="deleteMentor({{ $mentor->nim }})">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    function mentorManager() {
        return {
            showAddMentor: false,
            showEditMentor: false,
            newMentor: {
                name: '',
                nim: '',
                role: ''
            },
            editMentor: {
                id: null,
                name: '',
                nim: '',
                role: ''
            },
            suggestions: [],

            fetchSuggestions(query) {
                if (query.length < 1) {
                    this.suggestions = [];
                    return;
                }

                fetch(`/mentor?query=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        this.suggestions = data;
                    });
            },

            selectSuggestion(suggestion) {
                this.newMentor.name = suggestion.name;
                this.newMentor.nim = suggestion.nim;
                this.suggestions = [];
            },

            addMentor() {
                console.log('Data baru:', this.newMentor);
                this.showAddMentor = false;
                Swal.fire('Berhasil!', 'Mentor berhasil ditambahkan.', 'success');
            },

            updateMentor() {
                console.log('Data diperbarui:', this.editMentor);
                this.showEditMentor = false;
                Swal.fire('Berhasil!', 'Mentor berhasil diperbarui.', 'success');
            },

            deleteMentor(nim) {
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
                            .post('/admin/mentor/destroy', {
                                nim: nim,
                            })
                            .then((response) => {
                                if (response.data.status === 'success') {
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

    function mentorForm() {
        return {
            showAddMentor: false,
            newMentor: {
                name: '',
                nim: '',
                role: '',
            },
            suggestions: [],

            fetchSuggestions(name) {
                // Logika untuk mengambil saran mentor berdasarkan nama (opsional)
                this.suggestions = []; // Reset suggestions (implement API jika ada)
            },

            selectSuggestion(suggestion) {
                this.newMentor.name = suggestion.name;
                this.newMentor.nim = suggestion.nim;
                this.suggestions = [];
            },
        };
    }
</script>
@endsection