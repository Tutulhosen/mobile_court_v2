<?php


namespace App\Repositories;

use App\Models\Criminal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Repositories\ComplainStatus;
use App\Repositories\BanglaDate;


class DashboardCitizenRepository
{

    /**
     * Returns the court_count and case_count for given criteria
     * @param $divid
     * @param $zillaId
     * @param $upozilaid
     * @param $start_date
     * @param $end_date
     * @return mixed
     */
    public static function getCountOfCourtAndCase($divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $start_date, $end_date){
          // Build the case count query
 
                $query = DB::table('prosecutions as p')
                ->leftJoin('upazila as u', function($join) {
                    $join->on('u.district_id', '=', 'p.zillaId')
                        ->on('u.id', '=', 'p.upazilaid');
                })
                ->leftJoin('geo_city_corporations as c', function($join) {
                    $join->on('c.geo_division_id', '=', 'p.divid')
                        ->on('c.geo_division_id', '=', 'p.zillaId')
                        ->on('c.id', '=', 'p.geo_citycorporation_id');
                })
                ->leftJoin('geo_metropolitan as m', function($join) {
                    $join->on('m.geo_division_id', '=', 'p.divid')
                        ->on('m.geo_district_id', '=', 'p.zillaId')
                        ->on('m.id', '=', 'p.geo_metropolitan_id');
                })
                ->where('p.delete_status', 1)
                ->where('p.is_approved', 1)
                ->whereNotNull('p.orderSheet_id')
                ->whereBetween('p.date', [$start_date, $end_date]);

            // Apply dynamic where conditions
            if ($divid != '') {
                $query->where('p.divid', $divid);
            }

            if ($zillaid != '') {
                $query->where('p.zillaId', $zillaid);
            }

            if ($upozilaid != '') {
                $query->where('p.upazilaid', $upozilaid);
            }

            if ($GeoCityCorporations != '') {
                $query->where('p.geo_citycorporation_id', $GeoCityCorporations);
            }

            if ($GeoMetropolitan != '' && $GeoThanas != '') {
                $query->where('p.geo_metropolitan_id', $GeoMetropolitan)
                    ->where('p.geo_thana_id', $GeoThanas);
            }

              $caseCount = $query
                ->select(DB::raw('SUM(IF(p.date BETWEEN "'.$start_date.'" AND "'.$end_date.'", 1, 0)) AS CCaseCount'))
                ->get();
            
             $no_case_dc = $caseCount[0]->CCaseCount;
            


             $whereClause = [];

             // Dynamic where clause conditions
              
 
             // Main query to calculate total court count
             $query1= DB::table('prosecutions AS p')
                 ->join('courts AS c', 'c.id', '=', 'p.court_id')
                 ->leftJoin('upazila AS u', function ($join) {
                     $join->on('u.district_id', '=', 'p.zillaId')
                         ->on('u.id', '=', 'p.upazilaid');
                 })
                 ->leftJoin('geo_city_corporations AS gc', function ($join) {
                     $join->on('gc.geo_division_id', '=', 'p.divid')
                         ->on('gc.geo_district_id', '=', 'p.zillaId')
                         ->on('gc.id', '=', 'p.geo_citycorporation_id');
                 })
                 ->leftJoin('geo_metropolitan AS m', function ($join) {
                     $join->on('m.geo_division_id', '=', 'p.divid')
                         ->on('m.geo_district_id', '=', 'p.zillaId')
                         ->on('m.id', '=', 'p.geo_metropolitan_id');
                 })
                 ->select(
                     DB::raw('IFNULL(SUM(IF(p.date BETWEEN "'.$start_date.'" AND "'.$end_date.'", 1, 0)), 0) AS court'))
                 ->where('p.delete_status', 1)
                 ->where('c.status', 2)
                 ->where('c.delete_status', 1)
                 ->whereBetween('c.date', [$start_date, $end_date]);
                 // Apply dynamic where conditions
                if ($divid != '') {
                    $query1->where('p.divid', $divid);
                }

                if ($zillaid != '') {
                    $query1->where('p.zillaId', $zillaid);
                }

                if ($upozilaid != '') {
                    $query1->where('p.upazilaid', $upozilaid);
                }

                if ($GeoCityCorporations != '') {
                    $query1->where('p.geo_citycorporation_id', $GeoCityCorporations);
                }

                if ($GeoMetropolitan != '' && $GeoThanas != '') {
                    $query1->where('p.geo_metropolitan_id', $GeoMetropolitan)
                        ->where('p.geo_thana_id', $GeoThanas);
                }
                 $courtCount =  $query1->groupBy('p.divid')
                 ->groupBy('p.zillaId')
                 ->groupBy('p.location_type')
                 ->groupBy('c.date')
                 ->groupBy('p.court_id')
                 ->get();
           
             //Check if the result exists and has the expected property
             $executed_court_dc = $courtCount[0]->court ?? 0; // Default to 0 if not found
            
            // Return the results as an array
            $dafasdf=[
                "executed_court" => $executed_court_dc,
                "no_case" => $no_case_dc
            ];
            return $dafasdf;
    }

