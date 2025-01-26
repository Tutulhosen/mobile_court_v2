$(function() {
    let ip_address = "https://voice.bangla.gov.bd";
    let port = "9394";
    let socket = io(ip_address + ':' + port, {
        transports: ['websocket'],
        upgrade: false
    });

    socket.on('connect', function() {
        console.log("Connected to socket");

        let mediaRecorder;
        let audioChunks = [];
        let recording = false;
        let currentIndex = 0;
        let intervalId;

        $('#startRecording_one').click(function() {
            $('#status').text('Starting recording...');

            if (recording) {
                return; // Already recording, so do nothing
            }

            navigator.mediaDevices.getUserMedia({ audio: true })
                .then(stream => {
                    mediaRecorder = new MediaRecorder(stream);

                    mediaRecorder.ondataavailable = function(event) {
                        audioChunks.push(event.data);
                    };

                    mediaRecorder.onstop = function() {
                        if (recording) {
                            const rawBlob = new Blob(audioChunks, { type: 'audio/webm' });
                            audioChunks = [];

                            convertToPCM(rawBlob).then((pcmData) => {
                                const wavHeader = createWavHeader(pcmData.byteLength);
                                const wavBlob = new Blob([wavHeader, pcmData], { type: 'audio/wav' });

                                const reader = new FileReader();
                                reader.readAsDataURL(wavBlob);
                                reader.onloadend = () => {
                                    const base64String = reader.result.split(',')[1];
                                    let message = {
                                        index: currentIndex,
                                        audio: base64String,
                                        endOfStream: false  // Indicate that the stream is ongoing
                                    };

                                    socket.emit('audio_transmit', message, function(response) {
                                        console.log('Server response:', response);
                                    });

                                    currentIndex++;
                                };
                            });
                        }
                    };

                    mediaRecorder.start();
                    recording = true;

                    // Start sending data every 500ms
                    intervalId = setInterval(() => {
                        if (recording) {
                            mediaRecorder.stop();
                            mediaRecorder.start(); // Restart recording after stopping to create chunks
                        }
                    }, 500);

                    $('#stopRecording').on('click', function() {
                        if (recording) {
                            clearInterval(intervalId);
                            mediaRecorder.stop(); // Stop recording which will trigger onstop event
                            recording = false;

                            // Send final data with endOfStream: true
                            setTimeout(() => {
                                const rawBlob = new Blob(audioChunks, { type: 'audio/webm' });
                                audioChunks = [];

                                convertToPCM(rawBlob).then((pcmData) => {
                                    const wavHeader = createWavHeader(pcmData.byteLength);
                                    const wavBlob = new Blob([wavHeader, pcmData], { type: 'audio/wav' });

                                    const reader = new FileReader();
                                    reader.readAsDataURL(wavBlob);
                                    reader.onloadend = () => {
                                        const base64String = reader.result.split(',')[1];
                                        let message = {
                                            index: currentIndex,
                                            audio: base64String,
                                            endOfStream: true  // Indicate end of stream
                                        };

                                        socket.emit('audio_transmit', message, function(response) {
                                            console.log('Final server response:', response);
                                        });

                                        currentIndex++;
                                    };
                                });

                                $('#status').text('Recording stopped.');
                            }, 500); // Ensure the last part is sent 500ms after stopping
                        }
                    });
                })
                .catch(error => {
                    console.error('Error accessing microphone:', error);
                    $('#status').text('Error accessing microphone.');
                });
        });
    });

    socket.on('result', function(data) {
        console.log('Received result from server:', data);
        var array = data.output.predicted_words;
        array.forEach(element => {
            $('#base64Output').text(element.word);
        });
    });

    socket.on('connect_error', function(error) {
        console.error("Connection error:", error);
        $('#status').text('Socket connection error.');
    });

    function createWavHeader(dataSize, sampleRate = 44100, numChannels = 1, bitsPerSample = 16) {
        const header = new ArrayBuffer(44);
        const view = new DataView(header);

        view.setUint32(0, 0x52494646, false);  // "RIFF"
        view.setUint32(4, 36 + dataSize, true);  // RIFF chunk size
        view.setUint32(8, 0x57415645, false);  // "WAVE"

        view.setUint32(12, 0x666d7420, false);  // "fmt " sub-chunk
        view.setUint32(16, 16, true);  // Subchunk1Size (16 for PCM)
        view.setUint16(20, 1, true);  // Audio format (PCM = 1)
        view.setUint16(22, numChannels, true);  // Number of channels
        view.setUint32(24, sampleRate, true);  // Sample rate
        view.setUint32(28, sampleRate * numChannels * (bitsPerSample / 8), true);  // Byte rate
        view.setUint16(32, numChannels * (bitsPerSample / 8), true);  // Block align
        view.setUint16(34, bitsPerSample, true);  // Bits per sample

        view.setUint32(36, 0x64617461, false);  // "data" sub-chunk
        view.setUint32(40, dataSize, true);  // Data size

        return header;
    }

    function convertToPCM(blob) {
        return new Promise((resolve) => {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const reader = new FileReader();

            reader.onloadend = () => {
                audioContext.decodeAudioData(reader.result, (buffer) => {
                    const channelData = buffer.getChannelData(0);
                    const pcmArray = new Int16Array(channelData.length);

                    for (let i = 0; i < channelData.length; i++) {
                        pcmArray[i] = Math.min(1, channelData[i]) * 0x7FFF;
                    }

                    resolve(pcmArray.buffer);
                });
            };

            reader.readAsArrayBuffer(blob);
        });
    }
});
