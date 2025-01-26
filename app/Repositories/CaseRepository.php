<?php


namespace App\Repositories;

use App\Models\Court;
use App\Models\Criminal;
use App\Models\LawsBroken;
use App\Models\Punishment;
use App\Models\Prosecution;
use App\Models\Seizurelist;
use App\Services\ApiService;
use App\Models\ProsecutionDetail;
use App\Models\CriminalConfession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\LawsBrokenProsecution;
use App\Repositories\SeizureRepository;
use Illuminate\Support\Facades\Session;
use App\Models\CriminalConfessionLawsbroken;
use App\Models\User;

class CaseRepository
{

    protected $service;

    public function __construct(ApiService $service)
    {
        $this->service = $service;
    }

    
    public static function getCaseInformationByProsecutionId($prosecutionID){

        
        $tablesContent = array();
        $criminalIds=array();

        if ($prosecutionID) {

            // For Prosecution Table
            // $prosecutionQuery = 'SELECT *
            //         FROM Mcms\Models\Prosecution AS prosecution 
            //         WHERE prosecution.id = "' . $prosecutionID . '"  ';
            // $query = $this->modelsManager->createQuery($prosecutionQuery);
            // $prosecution = $query->execute();

            // $prosecution = Prosecution::where('id', $prosecutionID)->first();
          
            // foreach ($prosecution as $data) {
            //     $tablesContent['prosecution']= $data;
            // }
            $prosecution = Prosecution::find($prosecutionID);
            $tablesContent['prosecution'] = $prosecution;
           
            //getProsecutionLocationName
            $prosecutionLocationName=self::getProsecutionLocationName($prosecution->divid,$prosecution->zillaId,$prosecution->location_type,$prosecution->upazilaid,$prosecution->geo_citycorporation_id,$prosecution->geo_metropolitan_id,$prosecution->geo_thana_id);
   
            $tablesContent['prosecutionLocationName']= $prosecutionLocationName;

      
            //convert time to bangla format
            $prosecutionTime=self::timeConvert($prosecution->time,12);

    
            $tablesContent['prosecutionTimeInBangla']= $prosecutionTime;
          

            // For Criminal Confession Table
            // $criminalConfessionQuery = 'SELECT *
            //         FROM Mcms\Models\CriminalConfession AS criminalConfession
            //         WHERE criminalConfession.prosecution_id = "' . $prosecutionID . '"  ';
            // $query = $this->modelsManager->createQuery($criminalConfessionQuery);
            // $criminalConfessions = $query->execute();

            

            /*----------------------------------
               pre dekte hbe
            ------------------------------------*/


          $criminalConfessions = CriminalConfession::where('prosecution_id', $prosecutionID)->get();
            foreach ($criminalConfessions as $data) {
                $tablesContent['criminalConfession'][] = $data;
              
            }
            
            // $criminalConfessionByLawsQuery = 'Select 
            // criminalConfession.criminal_id, 
            // criminalConfession.prosecution_id,
            // crmConfessBylaw.CriminalConfessionID, 
            // crmConfessBylaw.LawsBrokenID,
            // crmConfessBylaw.Confessed  
            // FROM Mcms\Models\CriminalConfessionLawsbroken  As crmConfessBylaw
            // Join Mcms\Models\CriminalConfession AS criminalConfession  on criminalConfession.id = crmConfessBylaw.CriminalConfessionID 
            // Where criminalConfession.prosecution_id = "' . $prosecutionID . '" ';

            // $query = $this->modelsManager->createQuery($criminalConfessionByLawsQuery);
            // $criminalConfessionsByLaws = $query->execute();


            $criminalConfessionsByLaws = CriminalConfessionLawsbroken::select([
                'criminalConfession.criminal_id',
                'criminalConfession.prosecution_id',
                'crmConfessBylaw.CriminalConfessionID',
                'crmConfessBylaw.LawsBrokenID',
                'crmConfessBylaw.Confessed'
            ])
            ->from('criminal_confession_lawsbrokens AS crmConfessBylaw') 
            ->join('criminal_confessions as criminalConfession', 'criminalConfession.id', '=', 'crmConfessBylaw.CriminalConfessionID')
            ->where('criminalConfession.prosecution_id', $prosecutionID)
            ->get();
         
    
            // this is laravel orm relation 
            // $criminalConfessionsByLaws = CriminalConfession::with('lawsBroken')
            // ->where('prosecution_id', $prosecutionID)
            // ->get();
            
         
            // $tablesContent['criminalConfessionsByLaws'] = []
            foreach ($criminalConfessionsByLaws as $emp) {
                $data = array(
                    "crmConfessId"=>$emp->CriminalConfessionID,
                    "criminalId" => $emp->criminal_id,
                    "prosecutionId" => $emp->prosecution_id,
                    "lawsBrokenId" => $emp->LawsBrokenID,
                    "isConfessed" => $emp->Confessed,
                );

                $tablesContent['criminalConfessionsByLaws'][] = $data;

            }

          
            // For SeizureList Table
            // $seizurelistQuery = 'SELECT *
            //         FROM Mcms\Models\Seizurelist AS seizurelist 
            //         WHERE seizurelist.prosecution_id = "' . $prosecutionID . '"  ';
            // $query = $this->modelsManager->createQuery($seizurelistQuery);
            // $seizurelist = $query->execute();


            $seizurelist = Seizurelist::where('prosecution_id', $prosecutionID)->get();
            // $tablesContent['seizurelist'] = [];
            foreach ($seizurelist as $data) {
                $tablesContent['seizurelist'][] = $data;
            }
            // For Prosecution Details table Table

            // $prosecutionDetailsQuery = 'SELECT *
            //         FROM Mcms\Models\ProsecutionDetails AS prosecutionDetail 
            //         WHERE prosecutionDetail.prosecution_id = "' . $prosecutionID . '"  ';
            // $query = $this->modelsManager->createQuery($prosecutionDetailsQuery);
            // $prosecutionDetails = $query->execute();

            $prosecutionDetails = ProsecutionDetail::where('prosecution_id', $prosecutionID)->get();
    
            foreach ($prosecutionDetails as $data) {
                // Setting Criminals Id in CriminalIds Array
                $criminalIds[] = $data->criminal_id;
                $tablesContent['prosecutionDetails'][] = $data;
            }

        
 
            
            // For LawsBrokenList Table
            // $lawsBrokenListQuery = 'SELECT lawsBroken.Description,law.bd_law_link,sec.punishment_sec_number,sec.punishment_des,
            //                                 sec.punishment_type_des,sec.max_jell,sec.min_jell,sec.max_fine,
            //                                 sec.min_fine,sec.next_jail,
            //                                 sec.next_fine,lawsBroken.LawID,lawsBroken.LawsBrokenID,lawsBroken.ProsecutionID,lawsBroken.SectionID,sec.sec_title,sec.sec_number,sec.sec_description
            //         FROM Mcms\Models\LawsBroken AS lawsBroken
            //         JOIN Mcms\Models\Section AS sec on sec.law_id = lawsBroken.LawID AND sec.id = lawsBroken.SectionID
            //         JOIN Mcms\Models\Law as law ON law.id=lawsBroken.LawID
            //         WHERE lawsBroken.ProsecutionID = "' . $prosecutionID . '"  ';

            // $query = $this->modelsManager->createQuery($lawsBrokenListQuery);
            // $lawsBrokenList = $query->execute();

            // select(
            //     'lawsBroken.Description as Description',
            //     'law.bd_law_link',
            //     'sec.punishment_sec_number',
            //     'sec.punishment_des',
            //     'sec.punishment_type_des',
            //     'sec.max_jell',
            //     'sec.min_jell',
            //     'sec.max_fine',
            //     'sec.min_fine',
            //     'sec.next_jail',
            //     'sec.next_fine',
            //     'lawsBroken.law_id',
            //     'lawsBroken.id as LawsBrokenID',
            //     'lawsBroken.prosecution_id',
            //     'lawsBroken.section_id',
            //     'sec.sec_title',
            //     'sec.sec_number',
            //     'sec.sec_description'
            // )
            
            $lawsBrokenList = LawsBroken::where('prosecution_id', $prosecutionID)->get();
            if(count($lawsBrokenList)>0){
                foreach ($lawsBrokenList as $emp) {
                    $sections=DB::table('mc_section')->where('id',$emp['section_id'])->where('law_id',$emp['law_id'])->first();
                    $laws=DB::table('mc_law')->where('id',$emp['law_id'])->first();

                    $data = array(
                        "LawID"=>$emp['law_id'],
                        "LawsBrokenID" => $emp['id'],
                        "ProsecutionID" => $emp['prosecution_id'],
                        "SectionID" => $emp['section_id'],
                        "sec_title" => $sections->sec_title,
                        "sec_number" => $sections->sec_number,
                        "sec_description" => $sections->sec_description,
                        "punishment_sec_number" => $sections->punishment_sec_number,
                        "punishment_des" => $sections->punishment_des,
                        "punishment_type_des" => $sections->punishment_type_des,
                        "max_jell" => $sections->max_jell,
                        "min_jell" => $sections->min_jell,
                        "max_fine" => $sections->max_fine,
                        "min_fine" => $sections->min_fine,
                        "next_jail" => $sections->next_jail,
                        "next_fine" => $sections->next_fine,
                        "bd_law_link"=>$laws->bd_law_link,
                        "Description"=>$laws->description

                    );

                    $tablesContent['lawsBrokenList'][] = $data;
                }
            }else {
                $tablesContent['lawsBrokenList'] = null;
            }

            // For LawsBrokenList With Prosecutor Table

            $lawsBrokenListWithProsecutor = LawsBrokenProsecution::select(
                'laws_broken_prosecutions.id as id', 
                'laws_broken_prosecutions.law_id', 
                'laws_broken_prosecutions.prosecution_id', 
                'laws_broken_prosecutions.section_id', 
                'laws_broken_prosecutions.Description', 
                'sections.sec_title', 
                'sections.sec_number', 
                'sections.sec_description'
            )
            ->join('mc_section as sections', function($join) {
                $join->on('sections.law_id', '=', 'laws_broken_prosecutions.law_id')
                     ->on('sections.id', '=', 'laws_broken_prosecutions.section_id');
            })
            ->where('laws_broken_prosecutions.prosecution_id', $prosecutionID)
            ->get();



            if(count($lawsBrokenListWithProsecutor)>0) {
                foreach ($lawsBrokenListWithProsecutor as $emp) {
                    $data = array(
                        "LawID" => $emp->law_id,
                        "LawsBrokenID" => $emp->id,
                        "ProsecutionID" => $emp->prosecution_id,
                        "SectionID" => $emp->section_id,
                        "sec_title" => $emp->sec_title,
                        "sec_number" => $emp->sec_number,
                        "sec_description" => $emp->sec_description,
                        "Description"=>$emp->Description
                    );
                    $tablesContent['lawsBrokenListWithProsecutor'][] = $data;
                }
            }else {
                $tablesContent['lawsBrokenListWithProsecutor'] = null;
            }

            // For Criminal Table
            foreach ($criminalIds as $id) {

                
                // $criminalDetailsQuery = 'SELECT *
                //         FROM Mcms\Models\Criminal AS criminal 
                //         WHERE criminal.id = "' . $id . '"  ';
                // $query = $this->modelsManager->createQuery($criminalDetailsQuery);
                // $criminalDetails = $query->execute();

                $criminalDetails = Criminal::where('id', $id)->get();
                foreach ($criminalDetails as $data) {
                    $tablesContent['criminalDetails'][] = $data;
                }
            }

            //for with prosecutor Getting Magistrate Info


            /*-------------------------
             api lagbe 
            -----------------------------*/
            $tablesContent['magistrateInfo']="";
             $magistrateInfo = Court::join('prosecutions as p', 'p.court_id', '=', 'courts.id')
             ->join('users as m', 'm.id', '=', 'courts.magistrate_id')
             ->join('doptor_user_access_info as dp', 'dp.user_id', '=', 'm.id')
             ->join('mc_role as r', 'r.id', '=', 'dp.role_id')
             ->join('office  as of', 'of.id', '=', 'm.office_id')
             // ->leftJoin('file_content as fc', function ($join) {
             //     $join->on('fc.EntityID', '=', 'm.id')
             //         ->where('fc.FileCategory', '=', 'Signature');
             // })
             ->leftJoin('district as z', 'z.id', '=', 'of.district_id')
             ->leftJoin('upazila as u', function ($join) {
                 $join->on('u.district_id', '=', 'of.district_id')
                     ->on('u.id', '=', 'of.upazila_id');
             })
             ->select(
                //  'u.upazila_name_bn as upazilaname',
                 'z.district_name_bn as zillaname',
                 'of.organization_type as office_type',
                 'of.dis_name_bn as location_details',
                 'r.role_name as designation_bng',
                 'm.name as name_eng',
                 'of.office_name_bn as location_str',
                 // 'fc.FileName as signature',
                 'p.id',
                 'courts.magistrate_id',
                 'p.zillaId',
                 'p.date',
                 'p.id',
                 'courts.id as court_id'
             )
             // ->where('jd.is_active', '=', 1)
            //  ->where('jd.user_type_id', '=', 6)
             ->where('p.id', '=', $prosecutionID)
             ->get();// self::getMagistrateInfoByProsecutionId($prosecutionID);


            foreach ($magistrateInfo as $data) {
                $tablesContent['magistrateInfo']= $data;
            }

            $seizureOrderContext = SeizureRepository::getSeizureOrderContext($prosecutionID);
        
            $tablesContent['seizureOrderContext']= $seizureOrderContext;

            // $prosecutorInformation=User::select('*','name as name_eng')->where('id', $prosecution->prosecutor_id)->get();//User::select('*','name as name_eng')->find($prosecution->prosecutor_id);
            $prosecutorInformation = Court::join('prosecutions as p', 'p.court_id', '=', 'courts.id')
             ->join('users as m', 'm.id', '=', 'p.prosecutor_id')
             ->join('doptor_user_access_info as dp', 'dp.user_id', '=', 'm.id')
             ->join('mc_role as r', 'r.id', '=', 'dp.role_id')
             ->join('office  as of', 'of.id', '=', 'm.office_id')
             // ->leftJoin('file_content as fc', function ($join) {
             //     $join->on('fc.EntityID', '=', 'm.id')
             //         ->where('fc.FileCategory', '=', 'Signature');
             // })
             ->leftJoin('district as z', 'z.id', '=', 'of.district_id')
             ->leftJoin('upazila as u', function ($join) {
                 $join->on('u.district_id', '=', 'of.district_id')
                     ->on('u.id', '=', 'of.upazila_id');
             })
             ->select(
                //  'u.upazila_name_bn as upazilaname',
                 'z.district_name_bn as zillaname',
                 'of.organization_type as office_type',
                 'of.dis_name_bn as location_details',
                 'r.role_name as designation_bng',
                 'm.name as name_eng',
                 'of.office_name_bn as office_address',
                 // 'fc.FileName as signature',
                 'p.id',
                 'courts.magistrate_id',
                 'p.zillaId',
                 'p.date',
                 'p.id',
                 'courts.id as court_id'
             )
             // ->where('jd.is_active', '=', 1)
             ->where('dp.role_id', '=',25)
             ->where('p.id', '=', $prosecutionID)
             ->get();
            // Check if the prosecutor exists
            if ($prosecutorInformation) {
                $tablesContent['prosecutorInfo'] = $prosecutorInformation;
            } else {
                $tablesContent['prosecutorInfo'] = null; // Handle the case where no data is found
            }
            //prosecutor info if not suomoto
            // $prosecutorInfo='SELECT * FROM Mcms\Models\Prosecutor prosecutor
            //                   WHERE prosecutor.id="' .$prosecution[0]->prosecutor_id. '" ';
            // $query = $this->modelsManager->createQuery($prosecutorInfo);
            // $prosecutorInformation = $query->execute();

            /*------------------------------------------------------
                         api lagbe 
            --------------------------------------------------------*/
            // $tablesContent['prosecutorInfo']= null;

            //punishment
            // $punishmentQuery='SELECT *
            //                         FROM Mcms\Models\Punishment pn
                                    
            //                         WHERE pn.prosecution_id="' .$prosecutionID. '" ';
            // $query = $this->modelsManager->createQuery($punishmentQuery);
            // $punishmentSelect = $query->execute();
            
            
            $punishmentSelect = Punishment::where('prosecution_id', $prosecutionID)->get();


            $tablesContent['punishmentSelect']= $punishmentSelect;


            $entityID = $prosecutionID;


            /*------------------------------------------------
             api lagbe 
            ----------------------------------------------------*/
            $fileCategory= "ChargeFame";
            $tablesContent['fileContent']['ChargeFame']="";
            $file = FileRepository::fileFindByEntityID($entityID, $fileCategory);
            $tablesContent['fileContent']['ChargeFame']=$file;

            $fileCategory= "CriminalConfession";
            $tablesContent['fileContent']['CriminalConfession']="";
            $file = FileRepository::fileFindByEntityID($entityID, $fileCategory);
            $tablesContent['fileContent']['CriminalConfession']=$file;

            $fileCategory= "OrderSheet";
            $tablesContent['fileContent']['OrderSheet']="";
            $file = FileRepository::fileFindByEntityID($entityID, $fileCategory);
            $tablesContent['fileContent']['OrderSheet']=$file;

            $tablesContent['fileContent']['AllFile']="";
            $file = FileRepository::fileAllFindByEntityID($entityID);
            $tablesContent['fileContent']['AllFile']=$file;

        }
        return $tablesContent;

    }

