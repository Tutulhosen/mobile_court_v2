@extends('layout.app')
@section('content')
    <style>
        .nav-primary {
            border-color: #357ebd;
            background-color: #428bca;
        }
        body {
            padding: 10px;
            background: #e5f3d4;

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
            padding: 4px 30px;
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

        .card-title {
            margin-bottom: 0px;
        }
    </style>

    <style>
        .thumbnail {
            position: relative;
            margin: 5px;
            float: left;
            border-radius: 3px;
            box-shadow: 1px 1px 5px #ccc;
            overflow: hidden;
            border: 1px solid #ccc;
        }

        .thumbnail>.img-label {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
            font-size: 0.8em;
            width: 50%;
            padding: 5px 10px;
            background: rgba(255, 255, 255, 0.75);
        }

        .img-button {
            position: absolute !important;
            z-index: 2 !important;
            right: 10px !important;
            color: #F44336;
            opacity: 1;
        }

        .img-thumbnail {
            /* padding: .25rem; */
            background-color: #fff;
            border: 1px solid #e4e6ef;
            /* border-radius: .42rem; */
            -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, .075);
            box-shadow: 0 1px 2px rgba(0, 0, 0, .075);
            max-width: 25%;
            height: auto;
        }

        img.img-responsive.multi-image {
            max-width: 100%;
        }
    </style>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom">
                    <div id="ordersheetform" name="ordersheetform" method="post" enctype="multipart/form-data"
                        novalidate="novalidate">
                        <div class="mainwrapper">
                            <div class="contentpanel">
                                <div class="panel panel-default">
                                    <div class="card-header">
                                        <h2 class="card-title">আদেশ প্রদান <b id="prosecutionType"></b> </h2>
                                        <input type="hidden" id="txtProsecutionID" value="{{ $prosecutionId }}" />
                                    </div>
                                    <div class="card-body cpv" >
                                        <ul id="myTab" class="nav nav-primary nav-tabs" style="justify-content: center">
                                            <li class="active">
                                                <a id="caseNo" href="#accordioncase" data-toggle="tab" class="active">
                                                </a>
                                            </li>
                                            <li id="criminalInfo">
                                                <a data-toggle="tab" href="#accordionCriminal">
                                                    আসামির বিবরণ
                                                </a>
                                            </li>

                                            <li id="criminalConfess">
                                                <a data-toggle="tab" href="#accordionConfession">
                                                    আসামির জবানবন্দি
                                                </a>
                                            </li>

                                            <li>
                                                <a data-toggle="tab" href="#accordionseizurelist">
                                                    জব্দতালিকা
                                                </a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#accordionAttachemnt">
                                                    সংযুক্তি
                                                </a>
                                            </li>
                                            <li><a href="#OrderSheet_punishment" tabindex="-1" data-toggle="tab">শাস্তি
                                                    প্রদান</a>
                                            </li>
                                        </ul>

                                        {{-- tab description --}}
                                        <div id="myTabContent" class="tab-content mt-3">
                                            <div class="tab-pane fade show active" id="accordioncase">
                                                <table class="table table-bordered table-striped" border="1"
                                                    cellpadding="4" cellspacing="3" width="100%">
                                                    <tr>
                                                        <td align="left" bgcolor="#E0F0E8">
                                                            প্রসিকিউটের নাম
                                                        </td>
                                                        <td align="left">
                                                            <span id="prosecutorName">nur alam</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" bgcolor="#E0F0E8">
                                                            অভিযোগের তারিখ
                                                        </td>
                                                        <td align="left">
                                                            <span id="complaintDate">20-9-2017</span>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td align="left" bgcolor="#E0F0E8">
                                                            অভিযোগের স্থান
                                                        </td>
                                                        <td align="left">
                                                            <span id="complaintPlace">20-9-2017</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" bgcolor="#E0F0E8">
                                                            আইন
                                                        </td>
                                                        <td align="left">
                                                            <span id="lawTitle">20-9-2017</span>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td align="left" bgcolor="#E0F0E8">
                                                            ধারা
                                                        </td>
                                                        <td align="left">
                                                            <span id="lawSection">20-9-2017</span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>

                                            <div class="tab-pane fade" id="accordionCriminal">
                                                <table id="criminalInfoTable" class="table-bordered" border="1"
                                                    cellpadding="4" cellspacing="3" width="100%">
                                                    <tr>
                                                        <td align="left" bgcolor="#E0F0E8">
                                                            নাম
                                                        </td>
                                                        <td align="left" bgcolor="#E0F0E8">
                                                            পিতা / স্বামীর নাম
                                                        </td>
                                                        <td align="left" bgcolor="#E0F0E8">
                                                            মাতার নাম
                                                        </td>
                                                        <td align="left" bgcolor="#E0F0E8">
                                                            ঠিকানা
                                                        </td>
                                                    </tr>

                                                </table>
                                            </div>
                                            <!-- tab accordionCriminal -->
                                            <div class="tab-pane fade" id="accordionConfession">
                                                <div id="confessiondiv">
                                                    <!-- Append body dinamicallly-->
                                                </div>
                                            </div>
                                            <!-- tab accordionCriminal -->
                                            <div class="tab-pane fade" id="accordionseizurelist">
                                                <table id="seizureTable" class="table-bordered" border="1"
                                                    cellpadding="4" cellspacing="3" width="100%">
                                                    <tr>
                                                        <td align="left" bgcolor="#E0F0E8">
                                                            বিবরণ
                                                        </td>
                                                        <td align="left" bgcolor="#E0F0E8">
                                                            ওজন
                                                        </td>
                                                        <td align="left" bgcolor="#E0F0E8">
                                                            উদ্ধার স্থান
                                                        </td>
                                                        <td align="left" bgcolor="#E0F0E8">
                                                            মালামালের ধরন
                                                        </td>
                                                    </tr>
                                                </table>
                                                <table id="alterTextForSeizureList" style="display:none"
                                                    class="text table-bordered" border="1" cellpadding="4"
                                                    cellspacing="3" width="100%">
                                                    <tr>
                                                        <td colspan="4" class="highlitedrow"> জব্দতালিকা নাই</td>
                                                    </tr>
                                                </table>
                                            </div> <!-- tab accordionseizurelist -->
                                            <div class="tab-pane fade" id="OrderSheet_punishment">
                                                @include('appeals.partials/tab6-2')
                                            </div> <!-- tab OrderSheet_punishment -->

                                            <div class="tab-pane fade row" id="accordionAttachemnt">
                                                <div class="panel-body cpv p-0">
                                                    <div class="form-group">
                                                        <div class="panel panel-danger-alt">
                                                            <div class="container" id="allFileView">

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- tab accordionAttachemnt -->
                                        </div> <!-- tab main -->
                                    </div> <!-- panel-body cpv-->
                                    <div class="panel-footer">
                                    </div>
                                </div> <!-- panel panel-default -->
                            </div> <!-- contentpanel -->
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/validation/input-validator.js') }}"></script>
    <script src="{{ asset('mobile_court/javascripts/source/utils/convertEngToBangla.js') }}"></script>
    <script src="{{ asset('mobile_court/javascripts/source/content/multiFileUpload.js') }}"></script>
    <script src="{{ asset('mobile_court/javascripts/source/prosecution/showAllprosecutionDataWithProsecutor.js') }}">
    </script>
    <script src="{{ asset('mobile_court/javascripts/source/prosecution/complaintInfoForm.js') }}"></script>
    <script src="{{ asset('mobile_court/javascripts/source/prosecution/ordersheetInfoForm.js') }}"></script>
@endsection
