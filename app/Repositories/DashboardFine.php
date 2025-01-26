<?php
 namespace App\Repositories;
use Illuminate\Support\Facades\DB;

class DashboardFine 
{
  //For ADM profile : Fetch a district information
  public static function getDataFineVSCaseZilla($zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $start_date, $end_date)
  {
    
//     // Initialize variables
// $childs = array();
// $join = [];
// $groupBy = '';
// $sWhereTime = '';
// $locationName = '';

// // Add parameters dynamically based on user profile
// if ($upozilaid !== null) {
//     $groupBy = 'upazila.id';
//     $locationName = 'upazila.upazila_name_bn as name';
//     $sWhereTime = "prosecutions.upazilaid = $upozilaid AND prosecutions.date BETWEEN '$start_date' AND '$end_date'";
//     $join[] = ['district', 'prosecutions.zillaId', '=', 'district.id'];
//     $join[] = ['upazila', 'upazila.district_id', '=', 'district.id', 'AND', 'prosecutions.upazilaid', '=', 'upazila.id'];
// } elseif ($GeoCityCorporations !== null) {
//     $locationName = 'cityCorporation.city_corporation_name_bng as name';
//     $sWhereTime = "prosecutions.geo_citycorporation_id = $GeoCityCorporations 
//                    AND prosecutions.date BETWEEN '$start_date' AND '$end_date'";
//     $join[] = ['geo_city_corporations as cityCorporation', 'prosecutions.geo_citycorporation_id', '=', 'cityCorporation.id'];
//     $groupBy = 'cityCorporation.id';
// } elseif ($GeoMetropolitan !== null && $GeoThanas !== null) {
//     $locationName = 'geoThana.thana_name_bng as name';
//     $sWhereTime = "AND prosecutions.geo_metropolitan_id = $GeoMetropolitan 
//                    AND prosecutions.geo_thana_id = $GeoThanas 
//                    AND prosecutions.date BETWEEN '$start_date' AND '$end_date'";
//     $join[] = ['geo_thanas as geoThana', 'prosecutions.geo_thana_id', '=', 'geoThana.id'];
//     $groupBy = 'geoThana.id';
// } else {
//     $locationName = 'upazila.upazila_name_bn as name';
//     $sWhereTime = "prosecutions.date BETWEEN '$start_date' AND '$end_date'";
//     $groupBy = 'upazila.id';
//     $join[] = ['district', 'prosecutions.zillaId', '=', 'district.id'];
//     $join[] = ['upazila', 'upazila.district_id', '=', 'district.id', 'AND', 'prosecutions.upazilaid', '=', 'upazila.id'];
// }

// // Build the query using Laravel's query builder
// $query = DB::table('punishments')
//     ->select(DB::raw("SUM(punishments.fine) AS fine, $locationName"))
//     ->join('prosecutions', 'prosecutions.id', '=', 'punishments.prosecution_id')
//     ->where('prosecutions.zillaId', $zillaid)
//     ->where('punishments.fine', '>', 0)
//     ->where('prosecutions.delete_status', 1)
//     ->where('prosecutions.is_approved', 1)
//     ->whereNotNull('prosecutions.orderSheet_id');

// // Apply joins dynamically using Laravel's `join` method
// foreach ($join as $j) {
//     $query->join($j[0], $j[1], $j[2], $j[3]); // No need for DB::raw()
// }

// // Add the dynamic where condition
// if (!empty($sWhereTime)) {
//     $query->whereRaw($sWhereTime);
// }

// // Group by dynamic fields
// if (!empty($groupBy)) {
//     $query->groupBy(DB::raw($groupBy));
// }

// // Fetch the SQL for debugging
// $info = $query->get()->toArray();

//     dd($info);
    
//       //getting the Prepared DataList from the getPrepareDataList Method As chart required
//       $childs=self::getPrepareDataList($info);
//       return $childs;
$childs = [];
$locationName = '';
$groupBy = '';
$sWhereTime = '';
$join = [];

// Add parameters dynamically based on user profile
if ($upozilaid !== null) {
    // Selected upazila
    $locationName = 'upazila.upazila_name_bn as name';
    $groupBy = 'upazila.id';
    $join[] = ['upazila', 'prosecutions.upazilaid', '=', 'upazila.id'];
    $join[] = ['district', 'prosecutions.zillaId', '=', 'district.id'];
    $sWhereTime = ['prosecutions.upazilaid' => $upozilaid, ['prosecutions.date', '>=', $start_date], ['prosecutions.date', '<=', $end_date]];
} elseif ($GeoCityCorporations !== null) {
    // Selected cityCorporation
    $locationName = 'cityCorporation.city_corporation_name_bng as name';
    $groupBy = 'cityCorporation.id';
    $join[] = ['geo_city_corporations as cityCorporation', 'prosecutions.geo_citycorporation_id', '=', 'cityCorporation.id'];
    $sWhereTime = ['prosecutions.geo_citycorporation_id' => $GeoCityCorporations, ['prosecutions.date', '>=', $start_date], ['prosecutions.date', '<=', $end_date]];
} elseif ($GeoMetropolitan !== null && $GeoThanas !== null) {
    // Selected metropolitan and thana
    $locationName = 'geoThana.thana_name_bng as name';
    $groupBy = 'geoThana.id';
    $join[] = ['geo_thanas as geoThana', 'prosecutions.geo_thana_id', '=', 'geoThana.id'];
    $sWhereTime = [
        'prosecutions.geo_metropolitan_id' => $GeoMetropolitan,
        'prosecutions.geo_thana_id' => $GeoThanas,
        ['prosecutions.date', '>=', $start_date],
        ['prosecutions.date', '<=', $end_date]
    ];
} else {
    // Default (no filters selected)
    $locationName = 'upazila.upazila_name_bn as name';
    $groupBy = 'upazila.id';
    $join[] = ['upazila', 'prosecutions.upazilaid', '=', 'upazila.id'];
    $join[] = ['district', 'prosecutions.zillaId', '=', 'district.id'];
    $sWhereTime = [['prosecutions.date', '>=', $start_date], ['prosecutions.date', '<=', $end_date]];
}

// Build the query using Laravel's Query Builder
$query = DB::table('punishments')
    ->select(DB::raw("SUM(punishments.fine) AS fine, $locationName"))
    ->join('prosecutions', 'prosecutions.id', '=', 'punishments.prosecution_id')
    ->where('prosecutions.zillaId', $zillaid)
    ->where('punishments.fine', '>', 0)
    ->where('prosecutions.delete_status', 1)
    ->where('prosecutions.is_approved', 1)
    ->whereNotNull('prosecutions.orderSheet_id');

// Apply joins dynamically
foreach ($join as $j) {
    $query->join($j[0], $j[1], $j[2], $j[3]);
}

// Apply the dynamic where condition
$query->where($sWhereTime);

// Group by dynamic field
if ($groupBy) {
    $query->groupBy(DB::raw($groupBy));
}

// Execute the query
// $info = $query->get()->toArray();
$info = $query->get()->toArray();

// Return prepared data list
return self::getPrepareDataList($info);

}

public static function getDataFineVSCaseDiv($divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $start_date, $end_date) // Division
{
    $childs = [];
    $sWhereTime = [
        ['prose.delete_status', '=', 1],
        ['prose.is_approved', '=', 1],
        ['prose.orderSheet_id', '!=', null],
        ['prose.date', '>=', $start_date],
        ['prose.date', '<=', $end_date],
    ];
    $locationName = '';
    $groupBy = '';
    $join = [];

    $query = DB::table('punishments as punishment')
        ->selectRaw('SUM(punishment.fine) AS fine');
    // Selected zilla and upazila from the filter box
    if ($zillaid !== null && $upozilaid !== null) {
        $locationName = 'upazila.upazila_name_bn as name';
        $query->where($sWhereTime)
            ->join('prosecutions as prose', 'prose.id', '=', 'punishment.prosecution_id')
            ->where('prose.divid', $divid)
            ->where('prose.zillaId', $zillaid)
            ->where('prose.upazilaid', $upozilaid)
            ->where('punishment.fine', '>', 0)
            ->join('district as zilla', 'prose.zillaId', '=', 'zilla.id')
            ->join('upazila as upazila', function($join) use ($zillaid) {
                $join->on('upazila.district_id', '=', 'zilla.id')
                    ->on('prose.upazilaid', '=', 'upazila.id');
            });
        $groupBy = 'upazila.id';
    }
    // Selected zilla and city corporation from the filter box
    elseif ($zillaid !== null && $GeoCityCorporations !== null) {
        $locationName = 'cityCorporation.city_corporation_name_bng as name';
        $query->where($sWhereTime)
            ->join('prosecutions as prose', 'prose.id', '=', 'punishment.prosecution_id')
            ->where('prose.divid', $divid)
            ->where('prose.zillaId', $zillaid)
            ->where('prose.upazilaid', $upozilaid)
            ->where('punishment.fine', '>', 0)
            ->where('prose.geo_citycorporation_id', $GeoCityCorporations)
            ->join('geo_city_corporations as cityCorporation', 'prose.geo_citycorporation_id', '=', 'cityCorporation.id');
        $groupBy = 'cityCorporation.id';
    }
    // Selected zilla, metropolitan, and thana from the filter box
    elseif ($zillaid !== null && $GeoMetropolitan !== null && $GeoThanas !== null) {
        $locationName = 'geoThana.thana_name_bng as name';
        $query->where($sWhereTime)
            ->join('prosecutions as prose', 'prose.id', '=', 'punishment.prosecution_id')
            ->where('prose.divid', $divid)
            ->where('prose.zillaId', $zillaid)
            ->where('prose.upazilaid', $upozilaid)
            ->where('punishment.fine', '>', 0)
            ->where('prose.geo_metropolitan_id', $GeoMetropolitan)
            ->where('prose.geo_thana_id', $GeoThanas)
            ->join('geo_thanas as geoThana', 'prose.geo_thana_id', '=', 'geoThana.id');
        $groupBy = 'geoThana.id';
    }elseif ($zillaid !== null) {
        // Selected zilla from the filter box
        $locationName = 'upazila.upazila_name_bn as name';
        $query->where($sWhereTime)
            ->join('prosecutions as prose', 'prose.id', '=', 'punishment.prosecution_id')
            ->where('prose.divid', $divid)
            ->where('prose.zillaId', $zillaid)
            ->where('prose.upazilaid', $upozilaid)
            ->where('punishment.fine', '>', 0)
            ->join('district as zilla', 'prose.zillaId', '=', 'zilla.id')
            ->join('upazila as upazila', function($join) use ($zillaid) {
                $join->on('upazila.district_id', '=', 'zilla.id')
                    ->on('prose.upazilaid', '=', 'upazila.id');
            });
        $groupBy = 'upazila.id';
    }

    // Default case (nothing selected, first-time load)
    else {
        $locationName = 'zilla.district_name_bn as name';
        $query->where($sWhereTime)
            ->join('prosecutions as prose', 'prose.id', '=', 'punishment.prosecution_id')
            ->where('prose.divid', $divid)
            ->where('prose.zillaId', $zillaid)
            ->where('prose.upazilaid', $upozilaid)
            ->where('punishment.fine', '>', 0)
            ->join('district as zilla', 'prose.zillaId', '=', 'zilla.id');
        $groupBy = 'zilla.id';
    }

    $query->addSelect(DB::raw($locationName))
            ->groupBy($groupBy);

    // Execute the query
    $info = $query->get();

    $childs = self::getPrepareDataList($info);

    return $childs;
}

////For Cabinet login: Fetch whole country information
public static function getDataFineVSCaseCountry($divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $start_date, $end_date)
{
        $childs = [];
        $sWhereTime = [
            ['prose.delete_status', '=', 1],
            ['prose.is_approved', '=', 1],
            ['prose.orderSheet_id', '!=', null],
            ['prose.date', '>=', $start_date],
            ['prose.date', '<=', $end_date]
        ];

        $locationName = '';
        $groupBy = '';
        $joinTables = [];

        // Building query with dynamic joins and conditions based on selected filters
        $query = DB::table('punishments as punishment')
            ->select(DB::raw('SUM(punishment.fine) AS fine'))
            ->join('prosecutions AS prose', 'prose.id', '=', 'punishment.prosecution_id')
            ->where($sWhereTime)
            ->where('punishment.fine', '>', 0);

        // Adding joins and conditions based on selected filters
        if ($divid !== null && $zillaid !== null && $upozilaid !== null) {
            $locationName = 'upazila.upazila_name_bn AS name';
            $groupBy = 'upazila.id';
            
            $query->join('district AS zilla', 'prose.zillaId', '=', 'zilla.id')
                ->join('upazila AS upazila', function ($join) use ($zillaid, $upozilaid) {
                    $join->on('upazila.district_id', '=', 'zilla.id')
                        ->where('prose.upazilaid', '=', $upozilaid);
                })
                ->where('prose.divid', $divid)
                ->where('prose.zillaId', $zillaid);
            
        } elseif ($divid !== null && $zillaid !== null && $GeoCityCorporations !== null) {
            $locationName = 'cityCorporation.city_corporation_name_bng AS name';
            $groupBy = 'cityCorporation.id';
            
            $query->join('geo_city_corporations AS cityCorporation', 'prose.geo_citycorporation_id', '=', 'cityCorporation.id')
                ->where('prose.divid', $divid)
                ->where('prose.zillaId', $zillaid)
                ->where('prose.geo_citycorporation_id', $GeoCityCorporations);

        } elseif ($divid !== null && $zillaid !== null && $GeoMetropolitan !== null && $GeoThanas !== null) {
            $locationName = 'geoThana.thana_name_bng AS name';
            $groupBy = 'geoThana.id';
            
            $query->join('geo_thanas AS geoThana', 'prose.geo_thana_id', '=', 'geoThana.id')
                ->where('prose.divid', $divid)
                ->where('prose.zillaId', $zillaid)
                ->where('prose.geo_metropolitan_id', $GeoMetropolitan)
                ->where('prose.geo_thana_id', $GeoThanas);

        } elseif ($divid !== null && $zillaid !== null) {
            $locationName = 'upazila.upazila_name_bn AS name';
            $groupBy = 'upazila.id';
            
            $query->join('district AS zilla', 'prose.zillaId', '=', 'zilla.id')
                ->join('upazila AS upazila', function ($join) use ($zillaid) {
                    $join->on('upazila.district_id', '=', 'zilla.id');
                })
                ->where('prose.divid', $divid)
                ->where('prose.zillaId', $zillaid);

        } elseif ($divid !== null) {
            $locationName = 'zilla.district_name_bn AS name';
            $groupBy = 'zilla.id';
            
            $query->join('district AS zilla', 'prose.zillaId', '=', 'zilla.id')
                ->where('prose.divid', $divid);

        } else {
            $locationName = 'division.division_name_bn AS name';
            $groupBy = 'division.id';
            
            $query->join('division AS division', 'prose.divid', '=', 'division.id');
        }

        // Adding groupBy and selecting locationName
        $query->addSelect(DB::raw($locationName))
            ->groupBy($groupBy);

        // Executing the query and processing the results
        $info = $query->get();
        $childs = self::getPrepareDataList($info);

        return $childs;
}
public static function getPrepareDataList($info){
 
    $childs = array();
    if (count($info) > 0) {
        foreach ($info as $t) {
            
            $childs[] = array('location' => $t->name, 'jorimana' =>"".$t->fine."" );
        }
    }else{
        $item = array();
        // $childs[] = array('location' => 'তথ্য নাই', 'jorimana' =>"".$info->fine."" );
        $childs[] = array('location' => 'তথ্য নাই', 'jorimana' => null);
        // $childs = $item;
    }
    return $childs;
}

}