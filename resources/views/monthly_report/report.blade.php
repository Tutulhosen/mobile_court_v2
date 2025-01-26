@extends('layout.app')

@section('content')

@push('head')

<link rel="stylesheet" type="text/css" href="{{ asset('/mobile_court/cssmc/bootstrap-timepicker.min.css')}}" />
<!-- <link rel="stylesheet" type="text/css" href="{{ asset('/mobile_court/cssmc/jquery-ui-1.10.3.css')}}" /> -->
<link href="{{ asset('mobile_court/cssmc/select2.css') }}" rel="stylesheet">

@endpush
  
 <style>
    label {
        /* background-color: #4c81db; */

        background-color: #2a7916;
        width: 100%;
        color: #fff;
        padding: 5px;

    }
</style>
 
<!-- <div class="mainwrapper"> -->
    <div class="contentpanel">
        <div class="divSpace"></div>
        <div class="card panel-default">
            <div class="card-body cpv p-0">
                <div class="clearfix" >
                        <div class="panel panel-success-alt">
                            <div class="card-header panel-heading-dashboard">
                                <h3 class="panel-title-dashboard">
                                    অনুসন্ধানের উপাত্তসমূহ
                                </h3>
                            </div>
                            <!-- panel-heading -->
                            <div class="card-body cpv p-15">
                                <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="clearfix">
                                        <label class="control-label">প্রতিবেদনের ধরন </label>
                                        <div class="form-group">
                                            <select id="reportType" name="reportType" class="input" style="width: 100% !important;" useDummy="1" onChange="reportList.getReportList(this.value)">
                                                <option value="">প্রতিবেদনের বিষয় নির্বাচন করুন...</option>
                                                <!-- {% set profile = auth.getProfile() %} -->
                                                <!-- {% if 'JS' == profile %}
                                                    <option value="1">সামগ্রিক প্রতিবেদন  </option>
                                                    <option value="2">বিভাগভিত্তিক প্রতিবেদন</option>
                                                {% endif %} -->
                                                <option value="3">জেলাভিত্তিক প্রতিবেদন</option>
                                                <option value="4">গ্রাফ (বিভাগভিত্তিক তথ্য)</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="clearfix">
                                        <label class="control-label">প্রতিবেদনের নাম </label>
                                        <div class="form-group">
                                            <select id="reportList" name="reportList" class="input"  style="width: 100% !important;">
                                                <option value="">প্রতিবেদনের নাম নির্বাচন করুন...</option>
                                            </select>
                                        </div>
                                        <div id="commentChk" class="form-group">
                                            <ul class="d-inline-flex list-inline m-0 pull-left">
                                                <li><input type="radio" name="optradio" value="1" class="m-2" checked />মন্তব্য ছাড়া </li>
                                                <li><input type="radio" name="optradio" value="2" class="m-2" />মন্তব্যসহ</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" style="color:#fff">বিবেচ্য মাস </label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                            <input type="text" class="form-control" placeholder="মাস  নির্বাচন করুন ..." id="report_date" name="report_date" required="true"/>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary pull-left" type="submit"  onclick="reportSelection()">অনুসন্ধান </button>
                                </div>

                                <div id="previousDate" class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" style="color:#fff">পূর্ববর্তী মাস</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                            <input type="text" class="form-control" placeholder="মাস  নির্বাচন করুন ..."  id="report_date2" name="report_date2" />
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>

                            <!-- panel-body cpv -->
                    </div>
                <div id="displaydiv"></div>
            </div>
        </div>
    </div>
    </div>
<!-- </div> -->
 


<!-- CountryWise Report -->
<div id="printcountrymobilecourtreport" style="display: none; ">
    
    @include('monthly_report.printcountrymobilecourtreport')
 
</div>
<div id="printcountryothersreport" style="display: none; ">
      @include('monthly_report.printcountryothersreport')  
</div>

