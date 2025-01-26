<?php


namespace App\Repositories;

use App\Models\LawsBroken;
use App\Models\Prosecution;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\LawsBrokenProsecution;
use Illuminate\Support\Facades\Session;


class LawRepository
{

    public static function saveLawsBrokenList($prosecutionId,$lawsList,$loginUserInfo){

        $crimeDescription = '';
        if($loginUserInfo['profile']=='Prosecutor'){
            $lawsBrokenList = LawsBrokenProsecution::where('prosecution_id', $prosecutionId)->get();
        }else{
            $lawsBrokenList = LawsBroken::where('prosecution_id', $prosecutionId)->get();
        }


        foreach ($lawsBrokenList as $data) {
            if($loginUserInfo['profile']=='Prosecutor'){
                $lawBroken = LawsBrokenProsecution::find($data->id);
            }else{

                $lawBroken = LawsBroken::find($data->id);
            }
                $lawBroken->delete();
        }

        foreach($lawsList as $key=>$law){

            if($loginUserInfo['profile']=='Prosecutor'){
                $brokenLaw = new LawsBrokenProsecution();
            }else{
                $brokenLaw = new LawsBroken();
            }   

            $brokenLaw->prosecution_id = $prosecutionId;
            $brokenLaw->law_id         = $law['law_id'];
            $brokenLaw->section_id     = $law['section_id'];
            $brokenLaw->Description    = $law['crime_description'];
            $brokenLaw->created_by     = $loginUserInfo['email'];
            // $prosecution->created_date = date('Y-m-d  H:i:s');
            $brokenLaw->updated_by      =$loginUserInfo['email'];
            // $brokenLaw->CreateBy = $this->auth->getIdentity()['email'];
            // $brokenLaw->CreateDate = date('Y-m-d  H:i:s');
            // $brokenLaw->UpdateBy = $this->auth->getIdentity()['email'];
            // $brokenLaw->UpdateDate = date('Y-m-d  H:i:s');
            $crimeDescription .= ($key).' - নম্বর: '. $law['crime_description'].'</br>';

            // $this->db->begin();
            if (!$brokenLaw->save()) {
                $messages = $brokenLaw->getMessages();
                foreach ($messages as $message) {
                    echo $message, "\n";
                }
            }
            else{
                // $this->db->commit();
            }
        }

        return $crimeDescription;
    }
}