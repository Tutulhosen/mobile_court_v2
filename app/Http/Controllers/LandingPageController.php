<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Audio\Wav;
class LandingPageController extends Controller
{
    //show landing page
    public function index()
    {

        // return $data;
        return view('index');   
    }

    public function show_log_in_page()
    {

        return view('loginPage');
    }

    
    //login User
    public function logined_in(Request $request){
        if (Auth::check()) {

            return redirect()->route('dashboard');
        } 
 
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        // Auth::attempt(['email' => $request->email, 'password' => $request->password]);
       
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
       
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['success' => 'Successfully logged in!']);
        } elseif (Auth::attempt(['username' => $request->email, 'password' => $request->password])) {
            return response()->json(['success' => 'Successfully logged in!']);
        } elseif (Auth::attempt(['citizen_nid' => $request->email, 'password' => $request->password])) {
            return response()->json(['success' => 'Successfully logged in!']);
        }

        return response()->json([
            'error' => 'Please Enter Valid Credential!',
            'nothi_msg' => 'সঠিক ইমেইল, মোবাইল নং অথবা পাসওয়ার্ড প্রদান করুন ',

        ]);
    }


    //logout user
    //logout user
    public function cslogout(Request $request){
        Auth::logout();
        $url=getCommonModulerBaseUrl();

        // $callbackurl = url($url);
        $zoom_join_url = DOPTOR_ENDPOINT() . '/logout?' . 'referer=' . base64_encode($url.'custom-logout');
        return redirect($zoom_join_url); 
        

    }
    public function home_redirct(){
        $url=getCommonModulerBaseUrl();
        $callbackurl = url($url.'doptor/court');
        $zoom_join_url = DOPTOR_ENDPOINT() . '/logout?' . 'referer=' . base64_encode($callbackurl);
        // return redirect($zoom_join_url); 
        Auth::logout();
        return redirect( $callbackurl);
        

    }

    public function audio_file_get(Request $request){
     // Validate the request to ensure an audio file is provided
    //  $request->validate([
    //     'audio' => 'required|base64mimes:audio/wav' // Validation for Base64 audio input
    // ]);

    // Get Base64-encoded audio data from the request
     // Get Base64-encoded audio data from the request
     $base64Audio = $request->input('audio');

     // Decode the Base64-encoded data
     $audio = base64_decode($base64Audio);

     // Define the directory and path for the file
     $directory = 'audio_files'; // New directory inside public
     $filePath = public_path($directory); // Get the full path to the directory

     // Ensure the directory exists
     if (!File::exists($filePath)) {
         File::makeDirectory($filePath, 0755, true); // Create the directory if it doesn't exist
     }

     // Generate a unique filename
     $filename = 'audio_' . Str::random(10) . '.wav';

     // Define the full path for the file
     $fileFullPath = $filePath . '/' . $filename;

     // Save the file to the specified directory
     File::put($fileFullPath, $audio);

     // Generate the full URL for the file
     $fileUrl = asset($directory . '/' . $filename);

     // Get the file's contents
     $fileContent = File::get($fileFullPath);

     // Encode the file content to Base64
     $base64Data = base64_encode($fileContent);
     $cleanBase64Data = str_replace(['\r', '\n'], '', $base64Data);
     // Return both the file URL and the Base64-encoded data
     return response()->json([
         'message' => 'File saved and encoded successfully',
         'file_url' => $fileUrl,
         'base64_data' => $cleanBase64Data
     ]);
    }



}
