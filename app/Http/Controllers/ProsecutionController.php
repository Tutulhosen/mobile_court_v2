<?php

namespace App\Http\Controllers;

use session;
use Carbon\Carbon;
use App\Models\User;
use App\Models\LawsBroken;
use App\Models\OrderSheet;

use App\Models\Punishment;
use App\Models\Prosecution;
use Illuminate\Http\Request;
use App\Models\CourtComplainInfo;
use Illuminate\Support\Facades\DB;
use App\Repositories\LawRepository;
use App\Repositories\CaseNoGenerate;
use App\Repositories\CaseRepository;
use App\Repositories\FileRepository;
use Illuminate\Support\Facades\Auth;
use App\Repositories\SeizureRepository;
use App\Repositories\WitnessRepository;
use App\Repositories\CriminalRepository;
use App\Repositories\OrderSheetRepository;
use App\Repositories\PunishmentRepository;
use App\Repositories\ProsecutionRepository;
use App\Repositories\ProsecutorLawMappingRepository;
use App\Http\Controllers\BaseController as BaseController;

class ProsecutionController extends BaseController
{
    //

    public function suomotucourt(Request $request, $prosecution_Id = '')
    {

        // $this->view->seizureitem_type = SeizureitemType::find();
        // $this->view->case_type = CaseType::find();

        // $this->view->jail_id = Jail::find();
        // dd($prosecution_Id);

        if ($prosecution_Id) {
            $data['prosecution_id'] = $prosecution_Id;
            $data['case_type'] = DB::table('mc_law_type')->get();
            $data['seizureitem_type'] = DB::table('seizureitem_type')->get();
            $data['jail'] = DB::table('mc_jail')->get();
            $data['division'] = DB::table('geo_divisions')
                ->select('id', 'division_name_bng')
                ->get();
            $data['selectMagistrateCourtId'] = '';
            // return $data;
            return view('appeals.suomotucourt')->with($data);
        } else {
            $data['prosecution_id'] = "";
        }
        $magistrateId = Auth::user()->id;
        $date = date('Y-m-d'); // today date
        if ($magistrateId) {
            $court = DB::table('courts')->where('magistrate_id', $magistrateId)->where('date', $date)->where('status', 1)->first();
            if (empty($court)) {  // if court not exist
                session()->flash('message', 'আজকের দিনে কোন কর্মসূচী নাই ।');
                return view('court.openclose');
            } else {
                $request->session()->put('court_id', $court->id);

                return redirect()->route('prosecution.prosecution_form');
            }
        } else {
            $str_message = "ম্যজিট্রেটের তথ্য পাওয়া যায়নি।";
        }
    }
    public function prosecution_form()
    {

        $data['case_type'] = DB::table('mc_law_type')->get();
        $data['seizureitem_type'] = DB::table('seizureitem_type')->get();
        $data['jail'] = DB::table('mc_jail')->get();
        $data['division'] = DB::table('geo_divisions')
            ->select('id', 'division_name_bng')
            ->get();
        $data['selectMagistrateCourtId'] = '';
        $data['prosecution_id'] = "";
        return view('appeals.suomotucourt')->with($data);
    }


