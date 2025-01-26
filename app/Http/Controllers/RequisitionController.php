<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Prosecution;
use App\Models\Requisition;
use App\Models\RegisterList;
use Illuminate\Http\Request;
use App\Models\RegisterLabel;
use App\Models\CitizenComplain;
use App\Models\CourtComplainInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RequisitionController extends Controller
{

    public function initialize()
    {

//        $this->view->cleanTemplateBefore();
//        $this->view->setLayout('adm');
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for requisition
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Requisition", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $requisition = Requisition::find($parameters);
        if (count($requisition) == 0) {
            $this->flash->notice("তথ্য নাই ।");
            return $this->dispatcher->forward(array(
                "controller" => "requisition",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $requisition,
            "limit" => 10,
            "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displayes the creation form
     */
    public function newAction()
    {
        // $this->view->setTemplateBefore('private');
    }

    public function getRequisitionByCourtIdAction()
    {
        $this->view->disable();
        $childs = array();
        if (($this->request->isPost()) && ($this->request->isAjax() == true)) {
            $courtId = $this->request->getQuery("ld", "string");

            $tmp = array();
            if ($courtId) {
                //  $tmp = Requisition::find("court_id=" .$courtId);

                $phql = 'SELECT req.id AS id, req.dateofcourt AS dateofcourt,
                req.location AS location,
                citComp.subject AS subject
                FROM Mcms\Models\Requisition AS req
                INNER JOIN Mcms\Models\CitizenComplain AS citComp  ON citComp.id = req.complain_id
                WHERE court_id ="' . $courtId . '" AND req.requisition_status = 1';

                $query = $this->modelsManager->createQuery($phql);
                $citizen_complains = $query->execute();

                foreach ($citizen_complains as $t) {
                    $childs[] = array('id' => $t->id, 'name' => $t->subject . ',' . $t->dateofcourt . ',' . $t->location);

                }
            }
        }
       //        var_dump($childs);

        $response = new \Phalcon\Http\Response();
        $response->setContentType('application/json', 'UTF-8');
        $response->setContent(json_encode($childs));
        return $response;
    }

    public function editRequisition(Request $request,$id){

         $entityID = $id;
         $fileCategory = 'CitizenComplaint';
        // $citigenComplainFile = $this->fileContentService->fileFindByEntityID($entityID, $fileCategory);
        // $this->view->uploaded_file = $citigenComplainFile;

        // Assuming $id is defined elsewhere in your code
            $citizen_complains = DB::table('citizen_complains as citComp')
            ->select(
                'citComp.id as id',
                'citComp.name_bng as name_bng',
                'citComp.name_eng as name_eng',
                'citComp.mobile as mobile',
                'citComp.location as location',
                'citComp.complain_date as complain_date',
                'citComp.created_date',
                'citComp.complain_details as complain_details',
                'citComp.subject as subject',
                'citComp.user_idno as user_idno',
                'division.division_name_bn as divname',
                'zilla.district_name_bn as zillaname',
                'upazila.upazila_name_bn as upazilaname'
            )
            ->leftJoin('division as division', 'citComp.divid', '=', 'division.id')
            ->leftJoin('district as zilla', function ($join) {
                $join->on('citComp.zillaid', '=', 'zilla.id')
                     ->on('citComp.divid', '=', 'zilla.division_id');
            })
            ->leftJoin('upazila as upazila', function ($join) {
                $join->on('upazila.id', '=', 'citComp.upazilaid')
                    ->on('citComp.zillaid', '=', 'upazila.district_id');
            })
            ->where('citComp.id', $id)
            ->distinct()
            ->get();

       
          //AND upazila.upazilaid = citComp. upazilaid
        //        $citizen_complain = CitizenComplain::findFirstByid($id);
        // if (!$citizen_complains) {
        //     $this->flash->error("citizen_complain was not found");
        //     return $this->dispatcher->forward(array(
        //         "controller" => "citizen_complain",
        //         "action" => "index"
        //     ));
        // }

       
        // Iterate through each complain and prepare data
        $data = [];
        foreach ($citizen_complains as $citizen_complain) {
            $data = [
                'name_eng' => $citizen_complain->name_eng,
                'name_bng' => $citizen_complain->name_bng,
                'mobile' => $citizen_complain->mobile,
                'user_idno' => $citizen_complain->user_idno,
                'created_date' => $citizen_complain->created_date,
                'divname' => $citizen_complain->divname,
                'zillaname' => $citizen_complain->zillaname,
                'upazilaname' => $citizen_complain->upazilaname,
                'cmp_location' => $citizen_complain->location,
                'subject' => $citizen_complain->subject,
                'complain_details' => $citizen_complain->complain_details,
                'complain_date' => $citizen_complain->complain_date,
                'complain_id' => $citizen_complain->id,
            ];
        }

   
        $office_id = globalUserInfo()->office_id;
        $roleID = globalUserInfo()->role_id;
        $officeinfo = DB::table('office')->where('id',$office_id)->first();
        $division_id = $officeinfo->division_id;
        $district_id = $officeinfo->district_id;

        // $admlocation  = $this->auth->getUserLocation();

        $divid = $division_id;
        $zillaId = $district_id;

        // $phql1 = 'SELECT mag.id as id,
        //           CONCAT(mag.name_bng,"-",mag.designation_bng,"-",job_des.location_str ,"-",job_des.location_details)as name_eng,
        //           national_id as national_id,
        //           mag.mobile as mobile,
        //           mag.email as email
        //          FROM Mcms\Models\Magistrate as mag
        //           INNER JOIN  Mcms\Models\JobDescription as job_des  ON job_des.user_id = mag.user_id
        //         WHERE job_des.zillaid = "'.$zillaId.'" AND job_des.divid = "'.$divid.'"
        //         AND job_des.is_active =1  AND job_des.user_type_id = 6
        //         ';
        // $query = $this->modelsManager->createQuery($phql1);
        // $magistrate = $query->execute();
        // $this->view->magistrate_id = $magistrate;

            $data['magistrates'] = DB::table('users as mag')
            ->join('doptor_user_access_info as dp', 'dp.user_id', '=', 'mag.id')
            ->join('mc_role as r', 'r.id', '=', 'dp.role_id')
            ->join('office as job_des', 'job_des.id', '=', 'mag.office_id')
            ->select(
                'mag.id as id',
                DB::raw('CONCAT(mag.name,"-",r.role_name,"-",job_des.office_name_bn) as name_eng'),
                // 'mag.national_id as national_id',
                'mag.mobile_no as mobile',
                'mag.email as email'
            )
            ->where('job_des.district_id', $zillaId)
            ->where('job_des.division_id', $divid)
            ->where('dp.role_id',26)
            ->get();


         
            // Return the view and pass the data
        return view('requisition.editRequisition',$data);


    }
    public function edit2RequisitionAction($id)
    { // requisition id

        // Get Citizen complain by  ID

        // $this->view->setTemplateBefore('private');//
        if (!$this->request->isPost()) {


            $requisition = Requisition::findFirstByid($id);
            if (!$requisition) {
                $this->flash->error("requisition was not found");
                return $this->dispatcher->forward(array(
                    "controller" => "requisition",
                    "action" => "index"
                ));
            }

            $citizen_complain = CitizenComplain::findFirstByid($requisition->complain_id);
            if (!$citizen_complain) {
                $this->flash->error("citizen_complain was not found");
                return $this->dispatcher->forward(array(
                    "controller" => "citizen_complain",
                    "action" => "index"
                ));
            }

            $this->view->name_bng = $citizen_complain->name_bng;

            $this->tag->setDefault("name_bng", $citizen_complain->name_bng);
            $this->tag->setDefault("name_eng", $citizen_complain->name_eng);
            $this->tag->setDefault("complain_details", $citizen_complain->complain_details);
            $this->tag->setDefault("complain_date", $citizen_complain->complain_date);
            $this->tag->setDefault("location", $citizen_complain->location);
            $this->tag->setDefault("complain_status", $citizen_complain->complain_status);
            $this->tag->setDefault("feedback", $citizen_complain->feedback);
            $this->tag->setDefault("feedback_date", $citizen_complain->feedback_date);
            //$this->tag->setDefault("magistrate_id", $citizen_complain->magistrate_id);
            $this->tag->setDefault("created_by", $citizen_complain->created_by);
            $this->tag->setDefault("created_date", $citizen_complain->created_date);
            $this->tag->setDefault("update_by", $citizen_complain->update_by);
            $this->tag->setDefault("update_date", $citizen_complain->update_date);
            $this->tag->setDefault("delete_status", $citizen_complain->delete_status);
            $this->tag->setDefault("id", $citizen_complain->id);
            $this->tag->setDefault("divid", $citizen_complain->divid);
            $this->tag->setDefault("complain_id", $citizen_complain->complain_id);
            //$this->tag->setDefault("citizen_idno", $citizen_complain->citizen_idno);

            $this->view->magistrate_id = Magistrate::find();

        }
    }
    public function changeMagistrate(Request $request){
       
        $childs = array();

           $magistrate_id = $request->magistrate_id;
           $id = $request->id; // requisition id

          $requisition = Requisition::find($id);

            if (!$requisition) {
                $childs[] = array(
                    'msg' => "Court was not found"
                );
                return response()->json($childs, 200, [], JSON_UNESCAPED_UNICODE);
            }
         
            $requisition->id = $id;
            $requisition->magistrate_own_id = $magistrate_id;

            //
            $court_complain_info = CourtComplainInfo::where('requisition_id', $id)->first();
        
            // citizen complain status change
            $citizen_complain = CitizenComplain::where('user_idno',$court_complain_info->user_idno)->first();
            $citizen_complain->complain_status='re-send';
           
            if($court_complain_info->case_number !=''){
               
                $childs[] = array(
                    'msg' => "দুঃখিত! নির্বাহী ম্যাজিস্ট্রেটপরিবর্তন করা  যায়নি ।"
                );
            }else{
               
                if (!$requisition->save()) {

                    $childs[] = array(
                        'msg' => "দুঃখিত! নির্বাহী ম্যাজিস্ট্রেট পরিবর্তন করা যায়নি ।"
                    );
                }else{
                    $court_complain_info->magistrate_id = $magistrate_id;
                    if($court_complain_info->save()){
                        If(!$citizen_complain->save()){
                            $errorMessage = "";
                            foreach ($citizen_complain->getMessages() as $message) {
                                $errorMessage .= $message;
                            }
                           
                        }
                    }
                  
                    $childs[] = array(
                        'msg' => "নির্বাহী ম্যাজিস্ট্রেট সফলভাবে পরিবর্তন করা হয়েছে।"
                    );
                }
            }
        return response()->json($childs, 200);
        // $response = new \Phalcon\Http\Response();
        // $response->setContentType('application/json', 'UTF-8');
        // $response->setContent(json_encode($childs));
        // return $response;
    }

    public function createRequisition(Request $request)
    {

        //$this->view->disable();
        // if (!$this->request->isPost()) {
        //     return $this->dispatcher->forward(array(
        //         "controller" => "requisition",
        //         "action" => "index"
        //     ));
        // }

        $id = $request->complain_id;
        $accept = $request->accept;
  
        if($accept){
            $magistrate_id = $request->magistrate_id;
            if (!$magistrate_id) {
            
                session()->flash('success', 'Please Select Magistrate');
                return redirect()->route('requisition.editRequisition', ['id' => request()->post('complain_id')]);
            }
            // Get Category
            // $category_id = $this->auth->getDesignationByUser();  //  Designation_id dc = 1 // magistrate = 6 // adm = 2
            $user = Auth::user();
            $roleID = globalUserInfo()->role_id;
            $complain_id = $request->complain_id;

            $requisition = new Requisition();


            $requisition->id = "";
            $requisition->complain_id =  $request->complain_id;
            $requisition->complain_type_id = 1;

            $requisition->authority_own_id = $roleID;
            $requisition->magistrate_own_id = $request->magistrate_id;
            $requisition->status_own = "send";

            $requisition->status = $request->status;
            $requisition->dateofcourt = $request->dateofcourt;
            $requisition->location = $request->location;
            $requisition->description = $request->description;

            $requisition->created_by =  $user->name;
            $requisition->created_date = date('Y-m-d');
            $requisition->update_by =  $user->name;
            $requisition->update_date = date('Y-m-d');
            $requisition->delete_status = 1;
            $requisition->status_court = 1;
            $requisition->requisition_status = 0;
            $es_date = $request->estimated_date;
            if($es_date){
                $requisition->estimated_date = $request->estimated_date;
            }else{
                $requisition->estimated_date= "";
            }
            $requisition->req_comment = $request->req_comment;

            if (!$requisition->save()) {
              
                foreach ($requisition->getMessages() as $message) {
                    session()->flash('error', $message);
                }
                // Redirecting to the controller action
                return redirect()->action([CitizenComplainController::class, 'showCitizenComplain']);
            } else {
            
                $citizen_complain = CitizenComplain::find($complain_id);
                if (!$citizen_complain) {
                    foreach ($requisition->getMessages() as $message) {
                        session()->flash('error', $message);
                    }
                    // Redirecting to the controller action
                    return redirect()->action([CitizenComplainController::class, 'showCitizenComplain']);
                }

                $user = Auth::user();
                $citizen_complain->complain_status = "accepted";
                $citizen_complain->update_by = $user->name;
                $citizen_complain->update_date = date('Y-m-d');

                if (!$citizen_complain->save()) {
                    
                  // Handling error messages
                    foreach ($citizen_complain->getMessages() as $message) {
                        session()->flash('error', $message);
                    }
                    // Redirecting with parameters
                    return redirect()->action([CitizenComplainController::class, 'showCitizenComplain'], ['name_bng' => $citizen_complain->name_bng]);
                }else{ 
                    // create Court Complain info
                    $court_complain_info = new CourtComplainInfo();
                    $user = Auth::user();
                    // $court_complain_info->assign(array(
                    //     'magistrate_id' => $requisition->magistrate_own_id,
                    //     'requisition_id' => $requisition->id,
                    //     'complain_id' => $requisition->complain_id,
                    //     'complain_type' => 1,
                    //     'comments' => 'test',
                    //     'complain_status' =>'accepted',
                    //     'office_name' => 'Dc Office',  // to do get location_str from Job_description
                    //     'user_idno' => $citizen_complain->user_idno,
                    //     'update_date'=>date('Y-m-d'),
                    //     'update_by'=>$user->name
                    // ));
                    // Assign values to the model using the fill method
                    $court_complain_info->fill([
                        'magistrate_id' => $requisition->magistrate_own_id,
                        'requisition_id' => $requisition->id,
                        'complain_id' => $requisition->complain_id,
                        'complain_type' => 1,
                        'comments' => 'test',
                        'complain_status' => 'accepted',
                        'office_name' => 'Dc Office',  // To do: get location_str from Job_description
                        'user_idno' => $citizen_complain->user_idno,
                        'update_date' => date('Y-m-d'), // Using Laravel's now() for the current date
                        'update_by' => $user->name,
                    ]);

                    if (!$court_complain_info->save()) {
                        // Loop through the error messages and add them to the session flash data
                        foreach ($court_complain_info->getErrors() as $error) {
                            // Assuming you have a method to flash error messages
                            session()->flash('error', $error); // Use session flash to store error messages
                        }
                    
                        // Redirect to the showCitizenComplain action in the citizen_complain controller
                        return redirect()->action([CitizenComplainController::class, 'showCitizenComplain']);
                    }
                }
            }
        


       // Flash success message
        session()->flash('success', 'অভিযোগটি সফল্ভাবে প্রেরণ হয়েছে ।');
        // Redirecting to the controller action
        return redirect()->action([CitizenComplainController::class, 'showCitizenComplain']);
     }elseif($request->delete) {
        return redirect()->action([CitizenComplainController::class, 'ignore_complain_new'], ['id' => $id]);
    }else {
      //no button pressed
          // Flash notice message
            session()->flash('notice', 'তথ্য পাওয়া যায়নি');
            // Redirecting to the controller action
            // return redirect()->action([ProfileAdmController::class, 'caseTracker']);
    }

    return response()->json('অভিযোগটি সফল্ভাবে প্রেরণ হয়েছে ।', 200, [], JSON_UNESCAPED_UNICODE);
    }


    public function showOwnRequisitionListAction()
    {

        //$this->view->setTemplateBefore('private');//
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Requisition", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        // Get Category
        $category_id = $this->auth->getCategoryByUser();

        $conditions = "authority_own_id = $category_id";

        $requisition = Requisition::find(array(
            $conditions,
            "bind" => $parameters
        ));

        // get Complain information
        $complain_id = "";

        foreach ($requisition as $i) {
            $complain_id = $i->complain_id;
        }
        $citizen_complain = CitizenComplain::findFirstByid($complain_id);
        if (!$citizen_complain) {
            $this->flash->error("তথ্য নাই ।");
            return $this->dispatcher->forward(array(
                "controller" => "citizen_complain",
                "action" => "index"
            ));
        }

        $this->view->name_bng = $citizen_complain->name_bng;

        $this->tag->setDefault("name_bng", $citizen_complain->name_bng);
        $this->tag->setDefault("name_eng", $citizen_complain->name_eng);
        $this->tag->setDefault("complain_details", $citizen_complain->complain_details);
        $this->tag->setDefault("complain_date", $citizen_complain->complain_date);
        $this->tag->setDefault("location", $citizen_complain->location);
        $this->tag->setDefault("complain_status", $citizen_complain->complain_status);
        $this->tag->setDefault("feedback", $citizen_complain->feedback);
        $this->tag->setDefault("feedback_date", $citizen_complain->feedback_date);

        $this->tag->setDefault("created_by", $citizen_complain->created_by);
        $this->tag->setDefault("created_date", $citizen_complain->created_date);
        $this->tag->setDefault("update_by", $citizen_complain->update_by);
        $this->tag->setDefault("update_date", $citizen_complain->update_date);
        $this->tag->setDefault("delete_status", $citizen_complain->delete_status);
        $this->tag->setDefault("id", $citizen_complain->id);

        $this->tag->setDefault("complain_id", $citizen_complain->complain_id);


        if (count($requisition) == 0) {
            $this->flash->notice("তথ্য নাই ।");
            return $this->dispatcher->forward(array(
                "controller" => "requisition",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $requisition,
            "limit" => 10,
            "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    public function showOthersRequisitionListAction()
    {

        //$this->view->setTemplateBefore('private');//
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Requisition", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        // Get Category
        $category_id = $this->auth->getCategoryByUser();


//        $conditions = "";


        $phql = 'SELECT *
                    FROM Mcms\Models\Requisition as re
                    WHERE
                    COALESCE( re.authority_own_id ,"" ) <> "' . $category_id . '" AND (re.authority_1_id = "' . $category_id . '"
                        OR re.authority_2_id = "' . $category_id . '" OR
                        re.authority_3_id = "' . $category_id . '" OR re.authority_4_id = "' . $category_id . '" )';


        $query = $this->modelsManager->createQuery($phql);
        $requisition = $query->execute();


//        $requisition = Requisition::find(array(
//            $conditions,
//            "bind" => $parameters
//        ));

        if (count($requisition) == 0) {
            $this->flash->notice("তথ্য নাই ।");
            return $this->dispatcher->forward(array(
                "controller" => "requisition",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $requisition,
            "limit" => 10,
            "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Searches for requisition
     */
    public function showRequisitionForMagistrateAction()
    {
//        $this->view->cleanTemplateBefore();
//        $this->view->setTemplateBefore('magistrate');
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Requisition", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id ";

        // Get Category
        $user = $this->auth->getUser();

        $phql1 = 'SELECT *
                    FROM Mcms\Models\Magistrate as ma
                    WHERE
                        ma.user_id = "' . $user->id . '" ';


        $query = $this->modelsManager->createQuery($phql1);
        $magistrate = $query->execute();

        $id = "";
        foreach ($magistrate as $emp) {
            $id = $emp->id;
        }


        $status_court = 2;
        $status = 3;

        $phql = 'SELECT re.id, re. magistrate_own_id ,
                    citComp.location  as location,
                    "নাগরিক" AS complain_type,
                    citComp.subject as subject,
                    citComp.complain_id as complain_id,
                    citComp.created_date,
                    division.divname,
                    zilla.zillaname,
                    upazila.upazilaname
                    FROM Mcms\Models\Requisition as re
                    LEFT JOIN Mcms\Models\CitizenComplain  AS  citComp  ON  re. complain_id = citComp.id
                    LEFT JOIN Mcms\Models\Division AS division ON citComp.divid = division.divid
                    LEFT JOIN Mcms\Models\Zilla AS zilla ON division.divid = zilla.divid
                    LEFT JOIN Mcms\Models\Upazila AS upazila ON upazila.zillaid = zilla.zillaid
                    WHERE
                        (re.magistrate_own_id = "' . $id . '" OR re.magistrate_1_id = "' . $id . '"
                        OR re.magistrate_2_id = "' . $id . '" OR
                        re.magistrate_3_id = "' . $id . '" OR re.magistrate_4_id = "' . $id . '") AND division.divid = citComp.divid
                        AND zilla.zillaid = citComp.zillaid
                        AND upazila.upazilaid = citComp. upazilaid AND  re.court_id is NULL
                        GROUP BY re.id, citComp.divid, citComp.zillaId, citComp.upazilaid ORDER BY citComp.created_date DESC';


        $query = $this->modelsManager->createQuery($phql);
        $requisition = $query->execute();


        if (count($requisition) == 0) {
            $this->flash->notice("তথ্য নাই ।");
            return $this->dispatcher->forward(array(
                "controller" => "requisition",
                "action" => "indexMagistrate"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $requisition,
            "limit" => 10,
            "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Edits a requisition
     *
     * @param string $id
     */
    public function editRequisitionForMagistrateAction($id)
    {
        $this->view->cleanTemplateBefore();
        $this->view->setLayout('magistrate');
        if (!$this->request->isPost()) {

            $requisition = Requisition::findFirstByid($id);
            if (!$requisition) {
                $this->flash->error("requisition was not found");
                return $this->dispatcher->forward(array(
                    "controller" => "requisition",
                    "action" => "index"
                ));
            }

            $this->view->id = $requisition->id;

            $citizen_complain = CitizenComplain::findFirstByid($requisition->complain_id);

            $this->tag->setDefault("complain_id", $requisition->complain_id);
            $this->tag->setDefault("name_bng", $citizen_complain->name_bng);
            $this->tag->setDefault("complain_details", $citizen_complain->complain_details);
            $this->tag->setDefault("complain_date", $citizen_complain->complain_date);
            $this->tag->setDefault("location", $citizen_complain->location);
            $this->tag->setDefault("complain_status", $citizen_complain->complain_status);

            $this->tag->setDefault("divid", $citizen_complain->divid);
            $this->tag->setDefault("zillaId", $citizen_complain->zillaId);
            $this->tag->setDefault("upazilaid", $citizen_complain->upazilaid);

        }
    }

    /**
     * Edits a requisition
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $requisition = Requisition::findFirstByid($id);
            if (!$requisition) {
                $this->flash->error("requisition was not found");
                return $this->dispatcher->forward(array(
                    "controller" => "requisition",
                    "action" => "index"
                ));
            }

            $this->view->id = $requisition->id;

            $this->tag->setDefault("id", $requisition->id);
            $this->tag->setDefault("complain_id", $requisition->complain_id);
            $this->tag->setDefault("complain_type_id", $requisition->complain_type_id);
            $this->tag->setDefault("authority_own_id", $requisition->authority_own_id);
            $this->tag->setDefault("magistrate_own_id", $requisition->magistrate_own_id);
            $this->tag->setDefault("status_own", $requisition->status_own);
            $this->tag->setDefault("authority_1_id", $requisition->authority_1_id);
            $this->tag->setDefault("magistrate_1_id", $requisition->magistrate_1_id);
            $this->tag->setDefault("status_1", $requisition->status_1);
            $this->tag->setDefault("authority_2_id", $requisition->authority_2_id);
            $this->tag->setDefault("magistrate_2_id", $requisition->magistrate_2_id);
            $this->tag->setDefault("status_2", $requisition->status_2);
            $this->tag->setDefault("authority_3_id", $requisition->authority_3_id);
            $this->tag->setDefault("magistrate_3_id", $requisition->magistrate_3_id);
            $this->tag->setDefault("status_3", $requisition->status_3);
            $this->tag->setDefault("authority_4_id", $requisition->authority_4_id);
            $this->tag->setDefault("magistrate_4_id", $requisition->magistrate_4_id);
            $this->tag->setDefault("status_4", $requisition->status_4);
            $this->tag->setDefault("status", $requisition->status);
            $this->tag->setDefault("dateofcourt", $requisition->dateofcourt);
            $this->tag->setDefault("location", $requisition->location);
            $this->tag->setDefault("description", $requisition->description);
            $this->tag->setDefault("created_by", $requisition->created_by);
            $this->tag->setDefault("created_date", $requisition->created_date);
            $this->tag->setDefault("update_by", $requisition->update_by);
            $this->tag->setDefault("update_date", $requisition->update_date);
            $this->tag->setDefault("delete_status", $requisition->delete_status);

        }
    }

    /**
     * Creates a new requisition
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "requisition",
                "action" => "index"
            ));
        }

        $requisition = new Requisition();

        $requisition->id = $this->request->getPost("id");
        $requisition->complain_id = $this->request->getPost("complain_id");
        $requisition->complain_type_id = $this->request->getPost("complain_type_id");
        $requisition->authority_own_id = $this->request->getPost("authority_own_id");
        $requisition->magistrate_own_id = $this->request->getPost("magistrate_own_id");
        $requisition->status_own = $this->request->getPost("status_own");
        $requisition->authority_1_id = $this->request->getPost("authority_1_id");
        $requisition->magistrate_1_id = $this->request->getPost("magistrate_1_id");
        $requisition->status_1 = $this->request->getPost("status_1");
        $requisition->authority_2_id = $this->request->getPost("authority_2_id");
        $requisition->magistrate_2_id = $this->request->getPost("magistrate_2_id");
        $requisition->status_2 = $this->request->getPost("status_2");
        $requisition->authority_3_id = $this->request->getPost("authority_3_id");
        $requisition->magistrate_3_id = $this->request->getPost("magistrate_3_id");
        $requisition->status_3 = $this->request->getPost("status_3");
        $requisition->authority_4_id = $this->request->getPost("authority_4_id");
        $requisition->magistrate_4_id = $this->request->getPost("magistrate_4_id");
        $requisition->status_4 = $this->request->getPost("status_4");
        $requisition->status = $this->request->getPost("status");
        $requisition->dateofcourt = $this->request->getPost("dateofcourt");
        $requisition->location = $this->request->getPost("location");
        $requisition->description = $this->request->getPost("description");
        $requisition->created_by = $this->request->getPost("created_by");
        $requisition->created_date = $this->request->getPost("created_date");
        $requisition->update_by = $this->request->getPost("update_by");
        $requisition->update_date = $this->request->getPost("update_date");
        $requisition->delete_status = $this->request->getPost("delete_status");


        if (!$requisition->save()) {
            foreach ($requisition->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->dispatcher->forward(array(
                "controller" => "requisition",
                "action" => "new"
            ));
        }

        $this->flash->success("অভিযোগটি সফল্ভাবে গঠন হয়েছে ।");
        return $this->dispatcher->forward(array(
            "controller" => "requisition",
            "action" => "index"
        ));

    }

    /**
     * Saves a requisition edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "requisition",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $requisition = Requisition::findFirstByid($id);
        if (!$requisition) {
            $this->flash->error("requisition does not exist " . $id);
            return $this->dispatcher->forward(array(
                "controller" => "requisition",
                "action" => "index"
            ));
        }

        $requisition->id = $this->request->getPost("id");
        $requisition->complain_id = $this->request->getPost("complain_id");
        $requisition->complain_type_id = $this->request->getPost("complain_type_id");
        $requisition->authority_own_id = $this->request->getPost("authority_own_id");
        $requisition->magistrate_own_id = $this->request->getPost("magistrate_own_id");
        $requisition->status_own = $this->request->getPost("status_own");
        $requisition->authority_1_id = $this->request->getPost("authority_1_id");
        $requisition->magistrate_1_id = $this->request->getPost("magistrate_1_id");
        $requisition->status_1 = $this->request->getPost("status_1");
        $requisition->authority_2_id = $this->request->getPost("authority_2_id");
        $requisition->magistrate_2_id = $this->request->getPost("magistrate_2_id");
        $requisition->status_2 = $this->request->getPost("status_2");
        $requisition->authority_3_id = $this->request->getPost("authority_3_id");
        $requisition->magistrate_3_id = $this->request->getPost("magistrate_3_id");
        $requisition->status_3 = $this->request->getPost("status_3");
        $requisition->authority_4_id = $this->request->getPost("authority_4_id");
        $requisition->magistrate_4_id = $this->request->getPost("magistrate_4_id");
        $requisition->status_4 = $this->request->getPost("status_4");
        $requisition->status = $this->request->getPost("status");
        $requisition->dateofcourt = $this->request->getPost("dateofcourt");
        $requisition->location = $this->request->getPost("location");
        $requisition->description = $this->request->getPost("description");
        $requisition->created_by = $this->request->getPost("created_by");
        $requisition->created_date = $this->request->getPost("created_date");
        $requisition->update_by = $this->request->getPost("update_by");
        $requisition->update_date = $this->request->getPost("update_date");
        $requisition->delete_status = $this->request->getPost("delete_status");


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

    /**
     * Deletes a requisition
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $requisition = Requisition::findFirstByid($id);
        if (!$requisition) {
            $this->flash->error("requisition was not found");
            return $this->dispatcher->forward(array(
                "controller" => "requisition",
                "action" => "index"
            ));
        }

        if (!$requisition->delete()) {

            foreach ($requisition->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "requisition",
                "action" => "search"
            ));
        }

        $this->flash->success("requisition was deleted successfully");
        return $this->dispatcher->forward(array(
            "controller" => "requisition",
            "action" => "index"
        ));
    }

    public function  getRequisitionByLocationAction()
    {
        $this->view->disable();
        $childs = array();
        if (($this->request->isPost()) && ($this->request->isAjax() == true)) {

            $divid = $this->request->getQuery("divid", "string");
            $zillaid = $this->request->getQuery("zillaid", "string");
            $upazilaid = $this->request->getQuery("upazilaid", "string");

            $requisition = array();
            if ($divid && $zillaid && $upazilaid) {
                $phql = 'SELECT req.id AS id,
                            cmp.subject AS subject,
                            DATE( cmp.created_date ) AS date
                            FROM Mcms\Models\Requisition AS req
                            INNER JOIN Mcms\Models\CitizenComplain AS cmp ON cmp.id = req.complain_id
                            WHERE cmp.divid = "' . $divid . '"
                            AND cmp.zillaid = "' . $zillaid . '"
                            AND cmp.upazilaid = "' . $upazilaid . '" AND req.requisition_status = 0';

                $query = $this->modelsManager->createQuery($phql);
                $requisition = $query->execute();
            }

            foreach ($requisition as $t) {
                $childs[] = array('id' => $t->id, 'subject' => $t->subject . "(" . $t->date . ")");

            }
        }
//        var_dump($childs);

        $response = new \Phalcon\Http\Response();
        $response->setContentType('application/json', 'UTF-8');
        $response->setContent(json_encode($childs));
        return $response;

    }


    public function getComplainforPrint(Request $request){



      $complain_id = $request->id;

        // Fetch complain details
        $results = DB::table('citizen_complains as cmp')
            ->leftJoin('requisitions as req', 'cmp.id', '=', 'req.complain_id')
            ->leftJoin('users as magistrate', 'magistrate.id', '=', 'req.magistrate_own_id')
            ->select(
                'cmp.name_bng as name',
                'cmp.mobile',
                'cmp.complain_details',
                'cmp.complain_date',
                'cmp.location',
                'cmp.user_idno',
                'req.estimated_date',
                'req.req_comment',
                'cmp.citizen_address as citizen_address',
                'magistrate.name as magistrate'
            )
            ->where('cmp.id', $complain_id)
            ->limit(1)
            ->get();

        $result = $results->toArray();
       

        // $this->view->disable();
        // $response = new \Phalcon\Http\Response();
        // $response->setContentType('application/json', 'UTF-8');
        // $response->setContent(json_encode(array("result"=>$result)));
        $response = array(
            "result"=>$result
        );
      return   response()->json( $response);
       
    }
}
