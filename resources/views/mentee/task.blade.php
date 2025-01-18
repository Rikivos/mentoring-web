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
                    <strong>Opened:</strong> Monday, 13 January 2025, 12:00 PM<br>
                    <strong>Due:</strong> Thursday, 15 January 2025, 12:00 AM
                </p>
            </div>

            <p class="mt-4 text-sm text-gray-700">Unggah tugas sesi 1 dalam bentuk .pdf</p>
            <button type="button"  class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mt-4">
                Add Submission
            </button>

            <div class="mt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Submission Status</h2>
                <table class="table-auto w-full text-sm text-left text-gray-700 border-collapse">
                    <tbody>
                        <tr class="border-b">
                            <td class="py-2 px-4 font-medium text-gray-900">Submission status</td>
                            <td class="py-2 px-4">No submission has been made yet</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2 px-4 font-medium text-gray-900">Grading status</td>
                            <td class="py-2 px-4">Not graded</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 font-medium text-gray-900">Time remaining</td>
                            <td class="py-2 px-4">12 hour 7 minute remaining</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
