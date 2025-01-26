<style>
.hidden {
    display: none !important;
}
</style>
<form action="/prosecution/saveOrderSheetSuomotu" id="ordersheetformsuomoto" name="ordersheetformsuomoto" method="post" enctype="multipart/form-data" novalidate="novalidate">
    <div class="panel panel-default">
        <div class="panel-heading">
            <!-- <h2 class="panel-title">আদেশ প্রদান<span class="pull-right">মামলা নম্বর: <span class="case_no text-primary">অভিযোগ গঠন হয়নি</span></span></h2> -->
            <h4 class="well well-sm mt-4">আদেশ প্রদান<span class="pull-right float-right">মামলা নম্বর: <span class="case_no text-primary">অভিযোগ গঠন হয়নি</span></span></h4>
        </div>
        <div class="panel-body cpv">

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label">আইন, ধারা ও শাস্তির বিবরণ</label>

                    </div>
                </div>
            </div>
            <div id="lawtemplete">
            </div>


            <div id="withCriminal" class="hidden">
                                                    
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"> আদেশ প্রদান করুন </label>
                        </div>
                    </div>
                </div>

                <div id="criminaltemplete">
                </div>



                <div class="row">
                    <div class="col-md-6">
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <a style="width: 100%" class="btn btn-success btn-mideum" href="#" onclick="ordersheetForm.showPunishmentSheet(3); return false"> অব্যাহতি প্রদান </a>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <a style="width: 100%" class="btn btn-danger btn-mideum" href="#" onclick="ordersheetForm.showPunishmentSheet(2); return false"> নিয়মিত  মামলায় </a>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <a style="width: 100%" class="btn btn-warning btn-mideum" href="#" onclick="ordersheetForm.showPunishmentSheet(1); return false"> শাস্তি প্রদান </a>
                        </div>
                    </div>
                </div>
                <hr>
                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"> আদেশ সমূহ </label>
                        </div>
                    </div>
                </div>

                <div id="punishmentDetailList">
                </div>

            </div>

            <div id="is_sizurelist" class="hidden">
                <div class="row" id="seizure_details">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"><span style="color:#FF0000">*</span>জব্দকৃত
                                মালামাল সম্পর্কিত আদেশ</label>
                            <?php // echo $this->tag->textArea(array("seizure_order","cols" => 50,
                            //"rows" => 4, 'class' => "input form-control" )) ?>
                            <textarea id="seizure_order" name="seizure_order" cols='50' rows="4" class="input form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div id="jimmadar">
                                <label class="control-label">দায়িত্বপ্রাপ্ত ব্যাক্তির নাম ও
                                    ঠিকানা </label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label"><span
                                                        style="color:#FF0000">*</span>নাম</label>
                                            <?php 
                                            // echo $this->
                                            // tag->textField(array("jimmader_name",'class' =>
                                            // "input form-control" ,
                                            // 'onchange' => "ordersheetForm.showseizureorder(this.value)")) 
                                            ?>
                                            <input type="text" id="jimmader_name" name="jimmader_name" class="input form-control" onchange="ordersheetForm.showseizureorder(this.value)">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label"><span
                                                        style="color:#FF0000">*</span>পদবী</label>
                                            <?php
                                            //  echo $this->
                                            // tag->textField(array("jimmader_custodian_name",'class'
                                            // => "input form-control"
                                            // ,'onchange' => "ordersheetForm.showseizureorder(this.value)"))
                                             ?>
                                             <input type="text" id="jimmader_custodian_name" name="jimmader_custodian_name" class="input form-control" onchange="ordersheetForm.showseizureorder(this.value)">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label"><span
                                                        style="color:#FF0000">*</span>ঠিকানা</label>
                                            <?php 
                                            // echo $this->
                                            // tag->textArea(array("jimmader_details","cols" => 50,
                                            // "rows" => 2,
                                            // 'class' => "input form-control" ,'onchange' =>
                                            // "ordersheetForm.showseizureorder(this.value)"))
                                             ?>
                                             <textarea id="jimmader_details" name="jimmader_details" class="input form-control" cols="50" rows="2" onchange="ordersheetForm.showseizureorder(this.value)" spellcheck="false"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <!-- <div class="form-group">
                        <label class="control-label">আদেশনামা ফরমের স্ক্যানকৃত ছবি সংযুক্ত করুন (যদি থাকে)</label>
                        <div class="form-group">
                            <div class="panel panel-danger-alt">
                                <div class="panel-body cpv p-15 photoContainer">
                                    <button type="button" class="btn btn-success multifileupload">
                                        <span><i class="glyphicon glyphicon-plus"></i></span>
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
                        <div class="form-group" id="orderSheetAttachemntLable"></div>
                        <div class="panel panel-danger-alt">
                            <div class="form-group">
                                <div class="panel panel-danger-alt">
                                    <div class="row" id="orderSheetAttachedFile">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                <div class="form-group" id="orderSheetAttachemntLable"></div>
                <div class="form-group" id="orderSheetAttachedFile"></div>
                <div class="panel panel-danger-alt">
                    <div class="form-group">
                        <div class="panel panel-danger-alt">
                            <div class="row" id=""                                                  ">
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
                                    <th style="width: 45%;">আদেশনামা ফরমের স্ক্যানকৃত ছবি সংযুক্ত করুন (যদি থাকে)</th>
                                    <th style="width: 15%; text-align: center;">
                                        <button type="button" class="btn btn-sm btn-primary addRowBtn2">+</button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="attachmentTableBody2">
                                <!-- Dynamic rows will be appended here -->
                            </tbody>
                        </table>
                    </div>
                </div>

                </div>
            </div>
        </div>
        <div class="panel-footer">
            <div class="row">
                <div class="col-md-12">
                    <div style="float: right">
                        <input value="প্রিভিউ  " type="button" id="previewsubmit" onclick="ordersheetForm.previewOrderSheet()" class="btn btn-primary">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div id="modal_law"></div>
