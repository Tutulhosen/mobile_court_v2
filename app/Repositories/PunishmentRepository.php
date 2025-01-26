<?php
namespace App\Repositories;

use App\Models\OrderSheet;
use App\Models\OrderText;
use App\Models\Punishment;
use Illuminate\Support\Facades\DB;

class PunishmentRepository
{
    public static function isPunishmentExist($data)
    {

        $flag = 0;
        foreach ($data as $punishment) {
            $prosecutionId = $punishment['prosecution_id'];
            $lawsBrokenId = $punishment['laws_broken_id'];
            $criminalId = $punishment['criminal_id'];
            // $conditions = ['prosecution_id' => $prosecutionId, 'criminal_id' => $criminalId, 'laws_broken_id' => $lawsBrokenId];
            // $punishmentTable = Punishment::findFirst(
            //     [
            //         "conditions" => 'prosecution_id=:prosecution_id: AND criminal_id=:criminal_id: AND laws_broken_id=:laws_broken_id:',
            //         'bind' => $conditions,
            //     ]
            // );

            $conditions = [
                'prosecution_id' => $prosecutionId,
                'criminal_id' => $criminalId,
                'laws_broken_id' => $lawsBrokenId,
            ];

            $punishmentTable = Punishment::where('prosecution_id', $conditions['prosecution_id'])
                ->where('criminal_id', $conditions['criminal_id'])
                ->where('laws_broken_id', $conditions['laws_broken_id'])
                ->first();
            if ($punishmentTable) {
                $flag = 1;
                break;
            }
        }
        return $flag;
    }

