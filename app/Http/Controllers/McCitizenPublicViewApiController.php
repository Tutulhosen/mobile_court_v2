<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Models\FileContent;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CitizenComplain;
use Illuminate\Support\Facades\DB;

class McCitizenPublicViewApiController extends Controller
{
    public function store(Request $request)
    {
        $requestData = $request->all();
        // return  ['fdsaf'=> $requestData['body_data']];
        $request = $requestData['body_data'];
        $timeString = $request['time'];
        // Convert to Carbon instance
        $time = Carbon::createFromFormat('h:i A', $timeString);
        // Format it to 24-hour format
        $formattedTime = $time->format('H:i:s');
        $citizen_complain = new CitizenComplain();

        $citizen_complain->category_id = $request['CaseType'];
        $citizen_complain->name_bng = $request['name_bng'];
        $citizen_complain->name_eng =  $request['name_bng'];
        $citizen_complain->complain_details = $request['complain_details'];
        $citizen_complain->mobile = $request['mobile'];
        $citizen_complain->email = $request['email'];
        $citizen_complain->national_idno = $request['national_idno'];
        $citizen_complain->citizen_address = $request['citizen_address'];
        $citizen_complain->divid = $request['division'];
        $citizen_complain->zillaId = $request['zilla'];
        $citizen_complain->upazilaid = $request['upazila'];
        $citizen_complain->geo_citycorporation_id = $request['GeoCityCorporations'] ? $request['GeoCityCorporations'] : null;
        $citizen_complain->geo_metropolitan_id = $request['GeoMetropolitan'] ? $request['GeoMetropolitan'] : null;
        $citizen_complain->geo_thana_id = $request['GeoThanas'] ? $request['GeoThanas'] : null;
        $citizen_complain->geo_ward_id = null;
        $citizen_complain->complain_date = date('Y-m-d', strtotime($request['complain_date']));
        $citizen_complain->time = $formattedTime;
        $citizen_complain->location = $request['location'];
        $citizen_complain->subject = $request['subject'];
        $citizen_complain->gender = $request['gender'];
        $citizen_complain->complain_status = "initial";
        $citizen_complain->feedback = 'নাই';
        $citizen_complain->feedback_date = date('Y-m-d');
        $date = new \DateTime();

        $citizen_complain->created_by = 'Jafrin';
        $citizen_complain->created_date = $date->format("Y-m-d H:i:s");
        $citizen_complain->update_by = 'Jafrin';
        $citizen_complain->update_date = $date->format("Y-m-d");
        $citizen_complain->delete_status = 1;
        $citizen_complain->location_str = "test";

        $digits = 4;
        $randno =  str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
        $code =  "CP-" . $citizen_complain->divid . '-' . $citizen_complain->complain_date . '-' . $randno;
        $citizen_complain->user_idno = $code;
        // $citizen_complain->complain_id =  UUID::generate(UUID::UUID_TIME, UUID::FMT_STRING, "a2ictz");
        $citizen_complain->complain_id = (string) Str::uuid();
        $date = date('Y-m-d-H-i-s');
        $filePrefix = "comp_";
        $new_file_name = $filePrefix . $date;
        $citizen_complain->file =  $new_file_name;
        $result = $citizen_complain->save();

        if ($result) {
            $entityID = $citizen_complain->id;
        }
        
        if (isset($request['files'])) {
            $Files = $request['files'];
            $appName = 'MobileCourt';
            $fileCategory = 'CitizenComplaint';
            $fileCaption = 'No Caption';
            // FileRepository::deleteExistingFiles($entityID, $fileCategory);
            if (!empty($Files) && is_array($Files)) {
                foreach ($Files as $File) {
                    $parameter = [
                        "entityID" => $entityID,
                        "appName" => $appName,
                        "fileCategory" => $fileCategory,
                        "fileCaption" => $fileCaption,
                        "fileBase64" => $File,
                    ];

                    if ($File) {
                        $newRequest = new Request($parameter);
                        $this->fileSave($newRequest);
                    }
                }
            }
        }
        return ['data' => $citizen_complain, 'result' => $result];
    }

