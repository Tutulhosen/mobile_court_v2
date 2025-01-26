@extends('mobile_court.layout.app')
@section('content')
@push('head')
    <link href="{{ asset('assets/css/pages/wizard/wizard-1.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/pages/tachyons.min.css') }}" rel="stylesheet" type="text/css" />
@endpush
<style>

body {
  padding : 10px ;
  
}

#exTab1 .tab-content {
  color : white;
  background-color: #428bca;
  padding : 5px 15px;
}

#exTab2 h3 {
  color : white;
  background-color: #428bca;
  padding : 5px 15px;
}

/* remove border radius for the tab */

#exTab1 .nav-pills > li > a {
  border-radius: 0;
}

/* change border radius for the tab , apply corners on top*/

#exTab3 .nav-pills > li > a {
  border-radius: 4px 4px 0 0 ;
}

#exTab3 .tab-content {
  color : white;
  background-color: #428bca;
  padding : 5px 15px;
}

.nav-item a.nav-link.active {
    background-color: #428bca;
    color:#fff;
}

.row img {
    border: 1px solid #336699;
    border-radius: 15px;
    box-shadow: none;
    width: 80%;
}

img:hover {
    box-shadow: 8px 8px 5px #888888;
    border: 3px solid #336699;
    background: #FFD700;
}
.input-error {
    border-color: red !important;
}   
</style>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
           <div class="card card-custom">
                <div class="card-header">
                    <div class="card-title ">
                        <h3 class="card-label font-weight-bolder text-dark h3">স্বপ্রণোদিত কোর্ট (নতুন)
                        </h3>
                        <input type="hidden" id="txtProsecutionID" value="" />
                    </div>
                    <div class="card-toolbar">
                        
                    </div>
                </div>
                <div class="card-body">
                  
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" id="tab-1">
                        <a class="nav-link active" data-toggle="tab" href="#tab1-2" role="tab">অভিযুক্ত ব্যক্তির তথ্য</a>
                    </li>
                    <li class="nav-item" id="tab-2">
                        <a class="nav-link" data-toggle="tab"  href="#tab2-2" role="tab"> সাক্ষীর তথ্য </a>
                    </li>
                    <li class="nav-item" id="tab-3">
                        <a class="nav-link" data-toggle="tab" href="#tab3-2" role="tab"> অভিযোগ গঠন</a>
                    </li>
                    <li class="nav-item" id="tab-4">
                        <a class="nav-link" data-toggle="tab" href="#tab4-2" role="tab">  জব্দতালিকা </a>
                    </li>
                    <li class="nav-item" id="tab-5">
                        <a class="nav-link" data-toggle="tab" href="#tab5-2" role="tab">  আসামির জবানবন্দি</a>
                    </li>
                    <li class="nav-item" id="tab-6">
                        <a class="nav-link" data-toggle="tab" href="#tab6-2" role="tab"> আদেশ প্রদান</a>
                    </li>
                </ul><!-- Tab panes -->
                <div class="tab-content">
                    
                    <div class="tab-pane active" id="tab1-2" role="tabpanel">

                       @include('mobile_court.appeals.partials.tab1-2')
                       
                    </div>
                    <div class="tab-pane" id="tab2-2" role="tabpanel">
                       @include('mobile_court.appeals.partials.tab2-2')
                    </div>
                    <div class="tab-pane" id="tab3-2" role="tabpanel">
                        @include('mobile_court.appeals.partials.tab3-2')
                    </div>

                    <div class="tab-pane" id="tab4-2" role="tabpanel">
                        @include('mobile_court.appeals.partials.tab4-2')
                    </div>
                     
                    <div class="tab-pane" id="tab5-2" role="tabpanel">
                        @include('mobile_court.appeals.partials.tab5-2')
                    </div>
                    <div class="tab-pane" id="tab6-2" role="tabpanel">
                        @include('mobile_court.appeals.partials.tab6-2')
                    </div>
                </div>
                      

                </div>
           </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<!-- <script src="https://unpkg.com/validator@latest/validator.min.js"></script> -->