    public static function savePunishment($data, $logininfo)
    {

        //        $order_text_id = $this->saveOrderText($data[0]['prosecution_id'],$data[0]['punishment_type'],$data[0]['order_detail']);
        $order_text_id = self::saveOrderText($data[0]['prosecution_id'], $data[0]['punishment_type'], $logininfo);
        $array = array();
        foreach ($data as $punishment) {
            $prosecutionId = $punishment['prosecution_id'];
            $lawsBrokenId = $punishment['laws_broken_id'];
            $criminalId = $punishment['criminal_id'];
            $conditions = [
                'prosecution_id' => $prosecutionId,
                'criminal_id' => $criminalId,
                'laws_broken_id' => $lawsBrokenId,
            ];
            $punishmentTable = Punishment::where($conditions)->first();

            if ($punishmentTable) {
                return;
            }

            $punishmentTable = new Punishment();

            $punishmentTable->created_by = $logininfo['email'];
            //   $punishmentTable->created_date = date('Y-m-d  H:i:s');
            // set order_text_id from orderText table
            $punishmentTable->order_text_id = $order_text_id ? $order_text_id : null;

            $punishmentTable->prosecution_id = (!empty($punishment['prosecution_id']) ? $punishment['prosecution_id'] : null);
            $punishmentTable->criminal_id = (!empty($punishment['criminal_id']) ? $punishment['criminal_id'] : null);
            $punishmentTable->laws_broken_id = (!empty($punishment['laws_broken_id']) ? $punishment['laws_broken_id'] : null);
            $punishmentTable->order_type = (!empty($punishment['punishment_type']) ? $punishment['punishment_type'] : null);

            //handle punishment table order detail field
            if ($punishment['punishment_type'] == 'RELEASE') {
                $punishmentTable->order_detail = 'অব্যাহতি প্রদান করা হল';
            } elseif ($punishment['punishment_type'] == 'REGULARCASE') {
                if ($punishment['regular_case_type_name'] == "HIGHCOURT") {
                    if ($punishment['responsibleDepartmentName'] != null) {
                        $punishmentTable->order_detail = $punishment['responsibleDepartmentName'] . "," . $punishment['responsibleAdalotAddress'] . ' এর আদালত বরাবর বিচারার্থে প্রেরণ করা হোক ।';
                    } else {
                        $punishmentTable->order_detail = 'উচ্চ আদালত বরাবর বিচারার্থে প্রেরণ করা হোক ।';
                    }
                } else {
                    if ($punishment['responsibleDepartmentName'] != null) {
                        $punishmentTable->order_detail = $punishment['responsibleDepartmentName'] . "," . $punishment['responsibleAdalotAddress'] . ' থানায় প্রেরণ করা হোক ।';
                    } else {
                        $punishmentTable->order_detail = 'থানায় প্রেরণ করা হোক ।';
                    }
                }

            } else {
                $punishmentTable->order_detail = $punishment['order_detail'] ? $punishment['order_detail'] : null;
            }

            // Punishment
            $punishmentTable->fine_in_word = (!empty($punishment['fine_in_word']) ? $punishment['fine_in_word'] : null);
            $punishmentTable->fine = (!empty($punishment['fine']) ? $punishment['fine'] : null);
            $punishmentTable->warrent_duration = (!empty($punishment['warrent_duration']) ? $punishment['warrent_duration'] : null);
            $punishmentTable->warrent_detail = (!empty($punishment['warrent_detail']) ? $punishment['warrent_detail'] : null);
            $punishmentTable->warrent_type = (!empty($punishment['warrent_type']) ? $punishment['warrent_type'] : null);
            $punishmentTable->warrent_type_text = (!empty($punishment['warrent_type_text']) ? $punishment['warrent_type_text'] : null);
            $punishmentTable->rep_warrent_duration = (!empty($punishment['rep_warrent_duration']) ? $punishment['rep_warrent_duration'] : null);
            $punishmentTable->rep_warrent_detail = (!empty($punishment['rep_warrent_detail']) ? $punishment['rep_warrent_detail'] : null);
            $punishmentTable->rep_warrent_type = (!empty($punishment['rep_warrent_type']) ? $punishment['rep_warrent_type'] : null);
            $punishmentTable->rep_warrent_type_text = (!empty($punishment['rep_warrent_type_text']) ? $punishment['rep_warrent_type_text'] : null);
            $punishmentTable->receipt_no = (!empty($punishment['receipt_no']) ? $punishment['receipt_no'] : null);
            $punishmentTable->punishmentJailID = (!empty($punishment['punishmentJailID']) ? $punishment['punishmentJailID'] : null);
            $punishmentTable->punishmentJailName = (!empty($punishment['punishmentJailName']) ? $punishment['punishmentJailName'] : null);
            $punishmentTable->exe_jail_type = (!empty($punishment['exe_jail_type']) ? $punishment['exe_jail_type'] : null);
            //$punishmentTable->orderJustificationText = $punishment['orderJustificationText']?$punishment['orderJustificationText']:null;

            // RegularCase
            $punishmentTable->regular_case_type_name = (!empty($punishment['regular_case_type_name']) ? $punishment['regular_case_type_name'] : null);
            $punishmentTable->responsibleThanaID = (!empty($punishment['responsibleThanaID']) ? $punishment['responsibleThanaID'] : null);
            $punishmentTable->responsibleDepartmentName = (!empty($punishment['responsibleDepartmentName']) ? $punishment['responsibleDepartmentName'] : null);
            $punishmentTable->responsibleAdalotAddress = (!empty($punishment['responsibleAdalotAddress']) ? $punishment['responsibleAdalotAddress'] : null);

            // Updated By
            $punishmentTable->updated_by = $logininfo['email'];
            //   $punishmentTable->update_date = date('Y-m-d  H:i:s');

            if (!$punishmentTable->save()) {
                $errorMessage = "";
                foreach ($punishmentTable->getMessages() as $message) {
                    $errorMessage .= $message;
                }
                //   $this->db->rollback();
                //   throw new Exception($errorMessage);
            } else {
                $array[] = $punishmentTable;
            }
        }
        //   $this->db->commit();
        return $array;

    }

