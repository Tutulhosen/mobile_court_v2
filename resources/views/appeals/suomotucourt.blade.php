@extends('layout.app')
@section('content')
    @push('head')
        <link href="{{ asset('assets/css/pages/wizard/wizard-1.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/pages/tachyons.min.css') }}" rel="stylesheet" type="text/css" />
    @endpush

    <style>
        body {
            padding: 10px;
            background: #e5f3d4;

        }

        #exTab1 .tab-content {
            color: white;
            background-color: #428bca;
            padding: 5px 15px;
        }

        #exTab2 h3 {
            color: white;
            background-color: #428bca;
            padding: 5px 15px;
        }

        /* remove border radius for the tab */

        #exTab1 .nav-pills>li>a {
            border-radius: 0;
        }

        /* change border radius for the tab , apply corners on top*/

        #exTab3 .nav-pills>li>a {
            border-radius: 4px 4px 0 0;
        }

        #exTab3 .tab-content {
            color: white;
            background-color: #428bca;
            padding: 5px 15px;
        }



        img:hover {
            box-shadow: 8px 8px 5px #7e958d;
            border: 3px solid #7d9589;
            background: #7d9589;
        }

        .input-error {
            border-color: red !important;
        }

        span.select2-selection.select2-selection--single {
            height: 40px;
        }

        .nav-primary {
            border-color: #357ebd;
            background-color: #428bca;
        }

        .nav-primary>li.active>a,
        .nav-primary>li.active>a:hover,
        .nav-primary>li.active>a:focus,
        .nav-primary>li.active>a:active {
            border-top-color: #357ebd;
            border-left-color: #357ebd;
            border-right-color: #357ebd;
        }

        .nav>li>a {
            padding: 4px 40px;
        }

        .nav-primary>li>a,
        .nav-success>li>a,
        .nav-info>li>a,
        .nav-danger>li>a,
        .nav-warning>li>a {
            color: #fff;
            line-height: 30px;

        }

        .nav-primary>li>a:hover,
        .nav-success>li>a:hover,
        .nav-info>li>a:hover,
        .nav-danger>li>a:hover,
        .nav-warning>li>a:hover {
            color: #000;
            background-color: rgb(255 255 255);

        }

        /* .nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {
                        color: #555;
                        cursor: default;
                        background-color: #fff;
                        border: 1px solid #ddd;
                        border-bottom-color: transparent;
                    } */
        .nav-primary>li>a.active {
            background-color: #fff;
            color: #000;
        }
    </style>

    <select id="dd_seizure" class=" " name="dd_seizure" usedummy="1" style="display:none;">
        <option value="">বাছাই করুন...</option>
        @if (!empty($seizureitem_type))
            @foreach ($seizureitem_type as $seizureitemtype)
                <option value="{{ $seizureitemtype->id }}">{{ $seizureitemtype->item_group }}</option>
            @endforeach
        @endif
    </select>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom">
                    <div class="panel panel-default" xmlns="http://www.w3.org/1999/html">
                        <div class="card-body cpv selfCourt">
                            <div class="panel panel-default">

                                <div class="panel-heading">
                                    <h2 class="card-label font-weight-bolder text-dark h3">স্বপ্রণোদিত কোর্ট
                                        <?php echo !$prosecution_id ? '(নতুন)' : ''; ?></h2>
                                    <input type="hidden" id="txtProsecutionID" value="{{ $prosecution_id }}" />
                                </div>
                                <div class=" cpv selfPanel">
                                    <div class="d-flex justify-content-center" style="background-color:#357ebd">
                                        <ul class="nav nav-primary nav-tabs" style="background-color:#357ebd"
                                            id="bs_tab">
                                            <li class="active" id="tab-1"><a href="#tab1-2" data-toggle="tab"> অভিযুক্ত
                                                    ব্যক্তির তথ্য</a></li>
                                            <li id="tab-2"><a href="#tab2-2" data-toggle="tab"> সাক্ষীর তথ্য</a></li>
                                            <li id="tab-3"><a href="#tab3-2" data-toggle="tab"> অভিযোগ গঠন</a></li>
                                            <li id="tab-4"><a href="#tab4-2" data-toggle="tab"> জব্দতালিকা</a></li>
                                            <li id="tab-5"><a href="#tab5-2" data-toggle="tab"> আসামির জবানবন্দি</a>
                                            </li>
                                            <li id="tab-6"><a href="#tab6-2" data-toggle="tab"> আদেশ প্রদান</a></li>
                                        </ul>
                                    </div>

                                    <div class="tab-content">

                                        <div class="tab-pane active" id="tab1-2">
                                            @include('appeals.partials.tab1-2')
                                        </div>

                                        <div class="tab-pane" id="tab2-2">
                                            @include('appeals.partials.tab2-2')
                                        </div>

                                        <div class="tab-pane" id="tab3-2">
                                            @include('appeals.partials.tab3-2')
                                        </div>

                                        <div class="tab-pane" id="tab4-2">
                                            @include('appeals.partials.tab4-2')
                                        </div>

                                        <div class="tab-pane" id="tab5-2">
                                            @include('appeals.partials.tab5-2')
                                        </div>

                                        <div class="tab-pane" id="tab6-2">
                                            @include('appeals.partials.tab6-2')
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="successprosecution" class="modal" style="display: none; ">
                            <div class="modal-dialog modal-md">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <a class="close" data-dismiss="modal">×</a>
                                        <h3>ধন্যবাদ</h3>
                                    </div>
                                    <div class="modal-body" style="height: 200px; overflow: auto;">
                                        @include('appeals.partials.message_suo')
                                    </div>
                                    <div class="modal-footer">
                                        <a href="#" class="btn btn-mideum" data-dismiss="modal">সমাপ্ত</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="container">
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
                <div class="card-body cpv selfPanel">
                  
                <ul class="nav nav-tabs" role="tablist" id="bs_tab">
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

                       @include('appeals.partials.tab1-2')
                       
                    </div>
                    <div class="tab-pane" id="tab2-2" role="tabpanel">
                       @include('appeals.partials.tab2-2')
                    </div>
                    <div class="tab-pane" id="tab3-2" role="tabpanel">
                        @include('appeals.partials.tab3-2')
                    </div>

                    <div class="tab-pane" id="tab4-2" role="tabpanel">
                        @include('appeals.partials.tab4-2')
                    </div>
                     
                    <div class="tab-pane" id="tab5-2" role="tabpanel">
                        @include('appeals.partials.tab5-2')
                    </div>
                    <div class="tab-pane" id="tab6-2" role="tabpanel">
                        @include('appeals.partials.tab6-2')
                    </div>
                </div>
                      

                </div>
           </div>
        </div>
    </div>
