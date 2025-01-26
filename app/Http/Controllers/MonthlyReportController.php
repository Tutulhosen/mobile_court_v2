<?php

namespace App\Http\Controllers;

use App\Models\ReportList;
use Illuminate\Http\Request;
use App\Models\MonthlyReport;
use App\Models\GraphMisReport;
use App\Repositories\BanglaDate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\DashboardPromap;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Mail;

class MonthlyReportController extends Controller
{
    //

    public function report()
    {
        return view('monthly_report.report');
    }
    public function getMisReportList(Request $request){
        // $this->view->disable();
        $user = Auth::user();
        $reportlist=array();
        $reportlistdiv=array();
        $reportGraphList=array();
        $reportCountryList=array();
        $resultSet=array();

        $office_id = globalUserInfo()->office_id;
        $roleID = globalUserInfo()->role_id;
        $officeinfo = DB::table('office')->where('id',$office_id)->first();
     
        if($roleID ==37 || $roleID ==38){
            $reportlist = ReportList::all();
        }
        
        // if ($user->profilesId == 7 || $user->profilesId == 11|| $user->profilesId == 12 || $user->profilesId == 14) { // ADM DM
        //     $reportlist = ReportList::find();
        // } elseif ($user->profilesId == 4) { // Divisional Commissioner
        //     $reportlist = ReportList::find();
        // } elseif ($user->profilesId == 5 || $user->profilesId == 19) { //JS
        //     $reportlist = ReportList::find("id NOT IN ('7')");
        //     $reportlistdiv = ReportList::find("id NOT IN ('5','6','7')");
        //     $reportCountryList=CountryMisReport::find();
        // }
      
        $reportGraphList=GraphMisReport::all();
       
        $resultSet=array("zillaReportList"=>$reportlist,
                        "divisionReportList"=>$reportlistdiv,
                        "countryReportList"=>$reportCountryList,
                        "graphReportList"=>$reportGraphList
        );
        return response()->json( $resultSet );
       

    }

    public function printmobilecourtreport(Request $request){
        $resultset = array();
        $reportName = "No data Found";
        $result = array();
        $zilla_name = "";
        $start_date = "";
        $month_year = "";

            $reportID = $request->reportID;
            // $userlocation = $this->auth->getUserLocation();
            
            $office_id = globalUserInfo()->office_id;
            $user = Auth::user();
            $roleID = globalUserInfo()->role_id;
            $officeinfo = DB::table('office')
            ->leftJoin('district as zilla', function ($join) {
               $join->on('office.district_id', '=', 'zilla.id')
                    ->on('office.division_id', '=', 'zilla.division_id');
           })->where('office.id',$office_id)->first();
        
            $divid = $officeinfo->division_id;
            $zillaId = $officeinfo->district_id;
            $div_name = $officeinfo->div_name_bn;
            $zilla_name = $officeinfo->dis_name_bn;

        
            $report_date = $request->report_date;

            $report_date_array= explode("-",$report_date);
            $mth = $report_date_array[0];
            $yr = $report_date_array[1];

            $month_year = BanglaDate::get_bangla_monthbymumber($mth, $yr);


            $lastmonth = "01";
            $year = "";
            $lastyear = "";

            if ($mth == '01') { //
                $lastmonth = 12;
                $lastyear = (int)$yr - 1;
            } else {
                $lastmonth = (int)$mth - 1;
                $lastyear = (int)$yr;
            }
            $sWhere = "";
          
            // 7 11 12 14


             if($roleID == 37 || $roleID == 38){

                $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>জেলা প্রশাসকের কার্যালয়্যালয়,$zilla_name  </br>মোবাইল কোর্টের পরিচালনা সংক্রান্ত মাসিক প্রতিবেদন";
                if ($sWhere == "") {
                    // $sWhere = "zilla.division_id = $divid  AND zilla.id =  $zillaId ";
                    $sWhere = [
                        ['zilla.division_id', '=', $divid],
                        ['zilla.id', '=', $zillaId]
                    ];
                }

             }
    
            // if ($user->profilesId == 7 || $user->profilesId == 11|| $user->profilesId == 12 || $user->profilesId == 14) { // ADM DM

            //     $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>জেলা প্রশাসকের কার্যালয়্যালয়,$zilla_name  </br>মোবাইল কোর্টের পরিচালনা সংক্রান্ত মাসিক প্রতিবেদন";
            //     if ($sWhere == "") {
            //         $sWhere = "WHERE   zilla.divid = $divid  AND zilla.zillaid =  $zillaId ";
            //     }
            // } elseif ($user->profilesId == 4) { // Divisional Commissioner
            //     $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার </br>বিভাগীয় কমিশনারের কার্যালয়,$div_name </br>মোবাইল কোর্টের পরিচালনা সংক্রান্ত মাসিক প্রতিবেদন";
            //     if ($sWhere == "") {
            //         $sWhere = "WHERE   zilla.divid = $divid ";
            //     }
            // } elseif ($user->profilesId == 5) { //JS
            //     $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার </br>মন্ত্রিপরিষদ  বিভাগ</br>জেলা ম্যাজিস্ট্রেসি পরিবীক্ষণ অধিশাখা</br>মোবাইল কোর্টের পরিচালনা সংক্রান্ত মাসিক প্রতিবেদন";
            //     if ($sWhere == "") {
            //         $sWhere = "WHERE 1=1 ";
            //     }
            // }

            $sCondition = [
                ['monthly_reports.report_type_id', '=', 1],
                ['monthly_reports.is_approved', '=', 1]
            ];
            // $sSel = "
            //    SUM(IF( report_month = $mth  AND report_year = $yr , promap ,0  ) )promap,
            //    SUM(IF( report_month = $mth  AND report_year = $yr , upozila ,0 ))upozila,
            //    SUM(IF( report_month = $lastmonth AND report_year = $lastyear , case_total ,0 ) )case_total1,
            //    SUM(IF( report_month = $mth  AND report_year = $yr, case_total ,0 ))case_total2,
            //    SUM(IF( report_month = $lastmonth AND report_year = $lastyear , court_total, 0 ))  court_total1,
            //    SUM(IF( report_month = $mth AND report_year = $yr , court_total ,0 ))  court_total2,
            //    SUM(IF( report_month = $lastmonth AND report_year = $lastyear ,fine_total, 0 )) fine_total1,
            //    SUM(IF( report_month = $mth  AND report_year = $yr , fine_total  ,0 )) fine_total2,
            //    SUM(IF( report_month = $lastmonth  AND report_year = $lastyear , lockup_criminal_total , 0)) lockup_criminal_total1,
            //    SUM(IF( report_month = $mth  AND report_year = $yr , lockup_criminal_total ,0 ) ) lockup_criminal_total2,
            //    SUM(IF( report_month = $lastmonth  AND report_year = $lastyear , criminal_total , 0)) criminal_total1,
            //    SUM(IF( report_month = $mth  AND report_year = $yr, criminal_total ,0 ) ) criminal_total2";
               $selects = [
                DB::raw("SUM(IF(report_month = $mth AND report_year = $yr, promap, 0)) as promap"),
                DB::raw("SUM(IF(report_month = $mth AND report_year = $yr, upozila, 0)) as upozila"),
                DB::raw("SUM(IF(report_month = $lastmonth AND report_year = $lastyear, case_total, 0)) as case_total1"),
                DB::raw("SUM(IF(report_month = $mth AND report_year = $yr, case_total, 0)) as case_total2"),
                DB::raw("SUM(IF(report_month = $lastmonth AND report_year = $lastyear, court_total, 0)) as court_total1"),
                DB::raw("SUM(IF(report_month = $mth AND report_year = $yr, court_total, 0)) as court_total2"),
                DB::raw("SUM(IF(report_month = $lastmonth AND report_year = $lastyear, fine_total, 0)) as fine_total1"),
                DB::raw("SUM(IF(report_month = $mth AND report_year = $yr, fine_total, 0)) as fine_total2"),
                DB::raw("SUM(IF(report_month = $lastmonth AND report_year = $lastyear, lockup_criminal_total, 0)) as lockup_criminal_total1"),
                DB::raw("SUM(IF(report_month = $mth AND report_year = $yr, lockup_criminal_total, 0)) as lockup_criminal_total2"),
                DB::raw("SUM(IF(report_month = $lastmonth AND report_year = $lastyear, criminal_total, 0)) as criminal_total1"),
                DB::raw("SUM(IF(report_month = $mth AND report_year = $yr, criminal_total, 0)) as criminal_total2")
            ];
            
            // Execute the query using Laravel's Query Builder
            $resultset = DB::table('district as zilla')
                ->join('division', 'zilla.division_id', '=', 'division.id')
                ->leftJoin('monthly_reports', function($join) use ($sCondition) {
                    $join->on('monthly_reports.zillaid', '=', 'zilla.id')
                         ->where($sCondition);
                })
                ->select('division.division_name_bn as divname', 'division.id as divid', 'zilla.id as zillaid', 'zilla.district_name_bn as zillaname', 'monthly_reports.comment2', 'monthly_reports.comment1_str', 'monthly_reports.comment1')
                ->addSelect($selects)
                ->where($sWhere)
                ->groupBy('zilla.division_id', 'zilla.id')
                ->get();
 


            // $data = [
                // "result" => $resultset, 
                // "name" => $reportName,  
                // "profileID" => $roleID, 
                // "zilla_name" => $zilla_name, 
                // "month_year" => $month_year 
            // ];
            $formattedResult = $resultset->map(function ($item) {
                return [
                    0 => $item->divname,
                    1 => $item->divid,
                    2 => $item->zillaid,
                    3 => $item->zillaname,
                    4 => $item->comment2,
                    5 => $item->comment1_str,
                    6 => $item->comment1,
                    7 => $item->promap,
                    8 => $item->upozila,
                    9 => $item->case_total1,
                    10 => $item->case_total2,
                    11 => $item->court_total1,
                    12 => $item->court_total2,
                    13 => $item->fine_total1,
                    14 => $item->fine_total2,
                    15 => $item->lockup_criminal_total1,
                    16 => $item->lockup_criminal_total2,
                    17 => $item->criminal_total1,
                    18 => $item->criminal_total2,
                    'case_total1' => $item->case_total1,
                    'case_total2' => $item->case_total2,
                    'comment1' => $item->comment1,
                    'comment1_str' => $item->comment1_str,
                    'comment2' => $item->comment2,
                    'court_total1' => $item->court_total1,
                    'court_total2' => $item->court_total2,
                    'criminal_total1' => $item->criminal_total1,
                    'criminal_total2' => $item->criminal_total2,
                    'divid' => $item->divid,
                    'divname' => $item->divname,
                    'fine_total1' => $item->fine_total1,
                    'fine_total2' => $item->fine_total2,
                    'lockup_criminal_total1' => $item->lockup_criminal_total1,
                    'lockup_criminal_total2' => $item->lockup_criminal_total2,
                    'promap' => $item->promap,
                    'upozila' => $item->upozila,
                    'zillaid' => $item->zillaid,
                    'zillaname' => $item->zillaname
                ];
            });
            
        

            // Prepare the final response data
            $data = [
                "result" => $formattedResult, 
                "name" => $reportName,  
                "profileID" => $roleID, 
                "zilla_name" => $zilla_name, 
                "month_year" => $month_year 
            ];

            // dd($data);
            // Return a JSON response
        return response()->json($data, 200, ['Content-Type' => 'application/json; charset=UTF-8']);

        // $response = new \Phalcon\Http\Response();
        // $response->setContentType('application/json', 'UTF-8');
        // $response->setContent(json_encode(array("result" => $resultset,
        //     "name" => $reportName,
        //     "profileID" => $this->auth->getUser()->profilesId,
        //     "zilla_name" => $zilla_name,
        //     "month_year" => $month_year

        // )));
        // return $response;
    }