    public static function getProsecutionLocationName($divid,$zillaid,$locationType,$upazilaid,$citycorporationId,$metroPolitonId,$thanaId){
        $underZillaLocation = '';
        $thanaName = '';
        
        // Fetch division name
        $divName = DB::table('division')->where('id', $divid)->first();
        $divName = $divName ? $divName->division_name_bn : '';


        // Fetch zilla by zillaid and divid

        $zilla = DB::table('district')->where('id', $zillaid)->where('division_id', $divid)->first();
        $zillaName = $zilla ? $zilla->district_name_bn : '';
        
        // Check location type
        if ($locationType == 'UPAZILLA') {
            $upazilla = DB::table('upazila')->where('district_id', $zillaid)->where('id', $upazilaid)->first();
            $underZillaLocation = $upazilla ? $upazilla->upazila_name_bn : '';
        } elseif ($locationType == 'CITYCORPORATION') {
            $cityCorporation = DB::table('geo_city_corporations')->where('district_bbs_code', $zillaid)->where('id', $citycorporationId)->first();
            $underZillaLocation = $cityCorporation ? $cityCorporation->city_corporation_name_bng : '';
        } elseif ($locationType == 'METROPOLITAN') {
          $metropolitan = DB::table('geo_metropolitan')->where('district_bbs_code', $zillaid)->where('id', $metroPolitonId)->first();
            $thana = DB::table('geo_thanas')->where('district_bbs_code', $zillaid)->where('geo_metropolitan_id', $metroPolitonId)->where('id', $thanaId)->first();
            $underZillaLocation = $metropolitan ? $metropolitan->metropolitan_name_bng : '';
            $thanaName = $thana ? $thana->thana_name_bng : '';
        }
    
        // Create location array
        $locationArray=array(
            "divName"=>$divName,
            "zillaName"=>$zillaName,
            "underZillaLocation"=>$underZillaLocation,
            "thanaName"=>$thanaName
        );
        return $locationArray;
    }
    public static function timeConvert($time,$f) {
     
        if (gettype($time)=='string')
            $time = strtotime($time);
        $dateString ="";
        $dateString = ($f==24) ? date("G:i A", $time) : date("g:i A", $time);  // g:i: A   = 4:45 PM

        $dateString = (explode(" ",$dateString));
        if($dateString[1] =='AM'){
            $dateString  = "পূর্বাহ্নে ". self::convertEnglishNumToBangla($dateString[0]);
        }else{
            $dateString = "মধ্যহ্ন ". self::convertEnglishNumToBangla($dateString[0]);
        }
        return $dateString;
    }
 
