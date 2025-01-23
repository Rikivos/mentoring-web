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

            <!-- Accordion Item 1 -->
            <h2 id="accordion-color-heading-1">
                <button type="button"
                    class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-800 dark:border-gray-700 dark:text-gray-400 hover:bg-blue-100 dark:hover:bg-gray-800 gap-3"
                    data-accordion-target="#accordion-color-body-1" aria-expanded="true"
                    aria-controls="accordion-color-body-1">
                    <span>Kelompok 1</span>
                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5 5 1 1 5" />
                    </svg>
                </button>
            </h2>
            <div id="accordion-color-body-1" class="hidden" aria-labelledby="accordion-color-heading-1">
                <div class="bg-white p-6 container mx-auto space-y-6">
                    @for ($i = 0; $i < 3; $i++)
                        @include('components.activity-card', ['index'=> $i])
                        @if ($i < 2)
                            <hr class="my-6 border-b border-gray-300">
                            @endif
                            @endfor
                </div>
            </div>

            <!-- Accordion Item 2 -->
            <h2 id="accordion-color-heading-2">
                <button type="button"
                    class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-800 dark:border-gray-700 dark:text-gray-400 hover:bg-blue-100 dark:hover:bg-gray-800 gap-3"
                    data-accordion-target="#accordion-color-body-2" aria-expanded="false"
                    aria-controls="accordion-color-body-2">
                    <span>Kelompok 2</span>
                    <svg data-accordion-icon class="w-3 h-3 shrink-0" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5 5 1 1 5" />
                    </svg>
                </button>
            </h2>
            <div id="accordion-color-body-2" class="hidden" aria-labelledby="accordion-color-heading-2">
                <div class="bg-white p-6 container mx-auto space-y-6">
                    @for ($i = 0; $i < 3; $i++)
                        @include('components.activity-card', ['index'=> $i])
                        @if ($i < 2)
                            <hr class="my-6 border-b border-gray-300">
                            @endif
                            @endfor
                </div>
            </div>

            <!-- Accordion Item 3 -->
            <h2 id="accordion-color-heading-3">
                <button type="button"
                    class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-gray-200 focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-800 dark:border-gray-700 dark:text-gray-400 hover:bg-blue-100 dark:hover:bg-gray-800 gap-3"
                    data-accordion-target="#accordion-color-body-3" aria-expanded="false"
                    aria-controls="accordion-color-body-3">
                    <span>What are the differences between Flowbite and Tailwind UI?</span>
                    <svg data-accordion-icon class="w-3 h-3 shrink-0" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5 5 1 1 5" />
                    </svg>
                </button>
            </h2>
            <div id="accordion-color-body-3" class="hidden" aria-labelledby="accordion-color-heading-3">
                <div class="p-5 border border-t-0 border-gray-200 dark:border-gray-700">
                    <p class="mb-2 text-gray-500 dark:text-gray-400">The main difference is that the core components
                        from Flowbite are open source under the MIT license, whereas Tailwind UI is a paid product...</p>
                    <ul class="list-disc ps-5 text-gray-500 dark:text-gray-400">
                        <li><a href="https://flowbite.com/pro/" class="text-blue-600 hover:underline">Flowbite Pro</a></li>
                        <li><a href="https://tailwindui.com/" class="text-blue-600 hover:underline">Tailwind UI</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection