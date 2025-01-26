
<style>
    .form-control[readonly] {
    background-color: #ddd;
}
</style>
<form action="/prosecution/Byprosecutor" id="courtselectform" name="courtselectform" method="post" enctype='application/json' enctype="multipart/form-data" novalidate>
    <div class="row p-5">
    <!-- onchange="magistrateForm.showMagistrate(this.value,'upazila')"  -->
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group ">
                <label class="control-label "><span class="text-error"></span>জেলা</label>
                <select  class="form-control  required" id="zilla" name="zilla"  required="true">
                    {{-- <option value="">জেলা নির্বাচন করুন</option> --}}
                    <option value="{{$district_id}}"><?php echo convert_dis_id_to_name($district_id); ?></option>
                     
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                <label class="control-label"><span class="text-error">*</span>আদালত বাছাই করুন</label>
                <select class="form-control selectDropdown required " name="magistrate" id="magistrate" required="true" onchange="magistrateForm.showScheduleByMagistrateId(this.value)">
                    <option value=""> আদালত নির্বাচন করুন</option>
                    <?php foreach($permited_megistrate as $maglist){?>
                    <option value="{{ $maglist->id }}"><?php echo $maglist->name; ?></option>
                     <?php } ?>
                </select>
            </div>
        </div><!-- form-group -->
       
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="form-group">
                <input type="text" id="schedulemsg" name="schedulemsg" class="input form-control" readonly="readonly">                
            </div>
        </div>
    </div>

    <div class="text-right float-right">
        <button class="btn btn-success   submitbutton next" type="button" onclick="magistrateForm.nextTab();" id="nextpage">
            <i class="glyphicon glyphicon-ok"></i> পরবর্তী ধাপ
        </button>
    </div>

</form>
<script>
    $(".selectDropdown").select2();
</script>