</div> --}}

    <div id="successprosecution" class="modal" style="display: none; ">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="close" data-dismiss="modal">×</a>
                    <h3>ধন্যবাদ</h3>
                </div>
                <div class="modal-body" style="height: 200px; overflow: auto;">

                    @include('appeals.partials.message_suo')
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-mideum" data-dismiss="modal">সমাপ্ত</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <!-- <script src="https://unpkg.com/validator@latest/validator.min.js"></script> -->
    <script src="{{ asset('js/validation/input-validator.js') }}"></script>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <!-- <script src="{{ asset('mobile_court/javascripts/lib/validation/input-validator.js') }}"></script> -->
    <script src="{{ asset('mobile_court/javascripts/source/content/multiFileUpload.js') }}"></script>
    <script src="{{ asset('mobile_court/javascripts/source/prosecution/prosecutionInitiate.js') }}"></script>
    <script src="{{ asset('mobile_court/javascripts/source/prosecution/magistrateSelectionForm.js') }}"></script>
    <script src="{{ asset('mobile_court/javascripts/source/prosecution/criminalInfoForm.js') }}"></script>
    <script src="{{ asset('mobile_court/javascripts/source/prosecution/witnessInfoForm.js') }}"></script>
    <script src="{{ asset('mobile_court/javascripts/source/prosecution/complaintInfoForm.js') }}"></script>
    <script src="{{ asset('mobile_court/javascripts/source/prosecution/seizureListInfoForm.js') }}"></script>
    <script src="{{ asset('mobile_court/javascripts/source/prosecution/confessionForm.js') }}"></script>
    <script src="{{ asset('mobile_court/javascripts/source/prosecution/ordersheetInfoForm.js') }}"></script>
    <script src="{{ asset('mobile_court/javascripts/source/law/law.js') }}"></script>
    <script src="{{ asset('mobile_court/javascripts/source/utils/ui-utils.js') }}"></script>
    <script src="{{ asset('mobile_court/javascripts/source/location/location.js') }}"></script>
    <script src="{{ asset('mobile_court/javascripts/source/fingerprint/allFingerPrint.js') }}"></script>
    <script src="{{ asset('mobile_court/javascripts/source/utils/convertEngToBangla.js') }}"></script>

    <script>
        function doParmanentAddress(val, criminal) {
            console.log(val, criminal)
            if ($('#do_address_' + criminal).is(":checked")) {
                var prmanent_address = $("#permanent_address_" + criminal).val();

                var upozillaID = 'ddlUpazilla' + criminal;
                var upozillaoptionName = "";

                var zilla_ID = 'ddlZilla' + criminal;
                var zillaoptionName = "";

                var thana_ID = 'ddlThana' + criminal; // thana
                var thanaoptionName = "";

                var optionValue = "";

                var expertise_chosen = false;
                var expertiseObj = document.getElementById(upozillaID);
                for (var i = 1; i < expertiseObj.length; i++) {
                    if (expertiseObj.options[i].selected == true) {
                        expertise_chosen = true;
                        upozillaoptionName = ", " + expertiseObj.options[i].text;

                        optionValue = expertiseObj.options.length;
                        break;
                    }
                }

                expertise_chosen = false;
                expertiseObj = document.getElementById(zilla_ID);
                for (var i = 1; i < expertiseObj.length; i++) {
                    if (expertiseObj.options[i].selected == true) {
                        expertise_chosen = true;
                        zillaoptionName = ", " + expertiseObj.options[i].text;

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
                var location = prmanent_address + upozillaoptionName + thanaoptionName + zillaoptionName;
                console.log('location', location, prmanent_address , upozillaoptionName ,thanaoptionName , zillaoptionName)
                $("#present_address_" + criminal).val(location);
            } else {
                $("#present_address_" + criminal).val("");
            }

        }
    </script>
    <!-- <link rel="stylesheet" type="text/css" href="{{ asset('mobile_court/javascripts/lib/select2/select2.min.css') }}" />
                    <script type="text/javascript" src="{{ asset('mobile_court/javascripts/lib/select2/select2.min.js') }}"></script> -->

    <!-- <script type="text/javascript" src="{{ asset('mobile_court/javascripts/lib/custom.js') }}"></script> -->
    <!-- <script type="text/javascript" src="{{ asset('mobile_court/javascripts/lib/custom_c.js') }}"></script> -->
@endsection
