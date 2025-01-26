<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Court;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Repositories\DashboardRepository;
use App\Repositories\DashboardLocationRepository;
use App\Repositories\DashboardCitizenRepository;
use App\Repositories\BanglaDate;
use App\Repositories\DashboardFine;
use App\Repositories\Dashboardlaw;
use App\Http\Controllers\Api\BaseController as BaseController;
// Import your model

class MDDashboardController extends BaseController
{
    // Show common dashboard page
    public function index()
    {
        $data['geoCityCorporations'] = [];
        $data['geoMetropolitan'] = [];
        $data['geoThanas'] = [];

        $roleID = globalUserInfo()->role_id;

        // if ($roleID == 37) {
        //     $data['title'] = 'জেলা ম্যাজিস্ট্রেট';
        //     return view('dashboard.monitoring_admin');
        // } elseif ($roleID == 38) {
          if($roleID == 37 || $roleID == 38){
            $data = [];
            $prosecutorId = auth()->user()->id;
            $userinfo = globalUserInfo();
            $office_id = $userinfo->office_id;
            $officeinfo = DB::table('office')->where('id', $office_id)->first();
            $division_id = $officeinfo->division_id;
            $district_id = $officeinfo->district_id;
            $data["zillaId"] =$district_id;
           $data["upazila"] =DB::table('upazila')->where('district_id',$district_id)->get();

            $start_date = Carbon::now()->startOfMonth()->toDateString();
            $end_date = Carbon::now()->toDateString();

            $subQuery = $this->getProsecutionSubQuery($district_id, $start_date, $end_date);

            $t1Query = DB::table(DB::raw("({$subQuery->toSql()}) as p"))
                ->mergeBindings($subQuery)
                ->select(
                    'p.divid',
                    'p.zillaId',
                    'p.location_type',
                    'p.LocationID',
                    'p.LocationName',
                    'p.date',
                    DB::raw("IF((p.date BETWEEN '$start_date' AND '$end_date'), 1, 0) AS CCourtCount")
                )
                ->groupBy('p.divid', 'p.zillaId', 'p.location_type', 'p.LocationID', 'p.LocationName');

            $result = DB::table('division as div')
                ->leftJoin('district as dist', 'dist.division_id', '=', 'div.id')
                ->leftJoin(DB::raw("({$t1Query->toSql()}) as j1"), function ($join) {
                    $join->on('j1.divid', '=', 'div.id')
                        ->on('j1.zillaId', '=', 'dist.id')
                        ->on('j1.location_type', '=', DB::raw("'UPAZILLA'"));
                })
                ->mergeBindings($t1Query)
                ->select(DB::raw('IFNULL(SUM(j1.CCourtCount), 0) AS court'))
                ->get();

            $data['executed_court'] = $result[0]->court;

            // Total case count
            $caseCountSubQuery = $this->getCaseCountSubQuery($district_id, $start_date, $end_date);

            $no_case_dc = DB::table('division as d')
                ->leftJoin('district as dist', 'dist.division_id', '=', 'd.id')
                ->leftJoin('upazila as u', 'u.district_id', '=', 'dist.id')
                ->leftJoinSub($caseCountSubQuery, 't', function ($join) {
                    $join->on('t.divid', '=', 'd.id')
                        ->on('t.zillaId', '=', 'dist.id')
                        ->on('t.LocationID', '=', 'u.id');
                })
                ->whereIn('t.location_type', ['UPAZILLA', 'CITYCORPORATION', 'METROPOLITAN'])
                ->select(DB::raw('IFNULL(SUM(t.CCaseCount), 0) AS caseCount'))
                ->first();
            $data['total_case'] = $no_case_dc->caseCount;

            // Total Fine Count
            $subQuery = DB::table('punishments as pn')
                ->join('prosecutions as p', 'p.id', '=', 'pn.prosecution_id')
                ->leftJoin('upazila as u', function ($join) {
                    $join->on('u.district_id', '=', 'p.zillaid')
                        ->on('u.id', '=', 'p.upazilaid');
                })->select([
                'p.divid',
                'p.zillaId',
                'p.location_type',
                DB::raw("CASE p.location_type
                            WHEN 'UPAZILLA' THEN u.id
                         END AS LocationID"),
                DB::raw("CASE p.location_type
                            WHEN 'UPAZILLA' THEN u.upazila_name_bn
                            ELSE NULL
                         END AS LocationName"),
                DB::raw("IF((p.date BETWEEN '$start_date' AND '$end_date'), 1, 0) AS CCriminalCount"),
                DB::raw("IF((p.date BETWEEN '$start_date' AND '$end_date'),
                            IF(LENGTH(TRIM(IFNULL(pn.receipt_no, ''))) > 0, IFNULL(pn.fine, 0), 0), 0) AS CFineCount"),
                DB::raw("IF((p.date BETWEEN '$start_date' AND '$end_date'),
                            IF(IFNULL(pn.punishmentJailID, 0) > 0, 1, 0), 0) AS CJailCount"),
            ])
                ->where('p.delete_status', 1)
                ->where('p.is_approved', 1)
                ->whereNotNull('p.orderSheet_id')
                ->whereNotNull('pn.criminal_id')
                ->whereBetween('p.date', [$start_date, $end_date]);

            $result = DB::table('division as d')
                ->leftJoin('district as dist', 'dist.division_id', '=', 'd.id')
                ->leftJoin('upazila as u', 'u.district_id', '=', 'dist.id')
                ->leftJoinSub($subQuery, 't', function ($join) {
                    $join->on('t.divid', '=', 'd.id')
                        ->on('t.zillaId', '=', 'dist.id')
                        ->on('t.LocationID', '=', 'u.id');
                })
                ->select([
                    DB::raw('IFNULL(SUM(t.CCriminalCount), 0) AS criminal'),
                    DB::raw('IFNULL(SUM(t.CFineCount), 0) AS fine'),
                    DB::raw('IFNULL(SUM(t.CJailCount), 0) AS jail_criminal'),
                ])
                ->whereIn('t.location_type', ['UPAZILLA'])
                ->first();

            $data['total_fine'] = $result->fine;
            $data['total_criminal'] = $result->criminal;
            $data['total_jail_criminal'] = $result->jail_criminal;

            // Total procecutor count

            $data['totalProcecutor'] = roleWiseUserList(26)->where('doptor_office_id', $userinfo->doptor_office_id)->count();
            // dd($data['totalProcecutor']);

            // Criminal Reacord

            $ignore = 'ignore';
            $excepted = 'excepted';


          // Unchanged case query
            $unchangeCitizenComplain = DB::table('citizen_complains')
                ->distinct()
                ->select('id')
                ->where('complain_status', '!=', $ignore)
                ->where('complain_status', '!=', $excepted)
                ->where('divid', '=', $division_id)
                ->where('zillaId', '=', $district_id)
                ->whereBetween('complain_date', [$start_date, $end_date])
                ->orderBy('created_date', 'DESC')
                ->get();

          // Ignore case query
            $citizenComplainIgnore = DB::table('citizen_complains')
                ->distinct()
                ->select('id')
                ->where('complain_status', '=', $ignore)
                ->where('divid', '=', $division_id)
                ->where('zillaId', '=', $district_id)
                ->groupBy('id')
                ->get();

            $citizenComplainTotal = DB::table('citizen_complains')
                ->where('divid', '=', $division_id)
                ->where('zillaId', '=', $district_id)
                ->count();

            $unchangeComplainCount = $unchangeCitizenComplain->count();


            $ignoredComplainCount = $citizenComplainIgnore->count();

            $exceptedComplainCount = $citizenComplainTotal - $unchangeComplainCount - $ignoredComplainCount;


                $data['totalComplain'] = $citizenComplainTotal;
                $data['unchangeComplain'] = $unchangeComplainCount;
                $data['ignoreComplain'] = $ignoredComplainCount;
                $data['exceptedComplain'] = $exceptedComplainCount;
                $data['roleID']=$roleID;
            $data['title'] = 'অতিরিক্ত জেলা ম্যাজিস্ট্রেট';
            return view('dashboard.monitoring_adm', $data);
        } elseif ($roleID == 25) {  

            $data = [];
            $prosecutorId = auth()->user()->id;
            $userinfo = globalUserInfo();

            // dd($prosecutorId);
            // $data['executed_court'] = Court::TotalCourt()->where('magistrate_id', $prosecutorId)
            //     ->count();
            //     $allcases = Prosecution::where('delete_status', '1')
            //     ->where('is_approved', '1')
            //     ->whereHas('courtinfo.user', function($query) use ($magistrateId) {
            //         $query->where('id', $magistrateId);
            //     })
            //     ->count();

            // $data['allcases'] = DB::table('prosecutions')
            //     ->join('courts', 'prosecutions.court_id', '=', 'courts.id')
            //     ->join('users', 'prosecutions.prosecutor_id', '=', 'users.id')
            //     ->where('prosecutions.delete_status', '1')
            //     ->where('prosecutions.prosecutor_id', $prosecutorId)
            //     ->count();
          
            $prosecutorId = auth()->user()->id;
            $condition ='';
            $total_case_number = DashboardRepository::getProsecutorTotalCaseCount($prosecutorId, $condition);   
            
            // $data['totalComplete'] = DB::table('prosecutions as pro')
            //     ->join('courts as court', 'court.id', '=', 'pro.court_id')
            //     ->where('pro.delete_status', '=', '1')
            //     ->where('pro.is_approved', '1')
            //     ->where('pro.is_suomotu', '1')
            //     ->where('pro.prosecutor_id', '=', $prosecutorId)
            //     ->whereNull('pro.orderSheet_id')
            //     ->count('pro.id');
            // dd($data);
           $incomplete_case_number = DashboardRepository::getprosecutorIncompleteCaseCount($prosecutorId, $condition);
           $complete_case_number =  $total_case_number - $incomplete_case_number;
           $executed_court=DashboardRepository::getprosecutorTotalExecutedCourt($prosecutorId);
            // $data['withoutCriminal'] = DB::table('prosecutions')
            //     ->join('courts', 'prosecutions.court_id', '=', 'courts.id')
            //     ->join('users', 'prosecutions.prosecutor_id', '=', 'users.id')
            //     ->where('prosecutions.delete_status', '1')
            //     ->where('prosecutions.hasCriminal', '0')
            //     ->where('users.id', $prosecutorId)
            //     ->count();

            // $data['withCriminal'] = DB::table('prosecutions')
            //     ->join('courts', 'prosecutions.court_id', '=', 'courts.id')
            //     ->join('users', 'prosecutions.prosecutor_id', '=', 'users.id')
            //     ->where('prosecutions.delete_status', '1')
            //     ->where('prosecutions.hasCriminal', '1')
            //     ->where('users.id', $prosecutorId)
            //     ->count();

            // $data['executed_court'] = DB::table('courts')
            //     ->join('prosecutions', 'courts.id', '=', 'prosecutions.court_id')
            //     ->join('users', 'prosecutions.prosecutor_id', '=', 'users.id')
            //     ->where('courts.status', '2')
            //     ->where('users.id', $prosecutorId)
            //     ->count();
           $criminalAndFine =DashboardRepository::getProsecutorTotalCriminalAndFine($prosecutorId);
            $fine = 0;
            foreach($criminalAndFine as $it){
                $fine += $it->fine;
            }
         
            // $data['punishments'] = DB::table('punishments')
            //     ->select('punishments.criminal_id as criminal', 'punishments.fine as fine')
            //     ->join('prosecutions', 'punishments.prosecution_id', '=', 'prosecutions.id')
            //     ->join('courts', 'prosecutions.court_id', '=', 'courts.id')
            //     ->where('prosecutions.delete_status', '1')
            //     ->where('prosecutions.is_approved', '1')
            //     ->where('prosecutions.prosecutor_id', $prosecutorId)
            //     ->groupBy('punishments.prosecution_id')
            //     ->get();

            // $data['criminalCount'] = DB::table('prosecution_details as PROS')
            //     ->join('prosecutions', 'PROS.prosecution_id', '=', 'prosecutions.id')
            //     ->join('courts', 'prosecutions.court_id', '=', 'courts.id')
            //     ->where('prosecutions.delete_status', '1')
            //     ->where('prosecutions.is_approved', '1')
            //     ->where('prosecutions.prosecutor_id', $prosecutorId)
            //     ->count('PROS.criminal_id');
            // Get criminal list
            $criminal = DashboardRepository::getProsecutorCriminalList($prosecutorId);
            $index = $criminal ?? 0;
            // $data['totalComplain'] = DB::table('prosecutions as pro')
            //     ->join('courts as court', 'court.id', '=', 'pro.court_id')
            //     ->join('court_complain_infos as cci', 'cci.court_id', '=', 'pro.court_id')
            //     ->where('pro.delete_status', '1')
            //     ->where('pro.prosecutor_id', $prosecutorId)
            //     ->where('cci.complain_type', 1)
            //     ->count('pro.id');
            // citizen complanev
            $totalCitizenComplain = DashboardRepository::getProsecutorTotalCitizenComplain($prosecutorId);
            // citizen complain those r not comlete
            $result_case_processing = DashboardRepository::getProsecutorListOfIncompleteCitizenComplain($prosecutorId);
            // $data['totalIncomplete'] = DB::table('prosecutions as pro')
            //     ->join('courts as court', 'court.id', '=', 'pro.court_id')
            //     ->join('court_complain_infos as cci', 'cci.court_id', '=', 'pro.court_id')
            //     ->where('pro.delete_status', '=', '1')
            //     ->where('pro.prosecutor_id', $prosecutorId)
            //     ->where('cci.complain_type', '=', 1)
            //     ->whereNull('pro.orderSheet_id')
            //     ->count('pro.id');
            // $allSelfCases = DashboardRepository::getListOfallSelfCases($magistrateId); //স্বপ্রণোদিত মামলা
            $totalCompleteCaseCount = ( $totalCitizenComplain) - ( $result_case_processing);

            // $data['totalPendingComplain'] = 0;

            $data['title'] = 'প্রসিকিউটর';

         
             // Pass data to the view
             $data=[
                'executed_court' => $executed_court,
                'citz_case_processing' => $result_case_processing,
                'citz_case_complete' => $totalCompleteCaseCount,
                'totalCitizenComplain' => $totalCitizenComplain,
                'complete_case' => $complete_case_number,
                'incomplete_case' => $incomplete_case_number,
                'total_case_number' => $total_case_number,
                // 'allSelfCases' => $allSelfCases,
                'criminal_no_mgt' => $index,
                'fine_mgt' =>$fine

            ];
        
            
            return view('dashboard.monitoring_pro', $data);
        } elseif ($roleID == 26) {

            $data = [];
            $magistrateId = auth()->user()->id;
            $condition ='';
            $total_case_number = DashboardRepository::getTotalCaseCount($magistrateId, $condition);
            // number of incomplete case

            $incomplete_case_number = DashboardRepository::getIncompleteCaseCount($magistrateId, $condition);

            $complete_case_number =  $total_case_number - $incomplete_case_number;
            
            //Total executed court
            $executed_court = DashboardRepository::getTotalExecutedCourt($magistrateId, $condition);

            // citizen complanev
            $totalCitizenComplain = DashboardRepository::getTotalCitizenComplain($magistrateId, $condition);

            // citizen complain those r not comlete
             $result_case_processing = DashboardRepository::getListOfIncompleteCitizenComplain($magistrateId, $condition);
             // Check if executed_court is not empty

            // Get criminal list
            $criminal = DashboardRepository::getCriminalList($magistrateId);
            $index = $criminal ?? 0;
        
            // Get total fine and number of criminals
            $criminalAndFine =DashboardRepository::getTotalCriminalAndFine($magistrateId);
            $allSelfCases = DashboardRepository::getListOfallSelfCases($magistrateId); //স্বপ্রণোদিত মামলা
            $fine = 0;
            foreach($criminalAndFine as $it){
                $fine += $it->fine;
            }

            $citz_case_complete = ((int)   $totalCitizenComplain ?? 0) - ((int) $result_case_processing ?? 0);

            // Pass data to the view
                $data=[
                    'executed_court' => $executed_court,
                    'citz_case_processing' => $result_case_processing,
                    'citz_case_complete' => $citz_case_complete,
                    'totalCitizenComplain' => $totalCitizenComplain,
                    'complete_case' => $complete_case_number,
                    'incomplete_case' => $incomplete_case_number,
                    'total_case_number' => $total_case_number,
                    'allSelfCases' => $allSelfCases,
                    'criminal_no_mgt' => $index . " জন",
                    'fine_mgt' => $fine . " টাকা"
                ];
            
                $data['title'] = 'এক্সিকিউটিভ ম্যাজিস্ট্রেট';

            return view('dashboard.monitoring_em', $data);
        } elseif ($roleID == 27) {
            $data['title'] = 'এসিজিএম';
            return view('dashboard.acjm_dashboard');
        }
    }

    public function case_count_for_mc(Request $request){
        $all_case=DB::table('prosecutions')->count();
  
        return $this->sendResponse($all_case, null);
    }
    private function getProsecutionSubQuery($district_id, $start_date, $end_date)
    {
        return DB::table('prosecutions as p')
            ->select(
                'p.divid',
                'p.zillaId',
                'p.location_type',
                DB::raw("CASE p.location_type
                    WHEN 'UPAZILLA' THEN u.id
                    ELSE NULL END AS LocationID"),
                DB::raw("CASE p.location_type
                    WHEN 'UPAZILLA' THEN u.upazila_name_bn
                    ELSE NULL END AS LocationName"),
                'c.date',
                'p.court_id'
            )
            ->join('courts as c', 'c.id', '=', 'p.court_id')
            ->leftJoin('upazila as u', function ($join) {
                $join->on('u.district_id', '=', 'p.zillaId')
                    ->on('u.id', '=', 'p.upazilaid');
            })
            ->where('p.delete_status', 1)
            ->where('c.status', 2)
            ->where('c.delete_status', 1)
            ->where('p.zillaId', $district_id)
            ->whereBetween('c.date', [$start_date, $end_date])
            ->groupBy('p.divid', 'p.zillaId', 'p.location_type', 'LocationName', 'c.date', 'p.court_id');
    }

    private function getCaseCountSubQuery($district_id, $start_date, $end_date)
    {
        return DB::table('prosecutions as p')
            ->select(
                'p.divid',
                'p.zillaId',
                'p.location_type',
                DB::raw("CASE p.location_type
                    WHEN 'UPAZILLA' THEN u.id
                    ELSE NULL END AS LocationID"),
                DB::raw("IF((p.date BETWEEN '$start_date' AND '$end_date'), 1, 0) AS CCaseCount")
            )
            ->leftJoin('upazila as u', function ($join) {
                $join->on('u.district_id', '=', 'p.zillaId')
                    ->on('u.id', '=', 'p.upazilaid');
            })
            ->where('p.delete_status', 1)
            ->where('p.is_approved', 1)
            ->whereNotNull('p.orderSheet_id')
            ->where('p.zillaId', $district_id)
            ->whereBetween('p.date', [$start_date, $end_date]);

    }

    public function dashboard_monthly_report(Request $request)
    {

        $requestData = $request->all();
        $request = $requestData['body_data'];

        $roleID = $request['role_id'];


        $condition = '';

     

        $year = $request['year'];
        $current_month = $request['currentMonth'];
        $previous_month = $request['previousMonth'];
        $divisionid = $request['divisionid'];


        if ($roleID==34) {
            $condition .= "AND l.Level1IDOld = $divisionid";
        }

        $resultset=  $this->fetchDataTableBySql_new($condition, $year, $current_month, $previous_month, $divisionid, $roleID);

        return response()->json($resultset);

    }
    

    public function fetchDataTableBySql($condition, $year, $current_month, $previous_month)
    {
        $previousYear = '';
        $currentYear = '';
        $previousYearMonth = '';
        $currentYearMonth = '';

        if ($current_month == 1) {
            $previousYear = $year - 1;
            $currentYear = $year;
            $previousYearMonth = 12;
            $currentYearMonth = $current_month;
        } else {
            $previousYear = $year;
            $currentYear = $year;
            $previousYearMonth = $previous_month;
            $currentYearMonth = $current_month;
        }

        $query = "
            SELECT
                o.division_id AS DivisionID,
                o.div_name_bn AS DivisionName,
                o.district_id AS ZillaID,
                o.dis_name_bn AS ZillaName,
                o.upazila_id AS OfficeID,
                o.upa_name_bn AS OfficeName,
                IFNULL(j1.PCourtCount, 0)    AS PCourtCount,
                IFNULL(j1.CCourtCount, 0)    AS CCourtCount,
                IFNULL(j2.PCaseCount, 0)     AS PCaseCount,
                IFNULL(j2.CCaseCount, 0)     AS CCaseCount,
                IFNULL(j3.PCriminalCount, 0) AS PCriminalCount,
                IFNULL(j3.CCriminalCount, 0) AS CCriminalCount,
                IFNULL(j3.PFineCount, 0)     AS PFineCount,
                IFNULL(j3.CFineCount, 0)     AS CFineCount,
                IFNULL(j3.PJailCount, 0)     AS PJailCount,
                IFNULL(j3.CJailCount, 0)     AS CJailCount
            FROM office o 
            LEFT JOIN (
                SELECT
                    t1.divid,
                    t1.zillaId,
                    t1.location_type, t1.OfficeID, t1.OfficeName,
                    SUM(t1.PCourtCount) AS PCourtCount,
                    SUM(t1.CCourtCount) AS CCourtCount
                FROM (
                    SELECT
                        p.divid,
                        p.zillaId,
                        p.location_type, p.OfficeID, p.OfficeName,
                        IF((YEAR(p.date) = $previousYear AND MONTH(p.date) = $previousYearMonth), 1, 0) AS PCourtCount,
                        IF((YEAR(p.date) = $currentYear AND MONTH(p.date) = $currentYearMonth), 1, 0) AS CCourtCount
                    FROM (
                        SELECT
                            p.divid,
                            p.zillaId,
                            p.location_type,
                            CASE p.location_type 
                                WHEN 'UPAZILLA' THEN u.id
                                WHEN 'CITYCORPORATION' THEN gc.id 
                                WHEN 'METROPOLITAN' THEN m.id 
                                ELSE NULL 
                            END AS OfficeID,
                            CASE p.location_type 
                                WHEN 'UPAZILLA' THEN u.upazila_name_bn 
                                WHEN 'CITYCORPORATION' THEN gc.city_corporation_name_bng 
                                WHEN 'METROPOLITAN' THEN m.metropolitan_name_bng 
                                ELSE NULL 
                            END AS OfficeName,
                            c.date,
                            p.court_id
                        FROM prosecutions AS p
                        JOIN courts c ON c.id = p.court_id
                        LEFT JOIN upazila u ON u.district_id = p.zillaid AND u.id= p.upazilaid
                        LEFT JOIN geo_city_corporations gc ON gc.division_bbs_code = p.divid AND gc.district_bbs_code = p.zillaid AND gc.id = p.geo_citycorporation_id
                        LEFT JOIN geo_metropolitan m ON m.division_bbs_code = p.divid AND m.district_bbs_code = p.zillaid AND m.id = p.geo_metropolitan_id
                        WHERE p.delete_status = 1 AND c.status = 2 AND c.delete_status = 1
                        AND (YEAR(c.date) = $previousYear OR YEAR(c.date) = $currentYear)
                        AND (MONTH(c.date) = $previousYearMonth OR MONTH(c.date) = $currentYearMonth)
                        GROUP BY p.divid, p.zillaId, p.location_type, OfficeName, c.date, p.court_id
                    ) p
                ) t1 GROUP BY t1.divid, t1.zillaId, t1.location_type, t1.OfficeName
            ) j1 ON j1.divid = o.division_id AND j1.zillaId = o.district_id AND j1.OfficeID = o.upazila_id

            LEFT JOIN (
                SELECT
                    t1.divid,
                    t1.zillaId,
                    t1.location_type, t1.OfficeID, t1.OfficeName,
                    SUM(t1.PCaseCount) AS PCaseCount,
                    SUM(t1.CCaseCount) AS CCaseCount
                FROM (
                    SELECT
                        p.divid,
                        p.zillaId,
                        p.location_type, p.OfficeID, p.OfficeName,
                        IF((YEAR(p.date) = $previousYear AND MONTH(p.date) = $previousYearMonth), 1, 0) AS PCaseCount,
                        IF((YEAR(p.date) = $currentYear AND MONTH(p.date) = $currentYearMonth), 1, 0) AS CCaseCount
                    FROM (
                        SELECT
                            p.divid,
                            p.zillaId,
                            p.location_type,
                            CASE p.location_type 
                                WHEN 'UPAZILLA' THEN u.id
                                WHEN 'CITYCORPORATION' THEN gc.id 
                                WHEN 'METROPOLITAN' THEN m.id 
                                ELSE NULL 
                            END AS OfficeID,
                            CASE p.location_type 
                                WHEN 'UPAZILLA' THEN u.upazila_name_bn 
                                WHEN 'CITYCORPORATION' THEN gc.city_corporation_name_bng 
                                WHEN 'METROPOLITAN' THEN m.metropolitan_name_bng 
                                ELSE NULL 
                            END AS OfficeName,
                            c.date,
                            p.case_no
                        FROM prosecutions AS p
                        JOIN courts c ON c.id = p.court_id
                        LEFT JOIN upazila u ON u.district_id = p.zillaid AND u.id= p.upazilaid
                        LEFT JOIN geo_city_corporations gc ON gc.division_bbs_code = p.divid AND gc.district_bbs_code = p.zillaid AND gc.id = p.geo_citycorporation_id
                        LEFT JOIN geo_metropolitan m ON m.division_bbs_code = p.divid AND m.district_bbs_code = p.zillaid AND m.id = p.geo_metropolitan_id
                        WHERE p.delete_status = 1 AND c.status = 2 AND c.delete_status = 1
                        AND (YEAR(c.date) = $previousYear OR YEAR(c.date) = $currentYear)
                        AND (MONTH(c.date) = $previousYearMonth OR MONTH(c.date) = $currentYearMonth)
                        GROUP BY p.divid, p.zillaId, p.location_type, OfficeName, c.date, p.case_no
                    ) p
                ) t1 GROUP BY t1.divid, t1.zillaId, t1.location_type, t1.OfficeName
            ) j2 ON j2.divid = o.division_id AND j2.zillaId = o.district_id AND j2.OfficeID = o.upazila_id

            LEFT JOIN (
                SELECT
                    t1.divid,
                    t1.zillaId,
                    t1.location_type, t1.OfficeID, t1.OfficeName,
                    SUM(t1.PCriminalCount) AS PCriminalCount,
                    SUM(t1.CCriminalCount) AS CCriminalCount,
                    SUM(t1.PFineCount) AS PFineCount,
                    SUM(t1.CFineCount) AS CFineCount,
                    SUM(t1.PJailCount) AS PJailCount,
                    SUM(t1.CJailCount) AS CJailCount
                FROM (
                    SELECT
                        p.divid,
                        p.zillaId,
                        p.location_type, p.OfficeID, p.OfficeName,
                        IF((YEAR(p.date) = $previousYear AND MONTH(p.date) = $previousYearMonth), 1, 0) AS PCriminalCount,
                        IF((YEAR(p.date) = $currentYear AND MONTH(p.date) = $currentYearMonth), 1, 0) AS CCriminalCount,
                        IF((YEAR(p.date) = $previousYear AND MONTH(p.date) = $previousYearMonth), 1, 0) AS PFineCount,
                        IF((YEAR(p.date) = $currentYear AND MONTH(p.date) = $currentYearMonth), 1, 0) AS CFineCount,
                        IF((YEAR(p.date) = $previousYear AND MONTH(p.date) = $previousYearMonth), 1, 0) AS PJailCount,
                        IF((YEAR(p.date) = $currentYear AND MONTH(p.date) = $currentYearMonth), 1, 0) AS CJailCount
                    FROM (
                        SELECT
                            p.divid,
                            p.zillaId,
                            p.location_type,
                            CASE p.location_type 
                                WHEN 'UPAZILLA' THEN u.id
                                WHEN 'CITYCORPORATION' THEN gc.id 
                                WHEN 'METROPOLITAN' THEN m.id 
                                ELSE NULL 
                            END AS OfficeID,
                            CASE p.location_type 
                                WHEN 'UPAZILLA' THEN u.upazila_name_bn 
                                WHEN 'CITYCORPORATION' THEN gc.city_corporation_name_bng 
                                WHEN 'METROPOLITAN' THEN m.metropolitan_name_bng 
                                ELSE NULL 
                            END AS OfficeName,
                            c.date
                 
                        FROM prosecutions AS p
                        JOIN courts c ON c.id = p.court_id
                        LEFT JOIN upazila u ON u.district_id = p.zillaid AND u.id= p.upazilaid
                        LEFT JOIN geo_city_corporations gc ON gc.division_bbs_code = p.divid AND gc.district_bbs_code = p.zillaid AND gc.id = p.geo_citycorporation_id
                        LEFT JOIN geo_metropolitan m ON m.division_bbs_code = p.divid AND m.district_bbs_code = p.zillaid AND m.id = p.geo_metropolitan_id
                        WHERE p.delete_status = 1 AND c.status = 2 AND c.delete_status = 1
                        AND (YEAR(c.date) = $previousYear OR YEAR(c.date) = $currentYear)
                        AND (MONTH(c.date) = $previousYearMonth OR MONTH(c.date) = $currentYearMonth)
                        GROUP BY p.divid, p.zillaId, p.location_type, OfficeName, c.date
                    ) p
                ) t1 GROUP BY t1.divid, t1.zillaId, t1.location_type, t1.OfficeName
            ) j3 ON j3.divid = o.division_id AND j3.zillaId = o.district_id  AND j3.OfficeID = o.upazila_id WHERE o.division_id != 0
        ";

        return DB::select(DB::raw($query));
    }

    public function fetchDataTableBySql_new($condition, $year, $current_month, $previous_month, $divisionid, $roleID)
    {
        $previousYear = '';
        $currentYear = '';
        $previousYearMonth = '';
        $currentYearMonth = '';

        if ($current_month == 1) {
            $previousYear = $year - 1;
            $currentYear = $year;
            $previousYearMonth = 12;
            $currentYearMonth = $current_month;
        } else {
            $previousYear = $year;
            $currentYear = $year;
            $previousYearMonth = $previous_month;
            $currentYearMonth = $current_month;
        }
        $result="SELECT 
                l.Level1Type,
                l.Level1IDOld AS DivisionID,
                l.Level1Name AS DivisionName,
                l.Level2Type,
                l.Level2IDOld AS ZillaID,
                l.Level2Name AS ZillaName,
                l.LocationType,
                l.Level3IDOld AS LocationID,
                l.LocationName,
                IFNULL(j1.PCourtCount, 0) AS PCourtCount,
                IFNULL(j1.CCourtCount, 0) AS CCourtCount,
                IFNULL(j2.PCaseCount, 0) AS PCaseCount,
                IFNULL(j2.CCaseCount, 0) AS CCaseCount,
                IFNULL(j3.PCriminalCount, 0) AS PCriminalCount,
                IFNULL(j3.CCriminalCount, 0) AS CCriminalCount,
                IFNULL(j3.PFineCount, 0) AS PFineCount,
                IFNULL(j3.CFineCount, 0) AS CFineCount,
                IFNULL(j3.PJailCount, 0) AS PJailCount,
                IFNULL(j3.CJailCount, 0) AS CJailCount
            FROM (
                SELECT 
                    'UPAZILLA' AS Level1Type, u.division_id AS Level1IDOld, d.division_name_bn AS Level1Name,
                    'ZILLA' AS Level2Type, u.district_id AS Level2IDOld, z.district_name_bn AS Level2Name,
                    'UPAZILLA' AS LocationType, u.id AS Level3IDOld, u.upazila_name_bn AS LocationName
                FROM upazila u
                JOIN division d ON d.id = u.division_id
                JOIN district z ON z.id = u.district_id
            
                UNION ALL
            
                SELECT 
                    'CITYCORPORATION' AS Level1Type, gc.geo_division_id AS Level1IDOld, d.division_name_bn AS Level1Name,
                    'ZILLA' AS Level2Type, gc.geo_district_id AS Level2IDOld, z.district_name_bn AS Level2Name,
                    'CITYCORPORATION' AS LocationType, gc.id AS Level3IDOld, gc.city_corporation_name_bng AS LocationName
                FROM geo_city_corporations gc
                JOIN division d ON d.id = gc.geo_division_id
                JOIN district z ON z.id = gc.geo_district_id
            
                UNION ALL
            
                SELECT 
                    'METROPOLITAN' AS Level1Type, m.geo_division_id AS Level1IDOld, d.division_name_bn AS Level1Name,
                    'ZILLA' AS Level2Type, m.geo_district_id AS Level2IDOld, z.district_name_bn AS Level2Name,
                    'METROPOLITAN' AS LocationType, m.id AS Level3IDOld, m.metropolitan_name_bng AS LocationName
                FROM geo_metropolitan m
                JOIN division d ON d.id = m.geo_division_id
                JOIN district z ON z.id = m.geo_district_id
            ) l
            LEFT JOIN (
                SELECT 
                    divid, zillaId, location_type, LocationID, 
                    SUM(PCourtCount) AS PCourtCount, SUM(CCourtCount) AS CCourtCount
                FROM (
                    SELECT 
                        p.divid, p.zillaId, p.location_type,
                        CASE p.location_type 
                            WHEN 'UPAZILLA' THEN p.upazilaid 
                            WHEN 'CITYCORPORATION' THEN p.geo_citycorporation_id 
                            WHEN 'METROPOLITAN' THEN p.geo_metropolitan_id 
                        END AS LocationID,
                        IF((YEAR(p.date) = $previousYear AND MONTH(p.date) = $previousYearMonth), 1, 0) AS PCourtCount,
                        IF((YEAR(p.date) = $currentYear AND MONTH(p.date) = $currentYearMonth), 1, 0) AS CCourtCount
                    FROM prosecutions p
                    JOIN courts c ON c.id = p.court_id
                    WHERE p.delete_status = 1 AND c.status = 2 AND c.delete_status = 1
                    AND (YEAR(c.date) = $previousYear OR YEAR(c.date) = $currentYear)
                    AND (MONTH(c.date) = $previousYearMonth OR MONTH(c.date) = $currentYearMonth)
                ) t1
                GROUP BY divid, zillaId, location_type, LocationID
            ) j1 ON j1.divid = l.Level1IDOld AND j1.zillaId = l.Level2IDOld AND j1.location_type = l.LocationType AND j1.LocationID = l.Level3IDOld

            
            LEFT JOIN (
                SELECT 
                    divid, zillaId, location_type, LocationID,
                    SUM(PCaseCount) AS PCaseCount, SUM(CCaseCount) AS CCaseCount
                FROM (
                    SELECT 
                        p.divid, p.zillaId, p.location_type,
                        CASE p.location_type 
                            WHEN 'UPAZILLA' THEN p.upazilaid 
                            WHEN 'CITYCORPORATION' THEN p.geo_citycorporation_id 
                            WHEN 'METROPOLITAN' THEN p.geo_metropolitan_id 
                        END AS LocationID,
                        IF((YEAR(p.date) = $previousYear AND MONTH(p.date) = $previousYearMonth), 1, 0) AS PCaseCount,
                        IF((YEAR(p.date) = $currentYear AND MONTH(p.date) = $currentYearMonth), 1, 0) AS CCaseCount
                    FROM prosecutions p
                    WHERE p.delete_status = 1 AND p.is_approved = 1 AND p.orderSheet_id IS NOT NULL
                    AND (YEAR(p.date) = $previousYear OR YEAR(p.date) = $currentYear)
                    AND (MONTH(p.date) = $previousYearMonth OR MONTH(p.date) = $currentYearMonth)
                ) t2
                GROUP BY divid, zillaId, location_type, LocationID
            ) j2 ON j2.divid = l.Level1IDOld AND j2.zillaId = l.Level2IDOld AND j2.location_type = l.LocationType AND j2.LocationID = l.Level3IDOld
            
            LEFT JOIN (
                SELECT 
                    divid, zillaId, location_type, LocationID,
                    SUM(PCriminalCount) AS PCriminalCount, SUM(CCriminalCount) AS CCriminalCount,
                    SUM(PFineCount) AS PFineCount, SUM(CFineCount) AS CFineCount,
                    SUM(PJailCount) AS PJailCount, SUM(CJailCount) AS CJailCount
                FROM (
                    SELECT 
                        p.divid, p.zillaId, p.location_type,
                        CASE p.location_type 
                            WHEN 'UPAZILLA' THEN p.upazilaid 
                            WHEN 'CITYCORPORATION' THEN p.geo_citycorporation_id 
                            WHEN 'METROPOLITAN' THEN p.geo_metropolitan_id 
                        END AS LocationID,
                        IF((YEAR(p.date) = $previousYear AND MONTH(p.date) = $previousYearMonth), 1, 0) AS PCriminalCount,
                        IF((YEAR(p.date) = $currentYear AND MONTH(p.date) = $currentYearMonth), 1, 0) AS CCriminalCount,
                        IF((YEAR(p.date) = $previousYear AND MONTH(p.date) = $previousYearMonth), IF(LENGTH(TRIM(IFNULL(pn.receipt_no, ''))) > 0, IFNULL(pn.fine, 0), 0), 0) AS PFineCount,
                        IF((YEAR(p.date) = $currentYear AND MONTH(p.date) = $currentYearMonth), IF(LENGTH(TRIM(IFNULL(pn.receipt_no, ''))) > 0, IFNULL(pn.fine, 0), 0), 0) AS CFineCount,
                        IF((YEAR(p.date) = $previousYear AND MONTH(p.date) = $previousYearMonth), IF(IFNULL(pn.punishmentJailID, 0) > 0, 1, 0), 0) AS PJailCount,
                        IF((YEAR(p.date) = $currentYear AND MONTH(p.date) = $currentYearMonth), IF(IFNULL(pn.punishmentJailID, 0) > 0, 1, 0), 0) AS CJailCount
                    FROM punishments pn
                    JOIN prosecutions p ON p.id = pn.prosecution_id
                    WHERE p.delete_status = 1 AND p.is_approved = 1 AND p.orderSheet_id IS NOT NULL
                    AND pn.criminal_id IS NOT NULL
                    AND (YEAR(p.date) = $previousYear OR YEAR(p.date) = $currentYear)
                    AND (MONTH(p.date) = $previousYearMonth OR MONTH(p.date) = $currentYearMonth)
                ) t3
                GROUP BY divid, zillaId, location_type, LocationID
            ) j3 ON j3.divid = l.Level1IDOld AND j3.zillaId = l.Level2IDOld AND j3.location_type = l.LocationType AND j3.LocationID = l.Level3IDOld ";
            if ($roleID == 34) {
                // Restrict data to the user's division
                $result .= " WHERE l.Level1IDOld = $divisionid";
            }

        
            $results = DB::select(DB::raw( $result));
       
        return $results;
    }


    /**
     * Ajax json returning function
     * Serves Criminal information statistics according to the date range
     * and selected area and user role
     */
    public function ajaxDashboardCriminalInformation(Request $request)
    {
        $requestData = $request->all();
        $request = $requestData['body_data'];
        $roleID = $request['role_id'];
        $office_id = $request['office_id'];

        $childs = array();
         

            list($end_date, $start_date, $divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas) = $this->getAjaxUrlParameters($request);

            list($start_date, $end_date) = $this->checkStartDateAndEndDate($start_date, $end_date);

            if ($roleID == 34) {
                // Divisional Commissioner
                $office_id = $office_id;
                $officeinfo = DB::table('office')->where('id',$office_id)->first();
                // $division_id = $officeinfo->division_id;
            
                list($divid, $block_label) = $this->setBlockLabelForDivisionalCommissioner($officeinfo,$zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas);
                $childs = DashboardCitizenRepository::getCitizenComplainStatistics($divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $end_date, $start_date);

            } elseif ($roleID ==2 || $roleID ==8 || $roleID ==25) {

                $block_label = $this->setBlockLabelForJS($divid,$zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas);
                $childs = DashboardCitizenRepository::getCitizenComplainStatistics($divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $end_date, $start_date);
            }
            

        
        $dates = $this->getBanglaDateRangeFormate($start_date,$end_date);
        // Return JSON response
        return response()->json([$childs, $dates, $block_label]);

    }

    /**
     * Ajax json returning function
     * Serves Case information statistics according to the date range
     * and selected area and user role
     */
    public function ajaxdashboardCaseStatistics(Request $request)
    {
      
     
        $requestData = $request->all();
        $request = $requestData['body_data'];
    

        $roleID = $request['role_id'];
        $office_id = $request['office_id'];
   
        $childs = [];

            list($end_date, $start_date, $divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas) = $this->getAjaxUrlParameters($request);
       
            list($start_date, $end_date) = $this->checkStartDateAndEndDate($start_date, $end_date);
  
           if ($roleID == 34) { //Divisional Commissioner
                $office_id = $office_id;
                $officeinfo = DB::table('office')->where('id',$office_id)->first();
                list($divid, $block_label) = $this->setBlockLabelForDivisionalCommissioner( $officeinfo, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas);
                list($result1, $result2, $result3) = $this->getingQueryResults($divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $start_date, $end_date);
                $childs = $this->setDataToChildArray($result1, $childs, $result2, $result3);
    
            } elseif ($roleID == 2 || $roleID == 8 || $roleID==25) {
                $block_label = $this->setBlockLabelForJS($divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas);
                list($result1, $result2, $result3) = $this->getingQueryResults($divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $start_date, $end_date);
                $childs = $this->setDataToChildArray($result1, $childs, $result2, $result3);
            }
         $dates = $this->getBanglaDateRangeFormate($start_date, $end_date);

        // Return JSON response
        return response()->json([$childs,$dates,$block_label]);
    }

    /**
     * Ajax json returning function
     * Serves Citizen Complain information for graph according to the date range
     * and selected area and user role
     */
    public function ajaxCitizen(Request $request)
    {

        $requestData = $request->all();
        $request = $requestData['body_data'];
        $roleID = $request['role_id'];
        $office_id = $request['office_id'];

        $childs = array();

        list($end_date, $start_date, $divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas) = $this->getAjaxUrlParameters($request);
        list($start_date, $end_date) = $this->setStartDateAndEndDateForGraphSection($start_date, $end_date);

        if ($roleID == 34) {
            $office_id = $office_id;
            $officeinfo = DB::table('office')->where('id',$office_id)->first();
            list($divid, $block_label) = $this->setBlockLabelForDivisionalCommissioner($officeinfo,$zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas);
            $childs = DashboardCitizenRepository::getDataCitizenComplainForCountry($divid,$zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas,$start_date,$end_date );

        } elseif ($roleID == 2 || $roleID == 8 || $roleID == 25) {

            $block_label = $this->setBlockLabelForJS($divid,$zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas);
            $childs = DashboardCitizenRepository::getDataCitizenComplainForCountry($divid,$zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas,$start_date,$end_date );

        }
 

        $dates = $this->getBanglaDateRangeFormate($start_date, $end_date);

        // $this->view->disable();
        // $response = new \Phalcon\Http\Response();
        // $response->setContentType('application/json', 'UTF-8');
        // $response->setContent(json_encode(array($childs, $dates, $block_label)));
        // return $response;
         // Return JSON response
         return response()->json([$childs,$dates,$block_label]);
    }
    /**
     * Ajax json returning function
     * Serves Fine wise case information for graph according to the date range
     * and selected area and user role
     */
    public function ajaxDataFineVSCase(Request $request)
    {
        $requestData = $request->all();
        $request = $requestData['body_data'];
        $roleID = $request['role_id'];
        $office_id = $request['office_id'];


        $childs = array();
       
        // $officeinfo = $;
 
        // if (($this->request->isPost()) && ($this->request->isAjax() == true)) {

            list($end_date, $start_date, $divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas) = $this->getAjaxUrlParameters($request);
        
            list($start_date, $end_date) = $this->setStartDateAndEndDateForGraphSection($start_date, $end_date);
           
            if ($roleID ==34 ) {
                $office_id = $office_id;
                $officeinfo = DB::table('office')->where('id',$office_id)->first();
                list($divid, $block_label) = $this->setBlockLabelForDivisionalCommissioner($officeinfo,$zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas);
                $childs = DashboardFine::getDataFineVSCaseCountry($divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $start_date, $end_date);

             } elseif ($roleID == 2 || $roleID == 8|| $roleID == 25) {

                $block_label = $this->setBlockLabelForJS($divid,$zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas);
                $childs = DashboardFine::getDataFineVSCaseCountry($divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $start_date, $end_date);

            }
        // }
        $dates = $this->getBanglaDateRangeFormate($start_date, $end_date);
        $need_data['childs']=$childs;
        $need_data['dates']=$dates;
        $need_data['block_label']=$block_label;
        return response()->json([$childs,$dates,$block_label]);
        // return ['success' => true, "data" => $need_data];
    }
    /**
     * Ajax json returning function
     * Serves Location wise case information for graph according to the date range
     * and selected area and user role
     */
    public function ajaxDataLocationVSCase(Request $request)
    {
        $requestData = $request->all();
        $request = $requestData['body_data'];
        $roleID = $request['role_id'];
        $office_id = $request['office_id'];

        $childs = array();
 

        list($end_date, $start_date, $divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas) = $this->getAjaxUrlParameters($request);
        list($start_date, $end_date) = $this->setStartDateAndEndDateForGraphSection($start_date, $end_date);

        if ($roleID == 34) {
            // Divisional Commissioner
            $office_id = $office_id;
            $officeinfo = DB::table('office')->where('id',$office_id)->first();
            list($divid, $block_label) = $this->setBlockLabelForDivisionalCommissioner($officeinfo,$zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas);
            $childs = DashboardLocationRepository::getDataLocationVSCaseForDiv($divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas,$start_date,$end_date );

        } elseif ($roleID=2 || $roleID == 8 || $roleID == 25) {

            $block_label = $this->setBlockLabelForJS($divid,$zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas);
            $childs = DashboardLocationRepository::getDataLocationVSCaseForCountry($divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas,$start_date,$end_date );

        }

    
        $dates = $this->getBanglaDateRangeFormate($start_date, $end_date);

        // $this->view->disable();
        // $response = new \Phalcon\Http\Response();
        // $response->setContentType('application/json', 'UTF-8');
        // $response->setContent(json_encode(array($childs, $dates, $block_label)));
        // return $response;
        return response()->json([$childs,$dates,$block_label]);


    }
    /**
     * Ajax json returning function
     * Serves Law wise case information for graph according to the date range
     * and selected area and user role
     */
    public function ajaxDataLawVSCase(Request $request)
    {

        $requestData = $request->all();
        $request = $requestData['body_data'];
        $roleID = $request['role_id'];
        $office_id = $request['office_id'];

        $childs = array();

        list($end_date, $start_date, $divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas) = $this->getAjaxUrlParameters($request);
        list($start_date, $end_date) = $this->setStartDateAndEndDateForGraphSection($start_date, $end_date);

        if ($roleID == 34) {
            // Divisional Commissioner
            $office_id =$office_id;
            $officeinfo = DB::table('office')->where('id',$office_id)->first();
            list($divid, $block_label) = $this->setBlockLabelForDivisionalCommissioner($officeinfo,$zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas);
            $childs = Dashboardlaw::getDataLawVSCaseDiv($divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $start_date, $end_date);

        } elseif ($roleID ==8 || $roleID ==2 || $roleID ==25) {

            $block_label = $this->setBlockLabelForJS($divid,$zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas);
            $childs = Dashboardlaw::getDataLawVSCaseForCountry($divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $start_date, $end_date);

        }

        $dates = $this->getBanglaDateRangeFormate($start_date, $end_date);
        // dd($block_label);
        // $response->setContent(json_encode(array($childs, $dates, $block_label)));
      
        return response()->json(array($childs, $dates, $block_label));

    }


   
    public function checkStartDateAndEndDate($start_date, $end_date)
    {
        if ($start_date == '') {
            $start_date = $this->firstOfMonth();
        }

        if ($end_date == '') {
            $end_date = $this->lastOfMonth();
        }
        return array($start_date, $end_date);
    }

     /**
     * returning the first date of current month
     * @return string
     */
    function firstOfMonth()
    {
        //return date("Y-m-d", strtotime(date('m').'/01/'.date('Y').' 00:00:00'));
        return date('Y-m-01');
    }

    /**
     * returning the last date of current month
     * @return string
     */
    function lastOfMonth()
    {
        //return date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m').'/01/'.date('Y').' 00:00:00'))));
        return date('Y-m-d');
    }

        /**
     * returning the first date of last month
     * @return string
     */
    function firstOfLastMonth()
    {

        $startLastMonth = mktime(0, 0, 0, date("m") - 1, 1, date("Y"));
        $startOutput = date("Y-m-d", $startLastMonth);
        return $startOutput;

    }

    /**
     * returning the first date of last month
     * @return string
     */
    function lastOfLastMonth()
    {
        $endLastMonth = mktime(0, 0, 0, date("m"), 0, date("Y"));
        $endOutput = date("Y-m-d", $endLastMonth);
        return $endOutput;
    }
    /**
     * @param $session_arr
     * @param $upozilaid
     * @return array
     */

     public function setBlockLabelForADM($upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas)
     {
        
        $roleID = globalUserInfo()->role_id;
        $userinfo = globalUserInfo();
        $office_id = $userinfo->office_id;
        $officeinfo = DB::table('office')->where('id', $office_id)->first();
        $division_id = $officeinfo->division_id;
        $district_id = $officeinfo->district_id;
         
         $zillaname =DB::table('district')->where('id',$district_id)->first()->district_name_bn;
         $zillaid = $district_id;
 
         if (($upozilaid == '' and $GeoCityCorporations =='' and ($GeoMetropolitan =='' and $GeoThanas == '') and $zillaid != '')  ) {
             $block_label = $zillaname;
         }elseif($GeoCityCorporations != ''){
             $block_label = DashboardLocationRepository::getGeoCityCorporationsName($GeoCityCorporations);
         } elseif($GeoMetropolitan !='' and $GeoThanas != '') {
             $block_label= DashboardLocationRepository::getGeoThanasName($GeoThanas);
         }else{
             $getLocation = DashboardLocationRepository::getLocationNameAll($division_id, $zillaid, $upozilaid);
             $block_label = $getLocation['upazilal_name'];
         }
         return array($zillaid,$block_label);
     }

         /**
     * @param $zillaid
     * @param $upozilaid
     * @param $start_date
     * @param $end_date
     * @return array
     */
    public function getingQueryResults($divid,$zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $start_date, $end_date)
    {   
      
        $result1 = DashboardCitizenRepository::getCountOfCourtAndCase($divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $start_date, $end_date);
       
        $result2 = DashboardCitizenRepository::getCountOfFineAndCriminal($divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $start_date, $end_date);
        
        $result3 = DashboardCitizenRepository::getCountOfJailCriminalAndMagistrate($divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $start_date, $end_date);
       
        return $da=[ $result1 ,$result2,$result3];
    }

    public function setDataToChildArray($result1, $childs, $result2, $result3)
    {     
         
        // $childs["executed_court"] = $this->bangladate->bangla_number($result1["executed_court"]);
        $childs["executed_court"] = BanglaDate::bangla_number($result1["executed_court"]);
        // $childs["no_case"] = $this->bangladate->bangla_number($result1["no_case"]);
        $childs["no_case"] = BanglaDate::bangla_number($result1["no_case"]);

        // $childs["fine"] = $this->bangladate->bangla_number($result2["fine"]);
        $childs["fine"] = BanglaDate::bangla_number($result2["fine"]);
        // $childs["criminal_no"] = $this->bangladate->bangla_number($result2["criminal_no"]);
        $childs["criminal_no"] = BanglaDate::bangla_number($result2["criminal_no"]);
        $childs["jail_criminal_no"] = BanglaDate::bangla_number($result2["jail_criminal_no"]); 
        // $childs["jail_criminal_no"] = $this->bangladate->bangla_number($result2["jail_criminal_no"]);
        $childs["magistrate_no"] = BanglaDate::bangla_number($result3["magistrate_no"]);
        return $childs;
    }
    public function getAjaxUrlParameters($request)
    {   
          
        
        $end_date = $request['end_date'];
        $start_date = $request['start_date'];
        $divid = $request['divisionid'];
        $zillaid = $request['zillaid'];
        $upozilaid = $request['upozilaid'];
        $GeoCityCorporations = $request['GeoCityCorporations'];
        $GeoMetropolitan = $request['GeoMetropolitan'];
        $GeoThanas =$request['GeoThanas'];
        $reportID = $request['role_id'];
        return [
            $end_date,
            $start_date,
            $divid,
            $zillaid,
            $upozilaid,
            $GeoCityCorporations,
            $GeoMetropolitan,
            $GeoThanas,
        ];
        // return array($end_date, $start_date, $divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $reportID);
    }

    public function getBanglaDateRangeFormate($start_date, $end_date)
    {
        $get_start_date = Carbon::parse($start_date);
        $get_end_date = Carbon::parse($end_date);
        $formatted_start_date = $get_start_date->format('dM Y');
        $formatted_end_date = $get_end_date->format('dM Y');
        $bangla_start_date = BanglaDate::get_bangla_MonthYear($formatted_start_date);
        $bangla_end_date = BanglaDate::get_bangla_MonthYear($formatted_end_date);
        $dates = ['start_date' => $bangla_start_date, 'end_date' => $bangla_end_date];
        return $dates;
    }

    public function setStartDateAndEndDateForGraphSection($start_date, $end_date){
        if ($start_date == '') {
            $start_date = $this->firstOfLastMonth();
        }
        if ($end_date == '') {
            $end_date = $this->lastOfLastMonth();
        }
        return array($start_date, $end_date);
    }

    /**
     * @param $divid
     * @param $upozilaid
     * @param $zillaid
     * @return string
     */
    public function setBlockLabelForJS($divid,$zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas)
    {
        if ($divid == '') {
            $block_label = '৮টি বিভাগ ';
        } elseif ($zillaid == '' and $divid !='' ) {
            $getLocation = $this->getLocationNameAll($divid, $zillaid, $upozilaid);
            $block_label = $getLocation['div_name'];
        } elseif (($upozilaid == '' and $GeoCityCorporations =='' and ($GeoMetropolitan =='' and $GeoThanas == '') and $zillaid != '')  ) {
            $getLocation = $this->getLocationNameAll($divid, $zillaid, $upozilaid);
            $block_label = $getLocation['zilla_name'];
        }elseif($GeoCityCorporations != ''){
            $block_label = DashboardLocationRepository::getGeoCityCorporationsName($GeoCityCorporations);
        } elseif($GeoMetropolitan !='' and $GeoThanas != '') {
            $block_label= DashboardLocationRepository::getGeoThanasName($GeoThanas);
        } else {
            $getLocation = $this->getLocationNameAll($divid, $zillaid, $upozilaid);
            $block_label = $getLocation['upazilal_name'];
        }
        return $block_label;
    }

    public function getLocationNameAll($divid = '', $zillaid = '', $upozillaid = '')
    {
        $div_name = "";
        $zilla_name = "";
        $upazilal_name = "";
        $output[] = array();

        $division = DB::table('division')->where("id",$divid)->get();

        foreach ($division as $temp) {
            $div_name = $temp->division_name_bn;
        }

        if ($zillaid && $divid) {
            $zilla = DB::table('district')->where("id",$zillaid)->where('division_id',$divid)->get();
            foreach ($zilla as $temp) {
                $zilla_name = $temp->district_name_bn;
            }

        }

        if ($zillaid && $divid && $upozillaid) {
            // $upazilla = Upazila :: find("zillaid =" . $zillaid . " AND upazilaid=" . $upozillaid);
            $upazilla = DB::table('upazila')->where("district_id",$zillaid)->where('id',$upozillaid)->get();
            foreach ($upazilla as $temp) {
                $upazilal_name = $temp->upazila_name_bn;
            }
        }

        return array("div_name" => $div_name,
            "zilla_name" => $zilla_name,
            "upazilal_name" => $upazilal_name);
    }

    /**
     * @param $session_arr
     * @param $upozilaid
     * @param $zillaid
     * @return array
     */
    public function setBlockLabelForDivisionalCommissioner($session_arr,$zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas)
    {
    
        // $division_id = $officeinfo->division_id;
        $divid = $session_arr->division_id;
        // return $session_arr;
        if ($zillaid == '' and $divid !='' ) {
            $block_label = $session_arr->div_name_bn;
        } elseif (($upozilaid == '' and $GeoCityCorporations =='' and ($GeoMetropolitan =='' and $GeoThanas == '') and $zillaid != '')  ) {
            $getLocation = $this->getLocationNameAll($divid, $zillaid, $upozilaid);
            $block_label = $getLocation['zilla_name'];
        }elseif($GeoCityCorporations != ''){
            $block_label = DashboardLocationRepository::getGeoCityCorporationsName($GeoCityCorporations);
        } elseif($GeoMetropolitan !='' and $GeoThanas != '') {
            $block_label= DashboardLocationRepository::getGeoThanasName($GeoThanas);
        } else {
            $getLocation = $this->getLocationNameAll($divid, $zillaid, $upozilaid);
            $block_label = $getLocation['upazilal_name'];
        }
        return array($divid, $block_label);
    }
}
