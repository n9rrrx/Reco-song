export default class AudioRecorder {
    constructor() {
        this.audioContext = null;
        this.processor = null;
        this.stream = null;
        this.buffer = [];
        this.isRecording = false;
    }

    async start() {
        try {
            // Request higher quality audio for better recognition
            this.stream = await navigator.mediaDevices.getUserMedia({
                audio: {
                    echoCancellation: false,
                    noiseSuppression: false,
                    autoGainControl: true,
                    sampleRate: 44100
                }
            });
        } catch (err) {
            // Fallback to simple constraints if advanced ones fail
            try {
                this.stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            } catch (fallbackErr) {
                console.error("Failed to get audio stream:", fallbackErr);
                throw fallbackErr;
            }
        }

        // 44100Hz (CD quality) for better recognition accuracy
        this.audioContext = new AudioContext({ sampleRate: 44100 });

        const source = this.audioContext.createMediaStreamSource(this.stream);
        this.processor = this.audioContext.createScriptProcessor(4096, 1, 1);

        source.connect(this.processor);
        this.processor.connect(this.audioContext.destination);

        this.buffer = [];

        this.processor.onaudioprocess = (e) => {
            this.buffer.push(new Float32Array(e.inputBuffer.getChannelData(0)));
        };

        this.isRecording = true;
        console.log("🎙️ Recording WAV PCM @ 44100Hz...");
    }

    stop() {
        return new Promise((resolve) => {
            this.processor.disconnect();
            this.stream.getTracks().forEach(t => t.stop());

            const wavBlob = this.encodeWAV(this.buffer, 44100);
            this.buffer = [];
            this.isRecording = false;

            console.log("✅ WAV ready:", wavBlob.size);
            resolve(wavBlob);
        });
    }

    encodeWAV(samples, sampleRate) {
        const buffer = new ArrayBuffer(44 + samples.length * 4096 * 2);
        const view = new DataView(buffer);

        let offset = 0;
        const writeString = s => { for (let i = 0; i < s.length; i++) view.setUint8(offset++, s.charCodeAt(i)); };

        writeString('RIFF');
        view.setUint32(offset, 36 + samples.length * 4096 * 2, true); offset += 4;
        writeString('WAVE');
        writeString('fmt ');
        view.setUint32(offset, 16, true); offset += 4;
        view.setUint16(offset, 1, true); offset += 2;
        view.setUint16(offset, 1, true); offset += 2;
        view.setUint32(offset, sampleRate, true); offset += 4;
        view.setUint32(offset, sampleRate * 2, true); offset += 4;
        view.setUint16(offset, 2, true); offset += 2;
        view.setUint16(offset, 16, true); offset += 2;
        writeString('data');
        view.setUint32(offset, samples.length * 4096 * 2, true); offset += 4;

        samples.forEach(chunk => {
            chunk.forEach(sample => {
                view.setInt16(offset, Math.max(-1, Math.min(1, sample)) * 0x7fff, true);
                offset += 2;
            });
        });

        return new Blob([view], { type: 'audio/wav' });
    }
}