<!-- <script src="{{ asset('js/validation/input-validator.js') }}"></script> -->



<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<script  src="{{ asset('mobile_court/javascripts/lib/validation/input-validator.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/content/multiFileUpload.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/prosecution/prosecutionInitiate.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/prosecution/magistrateSelectionForm.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/prosecution/criminalInfoForm.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/prosecution/witnessInfoForm.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/prosecution/complaintInfoForm.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/prosecution/seizureListInfoForm.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/prosecution/confessionForm.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/prosecution/ordersheetInfoForm.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/law/law.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/utils/ui-utils.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/location/location.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/fingerprint/allFingerPrint.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/utils/convertEngToBangla.js') }}" ></script>
<script>



// var criminal = {

// regex: /^(.+?)(\d+)$/i,
// cloneIndex: $(".criminalInfo").length,
// cloneCriminalForm: $('#addCriminalBlock .criminalInfo:first').clone(),

// init: function () {
//     $("#withCriminal").css("display","block");
//     $(document).on('click', $("#do_address_0"), function () {
//         // doParmanentAddress(this.value,0);
//     });
//     $('#iLEFT_THUMB_0').click(function () {
//         fingurePrint.captureFP_criminal(1,0);
//     });
//     $('#iRIGHT_THUMB_0').click(function () {
//         fingurePrint.captureFP_criminal(3,0);
//     });
//     $('#checkfingureprint_0').click(function () {
//         fingurePrint.signaturetest(0);
//     });
// },

// populateModel: function () {

//     var model = {};
//     model.prosecutionID = $('#txtProsecutionID').val();
//     model.selectMagistrateId=$('#selectMagistrateId').val();
//     model.selectMagistrateCourtId=$('.selectMagistrateCourtId').val();
//     model.criminals = [];

//     $('.criminalInfo').each(function (index, criminalContainer) {
//         model.criminals.push(criminal.getCriminalInfo(index, criminalContainer));
//     });

//     return model;
// },

// getCriminalInfo: function (index, criminalContainer) {
//     var criminal = {};
//     criminal.criminalID = $('#criminal_id_' + index).val();
//     // criminal.fingerprintID = $('#fingerprint_id_' + index).val();
//     //fingure print section
//     criminal.LEFT_THUMB_BMP=$('#criminal_LEFT_THUMB_BMP_' + index).val();
//     criminal.RIGHT_THUMB_BMP=$('#criminal_RIGHT_THUMB_BMP_' + index).val();
//     criminal.LEFT_THUMB=$('#criminal_LEFT_THUMB_' + index).val();
//     criminal.RIGHT_THUMB=$('#criminal_RIGHT_THUMB_' + index).val();
//     criminal.m_user_type=$('#criminal_m_user_type_' + index).val();
//     criminal.m_user_id=$('#criminal_m_user_id_' + index).val();
//     criminal.repeat_crime=$('#criminal_repeat_crime_' + index).val();

//     criminal.name = $('#criminal_name_' + index).val();
//     criminal.custodian_name = $('#criminal_custodian_name_' + index).val();
//     criminal.custodian_type = $('#custodian_type_' + index).val();
//     criminal.mother_name = $('#mother_name_' + index).val();
//     criminal.national_id = $('#national_id_' + index).val();
//     criminal.email = $('#email_' + index).val();
//     criminal.age = $('#age_' + index).val();
//     criminal.gender = $('#gender_' + index).val();
//     criminal.mobile_no = $('#mobile_no_' + index).val();
//     criminal.occupation = $('#occupation_' + index).val();
//     criminal.organization_name = $('#organization_name_' + index).val();
//     criminal.trade_no = $('#trade_no_' + index).val();
//     // criminal.divid = $('#' + eMobileLocation.ddlDivision + index).val();
//     // criminal.zillaId = $('#' + eMobileLocation.ddlZilla + index).val();

//     // var type = eMobileLocation.getLocationType(index);
    
