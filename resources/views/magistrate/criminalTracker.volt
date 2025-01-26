<?php echo $this->tag->form(array("magistrate/searchcasedetailsformagistrate", "method" => "post" ,"id" => "casetrackerform" , "name" => "casetrackerform")) ?>
<?php echo $this->getContent(); ?>
<div class="panel panel-default">
	<div class="panel-heading smx">
		<h2 class="panel-title">আসামি অনুসন্ধান</h2>
		<p>আসামির   নাম এবং বিভাগ, জেলা, উপজেলা অথবা মোবাইল নম্বর লিখে অনুসন্ধান করুন</p>
	</div>
	<div class="panel-body p-15 cpv">
		<div class="row">
			<div class="col-sm-3">
				<div class="form-group">
					<label class="control-label">নাম</label>
					<input  name="name_bng" id="name_bng" value="" class="form-control" type="text" />
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label class="control-label">বিভাগ</label>
					<?php echo $this->tag->select(array(
					"division",
					$division,
					'id' => "division",
					"using" => array("divid", "divname"),
					'useEmpty' => true,
					'emptyText' => 'বাছাই করুন...',
					'emptyValue' => '',
					'onchange' => "showZilla(this.value,'zilla')"
					)) ?>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label class="control-label">জেলা / সিটি কর্পোরেশন</label>
					<?php echo $this->tag->select(array(
					"zilla",
					$zilla,
					'id' => "zilla",
					"using" => array("zillaid", "zillaname"),
					'useEmpty' => true,
					'emptyText' => 'বাছাই করুন...',
					'emptyValue' => '',
					'onchange' => "showUpazila(this.value,'upazila')"
					)) ?>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label class="control-label">উপজেলা /ওয়ার্ড</label>
					<?php echo $this->tag->select(array(
					"upazila",
					$upazila,
					"using" => array("upazilaid", "upazilaname"),
					'id' => "upazila",
					'useEmpty' => true,
					'emptyText' => 'বাছাই করুন...',
					'emptyValue' => '',
					'class' => "input"
					)) ?>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-3">
				<div class="form-group">
					<label class="control-label">মোবাইল</label>
					<input  name="mobile" class="form-control" id="mobile" value="" type="text" />
				</div>
			</div>
			<div class="col-sm-9">
				<div class="form-group">
					<label class="control-label"> </label><br /><br />
					<div class="pull-right">
						<button class="btn btn-primary" type="button" name="casenothi" onclick="redrawCriminalTable();"><i class="fa fa-check"></i> অনুসন্ধান </button>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label class="control-label">আসামি  তথ্য</label>
					<div id="dynamic">
						<table width="100%" class="display table table-bordered table-striped" id="criminal_table">
							<thead>
							<tr>
								<th  >আসামি নাম </th>
								<th  >মামলা  নম্বর</th>
								<th  >মামলার তারিখ </th>
								<th  >আইন ও ধারা</th>
								<th  >অপরাধের বর্ণনা </th>
								<th  >শাস্তি  -  জরিমানা</th>
							</tr>
							</thead>
							<tbody>
							<tr>
								<td colspan="6" class="dataTables_empty">Loading data from server</td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

	</div><!-- panel-body -->
	<div class="panel-footer">

	</div><!-- panel-footer -->
</div><!-- panel -->
</form>

{{ stylesheet_link('css/select2.css') }}
{{ stylesheet_link('css/bootstrap-timepicker.min.css') }}

{{ javascript_include("js/select2.min.js") }}
{{ javascript_include("js/bootstrap-timepicker.min.js") }}
{{ javascript_include("js/jquery-ui-1.10.3.min.js") }}
{{ javascript_include("js/jquery.validate.min.js") }}
{{ stylesheet_link('css/style.datatables.css') }}
<link href="//cdn.datatables.net/responsive/1.0.1/css/dataTables.responsive.css" rel="stylesheet">

{{ javascript_include("js/jquery.dataTables.min.js") }}
<script src="//cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script src="//cdn.datatables.net/responsive/1.0.1/js/dataTables.responsive.js"></script>

{{ javascript_include("js/magistrate/script.js") }}


<script>
    $(document).ready(function(){
        jQuery("#division, #zilla, #upazila").select2();
//            document.forms[0].elements["intext2"].disabled=true;
//            document.forms[0].elements["intext"].disabled=true;
//            $('input[type=text]').each(function(){ $(this).val(''); });
    });
</script>

