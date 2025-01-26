<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Criminal;
use App\Models\Requisition;
use Illuminate\Http\Request;
use App\Models\CitizenComplain;
use App\Models\CriminalConfession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Input\Input;

class CitizenComplainController extends Controller
{

    public function showCitizenComplain(Request $request)
    {

        $office_id = globalUserInfo()->office_id;
        $roleID = globalUserInfo()->role_id;
        $officeinfo = DB::table('office')->where('id',$office_id)->first();
        $division_id = $officeinfo->division_id;
        $district_id = $officeinfo->district_id;

                    
        $divid =  $division_id;
        $zillaId = $district_id;

        // $userProfile = $this->auth->getProfile();


        $conditions = [
            ['citComp.divid', '=', $divid],
            ['citComp.zillaid', '=', $zillaId]
        ];
    
        // Handle post request
        $numberPage = 1;
        if ($request->isMethod('post')) {
            $parameters = $request->except('_token');
            session(['citizen_complain_parameters' => $parameters]);
        } else {
            $numberPage = $request->query('page', 1);
            $parameters = session('citizen_complain_parameters', []);
        }

        // Sorting and ordering
        $parameters['order'] = 'created_date DESC';

        // Define statuses
        $ignore = 'ignore';
        $initial = 'initial';

    // Query for citizen complaints with 'initial' status
    $citizenComplainQuery = DB::table('citizen_complains AS citComp')
        ->select('citComp.id', 'citComp.name_bng', 'citComp.name_eng', 'citComp.mobile', 'citComp.location',
            DB::raw('DATE_FORMAT(citComp.complain_date, "%Y-%m-%d") AS complain_date'),
            DB::raw('DATE_FORMAT(citComp.created_date, "%Y-%m-%d") AS created_date'),
            'citComp.complain_details', 'citComp.user_idno', 'citComp.subject', 'division.division_name_bn as divname ', 'zilla.district_name_bn as zillaname')
        ->leftJoin('division', 'citComp.divid', '=', 'division.id')
        ->leftJoin('district as zilla', function($join) {
            $join->on('citComp.zillaId', '=', 'zilla.id')
                 ->on('citComp.divid', '=', 'zilla.division_id');
        })
        ->where('citComp.complain_status', $initial)
        ->where($conditions)
        ->groupBy('citComp.id', 'citComp.divid', 'citComp.zillaId')
        ->orderBy('citComp.created_date', 'DESC')
        ->orderBy('citComp.name_bng');

    $citizen_complain = $citizenComplainQuery->paginate(10);
    //  dd($citizen_complain);
    // Query for ignored citizen complaints
    $citizenComplainIgnoreQuery = DB::table('citizen_complains AS citComp')
        ->select('citComp.id')
        ->leftJoin('division', 'citComp.divid', '=', 'division.id')
        ->leftJoin('district as zilla', 'citComp.zillaId', '=', 'zilla.id')
        ->leftJoin('upazila', 'citComp.upazilaid', '=', 'upazila.id')
        ->where('citComp.complain_status', $ignore)
        ->groupBy('citComp.id');
    
    $citizen_complain_ignore = $citizenComplainIgnoreQuery->get();

    // Count total, unchanged, ignored, and accepted complaints
    $citizen_complain_total = DB::table('citizen_complains')->count();
    $unchange_complain = $citizen_complain->total();
    $ignored_complain = count($citizen_complain_ignore);
    $accepted_complain = $citizen_complain_total - $unchange_complain - $ignored_complain;
 
            return view('citizen_complain.showCitizenComplain', [
                'page' => $citizen_complain,
                'citizen_complain_total' => $citizen_complain_total,
                'accepted_complain' => $accepted_complain,
                'initial' => $ignored_complain,
                'unchange_complain' => $unchange_complain,
            ]);
    }
    //
    public function showRequisition(Request $request)
    {

 
        $office_id = globalUserInfo()->office_id;
        $roleID = globalUserInfo()->role_id;
        $officeinfo = DB::table('office')->where('id',$office_id)->first();
        $division_id = $officeinfo->division_id;
        $district_id = $officeinfo->district_id;

                    
        $divid =  $division_id;
        $zillaId = $district_id;
        $accepted = 'accepted';
       $initial = 'initial';
       $numberPage = $request->get('page', 1);

        // If POST request, get input parameters
        if (request()->isMethod('post')) {
            $parameters = request()->all();
        } else {
            $parameters = [];
        }

        // Query citizen complaints with requisition
        $requisition = DB::table('requisitions as reqs')
        ->join('users as magistrate', 'magistrate.id', '=', 'reqs.magistrate_own_id')
        ->join('citizen_complains as citz', 'citz.id', '=', 'reqs.complain_id')
        ->leftJoin('division', 'citz.divid', '=', 'division.id')
        ->leftJoin('district as zilla', function ($join) {
            $join->on('citz.zillaid', '=', 'zilla.id')
                ->on('citz.divid', '=', 'zilla.division_id');
        })
        ->leftJoin('upazila', function ($join) {
            $join->on('citz.upazilaid', '=', 'upazila.id')
                ->on('citz.zillaid', '=', 'upazila.district_id');
        })
        ->select([
            'reqs.id as id',
            'citz.user_idno as user_idno',
            'citz.id as complain_id',
            'reqs.created_date as cdate',
            'reqs.estimated_date as esdate',
            'citz.location as location',
            'citz.subject as subject',
            'citz.name_bng as name_bng',
            'citz.complain_status as complain_status',
            'magistrate.name as magistrate_name',
            'magistrate.id as magistrate_id',
            'division.division_name_bn as divname',
            'zilla.district_name_bn as zillaname',
            'upazila.upazila_name_bn as upazilaname'
        ])
        ->where('citz.divid', $divid)
        ->where('citz.zillaid', $zillaId)
        ->where('citz.complain_status', '!=', $initial)
        ->orderBy('reqs.created_date', 'DESC')
        ->paginate(10, ['*'], 'page', $numberPage);

 
        // $magistrates = DB::table('users as mag')
        // ->select([
        //     'mag.id as id',
        //     DB::raw('CONCAT(mag.name as name_bng, "-", mag.designation) as name_eng'),
        //     'mag.national_id',
        //     'mag.mobile',
        //     'mag.email'
        // ])
        // // ->join('office as job_des', 'job_des.magistrate_id', '=', 'mag.id')
        // ->where('job_des.zillaid', $zillaId)
        // ->where('job_des.divid', $divid)
        // ->where('job_des.is_active', 1)
        // ->where('job_des.user_type_id', 6)
        // ->get();
        $magistrates = DB::table('users as mag')
        // ->join('users as mag', 'mag.id', '=', 'p.magistrate_id')
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
            'mag.email'
        ])
        ->where('of.division_id',$divid)
        ->where('of.district_id',$zillaId)
        ->where('dp.role_id',26)
        ->get();
        // dd($magistrates);

        return view('citizen_complain.showRequisition', ['magistrate_id' => $magistrates,'requisition'=>$requisition]);
    }

    public function ignore_complain_new(Request $request,$id)
    {

        
        $citizen_complain = CitizenComplain::find($id);
        // if (!$citizen_complain) {
        //     $this->flash->error("citizen_complain was not found");
        //     return $this->dispatcher->forward(array(
        //         "controller" => "citizen_complain",
        //         "action" => "index"
        //     ));
        // }

        $user = Auth::user();

        $citizen_complain->complain_status = "ignore";


        $citizen_complain->update_by = $user->name;
        $citizen_complain->update_date = date('Y-m-d');

        if (!$citizen_complain->save()) {

           // Loop through the error messages and flash them to the session
            foreach ($citizen_complain->getMessages() as $message) {
                session()->flash('error', $message);
            }
            return redirect()->action([CitizenComplainController::class, 'edit'], ['name_bng' => $citizen_complain->name_bng]);
            // return redirect()->route('citizen_complain.showCitizenComplain');
        }

        session()->flash('success', 'অভিযোগটি বাতিল করা হল');
        // return $this->dispatcher->forward(array(
        //     "controller" => "citizen_complain",
        //     "action" => "showCitizenComplain"
        // ));
        return redirect()->route('citizen_complain.showCitizenComplain');
    }

    public function ignore_complain(Request $request, $id)
    {
         
             $entityID = $id;

            $citizen_complain = CitizenComplain::find($id);
            // if (!$citizen_complain) {
            //     $this->flash->error("citizen_complain was not found");
            //     return $this->dispatcher->forward(array(
            //         "controller" => "citizen_complain",
            //         "action" => "index"
            //     ));
            // }

            $user = Auth::user();;

            $citizen_complain->complain_status = "ignore";
            $citizen_complain->update_by = $user->name;
            $citizen_complain->update_date = date('Y-m-d');

            if (!$citizen_complain->save()) {
                foreach ($citizen_complain->getErrors() as $error) {
                    // Flash the error messages
                    session()->flash('error', $error);
                }
                // Redirect back with errors
                return redirect()->back();
                // return $this->dispatcher->forward(array(
                //     "controller" => "citizen_complain",
                //     "action" => "edit",
                //     "params" => array($citizen_complain->name_bng)
                // ));
            }
            // $fileCategory = 'CitizenComplaint';
            // $this->fileContentService->fileDeleteByEntityID($entityID, $fileCategory);
            // $this->flash->success("অভিযোগটি বাতিল করা হল");
            // return $this->dispatcher->forward(array(
            //     "controller" => "citizen_complain",
            //     "action" => "showCitizenComplain"
            // ));
            // $fileCategory = 'CitizenComplaint';
            // $this->fileContentService->fileDeleteByEntityID($entityID, $fileCategory);
            // Flash a success message
            session()->flash('success', 'অভিযোগটি বাতিল করা হল');
            // Redirect to the specified action in the controller
            // return redirect('citizen_complain.showCitizenComplain/'.$id);
            return redirect()->route('citizen_complain.showCitizenComplain');
        
    }
    public function edit(Request $request,$id)
    {
    
            $citizen_complain = CitizenComplain::find($id);
            // if (!$citizen_complain) {
            //     $this->flash->error("citizen_complain was not found");
            //     return $this->dispatcher->forward(array(
            //         "controller" => "citizen_complain",
            //         "action" => "index"
            //     ));
            // }

            // $this->view->name_bng = $citizen_complain->name_bng;

            // $this->tag->setDefault("name_bng", $citizen_complain->name_bng);
            // $this->tag->setDefault("name_eng", $citizen_complain->name_eng);
            // $this->tag->setDefault("complain_details", $citizen_complain->complain_details);
            // $this->tag->setDefault("complain_date", $citizen_complain->complain_date);
            // $this->tag->setDefault("location", $citizen_complain->location);
            // $this->tag->setDefault("complain_status", $citizen_complain->complain_status);
            // $this->tag->setDefault("feedback", $citizen_complain->feedback);
            // $this->tag->setDefault("feedback_date", $citizen_complain->feedback_date);
            // $this->tag->setDefault("magistrate_id", $citizen_complain->magistrate_id);
            // $this->tag->setDefault("created_by", $citizen_complain->created_by);
            // $this->tag->setDefault("created_date", $citizen_complain->created_date);
            // $this->tag->setDefault("update_by", $citizen_complain->update_by);
            // $this->tag->setDefault("update_date", $citizen_complain->update_date);
            // $this->tag->setDefault("delete_status", $citizen_complain->delete_status);
            // $this->tag->setDefault("id", $citizen_complain->id);
            // $this->tag->setDefault("district_id", $citizen_complain->district_id);
            // $this->tag->setDefault("complain_id", $citizen_complain->complain_id);
            // $this->tag->setDefault("citizen_idno", $citizen_complain->citizen_idno);

        // Iterate through each complain and prepare data
        $data = [];
        foreach ($citizen_complain as $citizencomplain) {
            $data = [
                "name_bng"=>$citizencomplain->name_bng,
                "complain_details"=>$citizencomplain->complain_details,
                "complain_date"=>$citizencomplain->complain_date,
                "location"=>$citizencomplain->location,
                "complain_status"=>$citizencomplain->complain_status,
                "feedback"=>$citizencomplain->feedback,
                "feedback_date"=>$citizencomplain->feedback_date,
                "magistrate_id"=>$citizencomplain->magistrate_id,
                "created_by"=>$citizencomplain->created_by,
                "created_date"=>$citizencomplain->created_date,
                "update_by"=>$citizencomplain->update_by,
                "update_date"=>$citizencomplain->update_date,
                "delete_status"=>$citizencomplain->delete_status,
                "id"=>$citizencomplain->id,
                "district_id"=>$citizencomplain->district_id,
                "complain_id"=>$citizencomplain->complain_id,
                "citizen_idno"=>$citizencomplain->citizen_idno,
            ];
         }

        return view('citizen_complain.edit', $data); 

    }


     /**
     * Saves a requisition edited
     *
     */
    public function save(Request $request)
    {

        // if (!$this->request->isPost()) {
        //     return $this->dispatcher->forward(array(
        //         "controller" => "requisition",
        //         "action" => "index"
        //     ));
        // }

        $id = $request->id;

        $requisition = Requisition::find($id);
        // if (!$requisition) {
        //     $this->flash->error("requisition does not exist " . $id);
        //     return $this->dispatcher->forward(array(
        //         "controller" => "requisition",
        //         "action" => "index"
        //     ));
        // }

        $requisition->id = $request->id;
        $requisition->complain_id = $request->complain_id;
        $requisition->complain_type_id = $request->complain_type_id;
        $requisition->authority_own_id = $request->authority_own_id;
        $requisition->magistrate_own_id = $request->magistrate_own_id;
        $requisition->status_own = $request->status_own;
        $requisition->authority_1_id = $request->authority_1_id;
        $requisition->magistrate_1_id = $request->magistrate_1_id;
        $requisition->status_1 = $request->status_1;
        $requisition->authority_2_id = $request->authority_2_id;
        $requisition->magistrate_2_id = $request->magistrate_2_id;
        $requisition->status_2 = $request->status_2;
        $requisition->authority_3_id = $request->authority_3_id;
        $requisition->magistrate_3_id = $request->magistrate_3_id;
        $requisition->status_3 = $request->status_3;
        $requisition->authority_4_id = $request->authority_4_id;
        $requisition->magistrate_4_id = $request->magistrate_4_id;
        $requisition->status_4 = $request->status_4;
        $requisition->status = $request->status;
        $requisition->dateofcourt = $request->dateofcourt;
        $requisition->location = $request->location;
        $requisition->description = $request->description;
        $requisition->created_by = $request->created_by;
        $requisition->created_date = $request->created_date;
        $requisition->update_by = $request->update_by;
        $requisition->update_date = $request->update_date;
        $requisition->delete_status = $request->delete_status;
        if (!$requisition->save()) {

            foreach ($requisition->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "requisition",
                "action" => "edit",
                "params" => array($requisition->id)
            ));
        }

        $this->flash->success("অভিযোগটি সফল্ভাবে  সংরক্ষণ করা হয়েছে ।");
        return $this->dispatcher->forward(array(
            "controller" => "requisition",
            "action" => "index"
        ));

    }


    public function deletecaseview(Request $request){

        $roleID = globalUserInfo()->role_id;

        if ($roleID == 37 || $roleID =38) {
           
            
        }

        return view('deletecaseview'); 

    }
     public function deletecase(Request $request){
      
         $caseNumber_new = "";
         $new_service_str ="";
 
         $user = Auth::User();
         $update_by = $user->name;
         $update_date = date('Y-m-d');


         $caseNumber  = $request->case_no;  // 1967.01.16167.0001.16  // 42-001-১৫৬৭৫-000001/2016
         $exploded = $this->multiexplode(array("/","-","."),trim($caseNumber));  // [1967][01][16167][0001][16]
         $serviceld = $exploded[2];  // 3th slot is fixed for  service id

 
        //case number delete
        $dismissedCaseNumber = "case_no = '".$caseNumber ."-বাতিল করা হয়েছে ।'";

         $new_service_str = $this->ben_to_en_number_conversion($serviceld);
         if ($new_service_str == '')
              $new_service_str = $this->eng_to_bng_number_conversion($serviceld);
 
 
         $exploded[2] = $new_service_str;  // [1967][01][১৬১৬৭][0001][16]
 
         if(strlen($exploded[0]) == 4 ){// new
             $caseNumber_new = $exploded[0].".".$exploded[1].".".$exploded[2].".".$exploded[3].".".$exploded[4];
         }else{
             $caseNumber_new = $exploded[0]."-".$exploded[1]."-".$exploded[2]."-".$exploded[3]."/".$exploded[4];
         }
        //        1.	Get prosecution by  case number
         $condition ="";
        //  $condition .= "  case_no LIKE  '%$caseNumber_new%'" . " OR  case_no LIKE  '%$caseNumber%'";
 
        //  $phql = 'SELECT id
        //           FROM Mcms\Models\Prosecution AS prosecution
        //           WHERE ' . $condition . ' ';
 
 
        //  $query = $this->modelsManager->createQuery($phql);
        //  $prosecution = $query->execute();
       
          $prosecution = DB::table('prosecutions')
            ->select('id')
            ->where(function($query) use ($caseNumber_new, $caseNumber) {
                $query->where('case_no', 'LIKE', "%$caseNumber_new%")
                    ->orWhere('case_no', 'LIKE', "%$caseNumber%");
            })
          ->get();
           
         if(count($prosecution) > 0){
            $prosecution_id = $prosecution[0]->id;
             // Update deletestatus of Criminal
            //  $phql = 'SELECT GROUP_CONCAT(PROD.criminal_id ) AS criminal_id
            //              FROM Mcms\Models\ProsecutionDetails as PROD
            //              INNER JOIN Mcms\Models\Criminal AS criminal ON PROD.criminal_id =  criminal.id
            //              INNER JOIN Mcms\Models\Prosecution AS prosecution ON prosecution.id = PROD.prosecution_id
            //              WHERE  prosecution.id = 30356 ';
 
            //  $query = $this->modelsManager->createQuery($phql);
            //  $criminals = $query->execute();
 
             $criminals = DB::table('prosecution_details as PROD')
                        ->select(DB::raw('GROUP_CONCAT(PROD.criminal_id) AS criminal_id'))
                        ->join('criminals', 'PROD.criminal_id', '=', 'criminals.id')
                        ->join('prosecutions', 'prosecutions.id', '=', 'PROD.prosecution_id')
                        ->where('prosecutions.id',$prosecution[0]->id)
                        ->where('prosecutions.delete_status',1)
                        ->get();
         
             // strtoarray
             $criminalsarray = array();
             if(count($criminals) > 0){

                $updateData1 = [
                    'delete_status' => '2',
                    'updated_at' => $update_date,
                    'updated_by' => $update_by,
                ];

                 DB::table('criminals')
                ->whereIn('id', explode(',', $criminals[0]->criminal_id))
                ->update($updateData1);

                DB::table('criminal_confessions')
                ->whereIn('criminal_id', explode(',', $criminals[0]->criminal_id))
                ->update($updateData1);

                DB::table('order_sheets')
                ->where('prosecution_id', $prosecution_id)
                ->update($updateData1);

                DB::table('punishments')
                ->where('prosecution_id', $prosecution_id)
                ->update($updateData1);


                $updateData2 = [
                    'delete_status' => '2',
                    'updated_at' => $update_date,
                    'update_by' => $update_by,
                ];
                // $updateData['dismissed_case_number'] = $dismissedCaseNumber;
                DB::table('prosecutions')
                ->where('id', $prosecution_id)
                ->update(array_merge($updateData2, ['case_no' => $dismissedCaseNumber]));
                
                DB::table('prosecution_details')
                ->where('prosecution_id', $prosecution_id)
                ->update($updateData1);


                $update_date3 = [
                    'delete_status' => '2',
                    'update_date' => $update_date,
                    'update_by' => $update_by,
                ];
                DB::table('court_complain_infos')
                ->where('prosecution_id', $prosecution_id)
                ->update($update_date3);
    
                // $criminalsarray =  $this->strtoarray($criminals[0]->criminal_id,',');

                //  if(sizeof($criminalsarray) > 0){  // more than one criminal
                //      foreach ($criminalsarray as $cid) {
                //          $criminal_info = Criminal::find($cid);
                //          if (!$criminal_info->delete()) {
                //             foreach ($criminal_info->getErrors() as $error) {
                                
                //                 session()->flash('error', $error);
                //             }
                //         }
                //          //  3.Delete criminal confession
                //          $criminal_Confession = CriminalConfession::find($cid);
                //          if (!$criminal_Confession->delete()) {
                //             foreach ($criminal_Confession->getErrors() as $error) {
                              
                //                 session()->flash('error', $error);
                //             }
                //          }
                //      }
                //  }else{ // single criminal
                   
                //      $criminal_info = Criminal::find($criminals[0]->criminal_id);
                //      if (!$criminal_info->delete()) {
                //         foreach ($criminal_info->getErrors() as $error) {
                //             session()->flash('error', $error);
                //         }
                //      }
                //      // 3.Delete criminal confession
                //      $criminal_Confession = CriminalConfession::find($criminals[0]->criminal_id);
                //      if (!$criminal_Confession->delete()) {
                //         foreach ($criminal_Confession->getErrors() as $error) {
                //             session()->flash('error', $error);
                //         }
                //      }
                //  }
             }
 
 
            //            5.	Delete punishment details
            //            6.	Delete punishment
            //            7.	Delete prosecution details
            //            8.	Delete prosecution
            //            9.	Delete court complain info
            
             $msg["flag"] = "true";
             $msg["message"] = "মামলাটি  সফলভাবে বাতিল করা হয়েছে । " .$prosecution[0]->id;
         }else{
             $msg["flag"] = "false";
             $msg["message"] = "মামলাটি  নাই ।".$caseNumber_new;
         }
 
         return response()->json($msg);
     }

    //  controller method  start

     public function multiexplode($delimiters,$string) {

        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }
    function strtoarray($string, $part, $unqiue = true)
    {
        $string = trim($string);
        $ary = explode($part, $string);

        return ($unqiue ? array_unique($ary) : $ary);
    }

    //  controller method  end

    function  eng_to_bng_number_conversion($eng_number){

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
    function ben_to_en_number_conversion($ben_number) {


        $eng_number = '';

        $len =  mb_strlen($ben_number, 'UTF-8');

        $ben_number = $this->utf8Split($ben_number);

        for ($i = 0; $i < $len; $i++){
        if ($ben_number[$i] == "০" ) $eng_number .=  '0';
        if ($ben_number[$i] == "১" ) $eng_number .=  '1';
        if ($ben_number[$i] == "২" ) $eng_number .=  '2';
        if ($ben_number[$i] == "৩" ) $eng_number .=  '3';
        if ($ben_number[$i] == "৪" ) $eng_number .=  '4';
        if ($ben_number[$i] == "৫" ) $eng_number .=  '5';
        if ($ben_number[$i] == "৬" ) $eng_number .=  '6';
        if ($ben_number[$i] == "৭" ) $eng_number .=  '7';
        if ($ben_number[$i] == "৮" ) $eng_number .=  '8';
        if ($ben_number[$i] == "৯" ) $eng_number .=  '9';
       }

       return $eng_number;
    }

    public function utf8Split($str, $len = 1){
        $arr = array();
        $strLen = mb_strlen($str, 'UTF-8');
        for ($i = 0; $i < $strLen; $i++)
        {
            $arr[] = mb_substr($str, $i, $len, 'UTF-8');
        }
        return $arr;
    }


    public function getCitizen_complainByReqId(Request $request)
    {
    
            $childs =[];
            $reqid = $request->reqid;

            $citizen_complain = [];
            if ($reqid) {
                $citizen_complain = DB::table('requisitions AS req')
                ->join('citizen_complains AS cmp', 'cmp.id', '=', 'req.complain_id')
                ->where('req.id', $reqid)
                ->select([
                    'cmp.subject AS subject',
                    DB::raw('DATE(cmp.created_date) AS date'), 
                    'cmp.name_bng AS name',
                    'cmp.mobile AS mobile',
                    'cmp.email AS email',
                    'cmp.complain_details AS complain_details'
                ])
                ->get(); 
            }
          

            foreach ($citizen_complain as $t) {
                $childs[] = array('name' => $t->name,
                    'subject' => $t->subject . "(" . $t->date . ")",
                    'complain_details' => $t->complain_details,
                    'mobile' => $t->mobile,);

            }
            return response()->json($childs, 200, [], JSON_UNESCAPED_UNICODE);
     
      
    }


}