    public function createProsecutionCriminalBymagistrate(Request $request)
    {

        $userinfo = globalUserInfo();  // gobal user
        $office_id = globalUserInfo()->office_id;  // gobal user
        $officeinfo = DB::table('office')->where('id', $office_id)->first();
        $date = date('Y-m-d'); // today date
        // login magistate court info
        $court_id = $request->session()->get('court_id');
        $case_no = CaseNoGenerate::getDigitalCaseNumber(); //$this->generateCaseNo();
        $magistrateCourtId =  $court_id; //$authuserinfo['court_id'];
        $isSuomoto = 1;

        if ($userinfo->role_id == 25) {
            $profile = 'Prosecutor';

            $magistrateId = $request->data['selectMagistrateId'];
            $magistrateCourtId = $request->data["selectMagistrateCourtId"];
            // $loginUserInfo=$this->utilityService->processLoginUserInfo($magistrateId,$loginUserInfo);
            $magistrate = User::find($magistrateId);

            $isSuomoto = 0;
        } elseif ($userinfo->role_id == 26) {
            $profile = 'Magistrate';
            $magistrateId = $userinfo->id;
        }
        $prosecutionID = $request->prosecutionID;

        $authuserinfo = array(
            'id' => $userinfo->id,
            'magistrate-id' => $magistrateId,
            'courtname' => $userinfo->name,
            'name' => $userinfo->name,
            'email' => $userinfo->email,
            'profile' => $profile,   // P
            'divid' => $officeinfo->division_id,
            'zillaid' => $officeinfo->district_id,
            'upozilaid' => $officeinfo->upazila_id,
            'divname' =>  $officeinfo->div_name_bn,
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





        $loginUserInfo = $authuserinfo;




        if ($prosecutionID != null && $prosecutionID > 0) {
            // let go, not a valid case
        } else {
            $prosecution =  ProsecutionRepository::createProsecutionShell($isSuomoto, $loginUserInfo, $magistrateCourtId, true);
            $prosecutionID = $prosecution->id;
        }

        $caseinfo = array(
            'magistrate_id' =>   $loginUserInfo['magistrate-id'],
            'magistrate_name' => $loginUserInfo['name'],
            'case_no' => $authuserinfo['case_no'],
            'prosecution_id' => $prosecutionID
        );
        $data = $request->all();
        $criminals = $data['data']['criminals'];
        CriminalRepository::saveCriminals($criminals, $caseinfo, $loginUserInfo);
        $caseInformation = CaseRepository::getCaseInformationByProsecutionId($prosecutionID);
        // dd($caseInformation );
        $msg["flag"] = "true";
        $msg["message"] = "মামলার আসামির তথ্য এট্রি করা হয়েছে ।";
        $msg["step"] = 2;
        $msg["caseInfo"] = $caseInformation;
        $msg["case_no"] = $case_no; //$dd['data']['case_no'];
        $msg["no_criminal_punish"] = 0;
        $msg["selectMagistrateCourtId"] = $magistrateCourtId ?? '';
        return json_encode($msg);
    }

    public function createProsecutionWitness(Request $request)
    {


        $userinfo = globalUserInfo();  // gobal user
        $office_id = globalUserInfo()->office_id;  // gobal user
        $officeinfo = DB::table('office')->where('id', $office_id)->first();
        $date = date('Y-m-d'); // today date
        $flag = false;
        $isSuomoto = 1;
        $caseInformation["flag"] = false;
        // login magistate court info
        if ($userinfo->role_id == 26) {
            $court = DB::table('courts')->select('id as court_id')->where('magistrate_id', $userinfo->id)->where('date', $date)->where('status', 1)->get();
            $court_id = $court[0]->court_id;
        }
        $case_no = CaseNoGenerate::getDigitalCaseNumber(); // $this->generateCaseNo();

        if ($userinfo->role_id == 25) {
            $profile = 'Prosecutor';
            $magistrateId = $request->data['selectMagistrateId'];
            $magistrateCourtId = $request->data["selectMagistrateCourtId"];
            // $loginUserInfo=$this->utilityService->processLoginUserInfo($magistrateId,$loginUserInfo);
            $magistrate = User::find($magistrateId);

            $isSuomoto = 0;
        } elseif ($userinfo->role_id == 26) {
            $profile = 'Magistrate';
            $magistrateId = $userinfo->id;
            $magistrateCourtId = $court_id;
        }
        $userinfo = array(
            'id' => $userinfo->id,
            'magistrate-id' => $magistrateId,
            'courtname' => $userinfo->name,
            'name' => $userinfo->name,
            'email' => $userinfo->email,
            'profile' => $profile,   // P
            'divid' => $officeinfo->division_id,
            'zillaid' => $officeinfo->district_id,
            'upozilaid' => $officeinfo->upazila_id,
            'divname' =>  $officeinfo->div_name_bn,
            'zillaname' => $officeinfo->dis_name_bn,
            'upozillaname' => $officeinfo->dis_name_bn,
            'serviceid' => $officeinfo->upa_name_bn,
            'office' => $officeinfo->office_name_bn,
            'officeType' => $officeinfo->organization_type,
            'joblocation' => $officeinfo->dis_name_bn,
            'mobile' => $userinfo->mobile_no,
            'designation' => $userinfo->designation,
            'role_id' => $userinfo->role_id,
            'court_id' => (!empty($court_id) ? $court_id : 0),
            'case_no' => $case_no,
        );


        $prosecutionID = $request['data']['prosecutionID'];
        $data = $request->all()['data'];

        if (!ProsecutionRepository::getProsecutionById($prosecutionID)) {
            $haseCriminal = false;
            if ($userinfo['profile'] == "Prosecutor") {
                $magistrateId = $request->data['selectMagistrateId'];
                $magistrateCourtId = $request->data["selectMagistrateCourtId"];
                $magistrate = User::find($magistrateId);
                $isSuomoto = 0;
                // Get existing prosecution_id or create new prosecution
            }

            $prosecution = ProsecutionRepository::createProsecutionShell($isSuomoto, $userinfo, $magistrateCourtId, $haseCriminal);

            $prosecutionID = $prosecution->id;
            $caseInformation["flag"] = WitnessRepository::saveWitnesses($data, $prosecutionID);
        } else {
            $caseInformation["flag"] = WitnessRepository::saveWitnesses($data, $prosecutionID);
        }
        $updatedProsecution = ProsecutionRepository::getProsecutionById($prosecutionID);
        $magistrateInfo = CaseRepository::getMagistrateInfoByProsecutionId($prosecutionID);

        $msg["flag"] = true;
        // $msg["prosecution"] = $witnessinfo['data']['updatedProsecution'];
        $msg["prosecution"] = $updatedProsecution;
        $msg["case_no"] = $case_no;
        $msg["magistrateInfo"] = $magistrateInfo;
        $msg["message"] = "সাক্ষীর তথ্য  সফলভাবে সংরক্ষণ করা হয়েছে ।";
        $msg["step"] = 3; // for witness
        $msg["prosecutionId"] = $prosecutionID;
        $msg["no_criminal_punish"] = 0;
        $msg["selectMagistrateCourtId"] = $magistrateCourtId ?? '';
        return json_encode($msg);
    }
    public function  getLaw()
    {
        $data = [];
        $subcategories = DB::table("mc_law")->select('title', 'id')->get();
        foreach ($subcategories as $t) {
            $data[] = array('id' => $t->id, 'name' => $t->title);
        }
        return json_encode($data);
    }
    public function getSectionByLawId(Request $request)
    {

        $id = $request->id;
        $data = [];
        $subcategories = DB::table("mc_section")->where('law_id', $id)->get();
        foreach ($subcategories as $t) {
            $data[] = array('id' => $t->id, 'sec_description' => 'ধারা :' . $t->sec_number . '-' . $t->sec_description);
        }
        return json_encode($data);
    }
    public function getPunishmentBySectionId(Request $request)
    {


        $id = $request->id;
        $data = "";
        $subcategories = DB::table("mc_section")->where('id', $id)->get();

        foreach ($subcategories as $t) {
            $data = array('name' => $t->punishment_des, 'sectiondes' => $t->punishment_sec_number);
        }
        return json_encode($data);
    }
    public function createProsecution(Request $request)
    {

        $userinfo = globalUserInfo();  // gobal user
        $office_id = globalUserInfo()->office_id;  // gobal user
        $officeinfo = DB::table('office')->where('id', $office_id)->first();
        $date = date('Y-m-d'); // today date
        // login magistate court info
        if ($userinfo->role_id == 26) {
            $court = DB::table('courts')->select('id as court_id')->where('magistrate_id', $userinfo->id)->where('date', $date)->where('status', 1)->get();
            $court_id = $court[0]->court_id;
        }
        $prosecutionInfo = $_POST;
        $brokenLawsArray = $prosecutionInfo['brokenLaws'];   //geting prosecution ID
        $prosecutionId = $prosecutionInfo['prosecutionId'];   //geting prosecution ID
        if ($userinfo->role_id == 25) {
            $profile = 'Prosecutor';
        } else {
            $profile = 'Magistrate';
        }
        $authuserinfo = array(
            'id' => $userinfo->id,
            'magistrate-id' => $userinfo->id,
            'courtname' => $userinfo->name,
            'name' => $userinfo->name,
            'email' => $userinfo->email,
            'profile' => $profile,   // P
            'divid' => $officeinfo->division_id,
            'zillaid' => $officeinfo->district_id,
            'upozilaid' => $officeinfo->upazila_id,
            'divname' =>  $officeinfo->div_name_bn,
            'zillaname' => $officeinfo->dis_name_bn,
            'upozillaname' => $officeinfo->dis_name_bn,
            'serviceid' => $officeinfo->upa_name_bn,
            'office' => $officeinfo->office_name_bn,
            'officeType' => $officeinfo->organization_type,
            'joblocation' => $officeinfo->dis_name_bn,
            'mobile' => $userinfo->mobile_no,
            'designation' => $userinfo->designation,
            'role_id' => $userinfo->role_id,
            'court_id' => (!empty($court_id) ? $court_id : 0),
        );
        $loginUserInfo = $authuserinfo;

        //--------------- Start File Upload  -------------------
        if ($request->has('files')) {
            $request->validate([
                'files.*.someName' => 'mimes:jpeg,png,jpg,pdf,doc,docx|max:5120',
            ]);

            $fileCategory = 'ChargeFame';

            // FileRepository::deleteExistingFiles($prosecutionId, $fileCategory);
            $prosecutionFiles = $request->file('files');

            foreach ($prosecutionFiles as $fileArray) {
                if (isset($fileArray['someName'])) {
                    $file = $fileArray['someName'];

                    $parameter = [
                        "entityID" => $prosecutionId,
                        "appName" => 'MobileCourt',
                        "fileCategory" => $fileCategory,
                        "fileCaption" => 'No Caption',
                    ];

                    if ($file) {
                        $newRequest = Request::create('', 'POST', $parameter);
                        $newRequest->files->set('someName', $file);

                        FileRepository::fileSaveForWeb($newRequest);
                    }
                }
            }
        }
        // ------------ End File Upload -------------

        $crimeDescription = LawRepository::saveLawsBrokenList($prosecutionId, $brokenLawsArray, $loginUserInfo);

        $prosecution = ProsecutionRepository::saveProsecutionInformation($prosecutionId, $prosecutionInfo, $crimeDescription, $loginUserInfo);

        // $lawsBrokenList = LawsBroken::where('prosecution_id', $prosecutionId)->get();
        if ($prosecution == false) {
            $msg["flag"] = "false";
            $msg["message"] = "এই মামলা নম্বর দিয়ে ইতিমধ্যেই একটি মামলা দাখিল হয়ছে।";
            return json_encode($msg);
        }

        $caseInformation = CaseRepository::getCaseInformationByProsecutionId($prosecutionId);

        // $msg["flag"] = "true";
        // $msg["message"] = "অভিযোগ দায়ের সফলভাবে সম্পন্ন হয়েছে।";
        // $msg["case_no"] = $prosecution->case_no;
        // $msg["caseInfo"] = $caseInformation;
        // $msg["lawsBrokenList"] =  $lawsBrokenList;
        // $msg["step"] = 4; // for seizure list
        // $msg["no_criminal"] = $prosecution->no_criminal;   // $prosecution->no_criminal
        // $msg["no_criminal_punish"] = 0;
        // $msg["isSuomoto"] = $prosecution->is_suomotu;
        // return $this->sendResponse($msg, 'সাক্ষীর তথ্য  সফলভাবে সংরক্ষণ করা হয়েছে ।');

        $msg["flag"] = "true";
        $msg["message"] = "অভিযোগ দায়ের সফলভাবে সম্পন্ন হয়েছে।";
        $msg["case_no"] = $prosecution->case_no;
        $msg["caseInfo"] = $caseInformation;;
        $msg["lawbroken"] =   $caseInformation['lawsBrokenList'];
        $msg["step"] = 4; // for seizure list
        $msg["no_criminal"] = $prosecution->no_criminal;
        $msg["no_criminal_punish"] = 0;
        $msg["isSuomoto"] = $prosecution->is_suomotu;
        return json_encode($msg);
    }

    public function savelist(Request $request)
    {
        // $ddd=Session::get('variableName');

        $jsonData = $request->all();
        $prosecution_id = $request->prosecutionId;
        $seizure_data = $request->seizure;
        $prosecution = Prosecution::find($prosecution_id);
        $seizureitem_type = DB::table('seizureitem_type')->get();

        $seizureService = SeizureRepository::saveSeizureList($prosecution_id, $seizure_data, $seizureitem_type);
        $seizureOrderContext = SeizureRepository::getSeizureOrderContext($prosecution_id);


        $msg["seizureOrderContext"] = $seizureOrderContext;
        $msg["flag"] = "true";
        $msg["prosecutionInfo"] = $prosecution;
        $msg["step"] = 5; // for ordersheet
        $msg["message"] = "জব্দতালিকা  সফলভাবে সংরক্ষণ করা হয়েছে ।";

        return json_encode($msg);

        //   $data['prosecution'] =$prosecution;
        //   $data['seizureService']=$seizureService;
        //   $data['seizureOrderContext']=$seizureOrderContext;
        //   return $this->sendResponse($data, 'জব্দতালিকা  সফলভাবে সংরক্ষণ করা হয়েছে ।');


    }

    public function saveCriminalConfessionSuomotu(Request $request)
    {
        $userinfo = globalUserInfo();  // gobal user
        $office_id = globalUserInfo()->office_id;  // gobal user
        $officeinfo = DB::table('office')->where('id', $office_id)->first();
        $date = date('Y-m-d'); // today date
        // login magistate court info
        // $court = DB::table('courts')->select('id as court_id')->where('magistrate_id',$userinfo->id)->where('date', $date)->where('status',1)->get();
        // $court_id = $court[0]->court_id;
        $Data = $request->modelData;
        $modelData = json_decode(json_encode(json_decode($Data)), true);
        $isSuomotoFlag = 1;

        $prosecutionID = $modelData['prosecutionID'];
        $confessionDetails = $modelData['criminalConfessionDetails'];
        $loginUserInfo = array(
            'id' => $userinfo->id,
            'magistrate-id' => $userinfo->id,
            'courtname' => $userinfo->name,
            'name' => $userinfo->name,
            'email' => $userinfo->email,
            'profile' => 'Magistrate',   // P
            'divid' => $officeinfo->division_id,
            'zillaid' => $officeinfo->district_id,
            'upozilaid' => $officeinfo->upazila_id,
            'divname' =>  $officeinfo->div_name_bn,
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



        // $jsonData = $request->all();
        // $loginUserInfo = json_decode($jsonData['logininfo'], true);
        //  $prosecutionID = json_decode($jsonData['prosecution_id'], true);
        //  $confessionDetails = json_decode($jsonData['confessionDetails'], true);

        // return $request->all();
        $prosecution = CriminalRepository::saveConfessionDetails($prosecutionID, $confessionDetails, $loginUserInfo);
        if ($prosecution) {
            $isSuomotoFlag = $prosecution->is_suomotu;
        }

        //--------------- Start File Upload  -------------------
        if ($request->has('files')) {
            $request->validate([
                'files.*.someName' => 'mimes:jpeg,png,jpg,pdf,doc,docx|max:5120',
            ]);

            $fileCategory = 'CriminalConfession';
            //Retrieve and delete old files
            // FileRepository::deleteExistingFiles($prosecutionID, $fileCategory);
            $prosecutionFiles = $request->file('files');

            foreach ($prosecutionFiles as $fileArray) {
                if (isset($fileArray['someName'])) {
                    $file = $fileArray['someName'];

                    $parameter = [
                        "entityID" => $prosecutionID,
                        "appName" => 'MobileCourt',
                        "fileCategory" => $fileCategory,
                        "fileCaption" => 'No Caption',
                    ];

                    if ($file) {
                        $newRequest = Request::create('', 'POST', $parameter);
                        $newRequest->files->set('someName', $file);

                        FileRepository::fileSaveForWeb($newRequest);
                    }
                }
            }
        }
        // ------------ End File Upload -------------

        // return $this->sendResponse($data,'');
        // $msg["isSuomoto"]=$response['data']['isSuomotoFlag'];
        // $msg["flag"] = "true";
        // $msg["message"] = "জবানবন্দী সংরক্ষণ করা হয়েছে ।";
        // $msg["step"] = 6; // for ordersheet
        // return json_encode($msg);

        $msg["isSuomoto"] = $isSuomotoFlag;
        $msg["flag"] = "true";
        $msg["message"] = "জবানবন্দী সংরক্ষণ করা হয়েছে ।";
        $msg["step"] = 6; // for ordersheet
        return json_encode($msg);
    }

    public function getCriminalPreviousCrimeDetails(Request $request)
    {

        $prosecution_id = $request->prosecutionId;
        $criminal_id = $request->criminalId;
        $crimeDetail = CaseRepository::getPreviousCrimeDetailsByCriminalId($criminal_id, $prosecution_id);
        // return $this->sendResponse($crimeDetail, '');
        return  json_encode($crimeDetail);
    }

    public function isPunishmentExist(Request $request)
    {
        $msgExistOrder = null;
        $alldata = $request->data;
        $flag = "false";
        $isPunishmentExist = PunishmentRepository::isPunishmentExist($alldata);
        // dd($isPunishmentExist);
        if ($isPunishmentExist == 1) {
            $msgExistOrder = 'আসামি কে ইতিমধ্যে এই আইনে  আদেশ প্রদান করা হয়েছে। ';
            $flag = "true";
        }
        $msg["msgExistOrder"] = $msgExistOrder;
        $msg["flag"] = $flag;
        // return $this->sendResponse($isPunishmentExist, '');
        return  json_encode($msg);
    }

    public function saveOrderBylaw(Request $request)
    {

        $userinfo = globalUserInfo();  // gobal user
        $office_id = globalUserInfo()->office_id;  // gobal user
        $officeinfo = DB::table('office')->where('id', $office_id)->first();
        $date = date('Y-m-d'); // today date
        // login magistate court info
        // $court = DB::table('courts')->select('id as court_id')->where('magistrate_id',$userinfo->id)->where('date', $date)->where('status',1)->get();
        // $court_id = $court[0]->court_id;

        // $msg = '';
        $msgExistOrder = null;
        $data = $request->data;

        $logininfo = array(
            'id' => $userinfo->id,
            'magistrate-id' => $userinfo->id,
            'courtname' => $userinfo->name,
            'name' => $userinfo->name,
            'email' => $userinfo->email,
            'profile' => 'Magistrate',   // P
            'divid' => $officeinfo->division_id,
            'zillaid' => $officeinfo->district_id,
            'upozilaid' => $officeinfo->upazila_id,
            'divname' =>  $officeinfo->div_name_bn,
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

        $punishment = PunishmentRepository::savePunishment($data, $logininfo);

        if (!$punishment) {
            $msgExistOrder = 'আসামি কে ইতিমধ্যে এই আইনে  আদেশ প্রদান করা হয়েছে। ';
        }

        // prepare response message
        $msg["flag"] = "true";
        $msg["message"] = "আদেশ সম্পন্ন হয়েছে।";
        $msg["punishment"] = $punishment; //
        $msg["msgExistOrder"] = $msgExistOrder;
        $response = json_encode($msg);

        return $response;
    }

    public function getThanaByUsersZillaId()
    {

        $thanaArray = array();
        // $user = $this->auth->getUserLocation();
        $userinfo = globalUserInfo();  // gobal user
        $office_id = globalUserInfo()->office_id;  // gobal user
        $officeinfo = DB::table('office')->where('id', $office_id)->first();

        $date = date('Y-m-d'); // today date
        // login magistate court info
        // $court = DB::table('courts')->select('id as court_id')->where('magistrate_id',$userinfo->id)->where('date', $date)->where('status',1)->get();
        // $court_id = $court[0]->court_id;

        $userinfo = array(
            'id' => $userinfo->id,
            'magistrate-id' => $userinfo->id,
            'courtname' => $userinfo->name,
            'name' => $userinfo->name,
            'email' => $userinfo->email,
            'profile' => 'Magistrate',   // P
            'divid' => $officeinfo->division_id,
            'zillaid' => $officeinfo->district_id,
            'upozilaid' => $officeinfo->upazila_id,
            'divname' =>  $officeinfo->div_name_bn,
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
        $tmp = array();
        if ($userinfo['zillaid']) {

            $tmp = DB::table('geo_thanas')->where('geo_district_id', $userinfo['zillaid'])->get();
        }
        foreach ($tmp as $t) {
            $thanaArray[] = array('id' => $t->id, 'name' => $t->thana_name_bng);
        }
        return  json_encode($thanaArray);
    }

    public function getOrderListByProsecutionId(Request $request)
    {

        $proceutionId = $request->data;
        $proceutionId = $proceutionId['prosecution_id'];

        $punishmentList = PunishmentRepository::getOrderListByProsecutionId($proceutionId);
        // $msg=[];
        // $msg['caseInfo']=CaseRepository::getCaseInformationByProsecutionId($proceutionId['prosecution_id']);
        // $msg['lawsBrokenList'] = LawsBroken::where('prosecution_id', $proceutionId)->get();
        // $msg['orderList']=PunishmentRepository::getOrderListByProsecutionId($proceutionId);

        // $msg['punishmentInfo']=PunishmentRepository::getPunishmentInfo($proceutionId);
        // prepare response message
        //  return $this->sendResponse($msg, '');
        $msg["flag"] = "true";
        $msg["message"] = "ডাটা পাওয়া গেছে";
        $msg["punishmentList"] = $punishmentList; //
        $response = json_encode($msg);
        return $response;
    }

    public function deleteOrder(Request $request)
    {


        $orderId = $request->orderId;
        $proceutionId = $request->prosecutionId;
        $punishment = PunishmentRepository::deletePunishmentByOrderId($proceutionId, $orderId);

        // prepare response message
        $msg["flag"] = "true";
        $msg["message"] = " রেকর্ডটি";
        $msg["punishment"] = $punishment; //
        $response = json_encode($msg);
        return $response;
        // return $this->sendResponse( $punishment, '');
    }

    public function getCaseInfoByProsecutionId(Request $request)
    {
        $prosecutionId = $request->prosecutionId;
        $caseInfo = CaseRepository::getCaseInformationByProsecutionId($prosecutionId);

        if ($caseInfo['prosecution']->case_no != 'অভিযোগ গঠন হয়নি') {
            $case_no = $caseInfo['prosecution']->case_no;
        } else {
            $case_no = CaseNoGenerate::getDigitalCaseNumber(); // $this->generateCaseNo();
        }
        $msg["flag"] = "true";
        $msg["caseInfo"] = $caseInfo;   // $prosecution->no_criminal
        $msg["case_no"] = $case_no;   // Send Case no

        $ddd = json_encode($msg);
        return $ddd;
    }

    public function saveJimmaderInformation(Request $request)
    {

        $Data = $request->modelData;
        $jimmaderInfo = json_decode(json_encode(json_decode($Data)), true);
        $successMsg = ProsecutionRepository::saveJimmaderInformation($jimmaderInfo[0]);
        $msg["flag"] = $successMsg;
        $response = json_encode($msg);
        if (!empty($dataDecode) && isset($dataDecode[0]['prosecutionid'])) {
            $prosecutionId = $dataDecode[0]['prosecutionid'];

            //--------------- Start File Upload  -------------------
            if ($request->has('files')) {
                $request->validate([
                    'files.*.someName' => 'mimes:jpeg,png,jpg,pdf,doc,docx|max:5120',
                ]);

                $fileCategory = 'OrderSheet';
                //Retrieve and delete old files
                // FileRepository::deleteExistingFiles($prosecutionId, $fileCategory);
                $prosecutionFiles = $request->file('files');

                foreach ($prosecutionFiles as $fileArray) {
                    if (isset($fileArray['someName'])) {
                        $file = $fileArray['someName'];

                        $parameter = [
                            "entityID" => $prosecutionId,
                            "appName" => 'MobileCourt',
                            "fileCategory" => $fileCategory,
                            "fileCaption" => 'No Caption',
                        ];

                        if ($file) {
                            $newRequest = Request::create('', 'POST', $parameter);
                            $newRequest->files->set('someName', $file);

                            FileRepository::fileSaveForWeb($newRequest);
                        }
                    }
                }
            }
            // ------------ End File Upload -------------
        }
        $response = json_encode($msg);
        return $response;
    }

    public function previewOrderSheet(Request $request)
    {
        // orderSheetLayout
        $prosecutionId = $request->prosecutionId;

        return view('appeals.partials.orderSheetLayout', ['prosecutionId' => $prosecutionId]);
    }
    public function incompletecase()
    {
        $magistrate = Auth::user(); // Assuming the magistrate is the logged-in user

        $prosecutions = DB::table('prosecutions as prose')
            ->select([
                'prose.id as id',
                'prose.subject as subject',
                'prose.date as date',
                'prose.location as location',
                'prose.case_no as case_no',
                'magistrate.name',
                'prose.is_approved as is_approved',
                DB::raw('IF(szr.id IS NOT NULL, 1, 0) as is_seizurelist'),
                DB::raw('IF(prose.orderSheet_id IS NOT NULL, 1, 0) as is_orderSheet')
            ])
            ->join('courts as court', 'court.id', '=', 'prose.court_id')
            ->join('users as magistrate', 'magistrate.id', '=', 'court.magistrate_id')
            ->leftJoin('seizurelists as szr', 'szr.prosecution_id', '=', 'prose.id')
            ->where('court.magistrate_id', $magistrate->id)
            ->where('is_suomotu', 1)
            ->where('hasCriminal', 1)
            ->whereNull('orderSheet_id')
            ->where('court.status', '!=', 2)
            ->where('prose.delete_status', 1)
            ->groupBy('prose.id')
            ->orderBy('prose.id', 'DESC')
            ->orderBy('prose.date', 'DESC')
            ->paginate(10);


        // $allprosecutions = Prosecution::with(['court'])
        // ->where('is_suomotu',1)
        // ->where('hasCriminal',1)
        // ->whereNull('orderSheet_id')
        // ->where('delete_status',1)
        // ->where('magistrate_id', $magistrate->id)
        // ->groupBy('id')
        // ->orderBy('id', 'DESC')
        // // ->orderBy('date', 'DESC')
        // ->paginate(10);
        // dd(  $prosecutions);
        // $prosecutions = $allprosecutions;
        $page_title = 'দায়েরকৃত অভিযোগ';

        return view('appeals.incompletecase', compact('prosecutions', 'page_title'));
    }


    public function searchProsecution(Request $request)
    {
        // Set default page number
        $numberPage = 1;

        // Check if it's a POST request
        if ($request->isMethod('post')) {
            $parameters = $request->all();  // Retrieve POST data
            $query = Prosecution::query();  // Start a query with Prosecution model
        } else {
            // Get the page number from query parameters
            $numberPage = $request->query('page', 1);
        }

        // Default order parameters
        $parameters = [
            "order" => "date ASC"
        ];


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
            ->paginate(10, ['*'], 'page', $numberPage);
        //  dd($prosecution);
        return view('appeals.searchProsecution', ['prosecutions' => $prosecution, 'page_title' => "প্রসিকিউশনের তালিকা"]);
    }

    public function newComplain(Request $request, $prosecution_id = '')
    {

        // Find the prosecution by ID
        $prosecution = Prosecution::find($prosecution_id);
        // Check if the prosecution was found
        if ($prosecution) {
            // Pass the prosecution ID to the view
            return view('appeals.newComplain', ['prosecutionId' => $prosecution_id, 'page_title' => 'অভিযোগ গঠন']);
        }
    }
    public function searchComplain(Request $request)
    {

        $userinfo = Auth::user();
        // Default page number

        // Retrieve magistrate from the auth service (assuming you have a method for this)
        $magistrateId = $userinfo->id;

        $numberPage = $request->input('page', 1); // Default to page 1 if not specified
        // Build the query
        $query = Prosecution::select([
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
            'prosecutions.is_approved'
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
                'prosecutions.prosecutor_name'
            ])
            ->orderBy('prosecutions.id', 'DESC')
            ->paginate(10, ['*'], 'page', $numberPage);

        return view('appeals.searchComplain', ['prosecutions' => $query, 'page_title' => "মামলার তালিকা"]);
    }
    public function searchCase(Request $request)
    {
        $numberPage = 1;

        // Handle POST request and session persistence
        if ($request->isMethod('post')) {
            // For simplicity, the query is omitted, replace this as per your requirement
            $queryParams = $request->except('_token');
            session(['parameters' => $queryParams]);
        } else {
            $numberPage = $request->query('page', 1); // Get page number
        }

        $parameters = session('parameters', []);
        if (!is_array($parameters)) {
            $parameters = [];
        }

        // Get the logged-in magistrate (Assuming you have a magistrate role)
        $magistrate = Auth::user();

        // Build the query using Eloquent and Query Builder
        $prosecution = Prosecution::select([
            'prosecutions.id',
            'prosecutions.subject',
            'prosecutions.date',
            'prosecutions.location',
            'prosecutions.case_no',
            'prosecutions.hints',
            'prosecutions.hasCriminal',
            'prosecutions.prosecutor_name',
            DB::raw('CONCAT(criminal_confessions.id) as is_criminal_confession'),
            'prosecutions.orderSheet_id',
            'courts.status as status',
            'courts.date as courtdate',
            'prosecutions.is_suomotu'
        ])
            ->leftJoin('criminal_confessions', 'criminal_confessions.prosecution_id', '=', 'prosecutions.id')
            ->join('courts', 'courts.id', '=', 'prosecutions.court_id')
            ->where('courts.magistrate_id', $magistrate->id)
            ->where('prosecutions.is_approved', 1)
            ->whereNotNull('prosecutions.orderSheet_id')
            ->groupBy([
                'prosecutions.id',
                'prosecutions.subject',
                'prosecutions.date',
                'prosecutions.location',
                'prosecutions.case_no',
                'prosecutions.hints',
                'prosecutions.prosecutor_name'
            ])
            ->orderBy('prosecutions.created_at', 'desc')
            ->paginate(10, ['*'], 'page', $numberPage);
        // dd( $prosecution);
        // Pass paginated results to the view
        return view('appeals.searchCase', ['prosecutions' => $prosecution, 'page_title' => 'মামলার তালিকা']);
    }
    public function extendOrderSheet(Request $request, $id = '')
    {
        // Initialize variables

        $order_header = "";
        $order_details = "";
        $fine = "";
        $fine_in_word = "";
        $receipt_no = "";
        $punishmentId = "";

        // Get punishment
        // $punishment = Punishment::join('prosecution_details AS PrD', 'PrD.prosecution_id', '=', 'punishments.prosecution_id')
        //     ->where('punishments.prosecution_id', $id)
        //     ->orderBy('PrD.id', 'ASC')
        //     ->first();

        $punishment = Punishment::select([
            'punishments.id as punishment_id',
            'punishments.fine',
            'punishments.fine_in_word',
            'punishments.receipt_no'
        ])
            ->join('prosecution_details as PrD', 'PrD.prosecution_id', '=', 'punishments.prosecution_id')
            ->where('punishments.prosecution_id', $id)
            ->orderBy('PrD.id', 'ASC')
            ->limit(1)
            ->get();
        // dd($punishment);

        // if ($punishment) {
        //     $punishmentId = $punishment->id;
        //     $receiptNo = $punishment->receipt_no;
        //     $fineInWord = $punishment->fine_in_word;
        //     $fine = $punishment->fine;
        // }
        // return $punishment;
        foreach ($punishment as $emp) {
            $punishment_id = $emp->punishment_id;
            $receipt_no = $emp->receipt_no;
            $fine_in_word = $emp->fine_in_word;
            $fine = $emp->fine;
        }

        // dd($punishment_id);
        // Get order sheet details
        if ($id) {
            $orderSheets = OrderSheet::where('prosecution_id', $id)->get();
            if ($orderSheets->count() > 0) {
                $details = "";
                foreach ($orderSheets as $orderSheet) {
                    $details .= $orderSheet->order_details;
                    $orderHeader = $orderSheet->order_header;
                }
            }
        }
        $dd = [
            'order_header' => $orderHeader,
            'order_details' => $details,
            'fine' => $fine,
            'fine_in_word' => $fine_in_word,
            'receipt_no' => $receipt_no,
            'punishment_id' => $punishment_id,
            'prosecution_id' => $id,
        ];

        // Pass data to the view
        return view('appeals.extendOrderSheet', $dd);
    }
    public function saveExtendOrderSheet(Request $request)
    {
        $version = 0;
        $flag = false;

        // Retrieve inputs from the request
        $prosecution_id = $request->prosecution_id;
        $punishment_id = $request->punishment_id;
        $receiver_id = $request->select01;
        // $fine = $request->input('fine');
        $fine_in_word = $request->input('fine_in_word');
        $receipt_no = $request->input('receipt_no');
        $table = $request->input('tinymce_full');

        // Handle the date format
        $test = $request->date01;
        //  return $request->all();
        $formatted_date = Carbon::createFromFormat('Y-m-d', $test)->format('Y-m-d');

        // Call the service to process data
        $flag = OrderSheetRepository::makeExtendedOrderSheetBody(
            $receipt_no,
            $formatted_date,
            $table,
            $receiver_id,
            $prosecution_id
        );

        //--------------- Start File Upload  -------------------
        if ($request->has('files')) {
            $request->validate([
                'files.*.someName' => 'mimes:jpeg,png,jpg,pdf,doc,docx|max:5120',
            ]);

            $fileCategory = 'ExtendedOrder';

            // FileRepository::deleteExistingFiles($prosecutionId, $fileCategory);
            $prosecutionFiles = $request->file('files');

            foreach ($prosecutionFiles as $fileArray) {
                if (isset($fileArray['someName'])) {
                    $file = $fileArray['someName'];

                    $parameter = [
                        "entityID" => $prosecution_id,
                        "appName" => 'MobileCourt',
                        "fileCategory" => $fileCategory,
                        "fileCaption" => 'No Caption',
                    ];

                    if ($file) {
                        $newRequest = Request::create('', 'POST', $parameter);
                        $newRequest->files->set('someName', $file);

                        FileRepository::fileSaveForWeb($newRequest);
                    }
                }
            }
        }
        // ------------ End File Upload -------------

        if ($flag === true) {
            // Prepare parameters for file saving
            // $entityID = $prosecution_id;
            // $appName = 'Mobile';
            // $fileCategory = 'ExtendedOrder';
            // $fileCaption = 'No Caption';
            // $baseUrl = config('app.image_upload_uri'); // Assuming you store the base URL in config

            // $parameters = [
            //     'entityID' => $entityID,
            //     'baseUrl' => $baseUrl,
            //     'appName' => $appName,
            //     'fileCategory' => $fileCategory,
            //     'fileCaption' => $fileCaption,
            // ];

            // Check for file upload
            // if ($request->hasFile('file')) {
            //     $this->fileContentService->fileSave($parameters);
            // }

            // Set success message
            session()->flash('success', 'আদেশনামা সফলভাবে সংরক্ষণ করা হয়েছে ।');
        } else {
            session()->flash('error', 'আদেশনামা সফলভাবে সংরক্ষণ করা হয় নি ।');
        }

        // Redirect to another action
        return redirect()->action([ProsecutionController::class, 'searchComplain']);
    }

    public function getOrderSheetInfo(Request $request)
    {

        $prosecutionId = $request->data;
        // dd($prosecutionId);
        // $all_data=$request->all();
        // $prosecutionId=json_decode($all_data['prosecutionId'],true);
        $punishmentInfo = PunishmentRepository::getPunishmentInfo($prosecutionId);
        // dd()
        $orderList = PunishmentRepository::getOrderListByProsecutionId($prosecutionId);
        $caseInfo = CaseRepository::getCaseInformationByProsecutionId($prosecutionId);
        // $lawsBrokenList = LawsBroken::where('prosecution_id',$prosecutionId)->get();
        // $response=array(
        //     "punishmentConfessionByLaw"=>$punishmentInfo,
        //     "orderList"=>$orderList,
        //     "caseInfo"=>$caseInfo,
        //     "lawsBrokenList"=>$lawsBrokenList,
        // );

        $response = array(
            "punishmentConfessionByLaw" => $punishmentInfo,
            "orderList" => $orderList,
            "caseInfo" => $caseInfo
        );

        $msg["flag"] = "true";
        $msg["message"] = " রেকর্ডটি";
        $msg["info"] = $response; //
        $response = json_encode($msg);
        return $response;
        // return $this->sendResponse($response, '');

    }

    public  function saveOrderSheet(Request $request)
    {
        $header = $request->header;
        $tableBody = $request->tableBody;
        $prosecutionId = $request->prosecutionId;
        $successMsg = PunishmentRepository::saveOrderSheet($header, $tableBody, $prosecutionId);
        $msg["flag"] = $successMsg;
        $response = json_encode($msg);
        return $response;
    }

    public function showForms(Request $request)
    {


        // $searchCondition ='prosecutions.is_approved =1 AND  prosecutions.delete_status = 1 AND prosecutions.orderSheet_id IS NOT NULL';
        //     $prosecution = "";

        //     // Get the current authenticated user
        //         $magistrate = Auth::user();
        //     // Build the query
        //         $prosecutions = Prosecution::select([
        //             'prosecutions.id as id',
        //             'prosecutions.subject as subject',
        //             'prosecutions.date as prosecution_date',
        //             'prosecutions.location as location',
        //             'prosecutions.case_no as case_no',
        //             'prosecutions.hints as hints',
        //             'prosecutions.is_suomotu as is_suomotu',
        //             'prosecutions.hasCriminal as hasCriminal',
        //             'prosecutions.prosecutor_name as prosecutor_name',
        //             // 'office.office_name_bn as office_name_bn',
        //         ])
        //         ->join('courts', 'courts.id', '=', 'prosecutions.court_id')
        //         // ->join('office', 'office.district_id', '=', 'prosecutions.zillaId')
        //         ->where('courts.magistrate_id', $magistrate->id)
        //         // ->where('office.level',4)
        //         ->whereRaw($searchCondition) // Assuming $searchCondition is properly formatted
        //         ->orderBy('prosecutions.created_at', 'desc');
        //         $prosecutions = $prosecutions->paginate(15);

        //         $title='মামলাসমূহ';
        //         return view('appeals.showForms',compact('prosecutions','title'));


        $numberPage = 1;
        if ($request->isMethod('post')) {
            $searchParams = $request->except('_token');
            session(['searchParams' => $searchParams]);
        } else {
            $numberPage = $request->query('page', 1);
        }
        $parameters = session('searchParams', []);
        $cond = "prose.is_approved = 1";
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

        $caseNo = $request->case_no; // Default to an empty string if not provided
        $searchCriteria = $request->searchCriteria; // Default to an empty string if not provided
        $magistrateID = $request->magistrate_id; // Default to an empty string if not provided

        $prosecutions1 = DB::table('prosecutions AS prose')->join('courts AS court', 'court.id', '=', 'prose.court_id');

        if ($searchCriteria === 'is_case') {
            // $searchCondition = 'prose.case_no = ?'; // Use parameter binding for security
            $searchCriteriaLabel = '(মামলার নম্বর: ' . $caseNo . ')';
            $paginateType = 'is_case';
            $prosecutions = $prosecutions1->where('prose.case_no', $caseNo); // Use parameter binding to avoid SQL injection
        }

        if ($searchCriteria === 'is_date') {

            // $searchCondition .= 'prose.date BETWEEN ? AND ?';
            $searchCriteriaLabel = '(তারিখ: ' . $startDate . ' থেকে ' . $endDate . ')';
            $paginateType = 'is_date';
            $prosecutions = $prosecutions1->whereBetween('prose.date', [$startDate, $endDate]);
        }
        if ($searchCriteria == 'is_magistrate') {
            // Append to search condition
            $searchCondition = $searchCondition . ' AND court.magistrate_id = ' . $magistrateID;

            // Retrieve the magistrate's name using Eloquent or the query builder
            $magistrate = DB::table('users')
                ->select('users.name as name_bng')
                ->where('id', $magistrateID)
                ->first();

            // Pass data to the view

            $magistrate_name = $magistrate->name_bng ?? '';

            $data['searchCriteria'] = '(এক্সিকিউটিভ ম্যাজিস্ট্রেট: ' . $magistrate_name . ')';
            $data['magistrate_id'] = 'is_magistrate';
            $data['paginate'] = $magistrateID;
        }


        // $searchCondition = '';

        // if ($searchCriteria === 'is_case') {
        //     $searchCondition .= 'prose.case_no = ?';
        //     $searchCriteriaLabel = '(মামলার নম্বর: '.$caseNo.')';
        //     $paginateType = 'is_case';
        // } elseif ($searchCriteria === 'is_date') {
        //     $searchCondition .= 'prose.date BETWEEN ? AND ?';
        //     $searchCriteriaLabel = '(তারিখ: '.$startDate.' থেকে '.$endDate.')';
        //     $paginateType = 'is_date';
        // } elseif ($searchCriteria === 'is_magistrate') {
        //     $searchCondition .= 'court.magistrate_id = ?';

        //     // Retrieve magistrate name
        //     $magistrate = DB::table('magistrates')
        //         ->where('id', $magistrateID)
        //         ->value('name_bng'); // Get the magistrate's name directly

        //     $searchCriteriaLabel = '(এক্সিকিউটিভ ম্যাজিস্ট্রেট: '.$magistrate.')';
        //     $paginateType = 'is_magistrate';
        // } else {
        //     $searchCriteriaLabel = '';
        //     $paginateType = '';
        // }
        $roleID = globalUserInfo()->role_id;
        if ($roleID == 37 || $roleID == 38) {
            // Set user and get user location (assuming you have an Auth system with user location details)
            $user = 'ADM';

            $office_id = globalUserInfo()->office_id;
            $officeinfo = DB::table('office')->where('id', $office_id)->first();
            $divid = $officeinfo->division_id;
            $zillaId = $officeinfo->district_id;

            // Query using Laravel's query builder
            $magistrates = DB::table('users as mag')
                ->join('doptor_user_access_info as dp', 'dp.user_id', '=', 'mag.id')
                ->join('mc_role as r', 'r.id', '=', 'dp.role_id')
                ->join('office as job_des', 'job_des.id', '=', 'mag.office_id')
                ->select(

                    'mag.id as id',
                    DB::raw('CONCAT(mag.name,"-",r.role_name,"-",job_des.office_name_bn) as name_eng'),
                    // 'mag.national_id as national_id',
                    'mag.mobile_no as mobile',
                    'mag.email as email'
                )
                ->where('job_des.district_id', $zillaId)
                ->where('job_des.division_id', $divid)
                ->where('dp.role_id', 26)
                ->get();
            $data['magistrates'] = $magistrates  ?? '';
            // Assuming $user is the currently authenticated user
            $user = auth()->user();

            // Query using Laravel's query builder
            // $admProfile = DB::table('adm_profiles as admprofile')
            //     ->join('divisions as division', 'division.divid', '=', 'admprofile.divid')
            //     ->join('zillas as zilla', 'zilla.zillaid', '=', 'admprofile.zillaid')
            //     ->select(
            //         'admprofile.zillaId as zillaId',
            //         'admprofile.office_address as office_address',
            //         'division.divid as divid',
            //         'division.divname as divname',
            //         'zilla.zillaname as zillaname'
            //     )
            //     ->where('admprofile.user_id', $user->id)
            //     ->first();
            // dd($admProfile);
            // Check if the profile exists and extract the necessary data
            // $divid = $admProfile->divid ?? '';
            // $zillaId = $admProfile->zillaId ?? '';

            // Pass the results to the view
            // return view('magistrates.index', ['magistrates' => $magistrates, 'user' => $user]);

            $prosecutions = $prosecutions1->where('prose.divid', $divid);
            $prosecutions = $prosecutions1->where('prose.zillaId', $zillaId);
            $prosecutions = $prosecutions1->whereRaw($searchCondition); // Apply search conditions
            $prosecutions = $prosecutions1->select(
                'prose.id as id',
                // 'prose.magistrate_id',
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
                ->orderBy('prose.id', 'desc')
                ->paginate(10, ['*'], 'page', $numberPage);
        } elseif ($roleID == 26) {


            $prosecutions = $prosecutions1->where('court.magistrate_id', $magistrate_id);
            $prosecutions = $prosecutions1->whereRaw($searchCondition); // Apply search conditions
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
                ->orderBy('prose.id', 'desc')
                ->paginate(10, ['*'], 'page', $numberPage);
        }
        // dd($prosecutions);
        $data['prosecutions'] = $prosecutions;
        $data['searchCriteria'] = $searchCriteriaLabel ?? '';
        $data['paginate'] = $paginateType ?? "";
        $data['start_date'] = $startDate ?? "";
        $data['end_date'] = $endDate ?? "";
        $data['magistrate_id'] = $magistrateID;

        return view('appeals.showForms', $data);

        // return view('appeals.showForms', ['prosecutions' => $prosecutions,
        // 'searchCriteria' => $searchCriteriaLabel ?? '',
        // 'paginate' => $paginateType ?? "",
        // 'start_date' => $startDate ?? "",
        // 'end_date' => $endDate ?? "",

        // 'magistrate_id' => $magistrateID,'magistrates'=> $magistrates  ?? '']);

    }
    public function printForms(Request $request, $id)
    {

        // dd('ok');
        // Query 1: Get Criminals associated with the prosecution
        $criminals = DB::table('criminals as crm')
            ->select(
                'crm.id as id',
                'crm.name_bng as name',
                'crm.custodian_name as fathername',
                'crm.custodian_type as custodian_type',
                'crm.mother_name as mothername',
                'lb.law_id as law_ids',
                'lb.section_id as section_ids',
                'lb.Description as crime_description'
            )
            ->join('prosecution_details as PrD', 'PrD.criminal_id', '=', 'crm.id')
            ->join('laws_brokens as lb', 'lb.prosecution_id', '=', 'PrD.prosecution_id')
            ->where('PrD.prosecution_id', $id)
            ->groupBy('crm.id')
            ->limit(30)
            ->get();


        // Query 2: Get Prosecution details
        $prosecution = DB::table('prosecutions as prose')
            ->select(
                'prose.id as id',
                'prose.subject as subject',
                'prose.date as date',
                'prose.location as location',
                'prose.case_no as case_no',
                'prose.is_suomotu as is_suomotu',
                'prose.prosecutor_name as prosecutor_name',
                'prose.hasCriminal as hasCriminal'
            )
            ->where('prose.id', $id)
            ->get(); // First result for prosecution details
        //  dd( $prosecution );
        // Parse crime description
        $crime_description = "";
        // if ($prosecution) {
        //   return  $crime_description = json_decode($prosecution->subject, true);
        //     if (json_last_error() !== JSON_ERROR_NONE) {
        //         // Fallback if JSON is invalid
        //         $crime_description[0] = $prosecution->subject;
        //     }
        // }
        foreach ($prosecution as $temp) {
            $crime_description = json_decode($temp->subject, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                // JSON is valid
                // everything is OK
                //echo "everything is OK".$ade[0];
            } else {
                $crime_description[0] = $temp->subject;
            }
        }


        // Query 3: Retrieve files by entity ID
        $files = DB::table('file_content')
            ->where('entityID', $id)
            ->get()
            ->map(function ($value) {
                return (object) [
                    'FileID' => $value->FileID,
                    'FileName' => $value->FileName,
                    'FileCategory' => $value->FileCategory,
                    'FileType' => $value->FileType,
                    'FilePath' => $value->FilePath . $value->FileName
                ];
            });
        //    dd($files);
        // Return data to the view
        // return view('appeals.printForms', [
        //     'criminals' => $criminals,
        //     'prosecutions' => $prosecution,
        //     'crime_description' => $crime_description,
        //     // 'uploaded_file' => $files,
        //     'id' => $id,
        //     'is_suomotu' => $prosecution->is_suomotu,
        //     'hasCriminal' => $prosecution->hasCriminal
        // ]);
        $data['uploaded_file'] = $files;
        $data['prosecution'] = $prosecution;
        $data['is_suomotu'] = $prosecution[0]->is_suomotu;
        $data['hasCriminal'] = $prosecution[0]->hasCriminal;
        $data['criminals'] = $criminals;
        $data['crime_description'] = $crime_description;
        $data['id'] = $id;
        // Query 3: Retrieve files by entity ID
        // $files = DB::table('file_contents')
        //     ->where('entityID', $id)
        //     ->get()
        //     ->map(function($value) {
        //         return (object) [
        //             'FileID' => $value->FileID,
        //             'FileName' => $value->FileName,
        //             'FileCategory' => $value->FileCategory,
        //             'FileType' => $value->FileType,
        //             'FilePath' => '/ecourt/' . $value->FilePath . $value->FileName
        //         ];
        //     });

        // Return data to the view
        // return view('appeals.printForms', [
        //     'criminals' => $criminals,
        //     'prosecutions' => $prosecution,
        //     'crime_description' => $crime_description,
        //     // 'uploaded_file' => $files,
        //     'id' => $id,
        //     'is_suomotu' => $prosecution->is_suomotu,
        //     'hasCriminal' => $prosecution->hasCriminal
        // ]);

        $data['prosecution'] = $prosecution;
        $data['is_suomotu'] = $prosecution[0]->is_suomotu;
        $data['hasCriminal'] = $prosecution[0]->hasCriminal;
        $data['criminals'] = $criminals;
        $data['crime_description'] = $crime_description;
        $data['id'] = $id;

        // [
        // 'criminals'=>$criminals,
        // 'prosecution'=>$prosecution,
        // 'crime_description'=>$crime_description,
        // 'id'=>$id,
        // 'is_suomotu'=>$is_suomotu,
        // 'hasCriminal'=>$hasCriminal
        // ];
        // dd($data);
        return view('appeals.printForms')->with($data);
    }

    public function showTableByProsecution(Request $request)
    {
        $form_id = $request->form_id;
        $id = (int) $request->id;
        $result = [];

        // Perform database query using Laravel's query builder or Eloquent
        // $prosecution = DB::table('prosecutions')
        //     ->select('id')
        //     ->where('id', $id)
        //     ->whereNotNull('orderSheet_id')
        //     ->where('delete_status', 1)
        //     ->orderBy('created_at', 'desc')
        //     ->first();
        $prosecution = Prosecution::where('id', $id)
            ->whereNotNull('orderSheet_id')
            ->where('delete_status', 1)
            ->orderBy('created_at', 'desc')
            ->get(['id']); // Fetch only the 'id' column

        if (count($prosecution) > 0) {
            // Call a service method to get additional data if needed
            $result = $this->getTableData($id);
            $order_form_type = $result["order_form_type"];

            if ($form_id == "4") { // Order Sheet
                $form_id .= $order_form_type;
            }
        } else {
            $form_id = 41;

            $result = [
                "table" => "",
                "order_form_type" => 1
            ];
        }

        // Return JSON response
        return response()->json([
            "form_number" => $form_id,
            "table" => $result["table"]
        ]);
    }
    public function details($id)
    {
        // Get the current authenticated user
        $magistrate = Auth::user();
        // Build the query
        $case_details = Prosecution::with('punishments')->select([
            'prosecutions.id as id',
            'prosecutions.subject as subject',
            'prosecutions.date as prosecution_date',
            'prosecutions.location as location',
            'prosecutions.case_no as case_no',
            'prosecutions.hints as hints',
            'prosecutions.is_suomotu as is_suomotu',
            'prosecutions.hasCriminal as hasCriminal',
            'prosecutions.prosecutor_name as prosecutor_name',
            // 'office.office_name_bn as office_name_bn',
        ])
            ->join('courts', 'courts.id', '=', 'prosecutions.court_id')
            // ->join('office', 'office.district_id', '=', 'prosecutions.zillaId')
            ->where('courts.magistrate_id', $magistrate->id)
            // ->where('office.level',4)
            ->orderBy('prosecutions.created_at', 'desc')->first();

        // dd( $case_details );
        $page_title = 'মামলার বিবরণ';
        return view('appeals.details', compact('case_details', 'page_title'));
    }

    // Prosecutor
    public function newProsecution(Request $request, $prosecution_Id = null, $magistrateid = "")
    {
        $userinfo = globalUserInfo();  // gobal user
        $office_id = globalUserInfo()->office_id;  // gobal user
        $officeinfo = DB::table('office')->where('id', $office_id)->first();
        $jurisdiction = DB::table('jurisdiction')->where('user_id', $userinfo->username)->first();
        $division_id = $officeinfo->division_id;
        $district_id = $officeinfo->district_id;
        // dd($jurisdiction);
        $results = User::join('doptor_user_access_info', 'users.common_login_user_id', '=', 'doptor_user_access_info.common_login_user_id')
            ->join('office', 'office.id', '=', 'users.office_id')
            ->select('users.name', 'users.id as user_id', 'users.username as username', 'office.office_name_bn', DB::raw('CONCAT(users.name, " - ", office.office_name_bn) as name_eng'),)
            ->where('doptor_user_access_info.role_id', 26)
            ->where('office.district_id', $district_id)
            ->where('office.division_id', $division_id)
            ->get();

        $megistatr_list_arr = [];
        foreach ($results as $key => $value) {
            $jurisdiction_user = DB::table('jurisdiction')->where('user_id', $value->username)->first();
            if (!empty($jurisdiction_user)) {
                $jurisdiction_mgs_upa = json_decode($jurisdiction_user->upa_id_arr);
                $jurisdiction_proc_upa = json_decode($jurisdiction->upa_id_arr);
                $common = array_intersect($jurisdiction_mgs_upa, $jurisdiction_proc_upa);

                if (!empty($common)) {
                    array_push($megistatr_list_arr, $value->username);
                }
            }
        }

        $permited_megistrate = [];
        foreach ($megistatr_list_arr as  $mgt) {
            $user_info = DB::table('users')->where('username', $mgt)->first();
            array_push($permited_megistrate, $user_info);
        }
        // dd($permited_megistrate);
        // dd($permited_megistrate);
        $data['district'] = DB::table('district')->where('division_id', $division_id)->get();
        $data['permited_megistrate'] = $permited_megistrate;
        $data['magistrate_list'] = $results;
        $data['district_id'] = $district_id;
        $data['case_type'] = DB::table('mc_law_type')->get();
        $data['law_id'] = DB::table('mc_law')->orderBy('title')->get();
        // $this->view->is_sizurelist = "1";
        // $_GET['is_sizurelist'] = "no";
        // $data['officeinfo'] = $officeinfo;
        $data['case_type'] = DB::table('mc_law_type')->get();
        $data['seizureitem_type'] = DB::table('seizureitem_type')->get();
        $data['prosecutorId'] = $userinfo->id;
        $data['jail'] = DB::table('mc_jail')->get();
        $data['division'] = DB::table('geo_divisions')
            ->select('id', 'division_name_bng')
            ->get();

        if ($prosecution_Id) {
            $data['prosecution_id'] = $prosecution_Id;
        } else {
            $data['prosecution_id'] = '';
        }
        if ($magistrateid) {
            $data['magistrate_id'] = $magistrateid;
        } else {
            $data['magistrate_id'] = '';
        }
        $prosecutioninfo = prosecution::find($prosecution_Id);
        if (!empty($prosecution_Id)) {
            $data['selectMagistrateCourtId'] = $prosecutioninfo->court_id ? $prosecutioninfo->court_id : "";
        } else {
            $data['selectMagistrateCourtId'] = '';
        }
        //  dd($data);
        return view('appeals.newProsecution')->with($data);
    }
    public   function generateCaseNo($appealId = null)
    {
        $userinfo = globalUserInfo();
        $court = DB::table('courts')->select('id as court_id', 'title')->where('magistrate_id', $userinfo->id)->where('status', 1)->first();


        // if ($court_details->level == 0) {
        //     $upazila_name_en = $appeal->upazila->upazila_name_en;
        //     $upazila_name_en_exploded = explode(' ', $appeal->upazila->upazila_name_en);
        //     if (!empty($upazila_name_en_exploded[1])) {
        //         $upazilla_name = $upazila_name_en_exploded[1];
        //     } else {
        //         $upazilla_name = $upazila_name_en;
        //     }

        //     $part_1 = strtoupper(substr($appeal->district->district_name_en, 0, 3)) . '-' . strtoupper(substr($upazilla_name, 0, 3)) . '-GCC';
        // } else {
        //     $part_1 = strtoupper(substr($appeal->district->district_name_en, 0, 3)) . '-GCC';
        // }
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

        return  $case_no;
    }

    public function getLawListByProsecutorId(Request $request)
    {

        $flag = "false";
        $childs = array();

        $prosecutorId = $request->post('prosecutorId');
        if (!$prosecutorId) {
            $prosecutorId = (int) $request->query('prosecutorId', 0);
        }

        $lawListForProsecutor = ProsecutorLawMappingRepository::getSelectedLawListByProsecutorId($prosecutorId);

        foreach ($lawListForProsecutor as $t) {

            $childs[] = array('id' => $t->id, 'name' => $t->name);
        }

        return  response()->json($childs, 200);
        // $response = new \Phalcon\Http\Response();
        // $response->setContentType('application/json', 'UTF-8');
        // $response->setContent(json_encode($childs));
        // return $response;
    }

    public function showProsecutionList()
    {
        $prosecutor = globalUserInfo();  // gobal user; // Assuming you're getting the prosecutor via the authenticated user

        $data['page_title'] = 'দায়েরকৃত অভিযোগ';
        $data['prosecutions'] = DB::table('prosecutions as prose')
            ->select([
                'prose.id as id',
                'prose.subject as subject',
                'prose.date as date',
                'prose.location as location',
                'prose.case_no as case_no',
                'magistrate.name',
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
            ->orderBy('prose.id', 'DESC')->paginate(10);

        return view('appeals.showProsecutionList')->with($data);
    }

    public function incompleteProsecution(Request $request)
    {
        // Set default page number
        $numberPage = 1;

        // Check if it's a POST request
        if ($request->isMethod('post')) {
            $parameters = $request->all();  // Retrieve POST data
            $query = Prosecution::query();  // Start a query with Prosecution model
        } else {
            // Get the page number from query parameters
            $numberPage = $request->query('page', 1);
        }

        // Default order parameters
        $parameters = [
            "order" => "date ASC"
        ];

        // Fetch prosecutor info (assuming you have an auth method)
        $prosecutor = Auth::user();

        // Build the query with necessary conditions and joins
        $prosecution = Prosecution::select(
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
            ->where('prosecutions.prosecutor_id', $prosecutor->id)
            ->where('prosecutions.is_suomotu', 0)
            ->where('prosecutions.hasCriminal', 1)
            ->where('prosecutions.is_approved', 0)
            ->where('prosecutions.case_status', '<', 6)
            ->orderBy('prosecutions.created_at', 'DESC')
            ->paginate(10, ['*'], 'page', $numberPage);  // Paginate results with 10 items per page

        // If no prosecution data found
        // if ($prosecution->isEmpty()) {
        //     return redirect()->route('home.index')->with('notice', 'তথ্য পাওয়া যায়নি');
        // }

        // Pass the paginated prosecution data to the view
        return view('appeals.incompleteProsecution', ['prosecutions' => $prosecution, 'page_title' => "অসম্পূর্ণ অভিযোগের তালিকা"]);
    }
    public function newProsecutionWithoutCriminal(Request $request, $prosecution_Id = null, $magistrateid = "")
    {
        $userinfo = globalUserInfo();  // gobal user
        $office_id = globalUserInfo()->office_id;  // gobal user
        $officeinfo = DB::table('office')->where('id', $office_id)->first();
        $division_id = $officeinfo->division_id;
        $district_id = $officeinfo->district_id;
        $jurisdiction = DB::table('jurisdiction')->where('user_id', $userinfo->username)->first();

        $results = User::join('doptor_user_access_info', 'users.common_login_user_id', '=', 'doptor_user_access_info.common_login_user_id')
            ->join('office', 'office.id', '=', 'users.office_id')
            ->select('users.name', 'users.id as user_id', 'users.username as username', 'office.office_name_bn', DB::raw('CONCAT(users.name, " - ", office.office_name_bn) as name_eng'),) // Adjust this as needed
            ->where('doptor_user_access_info.role_id', 26)
            ->where('office.district_id', $district_id)
            ->where('office.division_id', $division_id)
            ->get();

        $megistatr_list_arr = [];
        foreach ($results as $key => $value) {
            $jurisdiction_user = DB::table('jurisdiction')->where('user_id', $value->username)->first();
            // dd($value);
            if (!empty($jurisdiction_user)) {
                $jurisdiction_mgs_upa = json_decode($jurisdiction_user->upa_id_arr);
                $jurisdiction_proc_upa = json_decode($jurisdiction->upa_id_arr);
                $common = array_intersect($jurisdiction_mgs_upa, $jurisdiction_proc_upa);

                if (!empty($common)) {
                    array_push($megistatr_list_arr, $value->username);
                }
            }
        }

        $permited_megistrate = [];
        foreach ($megistatr_list_arr as  $mgt) {
            $user_info = DB::table('users')->where('username', $mgt)->first();
            array_push($permited_megistrate, $user_info);
        }

        $data['district_id'] = $district_id;
        $data['district'] = DB::table('district')->where('division_id', $division_id)->get();
        $data['magistrate_list'] = $results;
        $data['permited_megistrate'] = $permited_megistrate;
        // dd($data);
        $data['case_type'] = DB::table('mc_law_type')->get();
        $data['law_id'] = DB::table('mc_law')->orderBy('title')->get();
        // $this->view->is_sizurelist = "1";
        // $_GET['is_sizurelist'] = "no";
        // $data['officeinfo'] = $officeinfo;
        $data['case_type'] = DB::table('mc_law_type')->get();
        $data['seizureitem_type'] = DB::table('seizureitem_type')->get();
        $data['prosecutorId'] = $userinfo->id;
        $data['jail'] = DB::table('mc_jail')->get();
        $data['division'] = DB::table('geo_divisions')
            ->select('id', 'division_name_bng')
            ->get();
        if ($prosecution_Id) {
            $data['prosecution_id'] = $prosecution_Id;
        } else {
            $data['prosecution_id'] = '';
        }
        $magistrateid = $request->query('magistrateid');
        if ($magistrateid) {
            $data['magistrate_id'] = $magistrateid;
        } else {
            $data['magistrate_id'] = '';
        }
        $data['page_title'] = "অসম্পূর্ণ অভিযোগের তালিকা";
        $prosecutioninfo = prosecution::find($prosecution_Id);
        if (!empty($prosecution_Id)) {
            $data['selectMagistrateCourtId'] = $prosecutioninfo->court_id ? $prosecutioninfo->court_id : "";
        } else {
            $data['selectMagistrateCourtId'] = '';
        }

        return view('appeals.newProsecutionWithoutCriminal')->with($data);
    }

    public function incompleteProsecutionWithoutCriminal(Request $request)
    {
        // Set default page number
        $numberPage = 1;

        // Check if it's a POST request
        if ($request->isMethod('post')) {
            $parameters = $request->all();  // Retrieve POST data
            $query = Prosecution::query();  // Start a query with Prosecution model
        } else {
            // Get the page number from query parameters
            $numberPage = $request->query('page', 1);
        }

        // Default order parameters
        $parameters = [
            "order" => "date ASC"
        ];

        // Fetch prosecutor info (assuming you have an auth method)
        $prosecutor = Auth::user();

        // Build the query with necessary conditions and joins
        $prosecution = Prosecution::select(
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
            ->where('prosecutions.prosecutor_id', $prosecutor->id)
            ->where('prosecutions.is_suomotu', 0)
            ->where('prosecutions.hasCriminal', 0)
            ->where('prosecutions.is_approved', 0)
            ->where('prosecutions.case_status', '<', 6)
            ->orderBy('prosecutions.created_at', 'DESC')
            ->paginate(10, ['*'], 'page', $numberPage);  // Paginate results with 10 items per page



        // Pass the paginated prosecution data to the view
        return view('appeals.incompleteProsecutionWithoutCriminal', ['prosecutions' => $prosecution, 'page_title' => "অসম্পূর্ণ অভিযোগের তালিকা"]);
    }

    public function createsizedList(Request $request)
    {
        // Fetch prosecutor info (assuming you have an auth method)
        $prosecutor = Auth::user();

        $prosecution = Prosecution::select(
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
            ->orderBy('prosecutions.created_at', 'DESC')
            ->paginate(10);
        // Pass the paginated prosecution data to the view
        return view('appeals.createsizedList', ['prosecutions' => $prosecution, 'page_title' => "দায়েরকৃত অভিযোগ"]);
    }

    public function editSeizedList(Request $request, $id)
    {
        $prosecution = Prosecution::find($id);

        // Pass data to the view
        return view('appeals.editSeizedList', [
            'prosecution_id' => $prosecution->id,
            'is_suomotu'     => $prosecution->is_suomotu,
            'case_no'        => $prosecution->case_no,
            'seizureitem_type' => DB::table('seizureitem_type')->get(),
        ]);
    }


    public function showFormByProsecution(Request $request)
    {
        $caseInfo = [];
        $jailInfo = [];

        $prosecutionId = $request->prosecutionId;
        // return ['trest' => $prosecutionId];
        if ($prosecutionId) {
            $form_id = 0;
            $caseInfo = CaseRepository::getCaseInformationByProsecutionId($prosecutionId);
        } else {
            $form_id = $request->form_id;
            $id = $request->id; // prosecutionId
            $suomotu = $request->suomotu;
            $confession_type = "";
            $is_prose = 0;

            if ($form_id == "1") {
                $is_prose = 1;
            }

            $result = "";
            if ($form_id == "7") {
                $caseInformation = CaseRepository::getCaseInformationByProsecutionId($id);
                $order_form_type = 1;
            } else {
                $caseInformation = CaseRepository::getCaseInformationByProsecutionId($id);
                $jailInfo = PunishmentRepository::punishmentJailInfoCriminal($id);
                $order_form_type = 0;
                // return [$caseInformation, $jailInfo];
            }

            if ($form_id == "4") { // Order Sheet
                $form_id .= $order_form_type;
            }

            if ($form_id == "3") { // confession type
                $form_id = "30";
            }

            if ($form_id == "2") { // complain
                $form_id = "20"; // criminal confession not exist
            }

            $caseInfo = [
                "form_number" => $form_id,
                "caseInfo" => $caseInformation,
                "jailInfo" => $jailInfo
            ];
        }

        return response()->json($caseInfo, 200);
    }

    public function createComplain(Request $request)
    {
        $flag = "";
        $prosecutionInfo = $_POST;  //geting Form data
        $prosecutionId = $prosecutionInfo['prosecutionId'];
        //file save
        // $entityID = $prosecutionId;
        // $appName = 'Mobile';
        // $fileCategory = 'ChargeFame';
        // $fileCaption = 'No Caption';
        // $baseUrl = $this->imageUploadUri;

        // $parameter=array(
        //     "entityID"=>$entityID,
        //     "baseUrl"=>$baseUrl,
        //     "appName"=>$appName,
        //     "fileCategory"=>$fileCategory,
        //     "fileCaption"=>$fileCaption,
        // );

        $entityID = $request->prosecutionId;

        // if ($this->request->hasFiles() == true) {
        //     $this->fileContentService->fileSave($parameter);
        // }

        // if (!$this->request->isPost()) {
        //     return $this->dispatcher->forward(array(
        //         "controller" => "prosecution",
        //         "action" => "index"
        //     ));
        // }
        //--------------- Start File Upload  -------------------
        if ($request->has('files')) {
            $request->validate([
                'files.*.someName' => 'mimes:jpeg,png,jpg,pdf,doc,docx|max:5120',
            ]);

            $fileCategory = 'ChargeFame';

            // FileRepository::deleteExistingFiles($prosecutionId, $fileCategory);
            $prosecutionFiles = $request->file('files');

            foreach ($prosecutionFiles as $fileArray) {
                if (isset($fileArray['someName'])) {
                    $file = $fileArray['someName'];

                    $parameter = [
                        "entityID" => $prosecutionId,
                        "appName" => 'MobileCourt',
                        "fileCategory" => $fileCategory,
                        "fileCaption" => 'No Caption',
                    ];

                    if ($file) {
                        $newRequest = Request::create('', 'POST', $parameter);
                        $newRequest->files->set('someName', $file);

                        FileRepository::fileSaveForWeb($newRequest);
                    }
                }
            }
        }
        // ------------ End File Upload -------------

        $prosecutionInfo = $_POST;  //geting Form data
        $prosecutionId = $prosecutionInfo['prosecutionId'];

        $prosecution = ProsecutionRepository::approveProsecution($prosecutionId, $prosecutionInfo);
        if ($prosecution == false) {
            $msg["flag"] = "false";
            $msg["message"] = "এই মামলা নম্বর দিয়ে ইতিমধ্যেই একটি মামলা দাখিল হয়ছে।";

            return response()->json($msg, 200);
        }
        if (!$prosecution) {
            // foreach ($prosecution->getMessages() as $message) {
            //     $this->flash->error($message);
            // }
            // return $this->dispatcher->forward(array(
            //     "controller" => "prosecution",
            //     "action" => "searchComplain"
            // ));
        } else {
            $flag = "true";
        }

        $msg["flag"] = $flag;
        $msg["message"] = "অভিযোগ দায়ের সফলভাবে সম্পন্ন হয়েছে।";
        $msg["case_no"] = $prosecution->case_no;

        return response()->json($msg, 200);
    }
    public function getMagistrate(Request $request)
    {

        $userinfo = globalUserInfo();  // gobal user
        $office_id = globalUserInfo()->office_id;  // gobal user
        $officeinfo = DB::table('office')->where('id', $office_id)->first();
        $division_id = $officeinfo->division_id;
        $district_id = $officeinfo->district_id;

        $divid =  $division_id;

        $childs = [];
        $zillaId = $request->zillaid;

        // Query the magistrates and their job descriptions
        return $magistrates = DB::table('users as mag')
            ->select(
                'mag.id as id',
                DB::raw('CONCAT(mag.name, "-", of.office_name_bn) as name_eng'),
                // 'mag.national_id',
                'mag.mobile_no',
                'mag.email',
                // 'mag.name as name_eng'
            )
            ->join('office as of', 'of.id', '=', 'mag.office_id')
            ->where('of.district_id', $zillaId)
            // ->where('job_des.division_id', $divid)
            ->get();

        // Build the response array
        foreach ($magistrates as $t) {
            $childs[] = ['id' => $t->id, 'name_eng' => $t->name_eng];
        }
        // Return the response in JSON format
        return response()->json($childs, 200);
    }

    public function editCriminalConfession($prosecutionId)
    {

        return view('appeals.editCriminalConfession', ['prosecutionId' => $prosecutionId]);
    }


    public function editOrderSheet($prosecutionId)
    {
        $data['case_type'] = DB::table('mc_law_type')->get();
        $data['seizureitem_type'] = DB::table('seizureitem_type')->get();
        $data['jail'] = DB::table('mc_jail')->get();
        $data['prosecutionId'] = $prosecutionId;
        return view('appeals.editOrderSheet')->with($data);
    }

    public function suomotucourtWithoutCriminal(Request $request, $prosecutionId = null)
    {

        $userinfo = globalUserInfo();  // gobal user
        $date = date('Y-m-d'); // today date
        $magistrateId = $userinfo->id;
        // Remove session values
        $request->session()->forget(['prosecution_id', 'case_no']);

        $data['case_type'] = DB::table('mc_law_type')->get();
        $data['seizureitem_type'] = DB::table('seizureitem_type')->get();
        $data['jail'] = DB::table('mc_jail')->get();
        $data['mc_law'] = DB::table('mc_law')->orderBy('title')->get();
        // Set the prosecution ID
        $data['prosecution_id'] = $prosecutionId ?? '';
        $data['selectMagistrateCourtId'] = '';

        if ($magistrateId) {
            // Query to fetch court data
            $court = DB::table('courts')->where('magistrate_id', $magistrateId)
                ->whereDate('date', $date)
                ->where('status', 1)
                ->first();
            if (!$court) {
                // Flash message if no court data found
                session()->flash('notice', "আজকের দিনে কোন কর্মসূচী নাই ।");
                return redirect()->route('court.openclose');
            } else {
                // Set the court ID in the session
                $request->session()->put('court_id', $court->id);
            }
        } else {
            // Flash message if no magistrate ID
            session()->flash('error', "ম্যজিট্রেটের তথ্য পাওয়া যায়নি।");
        }

        //    return view('your_view_name', compact('lawList', 'seizureitemTypes', 'caseTypes', 'jailList', 'prosecutionId'));
        return view('appeals.suomotucourtWithoutCriminal')->with($data);
    }

    public function incompletecaseWithoutCriminal(Request $request)
    {
        $userinfo = globalUserInfo();  // gobal user
        // Set default page number
        $numberPage = $request->input('page', 1);

        // Handle search parameters on POST request
        if ($request->isMethod('post')) {
            // Laravel equivalent of Phalcon's Criteria::fromInput
            $parameters = $request->except(['_token', 'page']);
            session(['parameters' => $parameters]);
        } else {
            $parameters = session('parameters', []);
        }

        // Fetch the magistrate information
        $magistrate =  $userinfo->id;
        // Build the query using Eloquent
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

        // Execute the query and paginate
        $prosecutions = $query->paginate(10, ['*'], 'page', $numberPage);
        //    dd( $prosecutions);
        // If no prosecution records found, return to the home page with a message
        //  if ($prosecutions->isEmpty()) {
        //      return redirect()->route('home.index')->with('notice', 'তথ্য পাওয়া যায়নি');
        //  }

        // Pass the paginated data to the view
        return view('appeals.incompletecaseWithoutCriminal', compact('prosecutions'));
    }
    public function searchProsecutionWithoutCriminal(Request $request)
    {
        $userinfo = globalUserInfo();  // gobal user
        // Set default page number
        $numberPage = 1;
        // Handle POST request and parameters
        if ($request->isMethod('post')) {
            $parameters = $request->all();  // Get all POST parameters
            // You can further process the input data here as needed
        } else {
            // Handle GET request (pagination page number)
            $numberPage = $request->query('page', 1);
        }
        // Default query parameters (sorting by date ascending)
        $parameters = [
            'order' => 'date ASC'
        ];
        // Define conditions
        $conditions = ['is_approved' => 0];
        // Get the magistrate
        $magistrate = $userinfo->id;  // Assuming you have a logged-in magistrate
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
            ->orderBy('prosecutions.id', 'DESC')
            ->paginate(10, ['*'], 'page', $numberPage);

        // // Check if no prosecution records are found
        // if ($prosecutions->isEmpty()) {
        //     return redirect()->route('home.index')->with('notice', 'তথ্য পাওয়া যায়নি');
        // }

        //  dd('test');

        // Pass the paginated prosecution data to the view
        return view('appeals.searchProsecutionWithoutCriminal', [
            'prosecutions' => $prosecutions,
        ]);
    }

    public function getTableData($id)
    {
        // Default value for order_form_type
        $order_form_type = "1";

        // Query to get order_sheet data (adjust table/column names as necessary)
        $punishment_t = DB::table('order_sheets')
            ->select('order_header', 'order_details')
            ->where('prosecution_id', $id)
            ->orderBy('version')
            ->get();

        // Initialize an array to hold punishment data
        $punishment = [];

        // Process each record
        foreach ($punishment_t as $temp) {
            $punishment[] = $temp;
            $order_form_type = "1"; // Assign or modify order_form_type as necessary
        }

        // Return the results as an array
        return [
            "table" => $punishment,
            "order_form_type" => $order_form_type
        ];
    }

    public function caseTracker(Request $request)
    {


        //    return $request->all();
        return view('caseTracker.em_caseTracker');
    }

    public function searchProsecutionforDashboard(Request $request)
    {

        $magistrate = auth()->user(); // Assuming you are using Laravel's built-in Auth system

        /* Array of database columns */
        $aColumns = ['DT_RowId', 'cdate', 'case_no', 'prosecutor_name', 'pdate', 'location', 'subject', 'hints'];

        /* Indexed column */
        $sIndexColumn = "id";


        /*
         * Paging
         */
        $sLimit = '';
        if ($request->has('iDisplayStart') && $request->get('iDisplayLength') != '-1') {
            $sLimit = [$request->get('iDisplayStart'), $request->get('iDisplayLength')];
        }

        /*
         * Ordering
         */
        $sOrder = '';
        if ($request->has('iSortCol_0')) {
            $sOrder = [];
            for ($i = 0; $i < intval($request->get('iSortingCols')); $i++) {
                if ($request->get('bSortable_' . intval($request->get('iSortCol_' . $i))) == "true") {
                    $sOrder[] = [
                        $aColumns[intval($request->get('iSortCol_' . $i))],
                        $request->get('sSortDir_' . $i) === 'asc' ? 'asc' : 'desc'
                    ];
                }
            }
        }

        /*
         * Filtering
         */
        $sWhere = '';
        if ($request->has('sSearch') && $request->get('sSearch') != "") {
            $sWhere = function ($query) use ($aColumns, $request) {
                foreach ($aColumns as $col) {
                    $query->orWhere($col, 'LIKE', '%' . $request->get('sSearch') . '%');
                }
            };
        }

        /* Individual column filtering */
        for ($i = 0; $i < count($aColumns); $i++) {
            if ($request->has('bSearchable_' . $i) && $request->get('bSearchable_' . $i) == "true" && $request->get('sSearch_' . $i) != '') {
                if (empty($sWhere)) {
                    $sWhere = function ($query) use ($aColumns, $i, $request) {
                        $query->where($aColumns[$i], 'LIKE', '%' . $request->get('sSearch_' . $i) . '%');
                    };
                } else {
                    $sWhere = function ($query) use ($aColumns, $i, $request) {
                        $query->orWhere($aColumns[$i], 'LIKE', '%' . $request->get('sSearch_' . $i) . '%');
                    };
                }
            }
        }

        // Start and end dates for filtering
        $m = (int)date('n');
        $start_date = date('Y-m-d', mktime(1, 1, 1, $m, 1, date('Y')));
        $end_date = date('Y-m-d', mktime(1, 1, 1, $m + 1, 0, date('Y')));

        /*
         * SQL queries
         * Get data to display
         */
        // $query = DB::table('prosecutions as prose')
        //     ->selectRaw('prose.id as DT_RowId, prose.case_no, prose.created_at as cdate, prose.prosecutor_name, prose.location, prose.subject, prose.date as pdate, GROUP_CONCAT(lb.Description, " ও ") as hints')
        //     ->join('courts as court', 'court.id', '=', 'prose.court_id')
        //     ->join('users as magistrate', 'magistrate.id', '=', 'court.magistrate_id')
        //     ->join('laws_brokens as lb', 'lb.prosecution_id', '=', 'prose.id')
        //     ->where('prose.delete_status', '1')
        //     ->where('magistrate.id', $magistrate->id)
        //     ->where('is_approved', '!=', 1)
        //     ->groupBy('prose.id');

        $query = DB::table('prosecutions as prose')
            ->select(
                'prose.id as DT_RowId',
                'prose.case_no',
                'prose.created_at as cdate',
                'prose.prosecutor_name',
                'prose.location',
                'prose.subject',
                'prose.date as pdate',
                DB::raw('GROUP_CONCAT(lb.Description SEPARATOR " ও ") as hints')
            )
            ->leftJoin('courts as court', 'court.id', '=', 'prose.court_id')
            ->leftJoin('users as magistrate', 'magistrate.id', '=', 'court.magistrate_id')
            ->leftJoin('laws_brokens as lb', 'lb.prosecution_id', '=', 'prose.id')
            ->where('prose.delete_status', 1)
            ->where('magistrate.id', $magistrate->id)
            ->where('prose.is_approved', '!=', 1)
            ->groupBy('prose.id');


        if ($sWhere) {
            $query->where($sWhere);
        }

        if (!empty($sOrder)) {
            foreach ($sOrder as $order) {
                $query->orderBy($order[0], $order[1]);
            }
        }

        if (!empty($sLimit)) {
            $query->skip($sLimit[0])->take($sLimit[1]);
        }

        $rResult = $query->get();

        /* Total data set length */
        $iTotal = DB::table('prosecutions as prose')
            ->join('courts as court', 'court.id', '=', 'prose.court_id')
            ->join('users as magistrate', 'magistrate.id', '=', 'court.magistrate_id')
            ->where('prose.delete_status', '1')
            ->where('magistrate.id', $magistrate->id)
            ->where('is_approved', '!=', 1)
            ->count();

        /*
         * Output
         */
        $output = [
            "draw" => intval($request->get('sEcho')),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iTotal,
            "data" => []
        ];

        foreach ($rResult as $emp) {
            $row = [];
            $row[0] = "<img src='" . asset('/mobile_court/images/details_open.png') . "'>";
            for ($i = 1; $i < count($aColumns); $i++) {
                $row[$i] = $emp->{$aColumns[$i]} ?? null;
            }
            $output['data'][] = $row;
        }

        return response()->json($output);
    }

    public function searchCitizenComplinforDashboard(Request $request)
    {

        $magistrate = Auth::user();
        $aColumns = ['DT_RowId', 'user_idno', 'esdate', 'name_bng', 'location', 'cdate', 'subject', 'complain_details'];

        // Limit & Offset
        $limit = intval($request->get('iDisplayLength', 10));
        $offset = intval($request->get('iDisplayStart', 0));

        // Ordering
        $orderByColumn = $aColumns[intval($request->get('iSortCol_0', 0))];
        $orderByDirection = $request->get('sSortDir_0', 'asc');

        // Date range for the current month
        $start_date = date('Y-m-d', mktime(1, 1, 1, date('n'), 1, date('Y')));
        $end_date = date('Y-m-d', mktime(1, 1, 1, date('n') + 1, 0, date('Y')));

        // Query setup
        $query = DB::table('requisitions as req1')
            ->join('citizen_complains as ctz_cmp', 'ctz_cmp.id', '=', 'req1.complain_id')
            ->select('req1.id as DT_RowId', 'ctz_cmp.user_idno', 'ctz_cmp.complain_date as cdate', 'ctz_cmp.name_bng', 'ctz_cmp.location', 'req1.estimated_date as esdate', 'ctz_cmp.subject', 'ctz_cmp.complain_details')
            ->where('req1.magistrate_own_id', $magistrate->id)
            ->where('req1.complain_type_id', 1)
            ->whereNotIn('req1.status_own', ['solved', 're-send']);

        // Global search
        if ($request->has('sSearch') && !empty($request->get('sSearch'))) {
            $search = $request->get('sSearch');
            $query->where(function ($q) use ($aColumns, $search) {
                foreach ($aColumns as $column) {
                    $q->orWhere($column, 'like', '%' . $search . '%');
                }
            });
        }

        // Individual column filtering
        foreach ($aColumns as $i => $column) {
            if ($request->get('bSearchable_' . $i) == "true" && !empty($request->get('sSearch_' . $i))) {
                $query->where($column, 'like', '%' . $request->get('sSearch_' . $i) . '%');
            }
        }

        // Get paginated results
        $results = $query->orderBy($orderByColumn, $orderByDirection)
            ->skip($offset)
            ->take($limit)
            ->get();

        // Count total records
        $total = DB::table('requisitions as req')
            ->join('citizen_complains as citz', 'citz.id', '=', 'req.complain_id')
            ->where('req.magistrate_own_id', $magistrate->id)
            ->whereBetween(DB::raw('DATE(req.created_date)'), [$start_date, $end_date])
            ->count();

        // Format output for DataTables
        $output = [
            "sEcho" => intval($request->get('sEcho')),
            "iTotalRecords" => $total,
            "iTotalDisplayRecords" => $total,
            "data" => [],
        ];

        foreach ($results as $emp) {
            $row = [];
            $row[] = "<img src='" . asset('images/details_open.png') . "'>";
            foreach ($aColumns as $column) {
                if ($column != ' ') {
                    $row[] = $emp->$column;
                }
            }
            $output['data'][] = $row;
        }

        // If no data, add empty row
        if (empty($output['data'])) {
            $output['data'][] = [
                "0" => '',
                "1" => '',
                "2" => '',
                "3" => '',
                "4" => '',
                "5" => '',
                "6" => ''
            ];
        }
        return response()->json($output);
    }
    public function getDataForTracker(Request $request)
    {

        $case_no = $request->input("case_no");
        $complain_no = $request->input("complain_no");
        $magistrate = auth()->user(); // Assuming you have a method to fetch the magistrate info.
        
        $output = [
            'data' => [[
                "0" => "", // magistrate name
                "1" => "", // case no
                "2" => "", // complain no
                "3" => "", // prosecution date
                "4" => "", // law section
                "5" => "", // punishment
                "6" => "", // status
                "7" => 'nothi' // nothi
            ]]
        ];

        $flag = true;

        if ($case_no  || $complain_no) {

            $sLimit = "";
            if ($request->has('iDisplayStart') && $request->input('iDisplayLength') != '-1') {
                $sLimit = "LIMIT " . intval($request->input('iDisplayStart')) . ", " . intval($request->input('iDisplayLength'));
            }

            $iTotal = 1;
            $output = [
                "draw" => intval($request->input('sEcho')),
                "iTotalRecords" => $iTotal,
                "iTotalDisplayRecords" => $iTotal,
                "data" => []
            ];

            if ($case_no) {
                $rResult = DB::table('prosecutions as prosec')
                    ->join('courts as court', 'court.id', '=', 'prosec.court_id')
                    ->join('users as mag', 'mag.id', '=', 'court.magistrate_id')
                    ->select(
                        'prosec.id as prosecution_id',
                        'prosec.orderSheet_id',
                        'mag.name as magistrate_name',
                        'prosec.case_no',
                        'prosec.date as pdate',
                        'prosec.user_idno as complain_no',
                        'prosec.case_status',
                        'prosec.is_attached',
                        'prosec.hasCriminal'
                    )
                    ->where('prosec.delete_status', '1')
                    ->where('prosec.case_no', $case_no)
                    ->where('court.magistrate_id', $magistrate->id)
                    ->get();



                if (count($rResult) > 0) {
                    $rResult = $rResult[0];
                    $row = [
                        "0" => $rResult->magistrate_name,
                        "1" => $rResult->case_no,
                        "2" => $rResult->complain_no,
                        "3" => $rResult->pdate,
                        "4" => "", // law section
                        "5" => "", // punishment
                        "6" => $rResult->case_status,
                        "7" => 'nothi'
                    ];

                    if ($rResult->orderSheet_id == null) {
                        $status = 'অনিস্পন্ন';
                        $row["6"] = $status;
                    } else {
                        $status = 'নিস্পন্ন';
                        if ($rResult->hasCriminal == 1) {
                            $punishResult = DB::table('punishments')
                                ->join('laws_brokens as lb', 'lb.prosecution_id', '=', 'punishments.prosecution_id')
                                ->select('lb.Description as law_section', 'punishments.order_detail as punishment')
                                ->where('punishments.prosecution_id', $rResult->prosecution_id)
                                ->first();

                            $row["4"] = json_decode($punishResult->law_section) ?: $punishResult->law_section;
                            $row["5"] = $punishResult->punishment;
                        } else {
                            $row["4"] = ''; // law section
                            $row["5"] = ''; // punishment
                        }

                        $row["6"] = $status;
                    }

                    $output['data'][] = $row;
                } else {
                    $flag = false;
                }
            } elseif ($complain_no) {
                $rResult = DB::table('court_complain_infos as cci')
                    ->join('users as mag', 'mag.id', '=', 'cci.magistrate_id')
                    ->select(
                        'cci.user_idno as complain_no',
                        'cci.complain_status',
                        'mag.name as magistrate_name',
                        'cci.prosecution_id',
                        'cci.case_number as case_no',
                        'cci.case_status',
                        'cci.prosecution_date'
                    )
                    ->where('cci.user_idno', $complain_no)
                    ->where('mag.id', $magistrate->id)
                    ->get();

                if ($rResult->isNotEmpty()) {
                    $rResult = $rResult[0];
                    $row = [
                        "0" => $rResult->magistrate_name,
                        "1" => $rResult->case_no,
                        "2" => $rResult->complain_no,
                        "3" => $rResult->prosecution_date,
                        "4" => "", // law section
                        "5" => "", // punishment
                        "6" => "", // status
                        "7" => 'nothi'
                    ];

                    $status = '';
                    if ($rResult->complain_status == 'initial') {
                        $status = 'কার্যক্রম গ্রহন হয়নি';
                    } elseif ($rResult->complain_status == 'ignore') {
                        $status = 'বাতিল';
                    } elseif ($rResult->case_status == 'processing') {
                        $status = 'অনিস্পন্ন';
                    } elseif ($rResult->case_status == 'solved') {
                        $status = 'নিস্পন্ন';
                        $punishResult = DB::table('punishments')
                            ->join('laws_brokens as lb', 'lb.prosecution_id', '=', 'punishments.prosecution_id')
                            ->select('lb.Description as law_section', 'punishment.order_detail as punishment')
                            ->where('punishments.prosecution_id', $rResult->prosecution_id)
                            ->first();

                        $row["4"] = json_decode($punishResult->law_section) ?: $punishResult->law_section;
                        $row["5"] = $punishResult->punishment;
                    } elseif ($rResult->complain_status == 'accepted') {
                        $status = 'মামলা হয়নি';
                    }

                    $row["6"] = $status;
                    $output['data'][] = $row;
                }
            } else {
                $flag = false;
            }

            if (!$flag) {
                $output = [
                    "draw" => intval($request->input('sEcho')),
                    "iTotalRecords" => 0,
                    "iTotalDisplayRecords" => 0,
                    "data" => []
                ];
            }
        } else {


            $sLimit = "";
            if ($request->has('iDisplayStart') && $request->input('iDisplayLength') != '-1') {
                $sLimit = "LIMIT " . intval($request->input('iDisplayStart')) . ", " . intval($request->input('iDisplayLength'));
            }

            $iTotal = 1;
            $output = [
                "draw" => intval($request->input('sEcho')),
                "iTotalRecords" => $iTotal,
                "iTotalDisplayRecords" => $iTotal,
                "data" => []
            ];


            $rResult = DB::table('prosecutions as prosec')
                ->join('courts as court', 'court.id', '=', 'prosec.court_id')
                ->join('users as mag', 'mag.id', '=', 'court.magistrate_id')
                ->select(
                    'prosec.id as prosecution_id',
                    'prosec.orderSheet_id',
                    'mag.name as magistrate_name',
                    'prosec.case_no',
                    'prosec.date as pdate',
                    'prosec.user_idno as complain_no',
                    'prosec.case_status',
                    'prosec.is_attached',
                    'prosec.hasCriminal'
                )
                ->where('prosec.delete_status', '1');

            // Add condition if $case_no is not empty
            if (!empty($case_no)) {
                $rResult->where('prosec.case_no', $case_no);
            }

            $rResult->where('court.magistrate_id', $magistrate->id);

            // Execute the query to get the results
            $rResult = $rResult->get();



            if (count($rResult) > 0) {
                $rResult = $rResult[0];
                $row = [
                    "0" => $rResult->magistrate_name,
                    "1" => $rResult->case_no,
                    "2" => $rResult->complain_no,
                    "3" => $rResult->pdate,
                    "4" => "", // law section
                    "5" => "", // punishment
                    "6" => $rResult->case_status,
                    "7" => 'nothi'
                ];

                if ($rResult->orderSheet_id == null) {
                    $status = 'অনিস্পন্ন';
                    $row["6"] = $status;
                } else {
                    $status = 'নিস্পন্ন';
                    if ($rResult->hasCriminal == 1) {
                        $punishResult = DB::table('punishments')
                            ->join('laws_brokens as lb', 'lb.prosecution_id', '=', 'punishments.prosecution_id')
                            ->select('lb.Description as law_section', 'punishments.order_detail as punishment')
                            ->where('punishments.prosecution_id', $rResult->prosecution_id)
                            ->first();

                        $row["4"] = json_decode($punishResult->law_section) ?: $punishResult->law_section;
                        $row["5"] = $punishResult->punishment;
                    } else {
                        $row["4"] = ''; // law section
                        $row["5"] = ''; // punishment
                    }

                    $row["6"] = $status;
                }

                $output['data'][] = $row;
            } else {
                $flag = false;
            }
        }

        return response()->json($output);
    }
    public function  criminalTracker(Request $request)
    {
        // Fetching all divisions
        $divisions = DB::table('division')->get();

        // Initializing empty arrays for zilla and upazila
        $zilla = [];
        $upazila = [];

        // Passing data to the view
        return view('caseTracker.criminalTracker', compact('divisions', 'zilla', 'upazila'));
    }

    public function getDataForCriminalTracker(Request $request)
    {

        $divid = $request->input('division');
        $zillaid = $request->input('zilla');
        $upazillaid = $request->input('upazila');
        $name_bng = $request->input('name_bng');
        $mobile = $request->input('mobile');

        $whereStr = "";
        $flag = true;
        $row = array();
        $output = array();
        $row = array(
            "0" => "", // magistrate name
            "1" => "", // case no
            "2" => "", // compalin no
            "3" => "", // prosecution date
            "4" => "",
            "5" => "", // punishment
            "6" => ""
        ); // nothi); // nothi
        $output['data'][] = $row;
        $iTotal = 1;



        if ($mobile || $name_bng) {
            $columns = ['name_bng', 'case_no', 'pdate', 'law_section', 'crime_description', 'punishment'];
            $output = [
                "draw" => intval($request->input('sEcho')),
                "iTotalRecords" => $iTotal,
                "iTotalDisplayRecords" => $iTotal,
                "data" => []
            ];
            // Set limit for pagination
            $sLimit = "";
            if ($request->has('iDisplayStart') && $request->input('iDisplayLength') != '-1') {
                $sLimit = "" . intval($request->input('iDisplayStart')) . ", " . intval($request->input('iDisplayLength'));
            }

            // Query by name_bng
            if ($name_bng) {
                $query = DB::table('criminals as crm')
                    ->select(
                        'crm.name_bng',
                        'pro.case_no',
                        'pro.date as pdate',
                        'pun.order_detail as punishment',
                        DB::raw("CONCAT(law.title, ' এর ', sec.sec_number, ' ধারায় ') as law_section"),
                        'lb.Description as crime_description'
                    )
                    ->join('prosecution_details as PrD', 'PrD.criminal_id', '=', 'crm.id')
                    ->join('prosecutions as pro', 'pro.id', '=', 'PrD.prosecution_id')
                    ->join('punishments as pun', 'pun.prosecution_id', '=', 'pro.id')
                    ->join('laws_brokens as lb', 'lb.prosecution_id', '=', 'pro.id')
                    ->join('mc_section as sec', function ($join) {
                        $join->on('sec.law_id', '=', 'lb.law_id')
                            ->on('sec.id', '=', 'lb.section_id');
                    })
                    ->join('mc_law as law', 'law.id', '=', 'lb.law_id')
                    ->where('crm.name_bng', '=', $name_bng)
                    ->where('crm.divid', '=', $divid)
                    ->where('crm.zillaid', '=', $zillaid)
                    ->limit(10)
                    ->get();

                foreach ($query as $criminal) {
                    $row = [];
                    foreach ($columns as $i => $column) {
                        if ($column != ' ') {
                            // if ($i == 4) {
                            //     $data = json_decode($criminal->$column);
                            //     $row[] = $data;
                            // } else {
                            $row[] = $criminal->$column;
                            // }
                        }
                    }
                    $output['data'][] = $row;
                }
            }

            // Query by mobile
            if ($mobile) {
                $query = DB::table('criminals as crm')
                    ->select(
                        'crm.name_bng',
                        'pro.case_no',
                        'pro.date as pdate',
                        'pun.order_detail as punishment',
                        DB::raw("CONCAT(law.title, ' এর ', sec.sec_number, ' ধারায় ') as law_section"),
                        'lb.Description as crime_description'
                    )
                    ->join('prosecution_details as PrD', 'PrD.criminal_id', '=', 'crm.id')
                    ->join('prosecutions as pro', 'pro.id', '=', 'PrD.prosecution_id')
                    ->join('punishments as pun', 'pun.prosecution_id', '=', 'pro.id')
                    ->join('laws_brokens as lb', 'lb.prosecution_id', '=', 'pro.id')
                    ->join('mc_section as sec', function ($join) {
                        $join->on('sec.law_id', '=', 'lb.law_id')
                            ->on('sec.id', '=', 'lb.section_id');
                    })
                    ->join('mc_law as law', 'law.id', '=', 'lb.law_id')
                    ->where('crm.mobile_no', '=', $mobile)
                    ->limit(10)
                    ->get();

                foreach ($query as $criminal) {
                    $row = [];

                    foreach ($columns as $i => $column) {

                        if ($column != ' ') {
                            // if ($i == 4) {
                            //     $data = json_decode($criminal->$column);
                            //     $row[] = $data;
                            // } else {
                            $row[] = $criminal->$column;
                            // }
                            // print_r($row);
                        }
                    }
                    $output['data'][] = $row;
                }
            }
        }

        return response()->json($output);
    }

    public function adm_caseTracker(Request $request)
    {
        // $userLocation = $this->auth->getUserLocation();
        // $divid = $userLocation['divid'];
        // $zillaId = $userLocation['zillaid'];

        $office_id = globalUserInfo()->office_id;
        $roleID = globalUserInfo()->role_id;
        $officeinfo = DB::table('office')->where('id', $office_id)->first();
        $divid = $officeinfo->division_id;
        $zillaId = $officeinfo->district_id;
        // Query to select magistrates with job descriptions
        $data['magistrate'] = DB::table('users as mag')
            // ->join('users as mag', 'mag.id', '=', 'p.magistrate_id')
            ->join('doptor_user_access_info as dp', 'dp.user_id', '=', 'mag.id')
            ->join('mc_role as r', 'r.id', '=', 'dp.role_id')
            ->join('office  as of', 'of.id', '=', 'mag.office_id')
            ->leftJoin('district as z', 'z.id', '=', 'of.district_id')
            ->select([
                'mag.id as id',
                'mag.name  as name_eng',
                // 'mag.national_id',
                DB::raw('CONCAT(mag.name, "-", of.office_name_bn) as name_eng'),
                'mag.mobile_no as mobile',
                'mag.email'
            ])
            ->where('dp.role_id', 26)
            ->where('of.district_id', $zillaId)
            ->where('of.division_id', $divid)
            ->get();

        // $magistrate = DB::table('magistrates as mag')
        //     ->join('job_descriptions as job_des', 'job_des.magistrate_id', '=', 'mag.id')
        //     ->select(
        //         'mag.id as id',
        //         DB::raw('CONCAT(mag.name_bng, "-", mag.designation_bng, "-", job_des.location_str, "-", job_des.location_details) as name_eng'),
        //         'mag.national_id',
        //         'mag.mobile',
        //         'mag.email'
        //     )
        //     ->where('job_des.zillaid', $zillaId)
        //     ->where('job_des.divid', $divid)
        //     ->where('job_des.is_active', 1)
        //     ->where('job_des.user_type_id', 6)
        //     ->get();

        // Pass the data to the view
        // $this->view->magistrate = $magistrate;
        return view('caseTracker.adm_caseTracker', $data);
    }

    public function adm_getDataForTracker(Request $request)
    {
        //    return $request->all();
        $magistrate_id = $request->magistrate;
        $case_no = $request->case_no;
        $complain_no = $request->complain_no;

        $start_date = $request->start_date;
        $end_date = $request->end_date;



        $whereStr = "";
        $flag = true;
        $row = array();
        $output = array();
        $row = array(
            "0" => "", // magistrate name
            "1" => "", // case no
            "2" => "", // compalin no
            "3" => "", // prosecution date
            "4" => "", // law section
            "5" => "", // punishment
            "6" => "", //  status
            "7" => 'nothi'
        ); // nothi

        $output['data'][] = $row;

        if ($magistrate_id || $case_no || $complain_no) {
            $aColumns = array('magistrate_name', 'case_no', 'complain_no', 'pdate', 'law_section', 'punishment', 'last_status', 'nothi');

            $sLimit = "";
            if (isset($request->iDisplayStart) && $request->iDisplayLength != '-1') {

                // $sLimit = "LIMIT " . intval($request->iDisplayStart) . ", " . intval($request->iDisplayLength);
                $sLimit = intval($request->iDisplayLength);
            }

            $iTotal = 1;
            $output = array(
                "draw" => intval($request->sEcho),
                "iTotalRecords" => $iTotal,
                "iTotalDisplayRecords" => $iTotal,
                "data" => array()
            );

            $whereStr_cci = "";
            $whereStr_p = "";

            if ($magistrate_id) {
                $total_row = 16;

                if ($start_date && $start_date) {
                    $whereStr_cci = "DATE(court_complain_infos.prosecution_date)  BETWEEN  \"$start_date\"  AND  \"$end_date\"";
                    $whereStr_p = "DATE(prosecutions.date)  BETWEEN  \"$start_date\"  AND  \"$end_date\"";
                }

                // get list for all data from prosecution details . it has partially data from requisition and all data from prosecution
                // get list of all requisition ids from prosecution details table .
                // get list of data from CCI  table which are not in prosecution details table

                // $phql = "
                // SELECT PROD.requisition_id AS requisition_id
                // FROM  Mcms\Models\Prosecution AS PROD
                // WHERE PROD.magistrate_id = '" . $magistrate_id . "' AND  PROD.requisition_id is NOT NULL
                // ";

                // $query = $this->modelsManager->createQuery($phql);
                // $rows = $query->execute();


                $rows = Prosecution::where('magistrate_id', $magistrate_id)
                    ->whereNotNull('requisition_id')
                    ->select('requisition_id')
                    ->get();

                $ids = '';
                foreach ($rows as $row) {

                    if ($ids == '') {
                        $ids .= $row->requisition_id;
                    } else {
                        $ids .= "," . $row->requisition_id;
                    }
                }


                $condition = "";

                if ($ids) {
                    $condition = "court_complain_infos.requisition_id NOT IN (" . $ids . ")";
                }

                // $extentedquery = "";
                // $extentedquery = "
                //                     SELECT
                //                     cci.user_idno as complain_no,
                //                     cci.complain_status as complain_status,
                //                     mag.name_eng as magistrate_name,
                //                     cci.prosecution_id as prosecution_id,
                //                     cci.case_number as case_no,
                //                     '' as orderSheet_id,
                //                     cci.case_status as case_status,
                //                     cci.prosecution_date as pdate
                //                     FROM Mcms\Models\CourtComplainInfo  AS cci
                //                     INNER JOIN  Mcms\Models\Magistrate AS mag  ON mag.id = cci.magistrate_id
                //                     where cci.magistrate_id = '" . $magistrate_id . "' $whereStr_cci  $condition  $sLimit
                //                     ";
                // $query = $this->modelsManager->createQuery($extentedquery);
                // $rResult1 = $query->execute();

                $query = CourtComplainInfo::select(
                    'court_complain_infos.user_idno as complain_no',
                    'court_complain_infos.complain_status as complain_status',
                    'magistrates.name  as magistrate_name',
                    'court_complain_infos.prosecution_id as prosecution_id',
                    'court_complain_infos.case_number as case_no',
                    DB::raw("'' as orderSheet_id"),
                    'court_complain_infos.case_status as case_status',
                    'court_complain_infos.prosecution_date as pdate'
                )
                    ->join('users As magistrates', 'magistrates.id', '=', 'court_complain_infos.magistrate_id')
                    ->where('court_complain_infos.magistrate_id', $magistrate_id);

                // Apply additional conditions if required
                if (!empty($whereStr_cci)) {
                    $query->whereRaw($whereStr_cci);
                }

                if (!empty($condition)) {
                    $query->whereRaw($condition);
                }

                // if (!empty($sLimit)) {
                $query->limit($sLimit);
                // }
                $rResult1 = $query->get();

                $total_row = count($rResult1);
                foreach ($rResult1 as $tempresult1) {
                    //                    $total_row++;
                    $row = array(
                        "0" => $tempresult1->magistrate_name, // magistrate name
                        "1" => $tempresult1->case_no, // case no
                        "2" => $tempresult1->complain_no, // compalin no
                        "3" => $tempresult1->pdate, // prosecution date
                        "4" => "", // law section
                        "5" => "", // punishment
                        "7" => 'nothi'
                    ); // nothi

                    if ($tempresult1->complain_status == "accepted") {
                        $status = "মামলা হয়নি";
                        $row["6"] = $status;
                    }

                    $output['data'][] = $row;
                }


                // $query_count = "
                //                     SELECT
                //                     count(DISTINCT prosec.id) as count
                //                     FROM Mcms\Models\Prosecution AS prosec
                //                     INNER JOIN Mcms\Models\Court AS court  ON court.id = prosec.court_id
                //                     INNER JOIN Mcms\Models\Magistrate AS mag  ON mag.id = court.magistrate_id
                //                     WHERE prosec.delete_status = '1' AND  court.magistrate_id = '" . $magistrate_id . "' $whereStr_p";

                // $query_count = $this->modelsManager->createQuery($query_count);
                // $count_row = $query_count->execute();

                // Count distinct prosecution IDs
                // $count_row = Prosecution::join('courts as court', 'court.id', '=', 'prosecutions.court_id')
                // ->join('users as mag', 'mag.id', '=', 'court.magistrate_id')
                // ->where('prosecutions.delete_status', '1')
                // ->where('court.magistrate_id', $magistrate_id);
                // // ->when(!empty($whereStr_p), function($query) use ($whereStr_p) {
                // //     return $query->whereRaw($whereStr_p);
                // // })
                // if(!empty($whereStr_p)){
                // $query->whereRaw($whereStr_p);
                // }
                // $query->distinct()
                // ->count('prosecutions.id'); // Count distinct prosecution IDs

                $count_row1 = DB::table('prosecutions AS prosecutions')
                    ->join('courts AS court', 'court.id', '=', 'prosecutions.court_id')
                    ->join('users AS mag', 'mag.id', '=', 'court.magistrate_id')
                    ->where('prosecutions.delete_status', '1')
                    ->where('court.magistrate_id', $magistrate_id);
                if (!empty($whereStr_p)) {
                    $count_row1->whereRaw($whereStr_p); // assuming $whereStr_p contains additional conditions
                }
                $count_row = $count_row1->distinct()
                    ->count('prosecutions.id');


                // $sQuery = "
                //                     SELECT
                //                      prosec.user_idno as complain_no,
                //                      ''  as complain_status,
                //                      mag.name_eng as magistrate_name,
                //                      prosec.id as prosecution_id,
                //                      prosec.case_no as case_no,
                //                      prosec.orderSheet_id as orderSheet_id,
                //                      prosec.case_status as case_status,
                //                      prosec.date as pdate,
                //                      prosec.orderSheet_id as ordersheetId
                //                     FROM Mcms\Models\Prosecution AS prosec
                //                     INNER JOIN Mcms\Models\Court AS court  ON court.id = prosec.court_id
                //                     INNER JOIN Mcms\Models\Magistrate AS mag  ON mag.id = court.magistrate_id
                //                     WHERE prosec.delete_status = '1' AND  court.magistrate_id = '" . $magistrate_id . "' $whereStr_p  GROUP BY prosec.id $sLimit ";


                // $query = $this->modelsManager->createQuery($sQuery);
                // $rResult2 = $query->execute();
                $rResult2 = Prosecution::select([
                    'prosecutions.user_idno as complain_no',
                    DB::raw("'' as complain_status"),
                    'mag.name as magistrate_name',
                    'prosecutions.id as prosecution_id',
                    'prosecutions.case_no as case_no',
                    'prosecutions.orderSheet_id as orderSheet_id',
                    'prosecutions.case_status as case_status',
                    'prosecutions.date as pdate',
                    'prosecutions.orderSheet_id as ordersheetId'
                ])
                    ->join('courts as court', 'court.id', '=', 'prosecutions.court_id')
                    ->join('users as mag', 'mag.id', '=', 'court.magistrate_id')
                    ->where('prosecutions.delete_status', '1')
                    ->where('court.magistrate_id', $magistrate_id)
                    ->when(!empty($whereStr_p), function ($query) use ($whereStr_p) {
                        return $query->whereRaw($whereStr_p);
                    })
                    ->groupBy('prosecutions.id')
                    ->limit($sLimit) // Add the limit condition
                    ->get(); // Execute the query and get the results
                // dd( $rResult2 );
                if (count($rResult2) > 0) {
                    foreach ($rResult2 as $tempresult) {
                        // $total_row++;
                        $row = array(
                            "0" => $tempresult->magistrate_name, // magistrate name
                            "1" => $tempresult->case_no, // case no
                            "2" => $tempresult->complain_no, // compalin no
                            "3" => $tempresult->pdate, // prosecution date
                            "4" => "", // law section
                            "5" => "", // punishment
                            "6" => $tempresult->case_status, //  status
                            "7" => 'nothi'
                        ); // nothi

                        if ($tempresult->ordersheetId == null) {
                            $status = 'অনিস্পন্ন';
                            $row["6"] = $status;
                        } elseif ($tempresult->case_status != null) {
                            $status = 'নিস্পন্ন';
                            // get punishment info
                            // $sQuery = "
                            //         SELECT   lb.Description as law_section,
                            //         punishment.order_detail as punishment
                            //         FROM Mcms\Models\Punishment AS punishment
                            //         INNER JOIN  Mcms\Models\LawsBroken AS lb   ON lb.ProsecutionID = punishment.prosecution_id
                            //         WHERE punishment.prosecution_id = '" . $tempresult->prosecution_id . "'
                            // ";
                            // //echo $sQuery;
                            // $query = $this->modelsManager->createQuery($sQuery);
                            // $punishResult = $query->execute();

                            $punishResult = Punishment::select([
                                'lb.Description as law_section',
                                'punishments.order_detail as punishment'
                            ])
                                ->join('laws_brokens as lb', 'lb.prosecution_id', '=', 'punishments.prosecution_id')
                                ->where('punishments.prosecution_id', $tempresult->prosecution_id)
                                ->get(); // Execute the query and get the results

                            $row["0"] = $tempresult->magistrate_name; // magistrate name
                            $row["1"] = $tempresult->case_no; // case no
                            $row["3"] = $tempresult->pdate;  // prosecution date
                            // $row["4"] = $this->utilityService->jsonValidator($punishResult[0]["law_section"])?json_decode($punishResult[0]["law_section"]):$punishResult[0]["law_section"]; // law section
                            $row["4"] = $this->jsonValidator($punishResult[0]->law_section) ? json_decode($punishResult[0]->law_section) : $punishResult[0]->law_section; // law section
                            $row["5"] = $punishResult[0]->punishment;
                            // punishment
                            $row["6"] = $status;
                        }
                        $output['data'][] = $row;
                    }

                    // $output['iTotalRecords'] = $total_row + $count_row[0]['count'];
                    $output['iTotalRecords'] = $total_row + $count_row;
                    // $output['iTotalDisplayRecords'] = $total_row + $count_row[0]['count'];
                    $output['iTotalDisplayRecords'] = $total_row + $count_row;
                }
            } elseif ($case_no) {

                // $sQuery = "
                //            SELECT   prosec.id as prosecution_id,
                //                     prosec.orderSheet_id as orderSheet_id,
                //                     mag.name_eng as magistrate_name,
                //                     prosec.case_no as case_no,
                //                     prosec.date as pdate,
                //                     prosec.user_idno as complain_no,
                //                     prosec.case_status as case_status,
                //                     prosec.is_attached as is_attached,
                //                     prosec.orderSheet_id as ordersheetId,
                //                     prosec.hasCriminal
                //                     FROM Mcms\Models\Prosecution AS prosec
                //                     INNER JOIN Mcms\Models\Court AS court  ON court.id = prosec.court_id
                //                     INNER JOIN Mcms\Models\Magistrate AS mag  ON mag.id = court.magistrate_id
                //                     WHERE prosec.delete_status = '1' AND  prosec.case_no = '" . $case_no . "'
                //                     ";

                // //echo $sQuery;
                // $query = $this->modelsManager->createQuery($sQuery);
                // $rResult = $query->execute();

                $rResult = Prosecution::select([
                    'prosecutions.id as prosecution_id',
                    'prosecutions.orderSheet_id as orderSheet_id',
                    'magistrates.name as magistrate_name',
                    'prosecutions.case_no as case_no',
                    'prosecutions.date as pdate',
                    'prosecutions.user_idno as complain_no',
                    'prosecutions.case_status as case_status',
                    'prosecutions.is_attached as is_attached',
                    'prosecutions.orderSheet_id as ordersheetId',
                    'prosecutions.hasCriminal'
                ])
                    ->join('courts', 'courts.id', '=', 'prosecutions.court_id')
                    ->join('users AS magistrates', 'magistrates.id', '=', 'courts.magistrate_id')
                    ->where('prosecutions.delete_status', '1')
                    ->where('prosecutions.case_no', $case_no)
                    ->get(); // Execute the query and get the results

                $status = '';

                if (count($rResult) > 0) {
                    $row = array(
                        "0" => $rResult[0]->magistrate_name, // magistrate name
                        "1" => $rResult[0]->case_no, // case no
                        "2" => $rResult[0]->complain_no, // compalin no
                        "3" => $rResult[0]->pdate, // prosecution date
                        "4" => "", // law section
                        "5" => "", // punishment
                        "6" => $rResult[0]->case_status, //  status
                        "7" => 'nothi'
                    ); // nothi

                    if ($rResult[0]->ordersheetId == null) {
                        $status = 'অনিস্পন্ন';
                        $row["6"] = $status;
                    } elseif ($rResult[0]->ordersheetId != null) {
                        $status = 'নিস্পন্ন';
                        // get punishment info
                        if ($rResult[0]->hasCriminal == 1) {
                            // $sQuery = "
                            //         SELECT   lb.Description as law_section,
                            //         punishment.order_detail as punishment
                            //         FROM Mcms\Models\Punishment AS punishment
                            //         INNER JOIN  Mcms\Models\LawsBroken AS lb   ON lb.ProsecutionID = punishment.prosecution_id
                            //         WHERE punishment.prosecution_id = '" . $rResult[0]["prosecution_id"] . "'
                            // ";
                            // $query = $this->modelsManager->createQuery($sQuery);
                            // $punishResult = $query->execute();

                            $punishResult = DB::table('punishments')
                                ->join('laws_brokens as lb', 'lb.prosecution_id', '=', 'punishments.prosecution_id')
                                ->select('lb.Description as law_section', 'punishments.order_detail as punishment')
                                ->where('punishments.prosecution_id', $rResult[0]->prosecution_id)
                                ->get();
                            // ->first()


                            // $row["4"] = $this->utilityService->jsonValidator($punishResult[0]["law_section"])?json_decode($punishResult[0]["law_section"]):$punishResult[0]["law_section"]  ; // law section
                            $row["4"] = $this->jsonValidator($punishResult[0]->law_section) ? json_decode($punishResult[0]->law_section) : $punishResult[0]->law_section; // law section
                            $row["5"] = $punishResult[0]->punishment;  // punishment

                        } else {
                            $row["4"] = ''; // law section
                            $row["5"] = '';  // punishment
                        }
                        //
                        $row["0"] = $rResult[0]->magistrate_name; // magistrate name
                        $row["1"] = $rResult[0]->case_no; // case no
                        $row["3"] = $rResult[0]->pdate;  // prosecution date
                        // punishment
                        $row["6"] = $status;
                    }
                    $output['data'][] = $row;
                } else {
                    $flag = false;
                }
            } elseif ($complain_no) {
                // $sQuery1 = "
                //                     SELECT   cci.user_idno as complain_no,
                //                     cci.complain_status as complain_status,
                //                     mag.name_eng as magistrate_name,
                //                     cci.prosecution_id as prosecution_id,
                //                     cci.case_number as case_no,
                //                     cci.case_status as case_status,
                //                     cci.prosecution_date as prosecution_date
                //                     FROM Mcms\Models\CourtComplainInfo  AS cci
                //                     INNER JOIN Mcms\Models\Magistrate AS mag  ON mag.id = cci.magistrate_id
                //                     where cci.user_idno = '" . $complain_no . "'";

                // $query = $this->modelsManager->createQuery($sQuery1);
                // $rResult = $query->execute();
                $rResult = DB::table('court_complain_infos as cci')
                    ->join('users as mag', 'mag.id', '=', 'cci.magistrate_id')
                    ->select(
                        'cci.user_idno as complain_no',
                        'cci.complain_status as complain_status',
                        'mag.name as magistrate_name',
                        'cci.prosecution_id as prosecution_id',
                        'cci.case_number as case_no',
                        'cci.case_status as case_status',
                        'cci.prosecution_date as prosecution_date'
                    )
                    ->where('cci.user_idno', $complain_no)
                    ->get();

                $status = '';

                if (count($rResult) > 0) {
                    $row = array(
                        "0" => $rResult[0]->magistrate_name, // magistrate name
                        "1" => $rResult[0]->case_no, // case no
                        "2" => $rResult[0]->complain_no, // compalin no
                        "3" => $rResult[0]->prosecution_date, // prosecution date
                        "4" => "", // law section
                        "5" => "", // punishment
                        "6" => "", //  status
                        "7" => 'nothi'
                    ); // nothi


                    if ($rResult[0]->complain_status == "initial") {
                        $status = "কার্যক্রম গ্রহন হয়নি";
                        $row["6"] = $status;
                    } elseif ($rResult[0]->complain_status == "ignore") {
                        $status = "বাতিল  ";
                        $row["6"] = $status;
                    } elseif ($rResult[0]->case_status == "processing") {
                        $status = 'অনিস্পন্ন';
                        $row["6"] = $status;
                        $row["0"] = $rResult[0]->magistrate_name; // magistrate name
                        $row["1"] = $rResult[0]->case_no; // case no
                        $row["3"] = $rResult[0]->prosecution_date;  // prosecution date
                    } elseif ($rResult[0]->case_status == "solved") {
                        $status = 'নিস্পন্ন';
                        // get punishment info
                        // $sQuery = "
                        //             SELECT   lb.Description as law_section,
                        //             punishment.order_detail as punishment
                        //             FROM Mcms\Models\Punishment AS punishment
                        //             INNER JOIN  Mcms\Models\LawsBroken AS lb   ON lb.ProsecutionID = punishment.prosecution_id
                        //             WHERE punishment.prosecution_id = '" . $rResult[0]["prosecution_id"] . "'
                        //     ";
                        // //echo $sQuery;
                        // $query = $this->modelsManager->createQuery($sQuery);
                        // $punishResult = $query->execute();
                        $punishResult = DB::table('punishments as punishment')
                            ->join('laws_brokens as lb', 'lb.prosecution_id', '=', 'punishment.prosecution_id')
                            ->select('lb.Description as law_section', 'punishment.order_detail as punishment')
                            ->where('punishment.prosecution_id', $rResult[0]->prosecution_id)
                            ->first();


                        $row["0"] = $rResult[0]->magistrate_name; // magistrate name
                        $row["1"] = $rResult[0]->case_no; // case no
                        $row["3"] = $rResult[0]->prosecution_date;  // prosecution date
                        $row["4"] = $this->jsonValidator($punishResult[0]->law_section) ? json_decode($punishResult[0]->law_section) : $punishResult[0]->law_section;; // law section
                        $row["5"] = $punishResult[0]->punishment;; // punishment
                        $row["6"] = $status;
                    } elseif ($rResult[0]->complain_status == "accepted") {
                        $status = "মামলা হয়নি";
                        $row["6"] = $status;
                    }
                    $output['data'][] = $row;
                } else {  // brfore requisition //  ok
                    // $sQuery1 = "
                    //         SELECT   ctzcomp.complain_status
                    //         FROM Mcms\Models\CitizenComplain  AS ctzcomp
                    //         where ctzcomp.user_idno = '" . $complain_no . "'";
                    // //echo $sQuery;
                    // $query = $this->modelsManager->createQuery($sQuery1);
                    // $rResult = $query->execute();
                    $rResult = DB::table('citizen_complains as ctzcomp')
                        ->select('ctzcomp.complain_status')
                        ->where('ctzcomp.user_idno', $complain_no)
                        ->get();

                    if ($rResult[0]->complain_status == "initial") {
                        $status = "কার্যক্রম গ্রহন হয়নি";
                    } elseif ($rResult[0]->complain_status == "ignore") {
                        $status = "বাতিল  ";
                    } else {
                        $status = "অপরিবর্তিত  ";
                    }

                    if (count($rResult) > 0) {
                        $row = array(
                            "0" => "", // magistrate name
                            "1" => "", // case no
                            "2" => $complain_no, // compalin no
                            "3" => "", // prosecution date
                            "4" => "", // law section
                            "5" => "", // punishment
                            "6" => $status, //  status
                            "7" => 'nothi'
                        ); // nothi
                        $output['data'][] = $row;
                    } else {
                        $flag = false;
                    }
                }
            } else { // no complain no , no magistrate id , no case number
                $flag = false;
            }

            if (!$flag) {
                // var_dump("error");
                $output = array(
                    "draw" => intval($request->sEcho),
                    "iTotalRecords" => 0,
                    "iTotalDisplayRecords" => 0,
                    "data" => array()
                );
            }
        }




        // $response = new \Phalcon\Http\Response();
        // $response->setContentType('application/json', 'UTF-8');
        // $response->setContent(json_encode($output));
        return response()->json($output);
    }

    public function jsonValidator($data)
    {

        if (!empty($data)) {

            json_decode($data);

            return (json_last_error() === JSON_ERROR_NONE);
        }

        return false;
    }

    // public function checkCaseNumberDuplicacy(Request $request){
    //     $prosecution_id = $request->prosecutionId;
    //     $caseNo = $request->caseNo;
    //     $msg["flag"] = "false";
    //     // $prosecution = Prosecution::find($caseNo);
    //     $prosecution = DB::table('prosecutions')->where('case_no', $caseNo)->first();

    //     if($prosecution){
    //         if($prosecution->id != $prosecution_id){
    //             $msg["flag"] = "true";
    //         }
    //     }
    //     $msg["case_no"] = $caseNo;

    //     return ($msg);
    // }

    public function checkCaseNumberDuplicacy(Request $request)
    {
        $prosecution_id = $request->prosecutionId;
        $caseNo = $request->caseNo;
        $msg["flag"] = "false";
        $msg["alert"] = "No issues";

        // Check if the case number already exists
        $prosecution = DB::table('prosecutions')->where('case_no', $caseNo)->first();
        if ($prosecution) {

            if ($prosecution->id != $prosecution_id) {
                $msg["flag"] = "true";
            }
        } else {
            // Extract parts of the case number
            $parts = explode('.', $caseNo);
            if (count($parts) < 5) {
                $msg["alert"] = "Invalid case number format";
                return $msg;
            }

            $part1 = $parts[0];
            $part2 = $parts[1];
            $part3 = $parts[2];
            $part5 = $parts[4];
            $currentPart4 = (int)$parts[3];

            // Fetch the case with the largest part 4
            $largestPart4Case = DB::table('prosecutions')
                ->select('case_no')
                ->whereRaw("SUBSTRING_INDEX(case_no, '.', 1) = ?", [$part1])
                ->whereRaw("SUBSTRING_INDEX(SUBSTRING_INDEX(case_no, '.', 2), '.', -1) = ?", [$part2])
                ->whereRaw("SUBSTRING_INDEX(SUBSTRING_INDEX(case_no, '.', 3), '.', -1) = ?", [$part3])
                ->whereRaw("SUBSTRING_INDEX(case_no, '.', -1) = ?", [$part5])
                ->orderByRaw("CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(case_no, '.', 4), '.', -1) AS UNSIGNED) DESC")
                ->first();

            if ($largestPart4Case) {
                $largestCaseNo = $largestPart4Case->case_no;
                $largestParts = explode('.', $largestCaseNo);

                if (count($largestParts) >= 4) {
                    $largestPart4 = (int)$largestParts[3];
                    $nextPart4 = $largestPart4 + 1;

                    // Check if the input part 4 matches the expected sequence
                    if ($currentPart4 != $nextPart4) {
                        $msg["flag"] = 'sequence_issue';
                        $msg["alert"] = "মামলার প্রত্যাশিত সিরিয়াল নাম্বারঃ $nextPart4, প্রাপ্ত সিরিয়াল নাম্বারঃ $currentPart4";
                    }
                }
            } else {
                $msg["alert"] = "পূর্ববর্তী তথ্য পাওয়া যায়নি";
            }
        }
        $msg["case_no"] = $caseNo;

        return $msg;
    }
}