{{-- #end# --}}


{{-- #Mobile Court# --}}
<div id="printmobilecourtreport" style="display: none; ">
    {{-- partial("monthly_report/partials/district/MobileCourt/printmobilecourtreport") --}}
    @include('monthly_report.partials.district.MobileCourt.printmobilecourtreport')
</div>
<div id="printmobilecourtreportWithComment" style="display: none; ">
    @include('monthly_report.partials.district.MobileCourt.printmobilecourtreportWithComment')
    {{-- partial("monthly_report/partials/district/MobileCourt/printmobilecourtreportWithComment") --}}
</div>


{{-- #Appeal# --}}
<div id="printappealcasereport" style="display: none; ">
    {{-- partial("monthly_report/partials/district/Appeal/printappealcasereport") --}}
    @include('monthly_report.partials.district.Appeal.printappealcasereport')
</div>
<div id="printappealcasereportWithComment" style="display: none; ">
    {{-- partial("monthly_report/partials/district/Appeal/printappealcasereportWithComment") --}}
    @include('monthly_report.partials.district.Appeal.printappealcasereportWithComment')
</div>


{{-- #ADM# --}}
<div id="printadmcasereport" style="display: none; ">
    {{-- partial("monthly_report/partials/district/ADM/printadmcasereport") --}}
    @include('monthly_report.partials.district.ADM.printadmcasereport')
</div>
<div id="printadmcasereportWithComment" style="display: none; ">
    {{-- partial("monthly_report/partials/district/ADM/printadmcasereportWithComment") --}}
    @include('monthly_report.partials.district.ADM.printadmcasereportWithComment')
    </div>
    
</div>


{{-- #EM# --}}
<div id="printemcasereport" style="display: none; ">
    {{-- partial("monthly_report/partials/district/EM/printemcasereport") --}}
    @include('monthly_report.partials.district.EM.printemcasereport')

</div>
<div id="printemcasereportWithComment" style="display: none; ">
    {{-- parpprintemcasereportWithCommenttial("monthly_report/partials/district/EM/printemcasereportWithComment") --}}
    @include('monthly_report.partials.district.EM.printemcasereportWithComment')
</div>

 
{{-- #Visit# --}}
<div id="printcourtvisitreport" style="display: none; ">
    {{-- partial("monthly_report/partials/district/Visit/printcourtvisitreport") --}}
    @include('monthly_report.partials.district.Visit.printcourtvisitreport')

</div>
<div id="printcourtvisitreportWithComment" style="display: none; ">
    {{-- partial("monthly_report/partials/district/Visit/printcourtvisitreportWithComment") --}}
    @include('monthly_report.partials.district.Visit.printcourtvisitreportWithComment')
</div>

 
{{-- #Record# --}}
<div id="printcaserecordreport" style="display: none; ">
    {{-- partial("monthly_report/partials/district/Record/printcaserecordreport") --}}
    @include('monthly_report.partials.district.Record.printcaserecordreport')
</div>
<div id="printapprovedreportWithComment" style="display: none; ">
    {{-- partial("monthly_report/partials/district/Record/printcaserecordreportWithComment") --}}
    @include('monthly_report.partials.district.Record.printcaserecordreportWithComment')
</div>


<!-- Divisional Report -->

{{-- #Mobile Court# --}}
<div id="printdivmobilecourtreport" style="display: none; ">
    {{-- partial("monthly_report/partials/division/MobileCourt/printdivmobilecourtreport") --}}
</div>
<div id="printdivmobilecourtreportWithComment" style="display: none; ">
    {{-- partial("monthly_report/partials/division/MobileCourt/printdivmobilecourtreportWithComment") --}}
</div>


{{-- #Appeal# --}}
<div id="printdivappealcasereport" style="display: none; ">
    {{-- partial("monthly_report/partials/division/Appeal/printdivappealcasereport") --}}
</div>
<div id="printdivappealcasereportWithComment" style="display: none; ">
    {{-- partial("monthly_report/partials/division/Appeal/printdivappealcasereportWithComment") --}}
</div>


{{-- #ADM# --}}
<div id="printdivadmcasereport" style="display: none; ">
    {{-- partial("monthly_report/partials/division/Adm/printdivadmcasereport") --}}
</div>
<div id="printdivadmcasereportWithComment" style="display: none; ">
    {{-- partial("monthly_report/partials/division/Adm/printdivadmcasereportWithComment") --}}
</div>

{{-- #EM# --}}
<div id="printdivemcasereport" style="display: none; ">
    {{-- partial("monthly_report/partials/division/EM/printdivemcasereport") --}}
</div>
<div id="printdivemcasereportWithComment" style="display: none; ">
    {{-- partial("monthly_report/partials/division/EM/printdivemcasereportWithComment") --}}
</div>

<div id="printdivapprovedreport" style="display: none; ">
    {{-- partial("monthly_report/partials/printdivapprovedreport") --}}
</div>

<div id="printmobilecourtstatisticreport" style="display: none; ">
    {{-- partial("monthly_report/partials/printmobilecourtstatisticreport") --}}
    @include('monthly_report.partials.printmobilecourtstatisticreport')
    
</div>
<div id="printapprovedreport" style="display: none; ">
    {{-- partial("monthly_report/partials/printapprovedreport") --}}
    @include('monthly_report.partials.district.Approved.printapprovedreport')

</div>

<div id="messageModal">
  
    @include('message.partials.message')
    
</div>

@endsection


@section('scripts')


<script src="{{ asset('mobile_court/js/jquery.canvasjs.min.js') }}"></script>
<script src="{{ asset('mobile_court/js/select2.min.js') }}"></script>
<script src="{{ asset('mobile_court/js/bootstrap-timepicker.min.js') }}"></script>
<!-- <script src="{{ asset('mobile_court/js/jquery-ui-1.11.0.min.js') }}"></script> -->
<script src="{{ asset('mobile_court/javascripts/source/monthly_report/english_to_bangla.js') }}"></script>
<script src="{{ asset('mobile_court/javascripts/source/monthly_report/country_based_report.js') }}"></script>
<script src="{{ asset('mobile_court/javascripts/source/monthly_report/reportDropDownPopulate.js') }}"></script>
<script src="{{ asset('mobile_court/javascripts/source/report/reportscript.js') }}"></script>

@endsection