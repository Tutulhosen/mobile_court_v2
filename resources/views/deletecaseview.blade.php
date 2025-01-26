@extends('layout.app')
@section('content')
{{-- JavaScript Includes --}}


{{-- Stylesheets --}}
<link href="{{ asset('mobile_court/cssmc/jquery-ui-1.11.0.min.css') }}" rel="stylesheet">
<link href="{{ asset('mobile_court/cssmc/jquery-ui-1.10.3.css') }}" rel="stylesheet">
<link href="{{ asset('mobile_court/cssmc/jquery.alerts.css') }}" rel="stylesheet">

<div class="card ">
    <div class="card-header smx">
        <h2 class="card-title"> মামলা বাতিল</h2>
    </div>
    <div class="card-body  cpv">
        {{-- Start the Form --}}
        <form method="post" name="casedeleteform" id="casedeleteform" action="#">
            @csrf {{-- CSRF Token --}}
            <div class="form-group">
                <label class="control-label">মামলা নম্বর</label>
                <input type="text" name="case_no" class="input form-control" value="{{ old('case_no') }}">
            </div>
            <div class="text-right">
                <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> মামলা বাতিল </button>
            </div>
        </form>
    </div>
</div>

{{-- Confirmation Dialog --}}
<div id="dialog-confirm" style="display: none">
    <p><span class="ui-icon ui-icon-alert"></span> মামলাটি বাতিল করতে চান ? </p>
</div>
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="{{ asset('mobile_court/js/jquery-ui-1.11.0.min.js') }}"></script>
<script src="{{ asset('mobile_court/js/jquery-ui-1.10.3.min.js') }}"></script>
<script src="{{ asset('mobile_court/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('mobile_court/js/bootstrap-wizard.min.js') }}"></script>
<script src="{{ asset('mobile_court/js/jquery.alerts.js') }}"></script>
<script>
     $("#casedeleteform").submit(function (e) {
        e.preventDefault(); // Prevent the default form submission
 
        var formObj = $(this);
        var formURL =  "/deletecase";
        var formData = new FormData(this);
        Swal.fire({
            title: "মামলা",
            text: "মামলা বাতিল  করতে চান ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "না",
            confirmButtonText: "হ্যাঁ"
          }).then((result) => {
            if (result.isConfirmed) {
                    $.ajax({
                        url: formURL,
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {

                            Swal.fire({
                                title: "অবহতিকরণ বার্তা!",
                                text: response.message,
                                icon: "success"
                            });
                            // Handle the response from the server
                            console.log(response);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            // Handle errors
                            console.error('Error:', textStatus, errorThrown);
                        }
                    });
               
            }
          });   
    });
    // $(document).ready(function() {
    //     //Callback handler for form submit event
    //     $("#casedeleteform").submit(function (e) {
    //         var formObj = $(this);
    //         var formURL = "{{ url('/deletecase') }}"; // Update URL as per your route
    //         var formData = new FormData(this);
    //         console.log(formData);
    //        return false;
    //         $.confirm({
    //             resizable: false,
    //             height: 250,
    //             width: 400,
    //             modal: true,
    //             title: "মামলা বাতিল ",
    //             buttons: {
    //                 "না": function () {},
    //                 "হ্যাঁ": function () {
    //                     $.ajax({
    //                         url: formURL,
    //                         type: 'POST',
    //                         data: formData,
    //                         dataType: 'json',
    //                         mimeType: "multipart/form-data",
    //                         contentType: false,
    //                         cache: false,
    //                         processData: false,
    //                         success: function (response) {
    //                             if (response.flag == 'true') {
    //                                 $.alert(response.message, "অবহিতকরণ বার্তা");
    //                                 $("#case_no").val("");
    //                             } else {
    //                                 $('#error').modal('show');
    //                             }
    //                         },
    //                         error: function (jqXHR, textStatus, errorThrown) {
    //                             console.error(errorThrown);
    //                         }
    //                     });
    //                 }
    //             }
    //         });
    //         e.preventDefault(); // Prevent Default action.
    //     });
    // });
</script>
@endsection