//     // criminal.locationtype = type;
//     // if (type === eMobileLocation.locationType.Upazilla) {
//     //     criminal.upazilaid = $('#' + eMobileLocation.ddlUpazilla + index).val();
//     // }
//     // if (type === eMobileLocation.locationType.CityCorporation) {
//     //     criminal.geo_citycorporation_id = $('#' + eMobileLocation.ddlCityCorporation + index).val();
//     // }
//     // if (type === eMobileLocation.locationType.Metropolitan) {
//     //     criminal.geo_metropolitan_id = $('#' + eMobileLocation.ddlMetropolitan + index).val();
//     //     criminal.geo_thana_id = $('#' + eMobileLocation.ddlThana + index).val();
//     // }

//     criminal.permanent_address = $('#permanent_address_' + index).val();
//     criminal.do_address = $('#do_address_' + index).val();
//     criminal.present_address = $('#present_address_' + index).val();
//     return criminal;
// },

// save: function () {
//     if(!validator.validateFields("#suomotcourtcriminalform")){
//       alert("সকল তথ্য সঠিক ভাবে দেওয়া হয়নি। ","অবহতিকরণ বার্তা");
//         return false;
//     }
//     var model = criminal.populateModel();
 
    
//         return false;
//     // $.confirm({
//     //     resizable: false,
//     //     height: 250,
//     //     width: 400,
//     //     modal: true,
//     //     title: "আসামির তথ্য",
//     //     titleClass: "modal-header",
//     //     content: "ফরমটি সংরক্ষণ করতে চান ?",
//     //     buttons: {
//     //         "না": function () {
//     //             // $(this).dialog("close");
//     //         },
//     //         "হ্যাঁ": function () {
//     //             $.ajax({
//     //                 url: url,
//     //                 type: 'POST',
//     //                 dataType: 'json',
//     //                 data: {'data': model},
//     //                 mimeType: "multipart/form-data",
//     //                 success: function (response, textStatus, jqXHR) {

//     //                     console.log(response);
//     //                     // if (response.flag == 'true') {

//     //                     //     $.alert("অভিযুক্তের তথ্য সংরক্ষণ করা হয়েছে। ", "অবহতিকরণ বার্তা");

//     //                     //     // Disable Magistrate select Section for (With Prosecutor)
//     //                     //     if(response.caseInfo.prosecution.is_suomotu == 0 ){
//     //                     //         // Disable Magistrate select Section for (With Prosecutor)
//     //                     //         magistrateForm.setMagistrateInfo(response.caseInfo.magistrateInfo);
//     //                     //         //set magistrate divid and zillaid
//     //                     //         eMobileLocation.populateLocation(999, response.caseInfo.prosecution.location_type, response.caseInfo.prosecution.divid, response.caseInfo.prosecution.zillaId, response.caseInfo.prosecution.upazilaId,response.caseInfo.prosecution.geo_citycorporation_id,response.caseInfo.prosecution.geo_metropolitan_id,response.caseInfo.prosecution.geo_thana_id);
//     //                     //         //disable divid and zillaid
//     //                     //         complaintForm.disableDivisionZillaWithProsecutor();
//     //                     //         $("#withOutProsecutor").html('');
//     //                     //     }else {
//     //                     //         $("#withProsecutor").html('');
//     //                     //         $("#withCriminal").removeClass("hidden");
//     //                     //     }

//     //                     //     // Set prosecution Id
//     //                     //     $('#txtProsecutionID').val(response.caseInfo.prosecution.id);

//     //                     //     // Generate Case Number list
//     //                     //     $('.case_no').html(response.case_no);
//     //                     //     $('#case_no').val(response.case_no);
//     //                     //     // Set criminal confession from with info
//     //                     //     // criminal.setConfessionDiv(response.caseInfo.criminalDetails);

//     //                     //     // Set criminal form info in Tab-6
//     //                     //     complaintForm.setCriminalDiv(response.caseInfo.criminalDetails);

//     //                     //     // Set Criminal Id
//     //                     //     criminal.setCriminalId(response.caseInfo.criminalDetails);

