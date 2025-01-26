<?php
 namespace App\Repositories;
use Illuminate\Support\Facades\DB;

class Dashboardlaw 
{
   public static function getDataLawVSCaseZilla($zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $start_date, $end_date)
    {
        
      // Initialize the query with static conditions
    $query = DB::table('prosecutions as p')
    ->join('case_type as ct', 'ct.id', '=', 'p.case_type1')
    ->where('p.delete_status', 1)
    ->where('p.is_approved', 1)
    ->whereNotNull('p.orderSheet_id')
    ->where('p.case_type1', '!=', '0')
    ->whereBetween('p.date', [$start_date, $end_date]);

$locationName = '';
$groupByProse = '';

// Add dynamic joins and conditions based on user profile filters
if ($upozilaid != null) {
    // If Upazila is selected
    $query->join('upazila as u', function ($join) {
        $join->on('u.district_id', '=', 'p.zillaid')
             ->on('u.id', '=', 'p.upazilaid');
    });
    $locationName = 'u.upazila_name_bn';
    $query->where('p.upazilaid', $upozilaid);
    $groupByProse = 'p.upazilaid';
} elseif ($GeoCityCorporations != null) {
    // If City Corporation is selected
    $query->join('geo_city_corporations as cityCorporation', 'p.geo_citycorporation_id', '=', 'cityCorporation.id');
    $locationName = 'cityCorporation.city_corporation_name_bng';
    $query->where('p.geo_citycorporation_id', $GeoCityCorporations);
    $groupByProse = 'p.geo_citycorporation_id';
} elseif ($GeoMetropolitan != null && $GeoThanas != null) {
    // If Metropolitan and Thana are selected
    $query->join('geo_thanas as geoThana', 'p.geo_thana_id', '=', 'geoThana.id');
    $locationName = 'geoThana.thana_name_bng';
    $query->where('p.geo_metropolitan_id', $GeoMetropolitan)
          ->where('p.geo_thana_id', $GeoThanas);
    $groupByProse = 'p.geo_thana_id';
} else {
    // Default case when nothing is selected
    $query->join('upazila as u', function ($join) {
        $join->on('u.district_id', '=', 'p.zillaid')
             ->on('u.id', '=', 'p.upazilaid');
    });
    $locationName = 'u.upazila_name_bn';
    $groupByProse = 'p.upazilaid';
}

// Select query with dynamic location name and grouping
$query->select(DB::raw("ct.name AS crime, COUNT(p.id) AS caseCount, $locationName AS Lname"))
      ->where('p.zillaid', $zillaid)
      ->groupBy('p.case_type1', $groupByProse);

// Fetch the results
$result = $query->get();

// Reformat the result to match the expected output structure
$formattedResult = $result->map(function ($item) {
    return [
        0 => $item->crime,
        1 => $item->caseCount,
        2 => $item->Lname,
        'crime' => $item->crime,
        'caseCount' => $item->caseCount,
        'Lname' => $item->Lname,
    ];
});

return $formattedResult;


    }

    //For divisional commissioner profile : Fetch a division information
    public static function getDataLawVSCaseDiv($divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $start_date, $end_date)
    {
        $query = DB::table('prosecutions as p')
        ->select(DB::raw('ct.name AS crime, COUNT(p.id) AS caseCount'))
        ->join('case_type as ct', 'ct.id', '=', 'p.case_type1')
        ->where('p.divid', $divid)
        ->where('p.delete_status', 1)
        ->where('p.is_approved', 1)
        ->whereNotNull('p.orderSheet_id')
        ->where('p.case_type1', '!=', '0')
        ->whereBetween('p.date', [$start_date, $end_date]);

    // Initialize dynamic variables
    $locationName = '';
    $groupByProse = '';

    // Conditions for dynamic joins and filters
    if (!is_null($zillaid) && !is_null($upozilaid)) {
        $locationName = 'u.upazila_name_bn as Lname';
        $query->join('upazila as u', function ($join) use ($zillaid, $upozilaid) {
            $join->on('u.district_id', '=', 'p.zillaId')
                 ->on('u.id', '=', 'p.upazilaid')
                 ->where('p.zillaId', $zillaid)
                 ->where('p.upazilaid', $upozilaid);
        });
        $groupByProse = 'p.upazilaid';
    } elseif (!is_null($zillaid) && !is_null($GeoCityCorporations)) {
        $locationName = 'cityCorporation.city_corporation_name_bng as Lname';
        $query->join('geo_city_corporations as cityCorporation', 'p.geo_citycorporation_id', '=', 'cityCorporation.id')
            ->where('p.zillaId', $zillaid)
            ->where('p.geo_citycorporation_id', $GeoCityCorporations);
        $groupByProse = 'p.geo_citycorporation_id';
    } elseif (!is_null($zillaid) && !is_null($GeoMetropolitan) && !is_null($GeoThanas)) {
        $locationName = 'geoThana.thana_name_bng as Lname';
        $query->join('geo_thanas as geoThana', 'p.geo_thana_id', '=', 'geoThana.id')
            ->where('p.zillaId', $zillaid)
            ->where('p.geo_metropolitan_id', $GeoMetropolitan)
            ->where('p.geo_thana_id', $GeoThanas);
        $groupByProse = 'p.geo_thana_id';
    } elseif (!is_null($zillaid)) {
        $locationName = 'u.upazila_name_bn as Lname';
        $query->join('upazila as u', function ($join) use ($zillaid) {
            $join->on('u.district_id', '=', 'p.zillaId')
                 ->on('u.id', '=', 'p.upazilaid')
                 ->where('p.zillaId', $zillaid);
        });
        $groupByProse = 'p.upazilaid';
    } else {
        $locationName = 'z.district_name_bn as Lname';
        $query->join('district as z', 'z.id', '=', 'p.zillaId');
        $groupByProse = 'p.zillaId';
    }

    // Add the selected location name and group by clauses
    $query->addSelect(DB::raw($locationName))
          ->groupBy('p.case_type1', $groupByProse);

    $result = $query->get();
    return $result;
    }

