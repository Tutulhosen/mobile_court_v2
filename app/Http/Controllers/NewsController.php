<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    //
    public function index(){
        return view('news.list');
    }

    public function createNews(){
        return view('news.createNews');
    }

    public function newsSave(Request $request){
    //    return $request->all();
        DB::beginTransaction();
        try{
            DB::table('news')->insert(
                array(
                        'title'     => $request->title, 
                        'details'   =>   $request->details,
                        'status'    => 1,
                        'attachment'=>$request->filename,
                        'date'      => $request->datenews,
                        'divid'     => null,
                        'zillaId'   => null,
                        'upazilaid' => null,
                        'created_by' => null,
                        'created_date' => date("Y-m-d H:i:s"),
                        'update_by' => null,
                        'update_date' => date("Y-m-d H:i:s"),
                        'location_str' => null,//$magistrate_info[0]["location_str"] .','.$magistrate_info[0]["location_details"];
                        'designation' => null,//$magistrate_info[0]["designation_bng"];
                )
            );

            $response = array(
                'success' => true,
                'title' => 'খবর',
                'message' => 'সফলভাবে খবর আপলোড হয়েছে'
            );
        }catch (\Exception $e) {
            DB::rollBack();
            $response = array(
                'success' =>'server',
                'title' => 'খবর',
                'message' => $e->getMessage()
            );
        }
        
        DB::commit();
       return  response()->json($response);
    }

    public function upload(Request $request){
        // return $image = $request->file('file');;
        $allowed_filetypes = array('.jpg','.gif','.bmp','.png','.pdf'); // These will be the types of file that will pass the validation.
        $max_filesize = 3145728;   //1048576 ; // currently 1MB //Maximum filesize in BYTES ( 0.5MB = 524288).
        $upload_path ='uploads/news/'; // The place the files will be uploaded to (currently a 'files' directory).
//        echo $upload_path;
        $valid_file = true;
        $message = "";
        $new_file_name = "daa";

        // $userlocation  = $this->auth->getUserLocation();
        // $divid = $userlocation["divid"];
        // $zillaId = $userlocation["zillaid"];


//        $date = date('Y-m-d-H-i-s');
        $new_file_name = "";
        $digits = 4;
        $randno =  str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);

        // $new_file_name =  "N_".$zillaId.'-'.$randno;
        $new_file_name =  "N_".'34'.'-'.$randno;

        // Check if the user has uploaded files
        if ($request->hasFile('file') == true) {
            // Print the real file names and sizes
            $image = $request->file('file');
            $fileInfo = $image->getClientOriginalName();
            $filename = pathinfo($fileInfo, PATHINFO_FILENAME);
            $extension = pathinfo($fileInfo, PATHINFO_EXTENSION);
            $file_name= $filename.'-'.time().'.'.$extension;
            $image->move(public_path('uploads/news'),$file_name);
            
            // $imageUpload = new Gallery;
            // $imageUpload->original_filename = $fileInfo;
            // $imageUpload->filename = $file_name;
            // $imageUpload->save();
            $dd=array('level' => 0, 'msg' => $new_file_name);
            return response()->json(  $dd);
        }
    }

      //https://github.com/Nimrod007/PHP_image_resize
      function smart_resize_image($file,
      $string             = null,
      $width              = 0,
      $height             = 0,
      $proportional       = false,
      $output             = 'file',
      $delete_original    = true,
      $use_linux_commands = false,
      $quality = 100
) {

if ( $height <= 0 && $width <= 0 ) return false;
        if ( $file === null && $string === null ) return false;
        # Setting defaults and meta
        $info                         = $file !== null ? getimagesize($file) : getimagesizefromstring($string);
        $image                        = '';
        $final_width                  = 0;
        $final_height                 = 0;
        list($width_old, $height_old) = $info;
        $cropHeight = $cropWidth = 0;
        # Calculating proportionality
        if ($proportional) {
        if      ($width  == 0)  $factor = $height/$height_old;
        elseif  ($height == 0)  $factor = $width/$width_old;
        else                    $factor = min( $width / $width_old, $height / $height_old );
        $final_width  = round( $width_old * $factor );
        $final_height = round( $height_old * $factor );
        }
        else {
        $final_width = ( $width <= 0 ) ? $width_old : $width;
        $final_height = ( $height <= 0 ) ? $height_old : $height;
        $widthX = $width_old / $width;
        $heightX = $height_old / $height;

        $x = min($widthX, $heightX);
        $cropWidth = ($width_old - $width * $x) / 2;
        $cropHeight = ($height_old - $height * $x) / 2;
        }
        # Loading image to memory according to type
        switch ( $info[2] ) {
        case IMAGETYPE_JPEG:  $file !== null ? $image = imagecreatefromjpeg($file) : $image = imagecreatefromstring($string);  break;
        case IMAGETYPE_GIF:   $file !== null ? $image = imagecreatefromgif($file)  : $image = imagecreatefromstring($string);  break;
        case IMAGETYPE_PNG:   $file !== null ? $image = imagecreatefrompng($file)  : $image = imagecreatefromstring($string);  break;
        default: return false;
        }


        # This is the resizing/resampling/transparency-preserving magic
        $image_resized = imagecreatetruecolor( $final_width, $final_height );
        if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
        $transparency = imagecolortransparent($image);
        $palletsize = imagecolorstotal($image);
        if ($transparency >= 0 && $transparency < $palletsize) {
        $transparent_color  = imagecolorsforindex($image, $transparency);
        $transparency       = imagecolorallocate($image_resized, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
        imagefill($image_resized, 0, 0, $transparency);
        imagecolortransparent($image_resized, $transparency);
        }
        elseif ($info[2] == IMAGETYPE_PNG) {
        imagealphablending($image_resized, false);
        $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
        imagefill($image_resized, 0, 0, $color);
        imagesavealpha($image_resized, true);
        }
        }
        imagecopyresampled($image_resized, $image, 0, 0, $cropWidth, $cropHeight, $final_width, $final_height, $width_old - 2 * $cropWidth, $height_old - 2 * $cropHeight);


        # Taking care of original, if needed
        if ( $delete_original ) {
        if ( $use_linux_commands ) exec('rm '.$file);
        else @unlink($file);
        }
        # Preparing a method of providing result
        switch ( strtolower($output) ) {
        case 'browser':
        $mime = image_type_to_mime_type($info[2]);
        header("Content-type: $mime");
        $output = NULL;
        break;
        case 'file':
        $output = $file;
        break;
        case 'return':
        return $image_resized;
        break;
        default:
        break;
        }

        # Writing image according to type to the output destination and image quality
        switch ( $info[2] ) {
        case IMAGETYPE_GIF:   imagegif($image_resized, $output);    break;
        case IMAGETYPE_JPEG:  imagejpeg($image_resized, $output, $quality);   break;
        case IMAGETYPE_PNG:
        $quality = 9 - (int)((0.9*$quality)/10.0);
        imagepng($image_resized, $output, $quality);
        break;
        default: return false;
        }
        return true;
    }
}
