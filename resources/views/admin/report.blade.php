@extends('layouts.admin')

@section('title', 'Report')

@section('content')
<div class="flex bg-gray-200">
    <!-- Sidebar -->
    @include('components.sidebar')


    <!-- Dashboard Content -->
    <div class="w-2/3 container mx-auto p-4">
        <!-- Accordion Component -->
        <div id="accordion-color" data-accordion="collapse"
            data-active-classes="bg-blue-100 dark:bg-gray-800 text-blue-600 dark:text-white">

            <!-- Accordion Item -->
            @foreach ($courses as $course)
            <h2 id="accordion-color-heading-{{ $course->course_id }}">
                <button type="button"
                    class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-800 dark:border-gray-700 dark:text-gray-400 hover:bg-blue-100 dark:hover:bg-gray-800 gap-3"
                    data-accordion-target="#accordion-color-body-{{ $course->course_id }}" aria-expanded="true"
                    aria-controls="accordion-color-body-{{ $course->course_id }}">
                    <span>{{ $course->course_title }}</span>
                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5 5 1 1 5" />
                    </svg>
                </button>
            </h2>
            <div id="accordion-color-body-{{ $course->course_id }}" class="hidden" aria-labelledby="accordion-color-heading-1">
                <div class="bg-white p-6 container mx-auto space-y-6">
                    @forelse ($course->reports as $report)
                    <div class="flex overflow-hidden">
                        <div class="w-1/2 p-4">
                            <button id="openEdit"
                                class="px-4 py-2 bg-yellow-500 text-white text-sm font-semibold rounded hover:bg-yellow-600">
                                Edit
                            </button>
                            <img src="/images/logbook.svg" alt="Activity Image" class="w-full h-auto object-cover rounded mt-4">
                        </div>
                        <div class="w-2/3 p-6 mt-10">
                            <div class="border border-gray-300 overflow-hidden bg-gray-200">
                                <div class="grid grid-cols-3 gap-4 p-4 border-b border-gray-500">
                                    <div class="col-span-1 text-gray-600 font-medium">Kegiatan</div>
                                    <div class="col-span-2">{{ $report->report_name }}</div>
                                </div>
                                <div class="grid grid-cols-3 gap-4 p-4 border-b border-gray-500">
                                    <div class="col-span-1 text-gray-600 font-medium">Tanggal</div>
                                    <div class="col-span-2">{{ $report->upload_date }}</div>
                                </div>
                                <div class="grid grid-cols-3 gap-4 p-4 border-b border-gray-500">
                                    <div class="col-span-1 text-gray-600 font-medium">Waktu</div>
                                    <div class="col-span-2">{{ $report->start_time }} - {{ $report->end_time }}</div>
                                </div>
                                <div class="grid grid-cols-3 gap-4 p-4 border-b border-gray-500">
                                    <div class="col-span-1 text-gray-600 font-medium">Persetujuan</div>
                                    <div class="col-span-2">
                                        <span class="border-b border-gray-400 px-3 py-1 text-sm font-semibold rounded-full
    @if ($report->status == 'approved') bg-green-100 text-green-600
    @elseif ($report->status == 'rejected') bg-red-100 text-red-600
    @else bg-yellow-100 text-yellow-600 @endif">
                                            @if ($report->status == 'approved')
                                            Disetujui
                                            @elseif ($report->status == 'rejected')
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
                                        {{ $report->description }}
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 gap-4 p-4 border-t border-gray-500
                                @if ($report->comment == '') hidden @endif">
                                    <div class="col-span-1 text-gray-600 font-medium">komentar</div>
                                    <div class="col-span-2 text-gray-700">
                                        {{ $report->comment }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500">No reports available for this course.</p>
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection