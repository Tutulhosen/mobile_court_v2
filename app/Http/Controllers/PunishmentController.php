<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PunishmentController extends Controller
{
    public function removedCase()
    {
        
        return view('punishment.removedCase');
    }
    public function getAllRemovedCaseBySystem(Request $request)
    {


        $userInfo = Auth::user();


        $result = DB::table('prosecutions AS p')
            ->select(
                'p.case_no AS case_no',
                'lb.Description AS crime_description',
                'p.date AS prosecution_date',
                'p.time AS prosecution_time',
                'p.location AS prosecution_location',
                'd.division_name_bn AS prosecution_division',
                'z.district_name_bn AS prosecution_zilla',
                'u.upazila_name_bn AS prosecution_upazila',
                'gc.city_corporation_name_bng AS prosecution_citycorporation',
                'gm.metropolitan_name_bng AS prosecution_metropolitan',
                DB::raw("CONCAT(p.prosecutor_name, '-', p.prosecutor_details) AS prosecutor_name"),
                DB::raw("GROUP_CONCAT(l.title, ' এর ', s.sec_number, '<hr>') AS law_section_number"),
                'ct.name AS crime_type',
                DB::raw("CASE WHEN p.occurrence_type = 1 THEN ' সংগঠিত ' ELSE 'উৎগঠিত ' END AS occurrence_type"),
                'p.witness1_name',
                'p.witness1_address',
                'p.witness1_age',
                'p.witness1_mobile_no',
                'p.witness1_custodian_name',
                'p.witness1_mother_name',
                'p.witness1_nationalid',
                'p.witness2_name',
                'p.witness2_address',
                'p.witness2_age',
                'p.witness2_mobile_no',
                'p.witness2_custodian_name',
                'p.witness2_mother_name',
                'p.witness2_nationalid',
                'p.jimmader_name',
                'p.jimmader_address',
                'p.jimmader_designation',
                DB::raw("GROUP_CONCAT(cr.name_bng, '<hr>') AS criminal_name"),
                'cr.custodian_name AS criminal_custodian_name',
                'cr.custodian_type AS criminal_custodian_type',
                'cr.mother_name AS criminal_mother_name',
                'cr.age AS criminal_age',
                'cr.gender AS criminal_gender',
                'cr.occupation AS criminal_occupation',
                'cr.present_address AS criminal_present_address',
                'cr.permanent_address AS criminal_permanent_address',
                'cr.national_id AS criminal_national_id',
                'cr.date_of_birth AS criminal_date_of_birth',
                'cr.profession AS criminal_profession',
                'cr.mobile_no AS criminal_mobile_no',
                'cr.email AS criminal_email',
                'cr.organization_name AS criminal_organization_name',
                'cr.trade_no AS criminal_trade_no',
                'cd.division_name_bn AS criminal_division',
                'cz.district_name_bn AS criminal_zilla',
                'cu.upazila_name_bn AS criminal_upazila',
                'cgc.city_corporation_name_bng AS criminal_citycorporation',
                'cgm.metropolitan_name_bng AS criminal_metropolitan'
            )
            ->leftJoin('courts AS c', 'c.id', '=', 'p.court_id')
            ->leftJoin('prosecution_details AS pd', 'pd.prosecution_id', '=', 'p.id')
            ->leftJoin('criminals AS cr', 'pd.criminal_id', '=', 'cr.id')
            ->leftJoin('criminal_confessions AS cf', 'cf.criminal_id', '=', 'cr.id')
            ->leftJoin('laws_brokens AS lb', 'lb.prosecution_id', '=', 'p.id')
            ->leftJoin('punishments AS pn', 'pn.prosecution_id', '=', 'p.id')
            ->leftJoin('division AS d', 'd.id', '=', 'p.divid')
            ->leftJoin('district AS z', function ($join) {
                $join->on('z.id', '=', 'p.zillaId')
                    ->on('z.division_id', '=', 'd.id');
            })
            ->leftJoin('upazila AS u', function ($join) {
                $join->on('u.district_id', '=', 'p.zillaid')
                    ->on('u.id', '=', 'p.upazilaid');
            })
            ->leftJoin('geo_city_corporations AS gc', function ($join) {
                $join->on('gc.geo_division_id', '=', 'p.divid')
                    ->on('gc.geo_district_id', '=', 'p.zillaid')
                    ->on('gc.id', '=', 'p.geo_citycorporation_id');
            })
            ->leftJoin('geo_metropolitan AS gm', function ($join) {
                $join->on('gm.geo_division_id', '=', 'p.divid')
                    ->on('gm.geo_district_id', '=', 'p.zillaid')
                    ->on('gm.id', '=', 'p.geo_metropolitan_id');
            })
            ->leftJoin('division AS cd', 'cd.id', '=', 'cr.divid')
            ->leftJoin('district AS cz', function ($join) {
                $join->on('cz.id', '=', 'cr.zillaId')
                    ->on('cz.division_id', '=', 'cr.divid');
            })
            ->leftJoin('upazila AS cu', function ($join) {
                $join->on('cu.district_id', '=', 'cr.zillaid')
                    ->on('cu.id', '=', 'cr.upazilaid');
            })
            ->leftJoin('geo_city_corporations AS cgc', function ($join) {
                $join->on('cgc.geo_division_id', '=', 'cr.divid')
                    ->on('cgc.geo_district_id', '=', 'cr.zillaid')
                    ->on('cgc.id', '=', 'cr.geo_citycorporation_id');
            })
            ->leftJoin('geo_metropolitan AS cgm', function ($join) {
                $join->on('cgm.geo_division_id', '=', 'cr.divid')
                    ->on('cgm.geo_district_id', '=', 'cr.zillaid')
                    ->on('cgm.id', '=', 'cr.geo_metropolitan_id');
            })
            ->join('mc_law AS l', 'l.id', '=', 'lb.law_id')
            ->join('mc_section AS s', function ($join) {
                $join->on('lb.section_id', '=', 's.id')
                    ->on('l.id', '=', 's.law_id');
            })
            ->leftJoin('case_type AS ct', 'p.case_type1', '=', 'ct.id')
            ->where('p.delete_status', 2)
            // ->where('p.update_by', 'System')
            ->where('p.magistrate_id', $userInfo->id)
            ->groupBy('p.id')
            ->get();
        
        return response()->json(['result' => $result], 200);

        // return view('');
    }
}
