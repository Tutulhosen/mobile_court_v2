
<?php echo $this->tag->form(array("magistrate/save", "method" => "post" ,"id" => "")) ?>




<ul class="pager">
    <li class="আগে pull-left">
        {{ link_to("home/index", "&larr; প্রথম পাতা") }}
    </li>

</ul>

<?php echo $this->getContent(); ?>

<div align="center">
    <h1>এক্সিকিউটিভ ম্যাজিস্ট্রেটের ের   প্রোফাইল  </h1>
</div>

<table width="100%" class="outter">

    <tr>
        <td>
            <table class="text" border="0" cellpadding="4" cellspacing="3" width="100%">
                <tr >
                    <td colspan="3" class="formHeading">বিস্তারিত</td>
                </tr>
                <tr height="10px">
                    <td colspan="3"></td>
                </tr>

                <tr>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>নাম </td>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>পদবী</td>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>সার্ভিস আইডি </td>
                </tr>

                <tr>
                    <td align="left">
                        <?php echo $this->tag->textField(array("name_eng",'class' => "input" ,"readonly")) ?>
                    </td>
                    <td align="left">
                        <?php echo $this->tag->textField(array("designation_eng",'class' => "input" ,"readonly" )) ?>
                    </td>
                    <td align="left">
                        <?php echo $this->tag->textField(array("service_id",'class' => "input" ,"readonly" )) ?>
                    </td>
                </tr>

                <tr>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>জাতীয় পরিচয় পত্র নন্বর</td>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>স্থায়ী ঠিকানা</td>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>বর্তমান ঠিকানা</td>
                </tr>

                <tr>
                    <td align="left">
                        <?php echo $this->tag->textField(array("national_id",'class' => "input" ,"readonly")) ?>
                    </td>
                    <td align="left">
                        <?php echo $this->tag->textField(array("present_address",'class' => "input" ,"readonly" )) ?>
                    </td>
                    <td align="left">
                        <?php echo $this->tag->textField(array("permanent_address",'class' => "input" ,"readonly" )) ?>
                    </td>
                </tr>

                <tr>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>মোবাইল নম্বর</td>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>জন্ম তারিখ</td>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>ইমেইল</td>
                </tr>

                <tr>
                    <td align="left">
                        <?php echo $this->tag->textField(array("mobile",'class' => "input" ,"readonly")) ?>
                    </td>
                    <td align="left">
                        <?php echo $this->tag->textField(array("date_of_birth",'class' => "input" ,"readonly" )) ?>
                    </td>
                    <td align="left">
                        <?php echo $this->tag->textField(array("email",'class' => "input" ,"readonly" )) ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%">
                <tr>
                    <td align="right"><?php echo $this->tag->linkTo(array("home/index", "প্রথম পাতা")) ?></td>
                    <td align="middle"><?php echo $this->tag->submitButton("সংরক্ষণ") ?></td>
                <tr>
            </table>
        </td>
    </tr>

</table>
<?php echo $this->tag->hiddenField("id") ?>
</form>