
<form action="/prosecution/createProsecutionCriminalBymagistrate" id="suomotcourtcriminalform" name="suomotcourtcriminalform" method="post" enctype='application/json' enctype="multipart/form-data" novalidate>
  
<div id="addCriminalBlock">
  <div id="criminalInfo_0" class="criminalInfo">


     
    <div class="form-group">
        <h4 class="well well-sm mt-10 text-center" style="background-color:#085F00;color:#fff;padding: 10px;">অভিযুক্ত ব্যক্তির তথ্য</h4>
        <input type="hidden" class="selectMagistrateCourtId" value="" />
    </div>
    <div class="panel-body" style="display:block;" id="panel_body_0">
        <div class="row">
            <div class="col-sm-12">
                <div class="well mt-10 text-center mb-2" style="background-color:#085F00;color:#fff;padding: 10px;"><span class="numOfcFinger">১</span> নম্বর অভিযুক্তের আঙ্গুলের ছাপ   </div>
            </div>
        </div>
        <div class="row m-bottom-10">
        
            <div class="col-sm-3">
                <img class="easyui-tooltip tooltip-f" id="iLEFT_THUMB_0" alt="LEFT_THUMB" sstyle="background-color:#085F00;color:#fff;padding: 10px;" src="{{ asset('images/left_hand_6.png')}}">
                <input type="hidden" id="criminal_LEFT_THUMB_0" name="criminal[0][LEFT_THUMB]">
                <input type="hidden" id="criminal_LEFT_THUMB_BMP_0" name="criminal[0][LEFT_THUMB_BMP]">
            </div>
            <div class="col-sm-3">
                <img class="easyui-tooltip tooltip-f" id="iRIGHT_THUMB_0" alt="RIGHT_THUMB" src="{{ asset('images/right_hand_1.png')}}">
                <input style="display: none" value="" id="criminal_RIGHT_THUMB_0" name="criminal[0][RIGHT_THUMB]">
                <input style="display: none" value="" id="criminal_RIGHT_THUMB_BMP_0" name="criminal[0][RIGHT_THUMB_BMP]">
            </div>
        
            <div class="col-sm-6">
                <div class="col-sm-12" style="margin-top: 100px"><span> ইতোমধ্যে অভিযুক্ত ব্যক্তির তথ্য আছে কিনা জানতে  </span>
                    <input style="display: none" name="var1" id="LEFT_THUMB1_0">
                    <input style="display: none" name="var3" id="RIGHT_THUMB1_0">
                    <button type="button" id="checkfingureprint_0" class="sumotosignature  btn btn-success mr5">ক্লিক করুন</button>
                </div>
            </div>
        </div>

        <div class="form-group">
            <h4 class="well well-sm mt-10 text-center" style="background-color:#085F00;color:#fff;padding: 10px;">অভিযুক্তের বিবরণ</h4>
        </div>
        <input type="hidden" class="form-control" name="criminal[0][id]" id="criminal_id_0">
        <div class="form-group row">
            <label class="col-sm-2"><span style="color:#FF0000">*</span> অভিযুক্তের নাম</label>
            <div class="col-sm-2">
                <input type="text" class="form-control required" name="criminal[0][name]" id="criminal_name_0" required="true">
                <input type="hidden" name="criminal[0][id]" id="criminal_id_0">
                <input type="hidden" name="criminal[0][repeat_crime]" id="criminal_repeat_crime_0">
                <input type="hidden" name="criminal[0][m_user_id]" id="criminal_m_user_id_0">
                <input type="hidden" name="criminal[0][m_user_type]" id="criminal_m_user_type_0">
            </div>
            <label class="col-sm-2"><span style="color:#FF0000">*</span> অভিযুক্তের পিতা/স্বামীর নাম</label>
            <div class="col-sm-2">
            <input type="text" class="form-control required " name="criminal[0][custodian_name]" id="criminal_custodian_name_0" required="true">
            </div>
            <label class="col-sm-2"><span style="color:#FF0000">*</span> পিতা / স্বামী </label>
            <div class="col-sm-2">
                <select class="form-control selectDropdown " name="criminal[0][custodian_type]" id="custodian_type_0"  >
                    <option value="">বাছাই করুন...</option>
                    <option value="পিতা">পিতা</option>
                    <option value="স্বামী">স্বামী</option>
                </select>
            </div>
        
        </div>

        <div class="form-group row">
            <label class="col-sm-2">
                <span style="color:#FF0000"></span> মাতার নাম
            </label>
            <div class="col-sm-2">
                <input type="text" class="form-control " name="criminal[0][mother_name]" id="mother_name_0">
            </div>
            <label class="col-sm-2">জাতীয় পরিচয় পত্র নম্বর</label>
            <div class="col-sm-2">
                <input type="text" class="form-control" name="criminal[0][national_id]" id="national_id_0">
            </div>
            <label class="col-sm-2">ইমেইল</label>
            <div class="col-sm-2">
                <input type="text" class="form-control" name="criminal[0][email]" id="email_0">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2">
                <span style="color:#FF0000">*</span>বয়স
            </label>
            <div class="col-sm-2">
                <input type="text" class="form-control required" name="criminal[0][age]" id="age_0" required="true">
            </div>
            <label class="col-sm-2"><span style="color:#FF0000">*</span>নারী / পুরুষ</label>
            <div class="col-sm-2">
                <select class="form-control gender required selectDropdown" name="criminal[0][gender]" id="gender_0" required="true" tabindex="-1" title="">

                    <option value="">বাছাই করুন...</option>
                    <option value="নারী">নারী</option>
                    <option value="পুরুষ">পুরুষ</option>
                    <option value="অন্যান্য"> অন্যান্য</option>
                </select>
            </div>


            <label class="col-sm-2">
                <span style="color:#FF0000"></span> যোগাযোগ নম্বর
            </label>
            <div class="col-sm-2">
                <input type="text" class="form-control" name="criminal[0][mobile_no]" id="mobile_no_0">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2">
                <span style="color:#FF0000">*</span>পেশা
            </label>
            <div class="col-sm-2">
                <input type="text" class="form-control required" name="criminal[0][occupation]" id="occupation_0" required="true">
            </div>
            <label class="col-sm-2">প্রতিষ্ঠানের নাম (প্রতিষ্ঠান অভিযুক্ত হলে)</label>
            <div class="col-sm-2">
                <input type="text" class="form-control" name="criminal[0][organization_name]" id="organization_name_0">
            </div>
            <label class="col-sm-2">ট্রেড লাইসেন্স </label>
            <div class="col-sm-2">
                <input type="text" class="form-control" name="criminal[0][trade_no]" id="trade_no_0">
            </div>
        </div>
        <div class="form-group">
            <h4 class="well well-sm text-center" style="background-color:#085F00;color:#fff;padding: 10px;">স্থায়ী ঠিকানা</h4>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label"><span style="color:#FF0000">*</span>বিভাগ</label>
                    <select class="form-control selectDropdown required criminal_division" name="criminal[0][division]" id="ddlDivision0" >
                        <option value="">বাছাই করুন...</option>
                        @foreach($division as $dblist)
                        {{--- <option value="{{$dblist->id}}"><?php echo $dblist->division_name_bn ?></option> ---}}
                        <option value="{{$dblist->id}}"><?php echo $dblist->division_name_bng ?></option>
                        @endforeach
                        
                    </select>
                </div>
                <!-- form-group -->
            </div>
            <!-- col-sm-6 -->

            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label"><span style="color:#FF0000">*</span> জেলা</label>
                    <select class="form-control selectDropdown required " id="ddlZilla0" name="criminal[0][zilla]" >
                        <option value="">বাছাই করুন...</option>
                    </select>
                </div>
                <!-- form-group -->
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <div id="locationTypeTable_0" class="radiorequired">
                        <label class="btn btn-default active">
                            <input class="optLocationType0" type="radio" name="criminal[0][locationtype]" id="upazillatype_0" value="UPAZILLA" required="true" ctrlid="0">উপজেলা
                        </label>
                        <label class="btn btn-default active">
                            <input class="optLocationType0" type="radio" name="criminal[0][locationtype]" id="citytype_0" value="CITYCORPORATION" ctrlid="0"> সিটি কর্পোরেশন
                        </label>
                        <label class="btn btn-default active">
                            <input class="optLocationType0" type="radio" name="criminal[0][locationtype]" id="metrotype_0" value="METROPOLITAN" ctrlid="0" >মেট্রোপলিটন
                        </label>
                    </div>
                </div>
            </div>

            <!-- <div id="upoziladiv_0"> -->
                <div class="col-sm-3">
                    <div class="form-group">
                        <select class="form-control selectDropdown required " name="criminal[0][locationDetails]" id="ddlUpazilla0">
                            <option value="">বাছাই করুন...</option>
                            
                        </select>
                        
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <select name="criminal[0][GeoThanas]" id="ddlThana0" required="true" tabindex="-1" class="form-control"  >
                            <option value="">বাছাই করুন...</option>
                            
                        </select>
                        
                        
                    </div>
                </div>
            <!-- </div> -->
        </div>
        <div class="form-group row">
            <label class="col-sm-2">
                <span style="color:#FF0000">*</span>গ্রাম – মহল্লা - সড়ক
            </label>
            <div class="col-sm-10">
                <input type="text" class="form-control required" name="criminal[0][permanent_address]" id="permanent_address_0" required="true">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4">
                <span style="color:#FF0000">*</span>  স্থায়ী ও বর্তমান ঠিকানা একই হলে টিক (✓)দিন
            </label>
            <div class="col-sm-2" style="text-align: center">
                <input type="checkbox" id="do_address_0" name="criminal[0][do_address]" value="Y">
            </div>
            <div class="col-sm-6">
                <textarea class="form-control required" name="criminal[0][present_address]" rows="1" cols="50" id="present_address_0" required="true"></textarea>
            </div>
        </div>
        
     

        
    </div>
   </div>
</div>
<div class="form-group row">
    <div class="col-sm-12">
        <input type="button" value="পরবর্তী অভিযুক্ত " id="addMoreCriminal" onclick="criminal.addMoreCriminalInfo(true);" name="add-c1" class="btn btn-primary">
        <div class="float-right">
            <button class="btn btn-success mr5" type="button"  ><i class="glyphicon glyphicon-ok"></i> সংরক্ষণ
            </button>
        </div>
    </div>
</div>  

</form>