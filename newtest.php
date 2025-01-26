$(function() {
    let ip_address = "https://voice.bangla.gov.bd";
    let port = "9394";
    let socket = io(ip_address + ':' + port, {
        transports: ['websocket'],
        upgrade: false
    });

    socket.on('connect', function() {
        alert('tst');
        console.log("Connected to socket");

        let mediaRecorder;
        let audioChunks = [];
        let recording = false;
        let currentIndex = 0;

        $('#startRecording_one').click(function() {
            $('#status').text('Starting recording...');

            if (recording) {
                mediaRecorder.stop();
                recording = false;
                return;
            }

            navigator.mediaDevices.getUserMedia({ audio: true })
                .then(stream => {
                    mediaRecorder = new MediaRecorder(stream);

                    mediaRecorder.ondataavailable = function(event) {
                        audioChunks.push(event.data);
                    };

                    mediaRecorder.onstop = function() {
                        console.log('Recording stopped');
                        let blob = new Blob(audioChunks, { type: 'audio/webm' });
                        audioChunks = [];

                        let reader = new FileReader();
                        reader.readAsArrayBuffer(blob);
                        reader.onloadend = function() {
                            let arrayBuffer = reader.result;
                            let wavHeader = createWavHeader(arrayBuffer.byteLength);
                            let wavBlob = new Blob([wavHeader, arrayBuffer], { type: 'audio/wav' });

                            let readerWav = new FileReader();
                            readerWav.readAsDataURL(wavBlob);
                            readerWav.onloadend = function() {
                                let base64String = readerWav.result.split(',')[1];
                                console.log(base64String);
                                let message = {
                                    index: currentIndex,
                                    audio: base64String,
                                    endOfStream: false
                                };
                                
                                socket.emit('audio_transmit', message, function(response) {
                                    console.log('Server response:', response);
                                });

                                currentIndex++;
                            };
                        };
                    };

                    mediaRecorder.start();

                    let intervalId = setInterval(() => {
                        if (recording) {
                            mediaRecorder.stop();
                            mediaRecorder.start();
                        }
                    }, 500);

                    recording = true;

                    $('#stopRecording').on('click', function() {
                        clearInterval(intervalId);
                        if (recording) {
                            mediaRecorder.stop();
                            recording = false;
                        }
                        $('#status').text('Recording stopped.');
                    });
                })
                .catch(error => {
                    console.error('Error accessing microphone:', error);
                    $('#status').text('Error accessing microphone.');
                });
        });
    });

    socket.on('result', function(data) {
        var array = data.output.predicted_words;
        console.log('Received result from server:', data);
        array.forEach(element => {
            $('#base64Output').text(element.word);
        });
    });

    socket.on('connect_error', function(error) {
        console.error("Connection error:", error);
        $('#status').text('Socket connection error.');
    });

    // Function to create a WAV header
    function createWavHeader(dataSize) {
        const sampleRate = 44100; // Sample rate
        const numChannels = 1; // Number of audio channels
        const bitsPerSample = 16; // Bit depth

        const header = new ArrayBuffer(44);
        const view = new DataView(header);

        // RIFF chunk descriptor
        view.setUint8(0, 'R'.charCodeAt(0));
        view.setUint8(1, 'I'.charCodeAt(0));
        view.setUint8(2, 'F'.charCodeAt(0));
        view.setUint8(3, 'F'.charCodeAt(0));
        view.setUint32(4, 36 + dataSize, true); // Chunk size
        view.setUint8(8, 'W'.charCodeAt(0));
        view.setUint8(9, 'A'.charCodeAt(0));
        view.setUint8(10, 'V'.charCodeAt(0));
        view.setUint8(11, 'E'.charCodeAt(0));
        
        // Format subchunk
        view.setUint8(12, 'f'.charCodeAt(0));
        view.setUint8(13, 'm'.charCodeAt(0));
        view.setUint8(14, 't'.charCodeAt(0));
        view.setUint8(15, ' '.charCodeAt(0));
        view.setUint32(16, 16, true); // Subchunk1 size
        view.setUint16(20, 1, true); // Audio format (1 = PCM)
        view.setUint16(22, numChannels, true); // Number of channels
        view.setUint32(24, sampleRate, true); // Sample rate
        view.setUint32(28, sampleRate * numChannels * (bitsPerSample / 8), true); // Byte rate
        view.setUint16(32, numChannels * (bitsPerSample / 8), true); // Block align
        view.setUint16(34, bitsPerSample, true); // Bits per sample
        
        // Data subchunk
        view.setUint8(36, 'd'.charCodeAt(0));
        view.setUint8(37, 'a'.charCodeAt(0));
        view.setUint8(38, 't'.charCodeAt(0));
        view.setUint8(39, 'a'.charCodeAt(0));
        view.setUint32(40, dataSize, true); // Subchunk2 size

        return header;
    }
});  


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!--begin::Head-->