    public static function convertEnglishNumToBangla($englishNum){
        $bn_digits=array('০','১','২','৩','৪','৫','৬','৭','৮','৯');

        $output = str_replace(range(0, 9),$bn_digits, $englishNum);
        return $output;
    }

    public static function getMagistrateInfoByProsecutionId($prosecutionID){
        // $division_id=$officeinfo->division_id;
        // $district_id=$officeinfo->district_id
        $magistrateInfo = Court::join('prosecutions as p', 'p.court_id', '=', 'courts.id')
        ->join('users as m', 'm.id', '=', 'courts.magistrate_id')
        ->join('office  as of', 'of.id', '=', 'm.office_id')
        // ->leftJoin('file_content as fc', function ($join) {
        //     $join->on('fc.EntityID', '=', 'm.id')
        //         ->where('fc.FileCategory', '=', 'Signature');
        // })
        ->leftJoin('district as z', 'z.id', '=', 'of.district_id')
        // ->leftJoin('upazila as u', function ($join) {
        //     $join->on('u.district_id', '=', 'of.district_id')
        //         ->on('u.id', '=', 'of.upazila_id');
        // })
        ->select(
            // 'u.upazila_name_bn as upazilaname',
            'z.district_name_bn as zillaname',
            'of.organization_type as office_type',
            'of.dis_name_bn as location_details',
            'm.designation as designation_bng',
            'm.name as name_eng',
            'of.office_name_bn as location_str',
            // 'fc.FileName as signature',
            'p.id',
            'courts.magistrate_id',
            'p.zillaId',
            'p.date',
            'p.id',
            'courts.id as court_id'
        )
        // ->where('jd.is_active', '=', 1)
        // ->where('jd.user_type_id', '=', 6)
        ->where('p.id', '=', $prosecutionID)
        ->get();
        return $magistrateInfo;
    }
    public static function getPreviousCrimeDetailsByCriminalId($criminalId,$prosecutionId){
          
      
        $crimeDetails = DB::table('prosecution_details as pd')
        ->join('prosecutions as p', 'p.id', '=', 'pd.prosecution_id')
        ->where('pd.criminal_id', $criminalId)
        ->whereNotNull('p.orderSheet_id')
        ->where('pd.prosecution_id', '!=', $prosecutionId)
        ->select('p.subject', 'p.id', 'p.date', 'p.case_no')
        ->get();
        return   $crimeDetails;
    }
   

}