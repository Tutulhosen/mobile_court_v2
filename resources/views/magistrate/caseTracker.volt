<?php echo $this->tag->form(array("magistrate/searchcasedetailsformagistrate", "method" => "post" ,"id" => "casetrackerform" , "name" => "casetrackerform")) ?>
<?php echo $this->getContent(); ?>
		<div class="panel panel-default">
			<div class="panel-heading smx">
				<h2 class="panel-title">মামলা / অভিযোগ অনুসন্ধান</h2>
			</div>
			<div class="panel-body cpv">
				<div class="row">
					<div class="col-sm-5">
						<div class="form-group">
							<input class="radio-input " type="radio" name="case_complain" value="is_compID" onclick="ifCaseNumber(this.value)"/>   অপরাধের তথ্য আইডি
							<input type="text" id="intext" name="complain_no" class="form-control" />
						</div>
					</div>
					<div class="col-sm-5">
						<div class="form-group">
							<input class="radio-input" type="radio" name="case_complain" value="is_case" onclick="ifCaseNumber(this.value)" /> মামলার নম্বর
							<input type="text" id="intext2" name="case_no" class="form-control" />
						</div>
					</div>
					<div class="col-sm-2">
						<div class="form-group">
							<br />
							<div class="pull-right">
								<button class="btn btn-primary" type="button" name="casenothi" onclick="redrawTable();"><i class="glyphicon glyphicon-ok"></i>অনুসন্ধান </button>
							</div>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-sm-12">
						<div class="form-group">
							<label class="control-label">অভিযোগ  /  মামলার তথ্য</label>
							<div id="dynamic">
								<table class="display table-bordered table-striped" id="magistrate_table" width="100%">
									<thead>
									<tr>
										<th>ম্যাজিস্ট্রেটের নাম  </th>
										<th>মামলা  নম্বর</th>
										<th>  অপরাধের তথ্য নম্বর</th>
										<th>মামলার তারিখ </th>
										<th>আইন ও ধারা</th>
										<th>শাস্তি</th>
										<th>সর্বশেষ অবস্থা</th>
									</tr>
									</thead>
									<tbody>
									<tr>
										<td colspan="7" class="dataTables_empty">Loading data from server</td>
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
            document.forms[0].elements["intext2"].disabled=true;
            document.forms[0].elements["intext"].disabled=true;
            $('input[type=text]').each(function(){ $(this).val(''); });
    });
</script>

