@extends('layout.app')
@yield('style')
<!-- <link href="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" /> -->
{{-- Includable CSS Related Page --}}
@section('styles')
    <link rel="stylesheet" href="{{ asset('/mobile_court/cssmc/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('/mobile_court/cssmc/jquery.alerts.css') }}">
    <link rel="stylesheet" href="{{ asset('/mobile_court/cssmc/monthly_report_table.css') }}">
    <link rel="stylesheet" href="{{ asset('/mobile_court/cssmc/morris/morris.css') }}">
    <link rel="stylesheet" href="{{ asset('/mobile_court/cssmc/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('/mobile_court/cssmc/jquery-ui-1.11.0.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/mobile_court/javascripts/source/dashboard/loading_plugin/loading.css') }}">
@endsection

@section('content')
    {{-- @include('dashboard.inc.icon_card') --}}

    <style type="text/css">
        label.control-label {
            font-size: 16px;
            font-weight: 600;
        }

        fieldset {
            border: 1px solid #ddd !important;
            margin: 0;
            xmin-width: 0;
            padding: 10px;
            position: relative;
            border-radius: 4px;
            background-color: #d5f7d5;
            padding-left: 10px !important;
        }

        fieldset .form-label {
            color: black;
        }

        legend {
            font-size: 14px;
            font-weight: bold;
            width: 45%;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px 5px 5px 10px;
            background-color: #5cb85c;
        }

        .list-group-flush>.list-group-item {
            padding-left: 0;
        }

        /*highchart css*/

        .highcharts-figure,
        .highcharts-data-table table {
            /*min-width: 310px; */
            /*max-width: 1000px;*/
            /*margin: 1em auto;*/
        }

        #container {
            /*height: 400px;*/
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #EBEBEB;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            /*max-width: 500px;*/
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }

        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }

        /*Pie chart*/
        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 320px;
            max-width: 1030px;
            margin: 1em auto;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #EBEBEB;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }

        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }

        input[type="number"] {
            min-width: 50px;
        }

        .highcharts-credits {
            display: none;
        }

        .list-group-item>.badge {
            float: right;
        }
    </style>

<style>
    .chartSubText {
        font-size: 18px !important;
        color: green !important;
    }
    #block2_label, #block3_label {
        color: green !important;
    }
    
