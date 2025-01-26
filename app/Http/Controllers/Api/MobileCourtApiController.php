<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MobileCourtApiController extends BaseController
{

    public function get_division(Request $request, $id = null)
    {

        try {
            if ($id == null) {
                $division = DB::table('division')->get();
            } else {
                $division = DB::table('division')->where('id', $id)->get();
            }
            return $this->sendResponse($division, 'Division List');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }
    }

    public function get_district_by_query(Request $request)
    {

        try {
            $district_id = $request->query('district_id');
            $division_id = $request->query('division_id');

            if ($district_id != null && $division_id != null) {
                $district = DB::table('district')->where('id', $district_id)->where('division_id', $division_id)->get();
            } elseif ($division_id != null) {
                $district = DB::table('district')->where('division_id', $division_id)->get();
            } elseif ($district_id != null) {
                $district = DB::table('district')->where('id', $district_id)->get();
            } else {
                $district = DB::table('district')->get();
            }
            return $this->sendResponse($district, 'District List');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }
    }

    public function get_upazila_by_query(Request $request)
    {
        try {
            $upazila_id = $request->query('upazila_id');
            $district_id = $request->query('district_id');
            $division_id = $request->query('division_id');

            if ($upazila_id != null && $district_id != null && $division_id != null) {
                $upazila = DB::table('upazila')->where('id', $upazila_id)->where('district_id', $district_id)->where('division_id', $division_id)->get();
            } elseif ($division_id != null && $district_id != null) {
                $upazila = DB::table('upazila')->where('division_id', $division_id)->where('district_id', $district_id)->get();
            } elseif ($division_id != null && $upazila_id != null) {
                $upazila = DB::table('upazila')->where('division_id', $division_id)->where('id', $upazila_id)->get();
            } elseif ($district_id != null && $upazila_id != null) {
                $upazila = DB::table('upazila')->where('district_id', $district_id)->where('id', $upazila_id)->get();
            } elseif ($division_id != null) {
                return $upazila = DB::table('upazila')->where('division_id', $division_id)->get();
            } elseif ($district_id != null) {
                $upazila = DB::table('upazila')->where('district_id', $district_id)->get();
            } elseif ($upazila_id != null) {
                $upazila = DB::table('upazila')->where('id', $upazila_id)->get();
            } else {
                $upazila = DB::table('upazila')->get();
            }
            return $this->sendResponse($upazila, 'Upazila List');
        } catch (\Throwable $th) {

            return $this->sendError('Error', $th->getMessage());
        }
    }

    public function get_city_corporation_by_query(Request $request)
    {
        try {
            $city_corporation_id = $request->query('city_corporation_id');
            $district_id = $request->query('district_id');
            $division_id = $request->query('division_id');
            // select('city_corporation_name_eng', 'city_corporation_name_bng','id', 'geo_division_id', 'geo_district_id')

            if ($city_corporation_id != null && $district_id != null && $division_id != null) {
                $city_corporation = DB::table('geo_city_corporations')->where('id', $city_corporation_id)->where('geo_district_id', $district_id)->where('geo_division_id', $division_id)->get();
            } elseif ($division_id != null && $district_id != null) {
                $city_corporation = DB::table('geo_city_corporations')->where('geo_division_id', $division_id)->where('geo_district_id', $district_id)->get();
            } elseif ($division_id != null && $city_corporation_id != null) {
                $city_corporation = DB::table('geo_city_corporations')->where('geo_division_id', $division_id)->where('id', $city_corporation_id)->get();
            } elseif ($district_id != null && $city_corporation_id != null) {
                $city_corporation = DB::table('geo_city_corporations')->where('geo_district_id', $district_id)->where('id', $city_corporation_id)->get();
            } elseif ($division_id != null) {
                $city_corporation = DB::table('geo_city_corporations')->where('geo_division_id', $division_id)->get();
            } elseif ($district_id != null) {
                $city_corporation = DB::table('geo_city_corporations')->where('geo_district_id', $district_id)->get();
            } elseif ($city_corporation_id != null) {
                $city_corporation = DB::table('geo_city_corporations')->where('id', $city_corporation_id)->get();
            } else {
                $city_corporation = DB::table('geo_city_corporations')->get();
            }

            return $this->sendResponse($city_corporation, 'City Corporation  fatched successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }
    }
    public function get_metropolition_by_query(Request $request)
    {
        try {
            $metropolition_id = $request->query('metropolition_id');
            $district_id = $request->query('district_id');
            $division_id = $request->query('division_id');
            // select('city_corporation_name_eng', 'city_corporation_name_bng','id', 'geo_division_id', 'geo_district_id')

            if ($metropolition_id != null && $district_id != null && $division_id != null) {
                $metropolition_data = DB::table('geo_metropolitan')->where('id', $metropolition_id)->where('geo_district_id', $district_id)->where('geo_division_id', $division_id)->get();
            } elseif ($division_id != null && $district_id != null) {
                $metropolition_data = DB::table('geo_metropolitan')->where('geo_division_id', $division_id)->where('geo_district_id', $district_id)->get();
            } elseif ($division_id != null && $metropolition_id != null) {
                $metropolition_data = DB::table('geo_metropolitan')->where('geo_division_id', $division_id)->where('id', $metropolition_id)->get();
            } elseif ($district_id != null && $metropolition_id != null) {
                $metropolition_data = DB::table('geo_metropolitan')->where('geo_district_id', $district_id)->where('id', $metropolition_id)->get();
            } elseif ($division_id != null) {
                $metropolition_data = DB::table('geo_metropolitan')->where('geo_division_id', $division_id)->get();
            } elseif ($district_id != null) {
                $metropolition_data = DB::table('geo_metropolitan')->where('geo_district_id', $district_id)->get();
            } elseif ($metropolition_id != null) {
                $metropolition_data = DB::table('geo_metropolitan')->where('id', $metropolition_id)->get();
            } else {
                $metropolition_data = DB::table('geo_metropolitan')->get();
            }

            return $this->sendResponse($metropolition_data, 'Metropolition  fatched successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }
    }

    public function court_event_create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'start' => 'required',
                'status' => 'required',
                'title' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "success" => false,
                    "message" => $validator->errors()->all(),
                    "err_res" => "",
                    "status" => 403,
                    "data" => null,
                ]);
            }

            $status = $request->status;
            $date = $request->start;
            $userinfo = globalUserInfo();

            $str_message = "";

            if ($status == "1") {
                $exist_court = DB::table('courts')
                    ->where('magistrate_id', $userinfo->id)
                    ->where(function ($query) use ($date) {
                        $query->where('date', $date)
                            ->orWhere('status', 1);
                    })
                    ->get();

                if (count($exist_court) > 0) {
                    $str_message = "একাধিক কর্মসূচি  প্রণয়ন করা যাবে না [আপনার পূর্বের একটি কোর্ট খোলা রয়েছে ]। ";
                    return $this->sendError($str_message);
                }
            } elseif ($status == "2") {
                $str_message = "এই তারিখে এখনো কোনো কোর্ট খোলা হয়নি,অতএব কোর্ট বন্ধ করা যাচ্ছে না।";
                return $this->sendError($str_message);
            } elseif ($status == "0") {
                $str_message = "এই তারিখে এখনো কোনো কোর্ট খোলা হয়নি,অতএব কোর্ট  কর্মসূচি প্রণয়ন করা যাচ্ছে না।";
                return $this->sendError($str_message);
            }
            // get by inserted id
            $inserted_id = DB::table('courts')->insertGetId([
                'date' => $request->start,
                'status' => $request->status,
                'title' => $request->title,
                'court_id' => "test",
                // 'status_court' =>  2,
                'magistrate_id' => $userinfo->id,
                'created_by' => $userinfo->name,
                'created_date' => date('Y-m-d'),
                'update_by' => $userinfo->name,
                'update_date' => date('Y-m-d'),
                'delete_status' => 1,
            ]);
            // get by inserted event
            $data = DB::table('courts')->where('id', $inserted_id)->get();
            if ($request->status == 1) { // open
                $str_message = "আপনার কোর্টের কর্মসূচি খোলা  হয়েছে ।";
            } else if ($request->status == 2) { //  close
                $str_message = "আপনার কোর্টের কর্মসূচি বন্ধ করা হয়েছে ।";
            } else { // initiate
                $str_message = "আপনার কোর্টের কর্মসূচি প্রণয়ন করা হয়েছে ।";
            }
            return $this->sendResponse($data, $str_message);
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }
    }

    public function getcourtdataAll()
    {
        try {
            $userinfo = globalUserInfo(); // gobal user
            $data = DB::table('courts')->where('magistrate_id', $userinfo->id)->get();
            return $this->sendResponse($data, 'কোর্টের কর্মসূচি পাওয়া গেছে ।');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }
    }

    public function getcourtdataById($id)
    {

        try {
            $userinfo = globalUserInfo(); // gobal user
            $data = DB::table('courts')->where('magistrate_id', $userinfo->id)->where('id', $id)->get();
            if (count($data) == 0) {
                return $this->sendError('কোর্টের কর্মসূচি পাওয়া যায়নি');
            }
            return $this->sendResponse($data, 'কোর্টের কর্মসূচি পাওয়া গেছে ।');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }
    }

    public function court_event_update(Request $request, $id)
    {

        $userinfo = globalUserInfo(); // gobal user
        $court = DB::table('courts')->find($id);
        $date = $request->start;
        // return $this->sendResponse($court,"Court Event List");
        // if ($court->status == "0") {
        //     return $this->sendError('কোর্টের কর্মসূচি প্রণয়ন করা যাবে না ।');
        // }

        // if ($court->date != $request->start) {
        //     return $this->sendError('কোর্টের কর্মসূচি তারিখ পরিবর্তন করা যাবে না ।');
        // }

        $str_message = '';
        if (!$court) {
            return $this->sendError('কোর্টের কর্মসূচি ঘটনা পাওয়া যায়নি');
        }

        if ($request->status == 1) { // open

            $exist_court = DB::table('courts')
                ->where('magistrate_id', $userinfo->id)
                ->Where('status', 1)
                ->get();
            if (count($exist_court) > 0) {
                $strmessage = "একাধিক কর্মসূচি  প্রণয়ন করা যাবে না । ";
                //  $str_message=[
                //      'msg' => $strmessage,
                //      'success' =>false
                //  ];
                return $this->sendError("একাধিক কর্মসূচি  প্রণয়ন করা যাবে না । ");
            }

        }

        $data = array(
            'id' => $id,
            'date' => $request->start,
            'title' => $request->title,
            'status' => $request->status,
            'magistrate_id' => $userinfo->id,
        );
        // return $this->sendResponse($data, 'Court Event List');
        $update = DB::table('courts')->where('id', $id)->update($data);
        if (!$update) {
            return $this->sendError("কর্মসূচি  পরিবর্তন  করা যাবে না । ");
        } else {
            if ($request->status == 1) { // open
                $str_message = "আপনার কোর্টের কর্মসূচি খোলা  হয়েছে ।";
            } else if ($request->status == 2) { //  close
                $str_message = "আপনার কোর্টের কর্মসূচি বন্ধ করা হয়েছে ।";
            } else { // initiate
                $str_message = "আপনার কোর্টের কর্মসূচি প্রণয়ন করা হয়েছে ।";
            }
        }
        $update_data = DB::table('courts')->where('id', $id)->get();
        return $this->sendResponse($update_data, $str_message);
    }

    public function court_event_delete(Request $request, $id)
    {

        try {
            $userinfo = globalUserInfo();
            $court = DB::table('courts')->where('id', $id)->where('magistrate_id', $userinfo->id)->delete();
            if (!$court) {
                return $this->sendError('কর্মসূচি মুছে ফেলা যাবে না ।');
            }
            return $this->sendResponse($court, 'কোর্টের কর্মসূচি মুছে ফেলা হয়েছে ।');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }
    }

    public function getmc_law()
    {
        try {
            $law_id = request()->query('law_id');
            if ($law_id != null) {
                $data = DB::table('mc_law')->where('id', $law_id)->get();
                return $this->sendResponse($data, 'MC Law List');
            }
            $data = DB::table('mc_law')->get();
            return $this->sendResponse($data, 'MC Law List');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }
    }

    public function getmc_section(Request $request)
    {
        try {

            $law_id = request()->query('law_id');
            $section_id = request()->query('section_id');
            if ($law_id != null && $section_id != null) {
                $data = DB::table('mc_section')->where('law_id', $law_id)->where('id', $section_id)->get();
                return $this->sendResponse($data, 'MC Section List');
            } elseif ($section_id != null) {

                return $data = DB::table('mc_section')->where('id', $section_id)->get();
                return $this->sendResponse($data, 'MC Section List');
            } elseif ($law_id != null) {
                $data = DB::table('mc_section')->where('law_id', $law_id)->get();
                return $this->sendResponse($data, 'MC Section List');
            }

            $data = DB::table('mc_section')->get();

            return $this->sendResponse($data, 'MC Section List');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }
    }

    public function getmc_law_type()
    {
        try {
            $law_type_id = request()->query('law_type_id');
            if ($law_type_id != null) {
                $data = DB::table('mc_law_type')->where('id', $law_type_id)->get();
                return $this->sendResponse($data, 'MC Law Type List');
            }
            $data = DB::table('mc_law_type')->get();
            return $this->sendResponse($data, 'MC Law Type List');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }
    }

    public function getThanaByUsersZillaId()
    {
        try {
            $thanaArray = array();
            $userinfo = globalUserInfo();
            $office_id = globalUserInfo()->office_id;
            $officeinfo = DB::table('office')->where('id', $office_id)->first();
            $date = date('Y-m-d');

            $userinfo = array(
                'id' => $userinfo->id,
                'magistrate-id' => $userinfo->id,
                'courtname' => $userinfo->name,
                'name' => $userinfo->name,
                'email' => $userinfo->email,
                'profile' => 'Magistrate',
                'divid' => $officeinfo->division_id,
                'zillaid' => $officeinfo->district_id,
                'upozilaid' => $officeinfo->upazila_id,
                'divname' => $officeinfo->div_name_bn,
                'zillaname' => $officeinfo->dis_name_bn,
                'upozillaname' => $officeinfo->dis_name_bn,
                'serviceid' => $officeinfo->upa_name_bn,
                'office' => $officeinfo->office_name_bn,
                'officeType' => $officeinfo->organization_type,
                'joblocation' => $officeinfo->dis_name_bn,
                'mobile' => $userinfo->mobile_no,
                'designation' => $userinfo->designation,
                'role_id' => $userinfo->role_id,
                // 'court_id'=> $court_id,
            );
            $tmp = array();
            if ($userinfo['zillaid']) {
                $tmp = DB::table('geo_thanas')->where('geo_district_id', $userinfo['zillaid'])->get();
            }
            foreach ($tmp as $t) {
                $thanaArray[] = array('id' => $t->id, 'name' => $t->thana_name_bng);
            }
            $data = $thanaArray;

            return $this->sendResponse($data, 'Zilla Wise Thana List');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }
    }

    public function getThanaByMetropoliton(Request $request)
    {
        try {
            $metropolitonId = $request->query('metropoliton_id');

            if ($metropolitonId != null) {
                $thana = DB::table('geo_thanas')->select('id as thana_id','geo_metropolitan_id as metropolitan_id','thana_name_eng','thana_name_bng')->where('geo_metropolitan_id', $metropolitonId)->get();
            } else {
                $thana = DB::table('geo_thanas')->select('id as thana_id','geo_metropolitan_id as metropolitan_id','thana_name_eng','thana_name_bng')->get();
            }
            return $this->sendResponse($thana, 'Thana List');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }
    }

}
