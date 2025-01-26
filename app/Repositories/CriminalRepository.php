<?php


namespace App\Repositories;

use App\Models\Criminal;
use App\Models\Prosecution;
use App\Models\ProsecutionDetail;
use App\Models\CriminalConfession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\CriminalConfessionLawsbroken;


class CriminalRepository
{


        public static function saveCriminals($criminals, $caseinfo, $loginUserInfo){
            // save criminals
              if (is_array($criminals) && !empty($criminals)) {
                     foreach ($criminals as $crm){
                        $criminal=self::saveCriminal($caseinfo,$crm, $loginUserInfo);
                     }
             }
        }

        public static function saveCriminal($caseinfo,$_criminal,$loginUserInfo) {

            $criminal = null;
            if($_criminal["criminalID"] != null && $_criminal["criminalID"] > 0) {
                $criminal = Criminal::find($_criminal["criminalID"]);
            }else {
                $criminal = new Criminal();
                $criminal->created_by = $loginUserInfo['email'];
                $criminal->created_at = now();
            }

            // update credentials
            $criminal->updated_by = $loginUserInfo['email'];

            $criminal->updated_at = now();
            // set criminal details
            $criminal->name_bng = $_criminal["name"] ?? '';
            $criminal->name_eng = $_criminal["name"] ?? '';
            $criminal->age = $_criminal['age'] ?? null;
            $criminal->email = $_criminal['email'] ?? '';
            $criminal->occupation = $_criminal['occupation'] ?? '';
            $criminal->custodian_name = $_criminal["custodian_name"] ?? '';
            $criminal->custodian_type = $_criminal["custodian_type"] ?? '';
            $criminal->gender = $_criminal["gender"] ?? '';
            $criminal->mother_name = $_criminal["mother_name"] ?? '';
            $criminal->national_id = $_criminal["national_id"] ?? '';
            $criminal->mobile_no = $_criminal["mobile_no"] ?? '';

            // set address
            $criminal->present_address = $_criminal["present_address"] ?? '';
            $criminal->permanent_address = $_criminal["permanent_address"] ?? '';
            $criminal->divid = $_criminal["divid"] ?? null;
            $criminal->zillaId = $_criminal["zillaId"] ?? null;
            $criminal->location_type = $_criminal["locationtype"] ?? '';  // location Type Should be string
            $criminal->organization_name = $_criminal["organization_name"] ?? '';  // location Type Should be string
            $criminal->trade_no = $_criminal["trade_no"] ?? '';  // location Type Should be string
            if ($_criminal["locationtype"] == 'CITYCORPORATION') {
                $criminal->geo_citycorporation_id = $_criminal["geo_citycorporation_id"] ? $_criminal["geo_citycorporation_id"] : null;
                $criminal->geo_ward_id = $criminal->geo_ward_id ?? null;
                $criminal->geo_metropolitan_id = $criminal->geo_metropolitan_id ?? null;
                $criminal->geo_thana_id = $criminal->geo_thana_id ?? null;
                $criminal->upazilaid = $criminal->upazilaid ?? null;
            }
            elseif ($_criminal["locationtype"] == 'METROPOLITAN') {
                $criminal->geo_citycorporation_id = $criminal->geo_citycorporation_id ?? null;
                $criminal->geo_ward_id = $criminal->geo_ward_id ?? null;
                $criminal->geo_metropolitan_id = $_criminal["geo_metropolitan_id"] ? $_criminal["geo_metropolitan_id"] : null;
                $criminal->geo_thana_id = $_criminal["geo_thana_id"] ? $_criminal["geo_thana_id"] : null;
                $criminal->upazilaid = $criminal->upazilaid ?? null;
            }
            else {
                $criminal->geo_citycorporation_id = $criminal->geo_citycorporation_id ?? null;
                $criminal->geo_ward_id = $criminal->geo_ward_id ?? null;
                $criminal->geo_metropolitan_id = $criminal->geo_metropolitan_id ?? null;
                $criminal->geo_thana_id = $criminal->geo_thana_id ?? null;
                $criminal->upazilaid = $_criminal["upazilaid"] ?? null;
            }
          
            $criminal->do_address= $_criminal['do_address'];
            $criminal->delete_status = 1;

            // code for fingerpring
            $criminal->imprint1 = $_criminal["LEFT_THUMB_BMP"] ?? '';
            $criminal->imprint2 = $_criminal["RIGHT_THUMB_BMP"] ?? '';
            if (!$criminal->save()) {
                throw new \Exception('Failed to save criminal data.');
            }else{
                $Usertype = 1;
                $pre_user_type = $_criminal["m_user_type"] ?? '';       //  1  criminal  or 2  witness
                $pre_user_id = $_criminal["m_user_id"] ?? '';           // witness user id  = prosecution   criminal previous  criminal id
                $pre_repeat_crime = $_criminal["repeat_crime"] ?? '';   //  if already exist
                $user_id = $criminal->id;
                $LEFT_THUMB1 = $_criminal["LEFT_THUMB"] ?? '';
                $RIGHT_THUMB1 = $_criminal["RIGHT_THUMB"] ?? '';
                // update prosecution
                self::saveProsecutionDetails($caseinfo, $criminal, $loginUserInfo);
                // return $criminal;
            }

            return $criminal;

        }
        public static function saveCriminal_old($caseinfo,$_criminal,$loginUserInfo) {

            $criminal = null;
            if($_criminal["criminalID"] != null && $_criminal["criminalID"] > 0) {
                $criminal = Criminal::find($_criminal["criminalID"]);
            }else {
                $criminal = new Criminal();
                $criminal->created_by = $loginUserInfo['email'];
                $criminal->created_at = date('Y-m-d  H:i:s');
            }

            // update credentials
            $criminal->updated_by = $loginUserInfo['email'];

            $criminal->updated_at = date('Y-m-d');
            // set criminal details
            $criminal->name_bng = $_criminal["name"];
            $criminal->name_eng = $_criminal["name"];
            $criminal->age = $_criminal['age'];
            $criminal->email = $_criminal['email'];
            $criminal->occupation = $_criminal['occupation'];
            $criminal->custodian_name = $_criminal["custodian_name"];
            $criminal->custodian_type = $_criminal["custodian_type"];
            $criminal->gender = $_criminal["gender"];
            $criminal->mother_name = $_criminal["mother_name"];
            $criminal->national_id = $_criminal["national_id"];
            $criminal->mobile_no = $_criminal["mobile_no"];

            // set address
            $criminal->present_address = $_criminal["present_address"];
            $criminal->permanent_address = $_criminal["permanent_address"];
            $criminal->divid = $_criminal["divid"];
            $criminal->zillaId = $_criminal["zillaId"];
            $criminal->	location_type = $_criminal["locationtype"];  // location Type Should be string
            $criminal->	organization_name = $_criminal["organization_name"];  // location Type Should be string
            $criminal->	trade_no = $_criminal["trade_no"];  // location Type Should be string
            if ($_criminal["locationtype"] == 'CITYCORPORATION') {
                $criminal->geo_citycorporation_id = $_criminal["geo_citycorporation_id"] ? $_criminal["geo_citycorporation_id"] : null;
                $criminal->geo_ward_id = null;
                $criminal->geo_metropolitan_id = null;
                $criminal->geo_thana_id = null;
                $criminal->upazilaid = null;
            }
            elseif ($_criminal["locationtype"] == 'METROPOLITAN') {
                $criminal->geo_citycorporation_id = null;
                $criminal->geo_ward_id = null;
                $criminal->geo_metropolitan_id = $_criminal["geo_metropolitan_id"] ? $_criminal["geo_metropolitan_id"] : null;
                $criminal->geo_thana_id = $_criminal["geo_thana_id"] ? $_criminal["geo_thana_id"] : null;
                $criminal->upazilaid = null;
            }
            else {
                $criminal->geo_citycorporation_id = null;
                $criminal->geo_ward_id = null;
                $criminal->geo_metropolitan_id = null;
                $criminal->geo_thana_id = null;
                $criminal->upazilaid = $_criminal["upazilaid"];
            }

            $criminal->delete_status = 1;

            // code for fingerpring
            $criminal->imprint1 = $_criminal["LEFT_THUMB_BMP"];
            $criminal->imprint2 = $_criminal["RIGHT_THUMB_BMP"];

            if (!$criminal->save()) {
                $errorMessage = "";
                foreach ($criminal->getMessages() as $message) {
                    $errorMessage .= $message;
                }
                throw new Exception($errorMessage);
            } else {
                $Usertype = 1;
                $pre_user_type = $_criminal["m_user_type"];       //  1  criminal  or 2  witness
                $pre_user_id = $_criminal["m_user_id"];           // witness user id  = prosecution   criminal previous  criminal id
                $pre_repeat_crime = $_criminal["repeat_crime"];   //  if already exist
                $user_id = $criminal->id;
                $LEFT_THUMB1 = $_criminal["LEFT_THUMB"];
                $RIGHT_THUMB1 = $_criminal["RIGHT_THUMB"];

                // $dtarrayinsert = array();
                // if ($LEFT_THUMB1 != "" && $LEFT_THUMB1 != null) {

                //     $dtarrayinsert[] = array(
                //         'FingerName' => "leftthumb",
                //         'FpTemplate' => $LEFT_THUMB1
                //     );
                // }

                // if ($RIGHT_THUMB1 != "" && $RIGHT_THUMB1 != null) {
                //     $dtarrayinsert[] = array(
                //         'FingerName' => "rightthumb",
                //         'FpTemplate' => $RIGHT_THUMB1
                //     );
                // }

                // if ($LEFT_THUMB1 != "" && $LEFT_THUMB1 != null && $RIGHT_THUMB1 != "" && $RIGHT_THUMB1 != null) {
                //     if ($pre_repeat_crime == '2') {

                //         if ($pre_user_type == '2') { // already exist as witness
                //             $datatoPass = array(
                //                 "UserId" => $pre_user_id,  // prosecution id
                //                 "Usertype" => $Usertype,  // will update
                //                 "NewUserId" => $user_id,  // will update  // criminal id
                //                 "Fingerprints" => $dtarrayinsert
                //             );
                //             $return_fpmessage = $this->utilityService->updateFpToserver($datatoPass);
                //         }

                //     } else {
                //         $datatoPass = array(
                //             "UserId" => $user_id,
                //             "Usertype" => $Usertype,
                //             "Fingerprints" => $dtarrayinsert
                //         );
                //         $return_fpmessage = $this->utilityService->saveFpToserver($datatoPass);
                //     }
                // }


                // update prosecution
                self::saveProsecutionDetails($caseinfo, $criminal, $loginUserInfo);

                return $criminal;

            }
        }

