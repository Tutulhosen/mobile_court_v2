<?php

namespace App\Repositories;

use App\Models\FileContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FileRepository
{
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
        $fileUploadBy = auth()->user()->email;
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

    // public static function fileSaveForWeb(Request $request)
    // {
    //     dd($request->all());
    //     $mimeTypeMap = [
    //         'image/jpeg' => ['type' => 'IMAGE', 'extension' => 'jpg'],
    //         'image/png' => ['type' => 'IMAGE', 'extension' => 'png'],
    //         'image/gif' => ['type' => 'IMAGE', 'extension' => 'gif'],
    //         'image/bmp' => ['type' => 'IMAGE', 'extension' => 'bmp'],
    //         'application/msword' => ['type' => 'DOCUMENT', 'extension' => 'doc'],
    //         'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => ['type' => 'DOCUMENT', 'extension' => 'docx'],
    //         'application/pdf' => ['type' => 'PDF', 'extension' => 'pdf'],
    //         'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => ['type' => 'EXCEL', 'extension' => 'xlsx'],
    //         'text/plain' => ['type' => 'TEXT', 'extension' => 'txt'],
    //     ];

    //     $entityID = $request->input('entityID');
    //     $appName = $request->input('appName');
    //     $fileCategory = $request->input('fileCategory');
    //     $fileCaption = $request->input('fileCaption');
    //     $fileUploadBy = auth()->user()->email;
    //     $fileUploadDate = now();
    //     $fileCaption = $request->hasFile('someName');
    //     // dd($fileCaption);
    //     if ($request->hasFile('someName')) {
    //     // dd($request->all());
    //         DB::beginTransaction();
    //         try {

    //             $file = $request->file('someName');

    //         // dd($file);
    //             if ($file) {
    //                 $mimeType = $file->getMimeType();
    //                 if (!isset($mimeTypeMap[$mimeType])) {
    //                     throw new \Exception('Unsupported file type');
    //                 }

    //                 $fileContentType = $mimeTypeMap[$mimeType]['type'];
    //                 $fileExtension = $mimeTypeMap[$mimeType]['extension'];

    //                 $fileName = Str::uuid()->toString() . '.' . $fileExtension;

    //                 $filePath = $appName . '/' . $fileCategory . '/' . $fileContentType . '/';
    //                 $fullPath = public_path('uploads/' . $filePath);

    //                 $file->move($fullPath, $fileName);

    //                 // Save file details to the database
    //                 $filecontent = new FileContent();
    //                 $filecontent->EntityID = $entityID;
    //                 $filecontent->FileType = $fileContentType;
    //                 $filecontent->FileCategory = $fileCategory;
    //                 $filecontent->FileName = $fileName;
    //                 $filecontent->FileCaption = $fileCaption;
    //                 $filecontent->FilePath = 'uploads/' . $filePath;
    //                 $filecontent->UploadBy = $fileUploadBy;
    //                 $filecontent->UploadDate = $fileUploadDate;

    //                 DB::commit();
    //             } else {
    //                 throw new \Exception('File is null');
    //             }
    //         } catch (\Exception $e) {
    //             DB::rollback();
    //             throw $e;
    //         }
    //     } else {
    //         throw new \Exception('No files provided');
    //     }

    // }

    public static function fileSaveForWeb(Request $request)
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
        $fileUploadBy = auth()->user()->email;
        $fileUploadDate = now();
        // dd($request->file('someName'));
        if ($request->hasFile('someName')) {

            DB::beginTransaction();
            try {
                $file = $request->file('someName');

                if ($file) {
                    $mimeType = $file->getMimeType();
                    if (!isset($mimeTypeMap[$mimeType])) {
                        throw new \Exception('Unsupported file type');
                    }

                    $fileContentType = $mimeTypeMap[$mimeType]['type'];
                    $fileExtension = $mimeTypeMap[$mimeType]['extension'];
                    $fileName = Str::uuid()->toString() . '.' . $fileExtension;

                    $filePath = $appName . '/' . $fileCategory . '/' . $fileContentType . '/';
                    $fullPath = public_path('uploads/' . $filePath);
                    $file->move($fullPath, $fileName);

                    $filecontent = new FileContent();
                    $filecontent->EntityID = $entityID;
                    $filecontent->FileType = $fileContentType;
                    $filecontent->FileCategory = $fileCategory;
                    $filecontent->FileName = $fileName;
                    $filecontent->FileCaption = $fileCaption;
                    $filecontent->FilePath = 'uploads/' . $filePath;
                    $filecontent->UploadBy = $fileUploadBy;
                    $filecontent->UploadDate = $fileUploadDate;
                    $filecontent->save();

                    DB::commit();
                } else {
                    throw new \Exception('File is null');
                }
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        } else {
            throw new \Exception('No files provided');
        }
    }

    public static function fileFindByEntityID($entityID, $fileCategory)
    {

        $filecontent = FileContent::where('EntityID', $entityID)
            ->select('FileID', 'EntityID', 'FileType', 'FileCategory', 'FileName', 'FilePath', 'UploadBy')
            ->where('FileCategory', $fileCategory)
            ->get();

        return $filecontent;
    }

    public static function fileAllFindByEntityID($entityID)
    {
        $filecontent = DB::table('file_content')
            ->where('EntityID', $entityID)
            ->whereIn('FileCategory', ['ChargeFame', 'CriminalConfession', 'Ordersheet', 'ExtendedOrder'])
            ->get();
        
        return $filecontent;
    }

    public static function deleteExistingFiles($entityID, $fileCategory)
    {
        $existingFiles = FileContent::where('EntityID', $entityID)
            ->where('FileCategory', $fileCategory)
            ->get();

        foreach ($existingFiles as $existingFile) {
            $filePath = public_path($existingFile->FilePath . $existingFile->FileName);

            // Check if the file exists before deleting
            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            FileContent::where('FileID', $existingFile->FileID)->delete();
        }
    }

}