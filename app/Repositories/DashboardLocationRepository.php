<?php


namespace App\Repositories;

use App\Models\Criminal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;



class DashboardLocationRepository
{

     //Prepared Data as chart Required
    public static function getPreparedDataList($info){
        $childs = array();
      
        if (count($info) > 0) {
            foreach ($info as $t) {
                $childs[] = array('location' => $t->name, 'মামলা' => "" . $t->casess . "");
            }
        }
        else{
            $item = array();
            $item[] = array('location' => 'তথ্য নাই', 'মামলা' =>!empty($info->casess)?$info->casess:0);
            $childs = $item;
        }
        return $childs;
    }

    public static function getPreparedDataList_admin($info){
        $childs = array();
      
        if (count($info) > 0) {
            foreach ($info as $t) {
                $childs[] = array('location' => $t->name, 'মামলা' => "" . $t->cases . "");
            }
        }
        else{
            $item = array();
            $item[] = array('location' => 'তথ্য নাই', 'মামলা' =>!empty($info->cases)?$info->cases:0);
            $childs = $item;
        }
        return $childs;
    }
    
    public static  function getGeoCityCorporationsName($geoCityCorporationID){
        $info = DB::table('geo_city_corporations')
        ->select('city_corporation_name_bng')
        ->where('id', $geoCityCorporationID)
        ->first();
        
        return $info ? $info->city_corporation_name_bng : 0;
    }

    public static function getLocationNameAll($divid = '', $zillaid = '', $upozillaid = '')
    {

        $div_name = "";
        $zilla_name = "";
        $upazilal_name = "";
    
        // Fetch division name
        if ($divid) {
            $division = DB::table('division')->where('id', $divid)->first();
            if ($division) {
                $div_name = $division->division_name_bn;
            }
        }
    
        // Fetch zilla name if both zillaid and divid are provided
        if ($zillaid && $divid) {
            $zilla = DB::table('district')->where('id', $zillaid)
                          ->where('division_id', $divid)
                          ->first();
            if ($zilla) {
                $zilla_name = $zilla->district_name_bn;
            }
        }
    
        // Fetch upazila name if zillaid, divid, and upozillaid are provided
        if ($zillaid && $divid && $upozillaid) {
            $upazila = DB::table('upazila')->where('district_id', $zillaid)
                              ->where('id', $upozillaid)
                              ->first();
            if ($upazila) {
                $upazilal_name = $upazila->upazila_name_bn;
            }
        }
    
        return [
            "div_name" => $div_name,
            "zilla_name" => $zilla_name,
            "upazilal_name" => $upazilal_name
        ];
    }

    public static function getGeoThanasName($geoThanasID){
        $info =DB::table('geo_thanas')->where('id', $geoThanasID)->get(['thana_name_bng']);
        $data = '';
        foreach ($info as $t) {
            $data = $t->thana_name_bng;
        }

        return $data;
    }

