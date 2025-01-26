<?php

use App\Models\User;
use App\Models\CaseActivityLog;
use App\Models\RM_CaseActivityLog;
use EasyBanglaDate\Types\DateTime;
use Illuminate\Support\Facades\DB;

// use App\Http\Controllers\CommonController;
use EasyBanglaDate\Types\BnDateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;


if (!function_exists('en2bn')) {
	function en2bn($item) {
		return App\Http\Controllers\CommonController::en2bn($item);
		// echo $item;
	}
}

if (!function_exists('bn2en')) {
    function bn2en($item) {
        return App\Http\Controllers\CommonController::bn2en($item);
        // echo $item;
    }
}

if (!function_exists('globalUserInfo')) {
	function globalUserInfo() {
        $dd= DB::table('doptor_user_access_info')
        ->join('users', 'doptor_user_access_info.common_login_user_id', '=', 'users.common_login_user_id')
        ->where('users.common_login_user_id', Auth::user()->common_login_user_id)
        ->select('users.*', 'doptor_user_access_info.court_id','doptor_user_access_info.role_id','doptor_user_access_info.court_type_id')
        ->first();
        $userInfo =$dd;// Auth::user(); //when laravel default auth system.
        return $userInfo;
	}
}

if(!function_exists('DOPTOR_ENDPOINT')){
    function DOPTOR_ENDPOINT()
    {
       return "https://api-training.doptor.gov.bd";    
    }
}
if(!function_exists('doptor_client_id')){
    function doptor_client_id()
    {
       return "BDNT4N";    
    }
}
if(!function_exists('doptor_password')){
    function doptor_password()
    {
       return "B5$1CF";    
    }
}
if(!function_exists('mygov_endpoint')){
    function mygov_endpoint()
    {
       return "https://beta-idp.stage.mygov.bd";    
    }
}
if(!function_exists('mygov_client_id')){
    function mygov_client_id()
    {
       return "978366b2-8759-448b-953b-79e7d21f5a86";    
    }
}
if(!function_exists('mygov_client_secret')){
    function mygov_client_secret()
    {
       return "UrcaH5t01cFYIpILhvvymd192mmAaTVvTMmMtjSG";    
    }
}


if(!function_exists('mygov_nid_verification_api_endpoint')){
    function mygov_nid_verification_api_endpoint()
    {
       return "https://si.stage.mygov.bd";    
    }
}

if(!function_exists('mygov_nid_verification_api_key')){
    function mygov_nid_verification_api_key()
    {
       return "zrT1ybNrzv";    
    }
}

if (!function_exists('roleName')) {
    function roleName($role_id) {
        
       $role_name=  DB::table('mc_role')->where('id',$role_id)->first();
       return $role_name;
    }
}

if (!function_exists('globalUserRoleInfo')) {
    function globalUserRoleInfo() {
        
        // $userInfo = Session::get('userInfo')->username; //when sso connected
         $userRole = Auth::user()->role_id;
        return DB::table('mc_role')->select('role_name')->where('id',$userRole)->first();
         //when laravel default auth system.
        // return $userInfo;
    }
}
if (!function_exists('convert_dis_id_to_name')) {
    function convert_dis_id_to_name($dis_id)
    {

        return  DB::table('district')
            ->select('district_name_bn')
            ->where('district.id', $dis_id)
            ->first()->district_name_bn;
    }
}
if (!function_exists('globalUserOfficeInfo')) {
    function globalUserOfficeInfo() {
        // $userInfo = Session::get('userInfo')->username; //when sso connected
        $userOffice = Auth::user()->office_id;
        return DB::table('office')->select('office_name_bn')->where('id',$userOffice)->first();
         //when laravel default auth system.
        // return $userInfo;
    }
}

if (!function_exists('globalUseroffice')) {
    function globalUseroffice($off_id) {
        
        return DB::table('office')->where('id',$off_id)->first();
         //when laravel default auth system.
        // return $userInfo;
    }
}


//Common module base url
if (!function_exists('getCommonModulerBaseUrl')) {
    function getCommonModulerBaseUrl()
    {
        if ($_SERVER['SERVER_NAME'] == '127.0.0.1' || $_SERVER['SERVER_NAME'] == 'localhost') {
            return 'http://localhost:8000/';
        } else {
            return 'http://ecourt.mysoftheaven.com/';
        }
    }
}

if (!function_exists('roleWiseAuthUser')) {
    function roleWiseAuthUser($role_id)
    {
        $dd= DB::table('doptor_user_access_info')
        ->join('users', 'doptor_user_access_info.common_login_user_id', '=', 'users.common_login_user_id')
        ->where('users.common_login_user_id', Auth::user()->common_login_user_id)
        ->where('doptor_user_access_info.role_id',$role_id)
        ->select('users.*', 'doptor_user_access_info.court_id','doptor_user_access_info.role_id','doptor_user_access_info.court_type_id')
        ->first();
        $userInfo =$dd;// Auth::user(); //when laravel default auth system.
        return $userInfo;
         
    }
}

if (!function_exists('roleWiseUserList')) {
    function roleWiseUserList($role_id)
    {
        $dd= DB::table('doptor_user_access_info')
        ->join('users', 'doptor_user_access_info.common_login_user_id', '=', 'users.common_login_user_id')
        ->where('doptor_user_access_info.role_id',$role_id)
        ->select('users.*', 'doptor_user_access_info.court_id','doptor_user_access_info.role_id','doptor_user_access_info.court_type_id')
        ->get();
        $userInfo =$dd;// Auth::user(); //when laravel default auth system.
        return $userInfo;
         
    }
}



?>