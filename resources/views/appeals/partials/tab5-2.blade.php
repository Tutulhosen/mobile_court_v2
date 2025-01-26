<style>
    div#accordion {
        padding: 10px 10px;
        font-size: 15px;
        font-weight: bolder;
        line-height: 34px;
    }

    tr.trLawsBroken {
        background-color: #085F00 !important;
        color: #fff;
    }

    /* .thumbnail {
    position: relative;
    margin: 5px;
    float: left;
    border-radius: 3px;
    box-shadow: 1px 1px 5px #ccc;
    overflow: hidden;
    border: 1px solid #ccc;
}
.thumbnail > .img-label {
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
} */
</style>

<form action="/prosecution/saveCriminalConfessionSuomotu" id="confessionform" name="confessionform" method="post"
    enctype="multipart/form-data" novalidate="novalidate">
    <div class="panel panel-default">
        <div class="panel-heading"  style="margin: 20px 0px 20px 0">
            <!-- <h2 class="panel-title">আসামির জবানবন্দি<span class="pull-right">মামলা নম্বর: <span class="case_no text-primary">অভিযোগ গঠন হয়নি</span></span></h2> -->
            <h4 class="well well-sm mt-2   mb-2">আসামির
                জবানবন্দি<span class="pull-right float-right">মামলা নম্বর: <span class="case_no text-primary">অভিযোগ গঠন
                        হয়নি</span></span></h4>
        </div>
        {{-- <div class="panel-heading" style="margin: 20px 0px 20px 0">
            <h4 class="well well-sm mt-2 ">জব্দতালিকা
                <span class="pull-right float-right">মামলা নম্বর:
                    <span class="case_no text-primary">অভিযোগ গঠন হয়নি</span>
                </span>
            </h4>

        </div> --}}
        <div class="panel-body cpv">
            <div id="confessiondiv">
                <!-- Append body dinamicallly-->
            </div>
            <div class="row">
                <div class="col-md-12">
                    <!-- <div class="form-group">
                        <label class="control-label">জবানবন্দি ফরমের স্ক্যানকৃত ছবি সংযুক্ত করুন
                            (যদি থাকে)</label>
                        <div class="form-group">
                            <div class="panel panel-danger-alt">
                                <div class="panel-body cpv p-15 photoContainer">
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
                    </div> -->
                    <div class="form-group" id="criminalConfessionAttachedFile"></div>
                    <div class="panel panel-danger-alt">
                        <div class="form-group">
                            <div class="panel panel-danger-alt">
                                <div class="row"
                                    id="                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               ">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="attachment-section" style="background: #eff5ee">
                        <h5 class="mb-3">সংযুক্তি</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 45%;">জবানবন্দি ফরমের স্ক্যানকৃত ছবি
                                            সংযুক্ত করুন
                                            (যদি থাকে)</th>
                                        <th style="width: 15%; text-align: center;">
                                            <button type="button" class="btn btn-sm btn-primary addRowBtn1">+</button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="attachmentTableBody1">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- <div class="form-group" id="criminalConfessionAttachemntLable"></div>
                    <div class="panel panel-danger-alt">
                        <div class="form-group">
                            <div class="panel panel-danger-alt">
                                <div class="row" id="criminalConfessionAttachedFile">
                                </div>
                            </div>
                        </div>
                    </div> -->

                </div>
            </div>
        </div>
        <div class="panel-footer" style="padding-top: 20px">
            <div class="pull-right">
                <button class="btn btn-success mr5" type="button" onclick="confessionFrom.save();"><i
                        class="glyphicon glyphicon-ok"></i> সংরক্ষণ
                </button>
            </div>
        </div>
    </div>
</form>
