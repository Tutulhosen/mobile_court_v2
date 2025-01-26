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
  <script src="https://cdn.socket.io/4.0.0/socket.io.min.js"></script>
<script>

$(function() {
    let ip_address = "https://voice.bangla.gov.bd";
    let port = "9394";
    let socket = io(ip_address + ':' + port, {
        transports: ['websocket'],
        upgrade: false
    });
   // let smallChunks = []; // Array to store the merged words from small chunks
    //let largeChunks = []; // Array to store the merged words from large chunks
    let mergedWords = []; // Array to store the merged words from large chunks
    let smallChunks = {}; // Dictionary to store small chunks
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
                    // setTimeout(() => {
                    const rawBlob = new Blob(audioChunks, { type: 'audio/webm' });

                    convertToPCM(rawBlob).then((pcmData) => {
                        const wavHeader = createWavHeader(pcmData.byteLength);
                        const wavBlob = new Blob([wavHeader, pcmData], { type: 'audio/wav' });

                        // Convert WAV Blob to Base64
                        const reader = new FileReader();
                        reader.readAsDataURL(wavBlob);
                        reader.onloadend = () => {
                            const base64String = reader.result.split(',')[1];
                            // console.log('Base64 String:', base64String);
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
        console.log(data);
        const array = data.output.predicted_words;
        const predictedWords = array.map(wordObj => wordObj.word).join(' ');
        $('#output').text(predictedWords);
     });
    // socket.on('result', function(data) {

        
    
    //     const array = data.output.predicted_words;
    
    //     if (data.chunk === 'small_chunk') {
    //      $('#output').text(finalSmallChunksOutput)
    //     }else{
    //         $('#output').text(finalSmallChunksOutput)
    //     }
    //    console.log('Received result from server:', data);
    // //    $('#output').text(finalSmallChunksOutput); 
    // // Example: Append each predicted word to the output div
    //     // array.forEach(wordInfo => {
    //     //     const wordElement = document.createElement('p');
    //     //     wordElement.textContent = wordInfo.word;
    //     //     console.log(wordElement);
    //     //     document.getElementById('output').appendChild(wordElement);
    //     // });


    //     // const predictedWords = data.output.predicted_words.map(wordObj => wordObj.word).join(' ');

    //     // if (data.chunk === 'small_chunk') {
    //     //     // Store small chunk words in the smallChunks array at the given index
    //     //     smallChunks[data.index] = predictedWords;
    //     // } else if (data.chunk === 'large_chunk') {
    //     //     const [startIndex, endIndex] = data.index.split(':').map(Number);

    //     //     // Ensure the array has enough elements to cover the range
    //     //     while (smallChunks.length <= endIndex) {
    //     //         smallChunks.push('');
    //     //     }

    //     //     // Replace the range of small chunks with the large chunk
    //     //     smallChunks.splice(startIndex, endIndex - startIndex + 1, predictedWords);

    //     //     // Display the updated merged output
    //     //     const finalSmallChunksOutput = smallChunks.join(' ');

    //     //     console.log('Updated small chunks array:', smallChunks); // Logs the current state of smallChunks array
    //     //     $('#output').text(finalSmallChunksOutput); // Display the final merged output
    //     // }


    // });

    

    socket.on('connect_error', function(error) {
        console.error("Connection error:", error);
        $('#status').text('Socket connection error.');
    });
    // const numSamples = 44100 * 0.5; // 500 milliseconds
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