//     //                     //     // Set tab index
//     //                     //     prosecutionInit.setTabIndex(response.step);

//     //                     // } else {
//     //                     //     $.alert("তথ্য অসম্পূর্ন থাকায় আবেদনটি গ্রহণ করা হয়নি ।  পূনরায় চেষ্টা করুন ।  ", "ধন্যবাদ");
//     //                     // }
//     //                 },
//     //                 error: function (jqXHR, textStatus, errorThrown) {
//     //                     $.alert("অভিযুক্তের তথ্য সংরক্ষণ হয়নি। ", "অবহতিকরণ বার্তা");
//     //                 }
//     //             });
//     //         }
//     //     }
//     // });
//     // save
// },

// setCriminalId:function (criminalDetails) {
//     var myStringArray = criminalDetails;
//     var arrayLength = myStringArray.length;
//     for (var i = 0; i < arrayLength; i++) {
//         $("#criminal_id_" + i).val(myStringArray[i].id);
//     }

// },

// toggleDiv: function (e) {

//     e.next('.panel-body').toggle();
//     if (e.children().find('i').hasClass('fa-minus')) {
//         e.children().find('i').removeClass('fa-minus').addClass('fa-plus');
//     }
//     else {
//         e.find('i').removeClass('fa-plus').addClass('fa-minus');
//     }
// },

// addMoreCriminalInfo: function (initiateFalg) {

//     var cloneCriminal = criminal.cloneCriminalForm.clone(true);
//     var cloneIndex = criminal.cloneIndex;
//     var regex = criminal.regex;
//     var section = cloneCriminal.find('input:text,input:checkbox, input:radio,input:hidden, select, table,div,textarea,a,img,button')
//         .each(function () {
//             var id = this.id || "";
//             var name = this.name || "";
//             var classval = this.className || "";
//             // Set New Name value
//             var newName = name.replace(/criminal[^]\d+]/, 'criminal[' + [cloneIndex] + ']');
//             this.name = newName;
//             //Set Location Class Value
//             var newclass = classval.replace(/optLocationType0/, 'optLocationType' + [cloneIndex]);
//             this.className = newclass;
//             // Set new Id
//             var match = id.match(regex) || [];
//             if (match.length == 3) {
//                 this.id = match[1] + (cloneIndex);
//             }
//         })
//         .end()
//         .appendTo('#addCriminalBlock');

//     // edit section Checking
//     if(initiateFalg){
//         eMobileLocation.init(cloneIndex);
//     }

//     $('.numOfcriminal:last').html(1 + cloneIndex);
//     $('.numOfcFinger:last').html(1 + cloneIndex);
//     $('.criminal_info_block:last').attr('id', 'criminalNo' + 1 + cloneIndex);
//     $('#do_address_' + (cloneIndex)).click(function () {
//         doParmanentAddress(this.value, (cloneIndex));
//     });
//     $('#iLEFT_THUMB_' + (cloneIndex)).click(function () {
//         fingurePrint.captureFP_criminal(1,cloneIndex);
//     });
//     $('#iRIGHT_THUMB_' + (cloneIndex)).click(function () {
//         fingurePrint.captureFP_criminal(3,cloneIndex);
//     });
//     $('#checkfingureprint_' + (cloneIndex)).click(function () {
//         fingurePrint.signaturetest(cloneIndex);
//     });
//     criminal.cloneIndex++;
//     return false;
// },

// setCriminalForm: function (criminaldetails) {