    public function search(Request $request)
    {
        $requestData = $request->all();
        //   dd(['fdsaf'=> $requestData['body_data']]);
        $request = $requestData['body_data'];
        $rResult = DB::table('court_complain_infos AS cci')
            ->select([
                'cci.user_idno AS complain_no',
                'cci.complain_status AS complain_status',
                'mag.name AS magistrate_name',
                'cci.prosecution_id AS prosecution_id',
                'cci.case_number AS case_no',
                'cci.case_status AS case_status',
                'cci.prosecution_date AS prosecution_date',
                'cci.magistrate_id AS magistrate_id',
            ])
            ->join('users AS mag', 'mag.id', '=', 'cci.magistrate_id')
            // ->join('doptor_user_access_info AS duai', 'duai.user_id', '=', 'mag.id')
            ->where('cci.user_idno', $request['caseNumber'])
            // ->where('duai.role_id', '=', 26)
            ->get();

        // if (!count($rResult)) {
        //     return ['success' => 'error', 'message' => "Can't find the mamla"];
        // } else {
        //     $findedData = $rResult[0];
        //     if ($findedData->complain_status == "initial") {
        //         $findedData->status =  "জেলা ম্যাজিস্ট্রেটের নিকট অপেক্ষমান";
        //         return ['success' => 'success', 'data' => $findedData];
        //     } elseif ($findedData->complain_status == "accepted") {
        //         $findedData->status = "ম্যাজিস্ট্রেটের  নিকট অপেক্ষমান।";
        //         return ['success' => 'success', 'data' => $findedData];
        //     } elseif ($findedData->complain_status == "ignore") {
        //         $findedData->status = "অভিযোগটি নথিজাত করা হয়েছে   ।";
        //         return ['success' => 'success', 'data' => $findedData];
        //     } elseif ($findedData->complain_status == "solved") {
        //         $findedData->status = 'নিষ্পত্তি (মামলা হয়নি) ।';
        //         return ['success' => 'success', 'data' => $findedData];
        //     } elseif ($findedData->case_status == "solved") {
        //         $findedData->status = 'নিষ্পত্তি (মামলা হয়েছে)।';
        //         $punishement = DB::table('punishments as pm')
        //             ->select('pm.order_detail as punishment', 'lb.Description as law_section')
        //             ->join('laws_brokens as lb', 'lb.prosecution_id', '=', 'pm.prosecution_id')
        //             ->where('pm.prosecution_id', $findedData->prosecution_id)->get();
        //         $findedData->law_section = $punishement[0]->law_section;
        //         $findedData->punishment = $punishement[0]->punishment;
        //         return ['success' => 'success', 'data' => $findedData];
        //     } else {
        //         $complain_info = DB::table('citizen_complains AS ctzcomp')
        //             ->select('ctzcomp.complain_status')
        //             ->where('ctzcomp.user_idno', $request['caseNumber'])
        //             ->get();
        //         $findedData = $complain_info[0];
        //         if ($findedData->complain_status == "initial") {
        //             $findedData->status =  "জেলা ম্যাজিস্ট্রেটের নিকট অপেক্ষমান";
        //             return ['success' => 'success', 'data' => $findedData];
        //         } elseif ($findedData->complain_status == "ignore") {
        //             $findedData->status = "অভিযোগটি নথিজাত করা হয়েছে   ।";
        //             return ['success' => 'success', 'data' => $findedData];
        //         } else {
        //             $findedData->status =  "অপরিবর্তিত  ";
        //             return ['success' => 'success', 'data' => $findedData];
        //         }
        //     }
        // }

        if (count($rResult)==0) {
           $is_find= DB::table('citizen_complains')->where('user_idno', $request['caseNumber'])->first();
      
           if (!empty($is_find)) {
                if ($is_find->complain_status=='initial') {
                    return response([
                        'status' => true,
                        'message' =>'জেলা ম্যাজিস্ট্রেটের নিকট অপেক্ষমান'
                    ]);
                }
                if ($is_find->complain_status=='ignore') {
                    return response([
                        'status' => true,
                        'message' =>'অভিযোগটি বাতিল করা হয়েছে'
                    ]);
                }
                
           } else {
                return response([
                    'status' => false,
                    'message' =>'মামলার তথ্য খুঁজে পাওয়া যায়নি'
                ]);
           }
        } else {
            $findedData = $rResult[0];
            if ($findedData->case_status== null) {
                if ($findedData->complain_status=='accepted' ) {
                    return response([
                        'status' => true,
                        'message' =>'ম্যাজিস্ট্রেটের নিকট অপেক্ষমান'
                    ]);
                }
                if ($findedData->complain_status=='re-send' ) {
                    return response([
                        'status' => true,
                        'message' =>'জেলা ম্যাজিস্ট্রেটের নিকট অপেক্ষমান'
                    ]);
                }
                if ($findedData->complain_status=='solved' ) {
                    return response([
                        'status' => true,
                        'message' =>'নিষ্পত্তি (মামলা হয়নি)'
                    ]);
                }
            } else {
                if ($findedData->case_status=='solved' ) {
                    return response([
                        'status' => true,
                        'message' =>'নিষ্পত্তি (মামলা হয়েছে)'
                    ]);
                }
            }
            
            
        }
        
    }

