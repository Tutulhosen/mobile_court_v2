<?php echo $this->getContent(); ?>

<div class="panel panel-default">
	<div class="panel-body cpv cpv96">
		<div class="row">
			<div class="col-sm-9">
				<div class="panel panel-warning-alt">
					<div class="panel-heading">
						<h4 class="panel-title">দায়েরকৃত অভিযোগ</h4>
					</div>
					<div class="panel-body panel-dashboard cpv">
						<div id="dynamic1">
							<table class="display  table-bordered table-striped" id="example" width="100%">
							</table>
						</div>
					</div>
                    {#<div class="panel-footer panel-footer-thin">
                    </div>#}
				</div>
				<div class="divSpace"></div>
				<div class="panel panel-success-alt" >
					<div class="panel-heading">
						<h4 class="panel-title">বিভিন্ন দপ্তর হতে প্রাপ্ত আবেদন</h4 >
					</div>
					<div class="panel-body cpv">
						<div id="dynamic2">
							<table class="table table-bordered table-striped " id="requisition" width="100%">
								<thead>
								<tr>
									<th ></th>
									<th  >নম্বর  </th>
									<th  >তারিখ</th>
									<th  >ঘটনাস্থল</th>
									<th  >অভিযোগ </th>
								</tr>
								</thead>
								<tbody>
								<tr>
									<td colspan="5" class="dataTables_empty">Loading data from server</td>
								</tr>
								</tbody>
							</table>
						</div>
					</div>
                    {#<div class="panel-footer panel-footer-thin">
                    </div>#}
				</div>
                <div class="divSpace"></div>
				<div class="panel panel-danger-alt">
					<div class="panel-heading">
						<h4 class="panel-title">অপরাধের তথ্য </h4>
						<div style="float: right">
							<img style="cursor:pointer; margin-top: -60px;" alt=" প্রিন্ট" title=" প্রিন্ট" src="{{ url.getBaseUri() }}images/print.png" onclick="printcitizencomplain();">
						</div>
					</div>
					<div class="panel-body cpv">
						<div id="dynamic3">
							<table width="100%" cellpadding="0" cellspacing="0" border="0" class="display table-bordered table-striped" id="citizen">
							</table>
						</div>
					</div>
                    {#<div class="panel-footer panel-footer-thin">
                    </div>#}
				</div>

				<div id="previewcitizencomplain" style="display: none; ">
					{{ partial("magistrate/partials/citizencomplain") }}
				</div>
				<div class="divSpace"></div>
			</div>
			<div class="col-sm-3">
				<div class="panel panel-info-alt">
					<div class="panel-heading">
						<h4 class="panel-title">পরিসংখ্যান</h4 >
					</div>

					<div class="panel-body cpv">
						<div class="list-group m-0">
							{#<a href="#" class="list-group-item">#}
								{#<span class="badge">30</span>#}
								{#টার্গেট সংখ্যা#}
							{#</a>#}
							<div class="list-group-item">
								<span class="badge"><?php echo $this->view->executed_court ?></span>
								মোট পরিচালিত কোর্ট
							</div>
							<div class="list-group-item">
								<span class="badge"><?php echo $this->view->total_case_number ?></span>
								মোট মামলার সংখ্যা
							</div>
							<div  class="list-group-item">
                                <span class="badge"><?php echo $this->view->fine_mgt ?></span>
                                আদায়কৃত অর্থ
							</div>
							<div class="list-group-item">
                                <span class="badge"><?php echo $this->view->criminal_no_mgt ?></span>
								মোট আসামির সংখ্যা
							</div >
						</div>
					</div>
                    {#<div class="panel-footer panel-footer-thin">
                    </div>#}
				</div>
                <div class="divSpace"></div>
				<div class="panel panel-warning-alt">
					<div class="panel-heading">
						<h4  class="panel-title">  অপরাধের তথ্য</h4 >
					</div>
					<div class="panel-body cpv">
						<div class="list-group m-0">
							<div  class="list-group-item">
								<span class="badge"><?php echo $this->view->citz_total ?> </span>
								মোট অভিযোগ
							</div>
							{#<div  class="list-group-item">#}
								{#<span class="badge"><?php echo $this->view->citz_case_processing ?></span>#}
								{#অনিষ্পন্ন অভিযোগ সংখ্যা#}
							{#</div>#}
							<div  class="list-group-item">
								<span class="badge"><?php echo $this->view->citz_case_complete ?></span>
                                নিষ্পত্তিকৃত অভিযোগ  সংখ্যা
							</div>
							<div  class="list-group-item">
								<span class="badge"><?php echo $this->view->citz_case_pending ?></span>
								অপেক্ষমান অভিযোগ  সংখ্যা
							</div>
						</div>
					</div>
                    {#<div class="panel-footer panel-footer-thin">
                    </div>#}
				</div>
				{#<div class="panel panel-danger-alt">#}
					{#<div class="panel-heading">#}
						{#<h4 class="panel-title">রেজিস্টার</h4>#}
					{#</div>#}
					{#<div class="panel-body">#}
						{#<div class="list-group">#}
							{#<a  href="#" class="list-group-item" >  অপরাধের তথ্য   রেজিস্টার#}
								{#<img src="{{ url.getBaseUri() }}images/print.png" onclick="printregister();">#}
							{#</a>#}
						{#</div>#}
					{#</div>#}
				{#</div>#}
			</div>
		</div>
        {#<div class="panel-footer panel-footer-thin">
        </div>#}
	</div>

</div>
{#{ stylesheet_link('css/bootstrap-abadmin.css') }#}
{{ stylesheet_link('css/style.datatables.css') }}
<link href="//cdn.datatables.net/responsive/1.0.1/css/dataTables.responsive.css" rel="stylesheet">

{{ javascript_include("js/jquery.dataTables.min.js") }}
<script src="//cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script src="//cdn.datatables.net/responsive/1.0.1/js/dataTables.responsive.js"></script>


{{ javascript_include("js/magistrate/script.js") }}

<div id="printRegister" style="display: none; ">
    {{ partial("magistrate/partials/register") }}
</div>
