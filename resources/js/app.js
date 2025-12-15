import './bootstrap';

// 👇 KEEP THIS COMMENTED OUT FOR NOW
// We want to make sure the recorder works without template interference first.

import AudioRecorder from './recorder';

// 1. Setup Global Recorder
window.recorder = new AudioRecorder();

console.log("🚀 App.js Loaded - Recorder Ready");

// 2. Define the Global Function (Triggered by onclick)
window.startRecording = async function() {
    const btn = document.getElementById('live-listen-btn');
    const originalText = btn.innerHTML;

    console.log("🖱️ Button Clicked via onclick!");

    // UI Update
    btn.innerHTML = 'Listening...';
    btn.classList.remove('btn-danger');
    btn.classList.add('btn-warning');

    try {
        // Start Mic
        await window.recorder.start();
        console.log("🎙️ Recording Started...");

        // Stop after 5 seconds
        setTimeout(async () => {
            btn.innerHTML = 'Identifying...';

            const audioBlob = await window.recorder.stop();
            console.log("⏹️ Recording Stopped. Size:", audioBlob.size);

            // Upload
            await sendAudioToServer(audioBlob);

            // Reset UI
            btn.innerHTML = originalText;
            btn.classList.remove('btn-warning');
            btn.classList.add('btn-danger');
        }, 5000);

    } catch (err) {
        alert("Microphone Error: " + err);
        console.error(err);
        btn.innerHTML = originalText;
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

// 4. Show Result Function
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
        card.style.display = 'block';
        card.scrollIntoView({ behavior: 'smooth' });
    }
}

// 5. Global Reset Function
window.resetRecognition = function() {
    const card = document.getElementById('recognition-result-card');
    if (card) card.style.display = 'none';
}
