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
        <!-- General Section -->
        <div class="accordion-item outline outline-2 outline-gray-200 rounded-lg mb-6">
            <h2 class="accordion-header">
                <button class="accordion-button w-full text-left bg-white p-4 flex items-center focus:outline-none"
                    type="button" data-target="#general">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="31" viewBox="0 0 30 31"
                        fill="none" class="accordion-icon transition-transform duration-300">
                        <circle cx="15" cy="15.5" r="14" stroke="black" stroke-width="1.5" />
                        <path d="M11 6 L19 15.5 L11 25" stroke="black" stroke-width="1.875" stroke-linecap="round"
                            stroke-linejoin="round" class="arrow-path" />
                    </svg>
                    <span class="text-lg font-semibold ml-2">General</span>
                </button>
            </h2>
            <div id="general" class="accordion-collapse hidden">
                <div class="accordion-body p-4">
                    <p>Konten sesi 2 akan ditambahkan di sini.</p>
                </div>
            </div>
        </div>

        <!-- Sesi 1 -->
        <div class="accordion-item outline-2 outline outline-gray-200 rounded-lg mb-6">
            <h2 class="accordion-header">
                <button class="accordion-button w-full text-left bg-white p-4 flex items-center focus:outline-none"
                    type="button" data-target="#sesi1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="31" viewBox="0 0 30 31"
                        fill="none" class="accordion-icon transition-transform duration-300">
                        <circle cx="15" cy="15.5" r="14" stroke="black" stroke-width="1.5" />
                        <path d="M11 6 L19 15.5 L11 25" stroke="black" stroke-width="1.875" stroke-linecap="round"
                            stroke-linejoin="round" class="arrow-path" />
                    </svg>
                    <span class="text-lg font-semibold ml-2">Sesi 1</span>
                </button>
            </h2>
            <div id="sesi1" class="accordion-collapse hidden">
                <div class="accordion-body p-4">
                    <p class="mb-4">
                        Assalamu'alaikum Wr. Wb.<br>
                        Salam sehat dan bahagia selalu...<br><br>
                        Mata kuliah Kewirausahaan Berbasis Program Studi pertemuan pertama akan membahas kontrak
                        perkuliahan dan Pemahaman Dasar Kewirausahaan.
                    </p>
                    <p class="mb-4">
                        Pada pertemuan ini juga akan membahas Teori dan Konsep Kewirausahaan: Definisi, karakteristik
                        wirausahawan, dan peran kewirausahaan dalam perekonomian, Sejarah dan Perkembangan
                        Kewirausahaan: Evolusi kewirausahaan dalam industri teknologi, Tren Kewirausahaan Teknologi:
                        Studi kasus startup teknologi di Indonesia dan global.
                    </p>
                    <p class="mb-4">
                        Silakan unduh dokumen kontrak perkuliahan dan materi Pemahaman Dasar Kewirausahaan...!
                    </p>
                    <div class="mt-4">
                        <a href="#" class="flex items-center gap-2 text-blue-500 hover:underline">
                            <img src="/images/pdf-icon.svg" alt="PDF Icon" class="w-5 h-5">
                            Tugas Sesi 1 - Kumpulkan tugas dalam bentuk PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sesi 2 -->
        <div class="accordion-item outline-2 outline outline-gray-200 rounded-lg mb-6">
            <h2 class="accordion-header">
                <button class="accordion-button w-full text-left bg-white p-4 flex items-center focus:outline-none"
                    type="button" data-target="#sesi2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="31" viewBox="0 0 30 31"
                        fill="none" class="accordion-icon transition-transform duration-300">
                        <circle cx="15" cy="15.5" r="14" stroke="black" stroke-width="1.5" />
                        <path d="M11 6 L19 15.5 L11 25" stroke="black" stroke-width="1.875" stroke-linecap="round"
                            stroke-linejoin="round" class="arrow-path" />
                    </svg>
                    <span class="text-lg font-semibold ml-2">Sesi 2</span>
                </button>
            </h2>
            <div id="sesi2" class="accordion-collapse hidden">
                <div class="accordion-body p-4">
                    <p>Konten sesi 2 akan ditambahkan di sini.</p>
                </div>
            </div>
        </div>
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