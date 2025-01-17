@extends('layouts.app')

@section('content')
@if (session('message'))
<div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">
    {{ session('message') }}
</div>
@endif

@if (session('error'))
<div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
    {{ session('error') }}
</div>
@endif

<div class="bg-gray-100 min-h-screen py-8">
    <div class="container mx-auto">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Logbook</h1>
            <button id="openModal" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-500">
                Tambah Kegiatan
            </button>
        </div>

        <!-- Outer Activity Container -->
        <div class="bg-white p-6 container mx-auto">
            <!-- Activity Cards -->
            <div class="space-y-6">
                @for ($i = 0; $i < 3; $i++)
                    <div class="flex overflow-hidden ">
                    <!-- Image Section -->
                    <div class="w-1/2 p-4">
                        <button id="openEdit"
                            class="px-4 py-2 bg-yellow-500 text-white text-sm font-semibold rounded hover:bg-yellow-600">
                            Edit
                        </button>
                        <button
                            class="px-4 py-2 bg-red-500 text-white text-sm font-semibold rounded hover:bg-red-600">
                            Hapus
                        </button>
                        <img src="/images/logbook.svg" alt="Activity Image"
                            class="w-full h-auto object-cover rounded mt-4">

                    </div>

                    <!-- Content Section -->
                    <div class="w-2/3 p-6 mt-10">
                        <div class="border border-gray-300  overflow-hidden bg-gray-200">
                            <div class="grid grid-cols-3 gap-4 p-4 border-b border-gray-500">
                                <div class="col-span-1 text-gray-600 font-medium">Kegiatan</div>
                                <div class="col-span-2">Sejarah Muhammadiyah</div>
                            </div>
                            <div class="grid grid-cols-3 gap-4 p-4 border-b border-gray-500">
                                <div class="col-span-1 text-gray-600 font-medium">Tanggal</div>
                                <div class="col-span-2">27 Maret 2023</div>
                            </div>
                            <div class="grid grid-cols-3 gap-4 p-4 border-b border-gray-500">
                                <div class="col-span-1 text-gray-600 font-medium">Waktu</div>
                                <div class="col-span-2">10:00 - 12:00</div>
                            </div>
                            <div class="grid grid-cols-3 gap-4 p-4 border-b border-gray-500">
                                <div class="col-span-1 text-gray-600 font-medium">Persetujuan</div>
                                <div class="col-span-2">
                                    <span
                                        class=" border-b border-gray-400 px-3 py-1 text-sm font-semibold rounded-full
                                                @if ($i == 0) bg-green-100 text-green-600
                                                @elseif ($i == 1) bg-red-100 text-red-600
                                                @else bg-yellow-100 text-yellow-600 @endif">
                                        @if ($i == 0)
                                        Disetujui
                                        @elseif ($i == 1)
                                        Ditolak
                                        @else
                                        Proses
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-4 p-4">
                                <div class="col-span-1 text-gray-600 font-medium">Uraian Kegiatan</div>
                                <div class="col-span-2 text-gray-700">
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque sed elit eu
                                    augue tincidunt pharetra nec vel ligula.
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque sed elit eu
                                    augue tincidunt pharetra nec vel ligula.
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque sed elit eu
                                    augue tincidunt pharetra nec vel ligula.
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque sed elit eu
                                    augue tincidunt pharetra nec vel ligula.
                                </div>
                            </div>
                        </div>
                    </div>
            </div>

            <!-- Add separator -->
            @if ($i < 4)
                <hr class="my-6 border-b border-gray-300">
                @endif
                @endfor
        </div>
    </div>
</div>


<!-- Modal -->
<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg w-1/2 shadow-lg p-6 overflow-y-auto max-h-[90vh]">
        <h2 class="text-xl font-bold mb-4">Tambahkan Kegiatan Mentoring</h2>

        <form action="{{ route('logbook.add') }}" method="POST">
            <!-- Nama Kegiatan -->
            @csrf
            <div class="mb-4">
                <label for="report_name" class="block text-sm font-medium mb-1">Nama Kegiatan</label>
                <input type="text" id="report_name" name="report_name"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Tanggal -->
            <div class="mb-4">
                <label for="upload_date" class="block text-sm font-medium mb-1">Tanggal</label>
                <input type="date" id="upload_date" name="upload_date"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Waktu -->
            <div class="mb-4">
                <label for="waktu" class="block text-sm font-medium mb-1">Waktu</label>
                <div class="flex items-center space-x-2">
                    <input name="start_time" type="time" class="w-1/2 border rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
                    <span>s/d</span>
                    <input name="end_time" type="time" class="w-1/2 border rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Uraian Mentoring -->
            <div class="mb-4">
                <label for="uraian" class="block text-sm font-medium mb-1">Uraian Mentoring</label>
                <textarea name="description" id="description" rows="4" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <!-- Media Kamera -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Media Kamera</label>
                <button type="button" id="openCameraButton"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    Buka Kamera
                </button>
                <div id="cameraContainer" class="mt-4 hidden">
                    <video autoplay></video>
                    <canvas class="hidden"></canvas>
                    <div class="controls">
                        <button class="play">Play</button>
                        <button class="pause hidden"></button>
                        <button class="screenshot hidden bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 mt-2" type="button">Foto</button>
                    </div>
                </div>
            </div>
            <!-- Screenshot Preview -->
            <div class="screenshot-preview" style="display: none;">
                <h3 class="text-sm font-medium mb-2">Gambar Foto</h3>
                <img id="screenshotImage" alt="Screenshot" />
                <button id="deleteScreenshotButton" type="button" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 mt-2">
                    Hapus Foto
                </button>
                <input type="hidden" id="screenshotImage" name="image">
            </div>
            <!-- Buttons -->
            <div class="flex justify-end space-x-2">
                <button type="button" id="closeModal"
                    class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                    Batal
                </button>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                    Selesai
                </button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection