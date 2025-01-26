<form action="/prosecution/createProsecutionWitness" id="suomotcourtwitnessform" name="suomotcourtwitnessform"
    method="post" enctype="multipart/form-data">

    <div class="">
        <div class="panel-heading" style="margin: 20px 0px 20px 0">
            <h4 class="panel-title mt-2 text-left">সাক্ষীর তথ্য</h4>
            <input type="hidden" class="selectMagistrateCourtId" value="{{ $selectMagistrateCourtId }}" />

        </div>
        <div class="panel-body cpv" style="margin-top: 0%">
            {{-- <div class="row">
                <div class="col-sm-12">
                    <div class="well mt-10 text-center" style="background-color:#085F00;color:#fff;padding: 10px;">
                        ১ নম্বর সাক্ষীর বিবরণ
                    </div>
                </div>
            </div> --}}
            <div class="form-group">
                <h4 class="well well-sm text-center" style="background-color:green;color:#fff;padding: 10px;">
                    ১ নম্বর সাক্ষীর বিবরণ
                </h4>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label"><span style="color:#FF0000">*</span> নাম :</label>
                        <input type="text" id="witness1_name" name="witness1_name"
                            class="input
                        form-control required" required="true">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label"><span style="color:#FF0000">*</span>
                            পিতা/স্বামীর নাম :</label>
                        <input type="text" id="witness1_custodian_name" name="witness1_custodian_name"
                            class="input form-control required" required="true">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label"><span style="color:#ffffff"></span> মোবাইল নম্বর :</label>
                        <input type="text" id="witness1_mobile_no" name="witness1_mobile_no"
                            class="input form-control">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label"><span style="color:#FF0000"></span> মাতার
                            নাম:</label>
                        <input type="text" id="witness1_mother_name" name="witness1_mother_name"
                            class="input form-control">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label"><span style="color:#FF0000">*</span> বয়স
                            :</label>
                        <input type="text" id="witness1_age" name="witness1_age"
                            class="input
                        form-control required" required="true">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label"><span style="color:#ffffff"></span> জাতীয় পরিচয় পত্র নম্বর:</label>
                        <input type="text" id="witness1_nationalid" name="witness1_nationalid"
                            class="input form-control">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="control-label"><span style="color:#FF0000">*</span>
                            ঠিকানা:</label>
                        <textarea id="witness1_address" name="witness1_address" class="input form-control required" cols="50"
                            rows="2" required="true"></textarea>
                    </div>
                </div>
            </div>

            <!-- <div class="row">
                <div class="col-sm-12">
                    <div class="well mt-10 text-center" style="background-color:#085F00;color:#fff;padding: 10px;">
                        ২ নম্বর সাক্ষীর আঙ্গুলের ছাপ
                    </div>
                </div>
            </div> -->

            <!-- <div class="row"> -->

            <!-- <div class="col-sm-3"> -->

            <!-- <img class="easyui-tooltip tooltip-f" id="iLEFT_THUMB_W_2" alt="LEFT_THUMB" src="{{ asset('images/left_hand_6.png') }}" onclick="fingurePrint.captureFP_witness(1,2);"> -->
            <input type="hidden" value="" id="witness2_LEFT_THUMB" name="witness2_LEFT_THUMB">
            <input type="hidden" value="" id="witness2_LEFT_THUMB_BMP" name="witness2_LEFT_THUMB_BMP">
            <!-- </div>
                    <div class="col-sm-3"> -->

            <!-- <img class="easyui-tooltip tooltip-f" id="iRIGHT_THUMB_W_2" alt="RIGHT_THUMB" src="{{ asset('images/right_hand_1.png') }}" onclick="fingurePrint.captureFP_witness(3,2);"> -->
            <input type="hidden" value="" id="witness2_RIGHT_THUMB" name="witness2_RIGHT_THUMB">
            <input type="hidden" value="" id="witness2_RIGHT_THUMB_BMP" name="witness2_RIGHT_THUMB_BMP">
            <!-- </div> -->


            <!-- <div class="col-sm-6">
                    <div class="col-sm-12" style="margin-top: 100px">
                        <input type="hidden" name="LEFT_THUMB1_W2" id="witnessLEFT_THUMB1_W2" value="">
                        <input type="hidden" name="RIGHT_THUMB1_W2" id="witnessRIGHT_THUMB1_W2" value="">
                        <input type="hidden" name="witness2_repeat_crime" id="witness2_repeat_crime">
                        <span> ইতোমধ্যে সাক্ষীর তথ্য আছে কিনা জানতে  </span>
                        <input class="btn btn-primary" type="button" onclick="fingurePrint.witness2_formFormSubmit();" value="ক্লিক করুন">
                    </div>
                </div> -->
            <!-- </div> -->
            {{-- <div class="row">
                <div class="col-sm-12">
                    <div class="well mt-10 text-center" style="background-color:#085F00;color:#fff;padding: 10px;">
                        ২ নম্বর সাক্ষীর বিবরণ
                    </div>
                </div>
            </div> --}}
            <div class="form-group">
                <h4 class="well well-sm text-center" style="background-color:green;color:#fff;padding: 10px;">
                    ২ নম্বর সাক্ষীর বিবরণ
                </h4>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label"><span style="color:#FF0000">*</span> নাম :</label>
                        <input type="text" id="witness2_name" name="witness2_name"
                            class="input form-control required" required="true">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label"><span style="color:#FF0000">*</span> পিতা/স্বামীর নাম :</label>
                        <input type="text" id="witness2_custodian_name" name="witness2_custodian_name"
                            class="input form-control required" required="true">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label"><span style="color:#ffffff"></span> মোবাইল নম্বর :</label>
                        <input type="text" id="witness2_mobile_no" name="witness2_mobile_no"
                            class="input form-control">
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label"><span style="color:#FF0000"></span> মাতার
                            নাম:</label>
                        <input type="text" id="witness2_mother_name" name="witness2_mother_name"
                            class="input form-control">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label"><span style="color:#FF0000">*</span> বয়স :</label>
                        <input type="text" id="witness2_age" name="witness2_age"
                            class="input
                        form-control required" required="true">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label"><span style="color:#ffffff"></span> জাতীয় পরিচয় পত্র নম্বর:</label>
                        <input type="text" id="witness2_nationalid" name="witness2_nationalid"
                            class="input form-control">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="control-label"><span style="color:#FF0000">*</span>
                            ঠিকানা:</label>
                        <textarea id="witness2_address" name="witness2_address" class="input form-control required" cols="50"
                            rows="2" required="true"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row" >
            <div class="col-sm-12">
                <div class="panel-footer">
                    <div class="float-right">
                        <button class="btn btn-success" type="button" onclick="witnessInfoForm.save();"><i
                                class="glyphicon glyphicon-ok"></i> সংরক্ষণ
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

</form>