<head>
    <base href="">
    <meta charset="utf-8" />
    <!-- <title>@yield('title', $page_title ?? 'Page Title') | {{ config('app.name') }}</title> -->
    <title>@yield('title', $page_title ?? ' আদালত') </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- <meta name="_token" content="{{ csrf_token() }}" /> -->
    <meta name="description" content="Civil Suit Judiciary of Bangladesh" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="shortcut icon" href="{{ asset('media/logos/favicon.ico') }}" />
    <!--begin::Fonts-->
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" /> -->
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!--end::Fonts-->
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/fontawesome.min.css"  />

    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="{{ asset('plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/custom/prismjs/prismjs.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

    <!--end::Global Theme Styles-->
    <!--begin::Layout Themes(used by all pages)-->
    <link href="{{ asset('css/themes/layout/header/base/light.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/themes/layout/header/menu/light.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/themes/layout/brand/light.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/themes/layout/aside/light.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/landing/custom.css') }}" rel="stylesheet" type="text/css" />
    <!-- <link href="https://beta-idp.stage.mygov.bd/css/mygov-widgets.css" rel="stylesheet"/> -->
    <!--end::Layout Themes-->

    <!--begin::Page Custom Styles(used by specific page)-->
    @stack('head')
    <!--end::Page Custom Styles-->

    <!--begin::Page Vendors Styles(used by this page)-->
    {{-- Includable CSS Related Page --}}
    @yield('styles')
    <style>
        #pagePreLoader {
            position: fixed;
            width: 100%;
            height: 100%;
            background: #fff url('https://teamphotousa.com/assets/images/teamphoto-loading.gif') no-repeat center;
            z-index: 9999999;
        }

        #toast-container>div {
            opacity: 1 !important;
        }

        .toast.toast-success
         {
            background-color: #0bb7af !important;
            color: #fff !important;
        }
        .toast.toast-error,
        .toast.toast-warning
        {
            background-color: #ee2d41 !important;
            color: #fff !important;
        }

    </style>

<style>
    .input_bangla {
    font-family: boishakhi !important;
    font-size: 14px !important;
    }
    @font-face {
        font-family: 'boishakhi';
        src: url('/fonts/Boishkhi/Boishkhi.ttf') format('truetype');
    }


/* voice to text  */

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

* {
    margin: 0;
    padding: 0;
    font-family: 'Poppins', sans-serif;
}

body {
    background-image: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%);
    background-size: cover;
    background-attachment: fixed;
    background-repeat: no-repeat;
}

.main {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

#output {
    margin-top: 20px;
    border: 1px solid #ccc;
    padding: 10px;
    width: 80%;
    margin-left: auto;
    margin-right: auto;
    min-height: 100px;
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;

}

.container {
    background-color: rgba(255, 255, 255, 0.5);
    text-align: center;
    padding: 20px;            
    box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
    border-radius: 20px;
    width: 400px;
}

.button {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}

#startRecording_one {
    margin-top: 20px;
    padding: 20px;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    cursor: pointer;
    background-color: rgb(159, 159, 252);
}

#startRecording_one:hover {
    background-color: rgb(50, 50, 200);
}

#stopRecording {
    margin-top: 10px;
    padding: 5px 10px;
    cursor: pointer;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
}

#stopRecording:hover {
    background-color: #45a049;
}

#clearButton {
    margin-top: 10px;
    padding: 5px 10px;
    cursor: pointer;
    background-color: #af4c4c;
    color: white;
    border: none;
    border-radius: 4px;
}

#clearButton:hover {
    background-color: #ac3434;
}
   * /