    public function printappealcasereport(Request $request){
        $resultset = array();
        $reportName = "No data Found";
        $result = array();
        $zilla_name = "";
        $start_date = "";
        $month_year = "";
        $reportID = $request->reportID;
        $office_id = globalUserInfo()->office_id;
        $user = Auth::user();
        $roleID = globalUserInfo()->role_id;
        $officeinfo = DB::table('office')
        ->leftJoin('district as zilla', function ($join) {
           $join->on('office.district_id', '=', 'zilla.id')
                ->on('office.division_id', '=', 'zilla.division_id');
       })->where('office.id',$office_id)->first();
    
        $divid = $officeinfo->division_id;
        $zillaId = $officeinfo->district_id;
        $div_name = $officeinfo->div_name_bn;
        $zilla_name = $officeinfo->dis_name_bn;
        $report_date = $request->report_date;

        $report_date_array= explode("-",$report_date);
        $mth = $report_date_array[0];
        $yr = $report_date_array[1];
        $month_year = BanglaDate::get_bangla_monthbymumber($mth, $yr);
        $lastmonth = "01";
        $year = "";
        $lastyear = "";

        if ($mth == '01') { //
            $lastmonth = 12;
            $lastyear = (int)$yr - 1;
        } else {
            $lastmonth = (int)$mth - 1;
            $lastyear = (int)$yr;
        }
        $sWhere = "";
        $sCondition = "";
        if($roleID == 37 || $roleID == 38){
            if ($sWhere == "") {
                // $sWhere = "WHERE   zilla.divid = $divid  AND zilla.zillaid =  $zillaId ";
                $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>জেলা প্রশাসকের কার্যালয়, $zilla_name</br>মোবাইল কোর্টের আইনের আওতায় দায়েরকৃত আপিল মামলার বিবরণ ";
                $sWhere = [
                    ['zilla.division_id', '=', $divid],
                    ['zilla.id', '=', $zillaId]
                ];
            }
        }
        
        $sCondition = [
            ['monthly_reports.report_type_id', '=', 2],
            ['monthly_reports.report_month', '=', $mth],
            ['monthly_reports.report_year', '=', $yr],
            ['monthly_reports.is_approved', '=', 1]
        ];

        $selects = [
            DB::raw("SUM(IF(promap, promap, 0)) as promap"),
            DB::raw("SUM(IF(promap_achive, promap_achive, 0)) as promap_achive"),
            DB::raw("SUM(IF(pre_case_incomplete, pre_case_incomplete, 0)) as pre_case_incomplete"),
            DB::raw("SUM(IF(case_submit, case_submit, 0)) as case_submit"),
            DB::raw("SUM(IF(case_total, case_total, 0)) as case_total"),
            DB::raw("SUM(IF(case_complete, case_complete, 0)) as case_complete"),
            DB::raw("SUM(IF(case_incomplete, case_incomplete, 0)) as case_incomplete"),
            DB::raw("SUM(IF(case_above1year, case_above1year, 0)) as case_above1year"),
            DB::raw("SUM(IF(case_above2year, case_above2year, 0)) as case_above2year"),
            DB::raw("SUM(IF(case_above3year, case_above3year, 0)) as case_above3year"),
        ];

        // Execute the query using Laravel's Query Builder
        $resultset = DB::table('district as zilla')
        ->join('division', 'zilla.division_id', '=', 'division.id')
        ->leftJoin('monthly_reports', function($join) use ($sCondition) {
            $join->on('monthly_reports.zillaid', '=', 'zilla.id')
                ->where($sCondition);
        })
        ->select('division.division_name_bn as divname', 'division.id as divid', 'zilla.id as zillaid', 'zilla.district_name_bn as zillaname', 'monthly_reports.comment2', 'monthly_reports.comment1_str', 'monthly_reports.comment1')
        ->addSelect($selects)
        ->where($sWhere)
        ->groupBy('zilla.division_id', 'zilla.id')
        ->get();
        
         
        // dd($resultset);
        $formattedResult = $resultset->map(function ($item) {
            return [
                0 => $item->zillaname, // 0: "বরিশাল"
                1 => $item->promap, // 1: "10"
                2 => $item->promap_achive, // 2: "4"
                3 => $item->zillaname, // 3: "বরগুনা" (assuming this is another zilla name)
                4 => null, // 4: null
                5 => null, // 5: null
                6 => null, // 6: null
                7 => $item->case_above1year, // 7: "0"
                8 => $item->case_above2year, // 8: "0"
                9 => $item->case_above3year, // 9: "0"
                10 => $item->case_complete, // 10: "0"
                11 => $item->case_incomplete, // 11: "0"
                12 => $item->case_submit, // 12: "0"
                13 => $item->case_total, // 13: "0"
                14 => null, // 14: "0" (could add other needed fields here)
                15 => null, // 15: "0" (as above)
                16 => null, // 16: "0" (as above)
                17 => null, // 17: "0" (as above)
                'case_above1year' => $item->case_above1year, // "0"
                'case_above2year' => $item->case_above2year, // "0"
                'case_above3year' => $item->case_above3year, // "0"
                'case_complete' => $item->case_complete, // "0"
                'case_incomplete' => $item->case_incomplete, // "0"
                'case_submit' => $item->case_submit, // "0"
                'case_total' => $item->case_total, // "0"
                'comment1' => null, // null
                'comment1_str' => null, // null
                'comment2' => null, // null
                'divid' => $item->divid, // "10"
                'divname' => $item->zillaname, // "বরিশাল"
                'pre_case_incomplete' => $item->pre_case_incomplete, // "0"
                'promap' => $item->promap, // "0"
                'promap_achive' => $item->promap_achive, // "0"
                'zillaid' => $item->zillaid, // "4"
                'zillaname' => $item->zillaname, // "বরগুনা"
            ];
        });


        $data = [
            "result" => $formattedResult, 
            "name" => $reportName,  
            "profileID" => 9, 
            "zilla_name" => $zilla_name, 
            "month_year" => $month_year 
        ];
        return response()->json($data, 200, ['Content-Type' => 'application/json; charset=UTF-8']);

    }

    public function printadmcasereport(Request $request){
        $resultset = array();
        $reportName = "No data Found";
        $result = array();
        $zilla_name = "";
        $start_date = "";
        $month_year = "";
        $reportID = $request->reportID;
        $office_id = globalUserInfo()->office_id;
        $user = Auth::user();
        $roleID = globalUserInfo()->role_id;
        $officeinfo = DB::table('office')
        ->leftJoin('district as zilla', function ($join) {
           $join->on('office.district_id', '=', 'zilla.id')
                ->on('office.division_id', '=', 'zilla.division_id');
       })->where('office.id',$office_id)->first();
    
        $divid = $officeinfo->division_id;
        $zillaId = $officeinfo->district_id;
        $div_name = $officeinfo->div_name_bn;
        $zilla_name = $officeinfo->dis_name_bn;
        $report_date = $request->report_date;
        $report_date_array= explode("-",$report_date);
        $mth = $report_date_array[0];
        $yr = $report_date_array[1];
        $month_year = BanglaDate::get_bangla_monthbymumber($mth, $yr);
        $lastmonth = "01";
        $year = "";

        if ($mth == '01') { //
            $lastmonth = (int)$mth - 1;
            $year = (int)$yr - 1;
        } else {
            $lastmonth = (int)$mth - 1;
        }

        $sWhere = "";
        $sCondition = "";
        if($roleID == 37 || $roleID == 38){
            if ($sWhere == "") {
               
                $sWhere = [
                    ['zilla.division_id', '=', $divid],
                    ['zilla.id', '=', $zillaId]
                ];
                $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br> জেলা প্রশাসকের কার্যালয়, $zilla_name</br>  অতিরিক্ত জেলা ম্যাজিস্ট্রেট আদালতের ফৌজদারী কার্যবিধির আওতাধীন মামলার বিবরণ";
            }
        }
        $sCondition = [
            ['monthly_reports.report_type_id', '=', 3],
            ['monthly_reports.report_month', '=', $mth],
            ['monthly_reports.report_year', '=', $yr],
            ['monthly_reports.is_approved', '=', 1]
        ];
     
        $sSel = "
        SUM(IF(promap, promap, 0)) AS promap,
        SUM(IF(promap_achive, promap_achive, 0)) AS promap_achive,
        SUM(IF(pre_case_incomplete, pre_case_incomplete, 0)) AS pre_case_incomplete,
        SUM(IF(case_submit, case_submit, 0)) AS case_submit,
        SUM(IF(case_total, case_total, 0)) AS case_total,
        SUM(IF(case_complete, case_complete, 0)) AS case_complete,
        SUM(IF(case_incomplete, case_incomplete, 0)) AS case_incomplete,
        SUM(IF(case_above1year, case_above1year, 0)) AS case_above1year,
        SUM(IF(case_above2year, case_above2year, 0)) AS case_above2year,
        SUM(IF(case_above3year, case_above3year, 0)) AS case_above3year";


        $resultset = DB::table('district as zilla')
        ->join('division', 'zilla.division_id', '=', 'division.id')
        ->leftJoin('monthly_reports', 'monthly_reports.zillaid', '=', 'zilla.id')
        ->select("division.division_name_bn as divname","division.id as divid","zilla.id as zillaid", "zilla.district_name_bn as zillaname","monthly_reports.comment2","monthly_reports.comment1_str", "monthly_reports.comment1")
        ->selectRaw($sSel)
        ->where($sWhere)
        ->groupBy('zilla.division_id', 'zilla.id')
        ->get();

        $formattedResult = $resultset->map(function ($item) {
            return [
                0 => $item->divname,                      // Division name
                1 => $item->promap,                       // Promap value
                2 => $item->pre_case_incomplete,          // Pre-case incomplete
                3 => $item->zillaname,                    // Zilla name
                4 => $item->comment2,                     // Comment 2
                5 => $item->comment1_str,                 // Comment 1 string
                6 => $item->comment1,                      // Comment 1
                7 => $item->case_submit,                   // Case submit
                8 => $item->promap_achive,                // Promap achieved
                9 => $item->pre_case_incomplete,          // Pre-case incomplete (redundant, can be adjusted)
                10 => $item->case_submit,                  // Case submit
                11 => $item->case_total,                   // Case total
                12 => $item->case_complete,                // Case complete
                13 => $item->case_incomplete,              // Case incomplete
                14 => $item->case_above1year,             // Cases above 1 year
                15 => $item->case_above2year,             // Cases above 2 years
                16 => $item->case_above3year,             // Cases above 3 years
                'case_above1year' => $item->case_above1year,
                'case_above2year' => $item->case_above2year,
                'case_above3year' => $item->case_above3year,
                'case_complete' => $item->case_complete,
                'case_incomplete' => $item->case_incomplete,
                'case_submit' => $item->case_submit,
                'case_total' => $item->case_total,
                'comment1' => $item->comment1,
                'comment1_str' => $item->comment1_str,
                'comment2' => $item->comment2,
                'divid' => $item->divid,
                'divname' => $item->divname,
                'pre_case_incomplete' => $item->pre_case_incomplete,
                'promap' => $item->promap,
                'promap_achive' => $item->promap_achive,
                'zillaid' => $item->zillaid,
                'zillaname' => $item->zillaname
            ];
        });


        $data = [
            "result" => $formattedResult, 
            "name" => $reportName,  
            "profileID" => 9, 
            "zilla_name" => $zilla_name, 
            "month_year" => $month_year 
        ];
        return response()->json($data, 200, ['Content-Type' => 'application/json; charset=UTF-8']);

    }

