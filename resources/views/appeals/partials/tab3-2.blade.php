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

    .thumbnail>.img-label {
        position: absolute;
        top: 0;
        left: 0;
        z-index: 1;
        font-size: 0.8em;
        width: 100%;
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

    /* .row img {
    border: 1px solid #336699;
    border-radius: 15px;
    box-shadow: none;
    width: 80%;
} */
</style>
<form action="/prosecution/createProsecution" id="suomotcourtform" name="suomotcourtform" method="post"
    enctype="multipart/form-data">
    <div class="form-group" style="margin: 20px 0px 20px 0">
        <h4 class="well well-sm mt-2">অভিযোগ গঠন</h4>
        <input type="hidden" class="selectMagistrateCourtId" value="{{ $selectMagistrateCourtId }}" />
    </div>
    {{--  <div class="form-group">
        <h4 class="well well-sm mt-10 text-center" style="background-color:#085F00;color:#fff;padding: 10px;">স্থান ও সময়
        </h4>
    </div> --}}<!-- form-group -->
    <div class="form-group">
        <h4 class="well well-sm text-center" style="background-color:green;color:#fff;padding: 10px;">
            স্থান ও সময়
        </h4>
    </div>
    <div class="row" id="withProsecutor">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label"><span style="color:#FF0000">*</span>ঘটনার তারিখ</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    <input class="suodate input required form-control hasDatepicker" name="suodate" id="suodate"
                        value="" type="text">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label"><span style="color:#FF0000">*</span>ঘটনার সময়</label>
                <div class="">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                    <div class="bootstrap-timepicker">
                        <input name="time" id="suo_timepickersuomoto" type="text"
                            class="suo_timepickersuomoto input form-control required">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="withOutProsecutor">
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label"><span style="color:#FF0000">*</span>ঘটনার তারিখ</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    <input class="suodate input required form-control" name="suodate" id="suodate" value=""
                        type="text">
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label"><span style="color:#FF0000">*</span>ঘটনার সময়</label>
                <div class="input-group mb15">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                    <div class="bootstrap-timepicker">
                        <input name="time" id="suo_timepickersuomoto" type="text"
                            class="suo_timepickersuomoto input form-control required">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label"><span style="color:#FF0000">*</span> মামলা নম্বর</label>
                <input type="text" value='' readonly="" id="case_no" name="case_no"
                    class="input form-control required">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label style="font-size: medium" class="control-label"><span style="color:#ffffff"></span> মামলা নম্বরের
                    সিরিয়াল (প্রযোজ্য হলে)</label>
                <input placeholder="ম্যনুয়াল মামলার সিরিয়াল নম্বর" title=""
                    onchange="complaintForm.setCaseNumberValue();" type="text" id="case_no_sr" name="case_no_sr"
                    class="input form-control"
                    data-original-title="(xxxx.xx.xxxxx.সিরিয়াল নম্বর.xx) ম্যনুয়াল মামলার সিরিয়াল নম্বরটি দিন। এটি চার সংখ্যার হতে হবে। প্রয়োজনে নম্বরের শুরুতে শূন্য দিন।">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label class="control-label"><span style="color:#FF0000">*</span>বিভাগ</label>
                <select class="required  selectDropdown form-control" name="division" id="ddlDivision999">
                </select>
            </div>
            <!-- form-group -->
        </div>
        <!-- col-sm-6 -->

        <div class="col-sm-6">
            <div class="form-group">
                <label class="control-label"><span style="color:#FF0000">*</span> জেলা</label>
                <select class="required selectDropdown form-control" id="ddlZilla999" name="zilla"
                    required = "true">

                </select>

            </div>
            <!-- form-group -->
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <div id="locationTypeTable_1" class="radiorequired">
                    <label class="btn btn-default active">
                        <input class="optLocationType999" type="radio" name="locationtype" id="upazillatype_1"
                            value="UPAZILLA" checked="checked">
                        <small style="margin-left: 5px; font-size: 16px">উপজেলা</small>

                    </label>
                    <label class="btn btn-default active">
                        <input class="optLocationType999" type="radio" name="locationtype" id="citytype_1"
                            value="CITYCORPORATION">
                        <small style="margin-left: 5px; font-size: 16px">সিটি কর্পোরেশন</small>
                    </label>
                    <label class="btn btn-default active">
                        <input class="optLocationType999" type="radio" name="locationtype" id="metrotype_1"
                            value="METROPOLITAN">
                        <small style="margin-left: 5px; font-size: 16px">মেট্রোপলিটন</small>
                    </label>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group">

                <select class=" required selectDropdown form-control" name="upazilla" id="ddlUpazilla999">
                    <option>বাছাই করুন...</option>
                </select>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <select class="form-control" name="GeoThanas" id="ddlThana999" required = "true">
                    <option>বাছাই করুন...</option>
                </select>
            </div>
        </div>

    </div>


    <div class="row  ">
        <div class="col-sm-6">
            <div class="form-group">
                <label class="control-label"><span style="color:#FF0000">*</span>ঘটনাস্থল</label>

            </div>
            <!-- form-group -->
        </div>
        <div class="col-sm-6">
            <div class="form-group">

                <input type="text" id="location" name="location" class="input form-control required">
            </div>
            <!-- form-group -->
        </div>
    </div>

    {{-- <div class="form-group">
        <h4 class="well well-sm text-center " style="background-color:#085F00;color:#fff;padding: 10px;">লঙ্ঘিত আইন ও
            ধারা </h4>
    </div> --}}
    <div class="form-group">
        <h4 class="well well-sm text-center" style="background-color:green;color:#fff;padding: 10px;">
            লঙ্ঘিত আইন ও ধারা
        </h4>
    </div>
    <div class="form-group criminal_laws" id="c-lawdiv_1">
        <div class="form-group">
            <label class="col-sm-2"><span style="color:#FF0000">*</span>আইন</label>
            <div class="col-sm-10">
                <select class="required selectDropdown form-control" name="brokenLaws[1][law_id]" id="ddlLaw1">
                    <option value="">বাছাই করুন...</option>

                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2"><span style="color:#FF0000">*</span>ধারা</label>
            <div class="col-sm-10">
                <select class="required selectDropdown form-control" name="brokenLaws[1][section_id]"
                    id="ddlSection1">
                    <!-- <option value="">বাছাই করুন...</option> -->
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2"><span style="color:#FF0000"></span>শাস্তির ধারা</label>
            <div class="col-sm-10">
                <textarea id="txtPunishmentDesc1" name="brokenLaws[1][section_description]" class="input form-control"
                    cols="50" rows="1" readonly="readonly"></textarea>
            </div>
        </div><!-- form-group -->
        <div class="form-group">
            <label class="col-sm-2"><span style="color:#FF0000">*</span>অপরাধের বিবরণ </label>
            <div class="col-sm-10">
                <textarea id="txtCrimeDesc1" name="brokenLaws[1][crime_description]" class="input form-control required"
                    cols="50" rows="4"></textarea>
            </div>
        </div><!-- form-group -->

        <div class="form-group">
            <div class="col-sm-2">
                <button type="button" class="btn btn-small btn-primary" id="c_a_button_1" name="c-L"
                    onclick="complaintForm.addNewLaw(true);">
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

    {{-- <div class="form-group">
        <h4 class="well well-sm " style="background-color:#085F00;color:#fff;padding: 10px;">অপরাধের ধরন</h4>
    </div> --}}
    <div class="form-group">
        <h4 class="well well-sm text-center" style="background-color:green;color:#fff;padding: 10px;">
            অপরাধের ধরন
        </h4>
    </div>
    <div class="form-group row">
        <label class="col-sm-2"><span style="color:#FF0000">*</span>অপরাধের ধরন ১ </label>
        <div class="col-sm-3">

            <select id="case_type1" name="case_type1" class="input required  selectDropdown form-control"
                usedummy="1">
                <option value="">বাছাই করুন...</option>


                @if (!empty($case_type))
                    @foreach ($case_type as $case_type1)
                        <option value="{{ $case_type1->id }}">{{ $case_type1->name }}</option>
                    @endforeach
                @endif

            </select>
        </div>
        <label class="col-sm-2"><span style="color:#FF0000"></span>অপরাধের ধরন ২</label>
        <div class="col-sm-3">
            <select id="case_type2" name="case_type2" class="input selectDropdown  form-control" usedummy="1">
                <option value="">বাছাই করুন...</option>
                @if (!empty($case_type))
                    @foreach ($case_type as $case_type1)
                        <option value="{{ $case_type1->id }}">{{ $case_type1->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div><!-- form-group -->

    <div class="form-group">
        <label class="col-sm-2">সূত্র </label>
        <div class="col-sm-10">
            <input type="text" id="hints" name="hints" class="input form-control">
        </div>
    </div><!-- form-group -->

    <!-- <div class="form-group">
        <h4  class="well well-sm " style="background-color:#085F00;color:#fff;padding: 10px;">সংযুক্তি</h4>
        <label class="control-label">অভিযোগ গঠন ফরমের স্ক্যানকৃত ছবি সংযুক্ত করুন (যদি থাকে)</label>
    </div>
    <div class="row">
        <div class="form-group">
            <div class="panel panel-danger-alt">
                <div class="panel-body   p-5 photoContainer">
                    <button type="button" class="btn btn-success multifileupload">
                        <span>+</span>
                    </button>
                    <hr>
          
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
                        <th style="width: 45%;">অভিযোগ গঠন ফরমের স্ক্যানকৃত ছবি সংযুক্ত করুন (যদি
                            থাকে)</th>
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

    <div class="panel-footer" style="padding: 20px">
        <div class="pull-right float-right">
            <button class="btn btn-success " type="button" onclick="complaintForm.save()"><i
                    class="glyphicon glyphicon-ok"></i> সংরক্ষণ
            </button>
        </div>
    </div>
</form>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
