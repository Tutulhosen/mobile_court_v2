<form action="/prosecution/savelist" id="seizureform" name="seizureform" method="post" enctype="multipart/form-data"
    novalidate="novalidate">
    <div class="panel panel-default">
        <div class="panel-heading" style="margin: 20px 0px 20px 0">
            <h4 class="well well-sm mt-2 ">জব্দতালিকা
                <span class="pull-right float-right">মামলা নম্বর:
                    <span class="case_no text-primary">অভিযোগ গঠন হয়নি</span>
                </span>
            </h4>

        </div>
       {{--  <div class="panel-heading " style="background-color:#f5f5f5; margin: 20px 0px 20px 0">
            <h4 class="well well-sm mt-2 ">জব্দতালিকা
                <span class="pull-right float-right">মামলা নম্বর:
                    <span class="case_no text-primary">অভিযোগ গঠন হয়নি</span>
                </span>
            </h4>
        </div> --}}
        <div class="panel-body cpv">
            {{-- <div class="row">
                <div class="col-sm-12">
                    <div class="well well-sm mt-2 text-center mb-2"
                        style="background-color:#085F00;color:#fff;padding: 10px;">
                        জব্দকৃত মালামালের বর্ণনা
                    </div>
                </div>
            </div> --}}
            <div class="form-group">
                <h4 class="well well-sm text-center" style="background-color:green;color:#fff;padding: 10px;">
                    জব্দকৃত মালামালের বর্ণনা
                </h4>
            </div>

            <div id="seizureListTable">
                <div class="row">
                    <div class="col-sm-1">
                        <div class="form-group">
                            <label class="control-label">ক্রমিক </label>
                            <br>
                            <span>১</span>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="control-label">মালামালের বিবরণ </label>
                            <input class="required form-control" name="seizure[0][1]" id="seizure_0_1" value=""
                                type="text" required="true">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="control-label">পরিমাণ/ওজন</label>
                            <input class="form-control required" name="seizure[0][2]" id="seizure_0_2" value=""
                                type="text" required="true">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="control-label">আনুমানিক মূল্য</label>
                            <input class="form-control required" name="seizure[0][3]" id="seizure_0_3" value=""
                                type="text">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label">মালামালের ধরন </label>
                            <select id="seizure_0_4" name="seizure[0][4]" class="form-control selectDropdown required"
                                usedummy="1">
                                <option value="">বাছাই করুন...</option>
                                @if (!empty($seizureitem_type))
                                    @foreach ($seizureitem_type as $seizureitemtype)
                                        <option value="{{ $seizureitemtype->id }}">{{ $seizureitemtype->item_group }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="control-label">মন্তব্য </label>
                            <input class="form-control" name="seizure[0][5]" id="seizure_0_5" value=""
                                type="text">
                        </div>
                    </div>
                    <!-- <div class="col-sm-1">
                        &nbsp;
                    </div> -->
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="button" value="+" onclick="seizureForm.addSeizureListRow('seizureListTable')"
                            class="btn btn-primary">
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer" style="padding-bottom: 20px">
            <div class="pull-right float-right">
                <button class="btn btn-success" type="button" onclick="seizureForm.save();">
                    <i class="glyphicon glyphicon-ok"></i> সংরক্ষণ
                </button>
            </div>
        </div>
        <input class="hidden" id="prosecution_id" name="prosecution_id" value="" type="hidden">
    </div>
</form>
