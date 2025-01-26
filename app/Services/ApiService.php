<?php 
// app/Providers/MyServiceProvider.php

namespace App\Services;
use App\Traits\TokenVerificationTrait;

class ApiService
{
    use TokenVerificationTrait;
    
    public  function section()
    {
    
        $token = $this->verifySiModuleToken('WEB');
        
        if ($token) {
 
            $idpData = self::getSiModule();
           
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt_array($curl, array(
                CURLOPT_URL => $idpData['idp_url'] . 'api/v1/get-section',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => [
                    'test'=>'lasdkfasd'
                ],
                CURLOPT_HTTPHEADER => array(
                    'Accept: application/json',
                    "Authorization: Bearer $token",
                    "secrate_key: gcc-court-key"
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
           
             return  json_decode($response);
            
        }
    }
}