</style>


    <!-- <link href="{{ asset('css/custom.css') }}" rel="stylesheet" type="text/css" />  -->
    <!-- <link href="{{ asset('css/landing/custom.css') }}" rel="stylesheet" type="text/css" /> -->


</head>
<!--end::Head-->
<!--begin::Body-->

<body onload="" id="kt_body"
    class="header-fixed header-mobile-fixed aside-fixed aside-minimize-hoverable page-loading">
    {{-- <div class="" onload="pagePreLoaderOff()" id="pagePreLoader"></div> --}}
    <script src="https://cdn.socket.io/4.5.0/socket.io.min.js" integrity="sha384-7EyYLQZgWBi67fBtVxw60/OWl1kjsfrPFcaU0pp0nAh+i8FD068QogUvg85Ewy1k" crossorigin="anonymous">
    </script> 
    @include('layout.mobile_header')
    
    <div class="" id="kt_wrapper">
        @php
            $uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        @endphp

        @auth
            @include('layout.auth_header')
        @else
            @include('layout.header')
        @endauth
            
        @php
        if (str_contains($uri_path, '/forget/password/mobile/check')) {
            $uri_path = 'forget/password/mobile/check';
          }
        if (str_contains($uri_path, 'forget/password/otp/resend')) {
            $uri_path = 'forget/password/otp/resend';
          }
        if (str_contains($uri_path, '/citizen/mobile/check/')) {
            $uri_path = '/citizen/mobile/check/';
          }
          if (str_contains($uri_path, '/advocate/mobile/check')) {
            $uri_path = '/advocate/mobile/check';
          }
        @endphp
        
      
  
            <div class="container" style="margin-top:90px; margin-bottom:25px">
                @if (request()->is('dashboard'))
                    @yield('content')
                @else
                    <div class="row">
                        @if(isset($users) && $users != null)
                            <div class="custom-col-12">
                                @yield('content')
                            </div>
                        @elseif(isset($court_page) && $court_page != null)
                            <div class="custom-col-12">
                                @yield('content')
                            </div>
                        @else
                            <div class="col-md-12">
                                @yield('content')
                            </div>
                        @endif
                    </div>
                @endif
            </div>
      
        
        @yield('landing')

       
    </div>

  
    <!-- begin::User Panel-->
    @include('layout.partials.user_panel')
    <!-- end::User Panel-->

    <!--begin::Quick Panel -->
    @include('layout.partials.quick_panel')
    <!--end::Quick Panel-->


    @if(!Auth::check())
        @include('layout.footer')
    @endif

        
    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop">
        <span class="svg-icon">
            <!--begin::Svg Icon | path:media/svg/icons/Navigation/Up-2.svg-->
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                height="24px" viewBox="0 0 24 24" version="1.1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <polygon points="0 0 24 0 24 24 0 24" />
                    <rect fill="#000000" opacity="0.3" x="11" y="10" width="2" height="10"
                        rx="1" />
                    <path
                        d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z"
                        fill="#000000" fill-rule="nonzero" />
                </g>
            </svg>
            <!--end::Svg Icon-->
        </span>
    </div>
    <!--end::Scrolltop-->
    <!-- <button id="startRecording_one"  class="btn btn-success"> Recording</button>
    <textarea id="status" rows="10" cols="50"></textarea> -->
    <div class="main">
        <div class="container">
            <h1>Speech to Text</h1>
            <div class="button">
                <button id="startRecording_one"><img src="https://cdn-icons-png.flaticon.com/512/25/25682.png" alt="" width="50" height="50"></button>
                <small>Tap to Record</small>
            </div>
            <div id="output"></div>
            <div class="buttons">
                <!-- <button id="copyButton">Copy Text</button>
                <button id="clearButton">Clear Text</button> -->
                <button id="stopRecording">Stop Recording</button>
            </div>
        
    
        </div>

    </div>


 
    <!--begin::Global Config(global config for global JS scripts)-->
    <script>
        var KTAppSettings = {
            "breakpoints": {
                "sm": 576,
                "md": 768,
                "lg": 992,
                "xl": 1200,
                "xxl": 1400
            },
            "colors": {
                "theme": {
                    "base": {
                        "white": "#ffffff",
                        "primary": "#3699FF",
                        "secondary": "#E5EAEE",
                        "success": "#1BC5BD",
                        "info": "#8950FC",
                        "warning": "#FFA800",
                        "danger": "#F64E60",
                        "light": "#E4E6EF",
                        "dark": "#181C32"
                    },
                    "light": {
                        "white": "#ffffff",
                        "primary": "#E1F0FF",
                        "secondary": "#EBEDF3",
                        "success": "#C9F7F5",
                        "info": "#EEE5FF",
                        "warning": "#FFF4DE",
                        "danger": "#FFE2E5",
                        "light": "#F3F6F9",
                        "dark": "#D6D6E0"
                    },
                    "inverse": {
                        "white": "#ffffff",
                        "primary": "#ffffff",
                        "secondary": "#3F4254",
                        "success": "#ffffff",
                        "info": "#ffffff",
                        "warning": "#ffffff",
                        "danger": "#ffffff",
                        "light": "#464E5F",
                        "dark": "#ffffff"
                    }
                },
                "gray": {
                    "gray-100": "#F3F6F9",
                    "gray-200": "#EBEDF3",
                    "gray-300": "#E4E6EF",
                    "gray-400": "#D1D3E0",
                    "gray-500": "#B5B5C3",
                    "gray-600": "#7E8299",
                    "gray-700": "#5E6278",
                    "gray-800": "#3F4254",
                    "gray-900": "#181C32"
                }
            },
            "font-family": "Poppins"
        };
    </script>
    <!--end::Global Config-->
    <!--begin::Global Theme Bundle(used by all pages)-->
    <script src="{{ asset('plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('plugins/custom/prismjs/prismjs.bundle.js') }}"></script>
    <script src="{{ asset('js/scripts.bundle.js') }}"></script>
    <!--end::Global Theme Bundle-->
  <!-- <div id="downloadButton" >download</div> -->

