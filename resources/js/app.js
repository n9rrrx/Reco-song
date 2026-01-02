import './bootstrap';
import AudioRecorder from './recorder';

window.recorder = new AudioRecorder();
console.log("🚀 RECO SONG - Ready to identify songs");

let statusInterval;
let isRecording = false;
let isProcessing = false; // Track if we're still processing
// Visualizer Variables
let audioContext, analyser, source, animationId, visualizerStream;

const trendyMessages = [
    "Let him cook 👨‍🍳",
    "Lowkey fire 🔥",
    "It's giving ✨",
    "No cap 🧢",
    "Slay mode 💅🏼",
    "We ate 🍴",
    "Main character 👑"
];

const identifyingMessages = [
    "Cooked 🍳",
    "Gyatt 😳",
    "So sigma 🐺",
    "Rizz check 💫",
    "Bussin fr 🔥",
    "Assignment done 📝",
    "Hits different 🎯"
];

function setStatus(message) {
    // Update wave label with fade animation
    const waveLabel = document.querySelector('.wave-label');
    if (waveLabel) {
        waveLabel.classList.add('fade-out');
        setTimeout(() => {
            waveLabel.innerText = message;
            waveLabel.classList.remove('fade-out');
            waveLabel.classList.add('fade-in');
            setTimeout(() => waveLabel.classList.remove('fade-in'), 300);
        }, 150);
    }

    // Update screen label inside phone mockup with fade
    const screenLabel = document.querySelector('.screen-label');
    if (screenLabel) {
        screenLabel.classList.add('fade-out');
        setTimeout(() => {
            screenLabel.innerText = message.toUpperCase();
            screenLabel.classList.remove('fade-out');
            screenLabel.classList.add('fade-in');
            setTimeout(() => screenLabel.classList.remove('fade-in'), 300);
        }, 150);
    }
}

// --- 1. VISUALIZER ENGINE (COMPACT FOR PHONE MOCKUP) ---
async function startVisualizer() {
    // Simple audio request - most compatible
    visualizerStream = await navigator.mediaDevices.getUserMedia({ audio: true });

    audioContext = new (window.AudioContext || window.webkitAudioContext)();
    analyser = audioContext.createAnalyser();
    source = audioContext.createMediaStreamSource(visualizerStream);

    source.connect(analyser);
    analyser.fftSize = 256;

    const bufferLength = analyser.frequencyBinCount;
    const dataArray = new Uint8Array(bufferLength);
    const canvas = document.getElementById("visualizer");
    const ctx = canvas.getContext("2d");

    // Set canvas size for phone mockup
    canvas.width = 240;
    canvas.height = 240;

    function renderFrame() {
        // Keep rendering while recording OR processing
        if (!isRecording && !isProcessing) {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            return;
        }

        animationId = requestAnimationFrame(renderFrame);
        analyser.getByteFrequencyData(dataArray);

        ctx.clearRect(0, 0, canvas.width, canvas.height);

        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;
        const baseRadius = 55;
        const bars = 40;
        const step = (Math.PI * 2) / bars;
        const barWidth = 5;

        // Draw center glow circle
        const glowGradient = ctx.createRadialGradient(centerX, centerY, 0, centerX, centerY, baseRadius);
        glowGradient.addColorStop(0, "rgba(225, 29, 72, 0.15)");
        glowGradient.addColorStop(0.7, "rgba(225, 29, 72, 0.05)");
        glowGradient.addColorStop(1, "transparent");
        ctx.fillStyle = glowGradient;
        ctx.beginPath();
        ctx.arc(centerX, centerY, baseRadius, 0, Math.PI * 2);
        ctx.fill();

        // Draw base circle ring
        ctx.strokeStyle = "rgba(225, 29, 72, 0.3)";
        ctx.lineWidth = 2;
        ctx.beginPath();
        ctx.arc(centerX, centerY, baseRadius, 0, Math.PI * 2);
        ctx.stroke();

        for (let i = 0; i < bars; i++) {
            const value = dataArray[i % 64];
            const barHeight = (value / 255) * 45 + 8;

            const angle = i * step - Math.PI / 2;
            const startX = centerX + Math.cos(angle) * baseRadius;
            const startY = centerY + Math.sin(angle) * baseRadius;

            ctx.save();
            ctx.translate(startX, startY);
            ctx.rotate(angle + Math.PI / 2);

            const gradient = ctx.createLinearGradient(0, 0, 0, -barHeight);
            gradient.addColorStop(0, "rgba(225, 29, 72, 0.95)");
            gradient.addColorStop(0.4, "rgba(251, 113, 133, 0.7)");
            gradient.addColorStop(1, "rgba(255, 200, 200, 0.2)");

            ctx.fillStyle = gradient;
            ctx.beginPath();
            ctx.roundRect(-barWidth / 2, -barHeight, barWidth, barHeight, 3);
            ctx.fill();

            // Glowing tip at the outer end
            ctx.shadowBlur = 12;
            ctx.shadowColor = "rgba(225, 29, 72, 0.9)";
            ctx.fillStyle = "rgba(255, 255, 255, 0.95)";
            ctx.beginPath();
            ctx.arc(0, -barHeight, 2.5, 0, Math.PI * 2);
            ctx.fill();
            ctx.shadowBlur = 0;

            ctx.restore();
        }
    }
    renderFrame();
}

