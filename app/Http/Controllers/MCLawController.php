<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MCLawController extends Controller
{
    public function index()
    {
        // return['mere'=> 'klsadf'];
        $data = DB::table('mc_law')->orderBy('id', 'asc')->get();
        return $this->sendResponse($data, 'সফলভাবে তথ্য পাওয়া গেছে');
    }

    public function store(Request $request)
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
            'law_type_id' => 7,
            'is_rules' => isset($request['is_rules']) ? 1 : 0
        ]);
        return $this->sendResponse($inserted, 'সফলভাবে সংরক্ষিত হয়েছে');
    }


    public function edit($id)
    {
        // return['mere'=> 'klsadf'];
        $data = DB::table('mc_law')->where('id', '=', $id)->first();
        return $this->sendResponse($data, 'সফলভাবে তথ্য পাওয়া গেছে');
    }

    public function update(Request $request, $id)
    {
        $requestData = $request->all();
        $request = $requestData['body_data'];
    
        $updated = DB::table('mc_law')
            ->where('id', $id)
            ->update([
                'title' => $request['title'],
                'law_no' => $request['law_no'],
                'description' => $request['description'] ?? null,
                'bd_law_link' => $request['bd_law_link'] ?? null,
                'created_by' => 'Jafrin',
                'update_by' => 'Jafrin',
                'update_date' => now(),
                'delete_status' => $request['delete_status'],
                'law_type_id' => 7,
                'is_rules' => isset($request['is_rules']) ? 1 : 0
            ]);
    
        return $this->sendResponse($updated, 'সফলভাবে পরিবর্তিত হয়েছে');
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
}