//     var myStringArray = criminaldetails;
//     var arrayLength = myStringArray.length;
//     for (var i = 0; i < arrayLength; i++) {
//         var j = i;
//         $("#criminal_id_" + j).val(myStringArray[i].id);
//         $("#criminal_name_" + j).val(myStringArray[i].name_bng);
//         $("#criminal_custodian_name_" + j).val(myStringArray[i].custodian_name);
//         $("#occupation_" + j).val(myStringArray[i].occupation);
//         $("#present_address_" + j).val(myStringArray[i].present_address);
//         $("#permanent_address_" + j).val(myStringArray[i].permanent_address);
//         $("#locationtype" + j).val(myStringArray[i].location_type);
//         $("#mobile_no_" + j).val(myStringArray[i].mobile_no);
//         $("#age_" + j).val(myStringArray[i].age);
//         $("#email_" + j).val(myStringArray[i].email);
//         $("#national_id_" + j).val(myStringArray[i].national_id);
//         $("#mother_name_" + j).val(myStringArray[i].mother_name);
//         $("#criminal_repeat_crime_" + j).val(1);
//         // $("#criminal_m_user_id_" + j).val(reply.data[0]["UserId"]);
//         // $("#criminal_m_user_type_" + j).val(reply.data[0]["UserType"]);
//         if(myStringArray[i].imprint1 ){
//             document.getElementById("iLEFT_THUMB_" + j).src = "data:image/bmp;base64,"+myStringArray[i].imprint1;
//         }
//         if(myStringArray[i].imprint2){
//             document.getElementById("iRIGHT_THUMB_" + j).src = "data:image/bmp;base64,"+myStringArray[i].imprint2;
//         }

//         if (myStringArray[i].do_address == 1) {
//             $("#do_address_" + j).attr('checked', true);
//         }

//         $("#gender_" + j).val(myStringArray[i].gender);
//         $("#custodian_type_" + j).val(myStringArray[i].custodian_type);

//         $("#organization_name_" + j).val(myStringArray[i].organization_name);
//         $("#trade_no_" + j).val(myStringArray[i].trade_no);

//         var locationType = myStringArray[i].location_type;
//         var divId = myStringArray[i].divid;
//         var zillaId = myStringArray[i].zillaId;
//         var upazilaId = myStringArray[i].upazilaid;
//         var citycorporationId = myStringArray[i].geo_citycorporation_id;
//         var metropolitanId = myStringArray[i].geo_metropolitan_id;
//         var thanaId = myStringArray[i].geo_thana_id;


//         eMobileLocation.populateLocation(j, locationType, divId, zillaId, upazilaId, citycorporationId, metropolitanId, thanaId);
//     }

// }
// };

// $(document).ready(function () {
// //   criminal.init();
// });


// var url = "{{ url('/court/create_events') }}/" + id;
// $.ajax({
//     url:url,
//     data: "&title=" + title +"&status=" + status +"&start=" + start,
//     type: "POST",
//     success: function(json) {
    
       
//     }
// });



