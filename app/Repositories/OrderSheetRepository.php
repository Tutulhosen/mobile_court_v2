<?php

namespace App\Repositories;

use App\Models\OrderSheet;
use App\Models\Prosecution;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Exception;
class OrderSheetRepository
{


    public static function getOrderSheetLastVersion($prosecutionId){
        $orderSheet = DB::table('order_sheets')
        ->where('prosecution_id', $prosecutionId)
        ->where('delete_status', 1)
        ->orderBy('id', 'desc')
        ->first(); // Get the first result

        return $orderSheet ? $orderSheet->version : null; // Return version if order sheet exists
    }
    public static function makeExtendedOrderSheetBody($receipt_no, $formatted_date, $table, $receiver_id, $prosecution_id){
           
             // Get magistrate information (assuming you have an auth service that handles it)
    $magistrate = Auth::user(); // or however you retrieve the logged-in magistrate

    // 'of.organization_type as office_type',
    // 'of.dis_name_bn as location_details',
    // 'm.designation as designation_bng',
    // 'm.name as name_eng',
    // 'of.office_name_bn as location_str',
    // // 'fc.FileName as signature',
    // 'p.id',
    // 'courts.magistrate_id',
    // 'p.zillaId',
    // 'p.date',
    // 'p.id',
    // 'courts.id as court_id'
    // Query to get magistrate information
    $magistrate_info = DB::table('users as mag')
        ->join('office as job_des', 'job_des.id', '=', 'mag.office_id')
        ->select(
            'mag.id',
            'mag.name as name',
            DB::raw('CONCAT(mag.name, ",",job_des.office_name_bn) as name_eng'),
            // 'mag.national_id',
            'mag.mobile_no',
            'mag.email',
            'job_des.office_name_bn as location_str',
            'job_des.dis_name_bn'
        )
        // ->where('job_des.is_active', 1)
        // ->where('job_des.magistrate_id', $magistrate->id)
        // ->where('user_type_id', 6)
        ->first(); // Using first() instead of execute() to get a single record
 
    // Prepare fine string if receipt number is provided
    $str_fine = '';
    if (!empty($receipt_no)) {
        $str_fine = ' আসামি অর্থদণ্ড পরিশোধ করেন, যার রশিদ নম্বর– ' . $receipt_no . ', তারিখ- ' . $formatted_date . '। ';
    }

    // Append fine information to the table
    $table .= '<p>' . $str_fine . '</p>';

    // Get the last version number and increment by 1
    $version = self::getOrderSheetLastVersion($prosecution_id) + 1;

    // Construct the full HTML table
    $tableFull = '<table border="1" cellpadding="0" cellspacing="0" width="100%">
                    <tbody>
                        <tr>
                            <td align="center" valign="top" width="5%">
                                <span class="underline";>' . $version . '</span><br><font color="#fff"> আদেশের </font>
                            </td>
                            <td align="center" valign="top" width="10%"><br>' . $formatted_date . ' </td>
                            <td align="left" valign="middle" width="75%"><br>' . $table . '
                                <table border="0" width="100%">
                                    <tbody>
                                        <tr>
                                            <td width="40%">(সিলমোহর)</td>
                                            <td align="center" width="60%"><span>&nbsp;' . $formatted_date . '</span></td>
                                        </tr>
                                        <tr>
                                            <td width="40%"></td>
                                            <td align="center" width="60%"><span>' . $magistrate_info->name . '</span></td>
                                        </tr>
                                        <tr>
                                            <td width="40%"></td>
                                            <td align="center" width="60%">
                                                <span>' . $magistrate_info->location_str . '</span>&nbsp;
                                                &nbsp;<span>' .'$magistrate_info->location_details'. '</span>&nbsp;&nbsp;
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td align="center" valign="middle" width="10%">' . $receiver_id . ' </td>
                        </tr>
                    </tbody>
                  </table>';

    // Save the order sheet
    $flag =self::saveExtendedOrderSheet($tableFull,$prosecution_id, $receipt_no);
    return $flag;

    }

    public static function saveExtendedOrderSheet($tableBody,$prosecutionId,$receiptNo){
        $userinfo = Auth::user();
        $flag = false;
        $version = 0;
    
        // Start transaction
        DB::beginTransaction();
    
        try {
            // Get the previous order sheet version
            $previousOrderSheetVersion = self::getOrderSheetLastVersion($prosecutionId);
            if ($previousOrderSheetVersion) {
                $version = $previousOrderSheetVersion + 1;
            }
    
            // Create a new OrderSheet instance
            $orderSheet = new OrderSheet();
            $orderSheet->prosecution_id = $prosecutionId;
            $orderSheet->order_date = now(); // Current timestamp
            $orderSheet->order_details = $tableBody;
            $orderSheet->receipt_no = $receiptNo;
            $orderSheet->version = $version;
            $orderSheet->delete_status = 1;
            $orderSheet->created_by = $userinfo->email;
            $orderSheet->created_at = now();
    
            // Save the order sheet
            if (!$orderSheet->save()) {
                // Rollback and throw exception with error messages
                DB::rollBack();
                $errorMessages = implode(' ', $orderSheet->errors()->all());
                throw new Exception($errorMessages);
            }
    
            // Commit transaction
            DB::commit();
            $flag = true;
    
        } catch (Exception $e) {
            // Rollback on error
            DB::rollBack();
            throw $e;
        }
    
        return $flag;
    }
     
}