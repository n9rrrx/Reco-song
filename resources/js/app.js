import './bootstrap';
import AudioRecorder from './recorder';

window.recorder = new AudioRecorder();
console.log("🚀 Reco-song Ready");

let statusInterval;
let isRecording = false;
// Visualizer Variables
let audioContext, analyser, source, animationId;

const trendyMessages = [
    "Hold up, let me cook... 🍳",
    "Passing the vibe check... ✅",
    "Main character energy incoming... ✨",
    "Bet you heard this on TikTok... 📱",
    "Manifesting the lyrics... 🕯️",
    "Listening respectfully... 👁️👄👁️",
    "Entering my villain era... 😈",
    "Absolute cinema... ✋🙂‍↕️",
    "Slay loading... 💅"
];

// --- 1. VISUALIZER ENGINE ---
async function startVisualizer() {
    const stream = await navigator.mediaDevices.getUserMedia({ audio: true, video: false });

    audioContext = new (window.AudioContext || window.webkitAudioContext)();
    analyser = audioContext.createAnalyser();
    source = audioContext.createMediaStreamSource(stream);

    source.connect(analyser);
    analyser.fftSize = 256; // Controls bar count (256 = 128 bars)

    const bufferLength = analyser.frequencyBinCount;
    const dataArray = new Uint8Array(bufferLength);
    const canvas = document.getElementById("visualizer");
    const ctx = canvas.getContext("2d");

    // High-DPI Fix
    canvas.width = 600;
    canvas.height = 600;

    function renderFrame() {
        if (!isRecording) {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            return;
        }

        animationId = requestAnimationFrame(renderFrame);
        analyser.getByteFrequencyData(dataArray);

        ctx.clearRect(0, 0, canvas.width, canvas.height);

        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;
        const radius = 110; // Start bars outside the button (Button is ~100px radius)
        const bars = 60;    // Number of bars
        const step = (Math.PI * 2) / bars;

        for (let i = 0; i < bars; i++) {
            const barHeight = (dataArray[i] / 255) * 100; // Scale height

            // Calculate position on circle
            const angle = i * step;
            const x = centerX + Math.cos(angle) * radius;
            const y = centerY + Math.sin(angle) * radius;

            // Draw Bar
            ctx.save();
            ctx.translate(x, y);
            ctx.rotate(angle); // Rotate to point outward

            // Gradient Color (Sky Blue to Cyan)
            const gradient = ctx.createLinearGradient(0, 0, barHeight, 0);
            gradient.addColorStop(0, "rgba(6, 182, 212, 0.8)"); // Cyan
            gradient.addColorStop(1, "rgba(99, 102, 241, 0)");  // Fade out

            ctx.fillStyle = gradient;
            ctx.fillRect(0, -3, barHeight, 6); // (x, y, width, thickness)

            // Add a small "cap" dot at the end
            ctx.fillStyle = "rgba(255, 255, 255, 0.8)";
            ctx.fillRect(barHeight, -3, 2, 6);

            ctx.restore();
        }
    }
    renderFrame();
}

function stopVisualizer() {
    if (animationId) cancelAnimationFrame(animationId);
    if (audioContext) audioContext.close();
    // Clear canvas one last time
    const canvas = document.getElementById("visualizer");
    const ctx = canvas.getContext("2d");
    ctx.clearRect(0, 0, canvas.width, canvas.height);
}

// --- 2. TOGGLE LOGIC (UPDATED) ---
window.toggleRecording = async function() {
    const btn = document.getElementById('live-listen-btn');
    const statusText = document.getElementById('status-text');

    if (isRecording) {
        console.log("🛑 Cancelled");
        stopVisualizer(); // Stop graphics
        try { await window.recorder.stop(); } catch(e) {}

        stopVisualsUI(btn);
        isRecording = false;
        statusText.innerText = "Cancelled";
        return;
    }

    isRecording = true;
    try {
        await window.recorder.start();
        startVisualsUI(btn);

        // 🚀 START THE VISUALIZER!
        startVisualizer();

        setTimeout(async () => {
            if (!isRecording) return;

            statusText.innerText = "Bestie, I'm thinking... 🧠";

            try {
                const audioBlob = await window.recorder.stop();
                stopVisualizer(); // Stop graphics before upload

                if (!isRecording) return;
                await sendAudioToServer(audioBlob, btn);
            } catch (err) {
                console.error(err);
                resetApp(btn, "Mic Error 😢");
            }
        }, 5000);

    } catch (err) {
        alert("Please allow microphone access!");
        resetApp(btn, "No Mic Access 🚫");
    }
};

// --- UI HELPERS ---
function startVisualsUI(btn) {
    btn.parentElement.classList.add('listening');
    btn.classList.add('active');
    btn.innerHTML = `<i class="ri-stop-fill" style="font-size: 80px;"></i>`;

    const statusText = document.getElementById('status-text');
    let index = 0;
    statusText.innerText = trendyMessages[0];

    statusInterval = setInterval(() => {
        index = (index + 1) % trendyMessages.length;
        statusText.innerText = trendyMessages[index];
    }, 2000);
}

function stopVisualsUI(btn) {
    btn.parentElement.classList.remove('listening');
    btn.classList.remove('active');
    btn.innerHTML = `<i class="ri-shazam-line" style="font-size: 90px;"></i>`; // Reset Icon Size
    clearInterval(statusInterval);
    document.getElementById('status-text').innerText = "Tap to start listening";
}

// --- 3. UPLOAD & RESET ---
async function sendAudioToServer(audioBlob, btn) {
    if (!isRecording) return;
    const formData = new FormData();
    formData.append('audio', audioBlob, 'recording.wav');

    try {
        const response = await axios.post('/recognize', formData);
        resetApp(btn, "Tap to start listening");

        if (response.data.status === 'success') {
            showResult(response.data.data);
        } else {
            document.getElementById('status-text').innerText = "Flop era. Song not found. 💀";
        }
    } catch (error) {
        resetApp(btn, "Server Error 💀");
    }
}

function resetApp(btn, message) {
    isRecording = false;
    stopVisualizer();
    stopVisualsUI(btn);
    if(message) document.getElementById('status-text').innerText = message;
}

// --- 4. SHOW RESULT ---
function showResult(data) {
    document.getElementById('result-title').innerText = data.title;
    document.getElementById('result-artist').innerText = data.artist;

    const artUrl = data.album_art || "/assets/images/misc/plan.png";
    document.getElementById('result-album-art').src = artUrl;

    const bgDiv = document.querySelector('.result-bg-dynamic');
    if(bgDiv) bgDiv.style.backgroundImage = `url('${artUrl}')`;

    const btnSpotify = document.getElementById('btn-spotify');
    const btnYoutube = document.getElementById('btn-youtube');

    if(data.spotify_link) {
        btnSpotify.href = data.spotify_link;
        btnSpotify.style.display = 'flex';
    } else {
        btnSpotify.style.display = 'none';
    }

    if(data.youtube_link) {
        btnYoutube.href = data.youtube_link;
        btnYoutube.style.display = 'flex';
    } else {
        btnYoutube.style.display = 'none';
    }

    document.getElementById('result-overlay').style.display = 'flex';
}

window.closeResult = function() {
    document.getElementById('result-overlay').style.display = 'none';
    document.querySelector('.result-bg-dynamic').style.backgroundImage = 'none';
};