<script>

let mediaRecorder;
        let audioChunks = [];

        $(document).ready(function() {
            // Start Recording
            $('#startRecording').on('click', function() {
               
            // setInterval(() => { 
                navigator.mediaDevices.getUserMedia({ audio: true })
                    .then(stream => {
                       
                        mediaRecorder = new MediaRecorder(stream);
                        mediaRecorder.start();

                        mediaRecorder.ondataavailable = event => {
                            audioChunks.push(event.data);
                        };
                       
                        mediaRecorder.onstop = () => {
                            const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                            audioChunks = [];
                            console.log('Blob created successfully', audioBlob);
                            // Convert the Blob to Base64
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const base64String = e.target.result.split(',')[1];
                                $('#base64Output').text(base64String);

                            };
                            reader.readAsDataURL(audioBlob);
                        };
                       
                       
                    
                 
                        // $('#startRecording').prop('disabled', true);
                        // $('#stopRecording').prop('disabled', false);
                    });
           
           
                    // mediaRecorder.stop();
                // }, 500);

            });
        //     setInterval(() => {   // 500 milliseconds interval
        //     // Stop Recording
        //     $('#stopRecording').on('click', function() {
              
        //         $('#startRecording').prop('disabled', false);
        //         $('#stopRecording').prop('disabled', true);
        //     });
        // }, 500);
        });





// const startButton = document.getElementById('startButton');
// const output = document.getElementById('output');
// const copyButton = document.getElementById('copyButton');
// const clearButton = document.getElementById('clearButton');
// const downloadButton = document.getElementById('downloadButton');

// copyButton.onclick = function() {
//     const textToCopy = output.innerText;
//     navigator.clipboard.writeText(textToCopy).then(() => {
//         alert('Text copied to clipboard!');
//     }).catch(err => {
//         console.error('Failed to copy: ', err);
//     });
// };

// clearButton.onclick = function() {
//     output.innerText = '';
// };

// startButton.addEventListener('click', function() {
//     var speech = true;
//     window.SpeechRecognition =window.SpeechRecognition || window.webkitSpeechRecognition;
//     if (!window.SpeechRecognition) {
//                 alert('Speech Recognition API not supported.');
//                 return;
//         }
    
//     const recognition = new SpeechRecognition();
//     recognition.lang = 'en-US'; // Specify language if needed
//     recognition.interimResults = true;
 

 

    // recognition.addEventListener('result', e => {
    //     const transcript = Array.from(e.results)
    //         .map(result => result[0])
    //         .map(result => result.transcript)
    //         .join('')

    //         output.innerHTML = transcript;

    //     console.log(transcript);
        
        
    //     // textToWavAndBase64(transcript);
    // });

    // if (speech == true) {
    //     recognition.start();
    // }
