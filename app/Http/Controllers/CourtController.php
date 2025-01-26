<?php

namespace App\Http\Controllers;

use App\Models\Court;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourtController extends Controller
{
    //

    public function index()
    {
        return view('court.openclose');
    }

    public function create_events(Request $request)
    {

        $status = $request->status;
        $date = $request->start;
        $userinfo = globalUserInfo(); // gobal user
        $str_message = "";
        if ($status == "1") {

            $exist_court = DB::table('courts')
                ->where('magistrate_id', $userinfo->id)
                ->where(function ($query) use ($date) {
                    $query->where('date', $date)
                        ->orWhere('status', 1);
                })
                ->get();

            if (count($exist_court) > 0) {

                $strmessage = "একাধিক কর্মসূচি  প্রণয়ন করা যাবে না [আপনার পূর্বের একটি কোর্ট খোলা রয়েছে ]। ";
                $str_message = [
                    'msg' => $strmessage,
                    'success' => false,
                ];
                // return $str_message;
                return response()->json($str_message, 200);

            }
        } elseif ($status == "2") {
            $strmessage = "এই তারিখে এখনো কোনো কোর্ট খোলা হয়নি,অতএব কোর্ট বন্ধ করা যাচ্ছে না।";
            $str_message = [
                'msg' => $strmessage,
                'success' => false,
            ];
            // return $str_message;
            return response()->json($str_message, 200);
        }

        DB::table('courts')->insert([
            'date' => $request->start,
            'status' => $request->status,
            'title' => $request->title,
            'court_id' => "test",
            // 'status_court' =>  2,
            'magistrate_id' => $userinfo->id,
            'created_by' => $userinfo->name,
            'created_date' => date('Y-m-d'),
            'update_by' => $userinfo->name,
            'update_date' => date('Y-m-d'),
            'delete_status' => 1,
        ]);

        $str_message = "";
        if ($request->status == 1) { // open
            $strmessage = "আপনার কোর্টের কর্মসূচি খোলা  হয়েছে ।";
            $str_message = [
                'msg' => $strmessage,
                'success' => true,
            ];
        } else if ($request->status == 2) { //  close
            $strmessage = "আপনার কোর্টের কর্মসূচি বন্ধ করা হয়েছে ।";
            $str_message = [
                'msg' => $strmessage,
                'success' => true,
            ];
        } else { // initiate
            $strmessage = "আপনার কোর্টের কর্মসূচি প্রণয়ন করা হয়েছে ।";
            $str_message = [
                'msg' => $strmessage,
                'success' => true,
            ];
        }
        return response()->json($str_message, 200);

    }
    //  public function create_events(Request $request){

    //   DB::table('court')->insertGetId([
    //      'date' =>   $request->start,
    //      'status' => $request->status,
    //      'title' =>  $request->title,
    //      'court_id' =>  "test",
    //      // 'status_court' =>  2,
    //      'magistrate_id' =>1,
    //      'created_by' =>  'Jafrin',
    //      'created_date' => date('Y-m-d') ,
    //      'update_by' =>  'Jafrin',
    //      'update_date' => date('Y-m-d'),
    //      'delete_status' =>  1
    //   ]);
    //   $str_message = "";
    //   if($request->status == 1){  // open
    //       $str_message = "আপনার কোর্টের কর্মসূচি খোলা করা হয়েছে ।";
    //   }else if($request->status == 2){ //  close
    //       $str_message = "আপনার কোর্টের কর্মসূচি বন্ধ করা হয়েছে ।";
    //   }else{ // initiate
    //       $str_message = "আপনার কোর্টের কর্মসূচি প্রণয়ন করা হয়েছে ।";
    //   }
    //   return response()->json($str_message, 200);

    //  }

    public function getcourtdataAll()
    {
        $userinfo = globalUserInfo(); // gobal user
        $court = DB::table('courts')->where('magistrate_id', $userinfo->id)->get();
        $className = "";
        $childs = array();
        foreach ($court as $value) {
            if ($value->status == 1) {
                $className = 'opencourt'; // court open
            } elseif ($value->status == 2) {
                $className = 'colsedcourt'; // court open
            } else {
                $className = "";
            }

            $childs[] = array(
                'id' => $value->id,
                'start' => $value->date,
                'end' => $value->date,
                'title' => $value->title,
                'status' => $value->status,
                'className' => $className,

            );
        }

        return response()->json($childs);
    }
    //  public function getcourtdataAll(){
    //     $court =   DB::table('court')->where('magistrate_id',1)->get();

    //     $className = "";
    //     $childs = array();
    //     foreach ($court as $value) {
    //         if($value->status == 1){
    //             $className = 'opencourt'; // court open
    //         }elseif($value->status == 2){
    //             $className = 'colsedcourt'; // court open
    //         }else{
    //             $className = "";
    //         }

    //         $childs[] = array(
    //             'id'        => $value->id,
    //             'start'     =>$value->date,
    //             'end'       =>$value->date,
    //             'title'     =>$value->title,
    //             'status'    =>  $value->status,
    //             'className' => $className,

    //         );
    //     }

    //     return response()->json($childs);
    //  }

    public function update_events(Request $request)
    {

        $userinfo = globalUserInfo(); // gobal user
        $id = $request->id;
        $date = $request->start;
        $court = DB::table('courts')->find($id);

        if (!$court) {
            $childs[] = array(
                'msg' => "Court was not found",
            );
            return response()->json($childs);
        }
        if ($request->status == 1) { // open
            $exist_court = DB::table('courts')
                ->where('magistrate_id', $userinfo->id)
                ->Where('status', 1)
                ->get();
            if (count($exist_court) > 0) {
                $strmessage = "একাধিক কর্মসূচি  প্রণয়ন করা যাবে না । ";
                $str_message = [
                    'msg' => $strmessage,
                    'success' => false,
                ];
                return $str_message;
            }

        }

        // if($request->status == 1){
        //     $date=date('Y-m-d');

        //     // if($request->start==$date){
        //         // $phql1 =  DB::table('court')->where('magistrate_id', $userinfo->id)->where('date',$request->start)->where('status',1)->get();
        //         $phql1 =  DB::table('court')->where('magistrate_id', $userinfo->id)->where('status',1)->get();
        //         $exist_court = $phql1;
        //         if(count($exist_court) > 0){
        //             return response()->json("একাধিক কর্মসূচি  প্রণয়ন করা যাবে না ।");
        //         }
        //     // }
        //     // else{
        //     //     $phql1 =  DB::table('court')->where('magistrate_id',$userinfo->id)->where('status',1)->get();
        //     //     $exist_court = $phql1;
        //     //     if(count($exist_court) > 0){
        //     //         return response()->json("একাধিক কর্মসূচি  প্রণয়ন করা যাবে না ।   ");
        //     //     }
        //     // }
        // }

        $data = array(
            'id' => $id,
            'date' => $request->start,
            'title' => $request->title,
            'status' => $request->status,
            'magistrate_id' => $userinfo->id,
        );
        $update = DB::table('courts')->where('id', $id)->update($data);
        if (!$update) {
            // return response()->json("কর্মসূচি  পরিবর্তন  করা যাবে না । ");
            $strmessage = "কর্মসূচি  পরিবর্তন  করা যাবে না ।";
            $str_message = [
                'msg' => $strmessage,
                'success' => false,
            ];
            return $str_message;
        } else {
            $str_message = '';

            if ($request->status == 1) { // open
                $strmessage = "আপনার কোর্টের কর্মসূচি খোলা  হয়েছে ।";
                $str_message = [
                    'msg' => $strmessage,
                    'success' => true,
                ];

            } else if ($request->status == 2) { //  close
                $strmessage = "আপনার কোর্টের কর্মসূচি বন্ধ করা হয়েছে ।";
                $str_message = [
                    'msg' => $strmessage,
                    'success' => true,
                ];
            } else { // initiate
                $strmessage = "আপনার কোর্টের কর্মসূচি প্রণয়ন করা হয়েছে ।";
                $str_message = [
                    'msg' => $strmessage,
                    'success' => true,
                ];
            }
            return response()->json($str_message);
        }

        $str_message = [
            'msg' => "Court Update successfully ",
            'success' => true,
        ];

        return response()->json($str_message);

    }

    //  public function update_events(Request $request){
    //     // return $request->start;
    //     $id = $request->id;
    //     $court =DB::table('court')->find($id);

    //     if (!$court) {
    //         $childs[] = array(
    //             'msg' => "Court was not found"
    //         );
    //         return response()->json( $childs );
    //     }

    //         if($request->status == 1){
    //             $date=date('Y-m-d');

    //             if($request->start==$date){
    //                 $phql1 =  DB::table('court')->where('magistrate_id',1)->where('date',$request->start)->where('status',1)->get();
    //                 $exist_court = $phql1;
    //                 if(count($exist_court) > 0){
    //                     return response()->json("একাধিক কর্মসূচি  প্রণয়ন করা যাবে না ।");
    //                 }
    //             }else{
    //                 $phql1 =  DB::table('court')->where('magistrate_id',1)->where('status',1)->get();
    //                 $exist_court = $phql1;
    //                 if(count($exist_court) > 0){
    //                     return response()->json("একাধিক কর্মসূচি  প্রণয়ন করা যাবে না ।   ");
    //                 }
    //             }
    //         }

    //         $data=array(
    //             'id' => $id,
    //             'date' => $request->start,
    //             'title' => $request->title,
    //             'status' => $request->status,
    //             // 'court_status'  => $request->status,
    //             'magistrate_id' =>1
    //         );
    //      $ddd=   DB::table('court')->where('id', $id)->update($data);
    //     if (!$ddd) {
    //         // return response()->json("একাধিক কর্মসূচি  প্রণয়ন করা যাবে না ।   ");
    //     }else{
    //         $str_message='';

    //         if($request->status == 1){  // open
    //             $str_message = "আপনার কোর্টের কর্মসূচি খোলা করা হয়েছে ।";
    //         }else if($request->status == 2){ //  close
    //             $str_message = "আপনার কোর্টের কর্মসূচি বন্ধ করা হয়েছে ।";
    //         }else{ // initiate
    //             $str_message = "আপনার কোর্টের কর্মসূচি প্রণয়ন করা হয়েছে ।";
    //         }
    //         return response()->json( $str_message);
    //     }
    //     return response()->json("Court Update successfully ");

    //  }

    public function delete_events(Request $request)
    {

        $id = $request->id;
        $court = DB::table('courts')->where('id', $id)->first();
        $is_use = null;

        if ($court) {
            $is_use = DB::table('prosecutions')->where('court_id', $court->id)->first(); //Prosecution::findFirstBycourt_id($court->id);
        }

        $childs[] = array();

        if (!$court) {
            $childs = array(
                'msg' => "User was not found",
            );
        }

        if (!$is_use) {
            $delete = DB::table('courts')->where('id', $id)->delete();
            if (!$delete) {
                // $childs = array(
                //     'msg' => "কোর্ট বাতিল করা যাবে না "
                // );
                $childs = [
                    'msg' => "কোর্ট বাতিল করা যাবে না ",
                    'success' => true,
                ];
            } else {
                $childs = array(
                    'msg' => "কোর্ট বাতিল ",
                    'success' => true,
                );
            }
        } else {
            $childs = array(
                'msg' => "কোর্ট বাতিল করা যাবে না। ইতমধ্যে মোবাইল কোর্ট কার্যক্রম পরিচালনা হয়েছে।",
                'success' => true,
            );
        }
        return response()->json($childs);
    }

    public function delete_eventssdfgsdf(Request $request)
    {

        $id = $request->id;
        $court = DB::table('court')->find($id);

        $is_use = null;
        if ($court) {
            $is_use = DB::table('prosecution')->where('court_id', $court->id)->first(); //Prosecution::findFirstBycourt_id($court->id);
        }

        $childs[] = array();

        if (!$court) {
            $childs = array(
                'msg' => "User was not found",
            );
        }

        $court->delete();
        $childs = array(
            'msg' => "কোর্ট বাতিল ",
        );
        // if(!$is_use){

        //     if ($court->delete()) {
        //         $childs = array(
        //             'msg' => "কোর্ট বাতিল "
        //         );
        //     } else {

        //         $childs = array(
        //             'msg' => "কোর্ট বাতিল করা যাবে না"
        //         );
        //     }
        // }else{
        //     $childs = array(
        //         'msg' => "কোর্ট বাতিল করা যাবে না। ইতমধ্যে মোবাইল কোর্ট কার্যক্রম পরিচালনা হয়েছে।"
        //     );
        // }
        return response()->json($childs);

    }

    public function getScheduleByMagistrateId(Request $request, $id)
    {
        $childs = array();
        $date = date('Y-m-d');
        if (request()->ajax()) {
            $magistrateId = $id;
            $msg = "";
            if ($magistrateId) {
                $courts = Court::where('magistrate_id', $magistrateId)
                    ->where('date', $date)
                    ->where('status', 1)
                    ->select('id as court_id')
                    ->get();
                if (count($courts) > 0) {
                    $childs[] = array('court_id' => $courts[0]["court_id"], 'msg' => "কোর্ট  খোলা । ");
                } else {
                    $childs[] = array('court_id' => "", 'msg' => "আজকের দিনে কোন কর্মসূচী নাই ।");
                }

            }

        }
        return response()->json($childs);

    }
}
