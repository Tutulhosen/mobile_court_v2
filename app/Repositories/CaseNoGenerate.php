<?php
/**
 * Created by PhpStorm.
 * User: a2i
 * Date: 11/19/14
 * Time: 4:58 PM
 */

 namespace App\Repositories;
 use Illuminate\Support\Facades\DB;
 use App\Repositories\NumberConversion;

class CaseNoGenerate
{
   
    public static function getDigitalCaseNumber()
    {
      
        /*
         * total 17 digit
         *  District Code 2 digit
         *  upozila Code 2 digit
         * 	court code	2 digit
         *  service id	5 digit
         *  Sequential number  4 Digit
         *  last two digit of year 2 digit
         */
        /*
         * total 20 digit
         *  District Code 2 digit
         * 	Office Code	3 digit
         *  service id	5 digit
         *  Sequential number  6 Digit
         *  Year  4 digit
         */
        /*
         * total 18 digit
         * magistrate id  4
         * iffice id  4 digit
         * Sequential number 6
         * year 4
         */

        $loginUserInfo=globalUserInfo();
        $office_id=$loginUserInfo->office_id;
        $officeinfo=DB::table('office')->where('id',$office_id)->first();


        $divid_mag = $officeinfo->division_id;
        $zillaId_mag = $officeinfo->district_id;
        $upozilaid_mag = $officeinfo->upazila_id;
        // $office_mag = $loginUserInfo['officeType'];


        // $magistrate = Magistrate::findFirstByid($loginUserInfo['magistrate-id']);
        $magistrate = DB::table('users as mag')
        ->join('doptor_user_access_info as dp', 'dp.user_id', '=', 'mag.id')
        ->join('mc_role as r', 'r.id', '=', 'dp.role_id')
        ->join('office  as of', 'of.id', '=', 'mag.office_id')
        ->leftJoin('district as z', 'z.id', '=', 'of.district_id')
        // ->leftJoin('upazila as u', function ($join) {
        //     $join->on('u.district_id', '=', 'of.district_id')
        //         ->on('u.id', '=', 'of.upazila_id');
        // })
        ->select([
            'mag.id as id',
            'mag.name  as name_eng',
            DB::raw('CONCAT(mag.name, "-", of.office_name_bn) as name_eng'),
            // 'mag.national_id',
            'mag.mobile_no as mobile',
            'mag.email',
            'mag.username'
        ])
        ->where('of.division_id',$divid_mag)
        ->where('of.district_id',$zillaId_mag)
        ->where('dp.role_id',26)
        ->first();

       
        $magistrate_serviceId = "";
        $magistrate_serviceId_Bng = "";
        $magistrate_serviceId_Eng = "";

        if ($magistrate) {

            $serviceId =$magistrate->username;// $magistrate->service_id;
            $width = 5;

            $new_service_strEng = NumberConversion::ben_to_en_number_conversion($serviceId);
            $new_service_strBng = NumberConversion::eng_to_bng_number_conversion($serviceId);
          
            if ($new_service_strEng != ''){
                $magistrate_serviceId_Eng = str_pad((string)$new_service_strEng, $width, "0", STR_PAD_LEFT);
            }else{
                $magistrate_serviceId_Eng = $serviceId;
            }

            if ($new_service_strBng != '') {
                $magistrate_serviceId_Bng = str_pad((string)$new_service_strBng, $width, "0", STR_PAD_LEFT);
            } else {
                $magistrate_serviceId_Bng = $serviceId;
            }

        } else {
            $magistrate_serviceId = "**111";
        }
      

        $part1 = "";
        $upozilacode = "00";

        if (!empty($upozilaid_mag)) { // উপজেলা অফিস   // সহকারী কমিশনার (ভূমি)
            $upozilacode = $upozilaid_mag;
        } else {
            $upozilacode = "00";
        }
       
        $part1 = str_pad((string)$zillaId_mag, 2, "0", STR_PAD_LEFT) . str_pad((string)$upozilacode, 2, "0", STR_PAD_LEFT);
        $part2 = "01"; // mobile court
        $part_Eng = $magistrate_serviceId_Eng;
        $part_Bng = $magistrate_serviceId_Bng;
        $part4 = ".____."; // sequential number
        $part5 = date('y');

        $searchstring_Eng = $part1 . "." . $part2 . '.' . $part_Eng . $part4 . $part5;
        $searchstring_Bng = $part1 . "." . $part2 . '.' . $part_Bng . $part4 . $part5;

        // $phql1 = "SELECT Pros.case_no  AS case_no
        //             FROM Mcms\Models\Prosecution as Pros
        //             WHERE case_no LIKE  '$searchstring_Eng'  OR case_no LIKE  '$searchstring_Bng'";


        // $query = $this->modelsManager->createQuery($phql1);
        // $prosecution = $query->execute();
        $prosecution = DB::table('prosecutions')
                ->where('case_no', 'LIKE', $searchstring_Eng)
                ->orWhere('case_no', 'LIKE', $searchstring_Bng)
                ->select('case_no')
                ->get();

        $case_no = "";
        $case_number_array = array();
        if (count($prosecution) > 0) {
            foreach ($prosecution as $emp) {
                $case_no = $emp->case_no;
                $exploded = self::multiexplode(array("/", "-", "."), $case_no);
                array_push($case_number_array, $exploded[3]);
            }
        } else {
            $case_no = $part1 . "." . $part2 . '.' . $part_Eng . ".0000." . $part5;
        }
        
        // return $case_no;
        $max_value = 0;
        if (is_array($case_number_array) && !empty($case_number_array)) {
            $max_value = max($case_number_array);
        }

        $lastFourChar = $max_value;  // 4th slot is fixed for case number


        $lastFourNumber = (int)$lastFourChar + 1;

        $part4 = str_pad($lastFourNumber, 4, '0', STR_PAD_LEFT);

        $code = str_pad($part1, 4, '0', STR_PAD_LEFT) .
            '.' . str_pad($part2, 2, '0', STR_PAD_LEFT) .
            '.' . str_pad($part_Eng, 5, '0', STR_PAD_LEFT) .
            '.' . $part4 .
            '.' . $part5;

        return $code;
    } 

    public static function multiexplode($delimiters, $string){

        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return $launch;
    }

}