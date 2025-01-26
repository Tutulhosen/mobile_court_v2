<?php echo $this->tag->form(array("requisition/createRequisition", "method" => "post" ,"id" => "requsitionform")) ?>


<ul class="pager">
    <li class="আগে pull-left">
        {{ link_to("home/index", "&larr; প্রথম পাতা") }}
    </li>
    {#<li class="pull-right">#}
    {#{{ link_to("requisition/createRequisition", "Save", "class": "btn btn-primary") }}#}
    {#</li>#}
</ul>

<?php echo $this->getContent(); ?>


<table width="100%" class="outter">

    <tr>
        <td>
            <table class="text" border="0" cellpadding="4" cellspacing="3" width="100%">
                <tr >
                    <td colspan="2" class="formHeading">বিস্তারিত অভিযোগ</td>
                </tr>
                <tr height="10px">
                    <td colspan="2"></td>
                </tr>

                <tr>
                    <td colspan="2" align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>অভিযোগকারীর নাম</td>
                    {#<td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>নাম (ইংরেজি)</td>#}
                </tr>

                <tr>
                    <td colspan="2" align="left">
                        <?php echo $this->tag->textField(array("name_bng",'class' => "input" ,'readonly' => "readonly"))
                        ?>
                    </td>
                    {#<td align="left">#}
                        {#<?php echo $this->tag->textField(array("name_eng",'class' => "input" ,'readonly' => "readonly"#}
                        {#)) ?>#}
                    {#</td>#}
                </tr>

                <tr>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>অভিযোগের বর্ণনা
                    </td>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>অভিযোগের তারিখ
                    </td>
                </tr>

                <tr>

                    <td align="left">
                        <?php echo $this->tag->textArea(array("complain_details", "cols" => 50, "rows" => 4 ,'class' =>
                        "input" ,'readonly' => "readonly")) ?>
                    </td>
                    <td align="left">
                        <?php echo $this->tag->textField(array("complain_date", 'class' => "input", 'readonly' =>
                        "readonly")) ?>
                    </td>
                </tr>

                <tr>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>ঘটনাস্থল</td>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>বিভাগ</td>

                </tr>

                <tr>
                    <td align="left">
                        <?php echo $this->tag->textField(array("cmp_location",'class' => "input", 'readonly' =>
                        "readonly")) ?>
                    </td>
                    <td align="left">
                        <?php echo $this->tag->textField(array("divname",'class' => "input", 'readonly' => "readonly"))
                        ?>
                    </td>

                </tr>

                <tr>

                    <td  align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>জেলা </td>

                    <td  align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>উপজেলা</td>

                </tr>

                <tr>
                    <td align="left">
                        <?php echo $this->tag->textField(array("zillaname",'class' => "input", 'readonly' =>"readonly")) ?>
                    </td>
                    <td align="left">
                        <?php echo $this->tag->textField(array("upazilaname",'class' => "input", 'readonly' => "readonly"))
                        ?>
                    </td>

                </tr>


                <tr>
                    <td><?php echo $this->tag->hiddenField("id") ?></td>
                    <!--      <td><?php echo $this->tag->submitButton("Search") ?></td> -->
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="text" border="0" cellpadding="4" cellspacing="3" width="100%">
                <tr >
                    <td colspan="2" class="formHeading">অভিযোগ   দাখিল</td>
                </tr>
                <tr height="10px">
                    <td colspan="2"></td>
                </tr>

                <tr>
                    <td td colspan="2" align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000">*</span>এক্সিকিউটিভ ম্যাজিস্ট্রেটের
                        বাছাই করুন
                    </td>
                </tr>
                <tr>
                    <td align="left">

                        <?php echo $this->tag->select(array(
                        "magistrate_id",
                        $magistrate_id,
                        "using" => array("id", "name_eng"),
                        'useEmpty' => true,
                        'emptyText' => 'বাছাই করুন...',
                        'emptyValue' => '',
                        'class' => "input"
                        )) ?>

                    </td>

                </tr>

                {#<tr>#}
                    {#<td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>কোর্ট গঠনের তারিখ#}
                    {#</td>#}
                    {#<td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>কোর্ট গঠনের স্থান#}
                    {#</td>#}
                {#</tr>#}

                {#<tr>#}
                    {#<td align="left">#}
                        {#&#123;&#35;<?php echo $this->tag->textField(array("dateofcourt",'class' => "input" )) ?>&#35;&#125;#}
                        {#<input class="input" name="dateofcourt" id="date" value="" type="text"/>#}
                    {#</td>#}
                    {#<td align="left">#}
                        {#<?php echo $this->tag->textField(array("location",'class' => "input" )) ?>#}
                    {#</td>#}
                {#</tr>#}
            </table>
        </td>
    </tr>
    <tr>
        <td>

            <table class="text" border="0" cellpadding="4" cellspacing="3" width="100%">

                <tr>
                    <td>
                        <table width="100%">
                            <tr>
                                <td align="middle" style="font-size:medium;"><?php echo $this->tag->linkTo(array("home/index", "প্রথম পাতা")) ?>
                                </td>
                                <!--  <td align="right"><?php echo $this->tag->linkTo(array("complain/new", "আদালত গঠন")) ?></td>  -->
                                {#<td align="middle"><?php echo $this->tag->submitButton("সংরক্ষণ") ?></td>#}
                                <td align="middle"> <input value="সংরক্ষণ" type="submit"  class="btn btn-success"/></td>
                            <tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

</table>
</form>


<div id="requisition" class="modal hide fade in" style="display: none; ">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>

        <h3>This is a Modal Heading</h3>
    </div>
    <div class="modal-body">
        <h1>
            আপনার অভিযোগটি সফলভাবে গ্রহণ করা হয়েছে ।
        </h1>
        </br>

    </div>
    <div class="modal-footer">
        <a href="#" class="btn btn-success">Call to action</a>
        <a href="#" class="btn" data-dismiss="modal">Close</a>
    </div>
</div>