</style>
    <div class="container">
        <div class="divSpace"></div>
        <div class="card  panel-primary">
            <div class="card-body cpv cpv96">
                <div class="row m-bottom-30">
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="panel panel-success-alt">
                            <div class="panel-heading panel-heading-dashboard">
                                <h3 class="panel-title-dashboard">অনুসন্ধানের উপাত্তসমূহ</h3>
                            </div><!-- panel-heading -->
                            <div class="panel-body cpv cpv-fixed-height nopadding">


                                <?php if($roleID== 38 or $roleID== 37){ ?>
                                <input class="hidden" id="zilla" value="{{ $zillaId }}" type="hidden"> <br />
                                <?php } ?>


                                <?php
                                //if ($profile == 'Divisional Commissioner'){
                                ?>

                                <!-- <div class="form-group clearfix m-top-5">
                                                        <div class="col-sm-12" >
                                                            <label class="col-sm-12 control-label">জেলা </label>
                                                            <?php
                                                            //  echo $this->tag->select(array(
                                                            // "zilla",
                                                            // $zilla,
                                                            // "using" => array("zillaid", "zillaname"),
                                                            // 'useEmpty' => true,
                                                            // 'emptyText' => 'জেলা বাছাই করুন',
                                                            // 'emptyValue' => '',
                                                            // 'class' => "input",
                                                            // 'style' => 'width:100%',
                                                            // 'onchange'=>"showupozilladiv()"
                                                            // ))
                                                            ?>
                                                            <label for="fruits" class="error"></label>
                                                        </div>
                                                    </div> -->

                                <?php //}
                                ?>

                                <?php
                                //if ($profile == 'JS'){
                                ?>

                                <!-- <div class="clearfix form-group m-top-5">
                                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                            <label class="control-label">বিভাগ </label>
                                                            <?php
                                                            // echo $this->tag->select(array(
                                                            // "division",
                                                            // $division,
                                                            // "using" => array("divid", "divname"),
                                                            // 'useEmpty' => true,
                                                            // 'emptyText' => 'বিভাগ বাছাই করুন',
                                                            // 'emptyValue' => '',
                                                            // 'style' => 'width:100%',
                                                            // 'class' => "input",
                                                            // 'onchange' => "showZilla(this.value,'zilla')"
                                                            // ))
                                                            ?>
                                                            <label for="fruits" class="error"></label>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                            <label class="control-label">জেলা </label>
                                                            <?php
                                                            // echo $this->tag->select(array(
                                                            // "zilla",
                                                            // $zilla,
                                                            // "using" => array("zillaid", "zillaname"),
                                                            // 'useEmpty' => true,
                                                            // 'emptyText' => 'জেলা বাছাই করুন',
                                                            // 'emptyValue' => '',
                                                            // 'class' => "input",
                                                            // 'style' => 'width:100%',
                                                            // 'onchange'=>"showupozilladiv()"
                                                            // ))
                                                            ?>
                                                            <label for="fruits" class="error"></label>
                                                        </div>
                                                    </div> -->

                                <?php //}
                                ?>

                                <div class="check-local clearfix m-bottom-10">
                                    <div class="col-xs-12 d-inline-block  check-upazila">
                                        <label class="btn btn-sm btn-default active  ">
                                            <input class="custom_radio" type="radio" id="locationtype" name="formtype"
                                                value="1" checked="checked" onclick="showupozilladiv()"
                                                style="float: left;" /> <span class="place-label"
                                                style="font-size: 12px; color:black">উপজেলা</span>
                                        </label>
                                    </div>
                                    <div class="col-xs-12 d-inline-block check-city">
                                        <label class="btn btn-sm btn-default active  ">
                                            <input class="custom_radio" type="radio" id="locationtype" name="formtype"
                                                value="2" onclick="showcitycorporationdiv()" style="float: left;" />
                                            <span class="place-label" style="font-size: 12px; color:black">সিটি
                                                কর্পোরেশন</span>
                                        </label>
                                    </div>
                                    <div class="col-xs-12 d-inline-block check-metro">
                                        <label class="btn btn-sm btn-default active ">
                                            <input class="custom_radio" type="radio" id="locationtype" name="formtype"
                                                value="3" onclick="showmetropolotandiv()" style="float: left;" /> <span
                                                class="place-label" style="font-size: 12px; color:black">মেট্রোপলিটন</span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <div class="clearfix">
                                        <div id="upoziladiv" style="display: none; padding: 0%;"
                                            class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label class="control-label"><span style="color:#FF0000"></span>
                                                    উপজেলা</label>
                                                <select name="upazila" class="input  select2-offscreen" style="width:100%;"
                                                    id="upazila">
                                                    <option value="">{{ __('বাছাই করুন...') }}</option>
                                                    @foreach ($upazila as $item)
                                                        <option value="{{ $item->id }}">{{ $item->upazila_name_bn }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div id="citycorporationdiv" style="display: none; padding: 0%;"
                                            class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label class="control-label"><span style="color:#FF0000"></span> সিটি
                                                    কর্পোরেশন
                                                </label>
                                                <select name="GeoCityCorporations" id="GeoCityCorporations"
                                                    class="input select2-offscreen" style="width:100%">
                                                    <option value="">{{ __('বাছাই করুন...') }}</option>

                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="clearfix" id="metropolitandiv" style="display: none; padding: 0%;">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label class="control-label"><span
                                                        style="color:#FF0000"></span>মেট্রোপলিটন
                                                </label>
                                                <select name="GeoMetropolitan" id="GeoMetropolitan"
                                                    class="input select2-offscreen " style="width:100%"
                                                    onchange="showThanas(this.value, 'GeoThanas')">
                                                    <option value="">{{ __('বাছাই করুন...') }}</option>

                                                </select>
                                            </div>
                                            <!-- form-group -->
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label class="control-label"><span style="color:#FF0000"></span>থানা
                                                </label>
                                                <select name="GeoThanas" class="input select2-offscreen" id="GeoThanas"
                                                    style="width:100%">
                                                    <option value="">{{ __('বাছাই করুন...') }}</option>

                                                </select>
                                            </div>
                                            <!-- form-group -->
                                        </div>
                                    </div>

                                    <div class="clearfix m-bottom-15">
                                        <div class="col-xs-12 col-sm-6 col-md-12 col-lg-12" style="padding: 0%;">
                                            <div class="form-group">
                                                <label class="control-label">প্রথম তারিখ</label>
                                                <input class="input-sm form-control" style="width: 100%" name="startdate"
                                                    id="startdate" value="" type="text" />

                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-12 col-lg-12" style="padding: 0%;">
                                            <div class="form-group">
                                                <label class="control-label">শেষ তারিখ</label>
                                                <input class="input-sm form-control" style="width: 100%" name="enddate"
                                                    id="enddate" value="" type="text" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="clearfix">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0%;">
                                            <div class="form-group">
                                                <label class="control-label">গ্রাফ নির্বাচন করুন </label>
                                                <select class="form-control" name="graph" id="graph"
                                                    style="width: 100%; font-size: 12px !important;">
                                                    <option value="">বাছাই করুন...</option>
                                                    <option value="1">অপরাধের তথ্য</option>
                                                    <option value="2">স্থান ভিত্তিক মামলা</option>
                                                    <option value="3">জরিমানা</option>
                                                    <option value="4">মামলার ধরন ও আইন</option>
                                                </select>
                                                <label for="fruits" class="error"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                            </div><!-- panel-body -->

                            <div class="panel-footer">
                                <button class="btn btn-medium btn-primary" type="submit" id="saveComplain"
                                    onclick="dashboard.searchGraphInformation()">গ্রাফ অনুসন্ধান
                                </button>
                            </div><!-- panel-footer -->
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-4" id="dcofficeinfo">
                        <div class="panel panel-warning-alt">
                            <div class="panel-heading panel-heading-dashboard">
                                <h3 class="panel-title-dashboard">
                                    মামলার পরিসংখ্যান
                                </h3>
                            </div>
                            <div id="case_statistics" class="loading-div">
                                <div class="panel-body cpv cpv-fixed-height padding15">
                                    <p id="block2_label"></p>
                                    <div class="list-group m-bottom-0">
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-gavel icon-lg text-danger mr-2"></i> মোট পরিচালিত কোর্ট</span>
                                            <span class="badge label label-danger font-weight-bold" id="executed_court_dc">0</span>
                                        </div>
                    
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-gavel icon-lg text-danger mr-2"></i> মোট মামলার সংখ্যা</span>
                                            <span class="badge label label-danger font-weight-bold" id="no_case_dc">0</span>
                                        </div>
                    
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-gavel icon-lg text-danger mr-2"></i> আদায়কৃত অর্থ</span>
                                            <span class="badge label label-danger font-weight-bold" id="fine_dc">0</span>
                                        </div>
                    
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-gavel icon-lg text-danger mr-2"></i> মোট আসামির সংখ্যা</span>
                                            <span class="badge label label-danger font-weight-bold" id="criminal_no_dc">0</span>
                                        </div>
                    
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-gavel icon-lg text-danger mr-2"></i> কারাদণ্ড প্রাপ্ত আসামির সংখ্যা</span>
                                            <span class="badge label label-danger font-weight-bold" id="jail_criminal_no_dc">0</span>
                                        </div>
                    
                                        <?php if ($roleID == '37' or $roleID == '38') { ?>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <a id="linkShowMagistrateList" href="<?php echo url('dashboard/showMagistrateList'); ?>">
                                                    <i class="fas fa-gavel icon-lg text-danger mr-2"></i> এক্সিকিউটিভ ম্যাজিস্ট্রেটের সংখ্যা
                                                </a>
                                                <span class="badge label label-danger font-weight-bold" id="no_magistrate">0</span>
                                            </div>
                                        <?php } else { ?>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-gavel icon-lg text-danger mr-2"></i> এক্সিকিউটিভ ম্যাজিস্ট্রেটের সংখ্যা</span>
                                                <span class="badge label label-danger font-weight-bold" id="no_magistrate">0</span>
                                            </div>
                                        <?php } ?>
                    
                                        <?php if ($roleID == '37' or $roleID == '38') { ?>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <a id="linkShowProsecutorList" href="<?php echo url('dashboard/showProsecutorList'); ?>">
                                                    <i class="fas fa-gavel icon-lg text-danger mr-2"></i> মোট প্রসিকিউটরের সংখ্যা
                                                </a>
                                                <span class="badge label label-danger font-weight-bold" id="no_prosecutor">0</span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer" style="margin-top: 10px">
                                <input class="btn btn-medium btn-primary" value="পরিসংখ্যান অনুসন্ধান" onclick="dashboard.caseStatisticsBlock()" type="button" id="btn-upz" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="panel  panel-danger-alt">
                            <div class="panel-heading panel-heading-dashboard ">
                                <h3 class="panel-title-dashboard">
                                    অপরাধের তথ্য
                                </h3>
                            </div>
                            <div id="criminal_info" class="loading-div">
                                <!-- panel-heading -->
                                <div class="panel-body cpv cpv-fixed-height padding15">
                                    <p id="block3_label"></p>
                                    <div class="list-group m-bottom-0">
                                        <div class="list-group-item">

                                            মোট অপরাধের তথ্য
                                            <span class="badge  label  label-danger font-weight-bold"
                                                id="total">0</span>
                                        </div>

                                        <a id="acceptedComplain" href="<?php url('dashboard/showAcceptedComplain'); ?>" class="list-group-item">

                                            গ্রহণকৃত অভিযোগ সংখ্যা
                                            <span class="badge  label  label-danger font-weight-bold"
                                                id="accepted">0</span>
                                        </a>
                                        <a id="ignoreComplain" href="<?php url('dashboard/showIgnoreComplain'); ?>" class="list-group-item">

                                            বাতিলকৃত অভিযোগ সংখ্যা
                                            <span class="badge  label  label-danger font-weight-bold"
                                                id="ignore">0</span>
                                        </a>
                                        <a id="pendingComplain" href="<?php url('dashboard/showPendingComplain'); ?>" class="list-group-item">

                                            অপেক্ষমান অভিযোগের সংখ্যা
                                            <span class="badge  label  label-danger font-weight-bold"
                                                id="unchange">0</span>
                                        </a>

                                    </div>
                                </div>
                                <!-- panel-body cpv -->
                            </div>
                            <div class="panel-footer" style="margin-top: 10px">
                                <input class="btn btn-medium btn-primary" value="পরিসংখ্যান অনুসন্ধান" type="button"
                                    onclick="dashboard.citizenComplainStatisticsBlock()" id="btn-zilla-c" />
                            </div>
                            <!-- panel-footer -->
                        </div>
                        <!-- panel -->
                    </div>
                </div>

                <div class="mt-5">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="panel panel-default">
                                <div id="fine_loading" class="loading-div">
                                    <div class="panel-heading panel-heading-dashboard">
                                        <h3 class="panel-title-dashboard header-align">
                                            জরিমানা: <span class="label_design chartSubText" style="font-size: 21px"
                                                id="fine_label"></span>
                                        </h3>
                                    </div>
                                    <div class="panel-body cpv padding15">
                                        <div id="hero-bar-fine" style="width:100%;height:200px"></div>
                                    </div>
                                    <div class="panel-footer panel-footer-thin">
                                        <div class="col-sm-12 ">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix m-bottom-15 visible-xs-block visible-sm-block"></div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 p-bottom-10">
                            <div class="panel panel-default">
                                <div id="case_loc_loading" class="loading-div">
                                    <div class="panel-heading panel-heading-dashboard">
                                        <h3 class="panel-title-dashboard header-align">
                                            স্থানভিত্তিক মামলার তথ্য: <span class="label_design chartSubText"
                                                style="font-size: 21px" id="case_label"></span>
                                        </h3>
                                    </div>
                                    <div class="panel-body cpv padding15">
                                        <div id="basicFlotLegend" class="flotLegend1">
                                            <div id="hero-bar-location" style="width:100%;height:200px"></div>
                                        </div>
                                    </div>
                                    <div class="panel-footer panel-footer-thin">
                                        <div class="col-sm-12 ">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix m-bottom-15 visible-xs-block visible-sm-block"></div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="panel panel-default">
                                <div id="citizen_complain_graph_loading" class="loading-div">
                                    <div class="panel-heading panel-heading-dashboard">
                                        <h3 class="panel-title-dashboard">
                                            অপরাধের তথ্য: <span style="font-size: 21px" class="label_design chartSubText "
                                                id="crime_label"></span>
                                        </h3>
                                    </div>
                                    <div class="panel-body cpv padding15">
                                        <div id="hero-bar-citizen" style="width:100%;height:200px"></div>
                                    </div>
                                    <div class="panel-footer panel-footer-thin">
                                        <div class="col-sm-12 ">
                                        </div>
                                    </div>
                                </div>
                                <!-- panel-footer -->
                            </div>
                        </div>
                        <div class="clearfix m-bottom-15 visible-xs-block visible-sm-block"></div>
                        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                            <div class="panel panel-default">
                                <div id="case_type_graph_loading" class="loading-div">
                                    <div class="panel-heading panel-heading-dashboard">
                                        <h3 class="panel-title-dashboard">
                                            অপরাধের ধরন অনুসারে মামলার তথ্য: <span class="label_design chartSubText"
                                                style="font-size: 21px" id="law_label"></span>
                                        </h3>
                                    </div>
                                    <div class="panel-body cpv padding15">
                                        <div id="hero-bar-crimetype" style="width:100%;height:200px"></div>
                                    </div>
                                    <div class="panel-footer panel-footer-thin">
                                        <div class="col-sm-12 ">
                                        </div>
                                    </div>
                                </div>
                                <!-- panel-footer -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <input id="user_prfoile" value="{{ $roleID }}" type="hidden">
                    <div class="col-sm-12" id="tab">
                        <table class="collaptable table table-bordered table-hover">
                            <thead style="background-color: #1b8f1b; color: white;">
                                <tr>
                                    <th class="centertext" style="width: 10%" ROWSPAN=3>
                                        <?php
                                        if ($roleID == '37' or $roleID == '38') {
                                            echo 'উপজেলা';
                                        } elseif ($roleID == 'Divisional Commissioner') {
                                            echo 'জেলা';
                                        } elseif ($roleID == 'JS') {
                                            echo 'বিভাগ';
                                        }
                                        ?>
                                    </th>
                                    <th class="centertext" colspan="2" ROWSPAN=2 style="width: 10%">মোবাইল কোর্টের
                                        সংখ্যা</th>
                                    <th class="centertext" colspan="2" ROWSPAN=2 style="width: 10%">মামলার সংখ্যা</th>
                                    <th class="centertext" colspan="2" ROWSPAN=2 style="width: 18%">আদায়কৃত অর্থ
                                        (টাকায়)</th>
                                    <th class="centertext" colspan="4" style="width: 30%">আসামির সংখ্যা</th>
                                </tr>
                                <tr>
                                    <th class="centertext" colspan="2" style="width: 10%">মোট</th>
                                    <th class="centertext" colspan="2" style="width: 10%">কারাদণ্ড প্রাপ্ত</th>
                                </tr>
                                <tr>
                                    <th class="centertext" style="width: 5%">বর্তমান মাস</th>
                                    <th class="centertext" style="width: 5%">পূর্বের মাস</th>
                                    <th class="centertext" style="width: 5%">বর্তমান মাস</th>
                                    <th class="centertext" style="width: 5%">পূর্বের মাস</th>
                                    <th class="centertext" style="width: 9%">বর্তমান মাস</th>
                                    <th class="centertext" style="width: 9%">পূর্বের মাস</th>
                                    <th class="centertext" style="width: 5%">বর্তমান মাস</th>
                                    <th class="centertext" style="width: 5%">পূর্বের মাস</th>
                                    <th class="centertext" style="width: 5%">বর্তমান মাস</th>
                                    <th class="centertext" style="width: 5%">পূর্বের মাস</th>
                                </tr>
                            </thead>
                            <tbody id="tbdMonthlyReport"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



{{-- Scripts Section Related Page --}}
@section('scripts')
    <!-- <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

                                        <script src="https://code.highcharts.com/highcharts.js"></script>
                                        <script src="https://code.highcharts.com/modules/data.js"></script>
                                        <script src="https://code.highcharts.com/modules/drilldown.js"></script>
                                        <script src="https://code.highcharts.com/modules/exporting.js"></script>
                                        <script src="https://code.highcharts.com/modules/export-data.js"></script>
                                        <script src="https://code.highcharts.com/modules/accessibility.js"></script>
                                        <script src="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
                                        <script src="{{ asset('js/pages/widgets.js') }}"></script> -->
    <script>
        $('.Count').each(function() {
            var en2bnnumbers = {
                0: '০',
                1: '১',
                2: '২',
                3: '৩',
                4: '৪',
                5: '৫',
                6: '৬',
                7: '৭',
                8: '৮',
                9: '৯'
            };
            var bn2ennumbers = {
                '০': 0,
                '১': 1,
                '২': 2,
                '৩': 3,
                '৪': 4,
                '৫': 5,
                '৬': 6,
                '৭': 7,
                '৮': 8,
                '৯': 9
            };

            function replaceEn2BnNumbers(input) {
                var output = [];
                for (var i = 0; i < input.length; ++i) {
                    if (en2bnnumbers.hasOwnProperty(input[i])) {
                        output.push(en2bnnumbers[input[i]]);
                    } else {
                        output.push(input[i]);
                    }
                }
                return output.join('');
            }

            function replaceBn2EnNumbers(input) {
                var output = [];
                for (var i = 0; i < input.length; ++i) {
                    if (bn2ennumbers.hasOwnProperty(input[i])) {
                        output.push(bn2ennumbers[input[i]]);
                    } else {
                        output.push(input[i]);
                    }
                }
                return output.join('');
            }
            var $this = $(this);
            var nubmer = replaceBn2EnNumbers($this.text());
            jQuery({
                Counter: 0
            }).animate({
                Counter: nubmer
            }, {
                duration: 2000,
                easing: 'swing',
                step: function() {
                    var nn = Math.ceil(this.Counter).toString();
                    // console.log(replaceEn2BnNumbers(nn));
                    $this.text(replaceEn2BnNumbers(nn));
                }
            });
        });
    </script>

    <!-- Include the necessary libraries -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.7/raphael.min.js"></script> -->

    <script>
        var citizen_fine_graph = {
            init: function(search_data) {

                $('#fine_loading').show();
                $('#hero-bar-fine').html('');

                var citizen_fine = Morris.Bar({
                    element: 'hero-bar-fine',
                    data: [{
                        location: 'Sample',
                        jorimana: 0
                    }],
                    xkey: 'location',
                    ykeys: ['jorimana'],
                    labels: ['জরিমানা'],
                    resize: true,
                    stacked: true,
                    barRatio: 0.6,
                    xLabelMargin: 0.5,
                    hideHover: 'auto',
                    barColors: ["#b062a4"],
                    xLabelAngle: 45
                });


                citizen_fine_graph.graphFineVSCase(citizen_fine, search_data);
            },
            graphFineVSCase: function(citizen_fine, search_data) {
                var response = [
                    [{
                            location: "Dhaka",
                            jorimana: 50
                        },
                        {
                            location: "Chittagong",
                            jorimana: 30
                        }
                    ],
                    {
                        start_date: "2024-01-01",
                        end_date: "2024-12-31"
                    },
                    "2024"
                ];

                $('#fine_loading').hide();

                citizen_fine.setData(response[0]);
                $("#fine_label").text(' ' + response[2] + '- (' + response[1].start_date + ' → ' + response[1]
                    .end_date + ')');
            }
        };

        $(document).ready(function() {
            var search_data = {};
            citizen_fine_graph.init(search_data);
        });
    </script>
    <script src="{{ asset('/mobile_court/javascripts/lib/custom_c.js') }}"></script>
    <script src="{{ asset('/mobile_court/javascripts/source/dashboard/monthly-report.js') }}"></script>
    <script src="{{ asset('/mobile_court/js/jquery.alerts.js') }}"></script>
    <script src="{{ asset('/mobile_court/js/jquery.aCollapTable.js') }}"></script>
    <script src="{{ asset('/mobile_court/js/jquery-ui-1.11.0.min.js') }}"></script>
    <script src="{{ asset('/mobile_court/javascripts/source/dashboard/dashboard.js') }}"></script>
    <script src="{{ asset('/mobile_court/javascripts/source/dashboard/dashboard_statistics_citizen_complain.js') }}">
    </script>
    <script src="{{ asset('/mobile_court/javascripts/source/dashboard/dashboard_statistics_case.js') }}"></script>
    <script src="{{ asset('/mobile_court/js/select2.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <!-- <script src="{{ asset('/mobile_court/cssmc/morris/morris.js') }}"></script> -->
    <!-- <script src="{{ asset('vendors/raphael-min.js') }}"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.7/raphael.min.js"></script>
    <script src="{{ asset('/mobile_court/javascripts/source/dashboard/citizen_complain.js') }}"></script>
    <script src="{{ asset('/mobile_court/javascripts/source/dashboard/citizen_fine_graph.js') }}"></script>
    <script src="{{ asset('/mobile_court/javascripts/source/dashboard/location_vs_case.js') }}"></script>
    <script src="{{ asset('/mobile_court/javascripts/source/dashboard/law_vs_case.js') }}"></script>
    <script src="{{ asset('/mobile_court/javascripts/source/dashboard/loading_plugin/loading.js') }}"></script>
@endsection
