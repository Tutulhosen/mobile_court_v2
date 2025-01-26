<?php


namespace App\Repositories;

use App\Models\Prosecution;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class ProsecutionRepository
{



    // public static function createProsecutionShell($isSuoMoto,$loginUserInfo,$magistrateCourtId,$hascriminal){
    //     $prosecutorId=null;
    //     $isApproved=1;

    //     if($isSuoMoto==0){
    //         $prosecutorId=$loginUserInfo['id'];
    //         $isApproved=0;
    //     }

    //     $transactionStatus=false;
    //     $prosecution= new Prosecution();

    //     $prosecution->is_suomotu = $isSuoMoto;
    //     $prosecution->subject = "-"; // অপরাধের বিবরণ
    //     $prosecution->date = date('Y-m-d'); // 2003-10-16

    //     $prosecution->time = date('H:i:s');//  Linux Problem DataType
    //     $prosecution->location = "-";
    //     $prosecution->hints = "অভিযোগ গঠণ হয়নি";
    //     $prosecution->case_type1 = 0;
    //     $prosecution->case_type2 = 0;

    //     $prosecution->witness1_name = "";
    //     $prosecution->witness1_custodian_name = "";
    //     $prosecution->witness1_mobile_no = "";
    //     $prosecution->witness1_address = "";
    //     $prosecution->witness1_mother_name = "";
    //     $prosecution->witness1_nationalid = "";
    //     $prosecution->witness2_name = "";
    //     $prosecution->witness2_custodian_name = "";
    //     $prosecution->witness2_mobile_no = "";
    //     $prosecution->witness2_address = "";
    //     $prosecution->witness2_mother_name = "";
    //     $prosecution->witness2_nationalid = "";


    //     $prosecution->created_by = $loginUserInfo['email'];
    //     $prosecution->created_date = date('Y-m-d  H:i:s');
    //     $prosecution->update_by = $loginUserInfo['email'];
    //     $prosecution->update_date = date('Y-m-d');
    //     $prosecution->delete_status = 1;
    //     $prosecution->case_status = 2;
    //     $prosecution->is_approved = $isApproved;
    //     $prosecution->case_no = 'অভিযোগ গঠন হয়নি';
    //     $prosecution->court_id = $magistrateCourtId;

    //     ///
    //     $prosecution->divid = $loginUserInfo['divid'];
    //     $prosecution->zillaId = $loginUserInfo['zillaid'];
    //     $prosecution->upazilaid = $loginUserInfo['upozilaid'];

    //     $prosecution->geo_citycorporation_id = null;
    //     $prosecution->geo_metropolitan_id = null;
    //     $prosecution->geo_thana_id = null;
    //     $prosecution->geo_ward_id = null;
    //     $prosecution->prosecutor_id = $prosecutorId;
    //     $prosecution->hasCriminal = ($hascriminal == true)?1:0;


    //     $prosecution->prosecutor_name = $loginUserInfo['name'];
    //     $prosecution->prosecutor_details = $loginUserInfo['designation'];
    //     $prosecution->magistrate_id=$loginUserInfo['magistrate-id'];

    //     dd( $prosecution);
    //     // $$prosecution->save();
    //     // return $prosecution;


    // }

    public static function getProsecutionById($id) {
        return Prosecution::find($id);
    }
    public static function createProsecutionShell($isSuoMoto,$loginUserInfo,$magistrateCourtId,$hascriminal) {

        $prosecutionID=$_POST['data']['prosecutionID'];
        $prosecutorId=null;
        $isApproved=1;
        if($isSuoMoto==0){
            $prosecutorId=$loginUserInfo['id'];
            $isApproved=0;
        }
        // $prosecution = Prosecution::find($prosecutionID);
        // if(empty($prosecutorId)){
        //     $prosecution= new Prosecution();
        // }

        // $prosecution->is_suomotu = $isSuoMoto ? $isSuoMoto : 0;
        // $prosecution->subject = "-"; // অপরাধের বিবরণ
        // $prosecution->date = date('Y-m-d'); // 2003-10-16

        // $prosecution->time = date('H:i:s');//  Linux Problem DataType
        // $prosecution->location = "-";
        // $prosecution->hints = "অভিযোগ গঠণ হয়নি";
        // $prosecution->case_type1 = 0;
        // $prosecution->case_type2 = 0;

        // $prosecution->witness1_name = "";
        // $prosecution->witness1_custodian_name = "";
        // $prosecution->witness1_mobile_no = "";
        // $prosecution->witness1_address = "";
        // $prosecution->witness1_mother_name = "";
        // $prosecution->witness1_nationalid = "";
        // $prosecution->witness2_name = "";
        // $prosecution->witness2_custodian_name = "";
        // $prosecution->witness2_mobile_no = "";
        // $prosecution->witness2_address = "";
        // $prosecution->witness2_mother_name = "";
        // $prosecution->witness2_nationalid = "";


        // $prosecution->created_by = $loginUserInfo['email'];
        // // $prosecution->created_date = date('Y-m-d  H:i:s');
        // $prosecution->update_by = $loginUserInfo['email'];
        // // $prosecution->update_date = date('Y-m-d');
        // $prosecution->delete_status = 1;
        // $prosecution->case_status = 2;
        // $prosecution->is_approved = $isApproved;
        // $prosecution->case_no = 'অভিযোগ গঠন হয়নি';
        // $prosecution->court_id = $magistrateCourtId;

        // ///
        // $prosecution->divid = $loginUserInfo['divid'];
        // $prosecution->zillaId = $loginUserInfo['zillaid'];
        // $prosecution->upazilaid =(($loginUserInfo['upozilaid'])?$loginUserInfo['upozilaid']:null);

        // $prosecution->geo_citycorporation_id = null;
        // $prosecution->geo_metropolitan_id = null;
        // $prosecution->geo_thana_id = null;
        // $prosecution->geo_ward_id = null;
        // $prosecution->prosecutor_id = $prosecutorId;
        // $prosecution->hasCriminal = ($hascriminal == true)?1:0;


        // $prosecution->prosecutor_name = $loginUserInfo['name'];
        // $prosecution->prosecutor_details = $loginUserInfo['designation'];
        // $prosecution->magistrate_id=$loginUserInfo['magistrate-id'];

        // $prosecution->save();
        // return  $prosecution;
                $prosecution = Prosecution::find($prosecutionID);

                if (empty($prosecution)) {
                    // Create a new record if not found
                    $prosecution = new Prosecution();
                }
                // Common fields for both insert and update
                $prosecution->is_suomotu = $isSuoMoto ?? 0;
                $prosecution->subject = "-"; // অপরাধের বিবরণ
                $prosecution->date = date('Y-m-d'); // 2003-10-16
                $prosecution->time = date('H:i:s'); // Linux Problem DataType
                $prosecution->location = "-";
                $prosecution->hints = "অভিযোগ গঠণ হয়নি";
                $prosecution->case_type1 = 0;
                $prosecution->case_type2 = 0;
                $prosecution->witness1_name = "";
                $prosecution->witness1_custodian_name = "";
                $prosecution->witness1_mobile_no = "";
                $prosecution->witness1_address = "";
                $prosecution->witness1_mother_name = "";
                $prosecution->witness1_nationalid = "";
                $prosecution->witness2_name = "";
                $prosecution->witness2_custodian_name = "";
                $prosecution->witness2_mobile_no = "";
                $prosecution->witness2_address = "";
                $prosecution->witness2_mother_name = "";
                $prosecution->witness2_nationalid = "";

                $prosecution->created_by = $loginUserInfo['email'];
                $prosecution->update_by = $loginUserInfo['email'];
                $prosecution->delete_status = 1;
                $prosecution->case_status = 2;
                $prosecution->is_approved = $isApproved;
                $prosecution->case_no = 'অভিযোগ গঠন হয়নি';
                $prosecution->court_id = $magistrateCourtId;

                // Additional fields for location
                $prosecution->divid = $loginUserInfo['divid'];
                $prosecution->zillaId = $loginUserInfo['zillaid'];
                $prosecution->upazilaid = $loginUserInfo['upozilaid'] ?? null;
                $prosecution->geo_citycorporation_id = null;
                $prosecution->geo_metropolitan_id = null;
                $prosecution->geo_thana_id = null;
                $prosecution->geo_ward_id = null;

                // Prosecutor info
                $prosecution->prosecutor_id = $prosecutorId;
                $prosecution->hasCriminal = $hascriminal ? 1 : 0;
                $prosecution->prosecutor_name = $loginUserInfo['name'];
                $prosecution->prosecutor_details = $loginUserInfo['designation'];
                $prosecution->magistrate_id = $loginUserInfo['magistrate-id'];

            // Save the prosecution record (this will insert or update based on whether the record exists)
            $prosecution->save();

            return $prosecution;

    }

    public static function saveProsecutionInformation($prosecutionId,$prosecutionInfo,$crimeDescription,$loginUserInfo){
    
                $prosecution = Prosecution::find($prosecutionId);
        //        $prosecution->subject = $crime_des; // অপরাধের বিবরণ
                if ($prosecution->is_suomotu == '1') {
                    $prosecution->divid = $prosecutionInfo["division"];
                    $prosecution->zillaId = $prosecutionInfo["zilla"];
                    $prosecutionCaseCount = Prosecution::where('case_no',$prosecutionInfo["case_no"])->first();
                    if($prosecutionCaseCount){
                        if($prosecutionCaseCount->id != $prosecution->id){
                            return false;
                        }
                    }
                    else{
                        $prosecution->case_no = $prosecutionInfo["case_no"];
                    }
                }

                $temp = new \DateTime($prosecutionInfo["suodate"]);
                $prosecution->date = $temp->format('Y-m-d');
                $prosecution->time = $prosecutionInfo["time"];
                $prosecution->location = $prosecutionInfo["location"];
                $prosecution->hints = $prosecutionInfo["hints"];
                $prosecution->subject = $crimeDescription;
                $prosecution->case_type1 = $prosecutionInfo["case_type1"];
                $prosecution->case_type2 = $prosecutionInfo["case_type2"] ? $prosecutionInfo["case_type2"] : null;


                $prosecution->location_type = $prosecutionInfo["locationtype"];
                if ($prosecutionInfo["locationtype"] == 'UPAZILLA') {
                    $prosecution->geo_citycorporation_id = null;
                    $prosecution->geo_ward_id = null;
                    $prosecution->geo_metropolitan_id = null;
                    $prosecution->geo_thana_id = null;
                    $prosecution->upazilaid = $prosecutionInfo["upazilla"] ? $prosecutionInfo["upazilla"] : null;
                } else if ($prosecutionInfo["locationtype"] == 'CITYCORPORATION') {
                    $prosecution->geo_ward_id = null;
                    $prosecution->geo_metropolitan_id = null;
                    $prosecution->geo_thana_id = null;
                    $prosecution->upazilaid = null;
                    $prosecution->geo_citycorporation_id = $prosecutionInfo["upazilla"] ? $prosecutionInfo["upazilla"] : null;
                } else {
                    $prosecution->geo_citycorporation_id = null;
                    $prosecution->geo_ward_id = null;
                    $prosecution->upazilaid = null;
                    $prosecution->geo_metropolitan_id = $prosecutionInfo["upazilla"] ? $prosecutionInfo["upazilla"] : null;
                    $prosecution->geo_thana_id = $prosecutionInfo["GeoThanas"] ? $prosecutionInfo["GeoThanas"] : null;
                }

                $prosecution->geo_ward_id = null;//  Linux Problem DataType

                $prosecution->update_by =$loginUserInfo['email'];
                // $prosecution->update_date = date('Y-m-d');

                $prosecution->delete_status = 1;
                $prosecution->case_status = 4;

                $occurrence_type_val = $prosecutionInfo["occurrence_type"];

                $prosecution->occurrence_type = $occurrence_type_val;

                if ($occurrence_type_val == 1) {
                    $prosecution->occurrence_type_text = "সামনে সংঘটিত ";
                } else if ($occurrence_type_val == 2) {
                    $prosecution->occurrence_type_text = "কর্তৃক উদ্‌ঘাটিত  ";
                } else {
                    $prosecution->occurrence_type_text = "সামনে সংঘটিত";
                }

                $errorMessage = "";
                // $this->db->begin();

                if (!$prosecution->update()) {
                    $errorMessage = "";
                    foreach ($prosecution->getMessages() as $message) {
                        $errorMessage .= $message;
                    }
                    throw new Exception($errorMessage);
                }else{
                    // $this->db->commit();
                    return $prosecution;
                }

    }


    public static function saveJimmaderInformation($jimmaderInfo){


        $flag='false';
       $prosecution = self::getProsecutionById($jimmaderInfo['prosecutionid']);
        if($prosecution){
            // $this->db->begin();
            // $prosecution->jimmader_name=$jimmaderInfo['prosecutionid'];
            $prosecution->jimmader_name=$jimmaderInfo['jimmaderName'];
            $prosecution->jimmader_designation=$jimmaderInfo['jimmaderDesignation'];
            $prosecution->jimmader_address=$jimmaderInfo['jimmaderLocation'];
            $prosecution->dispose_detail=$jimmaderInfo['seizure_order'];
            $prosecution->is_sizeddispose=1;
            if ($prosecution->save() === false) {
                $messages = $prosecution->getMessages();
                foreach ($messages as $message) {
                    echo $message, "\n";
                }
            } else {
                $flag='true';
                // $this->db->commit();
            }

        }
        return $flag;

    }

    public static function updateProsecutionOrderSheetId($prosecutionId,$orderSheetId){


        $prosecution = self::getProsecutionById($prosecutionId);
        $flag='false';

        if($prosecution){
            // $this->db->begin();
            $prosecution->case_status=7;
            $prosecution->orderSheet_id=$orderSheetId;

            if ($prosecution->save() === false) {
                $messages = $prosecution->getMessages();
                foreach ($messages as $message) {
                    echo $message, "\n";
                }
            } else {
                $flag='true';
                // $this->db->commit();
            }
        }
        return $flag;

    }

    public static function approveProsecution($prosecutionId,$prosecutionInfo){
        $userinfo=globalUserInfo();  // gobal user
        $office_id=globalUserInfo()->office_id;  // gobal user
        $officeinfo= DB::table('office')->where('id',$office_id)->first();
        if($userinfo->role_id == 25){
            $profile= 'Prosecutor';
        }else{
            $profile= 'Magistrate';
        }
        $authuserinfo =array(
            'id' =>$userinfo->id,
            'magistrate-id' => $userinfo->id,
            'courtname' => $userinfo->name,
            'name' => $userinfo->name,
            'email' => $userinfo->email,
            'profile' => $profile,   // P
            'divid'=> $officeinfo->division_id,
            'zillaid' =>$officeinfo->district_id,
            'upozilaid' => $officeinfo->upazila_id,
            'divname' =>  $officeinfo->div_name_bn,
            'zillaname' =>$officeinfo->dis_name_bn,
            'upozillaname' =>$officeinfo->dis_name_bn ,
            'serviceid'=> $officeinfo->upa_name_bn,
            'office' =>$officeinfo->office_name_bn,
            'officeType' =>$officeinfo->organization_type,
            'joblocation'=>$officeinfo->dis_name_bn,
            'mobile' =>$userinfo->mobile_no,
            'designation'=> $userinfo->designation,
            'role_id'=> $userinfo->role_id,
            'court_id'=> (!empty($court_id)?$court_id:0),
        );
        $prosecution = Prosecution::find($prosecutionId);
        $brokenLawsArray = $prosecutionInfo['brokenLaws'];
         // Approve prosecution
        $prosecution->is_approved = 1;

        // Check for duplicate case_no
         // Check case number uniqueness
        $prosecutionCaseCount = Prosecution::where('case_no', $prosecutionInfo['case_no'])->first();
        if ($prosecutionCaseCount) {
            if ($prosecutionCaseCount->id != $prosecution->id) {
                return false;
            }
        } else {
            $prosecution->case_no = $prosecutionInfo['case_no'];
        }


        // Update prosecution information
        $prosecution->update_by = auth()->user()->email;
        $prosecution->updated_at = now();

        // Set occurrence type
        $occurrence_type_val = $prosecutionInfo["occurrence_type"];
        $prosecution->occurrence_type = $occurrence_type_val;
        if ($occurrence_type_val == 1) {
            $prosecution->occurrence_type_text = "সামনে সংঘটিত";
        } elseif ($occurrence_type_val == 2) {
            $prosecution->occurrence_type_text = "কর্তৃক উদ্‌ঘাটিত";
        } else {
            $prosecution->occurrence_type_text = "সামনে সংঘটিত";
        }

    // Begin database transaction
    DB::beginTransaction();

        try {
            // Attempt to update prosecution record
            if (!$prosecution->save()) {
                $errorMessage = $prosecution->getErrors()->implode(', ');
                throw new Exception($errorMessage);
            }

            // Law store in laws_broken table (assuming a service exists for this)
            $crimeDescription = LawRepository::saveLawsBrokenList($prosecutionId, $brokenLawsArray,$authuserinfo);

            // Update prosecution subject (assuming this method exists)
            $updateProsecution = self::updateProsecutionSubject($prosecution, $crimeDescription);

            // Commit transaction
            DB::commit();

            return $updateProsecution;

        } catch (Exception $e) {
            // Rollback in case of any error
            DB::rollBack();
            throw $e;
        }

    }

    public static function updateProsecutionSubject($prosecution,$crimeDescription){
        if ($prosecution) {
            DB::beginTransaction(); // Begin the transaction

            try {
                // Update the subject with crimeDescription
                $prosecution->subject = $crimeDescription;

                // Save the prosecution model
                if (!$prosecution->save()) {
                    // If saving fails, capture and display messages
                    $messages = $prosecution->getErrors();
                    foreach ($messages as $message) {
                        echo $message . "\n";
                    }

                    // If save fails, rollback transaction
                    DB::rollBack();
                } else {
                    // Commit the transaction if save is successful
                    DB::commit();
                }
            } catch (\Exception $e) {
                // Rollback in case of any error
                DB::rollBack();
                throw $e; // Optionally rethrow the exception for further handling
            }
        }

        return $prosecution;
    }
}