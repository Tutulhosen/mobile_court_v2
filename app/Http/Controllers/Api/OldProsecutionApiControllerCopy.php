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
use Illuminate\Support\Facades\DB;
use App\Repositories\LawRepository;
use App\Repositories\CaseRepository;
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

    public function suomotucourt(Request $request,$prosecution_Id = ''){

        // $this->view->seizureitem_type = SeizureitemType::find();
        // $this->view->case_type = CaseType::find();

        // $this->view->jail_id = Jail::find();

        if($prosecution_Id){
            $data['prosecution_id']=$prosecution_Id;
            $data['case_type'] = DB::table('mc_law_type')->get();
            $data['seizureitem_type'] = DB::table('seizureitem_type')->get();
            $data['jail'] = DB::table('mc_jail')->get();
            $data['division'] = DB::table('geo_divisions')
            ->select('id', 'division_name_bng')
            ->get();
            $data['selectMagistrateCourtId']= '';
            return view('appeals.suomotucourt')->with($data);
        }else{
            $data['prosecution_id']="";
        }
        $magistrateId = Auth::user()->id;
        $date = date('Y-m-d'); // today date
        if ($magistrateId) {
            $court=DB::table('courts')->where('magistrate_id',$magistrateId)->where('date',$date)->where('status', 1)->first();
            if (empty($court)) {  // if court not exist
                session()->flash('message', 'আজকের দিনে কোন কর্মসূচী নাই ।');
                return view('court.openclose');
            }else{
              $request->session()->put('court_id',$court->id);

              return redirect()->route('prosecution.prosecution_form');
            }
        }else {
            $str_message = "ম্যজিট্রেটের তথ্য পাওয়া যায়নি।";
        }


    }
    public function prosecution_form(){

        $data['case_type'] = DB::table('mc_law_type')->get();
        $data['seizureitem_type'] = DB::table('seizureitem_type')->get();
        $data['jail'] = DB::table('mc_jail')->get();
        $data['division'] = DB::table('geo_divisions')
        ->select('id', 'division_name_bng')
        ->get();
        $data['selectMagistrateCourtId']= '';
        $data['prosecution_id']="";
        return view('appeals.suomotucourt')->with($data);
    }


    public function createProsecutionCriminalBymagistrate(Request $request){

        $userinfo=globalUserInfo();  // gobal user
        $office_id=globalUserInfo()->office_id;  // gobal user
        $officeinfo= DB::table('office')->where('id',$office_id)->first();
        $date = date('Y-m-d'); // today date
        // login magistate court info
         $court_id= $request->session()->get('court_id');
        $case_no = $this->generateCaseNo();
        $magistrateCourtId=  $court_id;//$authuserinfo['court_id'];
        $isSuomoto=1;

        if($userinfo->role_id == 25){
            $profile= 'Prosecutor';

              $magistrateId= $request->data['selectMagistrateId'];
              $magistrateCourtId=$request->data["selectMagistrateCourtId"];
              // $loginUserInfo=$this->utilityService->processLoginUserInfo($magistrateId,$loginUserInfo);
               $magistrate=User::find($magistrateId);

            $isSuomoto=0;


        }elseif($userinfo->role_id == 26){
            $profile= 'Magistrate';
            $magistrateId= $userinfo->id;
        }
        $prosecutionID = $request->prosecutionID;

        $authuserinfo =array(
            'id' =>$userinfo->id,
            'magistrate-id' => $magistrateId,
            'courtname' => $userinfo->name,
            'name' => $userinfo->name,
            'email' => $userinfo->email,
            'profile' =>$profile,   // P
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
            'court_id'=> $court_id,
            'case_no'=> $case_no,
        );

        $loginUserInfo = $authuserinfo;
        if($prosecutionID != null && $prosecutionID > 0) {
            // let go, not a valid case
        }else {
           $prosecution =  ProsecutionRepository::createProsecutionShell($isSuomoto,$loginUserInfo,$magistrateCourtId,true);
           $prosecutionID = $prosecution->id;
        }

        $caseinfo = array(
            'magistrate_id' =>   $loginUserInfo['magistrate-id'],
            'magistrate_name' => $loginUserInfo['name'],
            'case_no' => $authuserinfo['case_no'],
            'prosecution_id' =>$prosecutionID
        );
        $data = $request->all();
        $criminals= $data['data']['criminals'];
        CriminalRepository::saveCriminals($criminals, $caseinfo, $loginUserInfo);
        $caseInformation = CaseRepository::getCaseInformationByProsecutionId($prosecutionID);
        // dd($caseInformation );
        $msg["flag"] = "true";
        $msg["message"] = "মামলার আসামির তথ্য এট্রি করা হয়েছে ।";
        $msg["step"] = 2;
        $msg["caseInfo"] = $caseInformation;
        $msg["case_no"] =$case_no;//$dd['data']['case_no'];
        $msg["no_criminal_punish"] = 0;
        $msg["selectMagistrateCourtId"] = $magistrateCourtId ?? '';
        return json_encode($msg);





    }

    public function createProsecutionWitness(Request $request){


            $userinfo=globalUserInfo();  // gobal user
            $office_id=globalUserInfo()->office_id;  // gobal user
            $officeinfo= DB::table('office')->where('id',$office_id)->first();
            $date = date('Y-m-d'); // today date
            $flag=false;
            $isSuomoto=1;
            $caseInformation["flag"]=false;
            // login magistate court info
            if($userinfo->role_id== 26){
            $court = DB::table('courts')->select('id as court_id')->where('magistrate_id',$userinfo->id)->where('date', $date)->where('status',1)->get();
            $court_id = $court[0]->court_id;
            }
            $case_no = $this->generateCaseNo();

            if($userinfo->role_id == 25){
                $profile= 'Prosecutor';
                  $magistrateId= $request->data['selectMagistrateId'];
                  $magistrateCourtId=$request->data["selectMagistrateCourtId"];
                  // $loginUserInfo=$this->utilityService->processLoginUserInfo($magistrateId,$loginUserInfo);
                   $magistrate=User::find($magistrateId);

                $isSuomoto=0;


            }elseif($userinfo->role_id == 26){
                $profile= 'Magistrate';
                $magistrateId= $userinfo->id;
                $magistrateCourtId =$court_id;
            }
            $userinfo =array(
                'id' =>$userinfo->id,
                'magistrate-id' => $magistrateId,
                'courtname' => $userinfo->name,
                'name' => $userinfo->name,
                'email' => $userinfo->email,
                'profile' =>$profile,   // P
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
                'case_no'=> $case_no,
            );


            $prosecutionID = $request['data']['prosecutionID'];
            $data = $request->all()['data'];

            if(!ProsecutionRepository::getProsecutionById( $prosecutionID )) {
                $haseCriminal = false;
                if($userinfo['profile']=="Prosecutor"){
                    $magistrateId= $request->data['selectMagistrateId'];
                    $magistrateCourtId=$request->data["selectMagistrateCourtId"];
                    $magistrate=User::find($magistrateId);
                    $isSuomoto = 0;
                    // Get existing prosecution_id or create new prosecution
                }

                $prosecution = ProsecutionRepository::createProsecutionShell($isSuomoto,$userinfo,$magistrateCourtId,$haseCriminal);

                $prosecutionID = $prosecution->id;
                $caseInformation["flag"] = WitnessRepository::saveWitnesses($data,$prosecutionID);
             }else{
                $caseInformation["flag"] = WitnessRepository::saveWitnesses($data,$prosecutionID);
            }
            
            $updatedProsecution = ProsecutionRepository::getProsecutionById($prosecutionID);
            $magistrateInfo=CaseRepository::getMagistrateInfoByProsecutionId($prosecutionID);

            $msg["flag"] =true;
            // $msg["prosecution"] = $witnessinfo['data']['updatedProsecution'];
            $msg["prosecution"] = $updatedProsecution;
            $msg["case_no"] = $case_no;
            $msg["magistrateInfo"]=$magistrateInfo;
            $msg["message"] = "সাক্ষীর তথ্য  সফলভাবে সংরক্ষণ করা হয়েছে ।";
            $msg["step"] = 3; // for witness
            $msg["prosecutionId"] =$prosecutionID ;
            $msg["no_criminal_punish"] = 0;
            $msg["selectMagistrateCourtId"] = $magistrateCourtId ?? '';
            return json_encode($msg);

    }
    public function  getLaw(){
        $data =[];
        $subcategories = DB::table("mc_law")->select('title','id')->get();
        foreach ($subcategories as $t) {
            $data[] = array('id' => $t->id, 'name' => $t->title);
        }
        return json_encode($data);
    }
    public function getSectionByLawId(Request $request)
    {

        $id=$request->id;
        $data =[];
        $subcategories = DB::table("mc_section")->where('law_id',$id)->get();
        foreach ($subcategories as $t) {
            $data[] = array('id' => $t->id, 'sec_description' => 'ধারা :' . $t->sec_number . '-' . $t->sec_description);
        }
        return json_encode($data);

    }
    public function getPunishmentBySectionId(Request $request){


        $id=$request->id;
           $data ="";
           $subcategories = DB::table("mc_section")->where('id',$id)->get();

           foreach ($subcategories as $t) {
               $data = array('name' =>$t->punishment_des ,'sectiondes' =>$t->punishment_sec_number );
           }
           return json_encode($data);
    }
    public function createProsecution(Request $request){

        $userinfo=globalUserInfo();  // gobal user
        $office_id=globalUserInfo()->office_id;  // gobal user
        $officeinfo= DB::table('office')->where('id',$office_id)->first();
        $date = date('Y-m-d'); // today date
        // login magistate court info
        if($userinfo->role_id== 26){
        $court = DB::table('courts')->select('id as court_id')->where('magistrate_id',$userinfo->id)->where('date', $date)->where('status',1)->get();
        $court_id = $court[0]->court_id;
        }
        $prosecutionInfo = $_POST;
        $brokenLawsArray = $prosecutionInfo['brokenLaws'];   //geting prosecution ID
        $prosecutionId = $prosecutionInfo['prosecutionId'];   //geting prosecution ID
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
        $loginUserInfo = $authuserinfo;


        $crimeDescription = LawRepository::saveLawsBrokenList($prosecutionId,$brokenLawsArray,$loginUserInfo);

        $prosecution = ProsecutionRepository::saveProsecutionInformation($prosecutionId,$prosecutionInfo,$crimeDescription,$loginUserInfo);

        // $lawsBrokenList = LawsBroken::where('prosecution_id', $prosecutionId)->get();
        if($prosecution == false){
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
        $msg["message"] ="অভিযোগ দায়ের সফলভাবে সম্পন্ন হয়েছে।";
        $msg["case_no"] = $prosecution->case_no;
        $msg["caseInfo"] = $caseInformation;;
        $msg["lawbroken"] =   $caseInformation['lawsBrokenList'];
        $msg["step"] = 4; // for seizure list
        $msg["no_criminal"] = $prosecution->no_criminal;
        $msg["no_criminal_punish"] = 0;
        $msg["isSuomoto"] =$prosecution->is_suomotu;
        return json_encode($msg);
    }

    public function savelist(Request $request){
        // $ddd=Session::get('variableName');

        $jsonData=$request->all();
        $prosecution_id = $request->prosecutionId;
        $seizure_data = $request->seizure;
        $prosecution = Prosecution::find($prosecution_id);
        $seizureitem_type = DB::table('seizureitem_type')->get();

        $seizureService=SeizureRepository::saveSeizureList($prosecution_id,$seizure_data,$seizureitem_type);
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

    public function saveCriminalConfessionSuomotu(Request $request){
        $userinfo=globalUserInfo();  // gobal user
        $office_id=globalUserInfo()->office_id;  // gobal user
        $officeinfo= DB::table('office')->where('id',$office_id)->first();
        $date = date('Y-m-d'); // today date
        // login magistate court info
        // $court = DB::table('courts')->select('id as court_id')->where('magistrate_id',$userinfo->id)->where('date', $date)->where('status',1)->get();
        // $court_id = $court[0]->court_id;
        $Data = $request->modelData;
        $modelData= json_decode(json_encode(json_decode($Data)),true);
        $isSuomotoFlag=1;

        $prosecutionID = $modelData['prosecutionID'];
        $confessionDetails = $modelData['criminalConfessionDetails'];
        $loginUserInfo =array(
            'id' =>$userinfo->id,
            'magistrate-id' => $userinfo->id,
            'courtname' => $userinfo->name,
            'name' => $userinfo->name,
            'email' => $userinfo->email,
            'profile' =>'Magistrate',   // P
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
            // 'court_id'=> $court_id,
        );



        // $jsonData = $request->all();
        // $loginUserInfo = json_decode($jsonData['logininfo'], true);
        //  $prosecutionID = json_decode($jsonData['prosecution_id'], true);
        //  $confessionDetails = json_decode($jsonData['confessionDetails'], true);

        // return $request->all();
        $prosecution=CriminalRepository::saveConfessionDetails($prosecutionID, $confessionDetails,$loginUserInfo);
        if($prosecution){
         $isSuomotoFlag = $prosecution->is_suomotu;
        }
        // return $this->sendResponse($data,'');
        // $msg["isSuomoto"]=$response['data']['isSuomotoFlag'];
        // $msg["flag"] = "true";
        // $msg["message"] = "জবানবন্দী সংরক্ষণ করা হয়েছে ।";
        // $msg["step"] = 6; // for ordersheet
        // return json_encode($msg);

        $msg["isSuomoto"]=$isSuomotoFlag;
        $msg["flag"] = "true";
        $msg["message"] = "জবানবন্দী সংরক্ষণ করা হয়েছে ।";
        $msg["step"] = 6; // for ordersheet
        return json_encode($msg);
    }

    public function getCriminalPreviousCrimeDetails(Request $request){

        $prosecution_id= $request->prosecutionId;
        $criminal_id= $request->criminalId;
        $crimeDetail=CaseRepository::getPreviousCrimeDetailsByCriminalId($criminal_id,$prosecution_id);
        // return $this->sendResponse($crimeDetail, '');
        return  json_encode($crimeDetail);

    }

    public function isPunishmentExist(Request $request){
        $msgExistOrder=null;
        $alldata=$request->data;
        $flag="false";
        $isPunishmentExist=PunishmentRepository::isPunishmentExist($alldata);
        // dd($isPunishmentExist);
        if($isPunishmentExist==1){
            $msgExistOrder='আসামি কে ইতিমধ্যে এই আইনে  আদেশ প্রদান করা হয়েছে। ';
            $flag = "true";
        }
        $msg["msgExistOrder"]=$msgExistOrder;
        $msg["flag"] = $flag;
        // return $this->sendResponse($isPunishmentExist, '');
        return  json_encode($msg);

    }

    public function saveOrderBylaw(Request $request){

        $userinfo=globalUserInfo();  // gobal user
        $office_id=globalUserInfo()->office_id;  // gobal user
        $officeinfo= DB::table('office')->where('id',$office_id)->first();
        $date = date('Y-m-d'); // today date
        // login magistate court info
        // $court = DB::table('courts')->select('id as court_id')->where('magistrate_id',$userinfo->id)->where('date', $date)->where('status',1)->get();
        // $court_id = $court[0]->court_id;

        // $msg = '';
        $msgExistOrder=null;
        $data = $request->data;

        $logininfo =array(
            'id' =>$userinfo->id,
            'magistrate-id' => $userinfo->id,
            'courtname' => $userinfo->name,
            'name' => $userinfo->name,
            'email' => $userinfo->email,
            'profile' =>'Magistrate',   // P
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
            // 'court_id'=> $court_id,
        );

        $punishment = PunishmentRepository::savePunishment($data, $logininfo);

          if(!$punishment){
            $msgExistOrder='আসামি কে ইতিমধ্যে এই আইনে  আদেশ প্রদান করা হয়েছে। ';
        }

        // prepare response message
        $msg["flag"] = "true";
        $msg["message"] = "আদেশ সম্পন্ন হয়েছে।";
        $msg["punishment"] = $punishment; //
        $msg["msgExistOrder"]=$msgExistOrder;
        $response =json_encode( $msg);

        return $response;

    }

    public function getThanaByUsersZillaId(){

        $thanaArray = array();
        // $user = $this->auth->getUserLocation();
        $userinfo=globalUserInfo();  // gobal user
        $office_id=globalUserInfo()->office_id;  // gobal user
        $officeinfo= DB::table('office')->where('id',$office_id)->first();

        $date = date('Y-m-d'); // today date
        // login magistate court info
        // $court = DB::table('courts')->select('id as court_id')->where('magistrate_id',$userinfo->id)->where('date', $date)->where('status',1)->get();
        // $court_id = $court[0]->court_id;

        $userinfo =array(
            'id' =>$userinfo->id,
            'magistrate-id' => $userinfo->id,
            'courtname' => $userinfo->name,
            'name' => $userinfo->name,
            'email' => $userinfo->email,
            'profile' =>'Magistrate',   // P
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
            // 'court_id'=> $court_id,
        );
        $tmp = array();
        if ($userinfo['zillaid']) {

            $tmp = DB::table('geo_thanas')->where('geo_district_id', $userinfo['zillaid'])->get();

        }
        foreach ($tmp as $t) {
            $thanaArray[] = array('id' => $t->id, 'name' => $t->thana_name_bng );
        }
        return  json_encode($thanaArray);
     }

    public function getOrderListByProsecutionId(Request $request){

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
        $response=json_encode($msg);
        return $response;
    }

    public function deleteOrder(Request $request){


         $orderId= $request->orderId;
         $proceutionId=$request->prosecutionId;
         $punishment = PunishmentRepository::deletePunishmentByOrderId($proceutionId,$orderId);

         // prepare response message
         $msg["flag"] = "true";
         $msg["message"] = " রেকর্ডটি";
         $msg["punishment"] = $punishment; //
         $response=json_encode($msg);
         return $response;
        // return $this->sendResponse( $punishment, '');
    }

    public function getCaseInfoByProsecutionId(Request $request){
        $prosecutionId = $request->prosecutionId;
        $caseInfo=CaseRepository::getCaseInformationByProsecutionId($prosecutionId);

        if($caseInfo['prosecution']->case_no!='অভিযোগ গঠন হয়নি'){
            $case_no = $caseInfo['prosecution']->case_no;
        }else{
            $case_no = $this->generateCaseNo();
        }
        $msg["flag"] = "true";
        $msg["caseInfo"] = $caseInfo;   // $prosecution->no_criminal
        $msg["case_no"] = $case_no;   // Send Case no

        $ddd=json_encode($msg);
        return $ddd ;
    }

    public function saveJimmaderInformation(Request $request){

        $Data = $request->modelData;
        $jimmaderInfo= json_decode(json_encode(json_decode($Data)),true);
        $successMsg=ProsecutionRepository::saveJimmaderInformation($jimmaderInfo[0]);
        $msg["flag"] = $successMsg;
        $response= json_encode($msg);
        return $response;

    }

    public function previewOrderSheet(Request $request){
        // orderSheetLayout
       $prosecutionId=$request->prosecutionId;

       return view('appeals.partials.orderSheetLayout',['prosecutionId' => $prosecutionId]);
    }
    public function incompletecase(){
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

        return view('appeals.incompletecase',compact('prosecutions','page_title'));

    }


    public function searchProsecution(Request $request){
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


        $magistrate =Auth::user();

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

    public function newComplain(Request $request,$prosecution_id = ''){

        // Find the prosecution by ID
        $prosecution = Prosecution::find($prosecution_id);
        // Check if the prosecution was found
        if ($prosecution) {
            // Pass the prosecution ID to the view
            return view('appeals.newComplain', ['prosecutionId' => $prosecution_id,'page_title'=>'অভিযোগ গঠন']);
        }

    }
    public function searchComplain(Request $request){

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
            'prosecutions.id', 'prosecutions.subject', 'prosecutions.date', 'prosecutions.location', 'prosecutions.case_no', 'prosecutions.hints', 'prosecutions.prosecutor_name'
        ])
        ->orderBy('prosecutions.id','DESC')
        ->paginate(10, ['*'], 'page', $numberPage);

        return view('appeals.searchComplain', ['prosecutions' => $query, 'page_title' => "মামলার তালিকা"]);
    }
    public function searchCase(Request $request){
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

        // Pass paginated results to the view
        return view('appeals.searchCase', ['prosecutions' => $prosecution,'page_title'=>'মামলার তালিকা']);
    }
    public function extendOrderSheet(Request $request,$id = ''){
             // Initialize variables

        $orderHeader = "";
        $orderDetails = "";
        $fine = "";
        $fineInWord = "";
        $receiptNo = "";
        $punishmentId = "";

        // Get punishment
        $punishment = Punishment::join('prosecution_details AS PrD', 'PrD.prosecution_id', '=', 'punishments.prosecution_id')
            ->where('punishments.prosecution_id', $id)
            ->orderBy('PrD.id', 'ASC')
            ->first();

        if ($punishment) {
            $punishmentId = $punishment->id;
            $receiptNo = $punishment->receipt_no;
            $fineInWord = $punishment->fine_in_word;
            $fine = $punishment->fine;
        }

        // Get order sheet details
        $orderSheets = OrderSheet::where('prosecution_id', $id)->get();

        if ($orderSheets->count() > 0) {
            $details = "";

            foreach ($orderSheets as $orderSheet) {
                $details .= $orderSheet->order_details;
                $orderHeader = $orderSheet->order_header;
            }
        }

        // Pass data to the view
        return view('appeals.extendOrderSheet', compact('orderHeader', 'orderDetails', 'fine', 'fineInWord', 'receiptNo', 'punishmentId', 'id'))
            ->with([
                'orderHeader' => $orderHeader,
                'orderDetails' => $details,
                'fine' => $fine,
                'fineInWord' => $fineInWord,
                'receiptNo' => $receiptNo,
                'punishmentId' => $punishmentId,
                'prosecutionId' => $id,
            ]);



    }
    public function saveExtendOrderSheet(Request $request){
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
            $receipt_no, $formatted_date, $table, $receiver_id, $prosecution_id
        );

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

    public function getOrderSheetInfo(Request $request){

        $prosecutionId = $request->data;

        // $all_data=$request->all();
        // $prosecutionId=json_decode($all_data['prosecutionId'],true);
        $punishmentInfo=PunishmentRepository::getPunishmentInfo($prosecutionId);
        $orderList=PunishmentRepository:: getOrderListByProsecutionId($prosecutionId);
        $caseInfo=CaseRepository::getCaseInformationByProsecutionId($prosecutionId);
        // $lawsBrokenList = LawsBroken::where('prosecution_id',$prosecutionId)->get();
        // $response=array(
        //     "punishmentConfessionByLaw"=>$punishmentInfo,
        //     "orderList"=>$orderList,
        //     "caseInfo"=>$caseInfo,
        //     "lawsBrokenList"=>$lawsBrokenList,
        // );

        $response=array(
            "punishmentConfessionByLaw"=>$punishmentInfo,
            "orderList"=>$orderList,
            "caseInfo"=>$caseInfo
        );

        $msg["flag"] = "true";
        $msg["message"] = " রেকর্ডটি";
        $msg["info"] = $response; //
        $response= json_encode($msg);
        return $response;
        // return $this->sendResponse($response, '');

    }

    public  function saveOrderSheet(Request $request){
        $header=$request->header;
        $tableBody=$request->tableBody;
        $prosecutionId=$request->prosecutionId;
         $successMsg=PunishmentRepository::saveOrderSheet($header,$tableBody,$prosecutionId);
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
    $cond = "is_approved = 1";
    if (!isset($parameters['conditions'])) {
        $parameters['conditions'] = $cond;
    } else {
        $parameters['conditions'] .= ' AND ' . $cond;
    }
    $parameters['order'] = "prose.id DESC";
    $user = Auth::user();
    $magistrate_id = $user->id;


    $searchCondition ='prose.is_approved =1 AND  prose.delete_status = 1 AND prose.orderSheet_id IS NOT NULL';
    $startDate =(!empty($request->start_date)?$request->start_date:"");
    $startDate = date('Y-m-d', strtotime($startDate));
    $endDate = (!empty($request->end_date)?$request->end_date:"");
    if($endDate == ''){
        $endDate = date('Y-m-d');
    }else{
        $endDate = date('Y-m-d', strtotime($endDate));
    }
    if($endDate == ''){
        $endDate = date('Y-m-d H:i:s');
    }

    $caseNo = $request->case_no; // Default to an empty string if not provided
    $searchCriteria = $request->searchCriteria; // Default to an empty string if not provided
    $magistrateID = $request->magistrate_id; // Default to an empty string if not provided

    $prosecutions1 = DB::table('prosecutions AS prose')->join('courts AS court', 'court.id', '=', 'prose.court_id');

    if ($searchCriteria === 'is_case') {
        $searchCondition = 'prose.case_no = ?'; // Use parameter binding for security
        $searchCriteriaLabel = '(মামলার নম্বর: '.$caseNo.')';
        $paginateType = 'is_case';
        $prosecutions=$prosecutions1->where('prose.case_no',$caseNo); // Use parameter binding to avoid SQL injection
    }

    if ($searchCriteria === 'is_date') {

        $searchCondition .= 'prose.date BETWEEN ? AND ?';
        $searchCriteriaLabel = '(তারিখ: '.$startDate.' থেকে '.$endDate.')';
        $paginateType = 'is_date';
        $prosecutions=$prosecutions1->whereBetween('prose.date',[$startDate, $endDate]);
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
    $prosecutions=$prosecutions1->where('court.magistrate_id', $magistrate_id);
    $prosecutions=$prosecutions1->whereRaw($parameters['conditions']) ;// Apply search conditions
    $prosecutions=$prosecutions1->select(
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


        // dd($prosecutions);
        return view('appeals.showForms', ['prosecutions' => $prosecutions,
        'searchCriteria' => $searchCriteriaLabel ?? '',
        'paginate' => $paginateType ?? "",
        'start_date' => $startDate ?? "",
        'end_date' => $endDate ?? "",
        'magistrate_id' => $magistrateID,]);

    }
    public function printForms(Request $request,$id){

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
                ->first(); // First result for prosecution details

            // Parse crime description
            $crime_description = "";
            if ($prosecution) {
                $crime_description = json_decode($prosecution->subject, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    // Fallback if JSON is invalid
                    $crime_description[0] = $prosecution->subject;
                }
            }

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
            $is_suomotu= $prosecution->is_suomotu;
            $hasCriminal =$prosecution->hasCriminal;
            return view('appeals.printForms',compact('criminals','prosecution','crime_description','id','is_suomotu','hasCriminal'));
    }
    public function details($id){
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
        return view('appeals.details',compact('case_details','page_title'));
    }

    // Prosecutor
    public function newProsecution(Request $request,$prosecution_Id=null,$magistrateid = "")
    {
        $userinfo=globalUserInfo();  // gobal user
        $office_id=globalUserInfo()->office_id;  // gobal user
        $officeinfo= DB::table('office')->where('id',$office_id)->first();
        $division_id=$officeinfo->division_id;
        $district_id=$officeinfo->district_id;

        $results = User::join('doptor_user_access_info', 'users.common_login_user_id', '=', 'doptor_user_access_info.common_login_user_id')
        ->join('office', 'office.id', '=', 'users.office_id')
        ->select('users.name','users.id as user_id','office.office_name_bn',DB::raw('CONCAT(users.name, " - ", office.office_name_bn) as name_eng'),) // Adjust this as needed
        ->where('doptor_user_access_info.role_id',26)
        ->where('office.district_id',$district_id)
        ->where('office.division_id',$division_id)
        ->get();




        $data['district'] = DB::table('district')->where('division_id',$division_id)->get();
        $data['magistrate_list'] = $results;

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

        if($prosecution_Id){
            $data['prosecution_id'] = $prosecution_Id;
        }else{
            $data['prosecution_id'] = '';
        }
        if($magistrateid){
            $data['magistrate_id'] = $magistrateid;
        }else{
            $data['magistrate_id'] = '';
        }
         $prosecutioninfo=prosecution::find($prosecution_Id);
         if(!empty($prosecution_Id)){
             $data['selectMagistrateCourtId']=$prosecutioninfo->court_id ? $prosecutioninfo->court_id:"";

         }else{
            $data['selectMagistrateCourtId'] ='';
         }
        return view('appeals.newProsecution')->with($data);
    }
    public   function generateCaseNo($appealId=null){
        $userinfo=globalUserInfo();
        $court = DB::table('courts')->select('id as court_id','title')->where('magistrate_id',$userinfo->id)->where('status',1)->first();


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
        if(!empty($court->title)){


        $part_1 = strtoupper(substr( (!empty($court->title)?$court->title:'proce'), 0, 3)) . '-MC';
        $case_postition = DB::table('courts')
            ->selectRaw('count(*) as case_postition')
            ->where('id', $court->court_id)
            ->where('id', '<=',$court->court_id)
            ->first()->case_postition;
        $case_no = $part_1 . '-' . $case_postition .'-' . date('Y') . '-' . (!empty($court->court_id)?$court->court_id:rand(5,100)).'-'.rand(10,100);
        }else{
            $case_no = 'court' . '-' . rand(10,90) .'-' . date('Y') . '-' . (!empty($court->court_id)?$court->court_id:rand(5,100)).'-'.rand(10,100);
        }

        return  $case_no;
     }

     public function getLawListByProsecutorId(Request $request){

        $flag="false";
        $childs = array();

        $prosecutorId=$request->post('prosecutorId');
        if(!$prosecutorId){
            $prosecutorId = (int) $request->query('prosecutorId', 0);
        }

         $lawListForProsecutor=ProsecutorLawMappingRepository::getSelectedLawListByProsecutorId($prosecutorId);

        foreach ($lawListForProsecutor as $t) {

            $childs[] = array('id' => $t->id, 'name' => $t->name);

        }

       return  response()->json($childs, 200);
        // $response = new \Phalcon\Http\Response();
        // $response->setContentType('application/json', 'UTF-8');
        // $response->setContent(json_encode($childs));
        // return $response;
     }

     public function showProsecutionList(){
         $prosecutor = globalUserInfo();  // gobal user; // Assuming you're getting the prosecutor via the authenticated user

         $data['page_title'] ='দায়েরকৃত অভিযোগ';
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
                ])
                ->join('courts as court', 'court.id', '=', 'prose.court_id')
                ->join('users as magistrate', 'magistrate.id', '=', 'court.magistrate_id')
                ->leftJoin('seizurelists as szr', 'szr.prosecution_id', '=', 'prose.id')
                ->where('prose.prosecutor_id', $prosecutor->id)
                // ->orderBy('prose.date', 'DESC')
                // ->orderBy('magistrate.id', 'DESC')
                ->groupBy('szr.prosecution_id')
                ->orderBy('prose.id', 'DESC')->paginate(10);

                return view('appeals.showProsecutionList')->with($data);
     }

     public function incompleteProsecution(Request $request){
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
     public function newProsecutionWithoutCriminal(Request $request,$prosecution_Id=null,$magistrateid = ""){
        $userinfo=globalUserInfo();  // gobal user
        $office_id=globalUserInfo()->office_id;  // gobal user
        $officeinfo= DB::table('office')->where('id',$office_id)->first();
        $division_id=$officeinfo->division_id;
        $district_id=$officeinfo->district_id;

        $results = User::join('doptor_user_access_info', 'users.common_login_user_id', '=', 'doptor_user_access_info.common_login_user_id')
        ->join('office', 'office.id', '=', 'users.office_id')
        ->select('users.name','users.id as user_id','office.office_name_bn',DB::raw('CONCAT(users.name, " - ", office.office_name_bn) as name_eng'),) // Adjust this as needed
        ->where('doptor_user_access_info.role_id',26)
        ->where('office.district_id',$district_id)
        ->where('office.division_id',$division_id)
        ->get();




        $data['district'] = DB::table('district')->where('division_id',$division_id)->get();
        $data['magistrate_list'] = $results;

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
        if($prosecution_Id){
            $data['prosecution_id'] = $prosecution_Id;
        }else{
            $data['prosecution_id'] = '';
        }
        $magistrateid = $request->query('magistrateid');
        if($magistrateid){
            $data['magistrate_id'] = $magistrateid;
        }else{
            $data['magistrate_id'] = '';
        }
        $data['page_title'] ="অসম্পূর্ণ অভিযোগের তালিকা";
        $prosecutioninfo=prosecution::find($prosecution_Id);
        if(!empty($prosecution_Id)){
            $data['selectMagistrateCourtId']=$prosecutioninfo->court_id ? $prosecutioninfo->court_id:"";
        }else{
            $data['selectMagistrateCourtId']='';
        }

        return view('appeals.newProsecutionWithoutCriminal')->with($data);

    }

    public function incompleteProsecutionWithoutCriminal(Request $request){
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

    public function createsizedList(Request $request){
         // Fetch prosecutor info (assuming you have an auth method)
         $prosecutor = Auth::user();

        $prosecution = Prosecution::select('prosecutions.id as id',
        'prosecutions.subject',
         'prosecutions.date',
         'prosecutions.location',
         'prosecutions.case_no',
         'prosecutions.prosecutor_name')
            ->join('courts', 'courts.id', '=', 'prosecutions.court_id')
            ->where('prosecutions.prosecutor_id', $prosecutor->id)
            ->where('prosecutions.is_approved', 0)
            ->orderBy('prosecutions.created_at', 'DESC')
            ->paginate(10);
              // Pass the paginated prosecution data to the view
              return view('appeals.createsizedList', ['prosecutions' => $prosecution, 'page_title' => "দায়েরকৃত অভিযোগ"]);
    }

    public function editSeizedList(Request $request,$id){
        $prosecution = Prosecution::find($id);

            // Pass data to the view
            return view('appeals.editSeizedList', [
                'prosecution_id' => $prosecution->id,
                'is_suomotu'     => $prosecution->is_suomotu,
                'case_no'        => $prosecution->case_no,
                'seizureitem_type' => DB::table('seizureitem_type')->get(),
            ]);
    }


    public function showFormByProsecution(Request $request) {
        $caseInfo = [];
        $jailInfo = [];

        $prosecutionId = $request->query('prosecutionId', null);
        if ($prosecutionId) {
            $form_id = 0;
            $caseInfo =CaseRepository::getCaseInformationByProsecutionId($prosecutionId);
        } else {
            $form_id = $request->query('form_id', 'string');
            $id = $request->query('id', 'int'); // prosecutionId
            $suomotu = $request->query('suomotu', 'int');
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

        return response()->json($caseInfo, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }

    public function createComplain(Request $request){
        $flag="";
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

        $prosecutionInfo = $_POST;  //geting Form data
        $prosecutionId = $prosecutionInfo['prosecutionId'];

        $prosecution = ProsecutionRepository::approveProsecution($prosecutionId,$prosecutionInfo);
        if($prosecution == false){
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
        }else{
            $flag="true";
        }

        $msg["flag"] = $flag;
        $msg["message"] = "অভিযোগ দায়ের সফলভাবে সম্পন্ন হয়েছে।";
        $msg["case_no"] = $prosecution->case_no;

        return response()->json($msg, 200);
    }
    public function getMagistrate(Request $request)
    {

    $userinfo=globalUserInfo();  // gobal user
    $office_id=globalUserInfo()->office_id;  // gobal user
    $officeinfo= DB::table('office')->where('id',$office_id)->first();
    $division_id=$officeinfo->division_id;
    $district_id=$officeinfo->district_id;

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

    public function editCriminalConfession($prosecutionId){

       return view('appeals.editCriminalConfession', ['prosecutionId' => $prosecutionId]);
    }


    public function editOrderSheet($prosecutionId){
        $data['case_type'] = DB::table('mc_law_type')->get();
        $data['seizureitem_type'] = DB::table('seizureitem_type')->get();
        $data['jail'] = DB::table('mc_jail')->get();
        $data['prosecutionId']=$prosecutionId;
        return view('appeals.editOrderSheet')->with($data);
    }

    public function suomotucourtWithoutCriminal(Request $request, $prosecutionId = null){

        $userinfo=globalUserInfo();  // gobal user
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
             $court =DB::table('courts')->where('magistrate_id', $magistrateId)
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
        }else {
            // Flash message if no magistrate ID
            session()->flash('error', "ম্যজিট্রেটের তথ্য পাওয়া যায়নি।");
        }

    //    return view('your_view_name', compact('lawList', 'seizureitemTypes', 'caseTypes', 'jailList', 'prosecutionId'));
        return view('appeals.suomotucourtWithoutCriminal')->with($data);
    }

    public function incompletecaseWithoutCriminal(Request $request){
        $userinfo=globalUserInfo();  // gobal user
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
             ->where('court.magistrate_id',$magistrate)
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
        $userinfo=globalUserInfo();  // gobal user
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
            $prosecutions = Prosecution::select('prosecutions.id as id',
                                'prosecutions.subject as subject',
                                'prosecutions.date as date',
                                'prosecutions.location as location',
                                'prosecutions.case_no as case_no',
                                'courts.status as status',
                                'courts.date as courtdate',
                                'prosecutions.prosecutor_name as prosecutor_name')
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
}