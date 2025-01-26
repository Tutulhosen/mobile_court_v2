@extends('layout.app')
@yield('style')

@section('content')
<style>
    .form-group .control-label{
        background-color: #4c81db;
        width: 100%;
        padding-left: 5px;
    }

    .control-label {
     color: #fff !important;
    display: block;
    padding-left: 5px;
    font-weight: normal;
    }
div#previews .file-row:nth-child(odd) {
    background: #f9f9f9;
}
div#previews .file-row {
    display: table-row;
}
#previews .file-row.dz-success .cancel {
 display: none;
}
div#previews {
    display: table;
}

.table {
    width: 100%;
    max-width: 100%;
    margin-bottom: 20px;
}
div#previews .file-row:nth-child(odd) {
    background: #f9f9f9;
}

div#previews .file-row {
    display: table-row;
}
div#previews .file-row > div {
    display: table-cell;
    vertical-align: top;
    border-top: 1px solid #ddd;
    padding: 8px;
}
.error {
    background-color: white;
    color: red;
}

#previews .file-row.dz-success .progress {
    opacity: 0;
    transition: opacity 0.3s linear;
}
#previews .file-row.dz-success .delete {
    display: block;
}


span.btn.btn-primary.btn-small.icon-only.start {
    display: none;
}
button.btn.btn-warning.btn-small.icon-only.cancel {
    display: none;
}
</style>
<form   id="mynewsForm" name="mynewsForm"  >
<div class="container">
    <div class="row">
     <div class="col-lg-12">
         
        <div class="card card-custom">
            <div class="card-header smx">
                <h2 class="card-title ">খবর </h2>
            </div>
            <div class="card-body p-15 cpv">

        
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label class="control-label">শিরোনাম <span class="text-danger">*</span></label>
                    
                            <input type="text" name="title" id="title" class="input form-control">
                        </div> 
                        <!-- form-group -->
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label class="control-label">ঘটনার তারিখ <span class="text-danger">*</span></label>

                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                <input type="text" class="form-control common_datepicker" placeholder="yyyy/mm/dd" id="datenews" name="datenews"/>
                            </div>
                            <!-- input-group -->
                        </div>
                        <!-- form-group -->
                    </div>
                    <!-- col-sm-6 -->
                </div>
                <!-- row -->

                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">বিস্তারিত</label>
                        
                            <textarea name="details" id='details' cols="50" rows="4" class="input
                            form-control"></textarea>
                        </div>
                        <!-- form-group -->
                    </div>
                    <!-- col-sm-6 -->
                </div>


                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">ছবি/ স্ক্যানকৃত ফাইল সংযুক্ত করুন (যদি থাকে) (সর্বোচ্চ ফাইল সাইজ 1MB)</label>
                            <!-- The Bootstrap style with 3 buttons -->
                            <!-- HTML heavily inspired by http://blueimp.github.io/jQuery-File-Upload/ -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <span> সংযুক্ত ফাইলের নামে একাধিক ডট (.) ব্যবহার করবেন না । উদাহরণঃ  abc.something.jpg(ভুল) । abcsomething.jpg/png/gif(সঠিক)</span>
                                </div>
                            </div>
                            <div id="actions" class="row">
                                <div class="col-xs-12 col-sm-8">
                                    <!-- The fileinput-button span is used to style the file input field as button -->
                                    <span class="btn btn-success btn-small fileinput-button icon-only" >
                                        +
                                    </span>
                                    <!-- {#<span class="btn btn-small btn-primary icon-only start">#}
                                    {#<i class="glyphicon glyphicon-upload"></i>#}
                                    {#</span>#} -->
                                    <!-- <span class="btn btn-small btn-warning icon-only cancel">
                                        <i class="glyphicon glyphicon-ban-circle"></i>
                                        0
                                    </span> -->
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <!-- The global file processing state -->
                                    <span class="fileupload-process">
                                        <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                            <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                                        </div>
                                    </span>
                                </div>
                            </div>
                            <div class="table table-striped files" id="previews">
                                <div id="templatenews" class="file-row">
                                    <!-- This is used as the file preview template -->
                                    <div>
                                        <span class="preview"><img data-dz-thumbnail/></span>
                                    </div>
                                    <div>
                                        <p class="name" data-dz-name></p>
                                        <strong class="error text-danger" data-dz-errormessage></strong>
                                    </div>
                                    <div>
                                        <p class="size" data-dz-size></p>
                                        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                            <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="btn btn-primary btn-small icon-only start">
                                            <i class="glyphicon glyphicon-upload"></i>
                                        </span>
                                        <button data-dz-remove class="btn btn-warning btn-small icon-only cancel">
                                            <i class="glyphicon glyphicon-ban-circle"></i>
                                        </button>
                                        <button data-dz-remove class="btn btn-danger btn-small icon-only delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- form-group -->
                    </div>
                    <!-- col-sm-6 -->
                </div>

                <!-- row -->
            </div>

            <input type="hidden" name="filename" id="filename">
            <input type="hidden" name="id" id="id">
            <!-- panel-body -->
            <div class="panel-footer">
            <!-- "&larr; প্রথম পাতা" -->
                <div class="pull-right float-right">
                    <button class="btn btn-primary" type="submit"  >
                        <i class="glyphicon glyphicon-ok"></i> সংরক্ষণ</button>
                </div>
            </div>
            <!-- panel-footer -->
        </div><!-- panel -->
     
     </div>

    </div>
</div>
</form>
<div id="successnews" class="modal fade" style="display: none; ">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <a class="close" data-dismiss="modal">×</a>

                <h1>ধন্যবাদ</h1>
            </div>
            <div class="modal-body">
                <h1>
                    সফলভাবে সংরক্ষণ করা হয়েছে ।
                </h1>

            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-mideum" data-dismiss="modal">সমাপ্ত</a>
            </div>
        </div>
    </div>
</div>

<div id="errornews" class="modal fade" style="display: none; ">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <a class="close" data-dismiss="modal">×</a>

                <h3>ধন্যবাদ</h3>
            </div>
            <div class="modal-body">
                <h3 style="color: red;">
                </h3>
                <br/>

                <h3 style="color: green;">
                    পূনরায় চেষ্টা করুন ।
                </h3>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-mideum" data-dismiss="modal">সমাপ্ত</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<link href="{{asset('assets/js/dropzone/dropzone.css')}}" rel="stylesheet" type="text/css" />

<script src="{{ asset('assets/js/dropzone/dropzone.min.js') }}"></script>
<!-- <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script> -->
<script>
//  $(document).ready(function () {
//         $('.ckeditor').ckeditor();
//     });
    // common datepicker =============== start
           $('.common_datepicker').datepicker({
                format: "yyyy/mm/dd",
                todayHighlight: true,
                mindate: new Date(),
                orientation: "bottom left"
            });
            // common datepicker =============== end
 
    // $('#mynewsForm').on('submit',function(){

 
    //     var formObj = $(this);
    //     var formURL = "{{ url('/news/newsSave') }}/";
    //     var formData = new FormData(this);
    //     $.ajax({
    //         url: formURL,
    //         type: 'POST',
    //         data: formData,
    //         dataType: 'json',
    //         mimeType: "multipart/form-data",
    //         contentType: false,
    //         cache: false,
    //         processData: false,
    //         success: function(json) {
    //             // console.log(json);
    //             // return false;
    //             alert("Updated Successfully");
    //         }
    //     });
    // });
    $('#mynewsForm').submit(function(event) {
    event.preventDefault();
        var formObj = $(this);
        var formURL = "{{ url('news/newSave') }}/";
        var title=$('#title').val();
        var datenews=$('#datenews').val();
        var details=$('#details').val();
        var filename=$('#filename').val();
        // var formData = new FormData(this);
        var fd = new FormData();

        
        if(title ==''){
            toastr.error("খবরের শিরোনাম লিখুন ।" ,"খবর");
            return false;
        }
        if(datenews ==''){
            toastr.error("খবরের তারিখ নির্বাচন ।" ,"খবর");
            return false;
        }
        if(details ==''){
            toastr.error("খবরের বিস্তারিত  লিখুন ।" ,"খবর");
            return false;
        }
        fd.append("title", title);
        fd.append("datenews", datenews);
        fd.append("details", details);
        fd.append("filename", filename);
        $.ajax({
            url: formURL,
            type: 'POST',
            data:fd,
            enctype: "multipart/form-data",
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {

                // console.log(response);
                // return false;
                // var response = $.parseJSON(res);
                if (response.success == true) {
                    toastr.success(response.message, response.title);
                     window.location.reload();
                } else if (response.success == 'server') {
                    toastr.warning(response.message, response.title);
                } else {
                    toastr.error(response.message, response.title);
                }
               
    
          
            },
            error: function(error) {
                // Handle the error
            }
        });
    });
</script>

<script>
    // Get the news HTML and remove it from the doumenthe template HTML and remove it from the doument
    var previewNode = document.querySelector("#templatenews");
    previewNode.id = "";
    var previewTemplate = previewNode.parentNode.innerHTML;
    previewNode.parentNode.removeChild(previewNode);
     var uploadurl="{{ url('/news/upload') }}/";
    var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
        url: uploadurl, // Set the url
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        thumbnailWidth: 80,
        maxFilesize: 3,
        acceptedFiles: "image/*,application/pdf,.doc,.docx,.pdf",
        thumbnailHeight: 80,
        parallelUploads: 20,
        previewTemplate: previewTemplate,
        autoQueue: false, // Make sure the files aren't queued until manually added
        previewsContainer: "#previews", // Define the container to display the previews
        clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
    });

    myDropzone.on("addedfile", function (file) {
        // Hookup the start button
        file.previewElement.querySelector(".start").onclick = function () {
            myDropzone.enqueueFile(file);
        };
        file.previewElement.querySelector(".cancel").onclick = function () {
            myDropzone.removeFile(file);
        };
        file.previewElement.querySelector(".delete").onclick = function () {
            myDropzone.removeFile(file);
        };
    });

    myDropzone.on("error", function (file) {
        // Hookup the start button
        file.previewElement.querySelector(".start").style.display = "none";
    });

    //    myDropzone.on("success", function (file) {
    //        // Hookup the start button
    //    });
    // Hookup the start button
    //                alert(responseText.msg);
    myDropzone.on("success", function (file, responseText) {
        // Hookup the start button
              
        var totalcriminal = "#filename";
       
        $(totalcriminal).val(responseText.msg);
   
    });

    // Update the total progress bar
    myDropzone.on("totaluploadprogress", function (progress) {
        document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
        if (progress == 100) {
            document.querySelector("#total-progress").style.opacity = "0";
        }
    });

    myDropzone.on("sending", function (file) {
        // Show the total progress bar when upload starts
        document.querySelector("#total-progress").style.opacity = "1";
        // And disable the start button
        file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
    });

    // Hide the total progress bar when nothing's uploading anymore
    myDropzone.on("queuecomplete", function (progress) {
        document.querySelector("#total-progress").style.opacity = "0";
    });

    // Setup the buttons for all transfers
    // The "add files" button doesn't need to be setup because the config
    // `clickable` has already been specified.
    document.querySelector("#actions .start").onclick = function () {
        myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
    };

    document.querySelector("#actions .cancel").onclick = function () {
        myDropzone.removeAllFiles(true);
    };
</script>
@endsection