        /**
     *
     * @param $caseinfo
     * @param $criminal
     * @param $repeat_crime
     */
    public static function saveProsecutionDetails($caseinfo, $criminal, $loginUserInfo)
    {

        $prosecutionDetailsID=null;
        $proseutionID=$caseinfo['prosecution_id'];

        if($proseutionID != null && $proseutionID > 0) {

            // $conditions = ['prosecution_id'=>$proseutionID,'criminal_id'=>$criminal->id];
            // $prosecution_detail_ext = ProsecutionDetail::findFirst(
            //     [
            //         "conditions" => 'prosecution_id=:prosecution_id: AND criminal_id=:criminal_id:',
            //         'bind' => $conditions,
            //     ]
            // );

            $conditions = [
                'prosecution_id' => $proseutionID,
                'criminal_id' => $criminal->id
            ];

            $prosecution_detail_ext = ProsecutionDetail::where($conditions)->first();
            $prosecutionDetailsID = @$prosecution_detail_ext->id;

            if($prosecutionDetailsID == null){
                $prosecution_detail_ext = new ProsecutionDetail();
                $prosecution_detail_ext->prosecution_id = $caseinfo["prosecution_id"];
                $prosecution_detail_ext->criminal_id = $criminal->id;
                $prosecution_detail_ext->delete_status = 1;

                $prosecution_detail_ext->updated_at = date('Y-m-d  H:i:s');
                $prosecution_detail_ext->updated_by = $loginUserInfo['email'];
            }
            // update credentials

        }

        if (!$prosecution_detail_ext->save()) {
            $errorMessage = "";
            foreach ($prosecution_detail_ext->getMessages() as $message) {
                $errorMessage .= $message;
            }
            throw new Exception($errorMessage);
        }
    }

