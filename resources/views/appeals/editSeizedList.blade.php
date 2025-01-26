@extends('layout.app')
@section('content')
    <style>
        body {
            padding: 10px;
            background: #e5f3d4;

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

                    <form action="/prosecution/savelist" id="seizureform" name="seizureform" method="post"
                        enctype="multipart/form-data" novalidate="novalidate">
                        <div class="panel panel-default p-5">
                            {{--  <div class="panel-heading smx">
                            <h2 class="panel-title">জব্দতালিকা </h2>
                            <input type="hidden" id="txtProsecutionID" value="{{ $prosecution_id }}" />
                            <input type="hidden" id="isSuomotu" value="{{ $is_suomotu }}" />

                        </div> --}}
                            <div class="panel-heading" style="margin: 20px 0px 20px 0">
                                <h4 class="well well-sm mt-2 "><span style="font-weight: 500">জব্দতালিকা</span>
                                    <span class="pull-right float-right">মামলা নম্বর:
                                        <span class="case_no text-primary">অভিযোগ গঠন হয়নি</span>
                                    </span>
                                </h4>
                                <input type="hidden" id="txtProsecutionID" value="{{ $prosecution_id }}" />
                                <input type="hidden" id="isSuomotu" value="{{ $is_suomotu }}" />

                            </div>
                            <div class="panel-body cpv">

                                {{-- <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group" style="background-color:#085F00;color:#fff;padding:3px;">
                                        <label class="control-label" style="color: #fff;">মামলা নম্বর </label>
                                        <span class="case_no" style="border: 1px solid;border-color: #ddd;"></span>
                                    </div>
                                </div>
                            </div> --}}
                                {{--  <div class="row">
                                    <div class="col-sm-12">
                                        <div class="well well-sm mt-2 text-center mb-2"
                                            style="background-color:#085F00;color:#fff;padding:2px;">
                                            জব্দকৃত মালামালের বর্ণনা
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="form-group">
                                    <h4 class="well well-sm text-center"
                                        style="background-color:green;color:#fff;padding: 10px;">
                                        জব্দকৃত মালামালের বর্ণনা
                                    </h4>
                                </div>

                                <div id="seizureListTable">
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <label class="control-label"> </label>
                                                <span>১</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label class="control-label">মালামালের বিবরণ </label>
                                                <input class="form-control" name="seizure[0][1]" id="seizure_0_1"
                                                    value="" type="text" required="true">
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label class="control-label">পরিমাণ/ওজন</label>
                                                <input class="form-control" name="seizure[0][2]" id="seizure_0_2"
                                                    value="" type="text" required="true">
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label class="control-label">আনুমানিক মূল্য</label>
                                                <input class="form-control" name="seizure[0][3]" id="seizure_0_3"
                                                    value="" type="text">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label">মালামালের ধরন </label>
                                                <select id="seizure_0_4" name="seizure[0][4]"
                                                    class="form-control selectDropdown required" usedummy="1">
                                                    <option value="">বাছাই করুন...</option>
                                                    @if (!empty($seizureitem_type))
                                                        @foreach ($seizureitem_type as $seizureitemtype)
                                                            <option value="{{ $seizureitemtype->id }}">
                                                                {{ $seizureitemtype->item_group }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label class="control-label">মন্তব্য </label>
                                                <input class="form-control" name="seizure[0][5]" id="seizure_0_5"
                                                    value="" type="text">
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            &nbsp;
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <input type="button" value="+"
                                                onclick="seizureForm.addSeizureListRow('seizureListTable')"
                                                class="btn btn-primary">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <div class="pull-right float-right">
                                    <button class="btn btn-success mr5" type="button" onclick="seizureForm.save();"><i
                                            class="glyphicon glyphicon-ok"></i> সংরক্ষণ
                                    </button>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{ asset('js/validation/input-validator.js') }}"></script>
    <script src="{{ asset('mobile_court/javascripts/source/prosecution/seizureListInfoForm.js') }}"></script>
@endsection
