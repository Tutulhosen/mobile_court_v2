<?php


namespace App\Repositories;

use App\Models\Criminal;
use App\Models\Prosecution;
use App\Models\ProsecutionDetail;
use App\Models\CriminalConfession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\CriminalConfessionLawsbroken;
use App\Models\Court;

class DashboardRepository
{

    public static function getTotalCaseCount($magistrateId, $condition)
    {
        
        
        $allcases = DB::table('prosecutions')
        ->join('courts', 'prosecutions.court_id', '=', 'courts.id')
        ->join('users', 'courts.magistrate_id', '=', 'users.id')
        ->where('prosecutions.delete_status', '1')
        ->where('prosecutions.is_approved', '1')
        ->where('users.id', $magistrateId)
        ->count();
         return   $allcases;       
    }

    public static function getIncompleteCaseCount($magistrateId, $condition)
    {
    
 
            // $data['incompleteCases'] = DB::table('prosecutions')
            //     ->join('courts', 'prosecutions.court_id', '=', 'courts.id')
            //     ->join('users', 'courts.magistrate_id', '=', 'users.id')
            //     ->where('prosecutions.delete_status', '1')
            //     ->where('prosecutions.is_suomotu', '0')
            //     ->where('prosecutions.is_approved', '1')
            //     ->where('prosecutions.case_status', '<', '6')
            //     ->where('users.id', $magistrateId)
            //     ->count();

            return $incompleteCase = DB::table('prosecutions')
            ->join('courts', 'courts.id', '=', 'prosecutions.court_id')
            ->join('users', 'users.id', '=', 'courts.magistrate_id')
            ->where('prosecutions.delete_status', 1)
            ->where('users.id',$magistrateId)
            ->where('prosecutions.is_approved', 1)
            ->whereNull('prosecutions.orderSheet_id')  // Ensure orderSheet_id is NULL
            // ->whereRaw($condition)  // Add any additional raw conditions
            ->count();
    }

    public static function getTotalExecutedCourt($magistrateId, $condition)
    {
        return $executed_court = Court::TotalCourt()->where('magistrate_id', $magistrateId)
        ->count();
    }

    public static function getTotalCitizenComplain($magistrateId, $condition)
    {
        
       return  $result = DB::table('prosecutions')
        ->join('courts', 'courts.id', '=', 'prosecutions.court_id')
        ->join('court_complain_infos', 'court_complain_infos.court_id', '=', 'prosecutions.court_id')
        ->where('prosecutions.delete_status', 1)
        ->where('courts.magistrate_id',$magistrateId)
        ->where('court_complain_infos.complain_type', 1)
        // ->whereRaw($condition)  // Add any additional raw conditions here if needed
        ->count('prosecutions.id');
    }

    public static function getListOfIncompleteCitizenComplain($magistrateId, $condition){

       return  $result_case_processing = DB::table('prosecutions')
        ->join('courts', 'courts.id', '=', 'prosecutions.court_id')
        ->join('court_complain_infos', 'court_complain_infos.court_id', '=', 'prosecutions.court_id')
        ->where('prosecutions.delete_status', 1)
        ->where('courts.magistrate_id',$magistrateId)
        ->where('court_complain_infos.complain_type', 1)
        ->whereNull('prosecutions.orderSheet_id')  // Ensure orderSheet_id is NULL
        // ->whereRaw($condition)  // Add any additional raw conditions
        ->count('prosecutions.id');  // Count the number of prosecutions

    }

    public static function getCriminalList($magistrateId)
    {
        $criminal = DB::table('prosecution_details')
                ->join('prosecutions', 'prosecutions.id', '=', 'prosecution_details.prosecution_id')
                ->join('courts', 'courts.id', '=', 'prosecutions.court_id')
                ->where('prosecutions.delete_status', 1)
                ->where('prosecutions.is_approved', 1)
                ->where('courts.magistrate_id',$magistrateId)
                ->count('prosecution_details.criminal_id');  // Count the number of criminal IDs

         return  $criminal;
    }

    public static function getTotalCriminalAndFine($magistrateId)
    {
      return   $criminalAndfine = DB::table('punishments')
        ->select('punishments.criminal_id as criminal', 'punishments.fine as fine')
        ->join('prosecutions', 'prosecutions.id', '=', 'punishments.prosecution_id')
        ->join('courts', 'courts.id', '=', 'prosecutions.court_id')
        ->where('prosecutions.delete_status', 1)
        ->where('prosecutions.is_approved', 1)
        ->where('courts.magistrate_id',$magistrateId)
        ->groupBy('punishments.prosecution_id')
        ->get();  // Use get() to retrieve the results
    }

