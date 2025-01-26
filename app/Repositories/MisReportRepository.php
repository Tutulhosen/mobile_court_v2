<?php

namespace App\Repositories;
use Illuminate\Support\Facades\DB;

class MisReportRepository
{
  
    public static function getMonthlyReportData($reportTypeId,$reportDate){
        $mth = substr($reportDate, 0, 2);
        $yr = substr($reportDate, 3, 4);
        $data = DB::table('monthly_reports')->where('report_year', $yr)
        ->where('report_month', $mth)
        ->where('report_type_id', $reportTypeId)
        ->select('*')
        ->get(); // Use first() to get a single record
        
        $formattedResults = $data->map(function ($item) {
            return (array) $item; // Convert each result to an array
        })->values()->all(); // Re-index the array
        
        // Return the final formatted results
        return $formattedResults;
        // // If no data is found, handle accordingly
        // return response()->json([]);
    }
    
    public static function getCountryBasedReportData($presentDate,$previousDate,$reportId){
        $resultset = [];
        $totResult = [];
        $reportName = "No data Found";
        $reportCommonHeading = "গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</br>মন্ত্রিপরিষদ বিভাগ</br>জেলা ম্যাজিস্ট্রেসি পরিবীক্ষণ অধিশাখা";

        $preMnth = (int)substr($presentDate, 0, 2);
        $preYr = substr($presentDate, 3, 4);
        $pastMnth = (int)substr($previousDate, 0, 2);
        $pastYr = substr($previousDate, 3, 4);

        // Swap if present year is less than past year
        if ($preYr < $pastYr) {
            [$preYr, $pastYr] = [$pastYr, $preYr];
            [$preMnth, $pastMnth] = [$pastMnth, $preMnth];
        }

        // Generate Bangla month and year
        $preBanglaMonthYear = self::get_bangla_monthandyearbymumber($preMnth, $preYr);
        $pastBanglaMonthYear = self::get_bangla_monthandyearbymumber($pastMnth, $pastYr);

        // Conditions based on reportId
        $query1Selection = "COALESCE(SUM(mr.case_submit), 0) as caseSubmitTotal,
                            COALESCE(SUM(mr.case_total), 0) as caseTotal,
                            COALESCE(SUM(mr.case_complete), 0) as caseCompleteTotal,
                            COALESCE(SUM(mr.case_incomplete), 0) as caseIncompleteTotal";
        $query2Selection = "SUM(x.caseSubmitTotal) as totalCaseSubmit,
                            SUM(x.caseTotal) as totalCase,
                            SUM(x.caseCompleteTotal) as totalCaseComplete,
                            SUM(x.caseIncompleteTotal) as totalCaseIncomplete";
        $reportIdCondition = "";

        if ($reportId == 1) {
            $reportName = "$reportCommonHeading</br>{$pastBanglaMonthYear} থেকে {$preBanglaMonthYear} সালের মোবাইল কোর্ট পরিচালনা সংক্রান্ত তথ্যসমূহ";
            $query1Selection = "COALESCE(SUM(mr.promap), 0) as promapTotal,
                                COALESCE(SUM(mr.court_total), 0) as courtTotal,
                                COALESCE(SUM(mr.case_total), 0) as caseTotal,
                                COALESCE(SUM(mr.fine_total), 0) as fineTotal,
                                COALESCE(CONCAT(CEIL((SUM(mr.court_total) / SUM(mr.promap)) * 100), '%'), '0%') as promapPercentage";
            $reportIdCondition = "mr.report_type_id = 1 AND";
            $query2Selection = "SUM(x.promapTotal) as TOTALPROMAP,
                                SUM(x.courtTotal) as TOTALCOURT,
                                SUM(x.caseTotal) as TOTALCASE,
                                SUM(x.fineTotal) as TOTALFINE,
                                CONCAT(CEIL((SUM(x.promapPercentage) / COUNT(x.report_month))), '%') as avgPromapPercentage";
        } elseif ($reportId == 2) {
            $reportName = "$reportCommonHeading</br>{$pastBanglaMonthYear} থেকে {$preBanglaMonthYear} সালের ফৌজদারী কার্যবিধির আপিল সংক্রান্ত তথ্যসমূহ";
            $reportIdCondition = "mr.report_type_id = 2 AND";
        } elseif ($reportId == 3) {
            $reportName = "$reportCommonHeading</br>{$pastBanglaMonthYear} থেকে {$preBanglaMonthYear} সালের ফৌজদারী কার্যবিধির অতিরিক্ত জেলা ম্যাজিস্ট্রেট আদালত সংক্রান্ত তথ্যসমূহ";
            $reportIdCondition = "mr.report_type_id = 3 AND";
        } elseif ($reportId == 4) {
            $reportName = "$reportCommonHeading</br>{$pastBanglaMonthYear} থেকে {$preBanglaMonthYear} সালের ফৌজদারী কার্যবিধির এক্সিকিউটিভ ম্যাজিস্ট্রেটের আদালত সংক্রান্ত তথ্যসমূহ";
            $reportIdCondition = "mr.report_type_id = 4 AND";
        }

        $condition = "(mr.report_year = $pastYr AND mr.report_month >= $pastMnth) OR (mr.report_year = $preYr AND mr.report_month <= $preMnth)";

        $diffYr = $preYr - $pastYr;
        if ($diffYr > 1) {
            $condition .= " OR (mr.report_year > $pastYr AND mr.report_year < $preYr)";
        } elseif ($diffYr == 0 && $pastMnth > $preMnth) {
            [$pastMnth, $preMnth] = [$preMnth, $pastMnth];
            $condition = "(mr.report_year = $preYr AND (mr.report_month >= $pastMnth AND mr.report_month <= $preMnth))";
        }

        // Main query
            $query1 = DB::table('monthly_reports as mr')
                ->selectRaw("
                    CASE mr.report_month
                        WHEN 1 THEN 'জানুয়ারী'
                        WHEN 2 THEN 'ফেব্রুয়ারী'
                        WHEN 3 THEN 'মার্চ'
                        WHEN 4 THEN 'এপ্রিল'
                        WHEN 5 THEN 'মে'
                        WHEN 6 THEN 'জুন'
                        WHEN 7 THEN 'জুলাই'
                        WHEN 8 THEN 'আগস্ট'
                        WHEN 9 THEN 'সেপ্টেম্বর'
                        WHEN 10 THEN 'অক্টোবর'
                        WHEN 11 THEN 'নভেম্বর'
                        WHEN 12 THEN 'ডিসেম্বর'
                    END AS cMonth,
                    mr.report_month,
                    mr.report_year,
                    $query1Selection
                ")
                ->whereRaw("is_approved = 1 AND $reportIdCondition ($condition)")
                ->groupBy('mr.report_year', 'mr.report_month');

            $query1Sql = $query1->toSql();

            // Execute the first query to get the data
            $resultset = $query1->get();
      
            $resultset1="";
            if ($reportId  == 1){
             $resultset1=$resultset->map(function($item) {
              
                return [
                    0 => $item->cMonth,
                    1 => (string) $item->report_month,
                    2 => (string) $item->report_year,
                    3 => $item->promapTotal,
                    4 => $item->courtTotal,
                    5 => $item->caseTotal,
                    6 => $item->fineTotal,
                    7 => $item->promapPercentage,
                    'cMonth' => $item->cMonth,
                    'report_month' => (string) $item->report_month,
                    'report_year' => (string) $item->report_year,
                    'promapTotal' => $item->promapTotal,
                    'courtTotal' => $item->courtTotal,
                    'caseTotal' => $item->caseTotal,
                    'fineTotal' => $item->fineTotal,
                    'promapPercentage' => $item->promapPercentage
                ];
            });
           }elseif($reportId  != 1){
          
            $resultset1 = $resultset->map(function($item) {
                return [
                    0 => $item->cMonth,
                    1 => (string) $item->report_month,
                    2 => (string) $item->report_year,
                    3 => $item->caseSubmitTotal,
                    4 => $item->caseTotal,
                    5 => $item->caseCompleteTotal,
                    6 => $item->caseIncompleteTotal,
                    'cMonth' => $item->cMonth,
                    'report_month' => (string) $item->report_month,
                    'report_year' => (string) $item->report_year,
                    'caseSubmitTotal' => $item->caseSubmitTotal,
                    'caseTotal' => $item->caseTotal,
                    'caseCompleteTotal' => $item->caseCompleteTotal,
                    'caseIncompleteTotal' => $item->caseIncompleteTotal
                ];
            });

           }
       
            // Summary query
            $result2="";
            $query2 = DB::table(DB::raw("({$query1Sql}) as x"))
            ->selectRaw($query2Selection)
            ->mergeBindings($query1)  
            ->get();
            if ($reportId == 1){
             
             $result2=$query2->map(function($item) {

                return [
                    0 => $item->TOTALPROMAP,
                    1 => $item->TOTALCOURT,
                    2 => $item->TOTALCASE,
                    3 => $item->TOTALFINE,
                    4 => $item->avgPromapPercentage,
                    'TOTALPROMAP' => $item->TOTALPROMAP,
                    'TOTALCOURT' => $item->TOTALCOURT,
                    'TOTALCASE' => $item->TOTALCASE,
                    'TOTALFINE' => $item->TOTALFINE,
                    'avgPromapPercentage' => $item->avgPromapPercentage
                ];
            });
            
           }elseif($reportId !=1){
           
            $result2 = $query2->map(function($item) {
                
                return [
                    0 => $item->totalCaseSubmit,
                    1 => $item->totalCase,
                    2 => $item->totalCaseComplete,
                    3 => $item->totalCaseIncomplete,
                    'totalCaseSubmit' => $item->totalCaseSubmit,
                    'totalCase' => $item->totalCase,
                    'totalCaseComplete' => $item->totalCaseComplete,
                    'totalCaseIncomplete' => $item->totalCaseIncomplete
                ];
            });
           } 
 
            return [
                "resultSet" => $resultset1,
                "totResult" =>  $result2,
                "reportName" => $reportName
            ];
    

    }

    public static function get_bangla_monthandyearbymumber($month, $year)
    {

        if ($year == "") {

            $year = self::bangla_number(date('Y'));
        }else{
            $year=self::bangla_number($year);
        }
        $mons = array(1 => "জানুয়ারী", 2 => "ফেব্রুয়ারী", 3 => "মার্চ", 4 => "এপ্রিল", 5 => "মে", 6 => "জুন", 7 => "জুলাই", 8 => "আগস্ট", 9 => "সেপ্টেম্বর", 10 => "অক্টোবর", 11 => "নভেম্বর", 12 => "ডিসেম্বর");
        $month_name = $mons[$month];

        return $month_name . " " . $year;
    }
    public static function bangla_number($int){
        $engNumber = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 0);
        $bangNumber = array('১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯', '০');

        $converted = str_replace($engNumber, $bangNumber, $int);
        return $converted;
    }
}