    //For ADM login :Fetch a Zilla information
    public static function getDataLocationVSCaseForZilla($zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas,$start_date,$end_date )
    {
       
        $query = DB::table('prosecutions as prose')
        ->join('district', 'prose.zillaId', '=', 'district.id')
        ->where('prose.case_type1', '!=', '0')
        ->where('prose.delete_status', 1)
        ->where('prose.is_approved', 1)
        ->where('prose.orderSheet_id', '!=', null)
        ->where('prose.zillaId', $zillaid)
        ->whereBetween('prose.date', [$start_date, $end_date]);

    $groupBy = '';
    $locationName = '';
     
    // Case 1: Filter by Upazila
    if ($upozilaid != null) {
        $query->where('prose.upazilaid', $upozilaid);
        $query->join('upazila as u', function($join) {
            $join->on('u.district_id', '=', 'prose.zillaid')
                 ->on('u.id', '=', 'prose.upazilaid');
        });
        $locationName = 'u.upazila_name_bn as name';
        $groupBy = 'u.id';
    }
    // Case 2: Filter by City Corporation
    elseif ($GeoCityCorporations != null) {
        $query->where('prose.geo_citycorporation_id', $GeoCityCorporations);
        $query->join('geo_city_corporations as cityCorporation', 'prose.geo_citycorporation_id', '=', 'cityCorporation.id');
        $locationName = 'cityCorporation.city_corporation_name_bng as name';
        $groupBy = 'cityCorporation.id';
    }
    // Case 3: Filter by Metropolitan and Thana
    elseif ($GeoMetropolitan != null && $GeoThanas != null) {
        $query->where('prose.geo_metropolitan_id', $GeoMetropolitan)
              ->where('prose.geo_thana_id', $GeoThanas);
        $query->join('geo_thanas as geoThana', 'prose.geo_thana_id', '=', 'geoThana.id');
        $locationName = 'geoThana.thana_name_bng as name';
        $groupBy = 'geoThana.id';
    }
    // Default: No filters selected
    else {
        $query->join('upazila as u', function($join) {
            $join->on('u.district_id', '=', 'prose.zillaid')
                 ->on('u.id', '=', 'prose.upazilaid');
        });
        $locationName = 'u.upazila_name_bn as name';
        $groupBy = 'u.id';
    }

    $query->select(DB::raw("COUNT(prose.id) AS casess, $locationName"))
          ->groupBy($groupBy);

    // Execute the query and get the result
    $info = $query->get();
    //    dd($info);
    // Call the helper function to prepare the final data list
    return self::getPreparedDataList($info);
    }


    //For Divisional login: Fetch a division information
    public static function getDataLocationVSCaseForDiv($divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas,$start_date,$end_date ) // Division
    {
            $query = DB::table('prosecutions AS prose')
            ->selectRaw('COUNT(prose.id) AS casess')
            ->join('district AS zilla', 'prose.zillaId', '=', 'zilla.id')
            ->where('prose.case_type1', '!=', '0')
            ->where('prose.divid', '=', $divid)
            ->where('prose.delete_status', '=', 1)
            ->where('prose.is_approved', '=', 1)
            ->whereNotNull('prose.orderSheet_id')
            ->whereBetween('prose.date', [$start_date, $end_date]);

        $locationName = '';
        $groupBy = '';

        // Selected zilla and upazilla
        if ($zillaid !== null && $upozilaid !== null) {
            $query->join('upazila AS u', function ($join) use ($zillaid, $upozilaid) {
                $join->on('u.district_id', '=', 'prose.zillaId')
                    ->where('u.id', '=', $upozilaid);
            });
            $query->selectRaw('u.upazila_name_bn as name');
            $query->where('prose.zillaId', '=', $zillaid);
            $groupBy = 'u.id';
        }
        // Selected zilla and city corporation
        elseif ($zillaid !== null && $GeoCityCorporations !== null) {
            $query->join('geo_city_corporations AS cityCorporation', 'prose.geo_citycorporation_id', '=', 'cityCorporation.id');
            $query->selectRaw('cityCorporation.city_corporation_name_bng as name');
            $query->where('prose.zillaId', '=', $zillaid)
                ->where('prose.geo_citycorporation_id', '=', $GeoCityCorporations);
            $groupBy = 'cityCorporation.id';
        }
        // Selected zilla, metropolitan, and thana
        elseif ($zillaid !== null && $GeoMetropolitan !== null && $GeoThanas !== null) {
            $query->join('geo_thanas AS geoThana', 'prose.geo_thana_id', '=', 'geoThana.id');
            $query->selectRaw('geoThana.thana_name_bng as name');
            $query->where('prose.zillaId', '=', $zillaid)
                ->where('prose.geo_metropolitan_id', '=', $GeoMetropolitan)
                ->where('prose.geo_thana_id', '=', $GeoThanas);
            $groupBy = 'geoThana.id';
        }
        // Selected only zilla
        elseif ($zillaid !== null) {
            $query->join('upazila AS u', 'u.district_id', '=', 'prose.zillaId');
            $query->selectRaw('u.upazila_name_bn as name');
            $query->where('prose.zillaId', '=', $zillaid);
            $groupBy = 'u.id';
        }
        // Selected nothing (Default)
        else {
            $query->selectRaw('zilla.district_name_bn as name');
            $groupBy = 'zilla.id';
        }

        // Group by the dynamically set field
        if ($groupBy) {
            $query->groupBy($groupBy);
        }

        // Execute the query and fetch results
        $info = $query->get();

        // Process the data for chart requirements (assuming `getPreparedDataList` is defined elsewhere)
        $childs = self::getPreparedDataList($info);
        return $childs;

    }

