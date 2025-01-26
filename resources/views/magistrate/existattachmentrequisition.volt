<?php echo $this->getContent(); ?>
<link rel="stylesheet" type="text/css"
      href="/js/datatables/examples/resources/bootstrap/3/dataTables.bootstrap.css">
{#<script src="/js/magistrate/script.js" type="text/javascript"></script>#}

{#<?php echo $this->tag->form(array("magistrate/saverequisitionattachment", "method" => "post" ,"id" => "formattachmentrequisition" ,"enctype" => "multipart/form-data")) ?>#}
<div class="row">
    <div class="span6">
        <div class="panel panel-primary">
            <div class="panel-heading" style="background-color:#683091;!important">
                <h3 class="panel-title" style="background-color:#683091;!important">সংযুক্ত রিকুইজিশনের তালিকা</h3>
            </div>
            <div id="dynamic">
                <table cellpadding="0" cellspacing="0" border="0" class="display" id="existattachmentrequisition">
                </table>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="panel panel-primary">
            <div class="panel-heading" style="background-color:#683091;!important">
                <h3 class="panel-title" style="background-color:#683091;!important">মামলার </h3>
            </div>
            <div id="dynamic">
                <table cellpadding="0" cellspacing="0" border="0" class="display" id="caselist">
                </table>
            </div>
        </div>
    </div>
    <div class="span6">
    <table width="100%">
        <tr>
            <td align="left"><input value="সংযুক্তিকরণ" type="button" id="existsubmit" class="btn btn-success"/></td>
        <tr>
    </table>
    </div>
</div>

{#</form>#}