
@extends('layout.app')
@section('content')
@push('head')
    <link href="{{ asset('assets/css/pages/wizard/wizard-1.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/pages/tachyons.min.css') }}" rel="stylesheet" type="text/css" />
@endpush
<style>
  .nav-wizard {
    background-color: #fff;
    margin-bottom: 15px;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border-radius: 3px;
    margin-bottom: 5px;
    /* border: 1px solid #42423a; */
    /* width: auto; */
    /* box-shadow: 2px 5px 2px -4px; */
  }

  .nav-wizard > li:first-child > a {
    padding-left: 15px;
    -moz-border-radius: 3px 0 0 3px;
    -webkit-border-radius: 3px 0 0 3px;
    border-radius: 3px 0 0 3px;
  }
  /* .nav-wizard > li > a:hover, 
  .nav-wizard > li > a:active, 
  .nav-wizard > li > a:focus {
    background-color: #ccc;
  } */
  .nav > li > a {
    padding: 12px 15px;
  }
  .nav-wizard > li {
        line-height: 40px;
   }
  .nav-wizard > li > a {
    position: relative;
    padding-left: 30px;
    -moz-border-radius: 0;
    -webkit-border-radius: 0;
    border-radius: 0;
}
.nav-wizard > li > a {
    color: #7e8299;
}
a:hover, a:active, a:focus {
    text-decoration: none;
    color: #000;
}
.nav-wizard > li {
    line-height: 40px;
}
.nav-wizard > li > a:before {
    border-left: 20px solid #fff;
    border-top: 22px solid rgba(0, 0, 0, 0);
    border-bottom: 22px solid rgba(0, 0, 0, 0);
    content: '';
    display: inline-block;
    position: absolute;
    top: -1px;
    right: -20px;
    z-index: 5;
}
/* .nav-disabled-click > li > a:hover, .nav-disabled-click > li > a:active, .nav-disabled-click > li > a:focus {
    background-color: #ddd;
    cursor: default;
} */

.nav-wizard > li > a:after {
    border-left: 19px solid #fff;
    border-top: 22px solid rgba(0, 0, 0, 0);
    border-bottom: 22px solid rgba(0, 0, 0, 0);
    content: '';
    display: inline-block;
    position: absolute;
    top: 0;
    right: -19px;
    z-index: 10;
}

.nav-tabs.nav-justified>.active>a, 
.nav-tabs.nav-justified>.active>a:focus, 
.nav-tabs.nav-justified>.active>a:hover {
    border: 1px solid #ddd;
}
.nav-wizard > li>a.active, 
.nav-wizard > li>a.active:hover, 
.nav-wizard > li>a.active:focus, 
.nav-wizard > li>a:active {
    background-color: #428bca !important;
    color: #fff !important;
}
.nav-wizard > li>a.active:after {
    border-left-color: #428bca;
}

body {
  padding : 10px ;
  
}

.input-error {
    border-color: red !important;
} 

span.select2-selection.select2-selection--single {
    height: 40px;
}
</style>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-custom">
                <div class="card-header">
                    <h2 class="card-title  card-label font-weight-bolder text-dark h3">অভিযোগ দায়ের ফরম</h2>
                    <input type="hidden" id="txtProsecutionID" value="{{ $prosecution_id}}" />
                    <input type="hidden" id="selectMagistrateId" value="{{$magistrate_id}}" />
                    <input type="hidden" id="prosecutorIdInProsecution" value="{{ $prosecutorId }}" />
                </div>
                <div class="card-body  cpv">
                    <ul class="nav nav-justified nav-wizard nav-disabled-click nav-tabs m-0" id="myTab">
                        <li id="tab-0" ><a href="#tab1-2-prosecutor" data-toggle="tab"><strong>ধাপ ১:</strong> কোর্ট নির্বাচন করুন</a></li>
                        <li id="tab-1"><a href="#tab1-2" data-toggle="tab"><strong>ধাপ ২:</strong> অভিযুক্ত সংক্রান্ত তথ্য</a></li>
                        <li id="tab-2"><a href="#tab2-2" data-toggle="tab"><strong>ধাপ ৩:</strong> সাক্ষী সংক্রান্ত তথ্য</a></li>
                        <li id="tab-3"><a href="#tab3-2" data-toggle="tab"><strong>ধাপ ৪:</strong> অপরাধ সংক্রান্ত তথ্য</a></li>
                    </ul>

                    <!-- <div class="progress progress-xs m-0">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                    </div> -->

                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1-2-prosecutor">
                             <!-- prosecution/partials/tab1-2-magistrate_selection -->
                             @include('appeals.partials.tab1-2-magistrate_selection')
                        </div>

                        <div class="tab-pane" id="tab1-2">
                              @include('appeals.partials.tab1-2')
                        </div>

                        <div class="tab-pane" id="tab2-2">
                             @include('appeals.partials.tab2-2')
                        </div>

                        <div class="tab-pane" id="tab3-2">
                             @include('appeals.partials.tab3-2')
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- <script src="{{ asset('js/validation/input-validator.js') }}"></script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>



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
<script  src="{{ asset('mobile_court/javascripts/source/utils/convertEngToBangla.js') }}" ></script> -->

<script src="{{ asset('js/validation/input-validator.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script  src="{{ asset('mobile_court/javascripts/source/content/multiFileUpload.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/prosecution/prosecutionInitiate.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/prosecution/criminalInfoForm.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/prosecution/witnessInfoForm.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/prosecution/complaintInfoForm.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/prosecution/magistrateSelectionForm.js') }}" ></script>

<script  src="{{ asset('mobile_court/javascripts/source/law/law.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/utils/ui-utils.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/location/location.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/utils/convertEngToBangla.js') }}" ></script>

<script>
    function doParmanentAddress(val, criminal) {

    if ($('#do_address_' + criminal).is(":checked")) {
        var prmanent_address = $("#permanent_address_" + criminal).val();

        var upozillaID = 'ddlUpazilla' + criminal;
        var upozillaoptionName ="";

        var zilla_ID  = 'ddlZilla' + criminal;
        var zillaoptionName ="";

        var thana_ID = 'ddlThana' + criminal; // thana
        var thanaoptionName ="";

        var optionValue ="";

        var expertise_chosen = false;
        var expertiseObj = document.getElementById(upozillaID);
        for (var i = 1; i < expertiseObj.length; i++) {
            if (expertiseObj.options[i].selected == true) {
                expertise_chosen = true;
                upozillaoptionName = ", " +  expertiseObj.options[i].text;

                optionValue = expertiseObj.options.length;
                break;
            }
        }

        expertise_chosen = false;
        expertiseObj = document.getElementById(zilla_ID);
        for (var i = 1; i < expertiseObj.length; i++) {
            if (expertiseObj.options[i].selected == true) {
                expertise_chosen = true;
                zillaoptionName = ", " +  expertiseObj.options[i].text;

                optionValue = expertiseObj.options.length;
                break;
            }
        }

        expertise_chosen = false;
        expertiseObj = document.getElementById(thana_ID);
        for (var i = 1; i < expertiseObj.length; i++) {
            if (expertiseObj.options[i].selected == true) {
                expertise_chosen = true;
                thanaoptionName = ", " + expertiseObj.options[i].text;

                optionValue = expertiseObj.options.length;
                break;
            }
        }
        var location = prmanent_address +  upozillaoptionName  + thanaoptionName + zillaoptionName;
        $("#present_address_" + criminal).val(location);
    }
    else {
        $("#present_address_" + criminal).val("");
    }

}
</script>
@endsection