@extends('layout.app')
@section('content')
    <style>
        body {
            padding: 10px;
            background: #e5f3d4;

        }
    </style>
    <form action="/prosecution/saveCriminalConfessionSuomotu" id="confessionform" name="confessionform" method="post"
        enctype="multipart/form-data" novalidate="novalidate">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-custom">
                        <div class="panel panel-default">
                            <div class="panel-heading  smx">
                                <!-- <h2 class="panel-title">আসামি / আসামিদের  জবানবন্দি  sdfgsdfg </h2> -->
                                <h4 class="well well-sm mt-2   mb-2"
                                    style="background-color:green;color:#fff;padding: 10px;">আসামি / আসামিদের জবানবন্দি</h4>
                                <input type="hidden" id="txtProsecutionID" value="{{ $prosecutionId }}" />
                            </div>
                            <div class="card-body cpv">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="control-label" style="font-weight: bold">মামলা নম্বর: </label>
                                            <span class="case_no"
                                                style="border: 1px solid;border-color: #ddd; padding: 8px"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label"
                                                style="background-color: green; padding: 10px; color: white">এক্সিকিউটিভ
                                                ম্যাজিস্ট্রেটের বিবরণ</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" style="font-weight: bold">নাম</label>
                                            <input id="magistrateName" name="magistrateName" class="input form-control"
                                                readonly="readonly"></input>

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" style="font-weight: bold">অফিস</label>
                                            <input id="magistrateOffice" name="magistrateOffice" class="input form-control"
                                                readonly="readonly"></input>
                                        </div>

                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" style="font-weight: bold">পদবী</label>
                                            <input id="magistrateDesignation" name="magistrateDesignation"
                                                class="input form-control" readonly="readonly"></input>

                                        </div>
                                    </div>

                                </div>
                                <div id="confessiondiv">
                                    <!-- Append body dinamicallly-->
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label" style="font-weight:bold;">জবানবন্দি ফরমের
                                                স্ক্যানকৃত ছবি সংযুক্ত করুন
                                                (যদি থাকে)</label>
                                            <div class="form-group">
                                                <div class="panel panel-danger-alt">
                                                    <div class="panel-body cpv p-5 photoContainer">
                                                        <button type="button" class="btn btn-success multifileupload">
                                                            <span>+</span>
                                                        </button>
                                                        <hr>
                                                        <div class="panel panel-danger-alt">
                                                            <div class="docs-toggles"></div>
                                                            <div class="docs-galley photoView">


                                                            </div>
                                                            <div class="docs-buttons" role="group"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel-footer">
                                <div class="pull-right float-right mr-5">
                                    <button class="btn btn-success mr5" type="button" onclick="confessionFrom.save();"><i
                                            class="glyphicon glyphicon-ok"></i> সংরক্ষণ
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('scripts')
    <script src="{{ asset('js/validation/input-validator.js') }}"></script>
    <script src="{{ asset('mobile_court/javascripts/source/content/multiFileUpload.js') }}"></script>
    <script src="{{ asset('mobile_court/javascripts/source/prosecution/confessionForm.js') }}"></script>
    <script src="{{ asset('mobile_court/javascripts/source/prosecution/criminalConfessionWithProsecutor.js') }}"></script>
    <script src="{{ asset('mobile_court/javascripts/source/prosecution/complaintInfoForm.js') }}"></script>
@endsection
