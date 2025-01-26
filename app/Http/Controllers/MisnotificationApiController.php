<?php

namespace App\Http\Controllers;

use App\Repositories\MisReportRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MisnotificationApiController extends Controller
{
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
            $lastyear = (int)$yr - 1;
        } else {
            $lastmonth = (int)$mth - 1;
            $lastyear = (int)$yr;
        }
        $sWhere = "";
        $sCondition = "";
        // $sCondition = [
        //     ['monthly_reports.report_type_id', '=', 1],
        //     ['monthly_reports.is_approved', '=', 1]
        // ];
          $sCondition = " AND report_type_id = 1   AND is_approved = 1";
        $sWhere = "WHERE 1=1 ";
        // $selects = [
        //     DB::raw("SUM(IF(report_month = $mth AND report_year = $yr, promap, 0)) as promap"),
        //     DB::raw("SUM(IF(report_month = $mth AND report_year = $yr, upozila, 0)) as upozila"),
        //     DB::raw("SUM(IF(report_month = $lastmonth AND report_year = $lastyear, case_total, 0)) as case_total1"),
        //     DB::raw("SUM(IF(report_month = $mth AND report_year = $yr, case_total, 0)) as case_total2"),
        //     DB::raw("SUM(IF(report_month = $lastmonth AND report_year = $lastyear, court_total, 0)) as court_total1"),
        //     DB::raw("SUM(IF(report_month = $mth AND report_year = $yr, court_total, 0)) as court_total2"),
        //     DB::raw("SUM(IF(report_month = $lastmonth AND report_year = $lastyear, fine_total, 0)) as fine_total1"),
        //     DB::raw("SUM(IF(report_month = $mth AND report_year = $yr, fine_total, 0)) as fine_total2"),
        //     DB::raw("SUM(IF(report_month = $lastmonth AND report_year = $lastyear, lockup_criminal_total, 0)) as lockup_criminal_total1"),
        //     DB::raw("SUM(IF(report_month = $mth AND report_year = $yr, lockup_criminal_total, 0)) as lockup_criminal_total2"),
        //     DB::raw("SUM(IF(report_month = $lastmonth AND report_year = $lastyear, criminal_total, 0)) as criminal_total1"),
        //     DB::raw("SUM(IF(report_month = $mth AND report_year = $yr, criminal_total, 0)) as criminal_total2")
        // ];

        // Execute the query using Laravel's Query Builder
        // $resultset = DB::table('district as zilla')
        //     ->join('division', 'zilla.division_id', '=', 'division.id')
        //     ->leftJoin('monthly_reports', function ($join) use ($sCondition) {
        //         $join->on('monthly_reports.zillaid', '=', 'zilla.id')
        //             ->where($sCondition);
        //     })
        //     ->select(
        //         'division.division_name_bn as divname',
        //         'division.id as divid',
        //         'zilla.id as zillaid',
        //         'zilla.district_name_bn as zillaname',
        //         'monthly_reports.comment2',
        //         'monthly_reports.comment1_str',
        //         'monthly_reports.comment1'
        //     )
        //     ->addSelect($selects)
        //     ->groupBy(
        //         'division.id',
        //         'zilla.id',
        //         'monthly_reports.comment2',
        //         'monthly_reports.comment1_str',
        //         'monthly_reports.comment1',
        //         'division.division_name_bn',
        //         'zilla.district_name_bn'
        //     ) // Add these columns to GROUP BY
        //     ->orderBy('division.id') // Optional: Order by division ID
        //     ->get();

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
    $result = DB::select(DB::raw($sql));
    // dd($result);
        $data = [
            "result" =>  $result,
            "name" => $reportName,
            "profileID" =>'',
            "zilla_name" => $zilla_name,
            "month_year" => $month_year
        ];
        return response()->json($data, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
        // return ['message'=> $request];
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
            $lastyear = (int)$yr - 1;
        } else {
            $lastmonth = (int)$mth - 1;
            $lastyear = (int)$yr;
        }
        $sWhere = "";
        $sCondition = "";


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
            ->leftJoin('monthly_reports', function ($join) use ($sCondition) {
                $join->on('monthly_reports.zillaid', '=', 'zilla.id')
                    ->where($sCondition);
            })
            ->select('division.division_name_bn as divname', 'division.id as divid', 'zilla.id as zillaid', 'zilla.district_name_bn as zillaname', 'monthly_reports.comment2', 'monthly_reports.comment1_str', 'monthly_reports.comment1')
            ->addSelect($selects)
            // ->where($sWhere)
            ->groupBy(
                'zilla.division_id',
                'zilla.id',
                'monthly_reports.comment2',
                'monthly_reports.comment1_str',
                'monthly_reports.comment1',
                'division.division_name_bn',
                'zilla.district_name_bn',
                'division.id'
            )
            ->orderBy('division.id')
            ->get();



        // dd($resultset);
        $formattedResult = $resultset->map(function ($item) {
            return [
                0 => $item->divname, // 0: "বরিশাল"
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
                'divname' => $item->divname, // "বরিশাল"
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
            ->select("division.division_name_bn as divname", "division.id as divid", "zilla.id as zillaid", "zilla.district_name_bn as zillaname", "monthly_reports.comment2", "monthly_reports.comment1_str", "monthly_reports.comment1")
            ->selectRaw($sSel)
            // ->where($sWhere)
            ->groupBy(
                'zilla.division_id',
                'zilla.id',
                'monthly_reports.comment2',
                'monthly_reports.comment1_str',
                'monthly_reports.comment1',
                'division.division_name_bn',
                'zilla.district_name_bn',
                'division.id'
            )
            ->orderBy('division.id') // Optional: Order by division ID
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
        $reportID = $request['reportID'];
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
            ->select("division.division_name_bn as divname", "division.id as divid", "zilla.id as zillaid", "zilla.district_name_bn as zillaname", "monthly_reports.comment2", "monthly_reports.comment1_str", "monthly_reports.comment1")
            ->selectRaw($sSel)
            // ->where($sWhere)
            ->where($sCondition)
            ->groupBy(
                'division.id',
                'zilla.id',
                'monthly_reports.comment2',
                'monthly_reports.comment1_str',
                'monthly_reports.comment1',
                'division.division_name_bn',
                'zilla.district_name_bn',
                'division.id'
            )
            ->orderBy('division.id') // Optional: Order by division ID
            ->get();
        // dd($resultset);
        $result = $resultset->map(function ($item) {
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
        $sCondition = [
            ['report_type_id', '=', 5],
            ['report_month', '=', $mth],
            ['report_year', '=', $yr],
            ['is_approved', '=', 1]
        ];

        $sSel = " 1 as visit_promap,
        if(visit_count ,visit_count ,0) as visit_count ";
        $sSel = "IFNULL(visit_count, 0) as visit_count"; // Correct IF syntax with IFNULL
        $resultset = DB::table('district as zilla')
            ->join('division', 'zilla.division_id', '=', 'division.id')
            ->leftJoin('monthly_reports', 'monthly_reports.zillaid', '=', 'zilla.id')
            ->select("division.division_name_bn as divname", "division.id as divid", "zilla.id as zillaid", "zilla.district_name_bn as zillaname", "monthly_reports.comment2", "monthly_reports.comment1_str", "monthly_reports.comment1")
            ->selectRaw($sSel)
            // ->where($sWhere)
            ->where($sCondition)
            ->groupBy(
                'division.id',
                'zilla.id',
                'monthly_reports.comment2',
                'monthly_reports.comment1_str',
                'monthly_reports.comment1',
                'division.division_name_bn',
                'zilla.district_name_bn',
                'division.id'
            )
            ->orderBy('division.id') // Optional: Order by division ID
            ->get();


        $result = $resultset->map(function ($item) {
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

        $reportID = $request['reportID'];
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



        $sCondition = [
            ['monthly_reports.report_type_id', '=', 6],
            ['monthly_reports.report_year', '=',  $yr],
            ['monthly_reports.report_month', '=', $mth],
            ['monthly_reports.is_approved', '=', 1]
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
                'monthly_reports.comment1',
                'division.division_name_bn',
                'zilla.district_name_bn',
                'division.id'
            )
            ->selectRaw($sSel) // Add the raw SQL for calculated fields
            ->where($sCondition) // Apply static conditions (report type, month, year, approval)
            // ->where($sWhere) // Apply dynamic conditions (if any)
            ->groupBy(
                'zilla.division_id',
                'zilla.id',
                'monthly_reports.comment2',
                'monthly_reports.comment1_str',
                'monthly_reports.comment1'
            ) // Group results by division and zilla
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
        $data = [
            "result" => $formattedResult,
            "name" => $reportName,
            "profileID" => 9,
            "zilla_name" => $zilla_name,
            "month_year" => $month_year
        ];
        return response()->json($data);
    }

    public function getReportsData(Request $request)
    {
        $requestData = $request->all();
        $request = $requestData['body_data'];

        $reportTypeId = $request['reportId'];
        $reportDate = $request['reportDate'];

        // Call a service method to fetch the report data
        $dataResponse = MisReportRepository::getMonthlyReportData($reportTypeId, $reportDate);

        // Return a JSON response with UTF-8 encoding
        return response()->json($dataResponse, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }

    public function setReportDataUnapproved(Request $request)
    {
        $requestData = $request->all();
        $request = $requestData['body_data'];

        $dataResponse = DB::table('monthly_reports')
            ->where('id', $request) // Specify the condition for selecting records
            ->update(['is_approved' => 0]);
        return response()->json($dataResponse, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
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
}