    /**
     * Returns the criminal no and fine for given criteria
     * @param $divid
     * @param $zillaId
     * @param $upozilaid
     * @param $start_date
     * @param $end_date
     * @return mixed
     */
    public static function getCountOfFineAndCriminal($divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $start_date, $end_date){
    // Step 1: Build the subquery separately and pass the actual date values directly
    $whereClause = [];

    // Dynamic where clause conditions
    if ($divid != '') {
        $whereClause[] = ['p.divid', '=', $divid];
    }
    
    if ($zillaid != '') {
        $whereClause[] = ['p.zillaId', '=', $zillaid];
    }
    
    if ($upozilaid != '') {
        $whereClause[] = ['p.upazilaid', '=', $upozilaid];
    }
    
    if ($GeoCityCorporations != '') {
        $whereClause[] = ['p.geo_citycorporation_id', '=', $GeoCityCorporations];
    }
    
    if ($GeoMetropolitan != '' && $GeoThanas != '') {
        $whereClause[] = ['p.geo_metropolitan_id', '=', $GeoMetropolitan];
        $whereClause[] = ['p.geo_thana_id', '=', $GeoThanas];
    }
    
    $results = DB::table('punishments AS pn')
        ->join('prosecutions AS p', 'p.id', '=', 'pn.prosecution_id')
        ->leftJoin('upazila AS u', function ($join) {
            $join->on('u.district_id', '=', 'p.zillaId')
                 ->on('u.id', '=', 'p.upazilaid');
        })
        ->leftJoin('geo_city_corporations AS c', function ($join) {
            $join->on('c.geo_division_id', '=', 'p.divid')
                 ->on('c.geo_district_id', '=', 'p.zillaId')
                 ->on('c.id', '=', 'p.geo_citycorporation_id');
        })
        ->leftJoin('geo_metropolitan AS m', function ($join) {
            $join->on('m.geo_division_id', '=', 'p.divid')
                 ->on('m.geo_district_id', '=', 'p.zillaId')
                 ->on('m.id', '=', 'p.geo_metropolitan_id');
        })
        ->selectRaw('
            IFNULL(SUM(IF((p.date BETWEEN ? AND ?), 1, 0)), 0) AS criminal,
            IFNULL(SUM(IF((p.date BETWEEN ? AND ?), IF(LENGTH(TRIM(IFNULL(pn.receipt_no, ""))) > 0, IFNULL(pn.fine, 0), 0), 0)), 0) AS fine,
            IFNULL(SUM(IF((p.date BETWEEN ? AND ?), IF(IFNULL(pn.punishmentJailID, 0) > 0, 1, 0), 0)), 0) AS jail_criminal', 
            [$start_date, $end_date, $start_date, $end_date, $start_date, $end_date]
        )
        ->where('p.delete_status', 1)
        ->where('p.is_approved', 1)
        ->where('p.orderSheet_id', '!=', null)
        ->whereNotNull('pn.criminal_id')
        ->whereBetween('p.date', [$start_date, $end_date])
        ->where($whereClause)
        ->first();
    
    $fine_dc = $results->fine;
    $criminal_no_dc = $results->criminal;
    $jail_criminal_no_dc = $results->jail_criminal;

    return (array("fine" => $fine_dc,
            "criminal_no" => $criminal_no_dc,
            "jail_criminal_no" => $jail_criminal_no_dc));


    
    }