<div id="previousCrimeModal"></div>

<div id="regularcase_modal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: #006d34;color: #fff;font-weight: 600;color: #000;">
                <h4 class="modal-title text-white" >নিয়মিত মামলা</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>
            <form class="contact" id="saveRegularCaseForm" name="saveRegularCaseForm" method="post">
                <div class="modal-body">
                    <div id="modal_regularcase" class="modal_regularcase"></div>
                    <div class="modal-footer">
                        <button class="btn btn-warning btn-mideum " data-dismiss="modal" aria-hidden="true">বাতিল
                        </button>
                        <button class="btn btn-modal btn-primary" type="button"  onclick="ordersheetForm.saveRegularCaseForm();">সংরক্ষণ করুন</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="release_case_modal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <h4 class="modal-title">অব্যহতি প্রদান</h4>
            </div>
            <form class="contact" id="saveReleaseCaseForm" name="saveReleaseCaseForm" method="post">
                <div class="modal-body">
                    <div id="modal_releasecase" class="modal_releasecase"></div>
                    <div class="modal-footer">
                        <button class="btn btn-warning btn-mideum " data-dismiss="modal" aria-hidden="true">বাতিল
                        </button>
                        <button class="btn btn-modal" type="button"  onclick="ordersheetForm.saveReleaseForm();">সংরক্ষণ করুন</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="punishment_suo" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: #006d34;color: #fff;font-weight: 600;color: #000;">
                <h4 class="modal-title  text-white" >শাস্তি প্রদান</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                {{--<a class="close" data-dismiss="modal">×</a> --}}
            </div>
            <form class="contact" id="saveOrderBylawform_suo" name="saveOrderBylawform" method="post">
                <div class="modal-body">
                    <div id="ordertemp_suo" class="ordertemp_suo">

                    </div>

                    <div class="row" id="jail_details" style="display: none">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label style="font-size: 20px; font-weight: 500"
                                       class="control-label textmid">কারাগার</label>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <select id="jail_id" class="form-control selectDropdown" style="width: 100%">
                                    <option value='' > কারাগার  নির্বাচন  করুন</option>
                                    @foreach($jail as $value)
                                     <option value="{{$value->id}}">{{$value->title}}</option>

                                    @endforeach
                                </select>
                                
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label textmid">কারাদণ্ড কার্যকরের ধরন </label>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input class="exe_jail_type" type="radio" name="exe_jail_type" value="1"/>&nbsp; উক্ত কারাদণ্ডসমূহ একটির পর
                                একটি কার্যকর
                                <input class="exe_jail_type" type="radio" name="exe_jail_type" value="2" checked/>&nbsp; উক্ত কারাদণ্ডসমূহ
                                যুগপৎভাবে কার্যকর
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-warning btn-mideum " data-dismiss="modal" aria-hidden="true">বাতিল
                        </button>
                        <button class="btn btn-modal btn-mideum btn-primary" id="submit_suo" type="button" onclick="ordersheetForm.savePunishmentForm();">সংরক্ষণ করুন</button>
                    </div>
                </div><!-- modal-body -->
            </form>
        </div><!-- modal-content -->
    </div><!-- modal-dialog modal-lg -->
</div>


<style>

    .table-bordered {
        border: 1px solid #ddd;
    }

    .table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 20px;
    }
    caption {
    padding-top: .75rem;
    padding-bottom: .75rem;
    color: #3f4254;
    text-align: left;
    caption-side: top;
}
</style>