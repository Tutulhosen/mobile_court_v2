<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
    //show common dashboard page
    public function index(){
        $roleID = globalUserInfo()->role_id;
   
        
        if($roleID == 37){
            $data['title']='জেলা ম্যাজিস্ট্রেট';
            return view('dashboard.monitoring_admin');
        }elseif($roleID == 38){
            $data['title']='অতিরিক্ত জেলা ম্যাজিস্ট্রেট';
            return view('dashboard.monitoring_adm');
        }elseif($roleID == 25){
            $data['title']='প্রসিকিউটর';
            return view('dashboard.monitoring_em');
        }elseif($roleID == 26){
            $data['title']='এক্সিকিউটিভ ম্যাজিস্ট্রেট';
            return view('dashboard.monitoring_em');
        }elseif($roleID == 27){
            $data['title']='এসিজিএম';
            return view('dashboard.monitoring_admin');
        }
        

            // return view('dashboard.monitoring_admin');
        

    }
}
