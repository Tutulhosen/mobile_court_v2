<?php


namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class ProsecutorLawMappingRepository
{

  public static function getSelectedLawListByProsecutorId($prosecutorId){
    $lawListForProsecutor = DB::table('mc_law as l')
    // $lawListForProsecutor = DB::table('prosecutor_law_mapping as plm')
    // ->join('mc_law as l', 'l.id', '=', 'plm.lawId')
    // ->where('plm.prosecutorId', $prosecutorId)
    ->select('l.title as name', 'l.id')
    ->get();

    return $lawListForProsecutor;
  }

}