    private static function saveOrderText($prosecution_id, $order_type, $logininfo)
    {

        $orderText = new OrderText();
        $orderText->prosecution_id = $prosecution_id;
        $orderText->order_type = $order_type;
        $orderText->note = "হয়েছে";
        $orderText->created_by = $logininfo['email'];
        $orderText->updated_by = $logininfo['email'];
        // $orderText->created_date = date('Y-m-d  H:i:s');

        // $this->db->begin();
        if (!$orderText->save()) {

            $errorMessage = "";
            foreach ($orderText->getMessages() as $message) {
                $errorMessage .= $message;
            }
            // $this->db->rollback();
            // throw new Exception($errorMessage);
        } else {
            // $this->db->commit();
            return $orderText->id;
        }
    }

    public static function getPunishmentInfo($prosecution_id)
    {

        $punishmentInfo = DB::table('punishments as p')
            ->select('c.name_bng',
                DB::raw('GROUP_CONCAT(s.sec_title, \' এর \', s.sec_number, \' ধারার \',
                CASE
                    WHEN ccl.Confessed = 1 THEN "অভিযোগ স্বীকার করেন"
                    WHEN ccl.Confessed = 0 THEN "অভিযোগ অস্বীকার করেন"
                END
            ) as lawWiseConfessionText'),
                'cc.description')
            ->join('criminals as c', 'c.id', '=', 'p.criminal_id')
            ->join('criminal_confessions as cc', function ($join) use ($prosecution_id) {
                $join->on('cc.prosecution_id', '=', 'p.prosecution_id')
                    ->on('cc.criminal_id', '=', 'p.criminal_id');
            })
            ->join('laws_brokens as lb', 'lb.id', '=', 'p.laws_broken_id')
            ->join('criminal_confession_lawsbrokens as ccl', function ($join) {
                $join->on('ccl.LawsBrokenID', '=', 'lb.id')
                    ->on('ccl.CriminalConfessionID', '=', 'cc.id');
            })
            ->join('mc_law as l', 'l.id', '=', 'lb.Law_id')
            ->join('mc_section as s', function ($join) {
                $join->on('s.id', '=', 'lb.section_id')
                    ->on('s.law_id', '=', 'l.id');
            })
            ->where('p.prosecution_id', $prosecution_id)
            ->groupBy('c.id')
            ->get();

        return $punishmentInfo;

        // $punishmentInfo = DB::table('punishment as p')
        //     ->join('criminal as c', 'c.id', '=', 'p.criminal_id')
        //     ->join('criminal_confession as cc', function($join) {
        //         $join->on('cc.prosecution_id', '=', 'p.prosecution_id')
        //             ->on('cc.criminal_id', '=', 'p.criminal_id');
        //     })
        //     ->join('laws_broken as lb', 'lb.LawsBrokenID', '=', 'p.laws_broken_id')
        //     ->join('criminal_confession_lawsbroken as ccl', function($join) {
        //         $join->on('ccl.LawsBrokenID', '=', 'lb.LawsBrokenID')
        //             ->on('ccl.CriminalConfessionID', '=', 'cc.id');
        //     })
        //     ->where('p.prosecution_id', $prosecution_id)
        //     ->groupBy('c.id')
        //     // ->selectRaw('
        //     //     c.name_bng,
        //     //     GROUP_CONCAT(
        //     //         s.sec_title, " এর ", s.sec_number, " ধারার ",
        //     //         (CASE
        //     //             WHEN ccl.Confessed = 1 THEN "অভিযোগ স্বীকার করেন"
        //     //             WHEN ccl.Confessed = 0 THEN "অভিযোগ অস্বীকার করেন"
        //     //         END)
        //     //     ) as lawWiseConfessionText,
        //     //     cc.description
        //     // ')
        //     ->get();

        //       // ->join('law as l', 'l.id', '=', 'lb.LawID')
        //     // ->join('section as s', function($join) {
        //     //     $join->on('s.id', '=', 'lb.SectionID')
        //     //         ->on('s.law_id', '=', 'l.id');
        //     // })
        // return $punishmentInfo;
    }

