@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <!-- Header Section -->
    <div class="text-left mb-8">
        <h1 class="text-3xl font-bold mb-4">Logbook</h1>
    </div>

    <!-- logbook Section -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <div class=" items-center gap-4 bg-[#cce6ea] p-8 rounded-lg border border-gray-200" >
            <!-- logbook Content -->
                <div class="flex items-center gap-4">
                    <p class="text-black font-bold text-2xl">Bagian ini hanya untuk mentor</p>
                </div>
                <button type="button" class="mt-4 bg-[#4976E7] text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    Kembali Ke Dashboard
                </button>
        </div>
    </div>
</div>
@endsection