    public static function getListOfallSelfCases($magistrateId)
    {   
        return $allSelfCases = DB::table('prosecutions')
                ->join('courts', 'prosecutions.court_id', '=', 'courts.id')
                ->join('users', 'courts.magistrate_id', '=', 'users.id')
                ->where('prosecutions.delete_status', '1')
                ->where('prosecutions.is_approved', '1')
                ->where('prosecutions.is_suomotu', '1')
                ->where('prosecutions.case_status', '<', '6')
                ->where('users.id', $magistrateId)
                ->count();

    }

    public static function getProsecutorTotalCaseCount($userid, $condition)
    {
        
        
        $allcases = DB::table('prosecutions')
        ->join('courts', 'prosecutions.court_id', '=', 'courts.id')
        ->join('users', 'prosecutions.prosecutor_id', '=', 'users.id')
        ->where('prosecutions.delete_status', '1')
        // ->where('prosecutions.is_approved', '1')
        ->where('users.id',$userid)
        ->count();
         return   $allcases;       

    }

    public static function getprosecutorIncompleteCaseCount($userid, $condition){

       $incompleteCase = DB::table('prosecutions')
        ->join('courts', 'courts.id', '=', 'prosecutions.court_id')
        ->join('users', 'prosecutions.prosecutor_id', '=', 'users.id')
        ->where('prosecutions.delete_status', 1)
        ->where('users.id',$userid)
        // ->where('prosecutions.is_approved',0)
        ->whereNull('prosecutions.orderSheet_id')  // Ensure orderSheet_id is NULL
        // ->whereRaw($condition)  // Add any additional raw conditions
        ->count();
        return $incompleteCase;
    }


    public static function getprosecutorTotalExecutedCourt($userid){
        $executed_court = DB::table('courts')
        ->join('prosecutions', 'courts.id', '=', 'prosecutions.court_id')
        ->join('users', 'prosecutions.prosecutor_id', '=', 'users.id')
        ->select('prosecutions.court_id')
        ->where('prosecutions.delete_status', 1)
        ->where('users.id',4)
        ->groupBy('prosecutions.court_id')
        ->get()
        ->count();
        return $executed_court;
    }
    public static function getProsecutorTotalCriminalAndFine($userid){

      $fine=  DB::table('punishments')
        ->select('punishments.criminal_id as criminal', 'punishments.fine as fine')
        ->join('prosecutions', 'punishments.prosecution_id', '=', 'prosecutions.id')
        ->join('courts', 'prosecutions.court_id', '=', 'courts.id')
        ->where('prosecutions.delete_status', '1')
        ->where('prosecutions.is_approved', '1')
        ->where('prosecutions.prosecutor_id', $userid)
        ->groupBy('punishments.prosecution_id')
        ->get();

         return $fine;
    }

    public static function getProsecutorCriminalList($userid){
        $criminalCount = DB::table('prosecution_details as PROS')
                ->join('prosecutions', 'PROS.prosecution_id', '=', 'prosecutions.id')
                ->join('courts', 'prosecutions.court_id', '=', 'courts.id')
                ->where('prosecutions.delete_status', '1')
                ->where('prosecutions.is_approved', '1')
                ->where('prosecutions.prosecutor_id', $userid)
                ->count('PROS.criminal_id');
        return  $criminalCount;
    }

    public static function getProsecutorTotalCitizenComplain($userid){
        $totalComplain = DB::table('prosecutions as pro')
                ->join('courts as court', 'court.id', '=', 'pro.court_id')
                ->join('court_complain_infos as cci', 'cci.court_id', '=', 'pro.court_id')
                ->where('pro.delete_status', '1')
                ->where('pro.prosecutor_id',$userid)
                ->where('cci.complain_type', 1)
                ->count('pro.id');
        return $totalComplain;
    }
    public static function getProsecutorListOfIncompleteCitizenComplain($userid){
         $totalIncomplete = DB::table('prosecutions as pro')
                ->join('courts as court', 'court.id', '=', 'pro.court_id')
                ->join('court_complain_infos as cci', 'cci.court_id', '=', 'pro.court_id')
                ->where('pro.delete_status', '=', '1')
                ->where('pro.prosecutor_id',$userid)
                ->where('cci.complain_type', '=', 1)
                ->whereNull('pro.orderSheet_id')
                ->count('pro.id');
        return $totalIncomplete;
    }
}