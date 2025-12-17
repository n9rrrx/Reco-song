import './bootstrap';
import AudioRecorder from './recorder';

window.recorder = new AudioRecorder();
console.log("🚀 Reco-song Ready");

let statusInterval;
let isRecording = false;

// TRENDY MESSAGES
const trendyMessages = [
    "Hold up, let me cook... 🍳",
    "Passing the vibe check... ✅",
    "Main character energy incoming... ✨",
    "Bet you heard this on TikTok... 📱",
    "Manifesting the lyrics... 🕯️",
    "This beat lives rent-free in my head... 🏠",
    "Listening respectfully... 👁️👄👁️",
    "Entering my villain era... 😈",
    "Sending this to the group chat... 💬",
    "Absolute cinema... ✋🙂‍↕️",
    "Slay loading... 💅"
];

// 1. ANIMATION START
function startVisuals(btn) {
    btn.parentElement.classList.add('listening');
    btn.classList.add('active');

    btn.innerHTML = `<i class="ri-stop-fill" style="font-size: 80px;"></i>`;

    const statusText = document.getElementById('status-text');
    let index = 0;

    statusText.innerText = trendyMessages[0];
    statusText.style.opacity = 1;

    statusInterval = setInterval(() => {
        index = (index + 1) % trendyMessages.length;
        statusText.style.opacity = 0;
        setTimeout(() => {
            statusText.innerText = trendyMessages[index];
            statusText.style.opacity = 1;
        }, 300);
    }, 2000);
}

// 2. ANIMATION STOP
function stopVisuals(btn) {
    btn.parentElement.classList.remove('listening');
    btn.classList.remove('active');
    btn.innerHTML = `<i class="ri-shazam-line"></i>`;
    clearInterval(statusInterval);
    document.getElementById('status-text').innerText = "Tap to start listening";
}

// 3. TOGGLE LOGIC
window.toggleRecording = async function() {
    const btn = document.getElementById('live-listen-btn');
    const statusText = document.getElementById('status-text');

    if (isRecording) {
        console.log("🛑 Cancelled");
        try { await window.recorder.stop(); } catch(e) {}
        stopVisuals(btn);
        isRecording = false;
        statusText.innerText = "Cancelled";
        return;
    }

    isRecording = true;
    try {
        await window.recorder.start();
        startVisuals(btn);

        setTimeout(async () => {
            if (!isRecording) return;
            statusText.innerText = "Bestie, I'm thinking... 🧠";
            try {
                const audioBlob = await window.recorder.stop();
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

// 4. UPLOAD
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
    stopVisuals(btn);
    if(message) document.getElementById('status-text').innerText = message;
}

// 5. SHOW RESULT (FIXED: Sets Background Image)
function showResult(data) {
    document.getElementById('result-title').innerText = data.title;
    document.getElementById('result-artist').innerText = data.artist;

    // Set Album Art
    const artUrl = data.album_art || "/assets/images/misc/plan.png";
    document.getElementById('result-album-art').src = artUrl;

    // Set Blurred Background
    const bgDiv = document.querySelector('.result-bg-dynamic');
    if(bgDiv) {
        bgDiv.style.backgroundImage = `url('${artUrl}')`;
    }

    // Buttons
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
    // Reset background to prevent flickering
    document.querySelector('.result-bg-dynamic').style.backgroundImage = 'none';
};