    public function printemcasereport(Request $request){
        $resultset = array();
        $reportName = "No data Found";
        $result = array();
        $zilla_name = "";
        $start_date = "";
        $month_year = "";
        $divname_name = "";
        $reportID = $request->reportID;
        $office_id = globalUserInfo()->office_id;
        $user = Auth::user();
        $roleID = globalUserInfo()->role_id;
        $officeinfo = DB::table('office')
        ->leftJoin('district as zilla', function ($join) {
           $join->on('office.district_id', '=', 'zilla.id')
                ->on('office.division_id', '=', 'zilla.division_id');
       })->where('office.id',$office_id)->first();
    
        $divid = $officeinfo->division_id;
        $zillaId = $officeinfo->district_id;
        $div_name = $officeinfo->div_name_bn;
        $zilla_name = $officeinfo->dis_name_bn;
        $report_date = $request->report_date;
        $report_date_array= explode("-",$report_date);
        $mth = $report_date_array[0];
        $yr = $report_date_array[1];

        $month_year = BanglaDate::get_bangla_monthbymumber($mth, $yr);
        $lastmonth = "01";
        $year = "";

        if ($mth == '01') { //
            $lastmonth = (int)$mth - 1;
            $year = (int)$yr - 1;
        } else {
            $lastmonth = (int)$mth - 1;
        }
        $sWhere = "";
        $sCondition = "";
        if($roleID == 37 || $roleID == 38){
            if ($sWhere == "") {
                // $sWhere = "WHERE   zilla.divid = $divid  AND zilla.zillaid =  $zillaId ";
                  $sWhere = [
                    ['zilla.division_id', '=', $divid],
                    ['zilla.id', '=', $zillaId]
                ];
                $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>জেলা প্রশাসকের কার্যালয়, $zilla_name</br>এক্সিকিউটিভ ম্যাজিস্ট্রেটের আদালতে ফৌজদারী কার্যবিধির আওতাধীন মামলার বিবরণ";
            }
        }
        // $sCondition = "AND report_type_id = 4 AND report_month = $mth AND report_year = $yr AND is_approved = 1";

        $sCondition = [
            ['report_type_id', '=', 4],
            ['report_month', '=', $mth],
            ['report_year', '=', $yr],
            ['is_approved', '=', 1]
        ];
        $sSel = "
        SUM(if(promap ,promap,0)) as promap,
        SUM(if(promap_achive ,promap_achive , 0)) as promap_achive,
        SUM(if(pre_case_incomplete ,pre_case_incomplete,0)) as pre_case_incomplete,
        SUM(if(case_submit ,case_submit,0)) as case_submit,
        SUM(if(case_total ,case_total, 0)) as case_total,
        SUM(if(case_complete , case_complete , 0)) as case_complete,
        SUM(if(case_incomplete , case_incomplete ,0 )) as case_incomplete,
        SUM(if(case_above1year , case_above1year , 0)) as case_above1year,
        SUM(if(case_above2year ,case_above2year , 0)) as case_above2year,
        SUM(if(case_above3year ,case_above3year,0)) as case_above3year";
        
        $resultset = DB::table('district as zilla')
        ->join('division', 'zilla.division_id', '=', 'division.id')
        ->leftJoin('monthly_reports', 'monthly_reports.zillaid', '=', 'zilla.id')
        ->select("division.division_name_bn as divname","division.id as divid","zilla.id as zillaid", "zilla.district_name_bn as zillaname","monthly_reports.comment2","monthly_reports.comment1_str", "monthly_reports.comment1")
        ->selectRaw($sSel)
        ->where($sWhere)
        ->where($sCondition)
        ->groupBy('zilla.division_id', 'zilla.id')
        ->get();
        $result = $resultset->map(function($item) {
            return [
                0 => $item->divname,                // Division name (e.g. "বরিশাল")
                1 => $item->divid,                  // Division ID (e.g. "10")
                2 => $item->zillaid,                // Zilla ID (e.g. "4")
                3 => $item->zillaname,              // Zilla name (e.g. "বরগুনা")
                4 => $item->comment2,               // Comment 2 (empty)
                5 => $item->comment1_str,           // Comment 1 string (e.g. " প্রমাপ অর্জিত হয়েছে")
                6 => $item->comment1,               // Comment 1
                7 => $item->promap,                 // Promap (e.g. "10")
                8 => $item->promap_achive,          // Promap achieved (e.g. "18")
                9 => $item->pre_case_incomplete,    // Pre-case incomplete (e.g. "666")
                10 => $item->case_submit,           // Case submit (e.g. "153")
                11 => $item->case_total,            // Case total (e.g. "819")
                12 => $item->case_complete,         // Case complete (e.g. "119")
                13 => $item->case_incomplete,       // Case incomplete (e.g. "700")
                14 => $item->case_above1year,       // Cases above 1 year (e.g. "215")
                15 => $item->case_above2year,       // Cases above 2 years (e.g. "10")
                16 => $item->case_above3year,       // Cases above 3 years (e.g. "3"),
                'divname' => $item->divname,               // Division name (e.g. "বরিশাল")
                'divid' => $item->divid,                   // Division ID (e.g. "10")
                'zillaid' => $item->zillaid,               // Zilla ID (e.g. "4")
                'zillaname' => $item->zillaname,           // Zilla name (e.g. "বরগুনা")
                'comment1' => $item->comment1,             // Comment 1
                'comment1_str' => $item->comment1_str,     // Comment 1 string
                'comment2' => $item->comment2,             // Comment 2
                'promap' => $item->promap,                 // Promap
                'promap_achive' => $item->promap_achive,   // Promap achieved
                'pre_case_incomplete' => $item->pre_case_incomplete, // Pre-case incomplete
                'case_submit' => $item->case_submit,       // Case submit
                'case_total' => $item->case_total,         // Case total
                'case_complete' => $item->case_complete,   // Case complete
                'case_incomplete' => $item->case_incomplete, // Case incomplete
                'case_above1year' => $item->case_above1year, // Cases above 1 year
                'case_above2year' => $item->case_above2year, // Cases above 2 years
                'case_above3year' => $item->case_above3year  // Cases above 3 years
            ];
        });

        $responseData = [
            "result" => $result,      // Your processed resultset
            "name" => $reportName,       // The report name
            "profileID" =>9,// $roleID,  // Fetching the user's profile ID
            "zilla_name" => $zilla_name, // Zilla name
            "month_year" => $month_year  // Month and Year
        ];
        // Returning the JSON response with UTF-8 encoding and setting the content type
        return response()->json($responseData, 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);

    }
    public function printcourtvisitreport(Request $request){
        $resultset = array();
        $reportName = "No data Found";
        $result = array();
        $zilla_name = "";
        $start_date = "";
        $month_year = "";
        $divname_name = "";

        $reportID = $request->reportID;
        $office_id = globalUserInfo()->office_id;
        $user = Auth::user();
        $roleID = globalUserInfo()->role_id;
        $officeinfo = DB::table('office')
        ->leftJoin('district as zilla', function ($join) {
           $join->on('office.district_id', '=', 'zilla.id')
                ->on('office.division_id', '=', 'zilla.division_id');
       })->where('office.id',$office_id)->first();

        $divid = $officeinfo->division_id;
        $zillaId = $officeinfo->district_id;
        $divname_name = $officeinfo->div_name_bn;
        $zilla_name = $officeinfo->dis_name_bn;
        $report_date = $request->report_date;

        $report_date_array= explode("-",$report_date);
        $mth = $report_date_array[0];
        $yr = $report_date_array[1];
        $month_year = BanglaDate::get_bangla_monthbymumber($mth, $yr);
        $lastmonth = "01";
        $year = "";

        if ($mth == '01') { //
            $lastmonth = (int)$mth - 1;
            $year = (int)$yr - 1;
        } else {
            $lastmonth = (int)$mth - 1;
        }

        $sWhere = "";
        $sCondition = "";
        if($roleID == 37 || $roleID == 38){ // ADM DM
            if ($sWhere == "") {
                // $sWhere = "WHERE   zilla.divid = $divid  AND zilla.zillaid =  $zillaId ";
                $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>জেলা প্রশাসকের কার্যালয়, $zilla_name</br> জেলা ম্যাজিস্ট্রেটগণ কর্তৃক এক্সিকিউটিভ ম্যাজিস্ট্রেটের আদালত পরিদর্শন ";
            
                $sWhere = [
                    ['zilla.division_id', '=', $divid],
                    ['zilla.id', '=', $zillaId]
                ];
            }
        }

    
        $sCondition = [
            ['report_type_id', '=', 5],
            ['report_month', '=', $mth],
            ['report_year', '=', $yr],
            ['is_approved', '=', 1]
        ];

        $sSel = " 1 as visit_promap,
                    if(visit_count ,visit_count ,0) as visit_count ";


        $resultset = DB::table('district as zilla')
        ->join('division', 'zilla.division_id', '=', 'division.id')
        ->leftJoin('monthly_reports', 'monthly_reports.zillaid', '=', 'zilla.id')
        ->select(
            'division.division_name_bn as divname',
            'division.id as divid',
            'zilla.id as zillaid',
            'zilla.district_name_bn as zillaname',
            'monthly_reports.comment2',
            'monthly_reports.comment1_str',
            'monthly_reports.comment1'
        )
        ->selectRaw($sSel) // Add the raw SQL for calculated fields
        ->where($sCondition) // Apply static conditions (report type, month, year, approval)
        ->where($sWhere) // Apply dynamic conditions (if any)
        ->groupBy('zilla.division_id', 'zilla.id') // Group results by division and zilla
        ->get();


        $result=$resultset->map(function ($item) {
            return [
                "0" => $item->divname,  // e.g., "বরিশাল"
                "1" => $item->divid,    // e.g., "10"
                "2" => $item->zillaid,  // e.g., "4"
                "3" => $item->zillaname, // e.g., "বরগুনা"
                "4" => $item->comment1, // e.g., null
                "5" => $item->comment1_str, // e.g., null
                "6" => $item->comment2, // e.g., null
                "7" => $item->visit_promap, // e.g., "1"
                "8" => $item->visit_count, // e.g., "0"
                "comment1" => $item->comment1,
                "comment1_str" => $item->comment1_str,
                "comment2" => $item->comment2,
                "divid" => $item->divid,
                "divname" => $item->divname,
                "visit_count" => $item->visit_count,
                "visit_promap" => $item->visit_promap,
                "zillaid" => $item->zillaid,
                "zillaname" => $item->zillaname,
            ];
        });



         

         $data=  [
                "result" => $result,
                "name" => $reportName,
                "profileID" =>"", // Assuming auth() is used in Laravel
                "zilla_name" => $zilla_name,
                "month_year" => $month_year
         ];
        return response()->json( $data);

    }

    public function printcaserecordreport(Request $request){
        $childs = array();
        $reportName = "No data Found";
        $result = array();
        $zilla_name = "";
        $start_date = "";
        $month_year = "";
        $divname_name = "";

        $reportID = $request->reportID;
        $office_id = globalUserInfo()->office_id;
        $user = Auth::user();
        $roleID = globalUserInfo()->role_id;
        $officeinfo = DB::table('office')
        ->leftJoin('district as zilla', function ($join) {
           $join->on('office.district_id', '=', 'zilla.id')
                ->on('office.division_id', '=', 'zilla.division_id');
       })->where('office.id',$office_id)->first();

        $divid = $officeinfo->division_id;
        $zillaId = $officeinfo->district_id;
        $divname_name = $officeinfo->div_name_bn;
        $zilla_name = $officeinfo->dis_name_bn;
        $report_date = $request->report_date;

        $report_date_array= explode("-",$report_date);
        $mth = $report_date_array[0];
        $yr = $report_date_array[1];
        $month_year = BanglaDate::get_bangla_monthbymumber($mth, $yr);
        $lastmonth = "01";
        $year = "";

        if ($mth == '01') { //
            $lastmonth = (int)$mth - 1;
            $year = (int)$yr - 1;
        } else {
            $lastmonth = (int)$mth - 1;
        }
        $sWhere = "";
        $sCondition = "";
        if($roleID == 37 || $roleID == 38){ // ADM DM
            if ($sWhere == "") {
                // $sWhere = "WHERE   zilla.divid = $divid  AND zilla.zillaid =  $zillaId ";
                $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>জেলা প্রশাসকের কার্যালয়, $zilla_name</br>জেলা ম্যাজিস্ট্রেটগণ কর্তৃক মোবাইল কোর্ট কেস রেকর্ড পর্যালোচনা সংক্রন্ত প্রতিবেদন";
            
                $sWhere = [
                    ['zilla.division_id', '=', $divid],
                    ['zilla.id', '=', $zillaId]
                ];
            }
        }

        // $sCondition = "AND report_type_id = 6 AND report_year = $yr AND report_month = $mth AND is_approved = 1";
        $sCondition = [
            ['monthly_reports.report_type_id', '=', 6],
            ['monthly_reports.report_year', '=',  $yr],
            ['monthly_reports.report_month', '=', $mth],
            ['monthly_reports.is_approved','=', 1]
        ];
        $sSel = "
                1 as caserecord_promap,
                SUM(if(caserecord_count ,caserecord_count,0)) as caserecord_count";

        $resultset = DB::table('district as zilla')
        ->join('division', 'zilla.division_id', '=', 'division.id')
        ->leftJoin('monthly_reports', 'monthly_reports.zillaid', '=', 'zilla.id')
        ->select(
            'division.division_name_bn as divname',
            'division.id as divid',
            'zilla.id as zillaid',
            'zilla.district_name_bn as zillaname',
            'monthly_reports.comment2',
            'monthly_reports.comment1_str',
            'monthly_reports.comment1'
        )
        ->selectRaw($sSel) // Add the raw SQL for calculated fields
        ->where($sCondition) // Apply static conditions (report type, month, year, approval)
        ->where($sWhere) // Apply dynamic conditions (if any)
        ->groupBy('zilla.division_id', 'zilla.id') // Group results by division and zilla
        ->get();

        $formattedResult = $resultset->map(function ($item) {
            return [
                "0" => $item->divname,           // e.g., "বরিশাল"
                "1" => $item->divid,             // e.g., "10"
                "2" => $item->zillaid,           // e.g., "4"
                "3" => $item->zillaname,         // e.g., "বরগুনা"
                "4" => $item->comment1,          // e.g., null
                "5" => $item->comment1_str,      // e.g., null
                "6" => $item->comment2,          // e.g., null
                "7" => $item->caserecord_promap, // e.g., "1"
                "8" => $item->caserecord_count,  // e.g., "0"
                "comment1" => $item->comment1,
                "comment1_str" => $item->comment1_str,
                "comment2" => $item->comment2,
                "divid" => $item->divid,
                "divname" => $item->divname,
                "caserecord_count" => $item->caserecord_count,
                "caserecord_promap" => $item->caserecord_promap,
                "zillaid" => $item->zillaid,
                "zillaname" => $item->zillaname,
            ];
        });
        
        // Return the formatted result
        $data=[
            "result" => $formattedResult,
            "name" => $reportName,
            "profileID" =>9,
            "zilla_name" => $zilla_name,
            "month_year" => $month_year
        ];
        return response()->json($data);
    

    }

