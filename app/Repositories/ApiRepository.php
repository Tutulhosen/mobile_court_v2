<?php

namespace App\Repositories;

use App\Models\Court;
use App\Models\Criminal;
use App\Models\CriminalConfession;
use App\Models\CriminalConfessionLawsbroken;
use App\Models\LawsBroken;
use App\Models\LawsBrokenProsecution;
use App\Models\OrderText;
use App\Models\Prosecution;
use App\Models\ProsecutionDetail;
use App\Models\Punishment;
use App\Models\Seizurelist;
use Illuminate\Support\Facades\DB;

class ApiRepository
{

    public static function createProsecutionShellForApi($isSuoMoto, $loginUserInfo, $magistrateCourtId, $hascriminal)
    {

        // $prosecutionID = $_POST['data']['prosecutionID'];
        $prosecutorId = null;
        $isApproved = 1;
        if ($isSuoMoto == 0) {
            $prosecutorId = $loginUserInfo['id'];
            $isApproved = 0;
        }

        // Create a new record
        $prosecution = new Prosecution();

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

    public static function saveWitnessesForApi($data, $prosecutionID)
    {

        $prosecution = Prosecution::find($prosecutionID);

        $errorMessage = "";
        // witness validation
        if ($data["witness1_name"] == "" || $data["witness2_name"] == "" || $data["witness1_custodian_name"] == "" || $data["witness2_custodian_name"] == "") {
            return false;
        }

        if ($prosecution) {
            $prosecution->witness1_name = $data["witness1_name"] ?? '';
            $prosecution->witness1_custodian_name = $data["witness1_custodian_name"] ?? '';
            $prosecution->witness1_mobile_no = $data["witness1_mobile_no"] ?? '';
            $prosecution->witness1_mother_name = $data["witness1_mother_name"] ?? '';
            $prosecution->witness1_age = $data["witness1_age"] ?? '';
            $prosecution->witness1_nationalid = $data["witness1_nationalid"] ?? '';
            $prosecution->witness1_address = $data["witness1_address"] ?? '';
            $prosecution->witness1_imprint1 = $data["witness1_LEFT_THUMB_BMP"] ?? '';
            $prosecution->witness1_imprint2 = $data["witness1_RIGHT_THUMB_BMP"] ?? '';

            $prosecution->witness2_name = $data["witness2_name"] ?? '';
            $prosecution->witness2_custodian_name = $data["witness2_custodian_name"] ?? '';
            $prosecution->witness2_mobile_no = $data["witness2_mobile_no"] ?? '';
            $prosecution->witness2_mother_name = $data["witness2_mother_name"] ?? '';
            $prosecution->witness2_age = $data["witness2_age"] ?? '';
            $prosecution->witness2_nationalid = $data["witness2_nationalid"] ?? '';
            $prosecution->witness2_address = $data["witness2_address"] ?? '';
            $prosecution->witness2_imprint1 = $data["witness2_LEFT_THUMB_BMP"] ?? '';
            $prosecution->witness2_imprint2 = $data["witness2_RIGHT_THUMB_BMP"] ?? '';

            $prosecution->case_status = 3;
        }

        if (!$prosecution->save()) {

            throw new \Exception('Failed to save criminal data.');
        } else {

            $pre_repeat_crime = $data["witness1_repeat_crime"] ?? ''; //  if already exist 2
            $witness1_Ltemplate = $data["witness1_LEFT_THUMB"] ?? '';
            $witness1_Rtemplate = $data["witness1_RIGHT_THUMB"] ?? '';

            $witness2_Ltemplate = $data["witness2_LEFT_THUMB"] ?? '';
            $witness2_Rtemplate = null; //$data["witness2_RIGHT_THUMB"];

            if ($pre_repeat_crime != '2') { // if not exist witness1

                $user_id = $prosecution->id;
                $Usertype = 2;

                $LEFT_THUMB1 = $witness1_Ltemplate ?? '';
                $RIGHT_THUMB1 = $witness1_Rtemplate ?? '';

                $dtarrayinsert = array();
                if ($LEFT_THUMB1 != "" && $LEFT_THUMB1 != null) {
                    $dtarrayinsert[] = array(
                        'FingerName' => "leftthumb",
                        'FpTemplate' => $LEFT_THUMB1,
                    );
                }
                if ($RIGHT_THUMB1 != "" && $RIGHT_THUMB1 != null) {

                    $dtarrayinsert[] = array(
                        'FingerName' => "rightthumb",
                        'FpTemplate' => $RIGHT_THUMB1,
                    );
                }

                $datatoPass = array(
                    "UserId" => $user_id,
                    "Usertype" => $Usertype ?? '',
                    "Fingerprints" => $dtarrayinsert ?? '',
                );

                if (is_array($dtarrayinsert) && !empty($dtarrayinsert)) {
                    // $return_fpmessage_witness1  = $this->utilityService->saveFpToserver($datatoPass);
                }
            }

            $pre_repeat_crime = $data["witness2_repeat_crime"] ?? " "; //  if already exist 2

            if ($pre_repeat_crime != '2') { // if not exist
                $user_id = $prosecution->id;
                $Usertype = 2;
                $LEFT_THUMB1 = $witness2_Ltemplate ?? '';
                $RIGHT_THUMB1 = $witness2_Rtemplate ?? '';

                $dtarrayinsert_witness2 = array();
                if ($LEFT_THUMB1 != "" && $LEFT_THUMB1 != null) {
                    $dtarrayinsert_witness2[] = array(
                        'FingerName' => "leftthumb",
                        'FpTemplate' => $LEFT_THUMB1,
                    );
                }

                if ($RIGHT_THUMB1 != "" && $RIGHT_THUMB1 != null) {

                    $dtarrayinsert_witness2[] = array(
                        'FingerName' => "rightthumb",
                        'FpTemplate' => $RIGHT_THUMB1,
                    );
                }

                $datatoPass_witness2 = array(
                    "UserId" => $user_id,
                    "Usertype" => $Usertype ?? '',
                    "Fingerprints" => $dtarrayinsert_witness2 ?? '',
                );
                if (is_array($dtarrayinsert_witness2) && !empty($dtarrayinsert_witness2)) {
                    // $return_fpmessage_witness2 =  $this->utilityService->saveFpToserver($datatoPass_witness2);
                }
            }
        }

        return true;
    }

    public static function savePunishmentRelease($data, $logininfo)
    {
        $order_text_id = self::saveOrderText($data['prosecution_id'], $data['punishment_type'], $logininfo);
        $array = array();

        // Decode the JSON arrays
        $lawsBrokenIds = json_decode($data['laws_broken_ids'], true);
        $criminalIds = json_decode($data['criminal_ids'], true);

        // Loop through each criminal_id and laws_broken_id combination
        foreach ($criminalIds as $criminalId) {
            foreach ($lawsBrokenIds as $lawId) {
                $conditions = [
                    'prosecution_id' => $data['prosecution_id'],
                    'criminal_id' => $criminalId,
                    'laws_broken_id' => $lawId,
                ];

                // Check if the punishment already exists
                $punishmentTable = Punishment::where($conditions)->first();
                if ($punishmentTable) {
                    continue; // Skip if the punishment already exists
                }

                // Create a new punishment record
                $punishmentTable = new Punishment();
                $punishmentTable->created_by = $logininfo['email'];
                $punishmentTable->order_text_id = $order_text_id ? $order_text_id : null;
                $punishmentTable->prosecution_id = $data['prosecution_id'];
                $punishmentTable->criminal_id = $criminalId;
                $punishmentTable->laws_broken_id = $lawId;
                $punishmentTable->order_type = $data['punishment_type'];
                // $punishmentTable->order_detail = $order_detail;

                //handle punishment table order detail field
                if ($data['punishment_type'] == 'RELEASE') {
                    $punishmentTable->order_detail = 'অব্যাহতি প্রদান করা হল';
                } elseif ($data['punishment_type'] == 'REGULARCASE') {
                    // dd('aaaa');
                    if ($data['regular_case_type_name'] == "HIGHCOURT") {
                        if ($data['responsibleDepartmentName'] != null) {
                            $punishmentTable->order_detail = $data['responsibleDepartmentName'] . "," . $data['responsibleAdalotAddress'] . ' এর আদালত বরাবর বিচারার্থে প্রেরণ করা হোক ।';
                        } else {
                            $punishmentTable->order_detail = 'উচ্চ আদালত বরাবর বিচারার্থে প্রেরণ করা হোক ।';
                        }
                    } else {
                        if ($data['responsibleDepartmentName'] != null) {
                            $punishmentTable->order_detail = $data['responsibleDepartmentName'] . "," . $data['responsibleAdalotAddress'] . ' থানায় প্রেরণ করা হোক ।';
                        } else {
                            $punishmentTable->order_detail = 'থানায় প্রেরণ করা হোক ।';
                        }
                    }
                } else {
                    $punishmentTable->order_detail = $data['order_detail'] ? $data['order_detail'] : null;
                }

                // Set other punishment fields
                $punishmentTable->fine_in_word = $data['fine_in_word'] ?? null;
                $punishmentTable->fine = $data['fine'] ?? null;
                $punishmentTable->warrent_duration = $data['warrent_duration'] ?? null;
                $punishmentTable->warrent_detail = $data['warrent_detail'] ?? null;
                $punishmentTable->warrent_type = $data['warrent_type'] ?? null;
                $punishmentTable->warrent_type_text = $data['warrent_type_text'] ?? null;
                $punishmentTable->rep_warrent_duration = $data['rep_warrent_duration'] ?? null;
                $punishmentTable->rep_warrent_detail = $data['rep_warrent_detail'] ?? null;
                $punishmentTable->rep_warrent_type = $data['rep_warrent_type'] ?? null;
                $punishmentTable->rep_warrent_type_text = $data['rep_warrent_type_text'] ?? null;
                $punishmentTable->receipt_no = $data['receipt_no'] ?? null;
                $punishmentTable->punishmentJailID = $data['punishmentJailID'] ?? null;
                $punishmentTable->punishmentJailName = $data['punishmentJailName'] ?? null;
                $punishmentTable->exe_jail_type = $data['exe_jail_type'] ?? null;

                // RegularCase fields
                $punishmentTable->regular_case_type_name = $data['regular_case_type_name'] ?? null;
                $punishmentTable->responsibleThanaID = $data['responsibleThanaID'] ?? null;
                $punishmentTable->responsibleDepartmentName = $data['responsibleDepartmentName'] ?? null;
                $punishmentTable->responsibleAdalotAddress = $data['responsibleAdalotAddress'] ?? null;

                // Updated By
                $punishmentTable->updated_by = $logininfo['email'];

                // Save the punishment record
                if (!$punishmentTable->save()) {
                    $errorMessage = implode(", ", $punishmentTable->getMessages());
                    // Handle error as needed
                } else {
                    $array[] = $punishmentTable;
                }
            }
        }

        return $array; // Return all saved punishment records
    }

    // public static function savePunishment($data, $logininfo)
    // {
    //     $order_text_id = self::saveOrderText($data['prosecution_id'], $data['punishment_type'], $logininfo);
    //     $array = array();

    //     // Decode the JSON arrays
    //     $lawsBrokenIds = json_decode($data['laws_broken_ids'], true);
    //     $criminalIds = json_decode($data['criminal_ids'], true);

    //     // Loop through each criminal_id and laws_broken_id combination
    //     foreach ($criminalIds as $criminalId) {
    //         foreach ($lawsBrokenIds as $lawId) {
    //             $conditions = [
    //                 'prosecution_id' => $data['prosecution_id'],
    //                 'criminal_id' => $criminalId,
    //                 'laws_broken_id' => $lawId,
    //             ];

    //             // Check if the punishment already exists
    //             $punishmentTable = Punishment::where($conditions)->first();
    //             if ($punishmentTable) {
    //                 continue; // Skip if the punishment already exists
    //             }

    //             // Create a new punishment record
    //             $punishmentTable = new Punishment();
    //             $punishmentTable->created_by = $logininfo['email'];
    //             $punishmentTable->order_text_id = $order_text_id ? $order_text_id : null;
    //             $punishmentTable->prosecution_id = $data['prosecution_id'];
    //             $punishmentTable->criminal_id = $criminalId;
    //             $punishmentTable->laws_broken_id = $lawId;
    //             $punishmentTable->order_type = $data['punishment_type'];
    //             // $punishmentTable->order_detail = $order_detail;

    //             //handle punishment table order detail field
    //             if ($data['punishment_type'] == 'PUNISHMENT') {
    //                 $punishmentTable->order_detail = 'শাস্তি প্রদান করা হল';
    //             }

    //             // Set other punishment fields
    //             $punishmentTable->fine_in_word = $data['fine_in_word'] ?? null;
    //             $punishmentTable->fine = $data['fine'] ?? null;
    //             $punishmentTable->warrent_duration = $data['warrent_duration'] ?? null;
    //             $punishmentTable->warrent_detail = $data['warrent_detail'] ?? null;
    //             $punishmentTable->warrent_type = $data['warrent_type'] ?? null;
    //             $punishmentTable->warrent_type_text = $data['warrent_type_text'] ?? null;
    //             $punishmentTable->rep_warrent_duration = $data['rep_warrent_duration'] ?? null;
    //             $punishmentTable->rep_warrent_detail = $data['rep_warrent_detail'] ?? null;
    //             $punishmentTable->rep_warrent_type = $data['rep_warrent_type'] ?? null;
    //             $punishmentTable->rep_warrent_type_text = $data['rep_warrent_type_text'] ?? null;
    //             $punishmentTable->receipt_no = $data['receipt_no'] ?? null;
    //             $punishmentTable->punishmentJailID = $data['punishmentJailID'] ?? null;
    //             $punishmentTable->punishmentJailName = $data['punishmentJailName'] ?? null;
    //             $punishmentTable->exe_jail_type = $data['exe_jail_type'] ?? null;

    //             $punishmentTable->updated_by = $logininfo['email'];

    //             if (!$punishmentTable->save()) {
    //                 $errorMessage = implode(", ", $punishmentTable->getMessages());
    //             } else {
    //                 $array[] = $punishmentTable;
    //             }
    //         }
    //     }

    //     return $array; // Return all saved punishment records
    // }

    public static function savePunishment($data, $logininfo)
    {
        $order_text_id = self::saveOrderText($data['prosecution_id'], $data['punishment_type'], $logininfo);
        $array = [];

        $lawsBrokenIds = json_decode(self::convertToJsonArray($data['laws_broken_ids']), true);
        $criminalIds = json_decode(self::convertToJsonArray($data['criminal_ids']), true);
        $fineInWord = json_decode(self::convertToJsonArray($data['fine_in_word']), true);
        $fine = json_decode(self::convertToJsonArray($data['fine']), true);
        $warrentDuration = json_decode(self::convertToJsonArray($data['warrent_duration']), true);
        $warrentDetail = json_decode(self::convertToJsonArray($data['warrent_detail']), true);
        $warrentType = json_decode(self::convertToJsonArray($data['warrent_type']), true);
        $warrentTypeText = json_decode(self::convertToJsonArray($data['warrent_type_text']), true);

        // Debug if decoding fails
        if ($lawsBrokenIds === null) {
            echo 'Error in laws_broken_ids: ' . json_last_error_msg();
        }
        if ($criminalIds === null) {
            echo 'Error in criminal_ids: ' . json_last_error_msg();
        }

        foreach ($criminalIds as $criminalId) {
            foreach ($lawsBrokenIds as $index => $lawId) {
                $conditions = [
                    'prosecution_id' => $data['prosecution_id'],
                    'criminal_id' => $criminalId,
                    'laws_broken_id' => $lawId,
                ];

                $punishmentTable = Punishment::where($conditions)->first();
                if ($punishmentTable) {
                    continue;
                }

                // Create a new punishment record
                $punishmentTable = new Punishment();
                $punishmentTable->created_by = $logininfo['email'];
                $punishmentTable->order_text_id = $order_text_id ? $order_text_id : null;
                $punishmentTable->prosecution_id = $data['prosecution_id'];
                $punishmentTable->criminal_id = $criminalId;
                $punishmentTable->laws_broken_id = $lawId;
                $punishmentTable->order_type = $data['punishment_type'];

                // Handle punishment table order detail field
                if ($data['punishment_type'] == 'PUNISHMENT') {
                    $punishmentTable->order_detail = 'শাস্তি প্রদান করা হল';
                }

                $punishmentTable->fine_in_word = $fineInWord[$index] ?? null;
                $punishmentTable->fine = $fine[$index] ?? null;
                $punishmentTable->warrent_duration = $warrentDuration[$index] ?? null;
                $punishmentTable->warrent_detail = $warrentDetail[$index] ?? null;
                $punishmentTable->warrent_type = $warrentType[$index] ?? null;
                $punishmentTable->warrent_type_text = $warrentTypeText[$index] ?? null;

                $punishmentTable->rep_warrent_duration = $data['rep_warrent_duration'] ?? null;
                $punishmentTable->rep_warrent_detail = $data['rep_warrent_detail'] ?? null;
                $punishmentTable->rep_warrent_type = $data['rep_warrent_type'] ?? null;
                $punishmentTable->rep_warrent_type_text = $data['rep_warrent_type_text'] ?? null;
                $punishmentTable->receipt_no = $data['receipt_no'] ?? null;
                $punishmentTable->punishmentJailID = $data['punishmentJailID'] ?? null;
                $punishmentTable->punishmentJailName = $data['punishmentJailName'] ?? null;
                $punishmentTable->exe_jail_type = $data['exe_jail_type'] ?? null;

                $punishmentTable->updated_by = $logininfo['email'];

                if (!$punishmentTable->save()) {
                    $errorMessage = implode(", ", $punishmentTable->getMessages());
                } else {
                    $array[] = $punishmentTable;
                }
            }
        }

        return $array;
    }

    private static function convertToJsonArray($string)
    {
        $string = trim($string, '[]"');
        $array = explode(',', $string);

        return json_encode(array_map('trim', $array));
    }

    private static function saveOrderText($prosecution_id, $order_type, $logininfo)
    {

        $orderText = new OrderText();
        $orderText->prosecution_id = $prosecution_id;
        $orderText->order_type = $order_type;
        $orderText->note = "হয়েছে";
        $orderText->created_by = $logininfo['email'];
        $orderText->updated_by = $logininfo['email'];
        // $orderText->created_date = date('Y-m-d  H:i:s');

        // $this->db->begin();
        if (!$orderText->save()) {

            $errorMessage = "";
            foreach ($orderText->getMessages() as $message) {
                $errorMessage .= $message;
            }
            // $this->db->rollback();
            // throw new Exception($errorMessage);
        } else {
            // $this->db->commit();
            return $orderText->id;
        }
    }

    public static function getCaseInformationByProsecutionId($prosecutionID)
    {

        $userinfo = globalUserInfo();
        $tablesContent = array();
        $criminalIds = array();

        if ($prosecutionID) {

            $prosecution = Prosecution::find($prosecutionID);
            $tablesContent['prosecution'] = $prosecution;

            //getProsecutionLocationName
            $prosecutionLocationName = self::getProsecutionLocationName($prosecution->divid, $prosecution->zillaId, $prosecution->location_type, $prosecution->upazilaid, $prosecution->geo_citycorporation_id, $prosecution->geo_metropolitan_id, $prosecution->geo_thana_id);

            $tablesContent['prosecutionLocationName'] = $prosecutionLocationName;

            //convert time to bangla format
            $prosecutionTime = self::timeConvert($prosecution->time, 12);

            $tablesContent['prosecutionTimeInBangla'] = $prosecutionTime;

            /*----------------------------------
            pre dekte hbe
            ------------------------------------*/
            $tablesContent['criminalConfession'] = array();

            $criminalConfessions = CriminalConfession::where('prosecution_id', $prosecutionID)->get();
            foreach ($criminalConfessions as $data) {
                $tablesContent['criminalConfession'][] = $data;
            }

            $criminalConfessionsByLaws = CriminalConfessionLawsbroken::select([
                'criminalConfession.criminal_id',
                'criminalConfession.prosecution_id',
                'crmConfessBylaw.CriminalConfessionID',
                'crmConfessBylaw.LawsBrokenID',
                'crmConfessBylaw.Confessed',
            ])
                ->from('criminal_confession_lawsbrokens AS crmConfessBylaw')
                ->join('criminal_confessions as criminalConfession', 'criminalConfession.id', '=', 'crmConfessBylaw.CriminalConfessionID')
                ->where('criminalConfession.prosecution_id', $prosecutionID)
                ->get();

            if (count($criminalConfessionsByLaws) > 0) {

                foreach ($criminalConfessionsByLaws as $emp) {
                    $data = array(
                        "crmConfessId" => $emp->CriminalConfessionID,
                        "criminalId" => $emp->criminal_id,
                        "prosecutionId" => $emp->prosecution_id,
                        "lawsBrokenId" => $emp->LawsBrokenID,
                        "isConfessed" => $emp->Confessed,
                    );

                    $tablesContent['criminalConfessionsByLaws'][] = $data;
                }

            } else {
                $tablesContent['criminalConfessionsByLaws'] = array();
            }

            $seizurelist = Seizurelist::where('prosecution_id', $prosecutionID)->get();
            if (count($seizurelist) > 0) {

                foreach ($seizurelist as $data) {
                    $data = $data->toArray();
                    $controller = new \App\Http\Controllers\Api\ProsecutionApiController();
                    $data['description'] = $controller->generateSeizureMessage($data['seizureitem_type_id']);

                    $tablesContent['seizurelist'][] = $data;
                }
            } else {
                $tablesContent['seizurelist'] = array();
            }

            $prosecutionDetails = ProsecutionDetail::where('prosecution_id', $prosecutionID)->get();
            if (count($prosecutionDetails) > 0) {

                foreach ($prosecutionDetails as $data) {

                    $criminalIds[] = $data->criminal_id;
                    $tablesContent['prosecutionDetails'][] = $data;
                }
            } else {
                $tablesContent['prosecutionDetails'] = array();
            }

            if ($userinfo->role_id == 26) {
                $lawsBrokenList = LawsBroken::where('prosecution_id', $prosecutionID)->get();

                if (count($lawsBrokenList) > 0) {
                    foreach ($lawsBrokenList as $emp) {
                        $sections = DB::table('mc_section')->where('id', $emp['section_id'])->where('law_id', $emp['law_id'])->first();
                        $laws = DB::table('mc_law')->where('id', $emp['law_id'])->first();

                        $data = array(
                            "LawID" => $emp['law_id'],
                            "LawsBrokenID" => $emp['id'],
                            "ProsecutionID" => $emp['prosecution_id'],
                            "SectionID" => $emp['section_id'],
                            "sec_title" => $sections->sec_title,
                            "sec_number" => $sections->sec_number,
                            "sec_description" => $sections->sec_description,
                            "punishment_sec_number" => $sections->punishment_sec_number,
                            "punishment_des" => $sections->punishment_des,
                            "punishment_type_des" => $sections->punishment_type_des,
                            "max_jell" => $sections->max_jell,
                            "min_jell" => $sections->min_jell,
                            "max_fine" => $sections->max_fine,
                            "min_fine" => $sections->min_fine,
                            "next_jail" => $sections->next_jail,
                            "next_fine" => $sections->next_fine,
                            "bd_law_link" => $laws->bd_law_link,
                            "Description" => $laws->description,

                        );

                        $data['selectedSection'] = DB::table('mc_section')->where('id', $emp['section_id'])->first();
                        $data['section_list'] = DB::table('mc_section')->where('law_id', $emp['law_id'])->get();
                        $tablesContent['lawsBrokenList'][] = $data;
                    }
                } else {
                    $tablesContent['lawsBrokenList'] = array();
                }
            } else if ($userinfo->role_id == 25) {
                $lawsBrokenList = LawsBrokenProsecution::where('prosecution_id', $prosecutionID)->get();
                // dd($lawsBrokenList);
                if (count($lawsBrokenList) > 0) {
                    foreach ($lawsBrokenList as $emp) {
                        $sections = DB::table('mc_section')->where('id', $emp['section_id'])->where('law_id', $emp['law_id'])->first();
                        $laws = DB::table('mc_law')->where('id', $emp['law_id'])->first();

                        $data = array(
                            "LawID" => $emp['law_id'],
                            "LawsBrokenID" => $emp['id'],
                            "ProsecutionID" => $emp['prosecution_id'],
                            "SectionID" => $emp['section_id'],
                            "sec_title" => $sections->sec_title,
                            "sec_number" => $sections->sec_number,
                            "sec_description" => $sections->sec_description,
                            "punishment_sec_number" => null,
                            "punishment_des" => null,
                            "punishment_type_des" => null,
                            "max_jell" => null,
                            "min_jell" => null,
                            "max_fine" => null,
                            "min_fine" => null,
                            "next_jail" => null,
                            "next_fine" => null,
                            "bd_law_link" => null,
                            "Description" => $laws->description,

                        );

                        $data['selectedSection'] = DB::table('mc_section')->where('id', $emp['section_id'])->first();
                        $data['section_list'] = DB::table('mc_section')->where('law_id', $emp['law_id'])->get();
                        $tablesContent['lawsBrokenList'][] = $data;
                    }
                } else {
                    $tablesContent['lawsBrokenList'] = array();
                }
            }

            // For LawsBrokenList With Prosecutor Table

            $lawsBrokenListWithProsecutor = LawsBrokenProsecution::select(
                'laws_broken_prosecutions.id as id',
                'laws_broken_prosecutions.law_id',
                'laws_broken_prosecutions.prosecution_id',
                'laws_broken_prosecutions.section_id',
                'laws_broken_prosecutions.Description',
                'sections.sec_title',
                'sections.sec_number',
                'sections.sec_description'
            )
                ->join('mc_section as sections', function ($join) {
                    $join->on('sections.law_id', '=', 'laws_broken_prosecutions.law_id')
                        ->on('sections.id', '=', 'laws_broken_prosecutions.section_id');
                })
                ->where('laws_broken_prosecutions.prosecution_id', $prosecutionID)
                ->get();

            if (count($lawsBrokenListWithProsecutor) > 0) {
                foreach ($lawsBrokenListWithProsecutor as $emp) {
                    $data = array(
                        "LawID" => $emp->law_id,
                        "LawsBrokenID" => $emp->id,
                        "ProsecutionID" => $emp->prosecution_id,
                        "SectionID" => $emp->section_id,
                        "sec_title" => $emp->sec_title,
                        "sec_number" => $emp->sec_number,
                        "sec_description" => $emp->sec_description,
                        "Description" => $emp->Description,
                    );
                    $tablesContent['lawsBrokenListWithProsecutor'][] = $data;
                }
            } else {
                $tablesContent['lawsBrokenListWithProsecutor'] = [];
            }

            // For Criminal Table
            foreach ($criminalIds as $id) {

                $criminalDetails = Criminal::where('id', $id)->get();
                foreach ($criminalDetails as $data) {
                    // dd($data);
                    $data = $data->toArray();
                    $data['criminalDivision'] = DB::table('division')->where('id', $data['divid'])->first();
                    $data['criminalDistrict'] = DB::table('district')->where('id', $data['zillaId'])->first();
                    $data['criminalUpazila'] = DB::table('upazila')->where('id', $data['upazilaid'])->first();
                    $data['criminalMetropolitan'] = DB::table('geo_metropolitan')->where('geo_district_id', $data['geo_metropolitan_id'])->first();
                    $data['criminalCitycorporation'] = DB::table('geo_city_corporations')->where('id', $data['geo_citycorporation_id'])->first();
                    $data['criminalThana'] = DB::table('geo_thanas')->where('id', $data['geo_thana_id'])->first();
                    $tablesContent['criminalDetails'][] = $data;
                }
            }

            //for with prosecutor Getting Magistrate Info

            /*-------------------------
            api lagbe
            -----------------------------*/
            $tablesContent['magistrateInfo'] = "";
            $magistrateInfo = Court::join('prosecutions as p', 'p.court_id', '=', 'courts.id')
                ->join('users as m', 'm.id', '=', 'courts.magistrate_id')
                ->join('doptor_user_access_info as dp', 'dp.user_id', '=', 'm.id')
                ->join('mc_role as r', 'r.id', '=', 'dp.role_id')
                ->join('office  as of', 'of.id', '=', 'm.office_id')

                ->leftJoin('district as z', 'z.id', '=', 'of.district_id')
                ->leftJoin('upazila as u', function ($join) {
                    $join->on('u.district_id', '=', 'of.district_id')
                        ->on('u.id', '=', 'of.upazila_id');
                })
                ->select(
                    //  'u.upazila_name_bn as upazilaname',
                    'z.district_name_bn as zillaname',
                    'of.organization_type as office_type',
                    'of.dis_name_bn as location_details',
                    'r.role_name as designation_bng',
                    'm.name as name_eng',
                    'of.office_name_bn as location_str',
                    // 'fc.FileName as signature',
                    'p.id',
                    'courts.magistrate_id',
                    'p.zillaId',
                    'p.date',
                    'p.id',
                    'courts.id as court_id'
                )

                ->where('p.id', '=', $prosecutionID)
                ->get();

            foreach ($magistrateInfo as $data) {
                $tablesContent['magistrateInfo'] = $data;
            }

            $seizureOrderContext = SeizureRepository::getSeizureOrderContext($prosecutionID);

            $tablesContent['seizureOrderContext'] = $seizureOrderContext;

            // $prosecutorInformation=User::select('*','name as name_eng')->where('id', $prosecution->prosecutor_id)->get();//User::select('*','name as name_eng')->find($prosecution->prosecutor_id);
            $prosecutorInformation = Court::join('prosecutions as p', 'p.court_id', '=', 'courts.id')
                ->join('users as m', 'm.id', '=', 'p.prosecutor_id')
                ->join('doptor_user_access_info as dp', 'dp.user_id', '=', 'm.id')
                ->join('mc_role as r', 'r.id', '=', 'dp.role_id')
                ->join('office  as of', 'of.id', '=', 'm.office_id')

                ->leftJoin('district as z', 'z.id', '=', 'of.district_id')
                ->leftJoin('upazila as u', function ($join) {
                    $join->on('u.district_id', '=', 'of.district_id')
                        ->on('u.id', '=', 'of.upazila_id');
                })
                ->select(
                    'z.district_name_bn as zillaname',
                    'of.organization_type as office_type',
                    'of.dis_name_bn as location_details',
                    'r.role_name as designation_bng',
                    'm.name as name_eng',
                    'of.office_name_bn as office_address',
                    // 'fc.FileName as signature',
                    'p.id',
                    'courts.magistrate_id',
                    'p.zillaId',
                    'p.date',
                    'p.id',
                    'courts.id as court_id'
                )
                ->where('dp.role_id', '=', 25)
                ->where('p.id', '=', $prosecutionID)
                ->get();
            // Check if the prosecutor exists
            if ($prosecutorInformation) {
                $tablesContent['prosecutorInfo'] = $prosecutorInformation;
            } else {
                $tablesContent['prosecutorInfo'] = null; // Handle the case where no data is found
            }

            $punishmentSelect = Punishment::where('prosecution_id', $prosecutionID)->get();

            $tablesContent['punishmentSelect'] = $punishmentSelect;

            $entityID = $prosecutionID;

            /*------------------------------------------------
            api lagbe
            ----------------------------------------------------*/
            $fileCategory = "ChargeFame";
            $tablesContent['fileContent']['ChargeFame'] = "";
            $file = ''; //$this->fileContentService->fileFindByEntityID($entityID, $fileCategory);
            $tablesContent['fileContent']['ChargeFame'] = $file;

            $fileCategory = "CriminalConfessionFile";
            $tablesContent['fileContent']['CriminalConfessionFile'] = "";
            $file = ''; //$this->fileContentService->fileFindByEntityID($entityID, $fileCategory);
            $tablesContent['fileContent']['CriminalConfessionFile'] = $file;

            $fileCategory = "OrderSheet";
            $tablesContent['fileContent']['OrderSheet'] = "";
            $file = ''; //$this->fileContentService->fileFindByEntityID($entityID, $fileCategory);
            $tablesContent['fileContent']['OrderSheet'] = $file;

            $tablesContent['fileContent']['AllFile'] = "";
            $file = ''; //$this->fileContentService->fileAllFindByEntityID($entityID);
            $tablesContent['fileContent']['AllFile'] = $file;
        }

        $tablesContent['divisions'] = DB::table('division')->get();
        $tablesContent['districts'] = DB::table('district')->get();
        $tablesContent['upazilas'] = DB::table('upazila')->get();
        $tablesContent['metropolitans'] = DB::table('geo_metropolitan')->get();
        $tablesContent['citycorporations'] = DB::table('geo_city_corporations')->get();
        $tablesContent['thanas'] = DB::table('geo_thanas')->get();
        $tablesContent['law_list'] = DB::table('mc_law')->get();

        return $tablesContent;
    }

    public static function getCaseInformationWithoutCriminalByProsecutionId($prosecutionID)
    {

        $tablesContent = array();
        $criminalIds = array();

        if ($prosecutionID) {

            $prosecution = Prosecution::find($prosecutionID);
            $tablesContent['prosecution'] = $prosecution;

            //getProsecutionLocationName
            $prosecutionLocationName = self::getProsecutionLocationName($prosecution->divid, $prosecution->zillaId, $prosecution->location_type, $prosecution->upazilaid, $prosecution->geo_citycorporation_id, $prosecution->geo_metropolitan_id, $prosecution->geo_thana_id);

            $tablesContent['prosecutionLocationName'] = $prosecutionLocationName;

            //convert time to bangla format
            $prosecutionTime = self::timeConvert($prosecution->time, 12);

            $tablesContent['prosecutionTimeInBangla'] = $prosecutionTime;

            /*----------------------------------
            pre dekte hbe
            ------------------------------------*/

            $seizurelist = Seizurelist::where('prosecution_id', $prosecutionID)->get();
            if (count($seizurelist) > 0) {

                // $tablesContent['seizurelist'] = [];
                foreach ($seizurelist as $data) {
                    $data = $data->toArray();
                    $controller = new \App\Http\Controllers\Api\ProsecutionApiController();
                    $data['description'] = $controller->generateSeizureMessage($data['seizureitem_type_id']);

                    $tablesContent['seizurelist'][] = $data;
                }
            } else {
                $tablesContent['seizurelist'] = array();
            }

            $prosecutionDetails = ProsecutionDetail::where('prosecution_id', $prosecutionID)->get();
            if (count($prosecutionDetails) > 0) {

                foreach ($prosecutionDetails as $data) {
                    // Setting Criminals Id in CriminalIds Array
                    $criminalIds[] = $data->criminal_id;
                    $tablesContent['prosecutionDetails'][] = $data;
                }
            } else {
                $tablesContent['prosecutionDetails'] = array();
            }

            $lawsBrokenList = LawsBroken::where('prosecution_id', $prosecutionID)->get();
            if (count($lawsBrokenList) > 0) {
                foreach ($lawsBrokenList as $emp) {
                    $sections = DB::table('mc_section')->where('id', $emp['section_id'])->where('law_id', $emp['law_id'])->first();
                    $laws = DB::table('mc_law')->where('id', $emp['law_id'])->first();

                    $data = array(
                        "LawID" => $emp['law_id'],
                        "LawsBrokenID" => $emp['id'],
                        "ProsecutionID" => $emp['prosecution_id'],
                        "SectionID" => $emp['section_id'],
                        "sec_title" => $sections->sec_title,
                        "sec_number" => $sections->sec_number,
                        "sec_description" => $sections->sec_description,
                        "punishment_sec_number" => $sections->punishment_sec_number,
                        "punishment_des" => $sections->punishment_des,
                        "punishment_type_des" => $sections->punishment_type_des,
                        "max_jell" => $sections->max_jell,
                        "min_jell" => $sections->min_jell,
                        "max_fine" => $sections->max_fine,
                        "min_fine" => $sections->min_fine,
                        "next_jail" => $sections->next_jail,
                        "next_fine" => $sections->next_fine,
                        "bd_law_link" => $laws->bd_law_link,
                        "Description" => $laws->description,

                    );

                    $data['selectedSection'] = DB::table('mc_section')->where('id', $emp['section_id'])->first();
                    $data['section_list'] = DB::table('mc_section')->where('law_id', $emp['law_id'])->get();
                    $tablesContent['lawsBrokenList'][] = $data;
                }
            } else {
                $tablesContent['lawsBrokenList'] = array();
            }

            // For LawsBrokenList With Prosecutor Table

            $lawsBrokenListWithProsecutor = LawsBrokenProsecution::select(
                'laws_broken_prosecutions.id as id',
                'laws_broken_prosecutions.law_id',
                'laws_broken_prosecutions.prosecution_id',
                'laws_broken_prosecutions.section_id',
                'laws_broken_prosecutions.Description',
                'sections.sec_title',
                'sections.sec_number',
                'sections.sec_description'
            )
                ->join('mc_section as sections', function ($join) {
                    $join->on('sections.law_id', '=', 'laws_broken_prosecutions.law_id')
                        ->on('sections.id', '=', 'laws_broken_prosecutions.section_id');
                })
                ->where('laws_broken_prosecutions.prosecution_id', $prosecutionID)
                ->get();

            if (count($lawsBrokenListWithProsecutor) > 0) {
                foreach ($lawsBrokenListWithProsecutor as $emp) {
                    $data = array(
                        "LawID" => $emp->law_id,
                        "LawsBrokenID" => $emp->id,
                        "ProsecutionID" => $emp->prosecution_id,
                        "SectionID" => $emp->section_id,
                        "sec_title" => $emp->sec_title,
                        "sec_number" => $emp->sec_number,
                        "sec_description" => $emp->sec_description,
                        "Description" => $emp->Description,
                    );
                    $tablesContent['lawsBrokenListWithProsecutor'][] = $data;
                }
            } else {
                $tablesContent['lawsBrokenListWithProsecutor'] = [];
            }

            //for with prosecutor Getting Magistrate Info

            /*-------------------------
            api lagbe
            -----------------------------*/
            $tablesContent['magistrateInfo'] = "";
            $magistrateInfo = Court::join('prosecutions as p', 'p.court_id', '=', 'courts.id')
                ->join('users as m', 'm.id', '=', 'courts.magistrate_id')
                ->join('doptor_user_access_info as dp', 'dp.user_id', '=', 'm.id')
                ->join('mc_role as r', 'r.id', '=', 'dp.role_id')
                ->join('office  as of', 'of.id', '=', 'm.office_id')
                ->leftJoin('district as z', 'z.id', '=', 'of.district_id')
                ->leftJoin('upazila as u', function ($join) {
                    $join->on('u.district_id', '=', 'of.district_id')
                        ->on('u.id', '=', 'of.upazila_id');
                })
                ->select(
                    //  'u.upazila_name_bn as upazilaname',
                    'z.district_name_bn as zillaname',
                    'of.organization_type as office_type',
                    'of.dis_name_bn as location_details',
                    'r.role_name as designation_bng',
                    'm.name as name_eng',
                    'of.office_name_bn as location_str',
                    // 'fc.FileName as signature',
                    'p.id',
                    'courts.magistrate_id',
                    'p.zillaId',
                    'p.date',
                    'p.id',
                    'courts.id as court_id'
                )
            // ->where('jd.is_active', '=', 1)
            //  ->where('jd.user_type_id', '=', 6)
                ->where('p.id', '=', $prosecutionID)
                ->get(); // self::getMagistrateInfoByProsecutionId($prosecutionID);

            foreach ($magistrateInfo as $data) {
                $tablesContent['magistrateInfo'] = $data;
            }

            $seizureOrderContext = SeizureRepository::getSeizureOrderContext($prosecutionID);

            $tablesContent['seizureOrderContext'] = $seizureOrderContext;

            // $prosecutorInformation=User::select('*','name as name_eng')->where('id', $prosecution->prosecutor_id)->get();//User::select('*','name as name_eng')->find($prosecution->prosecutor_id);
            $prosecutorInformation = Court::join('prosecutions as p', 'p.court_id', '=', 'courts.id')
                ->join('users as m', 'm.id', '=', 'p.prosecutor_id')
                ->join('doptor_user_access_info as dp', 'dp.user_id', '=', 'm.id')
                ->join('mc_role as r', 'r.id', '=', 'dp.role_id')
                ->join('office  as of', 'of.id', '=', 'm.office_id')
                ->leftJoin('district as z', 'z.id', '=', 'of.district_id')
                ->leftJoin('upazila as u', function ($join) {
                    $join->on('u.district_id', '=', 'of.district_id')
                        ->on('u.id', '=', 'of.upazila_id');
                })
                ->select(
                    'z.district_name_bn as zillaname',
                    'of.organization_type as office_type',
                    'of.dis_name_bn as location_details',
                    'r.role_name as designation_bng',
                    'm.name as name_eng',
                    'of.office_name_bn as office_address',
                    'p.id',
                    'courts.magistrate_id',
                    'p.zillaId',
                    'p.date',
                    'p.id',
                    'courts.id as court_id'
                )
                ->where('dp.role_id', '=', 25)
                ->where('p.id', '=', $prosecutionID)
                ->get();
            // Check if the prosecutor exists
            if ($prosecutorInformation) {
                $tablesContent['prosecutorInfo'] = $prosecutorInformation;
            } else {
                $tablesContent['prosecutorInfo'] = null; // Handle the case where no data is found
            }

            $punishmentSelect = Punishment::where('prosecution_id', $prosecutionID)->get();

            $tablesContent['punishmentSelect'] = $punishmentSelect;

            $entityID = $prosecutionID;

            /*------------------------------------------------
            api lagbe
            ----------------------------------------------------*/
            $fileCategory = "ChargeFame";
            $tablesContent['fileContent']['ChargeFame'] = "";
            $file = ''; //$this->fileContentService->fileFindByEntityID($entityID, $fileCategory);
            $tablesContent['fileContent']['ChargeFame'] = $file;

            $fileCategory = "CriminalConfessionFile";
            $tablesContent['fileContent']['CriminalConfessionFile'] = "";
            $file = ''; //$this->fileContentService->fileFindByEntityID($entityID, $fileCategory);
            $tablesContent['fileContent']['CriminalConfessionFile'] = $file;

            $fileCategory = "OrderSheet";
            $tablesContent['fileContent']['OrderSheet'] = "";
            $file = ''; //$this->fileContentService->fileFindByEntityID($entityID, $fileCategory);
            $tablesContent['fileContent']['OrderSheet'] = $file;

            $tablesContent['fileContent']['AllFile'] = "";
            $file = ''; //$this->fileContentService->fileAllFindByEntityID($entityID);
            $tablesContent['fileContent']['AllFile'] = $file;
        }

        $tablesContent['divisions'] = DB::table('division')->get();
        $tablesContent['districts'] = DB::table('district')->get();
        $tablesContent['upazilas'] = DB::table('upazila')->get();
        $tablesContent['metropolitans'] = DB::table('geo_metropolitan')->get();
        $tablesContent['citycorporations'] = DB::table('geo_city_corporations')->get();
        $tablesContent['thanas'] = DB::table('geo_thanas')->get();
        $tablesContent['law_list'] = DB::table('mc_law')->get();

        return $tablesContent;
    }

    public function getLawListByProsecutorId($prosecutorId)
    {

        $flag = "false";
        $childs = array();

        $lawListForProsecutor = ProsecutorLawMappingRepository::getSelectedLawListByProsecutorId($prosecutorId);

        foreach ($lawListForProsecutor as $t) {

            $childs[] = array('id' => $t->id, 'name' => $t->name);

        }

        return response()->json($childs, 200);
        // $response = new \Phalcon\Http\Response();
        // $response->setContentType('application/json', 'UTF-8');
        // $response->setContent(json_encode($childs));
        // return $response;
    }

    public static function timeConvert($time, $f)
    {

        if (gettype($time) == 'string') {
            $time = strtotime($time);
        }

        $dateString = "";
        $dateString = ($f == 24) ? date("G:i A", $time) : date("g:i A", $time); // g:i: A   = 4:45 PM

        $dateString = (explode(" ", $dateString));
        if ($dateString[1] == 'AM') {
            $dateString = "পূর্বাহ্নে " . self::convertEnglishNumToBangla($dateString[0]);
        } else {
            $dateString = "মধ্যহ্ন " . self::convertEnglishNumToBangla($dateString[0]);
        }
        return $dateString;
    }

    public static function getProsecutionLocationName($divid, $zillaid, $locationType, $upazilaid, $citycorporationId, $metroPolitonId, $thanaId)
    {
        $underZillaLocation = '';
        $thanaName = '';

        // Fetch division name
        $divName = DB::table('division')->where('id', $divid)->first();
        $divName = $divName ? $divName->division_name_bn : '';

        // Fetch zilla by zillaid and divid

        $zilla = DB::table('district')->where('id', $zillaid)->where('division_id', $divid)->first();
        $zillaName = $zilla ? $zilla->district_name_bn : '';

        // Check location type
        if ($locationType == 'UPAZILLA') {
            $upazilla = DB::table('upazila')->where('district_id', $zillaid)->where('id', $upazilaid)->first();
            $underZillaLocation = $upazilla ? $upazilla->upazila_name_bn : '';
        } elseif ($locationType == 'CITYCORPORATION') {
            $cityCorporation = DB::table('geo_city_corporations')->where('district_bbs_code', $zillaid)->where('id', $citycorporationId)->first();
            $underZillaLocation = $cityCorporation ? $cityCorporation->city_corporation_name_bng : '';
        } elseif ($locationType == 'METROPOLITAN') {
            $metropolitan = DB::table('geo_metropolitan')->where('district_bbs_code', $zillaid)->where('id', $metroPolitonId)->first();
            $thana = DB::table('geo_thanas')->where('district_bbs_code', $zillaid)->where('geo_metropolitan_id', $metroPolitonId)->where('id', $thanaId)->first();
            $underZillaLocation = $metropolitan ? $metropolitan->metropolitan_name_bng : '';
            $thanaName = $thana ? $thana->thana_name_bng : '';
        }

        // Create location array
        $locationArray = array(
            "divName" => $divName,
            "zillaName" => $zillaName,
            "underZillaLocation" => $underZillaLocation,
            "thanaName" => $thanaName,
        );
        return $locationArray;
    }

    public static function convertEnglishNumToBangla($englishNum)
    {
        $bn_digits = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');

        $output = str_replace(range(0, 9), $bn_digits, $englishNum);
        return $output;
    }
    public static function saveProsecutionInformation($prosecutionId, $prosecutionInfo, $crimeDescription, $loginUserInfo)
    {

        $prosecution = Prosecution::find($prosecutionId);
//        $prosecution->subject = $crime_des; // অপরাধের বিবরণ
        if ($prosecution->is_suomotu == '1') {
            $prosecution->divid = $prosecutionInfo["division"];
            $prosecution->zillaId = $prosecutionInfo["zilla"];
            $prosecutionCaseCount = Prosecution::where('case_no', $prosecutionInfo["case_no"])->first();
            if ($prosecutionCaseCount) {
                if ($prosecutionCaseCount->id != $prosecution->id) {
                    return false;
                }
            } else {
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
            $prosecution->geo_citycorporation_id = $prosecutionInfo["citycorporation_id"] ? $prosecutionInfo["citycorporation_id"] : null;
        } else {
            $prosecution->geo_citycorporation_id = null;
            $prosecution->geo_ward_id = null;
            $prosecution->upazilaid = null;
            $prosecution->geo_metropolitan_id = $prosecutionInfo["metropolitan_id"] ? $prosecutionInfo["metropolitan_id"] : null;
            $prosecution->geo_thana_id = $prosecutionInfo["thanas"] ? $prosecutionInfo["thanas"] : null;
        }

        $prosecution->geo_ward_id = null; //  Linux Problem DataType

        $prosecution->update_by = $loginUserInfo['email'];
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
        } else {
            // $this->db->commit();
            return $prosecution;
        }

    }

}
