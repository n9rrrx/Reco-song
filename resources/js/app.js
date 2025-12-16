import './bootstrap';

// 👇 UNCOMMENT THIS LINE if you haven't already moved it to the HTML footer
// (If you followed the "Move to Public" step, keep it commented/deleted)
// import './scripts.bundle.js';

import AudioRecorder from './recorder';

// 1. Setup Global Recorder
window.recorder = new AudioRecorder();

console.log("🚀 App.js Loaded - Recorder Ready");

// 2. Define the Global Function (Triggered by onclick)
window.startRecording = async function() {
    const btn = document.getElementById('live-listen-btn');

    // Save original content (icon + text) so we can restore it later
    const originalContent = btn.innerHTML;

    console.log("🖱️ Button Clicked via onclick!");

    // --- UI STATE: LISTENING ---
    // Change text to spinner and add the red pulse animation
    btn.innerHTML = '<i class="ri-loader-4-line spin align-middle fs-2"></i> <span>Listening...</span>';
    btn.classList.add('listening');

    // (Optional) Disable button so user doesn't click twice
    btn.style.pointerEvents = 'none';

    try {
        // Start Mic
        await window.recorder.start();
        console.log("🎙️ Recording Started...");

        // Stop after 5 seconds
        setTimeout(async () => {

            // --- UI STATE: ANALYZING ---
            btn.innerHTML = '<i class="ri-magic-line align-middle fs-2"></i> <span>Analyzing...</span>';

            const audioBlob = await window.recorder.stop();
            console.log("⏹️ Recording Stopped. Size:", audioBlob.size);

            // Upload
            await sendAudioToServer(audioBlob);

            // --- UI STATE: RESET ---
            btn.innerHTML = originalContent;
            btn.classList.remove('listening');
            btn.style.pointerEvents = 'auto'; // Re-enable clicking

        }, 5000);

    } catch (err) {
        alert("Microphone Error: " + err);
        console.error(err);

        // Reset on error
        btn.innerHTML = originalContent;
        btn.classList.remove('listening');
        btn.style.pointerEvents = 'auto';
    }
};

// 3. Upload Function
async function sendAudioToServer(audioBlob) {
    const formData = new FormData();
    formData.append('audio', audioBlob, 'recording.wav');

    try {
        console.log("📤 Uploading...");
        const response = await axios.post('/recognize', formData);

        console.log("✅ Server Response:", response.data);

        if (response.data.status === 'success') {
            showResult(response.data.data);
        } else {
            alert("Song not found.");
        }
    } catch (error) {
        console.error("❌ Upload Error:", error);
        alert("Server Error. Check Console.");
    }
}

// 4. Show Result Function (Updated for Glass Card)
function showResult(data) {
    const card = document.getElementById('recognition-result-card');
    const title = document.getElementById('result-title');
    const artist = document.getElementById('result-artist');
    const art = document.getElementById('result-album-art');

    if (title) title.innerText = data.title;
    if (artist) artist.innerText = data.artist;

    // Fix image path
    if (art) {
        art.src = data.album_art || "/assets/images/misc/plan.png";
    }

    if (card) {
        // Show the card with animation
        card.style.display = 'block';
        card.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}

// 5. Global Reset Function
window.resetRecognition = function() {
    const card = document.getElementById('recognition-result-card');
    if (card) {
        card.style.display = 'none';
    }
}