    public static function fileSave(Request $request)
    {
        $mimeTypeMap = [
            'image/jpeg' => ['type' => 'IMAGE', 'extension' => 'jpg'],
            'image/png' => ['type' => 'IMAGE', 'extension' => 'png'],
            'image/gif' => ['type' => 'IMAGE', 'extension' => 'gif'],
            'image/bmp' => ['type' => 'IMAGE', 'extension' => 'bmp'],
            'application/msword' => ['type' => 'DOCUMENT', 'extension' => 'doc'],
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => ['type' => 'DOCUMENT', 'extension' => 'docx'],
            'application/pdf' => ['type' => 'PDF', 'extension' => 'pdf'],
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => ['type' => 'EXCEL', 'extension' => 'xlsx'],
            'text/plain' => ['type' => 'TEXT', 'extension' => 'txt'],
        ];

        $entityID = $request->input('entityID');
        $appName = $request->input('appName');
        $fileCategory = $request->input('fileCategory');
        $fileCaption = $request->input('fileCaption');
        $fileUploadBy = 'General Citizen';
        $fileUploadDate = now();

        if ($request->has('fileBase64')) {
            $fileBase64 = $request->input('fileBase64');
            $fileContent = base64_decode($fileBase64);

            if ($fileContent === false) {
                throw new \Exception('Invalid base64 encoding');
            }

            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($fileContent);

            if (!isset($mimeTypeMap[$mimeType])) {
                throw new \Exception('Unsupported file type');
            }

            $fileContentType = $mimeTypeMap[$mimeType]['type'];
            $fileExtension = $mimeTypeMap[$mimeType]['extension'];
            $fileName = Str::uuid()->toString() . '.' . $fileExtension;

            $filePath = $appName . '/' . $fileCategory . '/' . $fileContentType . '/';
            $fullPath = public_path('uploads/' . $filePath);

            if (!is_dir($fullPath)) {
                if (!mkdir($fullPath, 0777, true)) {
                    throw new \Exception('Failed to create directory');
                }
            }

            DB::beginTransaction();

            try {
                $filecontent = new FileContent();
                $filecontent->EntityID = $entityID;
                $filecontent->FileType = $fileContentType;
                $filecontent->FileCategory = $fileCategory;
                $filecontent->FileName = $fileName;
                $filecontent->FileCaption = $fileCaption;
                $filecontent->FilePath = 'uploads/' . $filePath;
                $filecontent->UploadBy = $fileUploadBy;
                $filecontent->UploadDate = $fileUploadDate;

                if ($filecontent->save()) {
                    file_put_contents($fullPath . $fileName, $fileContent);
                    DB::commit();
                } else {
                    DB::rollback();
                    throw new \Exception('Failed to save file record');
                }
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        } else {
            throw new \Exception('No file data provided');
        }
    }
}
