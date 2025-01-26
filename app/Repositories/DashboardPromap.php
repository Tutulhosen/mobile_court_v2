<?php

namespace App\Repositories;
class DashboardPromap
{

    function getDivPromap($divid)
    {
        $phql = "
                    SELECT SUM(pro.promap) as promap
                    FROM Mcms\Models\Promap AS pro
                    Where pro.divid = $divid ";


        $query = $this->modelsManager->createQuery($phql);
        $info = $query->execute();

        return $info[0]["promap"];

    }

    function getZillaPromap($divid,$zillaId)
    {
        $phql = "   SELECT pro.promap as promap
                    FROM Mcms\Models\Promap AS pro
                    Where pro.divid = $divid  AND pro.zillaid = $zillaId ";


        $query = $this->modelsManager->createQuery($phql);
        $info = $query->execute();

        return $info[0]["promap"];

    }
    public static function  calculatePromap($pre_case_incomplete ,$case_complete, $reportid){
        $promap = 0;


        if($reportid == 2 || $reportid == 3 ) // ADM Court and appeal
        {

            if(1 <= $pre_case_incomplete && $pre_case_incomplete <= 1000){
                $promap = 10;
            }elseif(1000 < $pre_case_incomplete && $pre_case_incomplete<= 2000){
                $promap = 8;
            }elseif($pre_case_incomplete > 2000){
                $promap = 5;
            }else{
                $promap = 0;
            }
        }elseif($reportid == 4 ) //  EM court
        {
            if(1 <= $pre_case_incomplete && $pre_case_incomplete<= 500 ){
                $promap = 12;
            }elseif(500 < $pre_case_incomplete && $pre_case_incomplete<= 1000){
                $promap = 10;
            }elseif(1000 < $pre_case_incomplete && $pre_case_incomplete<= 2000){
                $promap = 8;
            }elseif($pre_case_incomplete > 2000){
                $promap = 5;
            }else{
                $promap = 0;
            }
        }else{ // for report 5 and 6
            $promap = 1;
        }

        return $promap;
    }

}