    /**
     * Returns the count of prosecutor for given criteria
     * @param $divid
     * @param $zillaId
     * @param $upozilaid
     * @param $start_date
     * @param $end_date
     * @return mixed
     */
    public static function getCountOfProsecutor($divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $start_date, $end_date){

        //total prosecutor
        // Start the query using the Query Builder

        $query = DB::table('users AS pro')
        ->join('doptor_user_access_info', 'pro.id', '=','doptor_user_access_info.user_id')
        ->join('office as  of', 'pro.office_id', '=', 'of.id')
        ->selectRaw('COUNT(pro.id) AS prosecutor')
        ->where('doptor_user_access_info.role_id',25)
        ->where('doptor_user_access_info.court_type_id',1);
        // Dynamically add conditions based on user profile
        if ($divid != '') {
            $query->where('of.division_id', $divid);
        }

        if ($zillaid != '') {
            $query->where('of.district_id', $zillaid);
        }

        if ($upozilaid != '') {
            $query->where('of.upazila_id', $upozilaid);
        }

        // if ($GeoCityCorporations != '') {
        //     $query->where('of.geo_citycorporation_id', $GeoCityCorporations);
        // }

        // if ($GeoMetropolitan != '' && $GeoThanas != '') {
        //     $query->where('of.geo_metropolitan_id', $GeoMetropolitan)
        //         ->where('of.geo_thana_id', $GeoThanas);
        // }

        // Execute the query and get the result
        $result = $query->first();

     

    
        $no_prosecutor = $result->prosecutor ?? 0;

        return $no_prosecutor;
    }

    public static function getCountOfJailCriminalAndMagistrate($divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $start_date, $end_date){
             
        
                    // $query = User::join('doptor_user_access_info', 'users.common_login_user_id', '=', 'doptor_user_access_info.common_login_user_id')
                    // ->join('office', 'office.id', '=', 'users.office_id')
                    // ->select(DB::raw('COUNT(users.id) AS magistrate')) // Adjust this as needed
                    // ->where('doptor_user_access_info.role_id',25);

                    $query = DB::table('users AS mag')
                            ->join('doptor_user_access_info', 'mag.id', '=','doptor_user_access_info.user_id')
                            ->join('office as  of', 'mag.office_id', '=', 'of.id')
                            ->selectRaw('COUNT(mag.id) AS magistrate')
                            ->where('doptor_user_access_info.role_id',26)
                            ->where('doptor_user_access_info.court_type_id',1);
                            // Dynamically add conditions based on user profile
                            if ($divid != '') {
                                $query->where('of.division_id', $divid);
                            }

                            if ($zillaid != '') {
                                $query->where('of.district_id', $zillaid);
                            }

                            if ($upozilaid != '') {
                                $query->where('of.upazila_id', $upozilaid);
                            }

                            // if ($GeoCityCorporations != '') {
                            //     $query->where('of.geo_citycorporation_id', $GeoCityCorporations);
                            // }

                            // if ($GeoMetropolitan != '' && $GeoThanas != '') {
                            //     $query->where('of.geo_metropolitan_id', $GeoMetropolitan)
                            //         ->where('of.geo_thana_id', $GeoThanas);
                            // }

                            // Execute the query and get the result
                            $result = $query->first();

                            $no_magistrate = $result->magistrate ?? 0;

                            return [
                                'magistrate_no' => $no_magistrate,
                            ];
                    

    }


