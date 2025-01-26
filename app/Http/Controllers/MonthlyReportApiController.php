<?php

namespace App\Http\Controllers;

use App\Repositories\MisReportRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonthlyReportApiController extends Controller
{
    public function printcountrymobilecourtreport(Request $request)
    {

        $requestData = $request->all();
        $request = $requestData['body_data'];
        $presentDate = $request['presentDate'];
        $previousDate = $request['previousDate'];
        $reportId = $request['reportId'];

        $allData = MisReportRepository::getCountryBasedReportData($presentDate, $previousDate, $reportId);
        return response()->json([
            'resultSet' => $allData['resultSet'] ?? [],
            'totResult' => $allData['totResult'] ?? 0,
            'reportName' => $allData['reportName'] ?? '',
        ]);
    }
    public function printdivmobilecourtreport(Request $request)
    {

        $requestData = $request->all();
        $request = $requestData['body_data'];

        $resultset = array();
        $reportName = "No data Found";
        $result = array();
        $zilla_name = "";
        $start_date = "";
        $month_year = "";
        $reportID = $request['reportID'];
        $report_date = $request['report_date'];
        $divid = $request['divid'];
        $divname_name = $request['divname_name'];
        $report_date_array = explode("-", $report_date);
        $mth = $report_date_array[0];
        $yr = $report_date_array[1];

        $month_year = $this->get_bangla_monthbymumber($mth, $yr);
        $lastmonth = "01";
        $year = "";
        $lastyear = "";

        if ($mth == '01') { //
            $lastmonth = 12;
            $lastyear = $yr - 1;
        } else {
            $lastmonth = $mth - 1;
            $lastyear = $yr;
        }
        $roleID = $request['roleID'];

        if ($roleID == 2 || $roleID == 8 || $roleID == 25) {
            $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার </br>মন্ত্রিপরিষদ  বিভাগ</br>জেলা ম্যাজিস্ট্রেসি পরিবীক্ষণ অধিশাখা</br>মোবাইল কোর্টের পরিচালনা সংক্রান্ত মাসিক প্রতিবেদন";
            // $sWhere = [
            //     ['monthly_reports.report_type_id', '=', 1],
            //     ['monthly_reports.is_approved', '=', 1]
            // ];
            $sWhere = "WHERE report_type_id = 1 AND is_approved = 1";
        }
        if ($roleID == 34) {
            // $sWhere = "WHERE divid = $divid AND report_type_id = 2 AND is_approved = 1";
            $sWhere = "WHERE   divid = $divid  AND report_type_id = 1 AND is_approved = 1 ";
            $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>বিভাগীয় কমিশনারের কার্যালয় , $divname_name</br>মোবাইল কোর্টের আইনের আওতায় দায়েরকৃত আপিল মামলার বিবরণ ";
        
        }
        // $select = "
        //     SUM(IF(report_month = $mth AND report_year = $yr, promap, 0)) as promap,
        //     SUM(IF(report_month = $mth AND report_year = $yr, upozila, 0)) as upozila,
        //     SUM(IF(report_month = $lastmonth AND report_year = $lastyear, case_total, 0)) as case_total1,
        //     SUM(IF(report_month = $mth AND report_year = $yr, case_total, 0)) as case_total2,
        //     SUM(IF(report_month = $lastmonth AND report_year = $lastyear, court_total, 0)) as court_total1,
        //     SUM(IF(report_month = $mth AND report_year = $yr, court_total, 0)) as court_total2,
        //     SUM(IF(report_month = $lastmonth AND report_year = $lastyear, fine_total, 0)) as fine_total1,
        //     SUM(IF(report_month = $mth AND report_year = $yr, fine_total, 0)) as fine_total2,
        //     SUM(IF(report_month = $lastmonth AND report_year = $lastyear, lockup_criminal_total, 0)) as lockup_criminal_total1,
        //     SUM(IF(report_month = $mth AND report_year = $yr, lockup_criminal_total, 0)) as lockup_criminal_total2,
        //     SUM(IF(report_month = $lastmonth AND report_year = $lastyear, criminal_total, 0)) as criminal_total1,
        //     SUM(IF(report_month = $mth AND report_year = $yr, criminal_total, 0)) as criminal_total2
        // ";

        $sSel = "
        SUM(IF( report_month = $mth  AND report_year = $yr  , promap ,0 ))promap,
        SUM(IF( report_month = $mth  AND report_year = $yr, upozila ,0 ))upozila,
        SUM(IF( report_month = $lastmonth  AND report_year = $lastyear, case_total ,0 ) )case_total1,
        SUM(IF( report_month = $mth  AND report_year = $yr, case_total ,0 ))case_total2,
        SUM(IF( report_month = $lastmonth  AND report_year = $lastyear, court_total, 0 ))  court_total1,
        SUM(IF( report_month = $mth  AND report_year = $yr, court_total ,0 ))  court_total2,
        SUM(IF( report_month = $lastmonth AND report_year = $lastyear ,fine_total, 0 )) fine_total1,
        SUM(IF( report_month = $mth  AND report_year = $yr, fine_total  ,0 )) fine_total2,
        SUM(IF( report_month = $lastmonth  AND report_year = $lastyear, lockup_criminal_total , 0)) lockup_criminal_total1,
        SUM(IF( report_month = $mth AND report_year = $yr , lockup_criminal_total ,0 ) ) lockup_criminal_total2,
        SUM(IF( report_month = $lastmonth  AND report_year = $lastyear, criminal_total , 0)) criminal_total1,
        SUM(IF( report_month = $mth  AND report_year = $yr , criminal_total ,0 ) ) criminal_total2";

        // $query = DB::table('monthly_reports')
        //     ->selectRaw("divname, comment2, monthly_reports.comment1_str, monthly_reports.comment1, $select")
        //     ->when($sWhere, function ($query) use ($sWhere) {
        //         $query->where($sWhere);
        //     })
        //     ->groupBy('divid', 'divname', 'comment2', 'monthly_reports.comment1_str', 'monthly_reports.comment1');;
        // $resultset = $query->get()->map(function ($item) {
        //     return [
        //         0 => $item->divname,
        //         1 => $item->comment2,
        //         2 => $item->comment1_str,
        //         3 => $item->comment1,
        //         4 => $item->promap,
        //         5 => $item->upozila,
        //         6 => $item->case_total1,
        //         7 => $item->case_total2,
        //         8 => $item->court_total1,
        //         9 => $item->court_total2,
        //         10 => $item->fine_total1,
        //         11 => $item->fine_total2,
        //         12 => $item->lockup_criminal_total1,
        //         13 => $item->lockup_criminal_total2,
        //         14 => $item->criminal_total1,
        //         15 => $item->criminal_total2,
        //         'divname' => $item->divname,
        //         'comment1' => $item->comment1,
        //         'comment1_str' => $item->comment1_str,
        //         'comment2' => $item->comment2,
        //         'promap' => $item->promap,
        //         'upozila' => $item->upozila,
        //         'case_total1' => $item->case_total1,
        //         'case_total2' => $item->case_total2,
        //         'court_total1' => $item->court_total1,
        //         'court_total2' => $item->court_total2,
        //         'fine_total1' => $item->fine_total1,
        //         'fine_total2' => $item->fine_total2,
        //         'lockup_criminal_total1' => $item->lockup_criminal_total1,
        //         'lockup_criminal_total2' => $item->lockup_criminal_total2,
        //         'criminal_total1' => $item->criminal_total1,
        //         'criminal_total2' => $item->criminal_total2,
        //     ];
        // });

        $sql = "
        SELECT
            divname,comment2,comment1_str,comment1,
            $sSel
        FROM monthly_reports
        $sWhere
        GROUP BY divid
        WITH ROLLUP
       ";
    
    // Execute the raw SQL query and fetch results
    $resultset = DB::select(DB::raw($sql));


        $result = array(
            "result" => $resultset,
            "name" => $reportName,
            "profileID" => 5,
            "zilla_name" => $zilla_name,
            "month_year" => $month_year
        );


        return response()->json($result);
    }

    public function printdivappealcasereport(Request $request)
    {
        $requestData = $request->all();
        $request = $requestData['body_data'];


        $resultset = array();
        $reportName = "No data Found";
        $result = array();
        $zilla_name = "";
        $start_date = "";
        $month_year = "";
        $pre_month_year = "";
        $reportID = $request['reportID'];
        $report_date = $request['report_date'];
        $report_date_array = explode("-", $report_date);
        $mth = $report_date_array[0];
        $yr = $report_date_array[1];
        $month_year = $this->get_bangla_monthbymumber($mth, $yr);

        $lastmonth = "01";
        $year = "";
        $lastyear = "";

        if ($mth == '01') { //
            $lastmonth = 12;
            $lastyear = $yr - 1;
        } else {
            $lastmonth = $mth - 1;
            $lastyear = $yr;
        }
        $sWhere = "";
        $roleID = $request['roleID'];
        if ($roleID == 2 || $roleID == 8 || $roleID == 25) {

            $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার </br>মন্ত্রিপরিষদ  বিভাগ</br>জেলা ম্যাজিস্ট্রেসি পরিবীক্ষণ অধিশাখা</br>মোবাইল কোর্টের পরিচালনা সংক্রান্ত মাসিক প্রতিবেদন";
            // $sWhere = [
            //     ['monthly_reports.report_type_id', '=', 2],
            //     ['monthly_reports.is_approved', '=', 1]
            // ];
            $sWhere = "WHERE report_type_id = 2   AND is_approved = 1";
        }


        if ($roleID == 34) {
            $divid =  $request['divid'];
            $zillaId =  $request['zillaId'];
            $zilla_name = $request['zilla_name'];
            $divname_name =  $request['divname_name'];
            $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>বিভাগীয় কমিশনারের কার্যালয় , $divname_name</br>মোবাইল কোর্টের আইনের আওতায় দায়েরকৃত আপিল মামলার বিবরণ ";
            // $sWhere = [
            //     ['monthly_reports.divid', '=', $zillaId],
            //     ['monthly_reports.report_type_id', '=', 2],
            //     ['monthly_reports.is_approved', '=', 1]
            // ];
            $sWhere = "WHERE   divid = $divid  AND report_type_id = 2   AND is_approved = 1 ";
        }

        // $result = DB::table('monthly_reports')
        //     ->select(
        //         'divname',
        //         'comment2',
        //         'monthly_reports.comment1_str',
        //         'monthly_reports.comment1',
        //         DB::raw("
        //             SUM(IF( report_month = $lastmonth AND report_year = $lastyear , case_submit ,0 ) )case_submit1,
        //             SUM(IF( report_month = $mth  AND report_year = $yr, case_submit ,0 ))case_submit2,
        //             SUM(IF( report_month = $lastmonth  AND report_year = $lastyear, case_total ,0 ) )case_total1,
        //             SUM(IF( report_month = $mth  AND report_year = $yr, case_total ,0 ))case_total2,
        //             SUM(IF( report_month = $lastmonth  AND report_year = $lastyear, case_complete ,0 ) )case_complete1,
        //             SUM(IF( report_month = $mth  AND report_year = $yr, case_complete ,0 ))case_complete2,
        //             SUM(IF( report_month = $lastmonth  AND report_year = $lastyear, case_incomplete ,0 ) )case_incomplete1,
        //             SUM(IF( report_month = $mth  AND report_year = $yr, case_incomplete,0 ))case_incomplete2
        //     ")
        //     )
        //     ->where($sWhere)  // Apply any raw where conditions
        //     ->groupBy('divname', 'comment2', 'monthly_reports.comment1_str', 'monthly_reports.comment1'); // Add all selected columns




        // $resultset = $result->get()->map(function ($item) {
        //     return [
        //         0 => $item->divname,
        //         1 => $item->comment2,
        //         2 => $item->comment1_str,
        //         3 => $item->comment1,
        //         4 => $item->case_submit1,
        //         5 => $item->case_submit2,
        //         6 => $item->case_total1,
        //         7 => $item->case_total2,
        //         8 => $item->case_complete1,
        //         9 => $item->case_complete2,
        //         10 => $item->case_incomplete1,
        //         11 => $item->case_incomplete2,
        //         'case_complete1' => $item->case_complete1,
        //         'case_complete2' => $item->case_complete2,
        //         'case_incomplete1' => $item->case_incomplete1,
        //         'case_incomplete2' => $item->case_incomplete2,
        //         'case_submit1' => $item->case_submit1,
        //         'case_submit2' => $item->case_submit2,
        //         'case_total1' => $item->case_total1,
        //         'case_total2' => $item->case_total2,
        //         'comment1' => $item->comment1,
        //         'comment1_str' => $item->comment1_str,
        //         'comment2' => $item->comment2,
        //         'divname' => $item->divname,
        //     ];
        // });

        $sSel = "
        SUM(IF( report_month = $lastmonth AND report_year = $lastyear , case_submit ,0 ) )case_submit1,
        SUM(IF( report_month = $mth  AND report_year = $yr, case_submit ,0 ))case_submit2,
        SUM(IF( report_month = $lastmonth  AND report_year = $lastyear, case_total ,0 ) )case_total1,
        SUM(IF( report_month = $mth  AND report_year = $yr, case_total ,0 ))case_total2,
        SUM(IF( report_month = $lastmonth  AND report_year = $lastyear, case_complete ,0 ) )case_complete1,
        SUM(IF( report_month = $mth  AND report_year = $yr, case_complete ,0 ))case_complete2,
        SUM(IF( report_month = $lastmonth  AND report_year = $lastyear, case_incomplete ,0 ) )case_incomplete1,
        SUM(IF( report_month = $mth  AND report_year = $yr, case_incomplete,0 ))case_incomplete2";

        $sql = "
        SELECT
            divname,comment2,comment1_str,comment1,
            $sSel
        FROM monthly_reports
        $sWhere
        GROUP BY divid
        WITH ROLLUP
       ";
        // Execute the raw SQL query and fetch results
        $resultset = DB::select(DB::raw($sql));
        $response = array(
            "result" => $resultset,
            "name" => $reportName,
            "profileID" => 5,
            "zilla_name" => $zilla_name,
            "month_year" => $month_year,
            "pre_month_year" => $pre_month_year

        );
        return response()->json($response);
    }

    public function printdivadmcasereport(Request $request)
    {
        $requestData = $request->all();
        $request = $requestData['body_data'];

        $resultset = array();
        $reportName = "No data Found";
        $result = array();
        $zilla_name = "";
        $start_date = "";
        $month_year = "";
        $pre_month_year = "";
        $reportID = $request['reportID'];
        $report_date = $request['report_date'];
        $report_date = trim($report_date);  // Ensure no extra spaces
        $report_date_array = explode("-", $report_date);
        $mth = trim($report_date_array[0]);
        $yr = $report_date_array[1];


        $month_year = $this->get_bangla_monthbymumber($mth, $yr);

        $lastmonth = "01";
        $year = "";
        $lastyear = "";

        if ($mth == '01') { //
            $lastmonth = 12;
            $lastyear = $yr - 01;
        } else {
            $lastmonth = $mth - 01;
            $lastmonth = str_pad($lastmonth, 2, '0', STR_PAD_LEFT);
            $lastyear = $yr;
        }

        $pre_month_year = $this->get_bangla_monthbymumber($lastmonth, $lastyear);

        $roleID = $request['roleID'];
        $sWhere = "";

        if ($roleID  == 34) { // Divisional Commissioner

            $divid =  $request['divid'];
            $zillaId =  $request['zillaId'];
            $zilla_name = $request['zilla_name'];
            $divname_name =  $request['divname_name'];

            $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>বিভাগীয় কমিশনারের কার্যালয় , $divname_name</br>  অতিরিক্ত জেলা ম্যাজিস্ট্রেট আদালতের ফৌজদারী কার্যবিধির আওতাধীন মামলার বিবরণ";
            // $sWhere = [
            //     ['monthly_reports.divid', '=', $zillaId],
            //     ['monthly_reports.report_type_id', '=', 3],
            //     ['monthly_reports.is_approved', '=', 1]
            // ];
            $sWhere = "WHERE   divid = $divid  AND report_type_id = 3  AND is_approved = 1";
        }
        if ($roleID  == 8 ||  $roleID == 2 || $roleID == 25) { //JS

            $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>মন্ত্রিপরিষদ  বিভাগ</br>জেলা ম্যাজিস্ট্রেসি পরিবীক্ষণ অধিশাখা</br>  অতিরিক্ত জেলা ম্যাজিস্ট্রেট আদালতের ফৌজদারী কার্যবিধির আওতাধীন মামলার বিবরণ";
            // $sWhere = [
            //     ['monthly_reports.report_type_id', '=', 3],
            //     ['monthly_reports.is_approved', '=', 1]
            // ];
            $sWhere = "WHERE report_type_id = 3  AND is_approved = 1";
        }



        // $result = DB::table('monthly_reports')
        //     ->select(
        //         'divname',
        //         'comment2',
        //         'monthly_reports.comment1_str',
        //         'monthly_reports.comment1',
        //         DB::raw("
        //             SUM(IF( report_month = $lastmonth AND  report_year = $lastyear , case_submit ,0 ) )case_submit1,
        //             SUM(IF( report_month = $mth  AND  report_year = $yr , case_submit ,0 ))case_submit2,
        //             SUM(IF( report_month = $lastmonth AND  report_year = $lastyear  , case_total ,0 ) )case_total1,
        //             SUM(IF( report_month = $mth AND  report_year = $yr , case_total ,0 ))case_total2,
        //             SUM(IF( report_month = $lastmonth  AND  report_year = $lastyear , case_complete ,0 ) )case_complete1,
        //             SUM(IF( report_month = $mth  AND  report_year = $yr, case_complete ,0 ))case_complete2,
        //             SUM(IF( report_month = $lastmonth AND  report_year = $lastyear  , case_incomplete ,0 ) )case_incomplete1,
        //             SUM(IF( report_month = $mth  AND  report_year = $yr, case_incomplete,0 ))case_incomplete2
        //         ")
        //     )
        //     ->where($sWhere)  // Apply any raw where conditions
        //     ->groupBy('divname', 'comment2', 'monthly_reports.comment1_str', 'monthly_reports.comment1')
        //     ->get();


        // $resultset = $result->map(function ($item) {
        //     return [
        //         0 => $item->divname,                  // "বরিশাল"
        //         1 => $item->comment2,                  // ""
        //         2 => $item->comment1_str,              // " প্রমাপ অর্জিত হয়েছে"
        //         3 => $item->comment1,                  // "1"
        //         4 => $item->case_submit1,              // "317"
        //         5 => $item->case_submit2,              // "235"
        //         6 => $item->case_total1,               // "2060"
        //         7 => $item->case_total2,               // "1499"
        //         8 => $item->case_complete1,            // "284"
        //         9 => $item->case_complete2,            // "278"
        //         10 => $item->case_incomplete1,         // "1776"
        //         11 => $item->case_incomplete2,         // "1733"
        //         'case_complete1' => $item->case_complete1,
        //         'case_complete2' => $item->case_complete2,
        //         'case_incomplete1' => $item->case_incomplete1,
        //         'case_incomplete2' => $item->case_incomplete2,
        //         'case_submit1' => $item->case_submit1,
        //         'case_submit2' => $item->case_submit2,
        //         'case_total1' => $item->case_total1,
        //         'case_total2' => $item->case_total2,
        //         'comment1' => $item->comment1,
        //         'comment1_str' => $item->comment1_str,
        //         'comment2' => $item->comment2,
        //         'divname' => $item->divname
        //     ];
        // });

    $sSel = "
        SUM(IF( report_month = $lastmonth AND  report_year = $lastyear , case_submit ,0 ) )case_submit1,
        SUM(IF( report_month = $mth  AND  report_year = $yr , case_submit ,0 ))case_submit2,
        SUM(IF( report_month = $lastmonth AND  report_year = $lastyear  , case_total ,0 ) )case_total1,
        SUM(IF( report_month = $mth AND  report_year = $yr , case_total ,0 ))case_total2,
        SUM(IF( report_month = $lastmonth  AND  report_year = $lastyear , case_complete ,0 ) )case_complete1,
        SUM(IF( report_month = $mth  AND  report_year = $yr, case_complete ,0 ))case_complete2,
        SUM(IF( report_month = $lastmonth AND  report_year = $lastyear  , case_incomplete ,0 ) )case_incomplete1,
        SUM(IF( report_month = $mth  AND  report_year = $yr, case_incomplete,0 ))case_incomplete2";

        $sql = "
        SELECT
            divname,comment2,comment1_str,comment1,
            $sSel
        FROM monthly_reports
        $sWhere
        GROUP BY divid
        WITH ROLLUP
        ";
        // Execute the raw SQL query and fetch results
        $resultset = DB::select(DB::raw($sql));
        $response = array(
            "result" => $resultset,
            "name" => $reportName,
            "profileID" => '',
            "zilla_name" => $zilla_name,
            "month_year" => $month_year,
            "pre_month_year" => $pre_month_year
        );
        return response()->json($response);
    }


    public function printdivemcasereport(Request $request)
    {
        $requestData = $request->all();
        $request = $requestData['body_data'];

        $resultset = array();
        $reportName = "No data Found";
        $result = array();
        $zilla_name = "";
        $start_date = "";
        $month_year = "";
        $divname_name = "";
        $pre_month_year = "";
        $reportID = $request['reportID'];
        $report_date = $request['report_date'];
        $report_date = trim($report_date);  // Ensure no extra spaces
        $report_date_array = explode("-", $report_date);
        $mth = trim($report_date_array[0]);

        $yr = $report_date_array[1];

        $month_year = $this->get_bangla_monthbymumber($mth, $yr);

        $roleID = $request['roleID'];

        $lastmonth = "01";
        $year = "";
        $lastyear = "";

        if ($mth == '01') { //
            $lastmonth = 12;
            $lastyear = $yr - 1;
        } else {
            $lastmonth = $mth - 1;
            $lastmonth = str_pad($lastmonth, 2, '0', STR_PAD_LEFT);
            $lastyear = $yr;
        }


        $pre_month_year = $this->get_bangla_monthbymumber($lastmonth, $lastyear);

        $sWhere = "";



        if ($roleID  == 34) {

            $divid =  $request['divid'];
            $zillaId =  $request['zillaId'];
            $zilla_name = $request['zilla_name'];
            $divname_name =  $request['divname_name'];

            $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>বিভাগীয় কমিশনারের কার্যালয় , $divname_name</br>এক্সিকিউটিভ ম্যাজিস্ট্রেটের আদালতে ফৌজদারী কার্যবিধির আওতাধীন মামলার বিবরণ";
            // $sWhere = [
            //     ['monthly_reports.divid', '=', $zillaId],
            //     ['monthly_reports.report_type_id', '=', 3],
            //     ['monthly_reports.is_approved', '=', 1]
            // ];
            $sWhere = "WHERE   divid = $divid  AND report_type_id = 4  AND is_approved = 1";
        }
        if ($roleID  == 8 ||  $roleID == 2 || $roleID == 25) { //JS

            $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>মন্ত্রিপরিষদ  বিভাগ</br>জেলা ম্যাজিস্ট্রেসি পরিবীক্ষণ অধিশাখা</br>এক্সিকিউটিভ ম্যাজিস্ট্রেটের আদালতে ফৌজদারী কার্যবিধির আওতাধীন মামলার বিবরণ";
            // $sWhere = [
            //     ['monthly_reports.report_type_id', '=', 4],
            //     ['monthly_reports.is_approved', '=', 1]
            // ];
            $sWhere = "WHERE report_type_id = 4   AND is_approved = 1";
        }

        // $result = DB::table('monthly_reports')
        //     ->select(
        //         'divname',
        //         'comment2',
        //         'monthly_reports.comment1_str',
        //         'monthly_reports.comment1',
        //         DB::raw("
        //         SUM(IF( report_month = $lastmonth  AND report_year = $lastyear, case_submit ,0 ) )case_submit1,
        //         SUM(IF( report_month = $mth  AND report_year = $yr, case_submit ,0 ))case_submit2,
        //         SUM(IF( report_month = $lastmonth  AND report_year = $lastyear, case_total ,0 ) )case_total1,
        //         SUM(IF( report_month = $mth  AND report_year = $yr, case_total ,0 ))case_total2,
        //         SUM(IF( report_month = $lastmonth AND report_year = $lastyear , case_complete ,0 ) )case_complete1,
        //         SUM(IF( report_month = $mth AND report_year = $yr , case_complete ,0 ))case_complete2,
        //         SUM(IF( report_month = $lastmonth  AND report_year = $lastyear, case_incomplete ,0 ) )case_incomplete1,
        //         SUM(IF( report_month = $mth  AND report_year = $yr, case_incomplete,0 ))case_incomplete2
        //     ")
        //     )
        //     ->where($sWhere)  // Apply any raw where conditions
        //     ->groupBy('divname', 'comment2', 'monthly_reports.comment1_str', 'monthly_reports.comment1')
        //     ->get();


        // $finalResults = $result->map(function ($item) {
        //     return [
        //         // Indexed fields
        //         $item->divname,        // Index 0
        //         $item->comment2,       // Index 1
        //         $item->comment1_str,   // Index 2
        //         $item->comment1,       // Index 3
        //         $item->case_submit1,   // Index 4
        //         $item->case_submit2,   // Index 5
        //         $item->case_total1,    // Index 6
        //         $item->case_total2,    // Index 7
        //         $item->case_complete1, // Index 8
        //         $item->case_complete2, // Index 9
        //         $item->case_incomplete1, // Index 10
        //         $item->case_incomplete2, // Index 11

        //         // Named fields
        //         'case_complete1' => $item->case_complete1,
        //         'case_complete2' => $item->case_complete2,
        //         'case_incomplete1' => $item->case_incomplete1,
        //         'case_incomplete2' => $item->case_incomplete2,
        //         'case_submit1' => $item->case_submit1,
        //         'case_submit2' => $item->case_submit2,
        //         'case_total1' => $item->case_total1,
        //         'case_total2' => $item->case_total2,
        //         'comment1' => $item->comment1,
        //         'comment1_str' => $item->comment1_str,
        //         'comment2' => $item->comment2,
        //         'divname' => $item->divname
        //     ];
        // });
        $sSel = "
        SUM(IF( report_month = $lastmonth  AND report_year = $lastyear, case_submit ,0 ) )case_submit1,
        SUM(IF( report_month = $mth  AND report_year = $yr, case_submit ,0 ))case_submit2,
        SUM(IF( report_month = $lastmonth  AND report_year = $lastyear, case_total ,0 ) )case_total1,
        SUM(IF( report_month = $mth  AND report_year = $yr, case_total ,0 ))case_total2,
        SUM(IF( report_month = $lastmonth AND report_year = $lastyear , case_complete ,0 ) )case_complete1,
        SUM(IF( report_month = $mth AND report_year = $yr , case_complete ,0 ))case_complete2,
        SUM(IF( report_month = $lastmonth  AND report_year = $lastyear, case_incomplete ,0 ) )case_incomplete1,
        SUM(IF( report_month = $mth  AND report_year = $yr, case_incomplete,0 ))case_incomplete2";
        $sql = "
        SELECT
            divname,comment2,comment1_str,comment1,
            $sSel
        FROM monthly_reports
        $sWhere
        GROUP BY divid
        WITH ROLLUP
        ";
        // Execute the raw SQL query and fetch results
        $resultset = DB::select(DB::raw($sql));

        $response = array(
            "result" => $resultset,
            "name" => $reportName,
            "profileID" => '',
            "zilla_name" => $zilla_name,
            "month_year" => $month_year,
            "pre_month_year" => $pre_month_year
        );
        return response()->json($response);
    }

    public function printdivapprovedreport(Request $request)
    {
        $requestData = $request->all();
        $request = $requestData['body_data'];

        $resultset = array();
        $reportName = "No data Found";
        $result = array();
        $zilla_name = "";
        $start_date = "";
        $month_year = "";
        $pre_month_year = "";

        $reportID = $request['reportID'];
        $report_date = $request['report_date'];
        $report_date = trim($report_date);  // Ensure no extra spaces
        $report_date_array = explode("-", $report_date);
        $mth = trim($report_date_array[0]);

        $yr = $report_date_array[1];

        $month_year = $this->get_bangla_monthbymumber($mth, $yr);

        $roleID = $request['roleID'];
        $lastmonth = "01";
        $year = "";

        if ($mth == '01') { //
            $lastmonth = $mth - 1;
            $lastmonth = str_pad($lastmonth, 2, '0', STR_PAD_LEFT);
            $year = $yr - 1;
        } else {
            $lastmonth = $mth - 1;
            $lastmonth = str_pad($lastmonth, 2, '0', STR_PAD_LEFT);
        }

        $sWhere = "";
        if ($roleID == 34) {
            // Divisional Commissioner
            $divid =  $request['divid'];
            $zillaId =  $request['zillaId'];
            $zilla_name = $request['zilla_name'];
            $divname_name =  $request['divname_name'];


            // $sWhere = [
            //     ['monthly_reports.divid', '=', $divid],
            //     ['monthly_reports.report_month', '=', $mth],
            //     ['monthly_reports.report_year', '=', $yr]
            // ];
            $sWhere = "WHERE   divid = $divid  AND  report_month = $mth  AND report_year = $yr";

            $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>বিভাগীয় কমিশনারের কার্যালয় , $divname_name</br>অনুমোদিত প্রতিবেদনের পরিসংখ্যান ";
        }
        if ($roleID == 2 || $roleID == 8 || $roleID == 25) { //JS

            // $sWhere = [
            //     ['monthly_reports.report_month', '=', $mth],
            //     ['monthly_reports.report_year', '=', $yr],
            // ];
            $sWhere = "WHERE  report_month = $mth  AND report_year = $yr  ";
            $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>মন্ত্রিপরিষদ  বিভাগ</br>জেলা ম্যাজিস্ট্রেসি পরিবীক্ষণ অধিশাখা</br>অনুমোদিত প্রতিবেদনের পরিসংখ্যান ";
        }



        // $result = DB::table('monthly_reports')
        //     ->select(
        //         'monthly_reports.divname',
        //         'monthly_reports.zillaname',
        //         'monthly_reports.comment2',
        //         'monthly_reports.comment1_str',
        //         'monthly_reports.comment1',
        //         'monthly_reports.divid',  // Fixed: Removed extra space here
        //         'monthly_reports.zillaid',
        //         DB::raw("
        //         SUM(CASE WHEN `report_type_id` = 1 THEN `is_approved` ELSE 0 END) AS re1,
        //         SUM(CASE WHEN `report_type_id` = 2 THEN `is_approved` ELSE 0 END) AS re2,
        //         SUM(CASE WHEN `report_type_id` = 3 THEN `is_approved` ELSE 0 END) AS re3,
        //         SUM(CASE WHEN `report_type_id` = 4 THEN `is_approved` ELSE 0 END) AS re4,
        //         SUM(CASE WHEN `report_type_id` = 5 THEN `is_approved` ELSE 0 END) AS re5,
        //         SUM(CASE WHEN `report_type_id` = 6 THEN `is_approved` ELSE 0 END) AS re6
        //     ")
        //     )
        //     ->where($sWhere)  // Apply any raw where conditions
        //     ->groupBy(
        //         'monthly_reports.divname',
        //         'monthly_reports.zillaname',
        //         'monthly_reports.comment2',
        //         'monthly_reports.comment1_str',
        //         'monthly_reports.comment1',
        //         'monthly_reports.divid',  // Fixed: Added column to group by
        //         'monthly_reports.zillaid'
        //     )
        //     ->get()
        //     ->map(function ($item) {
        //         return [
        //             0 => $item->divname,
        //             1 => $item->zillaname,
        //             2 => $item->comment1_str,
        //             3 => $item->comment1,
        //             4 => $item->comment2,
        //             5 => $item->divid,
        //             6 => $item->zillaid,
        //             7 => $item->re1 ?? 0,
        //             8 => $item->re2 ?? 0,
        //             9 => $item->re3 ?? 0,
        //             10 => $item->re4 ?? 0,
        //             11 => $item->re5 ?? 0,
        //             12 => $item->re6 ?? 0,
        //             'comment1' => $item->comment1 ?? '0',
        //             'comment1_str' => $item->comment1_str ?? null,
        //             'comment2' => $item->comment2 ?? null,
        //             'divid' => $item->divid,
        //             'divname' => $item->divname,
        //             're1' => $item->re1 ?? 0,
        //             're2' => $item->re2 ?? 0,
        //             're3' => $item->re3 ?? 0,
        //             're4' => $item->re4 ?? 0,
        //             're5' => $item->re5 ?? 0,
        //             're6' => $item->re6 ?? 0,
        //             'zillaid' => $item->zillaid,
        //             'zillaname' => $item->zillaname,
        //         ];
        //     });
        $sSel = "
        divid,zillaid  ,
        SUM(if( `report_type_id` =1, `is_approved` , 0 )) AS re1,
        SUM(if( `report_type_id` =2, `is_approved` , 0 )) AS re2,
        SUM(if( `report_type_id` =3, `is_approved` , 0 )) AS re3,
        SUM(if( `report_type_id` =4, `is_approved` , 0 )) AS re4,
        SUM(if( `report_type_id` =5, `is_approved` , 0 )) AS re5,
        SUM(if( `report_type_id` =6, `is_approved` , 0 )) AS re6
        ";
        $sql = "
        SELECT
            divname,
            zillaname , comment2 , comment1_str , comment1,
            $sSel
        FROM monthly_reports
        $sWhere
        GROUP BY divid , zillaid 
        ORDER BY `report_type_id`
        ";
        // Execute the raw SQL query and fetch results
        $resultset = DB::select(DB::raw($sql));
        $response = array(
            "result" => $resultset,
            "name" => $reportName,
            "profileID" => "",
            "zilla_name" => $zilla_name,
            "month_year" => $month_year,
            "pre_month_year" => $pre_month_year
        );

        return response()->json($response);
    }

    public function printmobilecourtreport(Request $request)
    {

        
        $requestData = $request->all();
        $request = $requestData['body_data'];

        $resultset = array();
        $reportName = "No data Found";
        $result = array();
        $zilla_name = "";
        $start_date = "";
        $month_year = "";

        $reportID = $request['reportID'];
        // $userlocation = $this->auth->getUserLocation();

        $office_id = $request['office_id'];
        $roleID = $request['roleID'];


        $divid =  $request['divid'];
        $zillaId =  $request['zillaId'];
        $zilla_name = $request['zilla_name'];
        $div_name =  $request['divname_name'];




        $report_date = $request['report_date'];

        $report_date_array = explode("-", $report_date);
        $mth = $report_date_array[0];
        $yr = $report_date_array[1];

        $month_year = $this->get_bangla_monthbymumber($mth, $yr);


        $lastmonth = "01";
        $year = "";
        $lastyear = "";

        if ($mth == '01') { //
            $lastmonth = 12;
            $lastyear = $yr - 1;
        } else {
            $lastmonth = $mth - 1;
            $lastyear = $yr;
        }
        $sWhere = "";


        if ($roleID == 37 || $roleID == 38) {

            $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>জেলা প্রশাসকের কার্যালয়্যালয়,$zilla_name  </br>মোবাইল কোর্টের পরিচালনা সংক্রান্ত মাসিক প্রতিবেদন";
            if ($sWhere == "") {
                $sWhere = [
                    ['zilla.division_id', '=', $divid],
                    ['zilla.id', '=', $zillaId]
                ];
            }
        }



        if ($roleID == 34 ) {
            // $sWhere = [
            //     ['zilla.division_id', '=', $divid],
            // ];
            $sWhere = "WHERE   zilla.division_id = $divid ";
            $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার </br>বিভাগীয় কমিশনারের কার্যালয়,$div_name </br>মোবাইল কোর্টের পরিচালনা সংক্রান্ত মাসিক প্রতিবেদন";
           
        }
        if ($roleID == 2 || $roleID == 8 || $roleID == 25) {
            $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার </br>মন্ত্রিপরিষদ  বিভাগ</br>জেলা ম্যাজিস্ট্রেসি পরিবীক্ষণ অধিশাখা</br>মোবাইল কোর্টের পরিচালনা সংক্রান্ত মাসিক প্রতিবেদন";
            // $sWhere = [
            //     [1, '=', 1],

            // ];
            $sWhere = "WHERE 1=1 ";
        }
        $sCondition = " AND report_type_id = 1   AND is_approved = 1";
        // $sCondition = [
        //     ['monthly_reports.report_type_id', '=', 1],
        //     ['monthly_reports.is_approved', '=', 1]
        // ];
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
       
        // $sWheres = "1=1 ";
        // Execute the query using Laravel's Query Builder
        // $results1 = DB::table('district as zilla')
        //     ->join('division', 'zilla.division_id', '=', 'division.id')
        //     ->leftJoin('monthly_reports', function ($join) use ($sCondition) {
        //         $join->on('monthly_reports.zillaid', '=', 'zilla.id')
        //             ->where($sCondition);
        //     })
        //     ->select('division.division_name_bn as divname', 'division.id as divid', 'zilla.id as zillaid', 'zilla.district_name_bn as zillaname', 'monthly_reports.comment2', 'monthly_reports.comment1_str', 'monthly_reports.comment1')
        //     ->addSelect($sSel);
        //     if ($roleID == 2 || $roleID == 8 || $roleID == 25) {
        //         $results1->whereRaw($sWhere); // Add $sWhere equivalent condition; 
        //         // $results1->where($sWhere);
        //     }else{
        //         $results1->where($sWhere);
        //     }
        //     $resultset=  $results1->groupBy(
        //         // 'division.division_name_bn',
        //         // 'zilla.division_id',
        //         // 'zilla.id',
        //         // 'zilla.district_name_bn',
        //         // 'monthly_reports.comment2',
        //         // 'monthly_reports.comment1_str',
        //         // 'monthly_reports.comment1'
        //         'zilla.division_id' , 'zilla.id'
        //     ) // Add these columns to GROUP BY
            
        //     ->orderBy('division.id') // Optional: Order by division ID
        //     ->get();

        // $result = DB::table('district as zilla')
        // ->join('division', 'zilla.division_id', '=', 'division.id')
        // ->leftJoin('monthly_reports', function($join) {
        //     $join->on('monthly_reports.zillaid', '=', 'zilla.id')
        //          ->whereRaw('monthly_reports.report_type_id = 1 AND monthly_reports.is_approved = 1');
        // })
        // ->select('division.divname', 'division.id as divid', 'zilla.id as zillaid', 'zilla.district_name_bn as zillaname', 
        //          'monthly_reports.comment2', 'monthly_reports.comment1_str', 'monthly_reports.comment1', 
        //          DB::raw($sSel))
        // ->whereRaw($sCondition)
        // ->whereRaw($sWhere)
        // ->groupBy('zilla.divid', 'zilla.zillaid')
        // ->withRollup()
        // ->get();
        $sSel = "
        SUM(IF( report_month = $mth  AND report_year = $yr , promap ,0  ) )promap,
        SUM(IF( report_month = $mth  AND report_year = $yr , upozila ,0 ))upozila,
        SUM(IF( report_month = $lastmonth AND report_year = $lastyear , case_total ,0 ) )case_total1,
        SUM(IF( report_month = $mth  AND report_year = $yr, case_total ,0 ))case_total2,
        SUM(IF( report_month = $lastmonth AND report_year = $lastyear , court_total, 0 ))  court_total1,
        SUM(IF( report_month = $mth AND report_year = $yr , court_total ,0 ))  court_total2,
        SUM(IF( report_month = $lastmonth AND report_year = $lastyear ,fine_total, 0 )) fine_total1,
        SUM(IF( report_month = $mth  AND report_year = $yr , fine_total  ,0 )) fine_total2,
        SUM(IF( report_month = $lastmonth  AND report_year = $lastyear , lockup_criminal_total , 0)) lockup_criminal_total1,
        SUM(IF( report_month = $mth  AND report_year = $yr , lockup_criminal_total ,0 ) ) lockup_criminal_total2,
        SUM(IF( report_month = $lastmonth  AND report_year = $lastyear , criminal_total , 0)) criminal_total1,
        SUM(IF( report_month = $mth  AND report_year = $yr, criminal_total ,0 ) ) criminal_total2";
    
    // Ensure that $sCondition and $sWhere are strings (if they're arrays)
    // $sCondition = implode(' ', (array) $sCondition);
    // $sWhere = implode(' ', (array) $sWhere);
    
    $sql = "
        SELECT
            division.division_name_bn as divname,
            division.id as divid,
            zilla.id as zillaid,
            zilla.district_name_bn as zillaname,
            monthly_reports.comment2,
            monthly_reports.comment1_str,
            monthly_reports.comment1,
            $sSel
        FROM district as zilla
        INNER JOIN division ON zilla.division_id = division.id
        LEFT OUTER JOIN monthly_reports ON monthly_reports.zillaid = zilla.id
        $sCondition
        $sWhere
        GROUP BY zilla.division_id, zilla.id
        WITH ROLLUP
    ";
    
    // Execute the raw SQL query and fetch results
    $result = DB::select(DB::raw($sql));
     
 


        // $formattedResult = $resultset->map(function ($item) {
        //     return [
        //         0 => $item->divname,
        //         1 => $item->divid,
        //         2 => $item->zillaid,
        //         3 => $item->zillaname,
        //         4 => $item->comment2,
        //         5 => $item->comment1_str,
        //         6 => $item->comment1,
        //         7 => $item->promap,
        //         8 => $item->upozila,
        //         9 => $item->case_total1,
        //         10 => $item->case_total2,
        //         11 => $item->court_total1,
        //         12 => $item->court_total2,
        //         13 => $item->fine_total1,
        //         14 => $item->fine_total2,
        //         15 => $item->lockup_criminal_total1,
        //         16 => $item->lockup_criminal_total2,
        //         17 => $item->criminal_total1,
        //         18 => $item->criminal_total2,
        //         'case_total1' => $item->case_total1,
        //         'case_total2' => $item->case_total2,
        //         'comment1' => $item->comment1,
        //         'comment1_str' => $item->comment1_str,
        //         'comment2' => $item->comment2,
        //         'court_total1' => $item->court_total1,
        //         'court_total2' => $item->court_total2,
        //         'criminal_total1' => $item->criminal_total1,
        //         'criminal_total2' => $item->criminal_total2,
        //         'divid' => $item->divid,
        //         'divname' => $item->divname,
        //         'fine_total1' => $item->fine_total1,
        //         'fine_total2' => $item->fine_total2,
        //         'lockup_criminal_total1' => $item->lockup_criminal_total1,
        //         'lockup_criminal_total2' => $item->lockup_criminal_total2,
        //         'promap' => $item->promap,
        //         'upozila' => $item->upozila,
        //         'zillaid' => $item->zillaid,
        //         'zillaname' => $item->zillaname
        //     ];
        // });


       
        $data = [
            "result" => $result,
            "name" => $reportName,
            "profileID" => 5,
            "zilla_name" => $zilla_name,
            "month_year" => $month_year
        ];

      
        return response()->json($data, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }

    public function printappealcasereport(Request $request)
    {
        $requestData = $request->all();
        $request = $requestData['body_data'];

        $resultset = array();
        $reportName = "No data Found";
        $result = array();
        $zilla_name = "";
        $start_date = "";
        $month_year = "";
        $reportID = $request['reportID'];
        $office_id = $request['office_id'];
        // $user = Auth::user();
        $roleID = $request['roleID'];

        $divid =  $request['divid'];
        $zillaId =  $request['zillaId'];
        $zilla_name = $request['zilla_name'];
        $divname_name =  $request['divname_name'];
        $report_date = $request['report_date'];
        // dd($officeinfo);

        $report_date_array = explode("-", $report_date);
        $mth = $report_date_array[0];
        $yr = $report_date_array[1];

        $month_year = $this->get_bangla_monthbymumber($mth, $yr);
        $lastmonth = "01";
        $year = "";
        $lastyear = "";

        if ($mth == '01') { //
            $lastmonth = 12;
            $lastyear = $yr - 1;
        } else {
            $lastmonth = $mth - 1;
            $lastyear = $yr;
        }
        $sWhere = "";
        $sCondition = "";
        if ($roleID == 37 || $roleID == 38) {
            if ($sWhere == "") {
                // $sWhere = "WHERE   zilla.divid = $divid  AND zilla.zillaid =  $zillaId ";
                $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>জেলা প্রশাসকের কার্যালয়, $zilla_name</br>মোবাইল কোর্টের আইনের আওতায় দায়েরকৃত আপিল মামলার বিবরণ ";
                $sWhere = [
                    ['zilla.division_id', '=', $divid],
                    ['zilla.id', '=', $zillaId]
                ];
            }
        }

        if ($roleID == 34) { // Divisional Commissioner
            if ($sWhere == "") {

                // $sWhere = [
                //     ['zilla.division_id', '=', $divid],

                // ];
                $sWhere = "WHERE   zilla.division_id = $divid ";
                $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>বিভাগীয় কমিশনারের কার্যালয় , $divname_name</br>মোবাইল কোর্টের আইনের আওতায় দায়েরকৃত আপিল মামলার বিবরণ ";
            }
        }
        if ($roleID == 2 || $roleID == 8 || $roleID == 25) { //JS
            if ($sWhere == "") {
                $sWhere = "";
                $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>মন্ত্রিপরিষদ  বিভাগ</br>জেলা ম্যাজিস্ট্রেসি পরিবীক্ষণ অধিশাখা</br>মোবাইল কোর্টের আইনের আওতায় দায়েরকৃত আপিল মামলার বিবরণ ";
            }
        }

        // $sCondition = [
        //     ['monthly_reports.report_type_id', '=', 2],
        //     ['monthly_reports.report_month', '=', $mth],
        //     ['monthly_reports.report_year', '=', $yr],
        //     ['monthly_reports.is_approved', '=', 1]
        // ];
        $sCondition = " AND report_type_id = 2 AND report_month = $mth AND report_year = $yr AND is_approved = 1";

        // $selects = [
        //     DB::raw("SUM(IF(promap, promap, 0)) as promap"),
        //     DB::raw("SUM(IF(promap_achive, promap_achive, 0)) as promap_achive"),
        //     DB::raw("SUM(IF(pre_case_incomplete, pre_case_incomplete, 0)) as pre_case_incomplete"),
        //     DB::raw("SUM(IF(case_submit, case_submit, 0)) as case_submit"),
        //     DB::raw("SUM(IF(case_total, case_total, 0)) as case_total"),
        //     DB::raw("SUM(IF(case_complete, case_complete, 0)) as case_complete"),
        //     DB::raw("SUM(IF(case_incomplete, case_incomplete, 0)) as case_incomplete"),
        //     DB::raw("SUM(IF(case_above1year, case_above1year, 0)) as case_above1year"),
        //     DB::raw("SUM(IF(case_above2year, case_above2year, 0)) as case_above2year"),
        //     DB::raw("SUM(IF(case_above3year, case_above3year, 0)) as case_above3year"),
        // ];

        // // Execute the query using Laravel's Query Builder
        // $result = DB::table('district as zilla')
        //     ->join('division', 'zilla.division_id', '=', 'division.id')
        //     ->leftJoin('monthly_reports', function ($join) use ($sCondition) {
        //         $join->on('monthly_reports.zillaid', '=', 'zilla.id')
        //             ->where($sCondition);
        //     })
        //     ->select('division.division_name_bn as divname', 'division.id as divid', 'zilla.id as zillaid', 'zilla.district_name_bn as zillaname', 'monthly_reports.comment2', 'monthly_reports.comment1_str', 'monthly_reports.comment1')
        //     ->addSelect($selects);
        // if ($sWhere) {
        //     $result->where($sWhere);
        // }
        // $resultset = $result->groupBy(
        //     'zilla.division_id',
        //     'zilla.id',
        //     'monthly_reports.comment2',
        //     'monthly_reports.comment1_str',
        //     'monthly_reports.comment1'
        // ) // Add these columns to GROUP BY
        //     ->orderBy('division.id') // Optional: Order by division ID
        //     ->get();



        // dd($resultset);
        // $formattedResult = $resultset->map(function ($item) {
        //     return [
        //         0 => $item->zillaname, // 0: "বরিশাল"
        //         1 => $item->promap, // 1: "10"
        //         2 => $item->promap_achive, // 2: "4"
        //         3 => $item->zillaname, // 3: "বরগুনা" (assuming this is another zilla name)
        //         4 => null, // 4: null
        //         5 => null, // 5: null
        //         6 => null, // 6: null
        //         7 => $item->case_above1year, // 7: "0"
        //         8 => $item->case_above2year, // 8: "0"
        //         9 => $item->case_above3year, // 9: "0"
        //         10 => $item->case_complete, // 10: "0"
        //         11 => $item->case_incomplete, // 11: "0"
        //         12 => $item->case_submit, // 12: "0"
        //         13 => $item->case_total, // 13: "0"
        //         14 => null, // 14: "0" (could add other needed fields here)
        //         15 => null, // 15: "0" (as above)
        //         16 => null, // 16: "0" (as above)
        //         17 => null, // 17: "0" (as above)
        //         'case_above1year' => $item->case_above1year, // "0"
        //         'case_above2year' => $item->case_above2year, // "0"
        //         'case_above3year' => $item->case_above3year, // "0"
        //         'case_complete' => $item->case_complete, // "0"
        //         'case_incomplete' => $item->case_incomplete, // "0"
        //         'case_submit' => $item->case_submit, // "0"
        //         'case_total' => $item->case_total, // "0"
        //         'comment1' => null, // null
        //         'comment1_str' => null, // null
        //         'comment2' => null, // null
        //         'divid' => $item->divid, // "10"
        //         'divname' => $item->divname, // "বরিশাল"
        //         'pre_case_incomplete' => $item->pre_case_incomplete, // "0"
        //         'promap' => $item->promap, // "0"
        //         'promap_achive' => $item->promap_achive, // "0"
        //         'zillaid' => $item->zillaid, // "4"
        //         'zillaname' => $item->zillaname, // "বরগুনা"
        //     ];
        // });


        $sSel = "
            SUM(IF(promap, promap, 0)) as promap,
            SUM(IF(promap_achive, promap_achive, 0)) as promap_achive,
            SUM(IF(pre_case_incomplete, pre_case_incomplete, 0)) as pre_case_incomplete,
            SUM(IF(case_submit, case_submit, 0)) as case_submit,
            SUM(IF(case_total, case_total, 0)) as case_total,
            SUM(IF(case_complete, case_complete, 0)) as case_complete,
            SUM(IF(case_incomplete, case_incomplete, 0)) as case_incomplete,
            SUM(IF(case_above1year, case_above1year, 0)) as case_above1year,
            SUM(IF(case_above2year, case_above2year, 0)) as case_above2year,
            SUM(IF(case_above3year, case_above3year, 0)) as case_above3year
        ";

        $result = DB::select(
            DB::raw(" 
                SELECT 
                    division.division_name_bn as divname, 
                    division.id as divid, 
                    zilla.id as zillaid, 
                    zilla.district_name_bn as zillaname, 
                    monthly_reports.comment2, 
                    monthly_reports.comment1_str, 
                    monthly_reports.comment1, 
                    $sSel 
                FROM district as zilla
                INNER JOIN division ON zilla.division_id = division.id
                LEFT OUTER JOIN monthly_reports ON monthly_reports.zillaid = zilla.id
                $sCondition
                $sWhere
                GROUP BY zilla.division_id, zilla.id 
                WITH ROLLUP
            ")
        );
 


        $data = [
            "result" => $result,
            "name" => $reportName,
            "profileID" => 9,
            "zilla_name" => $zilla_name,
            "month_year" => $month_year
        ];
        return response()->json($data, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }

    public function printadmcasereport(Request $request)
    {
        $requestData = $request->all();
        $request = $requestData['body_data'];

        $resultset = array();
        $reportName = "No data Found";
        $result = array();
        $zilla_name = "";
        $start_date = "";
        $month_year = "";
        $reportID = $request['reportID'];
        $office_id = $request['office_id'];
        // $user = Auth::user();
        $roleID = $request['roleID'];

        $divid =  $request['divid'];
        $zillaId =  $request['zillaId'];
        $zilla_name = $request['zilla_name'];
        $divname_name =  $request['divname_name'];

        $report_date = $request['report_date'];
        $report_date_array = explode("-", $report_date);
        $mth = $report_date_array[0];
        $yr = $report_date_array[1];
        $month_year = $this->get_bangla_monthbymumber($mth, $yr);
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
        if ($roleID == 37 || $roleID == 38) {
            if ($sWhere == "") {

                $sWhere = [
                    ['zilla.division_id', '=', $divid],
                    ['zilla.id', '=', $zillaId]
                ];
                $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br> জেলা প্রশাসকের কার্যালয়, $zilla_name</br>  অতিরিক্ত জেলা ম্যাজিস্ট্রেট আদালতের ফৌজদারী কার্যবিধির আওতাধীন মামলার বিবরণ";
            }
        }
        if ($roleID == 34) { // Divisional Commissioner
            if ($sWhere == "") {

                // $sWhere = [
                //     ['zilla.division_id', '=', $divid],

                // ];
                $sWhere = "WHERE   zilla.division_id = $divid ";
                $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>বিভাগীয় কমিশনারের কার্যালয় , $divname_name</br>  অতিরিক্ত জেলা ম্যাজিস্ট্রেট আদালতের ফৌজদারী কার্যবিধির আওতাধীন মামলার বিবরণ";
            }
        }
        if ($roleID == 2 || $roleID == 8 || $roleID == 25) { //JS
            if ($sWhere == "") {
                $sWhere = "";
                $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>মন্ত্রিপরিষদ  বিভাগ</br>জেলা ম্যাজিস্ট্রেসি পরিবীক্ষণ অধিশাখা</br>  অতিরিক্ত জেলা ম্যাজিস্ট্রেট আদালতের ফৌজদারী কার্যবিধির আওতাধীন মামলার বিবরণ";
            }
        }


        // $sCondition = [
        //     ['monthly_reports.report_type_id', '=', 3],
        //     ['monthly_reports.report_month', '=', $mth],
        //     ['monthly_reports.report_year', '=', $yr],
        //     ['monthly_reports.is_approved', '=', 1]
        // ];
        $sCondition = " AND report_type_id = 3 AND report_month = $mth AND report_year = $yr  AND is_approved = 1";
        // $sSel = "
        // SUM(IF(promap, promap, 0)) AS promap,
        // SUM(IF(promap_achive, promap_achive, 0)) AS promap_achive,
        // SUM(IF(pre_case_incomplete, pre_case_incomplete, 0)) AS pre_case_incomplete,
        // SUM(IF(case_submit, case_submit, 0)) AS case_submit,
        // SUM(IF(case_total, case_total, 0)) AS case_total,
        // SUM(IF(case_complete, case_complete, 0)) AS case_complete,
        // SUM(IF(case_incomplete, case_incomplete, 0)) AS case_incomplete,
        // SUM(IF(case_above1year, case_above1year, 0)) AS case_above1year,
        // SUM(IF(case_above2year, case_above2year, 0)) AS case_above2year,
        // SUM(IF(case_above3year, case_above3year, 0)) AS case_above3year";


        // $result = DB::table('district as zilla')
        //     ->join('division', 'zilla.division_id', '=', 'division.id')
        //     ->leftJoin('monthly_reports', function ($join) use ($sCondition) {
        //         $join->on('monthly_reports.zillaid', '=', 'zilla.id')
        //             ->where($sCondition);
        //     })
        //     ->select("division.division_name_bn as divname", "division.id as divid", "zilla.id as zillaid", "zilla.district_name_bn as zillaname", "monthly_reports.comment2", "monthly_reports.comment1_str", "monthly_reports.comment1")
        //     ->selectRaw($sSel);
        // if ($sWhere) {
        //     $result->where($sWhere);
        // }
        // $resultset = $result->groupBy(
        //     'zilla.division_id',
        //     'zilla.id',
        //     'monthly_reports.comment2',
        //     'monthly_reports.comment1_str',
        //     'monthly_reports.comment1'
        // )
        //     ->orderBy('division.id') // Optional: Order by division ID
        //     ->get();

        // $formattedResult = $resultset->map(function ($item) {
        //     return [
        //         0 => $item->divname,                      // Division name
        //         1 => $item->promap,                       // Promap value
        //         2 => $item->pre_case_incomplete,          // Pre-case incomplete
        //         3 => $item->zillaname,                    // Zilla name
        //         4 => $item->comment2,                     // Comment 2
        //         5 => $item->comment1_str,                 // Comment 1 string
        //         6 => $item->comment1,                      // Comment 1
        //         7 => $item->case_submit,                   // Case submit
        //         8 => $item->promap_achive,                // Promap achieved
        //         9 => $item->pre_case_incomplete,          // Pre-case incomplete (redundant, can be adjusted)
        //         10 => $item->case_submit,                  // Case submit
        //         11 => $item->case_total,                   // Case total
        //         12 => $item->case_complete,                // Case complete
        //         13 => $item->case_incomplete,              // Case incomplete
        //         14 => $item->case_above1year,             // Cases above 1 year
        //         15 => $item->case_above2year,             // Cases above 2 years
        //         16 => $item->case_above3year,             // Cases above 3 years
        //         'case_above1year' => $item->case_above1year,
        //         'case_above2year' => $item->case_above2year,
        //         'case_above3year' => $item->case_above3year,
        //         'case_complete' => $item->case_complete,
        //         'case_incomplete' => $item->case_incomplete,
        //         'case_submit' => $item->case_submit,
        //         'case_total' => $item->case_total,
        //         'comment1' => $item->comment1,
        //         'comment1_str' => $item->comment1_str,
        //         'comment2' => $item->comment2,
        //         'divid' => $item->divid,
        //         'divname' => $item->divname,
        //         'pre_case_incomplete' => $item->pre_case_incomplete,
        //         'promap' => $item->promap,
        //         'promap_achive' => $item->promap_achive,
        //         'zillaid' => $item->zillaid,
        //         'zillaname' => $item->zillaname
        //     ];
        // });

        $sSel = "
        SUM(if(promap,promap,0)) as promap,
        SUM(if(promap_achive ,promap_achive ,0)) as promap_achive,
        SUM(if(pre_case_incomplete,pre_case_incomplete ,0)) as pre_case_incomplete,
        SUM(if(case_submit,case_submit ,0)) as case_submit,
        SUM(if(case_total,case_total ,0)) as case_total,
        SUM(if(case_complete , case_complete ,0)) as case_complete,
        SUM(if(case_incomplete, case_incomplete ,0)) as case_incomplete,
        SUM(if(case_above1year, case_above1year ,0)) as case_above1year,
        SUM(if(case_above2year ,case_above2year ,0))as case_above2year,
        SUM(if(case_above3year, case_above3year ,0)) as case_above3year";

        $result = DB::select(
            DB::raw(" 
                SELECT 
                    division.division_name_bn as divname, 
                    division.id as divid, 
                    zilla.id as zillaid, 
                    zilla.district_name_bn as zillaname, 
                    monthly_reports.comment2, 
                    monthly_reports.comment1_str, 
                    monthly_reports.comment1, 
                    $sSel 
                FROM district as zilla
                INNER JOIN division ON zilla.division_id = division.id
                LEFT OUTER JOIN monthly_reports ON monthly_reports.zillaid = zilla.id
                $sCondition
                $sWhere
                GROUP BY zilla.division_id, zilla.id 
                WITH ROLLUP
            ")
        );

        $data = [
            "result" =>  $result,
            "name" => $reportName,
            "profileID" => 9,
            "zilla_name" => $zilla_name,
            "month_year" => $month_year
        ];
        return response()->json($data, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }
    public function printemcasereport(Request $request)
    {
        $requestData = $request->all();
        $request = $requestData['body_data'];

        $resultset = array();
        $reportName = "No data Found";
        $result = array();
        $zilla_name = "";
        $start_date = "";
        $month_year = "";
        $divname_name = "";
        // $reportID = $request->reportID;
        $office_id = $request['office_id'];
        // $user = Auth::user();
        $roleID = $request['roleID'];

        $divid =  $request['divid'];
        $zillaId =  $request['zillaId'];
        $zilla_name = $request['zilla_name'];
        $divname_name =  $request['divname_name'];

        $report_date = $request['report_date'];
        $report_date_array = explode("-", $report_date);
        $mth = $report_date_array[0];
        $yr = $report_date_array[1];

        $month_year = $this->get_bangla_monthbymumber($mth, $yr);
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
        if ($roleID == 37 || $roleID == 38) {
            if ($sWhere == "") {
                // $sWhere = "WHERE   zilla.divid = $divid  AND zilla.zillaid =  $zillaId ";
                $sWhere = [
                    ['zilla.division_id', '=', $divid],
                    ['zilla.id', '=', $zillaId]
                ];
                $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>জেলা প্রশাসকের কার্যালয়, $zilla_name</br>এক্সিকিউটিভ ম্যাজিস্ট্রেটের আদালতে ফৌজদারী কার্যবিধির আওতাধীন মামলার বিবরণ";
            }
        }

        if ($roleID == 34) { // Divisional Commissioner
            if ($sWhere == "") {

                // $sWhere = [
                //     ['zilla.division_id', '=', $divid],

                // ];
                $sWhere = "WHERE   zilla.division_id = $divid ";
                $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>বিভাগীয় কমিশনারের কার্যালয় , $divname_name</br>এক্সিকিউটিভ ম্যাজিস্ট্রেটের আদালতে ফৌজদারী কার্যবিধির আওতাধীন মামলার বিবরণ";
            }
        }
        if ($roleID == 2 || $roleID == 8 || $roleID == 25) { //JS
            if ($sWhere == "") {
                $sWhere = "";
                $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>মন্ত্রিপরিষদ  বিভাগ</br>জেলা ম্যাজিস্ট্রেসি পরিবীক্ষণ অধিশাখা</br>এক্সিকিউটিভ ম্যাজিস্ট্রেটের আদালতে ফৌজদারী কার্যবিধির আওতাধীন মামলার বিবরণ";
            }
        }
        // $sCondition = "AND report_type_id = 4 AND report_month = $mth AND report_year = $yr AND is_approved = 1";
        $sCondition = "AND report_type_id = 4 AND report_month = $mth AND report_year = $yr AND is_approved = 1";
        // $sCondition = [
        //     ['report_type_id', '=', 4],
        //     ['report_month', '=', $mth],
        //     ['report_year', '=', $yr],
        //     ['is_approved', '=', 1]
        // ];
        // $sSel = "
        // SUM(if(promap ,promap,0)) as promap,
        // SUM(if(promap_achive ,promap_achive , 0)) as promap_achive,
        // SUM(if(pre_case_incomplete ,pre_case_incomplete,0)) as pre_case_incomplete,
        // SUM(if(case_submit ,case_submit,0)) as case_submit,
        // SUM(if(case_total ,case_total, 0)) as case_total,
        // SUM(if(case_complete , case_complete , 0)) as case_complete,
        // SUM(if(case_incomplete , case_incomplete ,0 )) as case_incomplete,
        // SUM(if(case_above1year , case_above1year , 0)) as case_above1year,
        // SUM(if(case_above2year ,case_above2year , 0)) as case_above2year,
        // SUM(if(case_above3year ,case_above3year,0)) as case_above3year";

        // $result = DB::table('district as zilla')
        //     ->join('division', 'zilla.division_id', '=', 'division.id')
        //     ->leftJoin('monthly_reports', 'monthly_reports.zillaid', '=', 'zilla.id')
        //     ->select("division.division_name_bn as divname", "division.id as divid", "zilla.id as zillaid", "zilla.district_name_bn as zillaname", "monthly_reports.comment2", "monthly_reports.comment1_str", "monthly_reports.comment1")
        //     ->selectRaw($sSel);
        // if ($sWhere) {
        //     $result->where($sWhere);
        // }
        // $resultset = $result->where($sCondition);
        // // ->groupBy('zilla.division_id', 'zilla.id')
        // // if($sWhere){
        // //     $result->where($sWhere);
        // //     }
        // $resultset = $result->groupBy(
        //     'zilla.division_id',
        //     'zilla.id',
        //     'monthly_reports.comment2',
        //     'monthly_reports.comment1_str',
        //     'monthly_reports.comment1'
        // )
        //     ->orderBy('division.id') // Optional: Order by division ID
        //     ->get();
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
    $result = DB::select(
        DB::raw(" 
            SELECT 
                division.division_name_bn as divname, 
                division.id as divid, 
                zilla.id as zillaid, 
                zilla.district_name_bn as zillaname, 
                monthly_reports.comment2, 
                monthly_reports.comment1_str, 
                monthly_reports.comment1, 
                $sSel 
            FROM district as zilla
            INNER JOIN division ON zilla.division_id = division.id
            LEFT OUTER JOIN monthly_reports ON monthly_reports.zillaid = zilla.id
            $sCondition
            $sWhere
            GROUP BY zilla.division_id, zilla.id 
            WITH ROLLUP
        ")
    );
        // $result = $resultset->map(function ($item) {
        //     return [
        //         0 => $item->divname,                // Division name (e.g. "বরিশাল")
        //         1 => $item->divid,                  // Division ID (e.g. "10")
        //         2 => $item->zillaid,                // Zilla ID (e.g. "4")
        //         3 => $item->zillaname,              // Zilla name (e.g. "বরগুনা")
        //         4 => $item->comment2,               // Comment 2 (empty)
        //         5 => $item->comment1_str,           // Comment 1 string (e.g. " প্রমাপ অর্জিত হয়েছে")
        //         6 => $item->comment1,               // Comment 1
        //         7 => $item->promap,                 // Promap (e.g. "10")
        //         8 => $item->promap_achive,          // Promap achieved (e.g. "18")
        //         9 => $item->pre_case_incomplete,    // Pre-case incomplete (e.g. "666")
        //         10 => $item->case_submit,           // Case submit (e.g. "153")
        //         11 => $item->case_total,            // Case total (e.g. "819")
        //         12 => $item->case_complete,         // Case complete (e.g. "119")
        //         13 => $item->case_incomplete,       // Case incomplete (e.g. "700")
        //         14 => $item->case_above1year,       // Cases above 1 year (e.g. "215")
        //         15 => $item->case_above2year,       // Cases above 2 years (e.g. "10")
        //         16 => $item->case_above3year,       // Cases above 3 years (e.g. "3"),
        //         'divname' => $item->divname,               // Division name (e.g. "বরিশাল")
        //         'divid' => $item->divid,                   // Division ID (e.g. "10")
        //         'zillaid' => $item->zillaid,               // Zilla ID (e.g. "4")
        //         'zillaname' => $item->zillaname,           // Zilla name (e.g. "বরগুনা")
        //         'comment1' => $item->comment1,             // Comment 1
        //         'comment1_str' => $item->comment1_str,     // Comment 1 string
        //         'comment2' => $item->comment2,             // Comment 2
        //         'promap' => $item->promap,                 // Promap
        //         'promap_achive' => $item->promap_achive,   // Promap achieved
        //         'pre_case_incomplete' => $item->pre_case_incomplete, // Pre-case incomplete
        //         'case_submit' => $item->case_submit,       // Case submit
        //         'case_total' => $item->case_total,         // Case total
        //         'case_complete' => $item->case_complete,   // Case complete
        //         'case_incomplete' => $item->case_incomplete, // Case incomplete
        //         'case_above1year' => $item->case_above1year, // Cases above 1 year
        //         'case_above2year' => $item->case_above2year, // Cases above 2 years
        //         'case_above3year' => $item->case_above3year  // Cases above 3 years
        //     ];
        // });

        $responseData = [
            "result" => $result,      // Your processed resultset
            "name" => $reportName,       // The report name
            "profileID" => 9, // $roleID,  // Fetching the user's profile ID
            "zilla_name" => $zilla_name, // Zilla name
            "month_year" => $month_year  // Month and Year
        ];
        // Returning the JSON response with UTF-8 encoding and setting the content type
        return response()->json($responseData, 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
    }
    public function printcourtvisitreport(Request $request)
    {
        $requestData = $request->all();
        $request = $requestData['body_data'];

        $resultset = array();
        $reportName = "No data Found";
        $result = array();
        $zilla_name = "";
        $start_date = "";
        $month_year = "";
        $divname_name = "";

        $reportID = $request['reportID'];
        $office_id = $request['office_id'];
        // $user = Auth::user();
        $roleID = $request['roleID'];



        $divid =  $request['divid'];
        $zillaId =  $request['zillaId'];
        $zilla_name = $request['zilla_name'];
        $divname_name =  $request['divname_name'];

        $report_date = $request['report_date'];

        $report_date_array = explode("-", $report_date);
        $mth = $report_date_array[0];
        $yr = $report_date_array[1];
        $month_year = $this->get_bangla_monthbymumber($mth, $yr);
        $lastmonth = "01";
        $year = "";

        if ($mth == '01') { //
            $lastmonth = $mth - 1;
            $year = $yr - 1;
        } else {
            $lastmonth = $mth - 1;
        }

        $sWhere = "";
        $sCondition = "";
        if ($roleID == 37 || $roleID == 38) { // ADM DM
            if ($sWhere == "") {
                // $sWhere = "WHERE   zilla.divid = $divid  AND zilla.zillaid =  $zillaId ";
                $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>জেলা প্রশাসকের কার্যালয়, $zilla_name</br> জেলা ম্যাজিস্ট্রেটগণ কর্তৃক এক্সিকিউটিভ ম্যাজিস্ট্রেটের আদালত পরিদর্শন ";

                $sWhere = [
                    ['zilla.division_id', '=', $divid],
                    ['zilla.id', '=', $zillaId]
                ];
            }
        }
        if ($roleID == 34) { // Divisional Commissioner
            if ($sWhere == "") {

                // $sWhere = [
                //     ['zilla.division_id', '=', $divid],

                // ];
                $sWhere = "WHERE   zilla.division_id = $divid ";
                $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>বিভাগীয় কমিশনারের কার্যালয় , $divname_name</br>এক্সিকিউটিভ ম্যাজিস্ট্রেটের আদালতে ফৌজদারী কার্যবিধির আওতাধীন মামলার বিবরণ";
            }
        }
        if ($roleID == 2 || $roleID == 8 || $roleID == 25) { //JS
            if ($sWhere == "") {
                $sWhere = "";
                $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>মন্ত্রিপরিষদ  বিভাগ</br>জেলা ম্যাজিস্ট্রেসি পরিবীক্ষণ অধিশাখা</br>জেলা ম্যাজিস্ট্রেটগণ কর্তৃক এক্সিকিউটিভ ম্যাজিস্ট্রেটের আদালত পরিদর্শন ";
            }
        }

        // $sCondition = [
        //     ['report_type_id', '=', 5],
        //     ['report_month', '=', $mth],
        //     ['report_year', '=', $yr],
        //     ['is_approved', '=', 1]
        // ];
        $sCondition = "AND report_type_id = 5 AND report_year= $yr AND report_month = $mth AND is_approved = 1";
        $sSel = " 1 as visit_promap,
                        if(visit_count ,visit_count ,0) as visit_count ";


        // $result = DB::table('district as zilla')
        //     ->join('division', 'zilla.division_id', '=', 'division.id')
        //     ->leftJoin('monthly_reports', 'monthly_reports.zillaid', '=', 'zilla.id')
        //     ->select(
        //         'division.division_name_bn as divname',
        //         'division.id as divid',
        //         'zilla.id as zillaid',
        //         'zilla.district_name_bn as zillaname',
        //         'monthly_reports.comment2',
        //         'monthly_reports.comment1_str',
        //         'monthly_reports.comment1'
        //     )
        //     ->selectRaw($sSel) // Add the raw SQL for calculated fields
        //     ->where($sCondition); // Apply static conditions (report type, month, year, approval)
        // if ($sWhere) {
        //     $result->where($sWhere); // Apply dynamic conditions (if any)
        // }

        // $resultset = $result->groupBy(
        //     'zilla.division_id',
        //     'zilla.id',
        //     'monthly_reports.comment2',
        //     'monthly_reports.comment1_str',
        //     'monthly_reports.comment1',
        //     'monthly_reports.visit_count'
        // )
        //     ->orderBy('division.id') // Optional: Order by division ID
        //     ->get();


        // $result = $resultset->map(function ($item) {
        //     return [
        //         "0" => $item->divname,  // e.g., "বরিশাল"
        //         "1" => $item->divid,    // e.g., "10"
        //         "2" => $item->zillaid,  // e.g., "4"
        //         "3" => $item->zillaname, // e.g., "বরগুনা"
        //         "4" => $item->comment1, // e.g., null
        //         "5" => $item->comment1_str, // e.g., null
        //         "6" => $item->comment2, // e.g., null
        //         "7" => $item->visit_promap, // e.g., "1"
        //         "8" => $item->visit_count, // e.g., "0"
        //         "comment1" => $item->comment1,
        //         "comment1_str" => $item->comment1_str,
        //         "comment2" => $item->comment2,
        //         "divid" => $item->divid,
        //         "divname" => $item->divname,
        //         "visit_count" => $item->visit_count,
        //         "visit_promap" => $item->visit_promap,
        //         "zillaid" => $item->zillaid,
        //         "zillaname" => $item->zillaname,
        //     ];
        // });


        $result = DB::select(
            DB::raw(" 
                SELECT 
                    division.division_name_bn as divname, 
                    division.id as divid, 
                    zilla.id as zillaid, 
                    zilla.district_name_bn as zillaname, 
                    monthly_reports.comment2, 
                    monthly_reports.comment1_str, 
                    monthly_reports.comment1, 
                    $sSel 
                FROM district as zilla
                INNER JOIN division ON zilla.division_id = division.id
                LEFT OUTER JOIN monthly_reports ON monthly_reports.zillaid = zilla.id
                $sCondition
                $sWhere
                GROUP BY zilla.division_id, zilla.id 
                WITH ROLLUP
            ")
        );


        $data =  [
            "result" => $result,
            "name" => $reportName,
            "profileID" => "", // Assuming auth() is used in Laravel
            "zilla_name" => $zilla_name,
            "month_year" => $month_year
        ];
        return response()->json($data);
    }
    public function printcaserecordreport(Request $request)
    {
        $requestData = $request->all();
        $request = $requestData['body_data'];

        $childs = array();
        $reportName = "No data Found";
        $result = array();
        $zilla_name = "";
        $start_date = "";
        $month_year = "";
        $divname_name = "";

        $roleID = $request['roleID'];



        $divid =  $request['divid'];
        $zillaId =  $request['zillaId'];
        $zilla_name = $request['zilla_name'];
        $divname_name =  $request['divname_name'];

        $report_date = $request['report_date'];

        $report_date_array = explode("-", $report_date);
        $mth = $report_date_array[0];
        $yr = $report_date_array[1];
        $month_year = $this->get_bangla_monthbymumber($mth, $yr);
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
        if ($roleID == 37 || $roleID == 38) { // ADM DM
            if ($sWhere == "") {
                // $sWhere = "WHERE   zilla.divid = $divid  AND zilla.zillaid =  $zillaId ";
                $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>জেলা প্রশাসকের কার্যালয়, $zilla_name</br>জেলা ম্যাজিস্ট্রেটগণ কর্তৃক মোবাইল কোর্ট কেস রেকর্ড পর্যালোচনা সংক্রন্ত প্রতিবেদন";

                $sWhere = [
                    ['zilla.division_id', '=', $divid],
                    ['zilla.id', '=', $zillaId]
                ];
            }
        }
        if ($roleID == 34) { // Divisional Commissioner
            if ($sWhere == "") {

                // $sWhere = [
                //     ['zilla.division_id', '=', $divid],

                // ];
                $sWhere = "WHERE   zilla.division_id = $divid ";
                $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>বিভাগীয় কমিশনারের কার্যালয় , $divname_name</br>জেলা ম্যাজিস্ট্রেটগণ কর্তৃক মোবাইল কোর্ট কেস রেকর্ড পর্যালোচনা সংক্রন্ত প্রতিবেদন";
            }
        }
        if ($roleID == 2 || $roleID == 8 || $roleID == 25) { //JS
            if ($sWhere == "") {
                $sWhere = "";
                $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>মন্ত্রিপরিষদ  বিভাগ</br>জেলা ম্যাজিস্ট্রেসি পরিবীক্ষণ অধিশাখা</br>জেলা ম্যাজিস্ট্রেটগণ কর্তৃক মোবাইল কোর্ট কেস রেকর্ড পর্যালোচনা সংক্রন্ত প্রতিবেদন";
            }
        }

        // $sCondition = "AND report_type_id = 6 AND report_year = $yr AND report_month = $mth AND is_approved = 1";
        // $sCondition = [
        //     ['monthly_reports.report_type_id', '=', 6],
        //     ['monthly_reports.report_year', '=',  $yr],
        //     ['monthly_reports.report_month', '=', $mth],
        //     ['monthly_reports.is_approved', '=', 1]
        // ];
        $sCondition = "AND report_type_id = 6 AND report_year = $yr AND report_month = $mth AND is_approved = 1";
        $sSel = "
                1 as caserecord_promap,
                SUM(if(caserecord_count ,caserecord_count,0)) as caserecord_count";
        // $sSel = "
        //         1 as caserecord_promap,
        //         SUM(if(caserecord_count ,caserecord_count,0)) as caserecord_count";

        // $result = DB::table('district as zilla')
        //     ->join('division', 'zilla.division_id', '=', 'division.id')
        //     ->leftJoin('monthly_reports', 'monthly_reports.zillaid', '=', 'zilla.id')
        //     ->select(
        //         'division.division_name_bn as divname',
        //         'division.id as divid',
        //         'zilla.id as zillaid',
        //         'zilla.district_name_bn as zillaname',
        //         'monthly_reports.comment2',
        //         'monthly_reports.comment1_str',
        //         'monthly_reports.comment1'
        //     )
        //     ->selectRaw($sSel) // Add the raw SQL for calculated fields
        //     ->where($sCondition); // Apply static conditions (report type, month, year, approval)
        // if ($sWhere) {
        //     $result->where($sWhere); // Apply dynamic conditions (if any)
        // }
        // $resultset = $result->groupBy(
        //     'zilla.division_id',
        //     'zilla.id',
        //     'monthly_reports.comment2',
        //     'monthly_reports.comment1_str',
        //     'monthly_reports.comment1',
        // ) // Group results by division and zilla
        //     ->orderBy('division.id') // Optional: Order by division ID
        //     ->get();

        // $formattedResult = $resultset->map(function ($item) {
        //     return [
        //         "0" => $item->divname,           // e.g., "বরিশাল"
        //         "1" => $item->divid,             // e.g., "10"
        //         "2" => $item->zillaid,           // e.g., "4"
        //         "3" => $item->zillaname,         // e.g., "বরগুনা"
        //         "4" => $item->comment1,          // e.g., null
        //         "5" => $item->comment1_str,      // e.g., null
        //         "6" => $item->comment2,          // e.g., null
        //         "7" => $item->caserecord_promap, // e.g., "1"
        //         "8" => $item->caserecord_count,  // e.g., "0"
        //         "comment1" => $item->comment1,
        //         "comment1_str" => $item->comment1_str,
        //         "comment2" => $item->comment2,
        //         "divid" => $item->divid,
        //         "divname" => $item->divname,
        //         "caserecord_count" => $item->caserecord_count,
        //         "caserecord_promap" => $item->caserecord_promap,
        //         "zillaid" => $item->zillaid,
        //         "zillaname" => $item->zillaname,
        //     ];
        // });
        $result = DB::select(
            DB::raw(" 
                SELECT 
                    division.division_name_bn as divname, 
                    division.id as divid, 
                    zilla.id as zillaid, 
                    zilla.district_name_bn as zillaname, 
                    monthly_reports.comment2, 
                    monthly_reports.comment1_str, 
                    monthly_reports.comment1, 
                    $sSel 
                FROM district as zilla
                INNER JOIN division ON zilla.division_id = division.id
                LEFT OUTER JOIN monthly_reports ON monthly_reports.zillaid = zilla.id
                $sCondition
                $sWhere
                GROUP BY zilla.division_id, zilla.id 
                WITH ROLLUP
            ")
        );
        // Return the formatted result
        $data = [
            "result" => $result,
            "name" => $reportName,
            "profileID" => 9,
            "zilla_name" => $zilla_name,
            "month_year" => $month_year
        ];
        return response()->json($data);
    }
    public function printmobilecourtstatisticreport(Request $request)
    {
        $requestData = $request->all();
        $request = $requestData['body_data'];

        $resultset = array();
        $reportName = "No data Found";
        $result = array();
        $zilla_name = "";
        $start_date = "";
        $month_year = "";
        $roleID = $request['roleID'];
        $divid =  $request['divid'];
        $zillaId =  $request['zillaId'];
        $zilla_name = $request['zilla_name'];
        $divname_name =  $request['divname_name'];

        $report_date = $request['report_date'];

        $report_date_array = explode("-", $report_date);
        $mth = $report_date_array[0];
        $yr = $report_date_array[1];

        $month_year = $this->get_bangla_monthbymumber($mth, $yr);
        $sWhere = "";
        if ($roleID == 37 || $roleID == 38) { // ADM DM
            $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>জেলা প্রশাসকের কার্যালয়্যালয়,$zilla_name  </br>ই-মোবাইল কোর্ট সিস্টেমে নিষ্পত্তিকৃত মামলার প্রতিবেদন ";
            if ($sWhere == "") {
                $sWhere = [
                    'zilla.division_id' => $divid,
                    'zilla.id' => $zillaId
                ];
            }
        }
        if ($roleID == 34) { // Divisional Commissioner
            if ($sWhere == "") {

                // $sWhere = [
                //     ['zilla.division_id', '=', $divid],

                // ];
                $sWhere = "WHERE   zilla.division_id = $divid ";
                $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার </br>বিভাগীয় কমিশনারের কার্যালয়,$divname_name </br>ই-মোবাইল কোর্ট সিস্টেমে নিষ্পত্তিকৃত মামলার প্রতিবেদন ";
            }
        }
        if ($roleID == 2 || $roleID == 8 || $roleID == 25) { //JS
            if ($sWhere == "") {
                // $sWhere = "";
                $sWhere = "WHERE  1=1";
                $reportName = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার </br>মন্ত্রিপরিষদ  বিভাগ</br>জেলা ম্যাজিস্ট্রেসি পরিবীক্ষণ অধিশাখা</br>ই-মোবাইল কোর্ট সিস্টেমে নিষ্পত্তিকৃত মামলার প্রতিবেদন ";
            }
        }
        // $sCondition = [
        //     ['report_type_id', '=', 1],
        //     ['report_month', '=', $mth],
        //     ['report_year', '=', $yr],
        //     ['is_approved', '=', 1]
        // ];

        $sCondition = " AND report_type_id = 1  AND report_year = $yr AND report_month = $mth AND is_approved = 1";
        // return $result;
        // $result = DB::table('district as zilla')
        //     ->join('division', 'zilla.division_id', '=', 'division.id')
        //     ->leftJoin('monthly_reports', 'monthly_reports.zillaid', '=', 'zilla.id')
        //     ->select(
        //         'division.division_name_bn as divname',
        //         'division.id as divid',
        //         'zilla.id as zillaid',
        //         'zilla.district_name_bn as zillaname',
        //         'monthly_reports.comment2',
        //         'monthly_reports.comment1_str',
        //         'monthly_reports.comment1',
        //         DB::raw('SUM(IF(court_total, court_total, 0)) as court_total'),
        //         DB::raw('SUM(IF(case_total, case_total, 0)) as case_total'),
        //         DB::raw('SUM(IF(case_manual, case_manual, 0)) as case_manual'),
        //         DB::raw('SUM(IF(case_system, case_system, 0)) as case_system'),
        //         DB::raw('SUM(IF(case_complete, case_complete, 0)) as case_complete')
        //     )
        //     ->where('monthly_reports.report_type_id', 1)
        //     ->where('monthly_reports.report_year', $yr)
        //     ->where('monthly_reports.report_month', $mth)
        //     ->where('monthly_reports.is_approved', 1);
        // if ($sWhere) {
        //     $result->where($sWhere); // Apply dynamic conditions
        // }
        // // Grouping and Rollup

        // $results = $result->groupBy(
        //     'zilla.division_id',
        //     'zilla.id',
        //     'monthly_reports.comment2',
        //     'monthly_reports.comment1_str',
        //     'monthly_reports.comment1',
        // ) // Group results by division and zilla
        //     ->orderBy('division.id') // Optional: Order by division ID
        //     // ->withRollup()
        //     ->get();
        // $result = $query->get();
        // dd( $query);

        // $data = $results->map(function ($item) {
        //     return [
        //         0 => $item->divname,
        //         1 => $item->divid,
        //         2 => $item->zillaid,
        //         3 => $item->zillaname,
        //         4 => null,
        //         5 => null,
        //         6 => null,
        //         7 => $item->court_total,
        //         8 => $item->case_manual,
        //         9 => $item->case_system,
        //         10 => $item->case_total,
        //         11 => $item->case_complete,
        //         "case_complete" => $item->case_complete,
        //         "case_manual" => $item->case_manual,
        //         "case_system" => $item->case_system,
        //         "case_total" => $item->case_total,
        //         "comment1" => null,
        //         "comment1_str" => null,
        //         "comment2" => null,
        //         "court_total" => $item->court_total,
        //         "divid" => $item->divid,
        //         "divname" => $item->divname,
        //         "zillaid" => $item->zillaid,
        //         "zillaname" => $item->zillaname,
        //     ];
        // });
        $sSel = "
        SUM(if(court_total ,court_total, 0)) as court_total,
        SUM(if(case_total ,case_total, 0)) as case_total,
        SUM(if(case_manual ,case_manual, 0)) as case_manual,
        SUM(if(case_system ,case_system, 0)) as case_system,
        SUM(if(case_complete ,case_complete, 0)) as case_complete";
        $result = DB::select(
            DB::raw(" 
                SELECT 
                    division.division_name_bn as divname, 
                    division.id as divid, 
                    zilla.id as zillaid, 
                    zilla.district_name_bn as zillaname, 
                    monthly_reports.comment2, 
                    monthly_reports.comment1_str, 
                    monthly_reports.comment1, 
                    $sSel 
                FROM district as zilla
                INNER JOIN division ON zilla.division_id = division.id
                LEFT OUTER JOIN monthly_reports ON monthly_reports.zillaid = zilla.id
                $sCondition
                $sWhere
                GROUP BY zilla.division_id, zilla.id 
                WITH ROLLUP
            ")
        );

        return response()->json([
            "result" => $result,
            "name" => $reportName,
            "profileID" => 9, // Adjust according to your authentication setup
            "zilla_name" => $zilla_name,
            "month_year" => $month_year
        ]);
    }

    public function ajaxDataCourt(Request $request)
    {
      
        $childs = array();
      

        $requestData = $request->all();
        $request = $requestData['body_data'];
        $roleID = $request['roleID'];
        $divid =  $request['divid'];
        $zillaId =  $request['zillaId'];
        $div_name =  $request['divname_name'];
        $zilla_name = $request['zilla_name'];
        $office_id = $request['office_id'];


        if ($roleID == 37 || $roleID == 38) { // ADM DM
            $childs = $this->ajaxDataCourtForDivision($request);
        }

       
        if ($roleID == 34 ) { // Divisional Commissioner
            $childs = $this->ajaxDataCourtForDivision($request);
        }
     
        if ($roleID == 2 || $roleID == 8 || $roleID == 25) { //JS
            $childs = $this->ajaxDataCourtForCountry($request);
        }
     
        return response()->json([$childs]);
    }
    public function ajaxDataCase(Request $request)
    {
        $childs = array();

        $requestData = $request->all();
        $request = $requestData['body_data'];
        $roleID = $request['roleID'];
        $divid =  $request['divid'];
        $zillaId =  $request['zillaId'];
        $div_name =  $request['divname_name'];
        $zilla_name = $request['zilla_name'];
        $office_id = $request['office_id'];

        if ($roleID == 37 || $roleID == 38) { // ADM DM
            $childs = $this->ajaxDataCaseForDivision($request);
        }

        if ($roleID == 34) { //JS
            $childs = $this->ajaxDataCaseForDivision($request);
        }

        if ($roleID == 2 || $roleID == 8 || $roleID == 25) { // Divisional Commissioner
            $childs = $this->ajaxDataCaseForCountry($request);
        }
        
 
        return response()->json([$childs]);
    }
    public function ajaxDataFine(Request $request)
    {
 
        $childs = array();
        $requestData = $request->all();
        $request = $requestData['body_data'];
        $roleID = $request['roleID'];
        $divid =  $request['divid'];
        $zillaId =  $request['zillaId'];
        $div_name =  $request['divname_name'];
        $zilla_name = $request['zilla_name'];
        $office_id = $request['office_id'];

        if ($roleID == 37 || $roleID == 38) { // ADM DM
            $childs = $this->ajaxDataFineForDivision($request);
        }
        if ($roleID == 34) { //JS
           
            $childs = $this->ajaxDataFineForDivision($request);
        }
        if ($roleID == 2 || $roleID == 8 || $roleID == 25) { // Divisional Commissioner
            $childs = $this->ajaxDataFineForCountry($request);
        }
        


        return response()->json([$childs]);
    }
    public function ajaxDataAppeal(Request $request)
    {

        $childs = array();
        $requestData = $request->all();
        $request = $requestData['body_data'];
        $roleID = $request['roleID'];
        $divid =  $request['divid'];
        $zillaId =  $request['zillaId'];
        $div_name =  $request['divname_name'];
        $zilla_name = $request['zilla_name'];
        $office_id = $request['office_id'];

        if ($roleID == 37 || $roleID == 38) { // ADM DM
            $childs = $this->ajaxDataAppealForDivision($request);
        }
        if ($roleID == 34) { // Divisional Commissioner
            $childs = $this->ajaxDataAppealForDivision($request);
        }
        if ($roleID == 2 || $roleID == 8 || $roleID == 25) { //JS
            $childs = $this->ajaxDataAppealForCountry($request);
        }

        return response()->json([$childs]);
    }
    public function ajaxDataEm(Request $request)
    {
        $childs = array();
        $requestData = $request->all();
        $request = $requestData['body_data'];
        $roleID = $request['roleID'];
        $divid =  $request['divid'];
        $zillaId =  $request['zillaId'];
        $div_name =  $request['divname_name'];
        $zilla_name = $request['zilla_name'];
        $office_id = $request['office_id'];

        if ($roleID == 37 || $roleID == 38) { // ADM DM
            $childs = $this->ajaxDataEmForDivision($request);
        }
        if ($roleID == 34) { // Divisional Commissioner
        
            $childs = $this->ajaxDataEmForDivision($request);
        }
        if ($roleID == 2 || $roleID == 8 || $roleID == 25) {  //JS
            $childs = $this->ajaxDataEmForCountry($request);
        }

        return response()->json([$childs]);
    }
    public function ajaxDataAdm(Request $request)
    {

        $childs = array();
        $requestData = $request->all();
        $request = $requestData['body_data'];
        $roleID = $request['roleID'];
        $divid =  $request['divid'];
        $zillaId =  $request['zillaId'];
        $div_name =  $request['divname_name'];
        $zilla_name = $request['zilla_name'];
        $office_id = $request['office_id'];

        if ($roleID == 37 || $roleID == 38) { // ADM DM
            $childs = $this->ajaxDataAdmForDivision($request);
        }
        if ($roleID == 2 || $roleID == 8 || $roleID == 25) {  //JS
            $childs = $this->ajaxDataAdmForCountry($request);
        }
        if ($roleID == 34) {  // Divisional Commissioner
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
          
       

        $report_date = $request['report_date'];
        $report_date2 = $request['report_date2'];
        $divid = $request['divid'];
        $yData = '';
        if ($report_date != "") {
            $report_date_array = explode("-", $report_date);
            $mth = $report_date_array[0];
            $yr = $report_date_array[1];

            $month =  $this->get_bangla_monthonly($mth);


            $preMonth = $mth - 1;;
            $currentmonth = $mth;
            $nextMonth = $mth + 1;
            if ($report_date2 != "") {
                $report_date2 = trim($report_date2);  // Ensure no extra spaces
                $report_date2_array = explode("-", $report_date2);  // Split into components
                $mth2 = $report_date2_array[0];  // Expected to be '08' if correctly formatted
                $yr2 = $report_date2_array[1];
                $nextMonth = trim($mth2);
            }
 
            $yData = ["প্রমাপ", $this->get_bangla_monthonly($currentmonth), $this->get_bangla_monthonly($nextMonth)];
        } else {
            $preMonth = "03";
            $currentmonth = "04";
            $nextMonth = "05";
            $yData = [$this->get_bangla_monthonly($currentmonth), $this->get_bangla_monthonly($nextMonth)];
        }


        $childs = array();
       
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
    public function ajaxDataCourtForCountry($request)
    {
        $preMonth = "";
        $month = "";
        $nextMonth = "";
        $yr = "2015";
        $yData = array();

        $report_date = $request['report_date'];
        $report_date2 = $request['report_date2'];

        if ($report_date != "") {
            $report_date = trim($report_date);  // Ensure no extra spaces
            $report_date_array = explode("-", $report_date);
            $mth = trim($report_date_array[0]);
            $yr = $report_date_array[1];

            $month = $this->get_bangla_monthonly($mth);


            $preMonth = $mth - 1;
            $currentmonth = $mth;
            $nextMonth = $mth + 1;
            if ($report_date2 != "") {
                $mth2 = substr($report_date2, 0, 2);
                $yr2 = substr($report_date2, 3, 4); // 01/02/2015 => 2015 06-2015
                $nextMonth = $mth2;
            }
            $yData = ["প্রমাপ", $this->get_bangla_monthonly($currentmonth), $this->get_bangla_monthonly($nextMonth)];
        } else {
            $preMonth = "03";
            $currentmonth = "04";
            $nextMonth = "05";
            $yData = [$this->get_bangla_monthonly($currentmonth), $this->get_bangla_monthonly((int)$nextMonth), "প্রমাপ"];
        }

        $childs = array();
       
        $info = DB::table('monthly_reports')
        ->selectRaw('divname, 
            SUM(IF(report_month = ?, court_total, 0)) as court_total1, 
            SUM(IF(report_month = ?, court_total, 0)) as court_total2, 
            SUM(IF(report_month = ?, promap, 0)) as promap', 
            [$currentmonth, $nextMonth, $currentmonth])
        ->where('report_type_id', 1)
        ->where('report_year', $yr)
        ->groupBy('divname')
        ->get();

        $month_year = $this->get_bangla_monthbymumber($currentmonth, $yr);

        foreach ($info as $t) {
            $childs[] = array(
                'location' => $t->divname,
                $yData[0] => "" . $t->promap . "",
                $yData[1] => "" . $t->court_total1 . "",
                $yData[2] => "" . $t->court_total2 . "",
                'yData' =>  $yData
            );
        }

        return $childs;
    }
    public function ajaxDataCaseForDivision($request)
    {
        $preMonth = "";
        $month = "";
        $nextMonth = "";
        $yData = array();


       

        $report_date = $request['report_date'];
        $divid = $request['divid'];
        if ($report_date != "") {
            $report_date = trim($report_date);  // Ensure no extra spaces
            $report_date_array = explode("-", $report_date);
            $mth = trim($report_date_array[0]);
            $yr = $report_date_array[1];

            $month =  $this->get_bangla_monthonly($mth);


            $preMonth = $mth - 1;;
            $currentmonth = $mth;
            $nextMonth = $mth + 1;

            $yData = [$this->get_bangla_monthonly($currentmonth), $this->get_bangla_monthonly($nextMonth)];
        } else {
            $preMonth = "03";
            $currentmonth = "04";
            $nextMonth = "05";
            $yData = [$this->get_bangla_monthonly($currentmonth), $this->get_bangla_monthonly($nextMonth)];
        }

        $childs = array();
       
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
            $childs[] = array(
                'location' => $t->zillaname,
                $yData[0] => "" . $t->case_total1 . "",
                $yData[1] => "" . $t->case_total2 . "",
                'yData' =>  $yData
            );
        }

        return $childs;
    }

    public function ajaxDataCaseForCountry($request)
    {
        $preMonth = "";
        $month = "";
        $nextMonth = "";
        $yData = array();

        $report_date = $request['report_date'];
        $report_date2 = $request['report_date2'];
  

        if ($report_date != "") {
            $report_date = trim($report_date);  // Ensure no extra spaces
            $report_date_array = explode("-", $report_date);
            $mth = trim($report_date_array[0]);
            $yr = $report_date_array[1];
            $month = $this->get_bangla_monthonly($mth);


            $preMonth = $mth - 1;;
            $currentmonth = $mth;
            $nextMonth = $mth + 1;
            if ($report_date2 != "") {
                $mth2 = substr($report_date2, 0, 2);
                $yr2 = substr($report_date2, 3, 4); // 01/02/2015 => 2015 06-2015
                $nextMonth = $mth2;
            }


            $yData = [$this->get_bangla_monthonly($currentmonth), $this->get_bangla_monthonly($nextMonth)];
        } else {
            $preMonth = "03";
            $currentmonth = "04";
            $nextMonth = "05";
            $yData = [$this->get_bangla_monthonly($currentmonth), $this->get_bangla_monthonly($nextMonth)];
        }
        $childs = array();
    
        $info = DB::table('monthly_reports as monthlyreport')
        ->select(
            'divname',
            DB::raw("SUM(IF(monthlyreport.report_month = $currentmonth, monthlyreport.case_total, 0)) as case_total1"),
            DB::raw("SUM(IF(monthlyreport.report_month = $nextMonth, monthlyreport.case_total, 0)) as case_total2")
        )
        ->where('monthlyreport.report_type_id', 1)
        ->where('monthlyreport.report_year', $yr)
        ->groupBy('divname')
        ->get();
     
 
        $childs=[];
        foreach ($info as $t) {
            $childs[] = array(
                'location' => $t->divname,
                $yData[0] => "" . $t->case_total1 . "",
                $yData[1] => "" . $t->case_total2 . "",
                'yData' =>  $yData
            );
        }

        return $childs;
    }

    public function ajaxDataFineForDivision($request)
    {
        $preMonth = "";
        $month = "";
        $nextMonth = "";
        $yData = array();

    
        $report_date = $request['report_date'];
        $divid = $request['divid'];


        if ($report_date != "") {
            $report_date = trim($report_date);  // Ensure no extra spaces
            $report_date_array = explode("-", $report_date);
            $mth = trim($report_date_array[0]);
            $yr = $report_date_array[1];

            $month = $this->get_bangla_monthonly($mth);


            $preMonth = $mth - 1;;
            $currentmonth = $mth;
            $nextMonth = $mth + 1;

            $yData = [$this->get_bangla_monthonly($currentmonth), $this->get_bangla_monthonly($nextMonth)];
        } else {
            $preMonth = "03";
            $currentmonth = "04";
            $nextMonth = "05";
            $yData = [$this->get_bangla_monthonly($currentmonth), $this->get_bangla_monthonly($nextMonth)];
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
            $childs[] = array(
                'location' => $t->zillaname,
                $yData[0] => "" . $t->fine_total1 . "",
                $yData[1] => "" . $t->fine_total2 . "",
                'yData' =>  $yData
            );
        }

        return $childs;
    }
    public function ajaxDataFineForCountry($request)
    {
        $preMonth = "";
        $month = "";
        $nextMonth = "";
        $yData = array();

        $report_date = $request['report_date'];
        $report_date2 = $request['report_date2'];
 

        if ($report_date != "") {
            $report_date = trim($report_date);  // Ensure no extra spaces
            $report_date_array = explode("-", $report_date);
            $mth = trim($report_date_array[0]);
            $yr = $report_date_array[1];


            $month = $this->get_bangla_monthonly($mth);


            $preMonth = $mth - 1;;
            $currentmonth = $mth;
            $nextMonth = $mth + 1;
            if ($report_date2 != "") {
                $mth2 = substr($report_date2, 0, 2);
                $yr2 = substr($report_date2, 3, 4); // 01/02/2015 => 2015 06-2015
                $nextMonth =$mth2;
            }

            $yData = [$this->get_bangla_monthonly($currentmonth), $this->get_bangla_monthonly($nextMonth)];
        } else {
            $preMonth = "03";
            $currentmonth = "04";
            $nextMonth = "05";
            $yData = [$this->get_bangla_monthonly($currentmonth), $this->get_bangla_monthonly($nextMonth)];
        }

        $childs = array();
        $info = DB::table('monthly_reports as monthlyreport')
        ->select([
            'divname',
            DB::raw("SUM(IF(monthlyreport.report_month = $currentmonth, monthlyreport.fine_total, 0)) as fine_total1"),
            DB::raw("SUM(IF(monthlyreport.report_month = $nextMonth, monthlyreport.fine_total, 0)) as fine_total2")
        ])
        ->where('monthlyreport.report_type_id', 1)
        ->where('monthlyreport.report_year', $yr)
        ->groupBy('divname')
        ->get();
        foreach ($info as $t) {
            $childs[] = array(
                'location' => $t->divname,
                $yData[0] => "" . $t->fine_total1 . "",
                $yData[1] => "" . $t->fine_total2 . "",
                'yData' =>  $yData
            );
        }

        return $childs;
    }

    public function ajaxDataAppealForDivision($request)
    {
        $preMonth = "";
        $month = "";
        $nextMonth = "";
        $yData = array();
      
        $report_date = $request['report_date'];
        $divid = $request['divid'];


        if ($report_date != "") {
            $report_date = trim($report_date);  // Ensure no extra spaces
            $report_date_array = explode("-", $report_date);
            $mth = trim($report_date_array[0]);
            $yr = $report_date_array[1];

            $preMonth = $mth - 1;;
            $currentmonth = $mth;
            $nextMonth = $mth + 1;
           
        } else {
            $preMonth = "03";
            $currentmonth = "04";
            $nextMonth = "05";
          
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
        $month_year = $this->get_bangla_monthbymumber($currentmonth, $yr);
        $yData = ['জের', 'দায়েরকৃত', 'নিষ্পন্ন', 'অনিষ্পন্ন'];
        foreach ($info as $t) {
            $childs[] = array(
                'location' => $t->zillaname,
                'জের' => "" . $t->pre_case_incomplete . "",
                'দায়েরকৃত' => "" . $t->case_submit . "",
                'নিষ্পন্ন' => "" . $t->case_complete . "",
                'অনিষ্পন্ন' => "" . $t->case_incomplete . "",
                'reportmonth' => "(" . $month_year . ")",
                'yData' => $yData
            );
        }
        return $childs;
    }
    public function ajaxDataAppealForCountry($request)
    {
        $currentmonth = "04";
        $yr = "2015";
        $report_date = $request['report_date'];
        $report_date2 = $request['report_date2'];

        if ($report_date != "") {
            $report_date = trim($report_date);  // Ensure no extra spaces
            $report_date_array = explode("-", $report_date);
            $mth = trim($report_date_array[0]);
            $yr = $report_date_array[1];

            $currentmonth = $mth;

            $nextMonth = (int)$mth + 1;
            if ($report_date2 != "") {
                $mth2 = substr($report_date2, 0, 2);
                $yr2 = substr($report_date2, 3, 4); // 01/02/2015 => 2015 06-2015
                $nextMonth = $mth2;
            }
        } else {
            $currentmonth = "04";
        }

        //        SUM(monthlyreport.pre_case_incomplete) as pre_case_incomplete,
        $childs = array();
        $info = DB::table('monthly_reports')
        ->selectRaw("
            divname,
            SUM(pre_case_incomplete) as pre_case_incomplete,
            SUM(case_submit) as case_submit,
            SUM(case_complete) as case_complete,
            SUM(case_incomplete) as case_incomplete
        ")
        ->where('report_month', $currentmonth)
        ->where('report_type_id', 2)
        ->where('report_year', $yr)
        ->groupBy('divname')
        ->get();

        $month_year = $this->get_bangla_monthbymumber($currentmonth, $yr);
        $yData = ['জের', 'দায়েরকৃত', 'নিষ্পন্ন', 'অনিষ্পন্ন'];

        foreach ($info as $t) {
            $childs[] = array(
                'location' => $t->divname,
                'জের' => "" . $t->pre_case_incomplete . "",
                'দায়েরকৃত' => "" . $t->case_submit . "",
                'নিষ্পন্ন' => "" . $t->case_complete . "",
                'অনিষ্পন্ন' => "" . $t->case_incomplete . "",
                'reportmonth' => "(" . $month_year . ")",
                'yData' =>  $yData
            );
        }

        return $childs;
    }
    public function ajaxDataEmForCountry($request)
    {
        $currentmonth = "04";

   
        $report_date = $request['report_date'];
       
        if ($report_date != "") {
            $report_date = trim($report_date);  // Ensure no extra spaces
            $report_date_array = explode("-", $report_date);
            $mth = trim($report_date_array[0]);
            $yr = $report_date_array[1];
      
            $currentmonth = $mth;
        } else {
            $currentmonth = "04";
        }
      

       $childs = array();
       $info = DB::table('monthly_reports')
        ->select(
            'divname',
            DB::raw('SUM(pre_case_incomplete) as pre_case_incomplete'),
            DB::raw('SUM(case_submit) as case_submit'),
            DB::raw('SUM(case_complete) as case_complete'),
            DB::raw('SUM(case_incomplete) as case_incomplete')
        )
        ->where('report_month', $currentmonth)
        ->where('report_type_id', 4)
        ->where('report_year', $yr)
        ->groupBy('divname')
        ->get();

        $month_year = $this->get_bangla_monthbymumber($currentmonth, $yr);
        $yData = ['জের', 'দায়েরকৃত', 'নিষ্পন্ন', 'অনিষ্পন্ন'];

        foreach ($info as $t) {

            $childs[] = array(
                'location' => $t->divname,
                'জের' => "" . $t->pre_case_incomplete . "",
                'দায়েরকৃত' => "" . $t->case_submit . "",
                'নিষ্পন্ন' => "" . $t->case_complete . "",
                'অনিষ্পন্ন' => "" . $t->case_incomplete . "",
                'reportmonth' => "(" . $month_year . ")",
                'yData' => $yData
            );
        }

        return $childs;
    }
    public function ajaxDataEmForDivision($request)
    {

        $preMonth = "";
        $month = "";
        $nextMonth = "";
        $yData = array();

        $report_date = $request['report_date'];
 
        $divid = $request['divid'];


        if ($report_date != "") {
            $report_date = trim($report_date);  // Ensure no extra spaces
            $report_date_array = explode("-", $report_date);
            $mth = trim($report_date_array[0]);
            $yr = $report_date_array[1];

            // $month = BanglaDate::get_bangla_monthonly($mth);


            $preMonth = $mth - 1;;
            $currentmonth = $mth;
            $nextMonth = $mth + 1;

            // $yData = [BanglaDate::get_bangla_monthonly($currentmonth) ,BanglaDate::get_bangla_monthonly($nextMonth)];

        } else {
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
        $month_year = $this->get_bangla_monthbymumber($currentmonth, $yr);
        $yData = ['জের', 'দায়েরকৃত', 'নিষ্পন্ন', 'অনিষ্পন্ন'];
        foreach ($info as $t) {
            $childs[] = array(
                'location' => $t->zillaname,
                'জের' => "" . $t->pre_case_incomplete . "",
                'দায়েরকৃত' => "" . $t->case_submit . "",
                'নিষ্পন্ন' => "" . $t->case_complete . "",
                'অনিষ্পন্ন' => "" . $t->case_incomplete . "",
                'reportmonth' => "(" . $month_year . ")",
                'yData' => $yData
            );
        }
        return $childs;
    }

    public function ajaxDataAdmForDivision($request)
    {
        $preMonth = "";
        $month = "";
        $nextMonth = "";
        $yData = array();
      
        $report_date = $request['report_date'];
        $divid = $request['divid'];

        if ($report_date != "") {
            $report_date = trim($report_date);  // Ensure no extra spaces
            $report_date_array = explode("-", $report_date);
            $mth = trim($report_date_array[0]);
            $yr = $report_date_array[1];

            $preMonth = $mth - 1;;
            $currentmonth = $mth;
            $nextMonth = $mth + 1;

        } else {
            $preMonth = "03";
            $currentmonth = "04";
            $nextMonth = "05";
            // $yData = [BanglaDate::get_bangla_monthonly($currentmonth) ,BanglaDate::get_bangla_monthonly($nextMonth)];
        }
        $childs = array();
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
        $month_year = $this->get_bangla_monthbymumber($currentmonth, $yr);
        $yData = ['জের', 'দায়েরকৃত', 'নিষ্পন্ন', 'অনিষ্পন্ন'];
        foreach ($info as $t) {
            $childs[] = array(
                'location' => $t->divname,
                'জের' => "" . $t->pre_case_incomplete . "",
                'দায়েরকৃত' => "" . $t->case_submit . "",
                'নিষ্পন্ন' => "" . $t->case_complete . "",
                'অনিষ্পন্ন' => "" . $t->case_incomplete . "",
                'reportmonth' => "(" . $month_year . ")",
                'yData' => $yData
            );
        }

        return $childs;
    }
    public function ajaxDataAdmForCountry($request)
    {
        $currentmonth = "04";

  
        $report_date = $request['report_date'];
      
        if ($report_date != "") {
            $report_date_array = explode("-", $report_date);
            $mth = $report_date_array[0];
            $yr = $report_date_array[1];
        
            $currentmonth = $mth;
        } else {
            $currentmonth = "04";
        }


        $childs = array();
        $info = DB::table('monthly_reports')
                ->selectRaw("
                    divname,
                    SUM(pre_case_incomplete) as pre_case_incomplete,
                    SUM(case_submit) as case_submit,
                    SUM(case_complete) as case_complete,
                    SUM(case_incomplete) as case_incomplete
                ")
                ->where('report_month', $currentmonth)
                ->where('report_type_id', 3)
                ->where('report_year', $yr)
                ->groupBy('divname')
                ->get();
        $month_year = $this->get_bangla_monthbymumber($currentmonth, $yr);
        $yData = ['জের', 'দায়েরকৃত', 'নিষ্পন্ন', 'অনিষ্পন্ন'];

        foreach ($info as $t) {
            $childs[] = array(
                'location' => $t->divname,
                'জের' => "" . $t->pre_case_incomplete . "",
                'দায়েরকৃত' => "" . $t->case_submit . "",
                'নিষ্পন্ন' => "" . $t->case_complete . "",
                'অনিষ্পন্ন' => "" . $t->case_incomplete . "",
                'reportmonth' => "(" . $month_year . ")",
                'yData' => $yData
            );
        }

        return $childs;
    }
    public  function get_bangla_monthbymumber($month, $year)
    {

        if ($year == "") {
            $year = date('Y');
        }
        $mons = array(
            '01' => "জানুয়ারী",
            '02' => "ফেব্রুয়ারী",
            '03' => "মার্চ",
            '04' => "এপ্রিল",
            '05' => "মে",
            '06' => "জুন",
            '07' => "জুলাই",
            '08' => "আগস্ট",
            '09' => "সেপ্টেম্বর",
            '10' => "অক্টোবর",
            '11' => "নভেম্বর",
            '12' => "ডিসেম্বর"
        );

        $month_name = $mons[$month];

        return $month_name . " " . $year;
    }
    public   function get_bangla_monthonly($month)
    {

        $mons = array(
            '01' => "জানুয়ারী",
            '02' => "ফেব্রুয়ারী",
            '03' => "মার্চ",
            '04' => "এপ্রিল",
            '05' => "মে",
            '06' => "জুন",
            '07' => "জুলাই",
            '08' => "আগস্ট",
            '09' => "সেপ্টেম্বর",
            '10' => "অক্টোবর",
            '11' => "নভেম্বর",
            '12' => "ডিসেম্বর"
        );
        $convertedMonth = $mons[$month];
        return $convertedMonth;
    }

}