// });
// function textToWavAndBase64(text) {
//     const speech = new SpeechSynthesisUtterance(text);
//     const synth = window.speechSynthesis;
//     const audioContext = new (window.AudioContext || window.webkitAudioContext)();
//     const dest = audioContext.createMediaStreamDestination();
//     const source = audioContext.createMediaStreamSource(dest.stream);
//     const mediaRecorder = new MediaRecorder(dest.stream);

//     let chunks = [];

//     mediaRecorder.ondataavailable = function(e) {
//         chunks.push(e.data);
//     };

//     mediaRecorder.onstop = function() {
//         const blob = new Blob(chunks, { 'type' : 'audio/wav' });
//         // Convert Blob to Base64
//         const reader = new FileReader();
//         reader.readAsDataURL(blob);
//         console.log(reader.result);

//             // const base64data = reader.result.split(',')[1]; // Extract the Base64 part
//             // console.log('Base64 String:', base64data);

//             // console.log(reader);
//             // // If you want to download it as a WAV file as well, keep this part
//             // const url = URL.createObjectURL(blob);
//             // downloadButton.href = url;
//             // downloadButton.download = 'speech.wav';
//             // downloadButton.disabled = false;
        
//     };

//     mediaRecorder.start();

//     // Connect the speech synthesis to the destination
//     synth.speak(speech);

//     speech.onend = () => {
//         mediaRecorder.stop();
//     };
// }
            
// function ontest(currentIndex){      
// $(function(){
//     let ip_address = "https://voice.bangla.gov.bd";
//     let port = "9394";
//     let socket = io(ip_address + ':' + port, {
//         transports: ['websocket'], // Use WebSocket transport
//         upgrade: false
//     });

//     socket.on('connect', function() {
//         console.log("Connected to socket");

//         let currentIndex = 0;
//          // Initial index value
//         // Set up the interval to send the message every 500 milliseconds
//         // setInterval(() => {
//             // alert('connect');
          
//          console.log( currentIndex);
//             // Send a structured message to the server
//             let message = {
//                 index: currentIndex,
//                 audio:"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",
//                 endOfStream: false
//             };
//             socket.emit('audio_transmit', message, function(response) {
//                 // Callback function to handle server response
//                 console.log('Server response:', response);
//             });

//              // Cycle the index (0, 1, 2)
//              currentIndex = (currentIndex + 1);  // This will cycle through 0, 1, 2
//         // }, 500); // 500 milliseconds interval
//     });

//     socket.on('result', function(data) {
//         console.log('Received result from server:', data);
//     });
//     socket.on('connect_error', function(error) {
//         console.error("Connection error:", error);
//     });

//     // Listen for 'result' event from server
// });

