<?php
namespace App\Repositories;
use App\Models\Prosecution;
use App\Models\Seizurelist;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class SeizureRepository
{

     public static function getSeizureOrderContext($prosecution_id){
       
        $seizurelist = Seizurelist::where('prosecution_id',$prosecution_id)->get();

        $seizurelists = array();
        $seiz_group1 = "";
        $seiz_group2 = "";
        $seiz_group4 = "";
        $seiz_group6 = "";
        $seiz_group7 = "";

        $seiz_order1 = "";
        $seiz_order2 = "";
        $seiz_order4 = "";
        $seiz_order6 = "";
        $seiz_order7 = "";
     
        foreach ($seizurelist as $emp) {
           
            if ($emp->seizureitem_type_id == 1) {  //পঁচনশীল ও ধ্বংসযোগ্য
                if ($seiz_group1 == "") {
                    $seiz_group1 = $emp->stuff_description;
                } else {
                    $seiz_group1 .= "," . $emp->stuff_description;
                }

            }
           
            if ($emp->seizureitem_type_id == 2) { // পঁচনশীল ও বিক্রয়যোগ্য
                if ($seiz_group2 == "") {
                    $seiz_group2 = $emp->stuff_description;
                } else {
                    $seiz_group2 .= "," . $emp->stuff_description;
                }

            }
            if ($emp->seizureitem_type_id == 4) { // বিক্রয়যোগ্য

                if ($seiz_group4 == "") {
                    $seiz_group4 = $emp->stuff_description;
                } else {
                    $seiz_group4 .= "," . $emp->stuff_description;
                }

            }
            if ($emp->seizureitem_type_id == 6) { // সংরক্ষণযোগ্য
                if ($seiz_group6 == "") {
                    $seiz_group6 .= $emp->stuff_description;
                } else {
                    $seiz_group6 .= "," . $emp->stuff_description;
                }
            }

        
            $c = array(
                "stuff_description" => $emp->stuff_description,
                "stuff_weight" => $emp->stuff_weight,
                "location" => $emp->location,
                "group" => $emp->seizureitem_type_group
            );
            $seizurelists[] = $c;
        }
      
        if ($seiz_group1 != '') {
            $seiz_order1 .= "\n জব্দকৃত মালামাল রাষ্ট্রের অনুকূলে বাজেয়াপ্ত করা হলো।  " . "জব্দকৃত মালামাল " . $seiz_group1 . " ক্ষতিকর ও ধ্বংসযোগ্য। দায়িত্বপ্রাপ্ত ব্যাক্তি-কে  জব্দকৃত মালামালের নমুনা সংরক্ষণপূর্বক ধ্বংসের ব্যবস্থা গ্রহণ করার জন্য বলা হলো।";
        }
        if ($seiz_group2 != '') {
            $seiz_order2 .= "\n জব্দকৃত মালামাল রাষ্ট্রের অনুকূলে বাজেয়াপ্ত করা হলো।  " . "জব্দকৃত মালামাল " . $seiz_group2 . " পঁচনশীল ও বিক্রয়যোগ্য। দায়িত্বপ্রাপ্ত ব্যাক্তি-কে  জব্দকৃত মালামাল নিলামে বিক্রয়ের ব্যবস্থা গ্রহণ করার জন্য বলা হলো।";
        }
        if ($seiz_group4 != '') {
            $seiz_order4 .= "\n জব্দকৃত মালামাল রাষ্ট্রের অনুকূলে বাজেয়াপ্ত করা হলো।  " . "জব্দকৃত মালামাল " . $seiz_group4 . " বিক্রয়যোগ্য। দায়িত্বপ্রাপ্ত ব্যাক্তি-কে জব্দকৃত মালামাল নিলামে বিক্রয়ের ব্যবস্থা গ্রহণ করার জন্য বলা হলো।";
        }
        if ($seiz_group6 != '') {
            $seiz_order6 .= "\n জব্দকৃত মালামাল রাষ্ট্রের অনুকূলে বাজেয়াপ্ত করা হলো।  " . "জব্দকৃত মালামাল " . $seiz_group6 . " সংরক্ষণযোগ্য। দায়িত্বপ্রাপ্ত ব্যাক্তি-কে জব্দকৃত মালামাল জিম্মায় রাখার জন্য বলা হলো।";
        }
        if ($seiz_group7 != '') {
            $seiz_order7 = "";
        }
      
        $seizure_order = $seiz_order1 . $seiz_order2 . $seiz_order4 . $seiz_order6 . $seiz_order7;
        $ddd=  array(
         $seizure_order
        );
        return $ddd;
     }

     public static function saveSeizureList($prosecution_id,$seizure_data,$seizureitem_type=null){
        $prosecution = Prosecution::find($prosecution_id);
        
        $seizurelist = Seizurelist::where('prosecution_id', $prosecution_id)->get();
        foreach ($seizurelist as $item) {
            $seizure = Seizurelist::find($item->id);
            $seizure->delete();
        }
        

        // $seizureitemType_id = SeizureitemType::find();
        $seizureitemType_id = $seizureitem_type; // data get from common module
        $seizurelists = [];
        foreach ($seizure_data as $sizureitem) {

            $seizureitem_type_group = "";
            foreach ($seizureitemType_id as $stype) {
                if ($stype->id == $sizureitem[4]) {
                    $seizureitem_type_group = $stype->item_group;
                }
            }

            $seizurelist = new Seizurelist();
            $seizurelist->stuff_description = $sizureitem[1];
            $seizurelist->stuff_weight = $sizureitem[2];
            $seizurelist->apx_value = $sizureitem[3];
            $seizurelist->seizureitem_type_id = $sizureitem[4];
            $seizurelist->seizureitem_type_group = $seizureitem_type_group;
            $seizurelist->hints = $sizureitem[5];
            $seizurelist->location = $prosecution->location;
            $seizurelist->prosecution_id = $prosecution_id;
            $seizurelist->date = date('Y-m-d  H:i:s');
            $seizurelist->created_by ='test@gmail.com';
            // $seizurelist->created_date = date('Y-m-d  H:i:s');
            $seizurelist->updated_by = 'test@gmail.com';
            // $seizurelist->update_date = date('Y-m-d');
            $seizurelist->delete_status = 1;

            // $prosecution->case_status = 5;
         
            $seizurelist->save();
            $seizurelists[] = [
                $seizurelist->stuff_description = $sizureitem[1],
                $seizurelist->stuff_weight = $sizureitem[2],
                $seizurelist->apx_value = $sizureitem[3],
                $seizurelist->seizureitem_type_id = $sizureitem[4],
                $seizurelist->seizureitem_type_group = $seizureitem_type_group,
                $seizurelist->hints = $sizureitem[5],
                $seizurelist->location = $prosecution->location,
                $seizurelist->prosecution_id = $prosecution_id,
                $seizurelist->date = date('Y-m-d  H:i:s'),
                $seizurelist->created_by ='test@gmail.com',
                // $seizurelist->created_date = date('Y-m-d  H:i:s'),
                $seizurelist->updated_by = 'test@gmail.com',
                // $seizurelist->update_date = date('Y-m-d'),
                $seizurelist->delete_status = 1,
            ];
            $prosecution->case_status = 5;
            $prosecution->save();
            // print_r($seizurelist);
         
        }
        return $seizurelists;

     }


     public static function saveSeizureList_api($prosecution_id, $seizure_data,$seizureitem_type=null,$user)
    {
        $prosecution = Prosecution::find($prosecution_id);

        $seizurelist = Seizurelist::where('prosecution_id', $prosecution_id)->get();
        foreach ($seizurelist as $item) {
            $seizure = Seizurelist::find($item->id);
            $seizure->delete();
        }

        // $seizureitemType_id = SeizureitemType::find();
         $seizureitemType_id = $seizureitem_type; // data get from common module

        foreach ($seizure_data as $sizureitem) {
            $seizureitem_type_group = "";
            foreach ($seizureitemType_id as $stype) {
             
                if ($stype['id'] == $sizureitem['seizue_type']) {
                    // return $stype['id'];
                    $seizureitem_type_group = $stype['item_group'];
                    // return $seizureitem_type_group;
                }
            }
            // return $seizureitem_type_group;
            // DB::table('seizurelists')->insert([
            //     'date'=> date('Y-m-d H:i:s'),
            //     'stuff_weight'=>$sizureitem[2],
            //     'apx_value'=> $sizureitem[3],
            //     'seizureitem_type_id'=>$sizureitem[4], 
            //     'seizureitem_type_group' => $seizureitem_type_group,
            //     'hints'=> $sizureitem[5],
            //     'location'=> $prosecution->location,
            //     'prosecution_id'=> $prosecution_id,
            //     'created_by'=>'test@gmail.com',
            //     'updated_by'=>'test@gmail.com',
            //     'delete_status'=>1
            // ]);


            // $prosecution->case_status = 5;
            // $prosecution->save();
            $seizurelist = new Seizurelist();
            $seizurelist->stuff_description = $sizureitem['stuff_description'];
            $seizurelist->stuff_weight = $sizureitem['stuff_weight'];
            $seizurelist->apx_value = $sizureitem['apx_price'];
            $seizurelist->seizureitem_type_id = $sizureitem['seizue_type'];
            $seizurelist->seizureitem_type_group = $seizureitem_type_group;
            $seizurelist->hints = $sizureitem['comment'];
            $seizurelist->location = $prosecution->location;
            $seizurelist->prosecution_id = $prosecution_id;
            $seizurelist->date = date('Y-m-d  H:i:s');
            $seizurelist->created_by = $user->email;
            // $seizurelist->created_date = date('Y-m-d  H:i:s');
            $seizurelist->updated_by = $user->email;
            // $seizurelist->update_date = date('Y-m-d');
            $seizurelist->delete_status = 1;

            // $prosecution->case_status = 5;

            $seizurelist->save();
            $prosecution->case_status = 5;
            $prosecution->save();
            
        }
        return $seizurelist;
    }
}