function stopVisualizer() {
    if (animationId) cancelAnimationFrame(animationId);
    if (audioContext) {
        audioContext.close();
        audioContext = null;
    }
    if (visualizerStream) {
        visualizerStream.getTracks().forEach(track => track.stop());
        visualizerStream = null;
    }
    const canvas = document.getElementById("visualizer");
    if (canvas) {
        const ctx = canvas.getContext("2d");
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }
}

// --- 2. TOGGLE LOGIC ---
window.toggleRecording = async function () {
    const btn = document.getElementById('live-listen-btn');

    if (isRecording || isProcessing) {
        console.log("🛑 Cancelled");
        isRecording = false;
        isProcessing = false;
        stopVisualizer();
        try { await window.recorder.stop(); } catch (e) { }

        stopVisualsUI(btn);
        setStatus("Tap the mic to start");
        return;
    }

    isRecording = true;
    isProcessing = true;

    try {
        // Check if getUserMedia is supported
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            throw new Error("Browser doesn't support microphone access");
        }

        // Check for available audio input devices
        const devices = await navigator.mediaDevices.enumerateDevices();
        const audioInputs = devices.filter(d => d.kind === 'audioinput');
        console.log("🎤 Available microphones:", audioInputs.length, audioInputs);

        if (audioInputs.length === 0) {
            throw { name: 'NotFoundError', message: 'No microphone detected' };
        }

        await window.recorder.start();
        startVisualsUI(btn);
        startVisualizer();

        // 3 seconds recording
        setTimeout(async () => {
            if (!isRecording) return;

            // Switch to identifying messages (slower - 1.5s intervals)
            setStatus(identifyingMessages[0]);
            let msgIndex = 0;
            clearInterval(statusInterval);
            statusInterval = setInterval(() => {
                msgIndex = (msgIndex + 1) % identifyingMessages.length;
                setStatus(identifyingMessages[msgIndex]);
            }, 1500); // SLOWER message rotation

            try {
                const audioBlob = await window.recorder.stop();
                isRecording = false;
                // Keep isProcessing = true so visualizer stays

                await sendAudioToServer(audioBlob, btn);
            } catch (err) {
                console.error("Recording error:", err);
                clearInterval(statusInterval);
                resetApp(btn, "Recording failed 😢");
            }
        }, 3000);

    } catch (err) {
        console.error("Mic access error:", err);
        isRecording = false;
        isProcessing = false;

        // Show specific error message based on error type
        let errorMsg = "Mic error 🎤";
        if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
            errorMsg = "Mic blocked by browser";
        } else if (err.name === 'NotFoundError' || err.name === 'DevicesNotFoundError') {
            errorMsg = "No mic found - check connections";
        } else if (err.name === 'NotReadableError' || err.name === 'TrackStartError') {
            errorMsg = "Mic busy - close other apps";
        } else if (err.message) {
            errorMsg = err.message;
        }

        setStatus(errorMsg);
        stopVisualsUI(btn);
    }
};

// --- UI HELPERS ---
function startVisualsUI(btn) {
    const heroSection = document.querySelector('.hero-section');
    if (heroSection) heroSection.classList.add('listening');

    if (btn) btn.classList.add('active');

    let index = 0;
    setStatus(trendyMessages[0]);

    // SLOWER message rotation - 1.2 seconds instead of 600ms
    statusInterval = setInterval(() => {
        index = (index + 1) % trendyMessages.length;
        setStatus(trendyMessages[index]);
    }, 1200);
}

function stopVisualsUI(btn) {
    const heroSection = document.querySelector('.hero-section');
    if (heroSection) heroSection.classList.remove('listening');

    if (btn) btn.classList.remove('active');

    clearInterval(statusInterval);
    statusInterval = null;
}

// --- 3. UPLOAD & RESET ---
async function sendAudioToServer(audioBlob, btn, identifyInterval = null) {
    const formData = new FormData();
    formData.append('audio', audioBlob, 'recording.wav');

    try {
        const response = await axios.post('/recognize', formData);
        clearInterval(statusInterval);

        // NOW stop everything
        isProcessing = false;
        stopVisualizer();
        resetApp(btn, "Tap the mic to start");

        if (response.data.status === 'success') {
            showResult(response.data.data);
        } else {
            setStatus("No match found. Try again!");
        }
    } catch (error) {
        clearInterval(statusInterval);
        isProcessing = false;
        stopVisualizer();
        resetApp(btn, "Server Error 💀");
    }
}