// }

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

        $('#startRecording_one').click(function() {
            $('#status').text('Starting recording...');

            if (recording) {
                mediaRecorder.stop();
                recording = false;
                return;
            }

            navigator.mediaDevices.getUserMedia({ audio: true })
                .then(stream => {
                    mediaRecorder = new MediaRecorder(stream);

                    mediaRecorder.ondataavailable = function(event) {
                        audioChunks.push(event.data);
                    };

                    mediaRecorder.onstop = () => {
                    const rawBlob = new Blob(audioChunks, { type: 'audio/webm' });

                    convertToPCM(rawBlob).then((pcmData) => {
                        const wavHeader = createWavHeader(pcmData.byteLength);
                        const wavBlob = new Blob([wavHeader, pcmData], { type: 'audio/wav' });

                        // Convert WAV Blob to Base64
                        const reader = new FileReader();
                        reader.readAsDataURL(wavBlob);
                        reader.onloadend = () => {
                            const base64String = reader.result.split(',')[1];
                            console.log('Base64 String:', base64String);
                                 let message = {
                                    index: currentIndex,
                                    audio: base64String,
                                    endOfStream: false
                                };
                                
                                socket.emit('audio_transmit', message, function(response) {
                                    console.log('Server response:', response);
                                });

                                currentIndex++;
                            // Now you can use this Base64 string for transmission or storage
                        };
                    });
                    
                };


                    mediaRecorder.start();

                    let intervalId = setInterval(() => {
                        if (recording) {
                            mediaRecorder.stop();
                            mediaRecorder.start();
                        }
                    }, 500);

                    recording = true;

                    $('#stopRecording').on('click', function() {
                        clearInterval(intervalId);
                        if (recording) {
                            mediaRecorder.stop();
                            recording = false;
                        }
                        $('#status').text('Recording stopped.');
                    });
                })
                .catch(error => {
                    console.error('Error accessing microphone:', error);
                    $('#status').text('Error accessing microphone.');
                });
        });
    });

    socket.on('result', function(data) {
        var array = data.output.predicted_words;
        console.log('Received result from server:', data);
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
                const channelData = buffer.getChannelData(0); // Assuming mono
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



</script>
    {{-- csrf Token --}}
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <!--begin::Page Vendors(used by this page)-->
    {{-- Includable JS Related Page --}}
    {{-- Toster Alert --}}
    <script src="{{ asset('js/pages/features/miscellaneous/toastr.js') }}"></script>
    <script>
        // $(document).ready(function() {
        //     $("#pagePreLoader").addClass('d-none');
        //     toastr.options = {
        //         "closeButton": true,
        //         "debug": false,
        //         "newestOnTop": true,
        //         "progressBar": false,
        //         "positionClass": "toast-top-right",
        //         "preventDuplicates": false,
        //         "onclick": null,
        //         "showDuration": "300",
        //         "hideDuration": "1000",
        //         "timeOut": "5000",
        //         "extendedTimeOut": "1000",
        //         "showEasing": "swing",
        //         "hideEasing": "linear",
        //         "showMethod": "fadeIn",
        //         "hideMethod": "fadeOut"
        //     };

        //     @if (Session::has('success'))
        //         toastr.success("{{ session('success') }}", "Success");
        //     @endif
        //     @if (Session::has('error'))
        //         toastr.error("{{ session('error') }}", "Error");
        //     @endif
        //     @if (Session::has('info'))
        //         toastr.info("{{ session('info') }}", "Info");
        //     @endif
        //     @if (Session::has('warning'))
        //         toastr.warning("{{ session('warning') }}", "Warning");
        //     @endif

        //     function setCookie(name, value, days) {
        //         var expires = "";
        //         if (days) {
        //             var date = new Date();
        //             date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        //             expires = "; expires=" + date.toUTCString();
        //         }
        //         document.cookie = name + "=" + (value || "") + expires + "; path=/";
        //     }

        //     function getCookie(name) {
        //         var nameEQ = name + "=";
        //         var ca = document.cookie.split(';');
        //         for (var i = 0; i < ca.length; i++) {
        //             var c = ca[i];
        //             while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        //             if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        //         }
        //         return null;
        //     }


        //     var current_notifiaction = $('.cs_notification_count').data('notification');
        //     var previous_notification = getCookie('previous_notification');

        //     if (previous_notification == null) {
        //         previous_notification = 0;
        //     }
        //     if(current_notifiaction<previous_notification)
        //     {
        //         previous_notification=0;
        //     }

        //     var notify = current_notifiaction - previous_notification;


        //     $.ajax({
        //         url: '{{ route('en2bn') }}',
        //         method: 'get',
        //         data: {
        //             notify: notify,
        //             _token: '{{ csrf_token() }}'
        //         },
        //         success: function(response) {
        //             if (response.status == 'success') {

        //                 //$('.perssion_list').html(response.html);

        //                 $('.cs_notification_count').text(response.notify);
        //             }
        //         }
        //     });


        //     if (current_notifiaction == previous_notification) {
        //         $('.cs_notification_count').hide();
        //     } else {
        //         $('.cs_notification').on('click', function() {
                    
        //             $('.cs_notification_count').hide();




        //             setCookie('previous_notification', current_notifiaction, 30);
        //         });

        //     }


        // });
    </script>

    @yield('scripts')
</body>
<!--end::Body-->

</html>

{
    chunk:"small_chunk"
    index: "0"
    output:{
        "predicted_words":
            [
             {
                "word": "hello",
             }
            ]
    }
}
{
    chunk:"large_chunk"
    index: "1"
    output:{
        "predicted_words":
            [
             {
                "word": "hello",
             }
            ]
    }
}

