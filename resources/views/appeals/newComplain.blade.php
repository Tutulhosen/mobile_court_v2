@extends('layout.app')

@section('content')
<style>
    .attachment-section {
        background-color: #f9f9f9;
        padding: 15px;
        border-radius: 5px;
    }

    .attachment-section h5 {
        font-weight: bold;
        color: #333;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    .chooseFileBtn {
        white-space: nowrap;
    }

    .filePreview {
        max-width: 150px;
        max-height: 150px;
        border-radius: 1px;
        box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.2);
    }

    .fileIcon {
        font-size: 1.5em;
    }

    .filePreviewContainer {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 150px;
        height: 150px;
    }
    .thumbnail {
    position: relative;
    margin: 5px;
    float: left;
    border-radius: 3px;
    box-shadow: 1px 1px 5px #ccc;
    overflow: hidden;
    border: 1px solid #ccc;
}
.img-button {
    position: absolute !important;
    z-index: 2 !important;
    right: 10px !important;
    color: #F44336;
    opacity: 1;
}
img.img-responsive.multi-image {
    max-width: 100%;
}
</style>
<div class="row">
    <div class="col-md-12" style="background-color: #f5f5f5; padding-top:15px">
        <!--begin::Card-->
        <div class="card card-custom gutter-b example example-compact">

            <form action="/prosecution/createComplain" id="creatComplainFormId" name="creatComplainFormId" method="post" enctype='application/json' enctype="multipart/form-data" >

                <div class="card-header">
                    <h3 class="card-title float-left h2 font-weight-bolder">{{ $page_title}}</h3>
                    <input type="hidden" id="txtProsecutionID" value="{{ $prosecutionId }}" />
                </div>
                <div class="card-body  cpv">

                    <div class="row">
                        <div class="col-sm-4" >
                            <div class="form-group">
                            
                                <label style="background:#008841;color: #fff;width: 100%; padding:15px; border-radius:5px;text-align:center;font-size:16px">মামলা  বিবরণ </label>
                                <span id="noCase"><strong>মামলা নম্বরঃ</strong></span><br>
                                <span id="placeCase"><strong>ঘটনাস্থালঃ</strong></span><br>
                                <span id="dateCase"><strong>তারিখঃ</strong></span>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                       
                                <label class="well well-sm" style="background:#008841;padding-left: 5px;color: #fff;width: 100%; padding:15px; border-radius:5px;text-align:center;font-size:16px">এক্সিকিউটিভ ম্যাজিস্ট্রেটের বিবরণ (নাম ও অফিস)</label>
                                <span id="NameMagistrate">নামঃ </span><br>
                                <span id="OfficeMagistrate">অফিসঃ</span>

                            </div>
                        </div>

                        <div class="col-sm-4" >
                            <div class="form-group">
                       
                                <label class="well well-sm" style="background:#008841;padding-left: 5px;color: #fff;width: 100%; padding:15px; border-radius:5px;text-align:center;font-size:16px">জব্দতালিকা</label>
                                <div id="siezureNotExistChk">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" >জব্দতালিকা নাই</label>
                                        </div>
                                    </div>
                                </div>
                                <div id="seizureExist">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label" >জব্দতালিকা দেখুন</label>
                                                <div style="float: left">
                                                <img style="cursor:pointer; margin-top: 10px;" alt=" প্রিন্ট" title=" প্রিন্ট" src="{{asset('mobile_court/images/print.png')}}" onclick="initDataFromProsecutor.printSeizureListReport();">
                                                </div>
                                            </div>
                                        </div>
                                </div>

                            </div>
                        </div>

                        

                    </div><br>
                    <div id="criminalInformation"></div><br>
                    <div class="row" id="withOutProsecutor">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><span style="color:#FF0000">*</span> মামলা নম্বর</label>
                                <input type="text" readonly id="case_no" name="case_no" class="input form-control required" required="true">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="font-size: medium" class="control-label">মামলা নম্বরের সিরিয়াল (প্রযোজ্য হলে)</label>
                                <input placeholder="ম্যনুয়াল মামলার সিরিয়াল নম্বর" title="(xxxx.xx.xxxxx.সিরিয়াল নম্বর.xx) ম্যনুয়াল মামলার সিরিয়াল নম্বরটি দিন। এটি চার সংখ্যার হতে হবে। প্রয়োজনে নম্বরের শুরুতে শূন্য দিন।"  onchange="complaintForm.setCaseNumberValue();" type="text"  id="case_no_sr" name="case_no_sr" class="input form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="well well-sm" style="background:#008841;padding-left: 5px;color: #fff;width: 100%; padding:15px; border-radius:5px;text-align:center;font-size:16px">লঙ্ঘিত আইন ও ধারা</label>
                       
                    </div>
                    <div class="form-group criminal_laws" id="c-lawdiv_1">
                        <div class="form-group">
                            <label class="col-sm-2"><span style="color:#FF0000">*</span>আইন</label>
                            <div class="col-sm-12">
                                <select   name="brokenLaws[1][law_id]" id="ddlLaw1" class="required selectDropdown form-control">
                                    <option>বাছাই করুন...</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2"><span style="color:#FF0000">*</span>ধারা</label>
                            <div class="col-sm-12">
                                <select  name="brokenLaws[1][section_id]" id="ddlSection1" class="required selectDropdown form-control">
                                    <option>বাছাই করুন...</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2"><span style="color:#FF0000"></span>শাস্তির ধারা</label>
                            <div class="col-sm-12">
                                <textarea id="txtPunishmentDesc1" name="brokenLaws[1][section_description]" class="input form-control" cols="50" rows="1" readonly="readonly"></textarea>
                            </div>
                        </div><!-- form-group -->
                        <div class="form-group">
                            <label class="col-sm-2"><span style="color:#FF0000">*</span>অপরাধের বিবরণ </label>
                            <div class="col-sm-12">
                                <textarea id="txtCrimeDesc1" name="brokenLaws[1][crime_description]" class="input form-control" cols="50" rows="4" required="true"></textarea>
                            </div>
                        </div><!-- form-group -->

                        <div class="form-group">
                            <div class="col-sm-2">
                                <button type="button" style="background:#008841;" class="btn btn-small btn-primary" id="c_a_button_1" name="c-L" onclick="complaintForm.addNewLaw(true);">
                                    <i class="glyphicon glyphicon-plus"></i>
                                    <span>আরেকটি</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2"><span style="color:#FF0000">*</span>ঘটনাটি </label>
                        <div class="col-sm-10">
                            <input class="incidentType" type="radio" name="occurrence_type" value="1" checked=""> সংঘটিত
                            <input class="incidentType" type="radio" name="occurrence_type" value="2"> উৎঘাটিত
                        </div>
                    </div><!-- form-group -->
                    <!-- <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                 
                                <label class="well well-sm" style="background:#008841;padding-left: 5px;color: #fff;width: 100%; padding:15px; border-radius:5px;text-align:center;font-size:16px">অভিযোগ গঠন ফরমের স্ক্যানকৃত ছবি সংযুক্ত করুন (যদি থাকে)</label>
                                <div class="form-group">
                                    <div class="panel panel-danger-alt">
                                        <div class="panel-body cpv photoContainer">
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
                                <div class="form-group" id="chargeFameAttachemntLable"></div>
                                <div class="panel panel-danger-alt">
                                    <div class="form-group">
                                        <div class="panel panel-danger-alt">
                                            <div class="row" id="chargeFameAttachedFile">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    
                    <div class="form-group" id="chargeFameAttachemntLable"></div>
                    <div class="panel panel-danger-alt">
                        <div class="form-group">
                            <div class="panel panel-danger-alt">
                                    <div class="row" id="chargeFameAttachedFile">
                                    </div>
                            </div>
                        </div>
                    </div>

                    <div class="attachment-section">
                        <h5 class="mb-3">সংযুক্তি</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 45%;text-align: center;">অভিযোগ গঠন ফরমের স্ক্যানকৃত ছবি সংযুক্ত করুন (যদি থাকে)</th>
                                        <th style="width: 15%; text-align: center;">
                                            <button type="button" class="btn btn-sm btn-primary addRowBtn">+</button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="attachmentTableBody">
                                    <!-- Dynamic rows will be appended here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <?php //echo $this->tag->linkTo(array("home/index", "প্রথম পাতা" ,'class' => 'btn btn-mideum btn-info')) ?>
                    <div class="pull-right float-right">
                        <button class="btn btn-success mr5" type="button" onclick="initDataFromProsecutor.save()"><i class="glyphicon glyphicon-ok"></i> সংরক্ষণ</button>
                    </div>
                </div>
        
            </form>
            
        

        </div>
    </div>
</div>


@endsection

@section('scripts')

<script src="{{ asset('js/validation/input-validator.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script  src="{{ asset('mobile_court/javascripts/source/content/multiFileUpload.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/prosecution/initDatafromProsecutor.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/prosecution/seizureListInfoForm.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/prosecution/complaintInfoForm.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/law/law.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/utils/ui-utils.js') }}" ></script>

<script  src="{{ asset('mobile_court/javascripts/source/utils/convertEngToBangla.js') }}" ></script>
<div id="sizedList" style="display: none; ">
    @include('appeals.partials.sizedListReport')
</div>
@endsection