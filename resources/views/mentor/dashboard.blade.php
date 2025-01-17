@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <!-- Heading -->
        <div class="p-6 mb-6">
            <h1 class="text-2xl font-bold">Hi 👋</h1>
        </div>

        <!-- Timeline Section -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Timeline</h2>
            <div class="flex justify-center items-center h-40 bg-white rounded-lg outline outline-2 outline-gray-200">
                <img src="/images/timeline.svg" alt="No Activities" class="w-16 h-16">
                <p class="text-gray-500 mt-2">No activities require action</p>
            </div>

                <h2 class="text-xl font-semibold mb-4 mt-4">Calendar</h2>
                <div class="grid grid-cols-4 gap-4 p-4 bg-white rounded-lg shadow-md outline outline-2 outline-gray-200">
                    
                    <!-- Sidebar Agenda -->
                    <div class="col-span-1 p-4 bg-white">
                        <!-- Mini Calendar -->
                        <div id="mini-calendar" class="mb-4"></div>
        
                        <!-- Agenda & Tugas -->
                        <div class="flex items-center mb-4">
                            <img src="/images/agenda.png" alt="Agenda & Tugas" class="w-8 h-8 mr-4">
                            <h3 class="text-lg font-bold">Agenda & Tugas</h3>
                        </div>
                        <ul id="agenda-list">
        
                        </ul>
                    </div>
        
                    <!-- Kalender Utama -->
                    <div class="col-span-3">
                        <div class="flex justify-between items-center p-4 bg-gray-800 text-white rounded-lg mb-4">
                            <button id="btn-tambah-event" class="bg-blue-500 p-2 rounded-lg hover:bg-blue-600">Tambah Event</button>
                            <span class="text-lg font-semibold">Event</span>
                        </div>
                        <div id="main-calendar"></div>
                    </div>
                </div>
        </div>
</div>

<!-- Modal Pop-up -->
<div id="modal-tambah-event" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
        <h3 class="text-xl font-bold mb-4">Tambah Event</h3>
        <form id="form-tambah-event">
            <div class="mb-4">
                <label for="event-title" class="block text-gray-700 font-semibold">Judul Event</label>
                <input id="event-title" type="text" class="w-full border rounded-lg p-2">
            </div>
            <div class="mb-4">
                <label for="event-start" class="block text-gray-700 font-semibold">Tanggal Mulai</label>
                <input id="event-start" type="datetime-local" class="w-full border rounded-lg p-2">
            </div>
            <div class="mb-4">
                <label for="event-end" class="block text-gray-700 font-semibold">Tanggal Selesai</label>
                <input id="event-end" type="datetime-local" class="w-full border rounded-lg p-2">
            </div>
            <div class="flex justify-end">
                <button type="button" id="btn-close-modal" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2">Batal</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Mini Calendar
        const miniCalendarEl = document.getElementById("mini-calendar");
        const miniCalendar = new FullCalendar.Calendar(miniCalendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: false,
            fixedWeekCount: false,
            dayMaxEvents: false,
            height: 'auto',
            events: [
                { title: 'Daily Standup', start: '2024-01-02' },
            ],
        });
        miniCalendar.render();

        // Kalender Utama
        const mainCalendarEl = document.getElementById("main-calendar");
        const mainCalendar = new FullCalendar.Calendar(mainCalendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            editable: true,
            events: [
                { title: 'Daily Standup', start: '2024-01-02T08:00:00' },
                { title: 'Budget Review', start: '2024-01-04T09:00:00' },
            ],
        });

        mainCalendar.render();

        // Elemen untuk modal dan agenda
        const btnTambahEvent = document.getElementById("btn-tambah-event");
        const modalTambahEvent = document.getElementById("modal-tambah-event");
        const btnCloseModal = document.getElementById("btn-close-modal");
        const formTambahEvent = document.getElementById("form-tambah-event");
        const agendaList = document.getElementById("agenda-list");

        // Tampilkan modal saat tombol "Tambah Event" ditekan
        btnTambahEvent.addEventListener("click", () => {
            modalTambahEvent.classList.remove("hidden");
        });

        // Sembunyikan modal saat tombol "Batal" ditekan
        btnCloseModal.addEventListener("click", () => {
            modalTambahEvent.classList.add("hidden");
        });

        // Tambahkan event baru ke kalender dan agenda saat form disubmit
        formTambahEvent.addEventListener("submit", (e) => {
            e.preventDefault(); // Cegah reload halaman

            // Ambil data dari input
            const title = document.getElementById("event-title").value;
            const start = document.getElementById("event-start").value;

            if (!title || !start) {
                alert("Judul dan tanggal mulai harus diisi!");
                return;
            }

            // Tambahkan event ke kalender utama
            mainCalendar.addEvent({
                title: title,
                start: start,
            });

            // Tambahkan event ke daftar agenda
            const startDate = new Date(start).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const newAgendaItem = `<li class="text-blue-500">● ${title} <span class="float-right">${startDate}</span></li>`;
            agendaList.insertAdjacentHTML('beforeend', newAgendaItem);

            // Reset form dan sembunyikan modal
            formTambahEvent.reset();
            modalTambahEvent.classList.add("hidden");
        });
    });
</script>
@endsection
