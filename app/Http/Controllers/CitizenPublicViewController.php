<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CitizenComplain;
use Illuminate\Support\Facades\DB;

class CitizenPublicViewController extends Controller
{
    public function new(){
       // Clear any stored session parameters (if applicable)
        session()->forget('parameters');

        // Fetch data using Eloquent ORM
        $divisions = DB::table('division')->get();
        $caseTypes =DB::table('case_type')->get();

        // Prepare empty arrays for other models
        $zilla = [];
        $upazila = [];
        $geoCityCorporations = [];
        $geoMetropolitan = [];
        $geoThanas = [];

        // Pass the data to the view
        return view('citizen_public_view.new', [
            'division' => $divisions,
            'zilla' => $zilla,
            'upazila' => $upazila,
            'GeoCityCorporations' => $geoCityCorporations,
            'GeoMetropolitan' => $geoMetropolitan,
            'GeoThanas' => $geoThanas,
            'CaseType' => $caseTypes
        ]);
    }

    public function create(Request $request){

        // dd($request->all());
        $timeString = $request->time;
        // Convert to Carbon instance
        $time = Carbon::createFromFormat('h:i A', $timeString);
        // Format it to 24-hour format
        $formattedTime = $time->format('H:i:s');

        $message_data = "";
        $captcha = null;


        $citizen_complain = new CitizenComplain();

        $citizen_complain->category_id = $request->CaseType;
        $citizen_complain->name_bng = $request->name_bng;
        $citizen_complain->name_eng =  $request->name_bng;
        $citizen_complain->complain_details = $request->complain_details;
        $citizen_complain->mobile = $request->mobile;
        $citizen_complain->email = $request->email;
        $citizen_complain->national_idno = $request->national_idno;
        $citizen_complain->citizen_address = $request->citizen_address;
        $citizen_complain->divid = $request->division;
        $citizen_complain->zillaId = $request->zilla;
        $citizen_complain->upazilaid = $request->upazila;
        $citizen_complain->geo_citycorporation_id = $request->GeoCityCorporations ? $request->GeoCityCorporations:null;
        $citizen_complain->geo_metropolitan_id = $request->GeoMetropolitan ? $request->GeoMetropolitan:null;
        $citizen_complain->geo_thana_id = $request->GeoThanas ? $request->GeoThanas:null;
        $citizen_complain->geo_ward_id = null;
        $citizen_complain->complain_date = date('Y-m-d', strtotime($request->complain_date));
        $citizen_complain->time = $formattedTime;
        $citizen_complain->location = $request->location;
        $citizen_complain->subject = $request->subject;
        $citizen_complain->gender = $request->gender;
        $citizen_complain->complain_status = "initial";
        $citizen_complain->feedback = 'নাই';
        $citizen_complain->feedback_date = date('Y-m-d');
        $date = new \DateTime();

        $citizen_complain->created_by = 'Jafrin';
        $citizen_complain->created_date = $date->format("Y-m-d H:i:s");
        $citizen_complain->update_by = 'Jafrin';
        $citizen_complain->update_date = $date->format("Y-m-d");
        $citizen_complain->delete_status = 1;
        $citizen_complain->location_str = "test";

        $digits = 4;
        $randno =  str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
        $code =  "CP-".$citizen_complain->divid.'-'.$citizen_complain->complain_date.'-'.$randno;
        $citizen_complain->user_idno = $code;
        // $citizen_complain->complain_id =  UUID::generate(UUID::UUID_TIME, UUID::FMT_STRING, "a2ictz");
        $citizen_complain->complain_id = (string) Str::uuid();
        $date = date('Y-m-d-H-i-s');
        $filePrefix = "comp_";
        $new_file_name = $filePrefix.$date;
        $citizen_complain->file =  $new_file_name;
    
        $citizen_complain->save();
        if (!$citizen_complain->save()) {
            // If there are validation or saving errors, redirect back to the form
            session()->flash('error', 'An error occurred while creating the citizen complain.');
            return redirect()->route('citizen_public_view.new');  // Retain previous input data
        }else{
        session()->flash('success', 'Citizen complain was created successfully.');
        return redirect()->route('citizen_public_view.new');  // Retain previous input data
        }
        // $this->flash->success("citizen_complain was created successfully");
        // return $this->dispatcher->forward(array(
        //     "controller" => "citizen_complain",
        //     "action" => "index"
        // ));

    }
}