    public function printapprovedreport(Request $request){
        $childs = array();
        $reportName = "No data Found";
        $result = array();
        $zilla_name = "";
        $start_date = "";
        $month_year = "";
        $divname_name = "";
        $reportID = $request->reportID;
        $office_id = globalUserInfo()->office_id;
        $user = Auth::user();
        $roleID = globalUserInfo()->role_id;
        $officeinfo = DB::table('office')
        ->leftJoin('district as zilla', function ($join) {
           $join->on('office.district_id', '=', 'zilla.id')
                ->on('office.division_id', '=', 'zilla.division_id');
       })->where('office.id',$office_id)->first();

        $divid = $officeinfo->division_id;
        $zillaId = $officeinfo->district_id;
        $div_name = $officeinfo->div_name_bn;
        $zilla_name = $officeinfo->dis_name_bn;

        $report_date = $request->report_date;
        $report_date_array= explode("-",$report_date);
        $mth = $report_date_array[0];
        $yr = $report_date_array[1];

        $month_year = BanglaDate::get_bangla_monthbymumber($mth, $yr);
        $sWhere = '';
        $sCondition = '';

        if ($roleID == 37 || $roleID == 38) {  // ADM DM
            if ($sWhere == "") {
                $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>জেলা প্রশাসকের কার্যালয়, $zilla_name</br>অনুমোদিত প্রতিবেদনের পরিসংখ্যান ";
                // $sWhere = "zilla.division_id = $divid AND zilla.id = $zillaId";
               
                $sWhere = [
                    'divid' => $divid,
                    'zillaid' => $zillaId,
                    'report_month' => $mth,
                    'report_year' => $yr,
                ];
            }
        }

        $sSel = "
        SUM(IF(report_type_id = 1, is_approved, 0)) AS re1,
        SUM(IF(report_type_id = 2, is_approved, 0)) AS re2,
        SUM(IF(report_type_id = 3, is_approved, 0)) AS re3,
        SUM(IF(report_type_id = 4, is_approved, 0)) AS re4,
        SUM(IF(report_type_id = 5, is_approved, 0)) AS re5,
        SUM(IF(report_type_id = 6, is_approved, 0)) AS re6
    ";
   // Query to fetch the results based on the dynamic selection and where conditions
$resultset = MonthlyReport::select(
    'divname',
    'zillaname',
    'comment2',
    'comment1_str',
    'comment1'
)
->selectRaw($sSel) // Add calculated fields
->where($sWhere)   // Apply dynamic conditions
->groupBy('divid') // Group results by division and zilla
->groupBy('zillaid') // Group results by division and zilla
->orderBy('report_type_id')          // Order results by report_type_id
->get();


    $formattedResult = $resultset->map(function ($item) {
        return [
            // Indexed keys
            $item->divname,         // 0
            $item->zillaname,       // 1
            "0",                     // 2 (assuming this is a static value)
            $item->comment1_str,    // 3
            $item->comment1,        // 4
            $item->divid,           // 5
            $item->zillaid,         // 6
            $item->re1,             // 7
            $item->re2,             // 8
            $item->re3,             // 9
            $item->re4,             // 10
            $item->re5,             // 11
            $item->re6,             // 12
            // Additional keys
            'comment1' => $item->comment1,
            'comment1_str' => $item->comment1_str,
            'comment2' => "0",      // Assuming static as well
            'divid' => $item->divid,
            'divname' => $item->divname,
            'zillaid' => $item->zillaid,
            'zillaname' => $item->zillaname,
            're1' => $item->re1,
            're2' => $item->re2,
            're3' => $item->re3,
            're4' => $item->re4,
            're5' => $item->re5,
            're6' => $item->re6,
        ];
    });

    // Return the formatted result
    $formattedResult->values()->all();
    $responseData = [
        "result" => $formattedResult,
        "name" => $reportName,
        "profileID" => 9, // Get the authenticated user's profile ID
        "zilla_name" => $zilla_name,
        "month_year" => $month_year,
    ];
    
    // Create and return a JSON response
    return response()->json($responseData, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
   
    }

    public function printmobilecourtstatisticreport(Request $request){
        $resultset = array();
        $reportName = "No data Found";
        $result = array();
        $zilla_name = "";
        $start_date = "";
        $month_year = "";
        $reportID = $request->reportID;
        $office_id = globalUserInfo()->office_id;
        $user = Auth::user();
        $roleID = globalUserInfo()->role_id;
        $officeinfo = DB::table('office')
        ->leftJoin('district as zilla', function ($join) {
           $join->on('office.district_id', '=', 'zilla.id')
                ->on('office.division_id', '=', 'zilla.division_id');
       })->where('office.id',$office_id)->first();

        $divid = $officeinfo->division_id;
        $zillaId = $officeinfo->district_id;
        $div_name = $officeinfo->div_name_bn;
        $zilla_name = $officeinfo->dis_name_bn;

        $report_date = $request->report_date;
        $report_date_array= explode("-",$report_date);
        $mth = $report_date_array[0];
        $yr = $report_date_array[1];

        $month_year = BanglaDate::get_bangla_monthbymumber($mth, $yr);
        $sWhere = "";
        if($roleID == 37 || $roleID == 38) { // ADM DM
            $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>জেলা প্রশাসকের কার্যালয়্যালয়,$zilla_name  </br>ই-মোবাইল কোর্ট সিস্টেমে নিষ্পত্তিকৃত মামলার প্রতিবেদন ";
            if ($sWhere == "") {
                $sWhere = [
                    'zilla.division_id' => $divid,
                    'zilla.id' => $zillaId
                ];
            }
        }
       
        $sCondition = [
            ['report_type_id', '=', 1],
            ['report_month', '=', $mth],
            ['report_year', '=', $yr],
            ['is_approved', '=', 1]
        ];
 

        // return $result;
        $results = DB::table('district as zilla')
        ->join('division', 'zilla.division_id', '=', 'division.id')
        ->leftJoin('monthly_reports', 'monthly_reports.zillaid', '=', 'zilla.id')
        ->select(
            'division.division_name_bn as divname',
            'division.id as divid',
            'zilla.id as zillaid',
            'zilla.district_name_bn as zillaname',
            'monthly_reports.comment2',
            'monthly_reports.comment1_str',
            'monthly_reports.comment1',
            DB::raw('SUM(IF(court_total, court_total, 0)) as court_total'),
            DB::raw('SUM(IF(case_total, case_total, 0)) as case_total'),
            DB::raw('SUM(IF(case_manual, case_manual, 0)) as case_manual'),
            DB::raw('SUM(IF(case_system, case_system, 0)) as case_system'),
            DB::raw('SUM(IF(case_complete, case_complete, 0)) as case_complete')
        )
        ->where('monthly_reports.report_type_id', 1)
        ->where('monthly_reports.report_year', $yr)
        ->where('monthly_reports.report_month', $mth)
        ->where('monthly_reports.is_approved', 1)
        ->where($sWhere) // Apply dynamic conditions
        // Grouping and Rollup
        ->groupBy('zilla.division_id', 'zilla.id')
                // ->withRollup()
                ->get();
        // $result = $query->get();
        // dd( $query);

        $data = $results->map(function ($item) {
            return [
                0 => $item->divname,
                1 => $item->divid,
                2 => $item->zillaid,
                3 => $item->zillaname,
                4 => null,
                5 => null,
                6 => null,
                7 => $item->court_total,
                8 => $item->case_manual,
                9 => $item->case_system,
                10 => $item->case_total,
                11 => $item->case_complete,
                "case_complete" => $item->case_complete,
                "case_manual" => $item->case_manual,
                "case_system" => $item->case_system,
                "case_total" => $item->case_total,
                "comment1" => null,
                "comment1_str" => null,
                "comment2" => null,
                "court_total" => $item->court_total,
                "divid" => $item->divid,
                "divname" => $item->divname,
                "zillaid" => $item->zillaid,
                "zillaname" => $item->zillaname,
            ];
        });
      
        return response()->json([
            "result" => $data,
            "name" => $reportName,
            "profileID" => 9, // Adjust according to your authentication setup
            "zilla_name" => $zilla_name,
            "month_year" => $month_year
        ]);

         
    }

    public function mobilecourtreport(Request $request){

          // Fetch user location from Auth or a helper method
        // $userlocation = Auth::user()->getUserLocation();
        $userinfo = globalUserInfo();
    
        $office_id = $userinfo->office_id;
        $officeinfo = DB::table('office')->where('id', $office_id)->first();
        // $division_id = $officeinfo->division_id;
        // $district_id = $officeinfo->district_id;

        $user=DB::table('users as mag')
        ->join('office as job_des', 'job_des.id', '=', 'mag.office_id')
        ->select("*")
        ->where('job_des.id', $office_id)
        ->first();
       
    
        // Check if the request is a POST method
        if ($request->isMethod('post')) {
            //   dd($request->all());
       

            // Check if a report ID is provided to update an existing report
            // $report = new MonthlyReport();
            $report_id = $request->id;

            // if ($report_id) {
            //     $monthlyReport = MonthlyReport::find($report_id);
            //     $report->id = $monthlyReport->id;
            // } else {
            //     $report->id = null; // Laravel handles auto-increment for the 'id' field
            // }
            if ($report_id) {
             
                $report = MonthlyReport::find($report_id);
                $report->id = $report->id;
            } else {
                $report = new MonthlyReport();
                $report->id = null; // Laravel handles auto-increment for the 'id' field
            }

          
            // Map request inputs to report attributes
            $report->promap = $request->promap;
         
            $report->case_total = $request->case_total;
            $report->case_manual = $request->case_manual;
            $report->case_system = $request->case_system;
            $report->court_manual = $request->court_manual;
            $report->court_system = $request->court_system;
            $report->court_total = $request->court_total;
            $report->fine_manual = $request->fine_manual;
            $report->fine_system = $request->fine_system;
            $report->fine_total = $request->fine_total;
            $report->criminal_manual = $request->criminal_manual;
            $report->criminal_system = $request->criminal_system;
            $report->criminal_total = $request->criminal_total;
            $report->lockup_criminal_manual = $request->lockup_criminal_manual;
            $report->lockup_criminal_system = $request->lockup_criminal_system;
            $report->lockup_criminal_total = $request->lockup_criminal_total;
            $report->case_submit = $request->case_submit;
            $report->case_complete = $request->case_complete;
            $report->case_incomplete = $request->case_incomplete;
            $report->pre_case_incomplete = $request->pre_case_incomplete;
            $report->promap_achive = $request->promap_achive;
            $report->visit_promap = $request->visit_promap;
            $report->visit_count = $request->visit_count;
            $report->caserecord_promap = $request->caserecord_promap;
            $report->caserecord_count = $request->caserecord_count;
            $report->comment1 = $request->comment1;
            $report->comment2 = $request->comment2;
            $report->report_type_id = $request->report_type_id;

            // Handle report date parsing
            $report_date = $request->report_date;
            $report_date_array = explode("-", $report_date);
            $mth = $report_date_array[0];
            $yr = $report_date_array[1];
            $report->report_month = $mth;
            $report->report_year = $yr;

            // Set location and other details
            $report->divid = $user->division_id;
            $report->divname = $user->div_name_bn;
            $report->zillaid = $user->district_id;
            $report->zillaname =$user->dis_name_bn;
            $report->upozila = $request->upozila;

            // Set system fields
            $report->created_by = $userinfo->name;
            $report->created_date = date('Y-m-d');
            // $report->update_by = $userinfo->name;
            // $report->update_date = date('Y-m-d');
            // $report->delete_status = 1;
            $report->system_triger_date = date('Y-m-d');
            $report->system_triger_user = 'Jafrin';
            $report->is_approved = '';
            // Save report
            if (!$report->save()) {
                return redirect()->back()->with('error', 'প্রতিবেদন সংরক্ষণ করতে ব্যর্থ হয়েছে |');
            } else {
                return redirect()->back()->with('success', 'প্রতিবেদন সফলভাবে সংরক্ষণ করা হয়েছে |');
            }
        }

        // Return the view for the report form
        return view('monthly_report.mobilecourtreport');
    }
    public function appealcasereport(Request $request){

        $msg = "";

        $userinfo = globalUserInfo();
    
        $office_id = $userinfo->office_id;
        $officeinfo = DB::table('office')->where('id', $office_id)->first();
        // $division_id = $officeinfo->division_id;
        // $district_id = $officeinfo->district_id;

        $user=DB::table('users as mag')
        ->join('office as job_des', 'job_des.id', '=', 'mag.office_id')
        ->select("*")
        ->where('job_des.id', $office_id)
        ->first();
        if ($request->isMethod('post')) {
            // $report = new MonthlyReport();
            $report_id = $request->id;

            // if ($report_id) {
            //     $monthlyReport = MonthlyReport::find($report_id);
            //     $report->id = $monthlyReport->id;
            // } else {
            //     $report->id = null; // Laravel handles auto-increment for the 'id' field
            // }
            if ($report_id) {
             
                $report = MonthlyReport::find($report_id);
                $report->id = $report->id;
            } else {
                $report = new MonthlyReport();
                $report->id = null; // Laravel handles auto-increment for the 'id' field
            }

            $report->promap = $request->promap;
            $report->case_total = $request->case_total;
            $report->case_submit = $request->case_submit;
            $report->case_complete = $request->case_complete;
            $report->case_incomplete = $request->case_incomplete;
            $report->pre_case_incomplete = $request->pre_case_incomplete;
            $report->promap_achive = $request->promap_achive;

            $report->case_above1year = $request->case_above1year;
            $report->case_above2year = $request->case_above2year;
            $report->case_above3year = $request->case_above3year;

            $comment1_type_val = $request->comment1;
            if ($comment1_type_val == 1) {
                $report->comment1_str = "প্রমাপ অর্জিত হয়েছে";
            } elseif ($comment1_type_val == 2) {
                $report->comment1_str = "প্রমাপ অর্জিত হয়নি";
            }

            $report->comment1 = $comment1_type_val;
            $report->comment2 = $request->comment2;

            $report->report_type_id = $request->report_type_id;
            $report_type_id = $request->report_type_id;

            $report_date = $request->report_date;
            $report_date_array = explode("-", $report_date);
            $mth = $report_date_array[0];
            $yr = $report_date_array[1];

            $report->report_month = $mth;
            $report->report_year = $yr;

            $report->divid = $user->division_id;
            $report->divname = $user->div_name_bn;
            $report->zillaid = $user->district_id;
            $report->zillaname =$user->dis_name_bn;
   

            $report->created_by = $userinfo->name;
            $report->created_date = now(); // Using Carbon for the current date

            $report->system_triger_date = now();
            $report->system_triger_user = 'Jafrin';
            $report->update_status = "0";
            $report->is_approved = '';
            if ($report->save()) {
                return redirect()->back()->with('success', 'প্রতিবেদন সফলভাবে সংরক্ষণ করা হয়েছে');
            } else {
                return redirect()->back()->withErrors($report->getErrors());
            }
        }
        return view('monthly_report.appealcasereport');
    }
    public function admcasereport(Request $request){
        $userinfo = globalUserInfo();
    
        $office_id = $userinfo->office_id;
        $officeinfo = DB::table('office')->where('id', $office_id)->first();
        // $division_id = $officeinfo->division_id;
        // $district_id = $officeinfo->district_id;

        $user=DB::table('users as mag')
        ->join('office as job_des', 'job_des.id', '=', 'mag.office_id')
        ->select("*")
        ->where('job_des.id', $office_id)
        ->first();

        if ($request->isMethod('post')) {

            // Initialize or find the report
            // $report = new MonthlyReport();
            $report_id = $request->id;

            // if ($report_id) {
            //     $monthlyReport = MonthlyReport::find($report_id);
            //     $report->id = $monthlyReport->id;
            // } else {
            //     $report->id = null; // Laravel handles auto-increment for the 'id' field
            // }
            if ($report_id) {
             
                $report = MonthlyReport::find($report_id);
                $report->id = $report->id;
            } else {
                $report = new MonthlyReport();
                $report->id = null; // Laravel handles auto-increment for the 'id' field
            }
            // Set report fields
            $report->promap =  $request->promap;
            $report->case_total =  $request->case_total;
            $report->case_submit =  $request->case_submit;
            $report->case_complete =  $request->case_complete;
            $report->case_incomplete =  $request->case_incomplete;
            $report->pre_case_incomplete =  $request->pre_case_incomplete;
            $report->promap_achive =  $request->promap_achive;
            $report->case_above1year =  $request->case_above1year;
            $report->case_above2year =  $request->case_above2year;
            $report->case_above3year =  $request->case_above3year;
    
            // Handle comment1 and comment1_str
            $comment1_type_val =  $request->comment1;
            $report->comment1_str = $comment1_type_val == 1 ? "প্রমাপ অর্জিত হয়েছে" :
                                    ($comment1_type_val == 2 ? "প্রমাপ অর্জিত হয়নি" : "প্রমাপ অর্জিত হয়েছে");
            $report->comment1 = $comment1_type_val;
    
            // Other comments
            $report->comment2 = $request->comment2;
            $report->report_type_id =  $request->report_type_id;
    
            // Report date handling
            $report_date = $request->report_date;
            $report_date_array = explode("-", $report_date);
            $report->report_month = $report_date_array[0];
            $report->report_year = $report_date_array[1];
    
            // User location data
            $report->divid = $user->division_id;
            $report->divname = $user->div_name_bn;
            $report->zillaid = $user->district_id;
            $report->zillaname =$user->dis_name_bn;
    
            // System information
            $report->created_by = $userinfo->name; // Ideally, this should be dynamic or taken from the logged-in user
            $report->created_date = now();  // Laravel’s helper for the current date
    
            $report->system_triger_date = now();
            $report->system_triger_user = 'Jafrin';
            $report->is_approved = '';
            // Save the report
            if ($report->save()) {
                return redirect()->back()->with('success', 'প্রতিবেদন সফলভাবে সংরক্ষণ করা হয়েছে');
            } else {
                return redirect()->back()->withErrors($report->getMessages());
            }
        }
        
        return view('monthly_report.admcasereport');
    }
    public function courtvisitreport(Request $request){
        $userinfo = globalUserInfo();
    
        $office_id = $userinfo->office_id;
        $officeinfo = DB::table('office')->where('id', $office_id)->first();
        // $division_id = $officeinfo->division_id;
        // $district_id = $officeinfo->district_id;

        $user=DB::table('users as mag')
        ->join('office as job_des', 'job_des.id', '=', 'mag.office_id')
        ->select("*")
        ->where('job_des.id', $office_id)
        ->first();


        if ($request->isMethod('post')) {
            // $report = new MonthlyReport();
            $report_id = $request->id;

            // if ($report_id) {
            //     $monthlyReport = MonthlyReport::find($report_id);
            //     $report->id = $monthlyReport->id;
            // } else {
            //     $report->id = null; // Laravel handles auto-increment for the 'id' field
            // }
    
            if ($report_id) {
             
                $report = MonthlyReport::find($report_id);
                $report->id = $report->id;
            } else {
                $report = new MonthlyReport();
                $report->id = null; // Laravel handles auto-increment for the 'id' field
            }
    
            $report->visit_promap = $request->visit_promap;
            $report->visit_count = $request->visit_count;
    
            $comment1TypeVal = $request->comment1;
    
            switch ($comment1TypeVal) {
                case 1:
                    $report->comment1_str = "প্রমাপ অর্জিত হয়েছে";
                    break;
                case 2:
                    $report->comment1_str = "প্রমাপ অর্জিত হয়নি";
                    break;
                default:
                    $report->comment1_str = "প্রমাপ অর্জিত হয়েছে";
            }
    
            $report->comment1 = $comment1TypeVal;
            $report->comment2 = $request->comment2;
    
            $report->report_type_id = $request->report_type_id;
    
            // Report Date
            $reportDate = $request->report_date;
            [$mth, $yr] = explode("-", $reportDate);
    
            $report->report_month = $mth;
            $report->report_year = $yr;
    
            // User Location Details
            $report->divid = $user->division_id;
            $report->divname = $user->div_name_bn;
            $report->zillaid = $user->district_id;
            $report->zillaname =$user->dis_name_bn;
    
            // Meta Information
            $report->created_by = $userinfo->name;
            $report->created_date = now();
            $report->system_triger_date = now();
            $report->system_triger_user = 'Jafrin';
            $report->is_approved = '';
            // Save the report and handle success/error messages
            if ($report->save()) {
                return redirect()->back()->with('success', 'প্রতিবেদন সফলভাবে সংরক্ষণ করা হয়েছে');
            } else {
                return redirect()->back()->withErrors($report->getErrors());
            }
        }

        
        return view('monthly_report.courtvisitreport');
    }
    public function caserecordreport(Request $request){
        $userinfo = globalUserInfo();
    
        $office_id = $userinfo->office_id;
        $officeinfo = DB::table('office')->where('id', $office_id)->first();
        // $division_id = $officeinfo->division_id;
        // $district_id = $officeinfo->district_id;

        $user=DB::table('users as mag')
        ->join('office as job_des', 'job_des.id', '=', 'mag.office_id')
        ->select("*")
        ->where('job_des.id', $office_id)
        ->first();
     
        if ($request->isMethod('post')) {
            // Check if an ID was provided to update an existing report
            // $report = new MonthlyReport();
            $report_id = $request->id;

            // if ($report_id) {
            //     $monthlyReport = MonthlyReport::find($report_id);
            //     $report->id = $monthlyReport->id;
            // } else {
            //     $report->id = null; // Laravel handles auto-increment for the 'id' field
            // }
            if ($report_id) {
             
                $report = MonthlyReport::find($report_id);
                $report->id = $report->id;
            } else {
                $report = new MonthlyReport();
                $report->id = null; // Laravel handles auto-increment for the 'id' field
            }

            // Fill in report fields
            $report->caserecord_promap =  $request->caserecord_promap;
            $report->caserecord_count =  $request->caserecord_count;
            
            // Set comment1_str based on comment1 value
            $comment1_type_val =  $request->comment1;
            if ($comment1_type_val == 1) {
                $report->comment1_str = " প্রমাপ অর্জিত হয়েছে";
            } elseif ($comment1_type_val == 2) {
                $report->comment1_str = "প্রমাপ অর্জিত হয়নি";
            } else {
                $report->comment1_str = " প্রমাপ অর্জিত হয়েছে";
            }

            $report->comment1 = $comment1_type_val;
            $report->comment2 = $request->comment2;
            $report->report_type_id =  $request->report_type_id;

            // Extract month and year from the report_date field
            $report_date = $request->report_date;
            $report_date_array = explode('-', $report_date);
            $mth = $report_date_array[0];
            $yr = $report_date_array[1];

            $report->report_month = $mth;
            $report->report_year = $yr;

            // Set user location data
            $report->divid = $user->division_id;
            $report->divname = $user->div_name_bn;
            $report->zillaid = $user->district_id;
            $report->zillaname =$user->dis_name_bn;

            // Add created_by and system trigger information
            $report->created_by = 'Jafrin';  // Adjust this according to your application logic
            $report->created_date = now();
            $report->system_triger_date = now();
            $report->system_triger_user = 'Jafrin';  // Adjust accordingly
            $report->is_approved = '';
            // Save the report and check for success
            if (!$report->save()) {
                return redirect()->back()->withErrors($report->getMessages());
            } else {
                return redirect()->back()->with('success', 'প্রতিবেদন সফলভাবে সংরক্ষণ করা হয়েছে');
            }
        }
        return view('monthly_report.caserecordreport');
    }
    public function emcasereport(Request $request){
        
        $userinfo = globalUserInfo();
        // dd($userinfo);
        $office_id = $userinfo->office_id;
        $officeinfo = DB::table('office')->where('id', $office_id)->first();
        // $division_id = $officeinfo->division_id;
        // $district_id = $officeinfo->district_id;

        $user=DB::table('users as mag')
        ->join('office as job_des', 'job_des.id', '=', 'mag.office_id')
        ->select("*")
        ->where('job_des.id', $office_id)
        ->first();
        if ($request->isMethod('post')) {
       
            // Find report or create new instance
            // $report = new MonthlyReport();
            $report_id = $request->id;

            // if ($report_id) {
            //     $monthlyReport = MonthlyReport::find($report_id);
            //     $report->id = $monthlyReport->id;
            // } else {
            //     $report->id = null; // Laravel handles auto-increment for the 'id' field
            // }
            if ($report_id) {
             
                $report = MonthlyReport::find($report_id);
                $report->id = $report->id;
            } else {
                $report = new MonthlyReport();
                $report->id = null; // Laravel handles auto-increment for the 'id' field
            }
    
            // Set report fields
            $report->promap = $request->promap;
            $report->case_total = $request->case_total;
            $report->case_submit = $request->case_submit;
            $report->case_complete = $request->case_complete;
            $report->case_incomplete = $request->case_incomplete;
            $report->pre_case_incomplete = $request->pre_case_incomplete;
            $report->promap_achive = $request->promap_achive;
            $report->case_above1year = $request->case_above1year;
            $report->case_above2year = $request->case_above2year;
            $report->case_above3year = $request->case_above3year;
    
            // Handle comment1_str based on comment1 type
            $comment1TypeVal = $request->comment1;
            if ($comment1TypeVal == 1) {
                $report->comment1_str = 'প্রমাপ অর্জিত হয়েছে';
            } elseif ($comment1TypeVal == 2) {
                $report->comment1_str = 'প্রমাপ অর্জিত হয়নি';
            } else {
                $report->comment1_str = 'প্রমাপ অর্জিত হয়েছে';
            }
    
            $report->comment1 = $comment1TypeVal;
            $report->comment2 = $request->comment2;
    
            // Set report type and date
            $report->report_type_id =$request->report_type_id;
    
            // Handle report date
            $reportDate = $request->report_date;
            if (!empty($reportDate)) {
                list($mth, $yr) = explode('-', $reportDate);
                $report->report_month = $mth;
                $report->report_year = $yr;
            }
    
            // Set user location data
            $report->divid = $user->division_id;
            $report->divname = $user->div_name_bn;
            $report->zillaid = $user->district_id;
            $report->zillaname =$user->dis_name_bn;
    
            // Set other fields
            $report->created_by = $userinfo->name;
            $report->created_date = date('Y-m-d');
            $report->system_triger_date = date('Y-m-d');
            $report->system_triger_user = 'Jafrin';
            $report->is_approved = '';
            
    
            // Save report
            if ($report->save()) {
                return redirect()->back()->with('success', 'প্রতিবেদন সফলভাবে সংরক্ষণ করা হয়েছে');
            } else {
                return redirect()->back()->withErrors($report->getErrors());
            }
        }
        return view('monthly_report.emcasereport');
    }


    public function getdata(Request $request){
        $childs = array();
        $userinfo = globalUserInfo();
        $office_id = $userinfo->office_id;
        $officeinfo = DB::table('office')->where('id', $office_id)->first();
        // $division_id = $officeinfo->division_id;
        // $district_id = $officeinfo->district_id;

        $user=DB::table('users as mag')
        ->join('office as job_des', 'job_des.id', '=', 'mag.office_id')
        ->select("*")
        ->where('job_des.id', $office_id)
        ->first();
        
        $flag = "";
        $flagCA = "=";
        $flagP = "";
        $flagPA = "";
        $promap_res = 0;

        $divid = $user->division_id;
        $divname =  $user->div_name_bn;
        $zillaid =$user->district_id;;
        $zillaname = $user->dis_name_bn;


        $upazila = DB::table('upazila')->where("district_id",$zillaid)->get(); //find("zillaid = " . $zillaid);

        $upazillano = count($upazila);

        $report_date = $request->date;
        $report_type_id = $request->report_type_id;
        $report_date_array= explode("-",$report_date);
        $mth = $report_date_array[0];
        $yr = $report_date_array[1];
        
        $resultData= $this->getCountOfCourtCaseFineCriminalJailCriminal($mth,$yr,$zillaid);
   
        $sWhere = "";
        if ($sWhere == "") {
            $sWhere = 'WHERE monthly_reports.report_type_id  = "' . $report_type_id . '" AND  monthly_reports.report_month = "' . $mth . '"  AND  monthly_reports.report_year = "' . $yr . '" AND monthly_reports.zillaid  = "' . $zillaid . '"';
        }
        // Building the query
        $query = DB::table('monthly_reports')
        ->select(
            'id',
            'upozila',
            'promap',
            'promap_achive',
            'criminal_manual',
            'criminal_system',
            'criminal_total',
            'lockup_criminal_manual',
            'lockup_criminal_system',
            'lockup_criminal_total',
            'fine_manual',
            'fine_system',
            'fine_total',
            'court_manual',
            'court_system',
            'court_total',
            'case_total',
            'case_manual',
            'case_system',
            'case_submit',
            'case_complete',
            'case_incomplete',
            'pre_case_incomplete',
            'visit_promap',
            'visit_count',
            'caserecord_promap',
            'caserecord_count',
            'comment1',
            'comment2',
            'comment_from_adm',
            'case_above1year',
            'case_above2year',
            'case_above3year',
            'update_status',
            'is_approved'
        )
        ->where('report_type_id', $report_type_id)
        ->where('report_month', $mth)
        ->where('report_year', $yr)
        ->where('zillaid',$zillaid);

        // Execute the query
        $monthlyreport = $query->get();
       
        $promap_type = "";
        if($report_type_id == 1){
            $promap_type = "promap.promap";
        }elseif($report_type_id == 2){
            $promap_type = "promap.appeal_promap";
        }elseif($report_type_id == 3){
            $promap_type = "promap.adm_promap";
        }elseif($report_type_id == 4){
            $promap_type = "promap.em_promap";
        }elseif($report_type_id == 5){
            $promap_type = "promap.visitoranalysis_promap";
        }elseif($report_type_id == 6){
            $promap_type = "promap.visitoranalysis_promap";
        }
        if (count($monthlyreport) > 0) {
        
            if($report_type_id  == 1){
                // $sQuery = "
                //     SELECT promap   as promap
                //     FROM  Mcms\Models\Promap as promap
                //     WHERE promap.divid =  $divid  AND promap.zillaid =  $zillaid
                //     ";

                // $query = $this->modelsManager->createQuery($sQuery);
                // $res = $query->execute();
                $res = DB::table('promap')
                        ->select('promap')  // Select the 'promap' column
                        ->where('divid', $divid)  // Add the condition for 'divid'
                        ->where('zillaid', $zillaid)  // Add the condition for 'zillaid'
                        ->get();  // Execute the query and get the result

                        $promap_res = $res->isNotEmpty() ? $res[0]->promap : null;  // Get the first result's 'promap' value or null if no results;

            }else{
                $promap_res = DashboardPromap::calculatePromap($monthlyreport[0]->pre_case_incomplete,$monthlyreport[0]->case_incomplete,$report_type_id);

            }
           
            if($monthlyreport[0]->is_approved ==  1){
                $flagCA = "yes";
                $flag = "NO";
            }else{
                $flagCA = "NO";
                $flag = "yes";
            }
           
            $childs[] = array(
                'flag' => $flag,
                'flagP' => $flagP,
                'flagPA' => $flagPA,
                'flagCA' => $flagCA,
                'id' => $monthlyreport[0]->id,
                'upozila' => $upazillano,
                'promap' => $promap_res,
                'promap_achive' => $monthlyreport[0]->promap_achive,
                'criminal_manual' => $monthlyreport[0]->criminal_manual,
                'criminal_system' => $resultData["criminal_system"],
                'criminal_total' => $monthlyreport[0]->criminal_total,
                'lockup_criminal_manual' => $monthlyreport[0]->lockup_criminal_manual,
                'lockup_criminal_system' => $resultData["lockup_criminal_system"],
                'lockup_criminal_total' => $monthlyreport[0]->lockup_criminal_total,
                'fine_manual' => $monthlyreport[0]->fine_manual,
                'fine_system' => $resultData["fine_system"],
                'fine_total' => $monthlyreport[0]->fine_total,
                'court_manual' => $monthlyreport[0]->court_manual,
                'court_system' => $monthlyreport[0]->court_system,
                'court_total' => $monthlyreport[0]->court_total,
                'case_total' => $monthlyreport[0]->case_total,
                'case_manual' => $monthlyreport[0]->case_manual,
                'case_system' => $resultData["case_system"],
                'pre_case_incomplete' => $monthlyreport[0]->pre_case_incomplete,
                'case_submit' => $monthlyreport[0]->case_submit,
                'case_complete' => $monthlyreport[0]->case_complete,
                'case_incomplete' => $monthlyreport[0]->case_incomplete,
                'comment1' => $monthlyreport[0]->comment1,
                'comment2' => $monthlyreport[0]->comment2,
                'comment_from_adm' => $monthlyreport[0]->comment_from_adm,
                'visit_promap' => $monthlyreport[0]->visit_promap,
                'visit_count' => $monthlyreport[0]->visit_count,
                'caserecord_promap' => $monthlyreport[0]->caserecord_promap,
                'caserecord_count' => $monthlyreport[0]->caserecord_count,
                'case_above1year' => $monthlyreport[0]->case_above1year,
                'case_above2year' => $monthlyreport[0]->case_above2year,
                'case_above3year' => $monthlyreport[0]->case_above3year,
                'update_status' => $monthlyreport[0]->update_status
            );


        }else{
        
            $emptydata = DB::table('monthly_reports')
            ->select('case_incomplete', 'pre_case_incomplete', 'case_total', 'update_status', 'is_approved')
            // ->where('report_type_id', $report_type_id)
            ->where('zillaid', $zillaid)->get();
            // dd( $emptydata);
            // search for previous information
        if (!$emptydata->isEmpty()) {
            if($mth == 1){
                $premonth =  12;
                $yr = $yr - 1;
            }else{
                $premonth = $mth - 1;
            }

            $query = DB::table('monthly_reports')
            ->select('case_incomplete', 'pre_case_incomplete', 'case_total', 'update_status', 'is_approved')
            ->where('report_type_id', $report_type_id)
            ->where('report_month', $premonth)
            ->where('report_year', $yr)
            ->where('zillaid', $zillaid);
            // Execute the query and get the results
            $monthlyreport_pre = $query->get();  // Retrieves the results as a collection
       
             $pre_case_incomplete = 0;
            if (count($monthlyreport_pre) > 0) {

                // echo $report_type_id;
                // echo "******wwwwwww*******";
                if($report_type_id  == 1){
                    $res = DB::table('promap')
                            ->select('promap')  // Select the 'promap' column
                            ->where('divid', $divid)  // Condition for 'divid'
                            ->where('zillaid', $zillaid)  // Condition for 'zillaid'
                            ->first();  // Execute the query and get the first result

                        // Access the 'promap' value
                        $promap_res = $res ? $res->promap : null;  // Get the 'promap' value or null if no result

                }else{
                    $promap_res = DashboardPromap::calculatePromap($monthlyreport_pre[0]->case_incomplete,$monthlyreport_pre[0]->case_incomplete,$report_type_id);
                }

                $flagP = "yes";
                $pre_case_incomplete = $monthlyreport_pre[0]->case_incomplete;

                if($monthlyreport_pre[0]->is_approved ==  1){
                    $flagPA = "yes";
                    $flag = "yes";
                }else{
                    $flagPA = "NO";
                    $flag = "NO";
                }
            }else{
                $flagP = "NO";
                $flag = "NO";
            }
        }else{
            
            $flagP="yess";
            $flag="";
            $flagCA="";
            $flagPA="";
        
            $pre_case_incomplete = "";

            if($report_type_id  == 1){
                $res = DB::table('promap')
                        ->select('promap')  // Select the 'promap' column
                        ->where('divid', $divid)  // Condition for 'divid'
                        ->where('zillaid', $zillaid)  // Condition for 'zillaid'
                        ->first();  // Execute the query and get the first result

                    // Access the 'promap' value
                    $promap_res = $res ? $res->promap : null;  // Get the 'promap' value or null if no result

            }else{
                $promap_res = DashboardPromap::calculatePromap(0,0,$report_type_id);
            }
        }

            $childs[] = array('flag' => $flag,
                'flagP' => $flagP,
                'flagPA' => $flagPA,
                'flagCA' => $flagCA,
                'id' => "",
                'upozila' => $upazillano,
                'promap' => $promap_res,
                'promap_achive' => "",
                'criminal_manual' => "",
                'criminal_system' => $resultData["criminal_system"],
                'criminal_total' => "",
                'lockup_criminal_manual' => "",
                'lockup_criminal_system' => $resultData["lockup_criminal_system"],
                'lockup_criminal_total' => "",
                'fine_manual' => "",
                'fine_system' =>$resultData["fine_system"],
                'fine_total' => "",
                'court_manual' => "",
                'court_system' => 0,
                'court_total' => "",
                'case_total' => "",
                'case_manual' => "",
                'case_system' =>$resultData["case_system"],
                'pre_case_incomplete' => $pre_case_incomplete,
                'case_submit' => "",
                'case_complete' => "",
                'case_incomplete' => "",
                'comment1' => "",
                'comment2' => "",
                'comment_from_adm' => null,
                'visit_promap' => 1,
                'visit_count' => "",
                'caserecord_promap' => 1,
                'caserecord_count' => "",
                'case_above1year' => "",
                'case_above2year' => "",
                'case_above3year' => "",
                'update_status' => ""
            );
        }
        return response()->json($childs, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }

    public function getCountOfCourtCaseFineCriminalJailCriminal($month, $year,$zillaId){

        //case count total



        $start_date = $year.'-'.$month.'-'.'01';
        $end_date = $year.'-'.$month.'-'.substr(date('t',strtotime($start_date)),0,2);
        //substr($report_date, 0, 2)

        //  $whereClause="WHERE p.zillaId=$zillaId AND p.date  BETWEEN  \"$start_date\"  AND  \"$end_date\" AND p.delete_status = 1 AND p.is_approved = 1 AND p.orderSheet_id  IS NOT NULL ";

        // $sql1= "SELECT COUNT(p.id) AS caseCount
        //         FROM prosecutions AS p
        //         $whereClause
        // //         ";
        // $caseCount  = $this->db->query($sql1)->fetchAll();  //var_dump( $result); die();

        $caseCount = DB::table('prosecutions as p')
                        ->where('p.zillaId', $zillaId)
                        ->whereBetween('p.date', [$start_date, $end_date])
                        ->where('p.delete_status', 1)
                        ->where('p.is_approved', 1)
                        ->whereNotNull('p.orderSheet_id')
                        ->count('p.id');

        // $no_case_dc = $caseCount[0]['caseCount'];
        $no_case_dc =$caseCount;


        //calculate fine and criminal
        // $sql2 = "SELECT COUNT(pn.criminal_id) AS criminal, SUM(IF(LENGTH(TRIM(IFNULL(pn.receipt_no, ''))) > 0, IFNULL(pn.fine, 0), 0))  AS fine
        //         FROM punishment AS pn
        //         JOIN prosecution AS p ON p.id = pn.prosecution_id AND p.delete_status = 1 AND p.is_approved = 1 AND p.orderSheet_id  IS NOT NULL
        //         WHERE ( p.date BETWEEN '$start_date' AND '$end_date' )
        //         AND p.zillaId=$zillaId
        //         ";

        // $criminalFineCount  = $this->db->query($sql2)->fetchAll();
        // $fine_dc = $criminalFineCount[0]['fine'];
        // $criminal_no_dc = $criminalFineCount[0]['criminal'];

        // Building the query for criminal count and fine
        $criminalFineCount = DB::table('punishments as pn')
        ->join('prosecutions as p', function($join) {
            $join->on('p.id', '=', 'pn.prosecution_id')
                ->where('p.delete_status', 1)
                ->where('p.is_approved', 1)
                ->whereNotNull('p.orderSheet_id');
        })
        ->whereBetween('p.date', [$start_date, $end_date])
        ->where('p.zillaId', $zillaId)
        ->select(
            DB::raw('COUNT(pn.criminal_id) AS criminal'),
            DB::raw("SUM(CASE WHEN LENGTH(TRIM(IFNULL(pn.receipt_no, ''))) > 0 THEN IFNULL(pn.fine, 0) ELSE 0 END) AS fine")
        )
        ->first(); // Fetching the first result

        // Access the fine and criminal count
        $fine_dc = $criminalFineCount->fine;
        $criminal_no_dc = $criminalFineCount->criminal;
        
        //If fine=null then it set as =0
        if(!$fine_dc){
            $fine_dc=0;
        }
        //calculate jail criminal
        // $whereClause="WHERE p.zillaId=$zillaId AND p.date  BETWEEN  \"$start_date\"  AND  \"$end_date\" AND p.delete_status = 1 AND p.is_approved = 1 AND p.orderSheet_id  IS NOT NULL ";
        // $sql3 = "SELECT COUNT(pn.criminal_id) AS jail_criminal
        //         FROM punishment AS pn
        //         JOIN prosecution AS p ON p.id = pn.prosecution_id
        //         $whereClause
        //                 AND pn.criminal_id IS NOT NULL
        //                  AND pn.punishmentJailID !=0 ";

        // // add parameters dynamically based on user profile
        // $jailCriminalCount  = $this->db->query($sql3)->fetchAll();

        // $jail_criminal_dc = $jailCriminalCount[0]['jail_criminal'];
        
        $jailCriminalCount = DB::table('punishments as pn')
            ->join('prosecutions as p', function($join) {
                $join->on('p.id', '=', 'pn.prosecution_id')
                    ->where('p.delete_status', 1)
                    ->where('p.is_approved', 1)
                    ->whereNotNull('p.orderSheet_id');
            })
            ->whereBetween('p.date', [$start_date, $end_date])
            ->where('p.zillaId', $zillaId)
            ->whereNotNull('pn.criminal_id')
            ->where('pn.punishmentJailID', '!=', 0)
            ->select(DB::raw('COUNT(pn.criminal_id) AS jail_criminal'))
            ->first();  // Fetching the first result

        // Access the jail criminal count
        $jail_criminal_dc = $jailCriminalCount->jail_criminal;

        return (array("lockup_criminal_system" => $jail_criminal_dc, "case_system" => $no_case_dc,
            "fine_system" => $fine_dc, "criminal_system" => $criminal_no_dc));
    }

    public function approvemonth(request $request){

        return view('monthly_report.approvemonth');
    }

    public function approved(Request $request){

        if ($request->isMethod('post')) {
            $userinfo = globalUserInfo();
            $office_id = $userinfo->office_id;
            $officeinfo = DB::table('office')->where('id', $office_id)->first();
            // $division_id = $officeinfo->division_id;
            // $district_id = $officeinfo->district_id;
    
            $user=DB::table('users as mag')
                ->join('office as job_des', 'job_des.id', '=', 'mag.office_id')
                ->select("*",'mag.id as user_id')
                ->where('job_des.id', $office_id)
                ->first();
            // Get the report date
            $report_date = $request->input('report_approve_date');
            $report_date_array = explode("-", $report_date);
            $mth = $report_date_array[0];
            $yr = $report_date_array[1];
    
           
            $flag = "yes";
            $divid = $user->division_id;
            $divname = $user->div_name_bn;
            $zillaid = $user->district_id;
            $zillaname =$user->dis_name_bn;

            // Fetch Upazila data
            $upazila = DB::table('upazila')->where("district_id",$zillaid)->get(); 
            $upazillano = $upazila->count();
    
            // Search for selected month information
            $monthlyReports = DB::table('monthly_reports as monthlyreport')
                ->join('report_lists as replist', 'replist.id', '=', 'monthlyreport.report_type_id')
                ->select(
                    'replist.name',
                    'monthlyreport.id',
                    'monthlyreport.upozila',
                    'monthlyreport.promap',
                    'monthlyreport.promap_achive',
                    'monthlyreport.criminal_manual',
                    'monthlyreport.criminal_system',
                    'monthlyreport.criminal_total',
                    'monthlyreport.lockup_criminal_manual',
                    'monthlyreport.lockup_criminal_system',
                    'monthlyreport.lockup_criminal_total',
                    'monthlyreport.fine_manual',
                    'monthlyreport.fine_system',
                    'monthlyreport.fine_total',
                    'monthlyreport.court_manual',
                    'monthlyreport.court_system',
                    'monthlyreport.court_total',
                    'monthlyreport.case_total',
                    'monthlyreport.case_manual',
                    'monthlyreport.case_system',
                    'monthlyreport.case_submit',
                    'monthlyreport.case_complete',
                    'monthlyreport.case_incomplete',
                    'monthlyreport.pre_case_incomplete',
                    'monthlyreport.visit_promap',
                    'monthlyreport.visit_count',
                    'monthlyreport.caserecord_promap',
                    'monthlyreport.caserecord_count',
                    'monthlyreport.comment1',
                    'monthlyreport.case_above1year',
                    'monthlyreport.case_above2year',
                    'monthlyreport.case_above3year',
                    'monthlyreport.update_status',
                    'monthlyreport.report_type_id',
                    'monthlyreport.is_approved'
                )
                ->where('monthlyreport.report_month', $mth)
                ->where('monthlyreport.zillaid', $zillaid)
                ->where('monthlyreport.report_year', $yr)
                ->get();
            
            // Check if data exists
            if ($monthlyReports->isEmpty()) {
                // return redirect()->back()->withErrors('error', 'তথ্য নাই ।');
                return redirect()->route('m.approvemonth')->with('error', 'তথ্য নাই ।');
            }

            return view('monthly_report.approved', [
                'reportlist' => $monthlyReports,
                'report_month' => BanglaDate::get_bangla_monthbymumber($mth, $yr)
            ]);
        } else {
            return 'OK';
            $report_date = $request->input('report_approve_date');
            $report_date_array = explode("-", $report_date);
            $mth = $report_date_array[0];
            $yr = $report_date_array[1];
        }
    }

    public function approvedreport(Request $request){
        $id=$request->id;
        $monthlyReport = MonthlyReport::find($id);
        if (!$monthlyReport) {
            return redirect()->route('m.approved')->with('error', 'Report was not found');
        }
        // Data Upgrade Operation
        $userinfo = globalUserInfo();
        $office_id = $userinfo->office_id;
        $officeinfo = DB::table('office')->where('id', $office_id)->first();
        $user=DB::table('users as mag')
        ->join('office as job_des', 'job_des.id', '=', 'mag.office_id')
        ->select("*",'mag.id as user_id')
        ->where('job_des.id', $office_id)
        ->first();
        $zillaid = $user->district_id;


        $mth = $monthlyReport->report_month;
        $yr = $monthlyReport->report_year;
        $resultSystemData = $this->getCountOfCourtCaseFineCriminalJailCriminal($mth, $yr,$zillaid);
    
        // Calculate totals
        $totalCase = $monthlyReport->case_total;
        $totalFine = $monthlyReport->fine_total;
        $totalCriminal = $monthlyReport->criminal_total;
        $totalLockCriminal = $monthlyReport->lockup_criminal_total;
    
        // System Data update
        $monthlyReport->case_system = $resultSystemData['case_system'];
        $monthlyReport->fine_system = $resultSystemData['fine_system'];
        $monthlyReport->criminal_system = $resultSystemData['criminal_system'];
        $monthlyReport->lockup_criminal_system = $resultSystemData['lockup_criminal_system'];
    
        // Manual Data update
        $monthlyReport->case_manual = $totalCase - $resultSystemData['case_system'];
        $monthlyReport->fine_manual = $totalFine - $resultSystemData['fine_system'];
        $monthlyReport->criminal_manual = $totalCriminal - $resultSystemData['criminal_system'];
        $monthlyReport->lockup_criminal_manual = $totalLockCriminal - $resultSystemData['lockup_criminal_system'];
    
        // Admin approved data update
        $monthlyReport->is_approved = 1;
        $monthlyReport->approved_user_id = $user->user_id;
    
        // Save the report and handle errors
        if (!$monthlyReport->save()) {
            return redirect()->route('m.approved')
                ->withErrors($monthlyReport->getErrors());
        }
    
        // Success message and forward to another action
        return redirect()->route('m.approvemonth')->with('success', 'প্রতিবেদনটি সফলভাবে সংরক্ষণ করা হয়েছে ।');
    }

    public function cancelReport(Request $request){
        $id = $request->data['reportId'];
        $comment_from_adm = $request->data["comment_from_adm"];
        $monthly_report = MonthlyReport::find($id);
        
        $userinfo = globalUserInfo();
        $office_id = $userinfo->office_id;
        $officeinfo = DB::table('office')->where('id', $office_id)->first();


        $user=DB::table('users')
        ->join('office as job_des', 'job_des.id', '=', 'users.office_id')
        ->select("*")
        ->where('job_des.id', $office_id)
        ->select('users.id as user_id')
        ->first();
       

        $monthly_report->is_approved = 2;
        $monthly_report->comment_from_adm = $comment_from_adm;
        $monthly_report->approved_user_id = $user->user_id;
        if (!$monthly_report->save()) {
              // Prepare error messages
        $errors = $monthly_report->errors()->all();
        $msg["flag"] = "false";
        // Return error response
        return response()->json($msg, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
        }else{
            $reportName =  ReportList::find($monthly_report->report_type_id);
            // dd( $acjmProfile);
            // $acjmProfile =Users::where('',$zillaid)->first(); 
            // DataEntryUser::findFirstByzillaId($userlocation['zillaid']);

            $mth = $this->toBanglaMonth($monthly_report->report_month);
            $yr = BanglaDate::bangla_number($monthly_report->report_year);
            $notificationBody = '"' . $reportName->name . '", ' . $mth . ', ' . $yr . ' - প্রতিবেদনটি সংশোধন করে পুনরায় দাখিল করুন।';
            // if($acjmProfile){

            //     Mail::to($acjmProfile->email)->send(new NotificationMail($notificationBody));

            //     // Send SMS (assuming sendSmsMessage is a custom function or service you created)
            //     $this->sendSmsMessage($acjmProfile->mobile, $notificationBody);

            // }
        }
      
        // Prepare response message
        $msg["flag"] = "true";

        // Return JSON response
        return response()->json($msg, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }

    public function sendSmsMessage($mobile, $message){
        // $sid = env('TWILIO_SID');
        // $token = env('TWILIO_TOKEN');
        // $twilio = new Client($sid, $token);

        // $twilio->messages->create($mobile, [
        //     'from' => env('TWILIO_FROM'),
        //     'body' => $message
        // ]);
    }

   public function ajaxDataCourt(Request $request){
   
    $childs = array();
    $office_id = globalUserInfo()->office_id;
    $user = Auth::user();
    $roleID = globalUserInfo()->role_id;
    $officeinfo = DB::table('office')
    ->leftJoin('district as zilla', function ($join) {
       $join->on('office.district_id', '=', 'zilla.id')
            ->on('office.division_id', '=', 'zilla.division_id');
   })->where('office.id',$office_id)->first();

    $divid = $officeinfo->division_id;
    $zillaId = $officeinfo->district_id;
    // $div_name = $officeinfo->div_name_bn;
    $zilla_name = $officeinfo->dis_name_bn;

    // $divid = $userlocation["divid"];
    // $divid = $userlocation["divid"];
    // $zillaId = $userlocation["zillaid"];
    // $zilla_name = $userlocation["zillaname"];

    if ($roleID == 37 || $roleID == 38) { // ADM DM
        $childs = $this->ajaxDataCourtForDivision($request);
    }

    // dd($childs);
    //  elseif ($user->profilesId == 4) { // Divisional Commissioner
    //     $childs = $this->ajaxDataCourtForDivision();
    // } elseif ($user->profilesId == 5) { //JS
    //     $childs = $this->ajaxDataCourtForCountry();
    // }

    return response()->json([$childs]);


   }
   public function ajaxDataCase(Request $request){
    $childs = array();
    $office_id = globalUserInfo()->office_id;
    $user = Auth::user();
    $roleID = globalUserInfo()->role_id;
    $officeinfo = DB::table('office')
    ->leftJoin('district as zilla', function ($join) {
       $join->on('office.district_id', '=', 'zilla.id')
            ->on('office.division_id', '=', 'zilla.division_id');
   })->where('office.id',$office_id)->first();
   
    // $divid = $officeinfo->division_id;
    // $zillaId = $officeinfo->district_id;
    // // $div_name = $officeinfo->div_name_bn;
    // $zilla_name = $officeinfo->dis_name_bn;
    if ($roleID == 37 || $roleID == 38) { // ADM DM
        $childs = $this->ajaxDataCaseForDivision($request);
    }
 
    return response()->json([$childs]);

   }
   public function ajaxDataFine(Request $request){
        $office_id = globalUserInfo()->office_id;
        $user = Auth::user();
        $roleID = globalUserInfo()->role_id;
        $officeinfo = DB::table('office')
        ->leftJoin('district as zilla', function ($join) {
        $join->on('office.district_id', '=', 'zilla.id')
                ->on('office.division_id', '=', 'zilla.division_id');
        })->where('office.id',$office_id)->first();

        if ($roleID == 37 || $roleID == 38) { // ADM DM
            $childs = $this->ajaxDataFineForDivision($request);
        }
       
 
    return response()->json([$childs]);
   }

   public function ajaxDataAppeal(Request $request){
    $office_id = globalUserInfo()->office_id;
    $user = Auth::user();
    $roleID = globalUserInfo()->role_id;
    $officeinfo = DB::table('office')
    ->leftJoin('district as zilla', function ($join) {
    $join->on('office.district_id', '=', 'zilla.id')
            ->on('office.division_id', '=', 'zilla.division_id');
    })->where('office.id',$office_id)->first();

    if ($roleID == 37 || $roleID == 38) { // ADM DM
        $childs = $this->ajaxDataAppealForDivision($request);
    }
   
    return response()->json([$childs]);
    }
    public function ajaxDataEm(Request $request){
        $office_id = globalUserInfo()->office_id;
        $user = Auth::user();
        $roleID = globalUserInfo()->role_id;
        $officeinfo = DB::table('office')
        ->leftJoin('district as zilla', function ($join) {
        $join->on('office.district_id', '=', 'zilla.id')
                ->on('office.division_id', '=', 'zilla.division_id');
        })->where('office.id',$office_id)->first();
    
        if ($roleID == 37 || $roleID == 38) { // ADM DM
            $childs = $this->ajaxDataEmForDivision($request);
        }
 
        return response()->json([$childs]);
    }
    public function ajaxDataAdm(Request $request){
        $office_id = globalUserInfo()->office_id;
        $user = Auth::user();
        $roleID = globalUserInfo()->role_id;
        $officeinfo = DB::table('office')
        ->leftJoin('district as zilla', function ($join) {
        $join->on('office.district_id', '=', 'zilla.id')
                ->on('office.division_id', '=', 'zilla.division_id');
        })->where('office.id',$office_id)->first();
    
        if ($roleID == 37 || $roleID == 38) { // ADM DM
            $childs = $this->ajaxDataAdmForDivision($request);
        }
 
        return response()->json([$childs]);
    }
   public function ajaxDataCourtForDivision($request)
    {

        $preMonth = "";
        $month = "";
        $nextMonth = "";
        $yData = array();
        $office_id = globalUserInfo()->office_id;
        $officeinfo = DB::table('office')
        ->leftJoin('district as zilla', function ($join) {
           $join->on('office.district_id', '=', 'zilla.id')
                ->on('office.division_id', '=', 'zilla.division_id');
       })->where('office.id',$office_id)->first();

     
        $divid = $officeinfo->division_id;



        $report_date = $request->report_date;
        $report_date2 = $request->report_date2;
        $yData='';
        if($report_date != ""){
            $report_date_array= explode("-",$report_date);
            $mth = $report_date_array[0];
            $yr = $report_date_array[1];

           $month =  BanglaDate::get_bangla_monthonly($mth);


            $preMonth = (int)$mth - 1;;
            $currentmonth = $mth;
            $nextMonth = (int)$mth + 1;
            if($report_date2 != ""){
                $report_date2 = trim($report_date2);  // Ensure no extra spaces
                $report_date2_array = explode("-", $report_date2);  // Split into components
                $mth2 = $report_date2_array[0];  // Expected to be '08' if correctly formatted
                $yr2 = $report_date2_array[1];
                $nextMonth = trim($mth2);

            }
        //     return  $second=BanglaDate::get_bangla_monthonly($nextMonth);
        //     // dd($currentmonth);
        //     // $yData = 
        //    $first= BanglaDate::get_bangla_monthonly($currentmonth);
         
            // dd($first);
       $yData = ["প্রমাপ" , BanglaDate::get_bangla_monthonly($currentmonth) , BanglaDate::get_bangla_monthonly($nextMonth)];

        }else{
            $preMonth = "03";
            $currentmonth = "04";
            $nextMonth = "05";
            $yData = [ BanglaDate::get_bangla_monthonly($currentmonth) , BanglaDate::get_bangla_monthonly($nextMonth)];
        }


        $childs = array();
        // $phql = "
        //             SELECT
        //             zillaname,
        //             SUM( IF( monthlyreport.report_month =$currentmonth, monthlyreport.court_total, 0 ) ) court_total1,
        //             SUM( IF( monthlyreport.report_month =$nextMonth, monthlyreport.court_total, 0 ) ) court_total2,
        //             SUM( IF( monthlyreport.report_month =$currentmonth, monthlyreport.promap, 0 ) ) promap
        //             FROM Mcms\Models\MonthlyReport as monthlyreport
        //             WHERE monthlyreport.divid = $divid AND monthlyreport.report_type_id = 1 AND monthlyreport.report_year = $yr
        //             GROUP BY zillaid";




        // $query = $this->modelsManager->createQuery($phql);
        // $info = $query->execute();

        $info = DB::table('monthly_reports')
            ->select(
                'zillaname',
                DB::raw("SUM(IF(report_month = $currentmonth, court_total, 0)) as court_total1"),
                DB::raw("SUM(IF(report_month = $nextMonth, court_total, 0)) as court_total2"),
                DB::raw("SUM(IF(report_month = $currentmonth, promap, 0)) as promap")
            )
            ->where('divid', $divid)
            ->where('report_type_id', 1)
            ->where('report_year', $yr)
            ->groupBy('zillaid')
            ->get();
                foreach ($info as $t) {
                
                    $childs[] = array(
                        'location' => $t->zillaname,
                        $yData[0] => "" . $t->promap . "",
                        $yData[1] => "" . $t->court_total1 . "",
                        $yData[2] => "" . $t->court_total2 . "",
                        'yData' =>  $yData
                    );
                }

            return $childs;

    }

    public function ajaxDataCaseForDivision($request){
        $preMonth = "";
        $month = "";
        $nextMonth = "";
        $yData = array();

        
        $office_id = globalUserInfo()->office_id;
        $officeinfo = DB::table('office')
        ->leftJoin('district as zilla', function ($join) {
           $join->on('office.district_id', '=', 'zilla.id')
                ->on('office.division_id', '=', 'zilla.division_id');
       })->where('office.id',$office_id)->first();

       $divid = $officeinfo->division_id;
 
        $report_date = $request->report_date;
        if($report_date != ""){
            $report_date = trim($report_date);  // Ensure no extra spaces
            $report_date_array= explode("-",$report_date);
            $mth = trim($report_date_array[0]);
            $yr = $report_date_array[1];

            $month =  BanglaDate::get_bangla_monthonly($mth);


            $preMonth = $mth - 1;;
            $currentmonth = $mth;
            $nextMonth = $mth + 1;
            $nextMonth = sprintf("%02d", $nextMonth);
            $yData = [ BanglaDate::get_bangla_monthonly($currentmonth) , BanglaDate::get_bangla_monthonly($nextMonth)];

        }else{
            $preMonth = "03";
            $currentmonth = "04";
            $nextMonth = "05";
            $nextMonth = sprintf("%02d", $nextMonth);
            $yData =[ BanglaDate::get_bangla_monthonly($currentmonth) , BanglaDate::get_bangla_monthonly($nextMonth)];
        }
       
        $childs = array();
        // $phql = "
        //             SELECT
        //             zillaname,
        //             SUM( IF( monthlyreport.report_month =$currentmonth, monthlyreport.case_total, 0 ) ) case_total1,
        //             SUM( IF( monthlyreport.report_month =$nextMonth, monthlyreport.case_total, 0 ) ) case_total2
        //             FROM Mcms\Models\MonthlyReport as monthlyreport
        //             WHERE monthlyreport.divid = $divid AND monthlyreport.report_type_id = 1 AND monthlyreport.report_year = $yr
        //             GROUP BY zillaname";
 
        // $query = $this->modelsManager->createQuery($phql);
        // $info = $query->execute();
       
        $info = DB::table('monthly_reports as monthlyreport')
                    ->select(
                        'zillaname',
                        DB::raw("SUM(IF(monthlyreport.report_month = $currentmonth, monthlyreport.case_total, 0)) as case_total1"),
                        DB::raw("SUM(IF(monthlyreport.report_month = $nextMonth, monthlyreport.case_total, 0)) as case_total2")
                    )
                    ->where('monthlyreport.divid', $divid)
                    ->where('monthlyreport.report_type_id', 1)
                    ->where('monthlyreport.report_year', $yr)
                    ->groupBy('zillaname')
                    ->get();

        foreach ($info as $t) {
            $childs[] = array('location' => $t->zillaname,
                $yData[0] => "" . $t->case_total1 . "",
                $yData[1] => "" . $t->case_total2 . "",
                'yData' =>  $yData);
        }

        return $childs;
    }
    public function ajaxDataFineForDivision($request){
        $preMonth = "";
        $month = "";
        $nextMonth = "";
        $yData = array();

        $report_date = $request->report_date;

        $office_id = globalUserInfo()->office_id;
        $officeinfo = DB::table('office')
        ->leftJoin('district as zilla', function ($join) {
           $join->on('office.district_id', '=', 'zilla.id')
                ->on('office.division_id', '=', 'zilla.division_id');
       })->where('office.id',$office_id)->first();

       $divid = $officeinfo->division_id;

        
        if($report_date != ""){
            $report_date = trim($report_date);  // Ensure no extra spaces
            $report_date_array= explode("-",$report_date);
            $mth = trim($report_date_array[0]);
            $yr = $report_date_array[1];
 
            $month = BanglaDate::get_bangla_monthonly($mth);


            $preMonth = $mth - 1;;
            $currentmonth = $mth;
            $nextMonth = $mth + 1;
            $nextMonth = sprintf("%02d", $nextMonth);
            $yData = [BanglaDate::get_bangla_monthonly($currentmonth) ,BanglaDate::get_bangla_monthonly($nextMonth)];

        }else{
            $preMonth = "03";
            $currentmonth = "04";
            $nextMonth = "05";
            $nextMonth = sprintf("%02d", $nextMonth);
            $yData = [BanglaDate::get_bangla_monthonly($currentmonth) ,BanglaDate::get_bangla_monthonly($nextMonth)];
        }

        $childs = array();
        $info = DB::table('monthly_reports as monthlyreport')
        ->select(
            'zillaname',
            DB::raw("SUM(IF(monthlyreport.report_month = $currentmonth, monthlyreport.fine_total, 0)) as fine_total1"),
            DB::raw("SUM(IF(monthlyreport.report_month = $nextMonth, monthlyreport.fine_total, 0)) as fine_total2")
        )
        ->where('monthlyreport.divid', $divid)
        ->where('monthlyreport.report_type_id', 1)
        ->where('monthlyreport.report_year', $yr)
        ->groupBy('zillaname')
        ->get();

        foreach ($info as $t) {
            $childs[] = array('location' => $t->zillaname,
                $yData[0] => "" . $t->fine_total1 . "",
                $yData[1] => "" . $t->fine_total2 . "",
                'yData' =>  $yData);
        }

        return $childs;
    }

    public function ajaxDataAppealForDivision($request){
        $preMonth = "";
        $month = "";
        $nextMonth = "";
        $yData = array();
        $childs=[];
        $report_date = $request->report_date;

        $office_id = globalUserInfo()->office_id;
        $officeinfo = DB::table('office')
        ->leftJoin('district as zilla', function ($join) {
           $join->on('office.district_id', '=', 'zilla.id')
                ->on('office.division_id', '=', 'zilla.division_id');
       })->where('office.id',$office_id)->first();

       $divid = $officeinfo->division_id;

        
        if($report_date != ""){
            $report_date = trim($report_date);  // Ensure no extra spaces
            $report_date_array= explode("-",$report_date);
            $mth = trim($report_date_array[0]);
            $yr = $report_date_array[1];
 
            // $month = BanglaDate::get_bangla_monthonly($mth);


            $preMonth = $mth - 1;;
            $currentmonth = $mth;
            $nextMonth = $mth + 1;
            $nextMonth = sprintf("%02d", $nextMonth);
            // $yData = [BanglaDate::get_bangla_monthonly($currentmonth) ,BanglaDate::get_bangla_monthonly($nextMonth)];

        }else{
            $preMonth = "03";
            $currentmonth = "04";
            $nextMonth = "05";
            $nextMonth = sprintf("%02d", $nextMonth);
            // $yData = [BanglaDate::get_bangla_monthonly($currentmonth) ,BanglaDate::get_bangla_monthonly($nextMonth)];
        }

            $info = DB::table('monthly_reports as monthlyreport')
            ->select(
                'zillaname',
                DB::raw("SUM(monthlyreport.pre_case_incomplete) as pre_case_incomplete"),
                DB::raw("SUM(monthlyreport.case_submit) as case_submit"),
                DB::raw("SUM(monthlyreport.case_complete) as case_complete"),
                DB::raw("SUM(monthlyreport.case_incomplete) as case_incomplete")
            )
            ->where('monthlyreport.divid', $divid)
            ->where('monthlyreport.report_month', $currentmonth)
            ->where('monthlyreport.report_type_id', 2)
            ->where('monthlyreport.report_year', $yr)
            ->groupBy('zillaname')
            ->get();
            $month_year = BanglaDate::get_bangla_monthbymumber($currentmonth, $yr);
            $yData = ['জের' ,'দায়েরকৃত','নিষ্পন্ন','অনিষ্পন্ন' ];
            foreach ($info as $t) {
                $childs[] = array('location' => $t->zillaname,
                    'জের' => "" . $t->pre_case_incomplete . "",
                    'দায়েরকৃত' => "" . $t->case_submit . "",
                    'নিষ্পন্ন' => "" . $t->case_complete . "",
                    'অনিষ্পন্ন' => "" . $t->case_incomplete . "",
                    'reportmonth' => "(".$month_year.")",
                    'yData' => $yData);
            }
            return $childs;
    }

    public function ajaxDataEmForDivision($request){

        $preMonth = "";
        $month = "";
        $nextMonth = "";
        $yData = array();

        $report_date = $request->report_date;

        $office_id = globalUserInfo()->office_id;
        $officeinfo = DB::table('office')
        ->leftJoin('district as zilla', function ($join) {
           $join->on('office.district_id', '=', 'zilla.id')
                ->on('office.division_id', '=', 'zilla.division_id');
       })->where('office.id',$office_id)->first();

       $divid = $officeinfo->division_id;

        
        if($report_date != ""){
            $report_date = trim($report_date);  // Ensure no extra spaces
            $report_date_array= explode("-",$report_date);
            $mth = trim($report_date_array[0]);
            $yr = $report_date_array[1];
 
            // $month = BanglaDate::get_bangla_monthonly($mth);


            $preMonth = $mth - 1;;
            $currentmonth = $mth;
            $nextMonth = $mth + 1;

            // $yData = [BanglaDate::get_bangla_monthonly($currentmonth) ,BanglaDate::get_bangla_monthonly($nextMonth)];

        }else{
            $preMonth = "03";
            $currentmonth = "04";
            $nextMonth = "05";
            // $yData = [BanglaDate::get_bangla_monthonly($currentmonth) ,BanglaDate::get_bangla_monthonly($nextMonth)];
        }

        $info = DB::table('monthly_reports as monthlyreport')
        ->select(
            'zillaname',
            DB::raw("SUM(monthlyreport.pre_case_incomplete) as pre_case_incomplete"),
            DB::raw("SUM(monthlyreport.case_submit) as case_submit"),
            DB::raw("SUM(monthlyreport.case_complete) as case_complete"),
            DB::raw("SUM(monthlyreport.case_incomplete) as case_incomplete")
        )
        ->where('monthlyreport.divid', $divid)
        ->where('monthlyreport.report_month', $currentmonth)
        ->where('monthlyreport.report_type_id', 4)
        ->where('monthlyreport.report_year', $yr)
        ->groupBy('zillaname')
        ->get();
        $month_year = BanglaDate::get_bangla_monthbymumber($currentmonth, $yr);
        $yData = ['জের' ,'দায়েরকৃত','নিষ্পন্ন','অনিষ্পন্ন' ];
        $childs=[];
        foreach ($info as $t) {
            $childs[] = array('location' => $t->zillaname,
                'জের' => "" . $t->pre_case_incomplete . "",
                'দায়েরকৃত' => "" . $t->case_submit . "",
                'নিষ্পন্ন' => "" . $t->case_complete . "",
                'অনিষ্পন্ন' => "" . $t->case_incomplete . "",
                'reportmonth' => "(".$month_year.")",
                'yData' => $yData);
        }
        return $childs;

    }

    public function ajaxDataAdmForDivision(Request $request){
        $preMonth = "";
        $month = "";
        $nextMonth = "";
        $yData = array();

        $report_date = $request->report_date;

        $office_id = globalUserInfo()->office_id;
        $officeinfo = DB::table('office')
        ->leftJoin('district as zilla', function ($join) {
           $join->on('office.district_id', '=', 'zilla.id')
                ->on('office.division_id', '=', 'zilla.division_id');
       })->where('office.id',$office_id)->first();

       $divid = $officeinfo->division_id;

        
        if($report_date != ""){
            $report_date = trim($report_date);  // Ensure no extra spaces
            $report_date_array= explode("-",$report_date);
            $mth = trim($report_date_array[0]);
            $yr = $report_date_array[1];
 
            // $month = BanglaDate::get_bangla_monthonly($mth);


            $preMonth = $mth - 1;;
            $currentmonth = $mth;
            $nextMonth = $mth + 1;

            // $yData = [BanglaDate::get_bangla_monthonly($currentmonth) ,BanglaDate::get_bangla_monthonly($nextMonth)];

        }else{
            $preMonth = "03";
            $currentmonth = "04";
            $nextMonth = "05";
            // $yData = [BanglaDate::get_bangla_monthonly($currentmonth) ,BanglaDate::get_bangla_monthonly($nextMonth)];
        }
        $childs=array();
        $info = DB::table('monthly_reports as monthlyreport')
        ->select(
            'divname',
            DB::raw("SUM(monthlyreport.pre_case_incomplete) as pre_case_incomplete"),
            DB::raw("SUM(monthlyreport.case_submit) as case_submit"),
            DB::raw("SUM(monthlyreport.case_complete) as case_complete"),
            DB::raw("SUM(monthlyreport.case_incomplete) as case_incomplete")
        )
        ->where('monthlyreport.report_month', $currentmonth)
        ->where('monthlyreport.report_type_id', 3)
        ->where('monthlyreport.divid', $divid)
        ->where('monthlyreport.report_year', $yr)
        ->groupBy('divname')
        ->get();
        $month_year = BanglaDate::get_bangla_monthbymumber($currentmonth, $yr);
        $yData = ['জের' ,'দায়েরকৃত','নিষ্পন্ন','অনিষ্পন্ন' ];
        foreach ($info as $t) {
            $childs[] = array('location' => $t->divname,
                'জের' => "" . $t->pre_case_incomplete . "",
                'দায়েরকৃত' => "" . $t->case_submit . "",
                'নিষ্পন্ন' => "" . $t->case_complete . "",
                'অনিষ্পন্ন' => "" . $t->case_incomplete . "",
                'reportmonth' => "(".$month_year.")",
                 'yData' => $yData);
        }

        return $childs;
    }
    private function toBanglaMonth($number){
        $search_array= array("1"=>"জানুয়ারী", "2"=>"ফেব্রুয়ারী", "3"=>"মার্চ", "4"=>"এপ্রিল", "5"=>"মে", "6"=>"জুন", "7"=>"জুলাই", "8"=>"আগস্ট", "9"=> "সেপ্টেম্বর", "10"=>"অক্টোবর", "11"=>"নভেম্বর", "12"=>"ডিসেম্বর");

        return $search_array[$number];
    }

    public function reportCorrectionList(Request $request){
        $userinfo = globalUserInfo();
        $office_id = $userinfo->office_id;
        $officeinfo = DB::table('office')->where('id', $office_id)->first();
        
        $user=DB::table('users as mag')
        ->join('office as job_des', 'job_des.id', '=', 'mag.office_id')
        ->select("*",'mag.id as user_id')
        ->where('job_des.id', $office_id)
        ->first();
        // Extract division and zilla ID from user location

        $divId = $user->division_id;
        $zillaid = $user->district_id;

        // Query using Eloquent or Query Builder
        $monthlyreports = MonthlyReport::where('zillaid', $zillaid)
                        ->where('divid', $divId)
                        ->where('is_approved', 2)
                        ->get();
        // dd($monthlyreports);
        // Pass data to the view
        $data=['reportlist' => $monthlyreports];
        return view('monthly_report.reportCorrectionList',$data);
    }
}
