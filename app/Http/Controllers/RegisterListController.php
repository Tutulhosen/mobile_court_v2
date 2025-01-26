<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\RegisterLabel;
use App\Models\RegisterList;
use App\Models\Prosecution;
use Illuminate\Support\Facades\Auth;
class RegisterListController extends Controller
{
    //

    public function register(){
  
        $userinfo=globalUserInfo();  // gobal user
        $office_id=globalUserInfo()->office_id;  
        $geoCityCorporations = [];
        $geoMetropolitan = [];
        $geoThanas = [];
        
        // Fetch session data
        $roleID = globalUserInfo()->role_id;
       
        
        if($roleID == 37){
           
            $data['title']='জেলা ম্যাজিস্ট্রেট';
            // return view('dashboard.monitoring_admin');
            $data['profile']= 'DM';
           
            $officeinfo = DB::table('office')
            ->leftJoin('district as zilla', function ($join) {
               $join->on('office.district_id', '=', 'zilla.id')
                    ->on('office.division_id', '=', 'zilla.division_id');
           })->where('office.id',$office_id)->first();
           $division_id = $officeinfo->division_id;
           $district_id = $officeinfo->district_id;
            $zillaname = $officeinfo->district_name_bn;
            $zillaId =$district_id ;
            $data['zillaId'] = $zillaId;
            $data['office_address'] = "জেলাঃ &nbsp;" . $zillaname . "&nbsp;।";
            // Fetch Upazila list by Zilla ID
            $data['reportlist'] =DB::table('register_lists')->whereIn('id',['1','2','3','7','8','10'])->get(); 
            $data['upazila'] = DB::table('upazila')->where('district_id', $zillaId)->get();
            $data['roleID']=$roleID;
        }elseif($roleID == 38){
            $data['title']='অতিরিক্ত জেলা ম্যাজিস্ট্রেট';
            $data['profile']= 'ADM';
            $data['office_address'] ="";
            $officeinfo = DB::table('office')
            ->leftJoin('district as zilla', function ($join) {
               $join->on('office.district_id', '=', 'zilla.id')
                    ->on('office.division_id', '=', 'zilla.division_id');
           })->where('office.id',$office_id)->first();
           $division_id = $officeinfo->division_id;
           $district_id = $officeinfo->district_id;
            $zillaname = $officeinfo->district_name_bn;
            $zillaId =$district_id ;
           // Set default values
           $data['zillaId'] = $zillaId;
           $data['office_address'] = "জেলাঃ &nbsp;" . $zillaname . "&nbsp;।";
           $data['upazila'] = DB::table('upazila')->where('district_id', $zillaId)->get();
           $data['reportlist'] =DB::table('register_lists')->whereIn('id',['1','2','3','7','8','10'])->get();  
           $data['roleID']=$roleID;
            // return view('dashboard.monitoring_adm');
        }elseif($roleID == 25){
            $data['title']='প্রসিকিউটর';
            $data['office_address'] = "";
            $data['reportlist'] =DB::table('register_lists')->where('id',6)->get();  
            $data['profile']= 'Prosecutor';
            $data['roleID']=$roleID;
        }elseif($roleID == 26){

            $data['title']='এক্সিকিউটিভ ম্যাজিস্ট্রেট';
            $data['profile']= 'Magistrate';
            $courtname = $userinfo->name;
            $data['office_address'] = "আদালতের নামঃ &nbsp;" . $courtname . "&nbsp;।";  // Concatenate the text with the court name
            $data['reportlist'] =DB::table('register_lists')->whereIn('id',['1','2','3','7','8','10'])->get();  
            $data['roleID']=$roleID;
        }elseif($roleID == 27){
            $data['title']='এসিজিএম';
            // return view('dashboard.monitoring_admin');
            $data['roleID']=$roleID;
        }

       $data['lawlist'] = DB::table('mc_law')->get();
   
        // $data['profile'] = $profile;

        // // Profile specific logic
        // if ($profile == 'ADM' || $profile == 'DM') {
        //     $zillaId = $session_arr['zillaid'];
        //     $zillaname = $session_arr['zillaname'];
            
        //     // Set default values
        //     $data['zillaId'] = $zillaId;
        //     $data['office_address'] = "জেলাঃ &nbsp;" . $zillaname . "&nbsp;।";

        //     // Fetch Upazila list by Zilla ID
        //     $data['upazila'] = Upazila::where('zillaid', $zillaId)->get();

        // } elseif ($profile == 'Divisional Commissioner') {
        //     $divid = $session_arr['divid'];
        //     $divname = $session_arr['divname'];
            
        //     // Fetch Zilla list by Division ID
        //     $data['zilla'] = Zilla::where('divid', $divid)->get();
        //     $data['upazila'] = [];
        //     $data['office_address'] = "বিভাগঃ &nbsp;" . $divname . "&nbsp;।";

        // } elseif ($profile == 'JS') {
        //     // Fetch Division list for JS profile
        //     $data['division'] = Division::all(); // Assuming you have a Division model
        //     $data['zilla'] = [];
        //     $data['upazila'] = [];
        //     $data['office_address'] = "";

        // } elseif ($profile == 'Magistrate') {
        //     $courtname = $session_arr['courtname'];
        //     $data['office_address'] = "আদালতের নামঃ &nbsp;" . $courtname . "&nbsp;।";

        // } elseif ($profile == 'Prosecutor') {
        //     $data['office_address'] = "";
        // }

        // Fetch logged-in user's profile ID
        // $loginUserProfileID = auth()->user()->profilesId;

        // Query registerlist and report list
        // $data['reportlist'] = RegisterList::join('register_profile', 'register_profile.register_id', '=', 'registerlist.id')
        //     ->select('registerlist.id', 'registerlist.name')
        //     ->distinct()
        //     ->where('register_profile.profille_id', $loginUserProfileID)
        //     ->get();

        // // Fetch lawlist
        // $data['lawlist'] = Law::all();
 
        // Return view with data
        return view('register_list.register', $data);
    }
    /**
     * নাগরিক অভিযোগ রেজিস্টার
     */
    public function printcitizenregister(Request $request){
        $result = [];
        $reportName = "No data Found";
        $status = '';
        $registerLabelList = '';
        $groupingValue = 'divname';
        $complainStatus = '';
        $mag_name = "";
        $zilla_name = "";
  
        $profilesId = Auth::user()->id;
    
        // if ($request->isMethod('post') && $request->ajax()) {
        
            list($end_date, $start_date, $divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $reportID) = $this->getAjaxUrlParameter($request);
            $complainStatus = $request->complainstatus;
 
            $condition = '';
    
            // $register_rep = new RegisterLabel();
            // $registerLabelList = $register_rep->getRegisterLabelList($reportID);
            $registerLabelList = RegisterList::find($reportID)->labels()->get(['label']);
            
            $roleID = globalUserInfo()->role_id;
            // $session_arr = Session::get('auth-identity');
            // $profile = $session_arr['profile'];
            $office_id = globalUserInfo()->office_id;
            $officeinfo = DB::table('office')->where('id',$office_id)->first();
            $division_id = $officeinfo->division_id;
            $district_id = $officeinfo->district_id;
            // if (in_array($profile, ['ADM', 'DM'])) {
                if ($roleID == 37 || $roleID == 38) {
                $divid = $division_id;
                $zillaid = $district_id;
                $groupingValue = 'zillaname';
            }
            //  elseif ($profile == 'Divisional Commissioner') {
            //     $divid = $session_arr['divid'];
            // }
    
            if (empty($end_date)) {
                $end_date = now()->format('m/d/Y');
            }
    
            if (!empty($start_date) && !empty($end_date)) {
                $start_date = \Carbon\Carbon::createFromFormat('m/d/Y', $start_date)->format('Y-m-d');
                $end_date = \Carbon\Carbon::createFromFormat('m/d/Y', $end_date)->format('Y-m-d');
                $condition .= "DATE(citz.created_date) BETWEEN \"$start_date\" AND \"$end_date\"";
            }
    
            if($complainStatus == '0'){
                $status = "AND  citz.complain_status =".'"initial"';
            }elseif(strcmp($complainStatus ,'1') == 0 ){
                $status .= "AND  citz.complain_status =".'"accepted"';
            }elseif(strcmp($complainStatus ,'2') == 0 ){
                $status .= "AND  citz.complain_status =".'"ignore"';
            }else{
                $status .= "";
            }
    
         $sWhere = $condition . ' ' . $status;

            if (!empty($divid)) {
                $sWhere .= " AND citz.divid = $divid";
                $groupingValue = 'zillaname';
            }
            if (!empty($zillaid)) {
                $sWhere .= " AND citz.zillaid = $zillaid";
                $groupingValue = 'upazilaname';
            }
            if (!empty($upozilaid)) {
                $sWhere .= " AND citz.upazilaId = $upozilaid";
            }
            if (!empty($GeoCityCorporations)) {
                $sWhere .= " AND citz.geo_citycorporation_id = $GeoCityCorporations";
            }
            if (!empty($GeoMetropolitan) && !empty($GeoThanas)) {
                $sWhere .= " AND citz.geo_metropolitan_id = $GeoMetropolitan AND citz.geo_thana_id = $GeoThanas";
            }
           
            $magCondition = '';
            
            if ($roleID == 26) {  //magistrate

                $userinfo=globalUserInfo();  // gobal user
                $office_id=globalUserInfo()->office_id;  // gobal user
                $officeinfo= DB::table('office')->where('id',$office_id)->first();
                $division_id=$officeinfo->division_id;
                $district_id=$officeinfo->district_id;
        
                $results = User::join('doptor_user_access_info', 'users.common_login_user_id', '=', 'doptor_user_access_info.common_login_user_id')
                ->join('office', 'office.id', '=', 'users.office_id')
                ->select('users.name','users.id as user_id','office.office_name_bn') // Adjust this as needed
                ->where('doptor_user_access_info.role_id',26)
                ->where('office.district_id',$district_id)
                ->where('office.division_id',$division_id)
                ->first();

                // $userlocation = Auth::user()->getUserLocation();
                // $zilla_name = $userlocation['zillaname'];
                // $joblocation = $userlocation['joblocation'] . ', ' . $zilla_name;
                $mag_name = Auth::user()->name;
                $magistrate = Auth::user();
                $magCondition .= " AND req.magistrate_own_id = " . $magistrate->id;
                $zilla_name = $results->office_name_bn;
            }
    
            $result = DB::select(
                DB::raw("
                    SELECT
                        citz.user_idno AS user_idno,
                        DATE_FORMAT(citz.created_date, '%Y-%m-%d') AS cdate,
                        DATE_FORMAT(citz.complain_date, '%Y-%m-%d') AS idate,
                        citz.location AS location,
                        citz.subject AS subject,
                        citz.complain_status AS complain_status,
                        citz.name_bng AS name_bng,
                        citz.feedback AS feedback,
                        citz.complain_details AS complain_details,
                        '' AS case_no,
                        COALESCE(magistrate.name, ' ') AS mag_name,
                        DATE_FORMAT(req.estimated_date, '%Y-%m-%d') AS estimated_date,
                        zilla.district_name_bn AS zillaname,
                        division.division_name_bn AS divname
                    FROM citizen_complains AS citz
                    LEFT JOIN requisitions AS req ON citz.id = req.complain_id
                    INNER JOIN users AS magistrate ON magistrate.id = req.magistrate_own_id
                    LEFT JOIN division AS division ON division.id = citz.divid
                    LEFT JOIN district AS zilla ON zilla.id = citz.zillaId AND zilla.division_id = division.id
                    LEFT JOIN upazila AS upazila ON upazila.id = citz.upazilaid AND upazila.district_id = zilla.id
                    LEFT JOIN geo_city_corporations gc ON gc.geo_division_id = citz.divid AND gc.geo_district_id = citz.zillaid AND gc.id = citz.geo_citycorporation_id
                    LEFT JOIN geo_metropolitan m ON m.geo_division_id = citz.divid AND m.geo_district_id = citz.zillaid AND m.id = citz.geo_metropolitan_id
                    WHERE $sWhere   $magCondition
                    ORDER BY division.division_name_bn, req.created_date, req.id ASC
                ")
            );
     
             $RegisterList = RegisterList::find($reportID);
              $reportName = $RegisterList->name;
        // }
    
        return response()->json([
            'result' => $result,
            'name' => $reportName,
            'registerLabelList' => $registerLabelList,
            'profileID' => $roleID,
            'mag_name' => $mag_name,
            'groupingValue' => $groupingValue,
            'zilla_name' => $zilla_name,
        ]);
    }


    public function getAjaxUrlParameter(Request $request)
    {   
          
        
        $end_date = $request->input('end_date');
        $start_date = $request->input('start_date');
        $divid = $request->input('divisionid');
        $zillaid = $request->input('zillaid');
        $upozilaid = $request->input('upozilaid');
        $GeoCityCorporations = $request->input('GeoCityCorporations');
        $GeoMetropolitan = $request->input('GeoMetropolitan');
        $GeoThanas = $request->input('GeoThanas');
        $reportID = $request->input('reportID');
        return [
            $end_date,
            $start_date,
            $divid,
            $zillaid,
            $upozilaid,
            $GeoCityCorporations,
            $GeoMetropolitan,
            $GeoThanas,
            $reportID
        ];
        // return array($end_date, $start_date, $divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $reportID);
    }

    /**
     * প্রাত্যহিক রেজিষ্টার
     */
    // public function printdailyregister(Request $request){
    //     $reportName = "No data Found";
    //     $status = '';
    //     $registerLabelList = '';
    //     $groupingValue= 'divname';
    //     $mag_name = "";
    //     $zilla_name = "";
    //     $result  = array();
    //     $profilesId = Auth::user()->id;

    //     // if (($this->request->isPost()) && ($this->request->isAjax() == true)) {

    //         list($end_date, $start_date, $divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $reportID) = $this->getAjaxUrlParameter($request);

    //         $condition = "prosecutions.delete_status = '1' AND prosecutions.is_approved = 1";

    //         // $register_rep = new RegisterLabel();
    //         // $registerLabelList = $register_rep->getRegisterLabelList($reportID);
    //         // $registerLabelList = RegisterList::find($reportID)->labels()->get(['label']);
    //         $registerLabelList = RegisterList::find($reportID)->labels()->get(['label'])->map(function ($item) {
    //             return [
    //                 0 => $item->label,
    //                 'label' => $item->label
    //             ];
    //         })->toArray();
    //         $roleID = globalUserInfo()->role_id;


    //         // $session_arr = $this->session->get("auth-identity");
    //         // $profile = $session_arr["profile"];

    //         // if ($profile == "ADM" || $profile == "DM") {
    //         //     $divid = $session_arr["divid"];
    //         //     $zillaid = $session_arr["zillaid"];
    //         //     $groupingValue = 'zillaname';

    //         // } elseif ($profile == "Divisional Commissioner") {
    //         //     $divid = $session_arr["divid"];
    //         // }
    //         $office_id = globalUserInfo()->office_id;
    //         $officeinfo = DB::table('office')->where('id',$office_id)->first();
    //         $division_id = $officeinfo->division_id;
    //         $district_id = $officeinfo->district_id;
    //         // if (in_array($profile, ['ADM', 'DM'])) {
    //             if ($roleID == 37 || $roleID == 38) {
    //             $divid = $division_id;
    //             $zillaid = $district_id;
    //             $groupingValue = 'zillaname';
    //         }

    //         if($start_date !=''){
    //             $temp  = new \DateTime($start_date);
    //             $start_date  = $temp->format('Y-m-d'); // 2003-10-16
    //             $condition .= " AND DATE (prosecutions.date)  =  \"$start_date\" ";
    //         }

    //         $sWhere = "";
    //         if ($sWhere == "") {
    //             $sWhere = "$condition $status";
    //         }
    //         if ($divid != '') {
    //             $sWhere .= " AND prosecutions.divid = $divid";
    //             $groupingValue= 'zillaname';
    //         }
    //         if ($zillaid != '') {
    //             $sWhere .= " AND prosecutions.zillaid = $zillaid ";
    //             $groupingValue= 'upazilaname';
    //         }
    //         if($upozilaid !=''){
    //             $sWhere .= " AND prosecutions.upazilaId = $upozilaid";
    //         }

    //         if($GeoCityCorporations !=''){
    //             $sWhere .= " AND prosecutions.geo_citycorporation_id = $GeoCityCorporations";
    //         }

    //         if($GeoMetropolitan !='' && $GeoThanas != ''){
    //             $sWhere .= " AND prosecutions.geo_metropolitan_id = $GeoMetropolitan AND prosecutions.geo_thana_id = $GeoThanas";
    //         }

    //         $magCondition = "";
    //         // if($profilesId == 6){
    //         //     $userlocation = $this->auth->getUserLocation();
    //         //     $zilla_name = $userlocation["zillaname"];
    //         //     $joblocation = $userlocation["joblocation"] . "," . $zilla_name;
    //         //     $magCondition .= "";
    //         //     $mag_name =  $this->auth->getName();
    //         //     $magistrate = $this->auth->getMagistrate();
    //         //     $user_id = $this->auth->getMagistrateUserId();
    //         //     $magCondition .=" AND  ma.user_id =" .$user_id;
    //         //     $zilla_name = $joblocation;
    //         // }
    //         if ($roleID == 26) {  //magistrate

    //             $userinfo=globalUserInfo();  // gobal user
    //             $office_id=globalUserInfo()->office_id;  // gobal user
    //             $officeinfo= DB::table('office')->where('id',$office_id)->first();
    //             $division_id=$officeinfo->division_id;
    //             $district_id=$officeinfo->district_id;
        
    //             $results = User::join('doptor_user_access_info', 'users.common_login_user_id', '=', 'doptor_user_access_info.common_login_user_id')
    //             ->join('office', 'office.id', '=', 'users.office_id')
    //             ->select('users.name','users.id as user_id','office.office_name_bn') // Adjust this as needed
    //             ->where('doptor_user_access_info.role_id',26)
    //             ->where('office.district_id',$district_id)
    //             ->where('office.division_id',$division_id)
    //             ->first();
    //             $magCondition .= "";

    //             $mag_name = Auth::user()->name;
    //             $user_id = Auth::user()->id;
    //             $magCondition .="magistrates.id =$user_id";
    //             $zilla_name = $results->office_name_bn;
    //         }
    
    //         $results1 = Prosecution::select(
    //             DB::raw("DATE_FORMAT(prosecutions.date, '%Y-%m-%d') AS case_date"),
    //             'prosecutions.case_no',
    //             'magistrates.name AS mag_name',
    //             DB::raw("CONCAT(prosecutors.name) AS proc_name_and_designation"),
    //             DB::raw("GROUP_CONCAT(laws.title, ' এর ', sections.sec_number, '<hr>') AS law_details"),
    //             DB::raw("GROUP_CONCAT(laws_broken.description) AS crime_details"),
    //             DB::raw("GROUP_CONCAT(punishments.order_detail, '<hr>') AS order_details"),
    //             DB::raw("CONCAT(punishments.receipt_no, '<hr>') AS receipt_no"),
    //             DB::raw("'' AS next_order"),
    //             'zillas.district_name_bn as zillaname ',
    //             'divisions.division_name_bn as divname'
    //         ) 
    //         ->leftJoin('laws_brokens as laws_broken', 'laws_broken.prosecution_id', '=', 'prosecutions.id')
    //         ->leftJoin('mc_law as laws', 'laws.id', '=', 'laws_broken.law_id')
    //         ->leftJoin('mc_section as sections', function($join) {
    //             $join->on('laws_broken.section_id', '=', 'sections.id')
    //                  ->on('laws.id', '=', 'sections.law_id');
    //         })
    //         ->leftJoin('users as magistrates', 'magistrates.id', '=', 'prosecutions.magistrate_id')
    //         ->leftJoin('users as prosecutors', 'prosecutors.id', '=', 'prosecutions.prosecutor_id')
    //         ->leftJoin('punishments', function($join) {
    //             $join->on('punishments.prosecution_id', '=', 'prosecutions.id')
    //                  ->on('punishments.laws_broken_id', '=', 'laws_broken.id');
    //         })
    //         ->leftJoin('division as divisions', 'divisions.id', '=', 'prosecutions.divid')
    //         ->leftJoin('district as zillas', function($join) {
    //             $join->on('zillas.id', '=', 'prosecutions.zillaId')
    //                  ->on('zillas.division_id', '=', 'prosecutions.divid');
    //         })
    //         ->leftJoin('upazila as upazilas', function($join) {
    //             $join->on('upazilas.district_id', '=', 'prosecutions.zillaId')
    //                  ->on('upazilas.id', '=', 'prosecutions.upazilaId');
    //         })
    //         ->leftJoin('geo_city_corporations', function($join) {
    //             $join->on('geo_city_corporations.geo_division_id', '=', 'prosecutions.divid')
    //                  ->on('geo_city_corporations.geo_district_id', '=', 'prosecutions.zillaId')
    //                  ->on('geo_city_corporations.id', '=', 'prosecutions.geo_citycorporation_id');
    //         })
    //         ->leftJoin('geo_metropolitan as geo_metropolitans', function($join) {
    //             $join->on('geo_metropolitans.geo_division_id', '=', 'prosecutions.divid')
    //                  ->on('geo_metropolitans.geo_district_id', '=', 'prosecutions.zillaId')
    //                  ->on('geo_metropolitans.id', '=', 'prosecutions.geo_metropolitan_id');
    //         })
    //         ->whereRaw($sWhere); // Add conditions from $sWhere
    //         if(!empty($magCondition)){
    //             $results1->whereRaw($magCondition) ;
    //         }
           
    //         $result= $results1->groupBy('prosecutions.court_id', 'prosecutions.id')
    //         ->orderBy('divisions.division_name_bn')
    //         ->orderBy('zillas.district_name_bn')
    //         ->get();
       
    //         $formattedResults = $result->map(function ($item) {

                
    //             return [
    //                 0 => $item->case_date,
    //                 1 => $item->case_no,
    //                 2 => $item->mag_name,
    //                 3 => $item->proc_name_and_designation ?? null,
    //                 4 => $item->law_details,
    //                 5 => $item->crime_details,
    //                 6 => $item->order_details,
    //                 7 => $item->receipt_no ?? null,
    //                 8 => $item->next_order,
    //                 9 => $item->zillaname,
    //                 10 => $item->divname,
    //                 'case_date' => $item->case_date,
    //                 'case_no' => $item->case_no,
    //                 'mag_name' => $item->mag_name,
    //                 'proc_name_and_designation' => $item->proc_name_and_designation ?? null,
    //                 'law_details' => $item->law_details,
    //                 'crime_details' => $item->crime_details,
    //                 'order_details' => $item->order_details,
    //                 'receipt_no' => $item->receipt_no ?? null,
    //                 'next_order' => $item->next_order,
    //                 'zillaname' => $item->zillaname,
    //                 'divname' => $item->divname,
    //             ];
    //         })->toArray();
    //         $RegisterList = RegisterList::find($reportID);
    //         $reportName = $RegisterList->name;

    //         return response()->json([
    //             'result' => $formattedResults,
    //             'name' => $reportName,
    //             'registerLabelList' => $registerLabelList,
    //             'profileID' => $roleID,
    //             'mag_name' => $mag_name,
    //             'groupingValue' => $groupingValue,
    //             'zilla_name' => $zilla_name,
    //         ]);
    // }

    public function printdailyregister(Request $request){
        $reportName = "No data Found";
        $status = '';
        $registerLabelList = '';
        $groupingValue= 'divname';
        $mag_name = "";
        $zilla_name = "";
        $result  = array();
        $profilesId = Auth::user()->id;

        // if (($this->request->isPost()) && ($this->request->isAjax() == true)) {

            list($end_date, $start_date, $divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $reportID) = $this->getAjaxUrlParameter($request);

            $condition = "prosecutions.delete_status = '1' AND prosecutions.is_approved = 1";

            // $register_rep = new RegisterLabel();
            // $registerLabelList = $register_rep->getRegisterLabelList($reportID);
            // $registerLabelList = RegisterList::find($reportID)->labels()->get(['label']);
            $registerLabelList = RegisterList::find($reportID)->labels()->get(['label'])->map(function ($item) {
                return [
                    0 => $item->label,
                    'label' => $item->label
                ];
            })->toArray();

            $roleID = globalUserInfo()->role_id;


            // $session_arr = $this->session->get("auth-identity");
            // $profile = $session_arr["profile"];

            // if ($profile == "ADM" || $profile == "DM") {
            //     $divid = $session_arr["divid"];
            //     $zillaid = $session_arr["zillaid"];
            //     $groupingValue = 'zillaname';

            // } elseif ($profile == "Divisional Commissioner") {
            //     $divid = $session_arr["divid"];
            // }
            $office_id = globalUserInfo()->office_id;
            $officeinfo = DB::table('office')->where('id',$office_id)->first();
            $division_id = $officeinfo->division_id;
            $district_id = $officeinfo->district_id;
            // if (in_array($profile, ['ADM', 'DM'])) {
                if ($roleID == 37 || $roleID == 38) {
                $divid = $division_id;
                $zillaid = $district_id;
                $groupingValue = 'zillaname';
            }

            if($start_date !=''){
                $temp  = new \DateTime($start_date);
                $start_date  = $temp->format('Y-m-d'); // 2003-10-16
                $condition .= " AND DATE (prosecutions.date)  =  \"$start_date\" ";
            }

            $sWhere = "";
            if ($sWhere == "") {
                $sWhere = "$condition $status";
            }
            if ($divid != '') {
                $sWhere .= " AND prosecutions.divid = $divid";
                $groupingValue= 'zillaname';
            }
            if ($zillaid != '') {
                $sWhere .= " AND prosecutions.zillaid = $zillaid ";
                $groupingValue= 'upazilaname';
            }
            if($upozilaid !=''){
                $sWhere .= " AND prosecutions.upazilaId = $upozilaid";
            }

            if($GeoCityCorporations !=''){
                $sWhere .= " AND prosecutions.geo_citycorporation_id = $GeoCityCorporations";
            }

            if($GeoMetropolitan !='' && $GeoThanas != ''){
                $sWhere .= " AND prosecutions.geo_metropolitan_id = $GeoMetropolitan AND prosecutions.geo_thana_id = $GeoThanas";
            }

            $magCondition = "";
            // if($profilesId == 6){
            //     $userlocation = $this->auth->getUserLocation();
            //     $zilla_name = $userlocation["zillaname"];
            //     $joblocation = $userlocation["joblocation"] . "," . $zilla_name;
            //     $magCondition .= "";
            //     $mag_name =  $this->auth->getName();
            //     $magistrate = $this->auth->getMagistrate();
            //     $user_id = $this->auth->getMagistrateUserId();
            //     $magCondition .=" AND  ma.user_id =" .$user_id;
            //     $zilla_name = $joblocation;
            // }
            if ($roleID == 26) {  //magistrate

                $userinfo=globalUserInfo();  // gobal user
                $office_id=globalUserInfo()->office_id;  // gobal user
                $officeinfo= DB::table('office')->where('id',$office_id)->first();
                $division_id=$officeinfo->division_id;
                $district_id=$officeinfo->district_id;
        
                $results = User::join('doptor_user_access_info', 'users.common_login_user_id', '=', 'doptor_user_access_info.common_login_user_id')
                ->join('office', 'office.id', '=', 'users.office_id')
                ->select('users.name','users.id as user_id','office.office_name_bn') // Adjust this as needed
                ->where('doptor_user_access_info.role_id',26)
                ->where('office.district_id',$district_id)
                ->where('office.division_id',$division_id)
                ->first();
                $magCondition .= "";

                $mag_name = Auth::user()->name;
                $user_id = Auth::user()->id;
                $magCondition .="magistrates.id =$user_id";
                $zilla_name = $results->office_name_bn;
            }
    
            $results1 = Prosecution::select(
                DB::raw("DATE_FORMAT(prosecutions.date, '%Y-%m-%d') AS case_date"),
                'prosecutions.case_no',
                'magistrates.name AS mag_name',
                DB::raw("CONCAT(prosecutors.name) AS proc_name_and_designation"),
                DB::raw("GROUP_CONCAT(laws.title, ' এর ', sections.sec_number, '<hr>') AS law_details"),
                DB::raw("GROUP_CONCAT(laws_broken.description) AS crime_details"),
                DB::raw("GROUP_CONCAT(punishments.order_detail, '<hr>') AS order_details"),
                DB::raw("CONCAT(punishments.receipt_no, '<hr>') AS receipt_no"),
                DB::raw("'' AS next_order"),
                'zillas.district_name_bn as zillaname',
                'divisions.division_name_bn AS divname'
            ) 
            ->leftJoin('laws_brokens as laws_broken', 'laws_broken.prosecution_id', '=', 'prosecutions.id')
            ->leftJoin('mc_law as laws', 'laws.id', '=', 'laws_broken.law_id')
            ->leftJoin('mc_section as sections', function($join) {
                $join->on('laws_broken.section_id', '=', 'sections.id')
                     ->on('laws.id', '=', 'sections.law_id');
            })
            ->leftJoin('users as magistrates', 'magistrates.id', '=', 'prosecutions.magistrate_id')
            ->leftJoin('users as prosecutors', 'prosecutors.id', '=', 'prosecutions.prosecutor_id')
            ->leftJoin('punishments', function($join) {
                $join->on('punishments.prosecution_id', '=', 'prosecutions.id')
                     ->on('punishments.laws_broken_id', '=', 'laws_broken.id');
            })
            ->leftJoin('division AS divisions', 'divisions.id', '=', 'prosecutions.divid')
            ->leftJoin('district as zillas', function($join) {
                $join->on('zillas.id', '=', 'prosecutions.zillaId')
                     ->on('zillas.division_id', '=', 'prosecutions.divid');
            })
            ->leftJoin('upazila as upazilas', function($join) {
                $join->on('upazilas.district_id', '=', 'prosecutions.zillaId')
                     ->on('upazilas.id', '=', 'prosecutions.upazilaId');
            })
            ->leftJoin('geo_city_corporations', function($join) {
                $join->on('geo_city_corporations.geo_division_id', '=', 'prosecutions.divid')
                     ->on('geo_city_corporations.geo_district_id', '=', 'prosecutions.zillaId')
                     ->on('geo_city_corporations.id', '=', 'prosecutions.geo_citycorporation_id');
            })
            ->leftJoin('geo_metropolitan as geo_metropolitans', function($join) {
                $join->on('geo_metropolitans.geo_division_id', '=', 'prosecutions.divid')
                     ->on('geo_metropolitans.geo_district_id', '=', 'prosecutions.zillaId')
                     ->on('geo_metropolitans.id', '=', 'prosecutions.geo_metropolitan_id');
            })
            ->whereRaw($sWhere); // Add conditions from $sWhere
            if(!empty($magCondition)){
                $results1->whereRaw($magCondition) ;
            }
           
            $result= $results1->groupBy('prosecutions.court_id', 'prosecutions.id')
            ->orderBy('divisions.division_name_bn')
            ->orderBy('zillas.district_name_bn')
            ->get();


            // dd($result);

// Format the results
// $formattedResults = $result->map(function ($caseData) {
//     return [
//         0 => $caseData->case_date,
//         1 => $caseData->case_no,
//         2 => $caseData->mag_name,
//         3 => $caseData->proc_name_and_designation,
//         4 => $caseData->law_details,
//         5 => $caseData->crime_details,
//         6 => $caseData->order_details,
//         7 => $caseData->receipt_no,
//         8 => null, // Assuming empty or null for your structure
//         9 => $caseData->zillaname,
//         10 => $caseData->divname,
//         'case_date' => $caseData->case_date,
//         'case_no' => $caseData->case_no,
//         'mag_name' => $caseData->mag_name,
//         'proc_name_and_designation' => $caseData->proc_name_and_designation,
//         'law_details' => $caseData->law_details,
//         'crime_details' => $caseData->crime_details,
//         'order_details' => $caseData->order_details,
//         'receipt_no' => $caseData->receipt_no,
//         'zillaname' => $caseData->zillaname,
//         'divname' => $caseData->divname
//     ];
// });

            // return $formattedResults;
//             $results1 = Prosecution::select(
//     DB::raw("DATE_FORMAT(prosecutions.date, '%Y-%m-%d') AS case_date"),
//     'prosecutions.case_no',
//     'magistrates.name AS mag_name',
//     DB::raw("CONCAT(prosecutors.name) AS proc_name_and_designation"),
//     DB::raw("GROUP_CONCAT(laws.title, ' এর ', sections.sec_number, '<hr>') AS law_details"),
//     DB::raw("GROUP_CONCAT(laws_broken.description) AS crime_details"),
//     DB::raw("GROUP_CONCAT(punishments.order_detail, '<hr>') AS order_details"),
//     DB::raw("CONCAT(punishments.receipt_no, '<hr>') AS receipt_no"),
//     DB::raw("'' AS next_order"),
//     'zillas.district_name_bn as zillaname',
//     'divisions.division_name_bn as divname'
// ) 
// ->leftJoin('laws_brokens as laws_broken', 'laws_broken.prosecution_id', '=', 'prosecutions.id')
// ->leftJoin('mc_law as laws', 'laws.id', '=', 'laws_broken.law_id')
// ->leftJoin('mc_section as sections', function($join) {
//     $join->on('laws_broken.section_id', '=', 'sections.id')
//          ->on('laws.id', '=', 'sections.law_id');
// })
// ->leftJoin('users as magistrates', 'magistrates.id', '=', 'prosecutions.magistrate_id')
// ->leftJoin('users as prosecutors', 'prosecutors.id', '=', 'prosecutions.prosecutor_id')
// ->leftJoin('punishments', function($join) {
//     $join->on('punishments.prosecution_id', '=', 'prosecutions.id')
//          ->on('punishments.laws_broken_id', '=', 'laws_broken.id');
// })
// ->leftJoin('divisions', 'divisions.id', '=', 'prosecutions.divid')
// ->leftJoin('districts as zillas', function($join) {
//     $join->on('zillas.id', '=', 'prosecutions.zillaid')
//          ->on('zillas.division_id', '=', 'prosecutions.divid');
// })
// // Additional joins...
// ->whereRaw($sWhere);

// if(!empty($magCondition)){
//     $results1->whereRaw($magCondition);
// }

// $result = $results1->groupBy('prosecutions.court_id', 'prosecutions.id')
//     ->orderBy('divisions.division_name_bn')
//     ->orderBy('zillas.district_name_bn')
//     ->get();
 


             $formattedResults = $result->map(function ($item) {

                
                return [
                    0 => $item->case_date,
                    1 => $item->case_no,
                    2 => $item->mag_name,
                    3 => $item->proc_name_and_designation ?? null,
                    4 => $item->law_details,
                    5 => $item->crime_details,
                    6 => $item->order_details,
                    7 => $item->receipt_no ?? null,
                    8 => $item->next_order,
                    9 => $item->zillaname,
                    10 => $item->divname,
                    'case_date' => $item->case_date,
                    'case_no' => $item->case_no,
                    'mag_name' => $item->mag_name,
                    'proc_name_and_designation' => $item->proc_name_and_designation ?? null,
                    'law_details' => $item->law_details,
                    'crime_details' => $item->crime_details,
                    'order_details' => $item->order_details,
                    'receipt_no' => $item->receipt_no ?? null,
                    'next_order' => $item->next_order,
                    'zillaname' => $item->zillaname,
                    'divname' => $item->divname,
                ];
            })->toArray();

            $RegisterList = RegisterList::find($reportID);
            $reportName = $RegisterList->name;

            return response()->json([
                'result' => $formattedResults,
                'name' => $reportName,
                'registerLabelList' => $registerLabelList,
                'profileID' => $roleID,
                'mag_name' => $mag_name,
                'groupingValue' => $groupingValue,
                'zilla_name' => $zilla_name,
            ]);
    }

    /**
     * কারাদণ্ড প্রদান রেজিস্টার
     */
    public function printPunishmentJailRegister(Request $request){
      
        $reportName = "No data Found";
        $status = '';
        $registerLabelList = '';
        $groupingValue= 'divname';
        $mag_name = "";
        $zilla_name = "";
        $result  = array();
        $profilesId = Auth::user()->id;

            list($end_date, $start_date, $divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $reportID) = $this->getAjaxUrlParameter($request);

            $condition = "prosecutions.delete_status = '1' AND prosecutions.is_approved = 1 AND punishments.punishmentJailID > 0 AND punishments.criminal_id IS NOT NULL" ;

            // $register_rep = new RegisterLabel();
            // $registerLabelList = $register_rep->getRegisterLabelList($reportID);
            $registerLabelList = RegisterList::find($reportID)->labels()->get(['label']);
            $roleID = globalUserInfo()->role_id;

            // $session_arr = $this->session->get("auth-identity");
            // $profile = $session_arr["profile"];

            // if ($profile == "ADM" || $profile == "DM") {
            //     $divid = $session_arr["divid"];
            //     $zillaid = $session_arr["zillaid"];
            //     $groupingValue = 'zillaname';

            // } elseif ($profile == "Divisional Commissioner") {
            //     $divid = $session_arr["divid"];
            // }
            $office_id = globalUserInfo()->office_id;
            $officeinfo = DB::table('office')->where('id',$office_id)->first();
            $division_id = $officeinfo->division_id;
            $district_id = $officeinfo->district_id;
            // if (in_array($profile, ['ADM', 'DM'])) {
                if ($roleID == 37 || $roleID == 38) {
                $divid = $division_id;
                $zillaid = $district_id;
                $groupingValue = 'zillaname';
            }

            if($end_date == ''){
                $date = new \DateTime();
                $end_date = $date->format("m/d/Y");
            }

            if($start_date !='' & $end_date !=''){
                $temp  = new \DateTime($start_date);
                $start_date  = $temp->format('Y-m-d'); // 2003-10-16
                $temp  = new \DateTime($end_date);
                $end_date = $temp->format('Y-m-d'); // 2003-10-16
                $condition .= " AND DATE (prosecutions.date)  BETWEEN  \"$start_date\"  AND  \"$end_date\" ";
            }

            $sWhere = "";
            if ($sWhere == "") {
                $sWhere = "$condition $status";
            }

            if ($divid != '') {
                $sWhere .= " AND prosecutions.divid = $divid";
                $groupingValue= 'zillaname';
            }

            if ($zillaid != '') {
                $sWhere .= " AND prosecutions.zillaid = $zillaid ";
                $groupingValue= 'upazilaname';
            }

            if($upozilaid !=''){
                $sWhere .= " AND prosecutions.upazilaId = $upozilaid";
            }

            if($GeoCityCorporations !=''){
                $sWhere .= " AND prosecutions.geo_citycorporation_id = $GeoCityCorporations";
            }

            if($GeoMetropolitan !='' && $GeoThanas != ''){
                $sWhere .= " AND prosecutions.geo_metropolitan_id = $GeoMetropolitan AND prosecutions.geo_thana_id = $GeoThanas";
            }

            $magCondition = "";

            // if($profilesId == 6){
            //     $userlocation = $this->auth->getUserLocation();
            //     $zilla_name = $userlocation["zillaname"];
            //     $joblocation = $userlocation["joblocation"] . "," . $zilla_name;
            //     $magCondition .= "";
            //     $mag_name =  $this->auth->getName();
            //     $magistrate = $this->auth->getMagistrate();
            //     $user_id = $this->auth->getMagistrateUserId();
            //     $magCondition .=" AND  ma.user_id =" .$user_id;
            //     $zilla_name = $joblocation;
            // }

            if ($roleID == 26) {  //magistrate

                $userinfo=globalUserInfo();  // gobal user
                $office_id=globalUserInfo()->office_id;  // gobal user
                $officeinfo= DB::table('office')->where('id',$office_id)->first();
                $division_id=$officeinfo->division_id;
                $district_id=$officeinfo->district_id;
        
                $results = User::join('doptor_user_access_info', 'users.common_login_user_id', '=', 'doptor_user_access_info.common_login_user_id')
                ->join('office', 'office.id', '=', 'users.office_id')
                ->select('users.name','users.id as user_id','office.office_name_bn') // Adjust this as needed
                ->where('doptor_user_access_info.role_id',26)
                ->where('office.district_id',$district_id)
                ->where('office.division_id',$division_id)
                ->first();
                $magCondition .= "";

                $mag_name = Auth::user()->name;
                $user_id = Auth::user()->id;
                $magCondition .="magistrates.id =$user_id";
                $zilla_name = $results->office_name_bn;
            }

            $results1 = Prosecution::select(
                DB::raw("DATE_FORMAT(prosecutions.date, '%Y-%m-%d') AS case_date"),
                'prosecutions.case_no',
                'magistrates.name AS court_name',
                DB::raw("GROUP_CONCAT(criminals.name_bng, '<hr>') AS crim_name"),
                DB::raw("GROUP_CONCAT(laws.title, ' এর ', sections.sec_number, '<hr>') AS law_details"),
                DB::raw("GROUP_CONCAT(punishments.warrent_duration, '<hr>') AS order_details"),
                DB::raw("'' AS next_order"),
                'zillas.district_name_bn as zillaname',
                'divisions.division_name_bn as divname'
            )
            ->leftJoin('laws_brokens as laws_broken', 'laws_broken.prosecution_id', '=', 'prosecutions.id')
            ->leftJoin('mc_law as laws', 'laws.id', '=', 'laws_broken.law_id')
            ->leftJoin('mc_section as sections', function($join) {
                $join->on('laws_broken.section_id', '=', 'sections.id')
                     ->on('laws.id', '=', 'sections.law_id');
            })
            ->leftJoin('users as magistrates', 'magistrates.id', '=', 'prosecutions.magistrate_id')
            ->leftJoin('punishments', function($join) {
                $join->on('punishments.prosecution_id', '=', 'prosecutions.id')
                     ->on('punishments.laws_broken_id', '=', 'laws_broken.id');
            })
            ->leftJoin('criminals', 'criminals.id', '=', 'punishments.criminal_id')
            ->leftJoin('division as divisions', 'divisions.id', '=', 'prosecutions.divid')
            ->leftJoin('district as zillas', function($join) {
                $join->on('zillas.id', '=', 'prosecutions.zillaId')
                     ->on('zillas.division_id', '=', 'prosecutions.divid');
            })
            ->leftJoin('upazila as upazilas', function($join) {
                $join->on('upazilas.district_id', '=', 'prosecutions.zillaId')
                     ->on('upazilas.id', '=', 'prosecutions.upazilaId');
            })
            ->leftJoin('geo_city_corporations', function($join) {
                $join->on('geo_city_corporations.geo_division_id', '=', 'prosecutions.divid')
                     ->on('geo_city_corporations.geo_district_id', '=', 'prosecutions.zillaId')
                     ->on('geo_city_corporations.id', '=', 'prosecutions.geo_citycorporation_id');
            })
            ->leftJoin('geo_metropolitan as geo_metropolitans', function($join) {
                $join->on('geo_metropolitans.geo_division_id', '=', 'prosecutions.divid')
                     ->on('geo_metropolitans.geo_district_id', '=', 'prosecutions.zillaId')
                     ->on('geo_metropolitans.id', '=', 'prosecutions.geo_metropolitan_id');
            })
            ->whereRaw($sWhere); // Dynamic condition from $sWhere
            if(!empty($magCondition)){
                $results1->whereRaw($magCondition); 
            }
           
            $result=$results1->groupBy('prosecutions.court_id', 'prosecutions.id')
            ->orderBy('divisions.division_name_bn')
            ->orderBy('zillas.district_name_bn')
            ->get();

            $RegisterList = RegisterList::find($reportID);
            $reportName = $RegisterList->name;

        // dd($result);
        return response()->json([
            'result' => $result,
            'name' => $reportName,
            'registerLabelList' => $registerLabelList,
            'profileID' => $roleID,
            'mag_name' => $mag_name,
            'groupingValue' => $groupingValue,
            'zilla_name' => $zilla_name,
        ]);
    }

    public function printmonthlystatisticsregister(Request $request){
        $reportName = "No data Found";
        $mag_name = "";
        $registerLabelList = '';
        $result  = array();
        $zilla_name = "";
        $profilesId =Auth::user()->id;
        $groupingValue= 'divname';

 

            list($end_date, $start_date, $divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $reportID) = $this->getAjaxUrlParameter($request);
            $condition = "";

            $registerLabelList = RegisterList::find($reportID)->labels()->get(['label']);
            $roleID = globalUserInfo()->role_id;


            // $session_arr = $this->session->get("auth-identity");
            // $profile = $session_arr["profile"];

            // if ($profile == "ADM" || $profile == "DM") {
            //     $divid = $session_arr["divid"];
            //     $zillaid = $session_arr["zillaid"];
            //     $groupingValue = 'zillaname';

            // } elseif ($profile == "Divisional Commissioner") {
            //     $divid = $session_arr["divid"];
            // }

            $office_id = globalUserInfo()->office_id;
            $officeinfo = DB::table('office')->where('id',$office_id)->first();
            $division_id = $officeinfo->division_id;
            $district_id = $officeinfo->district_id;
            // if (in_array($profile, ['ADM', 'DM'])) {
                if ($roleID == 37 || $roleID == 38) {
                $divid = $division_id;
                $zillaid = $district_id;
                $groupingValue = 'zillaname';
            }


            if (empty($end_date)) {
                $end_date = now()->format('m/d/Y');
            }
    
            if (!empty($start_date) && !empty($end_date)) {
                $start_date = (new \DateTime($start_date))->format('Y-m-d');
                $end_date = (new \DateTime($end_date))->format('Y-m-d');
                $condition .= "p.delete_status = '1' AND p.is_approved = 1 AND DATE(p.date) BETWEEN '$start_date' AND '$end_date'";
            }
    
            $sWhere = $condition;
    
            if (!empty($divid)) {
                $sWhere .= " AND p.divid = $divid";
                $groupingValue = 'zillaname';
            }
            if (!empty($zillaid)) {
                $sWhere .= " AND p.zillaid = $zillaid";
                $groupingValue = 'upazilaname';
            }
            if (!empty($upozilaid)) {
                $sWhere .= " AND p.upazilaId = $upozilaid";
            }
            if (!empty($GeoCityCorporations)) {
                $sWhere .= " AND p.geo_citycorporation_id = $GeoCityCorporations";
            }
            if (!empty($GeoMetropolitan) && !empty($GeoThanas)) {
                $sWhere .= " AND p.geo_metropolitan_id = $GeoMetropolitan AND p.geo_thana_id = $GeoThanas";
            }
    
         

            $magCondition = "";

                if ($roleID == 26) {  //magistrate

                    $userinfo=globalUserInfo();  // gobal user
                    $office_id=globalUserInfo()->office_id;  // gobal user
                    $officeinfo= DB::table('office')->where('id',$office_id)->first();
                    $division_id=$officeinfo->division_id;
                    $district_id=$officeinfo->district_id;
            
                    $results = User::join('doptor_user_access_info', 'users.common_login_user_id', '=', 'doptor_user_access_info.common_login_user_id')
                    ->join('office', 'office.id', '=', 'users.office_id')
                    ->select('users.name','users.id as user_id','office.office_name_bn') // Adjust this as needed
                    ->where('doptor_user_access_info.role_id',26)
                    ->where('office.district_id',$district_id)
                    ->where('office.division_id',$division_id)
                    ->first();
                    $magCondition .= "";
    
                    $mag_name = Auth::user()->name;
                    $user_id = Auth::user()->id;
                    $magCondition .="ma.id = $user_id";
                    $zilla_name = $results->office_name_bn;
                }
            

            // $result = DB::table('prosecutions as p')
            //             ->selectRaw("
            //                 DATE_FORMAT(p.date, '%Y-%m-%d') AS case_date,
            //                 p.case_no AS case_no,
            //                 ma.name AS mag_name,
            //                 CONCAT(po.name) AS proc_name_and_designation,
            //                 GROUP_CONCAT(CONCAT(l.title, ' এর ', s.sec_number) SEPARATOR '<hr>') AS law_details,
            //                 GROUP_CONCAT(lb.description) AS crime_details,
            //                 CASE 
            //                     WHEN p.orderSheet_id IS NOT NULL 
            //                         THEN GROUP_CONCAT(IFNULL(pn.order_detail, ' দণ্ডের বিবরণ  নেই ।') SEPARATOR '<hr>') 
            //                     ELSE NULL 
            //                 END AS order_details,
            //                 CONCAT(pn.receipt_no, '') AS receipt_no,
            //                 '' AS next_order,
            //                 z.district_name_bn as zillaname,
            //                 d.division_name_bn as divname
            //             ")
            //             ->leftJoin('laws_brokens as lb', 'lb.prosecution_id', '=', 'p.id')
            //             ->leftJoin('mc_law as l', 'l.id', '=', 'lb.law_id')
            //             ->leftJoin('mc_section as s', function($join) {
            //                 $join->on('lb.section_id', '=', 's.id')
            //                     ->on('l.id', '=', 's.law_id');
            //             })
                 
            //             ->leftJoin('users as ma', 'ma.id', '=', 'p.magistrate_id')
            //             ->leftJoin('users as po', 'po.id', '=', 'p.prosecutor_id')
            //             ->leftJoin('punishments pn', function($join) {
            //                 $join->on('pn.prosecution_id', '=', 'p.id')
            //                     ->on('pn.laws_broken_id', '=', 'lb.id');
            //             })
            //             ->leftJoin('division as d', 'd.id', '=', 'p.divid')
            //             ->leftJoin('district as z', function($join) {
            //                $join->on('z.id', '=', 'p.zillaId')
            //                     ->on('z.division_id', '=', 'p.divid');
            //             })
            //             ->leftJoin('upazila as u', function($join) {
            //                 $join->on('u.district_id', '=', 'p.zillaId')
            //                     ->on('u.id', '=', 'p.upazilaId');
            //             })
            //             ->leftJoin('geo_city_corporations as gc', function($join) {
            //                 $join->on('gc.geo_division_id', '=', 'p.divid')
            //                      ->on('gc.geo_district_id', '=', 'p.zillaId')
            //                      ->on('gc.id', '=', 'p.geo_citycorporation_id');
            //             })
            //             ->leftJoin('geo_metropolitan as m', function($join) {
            //                 $join->on('m.geo_division_id', '=', 'p.divid')
            //                      ->on('m.geo_district_id', '=', 'p.zillaId')
            //                      ->on('m.id', '=', 'p.geo_metropolitan_id');
            //             })
            //             // ->whereRaw($sWhere)
            //             // ->whereRaw($magCondition)
            //             ->groupBy('p.court_id', 'p.id')
            //             ->orderBy('d.division_name_bn')
            //             ->orderBy('z.district_name_bn')
            //             ->get();
            $results1 = DB::table('prosecutions as p')
    ->selectRaw("
        DATE_FORMAT(p.date, '%Y-%m-%d') AS case_date,
        p.case_no AS case_no,
        ma.name AS mag_name,
        CONCAT(po.name) AS proc_name_and_designation,
        GROUP_CONCAT(CONCAT(l.title, ' এর ', s.sec_number) SEPARATOR '<hr>') AS law_details,
        GROUP_CONCAT(lb.description) AS crime_details,
        CASE 
            WHEN p.orderSheet_id IS NOT NULL 
                THEN GROUP_CONCAT(IFNULL(pn.order_detail, ' দণ্ডের বিবরণ নেই।') SEPARATOR '<hr>') 
            ELSE NULL 
        END AS order_details,
        CONCAT(pn.receipt_no, '') AS receipt_no,
        '' AS next_order,
        z.district_name_bn AS zillaname,
        d.division_name_bn AS divname
    ")
    ->leftJoin('laws_brokens as lb', 'lb.prosecution_id', '=', 'p.id')
    ->leftJoin('mc_law as l', 'l.id', '=', 'lb.law_id')
    ->leftJoin('mc_section as s', function($join) {
        $join->on('lb.section_id', '=', 's.id')
             ->on('l.id', '=', 's.law_id');
    })
    ->leftJoin('users as ma', 'ma.id', '=', 'p.magistrate_id')
    ->leftJoin('users as po', 'po.id', '=', 'p.prosecutor_id')
    ->leftJoin('punishments as pn', function($join) {
        $join->on('pn.prosecution_id', '=', 'p.id')
             ->on('pn.laws_broken_id', '=', 'lb.id');  // fixed alias
    })
    ->leftJoin('division as d', 'd.id', '=', 'p.divid')  // fixed alias
    ->leftJoin('district as z', function($join) {
        $join->on('z.id', '=', 'p.zillaId')
             ->on('z.division_id', '=', 'p.divid');
    })
    ->leftJoin('upazila as u', function($join) {
        $join->on('u.district_id', '=', 'p.zillaId')
             ->on('u.id', '=', 'p.upazilaId');
    })
    ->leftJoin('geo_city_corporations as gc', function($join) {
        $join->on('gc.geo_division_id', '=', 'p.divid')
             ->on('gc.geo_district_id', '=', 'p.zillaId')
             ->on('gc.id', '=', 'p.geo_citycorporation_id');
    })
    ->leftJoin('geo_metropolitan as m', function($join) {
        $join->on('m.geo_division_id', '=', 'p.divid')
             ->on('m.geo_district_id', '=', 'p.zillaId')
             ->on('m.id', '=', 'p.geo_metropolitan_id');
    })
    ->whereRaw($sWhere);
    if(!empty($magCondition)){
        $results1->whereRaw($magCondition);
    }
 
    $result=  $results1->groupBy('p.court_id', 'p.id')
    ->orderBy('d.division_name_bn')
    ->orderBy('z.district_name_bn')
    ->get();
// Apply conditions
// if (!empty($sWhere)) {
//     $result->whereRaw($sWhere);
// }

// if (!empty($magCondition)) {
//     $result->whereRaw($magCondition);
// }

    
                        // dd($result->toSql());
            $RegisterList = RegisterList::find($reportID);
            $reportName = $RegisterList->name;
        
    

            return response()->json([
                'result' => $result,
                'name' => $reportName,
                'registerLabelList' => $registerLabelList,
                'profileID' => $roleID,
                'mag_name' => $mag_name,
                'groupingValue' => $groupingValue,
                'zilla_name' => $zilla_name,
            ]);
            
       
    }

    public function printlawbasedReport(Request $request){
      
        $result = [];
        $childs = [];
        $reportName = "No data Found";
        $status = '';
        $registerLabelList = '';
        $getDropDownLawId = '';
        $mag_name = "";
        $zilla_name = "";
    
      
    
            // Get parameters from the request
            list($end_date, $start_date, $divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $reportID) = $this->getAjaxUrlParameter($request);
            $getDropDownLawId = $request->lawID;
    
            $condition = "";
            $profilesId = auth()->user()->id;
            $registerLabelList = RegisterList::find($reportID)->labels()->get(['label']);
            $roleID = globalUserInfo()->role_id;
    
            // $session = session()->get('auth-identity');
            // $profile = $session['profile'];
            // $prosecutor = auth()->user()->getProsecutor();
            $prosecutor_con = "";
    
            // if ($profile == 'ADM' || $profile == 'DM') {
            //     $divid = $session['divid'];
            //     $zillaid = $session['zillaid'];
            // } elseif ($profile == 'Divisional Commissioner') {
            //     $divid = $session['divid'];
            // }
            $office_id = globalUserInfo()->office_id;
            $officeinfo = DB::table('office')->where('id',$office_id)->first();
            $division_id = $officeinfo->division_id;
            $district_id = $officeinfo->district_id;
            // if (in_array($profile, ['ADM', 'DM'])) {
                if ($roleID == 37 || $roleID == 38) {
                $divid = $division_id;
                $zillaid = $district_id;
                
            }

    
            if (empty($end_date)) {
                $end_date = now()->format('m/d/Y');
            }
    
            // For prosecutor
            if ($roleID == 25) {
                $userinfo=globalUserInfo();  // gobal user
                $office_id=globalUserInfo()->office_id;  // gobal user
                $officeinfo= DB::table('office')->where('id',$office_id)->first();
                $division_id=$officeinfo->division_id;
                $district_id=$officeinfo->district_id;
                
                $zilaname=DB::table('district')->where('district.id',$district_id)->where('district.division_id',$division_id)->first();
                $divid = $division_id;
                $zillaid = $district_id;
                $zilla_name = $zilaname->district_name_bn;
                $prosecutor_con .= " AND p.prosecutor_id = $userinfo->id ";
            }
 
            if (!empty($start_date) && !empty($end_date)) {
                $start_date = (new \DateTime($start_date))->format('Y-m-d');
                $end_date = (new \DateTime($end_date))->format('Y-m-d');
                $condition .= "DATE(p.date)  BETWEEN   '$start_date'  AND  '$end_date'  ";
            }
    
            $lawIdCon = "";
            if (!empty($getDropDownLawId)) {
                $lawIdCon .= "AND l.id = $getDropDownLawId ";
            }
    
            $sWhere = $condition;
            if (!empty($divid)) {
                $sWhere .= " AND p.divid = $divid";
            }
            if (!empty($zillaid)) {
                $sWhere .= " AND p.zillaid = $zillaid";
            }
            if (!empty($upozilaid)) {
                $sWhere .= " AND p.upazilaId = $upozilaid";
            }
            if (!empty($GeoCityCorporations)) {
                $sWhere .= " AND p.geo_citycorporation_id = $GeoCityCorporations";
            }
            if (!empty($GeoMetropolitan) && !empty($GeoThanas)) {
                $sWhere .= " AND p.geo_metropolitan_id = $GeoMetropolitan AND p.geo_thana_id = $GeoThanas";
            }
    
            // $user = auth()->user();
            // $userlocation = $user->getUserLocation();
            // $joblocation = $userlocation['joblocation'];
    
            $magCondition = "";
            if ($roleID == 26) {
                $userinfo=globalUserInfo();  // gobal user
                $office_id=globalUserInfo()->office_id;  // gobal user
                $officeinfo= DB::table('office')->where('id',$office_id)->first();
                $division_id=$officeinfo->division_id;
                $district_id=$officeinfo->district_id;
        
                $results = User::join('doptor_user_access_info', 'users.common_login_user_id', '=', 'doptor_user_access_info.common_login_user_id')
                ->join('office', 'office.id', '=', 'users.office_id')
                ->select('users.name','users.id as user_id','office.office_name_bn') // Adjust this as needed
                ->where('doptor_user_access_info.role_id',26)
                ->where('office.district_id',$district_id)
                ->where('office.division_id',$division_id)
                ->first();
                $mag_name =$userinfo->name;
                $user_id = $userinfo->id;
                $magCondition .= " AND ma.id = $user_id";
                $zilla_name =   $results->office_name_bn;
            }
     $ff="$sWhere $lawIdCon $magCondition $prosecutor_con";
            // Construct query
            $resultsss = DB::select("
                SELECT z.district_name_bn AS zillaname, d.division_name_bn as divname, p.id AS pro_id, l.title, DATE_FORMAT(p.date, '%Y-%m-%d') AS pdate,
                       p.case_no, ma.name as name_bng, CONCAT(s.sec_number) AS sec_number, lb.Description as crimedescription,
                       pn.order_detail
                FROM prosecutions AS p
                LEFT JOIN laws_brokens  AS lb ON lb.prosecution_id = p.id
                LEFT JOIN mc_law as l ON l.id = lb.law_id
                LEFT JOIN mc_section  AS s ON lb.section_id = s.id AND l.id = s.law_id
                LEFT JOIN users as ma ON ma.id = p.magistrate_id
                LEFT JOIN punishments as pn ON p.id = pn.prosecution_id AND pn.laws_broken_id = lb.id
                LEFT JOIN division as d ON d.id = p.divid
                LEFT JOIN district AS z ON z.id = p.zillaid AND z.division_id = p.divid
                LEFT JOIN upazila AS u ON u.district_id = p.zillaid AND u.id = p.upazilaid
                LEFT JOIN geo_city_corporations AS gc ON gc.geo_division_id = p.divid AND gc.geo_district_id = p.zillaid AND gc.id = p.geo_citycorporation_id
                LEFT JOIN geo_metropolitan as m ON m.geo_division_id = p.divid AND m.geo_district_id = p.zillaid AND m.id = p.geo_metropolitan_id
                WHERE p.delete_status = '1' AND p.is_approved = 1 AND  $ff
                GROUP BY p.court_id, p.id, l.title, s.sec_number
            ");
           
       
            $RegisterList = RegisterList::find($reportID);
            $reportName = $RegisterList->name;
        
        return response()->json([
            "result" => $resultsss,
            "name" => $reportName,
            "profileID" => auth()->user()->id,
            "registerLabelList" => $registerLabelList,
            "zilla_name" => $zilla_name
        ]);
    }

    public function printPunishmentFineRegister(Request $request){
        $reportName = "No data Found";
        $status = '';
        $registerLabelList = '';
        $groupingValue = 'divname';
        $mag_name = "";
        $zilla_name = "";
        $result = [];
        $profilesId = auth()->user()->id;
                  // $register_rep = new RegisterLabel();
            // $registerLabelList = $register_rep->getRegisterLabelList($reportID);

            // $session_arr = session('auth-identity');
            // $profile = $session_arr["profile"];

            // if ($profile == "ADM" || $profile == "DM") {
            //     $divid = $session_arr["divid"];
            //     $zillaid = $session_arr["zillaid"];
            //     $groupingValue = 'zillaname';
            // } elseif ($profile == "Divisional Commissioner") {
            //     $divid = $session_arr["divid"];
            // }
            $roleID = globalUserInfo()->role_id;
            $office_id = globalUserInfo()->office_id;
            $officeinfo = DB::table('office')->where('id',$office_id)->first();
            $division_id = $officeinfo->division_id;
            $district_id = $officeinfo->district_id;
            // if (in_array($profile, ['ADM', 'DM'])) {
                if ($roleID == 37 || $roleID == 38) {
                $divid = $division_id;
                $zillaid = $district_id;
                $groupingValue = 'zillaname';
            }
        // if ($request->ajax() && $request->isMethod('post')) {
            list($end_date, $start_date, $divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $reportID) = $this->getAjaxUrlParameter($request);
            $registerLabelList = RegisterList::find($reportID)->labels()->get(['label']);
            $roleID = globalUserInfo()->role_id;

            $condition = "p.delete_status = '1' AND p.is_approved = 1";

      

            if (empty($end_date)) {
                $end_date = now()->format('m/d/Y');
            }

            if (!empty($start_date) && !empty($end_date)) {
                $start_date = \Carbon\Carbon::parse($start_date)->format('Y-m-d');
                $end_date = \Carbon\Carbon::parse($end_date)->format('Y-m-d');
                $condition .= " AND DATE(p.date)  BETWEEN   '$start_date'  AND  '$end_date'  ";
            }

         
          $sWhere = $condition;
        

            if (!empty($divid)) {
                $sWhere .= " AND p.divid = $divid";
                $groupingValue = 'zillaname';
            }
    
            if (!empty($zillaid)) {
                $sWhere .= " AND p.zillaid = $zillaid";
                $groupingValue = 'upazilaname';
            }
    
            if (!empty($upozilaid)) {
                $sWhere .= " AND p.upazilaId = $upozilaid";
            }
    
            if (!empty($GeoCityCorporations)) {
                $sWhere .= " AND p.geo_citycorporation_id = $GeoCityCorporations";
            }
    
            if (!empty($GeoMetropolitan) && !empty($GeoThanas)) {
                $sWhere .= " AND p.geo_metropolitan_id = $GeoMetropolitan AND p.geo_thana_id = $GeoThanas";
            }
   
            // Magistrate condition
            $magCondition = "";
         

    
            if ($roleID == 26) {  // Magistrate Role
                $userinfo = Auth::user();
                $office_id = $userinfo->office_id;
                $officeinfo = DB::table('office')->where('id', $office_id)->first();
                $division_id = $officeinfo->division_id;
                $district_id = $officeinfo->district_id;
    
                $results = User::join('doptor_user_access_info', 'users.common_login_user_id', '=', 'doptor_user_access_info.common_login_user_id')
                    ->join('office', 'office.id', '=', 'users.office_id')
                    ->select('users.name', 'users.id as user_id', 'office.office_name_bn')
                    ->where('doptor_user_access_info.role_id', 26)
                    ->where('office.district_id', $district_id)
                    ->where('office.division_id', $division_id)
                    ->first();
    
                $mag_name = $userinfo->name;
                $user_id = $userinfo->id;
                 $magCondition .= "AND ma.id =$user_id ";
                $zilla_name = $results->office_name_bn;
            }

            // Final query execution
           
            $magCondition = "";

            if ($roleID == 26) {  //magistrate

                $userinfo=globalUserInfo();  // gobal user
                $office_id=globalUserInfo()->office_id;  // gobal user
                $officeinfo= DB::table('office')->where('id',$office_id)->first();
                $division_id=$officeinfo->division_id;
                $district_id=$officeinfo->district_id;
        
                $results = User::join('doptor_user_access_info', 'users.common_login_user_id', '=', 'doptor_user_access_info.common_login_user_id')
                ->join('office', 'office.id', '=', 'users.office_id')
                ->select('users.name','users.id as user_id','office.office_name_bn') // Adjust this as needed
                ->where('doptor_user_access_info.role_id',26)
                ->where('office.district_id',$district_id)
                ->where('office.division_id',$division_id)
                ->first();
                $magCondition .= "";

                $mag_name = Auth::user()->name;
                $user_id = Auth::user()->id;
                $magCondition .=" AND ma.id =$user_id ";
                $zilla_name = $results->office_name_bn;
            }
            $cc= $sWhere.'  '.$magCondition;
            $result = DB::select(
                DB::raw("
                    SELECT
                        DATE_FORMAT(p.date, '%Y-%m-%d') AS case_date,
                        p.case_no AS case_no,
                        ma.name AS court_name,
                        GROUP_CONCAT(cr.name_bng, '<hr>') AS crim_name,
                        GROUP_CONCAT(l.title, ' এর ' ,s.sec_number, '<hr>') AS law_details,
                        CONCAT(pn.receipt_no, '<hr>') AS receipt_no,
                        GROUP_CONCAT(pn.fine, '<hr>') AS collected_fine,
                        '' AS next_order,
                        z.district_name_bn AS zillaname,
                        d.division_name_bn AS divname
                    FROM prosecutions p
                    LEFT JOIN laws_brokens AS lb ON lb.prosecution_id = p.id
                    LEFT JOIN mc_law AS l ON l.id = lb.law_id
                    LEFT JOIN mc_section AS s ON lb.section_id = s.id AND l.id = s.law_id
                    LEFT JOIN users AS ma ON ma.id = p.magistrate_id
                    LEFT JOIN punishments AS pn ON p.id = pn.prosecution_id AND pn.laws_broken_id = lb.id
                    LEFT JOIN criminals AS cr ON cr.id = pn.criminal_id
                    LEFT JOIN division AS d ON d.id = p.divid
                    LEFT JOIN district AS z ON z.id = p.zillaId AND z.division_id = p.divid
                    LEFT JOIN upazila AS u ON u.district_id = p.zillaid AND u.id = p.upazilaid
                    LEFT JOIN geo_city_corporations AS gc ON gc.geo_division_id = p.divid AND gc.geo_district_id = p.zillaid AND gc.id = p.geo_citycorporation_id
                    LEFT JOIN geo_metropolitan AS m ON m.geo_division_id = p.divid AND m.geo_district_id = p.zillaid AND m.id = p.geo_metropolitan_id
                    WHERE $cc
                    GROUP BY p.court_id, p.id
                    ORDER BY d.division_name_bn ASC, z.district_name_bn ASC
                ")
            );
            
        
           $RegisterList = RegisterList::find($reportID);
           $reportName = $RegisterList->name;
        // }

        return response()->json([
            'result' => $result,
            'name' => $reportName,
            'registerLabelList' => $registerLabelList,
            'profileID' => $profilesId,
            'mag_name' => $mag_name,
            'groupingValue' => $groupingValue,
            'zilla_name' => $zilla_name
        ]);
    }


    public function  getUpazila(Request $request)
    {
       
        $childs = [];
        $city_corp = false;

            $zillaid = $request->query('ld', 'string');

            // check for city Corporation
            $zilla = DB::table('district')
                         ->join('geo_city_corporations', 'geo_city_corporations.geo_district_id', '=', 'district.id')
                         ->where('district.id', $zillaid)
                        ->first();
      
            if (!empty($zilla)) {
                $city_corp = true;
            }

            if ($zillaid) {
                $upazilas = DB::table('upazila')
                ->where('upazila.district_id', $zillaid)->get();
            }

            foreach ($upazilas as $upazila) {
                if (!$city_corp) {
                    $childs[] = [
                        'id' => $upazila->id, 
                        'name' => $upazila->upazila_name_bn, 
                        'title' => ""
                    ];
                } else {
                    $childs[] = [
                        'id' => $upazila->id, 
                        'name' => $upazila->upazila_name_bn, 
                        'title' => $upazila->upazila_name_en
                    ];
                }
            }

        return response()->json($childs);
    }

    public function getzilla(Request $request){
        $childs = [];
    
        // Check if the request is a POST and AJAX
        if ($request->isMethod('post') && $request->ajax()) {
            $divId = $request->query('ld'); // Get the query parameter
    
            $tmp = [];
            if ($divId) {
                $tmp = DB::table('district')->where('division_id', $divId)->get(); // Fetch Zilla records
            }
    
            foreach ($tmp as $t) {
                $childs[] = [
                    'id' => $t->id,
                    'name' => $t->district_name_bn
                ];
            }
        }
    
        // Return JSON response
        return response()->json($childs);
    }

    public function printprosecutionreport(Request $request){
        $childs = [];
        $reportName = "No data Found";
        $status = '';
        $registerLabelList = '';
        $complainStatus = '';
        $mag_name = "";
        $zilla_name = "";
        $result = [];
        $groupingValue = 'divname';
        $profilesId = auth()->user();
    
        // if ($request->isMethod('post') && $request->ajax()) {
            list($end_date, $start_date, $divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $reportID) = $this->getAjaxUrlParameter($request);
        
         
            $condition = "";
    
            // Get RegisterLabel List
            $registerLabelList = RegisterList::find($reportID)->labels()->get(['label']);
            $roleID = globalUserInfo()->role_id;
    
            // $session_arr = session('auth-identity');
            // $profile = $session_arr["profile"];
            // $prosecutor = auth()->user()->getProsecutor();
            $prosecutor_con = "";
    
            // For ADM/DM
            // if ($profile == "ADM" || $profile == "DM") {
            //     $divid = $session_arr["divid"];
            //     $zillaid = $session_arr["zillaid"];
            //     $groupingValue = 'zillaname';
            // } elseif ($profile == "Divisional Commissioner") {
            //     $divid = $session_arr["divid"];
            // }
   
            $office_id = globalUserInfo()->office_id;
            $officeinfo = DB::table('office')->where('id',$office_id)->first();
            $division_id = $officeinfo->division_id;
            $district_id = $officeinfo->district_id;
            // if (in_array($profile, ['ADM', 'DM'])) {
                if ($roleID == 37 || $roleID == 38) {
                $divid = $division_id;
                $zillaid = $district_id;
                $groupingValue = 'zillaname';
            }
            $magCondition = "";
            // For prosecutor
            if ( $roleID == 25) {
                // $userlocation = auth()->user()->getUserLocation();
                // $divid = $userlocation["divid"];
                // $zillaId = $userlocation["zillaid"];
                // $zilla_name = $userlocation["zillaname"];
                // $prosecutor_con .= " AND p.prosecutor_id = $prosecutor->id ";

                $userinfo=globalUserInfo();  // gobal user
                $office_id=globalUserInfo()->office_id;  // gobal user
                $officeinfo= DB::table('office')->where('id',$office_id)->first();
                $division_id=$officeinfo->division_id;
                $district_id=$officeinfo->district_id;
                
                $zilaname=DB::table('district')->where('district.id',$district_id)->where('district.division_id',$division_id)->first();
                $divid = $division_id;
                $zillaId = $district_id;
                $zilla_name = $zilaname->district_name_bn;
                $prosecutor_con .= "p.prosecutor_id = $userinfo->id";
                

            }
             
            // Set end date if empty
            if (empty($end_date)) {
                $end_date = now()->format("m/d/Y");
            }
    
            // Convert dates to Y-m-d format
            if (!empty($start_date) && !empty($end_date)) {
                $start_date = (new \DateTime($start_date))->format('Y-m-d');
                $end_date = (new \DateTime($end_date))->format('Y-m-d');
                $condition .= "p.is_suomotu = 0 AND p.delete_status = '1' AND DATE(p.date) BETWEEN '$start_date' AND  '$end_date' ";
            }
     
            $sWhere = "";
            if ($sWhere == "") {
                $sWhere = "$condition";
            }
            if (!empty($divid)) {
                $sWhere .= " AND p.divid = $divid";
                $groupingValue = 'zillaname';
            }
          
            if (!empty($zillaid)) {
                $sWhere .= " AND p.zillaid = $zillaid ";
                $groupingValue = 'upazilaname';
            }
            if (!empty($upozilaid)) {
                $sWhere .= " AND p.upazilaId = $upozilaid";
            }
            if (!empty($GeoCityCorporations)) {
                $sWhere .= " AND p.geo_citycorporation_id = $GeoCityCorporations";
            }
            if (!empty($GeoMetropolitan) && !empty($GeoThanas)) {
                $sWhere .= " AND p.geo_metropolitan_id = $GeoMetropolitan AND p.geo_thana_id = $GeoThanas";
            }
    
            // For magistrate
            if ($roleID == 26) {
                // $userlocation = auth()->user()->getUserLocation();
                // $zilla_name = $userlocation["zillaname"];
                // $joblocation = $userlocation["joblocation"] . "," . $zilla_name;
                // $mag_name = auth()->user()->getName();
                // $user_id = auth()->user()->getMagistrateUserId();
                // $sWhere .= " AND ma.user_id = $user_id";
                // $zilla_name = $joblocation;

                $userinfo=globalUserInfo();  // gobal user
                $office_id=globalUserInfo()->office_id;  // gobal user
                $officeinfo= DB::table('office')->where('id',$office_id)->first();
                $division_id=$officeinfo->division_id;
                $district_id=$officeinfo->district_id;
        
                $results = User::join('doptor_user_access_info', 'users.common_login_user_id', '=', 'doptor_user_access_info.common_login_user_id')
                ->join('office', 'office.id', '=', 'users.office_id')
                ->select('users.name','users.id as user_id','office.office_name_bn') // Adjust this as needed
                ->where('doptor_user_access_info.role_id',26)
                ->where('office.district_id',$district_id)
                ->where('office.division_id',$division_id)
                ->first();

                $magCondition .= "";

                $mag_name = Auth::user()->name;
                $user_id = Auth::user()->id;
                $magCondition .=" AND ma.id =$user_id ";
                $zilla_name = $results->office_name_bn;
            }
         
            // $phql = "
            //     SELECT
            //         p.id,
            //         DATE_FORMAT(p.date, '%Y-%m-%d') AS pdate,
            //         p.case_no,
            //         IFNULL(ma.name, '') AS mag_name,
            //         CONCAT(p.prosecutor_name, '-', p.prosecutor_details) AS pro_name,
            //         GROUP_CONCAT(l.title, ' এর ', s.sec_number, '<hr>') AS law_section_number,
            //         CONCAT(lb.Description) AS crime_description,
            //         GROUP_CONCAT(cr.name_bng, '<hr>') AS criminal_details,
            //         GROUP_CONCAT(pn.order_detail, '<hr>') AS order_detail,
            //         d.division_name_bn as divname,
            //         z.district_name_bn as zillaname
            //     FROM prosecutions p
            //     LEFT JOIN laws_brokens AS lb ON lb.prosecution_id = p.id
            //     LEFT JOIN mc_law AS l ON l.id= lb.law_id
            //     LEFT JOIN mc_section AS s ON lb.section_id = s.id AND l.id = s.law_id
            //     LEFT JOIN users AS ma on ma.id = p.magistrate_id
            //     LEFT JOIN punishments AS pn ON p.id = pn.prosecution_id AND pn.laws_broken_id = lb.id
            //     LEFT JOIN criminals AS cr ON pn.criminal_id = cr.id
            //     LEFT JOIN division AS d ON d.id = p.divid
            //     LEFT JOIN district AS z ON z.id = p.zillaId AND z.division_id = p.divid
            //     LEFT JOIN upazila AS u ON u.district_id = p.zillaid AND u.id = p.upazilaid
            //     LEFT JOIN geo_city_corporations AS gc ON gc.geo_division_id = p.divid AND gc.geo_district_id = p.zillaid AND gc.id = p.geo_citycorporation_id
            //     LEFT JOIN geo_metropolitan AS m ON m.geo_division_id = p.divid AND m.geo_district_id = p.zillaid AND m.id = p.geo_metropolitan_id
            //     WHERE $sWhere $prosecutor_con
            //     GROUP BY p.court_id, p.id
            //     ORDER BY d.division_name_bn, z.district_name_bn ASC
            // ";
         
            // $result = DB::select($phql);
            // dd( $results);
            $results1 = DB::table('prosecutions as p')
            ->select([
                'p.id',
                DB::raw('DATE_FORMAT(p.date, "%Y-%m-%d") AS pdate'),
                'p.case_no',
                DB::raw('IFNULL(ma.name, "") AS mag_name'),
                DB::raw('CONCAT(p.prosecutor_name, "-", p.prosecutor_details) AS pro_name'),
                DB::raw('GROUP_CONCAT(l.title, " এর ", s.sec_number, "<hr>") AS law_section_number'),
                DB::raw('CONCAT(lb.description) AS crime_description'),
                DB::raw('GROUP_CONCAT(cr.name_bng, "<hr>") AS criminal_details'),
                DB::raw('GROUP_CONCAT(pn.order_detail, "<hr>") AS order_detail'),
                'd.division_name_bn as divname',
                'z.district_name_bn as zillaname'
            ])
            ->leftJoin('laws_brokens as lb', 'lb.prosecution_id', '=', 'p.id')
            ->leftJoin('mc_law as l', 'l.id', '=', 'lb.law_id')
            ->leftJoin('mc_section as s', function ($join) {
                $join->on('lb.section_id', '=', 's.id')->on('l.id', '=', 's.law_id');
            })
            ->leftJoin('users as ma', 'ma.id', '=', 'p.magistrate_id')
            ->leftJoin('punishments as pn', function ($join) {
                $join->on('p.id', '=', 'pn.prosecution_id')->on('pn.laws_broken_id', '=', 'lb.id');
            })
            ->leftJoin('criminals as cr', 'pn.criminal_id', '=', 'cr.id')
            ->leftJoin('division as d', 'd.id', '=', 'p.divid')
            ->leftJoin('district as z', function ($join) {
                $join->on('z.id', '=', 'p.zillaid')->on('z.division_id', '=', 'p.divid');
            })
            ->leftJoin('upazila as u', function($join) {
                $join->on('u.district_id', '=', 'p.zillaid')->on('u.id', '=', 'p.upazilaid');
            })
            ->leftJoin('geo_city_corporations as gc', function($join) {
                $join->on('gc.geo_division_id', '=', 'p.divid')->on('gc.geo_district_id', '=', 'p.zillaid')
                    ->on('gc.id', '=', 'p.geo_citycorporation_id');
            })
            ->leftJoin('geo_metropolitan as m', function($join) {
                $join->on('m.geo_division_id', '=', 'p.divid')
                    ->on('m.geo_district_id', '=', 'p.zillaid')
                    ->on('m.id', '=', 'p.geo_metropolitan_id');
            })
            ->whereRaw($sWhere);
            if(!empty($prosecutor_con)){
                $results1->whereRaw($prosecutor_con);
            }
           
            $result = $results1->groupBy('p.court_id', 'p.id')
            ->orderBy('d.division_name_bn', 'asc')
            ->orderBy('z.district_name_bn', 'asc')
            ->get()
            ->map(function($item) {
                // Create an array with both indexed and associative keys
                $indexed = array_values((array) $item);
                $associative = (array) $item;
        
                // Combine both indexed and associative parts
                return array_merge($indexed, $associative);
            });

            
            $RegisterList = RegisterList::find($reportID);
            $reportName = $RegisterList->name;
             // Fallback to default name if not found
        // }
    


        return response()->json([
            "result" => $result,
            "name" => $reportName,
            "zilla_name" => $zilla_name,
            "registerLabelList" => $registerLabelList,
            "groupingValue" => $groupingValue,
            "profileID" => $roleID
        ]);
    }
}
