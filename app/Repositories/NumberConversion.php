<?php
/**
 * Created by PhpStorm.
 * User: a2i
 * Date: 11/19/14
 * Time: 4:58 PM
 */
namespace App\Repositories;
use Illuminate\Support\Facades\DB;

class NumberConversion
{

    public static function  eng_to_bng_number_conversion($eng_number){

        $bng_number = '';

        $len =  mb_strlen($eng_number, 'UTF-8');


        for ($i = 0; $i < $len; $i++)
        {
            if ($eng_number[$i] == '0' ) $bng_number .= "০" ;
            if ($eng_number[$i] == '1' ) $bng_number .= "১" ;
            if ($eng_number[$i] == '2' ) $bng_number .= "২" ;
            if ($eng_number[$i] == '3' ) $bng_number .= "৩" ;
            if ($eng_number[$i] == '4' ) $bng_number .= "৪" ;
            if ($eng_number[$i] == '5' ) $bng_number .= "৫" ;
            if ($eng_number[$i] == '6' ) $bng_number .= "৬" ;
            if ($eng_number[$i] == '7' ) $bng_number .= "৭" ;
            if ($eng_number[$i] == '8' ) $bng_number .= "৮" ;
            if ($eng_number[$i] == '9' ) $bng_number .= "৯" ;
        }

        return $bng_number;
    }

    public static function ben_to_en_number_conversion($ben_number) {

       return $ben_number;
        $eng_number = '';

        $len =  mb_strlen($ben_number, 'UTF-8');

        return $ben_number = self::utf8Split($ben_number);

        for ($i = 0; $i < $len; $i++){
            if ($ben_number[$i] == "০"){ $eng_number .=  '0';}
            if ($ben_number[$i] == "১"){ $eng_number .=  '1';}
            if ($ben_number[$i] == "২"){ $eng_number .=  '2';}
            if ($ben_number[$i] == "৩"){ $eng_number .=  '3';}
            if ($ben_number[$i] == "৪"){ $eng_number .=  '4';}
            if ($ben_number[$i] == "৫"){ $eng_number .=  '5';}
            if ($ben_number[$i] == "৬"){ $eng_number .=  '6';}
            if ($ben_number[$i] == "৭"){ $eng_number .=  '7';}
            if ($ben_number[$i] == "৮"){ $eng_number .=  '8';}
            if ($ben_number[$i] == "৯"){ $eng_number .=  '9';}
       }

       return $eng_number;
}

    public function uniord($u) {
        $k = mb_convert_encoding($u, 'UCS-2LE', 'UTF-8');
        $k1 = ord(substr($k, 0, 1));
        $k2 = ord(substr($k, 1, 1));
        return $k2 * 256 + $k1;
    }


public static function utf8Split($str, $len = 1)
    {
        $arr = array();
        $strLen = mb_strlen($str, 'UTF-8');
        for ($i = 0; $i < $strLen; $i++){
            $arr[] = mb_substr($str, $i, $len, 'UTF-8');
        }
        return $arr;
    }
}