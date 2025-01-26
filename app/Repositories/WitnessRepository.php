<?php


namespace App\Repositories;

use App\Models\Prosecution;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class WitnessRepository
{

    public static function saveWitnesses($data,$prosecutionID){
             
            //  if(!empty($data['prosecutionID'])){
            //     $prosecutionID = $data['prosecutionID'];
            //  }else{
            //      $prosecutionID = $data['prosecutionId'];
            //  }
            
             $prosecution =  Prosecution::find($prosecutionID);

             $errorMessage = "";
             // witness validation
            if($data["witness1_name"]=="" || $data["witness2_name"]=="" || $data["witness1_custodian_name"]=="" || $data["witness2_custodian_name"]==""){
                return false;
            }
       
            if ($prosecution) {
                $prosecution->witness1_name = $data["witness1_name"]  ?? '';
                $prosecution->witness1_custodian_name = $data["witness1_custodian_name"]  ?? '';
                $prosecution->witness1_mobile_no = $data["witness1_mobile_no"]  ?? '';
                $prosecution->witness1_mother_name = $data["witness1_mother_name"]  ?? '';
                $prosecution->witness1_age = $data["witness1_age"]  ?? '';
                $prosecution->witness1_nationalid = $data["witness1_nationalid"]  ?? '';
                $prosecution->witness1_address = $data["witness1_address"]  ?? '';
                $prosecution->witness1_imprint1 =$data["witness1_LEFT_THUMB_BMP"]  ?? '';
                $prosecution->witness1_imprint2 =$data["witness1_RIGHT_THUMB_BMP"]  ?? '';
     
                $prosecution->witness2_name = $data["witness2_name"]  ?? '';
                $prosecution->witness2_custodian_name = $data["witness2_custodian_name"]  ?? '';
                $prosecution->witness2_mobile_no = $data["witness2_mobile_no"]  ?? '';
                $prosecution->witness2_mother_name = $data["witness2_mother_name"]  ?? '';
                $prosecution->witness2_age = $data["witness2_age"]  ?? '';
                $prosecution->witness2_nationalid = $data["witness2_nationalid"]  ?? '';
                $prosecution->witness2_address = $data["witness2_address"]  ?? '';
                $prosecution->witness2_imprint1 = $data["witness2_LEFT_THUMB_BMP"]  ?? '';
                $prosecution->witness2_imprint2 = $data["witness2_RIGHT_THUMB_BMP"]  ?? '';
     
                $prosecution->case_status = 3;
                
              
            }
 
            if (!$prosecution->save()) {
              
                throw new \Exception('Failed to save criminal data.');
            }else{
               
                $pre_repeat_crime = $data["witness1_repeat_crime"]  ?? '';  //  if already exist 2
                $witness1_Ltemplate = $data["witness1_LEFT_THUMB"]  ?? '';
                $witness1_Rtemplate = $data["witness1_RIGHT_THUMB"] ?? '';
     
                $witness2_Ltemplate = $data["witness2_LEFT_THUMB"]  ?? '';
                $witness2_Rtemplate = null; //$data["witness2_RIGHT_THUMB"];
              
                if($pre_repeat_crime != '2' ){  // if not exist witness1
     
                    $user_id = $prosecution->id;
                    $Usertype = 2;
     
                    $LEFT_THUMB1 =  $witness1_Ltemplate ?? '';
                     $RIGHT_THUMB1 = $witness1_Rtemplate  ?? '';
     
                    $dtarrayinsert = array();
                    if ($LEFT_THUMB1 != "" && $LEFT_THUMB1 != null) {
                        $dtarrayinsert[] = array(
                            'FingerName' => "leftthumb",
                            'FpTemplate' => $LEFT_THUMB1 
                        );
                    }
                    if ($RIGHT_THUMB1 != "" && $RIGHT_THUMB1 != null) {
                    
     
                        $dtarrayinsert[] = array(
                            'FingerName' => "rightthumb",
                            'FpTemplate' => $RIGHT_THUMB1
                        );
                        
                    }
              
                    $datatoPass = array(
                        "UserId" => $user_id,
                        "Usertype" => $Usertype  ?? '',
                        "Fingerprints" => $dtarrayinsert  ?? ''
                    );
     
                    if (is_array($dtarrayinsert) && !empty($dtarrayinsert)) {
                        // $return_fpmessage_witness1  = $this->utilityService->saveFpToserver($datatoPass);
                    }
                  
                }
               
                $pre_repeat_crime = $data["witness2_repeat_crime"] ?? " ";  //  if already exist 2
            
                if($pre_repeat_crime != '2' ) {  // if not exist
                    $user_id = $prosecution->id;
                    $Usertype = 2;
                    $LEFT_THUMB1 = $witness2_Ltemplate  ?? '';
                    $RIGHT_THUMB1 = $witness2_Rtemplate  ?? '';
     
              
                    $dtarrayinsert_witness2 = array();
                    if ($LEFT_THUMB1 != "" && $LEFT_THUMB1 != null) {
                        $dtarrayinsert_witness2[] = array(
                            'FingerName' => "leftthumb",
                            'FpTemplate' => $LEFT_THUMB1
                        );
                    }
                   
                    if ($RIGHT_THUMB1 != "" && $RIGHT_THUMB1 != null) {
     
                        $dtarrayinsert_witness2[] = array(
                            'FingerName' => "rightthumb",
                            'FpTemplate' => $RIGHT_THUMB1
                        );
                    }
                    
                    $datatoPass_witness2 = array(
                        "UserId" => $user_id,
                        "Usertype" => $Usertype  ?? '',
                        "Fingerprints" => $dtarrayinsert_witness2  ?? ''
                    );
                    if (is_array($dtarrayinsert_witness2) && !empty($dtarrayinsert_witness2)) {
                        // $return_fpmessage_witness2 =  $this->utilityService->saveFpToserver($datatoPass_witness2);
                    }
                  
                }
                // return $criminal;

                 
            }
            // if (!$prosecution->save()) {
            //     // foreach ($prosecution->getMessages() as $message) {
            //     //     $errorMessage .= $message;
            //     // }
            //     throw new \Exception('Failed to save criminal data.');
            // }else{
            //     $pre_repeat_crime = $data["witness1_repeat_crime"]  ?? '';  //  if already exist 2
            //     $witness1_Ltemplate = $data["witness1_LEFT_THUMB"]  ?? '';
            //     $witness1_Rtemplate = $data["witness1_RIGHT_THUMB"] ?? '';
     
            //     $witness2_Ltemplate = $data["witness2_LEFT_THUMB"]  ?? '';
            //     $witness2_Rtemplate = null; //$data["witness2_RIGHT_THUMB"];
     
            //     if($pre_repeat_crime != '2' ){  // if not exist witness1
     
            //         $user_id = $prosecution->id;
            //         $Usertype = 2;
     
            //         $LEFT_THUMB1 =  $witness1_Ltemplate ?? '';
            //         $RIGHT_THUMB1 = $witness1_Rtemplate  ?? '';
     
            //         $dtarrayinsert = array();
            //         if ($LEFT_THUMB1 != "" && $LEFT_THUMB1 != null) {
            //             $dtarrayinsert[] = array(
            //                 'FingerName' => "leftthumb",
            //                 'FpTemplate' => $LEFT_THUMB1 
            //             );
            //         }
     
            //         if ($RIGHT_THUMB1 != "" && $RIGHT_THUMB1 != null) {
     
            //             $dtarrayinsert[] = array(
            //                 'FingerName' => "rightthumb",
            //                 'FpTemplate' => $RIGHT_THUMB1
            //             );
            //         }
            //         $datatoPass = array(
            //             "UserId" => $user_id,
            //             "Usertype" => $Usertype  ?? '',
            //             "Fingerprints" => $dtarrayinsert  ?? ''
            //         );
     
            //         if (is_array($dtarrayinsert) && !empty($dtarrayinsert)) {
            //             // $return_fpmessage_witness1  = $this->utilityService->saveFpToserver($datatoPass);
            //         }
            //     }
            //     $pre_repeat_crime = $data["witness2_repeat_crime"];  //  if already exist 2
     
            //     if($pre_repeat_crime != '2' ) {  // if not exist
            //         $user_id = $prosecution->id;
            //         $Usertype = 2;
            //         $LEFT_THUMB1 = $witness2_Ltemplate  ?? '';
            //         $RIGHT_THUMB1 = $witness2_Rtemplate  ?? '';
     
            //         $dtarrayinsert_witness2 = array();
            //         if ($LEFT_THUMB1 != "" && $LEFT_THUMB1 != null) {
            //             $dtarrayinsert_witness2[] = array(
            //                 'FingerName' => "leftthumb",
            //                 'FpTemplate' => $LEFT_THUMB1
            //             );
            //         }
     
            //         if ($RIGHT_THUMB1 != "" && $RIGHT_THUMB1 != null) {
     
            //             $dtarrayinsert_witness2[] = array(
            //                 'FingerName' => "rightthumb",
            //                 'FpTemplate' => $RIGHT_THUMB1
            //             );
            //         }
            //         $datatoPass_witness2 = array(
            //             "UserId" => $user_id,
            //             "Usertype" => $Usertype  ?? '',
            //             "Fingerprints" => $dtarrayinsert_witness2  ?? ''
            //         );
            //         if (is_array($dtarrayinsert_witness2) && !empty($dtarrayinsert_witness2)) {
            //             // $return_fpmessage_witness2 =  $this->utilityService->saveFpToserver($datatoPass_witness2);
            //         }
            //     }
     
            // }
          
            return true;
     
         
    }

    public static function saveWitnessesOld($data){
        $prosecutionID = $data['prosecutionID'];
        $prosecution =  Prosecution::find($prosecutionID);

       $errorMessage = "";
//       witness validation
       if($data["witness1_name"]=="" || $data["witness2_name"]=="" || $data["witness1_custodian_name"]=="" || $data["witness2_custodian_name"]==""){
           return false;
       }
       if ($prosecution) {
           $prosecution->witness1_name = $data["witness1_name"];
           $prosecution->witness1_custodian_name = $data["witness1_custodian_name"];
           $prosecution->witness1_mobile_no = $data["witness1_mobile_no"];
           $prosecution->witness1_mother_name = $data["witness1_mother_name"];
           $prosecution->witness1_age = $data["witness1_age"];
           $prosecution->witness1_nationalid = $data["witness1_nationalid"];
           $prosecution->witness1_address = $data["witness1_address"];
           $prosecution->witness1_imprint1 =$data["witness1_LEFT_THUMB_BMP"];
           $prosecution->witness1_imprint2 =$data["witness1_RIGHT_THUMB_BMP"];

           $prosecution->witness2_name = $data["witness2_name"];
           $prosecution->witness2_custodian_name = $data["witness2_custodian_name"];
           $prosecution->witness2_mobile_no = $data["witness2_mobile_no"];
           $prosecution->witness2_mother_name = $data["witness2_mother_name"];
           $prosecution->witness2_age = $data["witness2_age"];
           $prosecution->witness2_nationalid = $data["witness2_nationalid"];
           $prosecution->witness2_address = $data["witness2_address"];
           $prosecution->witness2_imprint1 = $data["witness2_LEFT_THUMB_BMP"];
           $prosecution->witness2_imprint2 = $data["witness2_RIGHT_THUMB_BMP"];

           $prosecution->case_status = 3;
         
       }

     
       if (!$prosecution->save()) {
           foreach ($prosecution->getMessages() as $message) {
               $errorMessage .= $message;
           }

       }else{
           $pre_repeat_crime = $data["witness1_repeat_crime"];  //  if already exist 2
           $witness1_Ltemplate = $data["witness1_LEFT_THUMB"];
           $witness1_Rtemplate = $data["witness1_RIGHT_THUMB"];

           $witness2_Ltemplate = $data["witness2_LEFT_THUMB"];
           $witness2_Rtemplate = null; //$data["witness2_RIGHT_THUMB"];

           if($pre_repeat_crime != '2' ){  // if not exist witness1

               $user_id = $prosecution->id;
               $Usertype = 2;

               $LEFT_THUMB1 =  $witness1_Ltemplate;
               $RIGHT_THUMB1 = $witness1_Rtemplate;

               $dtarrayinsert = array();
               if ($LEFT_THUMB1 != "" && $LEFT_THUMB1 != null) {
                   $dtarrayinsert[] = array(
                       'FingerName' => "leftthumb",
                       'FpTemplate' => $LEFT_THUMB1
                   );
               }

               if ($RIGHT_THUMB1 != "" && $RIGHT_THUMB1 != null) {

                   $dtarrayinsert[] = array(
                       'FingerName' => "rightthumb",
                       'FpTemplate' => $RIGHT_THUMB1
                   );
               }
               $datatoPass = array(
                   "UserId" => $user_id,
                   "Usertype" => $Usertype,
                   "Fingerprints" => $dtarrayinsert
               );

               if (is_array($dtarrayinsert) && !empty($dtarrayinsert)) {
                   // $return_fpmessage_witness1  = $this->utilityService->saveFpToserver($datatoPass);
               }
           }
           $pre_repeat_crime = $data["witness2_repeat_crime"];  //  if already exist 2

           if($pre_repeat_crime != '2' ) {  // if not exist
               $user_id = $prosecution->id;
               $Usertype = 2;
               $LEFT_THUMB1 = $witness2_Ltemplate;
               $RIGHT_THUMB1 = $witness2_Rtemplate;

               $dtarrayinsert_witness2 = array();
               if ($LEFT_THUMB1 != "" && $LEFT_THUMB1 != null) {
                   $dtarrayinsert_witness2[] = array(
                       'FingerName' => "leftthumb",
                       'FpTemplate' => $LEFT_THUMB1
                   );
               }

               if ($RIGHT_THUMB1 != "" && $RIGHT_THUMB1 != null) {

                   $dtarrayinsert_witness2[] = array(
                       'FingerName' => "rightthumb",
                       'FpTemplate' => $RIGHT_THUMB1
                   );
               }
               $datatoPass_witness2 = array(
                   "UserId" => $user_id,
                   "Usertype" => $Usertype,
                   "Fingerprints" => $dtarrayinsert_witness2
               );
               if (is_array($dtarrayinsert_witness2) && !empty($dtarrayinsert_witness2)) {
                   // $return_fpmessage_witness2 =  $this->utilityService->saveFpToserver($datatoPass_witness2);
               }
           }

       }
     
       return true;

    
     }
}