    ////For Cabinet login: Fetch whole country information
    public static function getDataLawVSCaseForCountry($divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $start_date, $end_date)
    {

        $locationName = '';
        $join = '';
        $sWhere = '';
        $groupByProse = '';

        // Start building the base query
        $query = DB::table('prosecutions as p')
            ->join('case_type as ct', 'ct.id', '=', 'p.case_type1')
            ->selectRaw('ct.name AS crime, COUNT(p.id) AS caseCount');

        // Add filtering conditions based on user profile
        if ($divid && $zillaid && $upozilaid) {
            $locationName = 'u.upazila_name_bn as Lname';
            $query->where([
                ['p.divid', '=', $divid],
                ['p.zillaId', '=', $zillaid],
                ['p.upazilaid', '=', $upozilaid]
            ]);
            $groupByProse = 'p.upazilaid';
            $query->join('upazila as u', function ($join) {
                $join->on('u.district_id', '=', 'p.zillaId')
                    ->on('u.id', '=', 'p.upazilaid');
            });
        } elseif ($divid && $zillaid && $GeoCityCorporations) {
            $locationName = 'cityCorporation.city_corporation_name_bng as Lname';
            $query->where([
                ['p.divid', '=', $divid],
                ['p.zillaId', '=', $zillaid],
                ['p.geo_citycorporation_id', '=', $GeoCityCorporations]
            ]);
            $groupByProse = 'p.geo_citycorporation_id';
            $query->join('geo_city_corporations as cityCorporation', 'p.geo_citycorporation_id', '=', 'cityCorporation.id');
        } elseif ($divid && $zillaid && $GeoMetropolitan && $GeoThanas) {
            $locationName = 'geoThana.thana_name_bng as Lname';
            $query->where([
                ['p.divid', '=', $divid],
                ['p.zillaId', '=', $zillaid],
                ['p.geo_metropolitan_id', '=', $GeoMetropolitan],
                ['p.geo_thana_id', '=', $GeoThanas]
            ]);
            $groupByProse = 'p.geo_thana_id';
            $query->join('geo_thanas as geoThana', 'p.geo_thana_id', '=', 'geoThana.id');
        } elseif ($divid && $zillaid) {
            $locationName = 'u.upazila_name_bn as Lname';
            $query->where([
                ['p.divid', '=', $divid],
                ['p.zillaId', '=', $zillaid]
            ]);
            $groupByProse = 'p.upazilaid';
            $query->join('upazila as u', function ($join) {
                $join->on('u.district_id', '=', 'p.zillaId')
                    ->on('u.id', '=', 'p.upazilaid');
            });
        } elseif ($divid) {
            $locationName = 'z.district_name_bn as Lname';
            $query->where('p.divid', $divid);
            $groupByProse = 'p.zillaId';
            $query->join('zilla as z', 'z.id', '=', 'p.zillaiId');
        } else {
            $locationName = 'd.division_name_bn as Lname';
            $groupByProse = 'p.divid';
            $query->join('division as d', 'd.id', '=', 'p.divid');
        }

        // Add other fixed conditions
        $query->where([
            ['p.delete_status', '=', 1],
            ['p.is_approved', '=', 1],
            ['p.orderSheet_id', '!=', null],
            ['p.case_type1', '!=', '0'],
            ['p.date', '>=', $start_date],
            ['p.date', '<=', $end_date]
        ]);

        // Add the grouping and select the dynamic location name
        $query->selectRaw("$locationName")
            ->groupBy('p.case_type1', $groupByProse);

        // Get the results
       return  $result = $query->get();
    }
}