$(document).ready(function () {

        jQuery('select[name="criminal[0][division]"]').on('change',function(){
            const dataID = jQuery(this).val();
            jQuery("#ddlZilla0").after('<div class="loadersmall"></div>');
            if(dataID)
            {
                jQuery.ajax({
                url : '{{url("/")}}/mobile-court/dropdownlist/getdependentdistrict/' +dataID,
                type : "GET",
                dataType : "json",
                success:function(data)
                {
                    jQuery('select[name="criminal[0][zilla]"]').html('<div class="loadersmall"></div>');
                    jQuery('select[name="criminal[0][zilla]"]').html('<option value="">-- নির্বাচন করুন --</option>');
                    jQuery.each(data, function(key,value){
                        jQuery('select[name="criminal[0][zilla]"]').append('<option value="'+ key +'">'+ value +'</option>');
                    });
                    jQuery('.loadersmall').remove();
                }
                });
            }
            else
            {
                $('select[name="criminal[0][zilla]"]').empty();
            }
        });

        // zila Dropdown
         /*---------------------------------------------------------------------------
        location type wise upozilla , zilla , metropolitan , citycorporation list show 
        -----------------------------------------------------------------------------*/
        jQuery('select[name="criminal[0][zilla]"]').on('change',function(){
            const dataID = jQuery(this).val();
            const location_type =  $("input[name='criminal[0][locationtype]']:checked").val();
            var crimial_zila_id = jQuery('#ddlZilla0 option:selected').val();
            if(crimial_zila_id){
                if(location_type == 'CITYCORPORATION'){
                    $('#citytype_0').attr("checked", "checked");
                    $('#upazillatype_0, #metrotype_0').removeAttr("checked");  
                    $('#ddlThana0').next('.select2-container').hide();  
                  
                
                    jQuery.ajax({
                        url : '{{url("/")}}/mobile-court/dropdownlist/getdependentcitycorporation/' +crimial_zila_id,
                        type : "GET",
                        dataType : "json",
                        success:function(data)
                        {
                            
                            jQuery('select[name="criminal[0][locationDetails]"]').html('<div class="loadersmall"></div>');
                
                            jQuery('select[name="criminal[0][locationDetails]"]').html('<option value="">-- নির্বাচন করুন --</option>');
                            jQuery.each(data, function(key,value){
                                jQuery('select[name="criminal[0][locationDetails]"]').append('<option value="'+ key +'">'+ value +'</option>');
                            });
                            jQuery('.loadersmall').remove();
                        }
                    });

                }else if(location_type =='METROPOLITAN'){
                    
                    $('#metrotype_0').attr("checked", "checked");
                    $('#upazillatype_0, #citytype_0').removeAttr("checked"); 
                    $('#ddlThana0').next('.select2-container').show();
                    
                    // var crimial_zila_id = jQuery('#ddlZilla0 option:selected').val();
                
                    jQuery.ajax({
                    url : '{{url("/")}}/mobile-court/dropdownlist/getdependentmetropolitan/' +crimial_zila_id,
                    type : "GET",
                    dataType : "json",
                    success:function(data)
                    {
                        
                        jQuery('select[name="criminal[0][locationDetails]"]').html('<div class="loadersmall"></div>');
            
                        jQuery('select[name="criminal[0][locationDetails]"]').html('<option value="">-- নির্বাচন করুন --</option>');
                        jQuery.each(data, function(key,value){
                            jQuery('select[name="criminal[0][locationDetails]"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                        jQuery('.loadersmall').remove();
                    }
                    });

                    
                }else{
                    $('#upazillatype_0').attr("checked", "checked");
                    $('#metrotype_0, #citytype_0').removeAttr("checked");
                    $('#ddlUpazilla0').next('.select2-container').show();   
                    $('#ddlThana0').next('.select2-container').hide();   
                    // var crimial_zila_id = jQuery('#ddlZilla0 option:selected').val();
                
                    jQuery.ajax({
                        url : '{{url("/")}}/mobile-court/dropdownlist/getdependentupazila/' +crimial_zila_id,
                        type : "GET",
                        dataType : "json",
                        success:function(data)
                        {
                        jQuery('select[name="criminal[0][locationDetails]"]').html('<div class="loadersmall"></div>');
                
                        jQuery('select[name="criminal[0][locationDetails]"]').html('<option value="">-- নির্বাচন করুন --</option>');
                        jQuery.each(data, function(key,value){
                            jQuery('select[name="criminal[0][locationDetails]"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                        jQuery('.loadersmall').remove();
                        }
                    });
                }
            }

        });
    
    
        /*---------------------------------------------
         default setup , upozilla checked , thana hide 
        -----------------------------------------------*/
        $('#upazillatype_0').attr("checked", "checked");
        $('#ddlThana0').next('.select2-container').hide();
        /*---------------------------------------------------------------------------
        location type wise upozilla , zilla , metropolitan , citycorporation list show 
        -----------------------------------------------------------------------------*/


        $('input[name="criminal[0][locationtype]"]').on('click',function(){

            const dataID = jQuery(this).val();
            var crimial_zila_id = jQuery('#ddlZilla0 option:selected').val();
            if(crimial_zila_id){
                if(dataID == 'CITYCORPORATION'){
                    $('#citytype_0').attr("checked", "checked");
                    $('#upazillatype_0, #metrotype_0').removeAttr("checked");  
                    $('#ddlThana0').next('.select2-container').hide();  
                
                
                    jQuery.ajax({
                        url : '{{url("/")}}/mobile-court/dropdownlist/getdependentcitycorporation/' +crimial_zila_id,
                        type : "GET",
                        dataType : "json",
                        success:function(data)
                        {
                            
                            jQuery('select[name="criminal[0][locationDetails]"]').html('<div class="loadersmall"></div>');
                
                            jQuery('select[name="criminal[0][locationDetails]"]').html('<option value="">-- নির্বাচন করুন --</option>');
                            jQuery.each(data, function(key,value){
                                jQuery('select[name="criminal[0][locationDetails]"]').append('<option value="'+ key +'">'+ value +'</option>');
                            });
                            jQuery('.loadersmall').remove();
                        }
                    });

                }else if(dataID =='METROPOLITAN'){
                    
                    $('#metrotype_0').attr("checked", "checked");
                    $('#upazillatype_0, #citytype_0').removeAttr("checked"); 
                    $('#ddlThana0').next('.select2-container').show();
                    
                    // var crimial_zila_id = jQuery('#ddlZilla0 option:selected').val();
                
                    jQuery.ajax({
                    url : '{{url("/")}}/mobile-court/dropdownlist/getdependentmetropolitan/' +crimial_zila_id,
                    type : "GET",
                    dataType : "json",
                    success:function(data)
                    {
                        
                        jQuery('select[name="criminal[0][locationDetails]"]').html('<div class="loadersmall"></div>');
            
                        jQuery('select[name="criminal[0][locationDetails]"]').html('<option value="">-- নির্বাচন করুন --</option>');
                        jQuery.each(data, function(key,value){
                            jQuery('select[name="criminal[0][locationDetails]"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                        jQuery('.loadersmall').remove();
                    }
                    });

                    
                }else{
                    $('#upazillatype_0').attr("checked", "checked");
                    $('#metrotype_0, #citytype_0').removeAttr("checked");
                    $('#ddlUpazilla0').next('.select2-container').show();   
                    $('#ddlThana0').next('.select2-container').hide();   
                    // var crimial_zila_id = jQuery('#ddlZilla0 option:selected').val();
                
                    jQuery.ajax({
                        url : '{{url("/")}}/mobile-court/dropdownlist/getdependentupazila/' +crimial_zila_id,
                        type : "GET",
                        dataType : "json",
                        success:function(data)
                        {
                        jQuery('select[name="criminal[0][locationDetails]"]').html('<div class="loadersmall"></div>');
                
                        jQuery('select[name="criminal[0][locationDetails]"]').html('<option value="">-- নির্বাচন করুন --</option>');
                        jQuery.each(data, function(key,value){
                            jQuery('select[name="criminal[0][locationDetails]"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                        jQuery('.loadersmall').remove();
                        }
                    });
                    
                }
            } 
        });



        /*-----------------------------------------------------------
         metropolitan wise thana get 
        -------------------------------------------------------------*/
        $('select[name="criminal[0][locationDetails]"]').on('change',function(){
            // var ddlUpazilla = jQuery('#ddlUpazilla0 option:selected').val();
            const crimial_zila_id = jQuery('#ddlZilla0 option:selected').val();
            // alert(ddlUpazilla);
            jQuery.ajax({
                url : '{{url("/")}}/mobile-court/dropdownlist/getdependentmetropolitanthana/' +crimial_zila_id,
                type : "GET",
                dataType : "json",
                success:function(data)
                {
                jQuery('select[name="criminal[0][GeoThanas]"]').html('<div class="loadersmall"></div>');
        
                jQuery('select[name="criminal[0][GeoThanas]"]').html('<option value="">-- নির্বাচন করুন --</option>');
                jQuery.each(data, function(key,value){
                    jQuery('select[name="criminal[0][GeoThanas]"]').append('<option value="'+ key +'">'+ value +'</option>');
                });
                jQuery('.loadersmall').remove();
                }
            });
        });
});
</script>
<!-- <script src="{{ asset('js/location/location.js') }}"></script> -->


@endsection