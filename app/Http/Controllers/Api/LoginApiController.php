<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;

class LoginApiController extends BaseController
{
    //
    public function login_mobilecourt(Request $request){
        $data=$request->all();
        $userAray = json_decode($data['user_info']);
        $officedata = json_decode($data['office_data']);
        $password =json_decode($data['password'],true); //json_decode($data['password']);
       

        $username =$userAray->username;
        $user = User::where("username",  $username)->first();

        $officedata->id;
        //office data setup
        $office_data = [
            'id'    =>$officedata->id,
            'level' => $officedata->level,
            'parent' => $officedata->parent,
            'parent_name' => $officedata->parent_name,
            'office_name_bn' => $officedata->office_name_bn,
            'office_name_en' => $officedata->office_name_en,
            'unit_name_bn' => $officedata->unit_name_bn,
            'unit_name_en' => $officedata->unit_name_en,
            'division_id' => $officedata->division_id,
            'div_name_bn' => $officedata->div_name_bn,
            'div_name_en' => $officedata->div_name_en,
            'office_unit_organogram_id' => $officedata->office_unit_organogram_id,
            'is_gcc' => $officedata->is_gcc,
            'is_organization' => $officedata->is_organization,
            'status' => $officedata->status,

            'upazila_id' => null,
            'upa_name_bn' => null,
            'upa_name_en' => null,
            'district_id' => $officedata->district_id,
            'dis_name_bn' => $officedata->dis_name_bn,
            'dis_name_en' => $officedata->dis_name_en,
            'district_bbs_code' =>$officedata->district_bbs_code,
            'upazila_bbs_code' => null,
        ];
         
        
        self::OfficeExitsCheck($office_data);
       
        //user data setup
        if(!$user){
            $user =new User;
            $user->common_login_user_id=$userAray->id;
            $user->name=$userAray->name;
            $user->username=$userAray->username;
            $user->office_id=$userAray->office_id;
            $user->doptor_office_id=$userAray->doptor_office_id;
            $user->doptor_user_flag=$userAray->doptor_user_flag;
            $user->doptor_user_active=$userAray->doptor_user_active;
            $user->peshkar_active=$userAray->peshkar_active;
            $user->court_id=$userAray->court_id;
            $user->mobile_no=$userAray->mobile_no;
            $user->profile_image=$userAray->profile_image;
            $user->signature=$userAray->signature;
            $user->designation=$userAray->designation;
            $user->email=$userAray->email;
            $user->is_verified_account=$userAray->is_verified_account;
            $user->email_verified_at=$userAray->email_verified_at;
            $user->profile_pic=$userAray->profile_pic;
            $user->password= json_decode($data['password']);
            $user->common_login_user_created_at=$userAray->created_at;
            $user->common_login_user_updated_at=$userAray->updated_at;
            $user->save();

            DB::table('doptor_user_access_info')->insert([
                'user_id' =>$user->id,
                'common_login_user_id' =>$userAray->id,
                'court_type_id' =>$userAray->user_access_court_type_id,
                'role_id' =>$userAray->user_access_role_id,
                'court_id' =>$userAray->user_access_court_id,
            ]);

            
        }else {
            DB::table('users')->where('username',$userAray->username)->update([
                'name' =>$userAray->name,
                'office_id' =>$userAray->office_id,
                'doptor_office_id' =>$userAray->doptor_office_id,
                'doptor_user_flag' =>$userAray->doptor_user_flag,
                'doptor_user_active' =>$userAray->doptor_user_active,
                'peshkar_active' =>$userAray->peshkar_active,
                'mobile_no' =>$userAray->mobile_no,
                'profile_image' =>$userAray->profile_image,
                'signature' =>$userAray->signature,
                'designation' =>$userAray->designation,
                'email' =>$userAray->email,
                'is_verified_account' =>$userAray->is_verified_account,
                'email_verified_at' =>$userAray->email_verified_at,
                'profile_pic' =>$userAray->profile_pic,
                'common_login_user_created_at' =>$userAray->created_at,
                'password' => json_decode($data['password']),
            ]);

            DB::table('doptor_user_access_info')->where('common_login_user_id',$userAray->id)->update([
                'court_type_id' =>$userAray->user_access_court_type_id,
                'role_id' =>$userAray->user_access_role_id,
                'court_id' =>$userAray->user_access_court_id,
            ]);
        }

    
        if (Auth::attempt(['username' =>$username,'password'=>'THIS_IS_N_DOPTOR_USER_wM-zu+93Fh+bvn%T78=j*G62nWH-C'])) {
           
        
            $roleName = DB::table('mc_role')->select('role_name')->where('id',$userAray->user_access_role_id)
            ->first()->role_name;
            $officeInfo = DB::table('office')->select('office_name_bn', 'division_id', 'district_id', 'upazila_id')->where('id', $user->office_id)
                        ->first();
            
            $success['user_id']      =  isset($user->id) ? $user->id : null;
            $success['name']         =  isset($user->name) ? $user->name : null;
            $success['email']        =  isset($user->email) ? $user->email : null;
            $success['profile_pic']  =  isset($user->profile_pic) ? $user->profile_pic : null;
            $success['role_id']      =  isset($userAray->user_access_role_id) ? $userAray->user_access_role_id : null;
            $success['court_id']     =  isset($userAray->user_access_court_id) ? $userAray->user_access_court_id : null;
            $success['role_name']    =  isset($roleName) ? $roleName : null;
            $success['office_id']    =  isset($user->office_id) ? $user->office_id : null;
            $success['office_name']  =  isset($officeInfo->office_name_bn) ? $officeInfo->office_name_bn : null;
            $success['division_id']  =  isset($officeInfo->division_id) ? $officeInfo->division_id : null;
            $success['district_id']  =  isset($officeInfo->district_id) ? $officeInfo->district_id : null;
            $success['upazila_id']   =  isset($officeInfo->upazila_id) ? $officeInfo->upazila_id : null;
        



           $success['user']=Auth::user();
           $success['token'] =  $user->createToken('EMC-CLIENT')->accessToken;
           return $this->sendResponse($success, 'User login successfully.');
     
        }
       
        
        
    }

    //office check
    public static function OfficeExitsCheck($office_data)
    {
        
         $office_id=DB::table('office')
                    ->where('id','=',$office_data['id'])
                     ->first();
        
         if(empty($office_id))
         {
            $office_id = DB::table('office')->insertGetId($office_data);

            return $office_id;
         }
         else
         {
          
            $office_id = DB::table('office')->where('id',$office_id->id)->update($office_data);
           
            return $office_id;
         }            
    }
}
