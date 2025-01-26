<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class McLawAndSectionApiController extends Controller
{
    public function mc_law_section()
    {
        // return['mere'=> 'klsadf'];
        $data = DB::table('mc_law')->get();
        return $this->sendResponse($data, 'সফলভাবে তথ্য পাওয়া গেছে');
    }
    public function mc_law_section_store(Request $request)
    {
        $requestData = $request->all();
        $request= $requestData['body_data'];
        $punishment_type = DB::table('punishment_type')->where('id', $request['punishment_type'])->first();
        $punishment_type_des = $punishment_type ? $punishment_type->description : '';
        // return['bfdd'=> $request];
  
        $inserted = DB::table('mc_section')->insert([
           'law_id' => $request['law_id'],
           'sec_description' => $request['sec_description'],
           'sec_number' => $request['sec_number'],
           'punishment_sec_number' => $request['punishment_sec_number'],
           'sec_title' => $request['sec_title'],
           'punishment_des' => $request['punishment_des'],
           'punishment_type' => $request['punishment_type'],
           'max_jell' => $request['max_jell'],
           'min_jell' => $request['min_jell'],
           'max_fine' => $request['max_fine'],
           'min_fine' => $request['min_fine'],
           'next_jail' => $request['next_jail'],
           'next_fine' => $request['next_fine'],
           'extra_punishment' => $request['extra_punishment'],
        //    'comment' => $request['comment'],
           'punishment_type_des' => $punishment_type_des,
           'created_by' => 'Jafrin',
           'created_date' => now(),
           'update_by' => 'Jafrin',
           'update_date' => now(),
           'delete_status' => 1,
        ]);
  
        return $this->sendResponse($inserted, 'সফলভাবে সংরক্ষিত হয়েছে');
    }

    static function sendResponse($result, $message)
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }

    public function mc_law_store(Request $request)
    {
        $requestData = $request->all();
        $request = $requestData['body_data'];
        $inserted = DB::table('mc_law')->insert([
            'title' => $request['title'],
            'law_no' => $request['law_no'],
            'description' => $request['description'] ?? null,
            'bd_law_link' => $request['bd_law_link'] ?? null,
            'created_by' => 'Jafrin',
            'created_date' => now(),
            'update_by' => 'Jafrin',
            'update_date' => now(),
            'delete_status' => 1,
            'is_rules' => isset($request['is_rules']) ? 1 : 0
        ]);
        return $this->sendResponse($inserted, 'সফলভাবে সংরক্ষিত হয়েছে');
    }
}
