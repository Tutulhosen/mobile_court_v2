<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Court;
use App\Models\Criminal;
use App\Models\LawsBroken;
use App\Models\LawsBrokenProsecution;
use App\Models\OrderSheet;
use App\Models\OrderText;
use App\Models\Prosecution;
use App\Models\ProsecutionDetail;
use App\Models\ProsecutionDetails;
use App\Models\Punishment;
use App\Models\Seizurelist;
use App\Models\User;
use App\Repositories\ApiRepository;
use App\Repositories\CaseRepository;
use App\Repositories\CriminalRepository;
use App\Repositories\DashboardRepository;
use App\Repositories\LawRepository;
use App\Repositories\ProsecutionRepository;
use App\Repositories\PunishmentRepository;
use App\Repositories\SeizureRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProsecutionApiController extends BaseController
{
    public function create_prosecution(Request $request)
    {
        $jsonString = $request->getContent();
        // Decode the JSON into a PHP array
        $data = json_decode($jsonString, true);
        try {
            // validation input
            $validator = Validator::make($data, [
                "body_data.*.name" => 'required',
                "body_data.*.custodian_name" => 'required',
                "body_data.*.custodian_type" => 'required',
                "body_data.*.age" => 'required',
                "body_data.*.gender" => 'required',
                "body_data.*.occupation" => 'required',
                "body_data.*.divid" => 'required',
                "body_data.*.zillaId" => 'required',
                "body_data.*.upazilaid" => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "success" => false,
                    "message" => $validator->errors()->all(),
                    "err_res" => "",
                    "status" => 403,
                    "data" => null,
                ]);
            }

            $userinfo = globalUserInfo(); // gobal user
            $office_id = globalUserInfo()->office_id; // gobal user
            $officeinfo = DB::table('office')->where('id', $office_id)->first();
            $date = date('Y-m-d');
            // login magistate court info
            $court = DB::table('courts')->select('id as court_id')->where('magistrate_id', $userinfo->id)->where('date', $date)->where('status', 1)->get();

            if (count($court) == 0) {
                $data = [];
                return $this->sendErrormgs('', 'ইভেন্ট তৈরি করুন');
            }

            if ($userinfo->role_id == 25) {
                $profile = 'Prosecutor';
                $magistrateId = $request->data['selectMagistrateId'];
                $magistrateCourtId = $request->data["selectMagistrateCourtId"];
                $magistrate = User::find($magistrateId);
                $isSuomoto = 0;
                $court_id = $magistrateCourtId;
            } elseif ($userinfo->role_id == 26) {
                $profile = 'Magistrate';
                $magistrateId = $userinfo->id;
                $isSuomoto = 1;
                $court_id = $court[0]->court_id;
            }

            $case_no = $this->generateCaseNo();
            $userinfo = array(
                'id' => $userinfo->id,
                'magistrate-id' => $magistrateId,
                'courtname' => $userinfo->name,
                'name' => $userinfo->name,
                'email' => $userinfo->email,
                'profile' => $profile,
                'divid' => $officeinfo->division_id,
                'zillaid' => $officeinfo->district_id,
                'upozilaid' => $officeinfo->upazila_id,
                'divname' => $officeinfo->div_name_bn,
                'zillaname' => $officeinfo->dis_name_bn,
                'upozillaname' => $officeinfo->dis_name_bn,
                'serviceid' => $officeinfo->upa_name_bn,
                'office' => $officeinfo->office_name_bn,
                'officeType' => $officeinfo->organization_type,
                'joblocation' => $officeinfo->dis_name_bn,
                'mobile' => $userinfo->mobile_no,
                'designation' => $userinfo->designation,
                'role_id' => $userinfo->role_id,
                'court_id' => $court_id,
                'case_no' => $case_no,
            );

            $magistrateCourtId = $court_id;

            $prosecution = ApiRepository::createProsecutionShellForApi($isSuomoto, $userinfo, $magistrateCourtId, true);
            $prosecutionID = $prosecution->id;
            $caseinfo = array(
                'magistrate_id' => $magistrateId,
                'magistrate_name' => $userinfo['name'],
                'case_no' => $userinfo['case_no'],
                'prosecution_id' => $prosecutionID,
            );

            // $criminals = $request->criminal;
            $criminals = $data['body_data'];

            CriminalRepository::saveCriminals($criminals, $caseinfo, $userinfo);

            $crminalId = DB::table('prosecution_details')->select('criminal_id')->where('prosecution_id', $prosecutionID)->get();

            if (count($crminalId) == 0) {

                return $this->sendErrormgs('', 'কোন অপরাধী পাওয়া যায়নি');
            }
            $criminalInfo = [];
            foreach ($crminalId as $key => $value) {
                $criminalInfo[] = DB::table('criminals')->where('id', $value->criminal_id)->first();
            }
            $insertInfo['magistateInfo'] = globalUserInfo();
            $insertInfo['criminalInfo'] = $criminalInfo;
            $insertInfo['prosecutionInfo'] = $prosecution;

            return $this->sendResponse($insertInfo, 'মামলার আসামির তথ্য এট্রি করা হয়েছে ।');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }
    }

    public function create_prosecution_by_prosecutor(Request $request)
    {
        $jsonString = $request->getContent();
        // Decode the JSON into a PHP array
        $data = json_decode($jsonString, true);
        // return $data['body_data']['selectMagistrateCourtId'];

        try {
            // validation input
            $validator = Validator::make($data, [
                "body_data.criminal.*.name" => 'required|string|max:255',
                "body_data.criminal.*.custodian_name" => 'required|string|max:255',
                "body_data.criminal.*.custodian_type" => 'required|string|max:255',
                "body_data.criminal.*.age" => 'required|integer|min:0',
                "body_data.criminal.*.gender" => 'required|in:male,female,other',
                "body_data.criminal.*.occupation" => 'required|string|max:255',
                "body_data.criminal.*.divid" => 'required|integer',
                "body_data.criminal.*.zillaId" => 'required|integer',
                "body_data.criminal.*.upazilaid" => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "success" => false,
                    "message" => $validator->errors()->all(),
                    "err_res" => "",
                    "status" => 403,
                    "data" => null,
                ]);
            }
            $userinfo = globalUserInfo(); // gobal user
            $office_id = globalUserInfo()->office_id; // gobal user
            $officeinfo = DB::table('office')->where('id', $office_id)->first();
            $date = date('Y-m-d');
            // login magistate court info

            $court = $data['body_data']['selectMagistrateCourtId'];

            // dd($request);
            if ($userinfo->role_id == 25) {
                $profile = 'Prosecutor';
                $magistrateId = $data['body_data']['selectMagistrateId'];
                $magistrateCourtId = $data['body_data']['selectMagistrateCourtId'];
                $magistrate = User::find($magistrateId);
                $isSuomoto = 0;
                $court_id = $magistrateCourtId;
            } elseif ($userinfo->role_id == 26) {
                $profile = 'Magistrate';
                $magistrateId = $userinfo->id;
                $isSuomoto = 1;
                $court_id = $court[0]->court_id;
            }
            $case_no = $this->generateCaseNo();
            // dd($case_no);
            $userinfo = array(
                'id' => $userinfo->id,
                'magistrate-id' => $magistrateId,
                'courtname' => $userinfo->name,
                'name' => $userinfo->name,
                'email' => $userinfo->email,
                'profile' => $profile,
                'divid' => $officeinfo->division_id,
                'zillaid' => $officeinfo->district_id,
                'upozilaid' => $officeinfo->upazila_id,
                'divname' => $officeinfo->div_name_bn,
                'zillaname' => $officeinfo->dis_name_bn,
                'upozillaname' => $officeinfo->dis_name_bn,
                'serviceid' => $officeinfo->upa_name_bn,
                'office' => $officeinfo->office_name_bn,
                'officeType' => $officeinfo->organization_type,
                'joblocation' => $officeinfo->dis_name_bn,
                'mobile' => $userinfo->mobile_no,
                'designation' => $userinfo->designation,
                'role_id' => $userinfo->role_id,
                'court_id' => $court_id,
                'case_no' => $case_no,
            );

            $magistrateCourtId = $court_id;

            $prosecution = ApiRepository::createProsecutionShellForApi($isSuomoto, $userinfo, $magistrateCourtId, true);
            $prosecutionID = $prosecution->id;
            $caseinfo = array(
                'magistrate_id' => $magistrateId,
                'magistrate_name' => $userinfo['name'],
                'case_no' => $userinfo['case_no'],
                'prosecution_id' => $prosecutionID,
            );

            // $criminals = $request->criminal;
            $criminals = $data['body_data']['criminal'];
            // dd($criminals);
            CriminalRepository::saveCriminals($criminals, $caseinfo, $userinfo);

            $crminalId = DB::table('prosecution_details')->select('criminal_id')->where('prosecution_id', $prosecutionID)->get();

            if (count($crminalId) == 0) {

                return $this->sendErrormgs('', 'কোন অপরাধী পাওয়া যায়নি');
            }
            $criminalInfo = [];
            foreach ($crminalId as $key => $value) {
                $criminalInfo[] = DB::table('criminals')->where('id', $value->criminal_id)->first();
            }
            $insertInfo['magistateInfo'] = User::find($magistrateId);
            $insertInfo['criminalInfo'] = $criminalInfo;
            $insertInfo['prosecutionInfo'] = $prosecution;

            return $this->sendResponse($insertInfo, 'মামলার আসামির তথ্য এট্রি করা হয়েছে ।');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }
    }

    public function generateCaseNo($appealId = null)
    {
        $userinfo = globalUserInfo();
        $court = DB::table('courts')->select('id as court_id', 'title')->where('magistrate_id', $userinfo->id)->where('status', 1)->first();

        if (!empty($court->title)) {

            $part_1 = strtoupper(substr((!empty($court->title) ? $court->title : 'proce'), 0, 3)) . '-MC';
            $case_postition = DB::table('courts')
                ->selectRaw('count(*) as case_postition')
                ->where('id', $court->court_id)
                ->where('id', '<=', $court->court_id)
                ->first()->case_postition;
            $case_no = $part_1 . '-' . $case_postition . '-' . date('Y') . '-' . (!empty($court->court_id) ? $court->court_id : rand(5, 100)) . '-' . rand(10, 100);
        } else {
            $case_no = 'court' . '-' . rand(10, 90) . '-' . date('Y') . '-' . (!empty($court->court_id) ? $court->court_id : rand(5, 100)) . '-' . rand(10, 100);
        }

        return $case_no;
    }

    public function create_witness(Request $request)
    {

        // Assuming you receive the JSON as a string in the request body
        $jsonString = $request->getContent();

        // Decode the JSON into a PHP array
        $data = json_decode($jsonString, true);

        // try {
        $userinfo = globalUserInfo(); // gobal user
        $office_id = globalUserInfo()->office_id; // gobal user
        $officeinfo = DB::table('office')->where('id', $office_id)->first(); // office info
        $date = date('Y-m-d'); // today date
        // login magistate court info
        $court = DB::table('courts')->select('id as court_id')->where('magistrate_id', $userinfo->id)->where('date', $date)->where('status', 1)->get();
        if (count($court) == 0) { // if no court found
            // return $this->sendError('Error', 'No Court Found');
            return $this->sendErrormgs('', 'ইভেন্ট তৈরি করুন');
        }

        $prosecutionID = $data['body_data']['prosecutionId']; //$request->prosecutionId;

        $alldata = $data['body_data'];

        $isSuomoto = 1;

        if ($userinfo->role_id == 26) {
            $court = DB::table('courts')->select('id as court_id')->where('magistrate_id', $userinfo->id)->where('date', $date)->where('status', 1)->get();
            $court_id = $court[0]->court_id;
            $magistrateCourtId = $court_id;
        } elseif ($userinfo->role_id == 25) {
            $magistrateId = $request->data['selectMagistrateId'];
            $court_id = $request->data["selectMagistrateCourtId"];
            $magistrateCourtId = $request->data["selectMagistrateCourtId"];
            $magistrateCourtId = $court_id;
        } elseif ($userinfo->role_id == 25) {
            $magistrateId = $request->data['selectMagistrateId'];
            $court_id = $request->data["selectMagistrateCourtId"];
            $magistrateCourtId = $request->data["selectMagistrateCourtId"];
        }

        $case_no = $this->generateCaseNo();

        $userinfo = array(
            'id' => $userinfo->id,
            'magistrate-id' => $userinfo->id,
            'courtname' => $userinfo->name,
            'name' => $userinfo->name,
            'email' => $userinfo->email,
            'profile' => 'Magistrate', // P
            'divid' => $officeinfo->division_id,
            'zillaid' => $officeinfo->district_id,
            'upozilaid' => $officeinfo->upazila_id,
            'divname' => $officeinfo->div_name_bn,
            'zillaname' => $officeinfo->dis_name_bn,
            'upozillaname' => $officeinfo->dis_name_bn,
            'serviceid' => $officeinfo->upa_name_bn,
            'office' => $officeinfo->office_name_bn,
            'officeType' => $officeinfo->organization_type,
            'joblocation' => $officeinfo->dis_name_bn,
            'mobile' => $userinfo->mobile_no,
            'designation' => $userinfo->designation,
            'role_id' => $userinfo->role_id,
            'court_id' => $court_id,
            'case_no' => $case_no,
        );

        // dd(magistrateCourtId);
        if (!ProsecutionRepository::getProsecutionById($prosecutionID)) {
            $haseCriminal = false;
            if ($userinfo['profile'] == "Prosecutor") {
                $magistrateId = $request->data['selectMagistrateId'];
                $magistrateCourtId = $request->data["selectMagistrateCourtId"];
                $magistrate = User::find($magistrateId);
                $isSuomoto = 0;
            }

            $prosecution = ApiRepository::createProsecutionShellForApi($isSuomoto, $userinfo, $magistrateCourtId, $haseCriminal);

            $prosecutionID = $prosecution->id;
            $caseInformation["flag"] = ApiRepository::saveWitnessesForApi($alldata, $prosecutionID);
        } else {
            // dd($data);
            $caseInformation["flag"] = ApiRepository::saveWitnessesForApi($alldata, $prosecutionID);
        }

        $caseInformation['updatedProsecution'] = ProsecutionRepository::getProsecutionById($prosecutionID);
        $caseInformation['magistrateInfo'] = globalUserInfo();
        $caseInformation['case_no'] = $case_no;
        $caseInformation['prosecutionId'] = $prosecutionID;
        return $this->sendResponse($caseInformation, 'সাক্ষীর তথ্য  সফলভাবে সংরক্ষণ করা হয়েছে ।');
        // } catch (\Throwable $th) {
        //     return $this->sendError('Error', $th->getMessage());
        // }
    }


    public function create_witness_for_prosecutor(Request $request)
    {
        // dd($request->all());
        // Assuming you receive the JSON as a string in the request body
        $jsonString = $request->getContent();
        // dd($jsonString);
        // Decode the JSON into a PHP array
        $data = json_decode($jsonString, true);
        // dd($data);
        $prosecutionID = $data['body_data']['prosecutionId']; //$request->prosecutionId;
        // try {

        $userinfo = globalUserInfo(); // gobal user
        $office_id = globalUserInfo()->office_id; // gobal user
        $officeinfo = DB::table('office')->where('id', $office_id)->first(); // office info
        $date = date('Y-m-d'); // today date
        // login magistate court info
        if (!empty($prosecutionID)) {
            $court = DB::table('prosecutions')->select('court_id')->where('id', $prosecutionID)->first();
            $prosecution = DB::table('prosecutions')->where('id', $prosecutionID)->first();
            $magistrateId = $prosecution->magistrate_id;
            $court_id = $prosecution->court_id;
            $magistrateCourtId = $prosecution->court_id;
        } else {
            $court = $data['body_data']['selectMagistrateCourtId'];
            $magistrateId = $data['body_data']['selectMagistrateId'];
            $magistrateCourtId = $data['body_data']['selectMagistrateCourtId'];
            $court_id = $data['body_data']['selectMagistrateCourtId'];
        }

        if (empty($court)) { // if no court found
            return $this->sendErrormgs('', 'ইভেন্ট তৈরি করুন');
        }

        $alldata = $data['body_data'];
        // dd($alldata);

        $case_no = $this->generateCaseNo();

        $userinfo = array(
            'id' => $userinfo->id,
            'magistrate-id' => $userinfo->id,
            'courtname' => $userinfo->name,
            'name' => $userinfo->name,
            'email' => $userinfo->email,
            'profile' => 'Magistrate', // P
            'divid' => $officeinfo->division_id,
            'zillaid' => $officeinfo->district_id,
            'upozilaid' => $officeinfo->upazila_id,
            'divname' => $officeinfo->div_name_bn,
            'zillaname' => $officeinfo->dis_name_bn,
            'upozillaname' => $officeinfo->dis_name_bn,
            'serviceid' => $officeinfo->upa_name_bn,
            'office' => $officeinfo->office_name_bn,
            'officeType' => $officeinfo->organization_type,
            'joblocation' => $officeinfo->dis_name_bn,
            'mobile' => $userinfo->mobile_no,
            'designation' => $userinfo->designation,
            'role_id' => $userinfo->role_id,
            'court_id' => $court_id,
            'case_no' => $case_no,
        );

        // dd(magistrateCourtId);
        if (!ProsecutionRepository::getProsecutionById($prosecutionID)) {

            $haseCriminal = false;
            $magistrateId = $data['body_data']['selectMagistrateId'];
            $magistrateCourtId = $data['body_data']['selectMagistrateCourtId'];
            $court_id = $data['body_data']['selectMagistrateCourtId'];
            $magistrate = User::find($magistrateId);
            $isSuomoto = 0;
            $prosecution = ApiRepository::createProsecutionShellForApi($isSuomoto, $userinfo, $magistrateCourtId, $haseCriminal);

            $prosecutionID = $prosecution->id;
            $caseInformation["flag"] = ApiRepository::saveWitnessesForApi($alldata, $prosecutionID);
        } else {
            // dd($data);
            $caseInformation["flag"] = ApiRepository::saveWitnessesForApi($alldata, $prosecutionID);
        }

        $caseInformation['updatedProsecution'] = ProsecutionRepository::getProsecutionById($prosecutionID);
        $caseInformation['magistrateInfo'] = globalUserInfo();
        $caseInformation['case_no'] = $case_no;
        $caseInformation['prosecutionId'] = $prosecutionID;
        return $this->sendResponse($caseInformation, 'সাক্ষীর তথ্য  সফলভাবে সংরক্ষণ করা হয়েছে ।');
        // } catch (\Throwable $th) {
        //     return $this->sendError('Error', $th->getMessage());
        // }
    }

    public function create_ovijug(Request $request)
    {
        // Assuming you receive the JSON as a string in the request body
        $jsonString = $request->getContent();
        // Decode the JSON into a PHP array
        $alldata = json_decode($jsonString, true);
        // dd($alldata);
        // try {
        // return $this->sendResponse($request->all(), "data");
        $userinfo = globalUserInfo(); // gobal user
        $office_id = globalUserInfo()->office_id; // gobal user
        $officeinfo = DB::table('office')->where('id', $office_id)->first(); // office info
        $date = date('Y-m-d'); // today date
        // login magistate court info
        $court = DB::table('courts')->select('id as court_id')->where('magistrate_id', $userinfo->id)->where('date', $date)->where('status', 1)->get();
        if (count($court) == 0) { // if no court found
            return $this->sendErrormgs('', 'ইভেন্ট তৈরি করুন');
        }

        $userinfo = array(
            'id' => $userinfo->id,
            'magistrate-id' => $userinfo->id,
            'courtname' => $userinfo->name,
            'name' => $userinfo->name,
            'email' => $userinfo->email,
            'profile' => 'Magistrate', // P
            'divid' => $officeinfo->division_id,
            'zillaid' => $officeinfo->district_id,
            'upozilaid' => $officeinfo->upazila_id,
            'divname' => $officeinfo->div_name_bn,
            'zillaname' => $officeinfo->dis_name_bn,
            'upozillaname' => $officeinfo->dis_name_bn,
            'serviceid' => $officeinfo->upa_name_bn,
            'office' => $officeinfo->office_name_bn,
            'officeType' => $officeinfo->organization_type,
            'joblocation' => $officeinfo->dis_name_bn,
            'mobile' => $userinfo->mobile_no,
            'designation' => $userinfo->designation,
            'role_id' => $userinfo->role_id,
        );
        // $court_id = $court[0]->court_id; // court id

        // $prosecutionID = $request->prosecutionId;

        // $case_no = $this->generateCaseNo();
        $data = $alldata['body_data'];
        // $isSuomoto = 1;
        $prosecutionId = $data['prosecutionId'];
        $brokenLawsArray = $data['brokenLaws'];
        $prosecutionInfo = $data['prosecutionInfo'];

        $crimeDescription = LawRepository::saveLawsBrokenList($prosecutionId, $brokenLawsArray, $userinfo);

        $prosecution = ApiRepository::saveProsecutionInformation($prosecutionId, $prosecutionInfo, $crimeDescription, $userinfo);
        // dd($data);
        $lawsBrokenList = LawsBroken::where('prosecution_id', $prosecutionId)->get();
        if ($prosecution == false) {
            // $msg["flag"] = "false";
            // $msg["message"] = "এই মামলা নম্বর দিয়ে ইতিমধ্যেই একটি মামলা দাখিল হয়ছে।";
            return $this->sendErrormgs([], "এই মামলা নম্বর দিয়ে ইতিমধ্যেই একটি মামলা দাখিল হয়ছে।");
        }

        $prosecutin_details_list = ProsecutionDetail::where('prosecution_id', $prosecutionId)->get();
        //    return $prosecutin_details_list = ProsecutionDetails::where('prosecution_id', $prosecutionId)->get();
        $criminal = []; // Initialize an empty array

        foreach ($prosecutin_details_list as $prosecution_details) {

            // Get the criminal details using the criminal_id from prosecution details
            $criminalDetails = Criminal::where('id', $prosecution_details->criminal_id)->first();
            if ($criminalDetails) {
                $criminal[] = $criminalDetails; // Append each criminal detail to the array
            }
        }

        $tablesContent = array();
        if (count($lawsBrokenList) > 0) {
            foreach ($lawsBrokenList as $b3) {
                $sections = DB::table('mc_section')->where('id', $b3['section_id'])->where('law_id', $b3['law_id'])->first();

                $laws = DB::table('mc_law')->where('id', $b3['law_id'])->first();
                $sectitle = array(
                    "LawID" => $b3['law_id'],
                    "LawsBrokenID" => $b3['id'],
                    "ProsecutionID" => $b3['prosecution_id'],
                    "SectionID" => $b3['section_id'],
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
                $tablesContent[] = $sectitle;
            }
        } else {
            $tablesContent[] = null;
        }

        // $msg["flag"] = "true";
        // $msg["message"] = "অভিযোগ দায়ের সফলভাবে সম্পন্ন হয়েছে।";
        $msg["case_no"] = $prosecution->case_no;
        $msg["criminal_info"] = $criminal;
        $msg["LawsBrokenList"] = $tablesContent;
        // $msg["caseInfo"] = $caseInformation;
        $msg["lawsBroken"] = $lawsBrokenList;
        $msg["step"] = 4; // for seizure list
        $msg["no_criminal"] = $prosecution->no_criminal; // $prosecution->no_criminal
        $msg["no_criminal_punish"] = 0;
        $msg["isSuomoto"] = $prosecution->is_suomotu;
        return $this->sendResponse($msg, 'অভিযোগ দায়ের সফলভাবে সম্পন্ন হয়েছে।');
        // } catch (\Throwable $th) {
        //     return $this->sendError('Error', $th->getMessage());
        // }
    }

    public function create_ovijug_for_prosecutor(Request $request)
    {
        // Assuming you receive the JSON as a string in the request body
        $jsonString = $request->getContent();
        // Decode the JSON into a PHP array
        $alldata = json_decode($jsonString, true);
        // dd($alldata);
        // try {
        // return $this->sendResponse($request->all(), "data");
        $userinfo = globalUserInfo(); // gobal user
        $office_id = globalUserInfo()->office_id; // gobal user
        $officeinfo = DB::table('office')->where('id', $office_id)->first(); // office info
        $date = date('Y-m-d'); // today date
        $data = $alldata['body_data'];

        // login magistate court info
        $court = DB::table('prosecutions')->select('court_id')->where('id', $data['prosecutionId'])->first();
        // dd(empty($court));
        if (empty($court)) { // if no court found
            return $this->sendErrormgs('', 'ইভেন্ট তৈরি করুন');
        }

        $userinfo = array(
            'id' => $userinfo->id,
            'magistrate-id' => $userinfo->id,
            'courtname' => $userinfo->name,
            'name' => $userinfo->name,
            'email' => $userinfo->email,
            'profile' => 'Prosecutor', // P
            'divid' => $officeinfo->division_id,
            'zillaid' => $officeinfo->district_id,
            'upozilaid' => $officeinfo->upazila_id,
            'divname' => $officeinfo->div_name_bn,
            'zillaname' => $officeinfo->dis_name_bn,
            'upozillaname' => $officeinfo->dis_name_bn,
            'serviceid' => $officeinfo->upa_name_bn,
            'office' => $officeinfo->office_name_bn,
            'officeType' => $officeinfo->organization_type,
            'joblocation' => $officeinfo->dis_name_bn,
            'mobile' => $userinfo->mobile_no,
            'designation' => $userinfo->designation,
            'role_id' => $userinfo->role_id,
        );
        // $court_id = $court[0]->court_id; // court id

        // $prosecutionID = $request->prosecutionId;

        // $case_no = $this->generateCaseNo();
        // $isSuomoto = 1;
        $prosecutionId = $data['prosecutionId'];
        $brokenLawsArray = $data['brokenLaws'];
        $prosecutionInfo = $data['prosecutionInfo'];

        $crimeDescription = LawRepository::saveLawsBrokenList($prosecutionId, $brokenLawsArray, $userinfo);
        $prosecution = ApiRepository::saveProsecutionInformation($prosecutionId, $prosecutionInfo, $crimeDescription, $userinfo);
        $lawsBrokenList = LawsBrokenProsecution::where('prosecution_id', $prosecutionId)->get();
        // dd($lawsBrokenList);
        if ($prosecution == false) {
            // $msg["flag"] = "false";
            // $msg["message"] = "এই মামলা নম্বর দিয়ে ইতিমধ্যেই একটি মামলা দাখিল হয়ছে।";
            return $this->sendErrormgs([], "এই মামলা নম্বর দিয়ে ইতিমধ্যেই একটি মামলা দাখিল হয়ছে।");
        }

        $prosecutin_details_list = ProsecutionDetail::where('prosecution_id', $prosecutionId)->get();
        //    return $prosecutin_details_list = ProsecutionDetails::where('prosecution_id', $prosecutionId)->get();
        $criminal = []; // Initialize an empty array

        foreach ($prosecutin_details_list as $prosecution_details) {

            // Get the criminal details using the criminal_id from prosecution details
            $criminalDetails = Criminal::where('id', $prosecution_details->criminal_id)->first();
            if ($criminalDetails) {
                $criminal[] = $criminalDetails; // Append each criminal detail to the array
            }
        }

        $tablesContent = array();
        // dd($lawsBrokenList);
        if (count($lawsBrokenList) > 0) {
            foreach ($lawsBrokenList as $b3) {
                $sections = DB::table('mc_section')->where('id', $b3['section_id'])->where('law_id', $b3['law_id'])->first();

                $laws = DB::table('mc_law')->where('id', $b3['law_id'])->first();
                $sectitle = array(
                    "LawID" => $b3['law_id'],
                    "LawsBrokenID" => $b3['id'],
                    "ProsecutionID" => $b3['prosecution_id'],
                    "SectionID" => $b3['section_id'],
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
                $tablesContent[] = $sectitle;
            }
        } else {
            $tablesContent = [];
        }

        // $msg["flag"] = "true";
        // $msg["message"] = "অভিযোগ দায়ের সফলভাবে সম্পন্ন হয়েছে।";
        $msg["case_no"] = $prosecution->case_no;
        $msg["prosecution"] = $prosecution;
        $msg["criminal_info"] = $criminal;
        $msg["LawsBrokenList"] = $tablesContent;
        $msg["lawsBroken"] = $lawsBrokenList;
        $msg["step"] = 4; // for seizure list
        $msg["no_criminal"] = $prosecution->no_criminal; // $prosecution->no_criminal
        $msg["no_criminal_punish"] = 0;
        $msg["isSuomoto"] = $prosecution->is_suomotu;
        return $this->sendResponse($msg, 'অভিযোগ দায়ের সফলভাবে সম্পন্ন হয়েছে।');
        // } catch (\Throwable $th) {
        //     return $this->sendError('Error', $th->getMessage());
        // }
    }

    public function create_ovijug_old(Request $request)
    {
        try {
            // return $this->sendResponse($request->all(), "data");
            $userinfo = globalUserInfo(); // gobal user
            $office_id = globalUserInfo()->office_id; // gobal user
            $officeinfo = DB::table('office')->where('id', $office_id)->first(); // office info
            $date = date('Y-m-d'); // today date
            // login magistate court info
            $court = DB::table('court')->select('id as court_id')->where('magistrate_id', $userinfo->id)->where('date', $date)->where('status', 1)->get();
            if (count($court) == 0) { // if no court found
                return $this->sendError('Error', 'No Court Found');
            }
            $userinfo = array(
                'id' => $userinfo->id,
                'magistrate-id' => $userinfo->id,
                'courtname' => $userinfo->name,
                'name' => $userinfo->name,
                'email' => $userinfo->email,
                'profile' => 'Magistrate', // P
                'divid' => $officeinfo->division_id,
                'zillaid' => $officeinfo->district_id,
                'upozilaid' => $officeinfo->upazila_id,
                'divname' => $officeinfo->div_name_bn,
                'zillaname' => $officeinfo->dis_name_bn,
                'upozillaname' => $officeinfo->dis_name_bn,
                'serviceid' => $officeinfo->upa_name_bn,
                'office' => $officeinfo->office_name_bn,
                'officeType' => $officeinfo->organization_type,
                'joblocation' => $officeinfo->dis_name_bn,
                'mobile' => $userinfo->mobile_no,
                'designation' => $userinfo->designation,
                'role_id' => $userinfo->role_id,
            );
            // $court_id = $court[0]->court_id; // court id

            // $prosecutionID = $request->prosecutionId;

            // $case_no = $this->generateCaseNo();
            $data = $request->all();
            // $isSuomoto = 1;
            $prosecutionId = $data['prosecutionId'];
            $brokenLawsArray = $data['brokenLaws'];
            $prosecutionInfo = $data['prosecutionInfo'];

            $crimeDescription = LawRepository::saveLawsBrokenList($prosecutionId, $brokenLawsArray, $userinfo);

            $prosecution = ProsecutionRepository::saveProsecutionInformation($prosecutionId, $prosecutionInfo, $crimeDescription, $userinfo);
            // dd($data);
            $lawsBrokenList = LawsBroken::where('prosecution_id', $prosecutionId)->get();
            if ($prosecution == false) {
                $msg["flag"] = "false";
                $msg["message"] = "এই মামলা নম্বর দিয়ে ইতিমধ্যেই একটি মামলা দাখিল হয়ছে।";
                return $this->sendError('Error', $msg);
            }
            $prosecutin_details_list = ProsecutionDetails::where('prosecution_id', $prosecutionId)->get();
            $criminal = []; // Initialize an empty array

            foreach ($prosecutin_details_list as $prosecution_details) {
                // Get the criminal details using the criminal_id from prosecution details
                $criminalDetails = Criminal::where('id', $prosecution_details->criminal_id)->first();
                if ($criminalDetails) {
                    $criminal[] = $criminalDetails; // Append each criminal detail to the array
                }
            }

            $tablesContent = array();
            if (count($lawsBrokenList) > 0) {
                foreach ($lawsBrokenList as $b3) {
                    $sections = DB::table('mc_section')->where('id', $b3['section_id'])->where('law_id', $b3['law_id'])->first();

                    $laws = DB::table('mc_law')->where('id', $b3['law_id'])->first();
                    $sectitle = array(
                        "LawID" => $b3['law_id'],
                        "LawsBrokenID" => $b3['id'],
                        "ProsecutionID" => $b3['prosecution_id'],
                        "SectionID" => $b3['section_id'],
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
                    $tablesContent[] = $sectitle;
                }
            } else {
                $tablesContent[] = null;
            }
            $msg["flag"] = "true";
            $msg["message"] = "অভিযোগ দায়ের সফলভাবে সম্পন্ন হয়েছে।";
            $msg["case_no"] = $prosecution->case_no;
            $msg["criminal_info"] = $criminal;
            $msg["LawsBrokenList"] = $tablesContent;
            // $msg["caseInfo"] = $caseInformation;
            $msg["lawsBroken"] = $lawsBrokenList;
            $msg["step"] = 4; // for seizure list
            $msg["no_criminal"] = $prosecution->no_criminal; // $prosecution->no_criminal
            $msg["no_criminal_punish"] = 0;
            $msg["isSuomoto"] = $prosecution->is_suomotu;
            return $this->sendResponse($msg, 'সাক্ষীর তথ্য  সফলভাবে সংরক্ষণ করা হয়েছে ।');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }
    }

    public function get_seizure_item_type($id = null)
    {
        try {
            if ($id != null) {
                $data = DB::table('seizureitem_type')->where('id', $id)->get();

                foreach ($data as $item) {
                    $item->description = $this->generateSeizureMessage($item->id);
                }

                return $this->sendResponse($data, 'Seizure Item Type List');
            }

            $data = DB::table('seizureitem_type')->get();

            foreach ($data as $item) {
                $item->description = $this->generateSeizureMessage($item->id);
            }

            return $this->sendResponse($data, 'Seizure Item Type List');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }
    }

    public function generateSeizureMessage($id)
    {
        if ($id == 1) {
            return "-কে জব্দকৃত মালামালের নমুনা সংরক্ষণপূর্বক ধ্বংসের ব্যবস্থা গ্রহণ করার জন্য বলা হলো।";
        } else if ($id == 2) {
            return "-কে জব্দকৃত মালামাল নিলামে বিক্রয়ের ব্যবস্থা গ্রহণ করার জন্য বলা হলো।";
        } else if ($id == 4) {
            return "-কে জব্দকৃত মালামাল নিলামে বিক্রয়ের ব্যবস্থা গ্রহণ করার জন্য বলা হলো।";
        } else if ($id == 6) {
            return "-কে জব্দকৃত মালামাল জিম্মায় রাখার জন্য বলা হলো।";
        } else {
            return null;
        }
    }

    public function create_seizure_item_list(Request $request)
    {
        try {
            // $data = $request->all();
            // Assuming you receive the JSON as a string in the request body
            $jsonString = $request->getContent();
            $dataall = json_decode($jsonString, true); // Decodes the JSON to an associative array
            $data = $dataall['body_data'] ?? null; // Retrieves 'body_data' from the decoded array

            $userinfo = globalUserInfo();
            $seizure_data = $data['seizure_data'];
            $prosecution_id = $data['prosecution_id'];
            $seizureitem_type = $data['seizureitem_type'];

            $prosecution = Prosecution::find($prosecution_id);

            $seizureService = SeizureRepository::saveSeizureList_api($prosecution_id, $seizure_data, $seizureitem_type, $userinfo);
            $sezureListIntem = Seizurelist::where('prosecution_id', $prosecution_id)->get();

            $seizureOrderContext = SeizureRepository::getSeizureOrderContext($prosecution_id);
            // return $this->sendResponse($seizureOrderContext, 'Seizure Item List');
            $prosecutin_details_list = ProsecutionDetail::where('prosecution_id', $prosecution_id)->get();
            $criminal = []; // Initialize an empty array
            foreach ($prosecutin_details_list as $prosecution_details) {
                // Get the criminal details using the criminal_id from prosecution details
                $criminalDetails = Criminal::where('id', $prosecution_details->criminal_id)->first();
                if ($criminalDetails) {
                    $criminal[] = $criminalDetails; // Append each criminal detail to the array
                }
            }
            $caseInfo = CaseRepository::getCaseInformationByProsecutionId($prosecution_id);

            $dataRes["criminal_info"] = $criminal;
            $dataRes['prosecution'] = $prosecution;
            $dataRes['seizureService'] = $sezureListIntem;
            $dataRes['seizureOrderContext'] = $seizureOrderContext;
            $dataRes["lawbroken"] = $caseInfo['lawsBrokenList'];

            return $this->sendResponse($dataRes, 'জব্দতালিকা  সফলভাবে সংরক্ষণ করা হয়েছে ।');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }
    }

    public function saveCriminalConfessionSuomotu(Request $request)
    {
        $jsonString = $request->getContent();
        $dataall = json_decode($jsonString, true); // Decodes the JSON to an associative array
        $data = $dataall['body_data'] ?? null; // Retrieves 'body_data' from the decoded array

        try {
            // return $request->all();
            $loginUserInfo = globalUserInfo();
            $userinfo = array(
                'email' => $loginUserInfo->email,
            );
            $prosecutionID = $data['prosecution_id'];
            $confessionDetails = $data['criminalConfessionDetails'];
            // return $request->all();
            $prosecution = CriminalRepository::saveConfessionDetails($prosecutionID, $confessionDetails, $userinfo);
            $caseInfo = CaseRepository::getCaseInformationByProsecutionId($prosecutionID);
            $seizureOrderContext = SeizureRepository::getSeizureOrderContext($prosecutionID);
            $lawsBrokenList = $caseInfo['lawsBrokenList'];
            $list = [];
            foreach ($lawsBrokenList as $value) {
                $list[] = [
                    'LawsBrokenID' => $value['LawsBrokenID'],
                    'sec_description' => $value['sec_description'],
                    'sec_number' => $value['sec_number'],
                    'law_title' => $value['sec_title'],
                ];
                // print_r( $value['sec_title']);
            }

            $prosecutin_details_list = ProsecutionDetail::where('prosecution_id', $prosecutionID)->get();
            $criminal = []; // Initialize an empty array
            foreach ($prosecutin_details_list as $prosecution_details) {
                // Get the criminal details using the criminal_id from prosecution details
                $criminalDetails = Criminal::where('id', $prosecution_details->criminal_id)->first();
                // $cus_name =$criminalDetails->custodian_type.' '.$prosecution_details->custodian_name;

                if ($criminalDetails) {
                    $criminal[] = [
                        'id' => $criminalDetails->id,
                        'name_bng' => $criminalDetails->name_bng,
                        'custodian_type' => $criminalDetails->custodian_type,
                        'custodian_name' => $criminalDetails->custodian_name,
                    ];
                }
            }

            if ($prosecution) {
                $data['isSuomotoFlag'] = $prosecution->is_suomotu;
                $data['seizureOrderContext'] = $seizureOrderContext;
                $data['lawsBrokenList'] = $list;
                $data['criminal'] = $criminal;
            }
            return $this->sendResponse($data, 'জবানবন্দী সংরক্ষণ করা হয়েছে');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }
    }

    public function incompletecase(Request $request)
    {
        // dd(225);
        $magistrate = globalUserInfo(); // Assuming the magistrate is the logged-in user
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 20);
        if ($magistrate->role_id != 25) {

            $query = Prosecution::select([
                'prosecutions.id as id',
                'prosecutions.subject as subject',
                'prosecutions.date as date',
                'prosecutions.location as location',
                'prosecutions.case_no as case_no',
                'court.status as status',
                'prosecutions.prosecutor_name as prosecutor_name',
                'prosecutions.prosecutor_id as prosecutor_id',
                'magistrate.name',
                'prosecutions.is_approved as is_approved',
                DB::raw('IF(szr.id IS NOT NULL, 1, 0) as is_seizurelist'),
                DB::raw('IF(prosecutions.orderSheet_id IS NOT NULL, 1, 0) as is_orderSheet'),
            ])
                ->join('courts as court', 'court.id', '=', 'prosecutions.court_id')
                ->join('users as magistrate', 'magistrate.id', '=', 'court.magistrate_id')
                ->leftJoin('seizurelists as szr', 'szr.prosecution_id', '=', 'prosecutions.id')
                ->where('court.magistrate_id', $magistrate->id)
                ->where('is_suomotu', 1)
                ->where('hasCriminal', 1)
                ->whereNull('orderSheet_id')
                ->where('court.status', '!=', 2)
                ->where('prosecutions.delete_status', 1)
                ->groupBy('prosecutions.id')
                ->orderBy('prosecutions.id', 'DESC')
                ->orderBy('prosecutions.date', 'DESC');
            $allprosecutions = $query->paginate($limit, ['*'], 'page', $page); // Add pagination here
            $totalCount = Prosecution::join('courts as court', 'court.id', '=', 'prosecutions.court_id')->where('court.status', '!=', 2)->count();
        } else {

            // dd(1);
            // Build the query with necessary conditions and joins
            $query = Prosecution::select(
                'prosecutions.id as id',
                'prosecutions.subject as subject',
                'prosecutions.date as date',
                'prosecutions.location as location',
                'prosecutions.case_no as case_no',
                'court.status as status',
                'court.date as courtdate',
                'court.magistrate_id as magistrateid',
                'szr.id as is_seizurelist',
                'prosecutions.prosecutor_name as prosecutor_name',
                'prosecutions.prosecutor_id as prosecutor_id'
            )
                ->join('courts as court', 'court.id', '=', 'prosecutions.court_id')
                ->leftJoin('seizurelists as szr', 'szr.prosecution_id', '=', 'prosecutions.id')
                ->where('prosecutions.prosecutor_id', $magistrate->id)
                ->where('prosecutions.is_suomotu', 0)
                ->where('prosecutions.hasCriminal', 1)
                ->where('prosecutions.is_approved', 0)
                ->where('prosecutions.case_status', '<', 6)
                ->orderBy('prosecutions.created_at', 'DESC');
            $allprosecutions = $query->paginate($limit, ['*'], 'page', $page); // Add pagination here
            $totalCount = Prosecution::join('courts as court', 'court.id', '=', 'prosecutions.court_id')->where('court.status', '!=', 2)->count();
        }

        $data['totalCount'] = $totalCount;
        $criminal = []; // Initialize an empty array
        // array_push($criminal, ['totalCount' =>$totalCount]);

        foreach ($allprosecutions as $prosecution_details) {
            $criminal[] = [
                'id' => $prosecution_details->id,
                'case_no' => $prosecution_details->case_no,
                'date' => $prosecution_details->date,
                'subject' => $prosecution_details->subject,
                'location' => $prosecution_details->location,
                'seizurelists' => count($prosecution_details->seizurelists),
                'orderSheet_id' => $prosecution_details->orderSheet_id,
                'court_status' => $prosecution_details->status,
                'prosecutor_name' => $prosecution_details->prosecutor_name,
            ];
        }
        $data['criminal'] = $criminal;
        $response = [
            'success' => true,
            'message' => 'incomplete list',
            'status' => 200,
            'totalCount' => $totalCount,
            'data' => $criminal,
        ];

        return response()->json($response);
    }

    public function getIncompleteCaseDetails(Request $request)
    {
        $prosecutionId = $request->prosecutionId;
        $caseInfo = ApiRepository::getCaseInformationByProsecutionId($prosecutionId);

        if ($caseInfo['prosecution']->case_no != 'অভিযোগ গঠন হয়নি') {
            $case_no = $caseInfo['prosecution']->case_no;
        } else {
            $case_no = $this->generateCaseNo();
        }

        $data = [
            'status' => true, // Setting status to true indicating success
            'message' => 'অসম্পূর্ণ মামলার(স্বপ্রণোদিত) তথ্য সফলভাবে পুনরুদ্ধার করা হয়েছে',
            'data' => [
                'caseInfo' => $caseInfo, // Include case information
                'case_no' => $case_no, // Include generated case number
            ],
        ];

        return response()->json($data, 200); // Returning the response with status 200 (OK)
    }

    public function prosecutorListwithCriminal()
    {
        $magistrate = Auth::user();
        // Build the query with relationships and conditions
        $prosecution = Prosecution::join('courts', 'courts.id', '=', 'prosecutions.court_id')
            ->where('courts.magistrate_id', $magistrate->id)
            ->select(
                'prosecutions.id',
                'prosecutions.subject',
                'prosecutions.date',
                'prosecutions.location',
                'prosecutions.case_no',
                'courts.status',
                'courts.date as courtdate',
                'prosecutions.prosecutor_name'
            )
            ->join('courts as court', 'court.id', '=', 'prosecutions.court_id')
            ->where('court.magistrate_id', $magistrate->id)
            ->where('prosecutions.delete_status', '!=', 2)
            ->where('prosecutions.is_approved', 0)
            ->where('prosecutions.hasCriminal', 1)
            ->where('prosecutions.case_status', '>', 3)
            ->orderBy('prosecutions.id', 'desc')
            ->get();
        //  ->paginate(10, ['*'], 'page', $numberPage);
        return $this->sendResponse($prosecution, 'প্রসিকিউশনের তালিকা');
    }

    public function getIncompleteCaseDetailsWithoutCriminal(Request $request)
    {
        $prosecutionId = $request->prosecutionId;
        $caseInfo = ApiRepository::getCaseInformationWithoutCriminalByProsecutionId($prosecutionId);

        if ($caseInfo['prosecution']->case_no != 'অভিযোগ গঠন হয়নি') {
            $case_no = $caseInfo['prosecution']->case_no;
        } else {
            $case_no = $this->generateCaseNo();
        }

        $data = [
            'status' => true, // Setting status to true indicating success
            'message' => 'অসম্পূর্ণ মামলার(স্বপ্রণোদিত) তথ্য সফলভাবে পুনরুদ্ধার করা হয়েছে',
            'data' => [
                'caseInfo' => $caseInfo, // Include case information
                'case_no' => $case_no, // Include generated case number
            ],
        ];

        return response()->json($data, 200); // Returning the response with status 200 (OK)
    }

    public function searchComplain(Request $request)
    {
        $userinfo = Auth::user();
        $magistrateId = $userinfo->id;
        $page = $request->input('page', 1);
        $InCompleteCaseListProcecution = Prosecution::select([
            'prosecutions.id as id',
            'prosecutions.subject as subject',
            'prosecutions.date as date',
            'prosecutions.location as location',
            'prosecutions.case_no as case_no',
            'prosecutions.hints as hints',
            'prosecutions.hasCriminal as hasCriminal',
            'prosecutions.prosecutor_name as prosecutor_name',
            DB::raw('CONCAT(CCF.id) as is_criminal_confession'),
            'prosecutions.orderSheet_id as orderSheet_id',
            'court.status as status',
            'court.date as courtdate',
            'prosecutions.is_suomotu as is_suomotu',
            'prosecutions.is_approved',
        ])
            ->leftJoin('criminal_confessions as CCF', 'CCF.prosecution_id', '=', 'prosecutions.id')
            ->join('courts as court', 'court.id', '=', 'prosecutions.court_id')
            ->where('court.magistrate_id', $magistrateId)
            ->where('prosecutions.is_approved', 1)
            ->where('prosecutions.is_suomotu', '=', 0)
            ->whereNull('prosecutions.orderSheet_id')
            ->where('prosecutions.delete_status', '!=', 2)
            ->groupBy([
                'prosecutions.id',
                'prosecutions.subject',
                'prosecutions.date',
                'prosecutions.location',
                'prosecutions.case_no',
                'prosecutions.hints',
                'prosecutions.prosecutor_name',
            ])
            ->orderBy('prosecutions.id', 'DESC');

        $limit = $request->limit;
        // $data = $InCompleteCaseListProcecution->paginate($limit ?? 20);
        $data['prosecutions'] = $InCompleteCaseListProcecution->paginate($limit, ['*'], 'page', $page);
        return $this->sendResponse($data, 'InComplete Case List With Procecution');
    }

    // public function showForms(Request $request)
    // {
    //     $data = [];
    //     $numberPage = 1;
    //     if ($request->isMethod('post')) {
    //         $searchParams = $request->except('_token');
    //         session(['searchParams' => $searchParams]);
    //     } else {
    //         $numberPage = $request->query('page', 1);
    //     }
    //     $parameters = session('searchParams', []);
    //     $cond = "is_approved = 1";
    //     if (!isset($parameters['conditions'])) {
    //         $parameters['conditions'] = $cond;
    //     } else {
    //         $parameters['conditions'] .= ' AND ' . $cond;
    //     }
    //     $parameters['order'] = "prose.id DESC";
    //     $user = Auth::user();
    //     $magistrate_id = $user->id;

    //     $searchCondition = 'prose.is_approved =1 AND  prose.delete_status = 1 AND prose.orderSheet_id IS NOT NULL';
    //     $startDate = (!empty($request->start_date) ? $request->start_date : "");
    //     $startDate = date('Y-m-d', strtotime($startDate));
    //     $endDate = (!empty($request->end_date) ? $request->end_date : "");
    //     if ($endDate == '') {
    //         $endDate = date('Y-m-d');
    //     } else {
    //         $endDate = date('Y-m-d', strtotime($endDate));
    //     }
    //     if ($endDate == '') {
    //         $endDate = date('Y-m-d H:i:s');
    //     }

    //     $caseNo = $request->case_no; // Default to an empty string if not provided
    //     $searchCriteria = $request->searchCriteria; // Default to an empty string if not provided
    //     $magistrateID = $request->magistrate_id; // Default to an empty string if not provided

    //     $prosecutions1 = DB::table('prosecutions AS prose')->join('courts AS court', 'court.id', '=', 'prose.court_id');

    //     if ($searchCriteria === 'is_case') {
    //         $searchCondition = 'prose.case_no = ?'; // Use parameter binding for security
    //         $searchCriteriaLabel = '(মামলার নম্বর: ' . $caseNo . ')';
    //         $paginateType = 'is_case';
    //         $prosecutions = $prosecutions1->where('prose.case_no', $caseNo); // Use parameter binding to avoid SQL injection
    //     }

    //     if ($searchCriteria === 'is_date') {

    //         $searchCondition .= 'prose.date BETWEEN ? AND ?';
    //         $searchCriteriaLabel = '(তারিখ: ' . $startDate . ' থেকে ' . $endDate . ')';
    //         $paginateType = 'is_date';
    //         $prosecutions = $prosecutions1->whereBetween('prose.date', [$startDate, $endDate]);
    //     }

    //     $prosecutions = $prosecutions1->where('court.magistrate_id', $magistrate_id);
    //     $prosecutions = $prosecutions1->whereRaw($parameters['conditions']); // Apply search conditions
    //     $prosecutions = $prosecutions1->select(
    //         'prose.id as id',
    //         'prose.subject',
    //         'prose.date AS prosecution_date',
    //         'prose.location',
    //         'prose.case_no',
    //         'prose.hints',
    //         'prose.is_suomotu',
    //         'prose.hasCriminal',
    //         'prose.prosecutor_name',
    //         'prose.is_approved',
    //         'prose.orderSheet_id as is_orderSheet',
    //     )
    //         ->orderBy('prose.id', 'desc');

    //     $data['prosecutions'] = $prosecutions;
    //     $data['searchCriteria'] = $searchCriteriaLabel ?? '';
    //     $data['paginate'] = $paginateType ?? "";
    //     $data['start_date'] = $startDate ?? "";
    //     $data['end_date'] = $endDate ?? "";
    //     $data['magistrate_id'] = $magistrateID;

    //     $limit = $request->limit;
    //     $data = $prosecutions->paginate($limit ?? 20);

    //     return $this->sendResponse($data, 'Case Information List With Procecution');

    // }

    public function showForms(Request $request)
    {
        $data = [];
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 20);

        if ($request->isMethod('post')) {
            $searchParams = $request->except('_token');
            session(['searchParams' => $searchParams]);
        }
        $caseNo = $request->case_no;
        $searchCriteria = $request->searchCriteria; // Default to an empty string if not provided
        $magistrateID = $request->magistrate_id; // Default to an empty string if not provided

        $parameters = session('searchParams', []);
        $cond = "is_approved = 1";
        if (!isset($parameters['conditions'])) {
            $parameters['conditions'] = $cond;
        } else {
            $parameters['conditions'] .= ' AND ' . $cond;
        }
        $parameters['order'] = "prose.id DESC";

        $user = Auth::user();
        $magistrate_id = $user->id;

        $searchCondition = 'prose.is_approved =1 AND  prose.delete_status = 1 AND prose.orderSheet_id IS NOT NULL';
        $startDate = (!empty($request->start_date) ? $request->start_date : "");
        $startDate = date('Y-m-d', strtotime($startDate));
        $endDate = (!empty($request->end_date) ? $request->end_date : "");
        if ($endDate == '') {
            $endDate = date('Y-m-d');
        } else {
            $endDate = date('Y-m-d', strtotime($endDate));
        }
        if ($endDate == '') {
            $endDate = date('Y-m-d H:i:s');
        }

        $prosecutions1 = DB::table('prosecutions AS prose')->join('courts AS court', 'court.id', '=', 'prose.court_id');

        // Apply search conditions
        if ($searchCriteria === 'is_case') {
            $prosecutions1->where('prose.case_no', $caseNo);
        } elseif ($searchCriteria === 'is_date') {
            $prosecutions1->whereBetween('prose.date', [$startDate, $endDate]);
        }

        // Filter by magistrate
        $prosecutions1->where('court.magistrate_id', $magistrate_id);
        $prosecutions1->whereRaw($searchCondition);

        $prosecutions = $prosecutions1->select(
            'prose.id as id',
            'prose.subject',
            'prose.date AS prosecution_date',
            'prose.location',
            'prose.case_no',
            'prose.hints',
            'prose.is_suomotu',
            'prose.hasCriminal',
            'prose.prosecutor_name',
            'prose.is_approved',
            'prose.orderSheet_id as is_orderSheet',
        )
            ->orderBy('prose.id', 'desc');

        // Paginate results with page number and limit
        $data['prosecutions'] = $prosecutions->paginate($limit, ['*'], 'page', $page);
        $data['searchCriteria'] = $searchCriteriaLabel ?? '';
        $data['paginate'] = $paginateType ?? "";
        $data['start_date'] = $startDate ?? "";
        $data['end_date'] = $endDate ?? "";
        $data['magistrate_id'] = $magistrateID;

        return $this->sendResponse($data, 'Case Information List With Prosecution');
    }

    public function incompletecaseWithoutCriminal(Request $request)
    {
        $userinfo = globalUserInfo();
        $page = $request->input('page', 1);
        // dd($page);
        // Fetch the magistrate information
        $magistrate = $userinfo->id;
        // Build the query using Eloquent
        if ($userinfo->role_id != 25) {
            $query = Prosecution::select([
                'prosecutions.id as id',
                'prosecutions.subject as subject',
                'prosecutions.date as date',
                'prosecutions.location as location',
                'prosecutions.case_no as case_no',
                'magistrate.name',
                'prosecutions.is_approved as is_approved',
                'seizurelist.id as is_seizurelist',
                'prosecutions.orderSheet_id as is_orderSheet',
            ])
                ->join('courts as court', 'court.id', '=', 'prosecutions.court_id')
                ->join('users as magistrate', 'magistrate.id', '=', 'court.magistrate_id')
                ->leftJoin('seizurelists as seizurelist', 'seizurelist.prosecution_id', '=', 'prosecutions.id')
                ->where('court.magistrate_id', $magistrate)
                ->where('prosecutions.is_suomotu', 1)
                ->where('prosecutions.hasCriminal', 0)
                ->whereNull('prosecutions.orderSheet_id')
                ->where('court.status', '!=', 2)
                ->where('prosecutions.delete_status', 1)
                ->groupBy('prosecutions.id')
                ->orderBy('prosecutions.id', 'DESC')
                ->orderBy('prosecutions.date', 'DESC');
        } else {

            // dd(1);
            // Build the query with necessary conditions and joins
            $query = Prosecution::select(
                'prosecutions.id as id',
                'prosecutions.subject as subject',
                'prosecutions.date as date',
                'prosecutions.location as location',
                'prosecutions.case_no as case_no',
                'prosecutions.prosecutor_name as name',
                'prosecutions.is_approved as is_approved',
                'seizurelist.id as is_seizurelist',
                'prosecutions.orderSheet_id as is_orderSheet',

            )
                ->join('courts as court', 'court.id', '=', 'prosecutions.court_id')
                ->leftJoin('seizurelists as seizurelist', 'seizurelist.prosecution_id', '=', 'prosecutions.id')
                // ->join('users as magistrate', 'magistrate.id', '=', 'court.magistrate_id')
                // ->leftJoin('seizurelists as szr', 'szr.prosecution_id', '=', 'prosecutions.id')
                ->where('prosecutions.prosecutor_id', $userinfo->id)
                ->where('prosecutions.is_suomotu', 0)
                ->where('prosecutions.hasCriminal', 1)
                ->where('prosecutions.is_approved', 0)
                ->where('prosecutions.case_status', '<', 6)
                ->orderBy('prosecutions.created_at', 'DESC');
        }

        $limit = $request->limit;
        // $data = $query->paginate($limit ?? 20);
        $data['prosecutions'] = $query->paginate($limit, ['*'], 'page', $page);
        return $this->sendResponse($data, 'Incomplete Case Without Criminal List');
    }

    public function searchProsecutionWithoutCriminal(Request $request)
    {
        $userinfo = globalUserInfo(); // gobal user
        $page = $request->input('page', 1);
        $magistrate = $userinfo->id; // Assuming you have a logged-in magistrate
        // Build the query using Eloquent ORM
        $prosecutions = Prosecution::select(
            'prosecutions.id as id',
            'prosecutions.subject as subject',
            'prosecutions.date as date',
            'prosecutions.location as location',
            'prosecutions.case_no as case_no',
            'courts.status as status',
            'courts.date as courtdate',
            'prosecutions.prosecutor_name as prosecutor_name'
        )
            ->join('courts', 'courts.id', '=', 'prosecutions.court_id')
            ->where('courts.magistrate_id', $magistrate)
            ->where('prosecutions.delete_status', '!=', 2)
            ->where('prosecutions.hasCriminal', 0)
            ->where('prosecutions.is_approved', 0)
            ->where('prosecutions.case_status', '>', 3)
            ->orderBy('prosecutions.id', 'DESC');

        $limit = $request->limit;
        // $data = $prosecutions->paginate($limit ?? 20);
        $data['prosecutions'] = $prosecutions->paginate($limit, ['*'], 'page', $page);
        return $this->sendResponse($data, 'Prosecution List Without Criminal');
    }

    public function getOwnDivisionWiseZilla()
    {
        try {
            $userinfo = globalUserInfo();
            $office_id = globalUserInfo()->office_id;
            $officeinfo = DB::table('office')->where('id', $office_id)->first();
            $division_id = $officeinfo->division_id;

            // dd($division_id);
            $data['district'] = DB::table('district')->select('id', 'district_name_en', 'district_name_bn')->where('division_id', $division_id)->get();
            return $this->sendResponse($data, 'Own division wise zilla list');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }
    }

    public function getOwnZillaWiseMagistrate()
    {
        try {

            $date = date('Y-m-d');
            $userinfo = globalUserInfo();
            $office_id = globalUserInfo()->office_id;
            $officeinfo = DB::table('office')->where('id', $office_id)->first();

            $division_id = $officeinfo->division_id;
            $district_id = request()->has('district_id') ? request()->input('district_id') : $officeinfo->district_id;

            $magistrateId = request()->input('magistrate_id');

            $data['userData'] = User::join('doptor_user_access_info', 'users.common_login_user_id', '=', 'doptor_user_access_info.common_login_user_id')
                ->join('office', 'office.id', '=', 'users.office_id')
                ->select('users.name', 'users.id as user_id', 'office.office_name_bn', DB::raw('CONCAT(users.name, " - ", office.office_name_bn) as name_eng'),)
                ->where('doptor_user_access_info.role_id', 26)
                ->where('office.district_id', $district_id)
                ->where('office.division_id', $division_id)
                ->get();

            $data['courtInfo'] = Court::where('magistrate_id', $magistrateId)
                ->where('date', $date)
                ->where('status', 1)
                ->select('id as court_id')
                ->get();

            return $this->sendResponse($data, 'Own division wise zilla list');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }
    }

    public function orderFormLawsBroken($prosecutionID)
    {
        try {
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

                    $tablesContent['lawsBrokenList'][] = $data;
                }
            } else {
                $tablesContent['lawsBrokenList'] = null;
            }
            return $this->sendResponse($tablesContent, 'Order form laws broken list');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }
    }

    public function getPreviousCrimeDetails(Request $request)
    {
        $criminalId = $request->query('criminal_id');
        $prosecutionId = $request->query('procecution_id');

        try {
            $crimeDetails = DB::table('prosecution_details as pd')
                ->join('prosecutions as p', 'p.id', '=', 'pd.prosecution_id')
                ->where('pd.criminal_id', $criminalId)
                ->whereNotNull('p.orderSheet_id')
                ->where('pd.prosecution_id', '!=', $prosecutionId)
                ->select('p.subject', 'p.id', 'p.date', 'p.case_no')
                ->get();

            return $this->sendResponse($crimeDetails, 'Previous Crime Details List');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }
    }

    public function saveOrderBylawRelease(Request $request)
    {
        try {
            $userinfo = globalUserInfo(); // gobal user
            $office_id = globalUserInfo()->office_id; // gobal user
            $officeinfo = DB::table('office')->where('id', $office_id)->first();
            $date = date('Y-m-d'); // today date

            $msgExistOrder = null;
            $data = $request->all();

            if (($data['laws_broken_ids']) === "[]" && ($data['criminal_ids']) === "[]") {
                return $this->sendErrormgs('', 'আসামি এবং আইন নির্বাচন করুন', 404);
            }
            if (($data['laws_broken_ids']) === "[]") {
                return $this->sendErrormgs('', 'আইন নির্বাচন করুন', 404);
            }
            if (($data['criminal_ids']) === "[]") {
                return $this->sendErrormgs('', 'আসামি নির্বাচন করুন', 404);
            }

            $logininfo = array(
                'id' => $userinfo->id,
                'magistrate-id' => $userinfo->id,
                'courtname' => $userinfo->name,
                'name' => $userinfo->name,
                'email' => $userinfo->email,
                'profile' => 'Magistrate', // P
                'divid' => $officeinfo->division_id,
                'zillaid' => $officeinfo->district_id,
                'upozilaid' => $officeinfo->upazila_id,
                'divname' => $officeinfo->div_name_bn,
                'zillaname' => $officeinfo->dis_name_bn,
                'upozillaname' => $officeinfo->dis_name_bn,
                'serviceid' => $officeinfo->upa_name_bn,
                'office' => $officeinfo->office_name_bn,
                'officeType' => $officeinfo->organization_type,
                'joblocation' => $officeinfo->dis_name_bn,
                'mobile' => $userinfo->mobile_no,
                'designation' => $userinfo->designation,
                'role_id' => $userinfo->role_id,
                // 'court_id'=> $court_id,
            );

            $punishment = ApiRepository::savePunishmentRelease($data, $logininfo);
            if ($punishment) {
                $msgExistOrder = 'অব্যাহতি প্রদান করা হল';
            } else {
                $msgExistOrder = 'আসামি কে ইতিমধ্যে অব্যাহতি প্রদান করা হয়েছে। ';
            }

            return $this->sendResponse($punishment, $msgExistOrder);
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }

        return $response;
    }

    public function saveOrderByRegularCase(Request $request)
    {
        try {
            $userinfo = globalUserInfo(); // gobal user
            $office_id = globalUserInfo()->office_id; // gobal user
            $officeinfo = DB::table('office')->where('id', $office_id)->first();
            $date = date('Y-m-d'); // today date

            $msgExistOrder = null;
            $data = $request->all();

            if (($data['laws_broken_ids']) === "[]" && ($data['criminal_ids']) === "[]") {
                return $this->sendErrormgs('', 'আসামি এবং আইন নির্বাচন করুন', 404);
            }
            if (($data['laws_broken_ids']) === "[]") {
                return $this->sendErrormgs('', 'আইন নির্বাচন করুন', 404);
            }
            if (($data['criminal_ids']) === "[]") {
                return $this->sendErrormgs('', 'আসামি নির্বাচন করুন', 404);
            }

            $lawsBrokenIds = json_decode($data['laws_broken_ids'], true);
            $criminalIds = json_decode($data['criminal_ids'], true);

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
                        $msgExistOrder = 'আসামি কে ইতিমধ্যে এই আইনে আদেশ প্রদান করা হয়েছে।';
                        return $this->sendResponse([], $msgExistOrder);
                    }
                }
            }

            $logininfo = array(
                'id' => $userinfo->id,
                'magistrate-id' => $userinfo->id,
                'courtname' => $userinfo->name,
                'name' => $userinfo->name,
                'email' => $userinfo->email,
                'profile' => 'Magistrate', // P
                'divid' => $officeinfo->division_id,
                'zillaid' => $officeinfo->district_id,
                'upozilaid' => $officeinfo->upazila_id,
                'divname' => $officeinfo->div_name_bn,
                'zillaname' => $officeinfo->dis_name_bn,
                'upozillaname' => $officeinfo->dis_name_bn,
                'serviceid' => $officeinfo->upa_name_bn,
                'office' => $officeinfo->office_name_bn,
                'officeType' => $officeinfo->organization_type,
                'joblocation' => $officeinfo->dis_name_bn,
                'mobile' => $userinfo->mobile_no,
                'designation' => $userinfo->designation,
                'role_id' => $userinfo->role_id,
                // 'court_id'=> $court_id,
            );
            // $orderDetail = $punishment[0]->order_detail
            $punishment = ApiRepository::savePunishmentRelease($data, $logininfo);

            if ($punishment) {
                $msgExistOrder = $punishment[0]->order_detail;
            } else {
                $msgExistOrder = 'আসামি কে ইতিমধ্যে এই আইনে আদেশ প্রদান করা হয়েছে।';
            }

            return $this->sendResponse($punishment, $msgExistOrder);
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }

        // return $response;
    }

    public function saveOrderByPunishments(Request $request)
    {
        try {
            $userinfo = globalUserInfo(); // gobal user
            $office_id = globalUserInfo()->office_id; // gobal user
            $officeinfo = DB::table('office')->where('id', $office_id)->first();
            $date = date('Y-m-d'); // today date

            $msgExistOrder = null;
            $data = $request->all();
            //    dd($data);
            if (($data['laws_broken_ids']) === "[]" && ($data['criminal_ids']) === "[]") {
                return $this->sendErrormgs('', 'আসামি এবং আইন নির্বাচন করুন', 404);
            }
            if (($data['laws_broken_ids']) === "[]") {
                return $this->sendErrormgs('', 'আইন নির্বাচন করুন', 404);
            }
            if (($data['criminal_ids']) === "[]") {
                return $this->sendErrormgs('', 'আসামি নির্বাচন করুন', 404);
            }

            $lawsBrokenIds = json_decode($data['laws_broken_ids'], true);
            $criminalIds = json_decode($data['criminal_ids'], true);

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
                        $msgExistOrder = 'আসামি কে ইতিমধ্যে এই আইনে আদেশ প্রদান করা হয়েছে।';
                        return $this->sendResponse([], $msgExistOrder);
                    }
                }
            }

            $logininfo = array(
                'id' => $userinfo->id,
                'magistrate-id' => $userinfo->id,
                'courtname' => $userinfo->name,
                'name' => $userinfo->name,
                'email' => $userinfo->email,
                'profile' => 'Magistrate', // P
                'divid' => $officeinfo->division_id,
                'zillaid' => $officeinfo->district_id,
                'upozilaid' => $officeinfo->upazila_id,
                'divname' => $officeinfo->div_name_bn,
                'zillaname' => $officeinfo->dis_name_bn,
                'upozillaname' => $officeinfo->dis_name_bn,
                'serviceid' => $officeinfo->upa_name_bn,
                'office' => $officeinfo->office_name_bn,
                'officeType' => $officeinfo->organization_type,
                'joblocation' => $officeinfo->dis_name_bn,
                'mobile' => $userinfo->mobile_no,
                'designation' => $userinfo->designation,
                'role_id' => $userinfo->role_id,
                // 'court_id'=> $court_id,
            );
            // $orderDetail = $punishment[0]->order_detail
            $punishment = ApiRepository::savePunishment($data, $logininfo);

            if ($punishment) {

                $msgExistOrder = $punishment[0]->order_detail;
            } else {
                $msgExistOrder = 'আসামি কে ইতিমধ্যে এই আইনে আদেশ প্রদান করা হয়েছে।';
            }

            return $this->sendResponse($punishment, $msgExistOrder);
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }

        return $response;
    }

    public function deleteOrder(Request $request)
    {
        try {
            $prosecutionId = $request->query('prosecution_id');
            $orderId = $request->query('order_id');

            $result = PunishmentRepository::deletePunishmentByOrderId($prosecutionId, $orderId);

            if ($result === 'success') {
                return $this->sendResponse($result, 'Order List Deleted Successfully');
            } else {

                return $this->sendError($result, []);
            }
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), []);
        }
    }

    public function getOrderListByProcecutionId(Request $request)
    {

        $prosecutionId = $request->query('prosecution_id');
        try {
            $punishmentList = PunishmentRepository::getOrderListByProsecutionId($prosecutionId);
            return $this->sendResponse($punishmentList, 'Order List Shown Succesfully');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), []);
        }
    }

    public function jailList()
    {
        $data['jailList'] = DB::table('mc_jail')->get();
        return $this->sendResponse($data, 'Jail List Shown Succesfully');
    }

    // public function saveJimmaderInformation(Request $request)
    // {
    //     $data = $request->all();
    //     $prosecutionId = $data['prosecution_id'];
    //     $orderIds = json_decode($data['order_ids'], true);
    //     $jimmaderName = $data['jimmaderName'];
    //     $jimmaderDesignation = $data['jimmaderDesignation'];
    //     $jimmaderLocation = $data['jimmaderLocation'];
    //     $seizureOrder = $data['seizure_order'];

    //     if($jimmaderName==null || $jimmaderDesignation==null || $jimmaderLocation==null){
    //         return $this->sendError('Jimmader Information was not properly given', []);
    //     }

    //     foreach ($orderIds as $orderId) {
    //         $conditions = [
    //             'id' => $orderId,
    //             'prosecution_id' => $prosecutionId,
    //         ];

    //         $orderTextData = OrderText::where($conditions)->first();

    //         if ($orderTextData) {
    //             continue;
    //         }
    //         else{
    //             return $this->sendError('Order was not given yet', []);
    //         }
    //     }

    //     $flag = false;

    //     $prosecution = Prosecution::find($prosecutionId);

    //     if ($prosecution) {
    //         $prosecution->jimmader_name = $jimmaderName;
    //         $prosecution->jimmader_designation = $jimmaderDesignation;
    //         $prosecution->jimmader_address = $jimmaderLocation;
    //         $prosecution->dispose_detail = $seizureOrder;
    //         $prosecution->is_sizeddispose = 1;

    //         if ($prosecution->save()) {
    //             $flag = true;
    //         } else {
    //             return $this->sendError('Failed to save Jimmader info details.', []);
    //         }
    //     } else {
    //         return $this->sendError('Prosecution record not found.', []);
    //     }

    //     if ($flag) {
    //         $prosecutionInfo = CaseRepository::getCaseInformationByProsecutionId($prosecutionId);
    //         return $this->sendResponse($prosecutionInfo, 'Full Prosecution Preview Information Shown Successfully');
    //     }

    // }

    public function saveJimmaderInformation(Request $request)
    {
        $data = $request->only(['prosecution_id', 'order_ids', 'jimmaderName', 'jimmaderDesignation', 'jimmaderLocation', 'seizure_order']);

        if (empty($data['jimmaderName']) || empty($data['jimmaderDesignation']) || empty($data['jimmaderLocation'])) {
            return $this->sendError('Jimmader Information was not properly given', []);
        }

        $prosecutionId = $data['prosecution_id'];
        $orderIds = json_decode($data['order_ids'], true);

        $missingOrder = OrderText::where('prosecution_id', $prosecutionId)
            ->whereIn('id', $orderIds)
            ->doesntExist();

        if ($missingOrder) {
            return $this->sendError('Order was not given yet', []);
        }

        $prosecution = Prosecution::find($prosecutionId);

        if (!$prosecution) {
            return $this->sendError('Prosecution record not found.', []);
        }

        $prosecution->update([
            'jimmader_name' => $data['jimmaderName'],
            'jimmader_designation' => $data['jimmaderDesignation'],
            'jimmader_address' => $data['jimmaderLocation'],
            'dispose_detail' => $data['seizure_order'],
            'is_sizeddispose' => 1,
        ]);

        $prosecutionInfo = CaseRepository::getCaseInformationByProsecutionId($prosecutionId);
        return $this->sendResponse($prosecutionInfo, 'Full Prosecution Preview Information Shown Successfully');
    }

    public function saveOrderSheetInfo(Request $request)
    {
        $prosecutionId = $request->query('prosecution_id');
        // $prosecutionId = $request->procecution_id;
        $userinfo = globalUserInfo();
        $office_id = $userinfo->office_id;
        $officeinfo = DB::table('office')->where('id', $office_id)->first();

        $districtName = $officeinfo->dis_name_bn;
        $districtId = $officeinfo->district_id;
        $divisionId = $officeinfo->division_id;

        $magistrates = DB::table('users as mag')
            ->join('doptor_user_access_info as dp', 'dp.user_id', '=', 'mag.id')
            ->join('mc_role as r', 'r.id', '=', 'dp.role_id')
            ->join('office as job_des', 'job_des.id', '=', 'mag.office_id')
            ->select(
                'mag.id as id',
                DB::raw('CONCAT(mag.name,",",r.role_name,",",job_des.office_name_bn) as name_eng'),
                'mag.mobile_no as mobile',
                'mag.email as email'
            )
            ->where('job_des.district_id', $districtId)
            ->where('job_des.division_id', $divisionId)
            ->where('dp.role_id', 26)
            ->where('dp.user_id', $userinfo->id)
            ->first();

        $caseNo = $request->case_no ?? '';
        $procecutionDate = $request->procecution_date ?? '';

        $prosecutionInfo = CaseRepository::getCaseInformationByProsecutionId($prosecutionId);

        $procecutionDate = $this->convertToBanglaDate($prosecutionInfo['prosecution']->date);
        $procecutionZilla = $prosecutionInfo['prosecutionLocationName']['zillaName'];
        $procecutionUnderZillaLocation = $prosecutionInfo['prosecutionLocationName']['underZillaLocation'];
        $procecutionLocation = $prosecutionInfo['prosecution']->location;
        $prosecutionTimeInBangla = $prosecutionInfo['prosecutionTimeInBangla'];
        $disposeDetail = $prosecutionInfo['prosecution']->dispose_detail;
        $lawsBrokenForCriminal = '';
        $lawsBrokenForCriminal = $this->lawsBrokenForCrimeTextGeneration($prosecutionInfo['lawsBrokenList']);

        $seizureListTextGeneration = $this->seizureListTextGeneration($prosecutionInfo['seizurelist']);

        if ($prosecutionInfo['prosecution']->hasCriminal == 1) {
            $criminalAllbio = $this->criminalFullBioStringGeneration($prosecutionInfo['criminalDetails']);
            $criminalList = $this->numberOfcriminalText($prosecutionInfo['criminalDetails']);

            $punishmentInfo = $this->getPunishmentInfo($prosecutionId);

            $orderList = PunishmentRepository::getOrderListByProsecutionId($prosecutionId);
            $prosecutorText = '';
            $criminalDetailName = [];
            $criminalDetails = $prosecutionInfo['criminalDetails'];

            $criminalDetailName = '';
            foreach ($criminalDetails as $key => $criminalDetail) {
                if ($key > 0) {
                    $criminalDetailName .= ', ';
                }
                $criminalDetailName .= $criminalDetail->name_eng;
            }
        }

        $header = '';
        $header = "<p class='form-bd' style='text-align: left;'>বাংলাদেশ ফরম নম্বর - ৩৮৯০ <br> সুপ্রীম কোর্ট (হাইকোর্ট বিভাগ) ক্রিমিনাল ফরম নম্বর (এম) ১০৬</p><h2 style='text-align: center;'>এক্সিকিউটিভ ম্যাজিস্ট্রেটের রেকর্ডের জন্য আদেশনামা</h2><p style='text-align: center;'>জনাব <span>{$magistrates->name_eng}, &nbsp;{$districtName}-এর আদালত।<br>বিবিধ মামলা নম্বর-&nbsp;<span>{$caseNo}</span>&nbsp;তারিখ:&nbsp;<span>{$procecutionDate}</span>।&nbsp;</p><div id='dependent' style='
    text-align: center;' ><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; রাষ্ট্র &nbsp; বনাম</span><span style='margin-right: 40px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>" . ($criminalDetailName ?? "") . "</span></span></div>";
        $orderSheetFirstPara = '';
        if ($prosecutionInfo['prosecution']->hasCriminal == 1) {
            $orderSheetFirstPara = "<div><span contenteditable='false' class='noneditable'>আজ {$procecutionDate} তারিখ {$procecutionZilla} জেলার {$procecutionUnderZillaLocation} এর {$procecutionLocation} স্থানে মোবাইল কোর্ট পরিচালনাকালে {$prosecutionTimeInBangla} ঘটিকায় {$criminalAllbio}</span><span contenteditable='true' class='editable'>কর্তৃক অপরাধ সংঘটনের সময় হাতে নাতে ধৃত হন ।</span>{$prosecutorText}</div><div class='para'><span contenteditable='true' class='editable'> ঘটনার সংক্ষিপ্ত বিবরণ এই যে, </span><span contenteditable='false' class='noneditable'>{$criminalDetailName} {$procecutionLocation} স্থানে</span></div>";

            $complaintTextBlock = $this->complaintTextGeneration($prosecutionInfo, $criminalList);
            $confessionTextGeneration = '';
            $confessionTextGeneration = $this->confessionTextGeneration($punishmentInfo);
            $orderTextBlock = '';
            $orderTextBlock = $this->orderTextGeneration($orderList, $procecutionDate, $criminalList);

            $seizureOrderContextText = '';
            $seizureListText = '';
            $finePaymentOrder = '';
            if ($prosecutionInfo['seizurelist']) {
                $seizureListText = '<div class="para" >' . $confessionTextGeneration .
                    "</div>";
                $seizureOrderContextText =
                    '<div class="para" ><span contenteditable="false" class="noneditable">' . $disposeDetail . "</span></div>";
            }

            if ($orderList[0]->receiptNo) {
                $finePaymentOrder =
                    '<div class="para" ><span contenteditable="true" class="editable">  আদায়কৃত অর্থ পরবর্তী কার্যদিবসের মধ্যে সরকারি কোষাগারে চালানের মাধ্যমে জমা প্রদানের জন্য বেঞ্চ সহকারীকে বলা হলো ।</span></div>';
            }

            $tableBodyContent = $orderSheetFirstPara . $lawsBrokenForCriminal . $complaintTextBlock . $confessionTextGeneration . $orderTextBlock . $seizureListText . $seizureOrderContextText . $finePaymentOrder;

            //  return $tableBodyContent;

        } else {
            $tableBody = "";
            $seizureOrderContextText = "";
            $seizureListText = "";
            $tableBodyContent = "";
            $prosecutorText = "";

            if (count($prosecutionInfo['prosecutorInfo']) > 0) {
                $prosecutor = $prosecutionInfo['prosecutorInfo'][0];
                $prosecutorText = '<div class="para">' . '<span contenteditable="false" class="noneditable">জনাব&nbsp;' . $prosecutor['name_eng'] . ', ' . $prosecutor['designation_bng'] . ', ' . $prosecutor['zillaname'] . '</span>' . '<span contenteditable="true" class="editable"> লিখিতভাবে অভিযোগ দায়ের করেন।</span>' . '</div>';
            }

            if (!empty($prosecutionInfo['seizurelist'])) {
                $seizureListText = '<div class="para">' . $seizureListTextGeneration . '<span contenteditable="true" class="editable">, দুই&nbsp;(০২) জন সাক্ষীর উপস্থিতিতে জব্দ করে জব্দনামা তৈরি করে তাতে সাক্ষীদ্বয়ের স্বাক্ষর গ্রহণ করি।</span>' . '</div>';

                $seizureOrderContextText = '<div class="para">' . '<span contenteditable="false" class="noneditable">' . $disposeDetail . '</span>' . '</div>';
            }

            $tableBodyContent = '<div>' . '<span contenteditable="false" class="noneditable">' . 'আজ ' . $procecutionDate . ' তারিখ ' . $procecutionZilla . ' জেলার ' .
                $procecutionUnderZillaLocation . ' এর ' . $procecutionLocation . ' স্থানে মোবাইল কোর্ট পরিচালনাকালে ' . $prosecutionTimeInBangla . ' ঘটিকায় ' . '</span>' .
                '<span contenteditable="true" class="editable">অপরাধ সংগঠিত হয় ।</span>' . '</div>' .
                $prosecutorText . '<div class="para">' . '<span contenteditable="true" class="editable">ঘটনার সংক্ষিপ্ত বিবরণ এই যে,</span>' . '<span contenteditable="false" class="noneditable">' . $procecutionLocation . ' স্থানে ' . '</span>' . '<span contenteditable="true" class="editable">' . $lawsBrokenForCriminal . '</span>' . '<span contenteditable="true" class="editable"> কোন ব্যক্তি আটক নয়, আসামি পলাতক। তাই অভিযোগ গঠন করা গেল না।</span>' . '</div>' . $seizureListText .
                $seizureOrderContextText;
        }

        $tableBody = '<table cellspacing="0" cellpadding="0" border="1" width="100%"><tbody><tr><td valign="middle" width="5%" align="center"> আদেশের ক্রম</td><td valign="middle" width="10%" align="center"> তারিখ</td><td valign="middle" width="75%" align="center"> আদেশ</td><td valign="middle" width="10%" align="center"> স্বাক্ষর</td></tr><tr><td valign="top" align="center"><span class="underline"> ১</span><br></td><td valign="top" align="center">' . $procecutionDate . '</td><td style="padding:5px; text-align : justify;">' . $tableBodyContent . '<table contenteditable="false" border="0" width="100%" align="center"><tbody><tr><td width="30%">(সিলমোহর)</td><td width="70%" align="center"><span>&nbsp;' . $procecutionDate . '</span></td></tr><tr><td width="30%"></td><td width="50%" align="center"><span> </span></td></tr><tr><td width="30%"></td><td width="70%" align="center"><span>' . $magistrates->name_eng . '</span></td></tr><tr><td width="30%"></td><td width="70%" align="center"><span>' . $districtName . ',</span></td></tr></tbody></table></td><td></td></tr></tbody></table>';

        // Save the order sheet information
        $orderSheet = new OrderSheet();
        $orderSheet->prosecution_id = $prosecutionId;
        $orderSheet->order_date = date('Y-m-d H:i:s');
        $orderSheet->order_header = $header;
        $orderSheet->order_details = $tableBody;
        $orderSheet->receipt_no = null;
        $orderSheet->version = 1;
        $orderSheet->delete_status = 1;
        $orderSheet->created_by = 'admin@gmail.com';
        $orderSheet->created_at = date('Y-m-d H:i:s');

        // Handle save failure and return flag
        if (!$orderSheet->save()) {
            $errorMessage = "";
            foreach ($orderSheet->getMessages() as $message) {
                $errorMessage .= $message;
            }
        } else {
            $flag = ProsecutionRepository::updateProsecutionOrderSheetId($prosecutionId, $orderSheet->id);
        }

        return $this->sendResponse($orderSheet, 'Order Sheet Saved Successfully');
    }

    public function seizureTextGenerationForOrderSheet($prosecutionInfo, $criminalList)
    {

        $seizureListTextGeneration = $this->seizureListTextGeneration($prosecutionInfo['seizurelist']);

        $text =
            '<span contenteditable="true" class="editable">' . $criminalList['accusedTextBng'] .
            ' হেফাজত থেকে </span><span contenteditable="false" class="noneditable">' . $seizureListTextGeneration . '</span><span contenteditable="true" class="editable">,দুই&nbsp; (০২)  জন সাক্ষীর উপস্থিতিতে জব্দ করে জব্দনামা তৈরি করে তাতে সাক্ষীদ্বয়ের স্বাক্ষর গ্রহণ করি এবং ' . $criminalList['criminalTextBng'] . "- কে  স্বাক্ষর দিতে বললে জব্দনামায় " . $criminalList['$personP_r2'] .
            " স্বাক্ষর প্রদান করেন ।</span>";

        return $text;
    }

    public function seizureListTextGeneration($seizureList)
    {
        $text = "";
        $banglaLetter = ["ক)", " খ)", " গ)", " ঘ)", " ঙ)", " চ)"];

        foreach ($seizureList as $index => $seizure) {
            if (isset($banglaLetter[$index])) {
                $text .= $banglaLetter[$index] . ' ' . $seizure->stuff_description;
            }
        }

        return $text;
    }

    public function orderTextGeneration($orderList, $date, $criminalList)
    {
        $text = "";
        $additionalText = "";
        $finePaymentText = "";
        $punishmentJailText = "";
        $repWarrentText = "";

        foreach ($orderList as $p) {
            $accuse = " অভিযুক্তের";
            $accusePerson = "অভিযুক্ত ব্যক্তিকে";
            $pronoun = "তার";
            $criminal = "আসামি";
            $fineAndJailText = "";
            $warrentDurationText = "";
            $fineText = "";
            $orderTemplate = [];
            if (strpos($p->CriminalName, ",") !== false) {
                $accuse = "অভিযুক্তদের";
                $accusePerson = "অভিযুক্ত ব্যক্তিদের";
                $pronoun = " তাদের";
                $criminal = " আসামিগন ";
            }

            if ($p->orderType == "PUNISHMENT") {
                if ($p->rep_warrent_duration) {
                    $repWarrentText =
                        '<span contenteditable="false" class="noneditable"> অনাদায়ে ' .
                        $p->rep_warrent_duration .
                        '</span><span contenteditable="true" class="editable"> প্রদান করা হলো। </span>';
                } else {
                    $repWarrentText =
                        '<span contenteditable="true" class="editable">প্রদান করা হলো।</span>';
                }

                if ($p->receiptNo) {
                    $finePaymentText =
                        '<span contenteditable="true" class="editable">' . $criminal .
                        ' অর্থদণ্ড নগদ পরিশোধ করেন, যার রশিদ নম্বর- </span><span contenteditable="false" class="noneditable">' .
                        $p->receiptNo .
                        " ,তারিখ- " . $date . "।</span>";
                    $orderTemplate['reciptNo'] = $p->receiptNo;
                }

                if ($p->punishmentJailID) {
                    $punishmentJailText =
                        '<span contenteditable="true" class="editable"> সাজা পরোয়ানামূলে আসামি </span><span contenteditable="false" class="noneditable">' .
                        $p->CriminalName .
                        " কে " .
                        $p->punishmentJailName .
                        '</span><span contenteditable="true" class="editable">-এ প্রেরণ করা হোক। </span>';
                }

                $fineAndJailText = $p->DistinctOrder;

                $additionalText =
                    '<span contenteditable="true" class="editable"> ' .
                    $criminalList['personP'] .
                    ' মোবাইল কোর্ট আইন, ২০০৯-এর ৭(২) ধারার বিধানমতে, </span><span contenteditable="false" class="noneditable">' .
                    $p->lawAndSectionPunishment .
                    " " . $fineAndJailText .
                    "</span>" . $repWarrentText . $finePaymentText . $punishmentJailText;
            } elseif ($p->orderType == "REGULARCASE") {
                $additionalText =
                    '<span contenteditable="true" class="editable">' .
                    $pronoun .
                    " ও উপস্থিত সাক্ষীগনের বক্তব্য পর্যালোচনা করা হল। বর্ণিতাবস্থায় , মোবাইল কোর্ট আইন , ২০০৯- এর ৭(৪) ধারার বিধানমতে " .
                    $accusePerson .
                    '</span> <span contenteditable="false" class="noneditable">' .
                    $p->lawAndSectionDharaHote .
                    $p->DistinctOrder .
                    "</span>";
            } else {
                $additionalText =
                    '<span contenteditable="true" class="editable">' .
                    $pronoun .
                    " ও উপস্থিত সাক্ষীগনের বক্তব্য পর্যালোচনা করা হল। পর্যালোচনায় দেখা যায় যে , " .
                    $accuse .
                    " বক্তব্যে সত্যতা রয়েছে । " .
                    $accuse .
                    " বিরুদ্ধে গঠিত অভিযোগ অস্বীকার করায় ও ব্যখ্যা সন্তোষজনক হওয়ায় মোবাইল কোর্ট আইন ,২০০৯-এর ৭(৩) ধারার বিধানমতে " .
                    $accusePerson .
                    ' </span><span contenteditable="false" class="noneditable"> ' .
                    $p->lawAndSectionDharaHote .
                    $p->DistinctOrder .
                    "।</span>";
            }

            $text .=
                '<div class=\'para\'> <span contenteditable="false" class="noneditable">' .
                $p->CriminalName .
                "- এর বিরুদ্ধে আনীত " .
                $p->lawAndSectionConfession .
                ",  </span>" . $additionalText .
                "</div>";
        }

        return $text;
    }

    public function confessionTextGeneration($punishmentInfo)
    {

        $text = '';
        foreach ($punishmentInfo as $confession) {
            $text .= '<div class="para"><span contenteditable="false" class="noneditable">' .
                $confession->name_bng .
                '- এর বিরুদ্ধে আনীত ' .
                $confession->lawWiseConfessionText .
                ' ।তিনি বলেন যে, ' .
                $confession->description .
                '</span></div>';
        }

        return $text;
    }

    public static function getPunishmentInfo($prosecution_id)
    {
        $punishmentInfo = DB::table('punishments as p')
            ->select(
                'c.name_bng',
                DB::raw('GROUP_CONCAT(s.sec_title, \' এর \', s.sec_number, \' ধারার \',
                CASE
                    WHEN ccl.Confessed = 1 THEN "অভিযোগ স্বীকার করেন"
                    WHEN ccl.Confessed = 0 THEN "অভিযোগ অস্বীকার করেন"
                END
            ) as lawWiseConfessionText'),
                'cc.description'
            )
            ->join('criminals as c', 'c.id', '=', 'p.criminal_id')
            ->join('criminal_confessions as cc', function ($join) use ($prosecution_id) {
                $join->on('cc.prosecution_id', '=', 'p.prosecution_id')
                    ->on('cc.criminal_id', '=', 'p.criminal_id');
            })
            ->join('laws_brokens as lb', 'lb.id', '=', 'p.laws_broken_id')
            ->join('criminal_confession_lawsbrokens as ccl', function ($join) {
                $join->on('ccl.LawsBrokenID', '=', 'lb.id')
                    ->on('ccl.CriminalConfessionID', '=', 'cc.id');
            })
            ->join('mc_law as l', 'l.id', '=', 'lb.Law_id')
            ->join('mc_section as s', function ($join) {
                $join->on('s.id', '=', 'lb.section_id')
                    ->on('s.law_id', '=', 'l.id');
            })
            ->where('p.prosecution_id', $prosecution_id)
            ->groupBy('c.id')
            ->get();

        return $punishmentInfo;
    }

    public function complaintTextGeneration($prosecutionInfo, $criminalList)
    {
        $occurrenceTypeText = $prosecutionInfo['prosecution']->occurrence_type_text;
        $criminalList = $criminalList;
        $criminalDetails = $prosecutionInfo['criminalDetails'];

        $criminalDetailName = '';
        foreach ($criminalDetails as $key => $criminalDetail) {
            if ($key > 0) {
                $criminalDetailName .= ', ';
            }
            $criminalDetailName .= $criminalDetail->name_eng;
        }

        $text = '';
        $text =
            '<div class="para"><span contenteditable="true" class="editable">উক্ত অপরাধ আমার</span><span contenteditable="false" class="noneditable"> ' . $occurrenceTypeText . '</span><span contenteditable="true" class="editable"> হওয়ায় ঘটনাস্থল থেকে ' . $criminalList['criminalTextBng'] . ' কে তৎক্ষণাৎ আটক করি,&nbsp;মোবাইল কোর্ট আইন, ২০০৯–এর ৬(১) ধারা মোতাবেক অপরাধ আমলে গ্রহণ করে ৭(১) ধারার বিধানমতে&nbsp;' . $criminalList['criminalTextBng'] . ' ' . $criminalDetailName . ' এর বিরুদ্ধে &nbsp;অভিযোগ গঠন করি । উক্ত অভিযোগ ' . $criminalList['personP'] . ' পড়ে ও ব্যাখ্যা করে শোনানো হলো এবং ' . $criminalList['$personP_r'] . ' দোষ স্বীকার করেন কিনা জিজ্ঞাসা করা হলে ' . $criminalList['criminalTextBng'] . '-</span></div>';

        return $text;
    }

    public function numberOfcriminalText($criminalList)
    {
        if (count($criminalList) > 1) {
            $orderTemplate = [
                'criminalTextBng' => 'আসামিগণ',
                'accusedTextBng' => 'অভিযুক্তগণের',
                'personP' => 'তাদের',
                '$personP_r' => 'তারা',
                '$personP_r2' => 'তারাও',
            ];
        } else {
            $orderTemplate = [
                'criminalTextBng' => 'আসামি',
                'accusedTextBng' => 'অভিযুক্তের',
                'personP' => 'তাকে',
                '$personP_r' => 'তার',
                '$personP_r2' => 'তিনিও',
            ];
        }

        return $orderTemplate;
    }

    public function lawsBrokenForCrimeTextGeneration($lawsBrokenList)
    {
        $text = '';

        foreach ($lawsBrokenList as $law) {
            $text .= '</span><span contenteditable="true" class="editable">' .
                $law['Description'] .
                '</span><span contenteditable="false" class="noneditable"> যা ' .
                $law['sec_title'] . '-এর ' .
                $law['sec_number'] .
                ' ধারার লঙ্ঘন ও ' .
                $law['punishment_sec_number'] .
                ' ধারায় দণ্ডনীয় অপরাধ ।</span>';
        }

        $text .= "</div>";

        return $text;
    }

    public function criminalFullBioStringGeneration($criminalInfo)
    {
        $text = '';
        foreach ($criminalInfo as $criminal) {
            $text .= $criminal->name_bng . " , " .
                $criminal->custodian_type . ": " .
                $criminal->custodian_name . " , বয়স: " .
                $criminal->age . ", ঠিকানা: " .
                $criminal->present_address . ",";
        }

        return rtrim($text, ',');
    }

    public function convertToBanglaDate($date)
    {
        $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $banglaDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

        $formattedDate = \Carbon\Carbon::parse($date)->format('d-m-Y');

        $banglaFormattedDate = str_replace($englishDigits, $banglaDigits, $formattedDate);

        return $banglaFormattedDate;
    }

    public function myProfile(Request $request)
    {
        $user_id = Auth::user()->id;

        $userManagement = DB::table('users')
            ->join('doptor_user_access_info', 'users.common_login_user_id', '=', 'doptor_user_access_info.common_login_user_id')
            ->leftjoin('mc_role', 'doptor_user_access_info.role_id', '=', 'mc_role.id')
            ->leftjoin('office', 'users.office_id', '=', 'office.id')
            ->leftJoin('district', 'office.district_id', '=', 'district.id')
            ->leftJoin('upazila', 'office.upazila_id', '=', 'upazila.id')
            ->select(
                'users.id',
                'users.name',
                'users.username',
                'users.mobile_no',
                'users.email',
                'users.profile_pic',
                'users.signature',
                'mc_role.role_name',
                'office.office_name_bn',
                'district.district_name_bn',
                'upazila.upazila_name_bn'
            )
            ->where('users.id', $user_id)
            ->first();

        $page_title = "মাই প্রোফাইল";
        if (globalUserInfo()->is_cdap_user == 1) {
            $userManagement = DB::table('cdap_users')
                ->where('id', globalUserInfo()->cdap_user_id)
                ->first();
            $profile_pic = globalUserInfo()->profile_pic;
            return $this->sendResponse($userManagement, 'Profile Information Shown Successfully');
        }

        return $this->sendResponse($userManagement, 'Profile Information Shown Successfully');
    }

    // Dashboard Api
    public function dashboardInformation()
    {
        $roleID = globalUserInfo()->role_id;

        if ($roleID == 25) {
            $prosecutorId = auth()->user()->id;
            $condition = '';
            $data = [];

            $total_case_number = DashboardRepository::getProsecutorTotalCaseCount($prosecutorId, $condition);
            $incomplete_case_number = DashboardRepository::getprosecutorIncompleteCaseCount($prosecutorId, $condition);
            $complete_case_number =  $total_case_number - $incomplete_case_number;
            $executed_court = DashboardRepository::getprosecutorTotalExecutedCourt($prosecutorId);

            $criminalAndFine = DashboardRepository::getProsecutorTotalCriminalAndFine($prosecutorId);
            $fine = 0;
            foreach ($criminalAndFine as $it) {
                $fine += $it->fine;
            }

            $criminal = DashboardRepository::getProsecutorCriminalList($prosecutorId);
            $index = $criminal ?? 0;
            $totalCitizenComplain = DashboardRepository::getProsecutorTotalCitizenComplain($prosecutorId);

            $result_case_processing = DashboardRepository::getProsecutorListOfIncompleteCitizenComplain($prosecutorId);

            $totalCompleteCaseCount = ($totalCitizenComplain) - ($result_case_processing);

            // Grouping into firstLayer
            $largeCards = [
                'total_case_number' => $total_case_number,
                'criminal_no_mgt' => $index,
                'incomplete_case_number' => $incomplete_case_number,
                'complete_case_number' => $complete_case_number,
            ];

            // Grouping into statistics
            $statistics = [
                'executed_court' => $executed_court,
                'total_case_number' => $total_case_number,
                'fine_collection' => $fine . " টাকা",
                'criminal_no_mgt' => $index . " জন",
            ];

            // Grouping into crimeInformation
            $crimeInfo = [
                'totalCitizenComplain' => $totalCitizenComplain,
                'citizen_case_complete' => $totalCompleteCaseCount,
                'result_case_processing' => $result_case_processing,
            ];

            $data['firstLayer'] = $largeCards;
            $data['statistics'] = $statistics;
            $data['crimeInfo'] = $crimeInfo;

            return $this->sendResponse($data, 'Dashboard Information Shown Successfully');
        } else if ($roleID == 26) {
            $data = [];
            $magistrateId = auth()->user()->id;
            $condition = '';
            $fine = 0;
            // Fetch the data
            $total_case_number = DashboardRepository::getTotalCaseCount($magistrateId, $condition);
            $allSelfCases = DashboardRepository::getListOfallSelfCases($magistrateId);
            $incomplete_case_number = DashboardRepository::getIncompleteCaseCount($magistrateId, $condition);
            $complete_case_number = $total_case_number - $incomplete_case_number;
            $executed_court = DashboardRepository::getTotalExecutedCourt($magistrateId, $condition);
            // citizen complanev
            $totalCitizenComplain = DashboardRepository::getTotalCitizenComplain($magistrateId, $condition);

            $result_case_processing = DashboardRepository::getListOfIncompleteCitizenComplain($magistrateId, $condition);

            $criminalAndFine = DashboardRepository::getTotalCriminalAndFine($magistrateId);
            foreach ($criminalAndFine as $it) {
                $fine += $it->fine;
            }
            $citz_case_complete = ((int) $totalCitizenComplain ?? 0) - ((int) $result_case_processing ?? 0);
            // Get criminal list
            $criminal = DashboardRepository::getCriminalList($magistrateId);
            $index = $criminal ?? 0;
            // Grouping into firstLayer
            $largeCards = [
                'total_case_number' => $total_case_number,
                'allSelfCases' => $allSelfCases,
                'incomplete_case_number' => $incomplete_case_number,
                'complete_case_number' => $complete_case_number,
            ];

            // Grouping into statistics
            $statistics = [
                'executed_court' => $executed_court,
                'total_case_number' => $total_case_number,
                'fine_collection' => $fine . " টাকা",
                'criminal_no_mgt' => $index . " জন",
            ];

            // Grouping into crimeInformation
            $crimeInfo = [
                'totalCitizenComplain' => $totalCitizenComplain,
                'citizen_case_complete' => $citz_case_complete,
                'result_case_processing' => $result_case_processing,
            ];

            $data['firstLayer'] = $largeCards;
            $data['statistics'] = $statistics;
            $data['crimeInfo'] = $crimeInfo;

            return $this->sendResponse($data, 'Dashboard Information Shown Successfully');
        }
    }

    public function saveJimmaderInformationWithOutCriminal(Request $request)
    {
        $data = $request->only(['prosecution_id', 'jimmaderName', 'jimmaderDesignation', 'jimmaderLocation', 'seizure_order']);

        if (empty($data['jimmaderName']) || empty($data['jimmaderDesignation']) || empty($data['jimmaderLocation'])) {
            return $this->sendError('Jimmader Information was not properly given', []);
        }

        $prosecutionId = $data['prosecution_id'];

        $prosecution = Prosecution::find($prosecutionId);

        if (!$prosecution) {
            return $this->sendError('Prosecution record not found.', []);
        }

        $prosecution->update([
            'jimmader_name' => $data['jimmaderName'],
            'jimmader_designation' => $data['jimmaderDesignation'],
            'jimmader_address' => $data['jimmaderLocation'],
            'dispose_detail' => $data['seizure_order'],
            'is_sizeddispose' => 1,
        ]);

        $prosecutionInfo = CaseRepository::getCaseInformationByProsecutionId($prosecutionId);
        return $this->sendResponse($prosecutionInfo, 'Full Prosecution Preview Information Shown Successfully');
    }

    public function seizedList(Request $request)
    {

        $prosecutor = Auth::user();
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 20);

        $query = Prosecution::select(
            'prosecutions.id as id',
            'prosecutions.subject',
            'prosecutions.date',
            'prosecutions.location',
            'prosecutions.case_no',
            'prosecutions.prosecutor_name'
        )
            ->join('courts', 'courts.id', '=', 'prosecutions.court_id')
            ->where('prosecutions.prosecutor_id', $prosecutor->id)
            ->where('prosecutions.is_approved', 0)
            ->orderBy('prosecutions.created_at', 'DESC');

        $allprosecutions = $query->paginate($limit, ['*'], 'page', $page);
        return $this->sendResponse($allprosecutions, 'Full seized Information Shown Successfully');
    }


    public function showProsecutionList(Request $request)
    {
        $prosecutor = globalUserInfo();  // gobal user; // Assuming you're getting the prosecutor via the authenticated user

        $page = $request->input('page', 1);
        $limit = $request->input('limit', 20);

        $query = DB::table('prosecutions as prose')
            ->select([
                'prose.id as id',
                'prose.subject as subject',
                'prose.date as date',
                'prose.location as location',
                'prose.case_no as case_no',
                'magistrate.name as magistrate_name',
                'prose.is_approved as is_approved',
                'szr.id as is_seizurelist',
                'prose.prosecutor_name as prosecutor_name',
                'court.magistrate_id as magistrateid',
                'court.status as status',
                'prose.orderSheet_id as is_orderSheet',
                'prose.hasCriminal as hasCriminal',
                'prose.case_status as case_status',
            ])
            ->join('courts as court', 'court.id', '=', 'prose.court_id')
            ->join('users as magistrate', 'magistrate.id', '=', 'court.magistrate_id')
            ->leftJoin('seizurelists as szr', 'szr.prosecution_id', '=', 'prose.id')
            ->where('prose.prosecutor_id', $prosecutor->id)
            // ->orderBy('prose.date', 'DESC')
            // ->orderBy('magistrate.id', 'DESC')
            // ->groupBy('szr.prosecution_id')
            ->orderBy('prose.id', 'DESC');
        $allprosecutions = $query->paginate($limit, ['*'], 'page', $page);
        return $this->sendResponse($allprosecutions, 'Full Completed Prosecution List Shown Successfully ');
    }
}