    //For Cabinet login: Fetch whole country information
    public static function getDataLocationVSCaseForCountry_old($divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas,$start_date,$end_date )
    {
        $childs = [];
        $sWhereTime = [
            ['prosecution.delete_status', '=', 1],
            ['prosecution.is_approved', '=', 1],
            ['prosecution.orderSheet_id', '!=', null],
            ['prosecution.date', '>=', $start_date],
            ['prosecution.date', '<=', $end_date],
        ];
        
        $locationName = '';
        $groupBy = '';
        $join = '';
        $geoClause = 'JOIN upazila AS u ON u.district_id = prosecution.zillaId AND u.id = prosecution.upazilaid';
        
        $query = DB::table('prosecutions AS prosecution')
            ->selectRaw('COUNT(prosecution.id) AS cases')
            ->addSelect(DB::raw($locationName))
            ->join('division AS division', 'division.id', '=', 'prosecution.divid')
            ->leftJoin('district AS zilla', 'prosecution.zillaId', '=', 'zilla.id');
        
        if ($divid !== null && $zillaid !== null && $upozilaid !== null) {
            $locationName = 'u.upazila_name_bn AS name';
            $query->where($sWhereTime)
                ->where('prosecution.divid', $divid)
                ->where('prosecution.zillaId', $zillaid)
                ->where('prosecution.upazilaid', $upozilaid)
                ->groupBy('u.id');
        }
        
        elseif ($divid !== null && $zillaid !== null && $GeoCityCorporations !== null) {
            $locationName = 'cityCorporation.city_corporation_name_bng AS name';
            $query->join('geo_city_corporations AS cityCorporation', 'prosecution.geo_citycorporation_id', '=', 'cityCorporation.id')
                ->where($sWhereTime)
                ->where('prosecution.divid', $divid)
                ->where('prosecution.zillaId', $zillaid)
                ->where('prosecution.geo_citycorporation_id', $GeoCityCorporations)
                ->groupBy('cityCorporation.id');
            $geoClause = null;
        }
        
        elseif ($divid !== null && $zillaid !== null && $GeoMetropolitan !== null && $GeoThanas !== null) {
            $locationName = 'geoThana.thana_name_bng AS name';
            $query->join('geo_thanas AS geoThana', 'prosecution.geo_thana_id', '=', 'geoThana.id')
                ->where($sWhereTime)
                ->where('prosecution.divid', $divid)
                ->where('prosecution.zillaId', $zillaid)
                ->where('prosecution.geo_metropolitan_id', $GeoMetropolitan)
                ->where('prosecution.geo_thana_id', $GeoThanas)
                ->groupBy('geoThana.id');
            $geoClause = null;
        }
        
        elseif ($divid !== null && $zillaid !== null) {
            $locationName = 'u.upazila_name_bn AS name';
            $query->where($sWhereTime)
                ->where('prosecution.divid', $divid)
                ->where('prosecution.zillaId', $zillaid)
                ->groupBy('u.id');
        }
        
        elseif ($divid !== null) {
            $locationName = 'zilla.district_name_bn AS name';
            $query->where($sWhereTime)
                ->where('prosecution.divid', $divid)
                ->groupBy('zilla.id');
            $geoClause = null;
        }
        
        else {
            $locationName = 'division.division_name_bn AS name';
            $query->where($sWhereTime)
                ->groupBy('division.id');
            $geoClause = null;
        }
        
        $query->addSelect(DB::raw($locationName));
        $query->join(DB::raw($geoClause), function($join) use ($geoClause) {
            // if geoClause is not null, add it to the query
            if (!empty($geoClause)) {
                $join->whereRaw($geoClause);
            }
        });
        
        $info = $query->get();
        
        $childs = self::getPreparedDataList($info);
        
        return $childs;

    }
    public static function getDataLocationVSCaseForCountry($divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $start_date, $end_date)
    {
        $childs = [];
        $sWhereTime = [
            ['prosecution.delete_status', '=', 1],
            ['prosecution.is_approved', '=', 1],
            ['prosecution.orderSheet_id', '!=', null],
            ['prosecution.date', '>=', $start_date],
            ['prosecution.date', '<=', $end_date],
        ];

        $locationName = null;

        $query = DB::table('prosecutions AS prosecution')
            ->selectRaw('COUNT(prosecution.id) AS cases')
            ->join('division AS division', 'division.id', '=', 'prosecution.divid')
            ->leftJoin('district AS zilla', 'prosecution.zillaId', '=', 'zilla.id');

        if ($divid !== null && $zillaid !== null && $upozilaid !== null) {
            $locationName = 'u.upazila_name_bn AS name';
            $query->join('upazila AS u', 'u.id', '=', 'prosecution.upazilaid')
                ->where($sWhereTime)
                ->where('prosecution.divid', $divid)
                ->where('prosecution.zillaId', $zillaid)
                ->where('prosecution.upazilaid', $upozilaid)
                ->groupBy('u.id');
        } elseif ($divid !== null && $zillaid !== null && $GeoCityCorporations !== null) {
            $locationName = 'cityCorporation.city_corporation_name_bng AS name';
            $query->join('geo_city_corporations AS cityCorporation', 'prosecution.geo_citycorporation_id', '=', 'cityCorporation.id')
                ->where($sWhereTime)
                ->where('prosecution.divid', $divid)
                ->where('prosecution.zillaId', $zillaid)
                ->where('prosecution.geo_citycorporation_id', $GeoCityCorporations)
                ->groupBy('cityCorporation.id');
        } elseif ($divid !== null && $zillaid !== null && $GeoMetropolitan !== null && $GeoThanas !== null) {
            $locationName = 'geoThana.thana_name_bng AS name';
            $query->join('geo_thanas AS geoThana', 'prosecution.geo_thana_id', '=', 'geoThana.id')
                ->where($sWhereTime)
                ->where('prosecution.divid', $divid)
                ->where('prosecution.zillaId', $zillaid)
                ->where('prosecution.geo_metropolitan_id', $GeoMetropolitan)
                ->where('prosecution.geo_thana_id', $GeoThanas)
                ->groupBy('geoThana.id');
        } elseif ($divid !== null && $zillaid !== null) {
            $locationName = 'u.upazila_name_bn AS name';
            $query->join('upazila AS u', 'u.id', '=', 'prosecution.upazilaid')
                ->where($sWhereTime)
                ->where('prosecution.divid', $divid)
                ->where('prosecution.zillaId', $zillaid)
                ->groupBy('u.id');
        } elseif ($divid !== null) {
            $locationName = 'zilla.district_name_bn AS name';
            $query->where($sWhereTime)
                ->where('prosecution.divid', $divid)
                ->groupBy('zilla.id');
        } else {
            $locationName = 'division.division_name_bn AS name';
            $query->where($sWhereTime)
                ->groupBy('division.id');
        }

        if ($locationName) {
            $query->addSelect(DB::raw($locationName));
        }

        $info = $query->get();
        $childs = self::getPreparedDataList_admin($info);

        return $childs;
    }

}