function resetApp(btn, message) {
    isRecording = false;
    isProcessing = false;
    clearInterval(statusInterval);
    statusInterval = null;

    stopVisualizer();
    stopVisualsUI(btn);

    if (message) {
        setTimeout(() => {
            setStatus(message);
        }, 10);
    }
}

// --- 4. SHOW RESULT ---
function showResult(data) {
    document.getElementById('result-title').innerText = data.title;
    document.getElementById('result-artist').innerText = data.artist;

    const albumArtImg = document.getElementById('result-album-art');
    const embedContainer = document.getElementById('spotify-embed-container');
    const bgDiv = document.querySelector('.result-bg-dynamic');

    const artUrl = data.album_art || "/assets/images/misc/plan.png";
    if (bgDiv) bgDiv.style.backgroundImage = `url('${artUrl}')`;

    const sourceBadge = document.getElementById('source-badge');
    if (sourceBadge && data.source) {
        const sourceLabels = {
            'acrcloud': '⚡ ACRCloud',
            'audd': '🌍 Audd.io',
            'acoustid': '🎵 AcoustID'
        };
        sourceBadge.innerText = sourceLabels[data.source] || data.source;
        sourceBadge.style.display = 'inline-block';
    }

    const timeDisplay = document.getElementById('recognition-time');
    if (timeDisplay && data.recognition_time) {
        timeDisplay.innerText = `⏱ ${data.recognition_time}ms`;
        timeDisplay.style.display = 'inline-block';
    }

    if (data.spotify_id) {
        embedContainer.innerHTML = `
            <iframe
                src="https://open.spotify.com/embed/track/${data.spotify_id}?utm_source=generator&theme=0"
                width="100%"
                height="80"
                frameBorder="0"
                allowfullscreen=""
                allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
                style="border-radius:12px;"
                loading="lazy">
            </iframe>`;
        embedContainer.style.display = 'block';
        albumArtImg.style.display = 'none';
    } else {
        embedContainer.style.display = 'none';
        albumArtImg.src = artUrl;
        albumArtImg.style.display = 'block';
    }

    const btnYoutube = document.getElementById('btn-youtube');
    const btnSpotify = document.getElementById('btn-spotify');

    if (data.youtube_link) {
        btnYoutube.href = data.youtube_link;
        btnYoutube.style.display = 'flex';
    } else {
        btnYoutube.style.display = 'none';
    }

    if (data.spotify_id) {
        btnSpotify.href = `https://open.spotify.com/track/${data.spotify_id}`;
        btnSpotify.style.display = 'flex';
    } else {
        btnSpotify.style.display = 'none';
    }

    saveToHistory(data);

    document.getElementById('result-overlay').style.display = 'flex';
}

// --- 5. HISTORY MANAGEMENT ---
function saveToHistory(data) {
    try {
        let history = JSON.parse(localStorage.getItem('reco_history') || '[]');

        history.unshift({
            title: data.title,
            artist: data.artist,
            album_art: data.album_art,
            spotify_id: data.spotify_id,
            youtube_link: data.youtube_link,
            source: data.source,
            timestamp: new Date().toISOString()
        });

        history = history.slice(0, 50);

        localStorage.setItem('reco_history', JSON.stringify(history));
        console.log('📝 Saved to history');
    } catch (e) {
        console.warn('Could not save to history:', e);
    }
}

window.getHistory = function () {
    try {
        return JSON.parse(localStorage.getItem('reco_history') || '[]');
    } catch (e) {
        return [];
    }
};

window.clearHistory = function () {
    localStorage.removeItem('reco_history');
    console.log('🗑️ History cleared');
};

// --- 6. SHARE FUNCTIONALITY ---
window.shareResult = async function () {
    const title = document.getElementById('result-title').innerText;
    const artist = document.getElementById('result-artist').innerText;
    const shareText = `🎵 Just discovered "${title}" by ${artist} using RECO SONG!`;

    if (navigator.share) {
        try {
            await navigator.share({
                title: 'RECO SONG - Song Discovery',
                text: shareText,
                url: window.location.href
            });
        } catch (e) {
            console.log('Share cancelled');
        }
    } else {
        try {
            await navigator.clipboard.writeText(shareText);
            alert('Copied to clipboard! 📋');
        } catch (e) {
            alert(shareText);
        }
    }
};

window.closeResult = function () {
    document.getElementById('result-overlay').style.display = 'none';
    document.querySelector('.result-bg-dynamic').style.backgroundImage = 'none';
    document.getElementById('spotify-embed-container').innerHTML = '';

    const sourceBadge = document.getElementById('source-badge');
    const timeDisplay = document.getElementById('recognition-time');
    if (sourceBadge) sourceBadge.style.display = 'none';
    if (timeDisplay) timeDisplay.style.display = 'none';
};
