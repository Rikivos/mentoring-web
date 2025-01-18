import "./bootstrap";
import 'flowbite';
import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";

// Script to Handle Dropdown From Navbar
document.addEventListener('DOMContentLoaded', () => {
    const profileButton = document.querySelector('.profile-button');
    const dropdownMenu = document.querySelector('.dropdown-menu');

    profileButton.addEventListener('click', () => {
        dropdownMenu.classList.toggle('hidden');
    });
});


// Script to Handle Calendar From dashboard


// Script to Handle Modal From logbook
// Modal Handling
const modal = document.getElementById('modal');
const openModalButton = document.getElementById('openModal');
const closeModalButton = document.getElementById('closeModal');

openModalButton.onclick = () => modal.classList.remove('hidden');
closeModalButton.onclick = () => modal.classList.add('hidden');

// Camera Handling
const openCameraButton = document.getElementById('openCameraButton');
const cameraContainer = document.getElementById('cameraContainer');
const video = cameraContainer.querySelector('video');
const canvas = cameraContainer.querySelector('canvas');
const screenshotImage = document.getElementById('screenshotImage');
const screenshotPreview = document.querySelector('.screenshot-preview');
const [playButton, pauseButton, screenshotButton] = cameraContainer.querySelectorAll('button');

let streamStarted = false;

const constraints = { video: { width: { ideal: 1280 }, height: { ideal: 720 } } };

const startStream = (stream) => {
    video.srcObject = stream;
    streamStarted = true;
    playButton.classList.toggle('hidden', true);
    pauseButton.classList.toggle('hidden', false);
    screenshotButton.classList.toggle('hidden', false);
    openCameraButton.textContent = 'Tutup Kamera';
};

const stopStream = () => {
    video.srcObject?.getTracks().forEach(track => track.stop());
    video.srcObject = null;
    streamStarted = false;
    openCameraButton.textContent = 'Buka Kamera';
};

openCameraButton.onclick = async () => {
    if (streamStarted) {
        stopStream();
        cameraContainer.classList.add('hidden');
    } else {
        try {
            const stream = await navigator.mediaDevices.getUserMedia(constraints);
            startStream(stream);
            cameraContainer.classList.remove('hidden');
        } catch (error) {
            alert('Tidak dapat mengakses kamera.');
        }
    }
};

playButton.onclick = video.play.bind(video);
pauseButton.onclick = video.pause.bind(video);

pauseButton.onclick = video.pause.bind(video) || (() => {
    pauseButton.classList.add('hidden');
    playButton.classList.remove('hidden');
});

screenshotButton.onclick = () => {
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

    const imageURL = canvas.toDataURL('image/jpeg');
    screenshotImage.src = imageURL;
    screenshotPreview.style.display = 'block';

    document.getElementById('screenshotImage').value = imageURL;
};


// Script to Handle Delete Screenshot
const deleteScreenshotButton = document.getElementById('deleteScreenshotButton');
deleteScreenshotButton.onclick = () => {
    // Delete Image from form
    screenshotImage.src = '';
    screenshotPreview.style.display = 'none';
    document.getElementById('screenshotImage').value = '';
};