    // public static function deletePunishmentByOrderId($proceutionId,$orderId){
    //     // DB::beginTransaction();
    //     // try {
    //         // Find punishments matching the given conditions
    //         $punishments = Punishment::where('prosecution_id', $proceutionId)
    //             ->where('order_text_id', $orderId)
    //             ->get();

    //         // Delete each punishment and the related order text
    //         foreach ($punishments as $punishment) {
    //             $punishment->delete();
    //             // Also, delete the related order text
    //             $orderText = OrderText::find($orderId);
    //             if ($orderText) {
    //                 $orderText->delete();
    //             } else {
    //                 // DB::rollBack();
    //                 return 'error';
    //             }
    //         }

    //         // DB::commit();
    //         return 'success';
    //     // } catch (\Exception $e) {
    //     //     DB::rollBack();
    //     //     return 'error';
    //     // }

    // }

    public static function deletePunishmentByOrderId($prosecutionId, $orderId)
    {

        $orderText = OrderText::find($orderId);
        if (!$orderText) {
            return 'OrderText not found';
        }

        Punishment::where('prosecution_id', $prosecutionId)
            ->where('order_text_id', $orderId)
            ->delete();

        $orderText->delete();

        return 'success';
    }


    public static function getOrderListByProsecutionId($prosecutionId)
    {

        //     $punishmentQuery = 'SELECT
        //         SUM(pn.fine) AS orderTotalFine,
        //         pn.warrent_duration AS warrentDuration,
        //         pn.receipt_no AS receiptNo,
        //         pn.rep_warrent_duration,
        //         pn.punishmentJailName,
        //         pn.punishmentJailID,
        //         responsibleDepartmentName,
        //         responsibleAdalotAddress,
        //         GROUP_CONCAT(
        //             DISTINCT CASE
        //                 WHEN ccl.Confessed = 1 THEN "স্বীকার করায়"
        //                 WHEN ccl.Confessed = 0 THEN "অস্বীকার করায়"
        //             END
        //         ) AS lawAndSectionConfession,
        //         GROUP_CONCAT(
        //             DISTINCT CASE
        //                 WHEN pn.order_type = "PUNISHMENT" THEN " ধারায় দোষী সাবাস্ত করে "
        //                 WHEN pn.order_type = "REGULARCASE" THEN " ধারার অভিযোগে "
        //                 WHEN pn.order_type = "RELEASE" THEN " ধারার অভিযোগ হতে "
        //             END
        //         ) AS lawAndSectionPunishment,
        //         GROUP_CONCAT(
        //             DISTINCT CASE
        //                 WHEN pn.order_type = "PUNISHMENT" THEN " ধারায় দোষী সাবাস্ত করে "
        //                 WHEN pn.order_type = "REGULARCASE" THEN " ধারার অভিযোগে "
        //                 WHEN pn.order_type = "RELEASE" THEN " ধারার অভিযোগ হতে "
        //             END
        //         ) AS lawAndSectionDharaHote,
        //         GROUP_CONCAT(DISTINCT pn.order_detail) AS DistinctOrder,
        //         GROUP_CONCAT(DISTINCT c.name_bng, "<br>") AS criminalName,
        //         GROUP_CONCAT(DISTINCT c.name_bng) AS CriminalName,
        //         CONCAT(pn.order_type) AS orderType,
        //         GROUP_CONCAT(pn.laws_broken_id) AS lawsBrokenId,
        //         pn.order_text_id AS orderId
        //     FROM
        //         punishments pn
        //     JOIN criminals c ON c.id = pn.criminal_id
        //     JOIN laws_brokens lb ON lb.id = pn.laws_broken_id
        //     JOIN criminal_confessions cc ON cc.prosecution_id = pn.prosecution_id AND cc.criminal_id = pn.criminal_id
        //     JOIN criminal_confession_lawsbrokens ccl ON ccl.LawsBrokenID = lb.id AND ccl.CriminalConfessionID = cc.id
        //     WHERE pn.prosecution_id = :prosecutionId
        //     GROUP BY
        //         pn.order_text_id,
        //         pn.warrent_duration,
        //         pn.receipt_no,
        //         pn.rep_warrent_duration,
        //         pn.punishmentJailName,
        //         pn.punishmentJailID,
        //         responsibleDepartmentName,
        //         responsibleAdalotAddress,
        //         pn.order_type
        // ';

        // $punishmentList = DB::select($punishmentQuery, ['prosecutionId' => $prosecutionId]);

        // return $punishmentList;
        $punishmentList = DB::select("SELECT
        SUM(pn.fine) AS orderTotalFine,
        pn.warrent_duration AS warrentDuration,
        pn.receipt_no AS receiptNo,
        pn.rep_warrent_duration,
        pn.punishmentJailName,
        pn.punishmentJailID,
        responsibleDepartmentName,
        responsibleAdalotAddress,
        GROUP_CONCAT(
            DISTINCT CONCAT(s.sec_title, ' এর ', s.sec_number, ' ধারার অভিযোগ',
                CASE
                    WHEN ccl.Confessed = 1 THEN ' স্বীকার করায়'
                    WHEN ccl.Confessed = 0 THEN ' অস্বীকার করায়'
                END)
        ) AS lawAndSectionConfession,
        GROUP_CONCAT(DISTINCT CONCAT(s.sec_title, ' এর ', s.sec_number, ' ধারার<br>')) AS lawAndSection,
        GROUP_CONCAT(DISTINCT CONCAT(s.sec_title, ' এর ', s.sec_number,
            CASE
                WHEN pn.order_type='PUNISHMENT' THEN ' ধারায় দোষী সাবাস্ত করে '
                WHEN pn.order_type='REGULARCASE' THEN ' ধারার অভিযোগে '
                WHEN pn.order_type='RELEASE' THEN ' ধারার অভিযোগ হতে '
            END)
        ) AS lawAndSectionPunishment,
        GROUP_CONCAT(DISTINCT CONCAT(s.sec_title, ' এর ', s.sec_number,
            CASE
                WHEN pn.order_type='PUNISHMENT' THEN ' ধারায় দোষী সাবাস্ত করে '
                WHEN pn.order_type='REGULARCASE' THEN ' ধারার অভিযোগে '
                WHEN pn.order_type='RELEASE' THEN ' ধারার অভিযোগ হতে '
            END)
        ) AS lawAndSectionDharaHote,
        GROUP_CONCAT(DISTINCT pn.order_detail) AS DistinctOrder,
        GROUP_CONCAT(DISTINCT c.name_bng, '<br>') AS criminalName,
        GROUP_CONCAT(DISTINCT c.name_bng) AS CriminalName,
        CONCAT(pn.order_type) AS orderType,
        GROUP_CONCAT(pn.laws_broken_id) AS lawsBrokenId,
        pn.order_text_id AS orderId
    FROM
        punishments pn
    JOIN
        criminals c ON c.id = pn.criminal_id
    JOIN
        laws_brokens lb ON lb.id = pn.laws_broken_id
    JOIN
        mc_section s ON s.law_id = lb.Law_id AND s.id = lb.section_id
    JOIN
        criminal_confessions cc ON cc.prosecution_id = pn.prosecution_id AND cc.criminal_id = pn.criminal_id
    JOIN
        criminal_confession_lawsbrokens ccl ON ccl.LawsBrokenID = lb.id AND ccl.CriminalConfessionID = cc.id
    WHERE
        pn.prosecution_id = :prosecutionId
    GROUP BY
        pn.order_text_id
", ['prosecutionId' => $prosecutionId]);

        return $punishmentList;

    }

    public static function saveOrderSheet($header, $tableBody, $prosecutionId)
    {
        $flag = 'false';
        $orderSheet = new OrderSheet();
        $orderSheet->prosecution_id = $prosecutionId;
        $orderSheet->order_date = date('Y-m-d  H:i:s');
        $orderSheet->order_header = $header;
        $orderSheet->order_details = $tableBody;
        $orderSheet->receipt_no = null;
        $orderSheet->version = 1;
        $orderSheet->delete_status = 1;
        $orderSheet->created_by = 'admin@gmail.com';
        $orderSheet->created_at = date('Y-m-d  H:i:s');

        if (!$orderSheet->save()) {
            $errorMessage = "";
            foreach ($orderSheet->getMessages() as $message) {
                $errorMessage .= $message;
            }
            // $this->db->rollback();
            // throw new Exception($errorMessage);
        } else {
            $flag = ProsecutionRepository::updateProsecutionOrderSheetId($prosecutionId, $orderSheet->id);
            // $this->db->commit();
        }
        return $flag;
    }

    public static function punishmentJailInfoCriminal($prosecution_id)
    {
        //         $sql = "SELECT
        //         p.receipt_no,
        //         p.criminal_id,
        //         c.name_bng,
        //         c.age,
        //         c.occupation,
        //         c.custodian_type,
        //         c.custodian_name,
        //         c.permanent_address,
        //         p.exe_jail_type,
        //         p.rep_warrent_duration,
        //         concat(s.sec_title, ' -এর ', s.sec_number, ' ধারার অভিযোগ হতে ', p.warrent_duration) AS lawSectionJail,
        //         concat(s.sec_title, ' -এর ', s.sec_number, ' ধারার ') AS lawSection,
        //         p.warrent_duration,
        //         p.punishmentJailName,
        //         lb.law_id,
        //         lb.Description,
        //         s.sec_title,
        //         s.sec_number,
        //         s.punishment_sec_number
        //     FROM punishments p
        //     JOIN laws_brokens lb ON lb.law_id = p.laws_broken_id
        //     JOIN criminals c ON c.id = p.criminal_id
        //     JOIN mc_section s ON s.id = lb.section_id AND s.law_id = lb.law_id
        //     WHERE p.prosecution_id = :prosecution_id
        //     AND p.order_type = 'PUNISHMENT'
        //     AND p.punishmentJailID IS NOT NULL
        //     GROUP BY p.laws_broken_id, p.criminal_id";

        // // Execute the query with bindings
        // $punishmentJailInfo = DB::select($sql, ['prosecution_id' => $prosecution_id]);

        // return $punishmentJailInfo;

        $punishmentJailInfo = DB::table('punishments as p')
            ->select(
                'p.receipt_no',
                'p.criminal_id',
                'c.name_bng',
                'c.age',
                'c.occupation',
                'c.custodian_type',
                'c.custodian_name',
                'c.permanent_address',
                'p.exe_jail_type',
                'p.rep_warrent_duration',
                DB::raw('CONCAT(s.sec_title, " -এর ", s.sec_number, " ধারার অভিযোগ হতে ", p.warrent_duration) AS lawSectionJail'),
                DB::raw('CONCAT(s.sec_title, " -এর ", s.sec_number, " ধারার ") AS lawSection'),
                'p.warrent_duration',
                'p.punishmentJailName',
                'lb.id',
                'lb.Description',
                's.sec_title',
                's.sec_number',
                's.punishment_sec_number'
            )
            ->join('laws_brokens as lb', 'lb.id', '=', 'p.laws_broken_id')
            ->join('criminals as c', 'c.id', '=', 'p.criminal_id')
            ->join('mc_section as s', 's.id', '=', 'lb.section_id')
            ->where('p.prosecution_id', $prosecution_id)
            ->where('p.order_type', 'PUNISHMENT')
            ->whereNotNull('p.punishmentJailID')
            ->groupBy('p.laws_broken_id', 'p.criminal_id')
            ->get();

        return $punishmentJailInfo;
    }
}