    public static function  saveConfessionDetails($prosecutionID, $confessionDetails,$loginUserInfo){


        $prosecution=null;
        // $existingConfession = CriminalConfession::findByprosecution_id($prosecutionID);
        $existingConfession = CriminalConfession::where('prosecution_id', $prosecutionID)->get();

        if($existingConfession) {
            foreach ($existingConfession as $data) {
                $xConfessionDetails = CriminalConfessionLawsbroken::where('CriminalConfessionID', $data->id)->get();
                if($xConfessionDetails) {
                    foreach ($xConfessionDetails as $del) {
                        $del->delete();
                    }
                }
                $data->delete();
            }
        }


        $errorMessage = "";



        if (is_array($confessionDetails) && !empty($confessionDetails)) {

            foreach ($confessionDetails as $confession){
                $criminalConfession = new CriminalConfession();
               //echo $confession['criminalID'];
                $criminalConfession->prosecution_id = $prosecutionID;
                $criminalConfession->criminal_id = $confession['criminalID'];
                $criminalConfession->description = $confession['confession'];
                $criminalConfession->created_by = $loginUserInfo['email'];
                // $criminalConfession->created_date = date('Y-m-d  H:i:s');
                $criminalConfession->updated_by = $loginUserInfo['email'];
                // $criminalConfession->update_date = date('Y-m-d');
                $criminalConfession->delete_status = 1;
                // $criminalConfession->id = '';
                // $criminalConfession->date = date('Y-m-d  H:i:s');

                // $this->db->begin();
                if (!$criminalConfession->save()) {
                    foreach ($criminalConfession->getMessages() as $message) {
                        $errorMessage .= $message;
                    }

                }

                if($criminalConfession->id) {
                    foreach ($confession['brokenLaws'] as $brokenLaws) {
                        $confessionByLaws = new CriminalConfessionLawsbroken();
                        // $confessionByLaws->id = '';
                        $confessionByLaws->CriminalConfessionID = $criminalConfession->id;
                        $confessionByLaws->LawsBrokenID = $brokenLaws['lawsBrokenID'];
                        $confessionByLaws->Confessed = $brokenLaws['confessed'];
                        $confessionByLaws->created_by = $loginUserInfo['email'];
                        // $confessionByLaws->CreateDate = date('Y-m-d  H:i:s');
                        $confessionByLaws->updated_by = $loginUserInfo['email'];
                        // $confessionByLaws->UpdateDate = date('Y-m-d  H:i:s');


                        if (!$confessionByLaws->save()) {
                            foreach ($confessionByLaws->getMessages() as $message) {
                                $errorMessage .= $message;
                            }
                        }

                    }
                }
                // $this->db->commit();

            }

        }

        if($errorMessage==""){
            $prosecution = Prosecution::find($prosecutionID);
            $prosecution->case_status=6;
            // $this->db->begin();
            if (!$prosecution->save()) {
                foreach ($prosecution->getMessages() as $message) {
                    $errorMessage .= $message;
                }
            }else{
                // $this->db->commit();
            }
        }
        return $prosecution;


    }



}