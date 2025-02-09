@extends('layouts.app')

@section('content')

@if (session('message'))
<div class="bg-green-100 text-green-700 p-4 text-center rounded-lg mb-6">
    {{ session('message') }}
</div>
@endif

@if (session('error'))
<div class="bg-red-100 text-red-700 p-4 text-center rounded-lg mb-6">
    {{ session('error') }}
</div>
@endif

<div class="container mx-auto p-4">
    <!-- Header Section -->
    <div class="text-left mb-8">
        <h1 class="text-3xl font-bold mb-4"> {{ $attendance->title }}</h1>
    </div>

    <!-- Mentoring Section -->
    <div class="bg-white shadow rounded-lg p-6 mb-8" x-data="attendanceManager()">
        <!-- Navigation Tabs -->
        <div class="flex justify-between items-center border-b pb-4 mb-4">
            <div class="flex gap-4">
                <button class="px-4 py-2 text-blue-600 font-medium border-b-2 border-blue-600">This course</button>
            </div>
            <div class="flex gap-4">
                <button class="px-4 py-2 text-blue-600 font-bold border-b-2 border-blue-600 ">Today</button>
            </div>
        </div>
        <button class="px-4 py-2 text-blue-600 font-bold bg-blue-100 rounded-md mb-6">{!! nl2br(e($attendanceDetails)) !!}</button>

        <!-- Table -->
        <table class="w-full text-left border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-4 border border-gray-200">Date</th>
                    <th class="p-4 border border-gray-200">Description</th>
                    <th class="p-4 border border-gray-200">Status</th>
                    <th class="p-4 border border-gray-200">Remarks</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="p-4 border border-gray-200">{!! nl2br(e($attendanceDetails)) !!}</td>
                    <td class="p-4 border border-gray-200">{{ $attendance->title }}</td>
                    <td class="p-4 border border-gray-200 capitalize {{ $status === null ? 'text-center' : 'text-left' }}">
                        {{ $status === null ? '?' : $status }}
                    </td>
                    <td class="p-4 border border-gray-200 text-600">
                        @if($status === null)
                        <a @click.prevent="showModal = true" class="underline cursor-pointer text-blue-600">Submit attendance</a>
                        @else
                        Self-record
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>


        <!-- Modal Attendance Selection -->
        <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center" style="display: none;" x-transition>
            <div class="bg-white p-6 rounded shadow-md w-1/3">
                <h2 class="text-lg font-semibold mb-4">Pilih Kehadiran</h2>
                <form id="attendanceForm" method="POST" action="{{ route('presence.store') }}">
                    @csrf
                    <input type="hidden" name="attendance_id" value="{{ $attendance->attendance_id }}">
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                    <div class="flex flex-col gap-2">
                        <label class="flex items-center">
                            <input type="radio" name="status" value="hadir" x-model="tempStatus" class="mr-2"> Hadir
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="status" value="izin" x-model="tempStatus" class="mr-2"> Izin
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="status" value="tidak hadir" x-model="tempStatus" class="mr-2"> Tidak Hadir
                        </label>
                    </div>

                    <div class="flex justify-end mt-4">
                        <button type="button" class="bg-gray-300 text-black px-4 py-2 rounded mr-2" @click="showModal = false">Batal</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function attendanceManager() {
        return {
            showModal: false,
            selectedStatus: null,
            tempStatus: null,
            confirmAttendance() {
                if (this.tempStatus) {
                    this.selectedStatus = this.tempStatus;
                    this.showModal = false;

                    // Submit form secara otomatis
                    setTimeout(() => {
                        document.getElementById('attendanceForm').submit();
                    }, 500);
                }
            }
        };
    }
</script>
@endsection