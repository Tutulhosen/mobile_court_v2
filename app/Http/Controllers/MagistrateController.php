<?php

namespace App\Http\Controllers;

use App\Models\Prosecution;
use App\Models\Requisition;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;
use App\Models\CitizenComplain;
use App\Models\CourtComplainInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MagistrateController extends Controller
{
    //

    public function newattachmentrequisition(Request $request){
        return view('magistrate.newattachmentrequisition');
    }

    public function attachmentrequisitionlist(Request $request){
            
       $magistrate = Auth::user();

        $aColumns = array(' ','DT_RowId', 'cmp_subject','cmp_date', 'name','mobile', 'cmp_details', 'rdate','subject');

        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "id";
        /*
         * Paging
         */
        $sLimit = '';
        // isset($request->iDisplayStart) && 
        if ($request->iDisplayLength != '-1') {
            $sLimit = intval($request->iDisplayLength);
        }


        /*
         * Ordering
         */
        $sOrder = '';
        if ($request->has('iSortCol_0')) {
            $sOrder = "ORDER BY ";
            $sortingCols = intval(request('iSortingCols'));
        
            for ($i = 0; $i < $sortingCols; $i++) {
                $sortColIndex = intval(request('iSortCol_' . $i));
                $sortable = request('bSortable_' . $sortColIndex);
        
                if ($sortable == "true") {
                    $columnName = $aColumns[$sortColIndex];
                    $sortDirection = request('sSortDir_' . $i) === 'asc' ? 'asc' : 'desc';
                    $sOrder .= $columnName . ' ' . $sortDirection . ', ';
                }
            }
        
            // Remove the trailing comma and space
            // $sOrder = rtrim($sOrder, ', ');
            $sOrder = substr_replace( $sOrder, "", -2 );
        
            // If no sorting columns are added, set $sOrder to an empty string
            if ($sOrder == "ORDER BY") {
                $sOrder = '';
            }
        }
       

        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        // $sWhere = "";
        // if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
        // {
        //     $sWhere = "WHERE (";
        //     for ( $i=0 ; $i<count($aColumns) ; $i++ )
        //     {
        //         $sWhere .= "".$aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
        //     }
        //     $sWhere = substr_replace( $sWhere, "", -3 );
        //     $sWhere .= ')';
        // }
 
        $sWhere = '';
         
        if (request()->has('sSearch') && request('sSearch') != '') {
            $sSearch = request('sSearch');
            
            // Start building the WHERE clause
            $sWhere = 'WHERE (';
            
            foreach ($aColumns as $column) {
                // Add LIKE condition for each column
                $sWhere .= $column . " LIKE '%" . addslashes($sSearch) . "%' OR ";
            }
            
            // Remove the trailing 'OR ' and close the WHERE clause
            $sWhere = substr($sWhere, 0, -3) . ')';
        }
      
        /* Individual column filtering */
        // for ( $i=0 ; $i<count($aColumns) ; $i++ )
        // {
        //     if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
        //     {
        //         if ( $sWhere == "" )
        //         {
        //             $sWhere = "WHERE";
        //         }
        //         else
        //         {
        //             $sWhere .= " AND ";
        //         }
        //         $sWhere .= "".$aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
        //     }
        // }

        for ($i = 0; $i < count($aColumns); $i++) {
            if (request()->has('bSearchable_' . $i) && request('bSearchable_' . $i) == "true" && request('sSearch_' . $i) != '') {
                if ($sWhere == '') {
                    $sWhere = 'WHERE';
                } else {
                    $sWhere .= ' AND ';
                }
        
                // Add the condition for each individual searchable column
                $sWhere .= $aColumns[$i] . " LIKE '%" . addslashes(request('sSearch_' . $i)) . "%' ";
            }
        }
      

        /*
         * SQL queries
         * Get data to display
         */

        // Count distinct used brands // those requisition which are  attached . if  attached case_number is not null
        // $phql = "
        //         SELECT ccf.requisition_id AS requisition_id
        //         FROM  Mcms\Models\CourtComplainInfo AS  ccf
        //         WHERE ccf.magistrate_id = $magistrate->id AND  ccf.case_number is NOT NULL
        // ";

        // $query = $this->modelsManager->createQuery($phql);
        // $rows  = $query->execute();
       
        $rows = DB::table('court_complain_infos as ccf')
                ->select('ccf.requisition_id')
                ->where('ccf.magistrate_id', $magistrate->id)
                ->whereNotNull('ccf.case_number')
                ->get();
  
        $ids = '';
        foreach ($rows as $row) {

            if($ids==''){
                $ids.= $row->requisition_id  ;
            }else{
                $ids.= ",".$row->requisition_id;
            }

        }
  
        if ( $sWhere == "" ){
            $sWhere = "WHERE  req1.magistrate_own_id =  $magistrate->id ";
            if($ids != "")
            {
                $sWhere .=  "AND req1.id NOT IN (" .$ids. ")" ;
            }
        }else{
            $sWhere .= " AND  req1.magistrate_own_id =  $magistrate->id  ";
            if($ids != ""){
                $sWhere .=  "AND req1.id NOT IN (" .$ids. ")" ;
            }
        }
    
        // $sQuery = "
		// SELECT
		// req1.id as DT_RowId,
		// ctz_cmp.subject AS cmp_subject ,
		// ctz_cmp.complain_date as cmp_date,
		// ctz_cmp.name_eng as name,
		// ctz_cmp.mobile AS mobile ,
		// ctz_cmp.complain_details as cmp_details,
		// req1.created_date as rdate,
		// ctz_cmp.subject AS subject
        // FROM Mcms\Models\Requisition AS req1
        // INNER JOIN Mcms\Models\CitizenComplain as ctz_cmp ON ctz_cmp.id = req1.complain_id
        // $sWhere
		// ORDER BY req1.created_date
		// $sLimit
		// ";

        // // echo $sQuery;

        // $query = $this->modelsManager->createQuery($sQuery);
        // $rResult  = $query->execute();

        $variable = $sWhere;
        $trimmedVariable = trim($variable);
        if (strpos($trimmedVariable, 'WHERE') === 0) {
            $trimmedVariable = substr($trimmedVariable, strlen('WHERE'));
        } elseif (strpos($trimmedVariable, 'AND') === 0) {
            $trimmedVariable = substr($trimmedVariable, strlen('AND'));
        }
       
        $query = DB::table('requisitions as req1')
        ->join('citizen_complains as ctz_cmp', 'ctz_cmp.id', '=', 'req1.complain_id')
        ->select(
            'req1.id as DT_RowId',
            'ctz_cmp.subject as cmp_subject',
            'ctz_cmp.complain_date as cmp_date',
            'ctz_cmp.name_eng as name',
            'ctz_cmp.mobile as mobile',
            'ctz_cmp.complain_details as cmp_details',
            'req1.created_date as rdate',
            'ctz_cmp.subject as subject'
        );

        if (!empty($sWhere)) {
            $query->whereRaw($trimmedVariable); 
        }
        $query->orderBy('req1.created_date');
        if (!empty($sLimit)) {
            $query->limit($sLimit); 
        }
        $rResult = $query->get();
      
        /* Total data set length */
        // $sQuery2 = "
        //         SELECT  COUNT(req1.id) as count
        //         FROM Mcms\Models\Requisition AS req1
        //         INNER JOIN Mcms\Models\CitizenComplain as ctz_cmp ON ctz_cmp.id = req1.complain_id
        //         $sWhere
        //     ";
        // $query = $this->modelsManager->createQuery($sQuery2);
        // $rResult2  = $query->execute();
        // $iTotal = $rResult2[0]["count"];

        // $query2 = DB::table('requisitions as req1')
        //     ->join('citizen_complains as ctz_cmp', 'ctz_cmp.id', '=', 'req1.complain_id')
        //     ->selectRaw('COUNT(req1.id) as count');
        // if (!empty($sWhere)) {
        //     $query2->whereRaw($trimmedVariable); // Assuming $sWhere is a raw SQL condition
        // }
        // Execute the count query
        // $iTotal = $query2->value('count'); // This will directly return the count value

        $query2 = DB::table('requisitions AS req1')
        ->join('citizen_complains AS ctz_cmp', 'ctz_cmp.id', '=', 'req1.complain_id');
        if (!empty($sWhere)) {
            $query2->whereRaw($trimmedVariable); // Assuming $sWhere is a raw SQL condition
        }
        $iTotal= $query2->count('req1.id');
     
        /*
         * Output
         */
        $output = array(
            "draw" => intval(request('sEcho')),
            "recordsTotal" => $iTotal,
            "recordsFiltered" =>  $iTotal,
            "data" => array()
        );
        

        // $path = $this->getDI()->get('config')->application["baseUri"];
      
        // foreach ($rResult as $emp) {
        //     $row = array();
        //     $row['0'] = "<img src='/mobile_court/images/details_open.png'>" ;
        //     for ( $i=0 ; $i<count($aColumns) ; $i++ ){
        //         if ( $aColumns[$i] != ' ' ){
        //             if ( $aColumns[$i] == 'DT_RowId' ){
        //                 $row[$aColumns[$i]] = (int) $emp->$aColumns[$i];
        //             }elseif($aColumns[$i] == 'subject'){
        //                 $row[$aColumns[$i]] =  $emp->$aColumns[$i];
        //             }else{
        //                 $row[] =  $emp->$aColumns[$i];
        //             }
        //             /* General output */

        //         }
        //     }

        //     $output['data'][] = $row;
        // }
        // dd($rResult);
        foreach ($rResult as $emp) {
            $row = [];
            $row[0] = "<img src='/mobile_court/images/details_open.png'>"; // First column with image
        
            // Loop through the columns and fill the data
            foreach ($aColumns as $i => $column) {
                if ($column !== ' ') { // Skip empty columns
                    if ($column === 'DT_RowId') {
                        // Cast the DT_RowId column value to an integer
                        $row[$column] = (int) $emp->$column;
                    } elseif ($column === 'subject') {
                        // Special handling for the 'subject' column
                        $row[$column] = $emp->$column;
                    } else {
                        // General output for other columns
                        $row[] = $emp->$column;
                    }
                }
            }
        
            // Add the row to the data array
            $output['data'][] = $row;
        }
        
        return response()->json( $output );
    }
    public function attachmentcaselist(Request $request){
 
         $magistrate =auth()->user();

        $aColumns = array( 'DT_RowId','prose.id','cdate','case_no','prosecutor_name', 'pdate', 'location','subject','hints');
        $searchColumns = array( 'case_no');



        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "id";
        /*
         * Paging
         */
        $sLimit = '';
        if (isset($request->iDisplayStart) && $request->iDisplayLength != '-1') {
            $sLimit = intval($request->iDisplayLength);
        }

        /*
         * Ordering
         */
        $sOrder = "";
        if ( isset( $_GET['iSortCol_0'] ) ){
            // $sOrder = "ORDER BY  ";
            $sOrder = "  ";
            for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ ){
                if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" ){
                    $sOrder .= "".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]." ".
                        ($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
                }
            }

            $sOrder = substr_replace( $sOrder, "", -2 );
            if ( $sOrder == "ORDER BY" ){
                $sOrder = "";
            }
        }
     
        

        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        $sWhere = "";
      
 
        if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" ){
      
            $sWhere = "WHERE (";
            for ( $i=0 ; $i<count($searchColumns) ; $i++ ){
                $sWhere .= "".$searchColumns[$i]." LIKE '%".$_GET['sSearch']."%' OR ";
            }
            $sWhere = substr_replace( $sWhere, "", -3 );
            $sWhere .= ')';
        }
        // return $request->all();
         
        /* Individual column filtering */
        for ( $i=0 ; $i<count($searchColumns) ; $i++ )
        {
            if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
            {
                if ( $sWhere == "" )
                {
                    $sWhere = "";
                }
                else
                {
                    $sWhere .= " AND ";
                }
                $sWhere .= "".$searchColumns[$i]." LIKE '%".$_GET['sSearch_'.$i]."%' ";
            }
        }
      

        $m = (integer) date('n');

        $start_date = date('Y-m-d',mktime(1,1,1,$m,1,date('Y')));
        $end_date = date('Y-m-d',mktime(1,1,1,$m+1,0,date('Y')));
        if ( $sWhere == "" ){
            $sWhere = "WHERE  court.magistrate_id =  $magistrate->id  AND prose.is_approved  = 1 AND prose.delete_status = '1' ";
        }else{
            $sWhere .= " AND  court.magistrate_id  =  $magistrate->id  AND prose.is_approved  = 1 AND prose.delete_status = '1'";
        }

       
        /*
         * SQL queries
         * Get data to display
         */
        $query = DB::table('prosecutions as prose')
        ->join('courts as court', 'court.id', '=', 'prose.court_id')
        ->select(
            'prose.id as DT_RowId',
            'prose.id as id',
            'prose.case_no',
            'prose.created_at as cdate',
            'prose.prosecutor_name',
            'prose.location',
            'prose.subject',
            'prose.date as pdate',
            'prose.hints'
        );
    
        $variable = $sWhere;
        $trimmedVariable = trim($variable);
        if (strpos($trimmedVariable, 'WHERE') === 0) {
            $trimmedVariable = substr($trimmedVariable, strlen('WHERE'));
        } elseif (strpos($trimmedVariable, 'AND') === 0) {
            $trimmedVariable = substr($trimmedVariable, strlen('AND'));
        }
        // Apply $sWhere conditions if they exist
        if (!empty($sWhere)) {
            $query->whereRaw($trimmedVariable); // Assuming $sWhere is a raw SQL condition
        }
        // Apply $sOrder conditions if they exist
        if (!empty($sOrder)) {
            $query->orderByRaw($sOrder); // Assuming $sOrder is a raw SQL order condition
        }
        // Apply $sLimit if it exists
        if (!empty($sLimit)) {
            $query->limit($sLimit); // Ensure $sLimit is a valid integer
        }
        // Execute the query
        $rResult = $query->get();
      
       

        $query22 = DB::table('prosecutions as prose')
            ->join('courts as court', 'court.id', '=', 'prose.court_id')
            ->join('users as magistrate', 'magistrate.id', '=', 'court.magistrate_id')
            ->select('prose.id');

        // Apply $sWhere conditions if they exist
        if (!empty($sWhere)) {
            $query22->whereRaw($trimmedVariable); // Assuming $sWhere is a raw SQL condition
        }
        // Execute the count query
       $iTotal = $query22->count(); // This will directly return the count value


        /*
         * Output
         */
        $output = [
            "draw" => intval($request->input('sEcho')),
            "recordsTotal" => $iTotal,
            "recordsFiltered" => $iTotal,
            "data" => []
        ];


        // $path = $this->getDI()->get('config')->application["baseUri"];
        // foreach ($rResult as $emp) {
        //     $row = [];
        //     $row[0] = "<img src='images/details_open.png'>"; // Image in the first column
    
        //     // Looping through $aColumns and accessing properties dynamically
        //     for ( $i = 1; $i < count($aColumns); $i++ ) {
        //         $column = $aColumns[$i];
        //         // Ensure that column exists in the result object
        //         if (isset($emp->$column)) {
        //             $row[$column] = $emp->$column;
        //         } else {
        //             // Handle missing columns or provide a default value
        //             $row[$column] = null;
        //         }
        //     }
            
        //     $output['data'][] = $row; // Add the constructed row to the output data
        // }
        //  dd( $output);

        foreach ($rResult as $emp) {
            $row = array();
            $row['0'] = "<img src='/mobile_court/images/details_open.png'>";
        
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] != ' ') {
                    if ($aColumns[$i] == 'DT_RowId') {
                        // Ensure we are accessing 'id' directly if 'DT_RowId' is the desired output
                        $row[$aColumns[$i]] = (int) $emp->id; // Adjusting to directly access 'id'
                    } else {
                        // Ensure that the property exists before accessing it
                        if (isset($emp->{$aColumns[$i]})) {
                            $row[] = $emp->{$aColumns[$i]};
                        } else {
                            $row[] = null; // Or any default value
                        }
                    }
                }
            }
            $output['data'][] = $row;
        }
        
        // Optionally: Output the result (for debugging purposes)
        // dd($output);
        return response()->json( $output );
     
    }

    public function saverequisitionattachment(Request $request){
            $magistrate = auth()->user();
            $str_requisition_ids = $request->input('requisition_ids');
            $requisition_ids = explode(',', $str_requisition_ids);

            $str_prosecution_ids = $request->input('prosecution_ids');
            for ( $i=0 ; $i<count($requisition_ids) ; $i++ ){
             
                $court_complain_info_old = CourtComplainInfo::where('magistrate_id', $magistrate->id)
                    ->where('requisition_id', $requisition_ids[$i])
                    ->first();
                   
                $court_complain_info = CourtComplainInfo:: find($court_complain_info_old->id);
                $prosecution = Prosecution::select('case_no as case_number', 'id as prosecution_id', 'date as prosecution_date', 'court_id', 'orderSheet_id')
                            ->where('delete_status', '1')
                            ->where('id', $str_prosecution_ids)
                            ->first(); // Using first() since you want the first record

                    if ($prosecution) {
                        // Assuming you have a model for court_complain_info
                        $court_complain_info->court_id = $prosecution->court_id;
                        $court_complain_info->prosecution_date = $prosecution->prosecution_date;
                        $court_complain_info->prosecution_id = $prosecution->prosecution_id;
                        $court_complain_info->case_number = $prosecution->case_number;
                        // Check if orderSheet_id is present
                        if (!empty($prosecution->orderSheet_id)) {
                            $court_complain_info->case_status = "solved";
                        } else {
                            $court_complain_info->case_status = "processing";
                        }
                        // Save the court_complain_info
                        $court_complain_info->save();

                       $prosecutionOld = Prosecution::where('id', $str_prosecution_ids)->get(); 

                        if ($prosecutionOld->isNotEmpty()) {
                            foreach ($prosecutionOld as $prosecution) {
                                $prosecution->is_attached = 1; 
                                $prosecution->user_idno = $court_complain_info->user_idno; 
                                $prosecution->requisition_id = $court_complain_info->requisition_id; 
                    
                                // Save the prosecution record
                                if (!$prosecution->save()) {
                                    // $errorMessage = $prosecution->getErrors()->implode(', '); // Join error messages into a single string
                                    // DB::rollBack(); // Rollback the transaction
                                    // throw new Exception($errorMessage); // Throw an exception with the error message
                                }
                            }
                    
                        
                        }
                }
                   
            }
            $msg[] = array();
            $msg["flag"] = "true";
            $msg["message"] = " সফলভাবে সংরক্ষণ করা হয়েছে ।" ;
            $response= json_encode($msg);
            return $response;
            // return redirect('/magistrate/newattachmentrequisition');
            // return redirect()->route('magistrate.newattachmentrequisition');  // Retain previous input data
        
            // $response=json_encode("Save Successfully");

            // return $response;

            // Fetch prosecution details once before the loop
            // $prosecutions = Prosecution::select(
            //         'case_no as case_number',
            //         'id as prosecution_id',
            //         'date as prosecution_date',
            //         'court_id',
            //         'orderSheet_id'
            //     )
            //     ->where('delete_status', '1')
            //     ->whereIn('id', explode(',', $str_prosecution_ids))
            //     ->get();

            // foreach ($requisition_ids as $requisition_id) {
            //     $court_complain_info_old = CourtComplainInfo::where('magistrate_id', $magistrate->id)
            //         ->where('requisition_id', $requisition_id)
            //         ->first();

            //     if ($court_complain_info_old) {

            //         $prosecution = $prosecutions->firstWhere('prosecution_id', $court_complain_info_old->prosecution_id);

       
            //         if ($prosecution) {
            //             $court_complain_info_old->court_id = $prosecution->court_id;
            //             $court_complain_info_old->prosecution_date = $prosecution->prosecution_date;
            //             $court_complain_info_old->prosecution_id = $prosecution->prosecution_id;
            //             $court_complain_info_old->case_number = $prosecution->case_number;

            //             $court_complain_info_old->case_status = !empty($prosecution->orderSheet_id) ? 'solved' : 'processing';
            //             $court_complain_info_old->save();
            //         }

              
            //         $prosecutionOld = Prosecution::find($court_complain_info_old->prosecution_id);
            //         if ($prosecutionOld) {
            //             $prosecutionOld->is_attached = 1;
            //             $prosecutionOld->user_idno = $court_complain_info_old->user_idno;
            //             $prosecutionOld->requisition_id = $court_complain_info_old->requisition_id;

            //             if (!$prosecutionOld->save()) {
                    
            //                 $errorMessage = $prosecutionOld->getErrors()->first(); 
            //                 throw new \Exception($errorMessage);
            //             }
            //         }
                    
            //     }
            // }
 
    }

    public function complainVarification(Request $request){
        $numberPage = 1;
        if ($request->isMethod('post')) {
            $query = CitizenComplain::where($request->all());
            session(['parameters' => $query->getBindings()]);
        } else {
            $numberPage = $request->query('page', 1);
        }
    
        $parameters = session('parameters');
        if (!is_array($parameters)) {
            $parameters = [];
        }
    
        // Set default ordering
        $parameters['order'] = ['created_date' => 'DESC'];
    
        $magistrate = auth()->user(); // Assuming magistrate info is linked to the user
    
        // Query distinct requisition_ids where case_number is not null
        $rows  = CourtComplainInfo::select('requisition_id AS requisition_id')
            ->where('magistrate_id', $magistrate->id)
            ->whereNotNull('case_number')
            ->get('requisition_id');
    
        $ids = '';
        foreach ($rows as $row) {

            if($ids==''){
                $ids.= $row->requisition_id;
            }else{
                $ids.= ",".$row->requisition_id;
            }

        }    
 
        // Prepare sWhere conditions

        $sWhere = "";
        if ( $sWhere == "" ){
            $sWhere = "WHERE  req1.magistrate_own_id =  $magistrate->id
                       AND req1.complain_type_id = 1 AND req1.status_own !='solved' AND req1.status_own !='re-send'" ;
            if($ids != ""){
                $sWhere .=  "AND req1.id NOT IN (" .$ids. ")" ;
            }
        }else{
            $sWhere .= " AND  req1.magistrate_own_id =  $magistrate->id
                             AND req1.complain_type_id = 1 AND req1.status_own !='solved' AND req1.status_own !='re-send'" ;
            if($ids != ""){
                $sWhere .=  "  AND req1.id NOT IN (" .$ids. ")" ;
            }
        }
       

    //    if(!empty($rows)){
    //     $sWhere .= " req1.magistrate_own_id =  $magistrate->id AND req1.complain_type_id = 1 AND req1.status_own !='solved' AND req1.status_own !='re-send'" ;
        
    //     // If requisition IDs exist, add the NOT IN condition
    //     if (!empty($ids)) {
    //         $sWhere .=  "  AND req1.id NOT IN (" .$ids. ")";
    //     }
    //    }
      
        $variable = $sWhere;
        $trimmedVariable = trim($variable);
        if (strpos($trimmedVariable, 'WHERE') === 0) {
            $trimmedVariable = substr($trimmedVariable, strlen('WHERE'));
        } elseif (strpos($trimmedVariable, 'AND') === 0) {
            $trimmedVariable = substr($trimmedVariable, strlen('AND'));
        }
        // Main query for CitizenComplain
        $citizen_complain = DB::table('citizen_complains AS citComp')
            ->selectRaw('DISTINCT citComp.id as complain_id,
                        citComp.name_bng,
                        citComp.name_eng,
                        citComp.mobile,
                        citComp.location,
                        DATE_FORMAT(citComp.complain_date, "%Y-%m-%d") AS complain_date,
                        DATE_FORMAT(citComp.created_date, "%Y-%m-%d") AS created_date,
                        citComp.complain_details,
                        citComp.user_idno,
                        citComp.subject')
            ->join('requisitions AS req1', 'citComp.id', '=', 'req1.complain_id')
            ->whereRaw($trimmedVariable)
            ->groupBy('citComp.id')
            ->orderBy('created_date', 'DESC')
            ->paginate(10, ['*'], 'page', $numberPage);
    
        // If no data found
        // if ($citizen_complain->isEmpty()) {
        //     return redirect()->route('home.index')->with('notice', 'তথ্য পাওয়া যাইনি');
        // }
        return view('magistrate.complainVarification', ['citizen_complain' => $citizen_complain]);
    }

    public function saveFeedback(Request $request){


        $id= $request->id;

        // if (!$this->request->isPost()) {
        //     return $this->dispatcher->forward(array(
        //         "controller" => "magistrate",
        //         "action" => "complainVarification"
        //     ));
        // }

        $magistrate = auth()->user();
        $citizen_complain = CitizenComplain::find($id);
        // if (!$citizen_complain) {
        //     $this->flash->error("citizen_complain does not exist " . $id);
        //     return $this->dispatcher->forward(array(
        //         "controller" => "magistrate",
        //         "action" => "saveFeedback"
        //     ));
        // }

        $citizen_complain->feedback = $request->feedback;
        $citizen_complain->feedback_date = date('Y-m-d');
        $feedback_action = $request->feedback_action;
        if($feedback_action == "1"){
            $citizen_complain->complain_status = "solved";
        }elseif($feedback_action == "2"){
            $citizen_complain->complain_status = "re-send";
        }else{
            $citizen_complain->complain_status = "re-send";
        }



        $citizen_complain->save();

        // if (!$citizen_complain->save()) {
        //     foreach ($citizen_complain->getMessages() as $message) {
        //         $this->flash->error($message);
        //     }
        //     return $this->dispatcher->forward(array(
        //         "controller" => "magistrate",
        //         "action" => "saveFeedback"
        //     ));
        // }

        $requisition = Requisition::where("complain_id",$id)->where('magistrate_own_id',$magistrate->id)->first();

        $requisition->status_own = $citizen_complain->complain_status;
        $requisition->save();


        $court_complain_info = CourtComplainInfo::where("complain_id",$id)->where('magistrate_id',$magistrate->id)->first();

        $court_complain_info->complain_status = $citizen_complain->complain_status;
        $court_complain_info->save();


        $msg[] = array();
        $msg["flag"] = "true";
        $msg["message"] = " সফলভাবে সংরক্ষণ করা হয়েছে ।" ;

        // $this->view->disable();
        // $response = new \Phalcon\Http\Response();
        // $response->setContentType('application/json', 'UTF-8');
        // $response->setContent(json_encode($msg));
        $response= json_encode($msg);
        return $response;
    }

    public function check_user_permission(Request $request){
        $is_already_has = DB::table('jurisdiction')
            ->where('user_id', $request->username)
            ->first();
        // dd($request->username);
        // Return upa_id_arr if data exists, else an empty array
        return response()->json([
            'upa_id_arr' => $is_already_has ? json_decode($is_already_has->upa_id_arr) : []
        ]);
    }

    //for super admin
    public function check_user_per(Request $request){
        $requestData = $request->all();
        $userInfo = $requestData['body_data'];
        $username=$userInfo['username'];
        $is_already_has = DB::table('jurisdiction')
            ->where('user_id', $username)
            ->first();
        return ['success' => true, "data" => $is_already_has];
        
    }
    public function jurisdiction_store_for_admin(Request $request){
        $requestData = $request->all();
        $userInfo = $requestData['body_data'];
        $user_id=$userInfo['user_id'];
        $div_id=$userInfo['div_id'];
        $dis_id=$userInfo['dis_id'];
        $upa_id_arr=$userInfo['upa'];

        $exists = DB::table('jurisdiction')->where('user_id', $user_id)->exists();
        if ($exists==1) {
   
            // If record exists, update it
            DB::table('jurisdiction')->where('user_id', $user_id)->update([
                'div_id' => $div_id,
                'dis_id' => $dis_id,
                'upa_id_arr' => $upa_id_arr,
            ]);
        } else {

            DB::table('jurisdiction')->insert([
                'user_id' => $user_id,
                'div_id' => $div_id,
                'dis_id' => $dis_id,
                'upa_id_arr' => $upa_id_arr,
                'created_by' => 1,
            ]);
        }

        return ['success' => true, "data" => null];
        
    }
    


    public function jurisdiction(){
        $user=globalUserInfo();
        $officeInfo = globalUseroffice($user->office_id);
       
        if ($user->role_id == 25 || $user->role_id ==26 ) {
            $all_user=null;
        }else {
            
            $all_user= DB::table('users')
            ->join('doptor_user_access_info','users.id', 'doptor_user_access_info.user_id')
            ->where('users.doptor_office_id', $user->doptor_office_id)
            ->whereIn('doptor_user_access_info.role_id', [25,26])
            ->get();
        }
        // dd($all_user);
        $is_already_has= DB::table('jurisdiction')->where('user_id', $user->username)->first();
        $selected_upazilas = $is_already_has ? json_decode($is_already_has->upa_id_arr) : [];
        $upazila = DB::table('upazila')->where('division_id', $officeInfo->division_id)->where('district_id', $officeInfo->district_id)->get();
    
        $data['is_already_has'] = $is_already_has;
        $data['all_user'] = $all_user;
        $data['selected_upazilas'] = $selected_upazilas;
        $data['upzillas'] = $upazila;
        $data['user'] = $user;
        $data['page_title'] = 'অধিক্ষেত্র নির্ধারণ';
  
        return view('magistrate.jurisdiction')->with($data);
    }


    public function jurisdiction_store(Request $request) {
        $user = globalUserInfo();
        $officeInfo = globalUseroffice($user->office_id);
    
        // Encode the selected upazila mapping to JSON
        $upa = json_encode($request->input('upzilla_mapping'));

        // Check if the record with the same user_id already exists
        if ($user->role_id == 25 || $user->role_id ==26) {
            $exists = DB::table('jurisdiction')->where('user_id', $user->username)->exists();
            $user_id=$user->username;
        } else {
            
            if (empty($request->user_select)) {
                return response()->json(['success' => false]);
            }
            $exists = DB::table('jurisdiction')->where('user_id', $request->user_select)->exists();
            $user_id=$request->user_select;
        }
        
        
        // dd( $user->username);
        if ($exists) {
            // If record exists, update it
            DB::table('jurisdiction')->where('user_id', $user_id)->update([
                'div_id' => $officeInfo->division_id,
                'dis_id' => $officeInfo->district_id,
                'upa_id_arr' => $upa,
            ]);
        } else {
            // If no record exists, insert a new one
            DB::table('jurisdiction')->insert([
                'user_id' => $user_id,
                'div_id' => $officeInfo->division_id,
                'dis_id' => $officeInfo->district_id,
                'upa_id_arr' => $upa,
                'created_by' => $user->id,
            ]);
        }
    
        return response()->json(['success' => true]);
    }
    
    //mamla cancel from admin panel
    public function mamla_cancel_from_admin(Request $request){
        $requestData = $request->all();
        $userInfo = $requestData['body_data'];
        $case_no=$userInfo['case_no'];
        $name=$userInfo['name'];

        $prosecution = DB::table('prosecutions')->where('case_no', $case_no)->first();
        if (empty($prosecution)) {
            return ['status' => false, "message" => 'মামলা নাম্বার খুঁজে পাওয়া যায়নি ।'];
        }
        $caseNumber_new = "";
        $new_service_str ="";
 
    
        $update_by = $name;
        $update_date = date('Y-m-d');


         $caseNumber  = $case_no;  // 1967.01.16167.0001.16  // 42-001-১৫৬৭৫-000001/2016
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
       
         $condition ="";
        
       
          $prosecution = DB::table('prosecutions')
            ->select('id')
            ->where(function($query) use ($caseNumber_new, $caseNumber) {
                $query->where('case_no', 'LIKE', "%$caseNumber_new%")
                    ->orWhere('case_no', 'LIKE', "%$caseNumber%");
            })
          ->get();
           
         if(count($prosecution) > 0){
            $prosecution_id = $prosecution[0]->id;
            
 
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
    
                
             }
             return ['status' => true, "message" => 'মামলা বাতিল হয়েছে'];
        
         }else{
            return ['status' => false, "message" => 'মামলা বাতিল হয়নি'];
         }  
        
        
    }

    public function multiexplode($delimiters,$string) {

        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }
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

    public function caseTracker(){
        return 'caseTracker';
    }
    public function criminalTracker(){
        return 'criminalTracker';
    }
}