    public static function  getCitizenComplainStatistics($divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas, $end_date, $start_date){

        //total citizen complain, accepted citizen complain, ignore citizen complain, initial citizen complain
        $information_of_complain = DB::table('citizen_complains')
        ->selectRaw("
            COUNT(id) AS total_complain,
            IFNULL(SUM(IF(complain_status = 'accepted' OR complain_status = 're-send', 1, 0)), 0) AS accepted_complain,
            IFNULL(SUM(IF(complain_status = 'ignore', 1, 0)), 0) AS ignore_complain,
            IFNULL(SUM(IF(complain_status = 'initial', 1, 0)), 0) AS initial_complain
        ")
        ->whereBetween('complain_date', [$start_date, $end_date])
        ->when($divid != '', function($query) use ($divid) {
            return $query->where('divid', $divid);
        })
        ->when($zillaid != '', function($query) use ($zillaid) {
            return $query->where('zillaId', $zillaid);
        })
        ->when($upozilaid != '', function($query) use ($upozilaid) {
            return $query->where('upazilaId', $upozilaid);
        })
        ->when($GeoCityCorporations != '', function($query) use ($GeoCityCorporations) {
            return $query->where('geo_citycorporation_id', $GeoCityCorporations);
        })
        ->when($GeoMetropolitan != '' && $GeoThanas != '', function($query) use ($GeoMetropolitan, $GeoThanas) {
            return $query->where('geo_metropolitan_id', $GeoMetropolitan)
                        ->where('geo_thana_id', $GeoThanas);
        })
        ->get();
       
       $total = $information_of_complain[0]->total_complain ;
        $accepted = $information_of_complain[0]->accepted_complain ;
        $ignore = $information_of_complain[0]->ignore_complain;
        $unchange = $information_of_complain[0]->initial_complain;

        return (array(
            "total" => BanglaDate::bangla_number($total) ,
            "accepted" => BanglaDate::bangla_number($accepted) ,
            "ignore" => BanglaDate::bangla_number($ignore),
            "unchange" =>BanglaDate::bangla_number($unchange)
        ));
    }

        //For ADM Login :Fetch a district Citizen complain information
    public static function getDataCitizenComplainForZilla($zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas,$start_date,$end_date ){
        
        $childs = [];
        $title = ["গ্রহণকৃত", "বাতিলকৃত", "অপেক্ষমান", "নিস্পন্ন"];
        
        $query = DB::table('citizen_complains')
            ->selectRaw('COUNT(id) as complain, complain_status')
            ->where('zillaId', $zillaid);
        
        // Apply filters based on selected fields
        
        // If upazila is selected
        if (!is_null($upozilaid)) {
            $query->where('upazilaid', $upozilaid)
                  ->whereBetween('complain_date', [$start_date, $end_date]);
        }
        // If city corporation is selected
        elseif (!is_null($GeoCityCorporations)) {
            $query->where('geo_citycorporation_id', $GeoCityCorporations);
        }
        // If both metropolitan and thana are selected
        elseif (!is_null($GeoMetropolitan) && !is_null($GeoThanas)) {
            $query->where('geo_metropolitan_id', $GeoMetropolitan)
                  ->where('geo_thana_id', $GeoThanas);
        }
        // Default case (if no specific filters are applied)
        else {
            $query->whereBetween('complain_date', [$start_date, $end_date]);
        }
        
        // Group by complain_status
        $query->groupBy('complain_status');
        
        // Execute the query and get the result
        $result = $query->get();
    
        $childs=self::getPrepareDataList($result);
        return $childs;
    
        }

    public static function getPrepareDataList($result){
      
        $childs = array();
        $accepted=0;
        $ignore=0;
        $initial=0;
        $solved=0;
        $resend=0;
        
        if (count($result) > 0) {
            $item = array();
            $i=0;
            $totalAccepted=0;
            foreach ($result as $rs) {
                if ($rs->complain_status == ComplainStatusRepository::$ACCEPTED ) {
                    $totalAccepted=$totalAccepted+$rs->complain;
                    $accepted=1;
                }


                if ($rs->complain_status == ComplainStatusRepository::$IGNORE) {
                    $item[] = array('label' => 'বাতিলকৃত', 'value' => $rs->complain);
                    $ignore=1;

                }

                if ($rs->complain_status == ComplainStatusRepository::$INITIAL) {
                    $item[] = array('label' => 'অপেক্ষমান', 'value' => $rs->complain);
                    $initial=1;
                }

                if ($rs->complain_status == ComplainStatusRepository::$SOLVED) {
                    $item[] = array('label' => 'নিস্পন্ন', 'value' => $rs->complain);
                    $solved=1;
                }
                if($rs->complain_status == ComplainStatusRepository::$RESEND){
                    $totalAccepted=$totalAccepted+$rs->complain;
                    $resend=1;

                }

            }
            if($accepted==1 || $resend==1){
                $item[] = array('label' => 'গ্রহণকৃত', 'value' => $totalAccepted);
            }

            if($accepted==0){
                $item[]=array('label' => 'গ্রহণকৃত', 'value' => 0);
            }
            if($ignore==0){
                $item[] = array('label' => 'বাতিলকৃত', 'value' => 0);
            }
            if($initial==0){

                $item[] = array('label' => 'অপেক্ষমান', 'value' => 0);
            }
            if($solved==0){
                $item[] = array('label' => 'নিস্পন্ন', 'value' => 0);
            }
            $childs = $item;
        }else{
            $item = array();
            $item[] = array('label' => 'তথ্য নাই', 'value' => !empty($result->complain)?$result->complain:0);
            $childs = $item;
        }
        return $childs;
    }

   public static function getDataCitizenComplainForDiv($divid, $zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas,$start_date,$end_date )
    {
        $childs = [];
        $where = [];
        $whereTime = [['citizencompalin.complain_date', '>=', $start_date], ['citizencompalin.complain_date', '<=', $end_date]];
        
        // User-selected filters
        if ($zillaid !== null && $upozilaid !== null) {
            $where[] = ['citizencompalin.zillaid', '=', $zillaid];
            $where[] = ['citizencompalin.upazilaid', '=', $upozilaid];
        } elseif ($zillaid !== null && $GeoMetropolitan !== null && $GeoThanas !== null) {
            $where[] = ['citizencompalin.zillaid', '=', $zillaid];
            $where[] = ['citizencompalin.geo_metropolitan_id', '=', $GeoMetropolitan];
            $where[] = ['citizencompalin.geo_thana_id', '=', $GeoThanas];
        } elseif ($zillaid !== null && $GeoCityCorporations !== null) {
            $where[] = ['citizencompalin.zillaid', '=', $zillaid];
            $where[] = ['citizencompalin.geo_citycorporation_id', '=', $GeoCityCorporations];
        } elseif ($zillaid !== null) {
            $where[] = ['citizencompalin.zillaid', '=', $zillaid];
        }
        
        // Main query with conditions
        $result = DB::table('citizencompalin')
            ->select(DB::raw('count(id) as complain'), 'complain_status')
            ->where('citizencompalin.divid', $divid)
            ->where($whereTime)
            ->where($where)
            ->groupBy('citizencompalin.complain_status')
            ->get();
        
        $childs = self::getPrepareDataList($result);
        return $childs;
    }
    //For Cabinet Login : Fetch All division data
   public static function getDataCitizenComplainForCountry($divid,$zillaid, $upozilaid, $GeoCityCorporations, $GeoMetropolitan, $GeoThanas,$start_date,$end_date )
    {
        $childs = [];
        $whereConditions = [];
        $startDate = $start_date;
        $endDate = $end_date;

        // Adding the date range condition
        $whereConditions[] = ['citizencompalin.complain_date', '>=', $startDate];
        $whereConditions[] = ['citizencompalin.complain_date', '<=', $endDate];

        // Applying filter based on selected options
        if ($divid != null && $zillaid != null && $upozilaid != null) {
            $whereConditions[] = ['citizencompalin.divid', '=', $divid];
            $whereConditions[] = ['citizencompalin.zillaid', '=', $zillaid];
            $whereConditions[] = ['citizencompalin.upazilaid', '=', $upozilaid];
        } elseif ($divid != null && $zillaid != null && $GeoCityCorporations != null) {
            $whereConditions[] = ['citizencompalin.divid', '=', $divid];
            $whereConditions[] = ['citizencompalin.zillaid', '=', $zillaid];
            $whereConditions[] = ['citizencompalin.geo_citycorporation_id', '=', $GeoCityCorporations];
        } elseif ($divid != null && $zillaid != null && $GeoMetropolitan != null && $GeoThanas != null) {
            $whereConditions[] = ['citizencompalin.divid', '=', $divid];
            $whereConditions[] = ['citizencompalin.zillaid', '=', $zillaid];
            $whereConditions[] = ['citizencompalin.geo_metropolitan_id', '=', $GeoMetropolitan];
            $whereConditions[] = ['citizencompalin.geo_thana_id', '=', $GeoThanas];
        } elseif ($divid != null && $zillaid != null) {
            $whereConditions[] = ['citizencompalin.divid', '=', $divid];
            $whereConditions[] = ['citizencompalin.zillaid', '=', $zillaid];
        } elseif ($divid != null) {
            $whereConditions[] = ['citizencompalin.divid', '=', $divid];
        }

        // Building the query
        $result = DB::table('citizen_complains as citizencompalin')
            ->selectRaw('COUNT(id) as complain, complain_status')
            ->where($whereConditions)
            ->groupBy('complain_status')
            ->get();

        // Get the prepared data for chart from getPrepareDataList function
        $childs = self::getPrepareDataList($result);

        return $childs;
    }
}