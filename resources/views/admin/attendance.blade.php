@extends('layouts.admin')

@section('title', 'Dashboard - Kehadiran Mentoring')

@section('content')
<div class="flex bg-gray-200">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Data Content -->
    <main class="w-2/3 p-4 container mx-auto">
        <!-- Section: Kehadiran Mentoring -->
        <section class="bg-white shadow-md rounded-md p-4">
            <header class="mb-4">
                <h1 class="text-xl font-bold">Kehadiran Mentoring</h1>
                <hr class="my-6 border-b border-gray-300">
            </header>

            <!-- Tahun Akademik -->
            <div class="flex justify-end items-center mb-4">
                <label for="tahun" class="text-sm font-medium text-gray-700 mr-2">Tahun Akademik</label>
                <div class="relative">
                    <input id="tahun" type="number" name="tahun_akademik" aria-label="Tahun Akademik"
                        class="block w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                        value="2024" />
                </div>
            </div>

            <!-- Keterangan -->
            <div class="mt-4 mb-4">
                <p class="text-md font-bold text-black">Keterangan</p>
                <ul class="list-disc list-inside text-black">
                    <li>Untuk melihat rekap presensi dalam bentuk file PDF</li>
                </ul>
            </div>

            <!-- Pencarian -->
            <div class="flex justify-end items-center mb-4">
                <div class="relative">
                    <form method="GET" action="{{ route('admin.attendance') }}" class="relative">
                        <input type="text" name="search" placeholder="Cari course..." aria-label="Search"
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

            <!-- Tabel Kehadiran -->
            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="text-left p-2">Kelompok</th>
                            <th class="text-left p-2">Jumlah Peserta</th>
                            <th class="text-left p-2">Mentor</th>
                            <th class="text-left p-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($courses as $course)
                        <tr>
                            <td class="text-left p-2">{{ $course['name'] }}</td>
                            <td class="text-left p-2">{{ $course['participants_count'] }}</td>
                            <td class="text-left p-2">{{ $course['mentor_name'] }}</td>
                            <td class="text-left p-2">
                                <a href="{{route('admin.attendance.pdf', $course['id'])}}">
                                    <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                                        aria-label="Download Data">
                                        Download
                                    </button>
                            </td>
                            </a>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center p-4">Tidak ada data kehadiran mentoring tersedia.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>
<script>
</script>
@endsection